jQuery(window).load(function(){
	// The slider being synced must be initialized first
	jQuery('#layout6-carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 150,
		itemMargin: 0,
		prevText: "",
		nextText: "",
		asNavFor: '#layout6-slider'
	});

	jQuery('#layout6-slider').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		prevText: "",
		nextText: "",
		sync: "#layout6-carousel"
	});
});