<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with(['course', 'schedules', 'students'])->latest()->paginate(15);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $courses = Course::orderBy('course_name')->get();
        return view('admin.sections.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'         => 'required|exists:courses,id',
            'name'              => 'required|string|max:50',
            'max_students'      => 'nullable|integer|min:1',
            'schedules'         => 'required|array|min:1',
            'schedules.*.day'   => 'required|string',
            'schedules.*.start' => 'required',
            'schedules.*.end'   => 'required',
            'schedules.*.room'  => 'nullable|string|max:50',
        ]);

        $section = Section::create([
            'course_id'    => $request->course_id,
            'name'         => $request->name,
            'max_students' => $request->max_students ?? 40,
        ]);

        foreach ($request->schedules as $sched) {
            $section->schedules()->create([
                'day'        => $sched['day'],
                'start_time' => $sched['start'],
                'end_time'   => $sched['end'],
                'room'       => $sched['room'] ?? null,
            ]);
        }

        return redirect()->route('admin.sections.index')
            ->with('success', "Section {$section->name} created successfully.");
    }

    public function show(Section $section)
    {
        $section->load(['course', 'schedules', 'students.user']);
        return view('admin.sections.show', compact('section'));
    }

    public function edit(Section $section)
    {
        $courses = Course::orderBy('course_name')->get();
        $section->load(['course', 'schedules']);
        return view('admin.sections.edit', compact('section', 'courses'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'course_id'         => 'required|exists:courses,id',
            'name'              => 'required|string|max:50',
            'max_students'      => 'nullable|integer|min:1',
            'schedules'         => 'required|array|min:1',
            'schedules.*.day'   => 'required|string',
            'schedules.*.start' => 'required',
            'schedules.*.end'   => 'required',
            'schedules.*.room'  => 'nullable|string|max:50',
        ]);

        $section->update([
            'course_id'    => $request->course_id,
            'name'         => $request->name,
            'max_students' => $request->max_students ?? 40,
        ]);

        $section->schedules()->delete();
        foreach ($request->schedules as $sched) {
            $section->schedules()->create([
                'day'        => $sched['day'],
                'start_time' => $sched['start'],
                'end_time'   => $sched['end'],
                'room'       => $sched['room'] ?? null,
            ]);
        }

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted.');
    }

    // ── ASSIGN STUDENTS TO SECTION ────────────────────────────────
    public function assignStudents(Section $section)
    {
        $section->load(['course', 'students']);

        $assignedIds = $section->students->pluck('id')->toArray();

        $students = \App\Models\Student::with('user')
            ->where('course_id', $section->course_id)
            ->orderBy('last_name')
            ->get();

        return view('admin.sections.assign-students', compact('section', 'students', 'assignedIds'));
    }

    public function saveAssignStudents(Request $request, Section $section)
    {
        $request->validate([
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $ids         = $request->input('student_ids', []);
        $studentType = $request->input('student_type', 'regular');

        $syncData = [];
        foreach ($ids as $id) {
            $syncData[$id] = ['student_type' => $studentType];
        }

        $section->students()->sync($syncData);

        return redirect()->route('admin.sections.show', $section->id)
            ->with('success', count($ids) . ' student(s) assigned to section ' . $section->name . '.');
    }
}