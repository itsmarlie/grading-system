@extends('layouts.app')
@section('content')
<div class="container py-4" style="max-width:600px">
    <h4 class="fw-bold mb-4"><i class="fas fa-cog me-2"></i>System Settings</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header fw-semibold">Active Semester</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-medium">Semester</label>
                    <select name="active_semester" class="form-select">
                        @foreach(['1st Semester','2nd Semester','Summer'] as $sem)
                            <option value="{{ $sem }}" @selected($activeSemester === $sem)>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">School Year <small class="text-muted">(e.g. 2025-2026)</small></label>
                    <input type="text" name="active_school_year" class="form-control" value="{{ $activeSchoolYear }}" pattern="\d{4}-\d{4}" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection