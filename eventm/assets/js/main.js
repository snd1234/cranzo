(function ($) {
	"use strict";
	
	$(function($){		
	
		$(".embed-responsive iframe").addClass("embed-responsive-item");
		$(".carousel-inner .item:first-child").addClass("active");
		$('[data-toggle="tooltip"]').tooltip();
		
		//Fixed nav on scroll
		$(document).on('scroll',function(e){
			var scrollTop = $(document).scrollTop();
			if(scrollTop > $('nav').height()){
				$('nav').removeClass('navbar-default').addClass('navbar-fixed-top');
			}
			else {
				$('nav').removeClass('navbar-fixed-top').addClass('navbar-default');
			}
		});
		
		
		
		//Numaric Counter
		$('.counter').counterUp({
			delay: 10,
			time: 1000
		});

		// testimonial slider
		$('.testimonial-slider').slick({
			dots: true,
			infinite: true,
			speed: 300,
			slidesToShow: 1,
			centerMode: true,
			variableWidth: true
		});

		// fancybox
		$('.popup-youtube').fancybox(); 

    });


	$(window).on('load', function(){
		
		//Preloader
		$('.preloader').delay(500).fadeOut('slow');
		$('body').delay(500).css({'overflow':'visible'});
	
		//Portfolio Section
		var $container = $('.portfolioContainer');
		$container.isotope({
			filter: '*',
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			}
		});
	 
		$('.portfolioFilter a').on('click',function(){
			$('.portfolioFilter a').removeClass('current');
			$(this).addClass('current');
		
			var selector = $(this).attr('data-filter');
			$container.isotope({
				filter: selector,
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			 });
			 return false;
		}); 
	});

	//Wow js
	new WOW().init();
		 
}(jQuery));	