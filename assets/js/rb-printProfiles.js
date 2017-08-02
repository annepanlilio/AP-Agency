/**
 * Print profiles
*/
jQuery(document).ready(function(){		

	jQuery(".link-profile-print").click(function(){
		jQuery(".rb-print").remove();
		
		var a = [];
		jQuery.each(jQuery(".rbprofile-list"),function(){
			a.push(jQuery(this).attr("data-profileid"));
		});
		var href = location.href;
		var segment = href.match(/([^\/]*)\/*$/)[1];
		window.open(rb_agency.site_url+"/"+segment+"/?print_profiles="+a.join(","));
	});

	jQuery(".link-profile-pdf").click(function(){
	    var a = [];
		jQuery.each(jQuery(".rbprofile-list"),function(){
			a.push(jQuery(this).attr("data-profileid"));
		});
		window.location.href = rb_agency.site_url+"/profile-pdf/?target="+a.join(",");			
	});
});