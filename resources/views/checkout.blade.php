@extends('layout.app')

@section('content')
@php
    $postedAddress = old('address_id', '__none__');
    if ($postedAddress === '__none__') {
        $defaultAddress = $addresses->first(fn ($address) => (int) $address->default_type === 1) ?: $addresses->first();
        $selectedId = $defaultAddress ? (string) $defaultAddress->id : '';
    } else {
        $selectedId = (string) $postedAddress;
    }
    $showNew = $addresses->isEmpty() || $selectedId === '';
@endphp
<section class="checkout-page">
    <div class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
        <div>
            <ol class="checkout-steps" aria-label="Checkout progress">
                <li><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="is-current"><span aria-current="step">Details</span></li>
                <li><span>Confirmation</span></li>
            </ol>
            <h1>Checkout</h1>
            <form action="{{ route('checkout.place') }}" method="post" class="mt-5 grid gap-4" id="checkout-form" data-online-url="{{ route('checkout.online') }}" data-verify-url="{{ route('checkout.online.verify') }}" data-cancel-url="{{ route('checkout.online.cancel') }}" data-online="{{ !empty($onlineEnabled) ? '1' : '0' }}">
                @csrf
                @if($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 text-red-800 text-sm p-4 grid gap-1" role="alert">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif
                <div class="checkout-card">
                    <h2><i class="fa-solid fa-house" aria-hidden="true"></i> Delivery Address</h2>
                    @if($addresses->isNotEmpty())
                        <div class="grid gap-2 mb-4">
                            @foreach($addresses as $saved)
                                <label class="address-choice {{ (string) $selectedId === (string) $saved->id ? 'is-selected' : '' }}">
                                    <span class="flex gap-3">
                                        <input type="radio" name="address_id" value="{{ $saved->id }}" class="mt-1" @checked((string) $selectedId === (string) $saved->id)>
                                        <span>
                                            <span class="flex flex-wrap items-center gap-2">
                                                <span class="font-semibold">{{ $saved->name }}</span>
                                                @if((int) $saved->default_type === 1)
                                                    <span class="rounded-full bg-[#f3ead8] px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-[#8a6a24]">Default</span>
                                                @endif
                                            </span>
                                            <span class="block text-sm text-stone-600 mt-1">{{ $saved->address }}, {{ $saved->locality }}, {{ $saved->city }}{{ $saved->state_name ? ', '.$saved->state_name : '' }} {{ $saved->pincode }}</span>
                                            <span class="block text-sm text-stone-500">{{ $saved->mobile_number }}</span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                            <label class="address-choice">
                                <span class="flex gap-3 items-center">
                                    <input type="radio" name="address_id" value="" @checked($showNew)>
                                    <span class="font-medium">Enter a new address</span>
                                </span>
                            </label>
                        </div>
                    @endif
                    <div id="new-address" class="grid sm:grid-cols-2 gap-4 {{ $showNew ? '' : 'hidden' }}">
                        <label class="checkout-field sm:col-span-2">Name
                            <span class="checkout-input"><i class="fa-regular fa-user" aria-hidden="true"></i><input name="name" value="{{ old('name') }}" placeholder="Name" autocomplete="name" maxlength="128"></span>
                        </label>
                        <label class="checkout-field">Mobile
                            <span class="checkout-input"><i class="fa-solid fa-phone" aria-hidden="true"></i><input name="mobile_number" value="{{ old('mobile_number') }}" inputmode="numeric" maxlength="10" autocomplete="tel" placeholder="10-digit mobile"></span>
                        </label>
                        <label class="checkout-field">Pincode
                            <span class="checkout-input"><i class="fa-solid fa-location-dot" aria-hidden="true"></i><input name="pincode" value="{{ old('pincode') }}" inputmode="numeric" maxlength="6" autocomplete="postal-code" placeholder="Pincode"></span>
                        </label>
                        <label class="checkout-field sm:col-span-2">Address
                            <span class="checkout-input"><i class="fa-solid fa-location-dot" aria-hidden="true"></i><input name="address" value="{{ old('address') }}" autocomplete="street-address" maxlength="256" placeholder="Address"></span>
                        </label>
                        <label class="checkout-field">Locality
                            <span class="checkout-input"><i class="fa-solid fa-house" aria-hidden="true"></i><input name="locality" value="{{ old('locality') }}" maxlength="256" placeholder="Locality"></span>
                        </label>
                        <label class="checkout-field">City
                            <span class="checkout-input"><i class="fa-solid fa-location-dot" aria-hidden="true"></i><input name="city" value="{{ old('city') }}" maxlength="30" autocomplete="address-level2" placeholder="City"></span>
                        </label>
                        <label class="checkout-field sm:col-span-2">State
                            <span class="checkout-input"><i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                <select name="state_id">
                                    <option value="">Select state</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" @selected(old('state_id') == $state->id)>{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="checkout-card">
                    <h2><i class="fa-regular fa-credit-card" aria-hidden="true"></i> Payment</h2>
                    <div class="checkout-pay" id="checkout-methods">
                        @if(!empty($onlineEnabled))
                            <label><input type="radio" name="payment_method" value="online"> Pay online — UPI, cards, net banking and wallets</label>
                        @endif
                        <label class="is-on"><input type="radio" name="payment_method" value="cod" checked> Cash on delivery</label>
                    </div>
                    <p id="checkout-alert" class="text-sm text-red-700" hidden></p>
                    <button class="checkout-place" id="checkout-submit" type="submit">Place cash on delivery order</button>
                </div>
            </form>
        </div>
        <aside class="checkout-card checkout-summary">
            <h2>Order summary</h2>
            @foreach($items as $item)
                @php
                    $image = null;
                    if (! empty($item->image_name) && is_file(public_path('uploads/products/'.$item->image_name))) {
                        $image = asset('uploads/products/'.$item->image_name);
                    }
                @endphp
                <div class="checkout-line">
                    @if($image)
                        <img src="{{ $image }}" alt="">
                    @else
                        <span class="checkout-line-empty">No image</span>
                    @endif
                    <p>{{ $item->product_title }} × {{ $item->qty }}</p>
                    <strong>₹{{ number_format($item->line_total, 0) }}</strong>
                </div>
            @endforeach
            <p><span>Subtotal</span><span>₹{{ number_format($quote['subtotal'], 0) }}</span></p>
            <p><span>Discount</span><span>₹{{ number_format($quote['discount'], 0) }}</span></p>
            <p><span>{{ $quote['tax_label'] }}</span><span>₹{{ number_format($quote['tax'], 0) }}</span></p>
            <p class="checkout-total"><span>Total</span><strong>₹{{ number_format($quote['total'], 0) }}</strong></p>
            <ul class="checkout-trust">
                <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Genuine Products</li>
                <li><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Secure COD</li>
                <li><i class="fa-solid fa-truck" aria-hidden="true"></i> Fast Delivery</li>
                <li><i class="fa-solid fa-rotate-left" aria-hidden="true"></i> Easy Returns</li>
            </ul>
        </aside>
    </div>
</section>
<script>
document.querySelectorAll('[name=address_id]').forEach(function (choice) {
    choice.addEventListener('change', function () {
        const panel = document.getElementById('new-address');
        if (!panel) return;
        const show = choice.value === '';
        panel.classList.toggle('hidden', !show);
        panel.querySelectorAll('input, select').forEach(function (field) {
            field.required = show;
        });
    });
});
document.getElementById('new-address')?.querySelectorAll('input, select').forEach(function (field) {
    field.required = !document.getElementById('new-address').classList.contains('hidden');
});

const checkoutForm = document.getElementById('checkout-form');
const checkoutButton = document.getElementById('checkout-submit');
const checkoutAlert = document.getElementById('checkout-alert');
function selectedMethod() {
    return checkoutForm?.querySelector('[name=payment_method]:checked')?.value || 'cod';
}
function showAlert(message) {
    if (!checkoutAlert) return;
    checkoutAlert.hidden = !message;
    checkoutAlert.textContent = message || '';
}
checkoutForm?.querySelectorAll('[name=payment_method]').forEach(function (choice) {
    choice.addEventListener('change', function () {
        const online = selectedMethod() === 'online';
        checkoutForm.dataset.skipLock = online ? '1' : '';
        if (checkoutButton) checkoutButton.textContent = online ? 'Pay securely' : 'Place cash on delivery order';
        checkoutForm.querySelectorAll('#checkout-methods label').forEach(function (label) {
            label.classList.toggle('is-on', Boolean(label.querySelector('input')?.checked));
        });
    });
});
checkoutForm?.addEventListener('submit', function (event) {
    if (selectedMethod() !== 'online') return;
    event.preventDefault();
    if (checkoutForm.dataset.paying === '1') return;
    checkoutForm.dataset.paying = '1';
    if (checkoutButton) {
        checkoutButton.disabled = true;
        checkoutButton.textContent = 'Opening secure payment...';
    }
    showAlert('');
    const token = checkoutForm.querySelector('[name=_token]')?.value || '';
    fetch(checkoutForm.dataset.onlineUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
        body: new FormData(checkoutForm)
    }).then(function (response) {
        return response.json().then(function (data) { return { ok: response.ok, data: data }; });
    }).then(function (result) {
        if (!result.ok) {
            const errors = result.data.errors || {};
            const first = Object.values(errors)[0];
            throw new Error(Array.isArray(first) ? first[0] : (result.data.message || 'Payment could not be started.'));
        }
        openRazorpay(result.data);
    }).catch(function (error) {
        releaseCheckout(error.message || 'Payment could not be started.');
    });
});
function releaseCheckout(message) {
    if (!checkoutForm) return;
    checkoutForm.dataset.paying = '';
    if (checkoutButton) {
        checkoutButton.disabled = false;
        checkoutButton.textContent = 'Pay securely';
    }
    showAlert(message);
}
function postJson(url, payload) {
    const token = checkoutForm.querySelector('[name=_token]')?.value || '';
    return fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify(payload)
    }).then(function (response) {
        return response.json().then(function (data) { return { ok: response.ok, data: data }; });
    });
}
function openRazorpay(data) {
    if (typeof Razorpay !== 'function') {
        releaseCheckout('Secure payment could not be loaded. Refresh the page and try again.');
        return;
    }
    const checkout = new Razorpay({
        key: data.key,
        amount: data.amount,
        currency: data.currency,
        name: data.name,
        description: data.description,
        order_id: data.razorpay_order_id,
        prefill: data.prefill,
        theme: { color: '#1b1613' },
        handler: function (response) {
            if (checkoutButton) checkoutButton.textContent = 'Confirming payment...';
            postJson(checkoutForm.dataset.verifyUrl, {
                order_number: data.order_number,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature: response.razorpay_signature
            }).then(function (result) {
                if (!result.ok || !result.data.redirect) {
                    const errors = result.data.errors || {};
                    const first = Object.values(errors)[0];
                    releaseCheckout(Array.isArray(first) ? first[0] : 'Payment could not be confirmed.');
                    return;
                }
                window.location = result.data.redirect;
            }).catch(function () {
                releaseCheckout('Payment could not be confirmed. If money was taken, it will be checked from the payment notice.');
            });
        },
        modal: {
            ondismiss: function () {
                postJson(checkoutForm.dataset.cancelUrl, { order_number: data.order_number }).catch(function () {});
                releaseCheckout('Payment was cancelled. You can try again.');
            }
        }
    });
    checkout.on('payment.failed', function () {
        releaseCheckout('Payment was not completed. You can try again.');
    });
    checkout.open();
}
</script>
@if(!empty($onlineEnabled))
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
@endsection
