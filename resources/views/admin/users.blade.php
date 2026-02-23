@extends('layout.admin')

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Users</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-users me-1"></i>
                        User List
                    </div>
                    <a href="{{ url('system-auth/add-user') }}" class="btn btn-sm btn-primary">Add User</a>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 1)
                                            Admin
                                        @elseif($user->role == 2)
                                            Editor
                                        @elseif($user->role == 3)
                                            Viewer
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status == 1)
                                            Active
                                        @else
                                            Inactive
                                        @endif
                                    </td>
                                    <td>{{ optional($user->created_at)->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ url('system-auth/view-user/'.$user->id) }}" class="btn btn-sm btn-info ms-1">View</a>
                                        <a href="{{ url('system-auth/edit-user/'.$user->id) }}" class="btn btn-sm btn-warning ms-1">Edit</a>
                                        <form action="{{ url('system-auth/delete-user/'.$user->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ms-1">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- If you use pagination (non-DataTables), render links here --}}
                    {{-- {{ $users->links() }} --}}
                </div>
            </div>
        </div>
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; Integrated Gulf Biosystems {{ date('Y') }}</div>
                <!-- <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div> -->
            </div>
        </div>
    </footer>
</div>

@endsection
