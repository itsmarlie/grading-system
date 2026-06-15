<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Auth;

class StudentSyllabusController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $student = Student::with('course')
                          ->where('user_id', $user->id)
                          ->firstOrFail();

        $courses = $student->course ? collect([$student->course]) : collect();

        $syllabi = Syllabus::with(['course', 'creator'])
            ->where('course_id', $student->course_id)
            ->get()
            ->groupBy('course_id');

        return view('student.syllabus', compact('student', 'syllabi', 'courses'));
    }

    public function show($id)
    {
        $user     = Auth::user();
        $student  = Student::where('user_id', $user->id)->firstOrFail();
        $syllabus = Syllabus::with(['course', 'creator'])->findOrFail($id);

        // Make sure student is enrolled in this course
        abort_unless(
            $student->course_id === $syllabus->course_id,
            403,
            'You are not enrolled in this course.'
        );

        return view('student.syllabus-show', compact('student', 'syllabus'));
    }
}