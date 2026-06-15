@extends('layouts.app')

@section('title', 'Attendance — ' . $course->course_name)

@section('content')
<div class="page-header">
    <h2>{{ $course->course_name }} — Attendance</h2>
    <p>{{ $course->course_code }} · {{ $course->term }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Take Attendance Form --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">✏️ Take Attendance</span>
    </div>
    <div class="card-body">
        @if($students->isEmpty())
            <p style="color:var(--gray-400);text-align:center;padding:24px;">
                No students enrolled in this course.
            </p>
        @else
        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="form-row" style="max-width:300px;margin-bottom:20px;">
                <div class="form-group">
                    <label class="form-label">Attendance Date *</label>
                    <input type="date" name="attendance_date"
                        value="{{ date('Y-m-d') }}"
                        class="form-input" required>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Student No.</th>
                            <th style="text-align:center;">Present</th>
                            <th style="text-align:center;">Late</th>
                            <th style="text-align:center;">Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td class="td-name">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->student_number }}</td>
                            <td style="text-align:center;">
                                <input type="radio"
                                    name="attendance[{{ $student->id }}]"
                                    value="present" checked>
                            </td>
                            <td style="text-align:center;">
                                <input type="radio"
                                    name="attendance[{{ $student->id }}]"
                                    value="late">
                            </td>
                            <td style="text-align:center;">
                                <input type="radio"
                                    name="attendance[{{ $student->id }}]"
                                    value="absent">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:16px;">
                <button type="submit" class="btn btn-primary">💾 Save Attendance</button>
            </div>
        </form>
        @endif
    </div>
</div>

{{-- Attendance History --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">📋 Attendance History</span>
    </div>
    <div class="card-body">
        @if($dates->isEmpty())
            <p style="color:var(--gray-400);text-align:center;padding:24px;">
                No attendance records yet.
            </p>
        @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        @foreach($dates as $date)
                        <th style="text-align:center;font-size:.7rem;">
                            {{ \Carbon\Carbon::parse($date)->format('M j') }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td class="td-name">{{ $student->first_name }} {{ $student->last_name }}</td>
                        @foreach($dates as $date)
                        @php $status = $attendance[$student->id][$date->format('Y-m-d')] ?? null; @endphp
                        <td style="text-align:center;">
                            @if($status === 'present')
                                <span style="color:var(--green-600);font-weight:700;">P</span>
                            @elseif($status === 'absent')
                                <span style="color:#dc2626;font-weight:700;">A</span>
                            @elseif($status === 'late')
                                <span style="color:#d97706;font-weight:700;">L</span>
                            @else
                                <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<div style="margin-top:16px;">
    <a href="{{ route('attendance.index') }}" class="btn btn-outline">← Back to Courses</a>
</div>
@endsection