@extends('layout.app')
@section('content')
<section class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-3xl font-semibold">Create an account</h1>
    <form method="post" action="{{ route('customer.register.submit') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4">
        @csrf
        <label>First name<input name="first_name" value="{{ old('first_name') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label>Last name<input name="last_name" value="{{ old('last_name') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label>Email<input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label>Mobile<input name="mobile_number" value="{{ old('mobile_number') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required placeholder="10-digit mobile"></label>
        <label>Password<input type="password" name="password" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label>Confirm password<input type="password" name="password_confirmation" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
        <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="terms" value="1" class="mt-1" required><span>I agree to the <a class="text-blue-700" href="{{ url('/terms-conditions') }}">terms</a>.</span></label>
        @if($errors->any())<div class="text-red-700 text-sm">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        <button class="bg-amber-500 text-stone-950 rounded-full px-5 py-3 font-medium" type="submit">Create account</button>
    </form>
</section>
@endsection
