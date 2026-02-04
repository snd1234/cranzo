@extends('layout.app')
@section('content')
  <!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image:  url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2>About Integrated Gulf Biosystems</h2>
                    <p class="text-white/90 my-3 max-w-2xl">For over two decades, Integrated Gulf Biosystems has
                        empowered laboratories, research institutions, and biotechnology companies with advanced
                        genomic, molecular, and analytical technologies across the MENA region.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="p-12">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-6 mb-7">

            <!-- Product Info -->
            <div
                class="product-info p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300 min-h-[520px]">
                <div class="page-heading relative mb-4">
                    <span class="bg-white  uppercase">{{$product->title}}</span>
                </div>
                {!! $product->description !!}
                
                <div class="my-10">
                    <div class="page-heading relative mb-4">
                        <span class="bg-white uppercase">Form Fill Up</span>
                    </div>
                    <form class="space-y-4" name="productEnquiryForm" id="productEnquiryForm">
                        @csrf
                        <div id="responseMessage" class="hidden">
                            <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative hidden" role="alert">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline" id="successText"></span>
                            </div>
                            <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative hidden" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <span class="block sm:inline" id="errorText"></span>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="text-red-500 text-xl ms-1">*</span></label>
                                <input type="text" name="full_name" id="full_name" placeholder="Full Name" class="form-input" required="" >
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address <span class="text-red-500 text-xl ms-1">*</span></label>
                                <input type="email" name="email" id="email" placeholder="Email Address" class="form-input" required="" >
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone<span class="text-red-500 text-xl ms-1">*</span></label>
                                <input  name="phone" id="phone"  type="text" placeholder="Phone" class="form-input">
                            </div>
                             <div class="form-group">
                                <label class="form-label">Designation<span class="text-red-500 text-xl ms-1">*</span></label>
                                <input  name="designation" id="designation"  type="text" placeholder="Designation"  class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pincode<span class="text-red-500 text-xl ms-1">*</span></label>
                                <input name="pincode" id="pincode"  type="text" placeholder="Pincode"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Address<span class="text-red-500 text-xl ms-1">*</span></label>
                                <input name="address" id="address" type="text" placeholder="Address"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">City<span class="text-red-500 text-xl ms-1">*</span></label>
                                <input name="city" id="city" type="text" placeholder="City"
                                    class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">State or Province<span class="text-red-500 text-xl ms-1">*</span></label>
                                <input name="state" id="state" type="text" placeholder="State or Province"
                                    class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Country / Region<span class="text-red-500 text-xl ms-1">*</span></label>
                                <select name="country" id="country" class="form-input select2" >
                                    <option value="" selected>Select Country</option>
                                    @foreach($countries as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Remarks</label>
                                <textarea id="remarks" placeholder="Remarks" class="form-input" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="submitBtn" class="btn uppercase flex items-center gap-2">Submit <i class="fa-regular fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
                <?php //dd($product_catalogs);
                // 
                if ($product_catalogs && count($product_catalogs) > 0) { 
                    ?>
                <div class="my-10">
                    <h4 class="mb-3 text-center">Our Resources</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach($product_catalogs as $catalog) {
                        ?>
                        <div class="resource-box">
                            <div class="mb-2"><i class="fa-solid fa-download"></i></div>
                            <p class="title">{{ $catalog->catalog_title }}</p>
                            <a href="{{ asset(config('uploads.path')  . $catalog->catalog_file) }}" download class="text-blue-500 hover:underline">
                                Download Catalog
                            </a>
                        </div>
                        <?php }  ?>
                        
                    </div>
                </div>
                <?php }  ?>

            </div>


            <!-- Side column -->
            <aside class="flex flex-col gap-4">
                <div
                    class="bg-gray-200 text-back text-lg flex items-center justify-center rounded border-2 border-gray-200 min-h-[240px]">
                    <div class="productSwiper swiper w-full">
                        <div class="slider-overlay"></div>
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            @foreach($product_images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset(config('uploads.path')  . $image->image_path) }}" alt="product image">
                                </div>
                            @endforeach                           
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <div class="p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="page-heading relative mb-4">
                        <span class="bg-white uppercase">Browse by</span>
                    </div>
                    <div class="accordion">
                        @if($subcategories->isNotEmpty())
                            @foreach($subcategories as $sub)
                             @if($sub->products->isNotEmpty())
                            <div class="acc-item">
                            <div class="acc-title">{{ $sub->name }}</div>
                            <div class="acc-content">
                                <div class="flex flex-col space-y-3">
                                    @foreach($sub->products as $product)
                                    <a href="{{ url('product-detail/' . $product->slug) }}" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i> {{ $product->title }}</a>
                                    @endforeach
                                   
                                    <!-- <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>GFood Safety Solutions</a>
                                    <a href="#" class="flex items-center gap-2"><i class="fa-solid fa-angle-right"></i>Clinical Research</a> -->
                                </div>
                            </div>
                            </div>
                             @endif
                            @endforeach
                        @endif
                      
                      </div>
                    
                </div>
            </aside>

        </div>
    </div>

@endsection

<script src="{{asset('front/js/jquery.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('#productEnquiryForm').on('submit', function(e) {
        e.preventDefault();
        // Form validation
        var fullName = $('#full_name').val().trim();
        var email = $('#email').val().trim();
        var phone = $('#phone').val().trim();
        var city = $('#city').val().trim();
        var state = $('#state').val().trim();
        var address = $('#address').val().trim();
        var pincode = $('#pincode').val().trim();
        var remarks = $('#remarks').val().trim();
        var country = $('#country').val().trim();
        if (fullName === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Full Name is required.');
            return false;
        }
        if (email === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Email Address is required.');
            return false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Please enter a valid Email Address.');
            return false;
        }
        if (phone !== '' && !/^\d{10}$/.test(phone)) {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Please enter a valid 10-digit Phone Number.');
            return false;
        }
        if (city === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('City is required.');
            return false;
        }
        if (state === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('State or Province is required.');
            return false;
        }
        if (address === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Address is required.');
            return false;
        }
        if (pincode === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Pincode is required.');
            return false;
        }
        if (country === '') {
            $('#responseMessage').removeClass('hidden');
            $('#errorMessage').removeClass('hidden');
            $('#errorText').text('Country / Region is required.');
            return false;
        }
        
        var formData = $(this).serialize();
        $.ajax({
            url: '{{ url("ajax/product-enquiry") }}',
            type: 'POST',
            data: formData,
            success: function(response) 
            {
                if(response.status === 'success')
                {
                    $('#responseMessage').removeClass('hidden');
                    $('#successMessage').removeClass('hidden');
                    $('#successText').text(response.message);
                    setTimeout(function() {
                        $('#responseMessage').addClass('hidden');
                        $('#successMessage').addClass('hidden');
                    }, 5000);
                    $('#productEnquiryForm')[0].reset();
                }
                else{
                    $('#responseMessage').removeClass('hidden');
                    $('#errorMessage').removeClass('hidden');
                    $('#errorText').text(response.message);
                    setTimeout(function() {
                        $('#responseMessage').addClass('hidden');
                        $('#errorMessage').addClass('hidden');
                    }, 5000);
                } 
            },
            error: function(xhr, status, error) {
                $('#responseMessage').removeClass('hidden');
                $('#errorMessage').removeClass('hidden');
                $('#errorText').text('An error occurred. Please try again.');
                setTimeout(function() {
                    $('#responseMessage').addClass('hidden');
                    $('#errorMessage').addClass('hidden');
                }, 5000);

            }
        });
    });
});
</script>