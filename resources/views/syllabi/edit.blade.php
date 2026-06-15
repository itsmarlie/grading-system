@extends('layouts.app')
@section('title', 'Edit Syllabus')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Edit Syllabus</h2>
        <p>Update the syllabus for {{ $syllabus->course->course_name ?? 'this course' }}.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card" style="max-width:780px;">
        <div class="card-header">
            <span class="card-title">Syllabus Details</span>
            <a href="{{ route('syllabi.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('syllabi.update', $syllabus->id) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Course <span style="color:#dc2626;">*</span></label>
                        <select name="course_id" class="form-select" required>
                            <option value="">— Select Course —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ $syllabus->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->course_code ?? '' }} {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Title <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="title" class="form-input"
                               value="{{ old('title', $syllabus->title) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea">{{ old('description', $syllabus->description) }}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Course Overview</label>
                        <textarea name="course_overview" class="form-textarea" style="min-height:120px;">{{ old('course_overview', $syllabus->course_overview) }}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Learning Outcomes</label>
                        <textarea name="learning_outcomes" class="form-textarea" style="min-height:120px;">{{ old('learning_outcomes', $syllabus->learning_outcomes) }}</textarea>
                    </div>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                    <a href="{{ route('syllabi.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Syllabus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection