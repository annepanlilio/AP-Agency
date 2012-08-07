<?php

// *************************************************************************************************** //
// Admin Head Section 

	add_action('admin_head', 'rb_agency_admin_head');
		function rb_agency_admin_head(){
		  if( is_admin() ) {
			echo "<link rel=\"stylesheet\" href=\"". rb_agency_BASEDIR ."style/admin.css\" type=\"text/css\" media=\"screen\" />\n";
			echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>\n";
			echo "<script type=\"text/javascript\" src=\"". rb_agency_BASEDIR ."js/js-customfields.js\"></script>\n"; 
		  }
		}
	

// *************************************************************************************************** //
// Page Head Section

	add_action('wp_head', 'rb_agency_inserthead');
		// Call Custom Code to put in header
		function rb_agency_inserthead() {
		  if( !is_admin() ) {
			
			$rb_agency_options_arr = get_option('rb_agency_options');
			
			if (isset($rb_agency_options_arr['rb_agency_option_gallerytype'])) {
				if ($rb_agency_options_arr['rb_agency_option_gallerytype'] == "1") {
					// Slimbox
					echo "<script type=\"text/javascript\" src=\"". rb_agency_BASEDIR ."js/slimbox2.js\"></script>\n";
					echo "<link rel=\"stylesheet\" href=\"". rb_agency_BASEDIR ."style/slimbox2.css\" type=\"text/css\" media=\"screen\" />\n";
						
				} elseif ($rb_agency_options_arr['rb_agency_option_gallerytype'] == "2") {
					// PrettyBox
					echo "<link rel=\"stylesheet\" href=\"". rb_agency_BASEDIR ."style/prettyPhoto.css\" type=\"text/css\" media=\"screen\" />\n";
					echo "<script type=\"text/javascript\" src=\"". rb_agency_BASEDIR ."js/jquery.prettyPhoto.js\"></script>\n";
						
				} elseif ($rb_agency_options_arr['rb_agency_option_gallerytype'] == "9") {
					// Disable jQuery
					//wp_deregister_script('jquery'); //deregister current jquery
					//wp_deregister_script('jquery-lightbox');
					//wp_deregister_script('jquery-lightbox-balupton-edition');
				
					
				}
			}
		
			echo "<link rel=\"stylesheet\" href=\"". rb_agency_BASEDIR ."theme/style.css\" type=\"text/css\" media=\"screen\" />\n";
          
			/* OBSOLETE
			if (isset($rb_agency_options_arr['rb_agency_option_defaultcss'])) {
			echo "<style type=\"text/css\">\n";
			echo $rb_agency_options_arr['rb_agency_option_defaultcss'];
			echo "</style>\n";
			}
			*/
		  }
		
			
		
		 }
		
        

// *************************************************************************************************** //
// Add to WordPress Dashboard

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
			$newrules['profile-print'] = 'index.php?type=print';
			$newrules['profile-email'] = 'index.php?type=email';
			$newrules['dashboard'] = 'index.php?type=dashboard';
			$newrules['client-view/(.*)$'] = 'index.php?type=profilesecure&target=$matches[1]';
			$newrules['profile/(.*)/contact'] = 'index.php?type=profilecontact&target=$matches[1]';
			$newrules['profile/(.*)$'] = 'index.php?type=profile&target=$matches[1]';
			$newrules['profile-favorite'] = 'index.php?type=favorite';
			$newrules['profile-casting-cart'] = 'index.php?type=castingcart';
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
				  }elseif (get_query_var( 'type' ) == "castingcart") {
					return dirname(__FILE__) . '/theme/view-castingcart.php'; 
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
		$timezone_offset = (int)$offset; // Server Time
		$time_altered = time() + $timezone_offset *60 *60;
	
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
			$rb_agency_option_locationtimezone 			 = (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
			$rb_agency_option_privacy					 = (int)$rb_agency_options_arr['rb_agency_option_privacy'];

		// Set It Up	
		global $wp_rewrite;
		extract(shortcode_atts(array(
				"profilesearch_layout" => "advanced"
			), $atts));
		
		if ( ($rb_agency_option_privacy > 1 && is_user_logged_in()) || ($rb_agency_option_privacy < 2) ) {

			// Set It Up	
			global $wp_rewrite;
			extract(shortcode_atts(array(
					"currentcategory" => NULL,
					"profilegender" => NULL,
					"profiledatebirth_min" => NULL,
					"profiledatebirth_max" => NULL,
			), $atts));
	
			$DataTypeID				= $currentcategory;
			$ProfileGender			= $profilegender;
			$ProfileDateBirth_min	= $profiledatebirth_min;
			$ProfileDateBirth_max	= $profiledatebirth_max;
			$filter = "";
			
			// Gender
			if (isset($ProfileGender) && !empty($ProfileGender)){
				if (strtolower($ProfileGender) == "female") {
					$filter .= " AND profile.ProfileGender='female'";
				} elseif (strtolower($ProfileGender) == "male") {
					$filter .= " AND profile.ProfileGender='male'";
				}
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
	
			echo "<div id=\"profile-category-list\">\n";
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
			echo "</div>\n";
		} else {
			include("theme/include-login.php"); 	
		}

	}


	// Profile List
	function rb_agency_profilelist($atts, $content = NULL) {
		/*
		if (function_exists('rb_agency_profilelist')) { 
			$atts = array('count' => $count);
			rb_agency_profilelist($atts); }
		*/
		
		// Get Preferences
		$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_privacy					 = $rb_agency_options_arr['rb_agency_option_privacy'];
			$rb_agency_option_profilelist_count			 = $rb_agency_options_arr['rb_agency_option_profilelist_count'];
			$rb_agency_option_profilelist_perpage		 = $rb_agency_options_arr['rb_agency_option_profilelist_perpage'];
			$rb_agency_option_profilelist_sortby		 = $rb_agency_options_arr['rb_agency_option_profilelist_sortby'];
			$rb_agency_option_layoutprofilelist		 	 = $rb_agency_options_arr['rb_agency_option_layoutprofilelist'];
			$rb_agency_option_profilelist_expanddetails	 = $rb_agency_options_arr['rb_agency_option_profilelist_expanddetails'];
			$rb_agency_option_locationtimezone 			 = (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
			$rb_agency_option_profilelist_favorite		 = (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'];
			$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];
			$rb_agency_option_profilelist_castingcart 	 = (int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart'];

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
				"profilecastingcart" => NULL
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

          
        // Set CustomFields
	  if(isset($atts) && !empty($atts)){
		foreach($atts as $key => $val){
			
			if (substr($key,0,15) == "ProfileCustomID") {
					
				$value = explode("_",$key);
				$ProfileTypeKey = substr($value[0],15);
				
				if ($ProfileTypeKey != "ProfileCustomID" && $ProfileTypeKey !="") {
					
					$q = mysql_query("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = ". $ProfileTypeKey  ." ");
					$ProfileCustomType = mysql_fetch_assoc($q);
						
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
							if(!empty($val)){
							   $filter .= " AND customfield_mux.ProfileCustomValue='".$val."' ";
								$_SESSION[$key] = $val;
							}
						} elseif ($ProfileCustomType["ProfileCustomType"] == 3) { // Dropdown
						
							if(!empty($val)){
								list($val1, $val2) = explode("|",implode("|",explode(",",$val)));
								
								if(!empty($val1) && !empty($val2)){
									$filter .= " AND customfield_mux.ProfileCustomValue BETWEEN '".$val1."' AND '".$val2."' ";
									$_SESSION[$key] = $val;
								}elseif(!empty($val1)){
									$data = implode(",",explode("|",implode("|",explode(",",$val))));
									$filter .= " AND customfield_mux.ProfileCustomValue IN('".$data ."') ";
									$_SESSION[$key] = $val;
								}else{
									$data = implode("','",explode(",",$val));
									$filter .= " AND customfield_mux.ProfileCustomValue IN('".$val."') ";
									$_SESSION[$key] = $val;
								}
							}
							
						} elseif ($ProfileCustomType["ProfileCustomType"] == 4) { //Textarea
							if(!empty($val)){
							  $filter .= " AND customfield_mux.ProfileCustomValue='".$val."' ";
								$_SESSION[$key] = $val;
							}
							
						} elseif ($ProfileCustomType["ProfileCustomType"] == 5) { //Checkbox
							 if(!empty($val)){
							   
							 $val = implode("','",explode(",",$val));
							 $filter .= " AND customfield_mux.ProfileCustomValue IN('".$val."') ";
								$_SESSION[$key] = $val;
								
							}else{
								$_SESSION[$key] = "";
							}
						} elseif ($ProfileCustomType["ProfileCustomType"] == 6) { //Radiobutton 
							 if (!empty($val)) {
							   
							 $val = implode("','",explode(",",$val));
							 $filter .= " AND customfield_mux.ProfileCustomValue IN('".$val."') ";
								$_SESSION[$key] = $val;
								
							} else {
								$_SESSION[$key] = "";
							}
							
						}

					}
					
				}
			
		}  // end for each
	  } // end if
	  
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
		
		// Gender
		if (isset($ProfileGender) && !empty($ProfileGender)){
		    if (strtolower($ProfileGender) == "female") {
				$filter .= " AND profile.ProfileGender='female'";
		    } elseif (strtolower($ProfileGender) == "male") {
				$filter .= " AND profile.ProfileGender='male'";
		    }
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
		
 /*
		// Is this a profile ID search?
		if (isset($ProfileID) && !empty($ProfileID)){
			$ProfileID = $ProfileID;
			$filter .= " AND profile.ProfileID IN (". $ProfileID .") ";
		}


		// Active
		/*
		if (isset($ProfileIsActive)){
		  if ($ProfileIsActive == "1") {
			$selectedActive = "active";
			$filter .= " AND profile.ProfileIsActive=1";
		  } elseif ($ProfileIsActive == "0") {
			$selectedActive = "inactive";
			$filter .= " AND profile.ProfileIsActive=0";
		  } elseif ($ProfileIsActive == "2") {
			$selectedActive = "declassified";
			$filter .= " AND profile.ProfileIsActive=2";
		  }
		} else {
			$selectedActive = "";
			$filter .= " AND (profile.ProfileIsActive=1 OR profile.ProfileIsActive=0)";
		}
		*/
		/*
		  if ($ProfileIsFeatured == "1") {
			$filter .= " AND  ".table_agency_profile.".ProfileIsFeatured=1";
		  }
		  if ($ProfileIsPromoted == "1") {
			$filter .= " AND  ".table_agency_profile.".ProfileIsPromoted=1";
		  }
		

		// Location
		if (isset($ProfileLocationCity) && !empty($ProfileLocationCity)){
			$ProfileLocationCity = $ProfileLocationCity;
			$filter .= " AND profile.ProfileLocationCity='". $ProfileLocationCity ."'";
		}
		// Race
		if (isset($ProfileStatEthnicity) && !empty($ProfileStatEthnicity)){
			$ProfileStatEthnicity = $ProfileStatEthnicity;
			$filter .= " AND profile.ProfileStatEthnicity='". $ProfileStatEthnicity ."'";
		}
		// Skin
		if (isset($ProfileStatSkinColor) && !empty($ProfileStatSkinColor)){
			$ProfileStatSkinColor = $ProfileStatSkinColor;
			$filter .= " AND profile.ProfileStatSkinColor='". $ProfileStatSkinColor ."'";
		}
		// Eye
		if (isset($ProfileStatEyeColor) && !empty($ProfileStatEyeColor)){
			$ProfileStatEyeColor = $ProfileStatEyeColor;
			$filter .= " AND profile.ProfileStatEyeColor='". $ProfileStatEyeColor ."'";
		}
		// Hair
		if (isset($ProfileStatHairColor) && !empty($ProfileStatHairColor)){
			$ProfileStatHairColor = $ProfileStatHairColor;
			$filter .= " AND profile.ProfileStatHairColor='". $ProfileStatHairColor ."'";
		}
		// Height
		if (isset($ProfileStatHeight_min) && !empty($ProfileStatHeight_min)){
			$ProfileStatHeight_min = $ProfileStatHeight_min;
			$filter .= " AND profile.ProfileStatHeight >= '". $ProfileStatHeight_min ."'";
		}
		if (isset($ProfileStatHeight_max) && !empty($ProfileStatHeight_max)){
			$ProfileStatHeight_max = $ProfileStatHeight_max;
			$filter .= " AND profile.ProfileStatHeight <= '". $ProfileStatHeight_max ."'";
		}
		// Weight
		if (isset($ProfileStatWeight_min) && !empty($ProfileStatWeight_min)){
			$ProfileStatWeight_min = $ProfileStatWeight_min;
			$filter .= " AND profile.ProfileStatWeight >= ". $ProfileStatWeight_min ."";
		}
		if (isset($ProfileStatWeight_max) && !empty($ProfileStatWeight_max)){
			$ProfileStatWeight_max = $ProfileStatWeight_max;
			$filter .= " AND profile.ProfileStatWeight <= ". $ProfileStatWeight_max ."";
		}
		// Bust/Chest
		if (isset($ProfileStatBust_min) && !empty($ProfileStatBust_min)){
			$ProfileStatBust_min = $ProfileStatBust_min;
			$filter .= " AND profile.ProfileStatBust >= ". $ProfileStatBust_min ."";
		}
		if (isset($ProfileStatBust_max) && !empty($ProfileStatBust_max)){
			$ProfileStatBust_max = $ProfileStatBust_max;
			$filter .= " AND profile.ProfileStatBust <= ". $ProfileStatBust_max ."";
		}
		// Waist
		if (isset($ProfileStatWaist_min) && !empty($ProfileStatWaist_min)){
			$ProfileStatWaist_min = $ProfileStatWaist_min;
			$filter .= " AND profile.ProfileStatWaist >= ". $ProfileStatWaist_min ."";
		}
		if (isset($ProfileStatWaist_max) && !empty($ProfileStatWaist_max)){
			$ProfileStatWaist_max = $ProfileStatWaist_max;
			$filter .= " AND profile.ProfileStatWaist <= ". $ProfileStatWaist_max ."";
		}
		// Hip
		if (isset($ProfileStatHip_min) && !empty($ProfileStatHip_min)){
			$ProfileStatHip_min = $ProfileStatHip_min;
			$filter .= " AND profile.ProfileStatHip >= ". $ProfileStatHip_min ."";
		}
		if (isset($ProfileStatHip_max) && !empty($ProfileStatHip_max)){
			$ProfileStatHip_max = $ProfileStatHip_max;
			$filter .= " AND profile.ProfileStatHip <= ". $ProfileStatHip_max ."";
		}
		// Shoe
		if (isset($ProfileStatShoe_min) && !empty($ProfileStatShoe_min)){
			$ProfileStatShoe_min = $ProfileStatShoe_min;
			$filter .= " AND profile.ProfileStatShoe >= ". $ProfileStatShoe_min ."";
		}
		if (isset($ProfileStatShoe_max) && !empty($ProfileStatShoe_max)){
			$ProfileStatShoe_max = $ProfileStatShoe_max;
			$filter .= " AND profile.ProfileStatShoe <= ". $ProfileStatShoe_max ."";
		}
		
      */
		// Can we show the profiles?
		if ( (isset($OverridePrivacy)) || ($rb_agency_option_privacy > 1 && is_user_logged_in()) || ($rb_agency_option_privacy < 2) ) {
			echo "<div id=\"profile-results\">\n";

			/*********** Paginate **************/
			$items = mysql_num_rows(mysql_query(
			"SELECT profile.ProfileID, customfield_mux.* 
					FROM ". table_agency_profile ." profile 
					INNER JOIN  ".table_agency_customfield_mux." customfield_mux 
					ON 
					profile.ProfileID=customfield_mux.ProfileID ")); // number of total rows in the database
				
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


			/*********** Execute Query **************/
			if (isset($profilefavorite) && !empty($profilefavorite)){  	
			// Execute Query
			$queryList = "SELECT profile.*,profile.ProfileID as pID , fav.SavedFavoriteTalentID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_savedfavorite."  fav  WHERE fav.SavedFavoriteTalentID = profile.ProfileID  AND ProfileIsActive = 1";
			
			}elseif (isset($profilecastingcart) && !empty($profilecastingcart)){  	
				// Execute Query
			$queryList = "SELECT profile.*,profile.ProfileID as pID , cart.CastingCartTalentID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_castingcart."  cart WHERE cart.CastingCartTalentID = profile.ProfileID AND ProfileIsActive = 1";
			
			}else{
			// Execute Query
			$queryList = "SELECT profile.*,profile.ProfileID as pID , customfield_mux.*,  (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_customfield_mux ."  AS customfield_mux ON profile.ProfileID = customfield_mux.ProfileID  $filter  GROUP BY profile.ProfileID ORDER BY $sort $dir  $limit";
			}
			$resultsList = mysql_query($queryList);
			$countList = mysql_num_rows($resultsList);
  #DEBUG!
  #echo $queryList;
                $profileDisplay = 0;
				$countFav = 0;
			while ($dataList = mysql_fetch_array($resultsList)) {
				if ($rb_agency_option_profilelist_favorite) {
				 //Execute query - Favorite Model
				 if(!empty($dataList["pID"])){
			     		$queryFavorite = mysql_query("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE ".rb_agency_get_current_userid()." = fav.SavedFavoriteProfileID AND fav.SavedFavoriteTalentID = '".$dataList["pID"]."' ") or die(mysql_error());
	                 		$dataFavorite = mysql_fetch_assoc($queryFavorite); 
			     		$countFavorite = mysql_num_rows($queryFavorite);
				 }
				}
				if ($rb_agency_option_profilelist_castingcart) {
			      //Execute query - Casting Cart
				 if(!empty($dataList["pID"])){
			    	 $queryCastingCart = mysql_query("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE ".rb_agency_get_current_userid()." = cart.CastingCartProfileID AND cart.CastingCartTalentID = '".$dataList["pID"]."' ") or die(mysql_error());
	                	 $dataCastingCart = mysql_fetch_assoc($queryCastingCart); 
			    	 $countCastingCart = mysql_num_rows($queryCastingCart);
				 }
				}
		
				$profileDisplay++;
				
				if ($profileDisplay == 1 ){
					 
						/*********** Show Count/Pages **************/
						echo "  <div id=\"profile-results-info\">\n";
							if($items > 0) {
								if ((!isset($profilefavorite) && empty($profilefavorite)) && (!isset($profilecastingcart) && empty($profilecastingcart))){ 
									echo "    <div class=\"profile-results-info-countpage\">\n";
											echo $p->show();  // Echo out the list of paging. 
									echo "    </div>\n";
								}
							}
						if ($rb_agency_option_profilelist_count) {
							if ((!isset($profilefavorite) && empty($profilefavorite)) && (!isset($profilecastingcart) && empty($profilecastingcart))){  
								echo "    <div id=\"profile-results-info-countrecord\">\n";
								echo "    	". __("Displaying", rb_agency_TEXTDOMAIN) ." <strong>". $countList ."</strong> ". __("of", rb_agency_TEXTDOMAIN) ." ". $items ." ". __(" records", rb_agency_TEXTDOMAIN) ."\n";
								echo "    </div>\n";
							}
						
						}
					echo "  </div><!-- #profile-results-info -->\n";
		
					echo "  <div id=\"profile-list\">\n";
				}
	           
						
				echo "<div class=\"profile-list-layout". (int)$rb_agency_option_layoutprofilelist ."\">\n";
				echo "  <div class=\"style\"></div>\n";
				if (isset($dataList["ProfileMediaURL"]) ) { // && (file_exists(rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"])) ) {
				echo "  <div class=\"image\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"". rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"] ."\" /></a></div>\n";
				} else {
				echo "  <div class=\"image image-broken\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">No Image</a></div>\n";
				}
				echo "  <div class=\"title\">\n";
				echo "     <h3 class=\"name\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">". stripslashes($dataList["ProfileContactDisplay"]) ."</a></h3>\n";
				if ($rb_agency_option_profilelist_expanddetails) {
				echo "     <div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span></div>\n";
				}
			//if (isset($profilefavorite) && !empty($profilefavorite)){  	
					if ($rb_agency_option_profilelist_favorite) {
						
						if($countFavorite <= 0){
							echo "     <div class=\"favorite\"><a rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$dataList["pID"]."\">Save as Favorite</a></div>\n";
						}else{
							echo "     <div class=\"favorite\"><a rel=\"nofollow\" href=\"javascript:;\" class=\"favorited\" id=\"".$dataList["pID"]."\">Favorited </a></div>\n";
						}
						
					}
			//}
			//if (isset($profilecastingcart) && !empty($profilecastingcart)){  	
				if ($rb_agency_option_profilelist_castingcart) {
					if($countCastingCart <=0){
					echo "<div class=\"castingcart\"><a href=\"javascript:;\" id=\"".$dataList["pID"]."\"  class=\"save_castingcart\">Add To Casting Cart</a></div>";
					}else{
					echo "<div class=\"castingcart\"><a href=\"javascript:;\" id=\"".$dataList["pID"]."\"  class=\"saved_castingcart\">Added To Casting Cart <a href=\"".get_bloginfo("wpurl")."/profile-casting-cart/\" style=\"font-size:12px;float:right;\" class=\"view_all_castingcart\"><strong>View</strong></a></div>";
					  }
				}
			//}
			
				echo "  </div>\n";
				
				echo "</div>\n";
						
						
				//$resultsList, $queryFavorite,  $queryCastingCart
					if ($rb_agency_option_profilelist_favorite) {
					mysql_free_result($queryFavorite);
					}
					if ($rb_agency_option_profilelist_castingcart) {
					mysql_free_result($queryCastingCart);
					}
				}
						
				
				if ($countList < 1) {
							echo __("No Profiles Found", rb_agency_TEXTDOMAIN);
				}
			
				echo "  <div style=\"clear: both; \"></div>\n";
				echo "  </div><!-- #profile-list -->\n";
				echo "</div><!-- #profile-results -->\n";
				mysql_free_result($resultsList);
			}  
		else {
			include("theme/include-login.php"); 	
		}
	      
	
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
		$queryList = "SELECT profile.*,(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile WHERE profile.ProfileIsActive = 1 $sql  ORDER BY RAND() LIMIT 0,$count";
		$resultsList = mysql_query($queryList);
		$countList = mysql_num_rows($resultsList);
		while ($dataList = mysql_fetch_array($resultsList)) {

			echo "<div class=\"profile-list-layout". (int)$rb_agency_option_layoutprofilelist ."\">\n";
			echo "  <div class=\"style\"></div>\n";
			if (isset($dataList["ProfileMediaURL"]) ) { // && (file_exists(rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"])) ) {
			echo "  <div class=\"image\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"". rb_agency_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"] ."\" /></a></div>\n";
			} else {
			echo "  <div class=\"image image-broken\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">No Image</a></div>\n";
			}
			echo "  <div class=\"title\">\n";
								if ($rb_agency_option_profilenaming == 0) {
									$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". $dataList["ProfileContactNameLast"];
								} elseif ($rb_agency_option_profilenaming == 1) {
									$ProfileContactDisplay = $dataList["ProfileContactNameFirst"] . " ". substr($dataList["ProfileContactNameLast"], 0, 1);
								} elseif ($rb_agency_option_profilenaming == 2) {
									$ProfileContactDisplay = $dataList["ProfileContactNameFirst"];
								} elseif ($rb_agency_option_profilenaming == 3) {
									$ProfileContactDisplay = "ID ". $ProfileID;
								}
			echo "     <h3 class=\"name\"><a href=\"". rb_agency_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">". $ActiveLanguage. $ProfileContactDisplay ."</a></h3>\n";
			if ($rb_agency_option_profilelist_expanddetails) {
			echo "     <div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span><span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span></div>\n";
			}
			if ($rb_agency_option_profilelist_favorite) {
			 //Execute query - Favorite Model
	             $queryFavorite = mysql_query("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE ".rb_agency_get_current_userid()." = fav.SavedFavoriteProfileID AND fav.SavedFavoriteTalentID = ".$dataList["ProfileID"]." ");
	             $dataFavorite = mysql_fetch_assoc($queryFavorite); 
			$countFavorite = mysql_num_rows($queryFavorite);
				 
			
			   if($countFavorite <= 0){
						echo "     <div class=\"favorite\"><a rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$dataList["ProfileID"]."\"><div class=\"favorite-box\"></div>Save as Favorite</a></div>\n";
					}else{
						echo "     <div class=\"favorite\"><a rel=\"nofollow\" href=\"javascript:;\" class=\"favorited\" id=\"".$dataList["ProfileID"]."\"><div class=\"favorite-box\"></div>Favorited <a href=\"".$rb_agency_WPURL."/profile-favorite/\"  style=\"font-size:12px;float:right;\" class=\"view_all_favorite\"><strong>View all favorites</strong></a></a></div>\n";
					}
			}
			
			if ($rb_agency_option_profilelist_castingcart) {
			//Execute query - Casting Cart
			 $queryCastingCart = mysql_query("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE ".rb_agency_get_current_userid()." = cart.CastingCartProfileID AND cart.CastingCartTalentID = ".$dataList["ProfileID"]." ");
	             $dataCastingCart = mysql_fetch_assoc($queryCastingCart); 
			 $countCastingCart = mysql_num_rows($queryCastingCart);
				
				
			
					if($countCastingCart <=0){
						echo "<div class=\"castingcart\"><a href=\"javascript:;\" id=\"".$dataList["ProfileID"]."\"  class=\"save_castingcart\">Add To Casting Cart</a></div>";
					}else{
						echo "<div class=\"castingcart\"><a href=\"javascript:;\" id=\"".$dataList["ProfileID"]."\"  class=\"saved_castingcart\">Added To Casting Cart <a href=\"".get_bloginfo("wpurl")."/profile-casting-cart/\" style=\"font-size:12px;float:right;\" class=\"view_all_castingcart\"><strong>View</strong></a></div>";
					}
				}
			// END Favorite
			echo "  </div>\n";
		
			echo "</div>\n";
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
			echo "<div id=\"profile-search-form-embed\">\n";
				include("theme/include-profile-search.php"); 	
			echo "</div>\n";
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
		var $className = "pagination";
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
								else{
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

function rb_custom_fields($visibility = 0, $ProfileID, $ProfileGender, $ProfileGenderShow = false){
				
	$query4 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderTitle='".$ProfileGender."' ORDER BY GenderID";
	$results4 = mysql_query($query4);
	$dataList4 = mysql_fetch_assoc($results4); 
								
	$query3 = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = ".$visibility."  ORDER BY ProfileCustomOrder";
	$results3 = mysql_query($query3) or die(mysql_error());
	$count3 = mysql_num_rows($results3);
	
	while ($data3 = mysql_fetch_assoc($results3)) {
		 if($ProfileGenderShow ==true){
			if($data3["ProfileCustomShowGender"] == $dataList4["GenderID"] && $count3 >=1 ){ // Depends on Current LoggedIn User's Gender
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
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype  			= $rb_agency_options_arr['rb_agency_option_unittype'];
		$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
		$rb_agency_option_locationtimezone 		= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
	
	if(!empty($data3['ProfileCustomID']) || $data3['ProfileCustomID'] !=""){ 
   
		$subresult = mysql_query("SELECT ProfileID,ProfileCustomValue,ProfileCustomID FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = '". $data3['ProfileCustomID'] ."' AND ProfileID = ". $ProfileID);
		$row = @mysql_fetch_assoc($subresult);
		
		$ProfileCustomValue = $row["ProfileCustomValue"];
		$ProfileCustomTitle = $data3['ProfileCustomTitle'];
		$ProfileCustomType  = $data3['ProfileCustomType'];
			
		  if (strtolower($ProfileCustomTitle) == "height") {
			  // Metric or Imperial?
			  if ($rb_agency_option_unittype == 1) {
				echo "    <tr valign=\"top\">\n";
				echo "        <th scope=\"row\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("In Inches", rb_agency_TEXTDOMAIN) .")</em></th>\n";
				echo "        <td><select  name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\"  id=\"ProfileStatHeight\">\n";
								if (empty($ProfileCustomValue)) {
				echo " 				<option value=\"\" selected>--</option>\n";
								}
								
								$i=36;
								$heightraw = 0;
								$heightfeet = 0;
								$heightinch = 0;
								while ($i<=90) { 
								  $heightraw = $i;
								  $heightfeet = floor($heightraw/12);
								  $heightinch = $heightraw - floor($heightfeet*12);
				echo " 				<option value=\"". $i ."\" ". selected($ProfileStatHeight, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
								  $i++;
								}
				echo " 			</select>\n";
				echo "        </td>\n";
				echo "    </tr>\n";
			} else {
				echo "    <tr valign=\"top\">\n";
				echo "        <th scope=\"row\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("cm", rb_agency_TEXTDOMAIN) .")</em></th>\n";
				echo "        <td>\n";
				echo "			<input type=\"text\" id=\"ProfileStatHeight\"  name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\"  value=\"". $ProfileCustomValue ."\" />\n";
				echo "        </td>\n";
				echo "    </tr>\n";
			}
	} elseif(strtolower($ProfileCustomTitle) == "weight") {
				echo "    <tr valign=\"top\">\n";
				echo "        <th scope=\"row\">". __("Weight", rb_agency_TEXTDOMAIN) ." \n";
							  if ($rb_agency_option_unittype == 1) { echo "<em>(". __("In Pounds", rb_agency_TEXTDOMAIN) .")</em>"; } else { echo "<em>(". __("In Kilo", rb_agency_TEXTDOMAIN) .")</em></th>\n"; }
				echo "        </th>\n";
				echo "        <td>\n";
				echo "			<input type=\"text\" id=\"ProfileStatWeight\"  name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\"  value=\"". $ProfileCustomValue ."\" />\n";
				echo "        </td>\n";
				echo "    </tr>\n";
	
	 } else {
			  
		echo "  <tr valign=\"top\">\n";
		echo "    <th scope=\"row\">". $data3['ProfileCustomTitle'] ."</th>\n";
		echo "    <td>\n";		  
		  
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
				
				echo "<label>".$data[0]."</label>";
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
					echo "<label>".$data2[0]."</label>";
				
						$pos2 = 0;
						echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
						echo "<option value=\"\">--</option>";
						foreach($data2 as $val2){
								if($val2 != end($data2) && $val2 !=  $data2[0]){
									 if ($val2 == $ProfileCustomValue ) {
										$isSelected = "selected=\"selected\"";
										echo "<option value=\"".$val2."\" ".$isSelected .">".$val2."</option>";
									 } else {
										echo "<option value=\"".$val2."\" >".$val2."</option>";
									 }
								}
							}
						echo "</select>\n";
				}
			} elseif ($ProfileCustomType == 4) {
				
				echo "<textarea style=\"width: 100%; min-height: 300px;\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">". $ProfileCustomValue ."</textarea>";
				
			} elseif ($ProfileCustomType == 5) {
				
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				echo "<div style=\"width:300px;float:left;\">";
				foreach($array_customOptions_values as $val){
					 echo "<label><input type=\"checkbox\" value=\"". $val."\"  name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					 echo "". $val."</label>";
				}
				echo "</div>";
				   
			} elseif ($ProfileCustomType == 6) {
				
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				foreach($array_customOptions_values as $val){
					 echo "<input type=\"radio\" value=\"". $val."\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					 echo "<span style=\"color:white;\">". $val."</span><br/>";
				}
			}
									
	 }
			
	} // End if Empty ProfileCustomID
}
?>