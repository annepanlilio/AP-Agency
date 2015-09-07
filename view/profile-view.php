<?php 
/*if (session_status() == PHP_SESSION_NONE) {
	session_start();
}*/
//header("Cache-control: private"); //IE 6 Fix

	/*
	 * Get Target Details
	 */

		// Get User Information
		global $user_ID; 
		global $current_user;
		get_currentuserinfo();
		$CurrentUser = $current_user->ID;

		// Get Target Profile ID
		$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
		$urlexploade = explode("/", $profileURLString);
		$profileURL=$urlexploade[0];

		//echo $profileURL;
		
		
		global $wpdb;
		$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='%s'";
		$results = $wpdb->get_results($wpdb->prepare($query,$profileURL),ARRAY_A);
		
		
		if(!$results){
		
			$rb_agency_options_arr = get_option('rb_agency_options');
			if(!empty($rb_agency_options_arr['rb_agency_option_404profile'])){
				wp_redirect(home_url().$rb_agency_options_arr['rb_agency_option_404profile']);
				exit;
			}
			echo $rb_header = RBAgency_Common::rb_header();
			$enable_sidebar = ($rb_agency_option_profilelist_sidebar == 0) ? 'one-column' : ''; // check sidebar

			echo "<div class=\"". $enable_sidebar ."\">
			   <div id=\"rbcontent\">
					<div class='restricted'>
						". __("Profile not found", RBAGENCY_TEXTDOMAIN) ."
					</div><!-- #content -->
			   </div>
			</div>\n";

			echo $rb_footer = RBAgency_Common::rb_footer();
			exit;
		}

	/*
	 * Get Preferences
	 */

		$rb_agency_options_arr = get_option('rb_agency_options');

			$rb_agency_value_agencyname = isset($rb_agency_options_arr['rb_agency_value_agencyname'])?$rb_agency_options_arr['rb_agency_value_agencyname']:get_bloginfo('name');
			$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy'])?$rb_agency_options_arr['rb_agency_option_privacy']:0;
			$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:1;
			$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
			$rb_agency_option_profilelist_sidebar = isset($rb_agency_options_arr['rb_agency_option_profilelist_sidebar'])?$rb_agency_options_arr['rb_agency_option_profilelist_sidebar']:0;
			$rb_agency_option_profilelist_expanddetails_year = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_year'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_year']:0;
			$rb_agency_option_profilelist_expanddetails_month = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_month'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_month']:0;
			$rb_agency_option_profilelist_expanddetails_day = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_day'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_day']:0;
			// Layout Type
			$rb_agency_option_layoutprofile = isset($rb_agency_options_arr['rb_agency_option_layoutprofile'])?$rb_agency_options_arr['rb_agency_option_layoutprofile']:0;
				$rb_agency_option_layoutprofile = sprintf("%02s", $rb_agency_option_layoutprofile);

			// Gallery Type
			$rb_agency_option_gallerytype = isset($rb_agency_options_arr['rb_agency_option_gallerytype'])?$rb_agency_options_arr['rb_agency_option_gallerytype']:0;
				if ($rb_agency_option_gallerytype == 1) {
					// Lightbox 2
					//$reltype = "data-lightbox=\"rbagency\"";
					$reltype = "rel=\"lightbox[rbagency]\"";
					$reltarget = ""; // target=\"_blank\"

					if($rb_agency_option_layoutprofile != "09"){
						wp_enqueue_script( 'lightbox2', plugins_url('/ext/lightbox2/js/lightbox-2.6.min.js', dirname(__FILE__)), array( 'jquery' ));
					}
						wp_register_style( 'lightbox2', plugins_url('/ext/lightbox2/css/lightbox.css', dirname(__FILE__)) );
						wp_enqueue_style( 'lightbox2' );
						
						wp_register_style( 'fancybox-style', RBAGENCY_PLUGIN_URL .'ext/fancybox/jquery.fancybox.css' );
						wp_enqueue_style( 'fancybox-style' );
	
						wp_enqueue_script( 'fancybox-jquery', RBAGENCY_PLUGIN_URL .'ext/fancybox/jquery.fancybox.pack.js', array( 'jquery-latest' ));
						wp_enqueue_script( 'fancybox-jquery' );
						
						wp_enqueue_script( 'fancybox-init', RBAGENCY_PLUGIN_URL .'ext/fancybox/fancybox.init.js', array( 'jquery-latest', 'fancybox-jquery' ));
						wp_enqueue_script( 'fancybox-init' );
						


				} else {
					// None
					$reltype = "";
					$reltarget = "";
				}

			// Gallery Order
			$rb_agency_option_galleryorder = isset($rb_agency_options_arr['rb_agency_option_galleryorder'])?$rb_agency_options_arr['rb_agency_option_galleryorder']:0;
				if ($rb_agency_option_galleryorder == 1) {$orderBy = "ProfileMediaID DESC, ProfileMediaPrimary DESC"; } else {$orderBy = "ProfileMediaID ASC, ProfileMediaPrimary DESC"; }


	/*
	 * Get Profile
	 */

	/* 	global $wpdb;
		$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='%s'";
		$results = $wpdb->get_results($wpdb->prepare($query,$profileURL),ARRAY_A) or die ( __("No Profile Found.", RBAGENCY_TEXTDOMAIN ));
 */
		$count = count($results);
		foreach($results as $rbdata) {
			$ProfileID					=$rbdata['ProfileID'];
			$ProfileUserLinked			=$rbdata['ProfileUserLinked'];
			$ProfileGallery				=stripslashes($rbdata['ProfileGallery']);
			$ProfileContactDisplay		=stripslashes($rbdata['ProfileContactDisplay']);
			$ProfileContactNameFirst	=stripslashes($rbdata['ProfileContactNameFirst']);
			$ProfileContactNameLast		=stripslashes($rbdata['ProfileContactNameLast']);
				if ($rb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID-". $ProfileID;
				} elseif ($rb_agency_option_profilenaming == 4) {
					$ProfileContactDisplay = $ProfileContactNameFirst;
				} elseif ($rb_agency_option_profilenaming == 5) {
					$ProfileContactDisplay = $ProfileContactNameLast;
				}
			$ProfileContactEmail		=stripslashes($rbdata['ProfileContactEmail']);
			$ProfileType				=$rbdata['ProfileType'];
			$ProfileContactWebsite		=stripslashes($rbdata['ProfileContactWebsite']);
			$ProfileContactPhoneHome	=stripslashes($rbdata['ProfileContactPhoneHome']);
			$ProfileContactPhoneCell	=stripslashes($rbdata['ProfileContactPhoneCell']);
			$ProfileContactPhoneWork	=stripslashes($rbdata['ProfileContactPhoneWork']);
			$ProfileGender				=stripslashes($rbdata['ProfileGender']);
			$ProfileDateBirth			=stripslashes($rbdata['ProfileDateBirth']);
			if($rb_agency_option_profilelist_expanddetails_year || $rb_agency_option_profilelist_expanddetails_day || $rb_agency_option_profilelist_expanddetails_month){

				$arr_query = array();
				$arr_query = null;

				$HideAgeYear = get_user_meta($rbdata['ProfileID'],"rb_agency_hide_age_year",true);
				if($HideAgeYear == 1){
					$arr_query['year_style'] = 'style="display:none!important;"';
					$hideY = 1;
				}
				$HideAgeMonth = get_user_meta($rbdata['ProfileID'],"rb_agency_hide_age_month",true);
				if($HideAgeMonth == 1){
					$arr_query['month_style'] = 'style="display:none!important;"';
					$hideM = 1;
				}
				$HideAgeDay = get_user_meta($rbdata['ProfileID'],"rb_agency_hide_age_day",true);
				if($HideAgeDay == 1){
					$arr_query['day_style'] = 'style="display:none!important;"';
					$hideD = 1;
				}

				$ProfileAge				= rb_agency_get_age($ProfileDateBirth,$arr_query);
			} else {
				$ProfileAge = "";
			}
			$ProfileLocationCity		=stripslashes($rbdata['ProfileLocationCity']);
			$ProfileLocationState		=stripslashes($rbdata['ProfileLocationState']);
			$ProfileLocationZip			=stripslashes($rbdata['ProfileLocationZip']);
			$ProfileLocationCountry		=stripslashes($rbdata['ProfileLocationCountry']);
			$ProfileDateUpdated			=stripslashes($rbdata['ProfileDateUpdated']);
			$ProfileIsActive			=stripslashes($rbdata['ProfileIsActive']); // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
			$isPrivate			        =stripslashes($rbdata['isPrivate']);
			$ProfileStatHits			=stripslashes($rbdata['ProfileStatHits']);
			$ProfileDateViewLast		=stripslashes($rbdata['ProfileDateViewLast']);

			// Update Stats
			$updateStats = $wpdb->query("UPDATE ". table_agency_profile ." SET ProfileStatHits = ProfileStatHits + 1, ProfileDateViewLast = NOW() WHERE ProfileID = '". $ProfileID ."' LIMIT 1");
		}

		RBAgency_Admin::link_profile_edit($ProfileID);

		function get_current_viewingID(){
			global $ProfileID;
			return $ProfileID;
		}

	/*
	 * Customize Page Title
	 */
		// Override Title?
		if ( !function_exists('rb_agency_override_title') ) {
			// title tag implementation with backward compatibility
			if ( !function_exists( '_wp_render_title_tag' ) ) {

				add_filter('wp_title', 'rb_agency_override_title', 10, 2);
					function rb_agency_override_title(){
						global $ProfileContactDisplay;

						$title = $ProfileContactDisplay ." | ". bloginfo('name');

						return $title;
					}

			} else { // WordPress 4.1 or greater

				// enabling theme support for title tag
				add_action( 'after_setup_theme', 'theme_slug_setup' );
					function theme_slug_setup() {
						add_theme_support( 'title-tag' );
					}

				add_filter( 'wp_title', 'custom_titles', 10, 2 );
					function custom_titles( $title, $sep ) {
						global $ProfileContactDisplay;

						$title = $ProfileContactDisplay ." $sep ". $title;

						return $title;
					}
			}

		}


	/*
	 * TODO: WHAT IS THIS?
	 */
		// GET HEADER  
		if(isset($_POST['print_all_images']) && $_POST['print_all_images']!=""){
			include(RBAGENCY_PLUGIN_DIR . 'theme/printable-profile.php');
			exit;
		}

	/*
	 * Create View
	 */

		echo $rb_header = RBAgency_Common::rb_header();
	/*
	 * Notify for under development layouts
	 */
		$allowed_hosts = array('demo1.modelingagencysoftware.com', 'demo2.modelingagencysoftware.com', 'demo3.modelingagencysoftware.com', 'demo4.modelingagencysoftware.com');
		$arr_under_dev = array();
		$arr_custom_layout = array();

		if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
			$arr_under_dev = array("04","05");
			$arr_custom_layout = array("06","07","08","09","10","11","12");
		} else {
			$arr_custom_pop_layout = array("04","05","06","07","08","09","10","11","12");
			if(in_array($rb_agency_option_layoutprofile, $arr_custom_pop_layout)){
				add_thickbox();
				echo "<div id=\"rb-underdev-layout\" style=\"display:none\"><div style=\"text-align:center;\">This is a Custom Layout. <br/>Please contact RB Plugin Support for quote &amp; integration</div></div>";
				echo "<script type=\"text/javascript\">";
				echo "jQuery(window).load(function(){";
				echo 'tb_show("RB Plugin","#TB_inline?height=100&width=605&amp;inlineId=rb-underdev-layout&modal=true",null);';
				echo "});";
				echo "</script>";
			}
		}



		$enable_sidebar = ($rb_agency_option_profilelist_sidebar == 0) ? 'one-column' : ''; // check sidebar

		echo "<div class=\"". $enable_sidebar ."\">\n";
		echo "    <div id=\"rbcontent\">\n";
		if ($count > 0) {

			// P R I V A C Y FILTER ====================================================
			if ( ( $rb_agency_option_privacy >= 1 && isset($_SESSION['SearchMuxHash']) ) ||
				// Public
				($rb_agency_option_privacy == 0) ||

				($rb_agency_option_privacy == 3 && is_user_logged_in()) ||

				($rb_agency_option_privacy == 2 && is_user_logged_in()) ||

				//admin users
				(is_user_logged_in() && current_user_can( 'edit_posts' )) ||

				//  Must be logged as "Client" to view model list and profile information
				($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype() /*|| ($ProfileUserLinked == $CurrentUser)*/  ) ||

				//  Model list public. Must be logged to view profile information
				($rb_agency_option_privacy == 1 && is_user_logged_in()) ||

				// View own profile
				($ProfileUserLinked == $CurrentUser && is_user_logged_in())
				) {

				// Ok, but whats the status of the profile?
				
		
				//check if the profile is in private
				if(!empty($isPrivate) and !is_user_logged_in()){
					echo "	<div class='restricted'>\n";
						echo "Profile is in private settings. Please login to view this profile.<br />";
					echo "  </div><!-- #content -->\n";
				}else{
					if ( ($ProfileIsActive == 1) || ($ProfileUserLinked == $CurrentUser) || current_user_can('level_10') ) {
						// If the profile is active or its your own profile or you are an admin, show it
						if(in_array($rb_agency_option_layoutprofile, $arr_under_dev)){
							echo "	<div id=\"rbprofile\">\n";
							echo "		<div id=\"rblayout-one rblayout-".$rb_agency_option_layoutprofile."\" class=\"rblayout\">\n";
									echo "This layout is under development.";
							echo " 		</div>\n";
							echo " 	</div>\n";
	
						} elseif(in_array($rb_agency_option_layoutprofile, $arr_custom_layout)){
							echo "	<div id=\"rbprofile\">\n";
							echo "		<div id=\"rblayout-one rblayout-".$rb_agency_option_layoutprofile."\" class=\"rblayout\">\n";
										echo "Please contact RB Plugin Support for custom layouts.";
							echo " 		</div>\n";
							echo " 	</div>\n";
	
						} else {
							include (RBAGENCY_PLUGIN_DIR .'view/layout/'. $rb_agency_option_layoutprofile .'/include-profile.php');
						}
	
					} elseif(strpos($_SERVER['HTTP_REFERER'],'client-view') > 0){
						// Show it if it came from an email sent
						if(in_array($rb_agency_option_layoutprofile, $arr_under_dev)){
									echo "This layout is under development.";
						} elseif(in_array($rb_agency_option_layoutprofile, $arr_custom_layout)){
									echo "Please contact RB Plugin Support for custom layouts.";
						} else {
							include (RBAGENCY_PLUGIN_DIR .'view/layout/'. $rb_agency_option_layoutprofile .'/include-profile.php');
						}
					} else {
						// Dont show it
						echo "". __("Inactive Profile", RBAGENCY_TEXTDOMAIN) ."\n";
					}
				}
			} else {
				if($rb_agency_option_privacy == 3 ){ // if casting only
					if(is_user_logged_in()){
						rb_get_profiletype();
					} else {
						echo "	<div class='restricted'>\n";
						if ( class_exists("RBAgencyCasting") ) {
						echo "Page restricted. Only Admin & Casting Agent can view this page.<br />Please <a href=\"".get_bloginfo("url")."/casting-login/\">login</a> or <a href=\"".get_bloginfo("url")."/casting-register/\">register</a>.";
						} else {
						echo "Page restricted. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login</a> or <a href=\"".get_bloginfo("url")."/profile-register/\">register</a>.";
						}
						echo "  </div><!-- #content -->\n";
					}
				} else {
					// hold last model requested as session so we can return them where we found them 
					$ProfileLastViewed = get_query_var('profile');
					$profileviewed = get_query_var('target');
					$_SESSION['ProfileLastViewed'] = $profileviewed;
					include(RBAGENCY_PLUGIN_DIR .'theme/include-login.php');
				}
			}

		} else {
			// There is no record found.
			echo "". __("Profile not found", RBAGENCY_TEXTDOMAIN) ."\n";
		}

		echo "  </div>\n";
		echo "</div>\n";

		echo $rb_footer = RBAgency_Common::rb_footer();
?>