@extends('layouts.app')
@section('title', 'Teachers')

@section('content')
<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>Teacher Management</h2>
            <p>View and manage all registered teachers.</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">+ Add Teacher</a>
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
                            <th>Name</th>
                            <th>Teacher ID</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td class="td-name">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:34px;height:34px;border-radius:50%;background:var(--green-100);color:var(--green-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0;">
                                        {{ strtoupper(substr($teacher->user->first_name ?? $teacher->user->name, 0, 1)) }}{{ strtoupper(substr($teacher->user->last_name ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:var(--green-900);">
                                            {{ $teacher->user->display_name ?? $teacher->user->name }}
                                        </div>
                                        <div style="font-size:.72rem;color:var(--gray-400);">
                                            {{ '@' . ($teacher->user->username ?? '—') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $teacher->user->teacher_id ?? '—' }}</td>
                            <td>{{ $teacher->user->department ?? '—' }}</td>
                            <td>{{ $teacher->user->email }}</td>
                            <td>{{ $teacher->user->phone ?? '—' }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:6px;justify-content:center;">
                                    <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-outline btn-sm">View</a>
                                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('teachers.destroy', $teacher->id) }}"
                                          onsubmit="return confirm('Delete this teacher?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--gray-400);padding:32px;">
                                No teachers found. <a href="{{ route('teachers.create') }}" style="color:var(--green-600);">Add one now.</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($teachers instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="pagination">
                <span>Showing {{ $teachers->firstItem() }}–{{ $teachers->lastItem() }} of {{ $teachers->total() }}</span>
                {{ $teachers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection