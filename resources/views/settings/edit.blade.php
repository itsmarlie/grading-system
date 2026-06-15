@extends('layouts.app')
@section('title', 'Settings')
@section('content')
<div class="page-header">
  <h2>⚙️ System Settings</h2>
  <p>Manage school-wide system settings.</p>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="max-width:500px;">
  <div class="card-header"><span class="card-title">Current Semester</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('settings.update') }}">
      @csrf
      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Semester Display Label</label>
        <input type="text" name="current_semester" class="form-input"
               value="{{ old('current_semester', $semester) }}"
               placeholder="e.g. S.Y. 2025-2026 · Sem 2" required>
        <p style="font-size:.75rem;color:var(--gray-400);margin-top:4px;">
          This appears in the top bar across the entire system.
        </p>
      </div>
      <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
  </div>
</div>
@endsection