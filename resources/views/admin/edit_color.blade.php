@extends('layout.admin')

@section('content')

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Color</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('system-auth/colors') }}">Colors</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-palette me-1"></i>
                    Edit Color
                </div>
                <div>
                    <a href="{{ url('system-auth/colors') }}" class="btn btn-sm btn-secondary">Back to List</a>
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

                <form action="{{ url('system-auth/update-color/'. encrypt($color->color_id)) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-9">
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Title <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="color_title" class="form-control" value="{{ old('color_title', $color->color_title) }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Color Code <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="color" name="color_code" class="form-control form-control-color" value="{{ old('color_code', $color->color_code) }}" required>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="color_status" class="form-select">
                                        @php $status = old('color_status', $color->color_status ?? 1); @endphp
                                        <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ url('system-auth/colors') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection