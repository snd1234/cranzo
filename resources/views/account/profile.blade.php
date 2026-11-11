@extends('account.layout')

@section('account')
<div class="mb-6">
    <p class="text-sm font-medium text-blue-700">My account</p>
    <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">Profile</h1>
    <p class="mt-2 text-stone-600">Update the name, email, and mobile number used for your orders.</p>
</div>

<form method="post" action="{{ route('account.profile.update') }}" class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
    @csrf @method('PUT')
    <input type="hidden" name="_form" value="profile">
    <div class="px-5 py-4 border-b border-stone-100 bg-stone-50">
        <h2 class="font-semibold text-slate-900">Personal details</h2>
    </div>
    <div class="p-5 grid sm:grid-cols-2 gap-5">
        <label class="block text-sm font-medium text-slate-700">First name
            <input name="first_name" value="{{ old('_form') === 'profile' ? old('first_name') : auth()->user()->first_name }}" maxlength="80" autocomplete="given-name" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-slate-900 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">Last name
            <input name="last_name" value="{{ old('_form') === 'profile' ? old('last_name') : auth()->user()->last_name }}" maxlength="80" autocomplete="family-name" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-slate-900 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">Email
            <input type="email" name="email" value="{{ old('_form') === 'profile' ? old('email') : auth()->user()->email }}" maxlength="255" autocomplete="email" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-slate-900 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">Mobile
            <input name="mobile_number" inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" autocomplete="tel" value="{{ old('_form') === 'profile' ? old('mobile_number') : auth()->user()->mobile_number }}" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-slate-900 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required placeholder="10-digit mobile">
        </label>
    </div>
    <div class="px-5 py-4 border-t border-stone-100 bg-stone-50 flex justify-end">
        <button class="bg-blue-700 text-white rounded-full px-6 py-2.5 font-medium hover:bg-blue-800" type="submit">Save profile</button>
    </div>
</form>

<form method="post" action="{{ route('account.password') }}" class="mt-6 bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
    @csrf @method('PUT')
    <div class="px-5 py-4 border-b border-stone-100 bg-stone-50">
        <h2 class="font-semibold text-slate-900">Change password</h2>
        <p class="mt-1 text-sm text-stone-500">Use at least 8 characters, with a letter and a number.</p>
    </div>
    <div class="p-5 grid gap-5 max-w-lg">
        <label class="block text-sm font-medium text-slate-700">Current password
            <input type="password" name="current_password" autocomplete="current-password" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">New password
            <input type="password" name="password" autocomplete="new-password" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">Confirm password
            <input type="password" name="password_confirmation" autocomplete="new-password" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" required>
        </label>
    </div>
    <div class="px-5 py-4 border-t border-stone-100 bg-stone-50">
        <button class="bg-amber-500 text-stone-950 rounded-full px-6 py-2.5 font-medium hover:bg-amber-400" type="submit">Update password</button>
    </div>
</form>
@endsection
