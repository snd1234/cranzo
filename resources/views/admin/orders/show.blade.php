@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4">
    <h1 class="mt-4">Order {{ $row->order_number }}</h1>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <p><a href="{{ route('order.invoice', $row->id) }}">Download invoice</a></p>
    @foreach($details as $line)<p>{{ $line->product_title }} × {{ $line->order_quantity }} — ₹{{ $line->amount_per_item }}</p>@endforeach
    @if($adjust)<p>{{ $adjust->tax_label }} ₹{{ $adjust->tax }}. Discount ₹{{ $adjust->discount }}. Total ₹{{ $adjust->total }}.</p>@endif
    <form method="post" action="{{ route('order.desk.update', $row->id) }}" class="card mt-3"><div class="card-body row g-2">
        @csrf @method('PUT')
        <div class="col-md-3"><label class="form-label">Order status</label><select name="order_status" class="form-select">@foreach([1=>'Received',2=>'Confirmed',3=>'Closed'] as $value=>$label)<option value="{{ $value }}" @selected((int)$row->order_status===$value)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Delivery</label><select name="delivery_status" class="form-select">@foreach([1=>'Not shipped',2=>'Shipped',3=>'Delivered'] as $value=>$label)<option value="{{ $value }}" @selected((int)$row->delivery_status===$value)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">COD payment</label><select name="payment_status" class="form-select"><option value="1" @selected((int)$row->payment_status===1)>Pending</option><option value="2" @selected((int)$row->payment_status===2)>Received</option></select></div>
        <div class="col-md-6"><label class="form-label">Note</label><input name="note" class="form-control"></div>
        <div class="col-12"><button class="btn btn-primary" type="submit">Save</button></div>
    </div></form>
    <h2 class="h5 mt-4">History</h2>
    @forelse($events as $event)<p>{{ $event->created_at }} {{ $event->field }} {{ $event->from_value }} → {{ $event->to_value }} {{ $event->note }}</p>@empty<p>No changes yet.</p>@endforelse
</div></main>
@endsection
