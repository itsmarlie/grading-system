@extends('layouts.app')
@section('title', 'Create Account')
@section('content')

<div class="section">
    <div class="page-header">
        <h2>Create Account</h2>
        <p>
            @if(Auth::user()->isAdmin())
                Create a new admin, teacher, or student account.
            @else
                Create a new student account.
            @endif
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card" style="max-width:700px;">
        <div class="card-header">
            <span class="card-title">
                @if(Auth::user()->isAdmin()) 🔒 @else 👥 @endif
                New Account
            </span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('store-user') }}" id="createForm">
                @csrf

                {{-- ── ROLE SELECTOR (Admin only) ─────────────────── --}}
                @if(Auth::user()->isAdmin())
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'admin') }}">
                <div style="margin-bottom:20px;">
                    <p style="font-size:.78rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">
                        Account Type
                    </p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <button type="button" onclick="selectRole('admin')" id="btn-admin"
                            class="btn btn-primary btn-sm">
                            🔒 Admin
                        </button>
                        <button type="button" onclick="selectRole('teacher')" id="btn-teacher"
                            class="btn btn-outline btn-sm">
                            🎓 Teacher
                        </button>
                        <button type="button" onclick="selectRole('student')" id="btn-student"
                            class="btn btn-outline btn-sm">
                            👥 Student
                        </button>
                    </div>
                    <p style="font-size:.72rem;color:var(--gray-400);margin-top:8px;" id="roleHint">
                        Creating an Admin account — full system access.
                    </p>
                </div>
                @else
                {{-- Teachers can only create students --}}
                <input type="hidden" name="role" value="student">
                <div style="margin-bottom:16px;padding:10px 14px;background:var(--green-50);border-radius:8px;border:1px solid var(--green-100);">
                    <span style="font-size:.83rem;color:var(--green-800);">👥 Creating a <strong>Student</strong> account</span>
                </div>
                @endif

                {{-- ── PERSONAL INFORMATION ────────────────────────── --}}
                <div style="border-top:1px solid var(--green-100);padding-top:16px;margin-bottom:4px;">
                    <p style="font-size:.78rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">
                        Personal Information
                    </p>
                </div>

                {{-- Teacher title --}}
                <div class="form-row teacher-only" style="display:none;">
                    <div class="form-group" style="max-width:160px;">
                        <label class="form-label">Title <span style="color:var(--gray-400);font-weight:400;">(optional)</span></label>
                        <input type="text" name="title" class="form-input"
                            placeholder="Dr., Prof., Engr." value="{{ old('title') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="first_name" class="form-input"
                            value="{{ old('first_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name <span style="color:var(--gray-400);font-weight:400;">(optional)</span></label>
                        <input type="text" name="middle_name" class="form-input"
                            value="{{ old('middle_name') }}" placeholder="Leave blank if none">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="last_name" class="form-input"
                            value="{{ old('last_name') }}" required>
                    </div>
                </div>

                {{-- Teacher-only fields --}}
                <div class="teacher-only" style="display:none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Teacher ID</label>
                            <input type="text" name="teacher_id" class="form-input"
                                value="{{ old('teacher_id') }}" placeholder="e.g. TCH-2024-001">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-input"
                                value="{{ old('department') }}" placeholder="e.g. Mathematics">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input"
                                value="{{ old('phone') }}" placeholder="e.g. 09XX-XXX-XXXX">
                        </div>
                    </div>
                </div>

                {{-- Student-only fields --}}
                <div class="student-only" style="display:none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Student Number <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="student_number" class="form-input"
                                value="{{ old('student_number') }}" placeholder="e.g. C-2024-0001">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender')===$g?'selected':'' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Course / Program <span style="color:#dc2626;">*</span></label>
                            <select name="course_id" class="form-select">
                                <option value="">Select Course</option>
                                @foreach(\App\Models\Course::orderBy('course_name')->get() as $c)
                                <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}>
                                    {{ $c->course_code }} — {{ $c->course_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Year Level <span style="color:#dc2626;">*</span></label>
                            <select name="year_level" class="form-select">
                                <option value="">Select Year</option>
                                @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year','Irregular'] as $yr)
                                <option value="{{ $yr }}" {{ old('year_level')===$yr?'selected':'' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Section</label>
                            <input type="text" name="section" class="form-input"
                                value="{{ old('section') }}" placeholder="e.g. BSIT-2A">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Term <span style="color:#dc2626;">*</span></label>
                            <select name="term" class="form-select">
                                <option value="">Select Term</option>
                                @foreach(['1st Semester','2nd Semester','Summer'] as $t)
                                <option value="{{ $t }}" {{ old('term')===$t?'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['active','inactive','graduated'] as $s)
                                <option value="{{ $s }}" {{ old('status','active')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Birthdate</label>
                            <input type="date" name="birthdate" class="form-input"
                                value="{{ old('birthdate') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input"
                                value="{{ old('phone') }}" placeholder="09XX-XXX-XXXX">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-input"
                                value="{{ old('address') }}">
                        </div>
                    </div>
                </div>

                {{-- ── LOGIN CREDENTIALS ───────────────────────────── --}}
                <div style="border-top:1px solid var(--green-100);padding-top:16px;margin-top:8px;margin-bottom:4px;">
                    <p style="font-size:.78rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">
                        Login Credentials
                    </p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Username <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="username" class="form-input"
                            value="{{ old('username') }}" required>
                    </div>
                    {{-- Email hidden for students (auto-generated) --}}
                    <div class="form-group non-student-only">
                        <label class="form-label">Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" class="form-input"
                            value="{{ old('email') }}">
                    </div>
                </div>

                {{-- Student temp password info box --}}
                <div class="student-only" style="display:none;">
                    <div style="background:var(--green-50);border:1.5px solid var(--green-200);border-radius:8px;padding:12px 16px;margin-bottom:14px;font-size:.8rem;color:var(--green-800);">
                        🔐 A student email will be auto-generated as <strong>studentnumber@student.edugrade.com</strong>.
                        Set a temporary password below — the student must change it on first login.
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Temporary Password <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="temp_password" class="form-input"
                                value="{{ old('temp_password') }}"
                                placeholder="Give this to the student" minlength="8">
                        </div>
                    </div>
                </div>

                {{-- Normal password for admin/teacher --}}
                <div class="non-student-only">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password <span style="color:#dc2626;">*</span></label>
                            <input type="password" name="password" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password <span style="color:#dc2626;">*</span></label>
                            <input type="password" name="password_confirmation" class="form-input">
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:16px;">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Create Admin Account
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};

function selectRole(role) {
    if (!isAdmin) return; // Teachers cannot switch role

    document.getElementById('roleInput').value = role;

    // Button styles
    ['admin','teacher','student'].forEach(r => {
        const btn = document.getElementById('btn-' + r);
        if (btn) btn.className = 'btn btn-sm ' + (r === role ? 'btn-primary' : 'btn-outline');
    });

    // Hints
    const hints = {
        admin:   'Creating an Admin account — full system access.',
        teacher: 'Creating a Teacher account — can manage courses and students.',
        student: 'Creating a Student account — email will be auto-generated.',
    };
    const hintEl = document.getElementById('roleHint');
    if (hintEl) hintEl.textContent = hints[role] ?? '';

    // Show/hide field groups
    document.querySelectorAll('.teacher-only').forEach(el => {
        el.style.display = role === 'teacher' ? '' : 'none';
    });
    document.querySelectorAll('.student-only').forEach(el => {
        el.style.display = role === 'student' ? '' : 'none';
    });
    document.querySelectorAll('.non-student-only').forEach(el => {
        el.style.display = role === 'student' ? 'none' : '';
    });

    // Submit button label
    const labels = {
        admin:   'Create Admin Account',
        teacher: 'Create Teacher Account',
        student: 'Create Student Account',
    };
    document.getElementById('submitBtn').textContent = labels[role] ?? 'Create Account';

    // Toggle required attributes
    const emailInput    = document.querySelector('input[name="email"]');
    const passInput     = document.querySelector('input[name="password"]');
    const passConfInput = document.querySelector('input[name="password_confirmation"]');
    const tempPassInput = document.querySelector('input[name="temp_password"]');
    const studNumInput  = document.querySelector('input[name="student_number"]');

    if (role === 'student') {
        if (emailInput)    emailInput.removeAttribute('required');
        if (passInput)     passInput.removeAttribute('required');
        if (passConfInput) passConfInput.removeAttribute('required');
        if (tempPassInput) tempPassInput.setAttribute('required', 'required');
        if (studNumInput)  studNumInput.setAttribute('required', 'required');
    } else {
        if (emailInput)    emailInput.setAttribute('required', 'required');
        if (passInput)     passInput.setAttribute('required', 'required');
        if (passConfInput) passConfInput.setAttribute('required', 'required');
        if (tempPassInput) tempPassInput.removeAttribute('required');
        if (studNumInput)  studNumInput.removeAttribute('required');
    }
}

@if(Auth::user()->isAdmin())
    selectRole('{{ old('role', 'admin') }}');
@else
    // Teacher
    document.querySelectorAll('.student-only').forEach(el => el.style.display = '');
    document.querySelectorAll('.non-student-only').forEach(el => el.style.display = 'none');
    document.getElementById('submitBtn').textContent = 'Create Student Account';
@endif
</script>
@endpush
@endsection