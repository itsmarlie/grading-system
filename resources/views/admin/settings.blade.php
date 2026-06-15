@extends('layouts.app')
@section('title', 'System Settings')
@section('content')

<div class="page-header">
    <h2>⚙️ System Settings</h2>
    <p>Control semester information shown to all users</p>
</div>

@if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

<div class="card" style="max-width:560px;">
    <div class="card-header">
        <div class="card-title">Semester Configuration</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Active Semester</label>
                    <select name="active_semester" class="form-select">
                        <option value="1st Semester" {{ $activeSemester === '1st Semester' ? 'selected' : '' }}>
                            1st Semester
                        </option>
                        <option value="2nd Semester" {{ $activeSemester === '2nd Semester' ? 'selected' : '' }}>
                            2nd Semester
                        </option>
                        <option value="Summer" {{ $activeSemester === 'Summer' ? 'selected' : '' }}>
                            Summer
                        </option>
                    </select>
                    @error('active_semester')
                        <div style="color:#991b1b;font-size:.78rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">School Year</label>
                    <input type="text" name="active_school_year" class="form-input"
                        value="{{ old('active_school_year', $activeSchoolYear) }}"
                        placeholder="e.g. 2025-2026">
                    @error('active_school_year')
                        <div style="color:#991b1b;font-size:.78rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="background:var(--green-50);border:1px solid var(--green-100);border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:16px;font-size:.82rem;color:var(--green-800);">
                📌 Preview: <strong id="livePreview">{{ $activeSemester }} • A.Y. {{ $activeSchoolYear }}</strong>
                &nbsp;—&nbsp;this is what will appear in the header banner for all users.
            </div>

            <button type="submit" class="btn btn-primary">💾 Save Settings</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live preview update
    const semesterSelect = document.querySelector('select[name="active_semester"]');
    const schoolYearInput = document.querySelector('input[name="active_school_year"]');
    const preview = document.querySelector('[data-preview]');

    function updatePreview() {
        const sem = semesterSelect?.value ?? '';
        const sy  = schoolYearInput?.value ?? '';
        const el  = document.getElementById('livePreview');
        if (el) el.textContent = sem + ' • A.Y. ' + sy;
    }

    semesterSelect?.addEventListener('change', updatePreview);
    schoolYearInput?.addEventListener('input', updatePreview);
</script>
@endpush