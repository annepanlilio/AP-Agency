$(function () {		
    $("#photobook").booklet({
    	width: '100%',
    	height: 300,
    	pageNumbers: false,
    	pagePadding: 0,
    	overlays: true,
    	manual: false
    });

    $('#next-page').click(function(e){
		e.preventDefault();
		$('#photobook').booklet("next");
	});

	$('#prev-page').click(function(e){
		e.preventDefault();
		$('#photobook').booklet("prev");
	});
});