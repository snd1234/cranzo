@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Newsletter Campaigns</h2>
        <p class="text-muted">Saving a draft does not email anyone. Queue sends only to subscribed addresses that ticked the consent box, and only when the queue worker is running.</p>
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
        <form method="post" action="{{ route('newsletter.campaigns.store') }}" class="card shadow-sm mb-4">
            @csrf
            <div class="card-body">
                <label class="form-label">Subject</label>
                <input name="subject" class="form-control mb-3" value="{{ old('subject') }}" required maxlength="255">
                <label class="form-label">Email content</label>
                <textarea name="content" class="form-control mb-3" rows="6" required>{{ old('content') }}</textarea>
                <label class="form-label">Send after (optional)</label>
                <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
            </div>
            <div class="card-footer"><button class="btn btn-primary" type="submit">Save draft</button></div>
        </form>
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light"><tr><th>Subject</th><th>Status</th><th>Recipients</th><th>Scheduled</th><th></th></tr></thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->subject }}</td>
                            <td>{{ $campaign->status }}</td>
                            <td>{{ $campaign->recipient_count }}</td>
                            <td>{{ $campaign->scheduled_at?->format('M d, Y H:i') ?: '—' }}</td>
                            <td>
                                @if($campaign->status !== 'sent')
                                    <form method="post" action="{{ route('newsletter.campaigns.queue', $campaign) }}">@csrf<button class="btn btn-sm btn-outline-dark" type="submit">Queue send</button></form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No campaigns yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
        <div class="mt-3">{{ $campaigns->links() }}</div>
    </div>
</main>
@endsection
