(function($){
// JavaScript Document
var _arrayImages = new Array();
	var _mouseOver = "";
		$("#profile-list div.image").hover(function(){
				var _id = $(this).find("img:first").attr("id");
				_arrayImages.length = 0;
				_arrayImages[_arrayImages.length] = $(this).find("img:first").attr("src");
				$(this).find(".roll").each(function(){
					_arrayImages[_arrayImages.length] = $(this).attr("src");
				});
				_mouseOver = setInterval(showImages(_id),200);
			},
			function(){
				clearInterval(_mouseOver);
				var _id = $(this).find("img:first").attr("id");
				$("#"+ _id).attr("src", _arrayImages[0]);
		});
	
	var i=0;
	function showImages(_id)
	{
		++i;
		if (i >= _arrayImages.length )
			i = 0;
		$("#"+ _id).attr("src", _arrayImages[i]);
	}
	function get_model_home( divsion_link_name, model_url_name ){
		location.href = 'model/' + model_url_name;
	}
	
	
//add the hover feature js snippet

		var zoom = "";
		$(document).ready(function(e)
		{
			zoom = document.documentElement.clientWidth / window.innerWidth;
			centerImages();
			
			$('#ulImages').show();
			if ($("#hdnCustom").length > 0)
				imageHeight();
	
			if ($('#imgRight').length > 0)
			{
				$('#imgRight').one("load",function(){
					if(this.complete)
					{
						onNav();
					}
				});
			}
		});
	
		function imageHeight()
		{
			var hgt1 = $(window).height() - 230;
			$('#imgRight').height(hgt1);
			//alert($('#imgRight').height());
			$('#imgRight').show();
		}
		
		 $(window).resize(function(){
			if ($("#hdnCustom").length > 0)
				imageHeight();
			var zoomNew = document.documentElement.clientWidth / window.innerWidth;
			if (zoom != zoomNew) {
				centerImages();
				zoom = zoomNew;
				return;
			}
			centerImages();
		});
		function centerImages()
		{
			var _width = ($(document).innerWidth() - 42);
			var _widthOriginal = $(document).innerWidth();
			var _outwidth = 182;
			var _pad1 = Math.round(_width % _outwidth);
			var _setWidth = _width - _pad1;
			$("#ulImages").css("width", _setWidth + "px");
			$("#ulImages").css("margin-left",((_widthOriginal - _setWidth)/2 -10 ) + "px");
			var _height = ($(window).innerHeight() - 162) ;
			var _height1 = ($(window).innerHeight() - 22) ;
			$("#divImages").css("height", _height + "px");
			$("#divBody").css("height", _height1 + "px");
			//$('body').height(_height1);
		}
})(jQuery);