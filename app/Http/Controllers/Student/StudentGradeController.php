<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $student = Student::with('course')
                          ->where('user_id', $user->id)
                          ->firstOrFail();

        // Use course_id directly — no sections needed
        $courses = collect();
        if ($student->course) {
            $courses = collect([$student->course]);
        }

        $courseGrades = $courses->map(function ($course) use ($student) {
            $assignments = Assignment::where('course_id', $course->id)
                ->orderBy('due_date')
                ->get();

            $byType = $assignments->groupBy('type')->map(function ($group) use ($student) {
                return $group->map(function ($assignment) use ($student) {
                    $grade = Grade::where('student_id', $student->id)
                                  ->where('assignment_id', $assignment->id)
                                  ->first();
                    $maxScore = $assignment->total_points ?? 100;
                    return [
                        'assignment'   => $assignment,
                        'score'        => $grade?->score,
                        'max_score'    => $maxScore,
                        'percentage'   => $grade && $maxScore
                                            ? round(($grade->score / $maxScore) * 100, 1)
                                            : null,
                        'letter_grade' => $grade
                                            ? $this->letterGrade($grade->score, $maxScore)
                                            : null,
                        'remarks'      => $grade?->remarks,
                    ];
                });
            });

            $weights = [
                'quiz'       => 0.20,
                'assignment' => 0.20,
                'project'    => 0.25,
                'exam'       => 0.30,
                'activity'   => 0.05,
            ];

            $computedGrade = $this->computeWeightedGrade($byType, $weights);

            $allGrades = Grade::where('student_id', $student->id)
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->get();

            $avgScore = $allGrades->count()
                ? round($allGrades->avg('score'), 1)
                : null;

            return [
                'course'         => $course,
                'by_type'        => $byType,
                'weights'        => $weights,
                'computed_grade' => $computedGrade,
                'avg_score'      => $avgScore,
                'letter_grade'   => $avgScore ? $this->letterGradeRaw($avgScore) : null,
                'total_items'    => $assignments->count(),
                'graded_items'   => $allGrades->count(),
            ];
        });

        return view('student.grades', compact('student', 'courseGrades'));
    }

    private function computeWeightedGrade($byType, $weights): ?float
    {
        $total = 0;
        $usedWeight = 0;

        foreach ($weights as $type => $weight) {
            $items  = $byType->get($type, collect());
            $scored = $items->filter(fn($i) => $i['score'] !== null);
            if ($scored->isEmpty()) continue;

            $avg = $scored->avg(fn($i) => $i['max_score'] > 0
                ? ($i['score'] / $i['max_score']) * 100 : 0);

            $total      += $avg * $weight;
            $usedWeight += $weight;
        }

        return $usedWeight > 0 ? round($total / $usedWeight, 1) : null;
    }

    private function letterGrade($score, $max): string
    {
        if ($max == 0) return '—';
        return $this->letterGradeRaw(($score / $max) * 100);
    }

    private function letterGradeRaw(float $pct): string
    {
        return match(true) {
            $pct >= 97 => '1.00',
            $pct >= 94 => '1.25',
            $pct >= 91 => '1.50',
            $pct >= 88 => '1.75',
            $pct >= 85 => '2.00',
            $pct >= 82 => '2.25',
            $pct >= 79 => '2.50',
            $pct >= 76 => '2.75',
            $pct >= 75 => '3.00',
            default    => '5.00',
        };
    }
}