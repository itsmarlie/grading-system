@extends('layouts.app')
@section('title', 'Create Syllabus')
@section('content')

<div class="page-header">
    <h2>📚 Create Syllabus</h2>
    <p>Build a course syllabus with topics, materials, and grading criteria.</p>
</div>

<form method="POST" action="{{ route('syllabi.store') }}" id="syllabusForm">
@csrf

<div class="tabs" id="createTabs">
    <button type="button" class="tab active" onclick="switchCreateTab('ct-basic', this)">📋 Basic Info</button>
    <button type="button" class="tab" onclick="switchCreateTab('ct-topics', this)">📆 Topics</button>
    <button type="button" class="tab" onclick="switchCreateTab('ct-grading', this)">📊 Grading</button>
    <button type="button" class="tab" onclick="switchCreateTab('ct-materials', this)">📁 Materials</button>
</div>

{{-- TAB 1: Basic Info --}}
<div class="tab-content active" id="ct-basic">
    <div class="card">
        <div class="card-body" style="padding-top:22px;">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Course *</label>
                    <select name="course_id" class="form-select" required>
                        <option value="">Select course</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ old('course_id')==$c->id?'selected':'' }}>
                            {{ $c->course_name }} {{ $c->course_code ? '('.$c->course_code.')' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Syllabus Title *</label>
                    <input type="text" name="title" class="form-input" required
                        placeholder="e.g. BSIT 201 Syllabus — 2nd Sem 2025-2026"
                        value="{{ old('title') }}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Brief Description</label>
                <textarea name="description" class="form-textarea" rows="2"
                    placeholder="Short description of this syllabus document">{{ old('description') }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Course Overview</label>
                <textarea name="course_overview" class="form-textarea" rows="4"
                    placeholder="Describe what this course covers, its purpose, and what students can expect.">{{ old('course_overview') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Learning Outcomes</label>
                <textarea name="learning_outcomes" class="form-textarea" rows="5"
                    placeholder="List each learning outcome on a new line:&#10;Students will be able to explain...&#10;Students will demonstrate...&#10;Students will apply...">{{ old('learning_outcomes') }}</textarea>
                <div style="font-size:.72rem;color:var(--gray-400);margin-top:4px;">Tip: Put each outcome on a new line — they'll display as a checklist for students.</div>
            </div>
        </div>
    </div>
</div>

{{-- TAB 2: Topics --}}
<div class="tab-content" id="ct-topics">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Weekly Topics</div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addTopic()">＋ Add Week</button>
        </div>
        <div class="card-body">
            <div id="topicSlots"></div>
            <div style="text-align:center;padding:8px 0;">
                <button type="button" class="btn btn-outline" onclick="addTopic()">＋ Add Another Week</button>
            </div>
        </div>
    </div>
</div>

{{-- TAB 3: Grading Criteria --}}
<div class="tab-content" id="ct-grading">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Grading Components</div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addGrading()">＋ Add Component</button>
        </div>
        <div class="card-body">
            <div id="gradingSlots"></div>
            <div id="gradingTotal" style="margin-top:12px;padding:10px 14px;background:var(--green-50);border-radius:8px;font-size:.875rem;color:var(--green-800);font-weight:500;">
                Total weight: <strong id="totalWeight">0</strong>% (must equal 100%)
            </div>
        </div>
    </div>
</div>

{{-- TAB 4: Materials --}}
<div class="tab-content" id="ct-materials">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Course Materials</div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addMaterial()">＋ Add Material</button>
        </div>
        <div class="card-body">
            <div id="materialSlots"></div>
            <div style="text-align:center;padding:8px 0;">
                <button type="button" class="btn btn-outline" onclick="addMaterial()">＋ Add Another Material</button>
            </div>
        </div>
    </div>
</div>

{{-- Submit --}}
<div style="margin-top:20px;display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary">💾 Save Syllabus</button>
    <a href="{{ route('syllabi.index') }}" class="btn btn-outline">Cancel</a>
</div>

</form>

@push('scripts')
<script>
function switchCreateTab(id, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#createTabs .tab').forEach(t => t.classList.remove('active'));
    document.getElementById(id)?.classList.add('active');
    btn.classList.add('active');
}

let topicIdx = 0;
function addTopic() {
    const i = topicIdx++;
    const html = `
        <div class="section-block" style="margin-bottom:12px;" id="topic_${i}">
            <div class="form-row" style="margin-bottom:8px;">
                <div class="form-group" style="flex:0.3;">
                    <label class="form-label">Week #</label>
                    <input type="number" name="topics[${i}][week]" class="form-input" value="${i+1}" min="1">
                </div>
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Topic Title *</label>
                    <input type="text" name="topics[${i}][title]" class="form-input" placeholder="e.g. Introduction to Programming">
                </div>
                <div class="form-group" style="flex:0;">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('topic_${i}').remove()">✕</button>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:8px;">
                <label class="form-label">Description</label>
                <textarea name="topics[${i}][description]" class="form-textarea" rows="2"
                    placeholder="What will be covered this week?"></textarea>
            </div>
        </div>`;
    document.getElementById('topicSlots').insertAdjacentHTML('beforeend', html);
}

let gradingIdx = 0;
const defaultTypes = ['Quiz','Assignment','Exam','Project','Activity'];
function addGrading() {
    const i = gradingIdx++;
    const html = `
        <div class="section-block" style="margin-bottom:10px;" id="grading_${i}">
            <div class="form-row" style="align-items:flex-end;margin-bottom:0;">
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Component Type</label>
                    <input type="text" name="grading_criteria[${i}][type]" class="form-input"
                        placeholder="e.g. Quiz, Exam, Project" list="typeList_${i}">
                    <datalist id="typeList_${i}">
                        ${defaultTypes.map(t => `<option value="${t}">`).join('')}
                    </datalist>
                </div>
                <div class="form-group" style="flex:0.8;">
                    <label class="form-label">Weight (%)</label>
                    <input type="number" name="grading_criteria[${i}][weight]" class="form-input weight-input"
                        min="0" max="100" placeholder="e.g. 20" onchange="updateTotal()">
                </div>
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Description (optional)</label>
                    <input type="text" name="grading_criteria[${i}][description]" class="form-input"
                        placeholder="e.g. 3 quizzes, best 2 counted">
                </div>
                <div class="form-group" style="flex:0;">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="document.getElementById('grading_${i}').remove();updateTotal()">✕</button>
                </div>
            </div>
        </div>`;
    document.getElementById('gradingSlots').insertAdjacentHTML('beforeend', html);
}

function updateTotal() {
    const inputs = document.querySelectorAll('.weight-input');
    let total = 0;
    inputs.forEach(i => total += parseFloat(i.value || 0));
    document.getElementById('totalWeight').textContent = total;
    document.getElementById('gradingTotal').style.background = total === 100
        ? 'var(--green-100)' : (total > 100 ? '#fee2e2' : 'var(--green-50)');
}

let materialIdx = 0;
function addMaterial() {
    const i = materialIdx++;
    const html = `
        <div class="section-block" style="margin-bottom:10px;" id="material_${i}">
            <div class="form-row" style="align-items:flex-end;">
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Material Title *</label>
                    <input type="text" name="materials[${i}][title]" class="form-input"
                        placeholder="e.g. Week 1 Lecture Slides">
                </div>
                <div class="form-group" style="flex:0.8;">
                    <label class="form-label">Type</label>
                    <select name="materials[${i}][type]" class="form-select">
                        <option value="link">🔗 Link</option>
                        <option value="pdf">📄 PDF</option>
                        <option value="file">📎 File</option>
                        <option value="video">🎬 Video</option>
                        <option value="doc">📝 Document</option>
                    </select>
                </div>
                <div class="form-group" style="flex:3;">
                    <label class="form-label">URL or Path</label>
                    <input type="text" name="materials[${i}][url]" class="form-input"
                        placeholder="https://... or /storage/...">
                </div>
                <div class="form-group" style="flex:0;">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="document.getElementById('material_${i}').remove()">✕</button>
                </div>
            </div>
        </div>`;
    document.getElementById('materialSlots').insertAdjacentHTML('beforeend', html);
}

addTopic();
addGrading();
addMaterial();
</script>
@endpush
@endsection