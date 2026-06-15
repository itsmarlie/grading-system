@extends('layouts.app')
@section('title', 'Syllabus')

@section('content')
<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>{{ $syllabus->title ?? $syllabus->course->course_name . ' Syllabus' }}</h2>
            <p>
                {{ $syllabus->course->course_name ?? '' }}
                @if($syllabus->course->course_code)
                    · {{ $syllabus->course->course_code }}
                @endif
            </p>
        </div>
        <a href="{{ route('my.syllabus') }}" class="btn btn-outline btn-sm">← Back</a>
    </div>

    <div class="grid-2" style="align-items:start;">

        {{-- Syllabus Details --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Course Information</span>
            </div>
            <div class="card-body">
                @php
                    $rows = [
                        'Course'      => $syllabus->course->course_name ?? '—',
                        'Course Code' => $syllabus->course->course_code ?? '—',
                        'Teacher'     => $syllabus->creator->display_name
                                         ?? $syllabus->creator->name
                                         ?? '—',
                        'Units'       => $syllabus->course->units ?? '—',
                        'Term'        => $syllabus->course->term ?? '—',
                        'Posted'      => $syllabus->created_at->format('F d, Y'),
                    ];
                @endphp
                @foreach($rows as $label => $value)
                <div style="display:flex;justify-content:space-between;padding:9px 0;
                            border-bottom:1px solid var(--green-50);font-size:.875rem;">
                    <span style="color:var(--gray-400);font-weight:500;">{{ $label }}</span>
                    <span style="color:var(--green-900);font-weight:500;text-align:right;max-width:60%;">
                        {{ $value }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Syllabus Content --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Syllabus Content</span>
            </div>
            <div class="card-body">
                @if(isset($syllabus->content) && $syllabus->content)
                    <div style="font-size:.875rem;color:var(--gray-700);line-height:1.7;white-space:pre-wrap;">
                        {{ $syllabus->content }}
                    </div>
                @elseif(isset($syllabus->file_path) && $syllabus->file_path)
                    <div style="text-align:center;padding:24px;">
                        <div style="font-size:2rem;margin-bottom:12px;">📄</div>
                        <p style="color:var(--gray-500);margin-bottom:16px;">
                            This syllabus is available as a file download.
                        </p>
                        <a href="{{ asset('storage/' . $syllabus->file_path) }}"
                           target="_blank" class="btn btn-primary">
                            Download Syllabus
                        </a>
                    </div>
                @else
                    <p style="color:var(--gray-400);font-size:.85rem;">
                        No content available for this syllabus.
                    </p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection