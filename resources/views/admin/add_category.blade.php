@extends('layout.admin')

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <div>
                    <h2 class="mb-0">Add Category</h2>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('system-auth/category') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
                <a href="{{ url('system-auth/category') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-plus me-2 text-primary"></i>Add New Category</h5>
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
                    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="mb-2">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status','1'); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status=='1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status=='0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ url('system-auth/category') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Add Category</button>
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
                <div class="text-muted">Copyright &copy; Integrated Gulf Biosystems {{ date('Y') }}</div>
            </div>
        </div>
    </footer>
</div>

@endsection

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if (imageInput) {
        imageInput.addEventListener('change', function (e) {
            previewContainer.innerHTML = '';
            const file = e.target.files[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Only image files are allowed.');
                imageInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.objectFit = 'cover';
                img.classList.add('rounded', 'border');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    function slugify(str) {
        return str.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '').replace(/-+/g, '-').replace(/^-+|-+$/g, '');
    }

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
});
</script>