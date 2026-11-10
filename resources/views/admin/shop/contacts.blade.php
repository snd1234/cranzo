@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Messages ({{ $unread }} new)</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form class="row g-2 mb-3" method="get"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div><div class="col-md-3"><select name="status" class="form-select"><option value="">Any</option>@foreach(['new','read','replied','closed'] as $status)<option @selected(request('status')===$status)>{{ $status }}</option>@endforeach</select></div><div class="col-md-2"><button class="btn btn-outline-primary">Filter</button></div></form>
@foreach($messages as $message)
    <form method="post" action="{{ route('messages.update', $message->id) }}" class="card mb-3"><div class="card-body">
        @csrf @method('PUT')
        <p><strong>{{ $message->name }}</strong> {{ $message->email }} {{ $message->phone }} — {{ $message->subject }}</p>
        <p>{{ $message->message }}</p>
        <div class="row g-2"><div class="col-md-3"><select name="status" class="form-select">@foreach(['new','read','replied','closed'] as $status)<option @selected(($message->status ?? 'new')===$status)>{{ $status }}</option>@endforeach</select></div><div class="col-md-6"><input name="admin_note" value="{{ $message->admin_note }}" class="form-control" placeholder="Internal note"></div><div class="col-md-2"><button class="btn btn-primary">Save</button></div></div>
    </div></form>
@endforeach
{{ $messages->links() }}
</div></main>@endsection
