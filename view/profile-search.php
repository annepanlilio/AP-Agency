<?php
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
$rb_agency_option_persearch  = isset($rb_agency_options_arr['rb_agency_option_persearch']) ? (int)$rb_agency_options_arr['rb_agency_option_persearch']:1000;
				
	// Call Header
	echo $rb_header = RBAgency_Common::rb_header();

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");


	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "	<div id=\"content\" role=\"main\" class=\"transparent\">\n";
	echo "		<div id=\"profile-search\">\n";

	// P R I V A C Y FILTER ====================================================
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

				if (isset($_REQUEST["form_action"]) && $_REQUEST["form_action"] == "search_profiles") {
				echo "			<h1 class=\"entry-title\">". __("Search Results", rb_agency_TEXTDOMAIN) ."</h1>\n";
				} else {
					if ( (get_query_var("type") == "search-basic") || (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ){
							echo "	<h1 class=\"entry-title\">". __("Basic Search", rb_agency_TEXTDOMAIN) ."</h1>\n";
					} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) ){
							echo "	<h1 class=\"entry-title\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</h1>\n";
					}
				}

				echo "			<div id=\"profile-search-results\">\n";

				if (isset($_POST["form_action"]) && $_POST["form_action"] == "search_profiles") {

					// Process Form Submission & catch variables
					$search_array = array();

					if(isset($_REQUEST)){
						$search_array = array_filter($_REQUEST);
					}

					// Return SQL string based on fields
					   unset($search_array["search_profiles"]);
					   $search_array["profilecontactnamefirst"] = $search_array["namefirst"];
					   $search_array["profilecontactnamelast"] = $search_array["namelast"];
					   unset($search_array["namefirst"]);
					   unset($search_array["namelast"]);
					   rb_agency_profilelist($search_array);
					
					//$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);
					
					// Process Form Submission
					//echo RBAgency_Profile::search_results($search_sql_query, 0);
					//  echo $formatted = RBAgency_Profile::search_formatted($search_results);

				} else {
				if (((get_query_var("type") == "search-basic")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ||  isset($profilesearch_layout) && $profilesearch_layout == 'condensed' )
					|| ((get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) || isset($profilesearch_layout) &&  $profilesearch_layout == 'advanced' )){
							// echo RBAgency_Profile::search_form("", "", 0);
					}else{
						if(isset($_GET["form_mode"])){
							$search_array = array_filter($_GET);
							rb_agency_profilelist($search_array);
						}else{
						echo "				<strong>". _e("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
						}
					}
				}
				echo "			</div><!-- #profile-search-results -->\n"; // #profile-search-results
				echo "			<hr />";

					//do not display on results
				if(!isset($_POST['form_mode'])){
					if (((get_query_var("type") == "search-basic")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ||  isset($profilesearch_layout) && $profilesearch_layout == 'condensed' )
					|| ((get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) || isset($profilesearch_layout) &&  $profilesearch_layout == 'advanced' )){
						
							echo RBAgency_Profile::search_form("", "", 0);
					}else{
							echo "					<input type=\"button\" name=\"back_search\" value=\"". __("Go Back to Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
							echo "					<input type=\"button\" name=\"back_search\" value=\"". __("Go Back to Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
					}
				} else {
					if ( isset($_POST['form_mode']) ){
							echo "					<input type=\"button\" name=\"back_search\" value=\"". __("Go Back to Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
							echo "					<input type=\"button\" name=\"back_search\" value=\"". __("Go Back to Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
					}
				}

	} else {

			if($rb_agency_option_privacy == 3 ){
					if(is_user_logged_in()){
						rb_get_profiletype();
					}else{
						echo "	<div class='restricted'>\n";
						echo "<h2>Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/casting-login/\">login or register</a>.</h2>";
						echo "  </div><!-- #content -->\n";
					}
			} else {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
				if(function_exists('rb_agency_interact_menu')){
					include(rb_agency_interact_BASEREL . "theme/include-login.php");
				} else {
					rb_loginform(rb_current_url());
				}
			}

	}

	echo "		</div><!-- #profile-search -->\n"; // #profile-search
	echo "	</div><!-- #content -->\n"; // #content
	echo "</div><!-- #primary -->\n"; // #primary

	// Call Footer
	echo $rb_footer = RBAgency_Common::rb_footer();

?>