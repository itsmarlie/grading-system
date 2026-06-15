@extends('layouts.app')
@section('title', 'My Syllabus')
@section('content')

<div class="page-header">
    <h2>📚 My Syllabus</h2>
    <p>Course materials, syllabi, and grading criteria for your enrolled courses.</p>
</div>

@if($courses->isEmpty())
    <div class="card" style="text-align:center;padding:48px;color:var(--gray-400);">
        <div style="font-size:3rem;margin-bottom:12px;">📭</div>
        You are not enrolled in any courses yet.
    </div>
@else

@foreach($courses as $course)
@php $courseSyllabi = $syllabi->get($course->id, collect()); @endphp

<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div>
            <div class="card-title">{{ $course->course_name }}</div>
            <div style="font-size:.78rem;color:var(--gray-400);margin-top:2px;">
                {{ $course->course_code ?? '' }}
                @if($course->teacher) · {{ $course->teacher->display_name ?? $course->teacher->name }} @endif
            </div>
        </div>
        <span class="badge {{ $courseSyllabi->isNotEmpty() ? 'badge-green' : 'badge-gray' }}">
            {{ $courseSyllabi->count() }} syllabus item{{ $courseSyllabi->count() != 1 ? 's' : '' }}
        </span>
    </div>
    <div class="card-body">
        @if($courseSyllabi->isEmpty())
            <div style="text-align:center;padding:20px;color:var(--gray-400);font-size:.85rem;background:var(--green-50);border-radius:var(--radius-sm);">
                No syllabus uploaded for this course yet.
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($courseSyllabi as $syllabus)
                <a href="{{ route('my.syllabus.show', $syllabus->id) }}"
                   style="display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--green-50);border-radius:var(--radius-sm);border:1px solid var(--green-100);text-decoration:none;transition:all .18s;"
                   onmouseover="this.style.background='var(--green-100)'"
                   onmouseout="this.style.background='var(--green-50)'">
                    <div style="width:44px;height:44px;background:var(--green-600);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">📋</div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:var(--green-900);font-size:.9rem;">{{ $syllabus->title }}</div>
                        @if($syllabus->description)
                        <div style="font-size:.78rem;color:var(--gray-500);margin-top:2px;">{{ Str::limit($syllabus->description, 100) }}</div>
                        @endif
                        <div style="font-size:.72rem;color:var(--gray-400);margin-top:4px;">
                            Updated {{ $syllabus->updated_at->diffForHumans() }}
                            @if($syllabus->creator) · by {{ $syllabus->creator->display_name ?? $syllabus->creator->name }} @endif
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                        @if($syllabus->materials && count($syllabus->materials))
                        <span class="badge badge-blue">{{ count($syllabus->materials) }} material{{ count($syllabus->materials) != 1 ? 's' : '' }}</span>
                        @endif
                        @if($syllabus->topics && count($syllabus->topics))
                        <span class="badge badge-green">{{ count($syllabus->topics) }} topic{{ count($syllabus->topics) != 1 ? 's' : '' }}</span>
                        @endif
                        <span style="color:var(--green-600);font-size:.78rem;font-weight:500;">View →</span>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endforeach

@endif
@endsection