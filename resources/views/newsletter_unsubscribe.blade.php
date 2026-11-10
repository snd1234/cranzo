@extends('layout.app')

@section('content')
<section class="max-w-lg mx-auto px-4 py-16">
    <h1 class="text-2xl font-semibold">Unsubscribe</h1>
    @if(!empty($done))
        <p class="mt-4 bg-white border border-stone-200 rounded-xl p-5">{{ $subscriber->email }} will not receive Cranzo offer emails.</p>
    @else
        <p class="mt-4 text-stone-600">Stop offer emails for {{ $subscriber->email }}.</p>
        <form method="post" action="{{ route('newsletter.unsubscribe.confirm', $subscriber->token) }}" class="mt-4">
            @csrf
            <button class="bg-stone-900 text-white rounded-full px-5 py-3" type="submit">Unsubscribe</button>
        </form>
    @endif
</section>
@endsection
