@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">{{ $product->product_title }}</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/products') }}">Products</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url('system-auth/products') }}" class="btn btn-secondary">Back to products</a>
                <a href="{{ route('product.update', $product->id) }}" class="btn btn-primary">Edit product</a>
                @if($publicUrl)
                    <a href="{{ $publicUrl }}" class="btn btn-outline-primary" target="_blank" rel="noopener">View on shop</a>
                @endif
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Product ID</dt><dd class="col-sm-8">{{ $product->id }}</dd>
                            <dt class="col-sm-4">Slug</dt><dd class="col-sm-8">{{ $product->slug ?: '—' }}</dd>
                            <dt class="col-sm-4">SKU</dt><dd class="col-sm-8">{{ $product->sku_id }}</dd>
                            <dt class="col-sm-4">Category</dt><dd class="col-sm-8">{{ $categories->firstWhere('id', $product->category_id)?->name ?? '—' }}</dd>
                            <dt class="col-sm-4">Subcategory</dt><dd class="col-sm-8">{{ $subcategories->firstWhere('id', $product->sub_category_id)?->name ?? '—' }}</dd>
                            <dt class="col-sm-4">Ternary category</dt><dd class="col-sm-8">{{ $ternaryCategories->firstWhere('ternary_id', $product->ternary_category_id)?->ternary_title ?? '—' }}</dd>
                            <dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $product->status ? 'Active' : 'Inactive' }}</dd>
                            <dt class="col-sm-4">Featured</dt><dd class="col-sm-8">{{ $product->featured_item ? 'Yes' : 'No' }}</dd>
                            <dt class="col-sm-4">Cash on delivery</dt><dd class="col-sm-8">{{ $product->cod_available ? 'Available' : 'Not marked' }}</dd>
                            <dt class="col-sm-4">Regular price</dt><dd class="col-sm-8">₹{{ number_format($product->actual_price, 2) }}</dd>
                            <dt class="col-sm-4">Selling price</dt><dd class="col-sm-8">₹{{ number_format($product->discounted_price, 2) }}</dd>
                            <dt class="col-sm-4">Discount</dt><dd class="col-sm-8">{{ $product->discount_rate }}%</dd>
                            <dt class="col-sm-4">Dimensions</dt><dd class="col-sm-8">{{ trim(($product->length ?: '—').' x '.($product->width ?: '—').' x '.($product->height ?: '—').' '.($product->m_unit ?: '')) }}</dd>
                            <dt class="col-sm-4">Created</dt><dd class="col-sm-8">{{ $product->created_at }}</dd>
                            <dt class="col-sm-4">Updated</dt><dd class="col-sm-8">{{ $product->updated_at ?: '—' }}</dd>
                            <dt class="col-sm-4">Meta title</dt><dd class="col-sm-8">{{ $product->meta_title ?: '—' }}</dd>
                            <dt class="col-sm-4">Meta keywords</dt><dd class="col-sm-8">{{ $product->meta_keywords ?: '—' }}</dd>
                            <dt class="col-sm-4">Meta description</dt><dd class="col-sm-8">{{ $product->meta_description ?: '—' }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-header">Highlights</div>
                    <div class="card-body">{!! $product->product_highlights ?: '<p class="text-muted mb-0">No highlights.</p>' !!}</div>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-header">Description</div>
                    <div class="card-body">{!! $product->description !!}</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header">Stock</div>
                    <ul class="list-group list-group-flush">
                        @forelse($details as $detail)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $detail->color_title ?: 'Color '.$detail->color_id }}</span>
                                <strong>{{ $detail->product_quantity }}</strong>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No stock row.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-header">Images</div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        @forelse($images as $image)
                            <img src="{{ asset('uploads/products/'.$image->image) }}" alt="" style="width:88px;height:88px;object-fit:cover;" class="border rounded">
                        @empty
                            <p class="text-muted mb-0">No images uploaded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
