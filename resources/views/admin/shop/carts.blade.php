@extends('layout.admin')
@section('content')
<main><div class="container-fluid px-4"><h1 class="mt-4">Abandoned carts</h1>
<p>Carts with no order after {{ config('shop.abandoned_hours') }} hours. Nothing is emailed from this page.</p>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@foreach($carts as $cart)
<p>Session {{ \Illuminate\Support\Str::limit($cart->session_id, 12) }} · user {{ $cart->user_id ?: 'guest' }} · updated {{ $cart->updated_at }}
<form class="d-inline" method="post" action="{{ route('carts.recover', $cart->id) }}">@csrf<button class="btn btn-sm btn-outline-dark">Mark reviewed</button></form></p>
@endforeach
{{ $carts->links() }}
</div></main>@endsection
