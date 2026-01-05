const homeSwiper = new Swiper('.homeSwiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,  // keeps autoplay after clicking
    },
  
    // Pagination dots
    pagination: {
      el: '.swiper-pagination',
      clickable: true,   // <-- REQUIRED for click next/prev
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
   
  });

  const productSwiper = new Swiper('.productSwiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,  // keeps autoplay after clicking
    },
  
    // Pagination dots
    pagination: {
      el: '.swiper-pagination',
      clickable: true,   // <-- REQUIRED for click next/prev
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
   
  });


  $(window).scroll(function () {
    if ($(this).scrollTop() > 150) {
      $(".header").addClass("scrolled");
    } else {
      $(".header").removeClass("scrolled");
    }
  });

  //accordian js

  $(document).ready(function () {
    $(".acc-title").click(function () {
      $(".acc-content").not($(this).next()).slideUp();
      $(this).next(".acc-content").slideToggle();
    });
  });