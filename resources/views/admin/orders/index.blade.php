@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4">
    <h1 class="mt-4">Orders</h1>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form class="row g-2 mb-3" method="get">
        <div class="col-md-3"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Order, name, email, phone"></div>
        <div class="col-md-2"><input class="form-control" type="date" name="from" value="{{ request('from') }}"></div>
        <div class="col-md-2"><input class="form-control" type="date" name="to" value="{{ request('to') }}"></div>
        <div class="col-md-2"><button class="btn btn-outline-primary" type="submit">Filter</button></div>
    </form>
    <div class="card"><div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Payment</th><th>Delivery</th><th></th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->first_name ?: 'Guest' }} {{ $order->email }}</td>
                <td>{{ $order->order_status }}</td>
                <td>{{ $order->payment_status }}</td>
                <td>{{ $order->delivery_status }}</td>
                <td><a href="{{ route('order.desk', $order->id) }}">View</a></td>
            </tr>
        @endforeach
        </tbody>
    </table></div></div>
    <div class="mt-3">{{ $orders->links() }}</div>
</div></main>
@endsection
