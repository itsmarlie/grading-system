@extends('layouts.app')
@section('title', 'Gradebook — ' . $course->course_name)
@section('content')

<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <div style="margin-bottom:4px;">
            <a href="{{ route('gradebook.index') }}" style="font-size:.8rem;color:var(--green-600);text-decoration:none;">← Back to Gradebook</a>
        </div>
        <h2>📊 {{ $course->course_name }}</h2>
        <p>{{ $course->course_code ?? '' }} · {{ $course->teacher?->display_name ?? $course->teacher?->name ?? '—' }}</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('reports.export.csv', ['type'=>'grades','course_id'=>$course->id]) }}"
           class="btn btn-outline btn-sm">📥 Export CSV</a>
        <a href="{{ route('reports.export.pdf', ['course_id'=>$course->id]) }}"
           class="btn btn-outline btn-sm">📄 Export PDF</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

{{-- ── CLASS SUMMARY ───────────────────────────────────────────────── --}}
<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon green">📊</div>
        <div>
            <div class="stat-label">Class Average</div>
            <div class="stat-value">{{ $classSummary['average'] !== null ? $classSummary['average'].'%' : '—' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald">⬆️</div>
        <div>
            <div class="stat-label">Highest</div>
            <div class="stat-value">{{ $classSummary['highest'] !== null ? $classSummary['highest'].'%' : '—' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;">⬇️</div>
        <div>
            <div class="stat-label">Lowest</div>
            <div class="stat-value">{{ $classSummary['lowest'] !== null ? $classSummary['lowest'].'%' : '—' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal">✅</div>
        <div>
            <div class="stat-label">Passed</div>
            <div class="stat-value">{{ $classSummary['passed'] }}<span style="font-size:.9rem;color:var(--gray-400);">/{{ $classSummary['total'] }}</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;">❌</div>
        <div>
            <div class="stat-label">Failed</div>
            <div class="stat-value">{{ $classSummary['failed'] }}</div>
        </div>
    </div>
</div>

{{-- ── GRADING FORMULA INFO ────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">⚖️ Grading Formula (DepEd Weighted)</div>
        <button class="btn btn-outline btn-sm" onclick="toggleFormula()">Show/Hide</button>
    </div>
    <div id="formulaBox" class="card-body">
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
            @foreach([
                ['Written Work',         '30%', '#3b82f6', 'written_work'],
                ['Performance Task',     '50%', '#22c55e', 'performance_task'],
                ['Quarterly Assessment', '20%', '#f59e0b', 'quarterly_assessment'],
            ] as [$label, $weight, $color, $key])
            <div style="flex:1;min-width:160px;padding:14px;border-radius:10px;background:var(--green-50);border:1px solid var(--green-100);text-align:center;">
                <div style="font-size:1.8rem;font-weight:700;color:{{ $color }};">{{ $weight }}</div>
                <div style="font-size:.83rem;color:var(--gray-600);margin-top:3px;">{{ $label }}</div>
                @php
                    $items = $byType->get($key, collect());
                @endphp
                <div style="font-size:.72rem;color:var(--gray-400);margin-top:4px;">{{ $items->count() }} item{{ $items->count()!=1?'s':'' }}</div>
            </div>
            @endforeach
        </div>
        <div style="background:#f0fdf4;border:1px solid var(--green-200);border-radius:8px;padding:12px;font-size:.83rem;color:var(--green-800);">
            <strong>Formula:</strong> Final Grade = (Written Work avg × 30%) + (Performance Task avg × 50%) + (Quarterly Assessment avg × 20%)<br>
            <strong>Passing:</strong> 75% and above · <strong>Score computation:</strong> Total earned ÷ Total possible × 100
        </div>
    </div>
</div>

{{-- ── GRADE ENTRY FORM ────────────────────────────────────────────── --}}
<form method="POST" action="{{ route('gradebook.update', $course->id) }}" id="gradeForm">
@csrf

@if($assignments->isEmpty())
    <div class="card" style="text-align:center;padding:48px;color:var(--gray-400);">
        <div style="font-size:3rem;margin-bottom:12px;">📭</div>
        No assignments for this course yet.
        <a href="{{ route('assignments.create') }}" class="btn btn-primary" style="margin-top:12px;">+ Create Assignment</a>
    </div>
@else

{{-- Loop by type --}}
@foreach([
    ['written_work',         'Written Work',         '30%', '#3b82f6'],
    ['performance_task',     'Performance Task',     '50%', '#22c55e'],
    ['quarterly_assessment', 'Quarterly Assessment', '20%', '#f59e0b'],
] as [$typeKey, $typeLabel, $typeWeight, $typeColor])

@php $typeAssignments = $byType->get($typeKey, collect()); @endphp

@if($typeAssignments->isNotEmpty())
<div class="card" style="margin-bottom:18px;">
    <div class="card-header">
        <div class="card-title" style="display:flex;align-items:center;gap:10px;">
            <span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:{{ $typeColor }};"></span>
            {{ $typeLabel }}
            <span class="badge badge-gray">{{ $typeWeight }}</span>
            <span class="badge badge-blue">{{ $typeAssignments->count() }} item{{ $typeAssignments->count()!=1?'s':'' }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="min-width:180px;">Student</th>
                        @foreach($typeAssignments as $a)
                        <th style="min-width:110px;text-align:center;">
                            <div style="font-weight:600;font-size:.78rem;">{{ Str::limit($a->title, 20) }}</div>
                            <div style="font-weight:400;font-size:.68rem;color:var(--gray-400);">/ {{ $a->total_points }} pts</div>
                        </th>
                        @endforeach
                        <th style="min-width:90px;text-align:center;">Type Avg</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    @php
                        $typeScores = $typeAssignments->map(fn($a) => [
                            'score'  => $gradeMatrix[$student->id][$a->id] ?? null,
                            'total'  => $a->total_points,
                        ]);
                        $scoredItems = $typeScores->filter(fn($s) => $s['score'] !== null);
                        $typeAvg = $scoredItems->isNotEmpty()
                            ? round($scoredItems->sum('score') / $scoredItems->sum('total') * 100, 1)
                            : null;
                    @endphp
                    <tr>
                        <td class="td-name">
                            {{ $student->last_name }}, {{ $student->first_name }}
                            <div style="font-size:.72rem;color:var(--gray-400);">{{ $student->student_number }}</div>
                        </td>
                        @foreach($typeAssignments as $a)
                        @php $score = $gradeMatrix[$student->id][$a->id] ?? null; @endphp
                        <td style="text-align:center;">
                            <input type="number"
                                name="grades[{{ $student->id }}][{{ $a->id }}]"
                                value="{{ $score }}"
                                min="0"
                                max="{{ $a->total_points }}"
                                step="0.01"
                                class="grade-input"
                                placeholder="—"
                                style="width:70px;">
                        </td>
                        @endforeach
                        <td style="text-align:center;">
                            @if($typeAvg !== null)
                            <span style="font-weight:600;color:{{ $typeAvg >= 75 ? 'var(--green-700)' : '#ef4444' }};">
                                {{ $typeAvg }}%
                            </span>
                            @else
                            <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endforeach

{{-- ── COMPUTED FINAL GRADES ───────────────────────────────────────── --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">🏁 Computed Final Grades</div>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th style="text-align:center;">Written Work<br><small style="font-weight:400;">(30%)</small></th>
                        <th style="text-align:center;">Performance Task<br><small style="font-weight:400;">(50%)</small></th>
                        <th style="text-align:center;">Quarterly Assess.<br><small style="font-weight:400;">(20%)</small></th>
                        <th style="text-align:center;">Final Grade</th>
                        <th style="text-align:center;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    @php
                        $g      = $studentGrades[$student->id];
                        $final  = $g['final'];
                        $passed = $final !== null && $final >= 75;
                    @endphp
                    <tr>
                        <td class="td-name">
                            {{ $student->last_name }}, {{ $student->first_name }}
                            <div style="font-size:.72rem;color:var(--gray-400);">{{ $student->student_number }}</div>
                        </td>
                        <td style="text-align:center;color:var(--gray-600);">
                            {{ $g['written_work'] !== null ? $g['written_work'].'%' : '—' }}
                        </td>
                        <td style="text-align:center;color:var(--gray-600);">
                            {{ $g['performance_task'] !== null ? $g['performance_task'].'%' : '—' }}
                        </td>
                        <td style="text-align:center;color:var(--gray-600);">
                            {{ $g['quarterly_assessment'] !== null ? $g['quarterly_assessment'].'%' : '—' }}
                        </td>
                        <td style="text-align:center;">
                            @if($final !== null)
                            <span style="font-size:1.1rem;font-weight:700;color:{{ $passed ? 'var(--green-700)' : '#ef4444' }};">
                                {{ $final }}%
                            </span>
                            @else
                            <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($final !== null)
                            <span class="badge {{ $passed ? 'badge-green' : 'badge-red' }}">
                                {{ $passed ? '✅ Passed' : '❌ Failed' }}
                            </span>
                            @else
                            <span style="font-size:.8rem;color:var(--gray-300);">Not graded</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Save button --}}
<div style="position:sticky;bottom:20px;display:flex;justify-content:flex-end;z-index:10;">
    <button type="submit" class="btn btn-primary" style="box-shadow:0 4px 14px rgba(22,163,74,.4);padding:10px 28px;">
        💾 Save All Grades
    </button>
</div>

@endif
</form>

@push('scripts')
<script>
function toggleFormula() {
    const box = document.getElementById('formulaBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
@endsection