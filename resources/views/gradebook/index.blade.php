@extends('layouts.app')
@section('title', 'Gradebook')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Gradebook</h2>
        <p>Select a course below to view and enter student grades.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="card-title">Available Courses</span>
            <span style="font-size:.78rem;color:var(--gray-400);">{{ $courses->count() }} course(s)</span>
        </div>
        <div class="card-body" style="padding-top:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Teacher</th>
                            <th>Term</th>
                            <th>Students</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td>
                                <span class="badge badge-green">{{ $course->course_code ?? '—' }}</span>
                            </td>
                            <td class="td-name">{{ $course->course_name }}</td>
                            <td>
                                @if($course->teacher)
                                    {{ $course->teacher->display_name ?? $course->teacher->name }}
                                @else
                                    <span style="color:var(--gray-300);">Unassigned</span>
                                @endif
                            </td>
                            <td>{{ $course->term ?? '—' }}</td>
                            <td>
                                <span class="badge badge-gray">{{ $course->students->count() }} students</span>
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('gradebook.show', $course->id) }}"
                                   class="btn btn-primary btn-sm">
                                    Open Gradebook
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--gray-400);padding:40px;">
                                @if(Auth::user()->isAdmin())
                                    No courses found. <a href="{{ route('courses.create') }}" style="color:var(--green-600);">Create a course.</a>
                                @else
                                    You have no assigned courses yet.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection