@extends('layouts.app')
@section('title', 'Syllabi')

@section('content')
<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>Syllabi</h2>
            <p>Manage course syllabi for students to view.</p>
        </div>
        <a href="{{ route('syllabi.create') }}" class="btn btn-primary">+ New Syllabus</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body" style="padding-top:22px;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Created By</th>
                            <th>Date</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($syllabi as $syllabus)
                        <tr>
                            <td class="td-name">{{ $syllabus->title }}</td>
                            <td>
                                <div>{{ $syllabus->course->course_name ?? '—' }}</div>
                                <div style="font-size:.72rem;color:var(--gray-400);">
                                    {{ $syllabus->course->course_code ?? '' }}
                                </div>
                            </td>
                            <td>{{ $syllabus->creator->display_name ?? $syllabus->creator->name ?? '—' }}</td>
                            <td style="font-size:.82rem;color:var(--gray-400);">
                                {{ $syllabus->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:6px;justify-content:center;">
                                    <a href="{{ route('syllabi.show', $syllabus->id) }}"
                                       class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('syllabi.edit', $syllabus->id) }}"
                                       class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('syllabi.destroy', $syllabus->id) }}"
                                          onsubmit="return confirm('Delete this syllabus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:40px;color:var(--gray-400);">
                                No syllabi yet. <a href="{{ route('syllabi.create') }}"
                                style="color:var(--green-600);">Create one.</a>
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