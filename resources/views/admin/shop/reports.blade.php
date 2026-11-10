@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Reports</h1>
<form class="row g-2 mb-3" method="get"><div class="col-md-3"><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div><div class="col-md-3"><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div><div class="col-md-2"><button class="btn btn-outline-primary">Filter</button></div></form>
<p>Orders in range: {{ $count }}. Recorded totals: ₹{{ number_format($gross, 2) }}. Customers: {{ $customers }}.</p>
<p>Older orders may have no stored total because tax and discounts were not saved then.</p>
<h2 class="h5">Best sellers in range</h2>
<ul>@foreach($best as $row)<li>{{ $row->product_title }} — {{ $row->sold }}</li>@endforeach</ul>
<p><a href="{{ route('reports.orders') }}">Export recent orders CSV</a></p>
</div></main>@endsection
