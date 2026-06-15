<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // ── HELPER: get IDs of students the current teacher handles ──
    private function teacherStudentIds(): array
    {
        return Student::whereHas('course', function ($q) {
            $q->where('teacher_id', Auth::id());
        })->pluck('id')->toArray();
    }

    // ── HELPER: abort 403 if teacher doesn't own this student ──
    private function authorizeStudent(Student $student): void
    {
        if (Auth::user()->role === 'admin') return;

        $allowed = Student::where('id', $student->id)
            ->whereHas('course', fn($q) => $q->where('teacher_id', Auth::id()))
            ->exists();

        abort_unless($allowed, 403, 'You do not have access to this student.');
    }

    // ─────────────────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();

        $students = $user->role === 'admin'
            ? Student::with(['course', 'user'])->get()
            : Student::with(['course', 'user'])
                ->whereHas('course', fn($q) => $q->where('teacher_id', $user->id))
                ->get();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $user = Auth::user();

        // Teachers can only assign students to their own courses
        $courses = $user->role === 'admin'
            ? Course::orderBy('course_name')->get()
            : Course::where('teacher_id', $user->id)->orderBy('course_name')->get();

        return view('students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'student_number' => 'required|string|unique:students',
            'course_id'      => 'required|exists:courses,id',
            'year_level'     => 'required|string',
            'section'        => 'nullable|string|max:100',
            'term'           => 'required|string',
            'gender'         => 'nullable|string',
            'birthdate'      => 'nullable|date',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string',
            'status'         => 'required|in:active,inactive,graduated',
            'username'       => 'required|string|unique:users,username',
            'temp_password'  => 'required|string|min:8',
        ]);

        // Prevent teacher from assigning a student to someone else's course
        if ($user->role !== 'admin') {
            $ownsCourse = Course::where('id', $request->course_id)
                ->where('teacher_id', $user->id)
                ->exists();

            abort_unless($ownsCourse, 403, 'You can only assign students to your own courses.');
        }

        $tempPassword = $request->temp_password;

        $newUser = User::create([
            'name'                  => trim($request->first_name . ' ' . $request->last_name),
            'first_name'            => $request->first_name,
            'middle_name'           => $request->middle_name,
            'last_name'             => $request->last_name,
            'username'              => $request->username,
            'email'                 => strtolower($request->student_number) . '@student.edugrade.com',
            'password'              => bcrypt($tempPassword),
            'role'                  => 'student',
            'force_password_change' => true,
        ]);

        Student::create([
            'user_id'        => $newUser->id,
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'student_number' => $request->student_number,
            'course_id'      => $request->course_id,
            'year_level'     => $request->year_level,
            'section'        => $request->section,
            'term'           => $request->term,
            'gender'         => $request->gender,
            'birthdate'      => $request->birthdate,
            'address'        => $request->address,
            'phone'          => $request->phone,
            'status'         => $request->status,
        ]);

        return redirect()->route('students.index')
            ->with('success', "Student created! Login: {$newUser->email} — Temporary password: {$tempPassword} (shown once, please note it down now)");
    }

    public function show($id)
    {
        $student = Student::with(['course', 'grades.assignment', 'attendances'])->findOrFail($id);

        $this->authorizeStudent($student);

        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);

        $this->authorizeStudent($student);

        $user = Auth::user();

        $courses = $user->role === 'admin'
            ? Course::orderBy('course_name')->get()
            : Course::where('teacher_id', $user->id)->orderBy('course_name')->get();

        return view('students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $this->authorizeStudent($student);

        $user = Auth::user();

        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'student_number' => 'required|string|unique:students,student_number,' . $id,
            'course_id'      => 'nullable|exists:courses,id',
            'year_level'     => 'nullable|string',
            'section'        => 'nullable|string|max:100',
            'term'           => 'nullable|string',
            'gender'         => 'nullable|string',
            'birthdate'      => 'nullable|date',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string',
            'status'         => 'required|in:active,inactive,graduated',
        ]);

        // Prevent teacher from reassigning student to someone else's course
        if ($user->role !== 'admin' && $request->course_id) {
            $ownsCourse = Course::where('id', $request->course_id)
                ->where('teacher_id', $user->id)
                ->exists();

            abort_unless($ownsCourse, 403, 'You can only assign students to your own courses.');
        }

        DB::transaction(function () use ($request, $student) {
            $student->update([
                'first_name'     => $request->first_name,
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'student_number' => $request->student_number,
                'course_id'      => $request->course_id,
                'year_level'     => $request->year_level,
                'section'        => $request->section,
                'term'           => $request->term,
                'gender'         => $request->gender,
                'birthdate'      => $request->birthdate,
                'address'        => $request->address,
                'phone'          => $request->phone,
                'status'         => $request->status,
            ]);

            if ($student->user) {
                $student->user->update([
                    'name'        => trim($request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name),
                    'first_name'  => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name'   => $request->last_name,
                ]);
            }
        });

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::with('user')->findOrFail($id);

        // Only admins can delete students
        abort_unless(Auth::user()->role === 'admin', 403, 'Only administrators can delete students.');

        DB::transaction(function () use ($student) {
            if ($student->user) {
                $student->user->delete();
            }
            $student->delete();
        });

        return redirect()->route('students.index')
            ->with('success', 'Student and their account deleted successfully.');
    }

    // ── ASSIGN STUDENTS TO A COURSE ──────────────────────────────
    public function assignCourse(Course $course)
    {
        // Teachers can only assign students to their own courses
        if (Auth::user()->role !== 'admin') {
            abort_unless(
                $course->teacher_id === Auth::id(),
                403,
                'You can only manage students in your own courses.'
            );
        }

        $assigned = $course->students()->pluck('students.id')->toArray();

        $students = Student::with('user')
            ->orderBy('last_name')
            ->get();

        return view('students.assign-course', compact('course', 'students', 'assigned'));
    }

    public function saveAssignCourse(Request $request, Course $course)
    {
        if (Auth::user()->role !== 'admin') {
            abort_unless(
                $course->teacher_id === Auth::id(),
                403,
                'You can only manage students in your own courses.'
            );
        }

        $request->validate([
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $ids = $request->input('student_ids', []);

        Student::whereIn('id', $ids)->update(['course_id' => $course->id]);

        Student::whereNotIn('id', $ids)
            ->where('course_id', $course->id)
            ->update(['course_id' => null]);

        return redirect()->route('courses.show', $course->id)
            ->with('success', count($ids) . ' student(s) assigned to ' . $course->course_name . '.');
    }
}