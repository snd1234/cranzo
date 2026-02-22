@extends('layout.admin')

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Add Page</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('admin/page-management') }}">Pages</a>
                </li>
                <li class="breadcrumb-item active">Add</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-file-alt me-1"></i>
                        Add Page
                    </span>
                    <a href="{{ url('admin/page-management') }}" class="btn btn-sm btn-secondary">
                        Back to List
                    </a>
                </div>

                <div class="card-body">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('admin/save-page-data') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-lg-9">

                                {{-- Title --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Page Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           class="form-control"
                                           value="{{ old('title') }}"
                                           required>
                                </div>

                                {{-- Slug --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="slug"
                                           id="slug"
                                           class="form-control"
                                           value="{{ old('slug') }}"
                                           required>
                                    <small class="text-muted">
                                        URL friendly (lowercase, hyphen separated)
                                    </small>
                                </div>

                                {{-- Short Description --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Short Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description"
                                              class="form-control"
                                              rows="3"
                                              required>{{ old('description') }}</textarea>
                                </div>

                                {{-- Content --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Content <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="content" id="contentEditor" class="form-control" rows="10">{{ old('content') }}</textarea>
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status', '1'); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <hr>

                                {{-- SEO --}}
                                <h5 class="mb-3">SEO Details</h5>
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description') }}</textarea>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/page-management') }}" class="btn btn-secondary me-2">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Create Page
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
</div>

@endsection

{{-- CKEditor --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // CKEditor with image upload
    ClassicEditor.create(document.querySelector('#contentEditor'), {
        simpleUpload: {
            uploadUrl: "{{ route('admin.ckeditor.upload') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        }
    }).catch(error => {
        console.error(error);
    });

    // Slug generator
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function () {
        if (!slugInput.dataset.touched) {
            slugInput.value = slugify(this.value);
        }
    });

    slugInput.addEventListener('input', function () {
        slugInput.dataset.touched = true;
    });

});
</script>
