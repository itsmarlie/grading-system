<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EduGrade Report — {{ $generatedAt }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1f2937; background: white; }
        .header { background: #14532d; color: white; padding: 18px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 3px; }
        .header p { font-size: 10px; opacity: .8; }
        .meta { display: flex; justify-content: space-between; font-size: 9px; color: #6b7280; margin-bottom: 16px; padding: 0 4px; }
        h2 { font-size: 13px; color: #14532d; margin: 16px 0 8px; border-bottom: 1px solid #dcfce7; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 10px; }
        thead th { background: #f0fdf4; color: #166534; font-weight: 600; padding: 7px 10px; text-align: left; border-bottom: 1px solid #bbf7d0; }
        tbody td { padding: 6px 10px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:nth-child(even) td { background: #fafaf9; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 20px; font-size: 9px; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red   { background: #fee2e2; color: #991b1b; }
        .badge-blue  { background: #dbeafe; color: #1e40af; }
        .summary-row { display: flex; gap: 12px; margin-bottom: 16px; }
        .summary-box { flex: 1; border: 1px solid #dcfce7; border-radius: 8px; padding: 10px 14px; text-align: center; }
        .summary-box .val { font-size: 20px; font-weight: 700; color: #14532d; }
        .summary-box .lbl { font-size: 9px; color: #6b7280; margin-top: 2px; }
        .page-break { page-break-after: always; }
        .formula-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px 14px; margin-bottom: 14px; font-size: 10px; color: #166534; }
        .passed { color: #166534; font-weight: 700; }
        .failed { color: #991b1b; font-weight: 700; }
    </style>
</head>
<body>

<div class="header">
    <h1>📊 EduGrade — Academic Report</h1>
    <p>Generated: {{ $generatedAt }} · By: {{ $generatedBy }}</p>
</div>

<div class="meta">
    <span>EduGrade Management System</span>
    <span>Report Date: {{ $generatedAt }}</span>
</div>

<div class="formula-box">
    <strong>Grading Formula (DepEd Weighted):</strong>
    Final Grade = (Written Work × 30%) + (Performance Task × 50%) + (Quarterly Assessment × 20%) · Passing: 75%
</div>

@foreach($courseReports as $r)
<h2>{{ $r['course']->course_name }} {{ $r['course']->course_code ? '('.$r['course']->course_code.')' : '' }}</h2>

{{-- Summary row --}}
<div class="summary-row">
    <div class="summary-box">
        <div class="val">{{ $r['total'] }}</div>
        <div class="lbl">Students</div>
    </div>
    <div class="summary-box">
        <div class="val">{{ $r['average'] }}%</div>
        <div class="lbl">Class Average</div>
    </div>
    <div class="summary-box">
        <div class="val" style="color:#166534;">{{ $r['passed'] }}</div>
        <div class="lbl">Passed</div>
    </div>
    <div class="summary-box">
        <div class="val" style="color:#991b1b;">{{ $r['failed'] }}</div>
        <div class="lbl">Failed</div>
    </div>
    <div class="summary-box">
        <div class="val">{{ $r['att_rate'] !== null ? $r['att_rate'].'%' : '—' }}</div>
        <div class="lbl">Attendance Rate</div>
    </div>
</div>

{{-- Student grade table --}}
<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Student No.</th>
            <th>Written Work (30%)</th>
            <th>Performance Task (50%)</th>
            <th>Quarterly Assess. (20%)</th>
            <th>Final Grade</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($r['students'] as $sd)
        @php
            $final  = $sd['computed']['final'];
            $passed = $final !== null && $final >= 75;
        @endphp
        <tr>
            <td>{{ $sd['student']->full_name }}</td>
            <td>{{ $sd['student']->student_number }}</td>
            <td style="text-align:center;">{{ $sd['computed']['written_work'] !== null ? $sd['computed']['written_work'].'%' : '—' }}</td>
            <td style="text-align:center;">{{ $sd['computed']['performance_task'] !== null ? $sd['computed']['performance_task'].'%' : '—' }}</td>
            <td style="text-align:center;">{{ $sd['computed']['quarterly_assessment'] !== null ? $sd['computed']['quarterly_assessment'].'%' : '—' }}</td>
            <td style="text-align:center;" class="{{ $final !== null ? ($passed ? 'passed' : 'failed') : '' }}">
                {{ $final !== null ? $final.'%' : '—' }}
            </td>
            <td>
                @if($final !== null)
                    <span class="badge {{ $passed ? 'badge-green' : 'badge-red' }}">{{ $passed ? 'Passed' : 'Failed' }}</span>
                @else
                    —
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(!$loop->last)
<div class="page-break"></div>
@endif
@endforeach

</body>
</html>