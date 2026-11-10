@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Subscriber</h2>
        <div class="card shadow-sm mt-3"><div class="card-body">
            <p><strong>Email:</strong> {{ $subscriber->email }}</p>
            <p><strong>Status:</strong> {{ $subscriber->status ? 'Subscribed' : 'Unsubscribed' }}</p>
            <p><strong>Consent for offers:</strong> {{ $subscriber->consent ? 'Yes' : 'No' }}</p>
            <p><strong>Subscribed:</strong> {{ $subscriber->subscribed_at?->format('M d, Y H:i') ?: '—' }}</p>
            <p><strong>Unsubscribed:</strong> {{ $subscriber->unsubscribed_at?->format('M d, Y H:i') ?: '—' }}</p>
        </div></div>
    </div>
</main>
@endsection
