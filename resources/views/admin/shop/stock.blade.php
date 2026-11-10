@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Stock at or below {{ $threshold }}</h1>
<ul>@foreach($rows as $row)<li>{{ $row->product_title }} — {{ $row->stock }}</li>@endforeach</ul>
<h2 class="h5">Recent movements</h2>
<ul>@foreach($moves as $move)<li>{{ $move->created_at }} product {{ $move->product_id }} {{ $move->quantity_change }} ({{ $move->reason }})</li>@endforeach</ul>
</div></main>@endsection
