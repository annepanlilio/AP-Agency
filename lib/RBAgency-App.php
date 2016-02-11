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
				$rb_agency_value_stylesheet = isset($rb_agency_options_arr['rb_agency_value_stylesheet']) ? $rb_agency_options_arr['rb_agency_value_stylesheet']:0;
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_layoutprofileviewmode = isset($rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']) ? $rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']:0;
				

				wp_register_style( 'rbagency-print-style', RBAGENCY_PLUGIN_URL .'assets/css/print.css', array(), strtotime("now"));
				wp_enqueue_style( 'rbagency-print-style' );

				// Set Default Values
					// Open File & Get Base Style if not exists
					if (!isset($rb_agency_value_stylesheet) || empty($rb_agency_value_stylesheet)) {

						if (file_exists(RBAGENCY_PLUGIN_DIR ."assets/css/style.css")) {
							// Use Custom
							$rb_agency_stylesheet = RBAGENCY_PLUGIN_DIR ."assets/css/style.css";
						} else { // Use Base
							$rb_agency_stylesheet = RBAGENCY_PLUGIN_DIR ."assets/css/style_base.css";
						}

						// Open File
						$rb_agency_stylesheet_file = fopen($rb_agency_stylesheet,"r") or exit("Unable to open file to read!");

						// Initialize Stgring
						$rb_agency_stylesheet_string = "";

						// Get all lines from css file
						while(!feof($rb_agency_stylesheet_file)) {
							$rb_agency_stylesheet_string .= fgets($rb_agency_stylesheet_file);
						}

						// Close File
						fclose($rb_agency_stylesheet_file);

						$rb_agency_value_stylesheet = $rb_agency_stylesheet_string;
					}

				wp_add_inline_style( 'rbagency-print-style', $rb_agency_value_stylesheet );

				wp_register_style( 'rbagency-formstyle',RBAGENCY_PLUGIN_URL .'assets/css/forms.css' );
				wp_enqueue_style( 'rbagency-formstyle' );

				wp_dequeue_style( 'rbagency-datepicker-css');
				wp_register_style( 'rbagency-datepicker-css', RBAGENCY_PLUGIN_URL .'assets/css/jquery-ui/jquery-ui.theme.min.css');
				wp_enqueue_style( 'rbagency-datepicker-css' );

				wp_register_style( 'rbagency-datepicker', RBAGENCY_PLUGIN_URL .'assets/css/jquery-ui/jquery-ui.min.css');
				wp_enqueue_style( 'rbagency-datepicker' );

				wp_register_style( 'rbagency-fontawesome', RBAGENCY_PLUGIN_URL .'ext/fontawesome/css/font-awesome.min.css');
				wp_enqueue_style( 'rbagency-fontawesome' );

				if($rb_agency_option_layoutprofileviewmode == 1){
					wp_register_style( 'rba-magnific-popup', RBAGENCY_PLUGIN_URL .'assets/css/magnific-popup.css');
					wp_enqueue_style( 'rba-magnific-popup' );
				}
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
			
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_layoutprofileviewmode = isset($rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']) ? $rb_agency_options_arr['rb_agency_option_layoutprofileviewmode']:0;

			wp_register_script( 'rb-customfields-search', RBAGENCY_PLUGIN_URL .'assets/js/js-customfields.js' );
			wp_enqueue_script( 'rb-customfields-search' );
			wp_register_script( 'rb-print-profile', RBAGENCY_PLUGIN_URL .'assets/js/rb-printProfiles.js' );
			wp_enqueue_script( 'rb-print-profile' );

			if($rb_agency_option_layoutprofileviewmode == 1){
				wp_register_script( 'rba-magnific-popup-js', RBAGENCY_PLUGIN_URL .'assets/js/jquery.magnific-popup.min.js' );
				wp_enqueue_script( 'rba-magnific-popup-js' );

				wp_register_script( 'rba-initmagnific-popup-js', RBAGENCY_PLUGIN_URL .'assets/js/initmagnific.js' );
				wp_enqueue_script( 'rba-initmagnific-popup-js' );				
			}
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
				$classes[] = is_user_logged_in() ? 'rbagency-profile':'profile-login';
			} elseif (rb_is_page("rb_category")) {
				$classes[] = 'rbagency-category';
			} elseif (rb_is_page("rb_register")) {
				$classes[] = 'rbagency-register';
			} elseif (rb_is_page("profile_search")) {
				$classes[] = 'rbagency-search';
				$classes[] = 'profile-search';
			} elseif (rb_is_page("basic_search")) {
				$classes[] = 'rbagency-search';
				$classes[] = 'basic-search';
			} elseif (rb_is_page("advanced_search")) {
				$classes[] = 'rbagency-search';
				$classes[] = 'advanced-search';
			} elseif (rb_is_page("rb_print")) {
				$classes[] = 'rbagency-print';
			} elseif (rb_is_page("search_results")) {
				$classes[] = 'rbagency-search';
				$classes[] = 'search-results';
			}
			// RB Agency Casting Pages
			elseif (rb_is_page("casting_login")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'casting-login';
			}
			elseif (rb_is_page("casting_postjob")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'casting-postjob';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("casting_manage")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'casting-manage';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("browse_jobs")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'browse-jobs';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("view_applicants")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'view-applicants';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("profile_casting")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'profile-casting';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("casting_register")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'casting-register';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("casting_dashboard")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'casting-dashboard';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("job_detail")) {
				$classes[] = 'rbagency-casting';
				$classes[] = 'job-detail';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			}
			// RB Interact Pages
			elseif (rb_is_page("profile_login")) {
				$classes[] = 'rba-interact';
				$classes[] = 'profile-login';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("profile_register")) {
				$classes[] = 'rba-interact';
				$classes[] = 'profile-register';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} elseif (rb_is_page("profile_member")) {
				$classes[] = 'rba-interact';
				$classes[] = 'profile-member';
				$classes[] = is_user_logged_in() ? 'logged-in':'logged-out';
			} else {
				$classes[] = 'rbagency';
			}
			return $classes;
		}
}
?>
