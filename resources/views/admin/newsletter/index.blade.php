@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="mb-0">Newsletter Subscribers</h2>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Marketing</li>
                    <li class="breadcrumb-item active">Subscribers</li>
                </ol>
            </div>
            <a href="{{ route('newsletter.export', request()->query()) }}" class="btn btn-outline-primary">Export CSV</a>
        </div>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-4"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search email"></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Subscribed</option>
                    <option value="0" @selected(request('status') === '0')>Unsubscribed</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-outline-primary" type="submit">Filter</button></div>
        </form>
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Email</th><th>Status</th><th>Consent</th><th>Subscribed</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                        <tr>
                            <td>{{ $subscriber->email }}</td>
                            <td>{{ $subscriber->status ? 'Subscribed' : 'Unsubscribed' }}</td>
                            <td>{{ $subscriber->consent ? 'Yes' : 'No' }}</td>
                            <td>{{ $subscriber->subscribed_at?->format('M d, Y') }}</td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('newsletter.show', $subscriber) }}">View</a>
                                <form action="{{ route('newsletter.status', $subscriber) }}" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-dark" type="submit">{{ $subscriber->status ? 'Unsubscribe' : 'Activate' }}</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No subscribers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
        <div class="mt-3">{{ $subscribers->links() }}</div>
    </div>
</main>
@endsection
