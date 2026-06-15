@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

@if(auth()->user()->force_password_change)
<div class="alert alert-danger" style="margin-bottom:20px;">
  ⚠️ You are using a temporary password. Please change it below before continuing.
</div>
@endif

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('warning'))
  <div class="alert alert-danger">{{ session('warning') }}</div>
@endif

<div class="page-header">
  <h2>👤 My Profile</h2>
  <p>Manage your personal information and password.</p>
</div>

<div class="grid-2" style="align-items:start;">

  {{-- LEFT: Avatar + Info --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Personal Information</span></div>
    <div class="card-body">

      {{-- Avatar --}}
      <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:24px;gap:12px;">
        @if($user->avatar)
          <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
               style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--green-400);">
        @else
          <div style="width:90px;height:90px;border-radius:50%;background:var(--green-600);display:flex;align-items:center;justify-content:center;font-size:2rem;color:white;border:3px solid var(--green-400);">
            {{ strtoupper(substr($user->name, 0, 2)) }}
          </div>
        @endif
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
          @csrf @method('PATCH')
          <input type="file" name="avatar" id="avatar-input" accept="image/*" style="display:none;"
                 onchange="this.form.submit()">
          <label for="avatar-input" class="btn btn-outline btn-sm" style="cursor:pointer;">
            📷 Change Photo
          </label>
        </form>
      </div>

      <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PATCH')

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
          </div>
          <div class="form-group">
            <label class="form-label">Birthdate</label>
            <input type="date" name="birthdate" class="form-input"
                   value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-input" value="{{ old('address', $user->address) }}">
          </div>
        </div>

        <div style="margin-top:8px;">
          <button type="submit" class="btn btn-primary">Save Profile</button>
        </div>
      </form>

      {{-- Read-only role info --}}
      <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--green-100);">
        <div style="display:flex;gap:20px;font-size:.82rem;color:var(--gray-500);">
          <div><span style="font-weight:600;color:var(--gray-700);">Role:</span> {{ ucfirst($user->role) }}</div>
          <div><span style="font-weight:600;color:var(--gray-700);">Username:</span> {{ $user->username ?? '—' }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- RIGHT: Change Password --}}
  <div class="card">
    <div class="card-header"><span class="card-title">🔒 Change Password</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('profile.password') }}">
        @csrf

        @if($errors->has('current_password'))
          <div class="alert alert-danger">{{ $errors->first('current_password') }}</div>
        @endif

        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-input" required>
        </div>
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-input" required minlength="8">
        </div>
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="password_confirmation" class="form-input" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Password</button>
      </form>
    </div>
  </div>

</div>
@endsection