@extends('layouts.app')

@section('title', 'Student Profile')

@section('content')
<div class="page-header">
    <h2>{{ $student->first_name }} {{ $student->last_name }}</h2>
    <p>Student No: {{ $student->student_number }} · {{ $student->course->course_name ?? $student->course }}</p>
</div>

<div class="grid-2">
    {{-- Personal Info --}}
    <div class="card">
        <div class="card-header"><span class="card-title">👤 Personal Information</span></div>
        <div class="card-body">
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Full Name</span>
                <span style="font-weight:500;">{{ $student->first_name }} {{ $student->last_name }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Gender</span>
                <span style="font-weight:500;">{{ $student->gender ?? '—' }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Birthdate</span>
                <span style="font-weight:500;">{{ $student->birthdate?->format('F j, Y') ?? '—' }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Phone</span>
                <span style="font-weight:500;">{{ $student->phone ?? '—' }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;">
                <span style="color:var(--gray-500);">Address</span>
                <span style="font-weight:500;">{{ $student->address ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- Academic Info --}}
    <div class="card">
        <div class="card-header"><span class="card-title">📖 Academic Information</span></div>
        <div class="card-body">
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Student Number</span>
                <span style="font-weight:500;">{{ $student->student_number }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Course</span>
                <span style="font-weight:500;">{{ $student->course->course_name ?? $student->course }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Year Level</span>
                <span style="font-weight:500;">{{ $student->year_level }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Term</span>
                <span style="font-weight:500;">{{ $student->term }}</span>
            </div>
            <div class="detail-row" style="display:flex;justify-content:space-between;padding:8px 0;">
                <span style="color:var(--gray-500);">Status</span>
                @if($student->status === 'active')
                    <span class="badge badge-green">Active</span>
                @elseif($student->status === 'inactive')
                    <span class="badge badge-gray">Inactive</span>
                @else
                    <span class="badge badge-blue">Graduated</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Grades --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">📊 Grades</span></div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Type</th>
                        <th>Term</th>
                        <th>Score</th>
                        <th>Total</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->grades as $grade)
                    <tr>
                        <td class="td-name">{{ $grade->assignment->title }}</td>
                        <td><span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $grade->assignment->type)) }}</span></td>
                        <td>{{ $grade->term }}</td>
                        <td>{{ $grade->score ?? '—' }}</td>
                        <td>{{ $grade->assignment->total_points }}</td>
                        <td>
                            @if($grade->score !== null)
                                {{ number_format(($grade->score / $grade->assignment->total_points) * 100, 1) }}%
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--gray-400);padding:24px;">
                            No grades recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Attendance --}}
<div class="card">
    <div class="card-header"><span class="card-title">📅 Attendance Record</span></div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->attendances as $att)
                    <tr>
                        <td>{{ $att->attendance_date->format('F j, Y') }}</td>
                        <td>
                            @if($att->status === 'present')
                                <span class="badge badge-green">Present</span>
                            @elseif($att->status === 'absent')
                                <span class="badge badge-red">Absent</span>
                            @else
                                <span class="badge badge-amber">Late</span>
                            @endif
                        </td>
                        <td>{{ $att->remarks ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:var(--gray-400);padding:24px;">
                            No attendance records yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:20px;display:flex;gap:10px;">
    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">Edit Student</a>
    <a href="{{ route('students.index') }}" class="btn btn-outline">Back to List</a>
</div>
@endsection