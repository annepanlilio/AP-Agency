
// *************************************************************************************************** //
/*
 * Header for Public facing Pages
 */

	add_action('wp_head', 'rb_agency_inserthead');

		// Call Custom Code to put in header
		function rb_agency_inserthead() {
			// Ensure we are NOT in the admin section of wordpress
			if( !is_admin() ) {
				
				// Get Custom Styles
				wp_register_style( 'rbagency-style', plugins_url('rb-agency/style/style.css'),array(), strtotime("now"));
				wp_enqueue_style( 'rbagency-style' );

				wp_register_style( 'rbagency-formstyle', plugins_url('rb-agency/style/forms.css'));
				wp_enqueue_style( 'rbagency-formstyle' );

				wp_register_style( 'rbagency-datepicker-theme', plugins_url('rb-agency/style/jquery-ui/jquery-ui.theme.min.css'));
				wp_enqueue_style( 'rbagency-datepicker-theme' );

				wp_register_style( 'rbagency-datepicker', plugins_url('rb-agency/style/jquery-ui/jquery-ui.min.css'));
				wp_enqueue_style( 'rbagency-datepicker' );

				wp_enqueue_script( 'jquery-ui-datepicker' );

			}
				?>
				<script type="text/javascript">
				var rb_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				</script>
				<script type="text/javascript">
				jQuery(function(){
						jQuery(".rb-datepicker").each(function(){
							jQuery(this).datepicker({ dateFormat: "yy-mm-dd" }).val(jQuery(this).val());
						})
						if(jQuery( "input[id=rb_datepicker_from],input[id=rb_datepicker_to]").length){
							jQuery( "input[id=rb_datepicker_from],input[id=rb_datepicker_to]").datepicker({
								dateFormat: "yy-mm-dd",
								defaultDate: "+1w",
								changeMonth: true,
								numberOfMonths: 3,
								onSelect: function( selectedDate ) {
									if(this.id == 'input[id=rb_datepicker_from]'){
									  var dateMin = jQuery('input[id=rb_datepicker_from]').datepicker("getDate");
									  var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); // Min Date = Selected + 1d
									  var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
									  jQuery('input[id=rb_datepicker_from]').datepicker("option","minDate",rMin);
									  jQuery('input[id=rb_datepicker_to]').datepicker("option","maxDate",rMax);                    
									}

								}
							});
						}
				});
				</script>

				<?php



			add_action('wp_enqueue_scripts', 'rb_agency_insertscripts');

			function rb_agency_insertscripts() {
				if( !is_admin() ) {
					if(get_query_var('type') == "search-basic" || get_query_var('type') == "search-badvanced" ){
						wp_enqueue_script( 'customfields-search', plugins_url('js/js-customfields.js', __FILE__) );
					}
				}
			}
		}










		// *************************************************************************************************** //
		/*
		 * Add Custom Classes to <body>
		 */

			add_filter("body_class", "rb_agency_insertbodyclass");
				// Add CSS Class based on URL
				function rb_agency_insertbodyclass($classes) {
					// Remove Blog
					if (rb_is_page("rb_profile")) {
						$classes[] = 'rbagency-profile';
					} elseif (rb_is_page("rb_category")) {
						$classes[] = 'rbagency-category';
					} elseif (rb_is_page("rb_register")) {
						$classes[] = 'rbagency-register';
					} elseif (rb_is_page("rb_search")) {
						$classes[] = 'rbagency-search';
					} elseif (rb_is_page("rb_print")) {
						$classes[] = 'rbagency-print';
					} else {
						$classes[] = 'rbagency';
					}
					return $classes;
				}

