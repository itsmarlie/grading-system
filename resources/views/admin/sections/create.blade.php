@extends('layouts.app')
@section('title', 'Create Section')

@section('content')
<div class="section">
    <div class="page-header">
        <h2>Create Section</h2>
        <p>Add a new section with multiple schedule slots.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card" style="max-width:740px;">
        <div class="card-header">
            <span class="card-title">Section Details</span>
            <a href="{{ route('admin.sections.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Course <span style="color:#dc2626;">*</span></label>
                        <select name="course_id" class="form-select" required>
                            <option value="">— Select Course —</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->code ?? '' }} {{ $c->name ?? $c->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Section Name <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="name" class="form-input"
                               placeholder="e.g. A, B, BSIT-2A"
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group" style="max-width:130px;">
                        <label class="form-label">Max Students</label>
                        <input type="number" name="max_students" class="form-input"
                               value="{{ old('max_students', 40) }}" min="1">
                    </div>
                </div>

                {{-- Schedule Slots --}}
                <div style="margin:20px 0 10px;">
                    <div style="font-weight:600;font-size:.85rem;color:var(--green-900);margin-bottom:10px;">
                        Weekly Schedule <span style="color:#dc2626;">*</span>
                    </div>
                    <div id="scheduleSlots"></div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addSlot()">+ Add Schedule Slot</button>
                </div>

                <div style="margin-top:24px;display:flex;gap:10px;justify-content:flex-end;">
                    <a href="{{ route('admin.sections.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Section</button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let slotIdx = 0;
const days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

function addSlot() {
    const i = slotIdx++;
    const dayOptions = days.map(d => `<option>${d}</option>`).join('');
    const html = `
        <div class="form-row" id="slot_${i}" style="align-items:flex-end;background:var(--green-50);padding:10px;border-radius:8px;margin-bottom:8px;">
            <div class="form-group" style="flex:1.2;">
                <label class="form-label">Day</label>
                <select name="schedules[${i}][day]" class="form-select">${dayOptions}</select>
            </div>
            <div class="form-group">
                <label class="form-label">Start Time</label>
                <input type="time" name="schedules[${i}][start]" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">End Time</label>
                <input type="time" name="schedules[${i}][end]" class="form-input" required>
            </div>
            <div class="form-group" style="flex:1.5;">
                <label class="form-label">Room</label>
                <input type="text" name="schedules[${i}][room]" class="form-input" placeholder="e.g. Room 301">
            </div>
            <div class="form-group" style="flex:0;padding-bottom:2px;">
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="document.getElementById('slot_${i}').remove()">✕</button>
            </div>
        </div>`;
    document.getElementById('scheduleSlots').insertAdjacentHTML('beforeend', html);
}

// Start with one slot
addSlot();
</script>
@endpush
@endsection