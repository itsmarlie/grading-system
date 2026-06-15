@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Welcome, {{ $student->display_name }}! 👋</h2>
        <p>Here's your academic overview — {{ now()->format('l, F j, Y') }}</p>
    </div>

    {{-- STAT CARDS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon green">📖</div>
            <div>
                <div class="stat-label">Enrolled Courses</div>
                <div class="stat-value">{{ $totalCourses }}</div>
                <div class="stat-change">This semester</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon lime">📝</div>
            <div>
                <div class="stat-label">Assignments Due</div>
                <div class="stat-value">{{ $assignmentsDue }}</div>
                <div class="stat-change">Upcoming deadlines</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">📊</div>
            <div>
                <div class="stat-label">Overall Average</div>
                <div class="stat-value">{{ $overallAvg ?? '—' }}{{ $overallAvg ? '%' : '' }}</div>
                <div class="stat-change">Across all subjects</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal">🗂️</div>
            <div>
                <div class="stat-label">My Section</div>
                <div class="stat-value" style="font-size:1.1rem;">
                    {{ $student->section ?? '—' }}
                </div>
                <div class="stat-change">
                    {{ $student->course->course_name ?? 'Not assigned' }}
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">

        {{-- TODAY'S SCHEDULE --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Today's Schedule</span>
                <span style="font-size:.75rem;color:var(--gray-400);">{{ now()->format('l') }}</span>
            </div>
            <div class="card-body">
                <div class="schedule-list">
                    @forelse($todaySchedules as $sched)
                    <div class="schedule-item">
                        <div class="sched-dot"></div>
                        <div style="flex:1;">
                            <div class="sched-subject">{{ $sched['subject'] }}</div>
                            <div style="font-size:.72rem;color:var(--gray-400);">
                                {{ $counts['teacher'] ?? 0 }}
                                @if($sched['room'] !== '—') · {{ $sched['room'] }} @endif
                            </div>
                        </div>
                        <div class="sched-time">
                            {{ \Carbon\Carbon::parse($sched['time_start'])->format('h:i A') }}
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">
                        No classes scheduled for today.
                    </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- UPCOMING ASSIGNMENTS --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Upcoming Assignments</span>
                <a href="{{ route('my.grades') }}" class="btn btn-outline btn-sm">My Grades</a>
            </div>
            <div class="card-body">
                <div class="upcoming-list">
                    @forelse($upcomingAssignments as $assignment)
                    @php
                        $isToday = $assignment->due_date->isToday();
                        $isSoon  = $assignment->due_date->diffInDays(now()) <= 3;
                    @endphp
                    <div class="upcoming-item">
                        <div class="upcoming-type" style="background:#ecfccb;">📝</div>
                        <div class="upcoming-info">
                            <h5>{{ $assignment->title }}</h5>
                            <p>{{ $assignment->course->course_name ?? '—' }}</p>
                        </div>
                        @if($isToday)
                            <span class="upcoming-due due-today">Due Today</span>
                        @elseif($isSoon)
                            <span class="upcoming-due due-soon">{{ $assignment->due_date->format('M j') }}</span>
                        @else
                            <span class="upcoming-due due-ok">{{ $assignment->due_date->format('M j') }}</span>
                        @endif
                    </div>
                    @empty
                    <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">
                        No upcoming assignments.
                    </p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div class="grid-2">

        {{-- GRADE BREAKDOWN --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Grade Breakdown</span>
                <a href="{{ route('my.grades') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="card-body">
                @forelse($gradeDistribution as $g)
                <div class="progress-list" style="margin-bottom:12px;">
                    <div class="progress-info">
                        <span class="progress-name">{{ $g['category'] }}</span>
                        <span class="progress-val">{{ $g['avg_score'] }}%</span>
                    </div>
                    <div class="progress-bg">
                        <div class="progress-fill"
                             style="width:{{ min($g['avg_score'], 100) }}%;background:var(--green-500);">
                        </div>
                    </div>
                </div>
                @empty
                <p style="color:var(--gray-400);font-size:.85rem;padding:8px 0;">
                    No grades recorded yet.
                </p>
                @endforelse
            </div>
        </div>

        {{-- RECENT ANNOUNCEMENTS --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Recent Announcements</span>
                <a href="{{ route('announcements.index') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="card-body">
                <div class="announce-list">
                    @forelse($announcements as $ann)
                    <div class="announce-item">
                        <h4>{{ $ann->title }}</h4>
                        <p>{{ Str::limit($ann->body, 100) }}</p>
                        <div class="announce-meta">
                            {{ $ann->created_at->format('F j, Y') }}
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--gray-400);font-size:.85rem;">
                        No announcements yet.
                    </p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection