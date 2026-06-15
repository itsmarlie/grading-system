<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradebookController extends Controller
{
    // DepEd weights
    const WEIGHTS = [
        'written_work'         => 0.30,
        'performance_task'     => 0.50,
        'quarterly_assessment' => 0.20,
    ];

    const WEIGHT_LABELS = [
        'written_work'         => 'Written Work (30%)',
        'performance_task'     => 'Performance Task (50%)',
        'quarterly_assessment' => 'Quarterly Assessment (20%)',
    ];

    public function index()
    {
        $user = Auth::user();

        $courses = $user->role === 'admin'
            ? Course::with(['teacher'])->withCount('students')->get()
            : Course::with(['teacher'])->withCount('students')
                    ->where('teacher_id', $user->id)->get();

        // Add grade summary per course
        $courses = $courses->map(function ($course) {
            $students = Student::where('course_id', $course->id)->get();
            $finals   = [];

            foreach ($students as $student) {
                $grades   = Grade::where('student_id', $student->id)
                                 ->where('course_id', $course->id)
                                 ->with('assignment')
                                 ->get();
                $computed = $this->computeGrade($grades);
                if ($computed['final'] !== null) {
                    $finals[] = $computed['final'];
                }
            }

            $course->grade_average = count($finals)
                ? round(array_sum($finals) / count($finals), 1)
                : null;
            $course->graded_count  = count($finals);

            return $course;
        });

        return view('gradebook.index', compact('courses'));
    }

    public function show($courseId)
    {
        $course      = Course::with(['teacher'])->findOrFail($courseId);
        $students    = Student::where('course_id', $courseId)
                              ->orderBy('last_name')
                              ->get();
        $assignments = Assignment::where('course_id', $courseId)
                                 ->orderBy('type')
                                 ->orderBy('created_at')
                                 ->get();

        // Group assignments by type
        $byType = $assignments->groupBy('type');

        // Build grade matrix: grades[student_id][assignment_id] = score
        $gradeMatrix = [];
        foreach ($students as $student) {
            foreach ($assignments as $assignment) {
                $grade = Grade::where('student_id', $student->id)
                    ->where('assignment_id', $assignment->id)
                    ->first();
                $gradeMatrix[$student->id][$assignment->id] = $grade?->score;
            }
        }

        // Compute weighted final grade per student
        $studentGrades = [];
        foreach ($students as $student) {
            $grades = Grade::where('student_id', $student->id)
                           ->where('course_id', $courseId)
                           ->with('assignment')
                           ->get();
            $studentGrades[$student->id] = $this->computeGrade($grades);
        }

        // Class summary
        $finals = collect($studentGrades)
            ->filter(fn($g) => $g['final'] !== null)
            ->pluck('final');

        $classSummary = [
            'average' => $finals->isNotEmpty() ? round($finals->avg(), 1) : null,
            'highest' => $finals->isNotEmpty() ? $finals->max() : null,
            'lowest'  => $finals->isNotEmpty() ? $finals->min() : null,
            'passed'  => $finals->filter(fn($f) => $f >= 75)->count(),
            'failed'  => $finals->filter(fn($f) => $f < 75)->count(),
            'total'   => $finals->count(),
        ];

        return view('gradebook.show', compact(
            'course',
            'students',
            'assignments',
            'byType',
            'gradeMatrix',
            'studentGrades',
            'classSummary'
        ));
    }

    public function update(Request $request, $courseId)
    {
        $request->validate([
            'grades'     => 'required|array',
            'grades.*.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->grades as $studentId => $assignmentGrades) {
            foreach ($assignmentGrades as $assignmentId => $score) {
                $assignment = Assignment::find($assignmentId);
                if (!$assignment) continue;

                // Validate score doesn't exceed total_points
                if ($score !== null && $score > $assignment->total_points) {
                    continue; // skip invalid scores
                }

                Grade::updateOrCreate(
                    [
                        'student_id'    => $studentId,
                        'assignment_id' => $assignmentId,
                    ],
                    [
                        'course_id' => $courseId,
                        'score'     => $score === '' ? null : $score,
                        'term'      => $assignment->term,
                    ]
                );
            }
        }

        return redirect()->route('gradebook.show', $courseId)
            ->with('success', 'Grades saved successfully!');
    }

    // ── Weighted grade computation (DepEd) ────────────────────────────
    private function computeGrade($grades): array
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