@extends('layouts.app')
@section('title', 'Announcements')
@section('content')

<div class="page-header">
  <h2>📢 Announcements</h2>
  <p>School-wide notices and updates.</p>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-header">
    <span class="card-title">All Announcements</span>
    @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
      <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm">+ Post Announcement</a>
    @endif
  </div>
  <div class="card-body">
    <div class="announce-list">
      @forelse($announcements as $ann)
      <div class="announce-item">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
          <div style="flex:1;">
            <h4>
              📢 {{ $ann->title }}
              <span class="badge badge-blue" style="font-size:.65rem;margin-left:6px;">{{ $ann->category }}</span>
              @if($ann->visibility !== 'all')
                <span class="badge badge-purple" style="font-size:.65rem;margin-left:4px;">
                  {{ $ann->visibility === 'teachers' ? '🧑‍🏫 Teachers Only' : '🎓 Students Only' }}
                </span>
              @endif
            </h4>
            <p style="margin-top:4px;">{{ Str::limit($ann->body, 160) }}</p>
            <div class="announce-meta">
              Posted by {{ $ann->author->name }} · {{ $ann->created_at->format('F j, Y') }}
            </div>
          </div>
          <div style="display:flex;gap:6px;flex-shrink:0;">
            <a href="{{ route('announcements.show', $ann->id) }}" class="btn btn-outline btn-sm">View</a>
            @can('update', $ann)
              <a href="{{ route('announcements.edit', $ann->id) }}" class="btn btn-outline btn-sm">Edit</a>
              <form method="POST" action="{{ route('announcements.destroy', $ann->id) }}"
                    onsubmit="return confirm('Delete this announcement?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            @endcan
          </div>
        </div>
      </div>
      @empty
      <p style="color:var(--gray-400);text-align:center;padding:32px 0;">No announcements yet.</p>
      @endforelse
    </div>

    <div style="margin-top:20px;">
      {{ $announcements->links() }}
    </div>
  </div>
</div>
@endsection