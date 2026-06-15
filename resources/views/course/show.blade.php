@extends('layouts.app')

@section('title', 'Course Details')

@section('content')
<div class="page-header">
    <h2>{{ $course->course_name }}</h2>
    <p>{{ $course->course_code }} · {{ $course->term }} · {{ $course->school_year }}</p>
</div>

<div class="grid-2" style="margin-bottom:20px;">
    <a href="{{ route('students.course.assign', $course->id) }}" class="btn btn-primary btn-sm">
    👥 Assign Students
    </a>
    <div class="card">
        <div class="card-header"><span class="card-title">📖 Course Details</span></div>
        <div class="card-body">
            @foreach([
                'Teacher' => $course->teacher->name ?? '—',
                'Units' => $course->units,
                'Schedule' => $course->schedule ?? '—',
                'Room' => $course->room ?? '—',
                'Term' => $course->term ?? '—',
                'School Year' => $course->school_year ?? '—',
            ] as $label => $value)
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">{{ $label }}</span>
                <span style="font-weight:500;">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">📊 Grading Weights</span></div>
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Written Work</span>
                <span class="badge badge-blue">{{ $course->ww_weight }}%</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--green-50);">
                <span style="color:var(--gray-500);">Performance Task</span>
                <span class="badge badge-purple">{{ $course->pt_weight }}%</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;">
                <span style="color:var(--gray-500);">Quarterly Assessment</span>
                <span class="badge badge-green">{{ $course->qa_weight }}%</span>
            </div>
        </div>
    </div>
</div>

{{-- Students enrolled --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <span class="card-title">👥 Enrolled Students ({{ $course->students->count() }})</span>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Year Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($course->students as $student)
                    <tr>
                        <td>{{ $student->student_number }}</td>
                        <td class="td-name">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->year_level }}</td>
                        <td><span class="badge badge-green">{{ ucfirst($student->status) }}</span></td>
                        <td>
                            <a href="{{ route('students.show', $student->id) }}"
                               class="btn btn-outline btn-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--gray-400);padding:24px;">
                            No students enrolled yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;">
    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary">Edit Course</a>
    <a href="{{ route('courses.index') }}" class="btn btn-outline">Back to List</a>
</div>
@endsection