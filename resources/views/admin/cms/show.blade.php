@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h2 class="mb-0">{{ $page->title }}</h2>
            <a class="btn btn-outline-primary" href="{{ route('cms.edit', $page) }}">Edit</a>
        </div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card shadow-sm"><div class="card-body">
            <p><strong>Slug:</strong> {{ $page->slug }}</p>
            <p><strong>URL:</strong> <a href="{{ \App\Support\CmsPages::url($page->slug) }}">{{ \App\Support\CmsPages::url($page->slug) }}</a></p>
            <p><strong>Canonical:</strong> {{ $page->publicUrl() }}</p>
            <p><strong>Status:</strong> {{ $page->status ? 'Active' : 'Inactive' }}</p>
            <p><strong>Footer:</strong> {{ $page->show_in_footer ? 'Yes' : 'No' }}, {{ $page->footer_group }}, order {{ $page->footer_order }}</p>
            <p><strong>SEO title:</strong> {{ $page->meta_title ?: '—' }}</p>
            <p><strong>Meta description:</strong> {{ $page->meta_description ?: '—' }}</p>
            @if($page->imageUrl())<img src="{{ $page->imageUrl() }}" alt="" class="img-fluid border rounded mb-3" style="max-width:320px">@endif
            <div class="border rounded p-3">{!! \App\Support\HtmlCleaner::clean($page->content) !!}</div>
        </div></div>
    </div>
</main>
@endsection
