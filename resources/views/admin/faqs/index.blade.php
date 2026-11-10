@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">FAQ</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Website Management</li>
                    <li class="breadcrumb-item active">FAQ</li>
                </ol>
            </div>
            <a href="{{ route('faqs.create') }}" class="btn btn-primary">Add question</a>
        </div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-4"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search question"></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Active</option>
                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-outline-primary" type="submit">Filter</button></div>
        </form>
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Order</th><th>Question</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td>{{ $faq->sort_order }}</td>
                            <td>{{ $faq->question }}</td>
                            <td>{{ $faq->status ? 'Active' : 'Inactive' }}</td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('faqs.edit', $faq) }}">Edit</a>
                                <form action="{{ route('faqs.status', $faq) }}" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-dark" type="submit">{{ $faq->status ? 'Deactivate' : 'Activate' }}</button></form>
                                <form action="{{ route('faqs.destroy', $faq) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this question?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Delete</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No questions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
        <div class="mt-3">{{ $faqs->links() }}</div>
    </div>
</main>
@endsection
