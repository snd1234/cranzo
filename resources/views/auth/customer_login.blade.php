@extends('layout.app')
@section('content')
<section class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold">Sign in</h1>
    @if(session('success'))<p class="mt-4 bg-green-50 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</p>@endif
    <form method="post" action="{{ route('customer.login.submit') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4">
        @csrf
        <label>Email<input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label>Password<input type="password" name="password" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Remember me</label>
        @if($errors->any())<div class="text-red-700 text-sm">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        <button class="bg-amber-500 text-stone-950 rounded-full px-5 py-3 font-medium" type="submit">Sign in</button>
    </form>
    <p class="mt-4 text-sm"><a class="text-blue-700" href="{{ route('password.request') }}">Forgot password</a> · <a class="text-blue-700" href="{{ route('customer.register') }}">Create an account</a></p>
</section>
@endsection
