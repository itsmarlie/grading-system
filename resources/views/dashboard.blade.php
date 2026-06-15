@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="section active" id="section-dashboard">
  <div class="page-header">
    <h2>{{ $greeting }}, {{ $user->name }}! 👋</h2>
    <p>Here's what's happening at your school today — {{ now()->format('l, F j, Y') }}</p>
  </div>

  {{-- SUMMARY CARDS --}}
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon green">👥</div>
      <div>
        <div class="stat-label">Total Students</div>
        <div class="stat-value">{{ $studentsCount }}</div>
        <div class="stat-change">↑ {{ $studentsCount }} enrolled this year</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon teal">📖</div>
      <div>
        <div class="stat-label">Active Courses</div>
        <div class="stat-value">{{ $coursesCount }}</div>
        <div class="stat-change">Active this semester</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon lime">📝</div>
      <div>
        <div class="stat-label">Assignments</div>
        <div class="stat-value">{{ $assignmentsCount }}</div>
        <div class="stat-change">Total created assignments</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon emerald">📊</div>
      <div>
        <div class="stat-label">School Average</div>
        <div class="stat-value">{{ $averageGrade }}%</div>
        <div class="stat-change">↑ compared to last term</div>
      </div>
    </div>
  </div>

  {{-- SCHEDULE + UPCOMING --}}
  <div class="grid-2">
    <div class="card">
      <div class="card-header">
        <span class="card-title">Today's Schedule</span>
        <a href="{{ route('courses.index') }}" class="btn btn-outline btn-sm">Full Schedule</a>
      </div>
      <div class="card-body">
        <div class="schedule-list">
          @forelse($todaySchedule as $course)
          <div class="schedule-item">
            <span class="sched-time">{{ $course->schedule }}</span>
            <div class="sched-dot"></div>
            <span class="sched-subject">{{ $course->course_name }}</span>
            <span class="sched-room">{{ $course->room ?? '—' }}</span>
          </div>
          @empty
          <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">No classes scheduled for today.</p>
          @endforelse
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <span class="card-title">📌 Upcoming Assignments</span>
        <a href="{{ route('assignments.index') }}" class="btn btn-outline btn-sm">View All</a>
      </div>
      <div class="card-body">
        <div class="upcoming-list">
          @forelse($upcomingAssignments as $assignment)
          @php
            $isToday = $assignment->due_date->isToday();
            $isPast  = $assignment->due_date->isPast();
          @endphp
          <div class="upcoming-item">
            <div class="upcoming-type" style="background:#ecfccb;">📝</div>
            <div class="upcoming-info">
              <h5>{{ $assignment->title }}</h5>
              <p>{{ $assignment->course->course_name ?? '—' }}</p>
            </div>
            @if($isToday)
              <span class="upcoming-due due-today">Due Today</span>
            @elseif($isPast)
              <span class="upcoming-due due-ok">Closed</span>
            @else
              <span class="upcoming-due due-soon">{{ $assignment->due_date->format('M j') }}</span>
            @endif
          </div>
          @empty
          <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">No upcoming assignments this week.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  {{-- GRADE DISTRIBUTION + ANNOUNCEMENTS --}}
  <div class="grid-2">
    <div class="card">
      <div class="card-header"><span class="card-title">Grade Distribution</span></div>
      <div class="card-body">
        @php
          $total = $totalGrades ?: 1;
          $pct = fn($n) => round(($n / $total) * 100);
          $colors = ['A'=>'#16a34a','B'=>'#4ade80','C'=>'#86efac','D'=>'#fde68a','F'=>'#fca5a5'];
          $labels = ['A'=>'Excellent','B'=>'Good','C'=>'Average','D'=>'Below Avg','F'=>'Failing'];
          $circumference = 251;
          $offset = 0;
        @endphp
        <div class="donut-wrap">
          <svg class="donut-svg" width="110" height="110" viewBox="0 0 110 110">
            <circle cx="55" cy="55" r="40" fill="none" stroke="#dcfce7" stroke-width="18"/>
            @foreach($gradeDistribution as $letter => $count)
              @php
                $dash = round(($count / $total) * $circumference);
                $gap  = $circumference - $dash;
              @endphp
              <circle cx="55" cy="55" r="40" fill="none"
                stroke="{{ $colors[$letter] }}" stroke-width="18"
                stroke-dasharray="{{ $dash }} {{ $gap }}"
                stroke-dashoffset="{{ -$offset }}"
                stroke-linecap="round"/>
              @php $offset += $dash; @endphp
            @endforeach
            <text x="55" y="52" text-anchor="middle" font-size="13" font-weight="700" fill="#14532d">{{ $averageGrade }}</text>
            <text x="55" y="65" text-anchor="middle" font-size="8" fill="#6b7280">avg %</text>
          </svg>
          <div class="donut-legend">
            @foreach($gradeDistribution as $letter => $count)
            <div class="donut-leg-item">
              <div class="donut-swatch" style="background:{{ $colors[$letter] }}"></div>
              {{ $letter }} — {{ $labels[$letter] }} ({{ $pct($count) }}%)
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <span class="card-title">Recent Announcements</span>
        <a href="{{ route('announcements.index') }}" class="btn btn-outline btn-sm">View All</a>
      </div>
      <div class="card-body">
        <div class="announce-list">
          @forelse($recentAnnouncements as $ann)
          <div class="announce-item">
            <h4>📢 {{ $ann->title }}</h4>
            <p>{{ Str::limit($ann->body, 100) }}</p>
            <div class="announce-meta">
              Posted by {{ $ann->author->name }} · {{ $ann->created_at->format('F j, Y') }}
            </div>
          </div>
          @empty
          <p style="color:var(--gray-400);font-size:.85rem;">No announcements yet.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

</div>
@endsection