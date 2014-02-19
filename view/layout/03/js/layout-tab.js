(function($){
	var $tab = jQuery.noConflict();
	$tab(window).load(function() {
		/*$tab(".tab").hide();
		$tab(".row-photos").show();
		$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
		$tab("#row-photos").removeClass("tab-inactive").addClass("tab-active");*/
		$tab(".maintab").click(function(){
		   var idx = this.id;
		   var elem = "." + idx;
		   var elem_id = "#" + idx;
		   if ((idx=="row-all")){
				$tab(".tab").hide();
				$tab(".tab").show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
				$tab(".tab-active").removeClass("tab-inactive").addClass("tab-active");
			} else {
				if(idx=="row-bookings"){					
					var url = "<?php echo get_permalink(get_page_by_title('booking')); ?>";
					window.location = url;					
				} else {					   
					$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
					$tab(".tab").css({ opacity: 1.0 }).stop().animate({ opacity: 0.0 }, 2000).hide();
					$tab(elem).show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
					$tab(elem_id).removeClass("tab-inactive").addClass("tab-active");
				}
		   	}
		});
	});
})(jQuery);