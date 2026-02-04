@extends('layout.admin')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Partner</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/partners') }}">Partners</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-edit me-1"></i>
                        Edit Partner
                    </div>
                    <div>
                        <a href="{{ url('admin/partners') }}" class="btn btn-sm btn-secondary">Back to List</a>
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

                    <form action="{{ url('admin/update-partner/' . $partner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="mb-3">
                                    <label class="form-label">Partner Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $partner->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Logo </label>
                                    <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                    <small class="text-muted">Upload a logo for the partner.</small>
                                   
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Website URL</label>
                                    <input type="url" name="website" id="website" class="form-control" value="{{ old('website', $partner->website_url) }}" placeholder="https://example.com">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $partner->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status', $partner->status); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/partners') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Partner</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var logoInput = document.getElementById('logo');
        var imagePreviewContainer = document.createElement('div');
        imagePreviewContainer.classList.add('mt-2', 'd-flex', 'gap-2', 'flex-wrap');
        logoInput.parentNode.appendChild(imagePreviewContainer);

        if (logoInput) {
            logoInput.addEventListener('change', function (e) {
                imagePreviewContainer.innerHTML = ''; // Clear previous previews
                var file = e.target.files[0];

                if (file && file.type.startsWith('image/')) {
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
                } else {
                    alert('Only image files are allowed.');
                    logoInput.value = ''; // Reset input
                }
            });
        }
    });
</script>