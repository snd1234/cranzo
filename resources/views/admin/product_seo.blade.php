@extends('layout.admin')

@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">Product SEO</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/products') }}">Products</a></li>
                    <li class="breadcrumb-item active">SEO</li>
                </ol>
            </div>
            <a href="{{ url('system-auth/products') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">{{ $product->product_title }}</h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('product.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="col-lg-8">
                        <div class="mb-2">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}">
                            <div class="form-text">Public URL: /product-detail/{{ old('slug', $product->slug) }}</div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Meta title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Meta keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $product->meta_keywords) }}" maxlength="255">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Meta description</label>
                            <textarea name="meta_description" class="form-control" rows="3" maxlength="500">{{ old('meta_description', $product->meta_description) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ url('system-auth/products') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save SEO</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
