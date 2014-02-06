<?php
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
	
rb_header();

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
		 (is_user_logged_in() && current_user_can( 'manage_options' )) ||
			 
		 //  Must be logged as "Client" to view model list and profile information
		 ($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype()) ) {
	

				if ($_REQUEST["form_action"] == "search_profiles") {
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
					$search_array = RBAgency_Profile::search_process();
			
					// Return SQL string based on fields
					$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);
			
					// Process Form Submission
					echo RBAgency_Profile::search_results($search_sql_query, 0);
					//  echo $formatted = RBAgency_Profile::search_formatted($search_results);
					
			
			
				} else {
				if (((get_query_var("type") == "search-basic")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ||  $profilesearch_layout == 'condensed' )
					|| ((get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) || $profilesearch_layout == 'advanced' )){
						
							// echo RBAgency_Profile::search_form("", "", 0);
					}else{
					echo "				<strong>". _e("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
					}
				
				
				}
				echo "			</div><!-- #profile-search-results -->\n"; // #profile-search-results
				echo "			<hr />";
			
					//do not display on results
				if(!isset($_POST['form_mode'])){
					if (((get_query_var("type") == "search-basic")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ||  $profilesearch_layout == 'condensed' )
					|| ((get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) || $profilesearch_layout == 'advanced' )){
						
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
		
			if($rb_agency_option_privacy == 3 && is_user_logged_in() && !is_client_profiletype()){
				echo "<h2>This is a restricted page. For Clients only.</h2>";
			} else {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
				if(is_plugin_active(ABSPATH . 'wp-content/plugins/rb-agency-interact/rb-agency-interact.php')){
					include("theme/include-login.php"); 	
				} else {
					rb_loginform(rb_current_url());
				} 	
			} 
	
	}

	echo "		</div><!-- #profile-search -->\n"; // #profile-search
	echo "	</div><!-- #content -->\n"; // #content
	echo "</div><!-- #primary -->\n"; // #primary

// get_sidebar(); 
rb_footer(); 
?>