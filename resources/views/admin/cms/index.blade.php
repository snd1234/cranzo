@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">CMS Pages</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Website Management</li>
                    <li class="breadcrumb-item active">CMS Pages</li>
                </ol>
            </div>
            <a href="{{ route('cms.create') }}" class="btn btn-primary">Add Page</a>
        </div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-4"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search title or slug"></div>
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
                            <th>ID</th><th>Title</th><th>Slug</th><th>URL</th><th>SEO title</th><th>Status</th><th>Created</th><th>Updated</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ $page->title }}</td>
                                <td>{{ $page->slug }}</td>
                                <td><a href="{{ \App\Support\CmsPages::url($page->slug) }}" target="_blank" rel="noopener">{{ \App\Support\CmsPages::url($page->slug) }}</a></td>
                                <td>{{ $page->meta_title ?: '—' }}</td>
                                <td><span class="badge {{ $page->status ? 'bg-success' : 'bg-secondary' }}">{{ $page->status ? 'Active' : 'Inactive' }}</span></td>
                                <td>{{ $page->created_at?->format('M d, Y') }}</td>
                                <td>{{ $page->updated_at?->format('M d, Y') }}</td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('cms.show', $page) }}">View</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('cms.edit', $page) }}">Edit</a>
                                    <form action="{{ route('cms.status', $page) }}" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-dark" type="submit">{{ $page->status ? 'Deactivate' : 'Activate' }}</button></form>
                                    <form action="{{ route('cms.destroy', $page) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Delete</button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No pages found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $pages->links() }}</div>
    </div>
</main>
@endsection
