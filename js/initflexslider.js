// Profile Seven Slider - Scroller
$(window).load(function() {
    
  $('#profile-carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
    asNavFor: '#profile-slider'
  });

  $('#profile-slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    smoothHeight: true,
    sync: "#profile-carousel"
  });
  
});
