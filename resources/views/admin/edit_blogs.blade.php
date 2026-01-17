@extends('layout.admin')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Page Content</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/blogs') }}">Page Content</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-box me-1"></i>
                        Edit Page Content
                    </div>
                    <div>
                        <a href="{{ url('admin/blogs') }}" class="btn btn-sm btn-secondary">Back to List</a>
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

                    <form action="{{ url('admin/update-blog/' . $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $blog->title) }}" required aria-required="true">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Page Category <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required aria-required="true">
                                        <option value="">-- Select Category --</option>
                                        <option value="1" {{ old('type', $blog->type) == '1' ? 'selected' : '' }}>Blog</option>
                                        <option value="2" {{ old('type', $blog->type) == '2' ? 'selected' : '' }}>News</option>
                                        <option value="3" {{ old('type', $blog->type) == '3' ? 'selected' : '' }}>Webinar</option>
                                        <option value="4" {{ old('type', $blog->type) == '4' ? 'selected' : '' }}>Events</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" id="blogImage" class="form-control" accept="image/*">
                                    @if ($blog->image)
                                        <img id="imagePreview" src="{{ asset('storage/'.$blog->image) }}" alt="Preview" class="img-fluid mt-2" style="max-height:160px;">
                                    @else
                                        <img id="imagePreview" src="" alt="Preview" class="img-fluid mt-2 d-none" style="max-height:160px;">
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status', $blog->status); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea name="short_description" class="form-control" rows="3" required aria-required="true">{{ old('short_description', $blog->short_description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" id="contentEditor" class="form-control" rows="10">{{ old('content', $blog->content) }}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/blogs') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Content</button>
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

        // Product image preview
        var productInput = document.getElementById('blogImage');
        var imagePreview = document.getElementById('imagePreview');
        if (productInput) {
            productInput.addEventListener('change', function (e) {
                var file = e.target.files[0];
                if (!file) { imagePreview.classList.add('d-none'); imagePreview.src = ''; return; }
                if (!file.type.startsWith('image/')) { alert('Select an image file.'); productInput.value = ''; return; }
                var reader = new FileReader();
                reader.onload = function (ev) {
                    imagePreview.src = ev.target.result;
                    imagePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>