@extends('layouts.app')
@section('title', 'Edit Teacher')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Edit Teacher</h2>
        <p>Update the information for {{ $teacher->user->display_name ?? $teacher->user->name }}.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="card-title">Teacher Information</span>
            <a href="{{ route('teachers.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
                @csrf
                @method('PUT')

                {{-- Name Row --}}
                <div class="form-row">
                    <div class="form-group" style="max-width:120px;">
                        <label class="form-label">Title <span style="color:var(--gray-400);font-weight:400;">(optional)</span></label>
                        <input type="text" name="title" class="form-input"
                               placeholder="Dr., Prof…"
                               value="{{ old('title', $teacher->user->title) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">First Name <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="first_name" class="form-input"
                               value="{{ old('first_name', $teacher->user->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name <span style="color:var(--gray-400);font-weight:400;">(optional)</span></label>
                        <input type="text" name="middle_name" class="form-input"
                               value="{{ old('middle_name', $teacher->user->middle_name) }}"
                               placeholder="Leave blank if none">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="last_name" class="form-input"
                               value="{{ old('last_name', $teacher->user->last_name) }}" required>
                    </div>
                </div>

                {{-- ID + Department --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Teacher ID</label>
                        <input type="text" name="teacher_id" class="form-input"
                               value="{{ old('teacher_id', $teacher->user->teacher_id) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-input"
                               value="{{ old('department', $teacher->user->department) }}">
                    </div>
                </div>

                {{-- Email + Username --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" class="form-input"
                               value="{{ old('email', $teacher->user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input"
                               value="{{ old('username', $teacher->user->username) }}">
                    </div>
                </div>

                {{-- Phone + Birthdate --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-input"
                               value="{{ old('phone', $teacher->user->phone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-input"
                               value="{{ old('birthdate', $teacher->user->birthdate?->format('Y-m-d')) }}">
                    </div>
                </div>

                {{-- Address --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-input"
                               value="{{ old('address', $teacher->user->address) }}">
                    </div>
                </div>

                {{-- Password (optional on edit) --}}
                <div style="border-top:1px solid var(--green-100);margin:18px 0 18px;padding-top:18px;">
                    <p style="font-size:.78rem;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Change Password</p>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:12px;">Leave blank to keep the current password.</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-input">
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection