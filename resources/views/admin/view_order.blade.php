@extends('layout.admin')

@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">View Order Details</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('system-auth/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('system-auth/orders') }}">Order</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-user me-1"></i>
                    Customer Details
                </div>
                <div>
                    <a href="{{ url('system-auth/orders') }}" class="btn btn-sm btn-secondary">Back to List</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="w-25">Customer Name</th>
                                    <td>{{ $orderData->user->first_name }}</td>
                                    <th>Email Id</th>
                                    <td>{{ $orderData->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile Number</th>
                                    <td>{{ $orderData->user->mobile_number }}</td>
                                    <th>Address</th>
                                    <td>{{ $orderData->address->address }}, {{ $orderData->address->locality }}, {{ $orderData->address->city }}, {{ $orderData->address->state->state_name }} - {{ $orderData->address->pincode }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Address Type</th>
                                    <td colspan="2">{{ $orderData->address_type == 1 ? 'Home' : 'Office' }}</td>
                                </tr>
                                <tr>
                                    <th>Order Number</th>
                                    <td>{{ $orderData->order_number }}</td>
                                    <th>Total Received Amount</th>
                                    <td>Rs NNNNNN {{ $orderData->total_recieved_amt }}</td>
                                </tr>
                                <tr>
                                    <th>Booking Date</th>
                                    <td>{{ $orderData->booking_date }}</td>
                                    <th>Booking Type</th>
                                    <td>{{ $orderData->booking_type == 1 ? 'Online' : 'COD' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order Details -->
        <div class="card mb-4">
           
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr bgcolor="#FFF8DC">
                                    <th colspan="3" style="text-align: center;"><h4>Order Details</h4></th>
                                </tr>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                               
                                @forelse($orderData->orderDetail as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('uploads/product/' . $item->image) }}" alt="" style="width:8%; margin-right: 20px;">
                                            {{ $item->product_title }}
                                        </td>
                                        <td>{{ $item->order_quantity }}</td>
                                        <td>Rs {{ $item->order_quantity * $item->amount_per_item }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align: center;">No record found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection