// Profile Seven Slider - Scroller
jQuery(window).load(function() {
    
  jQuery('#profile-carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 150,
    maxItems: 7,
    asNavFor: '#profile-slider'
  });

  jQuery('#profile-slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    smoothHeight: true,
    sync: "#profile-carousel"
  });

  jQuery('#layout6-carousel').flexslider({
    animation: "slide",
    controlNav: false,
    itemWidth: 100,
    animationLoop: false,
    slideshow: false,
    maxItems: 4,
    asNavFor: '#layout6-slider'
  });

  jQuery('#layout6-slider').flexslider({
    animation: "slide",
    directionNav: false,
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    smoothHeight: true,
    sync: "#layout6-carousel"
  });
  jQuery('#videos-carousel').flexslider({
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	slideshow: false,
	itemWidth: 120,
	asNavFor: '#video_player'
	});
	
  jQuery('#video_player').flexslider({
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	slideshow: false,
	smoothHeight: true,
	sync: "#videos-carousel"
	});
  jQuery('#media-carousel').flexslider({
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	slideshow: false,
	itemWidth: 120,
	asNavFor: '#media_player'
  });

  jQuery('#media_player').flexslider({
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	slideshow: false,
	smoothHeight: true,
	sync: "#media-carousel"
  });
});
