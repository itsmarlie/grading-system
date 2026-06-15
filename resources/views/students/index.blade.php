@extends('layouts.app')

@section('title', 'Student Management')

@section('content')
<div class="page-header">
    <h2>Student Management</h2>
    <p>Manage all enrolled students for this school year.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">👥 All Students</span>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">+ Add Student</a>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Term</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->student_number }}</td>
                        <td class="td-name">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->course->course_name ?? $student->course }}</td>
                        <td>{{ $student->year_level }}</td>
                        <td>{{ $student->term }}</td>
                        <td>
                            @if($student->status === 'active')
                                <span class="badge badge-green">Active</span>
                            @elseif($student->status === 'inactive')
                                <span class="badge badge-gray">Inactive</span>
                            @else
                                <span class="badge badge-blue">Graduated</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('students.show', $student->id) }}"
                                   class="btn btn-outline btn-sm">View</a>
                                <a href="{{ route('students.edit', $student->id) }}"
                                   class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST" action="{{ route('students.destroy', $student->id) }}"
                                      onsubmit="return confirm('Delete this student?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--gray-400);padding:32px;">
                            No students found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection