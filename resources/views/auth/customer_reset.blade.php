@extends('layout.app')
@section('content')
<section class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold">Choose a new password</h1>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label>Email<input type="email" name="email" value="{{ old('email', $email) }}" class="shop-field" required></label>
        <label>Password<input type="password" name="password" class="shop-field" required></label>
        <label>Confirm password<input type="password" name="password_confirmation" class="shop-field" required></label>
        @if($errors->any())<div class="text-red-700 text-sm">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        <button class="bg-stone-900 text-white rounded-full px-5 py-3" type="submit">Save password</button>
    </form>
</section>
@endsection
