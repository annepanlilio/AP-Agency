(function($){
	$(window).load(function(){
		var $container = $('#photos');
		$container.imagesLoaded( function(){
			$container.isotope({
			  	itemSelector : '.photo'
			});
		});
	});
})(jQuery);