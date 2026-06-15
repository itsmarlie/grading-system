@extends('layouts.app')
@section('title', $syllabus->title)

@section('content')
<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>{{ $syllabus->title }}</h2>
            <p>{{ $syllabus->course->course_name ?? '' }} · {{ $syllabus->course->course_code ?? '' }}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('syllabi.edit', $syllabus->id) }}" class="btn btn-primary btn-sm">Edit</a>
            <a href="{{ route('syllabi.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
    </div>

    <div class="grid-2" style="align-items:start;">

        {{-- Info --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Course Information</span></div>
            <div class="card-body">
                @php
                    $rows = [
                        'Course'     => $syllabus->course->course_name ?? '—',
                        'Code'       => $syllabus->course->course_code ?? '—',
                        'Teacher'    => $syllabus->creator->display_name ?? $syllabus->creator->name ?? '—',
                        'Units'      => $syllabus->course->units ?? '—',
                        'Term'       => $syllabus->course->term ?? '—',
                        'Created'    => $syllabus->created_at->format('F d, Y'),
                    ];
                @endphp
                @foreach($rows as $label => $value)
                <div style="display:flex;justify-content:space-between;padding:9px 0;
                            border-bottom:1px solid var(--green-50);font-size:.875rem;">
                    <span style="color:var(--gray-400);font-weight:500;">{{ $label }}</span>
                    <span style="color:var(--green-900);font-weight:500;">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Description --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Description</span></div>
            <div class="card-body">
                <p style="font-size:.875rem;color:var(--gray-700);line-height:1.7;">
                    {{ $syllabus->description ?? 'No description provided.' }}
                </p>
            </div>
        </div>

    </div>

    {{-- Course Overview --}}
    @if($syllabus->course_overview)
    <div class="card" style="margin-top:20px;">
        <div class="card-header"><span class="card-title">Course Overview</span></div>
        <div class="card-body">
            <p style="font-size:.875rem;color:var(--gray-700);line-height:1.7;white-space:pre-wrap;">{{ $syllabus->course_overview }}</p>
        </div>
    </div>
    @endif

    {{-- Learning Outcomes --}}
    @if($syllabus->learning_outcomes)
    <div class="card" style="margin-top:20px;">
        <div class="card-header"><span class="card-title">Learning Outcomes</span></div>
        <div class="card-body">
            <p style="font-size:.875rem;color:var(--gray-700);line-height:1.7;white-space:pre-wrap;">{{ $syllabus->learning_outcomes }}</p>
        </div>
    </div>
    @endif

    {{-- Topics --}}
    @if($syllabus->topics && count($syllabus->topics))
    <div class="card" style="margin-top:20px;">
        <div class="card-header"><span class="card-title">Topics</span></div>
        <div class="card-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Topic</th>
                            <th>Week</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($syllabus->topics as $i => $topic)
                        <tr>
                            <td style="color:var(--gray-400);">{{ $i + 1 }}</td>
                            <td class="td-name">{{ $topic['title'] ?? $topic }}</td>
                            <td>{{ $topic['week'] ?? '—' }}</td>
                            <td style="font-size:.82rem;color:var(--gray-500);">{{ $topic['description'] ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Grading Criteria --}}
    @if($syllabus->grading_criteria && count($syllabus->grading_criteria))
    <div class="card" style="margin-top:20px;">
        <div class="card-header"><span class="card-title">Grading Criteria</span></div>
        <div class="card-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th style="text-align:center;">Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($syllabus->grading_criteria as $criteria)
                        <tr>
                            <td class="td-name">{{ $criteria['component'] ?? $criteria }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-green">{{ $criteria['weight'] ?? '—' }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Materials --}}
    @if($syllabus->materials && count($syllabus->materials))
    <div class="card" style="margin-top:20px;">
        <div class="card-header"><span class="card-title">Course Materials</span></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach($syllabus->materials as $material)
                <div style="padding:10px 14px;border:1px solid var(--green-100);
                            border-radius:var(--radius-sm);background:var(--green-50);
                            font-size:.875rem;color:var(--gray-700);">
                    📚 {{ $material['title'] ?? $material }}
                    @if(isset($material['author']))
                        <span style="color:var(--gray-400);"> — {{ $material['author'] }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
