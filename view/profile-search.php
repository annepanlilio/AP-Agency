<?php
$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
	$rb_agency_option_persearch  = isset($rb_agency_options_arr['rb_agency_option_persearch']) ? (int)$rb_agency_options_arr['rb_agency_option_persearch']:1000;
	$rb_agency_option_form_sidebar = isset($rb_agency_options_arr['rb_agency_option_form_sidebar'])?$rb_agency_options_arr['rb_agency_option_form_sidebar']:0;
	$rb_agency_option_formhide_advancedsearch_button = isset($rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button'])?$rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button']:0;

	// Call Header
	echo $rb_header = RBAgency_Common::rb_header();

	$add_sidebar = false;
	if($rb_agency_option_form_sidebar == 1){
		$add_sidebar = true;
	}

	if (get_query_var("type") == "search-advanced") {
		$type = 1;
	} else {
		$type = 0;
	}
	
	if(( get_query_var( 'type' ) == "search-basic" || get_query_var( 'type' ) == "search-results"))
	{
		$search_fields = array('sort','dir','limit','perpage','page','override_privacy','namefirst','namelast','displayname','profiletype','gender','datebirth_min',
					   'datebirth_max','age_min','age_max','city','state','zip','country','isactive','rb_datepicker_from_bd','rb_datepicker_to_bd'
				);
		foreach($search_fields as $search_field)
		{
			unset($_SESSION[$search_field]);
		}
	}
	
	
	//".(!$add_sidebar?"site-content":primary_class())."
	echo "<div class=\"site-main  ".primary_class()."\">\n";
	echo "	<div id=\"rbcontent\" role=\"main\" >\n";
	echo "		<div id=\"profile-search\" "; echo post_class(); echo ">\n";

	// P R I V A C Y FILTER ====================================================
	if ( //Must be logged to view model list and profile information
		($rb_agency_option_privacy == 2 && is_user_logged_in()) || 

		// Model list public. Must be logged to view profile information
		($rb_agency_option_privacy == 1 && is_user_logged_in()) ||

		// All Public
		($rb_agency_option_privacy == 0) ||

		// Admin Users
		(is_user_logged_in() && current_user_can( 'edit_posts' )) ||

		//  Must be logged in as Casting Agent to View Profiles
		($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype()) ) {

		/*
		 * Set Title
		 */
		echo '			<header class="entry-header">';
			if ( (isset($_REQUEST["form_action"]) && $_REQUEST["form_action"] == "search_profiles") || isset($_REQUEST["page"]))  {
				echo "			<h1 class=\"entry-title\">". __("Search Results", RBAGENCY_TEXTDOMAIN) ."</h1>\n";
			} else {
				if ( (get_query_var("type") == "search-basic") || (isset($_POST['form_mode']) && $_POST['form_mode'] == 0 ) ){
						echo "	<h1 class=\"entry-title\">". __("Basic Search", RBAGENCY_TEXTDOMAIN) ."</h1>\n";
				} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == 1 ) ){
						echo "	<h1 class=\"entry-title\">". __("Advanced Search", RBAGENCY_TEXTDOMAIN) ."</h1>\n";
				}
			}
		echo '			</header>';

		/*
		 * IF: Search Results
		 */

			echo "	<div id=\"profile-search-results\" class=\"entry-content\">\n";

			//if ( isset($_REQUEST["form_action"]) && $_REQUEST["form_action"] == "search_profiles" ) {
			if ( (isset($_REQUEST["form_action"]) && $_REQUEST["form_action"] == "search_profiles") || isset($_REQUEST["page"]) ) {

				// Filter Post
				foreach($_REQUEST as $key=>$value) {
					if ( is_array($value) && !empty($value) ){
						$is_array_empty = array_filter($_REQUEST[$key]);
						if(empty($is_array_empty)){
							unset( $_REQUEST[$key] ); // Why unset custom fields? we have an array_filter
						}
					} else {
						if ( !isset($value) || empty ($value) ){
							unset( $_REQUEST[$key] );
						}
					}
				}
				//unset( $_REQUEST['search_profiles'] );
				//unset( $_REQUEST['form_mode'] );
				// Keep form_action
				$is_paging = get_query_var("page") ? get_query_var("page"):get_query_var("paging");
				// Check something was entered in the form

				
				if (count($_REQUEST) > 1 || $is_paging) {
					$search_array = array();
					$search_array = array_filter($_REQUEST);
					//var_dump($search_array);
					$search_array = RBAgency_Profile::search_process();
					$search_array["limit"] = $rb_agency_option_persearch;
					
					if(isset($_REQUEST['page']) && count($search_array) < 3 )
					{
						$search_array = $_SESSION ;
					}
					
					// Return SQL string based on fields
					$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);

					
					// Conduct Search
					echo RBAgency_Profile::search_results($search_sql_query, 0, false, $search_array);
					
					

				} else {
					echo "<h2>Please try again</h2><strong>". __("Please enter at least one value to search.", RBAGENCY_TEXTDOMAIN) ."</strong>\n";
				}
			
			}
			else {
				echo "<strong>". _e("No search criteria selected, please initiate your search.", RBAGENCY_TEXTDOMAIN) ."</strong>";
			}
			echo "	</div><!-- #profile-search-results -->\n"; // #profile-search-results
			echo "	<hr />";

		/*
		 * Search Form
		 */
			// // Show Search Form
			
			if (!isset($_POST['form_mode']) && !isset($_GET["form_action"]) && !isset($_GET["page"]) ) {
					echo RBAgency_Profile::search_form('', '', $type, 0);
			}

	} else {

			if($rb_agency_option_privacy == 3 ){
				if(is_user_logged_in()){
					rb_get_profiletype();
				} else {
					echo "	<div class='restricted'>\n";
					if ( class_exists("RBAgencyCasting") ) {
						echo "<h2>Page restricted. Only Admin & Casting Agents can view this page.<br />Please <a href=\"".get_bloginfo("url")."/casting-login/\">login</a> or <a href=\"".get_bloginfo("url")."/casting-register/\">register</a>.</h2>";
					} else {
						echo "Page restricted. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login</a> or <a href=\"".get_bloginfo("url")."/profile-register/\">register</a>.";
					}
					echo "	</div><!-- .restricted -->\n";
				}
			} else {
				if(function_exists('rb_agency_interact_menu')){
					include(RBAGENCY_interact_BASEREL . "theme/include-login.php");

				} else {
					rb_loginform(rb_current_url());

				}
			}

	}

	echo "		</div><!-- #profile-search -->\n"; // #profile-search
	echo "	</div><!-- #rbcontent -->\n"; // #rbcontent
	echo "</div><!-- #primary -->\n"; // #primary


	if($add_sidebar && !in_array(get_query_var("type"),array("search-basic","search-advanced"))){
	echo "<div id=\"secondary\" class=\"widget-area\">\n";
	echo "	<div id=\"rbwidgets\" role=\"main\" class=\"transparent\">\n";
	echo "<aside id=\"text-3\" class=\"widget widget_text\">";
	echo "<h3 class=\"widget-title\">Search Profiles</h3>";
	echo "<div class=\"textwidget\">";
	echo RBAgency_Profile::search_form('', '', $type, 0);
	echo "</div>";
	echo "</aside>";
	// Show Search Form
	echo "<aside id=\"text-3\" class=\"widget widget_text\">";
	echo "<h3 class=\"widget-title\">Featured Profile</h3>";
	echo "<div class=\"textwidget\">";
	if (class_exists('RBAgency_Profile')) {
				$atts = array(/*'type' => $type,*/"count"=> 1);
				echo RBAgency_Profile::view_featured($atts);
	} else {
				echo "Invalid Function (Profile Search)";
	}
	echo "</div>";
	echo "</aside>";

	echo "	</div><!-- #rbwidgets -->\n"; // #rbwidgets
	echo "</div><!-- #secondary -->\n"; // #secondary
	}

	// Call Footer
	echo $rb_footer = RBAgency_Common::rb_footer();

?>