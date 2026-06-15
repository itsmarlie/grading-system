@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="page-header">
  <h2>📢 {{ $announcement->title }}</h2>
  <p>Posted by {{ $announcement->author->name }} · {{ $announcement->created_at->format('F j, Y') }}</p>
</div>

<div class="card" style="max-width:720px;">
  <div class="card-header">
    <span class="card-title">{{ $announcement->title }}</span>
    <span class="badge badge-blue">{{ $announcement->category }}</span>
  </div>
  <div class="card-body">
    <p style="line-height:1.75;color:var(--gray-600);white-space:pre-line;">{{ $announcement->body }}</p>
    <div style="margin-top:20px;display:flex;gap:10px;">
      <a href="{{ route('announcements.index') }}" class="btn btn-outline">← Back</a>
      @can('update', $announcement)
        <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-primary">Edit</a>
      @endcan
    </div>
  </div>
</div>
@endsection