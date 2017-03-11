var $ = jQuery.noConflict();

$(function() {

	// cache container
    var $container = $('#profile-list');

    // $container.isotope({
    //     itemSelector : '.rbprofile-list',
    //     layoutMode : 'fitColumns'
    // });

    $container.isotope({
		// options
		itemSelector: '.rbprofile-list',
		layoutMode: 'fitRows',
		percentPosition: true
	});

	// filter items when filter link is clicked
	// $('#profile-filters a').click(function(){
	// 	var selector = $(this).attr('data-filter');
	// 	$container.isotope({
	// 		filter: selector
	// 	});
	// 	return false;
	// });

	// filter items on button click
	$('#profile-filters').on( 'click', 'a', function() {
		var filterValue = $(this).attr('data-filter');		
		$container.isotope({ filter: filterValue });
	});


});