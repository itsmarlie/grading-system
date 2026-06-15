<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── STUDENT: redirect to student dashboard ──────────────────
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        // ── ADMIN / TEACHER dashboard below ────────────────────────

        $studentsCount    = Student::count();
        $coursesCount     = Course::count();
        $assignmentsCount = Assignment::count();
        $averageGrade     = round(Grade::avg('score') ?? 0, 1);

        // Grade distribution buckets
        $gradeDistribution = [
            'A' => Grade::where('score', '>=', 90)->count(),
            'B' => Grade::whereBetween('score', [80, 89])->count(),
            'C' => Grade::whereBetween('score', [70, 79])->count(),
            'D' => Grade::whereBetween('score', [60, 69])->count(),
            'F' => Grade::where('score', '<', 60)->count(),
        ];
        $totalGrades = array_sum($gradeDistribution);

        // Upcoming assignments (due in the next 7 days)
        $upcomingAssignments = Assignment::with('course')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Today's schedule
        $today = now()->format('l');
        $todaySchedule = Course::with('teacher')
            ->where('schedule', 'like', "%{$today}%")
            ->get();

        // Recent announcements filtered by role
        $recentAnnouncements = Announcement::with('author')
            ->where(function ($q) use ($user) {
                $q->where('visibility', 'all')
                  ->orWhere('visibility', $user->role === 'student' ? 'students' : 'teachers');
            })
            ->latest()
            ->take(3)
            ->get();

        $hour = now()->format('H');
        $greeting = match(true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default    => 'Good evening',
        };

        return view('dashboard', compact(
            'user', 'greeting',
            'studentsCount', 'coursesCount', 'assignmentsCount', 'averageGrade',
            'gradeDistribution', 'totalGrades',
            'upcomingAssignments', 'todaySchedule', 'recentAnnouncements'
        ));
    }
}