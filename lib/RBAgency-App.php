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
				add_action( 'wp_head', array('RBAgency_App', 'rbagency_head_scripts') );

				// Add Scripts to Footer Section 
				add_action( 'wp_footer', array('RBAgency_App', 'rbagency_footer_scripts') );

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

				wp_dequeue_style( 'rbagency-datepicker-css');
				wp_register_style( 'rbagency-datepicker-css', RBAGENCY_PLUGIN_URL .'assets/css/jquery-ui/jquery-ui.theme.min.css');
				wp_enqueue_style( 'rbagency-datepicker-css' );

				wp_register_style( 'rbagency-datepicker', RBAGENCY_PLUGIN_URL .'assets/css/jquery-ui/jquery-ui.min.css');
				wp_enqueue_style( 'rbagency-datepicker' );



			}
		}

		// Get Public Scripts
		public static function rbagency_head_scripts() {

			// Ensure we are in the admin section of wordpress
			if( !is_admin() ) {

				// Add Jquery script 
				wp_enqueue_script( 'jquery-core' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-datepicker' );

				

			}
				?>
				<script type="text/javascript">
				var rb_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				</script>
				
				<?php


				// TODO: Check Validity
				add_action('wp_enqueue_scripts', array('RBAgency_App', 'rb_agency_insertscripts') );

		}

		// Get public footer scripts
		public static function rbagency_footer_scripts(){
			
				wp_register_script( 'rb-customfields-search', RBAGENCY_PLUGIN_URL .'assets/js/js-customfields.js' );
				wp_enqueue_script( 'rb-customfields-search' );
			
		}



		// TODO: Check Validity
		public static function rb_agency_insertscripts() {
			if( !is_admin() ) {
				//if(get_query_var('type') == "search-basic" || get_query_var('type') == "search-advanced" ){
			//		wp_enqueue_script( 'customfields-search' );
				//}
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