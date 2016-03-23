<?php
	
	/* 
	 * Debug Mode - $RB_DEBUG_MODE = true;
	 */


	/*
	 * Set Sessions
	 */

		if(!function_exists("rb_agency_init_sessions")){
			add_action('init', 'rb_agency_init_sessions');
				function rb_agency_init_sessions() {
					if (!session_id()) {
						session_start();
					}
				}
		}


	/*
	 * Set Mail
	 */

		//add_filter('wp_mail_content_type','rb_agency_set_content_type');
		function rb_agency_set_content_type($content_type){
			return 'text/html';
		}


	/*
	 * Remove header already sent
	 */

		if(!function_exists("rb_output_buffer")){

			function rb_output_buffer() {
				ob_start();
			}// soi_output_buffer
			add_action('init', 'rb_output_buffer');
		}


	/*
	 * Add Rewrite Rules based on Path
	 */

		// Todo: Remove lines below. Causes permalink incompatibility with other plugins such as woocommerce
		// Triggers on plugin activation
		function rbflush_rules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}


	/*
	 * Adding a new rule
	 */

		add_filter('rewrite_rules_array','rb_agency_rewriteRules');
			function rb_agency_rewriteRules($rules) {
				$newrules = array();
				$newrules['profile-search'] = 'index.php?type=search-basic'; // Cannot remove this route.
				$newrules['search-basic'] = 'index.php?type=search-basic';
				$newrules['search-advanced'] = 'index.php?type=search-advanced';
				$newrules['search-results/(.*)$'] = 'index.php?type=search-result&paging=$matches[1]';
				$newrules['search-results'] = 'index.php?type=search-result';
				$newrules['profile-category/(.*)/([0-9])$'] = 'index.php?type=category&target=$matches[1]&paging=$matches[2]';
				$newrules['profile-category/([0-9])$'] = 'index.php?type=category&paging=$matches[1]';
				$newrules['profile-category/(.*)'] = 'index.php?type=category&target=$matches[1]';
				$newrules['profile-category'] = 'index.php?type=category&target=all';
				$newrules['profile-casting/jobs/(.*)/(.*)$'] = 'index.php?type=castingjobs&target=$matches[1]&value=$matches[2]';
				$newrules['profile-casting/jobs/(.*)$'] = 'index.php?type=castingjobs&target=$matches[1]';
				
				$newrules['profile-print'] = 'index.php?type=print';
				$newrules['profile-pdf'] = 'index.php?type=rb-pdf';
				$newrules['profile-email'] = 'index.php?type=email';
				$newrules['client-view/(.*)$'] = 'index.php?type=profilecastingcart&target=$matches[1]';
				$newrules['profile/(.*)/contact'] = 'index.php?type=profilecontact&target=$matches[1]';
				$newrules['profile/(.*)/cardphotos'] = 'index.php?type=cardphotos&target=$matches[1]';
				$newrules['profile/(.*)$'] = 'index.php?type=profile&target=$matches[1]';
				$newrules['get-state/(.*)$'] = 'index.php?type=getstate&country=$matches[1]';
				$newrules['version-rb-agency'] = 'index.php?type=version';
				$newrules['rb-agency-version'] = 'index.php?type=version';
				$newrules['profile-favorite'] = 'index.php?type=favorite';
				$newrules['model-polaroid/profile/(.*)'] = 'index.php?type=modelpolaroid&target=$matches[1]';
				$newrules['logout'] = 'index.php?type=rblogout';

				
				return $newrules + $rules;
			}


	/*
	 * Get Variables & Identify View Type
	 */

		add_action( 'query_vars', 'rb_agency_query_vars' );
			function rb_agency_query_vars( $query_vars ) {
				$query_vars[] = 'type';
				$query_vars[] = 'target';
				$query_vars[] = 'value';
				$query_vars[] = 'country';
				// pagination
				$query_vars[] = 'paging';
				return $query_vars;
			}


	/*
	 * Get Veriables through filter
	 */

		add_filter( 'query_vars', 'rb_agency_filter_query_vars', 10, 1  );
			function rb_agency_filter_query_vars( $query_vars ) {
				$query_vars[] = 'paging';
				$query_vars[] = 'gender';
				$query_vars[] = 'age_start';
				$query_vars[] = 'age_stop';
				$query_vars[] = 'ref';
				$query_vars[] = 'jID';
				
				return $query_vars;
			}

	/*
	 * Set Custom Template
	 */

		add_filter('template_include', 'rb_agency_template_include', 1, 1);
			function rb_agency_template_include( $template ) {
				if ( get_query_var( 'type' ) ) {
					//echo get_query_var( 'type' );

					if (get_query_var( 'type' ) == "search-basic" || 
						get_query_var( 'type' ) == "search-result" || 
						get_query_var( 'type' ) == "search-advanced" ) {

						// Public Profile Search
						return RBAGENCY_PLUGIN_DIR . 'view/profile-search.php';

					} elseif (get_query_var( 'type' ) == "profilecastingcart") {
					// Casting cart
						return RBAGENCY_PLUGIN_DIR . 'view/profile-admincart.php';

					} elseif (get_query_var( 'type' ) == "castingjobs") {
					// Casting cart
						return RBAGENCY_PLUGIN_DIR . 'view/profile-castingjobs.php';

					} elseif (get_query_var( 'type' ) == "category") {
					// Category View
						return dirname(__FILE__) . '/view/profile-category.php';

					} elseif (get_query_var( 'type' ) == "profile") {
					// Profile View
						return RBAGENCY_PLUGIN_DIR . 'view/profile-view.php';

					} elseif (get_query_var( 'type' ) == "profilecontact") {
					// Profile Contact Form
						return dirname(__FILE__) . '/view/profile-contact.php';
					
					} elseif (get_query_var( 'type' ) == "cardphotos") {
					// Profile Card Photos
						return dirname(__FILE__) . '/view/profile-cardphotos.php';
					
					} elseif (get_query_var( 'type' ) == "rb-pdf") {
					// Download Profiles PDF
						return dirname(__FILE__) . '/view/profile-pdf.php';

					} elseif (get_query_var( 'type' ) == "print") {
					// Print Mode: TODO REFACTOR
						return dirname(__FILE__) . '/view/profile-print.php';

					} elseif (get_query_var( 'type' ) == "version") {
					// Have a dedicated route to ping latest version
						return dirname(__FILE__) . '/version.php'; 

					} elseif (get_query_var( 'type' ) == "getstate") {
					// TODO: Remove this, get states through wp-ajax url.
						return RBAGENCY_PLUGIN_DIR . '/view/partial/get-state.php'; 

					} elseif (get_query_var( 'type' ) == "rblogout") {
					// TODO: Custom /logout/ uri to catch the user and redirect to a login form.
						rb_logout_user();

					//model polaroid
					} elseif(get_query_var('type') == 'modelpolaroid'){
						return RBAGENCY_PLUGIN_DIR . 'view/model-polaroid.php'; 
					} 
				}
				return $template;
			}


	/*************************************************************************************************** //
	 * Errors & Alerts
	 */

	// Create Message Wrapper
	function rb_agency_adminmessage_former($message, $errormsg = false) {
		if ($errormsg) {
			echo '<div id="message" class="error">';
		} else {
			echo '<div id="message" class="updated fade">';
		}
		echo "<p><strong>$message</strong></p></div>";
	}

	/** 
		 * Call rb_agency_adminmessage() when showing other admin 
		 * messages. The message only gets shown in the admin
		 * area, but not on the frontend of your WordPress site. 
		 */
	add_action('admin_notices', 'rb_agency_adminmessage'); 
		function rb_agency_adminmessage() {

			// Are Permalinks Enabled?
			if ( get_option('permalink_structure') == '' ) {
				rb_agency_adminmessage_former('<a href="'. admin_url("options-permalink.php") .'">'. __("Permalinks", RBAGENCY_TEXTDOMAIN) .'</a> '. __("are not configured.  This will cause RB Agency not to function properly.", RBAGENCY_TEXTDOMAIN), true);
			}

		}


	/*************************************************************************************************** //
	 *  General Functions
	 */

	/**
	 * Create the directory (if exists, create new name)
	 *
	 * @param string $ProfileGallery
	 */
	function rb_agency_createdir($ProfileGallery, $Force_create = true){

		if (!is_dir(RBAGENCY_UPLOADPATH . $ProfileGallery)) {
			mkdir(RBAGENCY_UPLOADPATH . $ProfileGallery, 0755);
			chmod(RBAGENCY_UPLOADPATH . $ProfileGallery, 0777);
		} else {
			if($Force_create){
				$finished = false;
				$pos = 0;         // we're not finished yet (we just started)
				while ( ! $finished ):                   // while not finished
					$pos++;
					$NewProfileGallery = $ProfileGallery ."-".$pos; // output folder name
					if ( ! is_dir(RBAGENCY_UPLOADPATH . $NewProfileGallery) ): // if folder DOES NOT exist...
						mkdir(RBAGENCY_UPLOADPATH . $NewProfileGallery, 0755);
						chmod(RBAGENCY_UPLOADPATH . $NewProfileGallery, 0777);
						$ProfileGallery = $NewProfileGallery;// Set it to the new  thing
						$finished = true;            // ...we are finished
					endif;
				endwhile;
			}
		}
		return $ProfileGallery;
	}

	/**
	 * Check directory, if doesnt exist, create, if exists, skip
	 *
	 * @param string $ProfileGallery
	 */
	function rb_agency_checkdir($ProfileGallery){
		if (!is_dir(RBAGENCY_UPLOADPATH . $ProfileGallery)) {
			mkdir(RBAGENCY_UPLOADPATH . $ProfileGallery, 0755);
			chmod(RBAGENCY_UPLOADPATH . $ProfileGallery, 0777);
			// defensive return
			return $ProfileGallery;
		} else {
			//defensive return
			return $ProfileGallery;
		}
	}


	/**
	 * Get Profile Name
	 *
	 * @param id $ProfileID
	 */

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


		
	/**
	 * Hook Current Langauge
	 *
	 */

	add_filter( 'locale', 'rb_language_init' );
	if(!function_exists('rb_language_init')){
		function rb_language_init( $locale ){
			$lang = '';
			if( isset( $_COOKIE['rb_language'])){
				$lang = $_COOKIE['rb_language'];
			}else{
				$lang = $locale;
			}
			
			if( isset( $_GET['lang'])){
				$lang = $_GET['lang'];
				
				
			}
			if(is_admin()){
				return $locale;
			}
			return $lang;
		}
	}

	add_action( 'wp_footer', 'rb_language_foot');
	if(!function_exists('rb_language_foot')){
		function rb_language_foot(){
			echo '
			<script>
				function rb_setLanguage(lang_set){
					rb_setCookie("rb_language",lang_set,7);
					window.location.reload(false); 
					return false;
				}
				function rb_setCookie(cname, cvalue, exdays) {
					var d = new Date();
					d.setTime(d.getTime() + (exdays*24*60*60*1000));
					var expires = "expires="+d.toUTCString();
					document.cookie = cname + "=" + cvalue + "; " + expires;
				}
			</script>
			';
		}
	}
	
	if(!function_exists('rb_language_userflag')){
		function rb_language_userflag($title = 'Language: ',$_glue = ' | ',$_size=20, $_echo = true){
			
			$_lang_set = array(
				'en_GB' => array(
					'English',
					'data:image/gif;base64,R0lGODlhLgAhAOYAAOAEG+AGHCYmbwkJXAwMXjQ0eVNTjUREg2FhlulNXehCU+QnOt8KEvCJjz09fvKTnvSlrephZuQ0O/vb3/zp6+tcah4eauY9SYmJsQoKXeAFHGdnmvOfqG1tnltbknZ2pOAFG3Jyom1tn3V1pCwtdHFyoS0tdC0sdOENI3Jyof39/fOep3JxoVtakvf39/b29vHx8eEMIeINIiwsdN4TJ9wNIuEMItUEGtUFG9cGHPGPmHd2pEdHhUhIhvzr7fvY3DQzeScncPzl6O/v72BglkFBguZARkdHhOdHUudKVIaGr0VEg0pKh/SpsUBAgOIdJfWutSEhbPKSnepSYfB/iuENIj09fzc3emlpm+1qd3x8qOU2SN0PJPDw8PKXoVlZkIKCrd0QJBoaaHFxoTMzeDExduxwdDo6fOU9Q+QuNSkpcnBwoB0davz8/FBQi/vd4eELIdICF+c/UOUxPnZ3pCMjbeEHHUxMiOIPJdECF+ACGf///wAAAAAAAAAAAAAAACH5BAAAAAAALAAAAAAuACEAAAf/gHeCg4SFhoQJiYqLjI1MGHsYkpGTkpCWlJcYDZydnp+fST1KHx8jpx90pjulra0jrxyys7MrtLQrTVRHYCEhe2Mpv74peywsJXshKckhJc970dLT1NVLWiId2h3Z3NvZ3dvcItXl5kUfexvr6uzrG+3w7+0Q9fb3+PdQOk5rCP8AAwoc+C+CwYMIEyI0Y8QBFg8e9kCM2AKixIoSLU70UKGjx48gP2aRc4aIgZMoU6pceZKCy5cwY8L0IeTKlz2DcArSeYenz53mgkoj4+aA0aNIkyo1OqGp06dQn775UYaHAwd7rmLVmvVq161aL4gdS7YsWSRzTFgpwLat27dw/9lKmEu3rt26aNKoAbKHBIkZe06YONHXBIm+gvfMGIz4gePHkCND9iIliIDLmDNr3oxZqOc6FkLvCW1htGjSpkuTLu05aBQCsGPLnk07toLbuHPr1j2FTYY9GYIDFx78d/HhxjMwWM68uXPnT8QMmE69uvXr1Bdo3869e/ctKlqLH09NRRvy6MWroCHDjvv38OPLn09fvgwaXKpoCBBAg//+/AXIn3/7CVhggQP2tx+BA8IRRg0xgADAhACAYGGFFGY4oYQZSughhxuGOGEMNbiQ3olBufACiixS80IONugh44w01mjjjTjaaEMOOKCQ449ABokCDjf4GOSRSMqIwigNMLTo5B4wdPFki0PkgceVWGap5ZZcdtllHHmEKeaYZJZp5plo5hEIADs='
				),
				'pt_BR' => array(
					'Español',
					'data:image/gif;base64,R0lGODlhLgAhAOYAAMTLzXzCnoLEo5inlNsXHVuyhuuChdTk0eLl2BmUVaF6U2R1XMbZux6WWSGXW90hJ+NGS3CNbTGfZzmibPHRz7ndzYpZGIRnKGWmQ5m/iGK1i02re+6Tlevz6+A5PeZbX9C0q8Oai5xqOJNyR3NKC9weJLeqmoibi8PKqup6fbK8lswVGqeSaP35+dCvmtwcIReSVIleKHSkZ+VUWXS+mPfv7RWITep0eEapdlSvgb3LuuRNUdwaIOE/ROt+gelvctMaIPfy7t8yNxiNUUKmc9wrMI1tMVCufs7Fs+RRVvf69+7u7pTNsOZiZuhobW+8lGi5kD+lcW2lxKeLXWSUWM4hJqNrPX6JioRhHqRWDdvPvujBu+hnatPErkiKM93VyI6CXouLZedeY5W0ajlbKJJnMJF2ZI6xwseZfI10Pqq1oW67lOdkae7697OfdObu4u7u5Lp+XqSvmqW1iKiynKCMYo/LraCXfbeJaKnBq6XNt7PPqvf089sWHBaSU////yH5BAAAAAAALAAAAAAuACEAAAf/gAWCg4SFf4eIiYp/bB+Oj5CQBUyUlZaWi5mIHJydnp4fBXYCpKWmpZqaBqusrawcHzl2AbS1trWpmT67vL28HDM5ArfEtLmLKcnKy8oGM0cCNNLT1NPHijfZ2tvaBklHAU/i4+Tj14k/6err6j5JGzRQ8vP08+eITvn6+/o3OxtrNAgcSHDgvUNNEipcqPDHDhxQCkkUdPBPpIuOuEDAoSGHx48gP2pqkWmGyZMoT4qBQCTHhpcwY8JcxMdFHDQUFO3YybMnzxk9ohzBQbSo0aKI2hwAMUKBCCNlXCSCQLWq1ao7guKIwrWr165J8+BREKOMkQsxtiDqwbat27YQ/3pMmEu3rt1DfIJ0oEP2ApYYFqzUOOShsOHDiCdIWMy4ceNDQXToQSDCAhYjMbBg0XJIiOfPoEF7kOCgtOnTp/90aFFhQJwRJEjUQTIizZcOSh7o3s2btxAJDYILHz78QIYtISqEsBKbhBskLBDIOFCiuvXr138T3y4cDooaC06A0NJlihEsLL4gUNECu3vrRUhz3/4nAwMEERYsiHAHjBozFMiQwR/vufdAEQ40kMCCwiUQnIML/oHAHAgAIMUAYZARQQRyTNjBHy+EKOKIIpaAoAMLpqiiiocggIEOZyyQhQVXAICCFwgcwsOOPPbIYwkPJLjikBEecoAKY2RwwpoJMlDBwBuI+CjljkA+SOSKiSjBAAMmmLAHA4oQIOaYZI5ZnYMwpKnmmmpWVOabYpYABAwNsGlnmm7CWaacQzTgx5+ABgpoRX0UauihhvLpgKCM/kkoopD28QAQQyzaqKCPRnroAyvYYOmlgx6kKaKcegpqoJmOKmmnqLVqWkW9xbpbpzbUauutty6h66689rpEFSsEK+ywwwYCADs='
				),
				
			);
				
			$_echoLanguage = array();
			foreach($_lang_set as $_key => $_val){
				$_echoLanguage[] = 
				'<a href="#'.$_key.'" onclick="javascript:rb_setLanguage(\''.$_key.'\'); return false;" title="'.$_val[0].'"><img style="height:'.$_size.'px" src="'.$_val[1].'"></a>';
			}
			if($_echo == true)
				echo $title .implode($_glue, $_echoLanguage);
			return $title .implode($_glue, $_echoLanguage);
		}
	}
	 
	 
	/**
	 * Identify Current Langauge
	 *
	 */

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


	/**
	 * Get users role
	 *
	 */
	function rb_agency_get_userrole() {
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	};

	/**
	 * Convert Date & time to UnixTimestamp
	 *
	 * @param string $datetime
	 */
	function rb_agency_convertdatetime($datetime) {
		// Convert
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hours, $minutes, $seconds) = explode(':', $time);

		$UnixTimestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
		return $UnixTimestamp;
	}

	/**
	 * Convert Date & Time to Strings
	 *
	 * @param string $timestamp (unix timestamp)
	 * @param string $offset  (offset from server)
	 */
	function rb_agency_makeago($timestamp, $offset = null){
		// Ensure the Timestamp is not null
		if (isset($timestamp) && !empty($timestamp) && ($timestamp <> "0000-00-00 00:00:00") && ($timestamp <> "943920000")) {
			
			
			/* if(function_exists('rb_days_diff')){
				//from rb-interact
				$dat_up = date("Y-m-d H:i:s", strtotime( $timestamp ));
				$time_ago = rb_days_diff($dat_up ,'',true);
				
				print_r($time_ago);
				return 'wow'.$time_ago->day;
			} */
				
			// Offset Math
			$timezone_offset = (int)$offset;
			$time_altered =  strtotime("now") -  ($timezone_offset *60 *60);
			$difference = $time_altered - (int)$timestamp;

			// Prepare Text
			// TODO: Add multi lingual
			$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
			$lengths = array("60","60","24","7","4.35","12","10");

			// Logic
			for($j = 0; $difference >= $lengths[$j]; $j++)
				$difference /= $lengths[$j];
				$difference = round($difference);
				if($difference != 1) $periods[$j].= "s";
				$text = $difference." ".__($periods[$j] ." ago",RBAGENCY_TEXTDOMAIN);
				if ($j > 10) {exit; }

				return $text;

		} else {
			// If timestamp is blank, return non value
			return "--";
		}
	}

	
	
	
	/**
	 * Get Profile's Age
	 *
	 * @param string $p_strDate
	 */
	function rb_agency_get_age($p_strDate, $attrs = array() ) {
	//Get Age Option if it should display with months included

		if($p_strDate == "0000-00-00"){
			return "";
		}

			$sc = shortcode_atts( array(
				'show_age_year' => false,
				'show_age_month' => false,
				'show_age_day' => false,
				'email_month' => true,
			    'email_day' => true,
			    'email_year' => true,
				), $attrs );

		$rb_agency_options_arr = get_option('rb_agency_options');
		//$detail_year = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_year'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_year']:0;
		//$detail_month = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_month'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_month']:0;
		//$detail_day = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_day'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_day']:0;

		$detail_year_op = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_year'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_year']:0;
		$detail_month_op = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_month'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_month']:0;
		$detail_day_op = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_day'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_day']:0;

		if(!empty($sc['show_age_year'])){
			$detail_year = $sc['show_age_year'] == "true" ? 1 : 0;
		} else {
			$detail_year = $detail_year_op;
		}
		if(!empty($sc['show_age_month'])){
			$detail_month = $sc['show_age_month'] == "true" ? 1 : 0;
		} else {
			$detail_month = $detail_month_op;
		}
		if(!empty($sc['show_age_day'])){
			$detail_day = $sc['show_age_day'] == "true" ? 1 : 0;
		} else {
			$detail_day = $detail_day_op;
		}

		if ((isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails']) && $rb_agency_options_arr['rb_agency_option_profilelist_expanddetails'] == true) || is_admin()) {

			
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
				$age = date_diff(date_create($p_strDate), date_create('now'));
				
				$years = $age->y;
				$months = $age->m;
				$days = $age->d;
				
			}else{
				
				global $wpdb;
				$age_year = $wpdb->get_var("SELECT TIMESTAMPDIFF(YEAR, '{$p_strDate}', now()) AS age");
				$age_month = $wpdb->get_var("SELECT TIMESTAMPDIFF(MONTH, '{$p_strDate}', now()) % 12 age");
				$age_day = $wpdb->get_var("SELECT FLOOR(TIMESTAMPDIFF(DAY, '{$p_strDate}', now())% 30.4375) AS age");
				
				$years = $age_year;
				$months = $age_month;
				$days = $age_day;
				
			}
			
			
			$label_y = "";
			$label_m = "";
			$label_d = "";
		
			if($detail_year == 1 || is_admin() && $sc['email_year']==true){
				if($years == 0){
					$years = "";
				} else {
					$label_y = "<span ".$attrs['year_style'].">" . $years . " yr(s)</span>";
				}
			}
			if($detail_month == 1|| is_admin() && $sc['email_month']==true){

				if($months == 0){
					$label_m = "";
				} else {
					$label_m = "<span ".$attrs['month_style'].">" .(($months<12)?$months:11) . " mo(s)</span>";
				}
			}
			if($detail_day == 1|| is_admin() && $sc['email_day']==true){
				if($days == 0){
					$label_d = "";
				}else{
					$label_d = "<span ".$attrs['day_style'].">" . (($days<31)?$days:30) ." day(s)</span>";
				}
			}
			
			return implode(" ",array($label_y,$label_m,$label_d));
			/* 
		
			@list($Y,$m,$d) = @explode("-",$p_strDate);
			$dob = "$d-$m-$Y";
			$localtime = getdate();
			$today = $localtime['mday']."-".$localtime['mon']."-".$localtime['year'];
			$dob_a = explode("-", $dob);
			$today_a = explode("-", $today);
			$dob_d = $dob_a[0];$dob_m = $dob_a[1];$dob_y = $dob_a[2];
			$today_d = $today_a[0];$today_m = $today_a[1];$today_y = $today_a[2];
			$years = $today_y - $dob_y;
			$months = $today_m - $dob_m;
			$days = $today_d - $dob_d;

			if ($today_m.$today_d < $dob_m.$dob_d) {
				$years--;
				$months = 12 + $today_m - $dob_m;
			}
			if ($today_d < $dob_d) {
				$months--;
			}
			if($days < 0){
				$_day = $today_d + 30;
				$days =  $_day - ($today_d - $days);
			}

			$firstMonths=array(1,3,5,7,8,10,12);
			$secondMonths=array(4,6,9,11);
			$thirdMonths=array(2);

			if($today_m - $dob_m == 1) {
				if(in_array($dob_m, $firstMonths)){
					array_push($firstMonths, 0);
				} elseif(in_array($dob_m, $secondMonths)){
					array_push($secondMonths, 0);
				} elseif(in_array($dob_m, $thirdMonths)){
					array_push($thirdMonths, 0);
				}
			}
			$label_y = "";
			$label_m = "";
			$label_d = "";

			if($months >= 12 && $detail_month == 1){
				$months = $months - 12;
				$years++;
			}
			if($detail_year == 1 || is_admin() && $sc['email_year']==true){
				if($years == 0){
					$years = "";
				} else {

						$label_y = "<span ".$attrs['year_style'].">" . $years . " yr(s)</span>";
				}
			}
			if($detail_month == 1|| is_admin() && $sc['email_month']==true){

				if($months == 0){
					$label_m = "";
				} else {
					$label_m = "<span ".$attrs['month_style'].">" .(($months<12)?$months:11) . " mo(s)</span>";
				}
			}
			if($detail_day == 1|| is_admin() && $sc['email_day']==true){
				$label_d = "<span ".$attrs['day_style'].">" . (($days<31)?$days:30)." day(s)</span>";
			}

			return  implode(" ",array($label_y,$label_m,$label_d)); */

		// Or just do it the old way
		} else {
			list($Y,$m,$d) = explode("-",$p_strDate);
			return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
		}

		if(empty($p_strDate) || $p_strDate == "0000-00-00"){
			return '-';
		}
	}


	/**
	 * Get Current User ID
	 *
	 */
	function rb_agency_get_current_userid(){
		global $current_user;
		get_currentuserinfo();
		return $current_user->ID;
	}

	/**
	 * Get Filename Extension
	 *
	 * @param string $filename
	 */
	function rb_agency_filenameextension($filename) {
		$pos = strrpos($filename, '.');
		if($pos===false) {
			return false;
		} else {
			return substr($filename, $pos+1);
		}
	}


	/**
	 * Generate Video Thumbnail
	 *
	 * @param string $videoID
	 */
	function rb_agency_get_videothumbnail($url, $host) {
	/*
	TODO: Cleanup
		$videoID = ltrim($videoID);
		if (substr($videoID, 0, 23) == "http://www.youtube.com/") {
			$videoID = rb_agency_get_VideoID($videoID);
		} elseif (substr($videoID, 0, 7) == "<object") {
			$videoID = rb_agency_get_VideoFromObject($videoID);
		}
		$rb_agency_get_videothumbnail = "<img src='http://img.youtube.com/vi/" . $videoID . "/default.jpg' />";
		return $rb_agency_get_videothumbnail;
	 */

		$image_url = parse_url($url);
		if(isset($image_url['host']) && ($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com')){
			$array = explode("&", $image_url['query']);
			return "<img src=\"http://img.youtube.com/vi/".substr($array[0], 2)."/default.jpg\"/>";
		} elseif(isset($image_url['host']) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')){
			$is_host_active = @file_get_contents("http://vimeo.com/api/v2/video/".substr($image_url['path'], 1).".php");

			if(!empty($is_host_active)){
				$hash = unserialize($is_host_active);
				return "<img src=\"".$hash[0]["thumbnail_medium"]."\" width=\"120\" height=\"90\"/>";
			} else {
				return "<img src=\"". RBAGENCY_PLUGIN_URL ."assets/img/video-thumbnail.png\" width=\"120\" height=\"90\"/>";
			}

		} else {
			if ($host == "youtube") {
				return "<img src=\"http://img.youtube.com/vi/".$url."/default.jpg\"/>";
			} elseif ($host == "vimeo") {
				$is_host_active = @file_get_contents("http://vimeo.com/api/v2/video/".$url.".php");

				if(!empty($is_host_active)){
					$hash = unserialize($is_host_active);
					return "<img src=\"".$hash[0]["thumbnail_medium"]."\" width=\"120\" height=\"90\"/>";
				} else {
					return "<img src=\"". RBAGENCY_PLUGIN_URL ."assets/img/video-thumbnail.png\" width=\"120\" height=\"90\"/>";
				}
			} else {
				return "<img src=\"". RBAGENCY_PLUGIN_URL ."assets/img/video-thumbnail.png\" width=\"120\" height=\"90\"/>";
			}
		}
	}

	/**
	 * Get Video Type
	 *
	 * @param string $url
	 */
	function rb_agency_get_videotype($url) {
		$image_url = parse_url($url);
		if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com'){
			return "youtube";
		} else if($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com'){
			return "vimeo";
		} else {
			return "other";
		}
	}

	/**
	 * Strip out VideoID from URL
	 *
	 * @param string $videoURL
	 */
	function rb_agency_get_VideoID($videoURL) {

		$videoID = null;

		if (substr($videoURL, 0, 23) == "http://www.youtube.com/") {
				$image_url = parse_url($videoURL);
				if(isset($image_url['host']) && $image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com'){
					$array = explode("&", $image_url['query']);
					$videoID = "http://www.youtube.com/watch?v=".substr($array[0], 2);
				}
				/*
					TODO: Cleanup
							$videoURL = str_replace("http://www.youtube.com/v/", "", $videoURL);
							$videoURL = str_replace("http://www.youtube.com/watch?v=", "", $videoURL);
							$videoURL = str_replace("&feature=search", "", $videoURL);
							$videoURL = str_replace("?fs=1&amp;hl=en_US", "", $videoURL);
							//$videoID = $videoURL; // substr($videoURL, 25, 15);
				 */

		} else {
			$videoID = $videoURL;
		}
		return $videoID;
	}

	/**
	 * Create embed code from URL
	 *
	 * @param string $videoObject
	 */
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



	/**
	 * Generate Folder Name
	 *
	 * @param $ID - record id, $first = first name, $last - last name, $display - contact display
	 * @return - formatted folder name 
	 */
	function generate_foldername($ID = NULL, $first, $last, $display){

		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilenaming  = (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
		if ($rb_agency_option_profilenaming == 0) {
				$ProfileGalleryFixed = $first . "-". $last;
		} elseif ($rb_agency_option_profilenaming == 1) {
				$ProfileGalleryFixed = $first . "-". substr($last, 0, 1);
		} elseif ($rb_agency_option_profilenaming == 2) {
				$ProfileGalleryFixed = $display;
		} elseif ($rb_agency_option_profilenaming == 3) {
				$ProfileGalleryFixed = "ID-".$ID;
		} elseif ($rb_agency_option_profilenaming == 4) {
				$ProfileGalleryFixed = $first;
		} elseif ($rb_agency_option_profilenaming == 5) {
				$ProfileGalleryFixed = $last;
		}

		return RBAgency_Common::format_stripchars($ProfileGalleryFixed); 
	}

	/**
	 *  Check duplicate folder
	 * @param $diplay - contact display, $current_display - formatted contact display
	 * @return - suggested folder name
	 */

	function rb_check_duplicate_folder($display,$current_display, $arr = array()){

		if($display != $current_display && count($arr) > 1){
			$arr = array_unique($arr);
			$i = 0;
			do {
				//champ-camba
				//champ-camba-1
				$current_display = $current_display.($i>0?"-".$i:"");
				$i++;

			}while ( in_array($current_display,$arr,true));

			return $current_display;

		}
		return $display;
	}



	// Get profile images
	function rb_agency_profileimages($profileID){
		// Call DB
		global $wpdb;

		$queryImg = "SELECT * FROM ". table_agency_profile_media ." media INNER JOIN ".table_agency_profile." prof ON media.ProfileID = prof.ProfileID WHERE media.ProfileID =  \"%d\" AND media.ProfileMediaType = \"Image\" ORDER BY media.ProfileMediaPrimary "; // TODO: Why Limit of 7?
		$resultsImg = $wpdb->get_results($wpdb->prepare($queryImg,$profileID),ARRAY_A);
		$countImg = count($resultsImg);
		$images = "";
		foreach ($resultsImg as $dataImg) { //style=\"display:none\"

			// Fix Spaces
			$img_src = str_replace(array("%20", "+", " "), "%2B", $dataImg['ProfileMediaURL']);

			$image_path = RBAGENCY_UPLOADDIR . $dataImg["ProfileGallery"] ."/". $img_src;
			$bfi_params = array(
				'crop'=>true,
				'width'=>200
			);
			$image_src = bfi_thumb( $image_path, $bfi_params );
			//$images ."<img  class=\"roll\" src=\"".RBAGENCY_PLUGIN_URL."/ext/timthumb.php?src={PHOTO_PATH}". $img_src ."&w=200&q=30\" alt='' style='width:148px'   />\n";
			$images ."<img  class=\"roll\" src=\"". $image_src ."\" alt='' style='width:148px'   />\n";
		}

		return $images;
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
		global $wpdb;
		extract(shortcode_atts(array(
				"type" => 0,
				"count" => 1,
				"thumbsize" => array(200,300)
		), $atts));
		if ($type == 1) { // Featured
			$sqlWhere = " AND profile.ProfileIsPromoted=1";
		}

		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;

		if ( //Must be logged to view model list and profile information
		($rb_agency_option_privacy == 2 && is_user_logged_in()) || 

		// Model list public. Must be logged to view profile information
		($rb_agency_option_privacy == 1 && is_user_logged_in()) ||

		// All Public
		($rb_agency_option_privacy == 0) ||

		//admin users
		(is_user_logged_in() && current_user_can( 'edit_posts' )) ||

		//  Must be logged in as Casting Agent to View Profiles
		($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype()) ) {
				echo "<div id=\"profile-featured\">\n";

				/*
				 * Execute Query
				 */
					$queryList = "SELECT profile.*,

					(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
					WHERE profile.ProfileID = media.ProfileID 
					AND media.ProfileMediaType = \"Image\" 
					AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 
					FROM ". table_agency_profile ." profile 
					WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
					AND profile.ProfileIsFeatured = 1  
					ORDER BY RAND() LIMIT 0,$count";


				$resultsList =$wpdb->get_results($queryList,ARRAY_A);
				$countList = count($resultsList);
				foreach($resultsList as $dataList) {
					echo "<div class=\"rbprofile-list\">\n";
					if (isset($dataList["ProfileMediaURL"]) ) {
						echo "  <div class=\"image\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\"><img src=\"".RBAGENCY_PLUGIN_URL."ext/timthumb.php?src=". RBAGENCY_UPLOADDIR ."". $dataList["ProfileGallery"] ."/". $dataList["ProfileMediaURL"] ."&w=".$thumbsize[0]."&h=".$thumbsize[1]."&a=t\" /></a></div>\n";
					} else {
						echo "  <div class=\"image image-broken\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">No Image</a></div>\n";
					}
					echo "<div class=\"profile-info\">";
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
					echo "     <h3 class=\"name\"><a href=\"". RBAGENCY_PROFILEDIR ."". $dataList["ProfileGallery"] ."/\">". $ProfileContactDisplay ."</a></h3>\n";
					if (isset($rb_agency_option_profilelist_expanddetails)) {
						echo "<div class=\"details\"><span class=\"details-age\">". rb_agency_get_age($dataList["ProfileDateBirth"]) ."</span>";
						if($dataList["ProfileLocationState"]!=""){
							echo "<span class=\"divider\">, </span><span class=\"details-state\">". $dataList["ProfileLocationState"] ."</span>";
						}
						echo "</div>\n";
					}

					if(is_user_logged_in() && function_exists("rb_agency_get_miscellaneousLinks")){
						// Add Favorite and Casting Cart links
						rb_agency_get_miscellaneousLinks($dataList["ProfileID"]);
					}
					echo "  </div><!-- .profile-info -->\n";
					echo "</div><!-- .rbprofile-list -->\n";
				}
				if ($countList < 1) {
					echo __("No Featured Profiles", RBAGENCY_TEXTDOMAIN);
				}
				echo "  <div style=\"clear: both; \"></div>\n";
				echo "</div><!-- #profile-featured -->\n";
			}
	}



	// *************************************************************************************************** //
	// Image Resizing 
	if (!class_exists('rb_agency_image')) {
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

		function save($filename, $image_type=IMAGETYPE_JPEG, $compression = 100, $permissions=NULL) {

			$rb_agency_options_arr = get_option('rb_agency_options');
				$compression = isset($rb_agency_options_arr['rb_agency_option_image_compression'])?$rb_agency_options_arr['rb_agency_option_image_compression']:100;

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
	}

	/**
	 * RBAgency Pagination
	 */

	class RBAgency_Pagination {

		/*Default values*/
		var $total_pages = -1;//items
		var $limit = NULL;
		var $target = ""; 
		var $uriExtend = "";
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
		function target($value, $uriExtend = ""){$this->target = $value; $this->uriExtend = $uriExtend;}

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
					return "<div class=\"$this->className\">$this->pagination</div>\n";
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

			} elseif( is_front_page() or is_home()){
				//make sure pagination will works perfectly in homepage
				return "$this->target&$this->parameterName=$id";
			} else {

				// We are in Page
				preg_match('/[0-9]/', $this->target, $matches, PREG_OFFSET_CAPTURE);
				if (isset($matches[0][1]) && $matches[0][1] > 0) {
					return substr($this->target, 0, $matches[0][1]) ."/$id/?$this->uriExtend";
				} else {
					return "$this->target/$id/?$this->uriExtend";
				}

			}// End Admin/Page Toggle
		}

		function calculate(){
				$this->pagination = "";
				$this->calculate == true;
				$error = false;
				if($this->urlF and $this->urlF != '%' and strpos($this->target,$this->urlF)===false){
						//Es necesario especificar el comodin para sustituir
						echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
						$error = true;
					} elseif($this->urlF and $this->urlF == '%' and strpos($this->target,$this->urlF)===false){
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
					$start = ($this->page - 1) * $this->limit;//first item to display on this page
				else
					$start = 0;                   			//if no page var is given, set start to 0

				/* Setup page vars for display. */
				$prev = $this->page - 1;                    //previous page is page - 1
				$next = $this->page + 1;                    //next page is page + 1
				$lastpage = ceil($this->total_pages/$this->limit);//lastpage is = total pages / items per page, rounded up.
				$lpm1 = $lastpage - 1;            			//last page minus 1

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
					if ($lastpage < 7 + ($this->adjacents * 2)){ //not enough pages to bother breaking it up
						for ($counter = 1; $counter <= $lastpage; $counter++){
								if ($counter == $this->page)
										$this->pagination .= "<span class=\"pageno current\">$counter</span>";
									else
										$this->pagination .= "<a class=\"pageno\" href=\"".$this->get_pagenum_link($counter)."\">$counter</a>";
							}
					}
					elseif($lastpage > 5 + ($this->adjacents * 2)){ //enough pages to hide some
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

	// retrieving data type title
	// if rb-interact is not installed
	if ( !function_exists('retrieve_title') ) { 
		function retrieve_title($id=0) {
			global $wpdb;
					/* 
			 * return title
			 */
			$check_type = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID = %d";
			$check_query = $wpdb->get_results($wpdb->prepare($check_type, $id),ARRAY_A);// OR die($wpdb->print_error());
			if(count($check_query) > 0){
				$fetch = current($check_query);
				return $fetch['DataTypeTitle'];
			} else {
				return false;
			}
		}
	}

	// *************************************************************************************************** //
	// Custom Fields
	function rb_custom_fields($visibility = 0, $ProfileID = 0, $ProfileGender, $ProfileGenderShow = false, $SearchMode = false){

		$all_permit = false; // set to false
		global $wpdb;
		if($ProfileID != 0){
			$query = $wpdb->get_results($wpdb->prepare("SELECT ProfileType FROM ".table_agency_profile." WHERE ProfileID = %d",$ProfileID),ARRAY_A);
			$fetchID = current($query);
			$ptype = $fetchID["ProfileType"];
			if(strpos($ptype,",") > -1){
				$t = explode(",",$ptype);
				$ptype = ""; 
				foreach($t as $val){
					$ptyp[] = str_replace(" ","_",retrieve_title($val));
				}
				$ptype = implode(",",$ptyp);
			} else {
				$ptype = str_replace(" ","_",retrieve_title($ptype));
			}
			$ptype = str_replace(",","",$ptype);
		} else {
			$all_permit = true;
		}

		if(is_admin() && $visibility == 1 && $_GET["page"] == "rb_agency_profiles"){
			$query3 = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView IN(1,2)  ORDER BY ProfileCustomOrder ASC";
			$results3 = $wpdb->get_results($query3,ARRAY_A);
		} else {
			$query3 = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = %d  ORDER BY ProfileCustomOrder ASC";
			$results3 = $wpdb->get_results($wpdb->prepare($query3,$visibility),ARRAY_A);
		}

		$count3 = 0;

		foreach($results3 as $data3) {

				/*
				 * Get Profile Types to
				 * filter models from clients
				 */
				$permit_type = false;
				$PID = $data3['ProfileCustomID'];
				$get_types = "SELECT ProfileCustomTypes FROM ". table_agency_customfields_types .
							" WHERE ProfileCustomID = %d";

				$result = $wpdb->get_results($wpdb->prepare($get_types, $PID),ARRAY_A);
				$types = "";
				foreach ($result as $p){
						$types = $p['ProfileCustomTypes'];
				}
				if(!isset($ptype)){
					$ptype = "";
				}
				$ptype = str_replace(' ','_',$ptype);
				if($types != "" || $types != NULL){
					if(strpos($types,",") > -1){
						$types = explode(",",$types);
						foreach($types as $t){
							if(strpos($ptype,$t) > -1) {$permit_type=true; break;}
						}
					} else {
							if(strpos($ptype,$types) > -1) $permit_type=true;
					}
				}



				if($permit_type || $all_permit){
					if($ProfileGenderShow ==true){
						if($data3["ProfileCustomShowGender"] == $ProfileGender ){ // Depends on Current LoggedIn User's Gender
							rb_custom_fields_template($visibility, $ProfileID, $data3);
						} elseif(empty($data3["ProfileCustomShowGender"])) {
							rb_custom_fields_template($visibility, $ProfileID, $data3);
						}
					} else {
							rb_custom_fields_template($visibility, $ProfileID, $data3);
					}
					$count3++;

				}

				// END Query2
			echo "    </td>\n";
			echo "  </tr>\n";
		}// End while
		if ($count3 == 0 && $visibility != 1) {
			echo "  <tr valign=\"top\">\n";
			echo "    <th scope=\"row\">". __("There are no custom fields loaded", RBAGENCY_TEXTDOMAIN) .".  <a href='". admin_url("admin.php?page=rb_agency_settings&ConfigID=7") ."'>". __("Setup Custom Fields", RBAGENCY_TEXTDOMAIN) ."</a>.</th>\n";
			echo "  </tr>\n";
		}
	}

	// *************************************************************************************************** //
	// Custom Fields TEMPLATE 
	function rb_custom_fields_template($visibility = 0, $ProfileID, $data3){

		global $wpdb;

		$rb_agency_options_arr 				= get_option('rb_agency_options');
		$rb_agency_option_unittype  		= isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;
		$rb_agency_option_profilenaming 	= isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?(int)$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
		$rb_agency_option_locationtimezone 	= isset($rb_agency_options_arr['rb_agency_option_locationtimezone'])?(int)$rb_agency_options_arr['rb_agency_option_locationtimezone']:0;

		if( (!empty($data3['ProfileCustomID']) || $data3['ProfileCustomID'] !="") ){
			$subresult = $wpdb->get_row($wpdb->prepare("SELECT ProfileID,ProfileCustomValue,ProfileCustomDateValue,ProfileCustomID FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = %d AND ProfileID = %d ", $data3['ProfileCustomID'],$ProfileID),ARRAY_A);
			$row = $subresult;

			$ProfileCustomTitle = $data3['ProfileCustomTitle'];
			$ProfileCustomType  = $data3['ProfileCustomType'];
			$ProfileCustomDateValue =  ($row["ProfileCustomDateValue"]!=="1970-01-01"  && $row["ProfileCustomDateValue"]!=="0000-00-00")?$row["ProfileCustomDateValue"]:"";
			$ProfileCustomValue = !empty($row["ProfileCustomValue"])?$row["ProfileCustomValue"]:"";

			/* Pull data from post so data will not lost @Satya 12/12/2013 */
			if($ProfileCustomValue=="" && isset($_POST)){
				$customindex = "ProfileCustomID".$data3['ProfileCustomID'] ; 
				if (isset($_POST[$customindex]) && is_array($_POST[$customindex])) {
					$ProfileCustomValue = implode(",", $_POST[$customindex]);
				} else {
						$ProfileCustomValue = isset($_POST[$customindex])?$_POST[$customindex]:"";
				}
			}

				// SET Label for Measurements
				// Imperial(in/lb), Metrics(ft/kg)
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_unittype  = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;
				$measurements_label = "";
				if ($ProfileCustomType == 7) { //measurements field type
					if ($rb_agency_option_unittype ==0) { // 0 = Metrics(cm/kg)
						if($data3['ProfileCustomOptions'] == 1){
							$measurements_label  ="<em>(cm)</em>";
						} elseif($data3['ProfileCustomOptions'] == 2) {
							$measurements_label  ="<em>(kg)</em>";
						} elseif($data3['ProfileCustomOptions'] == 3) {
							$measurements_label  ="<em>(cm)</em>";
						}
					} elseif($rb_agency_option_unittype ==1) { //1 = Imperial(in/lb)
						if($data3['ProfileCustomOptions'] == 1){
							$measurements_label  ="<em>(in)</em>";
						} elseif($data3['ProfileCustomOptions'] == 2) {
							$measurements_label  ="<em>(lb)</em>";
						} elseif($data3['ProfileCustomOptions'] == 3) {
							$measurements_label  ="<em>(ft/in)</em>";
						}
					}
				}
				$isTextArea = "";
				if($ProfileCustomType == 4){
					$isTextArea ="textarea-field"; 
				}
			echo "  <tr valign=\"top rbfunc\" data-val=\"".htmlentities($ProfileCustomValue)."\" class=\"".$isTextArea."\">\n";
			echo "    <th scope=\"row\"><div class=\"box\">". stripcslashes($data3['ProfileCustomTitle'])." ".$measurements_label."</div></th>\n"; 
			echo "    <td>\n";

				if ($ProfileCustomType == 1) { //TEXT
							echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
				} elseif($ProfileCustomType == 11){
						//link
						$link = htmlentities(stripslashes($ProfileCustomValue));
							echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $link ."\" /><br />\n";
				} elseif ($ProfileCustomType == 2) { // Min Max

					$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data3['ProfileCustomOptions'],"}"),"{"));
					list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);

					if (!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)) {
						echo "<br /><br /> <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /><br />\n";
					} else {
						echo "<br /><br />  <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data3['ProfileCustomID']]."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", RBAGENCY_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data3['ProfileCustomID']]."\" /><br />\n";
					}

				} elseif ($ProfileCustomType == 3 || $ProfileCustomType == 9) { // Drop Down || Multi-Select
					$dropdown_arr = explode(":",$data3['ProfileCustomOptions']);
					if(count($dropdown_arr) == 1){
						list($option1) =  $dropdown_arr;
						$option2 = "";
					} elseif(count($dropdown_arr) == 2){
						list($option1,$option2) =  $dropdown_arr;
					} else {
						$option1 = "";
						$option2 = "";
					}


					$data = explode("|",$option1);
					$data2 = explode("|",$option2);


					if($ProfileCustomType == 9){
						
						$expertiseToArrayNotComma = explode("|",$ProfileCustomValue);
						$expertiseToArrayComma = explode(",",$ProfileCustomValue);
						$expertiseToArray = array_merge($expertiseToArrayComma,$expertiseToArrayNotComma);
					}

					echo "<label class=\"dropdown\">".$data[0]."</label>";
					echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" ".($ProfileCustomType == 9?"multiple":"").">\n";
					echo "<option value=\"\">--</option>";

					if($ProfileCustomType == 9){
							foreach($data as $val1){
								
								if(in_array(trim(stripcslashes($val1),'"'),$expertiseToArray)){
									$isSelected = "selected=\"selected\"";
									echo "<option value=\"".trim(stripslashes($val1),'"')."\"".$isSelected .">".stripslashes($val1)."</option>";
								} elseif(empty($val1)){
									echo "";
								} else {
									echo "<option value=\"".trim(stripslashes($val1),'"')."\">".stripslashes($val1)."</option>";
								}
							}
						} else {
							$pos = 0;
							foreach($data as $val1){

								if($val1 != end($data) && $val1 != $data[0]){
									if (trim(stripslashes($val1),'"') == trim(stripslashes($ProfileCustomValue),'"') || in_array(stripslashes($val1), explode(",",$ProfileCustomValue))) {
										$isSelected = "selected=\"selected\"";
										echo "<option value=\"".trim(stripslashes($val1),'"')."\"".$isSelected .">".stripslashes($val1)."</option>";
									} else {
										echo "<option value=\"".trim(stripslashes($val1),'"')."\" >".stripslashes($val1)."</option>";
									}
								}
							}
						}

						/**$pos = 0;
						foreach($data as $val1){

							if($val1 != end($data) && $val1 != $data[0]){
								if (trim(stripslashes($val1),'"') == trim(stripslashes($ProfileCustomValue),'"') || in_array(stripslashes($val1), explode(",",$ProfileCustomValue))) {
									$isSelected = "selected=\"selected\"";
									echo "<option value=\"".trim(stripslashes($val1),'"')."\"".$isSelected .">".stripslashes($val1)."</option>";
								} else {
									echo "<option value=\"".trim(stripslashes($val1),'"')."\" >".stripslashes($val1)."</option>";
								}
							}
						}**/
					echo "</select>\n";


					if (!empty($data2) && !empty($option2)) {
						echo "<label class=\"dropdown\">".$data2[0]."</label>";

							$pos2 = 0;
							echo "11<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
							echo "<option value=\"\">--</option>";
							foreach($data2 as $val2){
									if($val2 != end($data2) && $val2 !=  $data2[0]){
										echo "<option value=\"".$val2."\"". selected($val2, $ProfileCustomValue) ." >".stripslashes($val2)."</option>";
									}
								}
							echo "</select>\n";
					}
				} elseif ($ProfileCustomType == 4) {
						echo "<textarea style=\"width: 100%; min-height: 300px;\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">". stripslashes($ProfileCustomValue) ."</textarea>";
				} elseif ($ProfileCustomType == 5) {
					echo "<fieldset>";
					$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);

					foreach($array_customOptions_values as $val){
						if(strpos($ProfileCustomValue, ",") !== false){
							$xplode = explode(",",$ProfileCustomValue);
						} elseif(strpos($ProfileCustomValue, "|") !== false){
							$xplode = explode("|",$ProfileCustomValue);
						} else {
							$xplode = array($ProfileCustomValue);
						}
						if(!empty($val)){
							echo "<label class=\"checkbox\" data-raw=\"".addslashes($val)."\"><input type=\"checkbox\" value=\"". $val."\"   "; if(in_array(addslashes($val),$xplode) && !empty($val)){echo "checked=\"checked\""; }echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" /> ";
							echo "". $val."</label><br />";                       
						}
					}
					echo "</fieldset>";

				} elseif ($ProfileCustomType == 6) {

					$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);

					foreach($array_customOptions_values as $val){
						if(!empty($val)){
							echo "<fieldset>";
								echo "<label><input type=\"radio\" value=\"". $val."\" "; if(!empty($val)){checked($val, $ProfileCustomValue);}echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
								echo "". $val."</label><br/>";
							echo "</fieldset>";
						}
					}
				} elseif ($ProfileCustomType == 7) { //Imperial/Metrics

					if($data3['ProfileCustomOptions']==3){
						if($rb_agency_option_unittype == 1){
							// 
							echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">\n";
							echo "  <option value=\"\">--</option>\n";
							// 
							$i=12;
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
						preg_match_all('/(\d+(\.\d+)?)/',$ProfileCustomValue, $matches);
						$ProfileCustomValue = isset($matches[0][0])?$matches[0][0]:"";
						echo "  <input type=\"text\" id=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" />\n";
						}
					} else {
							//validate for float type.
						preg_match_all('/(\d+(\.\d+)?)/',$ProfileCustomValue, $matches);
						$ProfileCustomValue = isset($matches[0][0])?$matches[0][0]:"";

							echo "  <input class='imperial_metrics' type=\"text\" id=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" />
									<div class='error_msg' style='color:red; min-width:0px'></div>";
					}
				} elseif ($ProfileCustomType == 10) { //Date
						$getDateValue = !empty($ProfileCustomDateValue) ? $ProfileCustomDateValue : $ProfileCustomValue;
						echo "<input type=\"text\" id=\"rb_datepicker". $data3['ProfileCustomID']."\" class=\"rb-datepicker\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."_date\" value=\"". $getDateValue ."\" /><br />\n";
						echo "<script type=\"text/javascript\">\n\n";
						echo "jQuery(function(){\n\n";
						echo "jQuery(\"input[name=ProfileCustomID". $data3['ProfileCustomID'] ."_date]\").val('". (isset($_POST["ProfileCustomID". $data3['ProfileCustomID'] ."_date"])?$_POST["ProfileCustomID". $data3['ProfileCustomID'] ."_date"]:$getDateValue) ."');\n\n";
						echo "});\n\n";
						echo "</script>\n\n";

				}
		}// End if Empty ProfileCustomID
	}
	/*/
	 *   ================ Get Country Title ===================
	 *   @returns Country Title
	 *   @returns Country Code if $country_code = true
	/*/   
	function rb_agency_getCountryTitle($country_id="",$contry_code = false){

		global $wpdb;
		$rb_agency_options_arr 				= get_option('rb_agency_options');
		$rb_agency_option_showcountrycode  		= isset($rb_agency_options_arr['rb_agency_option_showcountrycode'])?$rb_agency_options_arr['rb_agency_option_showcountrycode']:0;


		if(empty($country_id)) return false;

		$return = "";

		if($rb_agency_option_showcountrycode  == 1){
			$query ="SELECT CountryCode FROM ". table_agency_data_country ." WHERE CountryID = " . (is_numeric($country_id)?$country_id:0);
			$result = $wpdb->get_row($query);
			$return = $result->CountryCode;
		} else {
			$query ="SELECT CountryTitle FROM ". table_agency_data_country ." WHERE CountryID = " . (is_numeric($country_id)?$country_id:0);
			$result = $wpdb->get_row($query);
			$return = isset($result->CountryTitle)? $result->CountryTitle:"";
		}

		if(count($result) > 0){
			return $return;
		}

		return false;

	}

	/*/
	 *   ================ Get State Title ===================
	 *   @returns State Title
	 *   @returns State Code if $state_code = true
	/*/   
	function rb_agency_getStateTitle($state_id="",$state_code = false){

		global $wpdb;
			$rb_agency_options_arr 				= get_option('rb_agency_options');
		$rb_agency_option_showstatecode  		= isset($rb_agency_options_arr['rb_agency_option_showstatecode'])?$rb_agency_options_arr['rb_agency_option_showstatecode']:0;
		$rb_agency_option_profilelist_expanddetails_state = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_state'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_state']:0;




		if(empty($state_id)) return false;

		$return = "";

		if($rb_agency_option_showstatecode  ==1){
			$query ="SELECT StateCode FROM ". table_agency_data_state ." WHERE StateID = " . (is_numeric($state_id)?$state_id:0);
			$result = $wpdb->get_row($query);
			$return = isset($result->StateCode)?$result->StateCode:"";
		} else {
			$query ="SELECT StateTitle FROM ". table_agency_data_state ." WHERE StateID = " . (is_numeric($state_id)?$state_id:0);
			$result = $wpdb->get_row($query);
			$return = isset($result->StateTitle)?$result->StateTitle:"";
		}

		if(count($result) > 0){
			return $return;

		}

		return false;

	}

	/*/
	 *   ================ Get City Title ===================
	 *   @returns City Title
	/*/   
	function rb_agency_getCityTitle($profile_id=""){

		global $wpdb;
		$rb_agency_options_arr 				= get_option('rb_agency_options');
		$rb_agency_option_showstatecode  	= isset($rb_agency_options_arr['rb_agency_option_showstatecode'])?$rb_agency_options_arr['rb_agency_option_showstatecode']:0;
		$rb_agency_option_profilelist_expanddetails_state = isset($rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_state'])?$rb_agency_options_arr['rb_agency_option_profilelist_expanddetails_state']:0;		

		$return = "";

		$query ="SELECT ProfileLocationCity FROM ". table_agency_profile ." WHERE ProfileID = " . (is_numeric($profile_id)?$profile_id:0);
		$result = $wpdb->get_row($query);
		$return = isset($result->ProfileLocationCity)?$result->ProfileLocationCity:"";

		return $return;

	}

	/*/
	 *   ================ Get Profile Gender for each user ===================
	 *   @returns GenderTitle
	/*/   
	function rb_agency_getGenderTitle($ProfileGenderID){
		global $wpdb;
		$query = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ." WHERE GenderID='%s'";
		$results = $wpdb->get_results($wpdb->prepare($query,$ProfileGenderID),ARRAY_A);
		$count = count($results);

		if($count > 0){
			$data = current($results);
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
	function rb_agency_filterfieldGender($ProfileCustomID, $ProfileGenderID, $Privacy = true){
		global $wpdb; 

		if($Privacy){
			$query = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 AND ProfileCustomID = %d AND ProfileCustomShowGender IN(%s,'') ";
		} else {
			$query = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomID = %d AND ProfileCustomShowGender IN(%s,'') ";
		}
		$results = $wpdb->get_results($wpdb->prepare($query,$ProfileCustomID,$ProfileGenderID),ARRAY_A);
		$count = $wpdb->num_rows;
			if($count > 0){
			return true;
			} else {
				return false;
			}
		rb_agency_checkExecution();
	}



	/*/
	 * ======================== Get New Custom Fields ===============
	 * @Returns Custom Fields
	/*/
	function rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender, $LabelTag="strong", $LabelSeparator=": ") {

		global $wpdb;
		global $rb_agency_option_unittype;

		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND c.ProfileCustomShowProfile = 1 AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC"), $ProfileID);
		foreach ($resultsCustom as $resultCustom) {

			if( $resultCustom->ProfileCustomID != 16 ):

			if(!empty($resultCustom->ProfileCustomValue )){
			
				// do not allow the space of any non numeric if the single char found. 
				$_strVal = $resultCustom->ProfileCustomValue;
				if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
					continue;
				}
			
			
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
							echo "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label .$LabelSeparator." </".$LabelTag."> ".$heightfeet."ft ".$heightinch." in</li>\n";
						} else {
							echo "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label. $LabelSeparator." </".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					} else {
							echo "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label. $LabelSeparator." </".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
				} elseif ($resultCustom->ProfileCustomView == "2") {
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
							$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							echo "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label. $LabelSeparator." </".$LabelTag."> ".$heightfeet."ft ".$heightinch." in</li>\n";
						} else {
							echo "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label. $LabelSeparator." </".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					} else {
						echo "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><".$LabelTag.">". $resultCustom->ProfileCustomTitle .$measurements_label. $LabelSeparator." </".$LabelTag."> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
				}
			}
			endif;
		}
	}

	/*/
	 * ======================== Get Custom Fields ===============
	 * @Returns Custom Fields
	/*/
	function rb_agency_getProfileCustomFields($ProfileID, $ProfileGender, $table=false, $title_to_exclude=array(), $label_tag="strong", $value_tag="span", $echo = true, $is_print = false) {

		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_options');
			// What is the unit of measurement?
			$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype']) ? $rb_agency_options_arr['rb_agency_option_unittype']:"";

				$display = "";
				$row_tag = "";

				if($table) {
					$display .="<table>";
					$row_tag = "tr";
				} else {
					$row_tag = "li";
				}
		$title_to_exclude_arr = array();
		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder,c.ProfileCustomView, cx.ProfileCustomValue, cx.ProfileCustomDateValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND c.ProfileCustomShowProfile = 1 AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));
		foreach ($resultsCustom as $resultCustom) {

			if(!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude_arr)){

				// If a value exists...
				//if(!empty($resultCustom->ProfileCustomValue ) || (!empty($resultCustom->ProfileCustomDateValue ) && $resultCustom->ProfileCustomDateValue!=="1970-01-01"  && $resultCustom->ProfileCustomDateValue!=="0000-00-00" && $resultCustom->ProfileCustomDateValue !== null)){
			
				if(
					(strlen($resultCustom->ProfileCustomValue) >= 1 && !is_numeric($resultCustom->ProfileCustomValue)) ||
					//(!empty($resultCustom->ProfileCustomValue) && !is_numeric($resultCustom->ProfileCustomValue)) || 
					(is_numeric($resultCustom->ProfileCustomValue)) || 
						(!empty($resultCustom->ProfileCustomDateValue ) && $resultCustom->ProfileCustomDateValue!=="1970-01-01" 
							&& $resultCustom->ProfileCustomDateValue!=="0000-00-00" && $resultCustom->ProfileCustomDateValue !== null)){
								
					// do not allow the space of any non numeric if the single char found. 
					$_strVal = $resultCustom->ProfileCustomValue;
					if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
						continue;
					}
					
					/*
					TODO:  REMOVE
					// Create Label for Measurement
					if ($resultCustom->ProfileCustomType == 7) { //measurements field type
						if($rb_agency_option_unittype == 0){ // 0 = Metrics(cm/kg)
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
					 */
					$measurements_label = ""; 
					$label = "";

							$resultCustom->ProfileCustomValue = stripslashes($resultCustom->ProfileCustomValue);
					if ($resultCustom->ProfileCustomType == 3 || $resultCustom->ProfileCustomType == 7  || $resultCustom->ProfileCustomType == 9){
							$resultCustom->ProfileCustomValue =  implode(", ",explode(",",$resultCustom->ProfileCustomValue));
					}

					if( $resultCustom->ProfileCustomType == 5){
								$resultCustom->ProfileCustomValue =  implode(", ",explode("|",$resultCustom->ProfileCustomValue));

					}
					if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender) || $is_print){
						if ($resultCustom->ProfileCustomType == 7){

							if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
								if($resultCustom->ProfileCustomOptions == 1 || $resultCustom->ProfileCustomOptions == 3){
									$label = "cm";
								} elseif($resultCustom->ProfileCustomOptions == 2){
									$label = "kg";
								}
							} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
								if($resultCustom->ProfileCustomOptions == 1){
									$label = "in";
								} elseif($resultCustom->ProfileCustomOptions == 2){
									$label = "lbs";
								} elseif($resultCustom->ProfileCustomOptions == 3){
									$label = "ft/in";
								}
							}

							preg_match_all('/(\d+(\.\d+)?)/',$resultCustom->ProfileCustomValue, $matches);
							$resultCustom->ProfileCustomValue = $matches[0][0];

							$measurements_label = "<span class=\"label options-".$resultCustom->ProfileCustomOptions."\">". $label ." </span>";

							/*if($resultCustom->ProfileCustomOptions == 3){
							 */
								$value = rb_get_imperial_metrics($resultCustom->ProfileCustomValue,$resultCustom->ProfileCustomOptions);
								$display .= "<".$row_tag." class=\"height-".$resultCustom->ProfileCustomValue." profilecustomid_".$resultCustom->ProfileCustomID." ctype_1_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">".$value."</".$value_tag."></".$row_tag.">\n";
							/*} elseif($resultCustom->ProfileCustomOptions == 2){ // kg
								$display .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_2_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ".$resultCustom->ProfileCustomValue." ". $measurements_label ."</li>\n";
							} elseif($resultCustom->ProfileCustomOptions == 1){
								if( $rb_agency_option_unittype == 1 ){ //cm/in
											$heightraw = $resultCustom->ProfileCustomValue; 
											$heightfeet = $heightraw; // * 2.54;
											$resultCustom->ProfileCustomValue = (int)$heightfeet;
								}

								$display .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_3_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ".$resultCustom->ProfileCustomValue." ". $measurements_label ."</li>\n";
							} else {
								$display .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_4_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ". $resultCustom->ProfileCustomValue ." ". $measurements_label ."</li>\n";
							}*/
						} 
						else {
							
							$resultCustom->ProfileCustomTitle = stripslashes($resultCustom->ProfileCustomTitle);
							
							if ($resultCustom->ProfileCustomType == 4){
								if(!empty($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."><br/> <".$value_tag.">". nl2br($resultCustom->ProfileCustomValue) ."</".$value_tag."></".$row_tag.">\n";
								}
							} elseif($resultCustom->ProfileCustomType == 11){
								if(!empty($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag.">&nbsp;<a href=".$resultCustom->ProfileCustomValue." target=\"blank\">Click Here</a></".$row_tag.">\n";
								}
							} elseif ($resultCustom->ProfileCustomType == 10){

								if(!empty($resultCustom->ProfileCustomDateValue) && $resultCustom->ProfileCustomDateValue !== "January 01, 1970"){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_6_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">". date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue)) ."</".$value_tag."></".$row_tag.">\n";
								}
							} elseif($resultCustom->ProfileCustomType == 9) {

								//print_r($resultCustom->ProfileCustomValue);

								if(!empty($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_7_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">". split_language(',',', ',$resultCustom->ProfileCustomValue) ."</".$value_tag."></".$row_tag.">\n";
								}

							} else {
								//if(!empty($resultCustom->ProfileCustomValue)){
								if(strlen($resultCustom->ProfileCustomValue) >= 1 && !is_numeric($resultCustom->ProfileCustomValue) || is_numeric($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_7_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">". split_language(',',', ',$resultCustom->ProfileCustomValue) ."</".$value_tag."></".$row_tag.">\n";
								}
							}
						}

					}
				}
			} // in _array

		}
		if($table) {
			$display .="</table>";
		}
					if($echo){
						echo $display;
					} else {
						return $display;
					}
	}

	


	//for pdf print
	function rb_agency_getProfileCustomFields_print($ProfileID, $ProfileGender, $table=false, $title_to_exclude="", $label_tag="strong", $value_tag="span", $echo = true, $is_print = false) {

		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_options');
			// What is the unit of measurement?
			$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype']) ? $rb_agency_options_arr['rb_agency_option_unittype']:"";

				$display = "";
				$row_tag = "";

				if($table) {
					$display .="<table>";
					$row_tag = "tr";
				} else {
					$row_tag = "li";
				}
		$title_to_exclude_arr = array();
		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder,c.ProfileCustomView, cx.ProfileCustomValue, cx.ProfileCustomDateValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND c.ProfileCustomShowProfile = 1 AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));
		

		foreach ($resultsCustom as $resultCustom) {

			if(!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude_arr)){

				// If a value exists...
				//if(!empty($resultCustom->ProfileCustomValue ) || (!empty($resultCustom->ProfileCustomDateValue ) && $resultCustom->ProfileCustomDateValue!=="1970-01-01"  && $resultCustom->ProfileCustomDateValue!=="0000-00-00" && $resultCustom->ProfileCustomDateValue !== null)){
			
				if(
					(strlen($resultCustom->ProfileCustomValue) >= 1 && !is_numeric($resultCustom->ProfileCustomValue)) ||
					//(!empty($resultCustom->ProfileCustomValue) && !is_numeric($resultCustom->ProfileCustomValue)) || 
					(is_numeric($resultCustom->ProfileCustomValue)) || 
						(!empty($resultCustom->ProfileCustomDateValue ) && $resultCustom->ProfileCustomDateValue!=="1970-01-01" 
							&& $resultCustom->ProfileCustomDateValue!=="0000-00-00" && $resultCustom->ProfileCustomDateValue !== null)){
								
					// do not allow the space of any non numeric if the single char found. 
					$_strVal = $resultCustom->ProfileCustomValue;
					if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
						continue;
					}
					
					/*
					TODO:  REMOVE
					// Create Label for Measurement
					if ($resultCustom->ProfileCustomType == 7) { //measurements field type
						if($rb_agency_option_unittype == 0){ // 0 = Metrics(cm/kg)
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
					 */
					$measurements_label = ""; 
					$label = "";

							$resultCustom->ProfileCustomValue = stripslashes($resultCustom->ProfileCustomValue);
					if ($resultCustom->ProfileCustomType == 3 || $resultCustom->ProfileCustomType == 7  || $resultCustom->ProfileCustomType == 9){
							$resultCustom->ProfileCustomValue =  implode(", ",explode(",",$resultCustom->ProfileCustomValue));
					}

					if( $resultCustom->ProfileCustomType == 5){
								$resultCustom->ProfileCustomValue =  implode(", ",explode("|",$resultCustom->ProfileCustomValue));

					}
					if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender) || $is_print){
						if ($resultCustom->ProfileCustomType == 7){

							if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
								if($resultCustom->ProfileCustomOptions == 1 || $resultCustom->ProfileCustomOptions == 3){
									$label = "cm";
								} elseif($resultCustom->ProfileCustomOptions == 2){
									$label = "kg";
								}
							} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
								if($resultCustom->ProfileCustomOptions == 1){
									$label = "in";
								} elseif($resultCustom->ProfileCustomOptions == 2){
									$label = "lbs";
								} elseif($resultCustom->ProfileCustomOptions == 3){
									$label = "ft/in";
								}
							}

							preg_match_all('/(\d+(\.\d+)?)/',$resultCustom->ProfileCustomValue, $matches);
							$resultCustom->ProfileCustomValue = $matches[0][0];

							$measurements_label = "<span class=\"label options-".$resultCustom->ProfileCustomOptions."\">". $label ." </span>";

							/*if($resultCustom->ProfileCustomOptions == 3){
							 */
								$value = rb_get_imperial_metrics($resultCustom->ProfileCustomValue,$resultCustom->ProfileCustomOptions);
								$display .= "<".$row_tag." class=\"height-".$resultCustom->ProfileCustomValue." profilecustomid_".$resultCustom->ProfileCustomID." ctype_1_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">".$value."</".$value_tag."></".$row_tag.">\n";
							/*} elseif($resultCustom->ProfileCustomOptions == 2){ // kg
								$display .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_2_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ".$resultCustom->ProfileCustomValue." ". $measurements_label ."</li>\n";
							} elseif($resultCustom->ProfileCustomOptions == 1){
								if( $rb_agency_option_unittype == 1 ){ //cm/in
											$heightraw = $resultCustom->ProfileCustomValue; 
											$heightfeet = $heightraw; // * 2.54;
											$resultCustom->ProfileCustomValue = (int)$heightfeet;
								}

								$display .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_3_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ".$resultCustom->ProfileCustomValue." ". $measurements_label ."</li>\n";
							} else {
								$display .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_4_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ". $resultCustom->ProfileCustomValue ." ". $measurements_label ."</li>\n";
							}*/
						} 
						else {
							
							$resultCustom->ProfileCustomTitle = stripslashes($resultCustom->ProfileCustomTitle);
							
							if ($resultCustom->ProfileCustomType == 4){
								if(!empty($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."><br/> <".$value_tag.">". nl2br($resultCustom->ProfileCustomValue) ."</".$value_tag."></".$row_tag.">\n";
								}
							} elseif($resultCustom->ProfileCustomType == 11){
								if(!empty($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag.">&nbsp;<a href=".$resultCustom->ProfileCustomValue." target=\"blank\">Click Here</a></".$row_tag.">\n";
								}
							} elseif ($resultCustom->ProfileCustomType == 10){

								if(!empty($resultCustom->ProfileCustomDateValue) && $resultCustom->ProfileCustomDateValue !== "January 01, 1970"){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_6_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">". date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue)) ."</".$value_tag."></".$row_tag.">\n";
								}
							} elseif($resultCustom->ProfileCustomType == 9) {

								//print_r($resultCustom->ProfileCustomValue);

								if(!empty($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_7_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">". split_language(',',', ',$resultCustom->ProfileCustomValue) ."</".$value_tag."></".$row_tag.">\n";
								}

							} else {
								//if(!empty($resultCustom->ProfileCustomValue)){
								if(strlen($resultCustom->ProfileCustomValue) >= 1 && !is_numeric($resultCustom->ProfileCustomValue) || is_numeric($resultCustom->ProfileCustomValue)){
									$display .="<".$row_tag." class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_7_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\" style=\"list-style: none;\"><".$label_tag.">". $resultCustom->ProfileCustomTitle ." : </".$label_tag."> <".$value_tag.">". split_language(',',', ',$resultCustom->ProfileCustomValue) ."</".$value_tag."></".$row_tag.">\n";
								}
							}
						}

					}
				}
			} // in _array

		}
		if($table) {
			$display .="</table>";
		}
					if($echo){
						//echo $display;
					} else {
						//return $display;
					}

					return $display;
	}

	/*/
	 * ======================== Get Custom Fields ===============
	 * @Returns Custom Fields
	/*/
	function rb_agency_getProfileCustomFields_admin($ProfileID, $ProfileGender, $Privacy = 0) {

		global $wpdb;
		global $rb_agency_option_unittype;
		$html = "";

		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder,c.ProfileCustomView, cx.ProfileCustomValue,  cx.ProfileCustomDateValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView IN(".$Privacy.") AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));

		foreach ($resultsCustom as $resultCustom) {

			if(!empty($resultCustom->ProfileCustomValue ) || !empty($resultCustom->ProfileCustomDateValue )){

			
			
				// do not allow the space of any non numeric if the single char found. 
				$_strVal = $resultCustom->ProfileCustomValue;
				if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
					continue;
				}
				
						$resultCustom->ProfileCustomDateValue = ($resultCustom->ProfileCustomDateValue!=="1970-01-01"  && $resultCustom->ProfileCustomDateValue!=="0000-00-00")?$resultCustom->ProfileCustomDateValue:"";

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
					$measurements_label = "<span class=\"label\">". (isset($label)?$label:"") ."</span>";
				} else {
					$measurements_label = "";
				}

				// Lets not do this...
				$measurements_label = "";

				if(strpos(strtolower($resultCustom->ProfileCustomTitle),"height") !== false){
							if(strpos($resultCustom->ProfileCustomValue, "'") !== false &&  strpos($resultCustom->ProfileCustomValue," ") !== false ){
											$resultCustom->ProfileCustomValue =  stripslashes($resultCustom->ProfileCustomValue)."\"";
							} else {
								$resultCustom->ProfileCustomValue = stripslashes($resultCustom->ProfileCustomValue);

							}
				}


				if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
							$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							$html .=  "<li class=\"options_3\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet."ft ".$heightinch." in</li>\n";
						} else {
							$html .=  "<li class=\"options_3\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					} elseif($resultCustom->ProfileCustomType == 11){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle ."</strong>:</".$label_tag.">&nbsp;<a href=".$resultCustom->ProfileCustomValue." target=\"_blank\">Click Here</a></li>\n";
					} elseif ($resultCustom->ProfileCustomType == 10){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_6_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ". (!empty($resultCustom->ProfileCustomDateValue)?date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue)):"Not set") ."</li>\n";
					} else {
						if ($resultCustom->ProfileCustomType == 4){
							$html .=  "<li class=\"options_3\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong><br/> ". nl2br($resultCustom->ProfileCustomValue) ."</li>\n";
						} else {
							$html .=  "<li class=\"options_3\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong>  ". split_language(',',', ',$resultCustom->ProfileCustomValue) ."</li>\n";
						}
					}

				} elseif (isset($resultCustom->ProfileCustomView) && $resultCustom->ProfileCustomView == "2") {
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
							$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							$html .=  "<li  class=\"options_2\"><strong>".  stripslashes($resultCustom->ProfileCustomTitle) .$measurements_label.":</strong> ".$heightfeet."ft ".$heightinch." in</li>\n";
						} else {
							$html .=  "<li  class=\"options_2\"><strong>".  stripslashes($resultCustom->ProfileCustomTitle) .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					} elseif($resultCustom->ProfileCustomType == 11){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle ."</strong>:</".$label_tag.">&nbsp;<a href=".$resultCustom->ProfileCustomValue." target=\"_blank\">Click Here</a></li>\n";
					} elseif ($resultCustom->ProfileCustomType == 10){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_6_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ". (!empty($resultCustom->ProfileCustomDateValue)?date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue)):"Not set") ."</li>\n";
					} else {
						$html .=  "<li  class=\"options_2\"><strong>".  stripslashes($resultCustom->ProfileCustomTitle) .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
				} elseif (isset($resultCustom->ProfileCustomView) && $resultCustom->ProfileCustomView == "0") { // TODO: Why is admin view showing? (Rob)
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
							$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							$html .= "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet." ft ".$heightinch." in</li>\n";
						} else {
							$html .="<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					} elseif($resultCustom->ProfileCustomType == 11){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle ."</strong>:</".$label_tag.">&nbsp;<a href=".$resultCustom->ProfileCustomValue." target=\"_blank\">Click Here</a></li>\n";
					} elseif ($resultCustom->ProfileCustomType == 10){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_6_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ". (!empty($resultCustom->ProfileCustomDateValue)?date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue)):"Not set") ."</li>\n";
					} else {
						$html .= "<li   class=\"profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
				} else {
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
							$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							$html .= "<li   class=\"options_3 profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet." ft ".$heightinch." in</li>\n";
						} else {
							$html .="<li   class=\"options_3 profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					} elseif($resultCustom->ProfileCustomType == 11){
							$html .="<li class=\"profilecustomid_".$resultCustom->ProfileCustomID." ctype_5_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle ."</strong>:</".$label_tag.">&nbsp;<a href=".$resultCustom->ProfileCustomValue." target=\"_blank\">Click Here</a></li>\n";
					} elseif ($resultCustom->ProfileCustomType == 10){
							$html .="<li class=\"options_3 profilecustomid_".$resultCustom->ProfileCustomID." ctype_6_".$resultCustom->ProfileCustomType."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .":</strong> ". (!empty($resultCustom->ProfileCustomDateValue)?date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue)):"Not set") ."</li>\n";
					} else {
						$html .= "<li   class=\"options_3 profilecustomid_".$resultCustom->ProfileCustomID."\" id=\"profilecustomid_".$resultCustom->ProfileCustomID."\"><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
					}
				}

			}
		}

		return $html;
	}

	/*/
	 * ======================== Split Languages ===============
	 * @Returns languages separatad by space
	 * @param $delimiter : the needle to split the languages
	 * @param $separator : the language output separator
	 * @param $languages : the languages
	/*/
	function split_language($delimiter, $separator, $languages){
		$languages = explode($delimiter, $languages);
		for ($start=0; $start < count($languages); $start++) {
			$languages = implode($separator, $languages);
		}
		return $languages;
	}

	/*/
	 * ======================== Get Custom Fields ===============
	 * @Returns Custom Fields excluding a title
	 * @parm includes an array of title
	/*/
	
		
	function rb_agency_getProfileCustomFieldsArray($ProfileID, $ProfileGender) {
	
		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_options');
		// What is the unit of measurement?
		$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype']) ? $rb_agency_options_arr['rb_agency_option_unittype']:"";
	
		$_allFields = array();
	
		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder,c.ProfileCustomView, cx.ProfileCustomValue, cx.ProfileCustomDateValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND c.ProfileCustomShowProfile = 1 AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));
		foreach ($resultsCustom as $resultCustom) {
			// If a value exists...
	
			if(!empty($resultCustom->ProfileCustomValue ) || (!empty($resultCustom->ProfileCustomDateValue ) && $resultCustom->ProfileCustomDateValue!=="1970-01-01"  && $resultCustom->ProfileCustomDateValue!=="0000-00-00" && $resultCustom->ProfileCustomDateValue !== null)){
	
				// do not allow the space of any non numeric if the single char found. 
				$_strVal = $resultCustom->ProfileCustomValue;
				if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
					continue;
				}
				
				$resultCustom->ProfileCustomValue = stripslashes($resultCustom->ProfileCustomValue);
				if ($resultCustom->ProfileCustomType == 3 || $resultCustom->ProfileCustomType == 7  || $resultCustom->ProfileCustomType == 9){
						$resultCustom->ProfileCustomValue =  implode(", ",explode(",",$resultCustom->ProfileCustomValue));
				}
	
				if( $resultCustom->ProfileCustomType == 5){
							$resultCustom->ProfileCustomValue =  implode(", ",explode("|",$resultCustom->ProfileCustomValue));
	
				}
			
				if ($resultCustom->ProfileCustomType == 7){
	
					if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
						if($resultCustom->ProfileCustomOptions == 1 || $resultCustom->ProfileCustomOptions == 3){
							$label = "cm";
						} elseif($resultCustom->ProfileCustomOptions == 2){
							$label = "kg";
						}
					} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)
						if($resultCustom->ProfileCustomOptions == 1){
							$label = "in";
						} elseif($resultCustom->ProfileCustomOptions == 2){
							$label = "lbs";
						} elseif($resultCustom->ProfileCustomOptions == 3){
							$label = "ft/in";
						}
					}
	
					preg_match_all('/(\d+(\.\d+)?)/',$resultCustom->ProfileCustomValue, $matches);
					$resultCustom->ProfileCustomValue = $matches[0][0];
					$value = rb_get_imperial_metrics($resultCustom->ProfileCustomValue,$resultCustom->ProfileCustomOptions);
					$_val = $value . $label;
				
				} else {
					if ($resultCustom->ProfileCustomType == 10){
						if(!empty($resultCustom->ProfileCustomDateValue) && $resultCustom->ProfileCustomDateValue !== "January 01, 1970"){
							$_val = date("F d, Y",strtotime($resultCustom->ProfileCustomDateValue));
						}
					} elseif(!empty($resultCustom->ProfileCustomValue)){
						$_val = split_language(',',', ',$resultCustom->ProfileCustomValue);
					}
					
					
				}
				if(!empty($_val)){
					$_allFields[ sanitize_title($resultCustom->ProfileCustomTitle)] = array(
						'title' => $resultCustom->ProfileCustomTitle,
						'value' => $_val,
					);
				}
	
			}
	
		}
		return $_allFields;
	}
	
	function rb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender, $title_to_exclude, $_echo = true) {

		global $wpdb;
		global $rb_agency_option_unittype;

		$_out='';
		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue, c.ProfileCustomView FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));
		foreach ($resultsCustom as $resultCustom) {
			if(!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude)){
				if(!empty($resultCustom->ProfileCustomValue )){
				
				
					// do not allow the space of any non numeric if the single char found. 
					$_strVal = $resultCustom->ProfileCustomValue;
					if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
						continue;
					}
				
				
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
								$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet." ft ".$heightinch." in</li>\n";
							} else {
								$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
							}
						} else {
							if ($resultCustom->ProfileCustomType == 4){
								$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong><br/> ". nl2br($resultCustom->ProfileCustomValue) ."</li>\n";
							} else {
								$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
							}
						}

					} elseif ($resultCustom->ProfileCustomView == "2") {
						if ($resultCustom->ProfileCustomType == 7){
							if($resultCustom->ProfileCustomOptions == 3){
								$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
								$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ".$heightfeet." ft ".$heightinch." in</li>\n";
							} else {
								$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
							}
						} else {
							$_out.= "<li><strong>". $resultCustom->ProfileCustomTitle .$measurements_label.":</strong> ". $resultCustom->ProfileCustomValue ."</li>\n";
						}
					}
				}
			}
		}
		
		if($_echo == true){
			echo $_out;
		}else{
			return $_out;
		}
		
		
		
	}

	function rb_agency_getProfileCustomFieldsExperienceDescription($ProfileID, $ProfileGender, $title_to_exclude) {

		global $wpdb;
		global $rb_agency_option_unittype;

		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC"));
		foreach ($resultsCustom as $resultCustom) {
			if(!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude)){
				if(!empty($resultCustom->ProfileCustomValue )){
					
					// do not allow the space of any non numeric if the single char found. 
					$_strVal = $resultCustom->ProfileCustomValue;
					if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
						continue;
					}
					
					// Lets not do this...
					$measurements_label = "";
					if ($resultCustom->ProfileCustomTitle == 'Experience(s)'){
						if (rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
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

		$query.=" GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC ";

		$resultsCustom = $wpdb->get_results($query,ARRAY_A);
		foreach ($resultsCustom as $resultCustom) {
			if(!empty($resultCustom->ProfileCustomValue )){
			
				// do not allow the space of any non numeric if the single char found. 
				$_strVal = $resultCustom->ProfileCustomValue;
				if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
					continue;
				}
				
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
							echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
						} else {
							echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
						}
					} else {
						if($echo!="dontecho"){ // so it wont exit if PDF generator request info
							if($resultCustom->ProfileCustomTitle.$measurements_label=="Experience"){return "";}
							echo "<li id='". $resultCustom->ProfileCustomTitle .$measurements_label."'><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
						}
					}

				} elseif ($resultCustom->ProfileCustomView == "2") {
					if ($resultCustom->ProfileCustomType == 7){
						if($resultCustom->ProfileCustomOptions == 3){
							$heightraw = $resultCustom->ProfileCustomValue; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>".$heightfeet." ft ".$heightinch." in</span></li>\n";
						} else {
							echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
						}
					} else {
						echo "<li><label>". $resultCustom->ProfileCustomTitle .$measurements_label."</label><span>". $resultCustom->ProfileCustomValue ."</span></li>\n";
					}
				}
			}
		}
		if($echo=="dontecho"){return $return;} else {echo $return;}
	}

	function rb_agency_getProfileCustomFieldsCustom($ProfileID, $ProfileGender,$echo="") {

		global $wpdb;
		global $rb_agency_option_unittype;

		$echo = "";
		$return = "";
		$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, cx.ProfileCustomValue, c.ProfileCustomView FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = %d GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC", $ProfileID),ARRAY_A);
		$total_rows = $wpdb->num_rows;
		foreach ($resultsCustom as $resultCustom) {
			if(!empty($resultCustom["ProfileCustomValue"] )){
			
				// do not allow the space of any non numeric if the single char found. 
				$_strVal = $resultCustom["ProfileCustomValue"];
				if(!ctype_alnum($_strVal) and strlen($_strVal) == 1){
					continue;
				}

				if ($resultCustom["ProfileCustomType"] == 7) { //measurements field type
					if($rb_agency_option_unittype == 0){ // 0 = Metrics(ft/kg)
						if($resultCustom["ProfileCustomOptions"] == 1){
							$label = "(cm)";
						} elseif($resultCustom["ProfileCustomOptions"] == 2){
							$label = "(kg)";
						}
					} elseif ($rb_agency_option_unittype ==1){ //1 = Imperial(in/lb)

						if($resultCustom["ProfileCustomOptions"] == 1){
							$label = "(in)";
						} elseif($resultCustom["ProfileCustomOptions"] == 2){
							$label = "(lbs)";
						} elseif($resultCustom["ProfileCustomOptions"] == 3){
							$label = "(ft/in)";
						}
					}

					$measurements_label = "<span class=\"label\">". $label ."</span>";

				} else {
					$measurements_label = "";
				}

				// Lets not do this...
				$measurements_label = "";
				if (rb_agency_filterfieldGender($resultCustom["ProfileCustomID"], $ProfileGender)){

					if ($resultCustom["ProfileCustomType"] == 7){
						if($resultCustom["ProfileCustomOptions"] == 3){
							$heightraw = $resultCustom["ProfileCustomValue"]; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							$return.="<li><label>". $resultCustom["ProfileCustomTitle"] .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
						} else {
							$return.="<li><label>". $resultCustom["ProfileCustomTitle"] .$measurements_label."</label><span>". $resultCustom["ProfileCustomValue"] ."</span></li>\n";
						}
					} else {
						if($echo!="dontecho"){ // so it wont exit if PDF generator request info
							if($resultCustom["ProfileCustomTitle"].$measurements_label=="Experience") {return ""; }
						}
						$return.="<li id='". $resultCustom["ProfileCustomTitle"] .$measurements_label."'><label>". $resultCustom["ProfileCustomTitle"] .$measurements_label."</label><span>". $resultCustom["ProfileCustomValue"] ."</span></li>\n";
					}

				} elseif ($resultCustom["ProfileCustomView"] == "2") {
					if ($resultCustom["ProfileCustomType"] == 7){
						if($resultCustom["ProfileCustomOptions"] == 3){
							$heightraw = $resultCustom["ProfileCustomValue"]; $heightfeet = floor($heightraw/12); $heightinch = $heightraw - floor($heightfeet*12);
							$return.="<li><label>". $resultCustom["ProfileCustomTitle"] .$measurements_label."</label><span>".$heightfeet."ft ".$heightinch." in</span></li>\n";
						} else {
							$return.="<li><label>". $resultCustom["ProfileCustomTitle"] .$measurements_label."</label><span>". $resultCustom["ProfileCustomValue"] ."</span></li>\n";
						}
					} else {
						$return.="<li><label>". $resultCustom["ProfileCustomTitle"] .$measurements_label."</label><span>". $resultCustom["ProfileCustomValue"] ."</span></li>\n";
					}
				}
			}
		}

		if($echo=="dontecho") {return $return; } else {echo $return; }

	}

		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilelist_favorite = isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;


	//****************************************************************************************************//


	/*/
	 * ======================== Get ProfileID by UserLinkedID ===============
	 * @Returns ProfileID
	/*/
	function rb_agency_getProfileIDByUserLinked($ProfileUserLinked){
		global $wpdb;
		if(!empty($ProfileUserLinked)){
			$query = $wpdb->get_results($wpdb->prepare("SELECT ProfileID,ProfileUserLinked FROM ".table_agency_profile." WHERE ProfileUserLinked = %s",$ProfileUserLinked),ARRAY_A);
			$fetchID = current($query);
			return $fetchID["ProfileID"];
		}
	}

	/*/
	 * ======================== Get Media Categories===============
	 * @Returns Media Categories
	/*/
	function rb_agency_getMediaCategories($GenderID){
		global $wpdb;
		$query = $wpdb->get_results("SELECT * FROM  ".table_agency_data_media." ORDER BY MediaCategoryOrder",ARRAY_A);
		$count = count($query);
		foreach($query as $f){
			if(($f["MediaCategoryGender"] == $GenderID || $f["MediaCategoryGender"] == 0) && !empty($f["MediaCategoryFileType"])){
				echo "<option value=\"rbcustommedia_".str_replace(" ","-",strtolower($f["MediaCategoryTitle"]))."_".$f["MediaCategoryLinkType"]."_".$f["MediaCategoryFileType"]."_".$f["MediaCategoryID"]."\">".$f["MediaCategoryTitle"]." (".$f["MediaCategoryFileType"].")</option>";
			}
		}
	}
	/*/
	 * ======================== Display Media Categories===============
	 * @Returns Media Categories
	/*/
	function rb_agency_showMediaCategories($ProfileID, $ProfileGallery){
		global $wpdb;
		$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' ";
		$resultsMedia =  $wpdb->get_results($wpdb->prepare($queryMedia, $ProfileID),ARRAY_A);
		foreach ($resultsMedia  as $dataMedia) {
			if (strpos($dataMedia['ProfileMediaType'] ,"rbcustommedia") !== false) {

				$custom_media_info = explode("_",$dataMedia['ProfileMediaType']);
				$custom_media_title = str_replace("-"," ",$custom_media_info[1]);
				$custom_media_type = $custom_media_info[2];
				$custom_media_id = $custom_media_info[4];
				$query = current($wpdb->get_results("SELECT MediaCategoryTitle FROM  ".table_agency_data_media." WHERE MediaCategoryID='".$custom_media_id."'",ARRAY_A));

				if($custom_media_type == "link"){
						//<li class=\"item custom_media-link\"></li>
					echo "<a  ".rb_get_profilemedia_link_opentype($ProfileGallery ."/". $dataMedia['ProfileMediaURL']) ."  style=\"text-transform: capitalize !important;\"> ".rb_get_profile_link_label()." ".(isset($query["MediaCategoryTitle"])?$query["MediaCategoryTitle"]:$custom_media_title). "</a>\n";
				} elseif($custom_media_type == "button") {
						//<li class=\"item custom_media-button\"></li>
					echo "<a  class=\"rb_button\"  ".rb_get_profilemedia_link_opentype( $ProfileGallery ."/". $dataMedia['ProfileMediaURL']) ."  style=\"text-transform: capitalize !important;\">".rb_get_profile_link_label()." ".(isset($query["MediaCategoryTitle"])?$query["MediaCategoryTitle"]:$custom_media_title). "</a>\n";
				} else {
					echo "<a target=\"_blank\"".rb_get_profilemedia_link_opentype( $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] )."  style=\"text-transform: capitalize !important;\">".rb_get_profile_link_label()." ".(isset($query["MediaCategoryTitle"])?$query["MediaCategoryTitle"]:$custom_media_title). "</a>\n";
				}
			}
		}
	}


	/*/
	 * ======================== check agency data ===============
	 * @Returns Check agency data
	/*/
	function rb_check_exists($data,$property,$type){

		global $wpdb;

		$count = 0;
		if($type == 'text'){
			$query = $wpdb->get_results("SELECT ProfileID FROM  ".table_agency_profile." WHERE ". $property . " = '" . $data . "'",ARRAY_A);
			$count = count($query);
		} elseif($type == 'numeric'){
			$query = $wpdb->get_results("SELECT ProfileID FROM  ".table_agency_profile." WHERE ". $property . " = " . $data ,ARRAY_A);
			$count = count($query);
		}
		if($count > 0) return true;
		return false;
	}

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

			if (is_object($object)) {$object = get_class($object); }
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
	function rb_agency_getSocialLinks($ProfileID = ""){

		$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_showsocial = isset($rb_agency_options_arr['rb_agency_option_showsocial'])?$rb_agency_options_arr['rb_agency_option_showsocial']:0;

		if($rb_agency_option_showsocial){
			echo "	<div class=\"social addthis_toolbox addthis_default_style\">\n";
			echo "		<a href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c4d7ce67dde9ce7\" class=\"addthis_button_compact\">". __("Share", RBAGENCY_TEXTDOMAIN). "</a>\n";
			echo "		<span class=\"addthis_separator\">|</span>\n";
			//echo "		<a class=\"addthis_button_facebook\"></a>\n";
			//echo "		<a class=\"addthis_button_myspace\"></a>\n";
			//echo "		<a class=\"addthis_button_google\"></a>\n";
			//echo "		<a class=\"addthis_button_twitter\"></a>\n";
			
			echo "	</div><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c4d7ce67dde9ce7\"></script>\n";
		}

		//get_social_media_links($ProfileID);


	}

function get_social_media_links($ProfileID = ""){
		global $wpdb;
		$social_media_arr = array(
			'Facebook',
			'Twitter',
			'Instagram',
			'Flickr',
			'Google+',
			'YouTube',
			'Vimeo',
			'LinkedIn',
			'Friendster',
			'hi5',
			'MySpace',
			'Custom'
		);
		$rb_agency_options_arr = get_option('rb_agency_options');
		$output = '';
		$output .='<style>
		.profile-social-media-icons{margin-left:130px;} 
		.profile-social-media-icons li{ padding:2px; float:left;margin-top:5px;}
		.profile-social-media-icons li img{ width:20px;}
		.profile-social-media-links{} 
		.profile-social-media-links li{ padding:2px;}
		.profile-social-media-links li img{ width:20px;}
		</style>';

		if($rb_agency_options_arr['rb_agency_option_profilemedia_links_displayoption'] == 1){
			$output .= "<ul class='profile-social-media-icons' style='list-style:none;'>";
			foreach($social_media_arr as $k=>$v){
				$socialMediaURL = get_user_meta($ProfileID,'SocialMediaURL_'.$v,true);
				$social_icon_filename = '';
				if($v == 'Facebook'){
					$social_icon_filename = 'fb.png';
				}elseif($v == 'Twitter'){
					$social_icon_filename = 'tw.png';
				}elseif($v == 'Instagram'){
					$social_icon_filename = 'instagram.png';
				}elseif($v == 'Flickr'){
					$social_icon_filename = 'flickr.png';
				}elseif($v == 'Google+'){
					$social_icon_filename = 'gplus.png';
				}elseif($v == 'YouTube'){
					$social_icon_filename = 'youtube.png';
				}elseif($v == 'Vimeo'){
					$social_icon_filename = 'vimeo.jpg';
				}elseif($v == 'LinkedIn'){
					$social_icon_filename = 'linkedin.png';
				}elseif($v == 'Friendster'){
					$social_icon_filename = 'friendster.png';
				}elseif($v == 'hi5'){
					$social_icon_filename = 'hi5.jpg';
				}elseif($v == 'MySpace'){
					$social_icon_filename = 'myspace.png';
				}elseif($v == 'Custom'){
					$social_icon_filename = '';
				}
				$widthForMySpace = $v == 'MySpace' ? "style='width:20px;height:20px;'" : "";
				$socialMediaURL = get_user_meta($ProfileID,"SocialMediaURL_".$v,true);
				if(!empty($socialMediaURL)){
					$social_icon_path = site_url()."/wp-content/plugins/rb-agency/assets/img/social-icons/".$social_icon_filename;
					$output .="<li><a href=".$socialMediaURL." target='_blank'><img src='".$social_icon_path."' $widthForMySpace></a></li>";
				}
			}
			$output .= "</ul>";
		}elseif($rb_agency_options_arr['rb_agency_option_profilemedia_links_displayoption'] == 2){
			$output .= "<ul class='profile-social-media-links' style='list-style:none;'>";
			foreach($social_media_arr as $k=>$v){
				$socialMediaURL = get_user_meta($ProfileID,'SocialMediaURL_'.$v,true);
				$socialMediaName = get_user_meta($ProfileID,'SocialMediaName_'.$v,true);
				if(!empty($socialMediaURL)){
					$output .= "<li><strong>".$v."</strong>: <a href=".$socialMediaURL." target='_blank'>".$socialMediaURL."</a></li>";
				}
				
			}
			$output .= "</ul>";
		}else{
			$output .= "<ul class='profile-social-media-icons' style='list-style:none;'>";
			foreach($social_media_arr as $k=>$v){
				$socialMediaURL = get_user_meta($ProfileID,'SocialMediaURL_'.$v,true);
				$social_icon_filename = '';
				if($v == 'Facebook'){
					$social_icon_filename = 'fb.png';
				}elseif($v == 'Twitter'){
					$social_icon_filename = 'tw.png';
				}elseif($v == 'Instagram'){
					$social_icon_filename = 'instagram.png';
				}elseif($v == 'Flickr'){
					$social_icon_filename = 'flickr.png';
				}elseif($v == 'Google+'){
					$social_icon_filename = 'gplus.png';
				}elseif($v == 'YouTube'){
					$social_icon_filename = 'youtube.png';
				}elseif($v == 'Vimeo'){
					$social_icon_filename = 'vimeo.jpg';
				}elseif($v == 'LinkedIn'){
					$social_icon_filename = 'linkedin.png';
				}elseif($v == 'Friendster'){
					$social_icon_filename = 'friendster.png';
				}elseif($v == 'hi5'){
					$social_icon_filename = 'hi5.jpg';
				}elseif($v == 'MySpace'){
					$social_icon_filename = 'myspace.png';
				}elseif($v == 'Custom'){
					$social_icon_filename = '';
				}
				$widthForMySpace = $v == 'MySpace' ? "style='width:20px;height:20px;'" : "";
				$socialMediaURL = get_user_meta($ProfileID,"SocialMediaURL_".$v,true);
				if(!empty($socialMediaURL)){
					$social_icon_path = site_url()."/wp-content/plugins/rb-agency/assets/img/social-icons/".$social_icon_filename;
					$output .="<li><a href=".$socialMediaURL." target='_blank'><img src='".$social_icon_path."' $widthForMySpace></a></li>";
				}
			}
			$output .= "</ul>";
		}

			



		echo $output;	
		
	}

	function old_get_social_media_links($ProfileID = ""){

		global $wpdb;

		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_layoutprofile = isset($rb_agency_options_arr['rb_agency_option_layoutprofile'])?$rb_agency_options_arr['rb_agency_option_layoutprofile']:0;

		$sql = "SELECT * FROM ".$wpdb->prefix."agency_profile WHERE ProfileID = %d";
		$result = $wpdb->get_results($wpdb->prepare($sql,$ProfileID));

		$twitter = get_user_meta( $ProfileID, 'ShowProfileContactLinkTwitter',true);
		$facebook = get_user_meta( $ProfileID, 'ShowProfileContactLinkFacebook',true);
		$youtube = get_user_meta( $ProfileID, 'ShowProfileContactLinkYouTube',true);
		$flickr = get_user_meta( $ProfileID, 'ShowProfileContactLinkFlickr',true);

		$output = "";
		$output ='<style>
		profile-social-media-links{margin-left:130px;} 
		.profile-social-media-links li{ padding:2px; float:left;}
		.profile-social-media-links li img{ width:20px;}
		.fa { font-size:22px!important;}
		.fa-twitter-square{ 
			color:#00B9EB; 
		}
		.fa-facebook-square{
			color:rgb(59, 89, 152);
		}
		.fa-youtube-square{
			color:#CC181E;
		}
		</style>';
		$output .= "<ul class='profile-social-media-links' style='list-style:none;'>";
		foreach($result as $profile){
			if($twitter == true){
				$output .="<li><a href=".$profile->ProfileContactLinkTwitter." target='_blank'><img src='".site_url()."/wp-content/plugins/rb-agency/view/layout/".$rb_agency_option_layoutprofile."/images/twitter.png'></a></li>";
			}
			if($facebook == true){
				$output .="<li><a href=".$profile->ProfileContactLinkFacebook." target='_blank'><img src='".site_url()."/wp-content/plugins/rb-agency/view/layout/".$rb_agency_option_layoutprofile."/images/facebook.png'></a></li>";
			}
			if($youtube == true){
				$output .="<li><a href=".$profile->ProfileContactLinkYoutube." target='_blank'><img src='".site_url()."/wp-content/plugins/rb-agency/view/layout/".$rb_agency_option_layoutprofile."/images/youtube.png'></a></a></li>";
			}
			if($flickr == true){
				$output .="<li><a href=".$profile->ProfileContactLinkFlickr." target='_blank'><img src='".site_url()."/wp-content/plugins/rb-agency/view/layout/".$rb_agency_option_layoutprofile."/images/flickr.png'></a></a></li>";
			}			
			
		}

		
		$output .= "</ul>";	

		echo $output;			
	}



	//get previous and next profile link
	function linkPrevNext($ppage,$nextprev,$type="",$division=""){

		global $wpdb;
		$pid = 0;
		if($nextprev=="next") {$nPid=$pid+1; }
		else {$nPid=$pid-1; }

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
		$query = $wpdb->get_results($sql,ARRAY_A);
		$fetch = current($query);

		if(empty($fetch["ProfileGallery"])){ //make sure it wont send empty url

			if($nextprev=="next"){
				$sql=$tempSql."  ORDER BY ProfileContactNameFirst ASC"; // to get next record
			} else {
				$sql=$tempSql."  ORDER BY ProfileContactNameFirst DESC"; // to get next
			}

			$sql.=" LIMIT 0,1 ";
			$query = $wpdb->get_results($sql,ARRAY_A);
			$fetch = current($query);
		}

		return  $fetch["ProfileGallery"];
	}

	function getExperience($pid){

		global $wpdb;

		$query = $wpdb->get_results($wpdb->prepare("SELECT ProfileCustomValue FROM ".table_agency_customfield_mux." WHERE ProfileID = '%s' AND ProfileCustomID ='16' ",$pid),ARRAY_A);
		$fetch = current($query);

		return  $fetch["ProfileCustomValue"];
	}

	function checkCart($currentUserID,$pid){
		global $wpdb;

		$query="SELECT * FROM  ".table_agency_castingcart." WHERE CastingCartProfileID='%s' AND CastingCartTalentID='%s' ";
		$results = $wpdb->get_results($wpdb->prepare($query,$currentUserID,$pid),ARRAY_A) or die ( __("Error, query failed", RBAGENCY_TEXTDOMAIN ));
		return count($results);
	}

	/* function that lists users for generating login/password */
	function rb_display_profile_list(){ 
		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_locationtimezone 		= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
		
		echo "<div class=\"wrap\">\n";
		echo "  <h3 class=\"title\">". __("Profiles List", RBAGENCY_TEXTDOMAIN) ."</h3>\n";

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


		if(isset($_POST['search_profiles'])){
			$searchTerm=$_POST['search_profiles'];
			$filter .= " AND profile.ProfileContactNameFirst LIKE '%". $searchTerm ."%'";
			$filter .= " OR profile.ProfileContactNameLast LIKE '%". $searchTerm ."%'";
			$filter .= " OR profile.ProfileLocationCity LIKE '%". $searchTerm ."%'";
			$filter .= " OR profile.ProfileContactEmail LIKE '%". $searchTerm ."%'";
			$filter .= " OR users_tbl.user_login LIKE '%". $searchTerm ."%'";
		}

		//Paginate

		$wpdb->get_results("SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID LEFT JOIN ".$wpdb->prefix."users users_tbl ON users_tbl.user_email = profile.ProfileContactEmail ". $filter  ."",ARRAY_A); // number of total rows in the database
		$items = $wpdb->num_rows;
		if($items > 0) {
			$p = new RBAgency_Pagination;
			$p->items($items);
			$p->limit(50); // Limit entries per page
			$p->target("admin.php?page=". @$_GET['page'] . '&ConfigID=99' .@$query);
			$p->currentPage(@$_GET[$p->paging]); // Gets and validates the current page
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
			echo $p->show();// Echo out the list of paging. 
		}

		echo "  </div>\n";

		if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){$ConfigID=0;} else {$ConfigID=$_REQUEST['ConfigID']; }
		//Is it generate login page?
		if($ConfigID == '99'){

			//Search profiles starts ..
			echo "<form method=\"post\" action=\"\">";
			echo  __("Search User", RBAGENCY_TEXTDOMAIN) ."\n";
			echo "<input type=\"text\" value=\"".(isset($_POST['search_profiles'])?$_POST['search_profiles']:"")."\" name=\"search_profiles\" id=\"search_profiles\" size=\"20\" >";
			echo "<input type=\"submit\" value=\"Search\" name=\"search_submit\" id=\"search_submit\" class=\"button-primary\">";
			echo "<input type=\"submit\" name=\"advanced_search\" value=\"". __("Advanced Search", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"this.form.action='".get_bloginfo("wpurl")."/search/?srch=1'\" />";
			echo "</form>";
		}
		echo "</div>\n";


	/* End Top pagination */

		/* Table Content */
				?>
			<a class="button-secondary" id="bulk_generate" href="javascript:void(0)" style="margin-bottom: 5px" title="Generate" disabled="disabled">Generate</a>
			<a class="button-primary"  id="bulk_send_email" href="javascript:void(0)" style="margin-left: 5px" title="Send Email" disabled="disabled">Send Email</a>
			<a class="button-primary"  id="open_popup" href="javascript:void(0)" style="margin-left: 5px;float: right" title="Send Email">Edit Email Content</a>
			<div id="ch_bulk" style="float: none !important;margin-left: 10px;width: 34px;display: inline-block;position: relative;top: 7px;margin-top: -15px;"></div>
			<table cellspacing="0" class="widefat fixed">
				<thead>
				<tr class="thead">
					<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
					<th style="width:50px;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileID&dir=". $sortDirection) ?>">ID</a></th>
					<th style="width:100%;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileContactNameFirst&dir=". $sortDirection) ?>">First Name</a></th>
					<th style="width:100%;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileContactNameLast&dir=". $sortDirection) ?>">Last Name</a></th>
					<th style="width:100%;"><a href="<?php echo admin_url("admin.php?page=". $_GET['page'] ."&ConfigID=99&sort=ProfileGender&dir=". $sortDirection) ?>">Email Addresses</a></th>
					<th style="width:100px;"></th>
					<th style="width:100px;"></th>
					<th style="width:100px;"></th>

					<th></th>
				</tr>
				</thead>
			<tbody>

			<?php
	//Search starts
			if(!isset($_POST['search_profiles'])){
				$query = "SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID LEFT JOIN ".$wpdb->prefix."users users_tbl ON users_tbl.user_email = profile.ProfileContactEmail "  . $filter  ." ORDER BY $sort $dir $limit";
				//echo $query;
			}
			else
			{
				
				$query = "SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID LEFT JOIN ".$wpdb->prefix."users users_tbl ON users_tbl.user_email = profile.ProfileContactEmail "  . $filter  ." ORDER BY $sort $dir $limit";
				
			}

			$results2 = $wpdb->get_results($query,ARRAY_A);
			$count = $wpdb->num_rows;
			$i = 0;
			foreach($results2 as $data) {

				$ProfileID = $data['ProfileID'];
				$ProfileContactNameFirst = stripslashes($data['ProfileContactNameFirst']);
				$ProfileContactNameLast = stripslashes($data['ProfileContactNameLast']);
				$ProfileContactEmail = RBAgency_Common::format_propercase(stripslashes($data['ProfileContactEmail']));

				//get wp user info
				$user_info = get_userdata($data['ProfileUserLinked']);
				$userlogin="";
				$userpass="";
				if($user_info){
					$userlogin = $user_info->user_login;
				//  $userpass = $user_info->user_pass;
				}
				$i++;
				if ($i % 2 == 0) {
						$rowColor = " style='background: #fcfcfc'"; 
				} else {
						$rowColor = " "; 
				}?>
				<tr <?php echo $rowColor ?>>
				<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $ProfileID ?>" id="<?php echo $ProfileID ?>" data-firstname="<?php echo $ProfileContactNameFirst ?>" data-lastname="<?php echo $ProfileContactNameLast ?>" data-email="<?php echo $ProfileContactEmail ?>" class="administrator"  name="<?php echo $ProfileID ?>"/></th>
				<td><?php echo $ProfileID ?></td>
				<td><?php echo $ProfileContactNameFirst ?></td>
				<td><?php echo $ProfileContactNameLast ?></td>
				<td style="text-transform: lowercase;"><?php echo $ProfileContactEmail ?></td>
				<td><a href="javascript:void(0)" class="generate_lp button-secondary" data-id="<?php echo $ProfileID ?>" data-firstname="<?php echo $ProfileContactNameFirst ?>" data-lastname="<?php echo $ProfileContactNameLast ?>">Generate</a></td>
				<td><a href="javascript:void(0)" class="email_lp button-primary" disabled="disabled" data-id="<?php echo $ProfileID ?>" id="em_<?php echo $ProfileID ?>" data-email="<?php echo $ProfileContactEmail ?>">Send Email</a></td>
				<td>
					<div id="ch_<?php echo $ProfileID ?>"></div>
					<input id="l_<?php echo $ProfileID ?>" style="width:100px;" type="text" placeholder="Login" value="<?php echo (!empty($userlogin)) ? $userlogin : ""; ?>" disabled/><br />
					<input id="p_<?php echo $ProfileID ?>" style="width:100px;" type="text" placeholder="Password" value="<?php echo (!empty($userpass)) ? $userpass : "";?>" disabled/>         
				</td>
				<td></td>
				</tr>
				<?php
			}

			if ($count < 1) {
				if (isset($filter)) {
			?>
				<tr>
					<th class="check-column" scope="row"></th>
					<td class="name column-name" colspan="5">
					<p>No profiles found with this criteria.</p>
					</td>
				</tr>
			<?php
				} else {
			?>

				<tr>
					<th class="check-column" scope="row"></th>
					<td class="name column-name" colspan="5">
					<p>There aren't any profiles loaded yet!</p>
					</td>
				</tr>
			<?php
				}
			}
			?>

			</tbody>
				<tfoot>
				<tr class="thead">
					<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
					<th class="column" scope="col">ID</th>
					<th class="column" scope="col">First Name</th>
					<th class="column" scope="col">Last Name</th>
					<th class="column" scope="col">Email Address</th>
					<th class="column" scope="col"></th>
					<th class="column" scope="col"></th>
					<th class="column" scope="col"></th>
					<th class="column" scope="col"></th>
				</tr>
				</tfoot>
			</table>
			<div id="popup">
				<span class="popup_close" title="Close">x</span>
				<span class="popup_title">Use following shortcodes to put constructions in text</span><br />
				<code>[login]</code>, <code>[password]</code>, <code>[url]</code><br />
				<textarea id="emailContent" style="width:100%; height:200px"></textarea><br/>
				<input id="saveEmailContent" type="button" value="Save" class="button-primary" style="float:right;"/>
			</div>
			<div id="popup_bg"></div>
			<script type="text/javascript">

			jQuery(document).ready(function($){
				$('#open_popup').click(function(){
				$('#popup_bg').fadeIn('fast',function(){
					$('#popup').fadeIn('fast');

					// get custom email message, if defined
					$.ajax({
					url: ajaxurl,
					type: 'get',
					data: {
						action: 'read_email_cnt'
					},
					success: function(data){
						if(data != 'empty'){
						$('#emailContent').val(data);
						}
						else {
						$('#emailContent').val('Hello, we generated new login and password for you at RB Agency\n\n[login]\n[password]\n\nYou can [url] here\n\nThanks.');
						}
					}
					});
				});
				});

				$('#popup_bg, .popup_close').click(function(){
				$('#popup').fadeOut('fast',function(){
					$('#popup_bg').fadeOut('fast');
				});
				});

				$('.generate_lp').click(function(){
				var pid = $(this).attr('data-id');
				var pfname = $(this).attr('data-firstname');
				var plname = $(this).attr('data-lastname');

				var lp_arr = generateLP(pid, pfname, plname);

				var login = lp_arr[0];
				var password = lp_arr[1];

				if($('#l_' + pid).val()==""){
					$('#l_' + pid).val(login);
				}
				$('#p_' + pid).val(password);
				$('#em_' + pid).removeAttr('disabled');
				$('#em_' + pid).bind('click', sendEmail);
				$('#ch_' + pid).addClass('pending-profile');
				});


				$('#cb input[type=checkbox], .administrator').click(function(){
				if($(this).is(':checked')){
					$('#bulk_generate').removeAttr('disabled');
					$('#bulk_generate').unbind('click').bind('click', bulkGenerateLP);
				}
				else {
					if($('.administrator:checked').length == 0 || $('.administrator:checked').length == 50)
					$('#bulk_generate').attr('disabled', 'disabled');
				}
				});


				// saving custom email message
				$('#saveEmailContent').click(function(){
				var emailContent = $('#emailContent').val();
				if(emailContent){
					$.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action: 'write_email_cnt',
						email_message: emailContent 
					},
					success: function(){
						$('#popup').fadeOut('fast',function(){
						$('#popup_bg').fadeOut('fast');
						});
					}
					});
				}
				});


				function generateLP(pid, pfname, plname){
				var login = pfname.toLowerCase().substr(0, 5).replace(' ', '-') + pid + plname.toLowerCase().substr(-3, 3);
				var password = generatepass(login);

				return [login, password];
				}

				function bulkGenerateLP(){
				if($('.administrator:checked').length > 0){
					$('#ch_bulk').removeClass();
					$('.administrator:checked').each(function(){
					var pid = $(this).attr('id');
					var pfname = $(this).attr('data-firstname');
					var plname = $(this).attr('data-lastname');

					var lp_arr = generateLP(pid, pfname, plname);

					var login = lp_arr[0];
					var password = lp_arr[1];

					if($('#l_' + pid).val() ==""){
						$('#l_' + pid).val(login);
					}
					$('#p_' + pid).val(password);
					$('#em_' + pid).removeAttr('disabled');
					$('#em_' + pid).bind('click', sendEmail);
					$('#ch_' + pid).addClass('pending-profile');
					});

					$('#bulk_send_email').removeAttr('disabled');
				}

				$('#bulk_send_email').unbind('click').bind('click', bulkSendEmail);
				}

				function sendEmail(){
				var pid = $(this).attr('data-id');

				var login = $('#l_' + pid).val();
				var password = $('#p_' + pid).val();
				var email = $(this).attr('data-email').toLowerCase();

				if(login && password && email){
					$('#ch_' + pid).removeClass('pending-profile').addClass('loading-profile');
					$.ajax({
					url: ajaxurl, // pointed to admin-ajax.php
					type: 'post',
					data: {
						action: 'send_mail',
						profileid : pid,
						login : login,
						password : password,
						email : email,
						generate_pass: true
					},
					success: function(data){
						if(data == 'SUCCESS'){
						$('#ch_' + pid).removeClass('loading-profile').addClass('checked-profile');
						$('#l_' + pid).attr('disabled', 'disabled');
						$('#p_' + pid).attr('disabled', 'disabled');
						$('#em_' + pid).attr('disabled', 'disabled');
						$('#em_' + pid).unbind('click');
						}
						else {
						$('#ch_' + pid).removeClass('loading-profile').addClass('error-profile');
						alert(data);
						return false;
						}
					}
					});
				}
				else {
					alert("Please Generate Login / Password, then send!");
					return false;
				}
				}

				function bulkSendEmail(){
				var usersLP = {};
				if($('.administrator:checked').length > 0){
					$('#ch_bulk').removeClass().addClass('loading-profile');
					$('.administrator:checked').each(function(){
						var pid = $(this).attr('id');
						var login = $('#l_' + pid).val();
						var password = $('#p_' + pid).val();
						var email = $(this).attr('data-email');

						usersLP[pid] = {
						pid : pid,
						login : login,
						password : password,
						email : email
						}; 
				});

					//console.log(usersLP); 
					$.ajax({
					url: ajaxurl, // pointed to admin-ajax.php
					type: 'post',
					data: {
						action: 'send_bulk_mail',
						users_pl : usersLP,
						generatepass: true
					},
					success: function(data){
						if(data == 'SUCCESS'){
						$('#ch_bulk').removeClass().addClass('checked-profile');
						$('.administrator:checked').each(function(){
							var pid = $(this).attr('id');
							$('#ch_' + pid).removeClass();
						});
						}
						else {
						$('#ch_bulk').removeClass().addClass('error-profile');
						}
						console.log(data);
					},
					error: function(e){
					console.log(e);
					}
					});
				}
				}
			});

			var keylist = "abcdefghijklmnopqrstuvwxyz123456789";

			function generatepass(str){
				var temp = '';
				for (i=0; i < str.length; i++)
				temp += keylist.charAt(Math.floor(Math.random() * keylist.length));
				return temp;
			}
			</script>
		<?php
		/* End Table Content */

		/* Bottom pagination */
		echo "<div class=\"tablenav\">\n";
		echo "  <div class='tablenav-pages'>\n";

		if($items > 0) {
				echo $p->show();// Echo out the list of paging. 
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
		$generate_pass = trim($_POST["generate_pass"]);

		// getting required fileds from rb_agency_profile
		$profile_row = $wpdb->get_results( "SELECT ProfileID, ProfileContactDisplay, ProfileContactNameFirst, ProfileContactNameLast FROM ". table_agency_profile ." WHERE ProfileID = '" . $profileid . "'" );

		//if ( $user_id ) {
			//$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			//$user_id = wp_create_user( $login, $random_password, $email ); 
			if(!email_exists($email)){

				$random_password = $password; //wp_generate_password( 8, true );
				$user_id = wp_create_user( $login, $random_password, $email ); 
				wp_set_password( $random_password, $user_id );

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

				// to store plain text login info
				$user_info_arr = serialize(array($login,$random_password));
				update_user_meta( $user_id, 'user_login_info', $user_info_arr);

				// linking the user ID with profile ID
				$wpdb->update(table_agency_profile,
					array( 'ProfileUserLinked' => $user_id ),
					array( 'ProfileID' => $profile_row[0]->ProfileID ),
					array( '%d' ),
					array( '%d' )
				);
				add_user_meta( $user_id, 'rb_agency_interact_profiletype',true);

				// Link WP ID to a Talent Profile
				$wpdb->query($wpdb->prepare("UPDATE ".table_agency_profile." SET ProfileUserLinked = %d WHERE ProfileID = %d", $user_id, $profileid));

				send_email_lp($login, $random_password, $email);

				echo 'SUCCESS';
			} else {
				if($generate_pass){
							// creating new user
							$user_id = email_exists($email);
							$random_password = $password; //wp_generate_password( 8, true );
							add_user_meta( $user_id, 'rb_agency_interact_profiletype',true);
							update_user_meta( $user_id, 'rb_agency_interact_profiletype',true);
							$user = get_userdata( $user_id );
							wp_set_password( $random_password, $user_id );

							// Link WP ID to a Talent Profile
							$wpdb->query($wpdb->prepare("UPDATE ".table_agency_profile." SET ProfileUserLinked = %d WHERE ProfileID = %d", $user_id, $profileid));

							send_email_lp($user->user_login, $random_password, $email);

						echo 'SUCCESS';
				} else {
					echo $user_id->errors['existing_user_login'][0];
				}
			}

		/*} else {
			echo 'The user is already registrated!';
		}*/

		die;
	}

	add_action('wp_ajax_send_bulk_mail', 'bulk_register_and_send_email');

	function bulk_register_and_send_email(){
		global $wpdb;

		$users_lp = $_POST['users_pl'];
		//echo '<pre>'; print_r($users_lp);
		$generate_pass = $_POST["generatepass"];

		$success = FALSE;
		$arr_email = array();

		foreach($users_lp as $user_lp){
			$profile_row = $wpdb->get_results( "SELECT ProfileID, ProfileUserLinked, ProfileContactEmail, ProfileContactDisplay, ProfileContactNameFirst, ProfileContactNameLast FROM ". table_agency_profile ." WHERE ProfileID = '" . $user_lp['pid'] . "'");

				if(!email_exists($profile_row[0]->ProfileContactEmail)){

				$random_password = $user_lp['password']; //wp_generate_password( 8, true );
				$user_id = wp_create_user( $user_lp['login'], $random_password, $profile_row[0]->ProfileContactEmail ); 
				wp_set_password( $random_password, $user_id );

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

				// to store plain text login info
				$user_info_arr = serialize(array($user_lp['login'],$random_password));
				update_user_meta( $user_id, 'user_login_info', $user_info_arr);

				// linking the user ID with profile ID
				$wpdb->update(table_agency_profile,
					array( 'ProfileUserLinked' => $user_id ),
					array( 'ProfileID' => $profile_row[0]->ProfileID ),
					array( '%d' ),
					array( '%d' )
				);
				add_user_meta( $user_id, 'rb_agency_interact_profiletype',true);

				// Link WP ID to a Talent Profile
				$wpdb->query($wpdb->prepare("UPDATE ".table_agency_profile." SET ProfileUserLinked = %d WHERE ProfileID = %d", $user_id, $profile_row[0]->ProfileID));

				send_email_lp($user_lp['login'], $random_password, $profile_row[0]->ProfileContactEmail);

				array_push($arr_email , $profile_row[0]->ProfileContactEmail);

					$success = TRUE;
			} else {
				if($generate_pass){
							$user_id = $profile_row[0]->ProfileUserLinked;
							$random_password = $user_lp['password']; //wp_generate_password( 8, true );
							update_user_meta( $user_id, 'rb_agency_interact_profiletype',true);
							wp_set_password( $random_password, $user_id );

							// Link WP ID to a Talent Profile
							$wpdb->query($wpdb->prepare("UPDATE ".table_agency_profile." SET ProfileUserLinked = %d WHERE ProfileID = %d", $user_id, $profile_row[0]->ProfileID));

							send_email_lp($user_lp['login'], $random_password, $profile_row[0]->ProfileContactEmail);
							array_push($arr_email , array("generate",$user_lp['login'], $random_password ,$profile_row[0]->ProfileContactEmail));
							//var_dump($arr_email);
							$success = TRUE;
				} else {
					$success = FALSE; // $user_id->errors['existing_user_login'][0];
				}
			}
	/*		$user_id = username_exists( $profile_row[0]->ProfileContactDisplay );
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

					// to store plain text login info
					$user_info_arr = serialize(array($user_lp['login'], $user_lp['password']));
					update_user_meta( $user_id, 'user_login_info', $user_info_arr);

					// linking the user ID with profile ID
					$wpdb->update( 
						table_agency_profile,
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
			}*/   
		}

		if($success){
			echo 'SUCCESS';
		}

		die;
	}

	function send_email_lp($login, $password, $email){
		$admin_email = get_bloginfo('admin_email');
		$site_name = get_bloginfo("name");

		//$headers = 'From: '.$site_name.' <' . $admin_email . '>\r\n';

		$headers = 'From: '. $site_name .' <'. $admin_email .'>' . "\r\n";

		$subject = 'Your new Login and Password';

		$message = read_email_content(true);
		if($message == 'empty'){
			$message = "Hello, we generated new login and password for you at RB Agency\n\n[login]\n[password]\n\nYou can login [url]\n\nThanks.";
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


	function get_state_ajax(){
			global $wpdb;
			$states = array();
			$country = $_POST['country'];
			$query_get ="SELECT * FROM ".table_agency_data_state." WHERE CountryID='".$country."'";
			$result_query_get = $wpdb->get_results($query_get);
			echo json_encode($result_query_get);
			die;
	}
	add_action('wp_ajax_get_state_ajax', 'get_state_ajax');
	add_action('wp_ajax_nopriv_get_state_ajax', 'get_state_ajax');



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

					$r = $wpdb->get_results($q,ARRAY_A);

					$countList = count($r);

					$array_data = array();

					foreach($r as $dataList) {

						/*
						 * Get From Custom Fields
						 * per profile
						 */
						$get_custom = 'SELECT * FROM ' . table_agency_customfield_mux .
										' WHERE ProfileID = %d'; 

						$result = $wpdb->get_results($wpdb->prepare($get_custom,$dataList["ProfileID"]),ARRAY_A);

						$desc_list = array('shoes', 'eyes', 'shoes', 'skin');

						$a_male = array('height','weight','waist', 'skin tone',
										'eye color',  'shoe size', 'shirt');

						$array_male = array();
						$array_female = array();

						$a_female = array('bust', 'waist', 'hips', 'dress',
												'shoe size','hair', 'eye color');

						$name = ucfirst($dataList["ProfileContactNameFirst"]) ." ". strtoupper($dataList["ProfileContactNameLast"][0]); ;

						foreach ($result as $custom) {

							$get_title = 'SELECT ProfileCustomTitle FROM ' . table_agency_customfields .
							' WHERE ProfileCustomID = ' . $custom["ProfileCustomID"] ; 

							$result2 = $wpdb->get_results($get_title,ARRAY_A);

							$custom2 = current($result2);

							if(strtolower(RBAgency_Common::profile_meta_gendertitle($dataList['ProfileGender'])) == "male"){

								if(in_array(strtolower($custom2['ProfileCustomTitle']),$a_male)){
									$array_male[$custom2['ProfileCustomTitle']] = $custom['ProfileCustomValue'];
								}

							} else if(strtolower(RBAgency_Common::profile_meta_gendertitle($dataList['ProfileGender'])) == "female"){

								if(in_array(strtolower($custom2['ProfileCustomTitle']),$a_female)){
									$array_female[$custom2['ProfileCustomTitle']] = $custom['ProfileCustomValue'];
								}
							}

						}

						if(strtolower(RBAgency_Common::profile_meta_gendertitle($dataList['ProfileGender'])) == "male"){

							$array_data = array($name,'male',$array_male,$dataList["ProfileGallery"],$dataList["ProfileMediaURL"]);

						} else if(strtolower(RBAgency_Common::profile_meta_gendertitle($dataList['ProfileGender'])) == "female"){

							$array_data = array($name,'female',$array_female,$dataList["ProfileGallery"],$dataList["ProfileMediaURL"]);

						}

					}

		return $array_data;

	}

	// *************************************************************************************************** //
	/*
	 *  Shortcodes
	 */
		// Search Form
		function rb_agency_searchform($DataTypeID) {
			$profilesearch_layout = "simple";
			include("theme/include-profile-search.php");
		}

	// 5/15/2013 sverma@ Home page
	function featured_homepage_profile($count,$type){
		global $wpdb;
		$dataList = array();
		$query = "SELECT profile.*,
		(SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media 
		WHERE profile.ProfileID = media.ProfileID 
		AND media.ProfileMediaType = \"Image\" 
		AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL 

		FROM ". table_agency_profile ." profile 
		WHERE profile.ProfileIsActive = 1 ".(isset($sql) ? $sql : "") ."
		AND profile.ProfileIsFeatured = 1
		AND FIND_IN_SET(".$type.",profile.ProfileType) <> 0
		ORDER BY profile.ProfileContactDisplay LIMIT 0,".$count;

		$result = $wpdb->get_results($query,ARRAY_A);
		$i=0;
		foreach($result as $row) {
			$dataList[$i] = $row ;
			$i++;
		}
		return $dataList ;

	}


	function get_profiletype_id($label){
		global $wpdb;
		$check_type = "SELECT DataTypeID FROM ". table_agency_data_type ." WHERE DataTypeTitle = %s";
			$check_query = $wpdb->get_results($wpdb->prepare($check_type, $label),ARRAY_A);// OR die($wpdb->print_error());
			if(count($check_query) > 0){
				$fetch = current($check_query);
				return $fetch['DataTypeID'];
			} else {
				return false;
			}
	}

	function primary_class(){
		return $class = "col_8";
	}

	function secondary_class(){
		return $class = "col_4";
	}

	function fullwidth_class(){
		return $class = "col_12";
	}
	/*
	 * recreate custom field search
	 */
	function recreate_custom_search($GET){
	// TODO REMOVED


	}

	/*
	 * load script for printing profile pdf 
	 */
	function rb_load_profile_pdf($row = 0, $logo = NULL){

	$profile_name = get_current_profile_info("ProfileContactDisplay");
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_agencylogo = !empty($rb_agency_options_arr['rb_agency_option_agencylogo'])?$rb_agency_options_arr['rb_agency_option_agencylogo']:get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/img/logo_example.jpg";
	$file_name = str_replace(" ", "", $profile_name); ?>
	

	<!-- ajax submit login -->
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#print_pr_pdf').click(function(){
			jQuery(this).text("PRINTING...");
			jQuery.ajax({
				type: 'POST',
				dataType: 'html',
				url: '<?php echo plugins_url("rb-agency/theme/print_profile_pdf.php"); ?>',
				data: {
					pid : <?php echo get_current_profile_info("ProfileID"); ?>, 
					row : <?php echo $row; ?>, 
					logo : "<?php echo get_site_url().$rb_agency_option_agencylogo; ?>",
					//logo : "<?php echo $logo; ?>",
					filename : "<?php echo $file_name; ?>"},
				success: function(response){
					if (response){
						jQuery('#print_pr_pdf').text("PRINT PDF");
						var lnk = response;
						var st = lnk.indexOf("http");
						lnk =  lnk.substring(st);
						document.location.href = lnk;
					} else {
						jQuery(this).text("ERROR...");
					}
				}
			});
		});
	});
	</script>        

	<?php        
	}

	/*
	 * current profile. 
	 * this is for files when profile ID is not available
	 * specially "header files"
	 * to get the current profile info not user info
	 * TODO, should also return any info needed for profiles
	 */
	function get_current_profile_info($data=NULL){

			global $wpdb;

			$uri = $_SERVER['REQUEST_URI'];

			if(!rb_is_page("rb_profile")) return false;

			$contact = strpos($uri,"/profile/");
			$contact = substr($uri,$contact+9);
			$contact = str_replace("/","",$contact);

			$query = "SELECT * FROM " . table_agency_profile .
					" WHERE ProfileGallery = '" . $contact . "'";

			$contact = $wpdb->get_results($query);

			if(count($contact)>0){
				foreach($contact as $c){
					if(is_null($data)){
						return ucwords(str_replace("-"," ",$c->ProfileContactDisplay));
			} else {
				return $c->$data;
			}
				}
			}

			return false;

	}

	/*
	 * Get primary image for profiles
	 */
	function rb_get_primary_image($PID){

		global $wpdb;

		if(empty($PID) or is_null($PID)) return false;

		if(isset($_SESSION["profilephotos_view"])){
			$arr_thumbnail = (array)unserialize($_SESSION["profilephotos_view"]);
			if(isset($arr_thumbnail[$PID])){
				$get_image = "SELECT ProfileMediaURL FROM ". table_agency_profile_media .
						" WHERE ProfileID = " .$PID . " AND ProfileMediaID =".$arr_thumbnail[$PID];
			} else {
				$get_image = "SELECT ProfileMediaURL FROM ". table_agency_profile_media .
						" WHERE ProfileID = " .$PID . " AND ProfileMediaPrimary = 1";
			}

		} else {
			$get_image = "SELECT ProfileMediaURL FROM ". table_agency_profile_media .
						" WHERE ProfileID = " .$PID . " AND ProfileMediaPrimary = 1";
		}

		$get_res = $wpdb->get_row($get_image,ARRAY_A);

		if($wpdb->num_rows > 0){
			//foreach($get_res as $data){
				return $get_res['ProfileMediaURL'];
			//}
		}

		return false;
	}
	/*
	 * check page
	 */
	function rb_is_page($page){

		if(empty($page)) {return false; }

		$uri = $_SERVER['REQUEST_URI'];

		if((strpos($uri,"/profile/") > -1 && $page == "rb_profile" ) ||
			(strpos($uri,"/dashboard/") > -1 && $page == "rb_dashboard") ||
			(strpos($uri,"/profile-category/") > -1 && $page == "rb_category") ||
			(strpos($uri,"/profile-register/") > 1 && $page == "rb_register") ||
			(strpos($uri,"/profile-search/") > -1 && $page == "profile_search")	||
			(strpos($uri,"/search-basic/") > -1 && $page == "basic_search")	||
			(strpos($uri,"/search-advanced/") > -1 && $page == "advanced_search") ||
			(strpos($uri,"/profile-print/") > -1 && $page == "rb_print") ||
			(strpos($uri,"/profile-casting/") > -1 && $page == "rb_casting") ||
			(strpos($uri,"/profile-favorites/") > -1 && $page == "rb_favorites") ||
			(strpos($uri,"/search-results/") > -1 && $page == "search_results") ||
			// RB Agency Casting Pages
			(strpos($uri,"/casting-login/") > -1 && $page == "casting_login" ) ||
			(strpos($uri,"/casting-register/") > -1 && $page == "casting_register" ) ||
			(strpos($uri,"/casting-manage/") > -1 && $page == "casting_manage" ) ||
			(strpos($uri,"/browse-jobs/") > -1 && $page == "browse_jobs" ) ||
			(strpos($uri,"/view-applicants/") > -1 && $page == "view_applicants" ) ||
			(strpos($uri,"/profile-casting/") > -1 && $page == "profile_casting" ) ||
			(strpos($uri,"/casting-postjob/") > -1 && $page == "casting_postjob" ) ||
			(strpos($uri,"/casting-dashboard/") > -1 && $page == "casting_dashboard" ) ||
			(strpos($uri,"/job-application/job-detail/") > -1 && $page == "job_detail" ) ||
			// RB Interact Pages
			(strpos($uri,"/profile-register/") > -1 && $page == "profile_register" ) ||
			(strpos($uri,"/profile-login/") > -1 && $page == "profile_login" ) ||
			(strpos($uri,"/profile-member/") > -1 && $page == "profile_member" )) {

				return true;
			}

			return false;
	}
	/*
	 *	Rb Agency login checker 
	 */
	function rb_agency_log(){
		check_ajax_referer( 'ajax-login-nonce', 'security' );
		$login_info = array();
		$login_info['user_login'] = $_POST['username'];
		$login_info['user_password'] = $_POST['password'];
		$login_info['remember'] = true;
		$user_login = wp_signon( $login_info, false );
		if ( is_wp_error($user_login) ){
			echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
		} else {
			echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
		}
		die();
	}
	/*
	 *	Rb Agency Search Profile via Ajax 
	 */
	
	
	function convert_custom_types($data = NULL){

			global $wpdb;

			$dat = explode("|",$data);

			$custom_fields = array();
			if(count($dat) > 0){
				foreach($dat as $d){
					$x = explode("/",$d);
					$custom_fields[$x[0]] = isset($x[1])?$x[1]:"";
				}
			}

			return $custom_fields;

	}
	
	
	function rb_agency_search_profile(){

		global $wpdb;
		$data = isset($_POST['value'])?trim($_POST['value']):get_option('custom_fields_criteria');

		$and_selected_critarea_for_gender = '' ; 
		
		$custom_field_and = '' ;
		
		if(!empty($data))
		{
			$data_custom_types = convert_custom_types($data);
			if(!empty($data_custom_types['gender']))
			{
				$gender = $data_custom_types['gender'] ;
				$and_selected_critarea_for_gender = "AND profile.ProfileGender = '".$gender."'" . ' ';
			}
			
			unset($data_custom_types['gender']);
			if(count($data_custom_types) > 0)
			{
				
				foreach($data_custom_types as $key=>$data_custom_type)
				{
					if($data_custom_type != ''  && $data_custom_type != 'null'  && $data_custom_type != 'undefined' && !empty($data_custom_type)  )
					{
						$custom_field_and .= "AND customfield.ProfileCustomID = $key".' ';
						$custom_field_and .= "AND customfield.ProfileCustomValue = '".$data_custom_type."'" . ' ';
					}
					
				}
				
			}
				
			
		}

		//$results = $wpdb->get_results("SELECT gender.*, media.ProfileMediaURL, profile.ProfileGallery,profile.ProfileDateBirth, profile.ProfileID, profile.ProfileContactNameFirst, profile.ProfileContactNameLast, profile.ProfileGender FROM ".table_agency_profile." as profile INNER JOIN ".table_agency_data_gender." as gender ON gender.GenderID = profile.ProfileGender INNER JOIN ".table_agency_profile_media." as media ON (media.ProfileID = profile.ProfileID AND media.ProfileMediaType = 'Image') GROUP BY ProfileID ORDER BY ProfileContactNameFirst",ARRAY_A);
		
		$results = $wpdb->get_results("SELECT gender.*, media.ProfileMediaURL, profile.ProfileGallery,profile.ProfileDateBirth, profile.ProfileID, profile.ProfileContactNameFirst, profile.ProfileContactNameLast, profile.ProfileGender ,profile.ProfileIsActive  , customfield.ProfileCustomMuxID , customfield.ProfileCustomID , customfield.ProfileID , customfield.ProfileCustomValue FROM ".table_agency_profile." as profile LEFT JOIN ".table_agency_customfield_mux." as customfield ON (profile.ProfileID = customfield.ProfileID) LEFT JOIN ".table_agency_profile_media." as media ON (media.ProfileID = profile.ProfileID AND media.ProfileMediaType = 'Image') INNER JOIN ".table_agency_data_gender." as gender ON gender.GenderID = profile.ProfileGender WHERE profile.ProfileIsActive = 1 ".$and_selected_critarea_for_gender." ".$custom_field_and." GROUP BY profile.ProfileID ORDER BY profile.ProfileContactNameFirst",ARRAY_A);
		
		echo json_encode($results);

		die();
	}
	add_action('wp_ajax_rb_agency_search_profile', 'rb_agency_search_profile');
	add_action('wp_ajax_nopriv_rb_agency_search_profile', 'rb_agency_search_profile');
	/*
	 *	Rb Agency Get Profile Photos via Ajax 
	 */
	function rb_agency_profile_photos(){

		global $wpdb;

		$results = $wpdb->get_results($wpdb->prepare("SELECT media.* FROM  ".table_agency_profile_media." as media WHERE media.ProfileMediaType = 'Image' AND  media.ProfileID = %s",$_POST["profileID"]),ARRAY_A);
		echo json_encode($results);

		die();
	}
	add_action('wp_ajax_rb_agency_profile_photos', 'rb_agency_profile_photos');
	add_action('wp_ajax_nopriv_rb_agency_profile_photos', 'rb_agency_profile_photos');
	/*
	 *	Rb Agency Save Profile Photos via Ajax 
	 */
	function rb_agency_save_profile_photos(){

		global $wpdb;
		if(!isset($_SESSION["profilephotos"])){
			$_SESSION["profilephotos"] = serialize(array_filter($_POST["profilephotos"]));
		} else {
			$thumbs = array();

			foreach ((array)unserialize($_SESSION["profilephotos"]) as $key => $value) {
				$thumbs[$key] = $value;
			}
			foreach ((array)unserialize(serialize(array_filter($_POST["profilephotos"]))) as $key => $value) {
				$thumbs[$key] = $value;
			}
			$_SESSION["profilephotos"] = serialize($thumbs);
		}
		die();

	}
	add_action('wp_ajax_rb_agency_save_profile_photos', 'rb_agency_save_profile_photos');
	add_action('wp_ajax_nopriv_rb_agency_save_profile_photos', 'rb_agency_save_profile_photos');

	/*
	 *	Rb Agency Clear Search filter session via Ajax 
	 */
	function rb_agency_clear_casting_array(){
		if (!session_id()) {
					session_start();
		}
		/* foreach($_SESSION as $key => $val){
			unset($_SESSION[$key]);
		}*/
		die();
	}
	add_action('wp_ajax_rb_agency_clear_casting_array', 'rb_agency_clear_casting_array');
	add_action('wp_ajax_nopriv_rb_agency_clear_casting_array', 'rb_agency_clear_casting_array');


	/*
	 * Add action hook if interact is inactive
	 */
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
	if(!function_exists('rb_agency_interact_menu')){
		add_action('wp_ajax_rb_agency_log', 'rb_agency_log');
		add_action('wp_ajax_nopriv_rb_agency_log', 'rb_agency_log');
		add_action('wp_logout','redirect_to_home');
		function redirect_to_home(){
			wp_redirect(home_url());
			exit();
		}
	}

	/*
	 * WP Login Form
	 * TODO REMOVE
	 */
	function rb_loginform($redirect){ ?>
			<!-- ajax submit login -->
			<script type="text/javascript">
			jQuery(document).ready(function(){
				// Perform AJAX login on form submit
				jQuery('#submit_login').on('click', function(){
					jQuery('#rbsign-in p.status').show().text("logging in...");
					jQuery.ajax({
						type: 'POST',
						dataType: 'json',
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: {
							'action': 'rb_agency_log', 
							'username': jQuery('#username').val(), 
							'password': jQuery('#password').val(), 
							'security': jQuery('#security').val() },
						success: function(data){
							if (data.loggedin == true){
								document.location.href = "<?php echo $redirect; ?>";
							} else {
								jQuery('#rbsign-in p.status').text(data.message);
							}
						}
					});
				});
			});
			</script>
			<?php
			//
			// Login Form
			//
			echo '	<div id="rbsignin-register" class="rbinteract">';
			echo '        <div id="rbsign-in" class="inline-block">';
			echo '          <h1>'. __("Sign in", rb_agencyinteract_TEXTDOMAIN). '</h1>';
			echo '          <p class="status" style="display:none"></p>'; 
			echo '          <form name="loginform" id="login_ajax" action="login" method="post">';
			echo '            <div class="field-row">';
			echo '              <label for="user-name">'. __("Username", rb_agencyinteract_TEXTDOMAIN). '</label><input type="text" name="user-name" value="'. wp_specialchars( $_POST['user-name'], 1 ) .'" id="username" />';
			echo '            </div>';
			echo '            <div class="field-row">';
			echo '              <label for="password">'. __("Password", rb_agencyinteract_TEXTDOMAIN). '</label><input type="password" name="password" value="" id="password" /> <a href="'. get_bloginfo('wpurl') .'/wp-login.php?action=lostpassword">'. __("forgot password", rb_agencyinteract_TEXTDOMAIN). '?</a>';
			echo '            </div>';
			echo '            <div class="field-row submit-row">';
			echo '              <input type="button" id="submit_login" value="'. __("Sign In", rb_agencyinteract_TEXTDOMAIN).'" /><br />';
			echo '            </div>';
								wp_nonce_field( 'ajax-login-nonce', 'security' );
			echo '          </form>';
			echo '        </div> <!-- rbsign-in -->';
			echo '      <div class="clear line"></div>';
			echo '      </div>';
	}

	/*
	 * Get Current URl for redirection
	 */
	function rb_current_url(){

		$URL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$URL .= "s";}
		$URL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$URL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$URL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $URL;
	}

	/*
	 * Check casting cart / add fav if permitted to be displayed
	 */
	function is_permitted($type){

		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_privacy = $rb_agency_options_arr['rb_agency_option_privacy'];
		$rb_agency_option_profilelist_castingcart  = isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart'] : 0;
		$rb_agency_option_profilelist_favorite	= isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;

		if($type=="casting" && !$rb_agency_option_profilelist_castingcart) return false;
		if($type=="favorite" && !$rb_agency_option_profilelist_favorite) return false;
		if(!is_user_logged_in()) return false;

		if($type == "casting" || $type == "favorite" ){

			if ( ($rb_agency_option_privacy == 2) || 

				// Model list public. Must be logged to view profile information
				($rb_agency_option_privacy == 1) ||

				// Model list public and information
				($rb_agency_option_privacy == 0) ||

				//admin users
				(current_user_can( 'edit_posts' )) ||

				//  Must be logged as "Client" to view model list and profile information
				($rb_agency_option_privacy == 3 && is_client_profiletype()) ) {

				return true;
			}
		}
		return false;
	}


	/**
	 * Clean String, remove extra quotes
	 *
	 * @param string $string
	 */
		function rb_agency_cleanString($string) {
			// Remove trailing dingleberry
			if (substr($string, -1) == ",") { $string = substr($string, 0, strlen($string)-1); }
			if (substr($string, 0, 1) == ",") {$string = substr($string, 1, strlen($string)-1); }
			// Just Incase
			$string = str_replace(",,", ",", $string);

			return $string;
		}


	/*
	 * Check if profilet type ID is "Client" type
	 */
	function is_client_profiletype(){

		global $current_user;
		global $wpdb;

		$query = "SELECT ProfileType FROM ". table_agency_profile ." WHERE ProfileUserLinked = ". rb_agency_get_current_userid();
		$results = $wpdb->get_results($query,ARRAY_A);

		if(count($results) > 0){
			/*$id = current($results);
			$id = $id['ProfileType'];
			$queryList = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID = ". $id;
			$resultsList = $wpdb->get_results($queryList,ARRAY_A);
			foreach($resultsList as $d) {
				if(strtolower($d["DataTypeTitle"]) == "client"){
					return false;
				}
			}	 */
			/*
			 * lets check postmeta field
			 */
			//$check_type = get_user_meta($current_user->ID, 'rb_agency_interact_clientdata', true);
			//if($check_type != ""){
			return false;
			//}
		}
		/*
		 * lets check postmeta field
		 */
		else {
			$check_type = get_user_meta($current_user->ID, 'rb_agency_interact_clientdata', true);
			if($check_type != ""){
				return false;
			}
		}

		return true;
	}

	/*
	 * Get current user's profile type
	 */
	function rb_get_profiletype(){

		global $current_user;
		global $wpdb;

		$query = "SELECT ProfileType FROM ". table_agency_profile ." WHERE ProfileUserLinked = ". rb_agency_get_current_userid();
		$results = $wpdb->get_results($query,ARRAY_A);

		if(count($results) > 0){
			$id = current($results);
			$id = $id['ProfileType'];
			$queryList = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID = ". $id;
			$resultsList = $wpdb->get_results($queryList,ARRAY_A);
			foreach($resultsList as $d) {
				echo "You are logged in as ".ucfirst($d["DataTypeTitle"]).", please <a href=".wp_logout_url().">logout</a> first.";
			}

		} else {
			echo "You are logged in as Model/Talent, please <a href=".wp_logout_url().">logout</a> first.";
		}
	}



	// Get State Name by State ID
	function get_state_by_id($StateID){
		global $wpdb;

		$results = $wpdb->get_row($wpdb->prepare("SELECT StateTitle FROM ".table_agency_data_state." WHERE StateID = %d", $StateID), ARRAY_A);
		return $results['StateTitle'];

	}

	// Get State Abreviation by State ID
	function get_stateabv_by_id($StateID){
		global $wpdb;

		$results = $wpdb->get_row($wpdb->prepare("SELECT StateCode FROM ".table_agency_data_state." WHERE StateID = %d", $StateID), ARRAY_A);
		return $results['StateCode'];

	}


	/**
	 * Genrate query for gallery Order
	 */
	function rb_agency_option_galleryorder_query($order,$profileID, $ProfileMediaType, $count = 99, $exclude_primary = false){
		global $wpdb;

		$queryImg = "";

		if ($count > 0) {
			$sql_count = " LIMIT ". $count;
		} else {
			$sql_count = "";
		}

		if ($exclude_primary == true) {
			$sql_exclude_primary_image = " AND ProfileMediaPrimary = 0";
			$sql_exclude_primary_image = "";
		} else {
			$sql_exclude_primary_image = "";
		}

		if($order == 1){
			$queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"%s\" AND ProfileMediaType = \"%s\" ". $sql_exclude_primary_image ." GROUP BY(ProfileMediaURL) ORDER BY ProfileMediaPrimary DESC, ProfileMediaID DESC ". $sql_count, $profileID, $ProfileMediaType);
		} else {
			$queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"%s\" AND ProfileMediaType = \"%s\" ". $sql_exclude_primary_image ." GROUP BY(ProfileMediaURL) ORDER BY convert(`ProfileMediaOrder`, decimal)  ASC ". $sql_count, $profileID, $ProfileMediaType);
		}

		return $queryImg ;
	}

	function rb_agency_option_galleryorder_boxcover_query($order,$profileID, $ProfileMediaType, $count = 99, $exclude_primary = false){
		global $wpdb;

		$queryImg = "";

		if ($count > 0) {
			$sql_count = " LIMIT ". $count;
		} else {
			$sql_count = "";
		}

		if ($sql_exclude_primary_image = true) {
			//$sql_exclude_primary_image = " AND ProfileMediaPrimary = 0";
			$sql_exclude_primary_image = "";
		} else {
			$sql_exclude_primary_image = "";
		}

		if($order){
			$queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"%s\" AND (ProfileMediaType = 'dvd' OR ProfileMediaType = 'magazine') ". $sql_exclude_primary_image ." GROUP BY(ProfileMediaURL) ORDER BY ProfileMediaID DESC,ProfileMediaPrimary DESC ". $sql_count, $profileID);
		} else {
			$queryImg = $wpdb->prepare("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"%s\" AND (ProfileMediaType = 'dvd' OR ProfileMediaType = 'magazine') ". $sql_exclude_primary_image ." GROUP BY(ProfileMediaURL) ORDER BY convert(`ProfileMediaOrder`, decimal)  ASC ". $sql_count, $profileID);
		}

		return $queryImg ;
	}

	/**
	 * User group permission redirect
	 */
	function rb_agency_group_permission($group){

		global $user_ID;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$is_model = get_user_meta( $user_ID, 'rb_agency_interact_profiletype',true);

		if(is_user_logged_in() /* &&  !current_user_can("edit_posts") && is_plugin_active("rb-agency-casting") */){
				if($group == "casting"){ //if we are in a casting page while using a model/talent profile, redirect to profile member paage
					if(!empty($is_model)){
					wp_safe_redirect(get_bloginfo("url")."/profile-member/");
					}
				} elseif($group == "models"){ //if casting is accessing model/talent page, redirect back to casting dashboard
					if(empty($is_model )){
					// wp_safe_redirect(get_bloginfo("url")."/casting-dashboard/");
					}
				}
		}
	}

	/**
	 * Custom logout link
	 */
	add_action('logout_url','rb_redirect_logout');
	function rb_redirect_logout(){
		$logout_link =  get_bloginfo("url")."/logout/";
		return $logout_link;
	}

	/**
	 * Set logout redirect per user group
	 */
	function rb_logout_user(){
		global $user_ID, $wpdb;
			//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if(is_user_logged_in()){

						$is_model = get_user_meta( $user_ID, 'rb_agency_interact_profiletype',true);
						if(empty($is_model)){
							$is_casting = $wpdb->get_row($wpdb->prepare("SELECT CastingID FROM ".table_agency_casting." WHERE CastingUserLinked = %d  ",$user_ID));
						}

						if(current_user_can("edit_posts")){
							wp_logout();
							wp_safe_redirect(admin_url());
						} else {
							$rb_agency_interact_options_arr = get_option('rb_agencyinteract_options');
							$rb_agencyinteract_option_redirect_custom_login = (int)$rb_agency_interact_options_arr['rb_agencyinteract_option_redirect_custom_login'];

							if($rb_agencyinteract_option_redirect_custom_login == 2){
								wp_logout();
								wp_safe_redirect(get_bloginfo("url"));
							} else {
								if(class_exists("RBAgencyCasting")){
									if(!empty($is_model )){
										wp_logout();
										wp_safe_redirect(get_bloginfo("url")."/profile-login/");
									} elseif(!empty($is_casting)) {
										wp_logout();
										wp_safe_redirect(get_bloginfo("url")."/casting-login/");
									}
								} else {
									wp_logout();
									wp_safe_redirect(get_bloginfo("url")."/profile-login/");
								}
							}
						}

				} else {
					wp_safe_redirect(get_bloginfo("url")."/profile-login/");
				}
	}

	/**
	 * Get RB ProfileID
	 */
	function rb_get_casting_profileid(){
		if(!defined("table_agency_casting"))
				return false;
		global $user_ID, $wpdb;
		$data = $wpdb->get_row($wpdb->prepare("SELECT CastingID FROM ".table_agency_casting." WHERE CastingUserLinked = %d ", $user_ID));
		if($wpdb->num_rows > 0){
			return $data->CastingID;
		} else {
			return false;
		}
	}

	/**
	 * Get Profile media Link Label 
	 */
	function rb_get_profile_link_label(){
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilemedia_links = isset($rb_agency_options_arr["rb_agency_option_profilemedia_links"])?$rb_agency_options_arr["rb_agency_option_profilemedia_links"]:2;

		if($rb_agency_option_profilemedia_links == 2){
			return __("View", RBAGENCY_TEXTDOMAIN);
		} elseif($rb_agency_option_profilemedia_links == 3){
			return __("Download", RBAGENCY_TEXTDOMAIN);
		}
	}

	/**
	 * Get Profile Media Open type
	 */
	function rb_get_profilemedia_link_opentype($url,$is_docs = false,$ext_url=false){
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilemedia_links = isset($rb_agency_options_arr["rb_agency_option_profilemedia_links"])?$rb_agency_options_arr["rb_agency_option_profilemedia_links"]:2;

		$_fullURL = RBAGENCY_UPLOADDIR . $url;
		if($ext_url == true){
			$_fullURL = $url;
		}
		
		if($rb_agency_option_profilemedia_links == 2){
			if($is_docs){
				return " href=\"javascript:;\" onclick=\"window.open('https://docs.google.com/viewer?url=".$_fullURL."', '_blank', 'toolbar=0,menubar=0');\" ";
			} else {
				return " href=\"javascript:;\" onclick=\"window.open('".$_fullURL."', '_blank', 'toolbar=0,location=4,menubar=0');\" ";
			}
		} elseif($rb_agency_option_profilemedia_links == 3){
			if($ext_url == true){
				return " href=\"".$url."\" ";
			}
			
			//return " href=\"".RBAGENCY_PLUGIN_URL."ext/forcedownload.php?file=".$url."\" ";
			//return " href=\"wp-content\plugins\wpfdl.php?dl=".$url."&token=".wpfdl_generate_token()."\" ";
			//require_once(RBAGENCY_PLUGIN_URL.'/view/helper/rbdl.php');
			return wpfdl_dl($url,get_option('wpfdl_token'),'dl');

			
		}
	}

	/**
	 * Mail Character encoding
	 * Todo: Add email charset setting - Champ
	 */
	//add_filter( 'wp_mail_charset', 'rb_change_mail_charset' );
	function rb_change_mail_charset( $charset ) {
		return 'UTF-32';
	}

	/**
	 * Convert Height and format
	 * @param value (default: ft/in/lb) string
	 * @param unit integer
	 * @param label boolean
	 */

	function rb_get_imperial_metrics($value,$sub_unit = 1,$label = true){
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;
		$rb_agency_option_old_unittype = isset($rb_agency_options_arr['rb_agency_option_old_unittype'])?$rb_agency_options_arr['rb_agency_option_old_unittype']:'';

		if ($rb_agency_option_unittype == 0){ // Metric

			if($sub_unit == 1 ){ // inch to cm
				if(!empty($rb_agency_option_old_unittype)){
					$heightraw = $value;
					$heightcm = $heightraw * 2.48;
					return $heightcm.($label?" ".__("cm", RBAGENCY_TEXTDOMAIN):"");
				} else {
					return $value.($label?" ".__("cm", RBAGENCY_TEXTDOMAIN):"");
				}
			} elseif($sub_unit == 2){ // lb to kg
				if(!empty($rb_agency_option_old_unittype)){
				$weightraw = $value;
				$weightkg = ceil($weightraw / 2.2046);
				return $weightkg.($label?" ".__("kg", RBAGENCY_TEXTDOMAIN):"");
				} else {
					return $value.($label?" ".__("kg", RBAGENCY_TEXTDOMAIN):"");
				}
			}
			return $value;

		} elseif ($rb_agency_option_unittype == 1){ // Imperial
			if($sub_unit == 1 || $sub_unit == 3 ){ // inches
			$heightraw = $value;
			$heightfeet = floor($heightraw/12);
			$heightinch = $heightraw - floor($heightfeet*12);
				if($sub_unit == 3){ //ft and inches
					return $heightfeet.($label? " ". __("ft", RBAGENCY_TEXTDOMAIN). " ". $heightinch ." ". __("in", RBAGENCY_TEXTDOMAIN):"");
				} else { //inches only
					return $value.($label? " ". __("in", RBAGENCY_TEXTDOMAIN):"");
				}
			} elseif($sub_unit == 2){ // lb 
				return $value.($label?" ".__("lb",RBAGENCY_TEXTDOMAIN):"");
			}
			return $value;
		}

	}

	/**
	 * Change email headers
	 */

	function rb_fromname($email){
		$rb_agency_options = get_option('rb_agency_options');
		$rb_blogname = get_option('blogname');
		$wpfrom = !empty($rb_agency_options["rb_agency_option_agencyheader"])?$rb_agency_options["rb_agency_option_agencyheader"]:$rb_blogname;
		return $wpfrom;
	}
	//add_filter('wp_mail_from_name', 'rb_fromname');

	/**
	 * Check remote version
	 */
		function rb_get_remote_version(){
			$args = array('action' => 'version','url' => get_bloginfo('url'),'name' => get_bloginfo('name'),'admin_email' => get_bloginfo('admin_email'),'charset' => get_bloginfo('charset'),'version' => get_bloginfo('version'),'html_type' => get_bloginfo('html_type'),'language' => get_bloginfo('language'),'ip' => $_SERVER['REMOTE_ADDR']);
			$request = wp_remote_post(RBAGENCY_UPDATE_PATH, array('method'=>'POST','body' => $args));
			if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) == 200) {
				return $request['body'];
			}
		}
		function rb_get_remote_license(){
			$args = array('action' => 'license','url' => get_bloginfo('url'),'name' => get_bloginfo('name'),'admin_email' => get_bloginfo('admin_email'),'charset' => get_bloginfo('charset'),'version' => get_bloginfo('version'),'html_type' => get_bloginfo('html_type'),'language' => get_bloginfo('language'),'ip' => $_SERVER['REMOTE_ADDR']);
			$request = wp_remote_post(RBAGENCY_UPDATE_PATH, array('method'=>'POST','body' => $args));
			if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) == 200) {
				return $request['body'];
			}
		}

	/**
	 * Make Ago
	 */
	function rb_make_ago($datetime) {

		// Check if Timezone specified
		$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_locationtimezone = (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];

		$origin_dtz = new DateTimeZone($rb_agency_option_locationtimezone);

		$now = new DateTime("now", $origin_dtz);
		$ago = new DateTime($datetime, $origin_dtz);
		$diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);

		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	function load_admin_css() {
		if( is_admin() ){
			wp_enqueue_style("rbagencyadmin", plugins_url( '/assets/css/admin.css', __FILE__ ) );
			wp_enqueue_style("rbagencyadmin", plugins_url( '/assets/css/forms.css', __FILE__ ) );
			wp_enqueue_style('rbagency-datepicker', plugins_url( '/assets/css/jquery-ui/jquery-ui.css', __FILE__ ) );
				wp_enqueue_style('rbagency-datepicker-theme', plugins_url( '/assets/css/jquery-ui/jquery-ui.theme.min.css', __FILE__ ) );
		}

		wp_enqueue_style('rbagency-datepicker', plugins_url( '/assets/css/jquery-ui/jquery-ui.css', __FILE__ ) );
	}
	add_action('init','load_admin_css');

	function load_admin_js(){
		if(is_admin()){
			wp_enqueue_script( 'customfields', RBAGENCY_PLUGIN_URL .'assets/js/js-customfields.js', array( 'jquery' ) );
		}else{
			wp_enqueue_script( 'jquery-ui', RBAGENCY_PLUGIN_URL .'assets/js/jquery-ui.js', array( 'jquery' ) );
		}
		wp_enqueue_script( 'audiojs', RBAGENCY_PLUGIN_URL .'assets/audiojs/audio.min.js', array( 'jquery' ) );
	}
	add_action( 'init', 'load_admin_js' );

	function load_datetime_basic_search(){
		echo '<script type="text/javascript">
				jQuery(function(){
				/*
					jQuery( "input[id=rb_datepicker_from_bd]").datepicker({
						dateFormat: "yy-mm-dd"
					});
					jQuery( "input[id=rb_datepicker_to_bd]").datepicker({
						dateFormat: "yy-mm-dd"
					});
				*/
				});
				</script>';
	}
	add_action( 'wp_head', 'load_datetime_basic_search' );


add_action( 'widgets_init', 'rblogin_widget' );


function rblogin_widget() {
	register_widget( 'RBLogin_Widget' );
}

function date_difference($date1,$date2, $differenceFormat = '%a'){
	$datetime1 = date_create($date1);
    $datetime2 = date_create($date2);
    
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
}

function expired_profile_notification($data){
	$to = $data["send_to"];
	$subject = get_option('blogname')." ". $data["subject"]." Expiry Notification";
	$message = __($data["profile_name"]." ".$data["subject"]." ".$data["expired_date"]." has expired. Time to update this profile user.");
	wp_mail( $to, $subject, $message );
}


//Send notification to admin when expecting models reached her due date
function rb_send_notif_due_date_reached(){
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');
	
	
		//send notif
		$qq = "SELECT * FROM ".$wpdb->prefix."agency_customfields WHERE ProfileCustomType = 10";
		$qresults = $wpdb->get_results($qq,ARRAY_A);

		$CustomFields = array();
		foreach($qresults as $qres){
			$CustomFields[] = $qres['ProfileCustomTitle'];
		}
		
		foreach($CustomFields as $CustomField){

			

			$q2 = "SELECT * FROM ".$wpdb->prefix."agency_customfields cu INNER JOIN ".
				   $wpdb->prefix."agency_customfield_mux mu ON mu.ProfileCustomID = cu.ProfileCustomID INNER JOIN ".
				   $wpdb->prefix."agency_profile pr ON pr.ProfileID = mu.ProfileID ".
				   "WHERE cu.ProfileCustomTitle = '".$CustomField."'";
			$result2 = $wpdb->get_results($q2,ARRAY_A);
			$x=0;
			//loop and send mail
			foreach($result2 as $res2)
			{
				$x++;
				if($res2['ProfileCustomDateValue'] != '0000-00-00' || !empty($res2['ProfileCustomDateValue']) || $res2['ProfileCustomDateValue'] != ""){
					//$diff=date_difference($res2['ProfileCustomDateValue'],date("Y-m-d"));
					$expired = $res2['ProfileCustomDateValue'] < date("Y-m-d") ? 1 : 0;
				}else{
					$expired = 0;
				}

				
				if($expired == 0 && get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true) == 0){
					update_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",0);
				}

				if($expired == 0 && get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true) == 2){
					update_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",0);
				}

				if($expired == 1 && get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true) == 0){
					update_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",2);
				}

				if($expired == 0 && get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true) == 3){
					update_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",0);
				}

				if($expired == 1 && get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true) == 3){
					update_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",3);
				}

				if($res2['ProfileCustomDateValue'] != '0000-00-00' && !empty($res2['ProfileCustomDateValue']) && $expired > 0 && get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true) == 2 && get_option("ProfileCustomNotifyAdmin_".$res2['ProfileCustomID']) == 1){
		
					$data = array();
					unset($data);
					
					$data["send_to"] = get_option("admin_email");
					//$data["send_to"] = 'legend_slash@yahoo.com';
					$data["profile_name"] = $res2['ProfileContactDisplay'];	
					$data["subject"] = rbagency_get_customfield_title($res2['ProfileCustomID']);
					$data["expired_date"] = $res2['ProfileCustomDateValue'];
					expired_profile_notification($data);				
					//echo $data["subject"] ." ".$res2['ProfileCustomDateValue']." ".$expired." ".get_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",true)."<br>";
					update_user_meta($res2['ProfileID'],$res2['ProfileCustomID']."_".$res2['ProfileID']."_user_expired_sent",3);
				}	

				

				if($x==10){
					break;
				}
				
				
			}
		}		

	
}
add_action('init','rb_send_notif_due_date_reached');


//Send notification to admin when expecting models reached her due date
function rb_send_notif_due_date_reached_edit($ProfileID,$profile_custom_id,$value){
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');

	if($value == ""){
		return false;
	}
	
	$expired = $value < date("Y-m-d") ? 1 : 0;	

	
	$pcID = str_replace("_date", "", $profile_custom_id);
	
	if($expired == 0 && get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true) == 0){
		update_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",0);
	}

	if($expired == 0 && get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true) == 2){
		update_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",0);
	}

	if($expired == 1 && get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true) == 0){
		update_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",2);
	}

	if($expired == 0 && get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true) == 3){
		update_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",0);
	}

	if($expired == 1 && get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true) == 3 ){
		update_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",3);
	}

	if($expired > 0 && get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true) == 2 && get_option("ProfileCustomNotifyAdmin_".$pcID) == 1){
		
		$data = array();
		unset($data);
		$q = "SELECT * FROM ".$wpdb->prefix."agency_profile WHERE ProfileID = ".$ProfileID;
		$results = $wpdb->get_results($q,ARRAY_A);

		$profileContactDisplay = "";

		foreach($results as $res2){
			$profileContactDisplay = $res2["ProfileContactDisplay"];
		}
		$data["send_to"] = get_option("admin_email");
		//$data["send_to"] = 'legend_slash@yahoo.com';
		$data["profile_name"] = $profileContactDisplay;	
		$data["subject"] = rbagency_get_customfield_title($pcID);
		$data["expired_date"] = $value;
		expired_profile_notification($data);
		//echo $data["subject"] ." ".$value." ".$expired." ".get_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",true)."<br>";
		update_user_meta($ProfileID,$pcID."_".$ProfileID."_user_expired_sent",3);
	} 
	
}


function rbagency_get_customfield_title($id){
	global $wpdb;
	$q2 = "SELECT * FROM ".$wpdb->prefix."agency_customfields WHERE ProfileCustomID = ".$id;
	$results2 = $wpdb->get_results($q2,ARRAY_A);

	foreach($results2 as $res2)
		return $res2["ProfileCustomTitle"];
	
}

class RBLogin_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'rblogin', 'description' => __('A widget that displays the authors name ', 'rblogin') );		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'rblogin-widget' );		
		
		//@$this->WP_Widget( 'rblogin-widget', __('RB Login Widget', 'rblogin'), $widget_ops, $control_ops );
		parent::__construct('rblogin-widget', __('RB Login Widget', 'rblogin'), $widget_ops, $control_ops);
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$mt_li = $instance['mt-li'];
		$ap_li = $instance['ap-li'];

		if (class_exists('RBAgencyInteract') || class_exists('RBAgencyCasting') ) {

			echo $before_widget;

			// Display the widget title 
			if ( $title )
				echo $before_title . $title . $after_title;

			echo "<ul class=\"nav-menu\">";

			if(!is_user_logged_in()){

				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				// check if rb interact is active
				if (class_exists('RBAgencyInteract') ) {
					echo "<li><a href=\"/profile-login/\" title=\"".$mt_li."\">".$mt_li."</a></li>";
				}
				// check if rb casting is active
				if (class_exists('RBAgencyCasting'))  {
					echo "<li><a href=\"/casting-login/\" title=\"".$ap_li."\">".$ap_li."</a></li>";
				}
			} else {
				echo "<li><a href=\"".wp_logout_url()."\" title=\"Log Out\">Log Out</a></li>";
			}

			echo "</ul>";

			echo $after_widget;
		}
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['mt-li'] = strip_tags( $new_instance['mt-li'] );
		$instance['ap-li'] = strip_tags( $new_instance['ap-li'] );

		return $instance;
	}

	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('', 'rblogin'), 'mt-li' => __('Login | Register as Model/Talent', 'rblogin'), 'ap-li' => __('Login | Register as Casting Agent/Producer', 'rblogin') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div class="widget-content">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'rblogin'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'mt-li' ); ?>"><?php _e('Link Text for Model/Talent', 'rblogin'); ?></label>
				<input id="<?php echo $this->get_field_id( 'mt-li' ); ?>" name="<?php echo $this->get_field_name( 'mt-li' ); ?>" value="<?php echo $instance['mt-li']; ?>" style="width:100%;" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'ap-li' ); ?>"><?php _e('Link Text for Casting Agent/Producer', 'rblogin'); ?></label>
				<input id="<?php echo $this->get_field_id( 'ap-li' ); ?>" name="<?php echo $this->get_field_name( 'ap-li' ); ?>" value="<?php echo $instance['ap-li']; ?>" style="width:100%;" />
			</p>
		</div>

	<?php
	}
}


function box_cover_dvd( $atts ) {

	global $wpdb;
	$profiles_perrow = array('one','two','three','four','five','six','seven','eight','nine','ten');

	extract(shortcode_atts(array(
		"thumbsize"=> "200,300",
		"columns"=> 3,
		"spacing"=> 3
	),$atts));

	$image_size = explode(",", $thumbsize);
	boxcover_styles($spacing); // Spacing CSS

	$query = "SELECT media.*,profile.* FROM ".table_agency_profile_media." AS media INNER JOIN ".table_agency_profile." AS profile ON profile.ProfileID = media.ProfileID WHERE media.ProfileMediaType = 'dvd' ORDER BY profile.ProfileContactNameFirst ASC";	
	$resultsImg = $wpdb->get_results($query,ARRAY_A);

	$output = "";
	$output = "<ul class=\"boxcover boxcover-dvd ".$profiles_perrow[$columns-1]."-cols\">";
	foreach ($resultsImg as $dataImg) {
		$output .="<li>";
		$image_path = RBAGENCY_UPLOADDIR . $dataImg['ProfileGallery'] . "/" . $dataImg['ProfileMediaURL'];

		// BFI THUMB
		// $params = array(
		// 	'crop' => false,
		// 	'width' => $rb_agency_value_boxcover_thumbwidth,
		// 	'height' => $rb_agency_value_boxcover_thumbheight
		// );
		// $profile_image_src = bfi_thumb( $image_path, $params );
		// $output .= "<a class='example-image-link' href='".$profile_image_src."' data-lightbox='example-set' data-title='The next image in the set is preloaded as you're viewing.'> <img src=\"" . $profile_image_src ."\" class='example-image'/></a>";
		
		// TIMTHUMB
		$output .= "<a class='example-image-link' href='".$image_path."' data-lightbox='example-set' data-title='The next image in the set is preloaded as you're viewing.'> <img src=\"" . RBAGENCY_PLUGIN_URL."ext/timthumb.php?src=".$image_path."&w=".$image_size[0]."&h=".$image_size[1]."&a=t\" class='example-image'/></a>";

		$output .= "<p>".$dataImg['ProfileContactNameFirst'].' '.$dataImg['ProfileContactNameLast'].'</p>';
		$output .= "</li>";
	}
	$output .= "</ul>";

	return $output;
}
add_shortcode( 'box-cover-dvd', 'box_cover_dvd' );


function box_cover_magazine( $atts ) {

	global $wpdb;
	$profiles_perrow = array('one','two','three','four','five','six','seven','eight','nine','ten');

	extract(shortcode_atts(array(
		"thumbsize"=> "200,300",
		"columns"=> 3,
		"spacing"=> 3
	),$atts));

	$image_size = explode(",", $thumbsize);
	boxcover_styles($spacing); // Spacing CSS	

	$query = "SELECT media.*,profile.* FROM ".table_agency_profile_media." AS media INNER JOIN ".table_agency_profile." AS profile ON profile.ProfileID = media.ProfileID WHERE media.ProfileMediaType = 'magazine' ORDER BY profile.ProfileContactNameFirst ASC";	
	$resultsImg = $wpdb->get_results($query,ARRAY_A);

	$output = "";

	$output .= "<ul class=\"boxcover boxcover-magazine ".$profiles_perrow[$columns-1]."-cols\">";
	foreach ($resultsImg as $dataImg) {
		$output .="<li>";
		$image_path = RBAGENCY_UPLOADDIR . $dataImg['ProfileGallery'] . "/" . $dataImg['ProfileMediaURL'];

		// BFI THUMB SETTINGS
		// $params = array(
		// 	'crop' => false,
		// 	'width' => $rb_agency_value_boxcover_thumbwidth,
		// 	'height' => $rb_agency_value_boxcover_thumbheight
		// );
		// $profile_image_src = bfi_thumb( $image_path, $params );
		// $output .= "<a class='example-image-link' href='".$profile_image_src."' data-lightbox='example-set' data-title='The next image in the set is preloaded as you're viewing.'> <img src=\"" . $profile_image_src ."\" class='example-image'/></a>";				

		// TIMTHUMB
		$output .= "<a class='example-image-link' href='".$image_path."' data-lightbox='example-set' data-title='The next image in the set is preloaded as you're viewing.'> <img src=\"" . RBAGENCY_PLUGIN_URL."ext/timthumb.php?src=".$image_path."&w=".$image_size[0]."&h=".$image_size[1]."&a=t\" class='example-image'/></a>";

		$output .= "<p>".$dataImg['ProfileContactNameFirst'].' '.$dataImg['ProfileContactNameLast'].'</p>';
		$output .= "</li>";
	}
	$output .= "</ul>";

	return $output;
}
add_shortcode( 'box-cover-magazine', 'box_cover_magazine' );


function boxcover_styles($spacing){
	$output = "<style id=\"boxcover-styles\" type=\"text/css\"> .boxcover li { padding-right: ".$spacing."px; } </style>";

	echo $output;
}
add_action('wp_head','boxcover_styles');

function rbagency_lightbox_style_scripts() {
    if(get_query_var('type') == 'profile'){
		
	}else{
		wp_enqueue_style( 'lightbox2-style', site_url()."/wp-content/plugins/rb-agency/ext/lightbox2/css/lightbox.css", NULL,'4.4.5');
		wp_enqueue_script( 'script-name', site_url()."/wp-content/plugins/rb-agency/ext/lightbox2/js/lightbox-2.6.min.js", array());
	}
}
add_action( 'wp_enqueue_scripts', 'rbagency_lightbox_style_scripts' );
 
 add_filter('ws_plugin__s2member_login_redirect', '__return_false');
 
 add_action( 'login_form_register', 'wpse45134_catch_register' );
/**
 * Redirects visitors to `wp-login.php?action=register` to 
 * `site.com/register`
 */
function wpse45134_catch_register()
{
    wp_redirect( home_url( '/profile-register' ) );
    exit(); // always call `exit()` after `wp_redirect`
}


	if ( !function_exists('wp_new_user_notification_approve') ) { 
		function wp_new_user_notification_approve( $user_id) { 
				global $wpdb;
			
			$user = new WP_User($user_id);
				$rb_agency_interact_options_arr = get_option('rb_agencyinteract_options');
				$rb_agencyinteract_option_registerapproval = isset($rb_agency_interact_options_arr['rb_agencyinteract_option_registerapproval'])?$rb_agency_interact_options_arr['rb_agencyinteract_option_registerapproval']:0;

				if($user){
					$user_login = stripslashes($user->user_login);
					$user_email = stripslashes($user->user_email);

					/*if($rb_agencyinteract_option_registerapproval == 0){
						$new_pass = wp_generate_password();
						wp_set_password( $new_pass, $user_id );
						$user_pass = $new_pass;
					}*/

					$message  = __('Hi there,', RBAGENCY_interact_TEXTDOMAIN) . "\r\n\r\n";
					$message .= sprintf(__('Congratulations! Your account is approved.', RBAGENCY_interact_TEXTDOMAIN), $user_login) . "\r\n"; 
					//$message .= sprintf(__("Here's how to log in:"), get_option('blogname')) . "\r\n\r\n"; 
					//$message .= get_option('home') ."/profile-login/\r\n"; 
					//if($rb_agencyinteract_option_registerapproval == 1){ // automally approved
					//			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n"; 
					//			$message .= sprintf(__('Password: %s'),  $user_pass) . "\r\n\r\n"; 
					//}/*else { // manually approved
					//			$message .= sprintf(__('Password: %s'),  "Your Password") . "\r\n\r\n"; 

					//}
					$message .= sprintf(__('If you have any problems, please contact us at %s.', RBAGENCY_interact_TEXTDOMAIN), get_option('admin_email')) . "\r\n\r\n"; 
					$message .= __('Regards,', RBAGENCY_interact_TEXTDOMAIN)."\r\n";
					$message .= get_option('blogname') . __(' Team') ."\r\n"; 
					$message .= get_option('home') ."\r\n"; 

					$headers = 'From: '. get_option('blogname') .' <'. get_option('admin_email') .'>' . "\r\n";
					//wp_mail($user_email, sprintf(__('%s Congratulations! Your account is approved.'), get_option('blogname')), make_clickable($message), $headers);
					wp_mail($user_email, sprintf(__('%s Congratulations! Your account is approved.'), get_option('blogname')), $message, $headers);
				}
		}
	}


add_action('wp_ajax_editauditiondemo', 'editauditiondemo');
function editauditiondemo(){
	$old = isset($_REQUEST['old_value']) ? $_REQUEST['old_value'] : '';
	$new = isset($_REQUEST['new_value']) ? $_REQUEST['new_value'] : '';
	$key = isset($_REQUEST['demo_name_key']) ? $_REQUEST['demo_name_key'] : '';
	
	//RENAME AUDITION DEMO
	$auditiondemo_option = get_option($key);
	if(empty($auditiondemo_option)){
		add_option($key,$new);
	}else{
		update_option($key,$new);
	}
	die();
}
add_action('wp_ajax_editvoicedemo', 'editvoicedemo');
function editvoicedemo(){
	$old = isset($_REQUEST['old_value']) ? $_REQUEST['old_value'] : '';
	$new = isset($_REQUEST['new_value']) ? $_REQUEST['new_value'] : '';
	$key = isset($_REQUEST['demo_name_key']) ? $_REQUEST['demo_name_key'] : '';
	
	//RENAME VOICE DEMO
	$voicedemo_option = get_option($key);
	if(empty($voicedemo_option)){
		add_option($key,$new);
	}else{
		update_option($key,$new);
	}

	die();
}

add_action('wp_ajax_audeditvoicedemo', 'audeditvoicedemo');
function audeditvoicedemo(){
	$old = isset($_REQUEST['old_value']) ? $_REQUEST['old_value'] : '';
	$new = isset($_REQUEST['new_value']) ? $_REQUEST['new_value'] : '';
	$key = isset($_REQUEST['demo_name_key']) ? $_REQUEST['demo_name_key'] : '';
	
	//RENAME VOICE DEMO
	$voicedemo_option = get_option($key);
	if(empty($voicedemo_option)){
		add_option($key,$new);
	}else{
		update_option($key,$new);
	}
	echo json_encode($_REQUEST);
	die();
}

add_action('wp_ajax_deleteauditiondemo_func', 'deleteauditiondemo_func');
function deleteauditiondemo_func(){
	$auditiondemo_path = isset($_REQUEST['auditiondemo_path']) ? $_REQUEST['auditiondemo_path'] : '';
	
	if(file_exists(RBAGENCY_UPLOADPATH.'/'.$auditiondemo_path)){
		unlink(RBAGENCY_UPLOADPATH.'/'.$auditiondemo_path);
		echo json_encode(array('response'=>RBAGENCY_UPLOADPATH.'/'.$auditiondemo_path.' has been deleted!'));
	}
	
	die();
}

function rate_profile(){

		global $wpdb;

		$profile_id = $_POST['profile_id'];
		$profile_rating = $_POST['profile_rating'];

		//check if ProfileRating col exists
		$queryAlterCheck = "SELECT ProfileRating FROM " . table_agency_profile ." LIMIT 1";
		$resultsDataAlter = $wpdb->get_results($queryAlterCheck,ARRAY_A);
		$count_alter = $wpdb->num_rows;
		if($count_alter > 0){
			$queryAlter = "ALTER TABLE " . table_agency_profile ." MODIFY ProfileRating varchar(20) default 0";
			$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);			
		}else{
			$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileRating varchar(20) default 0";
			$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
		}

		$update = "UPDATE " . table_agency_profile .
				" SET ProfileRating = " . $profile_rating . " WHERE ProfileID = " . $profile_id;

		$wpdb->query($update);

		die();
	}
add_action('wp_ajax_rate_profile', 'rate_profile');
add_action('wp_ajax_rate_profile', 'rate_profile');


function additional_columns(){
	global $wpdb;
	
	//CustomOrder
	$q = "SELECT CustomOrder FROM ". table_agency_profile ." LIMI 1";
	$rda = $wpdb->get_results($q,ARRAY_A);
	$count = $wpdb->num_rows;

	$q1 = "SELECT * FROM ".table_agency_profile;
	$rda = $wpdb->get_results($q1,ARRAY_A);
	$qnumrows = $wpdb->num_rows;

	if($count == 0){
		$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD CustomOrder integer default $qnumrows";
		$res = $wpdb->query($queryAlter);
	}

	//ProfileRating
	$queryAlterCheck = "SELECT ProfileRating FROM " . table_agency_profile ." LIMIT 1";
	$resultsDataAlter = $wpdb->get_results($queryAlterCheck,ARRAY_A);
	$count_alter = $wpdb->num_rows;
	if($count_alter > 0){
		$queryAlter = "ALTER TABLE " . table_agency_profile ." MODIFY ProfileRating varchar(20) default 0";
		$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);			
	}else{
		$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileRating varchar(20) default 0";
		$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
	}
}
add_action('init','additional_columns');

function insertNewCountries(){
	global $wpdb;

	$arr = array(
		'BE' => 'Belgium',
		'BG' => 'Bulgaria',
		'CZ'=>'Czech Republic',
		'DK' => 'Denmark',
		'DE'=>'Germany',
		'EE'=>'Estonia',
		'IE'=>'Ireland',
		'EL'=>'Greece',
		'ES'=>'Spain',
		'FR'=>'France',
		'HR'=>'Croatia',
		'IT'=>'Italy',
		'CY'=>'Cyprus',
		'LV'=>'Latvia',
		'LT'=>'Lithuania',
		'LU'=>'Luxembourg',
		'HU'=>'Hungary',
		'MT'=>'Malta',
		'NL'=>'Netherlands',
		'AT'=>'Austria',
		'PL'=>'Poland',
		'PT'=>'Portugal',
		'RO'=>'Romania',
		'SI'=>'Slovenia',
		'SK'=>'Slovakia',
		'FI'=>'Finland',
		'SE'=>'Sweden'

	);


	$val_arr = array();
	foreach($arr as $k=>$v){
		$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."agency_data_country WHERE CountryCode = '$k'");
		if($wpdb->num_rows == 0){
			$val_arr[] = "('".$v."','".$k."')";
		}else{
			$wpdb->query("DELETE FROM ".$wpdb->prefix."agency_data_country WHERE CountryCode = '$k'");
			$val_arr[] = "('".$v."','".$k."')";
		}		
	}
	$imploded_val = implode(',',$val_arr);
	$sql = "INSERT INTO ".$wpdb->prefix."agency_data_country(CountryTitle,CountryCode) VALUES".$imploded_val;
	$wpdb->query($sql);
}

add_action('init','insertNewCountries');

?>