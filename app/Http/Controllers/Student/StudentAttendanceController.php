<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Courses via sections
        $courses = $student->sections()->with('course')->get()
            ->pluck('course')->unique('id')->filter()->values();

        $courseAttendance = $courses->map(function ($course) use ($student) {
            $records = Attendance::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $total   = $records->count();
            $present = $records->where('status', 'present')->count();
            $absent  = $records->where('status', 'absent')->count();
            $late    = $records->where('status', 'late')->count();
            $excused = $records->where('status', 'excused')->count();

            $rate = $total > 0
                ? round(($present + $late) / $total * 100, 1)
                : null;

            $byMonth = $records->groupBy(
                fn($r) => Carbon::parse($r->created_at)->format('Y-m')
            );

            return [
                'course'   => $course,
                'records'  => $records,
                'total'    => $total,
                'present'  => $present,
                'absent'   => $absent,
                'late'     => $late,
                'excused'  => $excused,
                'rate'     => $rate,
                'by_month' => $byMonth,
            ];
        });

        $allRecords = Attendance::where('student_id', $student->id)->get();
        $overallRate = $allRecords->count()
            ? round(
                $allRecords->whereIn('status', ['present', 'late'])->count()
                / $allRecords->count() * 100, 1
              )
            : null;

        return view('student.attendance', compact(
            'student',
            'courseAttendance',
            'overallRate',
            'allRecords'
        ));
    }
}