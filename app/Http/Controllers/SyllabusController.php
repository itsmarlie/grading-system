<?php
namespace App\Http\Controllers;

use App\Models\Syllabus;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyllabusController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $syllabi = $user->isAdmin()
            ? Syllabus::with(['course', 'creator'])->latest()->get()
            : Syllabus::with(['course', 'creator'])
                ->whereHas('course', fn($q) => $q->where('teacher_id', $user->id))
                ->latest()->get();

        return view('syllabi.index', compact('syllabi'));
    }

    public function create()
    {
        $user    = Auth::user();
        $courses = $user->isAdmin()
            ? Course::all()
            : Course::where('teacher_id', $user->id)->get();

        return view('syllabi.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'         => 'required|exists:courses,id',
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'course_overview'   => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'grading_criteria'  => 'nullable|array',
            'materials'         => 'nullable|array',
            'topics'            => 'nullable|array',
        ]);

        Syllabus::create([
            ...$request->only([
                'course_id', 'title', 'description', 'course_overview',
                'learning_outcomes', 'grading_criteria', 'materials', 'topics'
            ]),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('syllabi.index')->with('success', 'Syllabus created!');
    }

    public function edit(Syllabus $syllabus)
    {
        $user    = Auth::user();
        $courses = $user->isAdmin()
            ? Course::all()
            : Course::where('teacher_id', $user->id)->get();

        return view('syllabi.edit', compact('syllabus', 'courses'));
    }

    public function update(Request $request, Syllabus $syllabus)
    {
        $request->validate([
            'course_id'         => 'required|exists:courses,id',
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'course_overview'   => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'grading_criteria'  => 'nullable|array',
            'materials'         => 'nullable|array',
            'topics'            => 'nullable|array',
        ]);

        $syllabus->update($request->only([
            'course_id', 'title', 'description', 'course_overview',
            'learning_outcomes', 'grading_criteria', 'materials', 'topics'
        ]));

        return redirect()->route('syllabi.index')->with('success', 'Syllabus updated!');
    }

    public function destroy(Syllabus $syllabus)
    {
        $syllabus->delete();
        return back()->with('success', 'Syllabus deleted.');
    }
}