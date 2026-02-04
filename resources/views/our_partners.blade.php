@extends('layout.app')
@section('content')
 <!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2 >Our Trusted Global Partners</h2>
                    <p class="text-white/90 my-3 max-w-2xl">We collaborate with world-leading biotechnology
                        manufacturers, genomic innovators,
                        and scientific solution providers to deliver cutting‑edge technologies across the
                        Middle East. Our partnerships empower researchers, clinicians, and laboratories with
                        tools that accelerate discovery and improve human health.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Logos Grid -->
    <section class="partner-card py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <h3 class="mb-8 text-center">Official Distribution Partners</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <?php foreach($partners as $partner): ?>
                <div class="partner-card bg-white p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    @if(!empty($partner->website_url))
                        <a href="{{ $partner->website_url }}" target="_blank" class="block">
                    @endif
                            <div class="h-20 mb-6 flex items-center justify-center">
                                <img src="{{ asset(config('uploads.path')  . $partner->logo) }}" class="mx-auto " />
                            </div>
                            <h4>{{ $partner->name }}</h4>
                    @if(!empty($partner->website_url))
                        </a>
                    @endif
                </div>
                <?php endforeach; ?>
                
            </div>
        </div>
    </section>

    <!-- Partnership Benefits Section -->
    <section class="partnerships-card py-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-center mb-8">Why Our Partnerships Matter</h3>
            <div class="grid md:grid-cols-3 gap-10">
                <div
                    class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <h4 class="mb-3">Advanced Technologies</h4>
                    <p class="text-gray-700 leading-relaxed">
                        Through our collaborations, we deliver state‑of‑the‑art equipment, reagents, and
                        platforms used by thousands of laboratories worldwide.
                    </p>
                </div>
                <div
                    class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <h4 class="mb-3">Certified Expertise</h4>
                    <p class="text-gray-700 leading-relaxed">
                        Our team receives continuous technical training from global partners, ensuring
                        installation, support, and workflows meet international standards.
                    </p>
                </div>
                <div
                    class="p-6 bg-gray-50 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <h4 class="mb-3">Local Market Knowledge</h4>
                    <p class="text-gray-700 leading-relaxed">
                        We bridge the gap between global innovation and local laboratory needs across
                        hospitals, research centers, and diagnostics facilities.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection