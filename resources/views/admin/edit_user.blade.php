@extends('layout.admin')

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/users') }}">Users</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-user-edit me-1"></i>
                        Edit User
                    </div>
                    <div>
                        <a href="{{ url('admin/users') }}" class="btn btn-sm btn-secondary">Back to List</a>
                        <a href="{{ url('admin/view-user/'.$user->id) }}" class="btn btn-sm btn-info ms-1">View</a>
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

                    <form action="{{ url('admin/update-user/'.$user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-9">
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Username <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" readonly="readonly" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Password <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Role <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="role" class="form-select">
                                            @php $role = old('role', $user->role ?? 'Viewer'); @endphp
                                            <option value="1" {{ $role === 1 ? 'selected' : '' }}>Admin</option>
                                            <option value="2" {{ $role === 2 ? 'selected' : '' }}>Editor</option>
                                            <option value="3" {{ $role === 3 ? 'selected' : '' }}>Viewer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="status" class="form-select">
                                            @php $status = old('status', $user->status ?? 1); @endphp
                                            <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                    </div>
                                </div>

                                <!-- <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-10">
                                        <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div> -->

                                <div class="d-flex justify-content-end">
                                    <a href="{{ url('admin/users') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
                <div class="text-muted">Copyright &copy; Your Website {{ date('Y') }}</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var avatarInput = document.getElementById('avatar');
    var preview = document.getElementById('avatarPreview');
    var fallback = document.getElementById('avatarFallback');

    if (!avatarInput) return;

    avatarInput.addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (!file) {
            if (preview) { preview.classList.add('d-none'); preview.src = ''; }
            if (fallback) { fallback.classList.remove('d-none'); }
            return;
        }

        var reader = new FileReader();
        reader.onload = function (ev) {
            if (fallback) fallback.classList.add('d-none');
            if (preview) {
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
            }
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection

@endsection
