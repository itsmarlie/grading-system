@extends('layouts.app')
@section('title', 'Edit Assignment')
@section('content')
<div class="page-header">
  <h2>Edit Assignment</h2>
  <p>Update {{ $assignment->title }}.</p>
</div>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card" style="max-width:700px;">
  <div class="card-header"><span class="card-title">✏️ Edit Assignment</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('assignments.update', $assignment->id) }}">
      @csrf @method('PUT')

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Course *</label>
          <select name="course_id" class="form-select" required>
            @foreach($courses as $course)
              <option value="{{ $course->id }}"
                {{ old('course_id', $assignment->course_id) == $course->id ? 'selected' : '' }}>
                {{ $course->course_code }} — {{ $course->course_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Type *</label>
          <select name="type" class="form-select" required>
            @foreach(['written_work' => 'Written Work', 'performance_task' => 'Performance Task', 'quarterly_assessment' => 'Quarterly Assessment'] as $val => $label)
              <option value="{{ $val }}"
                {{ old('type', $assignment->type) === $val ? 'selected' : '' }}>
                {{ $label }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group" style="flex:2;">
          <label class="form-label">Title *</label>
          <input type="text" name="title" value="{{ old('title', $assignment->title) }}"
                 class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Total Points *</label>
          <input type="number" name="total_points"
                 value="{{ old('total_points', $assignment->total_points) }}"
                 class="form-input" min="1" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Term *</label>
          <select name="term" class="form-select" required>
            @foreach(['1st Semester','2nd Semester','Summer'] as $t)
              <option value="{{ $t }}"
                {{ old('term', $assignment->term) === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Due Date *</label>
          <input type="date" name="due_date"
                 value="{{ old('due_date', $assignment->due_date->format('Y-m-d')) }}"
                 class="form-input" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Section</label>
          <input type="text" name="section"
                 value="{{ old('section', $assignment->section) }}"
                 class="form-input" placeholder="e.g. BSIT-3A — leave blank for