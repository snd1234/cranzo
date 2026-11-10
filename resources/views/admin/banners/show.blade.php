@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="mb-0">Banner {{ $banner->id }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('banners.index') }}" class="btn btn-secondary">Back to banners</a>
                <a href="{{ route('banners.edit', $banner) }}" class="btn btn-primary">Edit banner</a>
            </div>
        </div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Title</dt><dd class="col-sm-9">{{ $banner->title ?: '—' }}</dd>
                    <dt class="col-sm-3">Subtitle</dt><dd class="col-sm-9">{{ $banner->subtitle ?: '—' }}</dd>
                    <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $banner->description ?: '—' }}</dd>
                    <dt class="col-sm-3">Button text</dt><dd class="col-sm-9">{{ $banner->button_text ?: '—' }}</dd>
                    <dt class="col-sm-3">Button URL</dt><dd class="col-sm-9">{{ $banner->button_url ?: '—' }}</dd>
                    <dt class="col-sm-3">Display order</dt><dd class="col-sm-9">{{ $banner->sort_order }}</dd>
                    <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $banner->status ? 'Active' : 'Inactive' }}</dd>
                    <dt class="col-sm-3">Created</dt><dd class="col-sm-9">{{ $banner->created_at }}</dd>
                    <dt class="col-sm-3">Updated</dt><dd class="col-sm-9">{{ $banner->updated_at }}</dd>
                </dl>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="mb-1">Desktop image</p>
                        <img src="{{ $banner->imageUrl($banner->desktop_image) }}" alt="" class="img-fluid border rounded" style="max-height:220px;object-fit:cover">
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1">Mobile image</p>
                        @if($banner->mobile_image)
                            <img src="{{ $banner->imageUrl($banner->mobile_image) }}" alt="" class="img-fluid border rounded" style="max-height:220px;object-fit:cover">
                        @else
                            <p class="text-muted">No mobile image. The desktop image is used.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
