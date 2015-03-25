// Load
(function($){
    $(window).load(function(){
        $("#photo-scroller").mCustomScrollbar({
        	axis: "x",        	
        	theme:"light",
			autoDraggerLength: false,
			scrollbarPosition:"outside",
        	scrollButtons:{enable:true},        	
        	advanced:{autoExpandHorizontalScroll:true}
        });
    });
})(jQuery);