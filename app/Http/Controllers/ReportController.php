<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Course;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // ── Grading weights (DepEd standard) ─────────────────────────────
    const WEIGHTS = [
        'written_work'         => 0.30,
        'performance_task'     => 0.50,
        'quarterly_assessment' => 0.20,
    ];

    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        // ── Courses scoped to teacher ────────────────────────────────
        $courses = $isAdmin
            ? Course::with('teacher')->get()
            : Course::where('teacher_id', $user->id)->get();

        $courseIds  = $courses->pluck('id');
        $studentIds = Student::whereIn('course_id', $courseIds)->pluck('id');

        $courseReports = $courses->map(fn($course) => $this->buildCourseReport($course));

        // ── Summary stats — scoped to teacher's students ─────────────
        $overallAverage = round(
            Grade::whereIn('student_id', $studentIds)->avg('score') ?? 0, 1
        );

        $totalStudents = $studentIds->count();
        $totalCourses  = $courses->count();

        // ── Attendance — scoped to teacher's courses ─────────────────
        $attendanceQuery = Attendance::whereIn('course_id', $courseIds);

        $attendanceSummary = [
            'present' => (clone $attendanceQuery)->where('status', 'present')->count(),
            'absent'  => (clone $attendanceQuery)->where('status', 'absent')->count(),
            'late'    => (clone $attendanceQuery)->where('status', 'late')->count(),
            'excused' => (clone $attendanceQuery)->where('status', 'excused')->count(),
        ];

        $attendanceTotal = array_sum($attendanceSummary);
        $attendanceRate  = $attendanceTotal > 0
            ? round(
                ($attendanceSummary['present'] + $attendanceSummary['late'])
                / $attendanceTotal * 100, 1
              )
            : 0;

        // ── Top students — scoped to teacher's students ──────────────
        $topStudents = Grade::selectRaw('student_id, AVG(score) as avg_score')
            ->whereIn('student_id', $studentIds)
            ->groupBy('student_id')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->with('student')
            ->get();

        return view('reports.index', compact(
            'courseReports',
            'overallAverage',
            'totalStudents',
            'totalCourses',
            'attendanceSummary',
            'attendanceRate',
            'topStudents'
        ));
    }

    // ── Export CSV ────────────────────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $user     = Auth::user();
        $type     = $request->input('type', 'grades');
        $courseId = $request->input('course_id');

        if ($type === 'grades')     return $this->exportGradesCsv($user, $courseId);
        if ($type === 'attendance') return $this->exportAttendanceCsv($user, $courseId);
        if ($type === 'students')   return $this->exportStudentsCsv($user);

        return back()->with('error', 'Invalid export type.');
    }

    private function exportGradesCsv($user, $courseId = null)
    {
        $query = Student::with(['course'])
            ->when(!$user->isAdmin(), fn($q) =>
                $q->whereHas('course', fn($c) => $c->where('teacher_id', $user->id))
            )
            ->when($courseId, fn($q) => $q->where('course_id', $courseId));

        $students = $query->get();

        $rows   = [];
        $rows[] = [
            'Student Number', 'Last Name', 'First Name', 'Course',
            'Written Work (30%)', 'Performance Task (50%)', 'Quarterly Assessment (20%)',
            'Final Grade', 'Remarks'
        ];

        foreach ($students as $student) {
            $grades     = Grade::where('student_id', $student->id)->with('assignment')->get();
            $computed   = $this->computeStudentGrade($grades);
            $finalGrade = $computed['final'] ?? '—';
            $remarks    = $computed['final'] !== null
                ? ($computed['final'] >= 75 ? 'Passed' : 'Failed')
                : '—';

            $rows[] = [
                $student->student_number,
                $student->last_name,
                $student->first_name,
                $student->course?->course_name ?? '—',
                $computed['written_work'] ?? '—',
                $computed['performance_task'] ?? '—',
                $computed['quarterly_assessment'] ?? '—',
                $finalGrade,
                $remarks,
            ];
        }

        return $this->streamCsv($rows, 'grades_report_' . now()->format('Ymd') . '.csv');
    }

    private function exportAttendanceCsv($user, $courseId = null)
    {
        $query = Attendance::with(['student', 'course'])
            ->when(!$user->isAdmin(), fn($q) =>
                $q->whereHas('course', fn($c) => $c->where('teacher_id', $user->id))
            )
            ->when($courseId, fn($q) => $q->where('course_id', $courseId))
            ->orderBy('date', 'desc');

        $records = $query->get();

        $rows   = [];
        $rows[] = ['Date', 'Student Number', 'Student Name', 'Course', 'Status', 'Remarks'];

        foreach ($records as $r) {
            $rows[] = [
                $r->date,
                $r->student?->student_number ?? '—',
                $r->student?->full_name ?? '—',
                $r->course?->course_name ?? '—',
                ucfirst($r->status),
                $r->remarks ?? '',
            ];
        }

        return $this->streamCsv($rows, 'attendance_report_' . now()->format('Ymd') . '.csv');
    }

    private function exportStudentsCsv($user)
    {
        $students = Student::with('course')
            ->when(!$user->isAdmin(), fn($q) =>
                $q->whereHas('course', fn($c) => $c->where('teacher_id', $user->id))
            )
            ->orderBy('last_name')
            ->get();

        $rows   = [];
        $rows[] = [
            'Student Number', 'Last Name', 'First Name', 'Middle Name',
            'Course', 'Year Level', 'Section', 'Term', 'Status',
            'Gender', 'Birthdate', 'Phone', 'Address'
        ];

        foreach ($students as $s) {
            $rows[] = [
                $s->student_number,
                $s->last_name,
                $s->first_name,
                $s->middle_name ?? '',
                $s->course?->course_name ?? '—',
                $s->year_level,
                $s->section ?? '',
                $s->term ?? '',
                ucfirst($s->status),
                $s->gender ?? '',
                $s->birthdate?->format('Y-m-d') ?? '',
                $s->phone ?? '',
                $s->address ?? '',
            ];
        }

        return $this->streamCsv($rows, 'students_report_' . now()->format('Ymd') . '.csv');
    }

    private function streamCsv(array $rows, string $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Export PDF ────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $user     = Auth::user();
        $courseId = $request->input('course_id');
        $type     = $request->input('type', 'grades');

        $courses = $user->isAdmin()
            ? Course::with('teacher')->when($courseId, fn($q) => $q->where('id', $courseId))->get()
            : Course::where('teacher_id', $user->id)->when($courseId, fn($q) => $q->where('id', $courseId))->get();

        $courseReports = $courses->map(fn($course) => $this->buildCourseReport($course));

        $html = view('reports.pdf', [
            'courseReports' => $courseReports,
            'generatedAt'   => now()->format('F d, Y h:i A'),
            'generatedBy'   => $user->display_name ?? $user->name,
            'type'          => $type,
        ])->render();

        if (class_exists(\Dompdf\Dompdf::class)) {
            $dompdf = new \Dompdf\Dompdf(['defaultPaperSize' => 'a4', 'isHtml5ParserEnabled' => true]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            return response($dompdf->output(), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="report_' . now()->format('Ymd') . '.pdf"',
            ]);
        }

        return response($html, 200, [
            'Content-Type'        => 'text/html',
            'Content-Disposition' => 'attachment; filename="report_' . now()->format('Ymd') . '.html"',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────
    private function buildCourseReport($course): array
    {
        $students    = Student::where('course_id', $course->id)->get();
        $assignments = Assignment::where('course_id', $course->id)->get();

        $studentData = $students->map(function ($student) use ($course) {
            $grades = Grade::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->with('assignment')
                ->get();
            $computed = $this->computeStudentGrade($grades);
            return [
                'student'  => $student,
                'computed' => $computed,
                'passed'   => $computed['final'] !== null && $computed['final'] >= 75,
            ];
        });

        $graded   = $studentData->filter(fn($s) => $s['computed']['final'] !== null);
        $avgFinal = $graded->isNotEmpty()
            ? round($graded->avg(fn($s) => $s['computed']['final']), 1)
            : 0;

        $attendance = Attendance::where('course_id', $course->id)->get();
        $attTotal   = $attendance->count();
        $attRate    = $attTotal > 0
            ? round($attendance->whereIn('status', ['present', 'late'])->count() / $attTotal * 100, 1)
            : null;

        return [
            'course'      => $course,
            'students'    => $studentData,
            'total'       => $students->count(),
            'graded'      => $graded->count(),
            'passed'      => $studentData->where('passed', true)->count(),
            'failed'      => $studentData->where('passed', false)->where('computed.final', '!=', null)->count(),
            'average'     => $avgFinal,
            'att_rate'    => $attRate,
            'assignments' => $assignments->count(),
            '90+'         => $graded->filter(fn($s) => $s['computed']['final'] >= 90)->count(),
            '80s'         => $graded->filter(fn($s) => $s['computed']['final'] >= 80 && $s['computed']['final'] < 90)->count(),
            '75s'         => $graded->filter(fn($s) => $s['computed']['final'] >= 75 && $s['computed']['final'] < 80)->count(),
            'fail'        => $graded->filter(fn($s) => $s['computed']['final'] < 75)->count(),
        ];
    }

    public function computeStudentGrade($grades): array
    {
        $byType = $grades->groupBy(fn($g) => $g->assignment?->type);

        $components  = [];
        $weightedSum = 0;
        $usedWeight  = 0;

        foreach (self::WEIGHTS as $type => $weight) {
            $items  = $byType->get($type, collect());
            $scored = $items->filter(fn($g) => $g->score !== null);

            if ($scored->isEmpty()) {
                $components[$type] = null;
                continue;
            }

            $totalEarned   = $scored->sum('score');
            $totalPossible = $scored->sum(fn($g) => $g->assignment?->total_points ?? 100);

            $pct = $totalPossible > 0
                ? round(($totalEarned / $totalPossible) * 100, 2)
                : 0;

            $components[$type] = $pct;
            $weightedSum += $pct * $weight;
            $usedWeight  += $weight;
        }

        $final = $usedWeight > 0 ? round($weightedSum / $usedWeight, 1) : null;

        return array_merge($components, ['final' => $final]);
    }
}