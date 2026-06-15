@extends('layouts.app')
@section('title', 'Reports & Analytics')
@section('content')

<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h2>📈 Reports & Analytics</h2>
        <p>Student performance, grade distributions, and attendance records.</p>
    </div>
    {{-- Export buttons --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <a href="{{ route('reports.export.csv', ['type'=>'grades']) }}" class="btn btn-outline btn-sm">
            📥 Export Grades CSV
        </a>
        <a href="{{ route('reports.export.csv', ['type'=>'attendance']) }}" class="btn btn-outline btn-sm">
            📥 Export Attendance CSV
        </a>
        <a href="{{ route('reports.export.csv', ['type'=>'students']) }}" class="btn btn-outline btn-sm">
            📥 Export Students CSV
        </a>
        <a href="{{ route('reports.export.pdf') }}" class="btn btn-primary btn-sm">
            📄 Export PDF Report
        </a>
    </div>
</div>

{{-- ── SUMMARY CARDS ──────────────────────────────────────────────── --}}
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green">👥</div>
        <div>
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $totalStudents }}</div>
            <div class="stat-change">Enrolled</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald">📊</div>
        <div>
            <div class="stat-label">School Average</div>
            <div class="stat-value">{{ $overallAverage }}%</div>
            <div class="stat-change">All graded items</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal">📖</div>
        <div>
            <div class="stat-label">Courses</div>
            <div class="stat-value">{{ $totalCourses }}</div>
            <div class="stat-change">Tracked</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon lime">📅</div>
        <div>
            <div class="stat-label">Attendance Rate</div>
            <div class="stat-value">{{ $attendanceRate }}%</div>
            <div class="stat-change">Present + Late</div>
        </div>
    </div>
</div>

{{-- ── ATTENDANCE BREAKDOWN ────────────────────────────────────────── --}}
<div class="grid-2" style="margin-bottom:20px;">
    <div class="card">
        <div class="card-header"><div class="card-title">📅 Attendance Summary</div></div>
        <div class="card-body">
            @php $attTotal = array_sum($attendanceSummary) ?: 1; @endphp
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                @foreach([
                    ['Present', $attendanceSummary['present'], '#22c55e'],
                    ['Absent',  $attendanceSummary['absent'],  '#ef4444'],
                    ['Late',    $attendanceSummary['late'],    '#f59e0b'],
                    ['Excused', $attendanceSummary['excused'], '#8b5cf6'],
                ] as [$label, $count, $color])
                <div style="flex:1;min-width:100px;text-align:center;padding:12px;background:var(--green-50);border-radius:8px;border:1px solid var(--green-100);">
                    <div style="font-size:1.4rem;font-weight:700;color:{{ $color }};">{{ $count }}</div>
                    <div style="font-size:.75rem;color:var(--gray-500);">{{ $label }}</div>
                    <div style="font-size:.7rem;color:var(--gray-400);">{{ round($count/$attTotal*100,1) }}%</div>
                </div>
                @endforeach
            </div>
            {{-- Bar --}}
            @php
                $p = round($attendanceSummary['present']/$attTotal*100);
                $a = round($attendanceSummary['absent']/$attTotal*100);
                $l = round($attendanceSummary['late']/$attTotal*100);
                $e = round($attendanceSummary['excused']/$attTotal*100);
            @endphp
            <div style="height:12px;border-radius:99px;overflow:hidden;display:flex;gap:2px;">
                @if($p)<div style="flex:{{$p}};background:#22c55e;" title="Present {{$p}}%"></div>@endif
                @if($l)<div style="flex:{{$l}};background:#f59e0b;" title="Late {{$l}}%"></div>@endif
                @if($e)<div style="flex:{{$e}};background:#8b5cf6;" title="Excused {{$e}}%"></div>@endif
                @if($a)<div style="flex:{{$a}};background:#ef4444;" title="Absent {{$a}}%"></div>@endif
            </div>
        </div>
    </div>

    {{-- Top Students --}}
    <div class="card">
        <div class="card-header"><div class="card-title">🏆 Top Performing Students</div></div>
        <div class="card-body">
            @forelse($topStudents as $i => $ts)
            <div style="display:flex;align-items:center;gap:12px;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--green-50);' : '' }}">
                <div style="width:28px;height:28px;border-radius:50%;background:{{ ['#f59e0b','#9ca3af','#cd7c4e'][$i] ?? 'var(--green-200)' }};display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:white;flex-shrink:0;">
                    {{ $i + 1 }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:.875rem;font-weight:600;color:var(--green-900);">
                        {{ $ts->student?->full_name ?? '—' }}
                    </div>
                    <div style="font-size:.72rem;color:var(--gray-400);">
                        {{ $ts->student?->course?->course_name ?? '—' }}
                    </div>
                </div>
                <span class="badge badge-green">{{ round($ts->avg_score, 1) }}%</span>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:var(--gray-400);font-size:.85rem;">No grade data yet.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── PER-COURSE TABLE ────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">📋 Per-Course Performance Report</div>
        <div style="display:flex;gap:8px;">
            <select id="courseFilter" class="form-select" style="width:auto;font-size:.8rem;padding:5px 10px;"
                onchange="filterCourse(this.value)">
                <option value="">All Courses</option>
                @foreach($courseReports as $r)
                <option value="{{ $r['course']->id }}">{{ $r['course']->course_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table id="courseTable">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Students</th>
                        <th>Class Average</th>
                        <th>Passed</th>
                        <th>Failed</th>
                        <th>Attendance</th>
                        <th>Distribution</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courseReports as $r)
                    @php
                        $total = $r['graded'] ?: 1;
                        $p90   = round($r['90+']/$total*100);
                        $p80   = round($r['80s']/$total*100);
                        $p75   = round($r['75s']/$total*100);
                        $pfail = round($r['fail']/$total*100);
                    @endphp
                    <tr data-course="{{ $r['course']->id }}">
                        <td class="td-name">{{ $r['course']->course_name }}
                            @if($r['course']->course_code)
                            <div style="font-size:.72rem;color:var(--gray-400);">{{ $r['course']->course_code }}</div>
                            @endif
                        </td>
                        <td style="font-size:.83rem;">{{ $r['course']->teacher?->display_name ?? $r['course']->teacher?->name ?? '—' }}</td>
                        <td>
                            <span style="font-weight:600;">{{ $r['total'] }}</span>
                            <span style="font-size:.72rem;color:var(--gray-400);">({{ $r['graded'] }} graded)</span>
                        </td>
                        <td>
                            <span class="badge {{ $r['average'] >= 85 ? 'badge-green' : ($r['average'] >= 75 ? 'badge-blue' : 'badge-red') }}">
                                {{ $r['average'] }}%
                            </span>
                        </td>
                        <td><span class="badge badge-green">{{ $r['passed'] }}</span></td>
                        <td><span class="badge badge-red">{{ $r['failed'] }}</span></td>
                        <td>
                            @if($r['att_rate'] !== null)
                            <span class="badge {{ $r['att_rate'] >= 80 ? 'badge-green' : 'badge-amber' }}">
                                {{ $r['att_rate'] }}%
                            </span>
                            @else
                            <span style="color:var(--gray-300);font-size:.8rem;">—</span>
                            @endif
                        </td>
                        <td style="min-width:160px;">
                            <div style="display:flex;height:10px;border-radius:99px;overflow:hidden;gap:2px;margin-bottom:4px;">
                                @if($p90)<div style="flex:{{$p90}};background:#16a34a;" title="90+: {{$r['90+']}}"></div>@endif
                                @if($p80)<div style="flex:{{$p80}};background:#4ade80;" title="80s: {{$r['80s']}}"></div>@endif
                                @if($p75)<div style="flex:{{$p75}};background:#86efac;" title="75-79: {{$r['75s']}}"></div>@endif
                                @if($pfail)<div style="flex:{{$pfail}};background:#fca5a5;" title="Failed: {{$r['fail']}}"></div>@endif
                            </div>
                            <div style="font-size:.68rem;color:var(--gray-400);display:flex;gap:6px;">
                                <span>90+: {{ $r['90+'] }}</span>
                                <span>80s: {{ $r['80s'] }}</span>
                                <span>75+: {{ $r['75s'] }}</span>
                                <span style="color:#ef4444;">Fail: {{ $r['fail'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('gradebook.show', $r['course']->id) }}"
                                   class="btn btn-outline btn-sm">📊 Gradebook</a>
                                <a href="{{ route('reports.export.csv', ['type'=>'grades','course_id'=>$r['course']->id]) }}"
                                   class="btn btn-outline btn-sm">📥 CSV</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;color:var(--gray-400);padding:32px;">
                            No data available yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── STUDENT PERFORMANCE DETAIL ──────────────────────────────────── --}}
@foreach($courseReports as $r)
@if($r['students']->isNotEmpty())
<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">
            👥 {{ $r['course']->course_name }} — Student Breakdown
            <span class="badge badge-gray" style="margin-left:8px;">{{ $r['total'] }} students</span>
        </div>
        <a href="{{ route('reports.export.csv', ['type'=>'grades','course_id'=>$r['course']->id]) }}"
           class="btn btn-outline btn-sm">📥 Export CSV</a>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Student No.</th>
                        <th>Written Work<br><span style="font-weight:400;font-size:.7rem;">(30%)</span></th>
                        <th>Performance Task<br><span style="font-weight:400;font-size:.7rem;">(50%)</span></th>
                        <th>Quarterly Assessment<br><span style="font-weight:400;font-size:.7rem;">(20%)</span></th>
                        <th>Final Grade</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($r['students'] as $sd)
                    @php
                        $final   = $sd['computed']['final'];
                        $passed  = $final !== null && $final >= 75;
                        $color   = $final === null ? 'var(--gray-400)' : ($passed ? 'var(--green-700)' : '#ef4444');
                    @endphp
                    <tr>
                        <td class="td-name">{{ $sd['student']->full_name }}</td>
                        <td style="font-size:.8rem;color:var(--gray-400);">{{ $sd['student']->student_number }}</td>
                        <td style="text-align:center;">
                            {{ $sd['computed']['written_work'] !== null ? $sd['computed']['written_work'].'%' : '—' }}
                        </td>
                        <td style="text-align:center;">
                            {{ $sd['computed']['performance_task'] !== null ? $sd['computed']['performance_task'].'%' : '—' }}
                        </td>
                        <td style="text-align:center;">
                            {{ $sd['computed']['quarterly_assessment'] !== null ? $sd['computed']['quarterly_assessment'].'%' : '—' }}
                        </td>
                        <td style="text-align:center;">
                            @if($final !== null)
                            <span style="font-weight:700;font-size:1rem;color:{{ $color }};">{{ $final }}%</span>
                            @else
                            <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($final !== null)
                            <span class="badge {{ $passed ? 'badge-green' : 'badge-red' }}">
                                {{ $passed ? 'Passed' : 'Failed' }}
                            </span>
                            @else
                            <span style="color:var(--gray-300);font-size:.8rem;">Not graded</span>
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

@push('scripts')
<script>
function filterCourse(courseId) {
    document.querySelectorAll('#courseTable tbody tr').forEach(row => {
        if (!courseId || row.dataset.course === courseId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection