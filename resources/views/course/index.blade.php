@extends('layouts.app')

@section('title', 'Course Management')

@section('content')
<div class="page-header">
    <h2>Course Management</h2>
    <p>Manage all courses and their assigned teachers.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">📖 All Courses</span>
        <a href="{{ route('courses.create') }}" class="btn btn-primary btn-sm">+ Add Course</a>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Teacher</th>
                        <th>Units</th>
                        <th>Schedule</th>
                        <th>Room</th>
                        <th>Term</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td><span class="badge badge-green">{{ $course->course_code }}</span></td>
                        <td class="td-name">{{ $course->course_name }}</td>
                        <td>{{ $course->teacher->name ?? '—' }}</td>
                        <td>{{ $course->units }}</td>
                        <td>{{ $course->schedule ?? '—' }}</td>
                        <td>{{ $course->room ?? '—' }}</td>
                        <td>{{ $course->term ?? '—' }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('courses.show', $course->id) }}"
                                   class="btn btn-outline btn-sm">View</a>
                                <a href="{{ route('courses.edit', $course->id) }}"
                                   class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST" action="{{ route('courses.destroy', $course->id) }}"
                                      onsubmit="return confirm('Delete this course?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--gray-400);padding:32px;">
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