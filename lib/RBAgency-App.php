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

				// Get Custom CSS
				$rb_agency_options_arr = get_option('rb_agency_layout_options');
					$rb_agency_value_stylesheet = $rb_agency_options_arr['rb_agency_value_stylesheet'];
					wp_add_inline_style( 'child-theme', $rb_agency_value_stylesheet );

				wp_register_style( 'rbagency-print-style', RBAGENCY_PLUGIN_URL .'assets/css/print.css', array(), strtotime("now"));
				wp_enqueue_style( 'rbagency-print-style' );

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
				//wp_enqueue_script( 'jquery-core' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-datepicker' );

				

			}
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
			$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";

				?>

				<script type="text/javascript">
				var rb_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

				var rb_agency = {
					logo: "<?php echo $rb_agency_option_agencylogo;?>",
					name: "<?php echo $rb_agency_option_agencyname;?>",
					site_url: "<?php echo get_bloginfo('url');?>"
				}
				</script>
				
				<?php


				// TODO: Check Validity
				add_action('wp_enqueue_scripts', array('RBAgency_App', 'rb_agency_insertscripts') );

		}

		// Get public footer scripts
		public static function rbagency_footer_scripts(){
			
				wp_register_script( 'rb-customfields-search', RBAGENCY_PLUGIN_URL .'assets/js/js-customfields.js' );
				wp_enqueue_script( 'rb-customfields-search' );
				wp_register_script( 'rb-print-profile', RBAGENCY_PLUGIN_URL .'assets/js/rb-printProfiles.js' );
				wp_enqueue_script( 'rb-print-profile' );
			
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