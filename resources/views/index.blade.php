@extends('layout.app')
@section('content')
<!-- Slider main container -->
    <div class="homeSwiper swiper w-full">
        <div class="slider-overlay"></div>
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <img src="{{ asset('front/images/banner.jpg') }}" alt="">
                <div class="slide-content">
                    <h2>Beautiful Nature</h2>
                    <p>Experience the calm and beauty of green mountains, fresh air, and peaceful surroundings.</p>
                    <button class="btn-hover color-1">Read More</button>
                </div>
            </div>


            <div class="swiper-slide">
                <img src="{{ asset('front/images/banner.jpg') }}" alt="">
                <div class="slide-content">
                    <h2>City Lights</h2>
                    <p>Discover the vibrant nightlife and sparkling skyline of modern metropolitan cities.</p>
                    <button class="btn-hover color-1">Read More</button>
                </div>
            </div>

        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <!-- Upcoming Events + News -->

    <div class="p-12 grid md:grid-cols-3 gap-8">
        <div class="md:col-span-1">
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">upcoming Events</span>
                </div>
                <div class="space-y-8">
                    <?php foreach($events as $event){ ?>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="h-[180px] w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                            <figure>
                                <img src="{{asset('storage/' . $event->image) }}" class="w-full object-cover">
                            </figure>
                        </div>

                        <div class="date text-gray-600 flex items-center gap-2 mb-4">
                            <i class="fa-regular fa-calendar-days"></i>
                            <span>{{ \Carbon\Carbon::parse($event->created_at)->format('jS F Y') }}</span>
                        </div>
                        <h3 class="mb-2">{{ strlen($event->title) > 66 ? substr($event->title, 0, 66) . '...' : $event->title }}</h3>
                        <p class="text-gray-700">{{ \Illuminate\Support\Str::words($event->short_description, 25, '...') }}</p>
                        <a href="{{ url('/blog/' . $event->slug) }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                            More
                            <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <?php } ?>
                   
                </div>
            </div>


        </div>
        <div class="md:col-span-2">
            <div class="mb-6">
                <div class="page-heading relative mb-4">
                    <span class="uppercase">latest news</span>
                </div>
                <div class="space-y-6">
                    <?php foreach($latestnews as $news){ ?>
                    <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            <!-- Image -->
                            <div
                                class="w-full sm:w-[270px] h-[200px] bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <figure>
                                    <img src="{{asset('storage/' . $news->image) }}" alt="Event Image"
                                        class="w-full h-full object-cover">
                                </figure>

                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <!-- Date -->
                                <div class="date text-gray-600 flex items-center gap-2 mb-2">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    <span>{{ \Carbon\Carbon::parse($news->created_at)->format('jS F Y') }}</span>
                                </div>

                                <!-- Title -->
                                <h3 class="mb-2">{{ strlen($news->title) > 66 ? substr($news->title, 0, 66) . '...' : $news->title }}</h3>

                                <!-- Description -->
                                <p class="text-gray-700 mb-4">
                                    {{ \Illuminate\Support\Str::words($news->short_description, 30, '...') }}
                                </p>
                               

                                <!-- Learn More Link -->
                                <a href="{{ url('/blog/' . $news->slug) }}" class="flex items-center gap-2 inline-block  ">
                                    Learn More
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                </div>
            </div>

        </div>
    </div>

    <div class="p-12">
        <div class="mb-6">
            <div class="page-heading relative mb-4">
                <span class="uppercase">Latest Webinars</span>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach($webinar as $web){ ?>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-40 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('storage/' . $web->image) }}" class="w-full object-cover">
                        </figure>

                    </div>
                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>{{ \Carbon\Carbon::parse($web->created_at)->format('jS F Y') }}</span>
                    </div>
                    <h3 class="mb-2">{{ strlen($web->title) > 66 ? substr($web->title, 0, 66) . '...' : $web->title }}</h3>
                    <p class="text-gray-600">{{ \Illuminate\Support\Str::words($web->short_description, 23, '...') }}</p>
                    <a href="{{ url('/blog/' . $web->slug) }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="mb-6">
            <div class="page-heading relative mb-4">
                <span class="uppercase">Latest blogs</span>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php 
                //echo "<pre>";
                foreach($blogs as $blog){ 
                    //echo $blog->title;die;
                ?>
                <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-60 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('storage/' . $blog->image) }}" class="w-full object-cover">
                        </figure>
                    </div>
                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>{{ \Carbon\Carbon::parse($blog->created_at)->format('jS F Y') }}</span>
                    </div>
                    <h3 class="mb-2">{{ strlen($blog->title) > 66 ? substr($blog->title, 0, 66) . '...' : $blog->title }}</h3>
                    <p class="text-gray-600">{{ \Illuminate\Support\Str::words($blog->short_description, 23, '...') }}</p>
                    <a href="{{ url('/blog/' . $blog->slug) }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <?php } ?>
                <!-- <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-60 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                        </figure>
                    </div>

                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">Lorem Ipsum Dolor Sit Amet Consectetur</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div> -->
                <!-- <div class="card p-8 bg-white rounded-lg shadow hover:shadow-lg transition duration-300">
                    <div class="h-60 w-full bg-gray-200 mb-3 rounded-lg overflow-hidden">
                        <figure>
                            <img src="{{ asset('front/images/two.jpg') }}" class="w-full object-cover">
                        </figure>
                    </div>

                    <div class="date text-gray-600 flex items-center gap-2 mb-4">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>30th September</span>
                    </div>
                    <h3 class="mb-2">Lorem Ipsum Dolor Sit Amet Consectetur</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore.</p>
                    <a href="#" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                        More
                        <i class="fa-solid fa-arrow-right"></i></a>
                </div> -->
            </div>

        </div>
    </div>

    <!-- Latest Webinars + Blogs -->

    <div class="p-12 bg-white">
        <div class="page-heading relative mb-4">
            <span class="bg-white uppercase">Our solutions</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <figure>
                        <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                    </figure>
                </div>

                <h3 class="mb-2">Equipment</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="{{ url('/our-solution-detail') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                    More
                    <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <figure>
                        <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                    </figure>
                </div>

                <h3 class="mb-2">Consumables</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="{{ url('/our-solution-detail') }}" class="flex items-center gap-4 mt-4 inline-block  ">Learn
                    More
                    <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card p-8 bg-gray-100 rounded-lg shadow hover:shadow-lg transition duration-300">
                <div class="h-60 w-full bg-white mb-3 rounded-lg overflow-hidden">
                    <img src="{{ asset('front/images/one.jpg') }}" class="w-full object-cover">
                </div>

                <h3 class="mb-2">Services</h3>
                <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore.</p>
                <a href="{{ url('/our-solution-detail') }} " class="flex items-center gap-4 mt-4 inline-block  ">Learn
                    More
                    <i class="fa-solid fa-arrow-right"></i></a>
            </div>

        </div>

    </div>

    <div class="Newsletter p-12">
        <div class="page-heading relative mb-4">
            <span class="uppercase">Subscribe to our Newsletter</span>
        </div>
        <p class="text-gray-800 mb-8">
            Stay updated with the latest news, events, and exclusive offers delivered right to your inbox.
        </p>
        <form name="newsletterForm" id="newsletterForm" enctype="multipart/form-data">
        @csrf
            <div class="p-8 bg-white shadow rounded-xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="text-red-500 text-xl ms-1">*</span></label>
                        <input type="text" name="full_name" id="full_name" placeholder="Full Name" class="form-input" required="" >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Id</label>
                        <input type="email" name="email" id="email" placeholder="Email Id"
                            class="form-input"
                            required="">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number"
                            class="form-input"
                            required="">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Organisantion / Insttitue </label>
                        <input type="text"  name="organisantion" id="organisantion"  placeholder="Org/Inst"
                            class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <select name="country" id="country" class="form-input select2" required="">
                            <option value="" selected>Select Country</option>
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">State</label>
                        <input name="state" id="state" type="text" placeholder="State or Province"
                            class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input name="city" id="city" type="text" placeholder="City"
                            class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Remarks</label>
                        <textarea id="remarks" placeholder="Remarks" class="form-input" rows="2"></textarea>
                    </div>
                    <div class="form-group text-end col-span-4">
                        <button type="submit" class="btn-hover color-1">
                            Subscribe
                        </button>
                    </div>

                </div>
            </div>
        </form>

    </div>
@endsection
<!-- 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{asset('front/js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#newsletterForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ url("ajax/subscribe-newsletter") }}',
                type: 'POST',
                data: formData,
                success: function(response) 
                {
                    if(response.status === 'success')
                    {
                       // alert('Thank you for subscribing to our newsletter!');
                        $('#newsletterForm')[0].reset();
                        $('#country').val(null).trigger('change');
                    }
                    // else{
                    //     alert('You are already subscribed to our newsletter.') 
                    // } 
                     alert(response.message);
                    
                   
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>