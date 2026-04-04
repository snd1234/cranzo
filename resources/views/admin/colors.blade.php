@extends('layout.admin')

@section('content')

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Colors</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Colors</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-palette me-1"></i>
                    Color List
                </div>
                <a href="{{ url('system-auth/add-color') }}" class="btn btn-sm btn-primary">Add Color</a>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colors as $color)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $color->color_title }}</td>
                                <td>{{ $color->color_code }}</td>
                                <td><span style="display:inline-block; width:30px; height:30px; background-color:{{ $color->color_code }}; border:1px solid #ccc;"></span></td>
                                <td>
                                    @if($color->color_status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                
                                <td> 
                                    <a href="{{ url('system-auth/edit-color/'. encrypt($color->color_id)) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No colors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
    
@endsection