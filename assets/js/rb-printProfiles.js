/**
 * Print profiles
*/
jQuery(document).ready(function(){
		jQuery.each(jQuery(".rbprofile-list"),function(i,d){
			if(i%9==0){
					if(i>1){
									jQuery("<div class=\"print-clear\"></div><div class='rb-print-footer'><span>"+rb_agency.name+"</span></div><div class=\"print-clear\"></div><!--rb-printProfile.js-->").insertBefore(jQuery(this));
					
					}
							jQuery("<div class=\"print-clear\"></div><div class='rb-print-header'><img src=\""+rb_agency.logo+"\"/><span style=\"float:right;\">"+rb_agency.name+"</span></div><div class=\"print-clear\"></div><!--rb-printProfile.js-->").insertBefore(jQuery(this));
				
				
			}
			
		});

	jQuery(".link-profile-print").click(function(){
			window.print();
	});
});