@extends('layout.admin')

@section('content')
@php
    $tones = [
        'new' => ['label' => 'New', 'badge' => 'text-bg-primary', 'edge' => '#0d6efd'],
        'read' => ['label' => 'Read', 'badge' => 'text-bg-secondary', 'edge' => '#6c757d'],
        'replied' => ['label' => 'Replied', 'badge' => 'text-bg-success', 'edge' => '#198754'],
        'closed' => ['label' => 'Closed', 'badge' => 'text-bg-dark', 'edge' => '#212529'],
    ];
@endphp
<main>
    <div class="container-fluid px-4">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mt-4 mb-3">
            <div>
                <h1 class="mb-1">Messages</h1>
                <p class="text-muted mb-0">Contact form enquiries from the shop.</p>
            </div>
            <span class="badge rounded-pill text-bg-primary fs-6">{{ number_format($unread) }} new</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-2" method="get">
                    <div class="col-lg-5">
                        <label class="form-label small text-muted mb-1" for="message-search">Search</label>
                        <input id="message-search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name, email, phone, or subject">
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label small text-muted mb-1" for="message-status">Status</label>
                        <select id="message-status" name="status" class="form-select">
                            <option value="">All messages ({{ number_format($statusCounts->sum()) }})</option>
                            @foreach($tones as $key => $tone)
                                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $tone['label'] }} ({{ number_format((int) ($statusCounts[$key] ?? 0)) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label small mb-1 d-none d-lg-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Filter</button>
                            @if(request()->filled('q') || request()->filled('status'))
                                <a class="btn btn-outline-secondary" href="{{ route('messages.index') }}">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @forelse($messages as $message)
            @php
                $status = $tones[$message->status ?? 'new'] ?? $tones['new'];
                $initial = strtoupper(substr(trim((string) $message->name), 0, 1) ?: '?');
            @endphp
            <article class="card mb-3 shadow-sm" style="border-left: 4px solid {{ $status['edge'] }};">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 align-items-start justify-content-between">
                        <div class="d-flex gap-3">
                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center fw-semibold" style="width: 46px; height: 46px; flex: 0 0 46px;">{{ $initial }}</div>
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h2 class="h5 mb-0">{{ $message->name }}</h2>
                                    <span class="badge {{ $status['badge'] }}">{{ $status['label'] }}</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    @if($message->phone)
                                        <span class="mx-1">·</span>{{ $message->phone }}
                                    @endif
                                </div>
                                <div class="mt-2 fw-semibold">{{ $message->subject ?: 'No subject' }}</div>
                            </div>
                        </div>
                        <div class="small text-muted text-lg-end">
                            {{ $message->created_at ? \Illuminate\Support\Carbon::parse($message->created_at)->format('d M Y, H:i') : '' }}
                        </div>
                    </div>

                    <div class="bg-light rounded p-3 mt-3 mb-3" style="white-space: pre-wrap;">{{ $message->message }}</div>

                    <form method="post" action="{{ route('messages.update', $message->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1" for="status-{{ $message->id }}">Status</label>
                                <select id="status-{{ $message->id }}" name="status" class="form-select">
                                    @foreach($tones as $key => $tone)
                                        <option value="{{ $key }}" @selected(($message->status ?? 'new') === $key)>{{ $tone['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small text-muted mb-1" for="note-{{ $message->id }}">Internal note</label>
                                <input id="note-{{ $message->id }}" name="admin_note" value="{{ $message->admin_note }}" class="form-control" maxlength="2000" placeholder="Only visible to staff">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-envelope-open fa-2x mb-3"></i>
                    <p class="mb-0">No messages match this filter.</p>
                </div>
            </div>
        @endforelse

        <div class="mt-3">{{ $messages->links('pagination::bootstrap-5') }}</div>
    </div>
</main>
@endsection
