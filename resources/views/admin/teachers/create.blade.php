@extends('layouts.app')
@section('title', 'Add Teacher')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Add New Teacher</h2>
        <p>Fill in the details below to register a new teacher.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="card-title">Teacher Information</span>
            <a href="{{ route('teachers.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teachers.store') }}">
                @csrf

                {{-- Name Row --}}
                <div class="form-row">
                    <div class="form-group" style="max-width:120px;">
                        <label class="form-label">Title <span style="color:var(--gray-400);font-weight:400;">(optional)</span></label>
                        <input type="text" name="title" class="form-input"
                               placeholder="Dr., Prof…"
                               value="{{ old('title') }}">
                    </div>
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

                {{-- ID + Department --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Teacher ID <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="teacher_id" class="form-input"
                               value="{{ old('teacher_id') }}" required placeholder="e.g. TCH-2024-001">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-input"
                               value="{{ old('department') }}" placeholder="e.g. Mathematics">
                    </div>
                </div>

                {{-- Email + Username --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" class="form-input"
                               value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="username" class="form-input"
                               value="{{ old('username') }}" required>
                    </div>
                </div>

                {{-- Phone + Birthdate --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-input"
                               value="{{ old('phone') }}" placeholder="e.g. 09XX-XXX-XXXX">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-input"
                               value="{{ old('birthdate') }}">
                    </div>
                </div>

                {{-- Address --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-input"
                               value="{{ old('address') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div style="border-top:1px solid var(--green-100);margin:18px 0 18px;padding-top:18px;">
                    <p style="font-size:.78rem;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">Account Password</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password <span style="color:#dc2626;">*</span></label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password <span style="color:#dc2626;">*</span></label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Teacher</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection