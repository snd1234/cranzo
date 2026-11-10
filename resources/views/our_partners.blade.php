@extends('layout.app')

@section('content')
<section class="inner-banner relative">
    <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
        <div class="h-full w-full bg-black/45 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-white">Our Partners</h2>
                <p class="text-white/90 my-3 max-w-2xl">We work with leading organizations to bring reliable laboratory solutions closer to our customers.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($partners as $partner)
            <div class="bg-gray-50 rounded-xl shadow p-6 text-center">
                <div class="h-28 flex items-center justify-center mb-4">
                    @if(!empty($partner->logo))
                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="max-h-20 object-contain">
                    @else
                        <div class="text-xl font-semibold text-gray-700">{{ $partner->name }}</div>
                    @endif
                </div>
                <h3 class="text-lg font-semibold mb-2">{{ $partner->name }}</h3>
                <p class="text-gray-600">{{ $partner->short_description ?? 'Trusted partner for scientific innovation.' }}</p>
            </div>
        @empty
            <div class="md:col-span-3 text-center text-gray-600">No partners found yet.</div>
        @endforelse
    </div>
</section>
@endsection
