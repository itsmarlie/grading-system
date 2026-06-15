@extends('layouts.app')
@section('title', 'My Attendance')
@section('content')

<div class="page-header">
    <h2>📅 My Attendance</h2>
    <p>Your attendance records across all enrolled courses.</p>
</div>

{{-- Overall summary --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green">📅</div>
        <div>
            <div class="stat-label">Attendance Rate</div>
            <div class="stat-value">{{ $overallRate !== null ? $overallRate . '%' : '—' }}</div>
            <div class="stat-change">All courses combined</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">✅</div>
        <div>
            <div class="stat-label">Present</div>
            <div class="stat-value">{{ $allRecords->where('status','present')->count() }}</div>
            <div class="stat-change">Total days</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;">❌</div>
        <div>
            <div class="stat-label">Absent</div>
            <div class="stat-value">{{ $allRecords->where('status','absent')->count() }}</div>
            <div class="stat-change">Total days</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;">⏰</div>
        <div>
            <div class="stat-label">Late</div>
            <div class="stat-value">{{ $allRecords->where('status','late')->count() }}</div>
            <div class="stat-change">Total days</div>
        </div>
    </div>
</div>

@if($courseAttendance->isEmpty())
    <div class="card" style="text-align:center;padding:48px;color:var(--gray-400);">
        <div style="font-size:3rem;margin-bottom:12px;">📭</div>
        No attendance records found.
    </div>
@else

{{-- Course tabs --}}
<div class="tabs" id="attendanceTabs">
    @foreach($courseAttendance as $i => $ca)
    <button class="tab {{ $i === 0 ? 'active' : '' }}"
        onclick="switchAttTab('att-tab-{{ $i }}', this)">
        {{ $ca['course']->course_code ?? Str::limit($ca['course']->course_name, 14) }}
    </button>
    @endforeach
</div>

@foreach($courseAttendance as $i => $ca)
<div class="tab-content {{ $i === 0 ? 'active' : '' }}" id="att-tab-{{ $i }}">

    {{-- Course attendance summary --}}
    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:20px;">
        <div class="card" style="flex:1;min-width:150px;">
            <div class="card-body" style="padding:18px;text-align:center;">
                <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;margin-bottom:4px;">Attendance Rate</div>
                @php
                    $rateColor = $ca['rate'] === null ? 'var(--gray-400)' : ($ca['rate'] >= 80 ? 'var(--green-600)' : ($ca['rate'] >= 60 ? '#f59e0b' : '#ef4444'));
                @endphp
                <div style="font-size:2rem;font-weight:700;color:{{ $rateColor }};">
                    {{ $ca['rate'] !== null ? $ca['rate'] . '%' : '—' }}
                </div>
                @if($ca['rate'] !== null)
                <div style="font-size:.72rem;color:var(--gray-400);margin-top:4px;">
                    {{ $ca['rate'] >= 80 ? '✅ Good standing' : ($ca['rate'] >= 60 ? '⚠️ At risk' : '❗ Critical') }}
                </div>
                @endif
            </div>
        </div>

        @foreach([
            ['label' => 'Present', 'count' => $ca['present'], 'icon' => '✅', 'bg' => '#dcfce7'],
            ['label' => 'Absent',  'count' => $ca['absent'],  'icon' => '❌', 'bg' => '#fee2e2'],
            ['label' => 'Late',    'count' => $ca['late'],    'icon' => '⏰', 'bg' => '#fef3c7'],
            ['label' => 'Excused', 'count' => $ca['excused'], 'icon' => '📋', 'bg' => '#ede9fe'],
        ] as $stat)
        <div class="card" style="flex:1;min-width:100px;">
            <div class="card-body" style="padding:14px;text-align:center;">
                <div style="width:36px;height:36px;border-radius:10px;background:{{ $stat['bg'] }};display:flex;align-items:center;justify-content:center;margin:0 auto 6px;font-size:1.1rem;">{{ $stat['icon'] }}</div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--green-900);">{{ $stat['count'] }}</div>
                <div style="font-size:.72rem;color:var(--gray-400);">{{ $stat['label'] }}</div>
            </div>
        </div>
        @endforeach

        {{-- Attendance bar --}}
        @if($ca['total'] > 0)
        <div class="card" style="flex:2;min-width:220px;">
            <div class="card-body" style="padding:18px;">
                <div style="font-size:.78rem;font-weight:600;color:var(--green-900);margin-bottom:12px;">Breakdown</div>
                @php
                    $pPct = round($ca['present'] / $ca['total'] * 100);
                    $aPct = round($ca['absent']  / $ca['total'] * 100);
                    $lPct = round($ca['late']    / $ca['total'] * 100);
                    $ePct = round($ca['excused'] / $ca['total'] * 100);
                @endphp
                <div style="height:10px;border-radius:99px;overflow:hidden;display:flex;gap:2px;margin-bottom:10px;">
                    @if($pPct) <div style="flex:{{ $pPct }};background:#22c55e;border-radius:3px;" title="Present {{ $pPct }}%"></div> @endif
                    @if($lPct) <div style="flex:{{ $lPct }};background:#f59e0b;border-radius:3px;" title="Late {{ $lPct }}%"></div> @endif
                    @if($ePct) <div style="flex:{{ $ePct }};background:#8b5cf6;border-radius:3px;" title="Excused {{ $ePct }}%"></div> @endif
                    @if($aPct) <div style="flex:{{ $aPct }};background:#ef4444;border-radius:3px;" title="Absent {{ $aPct }}%"></div> @endif
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    @foreach([['Present','#22c55e',$pPct],['Late','#f59e0b',$lPct],['Excused','#8b5cf6',$ePct],['Absent','#ef4444',$aPct]] as [$l,$c,$p])
                    @if($p)
                    <span style="font-size:.72rem;display:flex;align-items:center;gap:4px;color:var(--gray-600);">
                        <span style="width:8px;height:8px;background:{{ $c }};border-radius:2px;display:inline-block;"></span>
                        {{ $l }}: {{ $p }}%
                    </span>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Records table --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Attendance Records — {{ $ca['course']->course_name }}</div>
            <span style="font-size:.75rem;color:var(--gray-400);">{{ $ca['total'] }} session{{ $ca['total'] != 1 ? 's' : '' }}</span>
        </div>
        <div class="card-body">
            @if($ca['records']->isEmpty())
                <div style="text-align:center;padding:28px;color:var(--gray-400);font-size:.85rem;">No records yet.</div>
            @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ca['records'] as $rec)
                        @php
                            $statusConfig = [
                                'present' => ['badge-green',  '✅ Present'],
                                'absent'  => ['badge-red',    '❌ Absent'],
                                'late'    => ['badge-amber',  '⏰ Late'],
                                'excused' => ['badge-purple', '📋 Excused'],
                            ];
                            [$badgeClass, $label] = $statusConfig[$rec->status] ?? ['badge-gray', ucfirst($rec->status)];
                        @endphp
                        <tr>
                            <td class="td-name">{{ \Carbon\Carbon::parse($rec->date)->format('M d, Y') }}</td>
                            <td style="color:var(--gray-400);font-size:.82rem;">{{ \Carbon\Carbon::parse($rec->date)->format('l') }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $label }}</span></td>
                            <td style="color:var(--gray-500);font-size:.82rem;">{{ $rec->remarks ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

@endif

@push('scripts')
<script>
function switchAttTab(targetId, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#attendanceTabs .tab').forEach(t => t.classList.remove('active'));
    document.getElementById(targetId)?.classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush
@endsection