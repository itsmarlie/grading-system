<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function create(): View
    {
        // Both admin and teacher can access this page
        abort_unless(
            auth()->user()->isAdmin() || auth()->user()->isTeacher(),
            403
        );

        return view('admin.create-user');
    }

    public function store(Request $request): RedirectResponse
    {
        $authUser = auth()->user();

        abort_unless(
            $authUser->isAdmin() || $authUser->isTeacher(),
            403
        );

        $role = $request->input('role', 'admin');

        // Teachers can ONLY create students
        if ($authUser->isTeacher() && $role !== 'student') {
            abort(403, 'Teachers can only create student accounts.');
        }

        // ── STUDENT ────────────────────────────────────────────────
        if ($role === 'student') {
            $request->validate([
                'first_name'     => ['required', 'string', 'max:255'],
                'middle_name'    => ['nullable', 'string', 'max:255'],
                'last_name'      => ['required', 'string', 'max:255'],
                'student_number' => ['required', 'string', 'unique:students,student_number'],
                'course_id'      => ['required', 'exists:courses,id'],
                'year_level'     => ['required', 'string'],
                'section'        => ['nullable', 'string', 'max:100'],
                'term'           => ['required', 'string'],
                'gender'         => ['nullable', 'string'],
                'birthdate'      => ['nullable', 'date'],
                'phone'          => ['nullable', 'string', 'max:20'],
                'address'        => ['nullable', 'string'],
                'status'         => ['nullable', 'in:active,inactive,graduated'],
                'username'       => ['required', 'string', 'unique:users,username'],
                'temp_password'  => ['required', 'string', 'min:8'],
            ]);

            $tempPassword = $request->temp_password;

            $user = User::create([
                'name'                  => trim($request->first_name . ' ' . $request->last_name),
                'first_name'            => $request->first_name,
                'middle_name'           => $request->middle_name,
                'last_name'             => $request->last_name,
                'username'              => $request->username,
                'email'                 => strtolower($request->student_number) . '@student.edugrade.com',
                'password'              => Hash::make($tempPassword),
                'role'                  => 'student',
                'force_password_change' => true,
            ]);

            Student::create([
                'user_id'        => $user->id,
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
                'phone'          => $request->phone,
                'address'        => $request->address,
                'status'         => $request->status ?? 'active',
            ]);

            return redirect()->route('create-user')
                ->with('success', "Student account created! Login: {$user->email} — Temporary password: {$tempPassword} (note this down, it won't be shown again)");
        }

        // ── ADMIN or TEACHER (admin only) ──────────────────────────
        abort_unless($authUser->isAdmin(), 403);

        $rules = [
            'role'        => ['required', 'in:admin,teacher'],
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'username'    => ['required', 'string', 'max:255', 'unique:users'],
            'email'       => ['required', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($role === 'teacher') {
            $rules['teacher_id'] = ['nullable', 'string', 'max:100'];
            $rules['department'] = ['nullable', 'string', 'max:255'];
            $rules['title']      = ['nullable', 'string', 'max:50'];
            $rules['phone']      = ['nullable', 'string', 'max:20'];
        }

        $request->validate($rules);

        $fullName = trim(
            $request->first_name . ' ' .
            ($request->middle_name ? $request->middle_name . ' ' : '') .
            $request->last_name
        );

        User::create([
            'name'        => $fullName,
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'title'       => $request->title,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $role,
            'teacher_id'  => $request->teacher_id,
            'department'  => $request->department,
            'phone'       => $request->phone,
        ]);

        $label = ucfirst($role);
        return redirect()->route('create-user')
            ->with('success', "{$label} account created successfully!");
    }
}
