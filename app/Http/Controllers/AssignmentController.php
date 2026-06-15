<?php
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $assignments = $user->role === 'admin'
            ? Assignment::with('course')->latest()->get()
            : Assignment::with('course')
                ->whereHas('course', fn($q) => $q->where('teacher_id', $user->id))
                ->latest()->get();

        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $user    = Auth::user();
        $courses = $user->role === 'admin'
            ? Course::all()
            : Course::where('teacher_id', $user->id)->get();

        return view('assignments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:written_work,performance_task,quarterly_assessment',
            'description'  => 'nullable|string',
            'total_points' => 'required|integer|min:1',
            'due_date'     => 'required|date',
            'term'         => 'required|string',
            'section'      => 'nullable|string|max:100',
            'status'       => 'required|in:open,closed',
        ]);

        Assignment::create($request->only([
            'course_id', 'title', 'type', 'description',
            'total_points', 'due_date', 'term', 'section', 'status',
        ]));

        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully!');
    }

    public function show($id)
    {
        $assignment = Assignment::with(['course', 'grades.student'])->findOrFail($id);
        return view('assignments.show', compact('assignment'));
    }

    public function edit($id)
    {
        $assignment = Assignment::findOrFail($id);
        $user       = Auth::user();
        $courses    = $user->role === 'admin'
            ? Course::all()
            : Course::where('teacher_id', $user->id)->get();

        return view('assignments.edit', compact('assignment', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:written_work,performance_task,quarterly_assessment',
            'description'  => 'nullable|string',
            'total_points' => 'required|integer|min:1',
            'due_date'     => 'required|date',
            'term'         => 'required|string',
            'section'      => 'nullable|string|max:100',
            'status'       => 'required|in:open,closed',
        ]);

        $assignment->update($request->only([
            'course_id', 'title', 'type', 'description',
            'total_points', 'due_date', 'term', 'section', 'status',
        ]));

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully!');
    }

    public function destroy($id)
    {
        Assignment::destroy($id);
        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully!');
    }
}