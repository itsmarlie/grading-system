@extends('layouts.app')
@section('title', 'Sections Management')

@section('content')
<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>Sections Management</h2>
            <p>Manage course sections and their weekly schedules.</p>
        </div>
        <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">+ Create Section</a>
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
                            <th>Section Name</th>
                            <th>Course</th>
                            <th>Schedule(s)</th>
                            <th>Max Students</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                        <tr>
                            <td class="td-name">{{ $section->name }}</td>
                            <td>{{ $section->course->name ?? $section->course->course_name ?? '—' }}</td>
                            <td style="font-size:.8rem;color:var(--gray-500);">
                                @forelse($section->schedules as $sched)
                                    <div>{{ $sched->day }} {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}–{{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                        @if($sched->room) <span style="color:var(--gray-400);">({{ $sched->room }})</span> @endif
                                    </div>
                                @empty
                                    <span style="color:var(--gray-300);">No schedule</span>
                                @endforelse
                            </td>
                            <td>{{ $section->max_students }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:6px;justify-content:center;">
                                    <a href="{{ route('admin.sections.show', $section->id) }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('admin.sections.edit', $section->id) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.sections.destroy', $section->id) }}"
                                          onsubmit="return confirm('Delete this section?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:32px;color:var(--gray-400);">
                                No sections yet. <a href="{{ route('admin.sections.create') }}" style="color:var(--green-600);">Create one now.</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sections->hasPages())
            <div class="pagination" style="margin-top:16px;">
                <span>Showing {{ $sections->firstItem() }}–{{ $sections->lastItem() }} of {{ $sections->total() }}</span>
                {{ $sections->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection