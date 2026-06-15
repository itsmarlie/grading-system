<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'username'    => ['required', 'string', 'max:255', 'unique:users'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'in:student,teacher'],
            'student_id'  => ['nullable', 'string'],
            'teacher_id'  => ['nullable', 'string'],
            'department'  => ['nullable', 'string'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:255'],
            'birthdate'   => ['nullable', 'date'],
        ]);

        $fullName = trim(
            $request->first_name . ' ' .
            ($request->middle_name ? $request->middle_name . ' ' : '') .
            $request->last_name
        );

        $user = User::create([
            'name'        => $fullName,
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'student_id'  => $request->student_id,
            'teacher_id'  => $request->teacher_id,
            'department'  => $request->department,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'birthdate'   => $request->birthdate,
        ]);

        // Auto-create Student record when role is student
        if ($request->role === 'student') {
            Student::create([
                'user_id'        => $user->id,
                'first_name'     => $request->first_name,
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'student_number' => $request->student_id ?? $user->id,
                'course'         => 'N/A',
                'year_level'     => 'N/A',
                'term'           => 'N/A',
                'status'         => 'active',
                'phone'          => $request->phone,
                'address'        => $request->address,
                'birthdate'      => $request->birthdate,
            ]);
        }

        event(new Registered($user));

        return redirect()->route('login')
            ->with('success', 'Account created! Please sign in with your username and password.');
    }
}