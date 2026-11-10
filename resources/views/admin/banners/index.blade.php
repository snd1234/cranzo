@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">Banner Management</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Website Management</li>
                    <li class="breadcrumb-item active">Banners</li>
                </ol>
            </div>
            <a href="{{ route('banners.create') }}" class="btn btn-primary">Add Banner</a>
        </div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-4"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search title"></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Active</option>
                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-outline-primary" type="submit">Filter</button></div>
        </form>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th><th>Image</th><th>Title</th><th>Subtitle</th><th>Button</th><th>Order</th><th>Status</th><th>Created</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td><img src="{{ $banner->imageUrl($banner->desktop_image) }}" alt="" style="width:72px;height:40px;object-fit:cover" class="border rounded"></td>
                                <td>{{ $banner->title ?: '—' }}</td>
                                <td>{{ $banner->subtitle ?: '—' }}</td>
                                <td>{{ $banner->button_text ?: '—' }}</td>
                                <td>{{ $banner->sort_order }}</td>
                                <td><span class="badge {{ $banner->status ? 'bg-success' : 'bg-secondary' }}">{{ $banner->status ? 'Active' : 'Inactive' }}</span></td>
                                <td>{{ $banner->created_at?->format('M d, Y') }}</td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('banners.show', $banner) }}">View</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('banners.edit', $banner) }}">Edit</a>
                                    <form action="{{ route('banners.status', $banner) }}" method="post" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-dark" type="submit">{{ $banner->status ? 'Deactivate' : 'Activate' }}</button>
                                    </form>
                                    <form action="{{ route('banners.destroy', $banner) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this banner and its images?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No banners found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $banners->links() }}</div>
    </div>
</main>
@endsection
