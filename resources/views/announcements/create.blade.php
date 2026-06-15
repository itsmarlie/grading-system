@extends('layouts.app')
@section('title', 'Post Announcement')
@section('content')
<div class="page-header">
  <h2>📢 Post Announcement</h2>
  <p>Broadcast a notice to users.</p>
</div>

@if($errors->any())
  <div class="alert alert-danger">
    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
  </div>
@endif

<div class="card" style="max-width:680px;">
  <div class="card-header"><span class="card-title">New Announcement</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('announcements.store') }}">
      @csrf

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-input" value="{{ old('title') }}" required>
        </div>
        <div class="form-group" style="max-width:160px;">
          <label class="form-label">Category</label>
          <select name="category" class="form-select">
            @foreach(['General','Academic','Event'] as $cat)
              <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Visible To</label>
          <select name="visibility" class="form-select">
            <option value="all"      {{ old('visibility', 'all') === 'all'      ? 'selected' : '' }}>👥 Everyone</option>
            <option value="teachers" {{ old('visibility') === 'teachers'        ? 'selected' : '' }}>🧑‍🏫 Teachers Only</option>
            <option value="students" {{ old('visibility') === 'students'        ? 'selected' : '' }}>🎓 Students Only</option>
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Body</label>
        <textarea name="body" class="form-textarea" rows="6" required>{{ old('body') }}</textarea>
      </div>

      <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary">Post Announcement</button>
        <a href="{{ route('announcements.index') }}" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection