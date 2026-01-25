@extends('layout.app')
@section('content')


    <!-- HERO -->
    <section class="inner-banner relative">
        <div class="h-72 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('front/images/banner1.jpg') }}');">
            <div class="h-full w-full bg-black/45 flex items-center">
                <div class="max-w-7xl mx-auto px-6 text-center">
                    <h2 >Get in Touch</h2>
                    <p class="text-white/90 my-3 max-w-2xl">We’re here to assist with your inquiries, partnerships, or
                        support needs.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="p-12">
        <div class="grid md:grid-cols-2 gap-10">

            <!-- Contact Details -->
            <div>
                <div class="page-heading relative mb-4">
                    <span class="uppercase">Reach Out to Us</span>
                </div>
                <p class="text-gray-700 mb-4">
                    Whether you have questions about our products, need technical support, or want to explore business
                    collaborations — our team is ready to assist you.
                </p>

                <div class="mt-8 space-y-6">

                    <!-- Address -->
                    <div class="contact-info flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 py-3 px-4 rounded-lg">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4>Office Address</h4>
                            <p class="text-gray-700">
                                123 Business Avenue, Technology Park,<br>
                                New Delhi, India – 110001
                            </p>
                             <br>
                            <div>
                                <p class="text-gray-700">  <i class="fa-solid fa-phone"></i> :  +91 98765 43210</p>
                                <br>
                                <p class="text-gray-700">  <i class="fa-solid fa-envelope"></i> : info@company.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <!-- <div class="contact-info flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 py-3 px-4 rounded-lg">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h4>Phone</h4>
                            <p class="text-gray-700">+91 98765 43210</p>
                        </div>
                    </div> -->

                    <!-- Email -->
                    <!-- <div class="contact-info flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 py-3 px-4 rounded-lg">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p class="text-gray-700">info@company.com</p>
                        </div>
                    </div> -->

                    <!-- Working Hours -->
                    <!-- <div class="contact-info flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 py-3 px-4 rounded-lg">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <h4>Working Hours</h4>
                            <p class="text-gray-700">
                                Mon – Fri: 9:00 AM – 6:00 PM<br>
                                Sat: 10:00 AM – 2:00 PM
                            </p>
                        </div>
                    </div> -->

                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white shadow rounded-xl p-8">
                <h3 class="text-2xl font-bold mb-6">Send Us a Message</h3>
                <form class="space-y-6" name="contactUsForm" id="contactUsForm" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label>Full Name <span class="text-red-500 text-xl ms-1">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="w-full mt-1 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="form-group">
                        <label>Email Address <span class="text-red-500 text-xl ms-1">*</span></label>
                        <input type="email" name="email" id="email" class="w-full mt-1 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="form-group">
                        <label>Phone Number <span class="text-red-500 text-xl ms-1">*</span></label>
                        <input type="text" name="phone" id="phone" class="w-full mt-1 p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="form-group">
                        <label>Your Message</label>
                        <textarea name="message" id="message" class="w-full mt-1 p-3 rounded-lg border border-gray-300 h-32 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn uppercase"> Submit Message </button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- ===== MAP SECTION ===== -->
    <section class="bg-white p-12">
        <div class="page-heading relative mb-4">
            <span class="uppercase bg-white">Find Us on the Map</span>
        </div>
        <div class="h-[340px] w-full  rounded-xl overflow-hidden shadow">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.1598209348782!2d77.22343737530115!3d28.625005575656497!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd35aa1db9ef%3A0xa01bbf7c8b1a1bc!2sConnaught%20Place%2C%20New%20Delhi%2C%20Delhi%20110001!5e0!3m2!1sen!2sin!4v1709988430000"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>
@endsection
<script src="{{asset('front/js/jquery.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('#contactUsForm').on('submit', function(e) {
        e.preventDefault();
        // Form validation
        var fullName = $('#full_name').val().trim();
        var email = $('#email').val().trim();
        var phone = $('#phone').val().trim();
        var message = $('#message').val().trim();

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
        var formData = $(this).serialize();
        $.ajax({
            url: '{{ url("ajax/contact-us") }}',
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
                    $('#contactUsForm')[0].reset();
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