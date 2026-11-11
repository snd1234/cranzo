@extends('account.layout')

@php
    $input = 'mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-slate-900 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100';
    $creating = old('_form') === 'create';
@endphp

@section('account')
<div class="mb-6">
    <p class="text-sm font-medium text-blue-700">My account</p>
    <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">Addresses</h1>
    <p class="mt-2 text-stone-600">Delivery is within India. A PIN code is six digits, and you can save up to 10 addresses.</p>
</div>

<form method="post" action="{{ route('account.addresses.store') }}" class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
    @csrf
    <input type="hidden" name="_form" value="create">
    <div class="px-5 py-4 border-b border-stone-100 bg-stone-50">
        <h2 class="font-semibold text-slate-900">Add an address</h2>
    </div>
    <div class="p-5 grid sm:grid-cols-2 gap-5">
        <label class="block text-sm font-medium text-slate-700">Recipient name
            <input name="name" value="{{ $creating ? old('name') : '' }}" maxlength="128" autocomplete="name" class="{{ $input }}" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">Mobile
            <input name="mobile_number" value="{{ $creating ? old('mobile_number') : '' }}" inputmode="numeric" maxlength="10" autocomplete="tel" placeholder="10-digit mobile" class="{{ $input }}" required>
        </label>
        <label class="sm:col-span-2 block text-sm font-medium text-slate-700">House or building
            <input name="address" value="{{ $creating ? old('address') : '' }}" maxlength="256" autocomplete="address-line1" class="{{ $input }}" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">Street or landmark
            <input name="locality" value="{{ $creating ? old('locality') : '' }}" maxlength="256" autocomplete="address-line2" class="{{ $input }}" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">City
            <input name="city" value="{{ $creating ? old('city') : '' }}" maxlength="30" autocomplete="address-level2" class="{{ $input }}" required>
        </label>
        <label class="block text-sm font-medium text-slate-700">State
            <select name="state_id" class="{{ $input }}" required>
                <option value="">Select a state</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" @selected($creating && (string) old('state_id') === (string) $state->id)>{{ $state->state_name }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-sm font-medium text-slate-700">PIN code
            <input name="pincode" value="{{ $creating ? old('pincode') : '' }}" inputmode="numeric" maxlength="6" pattern="[1-9][0-9]{5}" autocomplete="postal-code" class="{{ $input }}" required>
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="default_shipping" value="1" @checked($creating && old('default_shipping'))> Use as the default shipping address</label>
    </div>
    <div class="px-5 py-4 border-t border-stone-100 bg-stone-50 flex justify-end">
        <button class="bg-blue-700 text-white rounded-full px-6 py-2.5 font-medium hover:bg-blue-800" type="submit">Save address</button>
    </div>
</form>

<div class="grid md:grid-cols-2 gap-4 mt-6">
    @forelse($addresses as $address)
        @php $editing = old('_form') === 'address-'.$address->id; @endphp
        <article class="bg-white border border-stone-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between gap-3">
                <p class="font-semibold text-slate-900">{{ $address->name }}</p>
                @if((int) $address->default_type === 1)
                    <span class="text-xs font-medium rounded-full bg-blue-50 text-blue-800 px-2.5 py-1">Default</span>
                @endif
            </div>
            <p class="text-sm text-stone-600 mt-2">{{ $address->address }}, {{ $address->locality }}</p>
            <p class="text-sm text-stone-600">{{ $address->city }} {{ $address->pincode }}, {{ $states[(int) $address->state_id]->state_name ?? 'India' }}</p>
            <p class="text-sm text-stone-600 mt-1">{{ $address->mobile_number }}</p>
            <div class="mt-4 flex items-center gap-4 text-sm">
                <button type="button" class="text-blue-700 font-medium" onclick="document.getElementById('edit-address-{{ $address->id }}').classList.toggle('hidden')">Edit</button>
                <form method="post" action="{{ route('account.addresses.destroy', $address->id) }}" onsubmit="return confirm('Remove this address?')">
                    @csrf @method('DELETE')
                    <button class="text-red-700" type="submit">Remove</button>
                </form>
            </div>
            <form id="edit-address-{{ $address->id }}" method="post" action="{{ route('account.addresses.update', $address->id) }}" class="{{ $editing ? '' : 'hidden' }} mt-4 pt-4 border-t border-stone-100 grid gap-3">
                @csrf @method('PUT')
                <input type="hidden" name="_form" value="address-{{ $address->id }}">
                <label class="block text-sm font-medium text-slate-700">Recipient name<input name="name" value="{{ $editing ? old('name') : $address->name }}" maxlength="128" class="{{ $input }}" required></label>
                <label class="block text-sm font-medium text-slate-700">Mobile<input name="mobile_number" value="{{ $editing ? old('mobile_number') : $address->mobile_number }}" maxlength="10" inputmode="numeric" class="{{ $input }}" required></label>
                <label class="block text-sm font-medium text-slate-700">House or building<input name="address" value="{{ $editing ? old('address') : $address->address }}" maxlength="256" class="{{ $input }}" required></label>
                <label class="block text-sm font-medium text-slate-700">Street or landmark<input name="locality" value="{{ $editing ? old('locality') : $address->locality }}" maxlength="256" class="{{ $input }}" required></label>
                <label class="block text-sm font-medium text-slate-700">City<input name="city" value="{{ $editing ? old('city') : $address->city }}" maxlength="30" class="{{ $input }}" required></label>
                <label class="block text-sm font-medium text-slate-700">State
                    <select name="state_id" class="{{ $input }}" required>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" @selected((string) ($editing ? old('state_id') : $address->state_id) === (string) $state->id)>{{ $state->state_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm font-medium text-slate-700">PIN code<input name="pincode" value="{{ $editing ? old('pincode') : $address->pincode }}" maxlength="6" inputmode="numeric" class="{{ $input }}" required></label>
                <label class="flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="default_shipping" value="1" @checked($editing ? old('default_shipping') : (int) $address->default_type === 1)> Default shipping address</label>
                <button class="bg-blue-700 text-white rounded-full px-5 py-2.5 w-fit font-medium" type="submit">Update address</button>
            </form>
        </article>
    @empty
        <p class="text-stone-500">No saved addresses yet.</p>
    @endforelse
</div>
@endsection
