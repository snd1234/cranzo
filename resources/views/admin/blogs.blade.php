@extends('layout.admin')
@section('content')
 <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Page Content</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Page Content</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-blog me-1"></i>
                        Blog List
                    </div>
                    <a href="{{ url('admin/add-blog') }}" class="btn btn-sm btn-primary">Add Page Content</a>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($blogs as $blog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $blog->title }}</td>
                                    <td>
                                        @if($blog->type == 1)
                                            Blog
                                        @elseif($blog->type == 2)
                                            News
                                       @elseif($blog->type == 3)
                                            Webinar
                                       @elseif($blog->type == 4)
                                            Events
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $blog->slug }}</td>
                                    <td>
                                        @if($blog->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($blog->created_at)->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ url('admin/edit-blog/'.$blog->id) }}" class="btn btn-sm btn-warning ms-1">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No blogs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- If you use pagination (non-DataTables), render links here --}}
                    {{-- {{ $blogs->links() }} --}}
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
@endsection