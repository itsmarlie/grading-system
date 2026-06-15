@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="page-header">
    <h2>Attendance Tracking</h2>
    <p>Select a course to take or view attendance.</p>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">📅 Select Course</span>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Teacher</th>
                        <th>Schedule</th>
                        <th>Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td><span class="badge badge-green">{{ $course->course_code }}</span></td>
                        <td class="td-name">{{ $course->course_name }}</td>
                        <td>{{ $course->teacher->name ?? '—' }}</td>
                        <td>{{ $course->schedule ?? '—' }}</td>
                        <td>{{ $course->students->count() }}</td>
                        <td>
                            <a href="{{ route('attendance.show', $course->id) }}"
                               class="btn btn-primary btn-sm">Take Attendance</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--gray-400);padding:32px;">
                            No courses found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection