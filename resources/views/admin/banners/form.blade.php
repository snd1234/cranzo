@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="mb-0">{{ $banner->exists ? 'Edit Banner' : 'Add Banner' }}</h2>
            <a href="{{ route('banners.index') }}" class="btn btn-secondary">Back to banners</a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form id="banner-form" action="{{ $banner->exists ? route('banners.update', $banner) : route('banners.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if($banner->exists) @method('PUT') @endif
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="mb-2"><label class="form-label">Title</label><input name="title" class="form-control" value="{{ old('title', $banner->title) }}" maxlength="255"></div>
                            <div class="mb-2"><label class="form-label">Subtitle</label><input name="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle) }}" maxlength="255"></div>
                            <div class="mb-2"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3" maxlength="1000">{{ old('description', $banner->description) }}</textarea></div>
                            <div class="row">
                                <div class="col-md-6 mb-2"><label class="form-label">Button text</label><input name="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}" maxlength="80"></div>
                                <div class="col-md-6 mb-2"><label class="form-label">Button URL</label><input name="button_url" class="form-control" value="{{ old('button_url', $banner->button_url) }}" placeholder="/products or https://..."></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2"><label class="form-label">Display order</label><input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}" required></div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" @selected(old('status', $banner->status) == 1)>Active</option>
                                        <option value="0" @selected(old('status', $banner->status) == 0)>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Desktop image {{ $banner->exists ? '' : '*' }}</label>
                                @if($banner->desktop_image)
                                    <img src="{{ $banner->imageUrl($banner->desktop_image) }}" alt="" class="d-block border rounded mb-2" style="width:100%;height:90px;object-fit:cover">
                                @endif
                                <input type="file" name="desktop_image" id="desktop_image" accept="image/jpeg,image/png,image/webp" class="form-control" {{ $banner->exists ? '' : 'required' }}>
                                <div id="desktopPreview" class="mt-2"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile image</label>
                                @if($banner->mobile_image)
                                    <img src="{{ $banner->imageUrl($banner->mobile_image) }}" alt="" class="d-block border rounded mb-2" style="width:100%;height:90px;object-fit:cover">
                                @endif
                                <input type="file" name="mobile_image" id="mobile_image" accept="image/jpeg,image/png,image/webp" class="form-control">
                                <div id="mobilePreview" class="mt-2"></div>
                                <div class="form-text">JPG, PNG, or WEBP. Maximum 4 MB. Leave unchanged to keep the current image.</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-primary" type="submit">{{ $banner->exists ? 'Save banner' : 'Add banner' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function preview(inputId, targetId) {
        const input = document.getElementById(inputId);
        input.addEventListener('change', function () {
            const target = document.getElementById(targetId);
            target.innerHTML = '';
            const file = input.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.style.cssText = 'width:100%;height:90px;object-fit:cover';
                img.className = 'border rounded';
                target.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
    preview('desktop_image', 'desktopPreview');
    preview('mobile_image', 'mobilePreview');
    document.getElementById('banner-form').addEventListener('submit', function () {
        const button = this.querySelector('[type="submit"]');
        button.disabled = true;
        button.textContent = 'Saving...';
    });
});
</script>
@endsection
