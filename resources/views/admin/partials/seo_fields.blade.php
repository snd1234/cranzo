@php
    $record = $record ?? null;
@endphp
<div class="mb-2">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $record->slug ?? '') }}" placeholder="Generated from the name if left empty">
    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Lowercase words separated by hyphens. This becomes the public URL.</div>
</div>
<h6 class="mt-3 mb-2">SEO</h6>
<div class="mb-2">
    <label class="form-label">Meta title</label>
    <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $record->meta_title ?? '') }}" maxlength="255">
</div>
<div class="mb-2">
    <label class="form-label">Meta keywords</label>
    <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords', $record->meta_keywords ?? '') }}" maxlength="255">
</div>
<div class="mb-2">
    <label class="form-label">Meta description</label>
    <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="3" maxlength="500">{{ old('meta_description', $record->meta_description ?? '') }}</textarea>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    if (!nameInput || !slugInput) return;
    const slugify = function (value) {
        return value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    };
    nameInput.addEventListener('input', function () {
        if (!slugInput.dataset.touched) {
            slugInput.value = slugify(this.value);
        }
    });
    slugInput.addEventListener('input', function () {
        this.dataset.touched = '1';
    });
});
</script>
