@extends('layout.admin')

@section('content')

 <div id="layoutSidenav_content">

    <main>

        <div class="container-fluid px-4">

            <h1 class="mt-4">Product Categories</h1>

            <ol class="breadcrumb mb-4">

                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>

                <li class="breadcrumb-item active">Categories</li>

            </ol>



            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>

                        <i class="fas fa-list me-1"></i>

                        Category List

                    </div>

                    <a href="{{ url('admin/add-product-category') }}" class="btn btn-sm btn-primary">Add Category</a>

                </div>

                <div class="card-body">

                    <table id="datatablesSimple" class="table table-bordered table-striped">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Name</th>

                                <th>Status</th>

                                <th>Created</th>

                                <th>Actions</th>

                            </tr>

                        </thead>



                        <tbody>

                            @forelse($categories as $category)

                           

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $category->name }}</td>

                                    <td>

                                        @if($category->status == 1)

                                           <span class="badge bg-success">Active</span>

                                        @else

                                            <span class="badge bg-danger">Inactive</span>

                                        @endif

                                    </td>

                                    <td>{{ date("Y-m-d", strtotime($category->created_at)) }}</td>

                                    <td>

                                        <!-- <a href="{{ url('admin/view-category/'.$category->id) }}" class="btn btn-sm btn-info ms-1">View</a> -->

                                        <a href="{{ url('admin/edit-product-category/'.$category->id) }}" class="btn btn-sm btn-warning ms-1">Edit</a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">No categories found.</td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>



                    {{-- If you use pagination (non-DataTables), render links here --}}

                    {{-- {{ $categories->links() }} --}}

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

