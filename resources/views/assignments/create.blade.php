@extends('layouts.app')
@section('title', 'New Assignment')
@section('content')
<div class="page-header">
  <h2>Create Assignment</h2>
  <p>Add a new assignment to a course.</p>
</div>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card" style="max-width:700px;">
  <div class="card-header"><span class="card-title">Assignment Details</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('assignments.store') }}">
      @csrf

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Course *</label>
          <select name="course_id" class="form-select" required>
            <option value="">Select Course</option>
            @foreach($courses as $course)
              <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                {{ $course->course_code }} — {{ $course->course_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Type *</label>
          <select name="type" class="form-select" required>
            <option value="written_work"         {{ old('type') === 'written_work'         ? 'selected' : '' }}>Written Work</option>
            <option value="performance_task"     {{ old('type') === 'performance_task'     ? 'selected' : '' }}>Performance Task</option>
            <option value="quarterly_assessment" {{ old('type') === 'quarterly_assessment' ? 'selected' : '' }}>Quarterly Assessment</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group" style="flex:2;">
          <label class="form-label">Title *</label>
          <input type="text" name="title" value="{{ old('title') }}" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Total Points *</label>
          <input type="number" name="total_points" value="{{ old('total_points', 100) }}" class="form-input" min="1" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Term *</label>
          <select name="term" class="form-select" required>
            <option value="">Select Term</option>
            <option value="1st Semester" {{ old('term') === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
            <option value="2nd Semester" {{ old('term') === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
            <option value="Summer"       {{ old('term') === 'Summer'       ? 'selected' : '' }}>Summer</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Due Date *</label>
          <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-input" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Section</label>
          <input type="text" name="section" value="{{ old('section') }}"
                 class="form-input" placeholder="e.g. BSIT-3A — leave blank for all">
        </div>
        <div class="form-group">
          <label class="form-label">Status *</label>
          <select name="status" class="form-select" required>
            <option value="open"   {{ old('status', 'open') === 'open'   ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ old('status') === 'closed'         ? 'selected' : '' }}>Closed</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-textarea" rows="4"
                    placeholder="Assignment instructions...">{{ old('description') }}</textarea>
        </div>
      </div>

      <div style="display:flex;gap:10px;margin-top:8px;">
        <button type="submit" class="btn btn-primary">Create Assignment</button>
        <a href="{{ route('assignments.index') }}" class="btn btn-outline">Cancel</a>
      </div>

    </form>
  </div>
</div>
@endsection