@extends('layouts.app')
@section('title', 'Section Details')
@section('content')

<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>Section: {{ $section->name }}</h2>
            <p>{{ $section->course->course_code ?? '' }} — {{ $section->course->course_name ?? 'Unknown Course' }}</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('admin.sections.students.assign', $section->id) }}" class="btn btn-primary btn-sm">👥 Assign Students</a>
            <a href="{{ route('admin.sections.edit', $section->id) }}" class="btn btn-outline btn-sm">✏️ Edit</a>
            <a href="{{ route('admin.sections.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- Section Info --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
        <div class="card">
            <div class="card-header"><span class="card-title">📋 Section Info</span></div>
            <div class="card-body">
                <table style="width:100%;font-size:.88rem;border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 0;color:var(--gray-500);width:140px;">Section Name</td>
                        <td style="padding:6px 0;font-weight:600;">{{ $section->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:var(--gray-500);">Course</td>
                        <td style="padding:6px 0;">{{ $section->course->course_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:var(--gray-500);">Max Students</td>
                        <td style="padding:6px 0;">{{ $section->max_students }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:var(--gray-500);">Enrolled</td>
                        <td style="padding:6px 0;">
                            <span style="font-weight:600;color:{{ $section->students->count() >= $section->max_students ? '#dc2626' : 'var(--green-600)' }};">
                                {{ $section->students->count() }}
                            </span>
                            / {{ $section->max_students }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">🗓️ Schedules</span></div>
            <div class="card-body">
                @forelse($section->schedules as $sched)
                <div style="padding:8px 0;border-bottom:1px solid var(--green-100);font-size:.88rem;">
                    <strong>{{ $sched->day }}</strong>
                    {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}
                    – {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                    @if($sched->room)
                        <span style="color:var(--gray-400);">({{ $sched->room }})</span>
                    @endif
                </div>
                @empty
                <p style="color:var(--gray-400);font-size:.88rem;">No schedules set.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Students List --}}
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span class="card-title">👥 Assigned Students ({{ $section->students->count() }})</span>
            <a href="{{ route('admin.sections.students.assign', $section->id) }}" class="btn btn-primary btn-sm">+ Assign Students</a>
        </div>
        <div class="card-body">
            @if($section->students->isEmpty())
                <div style="text-align:center;padding:32px;color:var(--gray-400);">
                    No students assigned yet.
                    <a href="{{ route('admin.sections.students.assign', $section->id) }}" style="color:var(--green-600);">Assign students →</a>
                </div>
            @else
                <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--green-100);">
                            <th style="text-align:left;padding:8px 10px;color:var(--gray-500);font-weight:600;">#</th>
                            <th style="text-align:left;padding:8px 10px;color:var(--gray-500);font-weight:600;">Name</th>
                            <th style="text-align:left;padding:8px 10px;color:var(--gray-500);font-weight:600;">Student No.</th>
                            <th style="text-align:left;padding:8px 10px;color:var(--gray-500);font-weight:600;">Year Level</th>
                            <th style="text-align:left;padding:8px 10px;color:var(--gray-500);font-weight:600;">Type</th>
                            <th style="text-align:left;padding:8px 10px;color:var(--gray-500);font-weight:600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section->students as $i => $student)
                        <tr style="border-bottom:1px solid var(--green-50);">
                            <td style="padding:8px 10px;color:var(--gray-400);">{{ $i + 1 }}</td>
                            <td style="padding:8px 10px;font-weight:500;">
                                <a href="{{ route('students.show', $student->id) }}" style="color:var(--green-700);text-decoration:none;">
                                    {{ $student->display_name }}
                                </a>
                            </td>
                            <td style="padding:8px 10px;color:var(--gray-600);">{{ $student->student_number ?? '—' }}</td>
                            <td style="padding:8px 10px;color:var(--gray-600);">{{ $student->year_level ?? '—' }}</td>
                            <td style="padding:8px 10px;">
                                <span style="font-size:.72rem;padding:2px 8px;border-radius:20px;background:{{ $student->pivot->student_type === 'irregular' ? '#fef3c7' : 'var(--green-100)' }};color:{{ $student->pivot->student_type === 'irregular' ? '#92400e' : 'var(--green-700)' }};">
                                    {{ ucfirst($student->pivot->student_type ?? 'regular') }}
                                </span>
                            </td>
                            <td style="padding:8px 10px;">
                                <span style="font-size:.72rem;padding:2px 8px;border-radius:20px;
                                    background:{{ $student->status === 'active' ? 'var(--green-100)' : '#fee2e2' }};
                                    color:{{ $student->status === 'active' ? 'var(--green-700)' : '#dc2626' }};">
                                    {{ ucfirst($student->status ?? 'active') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection