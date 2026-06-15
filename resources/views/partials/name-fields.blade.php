@props(['model' => null, 'showTitle' => false])

@if($showTitle)
<div class="col-md-2">
    <label class="form-label fw-medium">Title <small class="text-muted">(optional)</small></label>
    <input type="text" name="title" class="form-control"
           placeholder="Dr., Prof., Mr., Ms.…"
           value="{{ old('title', $model?->title) }}">
    <div class="form-text">e.g. Dr., Prof., Engr., Atty.</div>
</div>
@endif

<div class="{{ $showTitle ? 'col-md-3' : 'col-md-4' }}">
    <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
           value="{{ old('first_name', $model?->first_name) }}" required>
    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="{{ $showTitle ? 'col-md-3' : 'col-md-4' }}">
    <label class="form-label fw-medium">Middle Name <small class="text-muted">(optional)</small></label>
    <input type="text" name="middle_name" class="form-control"
           value="{{ old('middle_name', $model?->middle_name) }}"
           placeholder="Leave blank if none">
</div>

<div class="{{ $showTitle ? 'col-md-4' : 'col-md-4' }}">
    <label class="form-label fw-medium">Last Name <span class="text-danger">*</span></label>
    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
           value="{{ old('last_name', $model?->last_name) }}" required>
    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>