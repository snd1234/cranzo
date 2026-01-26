@extends('layout.admin')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Product Category</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/product-category') }}">Product Categories</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-edit me-1"></i>
                        Edit Product Category    
                    </div>
                    <div>
                        <a href="{{ url('admin/product-category') }}" class="btn btn-sm btn-secondary">Back to List</a>
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

                    <form action="{{ url('admin/update-product-category/'.$category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required aria-required="true">
                                </div>
                                <!-- <div class="mb-3">
                                    <label class="form-label">Main Category <span class="text-danger">*</span></label>
                                    <select name="main_category_id" class="form-select" required>
                                        <option value="">Select Main Category</option>
                                        @foreach ($mainCategory as $mainCategory)
                                            <option value="{{ $mainCategory->id }}" {{ old('main_category_id', $category->main_category_id) == $mainCategory->id ? 'selected' : '' }}>
                                                {{ $mainCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="mb-3">
                                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $category->slug) }}" required aria-required="true">
                                    </div>
                                    <small class="text-muted">URL friendly identifier (lowercase, hyphens).</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Category Image</label>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*" multiple>
                                    <small class="text-muted">Upload an image for the category (optional).</small>
                                    <div id="imagePreviewContainer" class="mt-2 d-flex gap-2 flex-wrap">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $category->image_path) }}" alt="Preview" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border">
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="mb-3">
                                    <label class="form-label">Description </label>
                                    <textarea name="description" class="form-control" rows="3" aria-required="true">{{ old('description', $category->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status', $category->status); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status=='1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status=='0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/product-category') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Category</button>
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

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var productImagesInput = document.getElementById('image');
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
    document.addEventListener('DOMContentLoaded', function () {
        function slugifyLive(str) {
            return str
                .toLowerCase()
                .replace(/\s+/g, '-')        // space → -
                .replace(/[^a-z-]/g, '')    // allow only a-z and -
                .replace(/-+/g, '-');       // multiple - → single
        }

        function slugifyFinal(str) {
            return slugifyLive(str)
                .replace(/^-+|-+$/g, '');  // trim - only on blur
        }

        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        // Auto-generate slug from name
        if (nameInput && slugInput && !slugInput.value) {
            nameInput.addEventListener('input', function () {
                if (!slugInput.dataset.touched) {
                    slugInput.value = slugifyLive(this.value);
                }
            });
        }

        // Allow normal typing (including "-")
        if (slugInput) {
            slugInput.addEventListener('input', function () {
                this.dataset.touched = '1';
                this.value = slugifyLive(this.value);
            });

            // Clean up ONLY when user leaves field
            slugInput.addEventListener('blur', function () {
                this.value = slugifyFinal(this.value);
            });
        }

    });
</script>