@extends('layout.app')
@section('content')
<!-- HERO -->
<section class="inner-banner relative">
    <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
        <div class="h-full w-full bg-black/45 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-whited">About Integrated Gulf Biosystems</h2>
                <p class="text-white/90 my-3 max-w-2xl">For over two decades, Integrated Gulf Biosystems has
                    empowered laboratories, research institutions, and biotechnology companies with advanced
                    genomic, molecular, and analytical technologies across the MENA region.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="story-card py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h3 class="mb-4">Our Story</h3>
            <p class="mb-4 leading-relaxed">Integrated Gulf Biosystems (IGB) was founded with a mission to bridge
                the technological gap between global scientific innovation and regional laboratory capabilities.
                Over the years, we have grown into one of the most trusted partners for bioscience solutions,
                offering a comprehensive range of instrumentation, software platforms, and laboratory workflows.</p>
            <p class="leading-relaxed">Our journey has been defined by continuous learning, strong collaborations
                with global technology leaders, and a commitment to ensuring that every laboratory—whether academic,
                clinical, or industrial—has access to world‑class tools and support.</p>
        </div>
        <div class="bg-gray-200 h-80 rounded-xl flex items-center justify-center text-gray-700 font-semibold">

        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="mission-card py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <h3 class="text-center mb-10">Mission & Vision</h3>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-xl shadow">
                <h4 class="text-xl font-bold mb-3">Our Mission</h4>
                <p>To deliver cutting‑edge scientific solutions, world‑class service, and reliable laboratory
                    support to accelerate research, diagnostics, and innovation across the MENA region.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow">
                <h4 class="text-xl font-bold mb-3">Our Vision</h4>
                <p>To be the leading biosciences technology partner, empowering every laboratory with the tools and
                    expertise needed to achieve breakthrough discoveries and improve human health.</p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="p-12 bg-white">
    <div class="page-heading relative mb-4">
        <span class="uppercase bg-white">Our Core Values</span>
    </div>

    <div class="grid md:grid-cols-4 gap-8">
        <div
            class="leadership-card bg-gray-50 p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <h4 class="mb-2">Innovation</h4>
            <p>We continuously adapt to the rapidly evolving landscape of genomics and biotechnology.</p>
        </div>
        <div
            class="leadership-card bg-gray-50 p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <h4 class="mb-2">Integrity</h4>
            <p>We believe in honesty, reliability, and long-term commitment to our partners and clients.</p>
        </div>
        <div
            class="leadership-card bg-gray-50 p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <h4 class="mb-2">Service Excellence</h4>
            <p>Our team ensures seamless installation, training, and technical support for every solution we
                deliver.</p>
        </div>
        <div
            class="leadership-card bg-gray-50 p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <h4 class="mb-2">Collaboration</h4>
            <p>We work closely with global partners, researchers, and institutions to advance scientific discovery
                together.</p>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="p-12 bg-gray-100">
    <div class="page-heading relative mb-4">
        <span class="uppercase">Meet Our Leadership</span>
    </div>

    <div class="grid md:grid-cols-4 gap-8">
        <div
            class="leadership-card bg-white p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1607746882042-944635dfe10e"
                class="rounded-full w-32 h-32 mx-auto mb-4 object-cover" />
            <h4>Dr. Sarah Malik</h4>
            <h6>Chief Scientific Officer</h6>
            <p class="mt-2">Expert in genomics and molecular diagnostics with 18+ years of experience.
            </p>
        </div>
        <div
            class="leadership-card bg-white p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6"
                class="rounded-full w-32 h-32 mx-auto mb-4 object-cover" />
            <h4>Ahmed Al‑Hashmi</h4>
            <h6>Managing Director</h6>
            <p class="mt-2">Drives business growth, regional partnerships, and operational excellence.
            </p>
        </div>
        <div
            class="leadership-card bg-white p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9"
                class="rounded-full w-32 h-32 mx-auto mb-4 object-cover" />
            <h4>Laura Fernandez</h4>
            <h6>Head of Technical Services</h6>
            <p class="mt-2">Specialist in instrument workflows, training, and customer support.</p>
        </div>
        <div
            class="leadership-card bg-white p-6 text-center rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e"
                class="rounded-full w-32 h-32 mx-auto mb-4 object-cover" />
            <h4>Michael Thompson</h4>
            <h6>Director of Business Development</h6>
            <p class="mt-2">Leads strategic alliances, market expansion, and global partnership initiatives.</p>
        </div>
    </div>
</section>
@endsection