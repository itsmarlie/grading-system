@extends('layouts.app')

@section('title', 'Assignment Management')

@section('content')
<div class="page-header">
    <h2>Assignment Management</h2>
    <p>Create and manage assignments across all courses.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">All Assignments</span>
        <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm">+ New Assignment</a>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Type</th>
                        <th>Term</th>
                        <th>Total Points</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td class="td-name">{{ $assignment->title }}</td>
                        <td>{{ $assignment->course->course_name }}</td>
                        <td>
                            @php
                                $typeColors = [
                                    'written_work' => 'badge-blue',
                                    'performance_task' => 'badge-purple',
                                    'quarterly_assessment' => 'badge-amber',
                                ];
                            @endphp
                            <span class="badge {{ $typeColors[$assignment->type] ?? 'badge-gray' }}">
                                {{ ucfirst(str_replace('_', ' ', $assignment->type)) }}
                            </span>
                        </td>
                        <td>{{ $assignment->term }}</td>
                        <td>{{ $assignment->total_points }}</td>
                        <td>{{ $assignment->due_date->format('M j, Y') }}</td>
                        <td>
                            @if($assignment->status === 'open')
                                <span class="badge badge-green">Open</span>
                            @else
                                <span class="badge badge-gray">Closed</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('assignments.edit', $assignment->id) }}"
                                   class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST" action="{{ route('assignments.destroy', $assignment->id) }}"
                                      onsubmit="return confirm('Delete this assignment?')">
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
                            No assignments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection