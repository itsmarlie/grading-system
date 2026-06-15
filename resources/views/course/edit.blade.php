@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<div class="page-header">
    <h2>Edit Course</h2>
    <p>Update {{ $course->course_name }} details.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card" style="max-width:700px;">
    <div class="card-header">
        <span class="card-title">✏️ Edit Course Information</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('courses.update', $course->id) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Course Code *</label>
                    <input type="text" name="course_code"
                        value="{{ old('course_code', $course->course_code) }}"
                        class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Course Name *</label>
                    <input type="text" name="course_name"
                        value="{{ old('course_name', $course->course_name) }}"
                        class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Assigned Teacher *</label>
                    <select name="teacher_id" class="form-select" required>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Units *</label>
                    <input type="number" name="units"
                        value="{{ old('units', $course->units) }}"
                        class="form-input" min="1" max="6" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Schedule</label>
                    <input type="text" name="schedule"
                        value="{{ old('schedule', $course->schedule) }}"
                        class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Room</label>
                    <input type="text" name="room"
                        value="{{ old('room', $course->room) }}"
                        class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Term *</label>
                    <select name="term" class="form-select" required>
                        @foreach(['1st Semester','2nd Semester','Summer'] as $t)
                            <option value="{{ $t }}"
                                {{ old('term', $course->term) === $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">School Year *</label>
                    <input type="text" name="school_year"
                        value="{{ old('school_year', $course->school_year) }}"
                        class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea">{{ old('description', $course->description) }}</textarea>
                </div>
            </div>

            <div style="background:var(--green-50);border-radius:var(--radius-sm);padding:16px;margin-bottom:14px;">
                <p style="font-size:.8rem;font-weight:600;color:var(--green-800);margin-bottom:12px;">
                    📊 Grading Weights (must total 100%)
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Written Work %</label>
                        <input type="number" name="ww_weight"
                            value="{{ old('ww_weight', $course->ww_weight) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Performance Task %</label>
                        <input type="number" name="pt_weight"
                            value="{{ old('pt_weight', $course->pt_weight) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quarterly Assessment %</label>
                        <input type="number" name="qa_weight"
                            value="{{ old('qa_weight', $course->qa_weight) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Update Course</button>
                <a href="{{ route('courses.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection