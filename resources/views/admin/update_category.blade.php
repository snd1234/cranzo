@extends('layout.admin')

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <div>
                    <h2 class="mb-0">Update Category</h2>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('system-auth/category') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Update</li>
                    </ol>
                </div>
                <a href="{{ url('system-auth/category') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-edit me-2 text-primary"></i>Update Category</h5>
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
                    <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="mb-2">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Status</label>
                                    @php $status = old('status', $category->status); @endphp
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ url('system-auth/category') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Category</button>
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