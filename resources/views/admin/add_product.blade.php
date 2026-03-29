@extends('layout.admin')

@section('content')

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">Add Product</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/products') }}">Products</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
            <a href="{{ url('admin/products') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-plus me-2 text-primary"></i>Add New Product</h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ url('admin/save-product') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="mb-2">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                                <small class="text-muted">URL friendly identifier (lowercase, hyphens).</small>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Select Category --</option>
                                    @if(isset($categories) && count($categories))
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Sub Category <span class="text-danger">*</span></label>
                                <select name="sub_category_id" class="form-select @error('sub_category_id') is-invalid @enderror" required>
                                    <option value="">-- Select Sub Category --</option>
                                    @if(isset($subCategories) && count($subCategories))
                                        @foreach($subCategories as $subCat)
                                            <option value="{{ $subCat->id }}" {{ old('sub_category_id') == $subCat->id ? 'selected' : '' }}>{{ $subCat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Product Images (Max 3)</label>
                                <input type="file" name="images[]" id="productImages" class="form-control" accept="image/*" multiple>
                                <div id="imagePreviewContainer" class="mt-2 d-flex gap-2 flex-wrap"></div>
                                <small class="text-muted">You can upload up to 3 images.</small>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Status</label>
                                @php $status = old('status','1'); @endphp
                                <select name="status" class="form-select">
                                    <option value="1" {{ $status=='1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $status=='0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3" required>{{ old('short_description') }}</textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea name="content" id="contentEditor" class="form-control @error('content') is-invalid @enderror" rows="10">{{ old('content') }}</textarea>
                            </div>

                            <div class="col-12"><h5>Catalogs : </h5> </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Catalog Name </label>
                                        <input type="text" name="catalog[catalog_names][]" id="catalogName" class="form-control" value="{{ old('catalog_names.0', $product->catalog_name ?? '') }}">
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
                            <h5 class="mb-2 mt-4">SEO Details</h5>
                            <div class="mb-2">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ url('admin/products') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Add Product</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script src="{{asset('admin/js/jquery.min.js')}}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // CKEditor init
    var editorEl = document.querySelector('#contentEditor');
    if (editorEl) {
        ClassicEditor.create(editorEl, {}).catch(error => { console.error(error); });
    }

    // Product Images Preview
    var productImagesInput = document.getElementById('productImages');
    var imagePreviewContainer = document.getElementById('imagePreviewContainer');

    if (productImagesInput) {
        productImagesInput.addEventListener('change', function (e) {
            imagePreviewContainer.innerHTML = '';
            var files = e.target.files;

            if (files.length > 3) {
                alert('You can upload a maximum of 3 images.');
                productImagesInput.value = '';
                return;
            }

            Array.from(files).forEach(function (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Only image files are allowed.');
                    productImagesInput.value = '';
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (ev) {
                    var img = document.createElement('img');
                    img.src = ev.target.result;
                    img.style.width = '120px';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
                    img.classList.add('rounded', 'border');
                    imagePreviewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Slug generation
    function slugify(str) {
        return str.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '').replace(/-+/g, '-').replace(/^-+|-+$/g, '');
    }

    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            if (!slugInput.dataset.touched) {
                slugInput.value = slugify(this.value);
            }
        });

        slugInput.addEventListener('input', function () {
            this.dataset.touched = '1';
            this.value = slugify(this.value);
        });
    }


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
</script>
