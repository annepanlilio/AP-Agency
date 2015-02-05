<?php

/*
 * RBAgency_App Class
 *
 * These are public facing functions
 */

class RBAgency_App {

	// *************************************************************************************************** //

	/*
	 * Initialize
	 */

		public static function init(){

			// Ensure we are NOT in the admin section of wordpress
			if( !is_admin() ) {
				// Add Styles to Admin Head Section 
				add_action( 'wp_head', array('RBAgency_App', 'rbagency_head_style') );

				// Add Scripts to Admin Head Section 
				add_action( 'wp_head', array('RBAgency_App', 'rbagency_head_scripts'), 0 );

				// Apply Body Class
				add_filter( 'body_class', array('RBAgency_App', 'rb_agency_insertbodyclass') );
			}

		}

	// *************************************************************************************************** //

	/*
	 * Define Public Styles
	 */

		// Get Public Styles
		public static function rbagency_head_style() {

			// Ensure we are in the admin section of wordpress
			if( !is_admin() ) {

				// Get Custom Styles
				wp_register_style( 'rbagency-style', RBAGENCY_PLUGIN_URL .'assets/css/style.css', array(), strtotime("now"));
				wp_enqueue_style( 'rbagency-style' );

				wp_register_style( 'rbagency-formstyle',RBAGENCY_PLUGIN_URL .'assets/css/forms.css' );
				wp_enqueue_style( 'rbagency-formstyle' );

				wp_register_style( 'rbagency-datepicker-theme', RBAGENCY_PLUGIN_URL .'assets/jquery-ui/jquery-ui.theme.min.css');
				wp_enqueue_style( 'rbagency-datepicker-theme' );

				wp_register_style( 'rbagency-datepicker', RBAGENCY_PLUGIN_URL .'rb-agency/style/jquery-ui/jquery-ui.min.css');
				wp_enqueue_style( 'rbagency-datepicker' );

			}
		}

		// Get Public Scripts
		public static function rbagency_head_scripts() {

			// Ensure we are in the admin section of wordpress
			if( !is_admin() ) {

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


				// TODO: Check Validity
				add_action('wp_enqueue_scripts', array('RBAgency_App', 'rb_agency_insertscripts') );

		}



		// TODO: Check Validity
		public static function rb_agency_insertscripts() {
			if( !is_admin() ) {
				if(get_query_var('type') == "search-basic" || get_query_var('type') == "search-badvanced" ){
					wp_enqueue_script( 'customfields-search', RBAGENCY_PLUGIN_URL .'assets/js/js-customfields.js' );
				}
			}
		}


	// *************************************************************************************************** //

	/*
	 * Add Custom Classes to <body>
	 */

		// Add CSS Class based on URL
		public static function rb_agency_insertbodyclass($classes) {
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

}
?>