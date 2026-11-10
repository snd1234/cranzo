@extends('account.layout')

@section('account')
<h1 class="text-3xl font-semibold">Profile</h1>
<form method="post" action="{{ route('account.profile.update') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid sm:grid-cols-2 gap-4">
    @csrf @method('PUT')
    <label>First name<input name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>Last name<input name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>Email<input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>Mobile<input name="mobile_number" value="{{ old('mobile_number', auth()->user()->mobile_number) }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label class="sm:col-span-2">Current password<input type="password" name="current_password" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <button class="sm:col-span-2 bg-stone-900 text-white rounded-full px-5 py-3 w-fit" type="submit">Save profile</button>
</form>
<form method="post" action="{{ route('account.password') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid gap-4 max-w-lg">
    @csrf @method('PUT')
    <h2 class="font-semibold">Change password</h2>
    <label>Current password<input type="password" name="current_password" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>New password<input type="password" name="password" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>Confirm password<input type="password" name="password_confirmation" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <button class="bg-amber-500 text-stone-950 rounded-full px-5 py-3 w-fit" type="submit">Update password</button>
</form>
@endsection
