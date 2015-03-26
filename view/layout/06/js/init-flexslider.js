jQuery(window).load(function(){
	// The slider being synced must be initialized first
	jQuery('#layout6-carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 210,
		itemMargin: 5,
		asNavFor: '#layout6-slider'
	});

	jQuery('#layout6-slider').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		sync: "#layout6-carousel"
	});
});