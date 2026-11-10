@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Returns</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@foreach($requests as $item)
<form method="post" action="{{ route('returns.update', $item->id) }}" class="card mb-3"><div class="card-body">
@csrf @method('PUT')
<p>Order {{ $item->order_id }} product {{ $item->product_id }} qty {{ $item->quantity }} — {{ $item->reason }}</p>
<p>{{ $item->details }}</p>
<select name="status" class="form-select mb-2">@foreach(['pending','approved','rejected','received','refund_recorded'] as $status)<option @selected($item->status===$status)>{{ $status }}</option>@endforeach</select>
<input name="admin_note" value="{{ $item->admin_note }}" class="form-control mb-2" placeholder="Internal note">
<button class="btn btn-primary">Update</button>
</div></form>
@endforeach
{{ $requests->links() }}
</div></main>@endsection
