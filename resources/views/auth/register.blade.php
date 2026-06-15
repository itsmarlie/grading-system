@extends('layouts.guest')

@section('content')
<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:#052e16;padding:40px 16px;">

    <div style="background:white;border-radius:20px;padding:44px 40px;width:520px;max-width:100%;box-shadow:0 30px 80px rgba(0,0,0,.3);">

        {{-- Logo --}}
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:60px;height:60px;background:#16a34a;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 12px;">
                🎓
            </div>
            <h1 style="font-family:'DM Serif Display',serif;color:#14532d;font-size:1.6rem;font-weight:bold;margin-bottom:4px;">
                Create Account
            </h1>
            <p style="color:#6b7280;font-size:.82rem;">EduGrade — School Grading Management System</p>
        </div>

        {{-- Errors --}}
        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:10px 12px;border-radius:8px;font-size:.82rem;margin-bottom:16px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Role selector --}}
            <p style="font-size:.78rem;color:#374151;margin-bottom:8px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">Register as</p>
            <input type="hidden" name="role" id="role-input" value="student">
            <div style="display:flex;gap:8px;margin-bottom:22px;">
                <button type="button" class="reg-role-btn"
                    id="btn-student"
                    onclick="selectRegRole(this,'student')"
                    style="flex:1;padding:10px;border-radius:8px;border:none;background:#16a34a;color:white;font-size:.82rem;font-weight:600;cursor:pointer;">
                    Student
                </button>
                <button type="button" class="reg-role-btn"
                    id="btn-teacher"
                    onclick="selectRegRole(this,'teacher')"
                    style="flex:1;padding:10px;border-radius:8px;border:1px solid #d1d5db;background:white;color:#374151;font-size:.82rem;font-weight:600;cursor:pointer;">
                    Teacher
                </button>
            </div>

            {{-- Name Fields --}}
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">

                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">
                        First Name
                    </label>

                    <input type="text"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                        autofocus
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>

                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">
                        Middle Name
                    </label>

                    <input type="text"
                        name="middle_name"
                        value="{{ old('middle_name') }}"
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>

                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">
                        Last Name
                    </label>

                    <input type="text"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>

            </div>

            {{-- Username --}}
            <div style="margin-top:14px;">
                <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">
                    Username
                </label>

                <input type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
            </div>

            {{-- Email + Phone --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px;">
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>
            </div>

            {{-- Birthdate + Address --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px;">
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Birthdate</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate') }}"
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>
            </div>

            {{-- Student-only field --}}
            <div id="student-fields" style="margin-top:14px;">
                <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Student ID</label>
                <input type="text" name="student_id" value="{{ old('student_id') }}"
                    style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
            </div>

            {{-- Teacher-only fields --}}
            <div id="teacher-fields" style="display:none;margin-top:14px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Teacher ID</label>
                        <input type="text" name="teacher_id" value="{{ old('teacher_id') }}"
                            style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                    </div>
                    <div>
                        <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Department</label>
                        <input type="text" name="department" value="{{ old('department') }}"
                            style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                    </div>
                </div>
            </div>

            {{-- Password + Confirm --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px;">
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Password</label>
                    <input type="password" name="password" required
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:600;color:#4b5563;display:block;margin-bottom:5px;">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        style="width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.87rem;outline:none;font-family:inherit;">
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                style="width:100%;padding:12px;background:#16a34a;color:white;border:none;border-radius:8px;font-size:.95rem;font-weight:700;cursor:pointer;margin-top:24px;font-family:inherit;">
                Create Account
            </button>

            {{-- Back to login --}}
            <p style="text-align:center;margin-top:16px;font-size:.8rem;color:#6b7280;">
                Already have an account?
                <a href="{{ route('login') }}" style="color:#16a34a;font-weight:600;text-decoration:none;">Sign In</a>
            </p>

        </form>
    </div>
</div>

<script>
    function selectRegRole(btn, role) {
        document.getElementById('role-input').value = role;
        document.querySelectorAll('.reg-role-btn').forEach(b => {
            b.style.background = 'white';
            b.style.color = '#374151';
            b.style.border = '1px solid #d1d5db';
        });
        btn.style.background = '#16a34a';
        btn.style.color = 'white';
        btn.style.border = 'none';

        document.getElementById('student-fields').style.display = role === 'student' ? 'block' : 'none';
        document.getElementById('teacher-fields').style.display = role === 'teacher' ? 'block' : 'none';
    }
</script>
@endsection