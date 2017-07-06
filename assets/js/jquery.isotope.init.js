var $ = jQuery.noConflict();

$(window).load(function() {

	var filterValue = "";

	// cache container
    // var $container = $('#profile-list');

    // $container.isotope({
    //     itemSelector : '.rbprofile-list',
    //     layoutMode : 'fitColumns'
    // });

    var $container = $('#profile-list').isotope({
		// options
		itemSelector: '.rbprofile-list',
		layoutMode: 'fitRows',
		getSortData: {
			age: function(itemElem) {
				var age = $(itemElem).data('age');
				return parseInt(age);
			},
			name: '[data-name]',
			date_joined: '[data-date-joined]',
			display_name: '[data-display-name]',
			gender: '[data-gender]',
		},
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
		filterValue = $(this).attr('data-filter');		
		$container.isotope({ filter: filterValue });
	});

	$('#sort_by').on( 'change', function() {		
		var sortValue = $(this).find(':selected').data('sort-value');
  		$container.isotope({ sortBy: sortValue, sortAscending: true });
  		console.log(sortValue);
  		
  		var label = [];

  		if(sortValue == "age"){
  			label = ["young to old", "old to young"]
  			updateSortOption(label);
  		}
  		if(sortValue == "name" || sortValue == "display-name"){
  			label = ["a - z", "z - a"]
  			updateSortOption(label);
  		}
  		if(sortValue == "date-joined"){
  			label = ["ascending", "descending"]
  			updateSortOption(label);
  		}
  		if(sortValue == "gender"){
  			label = ["female first", "male first"]
  			updateSortOption(label);
  		}
	});

	$('#sort_option').on( 'change', function() {		
		var sortOption = $(this).find(':selected').val();
		filterValue = $('#sort_by').find(':selected').data('sort-value');
  		
  		if(filterValue == "age"){
  			sortOptions(filterValue, sortOption);
  		}
  		if(filterValue == "name" || filterValue == "display-name"){
  			sortOptions(filterValue, sortOption);
  		}
  		if(filterValue == "date-joined"){
  			sortOptions(filterValue, sortOption);
  		}
  		if(filterValue == "gender"){
  			sortOptions(filterValue, sortOption);
  		}
	});

	function sortOptions(sortby, sortoption){
		if(sortoption == 0) {
  			$container.isotope({ sortBy: sortby, sortAscending: true });
  		} else {
  			$container.isotope({ sortBy: sortby, sortAscending: false });
  		}
  		console.log(sortby);
  		console.log(sortoption);
	}

	function updateSortOption(label){
		var sortOption = $('#sort_option');
	    sortOption.empty();
	    sortOption.append("<option value=''>Sort Options</option>");
	    sortOption.append("<option value='0'>"+label[0]+"</option>");
	    sortOption.append("<option value='1'>"+label[1]+"</option>");
	}

	// filter buttons
	// $('#sort_by').change(function(){

	// 	var $this = $(this);

	// 	// store filter value in object
	// 	// i.e. filters.color = 'red'
	// 	var group = $this.attr('data-filter-group');

	// 	filters[ group ] = $this.find(':selected').attr('data-filter-value');
	// 	// console.log( $this.find(':selected') )
	// 	// convert object into array
	// 	var isoFilters = [];
	// 	for ( var prop in filters ) {
	// 		isoFilters.push( filters[ prop ] )
	// 	}
	// 	console.log(filters);
	// 	var selector = isoFilters.join('');
	// 	$container.isotope({ filter: selector });
	// 	return false;
	// });


});