@extends('layouts.app')
@section('title', 'My Attendance')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>My Attendance</h2>
        <p>Attendance records for {{ $student->display_name }}.</p>
    </div>

    {{-- Overall Summary --}}
    <div class="stats-grid" style="margin-bottom:24px;">
        <div class="stat-card">
            <div class="stat-icon green">📅</div>
            <div>
                <div class="stat-label">Overall Attendance Rate</div>
                <div class="stat-value">{{ $overallRate ?? '—' }}{{ $overallRate ? '%' : '' }}</div>
                <div class="stat-change">Across all courses</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal">✅</div>
            <div>
                <div class="stat-label">Total Present</div>
                <div class="stat-value">{{ $allRecords->whereIn('status',['present','late'])->count() }}</div>
                <div class="stat-change">Present + Late</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon lime">❌</div>
            <div>
                <div class="stat-label">Total Absent</div>
                <div class="stat-value">{{ $allRecords->where('status','absent')->count() }}</div>
                <div class="stat-change">Unexcused absences</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">📋</div>
            <div>
                <div class="stat-label">Total Sessions</div>
                <div class="stat-value">{{ $allRecords->count() }}</div>
                <div class="stat-change">All recorded sessions</div>
            </div>
        </div>
    </div>

    @if($courseAttendance->isEmpty())
        <div class="card">
            <div class="card-body" style="text-align:center;padding:48px;color:var(--gray-400);">
                No attendance records found.
            </div>
        </div>
    @endif

    @foreach($courseAttendance as $ca)
    <div class="card" style="margin-bottom:24px;">
        <div class="card-header" style="flex-wrap:wrap;gap:10px;">
            <div>
                <span class="card-title">{{ $ca['course']->course_name }}</span>
                <div style="font-size:.78rem;color:var(--gray-400);margin-top:3px;">
                    {{ $ca['course']->course_code ?? '' }}
                </div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                @if($ca['rate'] !== null)
                    <div style="text-align:center;">
                        <div style="font-size:1.3rem;font-weight:700;
                            color:{{ $ca['rate'] >= 80 ? 'var(--green-700)' : '#dc2626' }};">
                            {{ $ca['rate'] }}%
                        </div>
                        <div style="font-size:.7rem;color:var(--gray-400);">Attendance Rate</div>
                    </div>
                @endif
                <span class="badge badge-green">{{ $ca['present'] }} Present</span>
                <span class="badge badge-amber">{{ $ca['late'] }} Late</span>
                <span class="badge badge-red">{{ $ca['absent'] }} Absent</span>
                @if($ca['excused'])
                    <span class="badge badge-blue">{{ $ca['excused'] }} Excused</span>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if($ca['records']->isEmpty())
                <p style="color:var(--gray-400);font-size:.85rem;">No records yet.</p>
            @else

            {{-- Progress bar --}}
            @if($ca['rate'] !== null)
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:4px;">
                    <span style="color:var(--gray-500);">Attendance rate</span>
                    <span style="font-weight:600;color:var(--green-700);">{{ $ca['rate'] }}%</span>
                </div>
                <div class="progress-bg">
                    <div class="progress-fill"
                         style="width:{{ $ca['rate'] }}%;
                         background:{{ $ca['rate'] >= 80 ? 'var(--green-500)' : '#fca5a5' }};">
                    </div>
                </div>
                @if($ca['rate'] < 80)
                    <div style="font-size:.72rem;color:#dc2626;margin-top:4px;">
                        ⚠️ Below 80% attendance threshold
                    </div>
                @endif
            </div>
            @endif

            {{-- Records table --}}
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th style="text-align:center;">Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ca['records'] as $record)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y') }}</td>
                            <td style="color:var(--gray-400);">
                                {{ \Carbon\Carbon::parse($record->created_at)->format('l') }}
                            </td>
                            <td style="text-align:center;">
                                @php
                                    $statusClass = match($record->status) {
                                        'present' => 'badge-green',
                                        'absent'  => 'badge-red',
                                        'late'    => 'badge-amber',
                                        'excused' => 'badge-blue',
                                        default   => 'badge-gray',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td style="font-size:.78rem;color:var(--gray-400);">
                                {{ $record->remarks ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection