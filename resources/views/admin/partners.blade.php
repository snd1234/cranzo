@extends('layout.admin')

@section('content')

 <div id="layoutSidenav_content">

    <main>

        <div class="container-fluid px-4">

            <h1 class="mt-4">Partners</h1>

            <ol class="breadcrumb mb-4">

                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>

                <li class="breadcrumb-item active">Partners</li>

            </ol>



            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>

                        <i class="fas fa-handshake me-1"></i>

                        Partner List

                    </div>

                    <a href="{{ url('admin/add-partner') }}" class="btn btn-sm btn-primary">Add Partner</a>

                </div>

                <div class="card-body">

                    <table id="datatablesSimple" class="table table-bordered table-striped">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Name</th>

                                <th>Logo</th>

                                <th>Status</th>

                                <th>Created</th>

                                <th>Actions</th>

                            </tr>

                        </thead>



                        <tbody>

                            @forelse($partners as $partner)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $partner->name }}</td>

                                    <td><img src="{{ asset(config('uploads.path')  . $partner->logo) }}" alt="Logo" width="50"></td>

                                    <td>

                                        @if($partner->status == 1)

                                            Active

                                        @else

                                            Inactive

                                        @endif

                                    </td>

                                    <td>{{ optional($partner->created_at)->format('Y-m-d') }}</td>

                                    <td>

                                        <!-- <a href="{{ url('admin/view-partner/'.$partner->id) }}" class="btn btn-sm btn-info ms-1">View</a> -->

                                        <a href="{{ url('admin/edit-partner/'.$partner->id) }}" class="btn btn-sm btn-warning ms-1">Edit</a>

                                        <form action="{{ url('admin/delete-partner/'.$partner->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this partner?')">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger ms-1">Delete</button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">No partners found.</td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>



                    {{-- If you use pagination (non-DataTables), render links here --}}

                    {{-- {{ $partners->links() }} --}}

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