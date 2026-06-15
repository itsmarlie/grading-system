@extends('layouts.app')
@section('title', 'Teacher Profile')

@section('content')
<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>Teacher Profile</h2>
            <p>Viewing information for {{ $teacher->user->display_name ?? $teacher->user->name }}.</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary btn-sm">Edit</a>
            <a href="{{ route('teachers.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
    </div>

    <div class="grid-2" style="align-items:start;">

        {{-- Profile Card --}}
        <div class="card">
            <div class="card-body" style="padding-top:28px;text-align:center;">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--green-200);color:var(--green-800);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.5rem;margin:0 auto 14px;">
                    {{ strtoupper(substr($teacher->user->first_name ?? $teacher->user->name, 0, 1)) }}{{ strtoupper(substr($teacher->user->last_name ?? '', 0, 1)) }}
                </div>
                <div style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:var(--green-900);">
                    {{ $teacher->user->display_name ?? $teacher->user->name }}
                </div>
                <div style="margin-top:6px;">
                    <span class="badge badge-green">Teacher</span>
                </div>
                @if($teacher->user->department)
                <div style="margin-top:8px;font-size:.82rem;color:var(--gray-500);">
                    {{ $teacher->user->department }}
                </div>
                @endif
            </div>
        </div>

        {{-- Info Card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Personal Information</span>
            </div>
            <div class="card-body">
                @php
                    $rows = [
                        'Title'       => $teacher->user->title ?? '—',
                        'First Name'  => $teacher->user->first_name ?? '—',
                        'Middle Name' => $teacher->user->middle_name ?? '—',
                        'Last Name'   => $teacher->user->last_name ?? '—',
                        'Teacher ID'  => $teacher->user->teacher_id ?? '—',
                        'Department'  => $teacher->user->department ?? '—',
                        'Email'       => $teacher->user->email,
                        'Username'    => $teacher->user->username ? '@'.$teacher->user->username : '—',
                        'Phone'       => $teacher->user->phone ?? '—',
                        'Birthdate'   => $teacher->user->birthdate?->format('F d, Y') ?? '—',
                        'Address'     => $teacher->user->address ?? '—',
                    ];
                @endphp
                @foreach($rows as $label => $value)
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--green-50);font-size:.875rem;">
                    <span style="color:var(--gray-400);font-weight:500;">{{ $label }}</span>
                    <span style="color:var(--green-900);font-weight:500;text-align:right;max-width:60%;">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection