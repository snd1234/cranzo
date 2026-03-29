@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">Sub Categories</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/category') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Sub Categories</li>
                </ol>
            </div>
            <a href="{{ url('system-auth/add-sub-category') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Sub Category
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-list me-2 text-primary"></i>Sub Category List</h5>
            </div>

            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th width="10%">Status</th>
                                <th width="15%">Created</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subcategories as $subcategory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $subcategory['name'] }}</strong></td>
                                    <td>{{ $subcategory['category']['name']}}</td>
                                    <td>
                                        @if($subcategory['status'] == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ date("M d, Y", strtotime($subcategory['created_at'])) }}</td>
                                    <td>
                                        <a href="{{ url('system-auth/update-sub-category/' . encrypt($subcategory['id'])) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ url('system-auth/delete-sub-category/'.encrypt($subcategory['id'])) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this sub category?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox me-2"></i>No sub categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection