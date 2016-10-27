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
})(jQuery);