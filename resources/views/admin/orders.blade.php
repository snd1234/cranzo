@extends('layout.admin')

@section('content')

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Orders</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-shopping-cart me-1"></i>
                    Order List
                </div>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Order Status</th>
                            <th>Amount</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order['order_number'] }}</td>
                                <td>{{ $order['user']['first_name'] }} {{ $order['user']['middle_name'] }} {{ $order['user']['last_name'] }}</td>
                                <td>{{ $order['user']['email'] }}</td>
                                <td>{{ $order['user']['mobile_number'] }}</td>
                                <td>
                                    @if($order['order_status'] == 2)
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($order['order_status'] == 1)
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ "Amount" }}</td>
                                <td>{{ date('M d, Y', strtotime($order['booking_date'])) }}</td>
                                <td>
                                    <a href="{{ url('system-auth/view-order/'. encrypt($order['id'])) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

@endsection