@extends('layouts.app')
@section('title', 'Add Student')
@section('content')
<div class="page-header">
  <h2>Add New Student</h2>
  <p>Fill in the student's personal and academic details.</p>
</div>

@if($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card" style="max-width:740px;">
  <div class="card-header"><span class="card-title">👥 Student Information</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('students.store') }}">
      @csrf

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">First Name *</label>
          <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Last Name *</label>
          <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-input" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Student Number *</label>
          <input type="text" name="student_number" value="{{ old('student_number') }}" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">Select Gender</option>
            @foreach(['Male','Female','Other'] as $g)
              <option value="{{ $g }}" {{ old('gender') === $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Program / Course *</label>
          <select name="course_id" class="form-select" required>
            <option value="">Select Program</option>
            @foreach($courses as $course)
              <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                {{ $course->course_code }} — {{ $course->course_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Year Level *</label>
          <select name="year_level" class="form-select" required>
            <option value="">Select Year</option>
            @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year','Irregular'] as $yr)
              <option value="{{ $yr }}" {{ old('year_level') === $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Section</label>
          <input type="text" name="section" value="{{ old('section') }}"
                 class="form-input" placeholder="e.g. BSIT-3A">
        </div>
        <div class="form-group">
          <label class="form-label">Term *</label>
          <select name="term" class="form-select" required>
            <option value="">Select Term</option>
            @foreach(['1st Semester','2nd Semester','Summer'] as $t)
              <option value="{{ $t }}" {{ old('term') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Status *</label>
          <select name="status" class="form-select" required>
            @foreach(['active','inactive','graduated'] as $s)
              <option value="{{ $s }}" {{ old('status', 'active') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Birthdate</label>
          <input type="date" name="birthdate" value="{{ old('birthdate') }}" class="form-input">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Address</label>
          <input type="text" name="address" value="{{ old('address') }}" class="form-input">
        </div>
      </div>

      {{-- Account Access --}}
      <div style="background:var(--green-50);border:1.5px solid var(--green-200);border-radius:var(--radius-sm);padding:16px;margin-bottom:16px;">
        <p style="font-size:.8rem;font-weight:600;color:var(--green-800);margin-bottom:10px;">🔐 Student Account Access</p>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:12px;">
          A login account will be created automatically. Set a username and temporary password — the student must change their password on first login.
        </p>
        <div class="form-row" style="margin-bottom:0;">
          <div class="form-group">
            <label class="form-label">Username *</label>
            <input type="text" name="username" value="{{ old('username') }}"
                   class="form-input" required placeholder="e.g. juan.dela.cruz">
          </div>
          <div class="form-group">
            <label class="form-label">Temporary Password *</label>
            <input type="text" name="temp_password" value="{{ old('temp_password') }}"
                   class="form-input" required minlength="6"
                   placeholder="Give this to the student">
          </div>
        </div>
      </div>

      <div style="display:flex;gap:10px;margin-top:8px;">
        <button type="submit" class="btn btn-primary">Save Student</button>
        <a href="{{ route('students.index') }}" class="btn btn-outline">Cancel</a>
      </div>

    </form>
  </div>
</div>
@endsection