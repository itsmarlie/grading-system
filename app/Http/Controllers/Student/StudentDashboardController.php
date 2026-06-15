<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $student = Student::with('course')
                          ->where('user_id', $user->id)
                          ->firstOrFail();

        // ── STAT CARDS ──────────────────────────────────────────────
        // Courses: just 1 (their enrolled course) or count distinct
        $totalCourses = $student->course_id ? 1 : 0;

        // Assignments due — based on their course_id directly
        $assignmentsDue = Assignment::where('course_id', $student->course_id)
            ->where('due_date', '>=', Carbon::today())
            ->count();

        // Overall average grade
        $gradeAvg   = Grade::where('student_id', $student->id)->avg('score');
        $overallAvg = $gradeAvg ? round($gradeAvg, 1) : null;

        // ── TODAY'S SCHEDULE ─────────────────────────────────────────
        // Based on sections that match the student's section name + course
        $today = Carbon::now()->format('l');
        $todaySchedules = collect();

        $section = Section::with(['course', 'schedules'])
            ->where('course_id', $student->course_id)
            ->where('name', $student->section)
            ->first();

        if ($section) {
            $todaySchedules = $section->schedules
                ->where('day', $today)
                ->map(fn($s) => [
                    'subject'    => $section->course->course_name ?? $section->name,
                    'time_start' => $s->start_time,
                    'time_end'   => $s->end_time,
                    'room'       => $s->room ?? '—',
                ])
                ->values();
        }

        // ── UPCOMING ASSIGNMENTS ─────────────────────────────────────
        $upcomingAssignments = Assignment::with('course')
            ->where('course_id', $student->course_id)
            ->where('due_date', '>=', Carbon::today())
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        // ── GRADE DISTRIBUTION ───────────────────────────────────────
        $gradeDistribution = Grade::where('student_id', $student->id)
            ->join('assignments', 'grades.assignment_id', '=', 'assignments.id')
            ->selectRaw('assignments.type as category, AVG(grades.score) as avg_score, COUNT(*) as count')
            ->groupBy('assignments.type')
            ->get()
            ->map(fn($g) => [
                'category'  => ucfirst($g->category ?? 'Other'),
                'avg_score' => round($g->avg_score, 1),
                'count'     => $g->count,
            ]);

        // ── RECENT ANNOUNCEMENTS ─────────────────────────────────────
        $announcements = Announcement::latest()->limit(4)->get();

        return view('student.dashboard', compact(
            'student',
            'totalCourses',
            'assignmentsDue',
            'overallAvg',
            'todaySchedules',
            'upcomingAssignments',
            'gradeDistribution',
            'announcements'
        ));
    }
}