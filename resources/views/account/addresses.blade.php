@extends('account.layout')

@section('account')
<h1 class="text-3xl font-semibold">Addresses</h1>
<p class="text-stone-600 mt-1">India is the delivery country. PIN codes are six digits.</p>
<form method="post" action="{{ route('account.addresses.store') }}" class="mt-6 bg-white border border-stone-200 rounded-xl p-5 grid sm:grid-cols-2 gap-4">
    @csrf
    <label>Name<input name="name" value="{{ old('name') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>Mobile<input name="mobile_number" value="{{ old('mobile_number') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label class="sm:col-span-2">House or building<input name="address" value="{{ old('address') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>Street or landmark<input name="locality" value="{{ old('locality') }}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>City<input name="city" value="{{ old('city') }}" maxlength="30" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label>State
        <select name="state_id" class="mt-1 w-full border rounded-lg px-3 py-2" required>
            <option value="">Select</option>
            @foreach($states as $state)<option value="{{ $state->id }}" @selected(old('state_id') == $state->id)>{{ $state->state_name }}</option>@endforeach
        </select>
    </label>
    <label>PIN code<input name="pincode" value="{{ old('pincode') }}" pattern="[0-9]{6}" class="mt-1 w-full border rounded-lg px-3 py-2" required></label>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="default_shipping" value="1"> Default shipping address</label>
    <button class="sm:col-span-2 bg-stone-900 text-white rounded-full px-5 py-3 w-fit" type="submit">Save address</button>
</form>
<div class="grid md:grid-cols-2 gap-4 mt-6">
    @forelse($addresses as $address)
        <article class="bg-white border border-stone-200 rounded-xl p-4">
            <p class="font-medium">{{ $address->name }} @if((int) $address->default_type === 1)<span class="text-xs text-blue-700">Default</span>@endif</p>
            <p class="text-sm text-stone-600 mt-1">{{ $address->address }}, {{ $address->locality }}, {{ $address->city }} {{ $address->pincode }}</p>
            <p class="text-sm text-stone-600">{{ $states[(int) $address->state_id]->state_name ?? '' }} · {{ $address->mobile_number }}</p>
            <form method="post" action="{{ route('account.addresses.destroy', $address->id) }}" class="mt-3">@csrf @method('DELETE')<button class="text-sm text-red-700" type="submit">Remove</button></form>
        </article>
    @empty
        <p class="text-stone-500">No saved addresses yet.</p>
    @endforelse
</div>
@endsection
