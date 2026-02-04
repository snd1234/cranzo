@extends('layout.admin')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Add Page</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/pages') }}">Pages</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-file-alt me-1"></i>
                        Add Page
                    </div>
                    <div>
                        <a href="{{ url('admin/pages') }}" class="btn btn-sm btn-secondary">Back to List</a>
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

                    <form action="{{ url('admin/save-page-data') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required aria-required="true">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords (comma separated)</label>
                                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required aria-required="true">
                                        <!-- <button type="button" id="generateSlug" class="btn btn-outline-secondary">Generate</button> -->
                                    </div>
                                    <small class="text-muted">URL friendly identifier (lowercase, hyphens).</small>
                                </div>
                                <!-- <div class="mb-3">
                                    <label class="form-label">Page Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required aria-required="true">
                                        <option value="">-- Select Category --</option>
                                        @if(isset($categories) && count($categories))
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> -->
                                <div class="mb-3">
                                    <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="3" required aria-required="true" >{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" id="contentEditor" class="form-control" rows="10" >{{ old('content') }}</textarea>
                                </div>

                               

                                <!-- <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <input type="file" name="featured_image" id="featuredImage" class="form-control" accept="image/*">
                                    <img id="featuredPreview" src="" alt="Preview" class="img-fluid mt-2 d-none" style="max-height:160px;">
                                </div> -->

                                
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status','1'); @endphp
                                    <select name="status" class="form-select">
                                        <option value="published" {{ $status=='published' ? 'selected' : '' }}>Published</option>
                                        <option value="draft" {{ $status=='draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/users') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Create Template</button>
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