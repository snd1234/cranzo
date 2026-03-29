@extends('layout.admin')

@section('content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">View User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('system-auth/users') }}">Users</a></li>
                <li class="breadcrumb-item active">View</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-user me-1"></i>
                        User Details
                    </div>
                    <div>
                        <a href="{{ url('system-auth/users') }}" class="btn btn-sm btn-secondary">Back to List</a>
                        <a href="{{ url('system-auth/edit-user/'.$user->id) }}" class="btn btn-sm btn-warning ms-1">Edit</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="w-25">Name</th>
                                        <td>{{ $user->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td>
                                            @if($user->role == 1)
                                                Admin
                                            @elseif($user->role == 2)
                                                Editor
                                            @elseif($user->role == 3)
                                                Viewer
                                            @else
                                                User
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ optional($user->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ optional($user->updated_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                    @if(!empty($user->phone) || !empty($user->address))
                                    <tr>
                                        <th>Contact</th>
                                        <td>
                                            @if(!empty($user->phone)) <div><strong>Phone:</strong> {{ $user->phone }}</div> @endif
                                            @if(!empty($user->address)) <div><strong>Address:</strong> {{ $user->address }}</div> @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
