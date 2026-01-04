@extends('layout.admin')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Add Product Category</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/product-category') }}">Product Categories</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-file-alt me-1"></i>
                        Add Product Category    
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

                    <form action="{{ url('admin/save-product-category') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required aria-required="true">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Main Category <span class="text-danger">*</span></label>
                                    <select name="main_category_id" class="form-select" required>
                                        <option value="">Select Type</option>
                                        @foreach ($mainCategory as $mainCategory)
                                            <option value="{{$mainCategory->id }}" {{ old('main_category_id') == $mainCategory->id ? 'selected' : '' }}>
                                                {{ $mainCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required aria-required="true">
                                        <!-- <button type="button" id="generateSlug" class="btn btn-outline-secondary">Generate</button> -->
                                    </div>
                                    <small class="text-muted">URL friendly identifier (lowercase, hyphens).</small>
                                </div>
                               
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="3" required aria-required="true" >{{ old('description') }}</textarea>
                                </div>

                               
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status','1'); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status=='1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status=='0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/users') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Add Category</button>
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
    // CKEditor init
    var editorEl = document.querySelector('#contentEditor');
    if (editorEl) {
        ClassicEditor.create(editorEl, {
            // toolbar config as needed
        }).catch(error => { console.error(error); });
    }

   
});

 // Slug generation
    function slugify(str) {
        return str.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start
            .replace(/-+$/, '');            // Trim - from end
    }
    var titleInput = document.getElementById('title');
    var slugInput = document.getElementById('slug');
    var genBtn = document.getElementById('generateSlug');

    if (titleInput && !slugInput.value) {
        titleInput.addEventListener('input', function () {
            // optionally auto-update slug while typing if slug is empty
            if (!slugInput.dataset.touched) {
                slugInput.value = slugify(titleInput.value);
            }
        });
    }
    if (slugInput) {
        slugInput.addEventListener('input', function () {
            slugInput.dataset.touched = '1';
        });
    }
    if (genBtn) {
        genBtn.addEventListener('click', function () {
            if (titleInput) slugInput.value = slugify(titleInput.value || slugInput.value);
        });
    }

    // Featured image preview
    var featuredInput = document.getElementById('featuredImage');
    var featuredPreview = document.getElementById('featuredPreview');
    if (featuredInput) {
        featuredInput.addEventListener('change', function (e) {
            var f = e.target.files[0];
            if (!f) { featuredPreview.classList.add('d-none'); featuredPreview.src = ''; return; }
            if (!f.type.startsWith('image/')) { alert('Select an image file.'); featuredInput.value = ''; return; }
            var reader = new FileReader();
            reader.onload = function (ev) {
                featuredPreview.src = ev.target.result;
                featuredPreview.classList.remove('d-none');
            };
            reader.readAsDataURL(f);
        });
    }
</script>