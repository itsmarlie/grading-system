@extends('layouts.app')
@section('title', 'My Grades')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>My Grades</h2>
        <p>Academic performance overview for {{ $student->display_name }}.</p>
    </div>

    @if($courseGrades->isEmpty())
        <div class="card">
            <div class="card-body" style="text-align:center;padding:48px;color:var(--gray-400);">
                You are not enrolled in any courses yet.
            </div>
        </div>
    @endif

    @foreach($courseGrades as $cg)
    <div class="card" style="margin-bottom:24px;">

        {{-- Course Header --}}
        <div class="card-header" style="flex-wrap:wrap;gap:10px;">
            <div>
                <span class="card-title">{{ $cg['course']->course_name }}</span>
                <div style="font-size:.78rem;color:var(--gray-400);margin-top:3px;">
                    {{ $cg['course']->course_code ?? '' }}
                    @if($cg['course']->teacher)
                        · {{ $cg['course']->teacher->display_name ?? $cg['course']->teacher->name }}
                    @endif
                </div>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                @if($cg['avg_score'])
                    <div style="text-align:center;">
                        <div style="font-size:1.4rem;font-weight:700;color:var(--green-700);">
                            {{ $cg['avg_score'] }}%
                        </div>
                        <div style="font-size:.7rem;color:var(--gray-400);">Average</div>
                    </div>
                @endif
                @if($cg['computed_grade'])
                    <div style="text-align:center;">
                        <div style="font-size:1.4rem;font-weight:700;color:var(--green-800);">
                            {{ $cg['computed_grade'] }}%
                        </div>
                        <div style="font-size:.7rem;color:var(--gray-400);">Weighted</div>
                    </div>
                @endif
                @if($cg['letter_grade'])
                    <div style="width:44px;height:44px;border-radius:10px;background:var(--green-100);
                                color:var(--green-800);display:flex;align-items:center;
                                justify-content:center;font-weight:700;font-size:1rem;">
                        {{ $cg['letter_grade'] }}
                    </div>
                @endif
                <span class="badge badge-gray">
                    {{ $cg['graded_items'] }}/{{ $cg['total_items'] }} graded
                </span>
            </div>
        </div>

        <div class="card-body">

            @if($cg['by_type']->isEmpty())
                <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">
                    No assignments for this course yet.
                </p>
            @else

            @php
            $typeLabels = [
                'written_work'         => 'Written Work',
                'performance_task'     => 'Performance Task',
                'quarterly_assessment' => 'Quarterly Assessment',
                'quiz'                 => 'Quiz',
                'assignment'           => 'Assignment',
                'project'              => 'Project',
                'exam'                 => 'Exam',
                'activity'             => 'Activity',
            ];
            @endphp

            @foreach($cg['by_type'] as $type => $items)
            <div style="margin-bottom:20px;">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;
                            letter-spacing:.08em;color:var(--green-700);
                            margin-bottom:10px;padding-bottom:6px;
                            border-bottom:2px solid var(--green-100);">
                    {{ $typeLabels[$type] ?? ucfirst(str_replace('_',' ',$type)) }}
                    @if(isset($cg['weights'][$type]))
                        <span style="color:var(--gray-400);font-weight:400;">
                            ({{ $cg['weights'][$type] * 100 }}% weight)
                        </span>
                    @endif
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th style="text-align:center;">Score</th>
                                <th style="text-align:center;">Max</th>
                                <th style="text-align:center;">Percentage</th>
                                <th style="text-align:center;">Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td class="td-name">
                                    {{ $item['assignment']->title }}
                                    @if($item['assignment']->due_date)
                                    <div style="font-size:.7rem;color:var(--gray-400);">
                                        Due {{ $item['assignment']->due_date->format('M d, Y') }}
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align:center;font-weight:600;color:var(--green-800);">
                                    {{ $item['score'] ?? '—' }}
                                </td>
                                <td style="text-align:center;color:var(--gray-400);">
                                    {{ $item['max_score'] }}
                                </td>
                                <td style="text-align:center;">
                                    @if($item['percentage'] !== null)
                                    <div class="grade-bar-wrap">
                                        <div class="grade-bar-bg">
                                            <div class="grade-bar-fill"
                                                 style="width:{{ min($item['percentage'],100) }}%;
                                                 background:{{ $item['percentage'] >= 75 ? 'var(--green-500)' : '#fca5a5' }};">
                                            </div>
                                        </div>
                                        <span class="grade-pct">{{ $item['percentage'] }}%</span>
                                    </div>
                                    @else
                                        <span style="color:var(--gray-300);">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @if($item['letter_grade'])
                                        <span class="badge {{ $item['letter_grade'] === '5.00' ? 'badge-red' : 'badge-green' }}">
                                            {{ $item['letter_grade'] }}
                                        </span>
                                    @else
                                        <span style="color:var(--gray-300);">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.78rem;color:var(--gray-400);">
                                    {{ $item['remarks'] ?? '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection