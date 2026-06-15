<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courses = $user->role === 'admin'
            ? Course::with('teacher')->get()
            : Course::where('teacher_id', $user->id)->get();

        return view('attendance.index', compact('courses'));
    }

    public function show($courseId)
    {
        $course = Course::findOrFail($courseId);
        $students = Student::where('course_id', $courseId)->get();
        $dates = Attendance::where('course_id', $courseId)
            ->select('attendance_date')
            ->distinct()
            ->orderBy('attendance_date')
            ->pluck('attendance_date');

        $attendance = [];
        foreach ($students as $student) {
            foreach ($dates as $date) {
                $record = Attendance::where('student_id', $student->id)
                    ->where('course_id', $courseId)
                    ->where('attendance_date', $date)
                    ->first();
                $attendance[$student->id][$date->format('Y-m-d')] = $record?->status;
            }
        }

        return view('attendance.show', compact('course', 'students', 'dates', 'attendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'       => 'required|exists:courses,id',
            'attendance_date' => 'required|date',
            'attendance'      => 'required|array',
            'attendance.*'    => 'required|in:present,absent,late',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id'      => $studentId,
                    'course_id'       => $request->course_id,
                    'attendance_date' => $request->attendance_date,
                ],
                ['status' => $status]
            );
        }

        return redirect()->route('attendance.show', $request->course_id)
            ->with('success', 'Attendance saved successfully!');
    }
}