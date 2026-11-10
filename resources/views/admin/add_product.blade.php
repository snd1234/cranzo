@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">Add Product</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/products') }}">Products</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
            <a href="{{ url('system-auth/products') }}" class="btn btn-secondary">Back to products</a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form id="product-form" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.partials.product_fields')
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ url('system-auth/products') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
