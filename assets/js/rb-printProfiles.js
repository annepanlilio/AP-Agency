/**
 * Print profiles
*/
jQuery(document).ready(function(){
		

	jQuery(".link-profile-print").click(function(){
		jQuery(".rb-print").remove();
		jQuery.each(jQuery(".rbprofile-list"),function(i,d){
			if(i%6==0){
					if(i>1){
									jQuery("<div class='rb-print'><div class=\"print-clear\"></div><div class='rb-print-footer'><span>"+rb_agency.name+"</span></div><div class=\"print-clear\"></div></div><!--rb-printProfile.js-->").insertBefore(jQuery(this));
					
					}
							jQuery("<div class='rb-print'><div class=\"print-clear\"></div><div class='rb-print-header'><img src=\""+rb_agency.logo+"\"/><span style=\"float:right;\">"+rb_agency.name+"</span></div><div class=\"print-clear\"></div></div><!--rb-printProfile.js-->").insertBefore(jQuery(this));
				
				
			}
			
		});
			window.print();
	});

	jQuery(".link-profile-pdf").click(function(){
		    var a = [];
			jQuery.each(jQuery(".rbprofile-list"),function(){
				a.push(jQuery(this).attr("data-profileid"));
			});
			window.location.href = rb_agency.site_url+"/profile-pdf/?target="+a.join(",");
			
	});
});