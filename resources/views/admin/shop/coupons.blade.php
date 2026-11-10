@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Coupons</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="post" action="{{ route('coupons.store') }}" class="row g-2 mb-4">@csrf
    <div class="col-md-2"><input name="code" class="form-control" placeholder="Code" required></div>
    <div class="col-md-2"><select name="type" class="form-select"><option value="percent">Percent</option><option value="fixed">Fixed</option></select></div>
    <div class="col-md-2"><input name="amount" class="form-control" placeholder="Amount" required></div>
    <div class="col-md-2"><input name="min_order" class="form-control" placeholder="Min order"></div>
    <div class="col-md-2"><input name="max_discount" class="form-control" placeholder="Max discount"></div>
    <div class="col-md-2"><button class="btn btn-primary" type="submit">Save</button></div>
</form>
<table class="table"><thead><tr><th>Code</th><th>Type</th><th>Amount</th><th>Status</th><th></th></tr></thead>
<tbody>@foreach($coupons as $coupon)<tr><td>{{ $coupon->code }}</td><td>{{ $coupon->type }}</td><td>{{ $coupon->amount }}</td><td>{{ $coupon->status ? 'Active' : 'Off' }}</td><td><form method="post" action="{{ route('coupons.status', $coupon->id) }}">@csrf<button class="btn btn-sm btn-outline-dark">Toggle</button></form></td></tr>@endforeach</tbody></table>
</div></main>@endsection
