@extends('layout.admin')

@section('content')

 <div id="layoutSidenav_content">

    <main>

        <div class="container-fluid px-4">
            <h1 class="mt-4">Pages</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pages</li>
            </ol>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-alt me-1"></i>
                        Page List
                    </div>
                    <a href="{{ url('admin/add-page') }}" class="btn btn-sm btn-primary">Add Page</a>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->slug }}</td>
                                    <td>
                                        @if($page->status == 1)
                                           <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($page->created_at)->format('Y-m-d') }}</td>
                                    <td>
                                        <!-- <a href="{{ url('admin/view-page/'.$page->id) }}" class="btn btn-sm btn-info ms-1">View</a> -->

                                        <a href="{{ url('admin/edit-page/'.$page->id) }}" class="btn btn-sm btn-warning ms-1">Edit</a>

                                        <!-- <form action="{{ url('admin/delete-page/'.$page->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this page?')">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger ms-1">Delete</button>

                                        </form> -->

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">No pages found.</td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>



                    {{-- If you use pagination (non-DataTables), render links here --}}

                    {{-- {{ $pages->links() }} --}}

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

