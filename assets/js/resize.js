// JavaScript Document
// resize image
		jQuery(document).ready(function(){
		  var height = jQuery('.image:eq(0)').height();
		  var width = jQuery('.image:eq(0)').width();
		  jQuery('.image').each(function(){
			  var ig = jQuery(this).find('img').eq(0);
			  ig.css({'height': 'auto','width':width});
			  if(ig.height() < height){
				  ig.css({'height': height,'width':'auto'});
			  }
		  });
		});