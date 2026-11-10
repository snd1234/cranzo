@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">{{ $page->exists ? 'Edit page' : 'Add page' }}</h2>
        @if ($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
        <form method="post" action="{{ $page->exists ? route('cms.update', $page) : route('cms.store') }}" enctype="multipart/form-data" class="card shadow-sm mt-3" id="cms-form">
            @csrf
            @if($page->exists) @method('PUT') @endif
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Page title</label>
                    <input id="title" name="title" class="form-control" value="{{ old('title', $page->title) }}" required maxlength="255">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Page slug</label>
                    <input id="slug" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" maxlength="191" pattern="[a-z0-9]+(?:-[a-z0-9]+)*">
                    <div class="form-text">Leave blank to generate it from the title. You can edit it.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Short description</label>
                    <input name="excerpt" class="form-control" value="{{ old('excerpt', $page->excerpt) }}" maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">Page content</label>
                    <textarea id="content" name="content" class="form-control" rows="10">{{ old('content', $page->content) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Featured image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control" id="page-image">
                    @if($page->imageUrl())
                        <img src="{{ $page->imageUrl() }}" alt="" class="mt-2 border rounded" style="max-width:180px">
                    @endif
                    <img id="page-image-preview" alt="" class="mt-2 border rounded d-none" style="max-width:180px">
                </div>
                <div class="col-md-6">
                    <label class="form-label">SEO title</label>
                    <input name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}" maxlength="255">
                    <label class="form-label mt-3">SEO keywords</label>
                    <input name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords) }}" maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">SEO meta description</label>
                    <textarea name="meta_description" class="form-control" rows="3" maxlength="500">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Canonical URL</label>
                    <input name="canonical_url" class="form-control" value="{{ old('canonical_url', $page->canonical_url) }}" maxlength="255" placeholder="Leave blank to use this page URL">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" @selected(old('status', $page->status) == 1)>Active</option>
                        <option value="0" @selected(old('status', $page->status) == 0)>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Display in footer</label>
                    <select name="show_in_footer" class="form-select">
                        <option value="1" @selected(old('show_in_footer', $page->show_in_footer) == 1)>Yes</option>
                        <option value="0" @selected(old('show_in_footer', $page->show_in_footer) == 0)>No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Footer section</label>
                    <select name="footer_group" class="form-select">
                        <option value="support" @selected(old('footer_group', $page->footer_group) === 'support')>Customer Support</option>
                        <option value="information" @selected(old('footer_group', $page->footer_group) === 'information')>Information</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Footer order</label>
                    <input type="number" name="footer_order" class="form-control" min="0" max="9999" value="{{ old('footer_order', $page->footer_order) }}" required>
                </div>
            </div>
            <div class="card-footer"><button class="btn btn-primary" type="submit">Save page</button></div>
        </form>
    </div>
</main>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const title = document.getElementById('title');
    const slug = document.getElementById('slug');
    const slugify = function (value) {
        return value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    };
    if (slug.value) slug.dataset.touched = '1';
    title?.addEventListener('input', function () {
        if (!slug.dataset.touched) slug.value = slugify(this.value);
    });
    slug?.addEventListener('input', function () { this.dataset.touched = '1'; });
    const editor = document.querySelector('#content');
    if (editor && window.ClassicEditor) ClassicEditor.create(editor).catch(function () {});
    document.getElementById('page-image')?.addEventListener('change', function () {
        const file = this.files && this.files[0];
        const preview = document.getElementById('page-image-preview');
        if (!file || !preview) return;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    });
    document.getElementById('cms-form')?.addEventListener('submit', function () {
        const button = this.querySelector('[type=submit]');
        if (button) button.disabled = true;
    });
});
</script>
@endsection
