<?php 
/*if (session_status() == PHP_SESSION_NONE) {
	session_start();
}*/
header("Cache-control: private"); //IE 6 Fix

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

	/*
	 * Get Preferences
	 */

		$rb_agency_options_arr = get_option('rb_agency_options');

			$rb_agency_value_agencyname = isset($rb_agency_options_arr['rb_agency_value_agencyname'])?$rb_agency_options_arr['rb_agency_value_agencyname']:get_bloginfo('name');
			$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy'])?$rb_agency_options_arr['rb_agency_option_privacy']:0;
			$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:1;
			$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
			$rb_agency_option_profilelist_sidebar = isset($rb_agency_options_arr['rb_agency_option_profilelist_sidebar'])?$rb_agency_options_arr['rb_agency_option_profilelist_sidebar']:0;

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
					

				} else {
					// None
					$reltype = "";
					$reltarget = "";
				}

			// Gallery Order
			$rb_agency_option_galleryorder = isset($rb_agency_options_arr['rb_agency_option_galleryorder'])?$rb_agency_options_arr['rb_agency_option_galleryorder']:0;
				if ($rb_agency_option_galleryorder == 1) { $orderBy = "ProfileMediaID DESC, ProfileMediaPrimary DESC"; } else { $orderBy = "ProfileMediaID ASC, ProfileMediaPrimary DESC"; }


	/*
	 * Get Profile
	 */

		global $wpdb;

		$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='%s'";
		$results = $wpdb->get_results($wpdb->prepare($query,$profileURL),ARRAY_A) or die ( __("No Profile Found.", rb_agency_TEXTDOMAIN ));
		$count = count($results);
	foreach($results as $data) {
			$ProfileID					=$data['ProfileID'];
			$ProfileUserLinked			=$data['ProfileUserLinked'];
			$ProfileGallery				=stripslashes($data['ProfileGallery']);
			$ProfileContactDisplay		=stripslashes($data['ProfileContactDisplay']);
			$ProfileContactNameFirst	=stripslashes($data['ProfileContactNameFirst']);
			$ProfileContactNameLast		=stripslashes($data['ProfileContactNameLast']);
				if ($rb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID ". $ProfileID;
				} elseif ($rb_agency_option_profilenaming == 4) {
					$ProfileContactDisplay = $ProfileContactNameFirst;
				} elseif ($rb_agency_option_profilenaming == 5) {
					$ProfileContactDisplay = $ProfileContactNameLast;
				}
			$ProfileContactEmail		=stripslashes($data['ProfileContactEmail']);
			$ProfileType				=$data['ProfileType'];
			$ProfileContactWebsite		=stripslashes($data['ProfileContactWebsite']);
			$ProfileContactPhoneHome	=stripslashes($data['ProfileContactPhoneHome']);
			$ProfileContactPhoneCell	=stripslashes($data['ProfileContactPhoneCell']);
			$ProfileContactPhoneWork	=stripslashes($data['ProfileContactPhoneWork']);
			$ProfileGender    			=stripslashes($data['ProfileGender']);
			$ProfileDateBirth	    	=stripslashes($data['ProfileDateBirth']);
			$ProfileAge 				= rb_agency_get_age($ProfileDateBirth);
			$ProfileLocationCity		=stripslashes($data['ProfileLocationCity']);
			$ProfileLocationState		=stripslashes($data['ProfileLocationState']);
			$ProfileLocationZip			=stripslashes($data['ProfileLocationZip']);
			$ProfileLocationCountry		=stripslashes($data['ProfileLocationCountry']);
			$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
			$ProfileIsActive			=stripslashes($data['ProfileIsActive']); // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
			$ProfileStatHits			=stripslashes($data['ProfileStatHits']);
			$ProfileDateViewLast		=stripslashes($data['ProfileDateViewLast']);
			
			// Update Stats
			$updateStats = $wpdb->query("UPDATE ". table_agency_profile ." SET ProfileStatHits = ProfileStatHits + 1, ProfileDateViewLast = NOW() WHERE ProfileID = '". $ProfileID ."' LIMIT 1");
		}

		 rb_agency_add_editlink($ProfileID);
	
		 function get_current_viewingID(){
				global $ProfileID;
				return $ProfileID;
		 }
	/*
	 * Customize Page Title
	 */

		// Change Title
		if(!function_exists("rb_agency_override_title")){
			add_filter('wp_title', 'rb_agency_override_title', 10, 2);
				function rb_agency_override_title(){
					global $ProfileContactDisplay;
					return bloginfo('name') ." > ". $ProfileContactDisplay ."";
				}
		}
	

	/*
	 * TODO: WHT IS THIS?
	 */
		// GET HEADER  
		if(isset($_POST['print_all_images']) && $_POST['print_all_images']!=""){
			include(rb_agency_BASEREL . 'theme/printable-profile.php');
			exit;
		}


	/*
	 * Create View
	 */

		echo $rb_header = RBAgency_Common::rb_header();

		echo "<div id=\"container\" "; if ($rb_agency_option_profilelist_sidebar==0) { echo "class=\"one-column\""; } echo">\n";
		echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
		if ($count > 0) {

			// P R I V A C Y FILTER ====================================================
			if ( ( $rb_agency_option_privacy >= 1 && is_user_logged_in() ) || 
				( $rb_agency_option_privacy > 1 && isset($_SESSION['SearchMuxHash']) )
				|| ($rb_agency_option_privacy == 0) ||

				//admin users
				(is_user_logged_in() && current_user_can( 'edit_posts' )) ||

				//  Must be logged as "Client" to view model list and profile information
				($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype())) {

				// Ok, but whats the status of the profile?
				if ( ($ProfileIsActive == 1) || ($ProfileUserLinked == $CurrentUser) || current_user_can('level_10') ) {
					// If the profile is active or its your own profile or you are an admin, show it.
					include (rb_agency_BASEREL .'view/layout/'. $rb_agency_option_layoutprofile .'/include-profile.php');
				} elseif(strpos($_SERVER['HTTP_REFERER'],'client-view') > 0){
					// Show it if it came from an email sent
					include (rb_agency_BASEREL .'view/layout/'. $rb_agency_option_layoutprofile .'/include-profile.php');
				} else {
					// Dont show it
					echo "". __("Inactive Profile", rb_agency_TEXTDOMAIN) ."\n";
				}
			} else {
				// hold last model requested as session so we can return them where we found them 
				$ProfileLastViewed = get_query_var('profile');
				$profileviewed = get_query_var('target');
				$_SESSION['ProfileLastViewed'] = $profileviewed;
				include(rb_agency_BASEREL .'theme/include-login.php');
			}

		} else {
			// There is no record found.
			echo "". __("Profile not found", rb_agency_TEXTDOMAIN) ."\n";
		}

		echo "  </div>\n";
		echo "</div>\n";

		echo $rb_footer = RBAgency_Common::rb_footer();
?>