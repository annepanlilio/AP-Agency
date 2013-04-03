<?php
#Debug Mode
//$RB_DEBUG_MODE = true;
// *************************************************************************************************** //

// Admin Head Section 
	add_action('admin_head', 'rb_agency_admin_head');
	function rb_agency_admin_head() {
		if( is_admin() ) {

			// Get Styles
			wp_register_style( 'rbagencyadmin', plugins_url('/style/admin.css', __FILE__) );
			wp_enqueue_style( 'rbagencyadmin' );

			if ( ! wp_script_is( 'jquery', 'registered' ) )
				wp_register_script( 'jquery', plugins_url( 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', __FILE__ ), false, '1.8.3' );

			wp_enqueue_script( 'customfields', plugins_url('js/js-customfields.js', __FILE__) );
		}
	}
	
// *************************************************************************************************** //
// Page Head Section
	add_action('wp_head', 'rb_agency_inserthead');
	// Call Custom Code to put in header
	function rb_agency_inserthead() {
		if( !is_admin() ) {
			
			// Get Styles
			wp_register_style( 'rbagency-style', plugins_url('/theme/style.css', __FILE__) );
			wp_enqueue_style( 'rbagency-style' );
		}	
	}

// *************************************************************************************************** //
// Add to WordPress Dashboard
	$rb_agency_options_arr = get_option('rb_agency_options');
    
	$rb_agency_option_advertise = isset($rb_agency_options_arr['rb_agency_option_advertise']) ? $rb_agency_options_arr['rb_agency_option_advertise'] : 0;
			
	if($rb_agency_option_advertise == 1) {
		add_action('wp_dashboard_setup', 'rb_agency_add_dashboard' );
		// Hoook into the 'wp_dashboard_setup' action to register our other functions
		// Create the function use in the action hook
		function rb_agency_add_dashboard() {
			global $wp_meta_boxes;
			// Create Dashboard Widgets
			wp_add_dashboard_widget('rb_agency_dashboard_quicklinks', __("RB Agency Updates", rb_agency_TEXTDOMAIN), 'rb_agency_dashboard_quicklinks');
		
			// reorder the boxes - first save the left and right columns into variables
			$left_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
			$right_dashboard = $wp_meta_boxes['dashboard']['side']['core'];
			
			// take a copy of the new widget from the left column
			$rb_agency_dashboard_merge_array = array("rb_agency_dashboard_quicklinks" => $left_dashboard["rb_agency_dashboard_quicklinks"]);
			
			unset($left_dashboard['rb_agency_dashboard_quicklinks']); // remove the new widget from the left column
			$right_dashboard = array_merge($rb_agency_dashboard_merge_array, $right_dashboard); // use array_merge so that the new widget is pushed on to the beginning of the right column's array  
			
			// finally replace the left and right columns with the new reordered versions
			$wp_meta_boxes['dashboard']['normal']['core'] = $left_dashboard; 
			$wp_meta_boxes['dashboard']['side']['core'] = $right_dashboard;
		}
		 // Create the function to output the contents of our Dashboard Widget
		function rb_agency_dashboard_quicklinks() {
			// Display Quicklinks
			$rb_agency_options_arr = get_option('rb_agency_options');
			if (isset($rb_agency_options_arr['dashboardQuickLinks'])) {
			  	echo $rb_agency_options_arr['dashboardQuickLinks'];
			}
			$rss = fetch_feed("http://rbplugin.com/category/wordpress/rbagency/feed/");
			// Checks that the object is created correctly 
			if (!is_wp_error($rss)) { 
				// Figure out how many total items there are, but limit it to 5. 
				$maxitems = $rss->get_item_quantity($num_items); 
				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items(0, $maxitems); 
			}
			echo "<div class=\"feed-searchsocial\">\n";
			if ($maxitems == 0) {
				echo "Empty Feed\n";
			} else {
				// Loop through each feed item and display each item as a hyperlink.
				foreach ( $rss_items as $item ) {
					echo "  <div class=\"blogpost\">\n";
					echo "    <h4><a href='". $item->get_permalink() ."' title='Posted ". $item->get_date('j F Y | g:i a') ."' target=\"_blank\">". $item->get_title() ."</a></h4>\n";
					echo "    <div class=\"description\">". $item->get_description() ."</div>\n";
					echo "    <div class=\"clear\"></div>\n";
					echo "  </div>\n";
				}
			}
			echo "</div>\n";
			echo "<hr />\n";
			//echo "Need <a href=\"http://rob.bertholf.com\" target=\"_blank\" title=\"SEO Resource\">SEO Advice</a>?<br />";
		}
	}

// *************************************************************************************************** //
// Add Custom Classes
	add_filter("body_class", "rb_agency_insertbodyclass");
	add_filter("post_class", "rb_agency_insertbodyclass");
	function rb_agency_insertbodyclass($classes) {
		// Remove Blog
		if (substr($_SERVER['REQUEST_URI'], 0, 9) == "/profile/") {
			$classes[] = 'rbagency-profile';
		} elseif (substr($_SERVER['REQUEST_URI'], 0, 11) == "/dashboard/") {
			$classes[] = 'rbagency-dashboard';
		} elseif (substr($_SERVER['REQUEST_URI'], 0, 18) == "/profile-category/") {
			$classes[] = 'rbagency-category';
		} elseif (substr($_SERVER['REQUEST_URI'], 0, 18) == "/profile-register/") {
			$classes[] = 'rbagency-register';
		} elseif (substr($_SERVER['REQUEST_URI'], 0, 17) == "/profile-search/") {
			$classes[] = 'rbagency-search';
		} elseif (substr($_SERVER['REQUEST_URI'], 0, 15) == "/profile-print/") {
			$classes[] = 'rbagency-print';
		} else {
			$classes[] = 'rbagency';
		}
		return $classes;
	}

// *************************************************************************************************** //
// Handle Folders
			
	// Adding a new rule
	add_filter('rewrite_rules_array','rb_agency_rewriteRules');
	function rb_agency_rewriteRules($rules) {
		$newrules = array();
		$newrules['profile-search/([0-9])$'] = 'index.php?type=search&paging=$matches[1]';
		$newrules['profile-search'] = 'index.php?type=search&target=results';
		$newrules['profile-category/(.*)/([0-9])$'] = 'index.php?type=category&target=$matches[1]&paging=$matches[2]';
		$newrules['profile-category/([0-9])$'] = 'index.php?type=category&paging=$matches[1]';
		$newrules['profile-category/(.*)$'] = 'index.php?type=category&target=$matches[1]';
		$newrules['profile-category'] = 'index.php?type=category&target=all';
		$newrules['profile-casting/(.*)$'] = 'index.php?type=casting&target=$matches[1]';
		$newrules['profile-casting'] = 'index.php?type=casting&target=casting'; 
		$newrules['profile-print'] = 'index.php?type=print';
		$newrules['profile-email'] = 'index.php?type=email';
		$newrules['dashboard'] = 'index.php?type=dashboard';
		$newrules['client-view/(.*)$'] = 'index.php?type=profilesecure&target=$matches[1]';
		$newrules['profile/(.*)/contact'] = 'index.php?type=profilecontact&target=$matches[1]';
		$newrules['profile/(.*)$'] = 'index.php?type=profile&target=$matches[1]';
		
	    $newrules['rbv'] = 'index.php?type=rbv'; // ping this page for version checker
		
	    $rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilelist_castingcart  = isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart'] : 0;
		
		$rb_agency_option_profilelist_favorite	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;
		
        if($rb_agency_option_profilelist_favorite){
			$newrules['profile-favorite'] = 'index.php?type=favorite';
	  	}
        if($rb_agency_option_profilelist_castingcart){
			$newrules['profile-casting-cart'] = 'index.php?type=castingcart';
	   	}
		return $newrules + $rules;
	}
		
	// Get Veriables & Identify View Type
	add_action( 'query_vars', 'rb_agency_query_vars' );
		function rb_agency_query_vars( $query_vars ) {
			$query_vars[] = 'type';
			$query_vars[] = 'target';
			$query_vars[] = 'paging';
			$query_vars[] = 'value';
			return $query_vars;
		}
	
	// Set Custom Template
	add_filter('template_include', 'rb_agency_template_include', 1, 1); 
		function rb_agency_template_include( $template ) {
			if ( get_query_var( 'type' ) ) {				

				//echo get_query_var( 'type' );
				if (get_query_var( 'type' ) == "search") {
					return dirname(__FILE__) . '/theme/view-search.php'; 
				} elseif (get_query_var( 'type' ) == "category") {
					return dirname(__FILE__) . '/theme/view-category.php'; 
				} elseif (get_query_var( 'type' ) == "profile") {
					return dirname(__FILE__) . '/theme/view-profile.php'; 
				} elseif (get_query_var( 'type' ) == "profilecontact") {
					return dirname(__FILE__) . '/theme/view-profile-contact.php'; 
				} elseif (get_query_var( 'type' ) == "profilesecure") {
					return dirname(__FILE__) . '/theme/view-profilesecure.php'; 
				} elseif (get_query_var( 'type' ) == "dashboard") {
					return dirname(__FILE__) . '/theme/view-dashboard.php'; 
				} elseif (get_query_var( 'type' ) == "print") {
					return dirname(__FILE__) . '/theme/view-print.php'; 
				} elseif (get_query_var( 'type' ) == "favorite") {
					return dirname(__FILE__) . '/theme/view-favorite.php'; 
				}elseif (get_query_var( 'type' ) == "casting") {
					return dirname(__FILE__) . '/theme/view-castingcart.php'; 
				}elseif (get_query_var( 'type' ) == "rbv") {
					return dirname(__FILE__) . '/rbv.php'; 
				}			  
			}
			return $template;
		}
	
	// Remember to flush_rules() when adding rules
	add_filter('init','rb_agency_flushrules');

	function rb_agency_flushRules() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}	
	
// *************************************************************************************************** //
// Functions
	function rb_agency_getprofilename($ProfileID) {
		global $rb_agency_option_profilenaming;
		
		if ($rb_agency_option_profilenaming == 0) {
			$ProfileContactDisplay = $ProfileContactNameFirst . "". $ProfileContactNameLast;
		} elseif ($rb_agency_option_profilenaming == 1) {
			$ProfileContactDisplay = $ProfileContactNameFirst . "". substr($ProfileContactNameLast, 0, 1);
		} elseif ($rb_agency_option_profilenaming == 2) {
			// It already is :)
		}
	}

	function rb_agency_cleanString($string) {
		// Remove trailing dingleberry
		if (substr($string, -1) == ",") {  $string = substr($string, 0, strlen($string)-1); }
		if (substr($string, 0, 1) == ",") { $string = substr($string, 1, strlen($string)-1); }
		// Just Incase
		$string = str_replace(",,", ",", $string);
		return $string;
	}

	function rb_agency_getActiveLanguage() {
		if (function_exists('icl_get_languages')) {
			// fetches the list of languages
		  	$languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR');
	
		  	$activeLanguage = 'en';
		
		  	// runs through the languages of the system, finding the active language
		  	foreach($languages as $language) {

				// tests if the language is the active one
				if($language['active'] == 1) {
				  	$activeLanguage = $language['language_code'];
				}
			  	return "/". $activeLanguage;
		  	}
		} else {
		  	return "";
		}
	}
	
	function rb_agency_random() {
		return preg_replace("/([0-9])/e","chr((\\1+112))",rand(100000,999999));
	}
	
	function rb_agency_get_userrole() {
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	};
	
	function rb_agency_convertdatetime($datetime) {
		// Convert
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hours, $minutes, $seconds) = explode(':', $time);
		
		$UnixTimestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
		return $UnixTimestamp;
	}
	
	function rb_agency_makeago($timestamp, $offset){
		if (isset($timestamp) && !empty($timestamp) && ($timestamp <> "0000-00-00 00:00:00") && ($timestamp <> "943920000")) {
		// Offset
		$offset = $offset-5; //adjustment for server time
		$timezone_offset = (int)$offset; // Server Time
		$time_altered = time() -  $timezone_offset *60 *60;

		// Math
		$difference = $time_altered - $timestamp;

		//printf("\$timestamp: %d, \$difference: %d\n", $timestamp, $difference);
		$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");

		for($j = 0; $difference >= $lengths[$j]; $j++)
		$difference /= $lengths[$j];
		$difference = round($difference);

		if($difference != 1) $periods[$j].= "s";
		$text = "$difference $periods[$j] ago";
		if ($j > 10) { exit; }
		return $text;
		} else {
			return "--";
		}
	}
	
	function rb_agency_get_age($p_strDate) {
		list($Y,$m,$d) = explode("-",$p_strDate);
		return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
	}
	
	function rb_agency_collapseWhiteSpace($string) {
		return preg_replace('/\s+/', ' ', $string);
	}
	
	function rb_agency_safenames($filename) {
		$filename = rb_agency_collapseWhiteSpace(trim($filename));
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^a-z0-9-.]/i','',$filename);
		$filename = str_replace('--', '-', $filename);
		return strtolower($filename);
	}
	function rb_agency_get_current_userid(){
		global $current_user;
        get_currentuserinfo();
		return $current_user->ID;
	}
	function rb_agency_filenameextension($filename) {
		$pos = strrpos($filename, '.');
		if($pos===false) {
			return false;
		} else {
			return substr($filename, $pos+1);
		}
	}
	
	// Format a string in proper case.
	function rb_agency_strtoproper($someString) {
		return ucwords(strtolower($someString));
	}
	
	// Get Video Thumbnail
	function rb_agency_get_videothumbnail($videoID) {
		$videoID = ltrim($videoID);
		if (substr($videoID, 0, 23) == "http://www.youtube.com/") {
			$videoID = rb_agency_get_VideoID($videoID);
		} elseif (substr($videoID, 0, 7) == "<object") {
			$videoID = rb_agency_get_VideoFromObject($videoID);
		}
		$rb_agency_get_videothumbnail = "<img src='http://img.youtube.com/vi/" . $videoID . "/default.jpg' />";
		return $rb_agency_get_videothumbnail;
	}
	
	// Get Video Thumbnail
	function rb_agency_get_VideoID($videoURL) {
		if (substr($videoURL, 0, 23) == "http://www.youtube.com/") {
			$videoURL = str_replace("http://www.youtube.com/v/", "", $videoURL);
			$videoURL = str_replace("http://www.youtube.com/watch?v=", "", $videoURL);
			$videoURL = str_replace("&feature=search", "", $videoURL);
			$videoURL = str_replace("?fs=1&amp;hl=en_US", "", $videoURL);
			$videoID = $videoURL; // substr($videoURL, 25, 15);
		} else {
			$videoID = $videoURL;
		}
		return $videoID;
	}
	
	function rb_agency_get_VideoFromObject($videoObject) {
		if (substr(strtolower($videoObject), 0, 7) == "<object") {
			$videoObject = strip_tags($videoObject, '<embed>');
			//$videoObject = str_replace('<embed src="', '', $videoObject);
			$videoObject = substr($videoObject, 13);
			$videoObject = str_replace("http://www.youtube.com/v/", "", $videoObject);
			$videoObject_newend = strpos($videoObject, '?');
			$videoObject = substr($videoObject, 0, $videoObject_newend);
		} else {
			$videoObject = rb_agency_get_VideoID($videoObject);
		}
		return $videoObject;
	}
	
	// Make Directory for new profile
    function rb_agency_checkdir($ProfileGallery){
	      	
		if (!is_dir(rb_agency_UPLOADPATH . $ProfileGallery)) {
			mkdir(rb_agency_UPLOADPATH . $ProfileGallery, 0755);
			chmod(rb_agency_UPLOADPATH . $ProfileGallery, 0777);
		} else {
			$finished = false;      
			$pos = 0;                 // we're not finished yet (we just started)
			while ( ! $finished ):                   // while not finished
			 	$pos++;
			  	$NewProfileGallery = $ProfileGallery ."-".$pos;   // output folder name
			  	if ( ! is_dir(rb_agency_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
					mkdir(rb_agency_UPLOADPATH . $NewProfileGallery, 0755);
					chmod(rb_agency_UPLOADPATH . $NewProfileGallery, 0777);
					$ProfileGallery = $NewProfileGallery;  // Set it to the new  thing
					$finished = true;                    // ...we are finished
			  	endif;
			endwhile;
		}
		return $ProfileGallery;			
    }
	
	// just check the same directory
	// will not create new
    function rb_agency_createdir($ProfileGallery){
		if (!is_dir(rb_agency_UPLOADPATH . $ProfileGallery)) {
			mkdir(rb_agency_UPLOADPATH . $ProfileGallery, 0755);
			chmod(rb_agency_UPLOADPATH . $ProfileGallery, 0777);
			// defensive return
			return $ProfileGallery;		
		} else {
			//defensive return
			return $ProfileGallery;		
		}
    }	

// *************************************************************************************************** //
// Shortcodes

// Search Form
function rb_agency_searchform($DataTypeID) {
	$profilesearch_layout = "simple";
	include("theme/include-profile-search.php"); 	
}

// Category List
function rb_agency_categorylist($atts, $content = NULL) {
	/*
	if (function_exists('rb_agency_categorylist')) { 
		$atts = array('currentcategory' => 1, 'profilegender' => 'Female');
		rb_agency_categorylist($atts); }
	*/
	
	// Get Preferences
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_locationtimezone	= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
	$rb_agency_option_privacy			= (int)$rb_agency_options_arr['rb_agency_option_privacy'];

	// Set It Up	
	global $wp_rewrite;
	extract(shortcode_atts(array(
		"profilesearch_layout" => "advanced"
	), $atts));
	
	if ( ($rb_agency_option_privacy > 1 && is_user_logged_in()) || ($rb_agency_option_privacy < 2) ) {

		// Set It Up	
		global $wp_rewrite;
		extract(shortcode_atts(array(
			"currentcategory"		=> NULL,
			"profilegender"			=> NULL,
			"profiledatebirth_min"	=> NULL,
			"profiledatebirth_max"	=> NULL,
		), $atts));

		$DataTypeID				= $currentcategory;
		$ProfileGender			= $profilegender;
		$ProfileDateBirth_min	= $profiledatebirth_min;
		$ProfileDateBirth_max	= $profiledatebirth_max;
		$filter 				= "";
		
		// Gender
		if (isset($ProfileGender) && !empty($ProfileGender)){
			$filter .= " AND profile.ProfileGender='".$ProfileGender."'";			  
		} else {
			$ProfileGender = "";
		}
		
		// Age
		$date = gmdate('Y-m-d', time() + $rb_agency_option_locationtimezone *60 *60);
		if (isset($ProfileDateBirth_min) && !empty($ProfileDateBirth_min)){
			$selectedYearMin = date('Y-m-d', strtotime('-'. $ProfileDateBirth_min .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth <= '$selectedYearMin'";
		}
		if (isset($ProfileDateBirth_max) && !empty($ProfileDateBirth_max)){
			$selectedYearMax = date('Y-m-d', strtotime('-'. $ProfileDateBirth_max - 1 .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth >= '$selectedYearMax'";
		}

		// Query
		$queryList = "SELECT dt.DataTypeID, dt.DataTypeTitle, dt.DataTypeTag, (SELECT COUNT(profile.ProfileID) FROM  ". table_agency_profile ." profile WHERE profile.ProfileIsActive = 1 AND FIND_IN_SET(dt.DataTypeID, profile.ProfileType) $filter) AS CategoryCount FROM ". table_agency_data_type ." dt ORDER BY dt.DataTypeTitle ASC";
		$resultsList = mysql_query($queryList);
		$countList = mysql_num_rows($resultsList);			
	      
		while ($dataList = mysql_fetch_array($resultsList)) {
           
			echo "<div class=\"profile-category\">\n";
			if ($DataTypeID == $dataList["DataTypeID"]) {
				echo "  <div class=\"name\"><strong>". $dataList["DataTypeTitle"] ."</strong> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			} else {
				echo "  <div class=\"name\"><a href=\"/profile-category/". $dataList["DataTypeTag"] ."/\">". $dataList["DataTypeTitle"] ."</a> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			}
			echo "</div>\n";
		}
		if ($countList < 1) {
			echo __("No Profiles Found", rb_agency_TEXTDOMAIN);
		}
	} else {
		include("theme/include-login.php");	
	}
}

// Profile List
function rb_agency_profilelist($atts, $content = NULL) {

	// Get Preferences
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_privacy					 = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
	$rb_agency_option_profilelist_count			 = isset($rb_agency_options_arr['rb_agency_option_profilelist_count']) ? $rb_agency_options_arr['rb_agency_option_profilelist_count']:0;
	$rb_agency_option_profilelist_perpage		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_perpage']) ?$rb_agency_options_arr['rb_agency_option_profilelist_perpage']:0;
	$rb_agency_option_profilelist_sortby		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_sortby']) ?$rb_agency_options_arr['rb_agency_option_profilelist_sortby']:0;
	$rb_agency_option_layoutprofilelist		 	 = isset($rb_agency_options_arr['rb_agency_option_layoutprofilelist']) ? $rb_agency_options_arr['rb_agency_option_layoutprofilelist']:0;
	$rb_agency_option_profilelist_expanddetails	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']) ? $rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']:0;
	$rb_agency_option_locationtimezone 			 = isset($rb_agency_options_arr['rb_agency_option_locationtimezone']) ? (int)$rb_agency_options_arr['rb_agency_option_locationtimezone']:0;
	$rb_agency_option_profilelist_favorite		 = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite']:0;
	$rb_agency_option_profilenaming				 = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
	$rb_agency_option_profilelist_castingcart 	 = isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ?(int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart']:0;

	// Set It Up	
	global $wp_rewrite;
	extract(shortcode_atts(array(
			"profileid" => NULL,
			"profilecontactnamefirst" => NULL,
			"profilecontactnamelast" => NULL,
			"profilelocationcity" => NULL,
			"profiletype" => NULL,
			"type" => NULL,
			"profileisactive" => NULL,
			"profilegender" => NULL,
			"gender" => NULL,
			"profilestatheight_min" => NULL,
			"profilestatheight_max" => NULL,
			"profilestatweight_min" => NULL,
			"profilestatweight_max" => NULL,
			"profiledatebirth_min" => NULL,
			"age_start" => NULL,
			"profiledatebirth_max" => NULL,
			"age_stop" => NULL,
			"featured" => NULL,
			"stars" => NULL,
			"paging" => NULL,
			"pagingperpage" => NULL,
			"override_privacy" => NULL,
			"profilefavorite" => NULL,
			"profilecastingcart" => NULL,
			"getprofile_saved" => NULL,
			"profilecity" => NULL,
			"profilestate" => NULL,
			"profilezip" => NULL
	), $atts));

	// Filter It
	$sort = "profile.ProfileContactDisplay";

	//$limit = " LIMIT 0,". $rb_agency_option_profilelist_perpage;
	$dir = "asc";
	$filter = "WHERE profile.ProfileIsActive = 1 ";

	// Legacy Field Names
	if (!isset($paging) || empty($paging)) {
		$paging = 1; 
		if (get_query_var('paging')) {
			$paging = get_query_var('paging'); 
		} else { 
			preg_match('/[0-9]/', $_SERVER["REQUEST_URI"], $matches, PREG_OFFSET_CAPTURE);
			if ($matches[0][1] > 0) {
				$paging = str_replace("/", "", substr($_SERVER["REQUEST_URI"], $matches[0][1]));
			} else {
				$paging = 1; 
			}
		}
	}
		
	if (!isset($pagingperpage) || empty($pagingperpage)) { $pagingperpage = $rb_agency_option_profilelist_perpage; }
	if($pagingperpage=="0"){$pagingperpage="10";}//make it a default value
	if (isset($type) && !empty($type)){ $profiletype = $type; }
	if (isset($gender) && !empty($gender)){  $profilegender = $gender; }
	if (isset($age_start) && !empty($age_start)){ $profiledatebirth_min = $age_start; }
	if (isset($age_stop) && !empty($age_stop)){ $profiledatebirth_max = $age_stop; }
	$ProfileID 					= $profileid;
	$ProfileContactNameFirst	= $profilecontactnamefirst;
	$ProfileContactNameLast    	= $profilecontactnamelast;
	$ProfileLocationCity		= $profilelocationcity;
	$ProfileType				= $profiletype;
	$ProfileIsActive			= $profileisactive;
	$ProfileGender    			= $profilegender;
	$ProfileStatHeight_min		= $profilestatheight_min;
	$ProfileStatHeight_max		= $profilestatheight_max;
	$ProfileStatWeight_min		= $profilestatheight_min;
	$ProfileStatWeight_max		= $profilestatheight_max;
	$ProfileDateBirth_min		= $profiledatebirth_min;
	$ProfileDateBirth_max		= $profiledatebirth_max;
	$ProfileIsFeatured			= $featured;
	$ProfileIsPromoted			= $stars;
	$OverridePrivacy			= $override_privacy;
  	$GetProfileSaved			= $getprofile_saved;
	$City						= $profilecity;
	$State						= $profilestate;
	$Zip						= $profilezip;  
      
   	$filterDropdown = array();

    // Set CustomFields
  	if(isset($atts) && !empty($atts)){
		$filter2= '';

		foreach($atts as $key => $val){
			
                        if (substr($key,0,15) == "ProfileCustomID") {
                        
                              /*
                               *  Check if this is array or not
                               *  because sometimes $val is an array so
                               *  array_filter is not applicable
                               */	
                              if ((!empty($val) AND !is_array($val)) OR (is_array($val) AND count(array_filter($val)) > 0)) {
                                   
                                    /*
                                     * Id like to chop this one out and extract
                                     * the array values from here and make it a string with "," or
                                     * pass the single value back $val
                                     */
                                    if(is_array($val)){
                                      
                                            if(count(array_filter($val)) > 1) {
                                                $ct =1;
                                                foreach($val as $v){
                                                    if($ct == 1){
                                                        $val = $v;
                                                        $ct++;

                                                    } else {
                                                        $val = $val .",".$v;
                                                    }
                                                }
                                            } else {
                                                $val = array_shift(array_values($val));
                                            } 
                                    }
				    
                                    $q = mysql_query("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = '".substr($key,15)."' ");
				                    $ProfileCustomType = mysql_fetch_assoc($q);
					
				
                                        /*
                                         * Have created a holder $filter2 and
                                         * create its own filter here and change
                                         * AND should be OR
                                         */
                                        if(in_array($ProfileCustomType['ProfileCustomTitle'], $cusFields)) {
                                                        $minVal=$_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_min'];
                                                        $maxVal=$_GET['ProfileCustomID'.$ProfileCustomType['ProfileCustomID'].'_max'];
                                                        if($filter2 == ""){
                                                            $filter2 .= " AND ( customfield_mux.ProfileCustomValue BETWEEN '".$minVal."' AND '".$maxVal."' ";
                                                        } else {
                                                            $filter2 .= " OR customfield_mux.ProfileCustomValue BETWEEN '".$minVal."' AND '".$maxVal."' ";

                                                        }

                                                        //echo "-----";
                                        }else {

                                                        /******************
                                                        1 - Text
                                                        2 - Min-Max > Removed
                                                        3 - Dropdown
                                                        4 - Textbox
                                                        5 - Checkbox
                                                        6 - Radiobutton
                                                        7 - Metrics/Imperials
                                                        *********************/

                                                        if ($ProfileCustomType["ProfileCustomType"] == 1) { //TEXT
                                                                if($filter2 == ""){
                                                                    $filter2 .= " AND ( customfield_mux.ProfileCustomValue='".$val."' ";
                                                                } else {
                                                                    $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                                                }                                                           
                                                                $_SESSION[$key] = $val;

                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 3) { // Dropdown


                                                                                //$filter.= " AND LOWER(customfield_mux.ProfileCustomValue) = LOWER(\"".$val."\") ";
                                                            array_push($filterDropdown,$val);


                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 4) { //Textarea
                                                                if($filter2==""){
                                                                    $filter2 .= " AND ( customfield_mux.ProfileCustomValue='".$val."' ";
                                                                } else {
                                                                    $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                                                } 
                                                                        $_SESSION[$key] = $val;


                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 5) { //Checkbox
                                                                if(!empty($new_val)){
                                                                    if(strpos($val,",") === false){
                                                                        $val = implode("','",explode(",",$val));
                                                                        if($filter2==""){
                                                                                $filter2 .= " AND ( customfield_mux.ProfileCustomValue IN('".$val."') ";
                                                                        } else {
                                                                                $filter2 .= " OR customfield_mux.ProfileCustomValue IN('".$val."') ";
                                                                        }
                                                                    } else {
                                                                        if($filter2==""){
                                                                            $filter2 .= " AND ( customfield_mux.ProfileCustomValue='".$val."' ";
                                                                        } else {
                                                                            $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                                                        }
                                                                    }

                                                                $_SESSION[$key] = $val;
                                                                }else{
                                                                        $_SESSION[$key] = "";
                                                                }
                                                        } elseif ($ProfileCustomType["ProfileCustomType"] == 6) { //Radiobutton 
                                                                //var_dump($ProfileCustomType["ProfileCustomType"]);
                                                                   $val = implode("','",explode(",",$val));
                                                                    if($filter2==""){
                                                                        $filter2 .= " AND ( customfield_mux.ProfileCustomValue='".$val."' ";
                                                                    } else {
                                                                        $filter2 .= " OR customfield_mux.ProfileCustomValue='".$val."' ";
                                                                    } 
                                                                    $_SESSION[$key] = $val;
                                                               

                                                        }
                                                        elseif ($ProfileCustomType["ProfileCustomType"] == 7) { //Measurements 

                                                                    list($Min_val,$Max_val) = explode(",",$val);
                                                                        if(!empty($Min_val) && !empty($Max_val)){
                                                                            if($filter2==""){
                                                                                    $filter .= " AND ( customfield_mux.ProfileCustomValue BETWEEN '".$Min_val."' AND '".$Max_val."' ";
                                                                            } else {
                                                                                    $filter .= " OR customfield_mux.ProfileCustomValue BETWEEN '".$Min_val."' AND '".$Max_val."' ";

                                                                            }
                                                                        $_SESSION[$key] = $val;
                                                                        }

                                                        }
                                        }
					
						
						
						mysql_free_result($q);
				} // if not empty
			 }  // end if
	       } // end for each
	  
           if(count($filterDropdown) > 0){
               if($filter2==""){
               $filter2 .=" AND ( customfield_mux.ProfileCustomValue IN('".implode("','",$filterDropdown)."')";
               } else {
               $filter2 .=" OR customfield_mux.ProfileCustomValue IN('".implode("','",$filterDropdown)."')";
               }
               
           }
           
           /*
            * Refine filter and add the created 
            * holder $filter to $filter if not
            * equals to blanks
            */
           if($filter2!=""){
            $filter2.= " ) ";
            $filter .= $filter2;
           }
	}

  	// Name
	if (isset($ProfileContactNameFirst) && !empty($ProfileContactNameFirst)){
		$ProfileContactNameFirst = $ProfileContactNameFirst;
		$filter .= " AND profile.ProfileContactNameFirst LIKE '%". ucfirst($ProfileContactNameFirst) ."'";
	}
	if (isset($ProfileContactNameLast) && !empty($ProfileContactNameLast)){
		$ProfileContactNameLast = $ProfileContactNameLast;
		$filter .= " AND profile.ProfileContactNameLast LIKE '%". ucfirst($ProfileContactNameLast) ."'";
	}
	
	// Type
	if (isset($ProfileType) && !empty($ProfileType)){
		$ProfileType = $ProfileType;
		$filter .= " AND FIND_IN_SET(". $ProfileType .", profile.ProfileType) ";
	} else {
		$ProfileType = "";
	}		
	
	// Profile Search Saved 
	if(isset($GetProfileSaved) && !empty($GetProfileSaved)){
		$filter .= " AND profile.ProfileID IN(".$GetProfileSaved.") ";
	}

	// Gender			
	if (isset($ProfileGender) && !empty($ProfileGender)){
		$filter .= " AND profile.ProfileGender='".$ProfileGender."'";
	} else {
		$ProfileGender = "";
	}

	// Age
	$date = gmdate('Y-m-d', time() + $rb_agency_option_locationtimezone *60 *60);
	if (isset($ProfileDateBirth_min) && !empty($ProfileDateBirth_min)){
		$selectedYearMin = date('Y-m-d', strtotime('-'. $ProfileDateBirth_min .' year'. $date));
		$filter .= " AND profile.ProfileDateBirth <= '$selectedYearMin'";
	}
	if (isset($ProfileDateBirth_max) && !empty($ProfileDateBirth_max)){
		$selectedYearMax = date('Y-m-d', strtotime('-'. $ProfileDateBirth_max - 1 .' year'. $date));
		$filter .= " AND profile.ProfileDateBirth >= '$selectedYearMax'";
	}		
	if (isset($ProfileIsFeatured)){
		$filter .= " AND profile.ProfileIsFeatured = '1' ";
	}		
    if (isset($ProfileIsPromoted)){
		$filter .= " AND profile.ProfileIsPromoted = '1' ";
	}
	
	// City
	if (isset($City) && !empty($City)){
		$City = $City;
		$filter .= " AND profile.ProfileLocationCity = '". ucfirst($City) ."'";
	}

	// State
	if (isset($State) && !empty($State)){
		$State = $State;
		$filter .= " AND profile.ProfileLocationState = '". ucfirst($State) ."'";
	}

	// Zip
	if (isset($Zip) && !empty($Zip)){
		$Zip = $Zip;
		$filter .= " AND profile.ProfileLocationZip = '". ucfirst($Zip) ."'";
	}

	// Can we show the profiles?
	if ( (isset($OverridePrivacy)) || ($rb_agency_option_privacy > 1 && is_user_logged_in()) || ($rb_agency_option_privacy < 2) ) {
		
		if(get_query_var('target')!="print" AND get_query_var('target')!="pdf"){
			
			if (isset($profilecastingcart)){   //to tell prrint and pdf generators its for casting cart and new link
				$atts["type"]="casting";
				$addtionalLink='&nbsp;|&nbsp;<a id="sendemail" href="javascript:">Email to Admin</a>';
			}
			
			# print, downloads links to be added on top of profile list
			$links='<div class="rblinks">';
			  
				if(get_query_var('target')!="results"){// hide print and download PDF in Search result
				  	$links.='
					<div class="rbprint-download">
				  		<a target="_blank" href="/profile-category/print/?gd='.$atts["gender"].'&ast='.$atts["age_start"].'&asp='.$atts["age_stop"].'&t='.$atts["type"].'">Print</a></a>&nbsp;|&nbsp;<a target="_blank" href="/profile-category/pdf/?gd='.$atts["gender"].'&ast='.$atts["age_start"].'&asp='.$atts["age_stop"].'&t='.$atts["type"].'">Download PDF</a>'.$addtionalLink.'
				  	</div><!-- .rbprint-download -->';
				}
				  
				$links.='<div class="rbfavorites-castings">';

				if($rb_agency_options_arr['rb_agency_option_profilelist_favorite']==1){
				  	$links.='<a href="'.get_bloginfo('siteurl').'/profile-favorite/">'.__("View Favorites", rb_agency_TEXTDOMAIN).'</a>';
				}
				  
				if($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']==1){
				    if($rb_agency_options_arr['rb_agency_option_profilelist_favorite']==1){$links.='&nbsp;|&nbsp;';}
				    $links.='<a href="'.get_bloginfo('siteurl').'/profile-casting/">'.__("Casting Cart", rb_agency_TEXTDOMAIN).'</a>';
				}
				$links.='</div><!-- .rbfavorites-castings -->
			</div><!-- .rblinks -->';			
		}
	
	  	//remove  if its just for client view of listing via casting email
	 	if(get_query_var('type')=="profilesecure"){ $links="";}

		if(get_query_var('type')=="favorite"){ $links="";} // we dont need print and download pdf in favorites page
		
		echo "<div class=\"rbclear\"></div>\n";
		echo "$links<div id=\"profile-results\">\n";
		
	 	if(get_query_var('target')!="print" AND get_query_var('target')!="pdf"){ //if its printing or PDF no need for pagination belo
	  
			/*********** Paginate **************/
			$items = mysql_num_rows(($qItem = mysql_query("SELECT
				profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID , 
				 customfield_mux.*,  
				 (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media  WHERE  profile.ProfileID = media.ProfileID  AND media.ProfileMediaType = \"Image\"  AND media.ProfileMediaPrimary = 1) 
				 AS ProfileMediaURL 
				 FROM ". table_agency_profile ." profile 
			 	 LEFT JOIN ". table_agency_customfield_mux ."  AS customfield_mux 
				 ON profile.ProfileID = customfield_mux.ProfileID  
			 $filter  GROUP BY profile.ProfileID ORDER BY $sort $dir  ".(isset($limit) ? $limit : "").""))); // number of total rows in the database
                      
			if($items > 0) {
				$p = new rb_agency_pagination;
				$p->items($items);
				$p->limit($pagingperpage); // Limit entries per page
				$p->target($_SERVER["REQUEST_URI"]);
				$p->currentPage($paging); // Gets and validates the current page
				$p->calculate(); // Calculates what to show
				$p->parameterName('paging');
				$p->adjacents(1); //No. of page away from the current page
				
				if(!isset($paging)) {
					$p->page = 1;
				} else {
					$p->page = $paging;
				}
				//Query for limit paging
				$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
			} else {
				$limit = "";
			}

            if(get_query_var('target')=="print"){$limit = "";} //to remove limit on print page
			if(get_query_var('target')=="pdf"){$limit = "";} //to remove limit on pdf page
            mysql_free_result($qItem);
  
  		}//if(get_query_var('target')!="print" 
				  
       	if(is_user_logged_in()){
	        $sqlFavorite_userID  = " fav.SavedFavoriteTalentID = profile.ProfileID  AND fav.SavedFavoriteProfileID = '".rb_agency_get_current_userid()."' ";
		    $sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID   AND cart.CastingCartProfileID = '".rb_agency_get_current_userid()."'  ";
	 	} else {
			  $sqlFavorite_userID  = "  fav.SavedFavoriteProfileID = profile.ProfileID ";
			  $sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID   AND cart.CastingCartProfileID = 'none'  "; //this makes cart empty for non logged user
	 	}

		/*********** Execute Query **************/
		if (isset($profilefavorite) && !empty($profilefavorite)){
			// Execute Query
			$queryList = "SELECT profile.ProfileID, profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID , fav.SavedFavoriteTalentID, fav.SavedFavoriteProfileID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_savedfavorite."  fav  WHERE   $sqlFavorite_userID AND profile.ProfileIsActive = 1 GROUP BY fav.SavedFavoriteTalentID";
			
		} elseif (isset($profilecastingcart) && !empty($profilecastingcart)){

		// Execute Query
			
		$user = get_userdata(rb_agency_get_current_userid());  // check if user is admin, if yes this allow the admin to view other users cart 
		if($user->user_level==10 AND get_query_var('target')!="casting") {
			$sqlCasting_userID = " cart.CastingCartTalentID = profile.ProfileID   AND cart.CastingCartProfileID = '".get_query_var('target')."'  ";
		}
		$queryList = "SELECT profile.*, profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID , cart.CastingCartTalentID, cart.CastingCartTalentID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_castingcart."  cart WHERE  $sqlCasting_userID AND ProfileIsActive = 1 GROUP BY profile.ProfileID";  
		
		} elseif ($_GET['t']=="casting"){
					   
			$queryList = "SELECT profile.ProfileID, profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID , cart.CastingCartTalentID, cart.CastingCartTalentID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_castingcart."  cart WHERE  $sqlCasting_userID AND ProfileIsActive = 1 GROUP BY profile.ProfileID";  
			 
		} else {
			// Execute Query
			$queryList = "
			SELECT 
				profile.ProfileID,
				profile.*,
				profile.ProfileID as pID, 
				profile.ProfileGallery,
				profile.ProfileContactDisplay, 
				profile.ProfileDateBirth, 
				profile.ProfileLocationState, 
				customfield_mux.ProfileCustomMuxID, customfield_mux.ProfileCustomMuxID, customfield_mux.ProfileCustomID, customfield_mux.ProfileCustomValue,  
				(   SELECT media.ProfileMediaURL 
					FROM ". table_agency_profile_media ." media 
					WHERE profile.ProfileID = media.ProfileID 
						AND media.ProfileMediaType = \"Image\" 
						AND media.ProfileMediaPrimary = 1
				) 
				AS ProfileMediaURL 
			FROM ". table_agency_profile ." profile 
			LEFT JOIN ". table_agency_customfield_mux ." 
				AS customfield_mux 
				ON profile.ProfileID = customfield_mux.ProfileID  
				$filter  
			GROUP BY profile.ProfileID 
			ORDER BY $sort $dir $limit";
		}
		
		$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
                   
		$resultsList = mysql_query($queryList);
		$countList = mysql_num_rows($resultsList);
                
		$rb_user_isLogged = is_user_logged_in();

		#DEBUG!
		// echo $queryList;

		if($countList > 0){
	  	$displayHTML ="";

        $profileDisplay = 0;
		$countFav = 0;
		while ($dataList = mysql_fetch_assoc($resultsList)) {
				
			$profileDisplay++;
			if ($profileDisplay == 1 ){
				 
				/*********** Show Count/Pages **************/
				 $displayHTML .= "  <div id=\"profile-results-info\" class=\"six column\">\n";
					if($items > 0) {
						if ((!isset($profilefavorite) && empty($profilefavorite)) && (!isset($profilecastingcart) && empty($profilecastingcart))){ 
							$displayHTML .="    <div class=\"profile-results-info-countpage\">\n";
								echo $p->show();  // Echo out the list of paging. 
							$displayHTML .= "    </div>\n";
						}
					}
				if ($rb_agency_option_profilelist_count) {
					if ((!isset($profilefavorite) && empty($profilefavorite)) && (!isset($profilecastingcart) && empty($profilecastingcart))){  
						$displayHTML .= "    <div id=\"profile-results-info-countrecord\">\n";
						$displayHTML .="    	". __("Displaying", rb_agency_TEXTDOMAIN) ." <strong>". $countList ."</strong> ". __("of", rb_agency_TEXTDOMAIN) ." ". $items ." ". __(" records", rb_agency_TEXTDOMAIN) ."\n";
						$displayHTML .="    </div>\n";
					}				
				}

				$displayHTML.="  </div><!-- #profile-results-info -->\n";
				$displayHTML.="  <div class=\"rbclear\"></div><div id=\"profile-list\" class=\"twelve column\">\n";
			}	           
						
			$displayHTML .= "<div id=\"rbprofile-".$dataList["ProfileID"]."\" class=\"rbprofile-list profile-list-layout0\" >\n";

			if (isset($dataList["ProfileMediaURL"]) ) { // && (file_exists(rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"])) ) {
				
				//dont need other image for hover if its for print or pdf download view and dont use timthubm
				if(get_query_var('target')!="print" AND get_query_var('target')!="pdf"){
							 
					if($rb_agency_options_arr['rb_agency_option_profilelist_thumbsslide']==1){  //show profile sub thumbs for thumb slide on hover
						$images=getAllImages($dataList["ProfileID"]);
					    $images=str_replace("{PHOTO_PATH}",rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"]."/",$images);
					}

					$displayHTML .="<div class=\"image\">"."<a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"".rb_agency_BASEDIR."timthumb.php?src=".rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"]."&w=200&q=60\" id=\"roll".$dataList["ProfileID"]."\"  /></a>".$images."</div>\n";

				} else {
					$displayHTML .="<div  class=\"image\">"."<a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"".rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"]."\"  /></a>".$images."</div>\n";
				}
			
			} else {
			 	$displayHTML .= "  <div class=\"image image-broken\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">No Image</a></div>\n";
			}
				
			$displayHTML .= "  <div class=\"profile-info\">\n";
			$ProfileContactDisplay = "";
			if ($rb_agency_option_profilenaming == 0) {
				$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". $dataList["ProfileContactNameLast"];
			} elseif ($rb_agency_option_profilenaming == 1) {
				$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". substr($dataList["ProfileContactNameLast"], 0, 1);
			} elseif ($rb_agency_option_profilenaming == 2) {
				$ProfileContactDisplay = $dataList["ProfileContactDisplay"];
			} elseif ($rb_agency_option_profilenaming == 3) {
				$ProfileContactDisplay = "ID ". $ProfileID;
			}  elseif ($rb_agency_option_profilenaming == 4) {
				$ProfileContactDisplay = $dataList["ProfileContactNameFirst"];
			} elseif ($rb_agency_option_profilenaming == 5) {
				$ProfileContactDisplay = $dataList["ProfileContactNameLast"];
			}

			$displayHTML .= "     <h3 class=\"name\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">". stripslashes($ProfileContactDisplay) ."</a></h3>\n";

			if ($rb_agency_option_profilelist_expanddetails) {
			 	$displayHTML .= "     <div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span></div>\n";
			}
			
         	//echo "loaded: ".microtime()." ms";
				
			if($rb_user_isLogged ){

				$displayHTML .=" <div class=\"casting\">";
			   	//Get Favorite & Casting Cart links
		        $displayHTML .= rb_agency_get_miscellaneousLinks($dataList["ProfileID"]);
		        $displayHTML .=" </div> <!-- casting --> \n";
			}

			$displayHTML .=" </div> <!-- .profile-list --> \n";
			$displayHTML .= "</div>\n";
		}	// endwhile datalist
	}	// endif countlist

	if ($countList < 1) {
		$displayHTML .= __("No Profiles Found", rb_agency_TEXTDOMAIN);
	}
			
	$displayHTML .= "  <div class=\"rbclear\"></div>\n";
	$displayHTML .= "  </div><!-- #profile-list -->\n";
	$displayHTML .= "  <div class=\"rbclear\"></div>\n";
	$displayHTML .= "</div><!-- #profile-results -->\n";
			
	}
	else {
		include("theme/include-login.php"); 	
	}
			
  	echo  $displayHTML;

	// debug mode
	//  rb_agency_checkExecution();
    //add the thumbs slides on hover of profile listing
	echo "<script type=\"text/javascript\" src=\"". rb_agency_BASEDIR ."js/thumbslide.js\"></script>\n"; 
		   
	//load javascript for add to casting cart	
	if(get_query_var('target')!="print" AND get_query_var('target')!="pdf" AND get_query_var('type')!="profilesecure" AND !isset($profilecastingcart)){
		echo'	<script>
		            function addtoCart(pid){
						var qString = \'usage=addtocart&pid=\' +pid;
						var apid = "addtocart"+pid;
						$.post(\''.rb_agency_BASEDIR.'theme/sub_db_handler.php\', qString, processResponseAddtoCart);
						//document.getElementById(pid).style.display="none";
						// document.getElementById(apid).style.backgroundPosition="0 -134px;";
						}
					function processResponseAddtoCart(data) {			
					}
		    	</script>';
	} //end if(get_query_var
}
	
//get profile images
function getAllImages($profileID){
	$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $profileID ."\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC LIMIT 0, 7 ";
	$resultsImg = mysql_query($queryImg);
	$countImg = mysql_num_rows($resultsImg);
	while ($dataImg = mysql_fetch_array($resultsImg)) {//style=\"display:none\" 
	 	$images.="<img  class=\"roll\" src=\"".rb_agency_BASEDIR."/timthumb.php?src={PHOTO_PATH}". $dataImg['ProfileMediaURL'] ."&w=200&q=30\" alt='' style='width:148px'   />\n";
	}
return  $images;
}
	
	
// Profile List
function rb_agency_profilefeatured($atts, $content = NULL) {

	/*
	if (function_exists('rb_agency_profilefeatured')) { 
		$atts = array('count' => 8, 'type' => 0);
		rb_agency_profilefeatured($atts); }
	*/

	// Set It Up	
	global $wp_rewrite;
	extract(shortcode_atts(array(
			"type" => 0,
			"count" => 1
	), $atts));
	if ($type == 1) { // Featured
		$sqlWhere = " AND profile.ProfileIsPromoted=1";
	}
	echo "<div id=\"profile-featured\">\n";
	/*********** Execute Query **************/
	// Execute Query
		$queryList = "SELECT profile.*,

		(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
		 WHERE profile.ProfileID = media.ProfileID 
		 AND media.ProfileMediaType = \"Image\" 
		 AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 
		
		 FROM ". table_agency_profile ." profile 
 		 WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
		 AND profile.ProfileIsFeatured = 1  
		 ORDER BY RAND() LIMIT 0,$count";

	$resultsList = mysql_query($queryList);
	$countList = mysql_num_rows($resultsList);
	while ($dataList = mysql_fetch_array($resultsList)) {
	    echo "<div class=\"rbprofile-list\">\n";
		echo "  <div class=\"style\"></div>\n";
		if (isset($dataList["ProfileMediaURL"]) ) { 
		echo "  <div class=\"image\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"". rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"] ."\" /></a></div>\n";
		} else {
		echo "  <div class=\"image image-broken\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">No Image</a></div>\n";
		}
		echo "<div class=\"profile-info\">";
		echo "  <div class=\"title\">\n";
                    $rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
						if ($rb_agency_option_profilenaming == 0) {
							$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". $dataList["ProfileContactNameLast"];
						} elseif ($rb_agency_option_profilenaming == 1) {
							$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". substr($dataList["ProfileContactNameLast"], 0, 1);
						} elseif ($rb_agency_option_profilenaming == 2) {
							$ProfileContactDisplay = $dataList["ProfileContactNameFirst"];
						} elseif ($rb_agency_option_profilenaming == 3) {
							$ProfileContactDisplay = "ID ". $ProfileID;
						} elseif ($rb_agency_option_profilenaming == 4) {
							$ProfileContactDisplay = $ProfileContactNameFirst;
						} elseif ($rb_agency_option_profilenaming == 5) {
							$ProfileContactDisplay = $ProfileContactNameLast;
						}
			 
		echo "     <h3 class=\"name\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">". $ProfileContactDisplay ."</a></h3>\n";
		if (isset($rb_agency_option_profilelist_expanddetails)) {
			echo "<div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span></div>\n";
		}

		// Add Favorite and Casting Cart links		
	  	rb_agency_get_miscellaneousLinks($dataList["ProfileID"]);
		
		echo "  </div>\n";
		echo "  </div><!-- .profile-info -->\n";
		echo "</div><!-- .rbprofile-list -->\n";
	}
	if ($countList < 1) {
		echo __("No Profiles Found", rb_agency_TEXTDOMAIN);
	}
	echo "  <div style=\"clear: both; \"></div>\n";
	echo "</div><!-- #profile-featured -->\n";
}
	
// Profile Search
function rb_agency_profilesearch($atts, $content = NULL){
	/*
	if (function_exists('rb_agency_profilesearch')) { 
		$atts = array('profilesearch_layout' => 'advanced');
		rb_agency_profilesearch($atts); }
	*/
	// Get Privacy Information
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_privacy					 = $rb_agency_options_arr['rb_agency_option_privacy'];
	// Set It Up	
	global $wp_rewrite;
	extract(shortcode_atts(array(
			"profilesearch_layout" => "advanced"
	), $atts));
	
	if ( ($rb_agency_option_privacy > 1 && is_user_logged_in()) || ($rb_agency_option_privacy < 2) ) {
	 	$isSearchPage = 1;
		//echo "<div id=\"profile-search-form-embed\">\n";
			include("theme/include-profile-search.php"); 	
		//echo "</div>\n";
	} else {
		include("theme/include-login.php"); 	
	}
}

// *************************************************************************************************** //
// Image Resizing 
class rb_agency_image {
 
	var $image;
	var $image_type;

	function load($filename) {

		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {

			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {

			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {

			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=NULL) {

	if( $image_type == IMAGETYPE_JPEG ) {
		imagejpeg($this->image,$filename,$compression);
		} elseif( $image_type == IMAGETYPE_GIF ) {

			imagegif($this->image,$filename);
		} elseif( $image_type == IMAGETYPE_PNG ) {

			imagepng($this->image,$filename);
		}
		if( $permissions != NULL) {

			chmod($filename,$permissions);
		}
	}

	function output($image_type=IMAGETYPE_JPEG) {

		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image);
		} elseif( $image_type == IMAGETYPE_GIF ) {

			imagegif($this->image);
		} elseif( $image_type == IMAGETYPE_PNG ) {

			imagepng($this->image);
		}
	}

	function getWidth() {

		return imagesx($this->image);
	}

	function getHeight() {

		return imagesy($this->image);
	}

	function resizeToHeight($height) {

		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}

	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width,$height);
	}

	function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getHeight() * $scale/100;
		$this->resize($width,$height);
	}

	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}      

	function orientation() {
		if ($this->getWidth() == $this->getHeight()) {
			return "square";
		} elseif ($this->getWidth() > $this->getHeight()) {
			return "landscape";
		} else {
			return "portrait";
		}
	}
 
 
}

// *************************************************************************************************** //
// Pagination
class rb_agency_pagination {

	/*Default values*/
	var $total_pages = -1;//items
	var $limit = NULL;
	var $target = ""; 
	var $page = 1;
	var $adjacents = 2;
	var $showCounter = false;
	var $className = "rbpagination";
	var $parameterName = "page";
	var $urlF = false;//urlFriendly
	/*Buttons next and previous*/
	var $nextT = "Next";
	var $nextI = "&#187;"; //&#9658;
	var $prevT = "Previous";
	var $prevI = "&#171;"; //&#9668;
	/*****/
	var $calculate = false;
	
	#Total items
	function items($value){$this->total_pages = (int) $value;}
	
	#how many items to show per page
	function limit($value){$this->limit = (int) $value;}
	
	#Page to sent the page value
	function target($value){$this->target = $value;}
	
	#Current page
	function currentPage($value){$this->page = (int) $value;}
	
	#How many adjacent pages should be shown on each side of the current page?
	function adjacents($value){$this->adjacents = (int) $value;}
	
	#show counter?
	function showCounter($value=""){$this->showCounter=($value===true)?true:false;}
	#to change the class name of the pagination div
	function changeClass($value=""){$this->className=$value;}
	function nextLabel($value){$this->nextT = $value;}
	function nextIcon($value){$this->nextI = $value;}
	function prevLabel($value){$this->prevT = $value;}
	function prevIcon($value){$this->prevI = $value;}
	#to change the class name of the pagination div
	function parameterName($value=""){$this->parameterName=$value;}
	#to change urlFriendly
	function urlFriendly($value="%"){
		if(eregi('^ *$',$value)){
				$this->urlF=false;
				return false;
			}
		$this->urlF=$value;
	}
	
	var $pagination;
	function pagination(){}
	function show(){
		if(!$this->calculate)
			if($this->calculate())
				echo "<div class=\"$this->className\">$this->pagination</div>\n";
	}

	function getOutput(){
		if(!$this->calculate)
			if($this->calculate())
				return "<div class=\"$this->className\">$this->pagination</div>\n";
	}

	function get_pagenum_link($id) {
		if (substr($this->target, 0, 9) == "admin.php") {
			// We are in Admin
		
			if (strpos($this->target,'?') === false) {
				if ($this->urlF) {
					return str_replace($this->urlF,$id,$this->target);
				} else {
					return "$this->target?$this->parameterName=$id";
				}
			} else {
					return "$this->target&$this->parameterName=$id";
			}
		
		} else {
			// We are in Page
		
			preg_match('/[0-9]/', $this->target, $matches, PREG_OFFSET_CAPTURE);
			if ($matches[0][1] > 0) {
				return substr($this->target, 0, $matches[0][1]) ."/$id/";
			} else {
				return "$this->target/$id/";
			}
			
		} // End Admin/Page Toggle
	}
	
	function calculate(){
		$this->pagination = "";
		$this->calculate == true;
		$error = false;
		if($this->urlF and $this->urlF != '%' and strpos($this->target,$this->urlF)===false){
				//Es necesario especificar el comodin para sustituir
				echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
				$error = true;
			}elseif($this->urlF and $this->urlF == '%' and strpos($this->target,$this->urlF)===false){
				echo "Es necesario especificar en el target el comodin % para sustituir el número de página<br />";
				$error = true;
			}
		if($this->total_pages < 0){
				echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
				$error = true;
			}
		if($this->limit == NULL){
				echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
				$error = true;
			}
		if($error)return false;
		
		$n = trim('<span>'. $this->nextT.'</span> '.$this->nextI);
		$p = trim($this->prevI.' <span>'.$this->prevT .'</span>');
		
		/* Setup vars for query. */
		if($this->page) 
			$start = ($this->page - 1) * $this->limit;      //first item to display on this page
		else
			$start = 0;                               		//if no page var is given, set start to 0
	
		/* Setup page vars for display. */
		$prev = $this->page - 1;                            //previous page is page - 1
		$next = $this->page + 1;                            //next page is page + 1
		$lastpage = ceil($this->total_pages/$this->limit);  //lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;                        		//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		
		if($lastpage > 1){
			if($this->page){
				//anterior button
				if($this->page > 1)
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($prev)."\" class=\"pagedir prev\">$p</a>";
					else
						$this->pagination .= "<span class=\"pagedir disabled\">$p</span>";
			}
			//pages	
			if ($lastpage < 7 + ($this->adjacents * 2)){//not enough pages to bother breaking it up
				for ($counter = 1; $counter <= $lastpage; $counter++){
						if ($counter == $this->page)
								$this->pagination .= "<span class=\"pageno current\">$counter</span>";
							else
								$this->pagination .= "<a class=\"pageno\" href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
					}
			}
			elseif($lastpage > 5 + ($this->adjacents * 2)){//enough pages to hide some
				//close to beginning; only hide later pages
				if($this->page < 1 + ($this->adjacents * 2)){
						for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++){
								if ($counter == $this->page)
										$this->pagination .= "<span class=\"current\">$counter</span>";
									else
										$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
							}
						$this->pagination .= "...";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lpm1)."\">$lpm1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lastpage)."\">$lastpage</a>";
					}
				//in middle; hide some front and some back
				elseif($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)){
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(1)."\">1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(2)."\">2</a>";
						$this->pagination .= "...";
						for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
							if ($counter == $this->page)
									$this->pagination .= "<span class=\"current\">$counter</span>";
								else
									$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
						$this->pagination .= "...";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lpm1)."\">$lpm1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($lastpage)."\">$lastpage</a>";
					}
				//close to end; only hide early pages
				else {
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(1)."\">1</a>";
						$this->pagination .= "<a href=\"".$this->get_pagenum_link(2)."\">2</a>";
						$this->pagination .= "...";
						for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
							if ($counter == $this->page)
									$this->pagination .= "<span class=\"current\">$counter</span>";
								else
									$this->pagination .= "<a href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
					}
			}
			if($this->page){
				//siguiente button
				if ($this->page < $counter - 1)
						$this->pagination .= "<a href=\"".$this->get_pagenum_link($next)."\" class=\"pagedir next\">$n</a>";
					else
						$this->pagination .= "<span class=\"pagedir disabled\">$n</span>";
					if($this->showCounter)$this->pagination .= "<div class=\"pagedir pagination_data\">($this->total_pages Pages)</div>";
			}
		}
		return true;
	}
}

// *************************************************************************************************** //
// Custom Fields
function rb_custom_fields($visibility = 0, $ProfileID, $ProfileGender, $ProfileGenderShow = false, $SearchMode = false){
				
	$query3 = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = ".$visibility."  ORDER BY ProfileCustomOrder";
	$results3 = mysql_query($query3) or die(mysql_error());
	$count3 = mysql_num_rows($results3);
	
	while ($data3 = mysql_fetch_assoc($results3)) {
		 if($ProfileGenderShow ==true){
			if($data3["ProfileCustomShowGender"] == $ProfileGender && $count3 >=1 ){ // Depends on Current LoggedIn User's Gender
				rb_custom_fields_template($visibility, $ProfileID, $data3);
			} elseif(empty($data3["ProfileCustomShowGender"])) {
				rb_custom_fields_template($visibility, $ProfileID, $data3);
			}
		 } else {
					 rb_custom_fields_template($visibility, $ProfileID, $data3);
		 }
			// END Query2
		echo "    </td>\n";
		echo "  </tr>\n";
	} // End while
	if ($count3 < 1) {
		echo "  <tr valign=\"top\">\n";
		echo "    <th scope=\"row\">". __("There are no custom fields loaded", rb_agency_TEXTDOMAIN) .".  <a href=". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=7") ."'>". __("Setup Custom Fields", rb_agency_TEXTDOMAIN) ."</a>.</th>\n";
		echo "  </tr>\n";
	}
}

// *************************************************************************************************** //
// Custom Fields TEMPLATE 
function rb_custom_fields_template($visibility = 0, $ProfileID, $data3){

	$rb_agency_options_arr 				= get_option('rb_agency_options');
	$rb_agency_option_unittype  		= $rb_agency_options_arr['rb_agency_option_unittype'];
	$rb_agency_option_profilenaming 	= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
	$rb_agency_option_locationtimezone 	= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
	
	if( (!empty($data3['ProfileCustomID']) || $data3['ProfileCustomID'] !="") ){ 
   
		$subresult = mysql_query("SELECT ProfileID,ProfileCustomValue,ProfileCustomID FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = '". $data3['ProfileCustomID'] ."' AND ProfileID = ". $ProfileID);
		$row = @mysql_fetch_assoc($subresult);
		
		$ProfileCustomValue = $row["ProfileCustomValue"];
		$ProfileCustomTitle = $data3['ProfileCustomTitle'];
		$ProfileCustomType  = $data3['ProfileCustomType'];
	
			// SET Label for Measurements
			// Imperial(in/lb), Metrics(ft/kg)
			$rb_agency_options_arr = get_option('rb_agency_options');
			 $rb_agency_option_unittype  = $rb_agency_options_arr['rb_agency_option_unittype'];
			 $measurements_label = "";
			if ($ProfileCustomType == 7) { //measurements field type
				if ($rb_agency_option_unittype ==0) { // 0 = Metrics(ft/kg)
					if($data3['ProfileCustomOptions'] == 1){
						$measurements_label  ="<em>(cm)</em>";
					} elseif($data3['ProfileCustomOptions'] == 2) {
						$measurements_label  ="<em>(kg)</em>";
					} elseif($data3['ProfileCustomOptions'] == 3) {
					  	$measurements_label  ="<em>(In Feet/Inches)</em>";
					}
				} elseif($rb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
					if($data3['ProfileCustomOptions'] == 1){
						$measurements_label  ="<em>(In Inches)</em>";
					} elseif($data3['ProfileCustomOptions'] == 2) {
					  	$measurements_label  ="<em>(In Pounds)</em>";
					} elseif($data3['ProfileCustomOptions'] == 3) {
					  	$measurements_label  ="<em>(In Feet/Inches)</em>";
					}
				}
			}  
			$isTextArea = "";
			if($ProfileCustomType == 4){
				$isTextArea ="textarea-field"; 
			}
		echo "  <tr valign=\"top\" class=\"".$isTextArea."\">\n";
		echo "    <th scope=\"row\"><div class=\"box\">". $data3['ProfileCustomTitle'].$measurements_label."</div></th>\n"; 
		echo "    <td>\n";		  
		  
           	echo "<div class=\"box\">";
			if ($ProfileCustomType == 1) { //TEXT
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";						
			} elseif ($ProfileCustomType == 2) { // Min Max
			
				$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data3['ProfileCustomOptions'],"}"),"{"));
				list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
			 
				if (!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)) {
						echo "<br /><br /> <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /><br />\n";
				} else {
						echo "<br /><br />  <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data3['ProfileCustomID']]."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data3['ProfileCustomID']]."\" /><br />\n";
				}
			 
			} elseif ($ProfileCustomType == 3) {  // Drop Down
				
				list($option1,$option2) = explode(":",$data3['ProfileCustomOptions']);	
					
				$data = explode("|",$option1);
				$data2 = explode("|",$option2);
				
				echo "<label class=\"dropdown\">".$data[0]."</label>";
				echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
				echo "<option value=\"\">--</option>";
					$pos = 0;
					foreach($data as $val1){
						
						if($val1 != end($data) && $val1 != $data[0]){
						
							if ($val1 == $ProfileCustomValue ) {
								$isSelected = "selected=\"selected\"";
								echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
							} else {
								echo "<option value=\"".$val1."\" >".$val1."</option>";
							}					
						}
					}
				echo "</select>\n";
					
					
				if (!empty($data2) && !empty($option2)) {
					echo "<label class=\"dropdown\">".$data2[0]."</label>";
				
						$pos2 = 0;
						echo "11<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
						echo "<option value=\"\">--</option>";
						foreach($data2 as $val2){
								if($val2 != end($data2) && $val2 !=  $data2[0]){
									echo "<option value=\"".$val2."\" ". selected($val2, $ProfileCustomValue) ." >".$val2."</option>";
								}
							}
						echo "</select>\n";
				}
			} elseif ($ProfileCustomType == 4) {
				
				echo "<textarea style=\"width: 100%; min-height: 300px;\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">". $ProfileCustomValue ."</textarea>";
				
			} elseif ($ProfileCustomType == 5) {
			
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				//echo "<div style=\"width:300px;float:left;\">";
				foreach($array_customOptions_values as $val){
					$xplode = explode(",",$ProfileCustomValue);
					if(!empty($val)){
						echo "<label class=\"checkbox\"><input type=\"checkbox\" value=\"". $val."\"   "; if(in_array($val,$xplode)){ echo "checked=\"checked\""; } echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" /> ";
						echo "". $val."</label>";                               
					}
				}     

			} elseif ($ProfileCustomType == 6) {
				
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				
				foreach($array_customOptions_values as $val){
					echo "<input type=\"radio\" value=\"". $val."\" "; checked($val, $ProfileCustomValue); echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					echo "<span>". $val."</span><br/>";
				}
			} elseif ($ProfileCustomType == 7) { //Imperial/Metrics
			
				if($data3['ProfileCustomOptions']==3){
					if($rb_agency_option_unittype == 1){
						// 
						echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">\n";
						if (empty($ProfileCustomValue)) {
							echo "  <option value=\"\">--</option>\n";
						}
						// 
						$i=36;
						$heightraw = 0;
						$heightfeet = 0;
						$heightinch = 0;
						while($i<=90)  { 
							$heightraw = $i;
							$heightfeet = floor($heightraw/12);
							$heightinch = $heightraw - floor($heightfeet*12);
							echo " <option value=\"". $i ."\" ". selected($ProfileCustomValue, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
							$i++;
						}
						echo " </select>\n";
					} else {
					// 
					echo "  <input type=\"text\" id=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" />\n";
					}
				} else {
				echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
				}						
			}									
        echo "<div class=\"box\">";
	} // End if Empty ProfileCustomID
}

/*/
*   ================ Get Profile Gender for each user ===================
*   @returns GenderTitle
/*/   
function rb_agency_getGenderTitle($ProfileGenderID){
 
	$query = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID='".$ProfileGenderID."'";
	$results = mysql_query($query) or die(mysql_error());
	$count = mysql_num_rows($results);

	if($count > 0){
	 	$data = mysql_fetch_assoc($results);
		return $data["GenderTitle"];
	} else {
		return 0;	 
	}
	rb_agency_checkExecution();
}

/*/
*   ================ Filters custom fields to show based on assigned gender ===================
*   @returns GenderTitle
/*/
function rb_agency_filterfieldGender($ProfileCustomID, $ProfileGenderID){

	$query = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 AND ProfileCustomID ='".$ProfileCustomID."' AND ProfileCustomShowGender IN('".$ProfileGenderID."','') ";
	$results = mysql_query($query) or die(mysql_error());
	$count = mysql_num_rows($results);

	if($count > 0){
	 	return true;  
	} else {
	 	return false;
	}
	rb_agency_checkExecution();
}
 
/*/
* ======================== Get Favorite & Casting Cart Links ===============
* @Returns links
/*/		
function rb_agency_get_miscellaneousLinks($ProfileID = ""){
 
	$rb_agency_options_arr 						= get_option('rb_agency_options');
	$rb_agency_option_profilelist_favorite		= isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;
	$rb_agency_option_profilelist_castingcart 	= isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart'] : 0;
	rb_agency_checkExecution();

	if ($rb_agency_option_profilelist_favorite) {
		//Execute query - Favorite Model
		if(!empty($ProfileID)){
			$queryFavorite = mysql_query("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE ".rb_agency_get_current_userid()." = fav.SavedFavoriteProfileID AND fav.SavedFavoriteTalentID = '".$ProfileID."' ") or die(mysql_error());
			$dataFavorite = mysql_fetch_assoc($queryFavorite); 
			$countFavorite = mysql_num_rows($queryFavorite);
		}
	}	
	
	if ($rb_agency_option_profilelist_castingcart) {
      	//Execute query - Casting Cart
		if(!empty($ProfileID)){
			$queryCastingCart = mysql_query("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE ".rb_agency_get_current_userid()." = cart.CastingCartProfileID AND cart.CastingCartTalentID = '".$ProfileID."' ") or die(mysql_error());
			$dataCastingCart = mysql_fetch_assoc($queryCastingCart); 
			$countCastingCart = mysql_num_rows($queryCastingCart);
		}
	}

	$disp = "";	
	if ($rb_agency_option_profilelist_favorite) {
		
		if($countFavorite <= 0){
			$disp .= "    <div class=\"favorite\"><a title=\"Save to Favorites\" rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$ProfileID."\"></a></div>\n";
		}else{
			$disp .= "<div class=\"favorite\"><a rel=\"nofollow\" title=\"Remove from Favorites\" href=\"javascript:;\" class=\"favorited\" id=\"".$ProfileID."\"></a></div>\n";
		}					
	}
			
	if ($rb_agency_option_profilelist_castingcart) {
		if($countCastingCart <=0){
			$disp .= "<div class=\"castingcart\"><a title=\"Add to Casting Cart\" href=\"javascript:;\" id=\"".$ProfileID."\"  class=\"save_castingcart\"></a></div></li>";
		} else {
			if(get_query_var('type')=="casting"){ //hides profile block when icon is click
			 	$divHide="onclick=\"javascript:document.getElementById('div$ProfileID').style.display='none';\"";
			}
			$disp .= "<div class=\"castingcart\"><a $divHide href=\"javascript:void(0)\"  id=\"".$ProfileID."\" title=\"Remove from Casting Cart\"  class=\"saved_castingcart\"></a></div>";
	  	}
	}
 	return $disp; 
}
 
/*/
* ======================== Get Custom Fields ===============
* @Returns Custom Fields
/*/
function rb_agency_getProfileCustomFields($ProfileID, $ProfileGender) {

	global $wpdb;
	global $rb_agency_option_unittype;
	
	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");
	foreach ($resultsCustom as $resultCustom) {
		if(!empty($resultCustom->ProfileCustomValue )){
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
					if($resultCustom->ProfileCustomOptions == 1){
						$label = "(cm)";
					} elseif($resultCustom->ProfileCustomOptions == 2){
						$label = "(kg)";
					}
			 	} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
					if($resultCustom->ProfileCustomOptions == 1){
					$label = "(in)";
					} elseif($resultCustom->ProfileCustomOptions == 2){
						$label = "(lbs)";
					} elseif($resultCustom->ProfileCustomOptions == 3){
						$label = "(ft/in)";
					}
			 	}
				$measurements_label = "<span class=\"label\">". $label ."</span>";
			} else {
				$measurements_label = "";
			}

			// Lets not do this...
			$measurements_label = "";
		 
			if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
				if ($resultCustom->ProfileCustomType == 7){
					if($resultCustom->ProfileCustomOptions == 3){
					   	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet."ft ".$heightinch." in</li>\n";
					} else {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
			   	} else {
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
			   	}
			  
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7){
				  	if($resultCustom->ProfileCustomOptions == 3){
					 	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
					   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet."ft ".$heightinch." in</li>\n";
				  	} else {
						echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
				  	}
			   	} else {
					echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
			   	}
			}
		}
	}
}

/*/
* ======================== Get Custom Fields ===============
* @Returns Custom Fields excluding a title
* @parm includes an array of title
/*/
function rb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender, $title_to_exclude) {

	global $wpdb;
	global $rb_agency_option_unittype;
	
	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");
	foreach ($resultsCustom as $resultCustom) {
		if(!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude)){ 	
			if(!empty($resultCustom->ProfileCustomValue )){
				if ($resultCustom->ProfileCustomType == 7) { //measurements field type
					if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
						if($resultCustom->ProfileCustomOptions == 1){
							$label = "(cm)";
						} elseif($resultCustom->ProfileCustomOptions == 2){
							$label = "(kg)";
						}
				 	} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
						if($resultCustom->ProfileCustomOptions == 1){
							$label = "(in)";
						} elseif($resultCustom->ProfileCustomOptions == 2){
							$label = "(lbs)";
						} elseif($resultCustom->ProfileCustomOptions == 3){
							$label = "(ft/in)";
						}
					}
				 	$measurements_label = "<span class=\"label\">". $label ."</span>";
				} else {
					$measurements_label = "";
				}

				// Lets not do this...
				$measurements_label = "";
			 
				if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
						   	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
						   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet."ft ".$heightinch." in</li>\n";
						} else {
						  	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
				   	} else {
						echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
				   	}
				  
				} elseif ($resultCustom->ProfileCustomView == "2") {
					if ($resultCustom->ProfileCustomType == 7){
					  	if($resultCustom->ProfileCustomOptions == 3){
						 	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
						   	echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet."ft ".$heightinch." in</li>\n";
					  	} else {
							echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					  	}
				   	} else {
						echo "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
				   	}
				}
			}
		}
	} 
} 
 
function rb_agency_getProfileCustomFieldsEcho($ProfileID, $ProfileGender,$exclude="",$include="") {
	global $wpdb;
	global $rb_agency_option_unittype;
     
	$query="SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ."";
	
	if(!empty($exclude)){$query.="AND ProfileCustomID IN($exclude)";}
    if(!empty($include)){$query.="AND ProfileCustomID NOT IN($include)";}

	$query.="GROUP 3 BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC ";
	
	$resultsCustom = $wpdb->get_results($query);
	foreach ($resultsCustom as $resultCustom) {
		if(!empty($resultCustom->ProfileCustomValue )){
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
					if($resultCustom->ProfileCustomOptions == 1){
						$label = "(cm)";
					} elseif($resultCustom->ProfileCustomOptions == 2){
						$label = "(kg)";
					}
				} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
					if($resultCustom->ProfileCustomOptions == 1){
						$label = "(in)";
					} elseif($resultCustom->ProfileCustomOptions == 2){
						$label = "(lbs)";
					 }elseif($resultCustom->ProfileCustomOptions == 3){
						$label = "(ft/in)";
					}
			 	}
				$measurements_label = "<span class=\"label\">". $label ."</span>";
			} else {
			 	$measurements_label = "";
			}

			// Lets not do this...
			$measurements_label = "";
		
			if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
				
				if ($resultCustom->ProfileCustomType == 7){
					if($resultCustom->ProfileCustomOptions == 3){
					   	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
					   echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
					} else {
					   	echo  "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
					}
			   	} else {
				   if($echo!="dontecho"){  // so it wont exit if PDF generator request info
					    if($resultCustom->ProfileCustomTitle.$measurements_label=="Experience"){return "";}
				   	
					echo "<li id='". $resultCustom->ProfileCustomTitle .$measurements_label."'><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   	}
			  }
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7){
				  	if($resultCustom->ProfileCustomOptions == 3){
					 	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
					   	echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
				  	} else {
						echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
				  	}
			   	} else {
					echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   }
			}
		}
	}
	if($echo=="dontecho"){return $return;}else{echo $return;}
}  

function rb_agency_getProfileCustomFieldsCustom($ProfileID, $ProfileGender,$echo="") {

	global $wpdb;
	global $rb_agency_option_unittype;

	$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");

	foreach ($resultsCustom as $resultCustom) {

		if(!empty($resultCustom->ProfileCustomValue )){
			if ($resultCustom->ProfileCustomType == 7) { //measurements field type
			   	if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
					if($resultCustom->ProfileCustomOptions == 1){
						$label = "(cm)";
					} elseif($resultCustom->ProfileCustomOptions == 2){
						$label = "(kg)";
					}
				} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)

					if($resultCustom->ProfileCustomOptions == 1){
						$label = "(in)";
					} elseif($resultCustom->ProfileCustomOptions == 2){
						$label = "(lbs)";
					} elseif($resultCustom->ProfileCustomOptions == 3){
						$label = "(ft/in)";
					}
		 		}

				$measurements_label = "<span class=\"label\">". $label ."</span>";

	 		} else {
		 		$measurements_label = "";
	 		}

		 	// Lets not do this...
			$measurements_label = "";

			if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
				
				if ($resultCustom->ProfileCustomType == 7){
					if($resultCustom->ProfileCustomOptions == 3){
					   	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
					   	$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
					} else {
					   	$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
					}
			   	} else {
				   	if($echo!="dontecho"){  // so it wont exit if PDF generator request info
					    if($resultCustom->ProfileCustomTitle.$measurements_label=="Experience") { return ""; }
				   	}
					$return.="<li id='". $resultCustom->ProfileCustomTitle .$measurements_label."'><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   	}
			  
			} elseif ($resultCustom->ProfileCustomView == "2") {
				if ($resultCustom->ProfileCustomType == 7){
				  	if($resultCustom->ProfileCustomOptions == 3){
					 	$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
					   	$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
				  	} else {
						$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
				  	}
			   	} else {
					$return.="<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
			   	}
			}
		}
	}	
	if($echo=="dontecho"){return $return;}else{echo $return;}
}
  
  
/*/
* ======================== Get ProfileID by UserLinkedID ===============
* @Returns ProfileID
/*/
function rb_agency_getProfileIDByUserLinked($ProfileUserLinked){
  
   	if(!empty($ProfileUserLinked)){
      	$query = mysql_query("SELECT ProfileID,ProfileUserLinked FROM ".table_agency_profile." WHERE ProfileUserLinked = ".$ProfileUserLinked." ");
      	$fetchID = mysql_fetch_assoc($query);
		return $fetchID["ProfileID"];
   	}
}

/*/
* ======================== Get Media Categories===============
* @Returns Media Categories
/*/
function rb_agency_getMediaCategories($GenderID){

	$query = mysql_query("SELECT MediaCategoryID,MediaCategoryTitle,MediaCategoryGender,MediaCategoryOrder FROM  ".table_agency_mediacategory." ORDER BY MediaCategoryOrder");
	$count = mysql_num_rows($query);
	while($f = mysql_fetch_assoc($query)){
		if($f["MediaCategoryGender"] == $GenderID || $f["MediaCategoryGender"] == 0){
			echo "<option value=\"".$f["MediaCategoryTitle"]."\">".$f["MediaCategoryTitle"]."</option>";	 
		}
	}
}

/*/
* ======================== Show/Hide Admin Toolbar===============
* 
/*/
function rb_agency_disableAdminToolbar() {
	add_filter('show_admin_bar', '__return_false');
}

$rb_agencyinteract_options_arr = get_option('rb_agencyinteract_options');
$rb_agencyinteract_option_profilemanage_toolbar =isset($rb_agencyinteract_options_arr["rb_agencyinteract_option_profilemanage_toolbar"]) ? (int)$rb_agencyinteract_options_arr["rb_agencyinteract_option_profilemanage_toolbar"] : 0;

if($rb_agencyinteract_option_profilemanage_toolbar==1) {
  	rb_agency_disableAdminToolbar(); 
}

/*/
* ======================== Edit Text/Label/Header ===============
* 
/*/
//add_filter( 'gettext', 'rb_agency_editTitleText', 10, 3 );
function rb_agency_editTitleText($string){
	return "<span>".$string."<a href=\"javascript:;\" style=\"font-size:11px;color:blue;text-decoration:underline;\">Edit</a></span>";  
}

/*/
*================ Add toolbar menu ==============================
*
/*/ 
function rb_agency_callafter_setup() {

	if (current_user_can('level_10') && !is_admin()) {
		function rb_agency_add_toolbar($wp_toolbar) {
			$wp_toolbar->add_node(array(
				'id' => 'rb-agency-toolbar-settings',
				'title' => 'RB Agency Settings',
				'href' =>  get_admin_url().'admin.php?page=rb_agency_menu_settings',
				'meta' => array('target' => 'rb-agency-toolbar-settings')
			));
		}
	  	add_action('admin_bar_menu', 'rb_agency_add_toolbar', 999);
	}
}
add_action( 'after_setup_theme',"rb_agency_callafter_setup");

/*/
*  PHP Profiler DEBUG MODE
/*/ 
function rb_agency_checkExecution() {

	global $RB_DEBUG_MODE;

	if($RB_DEBUG_MODE == true){

		$start = microtime();
		echo "<div style=\"float:left;border:1px solid #ccc;background:#ccc !important; color:black!important;\">";
		echo "<pre >";
		for($i=100;$i>0;$i--) {
			echo $i;
			echo "\n";
		}

		$end = microtime();
		$parseTime = $end-$start;
		
		echo "-DEBUG MODE- Time Execution";
		echo "\n\n";
		echo $parseTime;
		echo "</pre>";
		
		$trace = debug_backtrace();
		$file   = $trace[$level]['file'];
		$line   = $trace[$level]['line'];
		$object = $trace[$level]['object'];
		
      	if (is_object($object)) { $object = get_class($object); }
		$result = var_export( $var, true );
        
        echo "\n<pre>Dump: $result</pre>";		
		echo "\n<pre>";

		debug_print_backtrace();

		echo "\n\nWhere called: line $line of $object \n(in $file)";
		echo "</pre>";
		echo "</div>";
	}
}

/*/
 *   Profile Extend Social Links
/*/ 
function rb_agency_getSocialLinks(){

	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_showsocial = $rb_agency_options_arr['rb_agency_option_showsocial'];

	if($rb_agency_option_showsocial){
		echo "		<div class=\"social addthis_toolbox addthis_default_style\">\n";
		echo "			<a href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c4d7ce67dde9ce7\" class=\"addthis_button_compact\">". __("Share", rb_agency_TEXTDOMAIN). "</a>\n";
		echo "			<span class=\"addthis_separator\">|</span>\n";
		echo "			<a class=\"addthis_button_facebook\"></a>\n";
		echo "			<a class=\"addthis_button_myspace\"></a>\n";
		echo "			<a class=\"addthis_button_google\"></a>\n";
		echo "			<a class=\"addthis_button_twitter\"></a>\n";
		echo "		</div><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c4d7ce67dde9ce7\"></script>\n";
	}
}

//get previous and next profile link
function linkPrevNext($ppage,$nextprev,$type="",$division=""){

	if($nextprev=="next") { $nPid=$pid+1; }
	else { $nPid=$pid-1; }
	
	$sql="SELECT ProfileGallery FROM ".table_agency_profile." WHERE 1 AND ProfileGender ='$type'  AND ProfileType ='1' ";
	
	//filter division 
	if($division=="/women/"){$ageStart=17;$ageLimit=99;}
	elseif($division=="/men/"){$ageStart=17;$ageLimit=99;}
	elseif($division=="/teen-girls/"){$ageStart=12;$ageLimit=27;}
	elseif($division=="/teen-boys/"){$ageStart=12;$ageLimit=17;}
	elseif($division=="/girls/"){$ageStart=1;$ageLimit=12;}
	elseif($division=="/boys/"){$ageStart=1;$ageLimit=12;}
 	$sql.="	AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth)), '%Y')+0 > $ageStart
        	AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(ProfileDateBirth)), '%Y')+0 <=$ageLimit
			AND ProfileIsActive = 1
	";
	$tempSql=$sql;
	
	//end filter 
	if($nextprev=="next"){ 
			$sql.=" AND  ProfileGallery > '$ppage' ORDER BY ProfileContactNameFirst ASC"; // to get next record
	} else {
		$sql.=" AND  ProfileGallery < '$ppage' ORDER BY ProfileContactNameFirst DESC"; // to get next
	}
  
	$sql.=" LIMIT 0,1 ";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_assoc($query);  
  
  	if(empty($fetch["ProfileGallery"])){ //make sure it wont send empty url

	  	if($nextprev=="next"){ 
  		 	$sql=$tempSql."  ORDER BY ProfileContactNameFirst ASC"; // to get next record
 	  	}else{
  	     	$sql=$tempSql."  ORDER BY ProfileContactNameFirst DESC"; // to get next
      	}
	  
     	$sql.=" LIMIT 0,1 ";
	 	$query = mysql_query($sql);
     	$fetch = mysql_fetch_assoc($query);
  	}
		 
	return  $fetch["ProfileGallery"];
}


function getExperience($pid){ 
	$query = mysql_query("SELECT ProfileCustomValue FROM ".table_agency_customfield_mux." WHERE ProfileID = '".$pid."' AND ProfileCustomID ='16' ");
    $fetch = mysql_fetch_assoc($query);
    return  $fetch["ProfileCustomValue"];
}

function checkCart($currentUserID,$pid){
  	$query="SELECT * FROM  ".table_agency_castingcart." WHERE CastingCartProfileID='".$currentUserID."' AND CastingCartTalentID='".$pid."' ";
	$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
	return mysql_num_rows($results);
}

/* function that lists users for generating login/password */
function rb_display_profile_list(){  
    global $wpdb;
    $rb_agency_options_arr = get_option('rb_agency_options');
    $rb_agency_option_locationtimezone 		= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];

    echo "<div class=\"wrap\">\n";
    echo "  <h3 class=\"title\">". __("Profiles List", rb_agency_TEXTDOMAIN) ."</h3>\n";
		
    // Sort By
    $sort = "";
    if (isset($_GET['sort']) && !empty($_GET['sort'])){
        $sort = $_GET['sort'];
    }
    else {
        $sort = "profile.ProfileContactNameFirst";
    }
		
    // Sort Order
    $dir = "";
    if (isset($_GET['dir']) && !empty($_GET['dir'])){
        $dir = $_GET['dir'];
        if ($dir == "desc" || !isset($dir) || empty($dir)){
            $sortDirection = "asc";
        } else {
            $sortDirection = "desc";
        } 
    } else {
       $sortDirection = "desc";
       $dir = "asc";
    }
  	
    // Filter
    $filter = "WHERE profile.ProfileIsActive IN (0,1,4) ";
    if ((isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) || isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
        if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])){
                $selectedNameFirst = $_GET['ProfileContactNameFirst'];
                $query .= "&ProfileContactNameFirst=". $selectedNameFirst ."";
                $filter .= " AND profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
        }
        if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
                $selectedNameLast = $_GET['ProfileContactNameLast'];
                $query .= "&ProfileContactNameLast=". $selectedNameLast ."";
                $filter .= " AND profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
        }
    }
    if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])){
            $selectedCity = $_GET['ProfileLocationCity'];
            $query .= "&ProfileLocationCity=". $selectedCity ."";
            $filter .= " AND profile.ProfileLocationCity='". $selectedCity ."'";
    }
    if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])){
            $selectedType = $_GET['ProfileType'];
            $query .= "&ProfileType=". $selectedType ."";
            $filter .= " AND profiletype.DataTypeID='". $selectedType ."'";
    }
    if (isset($_GET['ProfileVisible']) && !empty($_GET['ProfileVisible'])){
            $selectedVisible = $_GET['ProfileVisible'];
            $query .= "&ProfileVisible=". $selectedVisible ."";
            $filter .= " AND profile.ProfileIsActive='". $selectedVisible ."'";
    }
    if (isset($_GET['ProfileGender']) && !empty($_GET['ProfileGender'])){
            $ProfileGender = (int)$_GET['ProfileGender'];
            if($ProfileGender)
                    $filter .= " AND profile.ProfileGender='".$ProfileGender."'";
    }
		
    //Paginate
    $items = mysql_num_rows(mysql_query("SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID ". $filter  ."")); // number of total rows in the database
    if($items > 0) {
        $p = new rb_agency_pagination;
        $p->items($items);
        $p->limit(50); // Limit entries per page
        $p->target("admin.php?page=". $_GET['page'] . '&ConfigID=99' . $query);
        $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
        $p->calculate(); // Calculates what to show
        $p->parameterName('paging');
        $p->adjacents(1); //No. of page away from the current page

        if(!isset($_GET['paging'])) {
                $p->page = 1;
        } else {
                $p->page = $_GET['paging'];
        }

        //Query for limit paging
        $limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
    } else {
        $limit = "";
    }
    
    /* Top pagination */
    echo "<div class=\"tablenav\">\n";
    echo "  <div class=\"tablenav-pages\">\n";
    
    if($items > 0) {
        echo $p->show();  // Echo out the list of paging. 
    }
    echo "  </div>\n";
    echo "</div>\n";
    /* End Top pagination */
     
    /* Table Content */
    include_once('admin/profile_list_table.php');
    /* End Table Content */

    /* Bottom pagination */
    echo "<div class=\"tablenav\">\n";
    echo "  <div class='tablenav-pages'>\n";

    if($items > 0) {
            echo $p->show();  // Echo out the list of paging. 
    }

    echo "  </div>\n";
    echo "</div>\n";
    /*End Bottom pagination */
    
    echo "</div>";

}


add_action('wp_ajax_send_mail', 'register_and_send_email');

function register_and_send_email(){ 
    global $wpdb;
    $profileid = (int)$_POST['profileid'];
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // getting required fileds from rb_agency_profile
    $profile_row = $wpdb->get_results( "SELECT ProfileID, ProfileContactDisplay, ProfileContactNameFirst, ProfileContactNameLast FROM rb_agency_profile WHERE ProfileID = '" . $profileid . "'" );

    // creating new user
    $user_id = username_exists( $profile_row[0]->ProfileContactDisplay );
    if ( !$user_id and email_exists($user_email) == false ) {
        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $user_id = wp_create_user( $login, $random_password, $email ); 
        if(is_numeric($user_id)){
            wp_set_password( $password, $user_id );

            // updating some information we have in wp_users
            $wpdb->update( 
                    'wp_users', 
                    array( 'display_name' => $profile_row[0]->ProfileContactDisplay ), 
                    array( 'ID' => $user_id ), 
                    array( '%s' ), 
                    array( '%d' ) 
            );

            // inserting some information we have in wp_usermeta
            update_user_meta( $user_id, 'first_name', $profile_row[0]->ProfileContactNameFirst );
            update_user_meta( $user_id, 'last_name', $profile_row[0]->ProfileContactNameLast );
			
			// linking the user ID with profile ID
			$wpdb->update( 
				'rb_agency_profile',
				array( 'ProfileUserLinked' => $user_id ),
				array( 'ProfileID' => $profile_row[0]->ProfileID ),
				array( '%d' ),
				array( '%d' )
			);

            send_email_lp($login, $password, $email);

            echo 'SUCCESS';
        } else {
            echo $user_id->errors['existing_user_login'][0];
        }

    } else {
        echo 'The user is already registrated!';
    }
    
    die;
}

add_action('wp_ajax_send_bulk_mail', 'bulk_register_and_send_email');

function bulk_register_and_send_email(){
    global $wpdb;
    
    $users_lp = $_POST['users_pl'];
    //echo '<pre>'; print_r($users_lp);
    
    $success = FALSE;
    
    foreach($users_lp as $user_lp){ 
        $profile_row = $wpdb->get_results( "SELECT ProfileID, ProfileContactDisplay, ProfileContactNameFirst, ProfileContactNameLast FROM rb_agency_profile WHERE ProfileID = '" . $user_lp['pid'] . "'" );
        
        $user_id = username_exists( $profile_row[0]->ProfileContactDisplay );
        if ( !$user_id and email_exists($user_email) == false ) { 
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = wp_create_user( $user_lp['login'], $random_password, $user_lp['email'] ); 
            if(is_numeric($user_id)){ 
                wp_set_password( $user_lp['password'], $user_id );

                // updating some information we have in wp_users
                $wpdb->update( 
                        'wp_users', 
                        array( 'display_name' => $profile_row[0]->ProfileContactDisplay ), 
                        array( 'ID' => $user_id ), 
                        array( '%s' ), 
                        array( '%d' ) 
                );

                // inserting some information we have in wp_usermeta
                update_user_meta( $user_id, 'first_name', $profile_row[0]->ProfileContactNameFirst );
                update_user_meta( $user_id, 'last_name', $profile_row[0]->ProfileContactNameLast );
				
				// linking the user ID with profile ID
				$wpdb->update( 
					'rb_agency_profile',
					array( 'ProfileUserLinked' => $user_id ),
					array( 'ProfileID' => $profile_row[0]->ProfileID ),
					array( '%d' ),
					array( '%d' )
				);
                
                send_email_lp($user_lp['login'], $user_lp['password'], $user_lp['email']);
                
                $success = TRUE;
                
            } else {
                //print_r($user_id);
            }
        }        
    }
    
    if($success){
        echo 'SUCCESS';
    }
    
    die;    
}

function send_email_lp($login, $password, $email){
    $admin_email = get_bloginfo('admin_email');

    $headers = 'From: RB Agency <' . $admin_email . '>\r\n';

    $subject = 'Your new Login and Password';
    
    $message = read_email_content(true);
    if($message == 'empty'){
        $message = 'Hello, we generated new login and password for you at RB Agency\n\n[login]\n[password]\n\nYou can login [url]\n\nThanks.';
    }

    $message = str_replace('[login]', 'Login: <strong>' . $login . '</strong>', $message);
    $message = str_replace('[password]', 'Password: <strong>' . $password . '</strong>', $message);
    $message = str_replace('[url]', '<a href="' . site_url('profile-login') . '">login</a>', $message);
    
    $message = nl2br($message);

    add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
    wp_mail($email, $subject, $message, $headers);
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
    
}

add_action('wp_ajax_write_email_cnt', 'write_email_content');

function write_email_content(){
    $email_message = $_POST['email_message'];
    update_option( 'rb_email_content', $email_message );
    die;
}

add_action('wp_ajax_read_email_cnt', 'read_email_content');

function read_email_content($ret = false){ 
    if($ret){
        return $email_message = get_option( 'rb_email_content', 'empty' );
    }
    else {
        echo $email_message = get_option( 'rb_email_content', 'empty' );
    }
    
    die;
}

/*
 * Function to retrieve
 * featured widget profile
 *
 * @parm: none
 * @return:  		
 * Profile Name = array[0];
 * Gender = array[1];
 * Custom Fields = array[2];
 * Gallery Folder = array[3];
 * Profile Pic URL = array[4];
 */
function featured_homepage(){
	            
				global $wpdb;
				
				/*
				 * Get details for profile
				 * featured
				 */
				$count = 1;
				 
				$q = "SELECT profile.*,
		
				(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
				 WHERE profile.ProfileID = media.ProfileID 
				 AND media.ProfileMediaType = \"Image\" 
				 AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 
				
				 FROM ". table_agency_profile ." profile 
				 WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
				 AND profile.ProfileIsFeatured = 1  
				 ORDER BY RAND() LIMIT 0,$count";						

				$r = mysql_query($q);
				
				$countList = mysql_num_rows($r);
				
				$array_data = array();
				
				while ($dataList = mysql_fetch_assoc($r)) {
					
					/*
					 * Get From Custom Fields
					 * per profile
					 */
					$get_custom = 'SELECT * FROM ' . table_agency_customfield_mux .
								  ' WHERE ProfileID = ' . $dataList["ProfileID"]; 
								  
					$result = mysql_query($get_custom);
					
					$desc_list = array('shoes', 'eyes', 'shoes', 'skin');
					
					$a_male = array('height','weight','waist', 'skin tone',
									'eye color',  'shoe size', 'shirt');
					
					$array_male = array();
					$array_female = array();

					$a_female = array('bust', 'waist', 'hips', 'dress',
										  'shoe size','hair', 'eye color');
                    
					$name = ucfirst($dataList["ProfileContactNameFirst"]) ." ". strtoupper($dataList["ProfileContactNameLast"][0]); ;
					
					while ($custom = mysql_fetch_assoc($result)) {
                         
						 $get_title = 'SELECT ProfileCustomTitle FROM ' . table_agency_customfields .
						 ' WHERE ProfileCustomID = ' . $custom["ProfileCustomID"] ; 
						 
						 $result2 = mysql_query($get_title);
						 
						 $custom2 = mysql_fetch_assoc($result2);
						 
						 if(strtolower(rb_agency_getGenderTitle($dataList['ProfileGender'])) == "male"){
							 
							 if(in_array(strtolower($custom2['ProfileCustomTitle']),$a_male)){
								 $array_male[$custom2['ProfileCustomTitle']] = $custom['ProfileCustomValue'];
							 }
						 
						 } else if(strtolower(rb_agency_getGenderTitle($dataList['ProfileGender'])) == "female"){
							 
							 if(in_array(strtolower($custom2['ProfileCustomTitle']),$a_female)){
								 $array_female[$custom2['ProfileCustomTitle']] = $custom['ProfileCustomValue'];
							 }				 
						 }
					
					}
					
					if(strtolower(rb_agency_getGenderTitle($dataList['ProfileGender'])) == "male"){
						
						$array_data = array($name,'male',$array_male,$dataList["ProfileGallery"],$dataList["ProfileMediaURL"]);
						
					} else if(strtolower(rb_agency_getGenderTitle($dataList['ProfileGender'])) == "female"){
						
						$array_data = array($name,'female',$array_female,$dataList["ProfileGallery"],$dataList["ProfileMediaURL"]);
								 
					}
					
				}
	
	return $array_data;
	
}

/*
// Phel: Test Content
function replace_content_on_the_fly($text){
	$text ="sample";
}
apply_filter('the_content', 'replace_content_on_the_fly');

// Phel: Set Themes Defult Template
function plugin_myown_template() {
  include(TEMPLATEPATH."/page.php");
  exit;
}
add_action('template_redirect', 'plugin_myown_template');
*/

?>