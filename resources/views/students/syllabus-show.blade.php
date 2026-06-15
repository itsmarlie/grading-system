@extends('layouts.app')
@section('title', $syllabus->title)
@section('content')

<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <div style="margin-bottom:6px;">
            <a href="{{ route('my.syllabus') }}" style="font-size:.8rem;color:var(--green-600);text-decoration:none;">← Back to Syllabus</a>
        </div>
        <h2>{{ $syllabus->title }}</h2>
        <p>
            {{ $syllabus->course->course_name ?? '—' }}
            @if($syllabus->course->course_code) ({{ $syllabus->course->course_code }}) @endif
            @if($syllabus->creator) · {{ $syllabus->creator->display_name ?? $syllabus->creator->name }} @endif
        </p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        @if($syllabus->materials && count($syllabus->materials))
        <span class="badge badge-blue" style="font-size:.8rem;padding:5px 12px;">{{ count($syllabus->materials) }} Materials</span>
        @endif
        @if($syllabus->topics && count($syllabus->topics))
        <span class="badge badge-green" style="font-size:.8rem;padding:5px 12px;">{{ count($syllabus->topics) }} Topics</span>
        @endif
    </div>
</div>

{{-- TABS --}}
<div class="tabs" id="sylTabs">
    <button class="tab active" onclick="switchSylTab('syl-overview',this)">📋 Overview</button>
    @if($syllabus->topics && count($syllabus->topics))
    <button class="tab" onclick="switchSylTab('syl-topics',this)">📆 Topics</button>
    @endif
    @if($syllabus->grading_criteria && count($syllabus->grading_criteria))
    <button class="tab" onclick="switchSylTab('syl-grading',this)">📊 Grading</button>
    @endif
    @if($syllabus->materials && count($syllabus->materials))
    <button class="tab" onclick="switchSylTab('syl-materials',this)">📁 Materials</button>
    @endif
</div>

{{-- OVERVIEW --}}
<div class="tab-content active" id="syl-overview">
    <div class="grid-2">
        {{-- Description / Course Overview --}}
        <div class="card">
            <div class="card-header"><div class="card-title">📝 Course Overview</div></div>
            <div class="card-body">
                @if($syllabus->course_overview)
                    <div style="font-size:.875rem;color:var(--gray-700);line-height:1.7;white-space:pre-line;">{{ $syllabus->course_overview }}</div>
                @elseif($syllabus->description)
                    <div style="font-size:.875rem;color:var(--gray-700);line-height:1.7;">{{ $syllabus->description }}</div>
                @else
                    <div style="color:var(--gray-400);font-size:.85rem;">No overview provided.</div>
                @endif
            </div>
        </div>

        {{-- Learning Outcomes --}}
        <div class="card">
            <div class="card-header"><div class="card-title">🎯 Learning Outcomes</div></div>
            <div class="card-body">
                @if($syllabus->learning_outcomes)
                @php
                    // Try to split by newline for bullet display
                    $outcomes = array_filter(explode("\n", $syllabus->learning_outcomes));
                @endphp
                @if(count($outcomes) > 1)
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        @foreach($outcomes as $outcome)
                        @if(trim($outcome))
                        <li style="display:flex;align-items:flex-start;gap:8px;font-size:.875rem;color:var(--gray-700);">
                            <span style="color:var(--green-500);flex-shrink:0;margin-top:2px;">✓</span>
                            {{ trim($outcome) }}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                @else
                    <div style="font-size:.875rem;color:var(--gray-700);line-height:1.7;">{{ $syllabus->learning_outcomes }}</div>
                @endif
                @else
                    <div style="color:var(--gray-400);font-size:.85rem;">No learning outcomes listed.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick grading summary if available --}}
    @if($syllabus->grading_criteria && count($syllabus->grading_criteria))
    <div class="card">
        <div class="card-header">
            <div class="card-title">📊 Grading at a Glance</div>
            <button class="btn btn-outline btn-sm" onclick="switchSylTab('syl-grading', document.querySelectorAll('#sylTabs .tab')[2])">See full breakdown →</button>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                @foreach($syllabus->grading_criteria as $criterion)
                <div style="flex:1;min-width:120px;text-align:center;padding:14px;background:var(--green-50);border-radius:var(--radius-sm);border:1px solid var(--green-100);">
                    <div style="font-size:1.6rem;font-weight:700;color:var(--green-700);">{{ $criterion['weight'] ?? 0 }}%</div>
                    <div style="font-size:.78rem;color:var(--gray-600);margin-top:3px;">{{ $criterion['type'] ?? '—' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

{{-- TOPICS --}}
@if($syllabus->topics && count($syllabus->topics))
<div class="tab-content" id="syl-topics">
    <div class="card">
        <div class="card-header"><div class="card-title">📆 Course Topics by Week</div></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0;">
                @foreach($syllabus->topics as $idx => $topic)
                <div style="display:flex;gap:16px;padding:16px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--green-50);' : '' }}">
                    <div style="flex-shrink:0;width:54px;height:54px;background:var(--green-100);border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <div style="font-size:.6rem;color:var(--green-600);text-transform:uppercase;letter-spacing:.05em;">Week</div>
                        <div style="font-size:1.2rem;font-weight:700;color:var(--green-800);">{{ $topic['week'] ?? $idx+1 }}</div>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:var(--green-900);font-size:.9rem;margin-bottom:3px;">{{ $topic['title'] ?? 'Untitled' }}</div>
                        @if(!empty($topic['description']))
                        <div style="font-size:.82rem;color:var(--gray-500);line-height:1.6;">{{ $topic['description'] }}</div>
                        @endif
                        @if(!empty($topic['activities']))
                        <div style="margin-top:6px;display:flex;flex-wrap:wrap;gap:5px;">
                            @foreach((array)$topic['activities'] as $act)
                            <span class="badge badge-blue" style="font-size:.68rem;">{{ $act }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- GRADING CRITERIA --}}
@if($syllabus->grading_criteria && count($syllabus->grading_criteria))
<div class="tab-content" id="syl-grading">
    <div class="card">
        <div class="card-header"><div class="card-title">📊 Grading Criteria</div></div>
        <div class="card-body">
            {{-- Visual bar breakdown --}}
            <div style="margin-bottom:24px;">
                <div style="display:flex;height:22px;border-radius:99px;overflow:hidden;gap:3px;margin-bottom:10px;">
                    @php
                        $gradingColors = ['#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];
                    @endphp
                    @foreach($syllabus->grading_criteria as $ci => $criterion)
                    <div style="flex:{{ $criterion['weight'] ?? 0 }};background:{{ $gradingColors[$ci % count($gradingColors)] }};"
                         title="{{ $criterion['type'] }}: {{ $criterion['weight'] }}%"></div>
                    @endforeach
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach($syllabus->grading_criteria as $ci => $criterion)
                    <span style="display:flex;align-items:center;gap:5px;font-size:.78rem;color:var(--gray-700);">
                        <span style="width:10px;height:10px;border-radius:3px;background:{{ $gradingColors[$ci % count($gradingColors)] }};display:inline-block;"></span>
                        {{ $criterion['type'] ?? '—' }}: <strong>{{ $criterion['weight'] ?? 0 }}%</strong>
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Detailed table --}}
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Component</th>
                            <th>Weight</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($syllabus->grading_criteria as $ci => $criterion)
                        <tr>
                            <td>
                                <div style="width:28px;height:28px;border-radius:8px;background:{{ $gradingColors[$ci % count($gradingColors)] }};display:flex;align-items:center;justify-content:center;">
                                    <span style="font-size:.72rem;font-weight:700;color:white;">{{ $ci+1 }}</span>
                                </div>
                            </td>
                            <td class="td-name">{{ $criterion['type'] ?? '—' }}</td>
                            <td>
                                <span class="badge badge-green" style="font-size:.8rem;">{{ $criterion['weight'] ?? 0 }}%</span>
                            </td>
                            <td style="color:var(--gray-500);font-size:.82rem;">{{ $criterion['description'] ?? '—' }}</td>
                        </tr>
                        @endforeach
                        <tr style="background:var(--green-50);">
                            <td colspan="2" style="font-weight:700;color:var(--green-900);">Total</td>
                            <td><span class="badge badge-green" style="font-size:.8rem;">{{ collect($syllabus->grading_criteria)->sum('weight') }}%</span></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- MATERIALS --}}
@if($syllabus->materials && count($syllabus->materials))
<div class="tab-content" id="syl-materials">
    <div class="card">
        <div class="card-header"><div class="card-title">📁 Course Materials</div></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;">
                @foreach($syllabus->materials as $material)
                @php
                    $typeIcons = ['pdf'=>'📄','link'=>'🔗','file'=>'📎','video'=>'🎬','doc'=>'📝','image'=>'🖼️'];
                    $typeIcon  = $typeIcons[$material['type'] ?? 'file'] ?? '📎';
                    $typeBg    = ['pdf'=>'#fee2e2','link'=>'#dbeafe','file'=>'#f3f4f6','video'=>'#ede9fe','doc'=>'#fef3c7'][$material['type'] ?? 'file'] ?? '#f3f4f6';
                @endphp
                <div style="border:1px solid var(--green-100);border-radius:var(--radius-sm);padding:14px;background:white;display:flex;gap:12px;align-items:flex-start;">
                    <div style="width:42px;height:42px;border-radius:10px;background:{{ $typeBg }};display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">{{ $typeIcon }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;color:var(--green-900);font-size:.875rem;margin-bottom:3px;">{{ $material['title'] ?? 'Untitled' }}</div>
                        @if(!empty($material['description']))
                        <div style="font-size:.75rem;color:var(--gray-400);margin-bottom:6px;">{{ $material['description'] }}</div>
                        @endif
                        @if(!empty($material['url']))
                        <a href="{{ $material['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="btn btn-outline btn-sm" style="font-size:.72rem;padding:4px 10px;">
                            {{ $material['type'] === 'link' ? '🔗 Open Link' : '⬇️ Download' }}
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function switchSylTab(targetId, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#sylTabs .tab').forEach(t => t.classList.remove('active'));
    document.getElementById(targetId)?.classList.add('active');
    if(btn) btn.classList.add('active');
}
</script>
@endpush
@endsection