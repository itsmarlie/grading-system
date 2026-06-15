@extends('layouts.app')

@section('title', 'Add Course')

@section('content')
<div class="page-header">
    <h2>Add New Course</h2>
    <p>Fill in the course details and grading weights.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card" style="max-width:700px;">
    <div class="card-header">
        <span class="card-title">📖 Course Information</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            {{-- Row 1: Course Code + Course Name --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Course Code *</label>
                    <input type="text" name="course_code" value="{{ old('course_code') }}"
                        class="form-input" placeholder="e.g. MATH10" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Course Name *</label>
                    <input type="text" name="course_name" value="{{ old('course_name') }}"
                        class="form-input" placeholder="e.g. Mathematics 10" required>
                </div>
            </div>

            {{-- Row 2: Teacher + Units --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Assigned Teacher *</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Units *</label>
                    <input type="number" name="units" value="{{ old('units', 3) }}"
                        class="form-input" min="1" max="6" required>
                </div>
            </div>

            {{-- Sections & Schedules --}}
            <div class="form-group" style="margin-bottom:16px;">
                <div class="card" style="border:1px solid var(--border-color, #e2e8f0);border-radius:var(--radius-sm, 6px);">
                    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;">
                        <span style="font-weight:600;font-size:.9rem;">Sections &amp; Schedules</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addSection" style="font-size:.8rem;padding:4px 10px;">
                            + Add Section
                        </button>
                    </div>
                    <div class="card-body" id="sectionsContainer" style="padding:14px;">
                        {{-- Sections added dynamically --}}
                    </div>
                </div>
            </div>

            {{-- Row 3: Room --}}
            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Room</label>
                    <input type="text" name="room" value="{{ old('room') }}"
                        class="form-input" placeholder="e.g. Rm 201">
                </div>
            </div>

            {{-- Row 4: Term + School Year --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Term *</label>
                    <select name="term" class="form-select" required>
                        <option value="">Select Term</option>
                        <option value="1st Semester" {{ old('term') === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                        <option value="2nd Semester" {{ old('term') === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                        <option value="Summer" {{ old('term') === 'Summer' ? 'selected' : '' }}>Summer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">School Year *</label>
                    <input type="text" name="school_year" value="{{ old('school_year', '2025-2026') }}"
                        class="form-input" required>
                </div>
            </div>

            {{-- Row 5: Description (full width) --}}
            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea"
                        placeholder="Course description...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Grading Weights --}}
            <div style="background:var(--green-50);border-radius:var(--radius-sm);padding:16px;margin-bottom:14px;">
                <p style="font-size:.8rem;font-weight:600;color:var(--green-800);margin-bottom:12px;">
                    📊 Grading Weights (must total 100%)
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Written Work %</label>
                        <input type="number" name="ww_weight" value="{{ old('ww_weight', 25) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Performance Task %</label>
                        <input type="number" name="pt_weight" value="{{ old('pt_weight', 50) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quarterly Assessment %</label>
                        <input type="number" name="qa_weight" value="{{ old('qa_weight', 25) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary">Save Course</button>
                <a href="{{ route('courses.index') }}" class="btn btn-outline">Cancel</a>
            </div>

        </form>
    </div>
</div>

{{-- Section Template --}}
<template id="sectionTemplate">
    <div class="section-block" style="border:1px solid #dee2e6;border-radius:6px;padding:14px;margin-bottom:12px;position:relative;">
        <button type="button" class="remove-section"
            style="position:absolute;top:8px;right:8px;background:none;border:none;color:#dc3545;cursor:pointer;font-size:1rem;line-height:1;">
            &times;
        </button>
        <div class="form-row" style="margin-bottom:8px;">
            <div class="form-group">
                <label class="form-label" style="font-size:.8rem;">Section Name</label>
                <input type="text" name="sections[__SI__][name]" class="form-input" placeholder="e.g. A, B, Section 1" required>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-size:.8rem;">Max Students</label>
                <input type="number" name="sections[__SI__][max_students]" class="form-input" value="40" min="1">
            </div>
        </div>
        <div class="schedules-container"></div>
        <button type="button" class="btn btn-outline add-schedule" style="font-size:.8rem;padding:4px 10px;margin-top:4px;">
            + Add Schedule Slot
        </button>
    </div>
</template>

{{-- Schedule Row Template --}}
<template id="scheduleTemplate">
    <div class="schedule-row form-row" style="align-items:flex-end;margin-bottom:8px;">
        <div class="form-group" style="flex:1.5;">
            <label class="form-label" style="font-size:.8rem;">Day</label>
            <select name="sections[__SI__][schedules][__SCH__][day]" class="form-select">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option>{{ $day }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex:1;">
            <label class="form-label" style="font-size:.8rem;">Start</label>
            <input type="time" name="sections[__SI__][schedules][__SCH__][start]" class="form-input" required>
        </div>
        <div class="form-group" style="flex:1;">
            <label class="form-label" style="font-size:.8rem;">End</label>
            <input type="time" name="sections[__SI__][schedules][__SCH__][end]" class="form-input" required>
        </div>
        <div class="form-group" style="flex:1.5;">
            <label class="form-label" style="font-size:.8rem;">Room</label>
            <input type="text" name="sections[__SI__][schedules][__SCH__][room]" class="form-input" placeholder="e.g. Rm 201">
        </div>
        <div class="form-group" style="flex:0 0 auto;">
            <button type="button" class="btn btn-outline remove-schedule"
                style="color:#dc3545;border-color:#dc3545;padding:6px 10px;margin-top:22px;">
                🗑
            </button>
        </div>
    </div>
</template>

<script>
(function(){
    let si = 0;
    document.getElementById('addSection').addEventListener('click', () => {
        const tmpl = document.getElementById('sectionTemplate').innerHTML.replaceAll('__SI__', si);
        const div = document.createElement('div');
        div.innerHTML = tmpl;
        const block = div.firstElementChild;
        document.getElementById('sectionsContainer').appendChild(block);

        addSchedule(block, si, 0);

        block.querySelector('.remove-section').addEventListener('click', () => block.remove());
        block.querySelector('.add-schedule').addEventListener('click', () => {
            const count = block.querySelectorAll('.schedule-row').length;
            addSchedule(block, si, count);
        });
        si++;
    });

    function addSchedule(block, sectionIdx, schedIdx) {
        let sch = document.getElementById('scheduleTemplate').innerHTML
            .replaceAll('__SI__', sectionIdx)
            .replaceAll('__SCH__', schedIdx);
        const div = document.createElement('div');
        div.innerHTML = sch;
        const row = div.firstElementChild;
        row.querySelector('.remove-schedule').addEventListener('click', () => row.remove());
        block.querySelector('.schedules-container').appendChild(row);
    }
})();
</script>

@endsection