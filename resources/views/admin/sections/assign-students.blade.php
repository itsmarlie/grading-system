@extends('layouts.app')
@section('title', 'Assign Students — Section ' . $section->name)
@section('content')

<div class="section">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div>
            <h2>Assign Students to Section</h2>
            <p>Section {{ $section->name }} — {{ $section->course->course_code ?? '' }} {{ $section->course->course_name ?? '' }}</p>
        </div>
        <a href="{{ route('admin.sections.show', $section->id) }}" class="btn btn-outline btn-sm">← Back to Section</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="card-title">👥 Select Students</span>
            <span style="font-size:.8rem;color:var(--gray-400);">
                {{ count($assignedIds) }} assigned · max {{ $section->max_students }}
                @if(count($assignedIds) >= $section->max_students)
                    <span style="color:#dc2626;font-weight:600;">· Full</span>
                @endif
            </span>
        </div>
        <div class="card-body">

            @if($students->isEmpty())
                <div style="padding:24px;text-align:center;color:var(--gray-400);">
                    ⚠️ No students are enrolled in the course linked to this section
                    ({{ $section->course->course_name ?? 'Unknown' }}).
                    Assign students to the course first.
                </div>
            @else

            {{-- Student type --}}
            <div style="margin-bottom:16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <label style="font-size:.83rem;font-weight:600;color:var(--gray-600);">Student Type:</label>
                <label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;">
                    <input type="radio" name="student_type_preview" value="regular" checked
                        style="accent-color:var(--green-600);" onchange="setType('regular')">
                    Regular
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:.83rem;cursor:pointer;">
                    <input type="radio" name="student_type_preview" value="irregular"
                        style="accent-color:var(--green-600);" onchange="setType('irregular')">
                    Irregular
                </label>
            </div>

            {{-- Search --}}
            <div style="margin-bottom:16px;">
                <input type="text" id="studentSearch" class="form-input"
                    placeholder="🔍 Search by name or student number..."
                    style="max-width:360px;"
                    oninput="filterStudents(this.value)">
            </div>

            {{-- Select all / deselect all --}}
            <div style="display:flex;gap:10px;margin-bottom:12px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="selectAll()">Select All</button>
                <button type="button" class="btn btn-outline btn-sm" onclick="deselectAll()">Deselect All</button>
                <span id="selectedCount" style="font-size:.83rem;color:var(--gray-500);align-self:center;">
                    {{ count($assignedIds) }} selected
                </span>
                <span id="capacityWarning" style="font-size:.83rem;color:#dc2626;align-self:center;display:none;">
                    ⚠️ Exceeds max capacity ({{ $section->max_students }})
                </span>
            </div>

            <form method="POST" action="{{ route('admin.sections.students.save', $section->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="student_type" id="studentTypeInput" value="regular">

                <div id="studentList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:10px;max-height:500px;overflow-y:auto;padding:4px;">
                    @foreach($students as $student)
                    <label class="student-card"
                        data-name="{{ strtolower($student->full_name) }} {{ strtolower($student->student_number ?? '') }}"
                        style="display:flex;align-items:center;gap:12px;padding:12px 14px;border:1.5px solid {{ in_array($student->id, $assignedIds) ? 'var(--green-400)' : 'var(--green-100)' }};border-radius:8px;cursor:pointer;transition:all .15s;background:{{ in_array($student->id, $assignedIds) ? 'var(--green-50)' : 'white' }};">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                            {{ in_array($student->id, $assignedIds) ? 'checked' : '' }}
                            onchange="updateCard(this)" style="width:16px;height:16px;accent-color:var(--green-600);">
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:.88rem;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $student->display_name }}
                            </div>
                            <div style="font-size:.75rem;color:var(--gray-400);">
                                {{ $student->student_number ?? 'No ID' }}
                                @if($student->year_level) · {{ $student->year_level }} @endif
                            </div>
                        </div>
                        @if(in_array($student->id, $assignedIds))
                        <span style="font-size:.68rem;background:var(--green-100);color:var(--green-700);padding:2px 8px;border-radius:20px;white-space:nowrap;">Assigned</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <div style="display:flex;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--green-100);">
                    <button type="submit" class="btn btn-primary">💾 Save Assignments</button>
                    <a href="{{ route('admin.sections.show', $section->id) }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
const maxStudents = {{ $section->max_students }};

function setType(val) {
    document.getElementById('studentTypeInput').value = val;
}

function updateCard(checkbox) {
    const card = checkbox.closest('.student-card');
    if (checkbox.checked) {
        card.style.borderColor = 'var(--green-400)';
        card.style.background  = 'var(--green-50)';
    } else {
        card.style.borderColor = 'var(--green-100)';
        card.style.background  = 'white';
    }
    updateCount();
}

function updateCount() {
    const count = document.querySelectorAll('input[name="student_ids[]"]:checked').length;
    document.getElementById('selectedCount').textContent = count + ' selected';
    const warn = document.getElementById('capacityWarning');
    if (warn) warn.style.display = count > maxStudents ? '' : 'none';
}

function selectAll() {
    document.querySelectorAll('input[name="student_ids[]"]').forEach(cb => {
        if (cb.closest('.student-card').style.display !== 'none') {
            cb.checked = true;
            updateCard(cb);
        }
    });
}

function deselectAll() {
    document.querySelectorAll('input[name="student_ids[]"]').forEach(cb => {
        cb.checked = false;
        updateCard(cb);
    });
}

function filterStudents(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.student-card').forEach(card => {
        card.style.display = (card.dataset.name || '').includes(q) ? '' : 'none';
    });
}
</script>
@endpush
@endsection