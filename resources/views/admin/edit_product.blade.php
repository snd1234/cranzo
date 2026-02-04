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
                        <a href="{{ url('admin/product') }}" class="btn btn-sm btn-secondary">Back to List</a>
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
                                            <div class="position-relative">
                                                <img src="{{ asset(config('uploads.path')  . $image->image_path) }}" alt="Preview" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border"  data-id="{{ $image->id }}">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 removeImage btn-outline-danger" data-id="{{ $image->id }}" aria-label="Close" style="border: 2px solid red; color: red;"></button>
                                            </div>
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
                                <div class="col-12"><h5>Catalogs : </h5> </div>
                                @if(isset($product_catalogs) && count($product_catalogs))
                                    @foreach($product_catalogs as $cataData)
                                        <div class="mt-2 d-flex align-items-center">
                                            <span class="me-2">{{ $cataData->catalog_title }}</span>
                                            <a href="{{ asset(config('uploads.path')  . $cataData->catalog_file) }}" target="_blank" class="btn btn-link">View</a>
                                            <button type="button" class="btn btn-sm btn-danger ms-2 removeCatalog" data-id="{{ $cataData->id }}">Remove</button>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Catalog Name </label>
                                            <input type="text" name="catalog[catalog_names][]" id="catalogName" class="form-control" value="{{ old('catalog_names.0', $product->catalog_name ?? '') }}" >
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Catalog</label>
                                            <div class="d-flex align-items-center">
                                                <input type="file" name="catalog[catalog_files][]" id="catalogFile" class="form-control me-2" accept="application/pdf">
                                                <button type="button" id="addMoreCatalog" class="btn btn-sm btn-primary">+</button>
                                            </div>
                                            <small class="text-muted">Only PDF files are allowed.</small>
                                        </div>
                                    </div>
                                    <div id="additionalCatalogs" class="mt-3"></div>
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
<script src="{{asset('admin/js/jquery.min.js')}}"></script>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addMoreCatalogButton = document.getElementById('addMoreCatalog');
        const additionalCatalogsContainer = document.getElementById('additionalCatalogs');

        if (addMoreCatalogButton && additionalCatalogsContainer) {
            addMoreCatalogButton.addEventListener('click', function () {
                const catalogRow = document.createElement('div');
                catalogRow.classList.add('row', 'mt-2');

                catalogRow.innerHTML = `
                    <div class="col-md-6">
                        <label class="form-label">Catalog Name</label>
                        <input type="text" name="catalog[catalog_names][]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Catalog</label>
                        <div class="d-flex align-items-center">
                            <input type="file" name="catalog[catalog_files][]" class="form-control me-2" accept="application/pdf">
                            <button type="button" class="btn btn-sm btn-danger removeCatalog">-</button>
                        </div>
                    </div>
                `;

                additionalCatalogsContainer.appendChild(catalogRow);

                // Add event listener to remove button
                catalogRow.querySelector('.removeCatalog').addEventListener('click', function () {
                    catalogRow.remove();
                });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const removeButtons = document.querySelectorAll('.removeCatalog');

        removeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const catalogId = this.getAttribute('data-id');
                const catalogRow = this.closest('div.mt-2');

                if (catalogId) {
                    // Send AJAX request to delete catalog
                    $.ajax({
                        url: '{{ url("admin/delete-catalog") }}',
                        type: 'DELETE',
                        data: {catalogId: catalogId},
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Remove the catalog row from the DOM
                                if (catalogRow) {
                                    catalogRow.remove();
                                }
                                alert(response.message);
                            } else {
                                alert('Failed to delete catalog.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the catalog.');
                        }
                    });
                }
            });
        });
    });

        
    document.addEventListener('DOMContentLoaded', function () {
        removeImageButtons = document.querySelectorAll('.removeImage');;
        removeImageButtons.forEach(function(button) { 
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-id');
                const imageContainer = this.parentElement;

                if (imageId) {
                    // Send AJAX request to delete image
                    $.ajax({
                        url: '{{ url("admin/delete-image") }}',
                        type: 'DELETE',
                        data: {imageId: imageId},
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Remove the image container from the DOM
                                if (imageContainer) {
                                    imageContainer.remove();
                                }
                                alert(response.message);
                            } else {
                                alert('Failed to delete image.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the image.');
                        }
                    });
                }
            });
        });
    }); 

</script>