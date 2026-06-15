<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courses = $user->role === 'admin'
            ? Course::with('teacher')->get()
            : Course::with('teacher')->where('teacher_id', $user->id)->get();

        return view('course.index', compact('courses'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('course.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code'  => 'required|string|unique:courses',
            'course_name'  => 'required|string',
            'description'  => 'nullable|string',
            'teacher_id'   => 'required|exists:users,id',
            'units'        => 'required|integer',
            'schedule'     => 'nullable|string',
            'room'         => 'nullable|string',
            'term'         => 'required|string',
            'school_year'  => 'required|string',
            'ww_weight'    => 'required|integer',
            'pt_weight'    => 'required|integer',
            'qa_weight'    => 'required|integer',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully!');
    }

    public function show($id)
    {
        $course = Course::with(['teacher', 'students', 'assignments'])->findOrFail($id);
        return view('course.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();
        return view('course.edit', compact('course', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'course_code'  => 'required|string|unique:courses,course_code,' . $id,
            'course_name'  => 'required|string',
            'description'  => 'nullable|string',
            'teacher_id'   => 'required|exists:users,id',
            'units'        => 'required|integer',
            'schedule'     => 'nullable|string',
            'room'         => 'nullable|string',
            'term'         => 'required|string',
            'school_year'  => 'required|string',
            'ww_weight'    => 'required|integer',
            'pt_weight'    => 'required|integer',
            'qa_weight'    => 'required|integer',
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        Course::destroy($id);
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }
}