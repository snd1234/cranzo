/*------------

  Template Name:  Curex - Medical Clinic Service Bootstrap 4 Template
  Description: Curex is a unique website template designed in HTML with a simple & beautiful look. There is an excellent solution for creating clean, wonderful and trending material design Medical, Clinic any other purposes websites.
  Version: 1.0.0 
-----------------*/


(function($) {
    'use strict';

    
    
    
    //wow js active
    new WOW().init();

    
    // scrolling top js
    $(window).scroll(function(){
        if ($(this).scrollTop() > 200) 
        {
            $('.bdr-ripple-ani-btn').fadeIn();
        } 
        else 
        {
            $('.bdr-ripple-ani-btn').fadeOut();
        }
        });

        $('.bdr-ripple-ani-btn').click(function(){
                $('html, body').animate({scrollTop : 0},700);
                return false;
        });

   //Sticky Header
    $(window).on('scroll',function() {    
        var scroll = $(window).scrollTop();
        if (scroll < 245) {
            $('#sticky-header-with-topbar').removeClass("scroll-header");
        }else{
            $('#sticky-header-with-topbar').addClass("scroll-header");
        }
    });
    

    //slider_wrap
    $('.slider__wrap').slick({
        autoplay: true,
        dots: true,
        autoplaySpeed: 3000,
       
    });

    // gallery
    $('.pp__gallery').slick({
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: true,
        variableWidth: true
       
    });

    // mobile menu
    $(".meanmenu-reveal").click(function(){
        $(".mean-nav ul").slideToggle();
      });
    
    //Counter Js
    $('.counter').counterUp({
        delay: 10,
        time: 1000			
    });
   
    //Magnific Popup
				
    $('.gallery-photo').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });		

    // nav scroll page
    $(".main__menu__nav ul li").find("a").click(function(e) {
        e.preventDefault();
        var section = $(this).attr("href");
        $("html, body").animate({
            scrollTop: $(section).offset().top
        });
    });
   
   
  


})(jQuery);