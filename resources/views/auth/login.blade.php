@extends('layouts.guest')

@section('content')

<div id="login-screen" style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:#052e16;">

    <div class="login-card" style="background:white;border-radius:20px;padding:44px 40px;width:380px;box-shadow:0 30px 80px rgba(0,0,0,.3)">

        <!-- Logo -->
        <div class="login-logo text-center mb-4">

            <div style="width:60px;height:60px;background:#16a34a;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 12px;">
                🎓
            </div>

            <h1 style="font-family:'DM Serif Display',serif;color:#14532d;font-size:1.8rem;font-weight:bold;margin-bottom:4px;">
                EduGrade
            </h1>

            <p style="color:#6b7280;font-size:.82rem;">
                School Grading Management System
            </p>

        </div>


        <!-- Errors -->
        @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:10px 12px;border-radius:8px;font-size:.82rem;margin-bottom:16px;">
                {{ $errors->first() }}
            </div>
        @endif


        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">

            @csrf

            <p style="font-size:.78rem;color:#374151;margin-bottom:8px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">
                Sign in as
            </p>


            <!-- Hidden Role -->
            <input type="hidden" name="role" id="role" value="admin">


            <!-- Role Buttons -->
            <div style="display:flex;gap:8px;margin-bottom:22px;">

                <button
                    type="button"
                    class="role-btn active-role"
                    onclick="selectRole(this, 'admin')"
                    style="flex:1;padding:10px;border-radius:8px;border:none;background:#16a34a;color:white;font-size:.82rem;font-weight:600;cursor:pointer;">
                    Admin
                </button>

                <button
                    type="button"
                    class="role-btn"
                    onclick="selectRole(this, 'teacher')"
                    style="flex:1;padding:10px;border-radius:8px;border:1px solid #d1d5db;background:white;color:#374151;font-size:.82rem;font-weight:600;cursor:pointer;">
                    Teacher
                </button>

                <button
                    type="button"
                    class="role-btn"
                    onclick="selectRole(this, 'student')"
                    style="flex:1;padding:10px;border-radius:8px;border:1px solid #d1d5db;background:white;color:#374151;font-size:.82rem;font-weight:600;cursor:pointer;">
                    Student
                </button>

            </div>


            <!-- Username -->
            <label style="font-size:.82rem;font-weight:600;color:#4b5563;">
                Username
            </label>

            <input
                type="text"
                name="username"
                value="{{ old('username') }}"
                required
                autofocus
                autocomplete="username"
                style="width:100%;padding:11px 14px;margin-bottom:16px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.92rem;"
            >


            <!-- Password -->
            <label style="font-size:.82rem;font-weight:600;color:#4b5563;">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                autocomplete="current-password"
                style="width:100%;padding:11px 14px;margin-bottom:22px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.92rem;"
            >


            <!-- Submit -->
            <button
                type="submit"
                style="width:100%;padding:12px;background:#16a34a;color:white;border:none;border-radius:8px;font-size:.95rem;font-weight:700;cursor:pointer;">
                Sign In
            </button>


            <!-- Register -->
            <p style="text-align:center;margin-top:16px;font-size:.8rem;color:#6b7280;">
                Don't have an account?
            <span style="color:#6b7280;font-weight:600;">Contact your administrator.</span>
            </p>

        </form>

    </div>

</div>


<script>

    function selectRole(button, role)
    {
        // Set role value
        document.getElementById('role').value = role;

        // Reset all buttons
        document.querySelectorAll('.role-btn').forEach(btn => {

            btn.style.background = 'white';
            btn.style.color = '#374151';
            btn.style.border = '1px solid #d1d5db';

        });

        // Activate selected button
        button.style.background = '#16a34a';
        button.style.color = 'white';
        button.style.border = 'none';
    }

</script>

@endsection