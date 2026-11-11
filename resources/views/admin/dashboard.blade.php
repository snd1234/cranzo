@extends('layout.admin')

@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Live shop totals</li>
        </ol>

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="small text-white-50">Orders</div>
                        <div class="fs-3">{{ number_format($stats['orders']) }}</div>
                        <div class="small">{{ number_format($stats['today_orders']) }} placed today</div>
                    </div>
                    @if(\App\Support\Staff::allows('orders'))
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('order.index') }}">Open orders</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="small text-white-50">Recorded sales</div>
                        <div class="fs-3">₹{{ number_format($stats['sales'], 2) }}</div>
                        <div class="small">Totals stored on orders that are not cancelled</div>
                    </div>
                    @if(\App\Support\Staff::allows('reports'))
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('reports.index') }}">Open reports</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body">
                        <div class="small text-white-50">Products</div>
                        <div class="fs-3">{{ number_format($stats['active_products']) }}</div>
                        <div class="small">{{ number_format($stats['products']) }} in the catalogue</div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('product.index') }}">Open products</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="small text-white-50">Needs attention</div>
                        <div class="fs-3">{{ number_format($stats['open_orders']) }}</div>
                        <div class="small">Received and not yet shipped</div>
                    </div>
                    @if(\App\Support\Staff::allows('orders'))
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('order.index', ['order_status' => 1, 'delivery_status' => 1]) }}">Review open orders</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @foreach([
                ['Customers', number_format($stats['customers']), 'Registered accounts', null, true],
                ['Low stock', number_format($stats['low_stock']), $stats['out_of_stock'].' out of stock, threshold '.$stats['low_stock_threshold'], \App\Support\Staff::allows('stock') ? route('stock.index') : null, true],
                ['Returns', number_format($stats['returns']), 'Waiting for a decision', \App\Support\Staff::allows('orders') ? route('returns.index') : null, true],
                ['Messages', number_format($stats['messages']), 'New contact messages', \App\Support\Staff::allows('support') ? route('messages.index') : null, true],
                ['Reviews', number_format($stats['reviews']), 'Hidden until published', \App\Support\Staff::allows('support') ? route('reviews.index') : null, true],
                ['Coupons', number_format($stats['coupons']), 'Active coupon codes', \App\Support\Staff::allows('marketing') ? route('coupons.index') : null, true],
                ['Newsletter', number_format($stats['subscribers']), 'Subscribers', route('newsletter.index'), true],
                ['Catalogue', number_format($stats['categories']), $stats['subcategories'].' subcategories', route('category.index'), true],
                ['Banners', number_format($stats['banners']), 'Active homepage banners', route('banners.index'), true],
                ['Pages', number_format($stats['pages']), $stats['faqs'].' published FAQs', route('cms.index'), true],
            ] as [$label, $value, $note, $href, $show])
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="text-muted small">{{ $label }}</div>
                            <div class="fs-4">{{ $value }}</div>
                            <div class="small text-muted">{{ $note }}</div>
                            @if($href)
                                <a class="small" href="{{ $href }}">Open</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xl-7">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-shopping-cart me-1"></i> Latest orders</div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            @if(\App\Support\Staff::allows('orders'))
                                                <a href="{{ route('order.desk', $order->id) }}">{{ $order->order_number }}</a>
                                            @else
                                                {{ $order->order_number }}
                                            @endif
                                            <div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($order->booking_date)->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td>{{ trim(($order->first_name ?? '').' '.($order->last_name ?? '')) ?: ($order->email ?: 'Guest') }}</td>
                                        <td>{{ \App\Support\OrderLabel::summary($order) }}</td>
                                        <td class="text-end">{{ $order->total === null ? '—' : '₹'.number_format((float) $order->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">No orders yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-chart-bar me-1"></i> Orders, last 14 days</div>
                    <div class="card-body">
                        <div class="d-flex align-items-end gap-1" style="height: 140px;">
                            @foreach($orderDays as $day)
                                <div class="flex-fill d-flex flex-column justify-content-end h-100" title="{{ $day['label'] }}: {{ $day['count'] }}">
                                    <div class="bg-primary rounded-top" style="height: {{ max(4, (int) round($day['count'] / $orderDayMax * 100)) }}%;"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex gap-1 mt-2">
                            @foreach($orderDays as $day)
                                <div class="flex-fill text-center text-muted" style="font-size: 10px;">{{ $loop->even ? $day['label'] : '' }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-cube me-1"></i> Best selling products</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($bestSellers as $product)
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>{{ $product->product_title }}</span>
                                    <span class="text-nowrap ms-3">{{ (int) $product->sold }} sold</span>
                                </li>
                            @empty
                                <li class="list-group-item px-0 text-muted">No product sales recorded yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
