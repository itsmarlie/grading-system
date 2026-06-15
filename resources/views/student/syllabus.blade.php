@extends('layouts.app')
@section('title', 'My Syllabus')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Course Syllabi</h2>
        <p>View the syllabus for each of your enrolled courses.</p>
    </div>

    @if($courses->isEmpty())
        <div class="card">
            <div class="card-body" style="text-align:center;padding:48px;color:var(--gray-400);">
                You are not enrolled in any courses yet.
            </div>
        </div>
    @endif

    @foreach($courses as $course)
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <div>
                <span class="card-title">{{ $course->course_name }}</span>
                <div style="font-size:.78rem;color:var(--gray-400);margin-top:3px;">
                    {{ $course->course_code ?? '' }}
                    @if($course->teacher)
                        · {{ $course->teacher->display_name ?? $course->teacher->name }}
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(isset($syllabi[$course->id]) && $syllabi[$course->id]->count())
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($syllabi[$course->id] as $syllabus)
                    <div style="display:flex;align-items:center;justify-content:space-between;
                                padding:12px 16px;border:1px solid var(--green-100);
                                border-radius:var(--radius-sm);background:var(--green-50);
                                flex-wrap:wrap;gap:10px;">
                        <div>
                            <div style="font-weight:600;color:var(--green-900);font-size:.9rem;">
                                {{ $syllabus->title ?? $course->course_name . ' Syllabus' }}
                            </div>
                            <div style="font-size:.72rem;color:var(--gray-400);margin-top:2px;">
                                Posted by {{ $syllabus->creator->display_name ?? $syllabus->creator->name ?? 'Admin' }}
                                · {{ $syllabus->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        <a href="{{ route('my.syllabus.show', $syllabus->id) }}"
                           class="btn btn-primary btn-sm">View Syllabus</a>
                    </div>
                    @endforeach
                </div>
            @else
                <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">
                    No syllabus uploaded for this course yet.
                </p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection