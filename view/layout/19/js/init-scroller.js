(function($){
	$(window).load(function(){
		$("#photo-scroller").mCustomScrollbar({
			horizontalScroll:true,
			scrollButtons:{
				enable:true
			},
			advanced:{
				updateOnContentResize: true,
				updateOnImageLoad: true
			}

		});
		$("#toggle-photos").click(function(){

			$(this).toggleClass("scroller-view");

			if($(this).hasClass("scroller-view")){

				// Update Icon
				$(this).children("i").addClass("fa-th");
				$(this).children("i").removeClass("fa-square");

				// Change View
				$("#photo-scroller").show();
				$("#grid-view").hide();

				// $(this).addClass("scroller-view");
				// $(this).removeClass("grid-view");
			} else {

				// Update Icon
				$(this).children("i").removeClass("fa-th");
				$(this).children("i").addClass("fa-square");

				// Change View
				$("#grid-view").show();
				$("#photo-scroller").hide();

				// $(this).removeClass("scroller-view");
				// $(this).addClass("grid-view");
			}
		});
		$("#links #nav-photos").on("click",function(){
			$(".profile-action").show();
		});
		$("#links #nav-digitals").on("click",function(){
			$(".profile-action").show();
		});
		$("#links #nav-videos").on("click",function(){
			$(".profile-action").hide();
		});
	});


	var viewportHeight = $(window).height();
	resize_scroller(viewportHeight);

	$(window).resize(function(){

		var viewportHeight = $(window).height();
		resize_scroller(viewportHeight);
	});

	function resize_scroller(viewportHeight){
		var logo_height = $(".site-header").outerHeight();
		var nav_height = $(".site-nav").outerHeight();
		var cont_height = $("#rblayout-nineteen > container").outerHeight();
		var stats_height = $(".tab-content > .container").outerHeight();
		var footer_height = $(".site-footer").outerHeight();

		var all_height = logo_height + nav_height + cont_height + stats_height + footer_height;
		var scroller_height = viewportHeight - 448;

		$('#photo-scroller a img').each(
	        function(){
	            
	            var scroller_height = viewportHeight - 480;

	            $(this).css('height',scroller_height+'px');
	            // $(this).css({'margin-top': -theHeight / 2 + 'px', 'margin-left': -theWidth / 2 + 'px'});
	        });
	    // });				
	}
})(jQuery);