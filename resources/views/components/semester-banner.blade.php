@php
    $semester   = \App\Models\Setting::get('active_semester', '1st Semester');
    $schoolYear = \App\Models\Setting::get('active_school_year', '2025-2026');
@endphp
<div class="semester-banner bg-primary text-white text-center py-1 small fw-semibold">
    {{ $semester }} &bull; A.Y. {{ $schoolYear }}
</div>