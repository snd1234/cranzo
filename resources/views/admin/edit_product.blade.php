@extends('layout.admin')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Product</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/products') }}">Products</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-box me-1"></i>
                        Edit Product
                    </div>
                    <div>
                        <a href="{{ url('admin/products') }}" class="btn btn-sm btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('admin/update-product/' . $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->title) }}" required aria-required="true">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $product->slug) }}" required aria-required="true">
                                    </div>
                                    <small class="text-muted">URL friendly identifier (lowercase, hyphens).</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required aria-required="true">
                                        <option value="">-- Select Category --</option>
                                        @if(isset($categories) && count($categories))
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sub Category <span class="text-danger">*</span></label>
                                    <select name="sub_category_id" class="form-select" required aria-required="true">
                                        <option value="">-- Select Sub Category --</option>
                                        @if(isset($subCategories) && count($subCategories))
                                            @foreach($subCategories as $subCat)
                                                <option value="{{ $subCat->id }}" {{ old('sub_category_id', $product->sub_category_id) == $subCat->id ? 'selected' : '' }}>{{ $subCat->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Product Images (Max 3)</label>
                                    <input type="file" name="images[]" id="productImages" class="form-control" accept="image/*" multiple>
                                    <div id="imagePreviewContainer" class="mt-2 d-flex gap-2 flex-wrap">
                                        @foreach($product_images as $image)
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Preview" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border">
                                        @endforeach
                                    </div>
                                    <small class="text-muted">You can upload up to 3 images.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status', $product->status); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea name="short_description" class="form-control" rows="3" required aria-required="true">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" id="contentEditor" class="form-control" rows="10">{{ old('content', $product->description) }}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/product') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Product</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; gulfbiosystem {{ date('Y') }}</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // CKEditor init
        var editorEl = document.querySelector('#contentEditor');
        if (editorEl) {
            ClassicEditor.create(editorEl, {
                // toolbar config as needed
            }).catch(error => { console.error(error); });
        }

        var productImagesInput = document.getElementById('productImages');
        var imagePreviewContainer = document.getElementById('imagePreviewContainer');

        if (productImagesInput) {
            productImagesInput.addEventListener('change', function (e) {
                imagePreviewContainer.innerHTML = ''; // Clear previous previews
                var files = e.target.files;

                if (files.length > 3) {
                    alert('You can upload a maximum of 3 images.');
                    productImagesInput.value = ''; // Reset input
                    return;
                }

                Array.from(files).forEach(function (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Only image files are allowed.');
                        productImagesInput.value = ''; // Reset input
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function (ev) {
                        var img = document.createElement('img');
                        img.src = ev.target.result;
                        img.alt = 'Preview';
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.classList.add('rounded', 'border');
                        imagePreviewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }
    });
</script>