<?php
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_privacy = isset($rb_agency_options_arr['rb_agency_option_privacy']) ? $rb_agency_options_arr['rb_agency_option_privacy'] :0;
$rb_agency_option_persearch  = isset($rb_agency_options_arr['rb_agency_option_persearch']) ? (int)$rb_agency_options_arr['rb_agency_option_persearch']:1000;

echo $rb_header = RBAgency_Common::rb_header();
global $wpdb;
// Profile Class
include(rb_agency_BASEREL ."app/profile.class.php");
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];

	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

		echo " <div id=\"profile-private\">\n";
	// P R I V A C Y FILTER ====================================================
	if ( //Must be logged to view model list and profile information
		($rb_agency_option_privacy == 2 && is_user_logged_in()) || 

		// Model list public. Must be logged to view profile information
		($rb_agency_option_privacy == 1 && is_user_logged_in()) ||

		// All Public
		($rb_agency_option_privacy == 0) ||

		//admin users
		(is_user_logged_in() && current_user_can( 'edit_posts' )) ||

		//  Must be logged as "Client" to view model list and profile information
		($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype()) ) {
					// Get Profile
					 $SearchMuxHash = get_query_var('target');

					if (isset($SearchMuxHash)) {

						// Get Identifier
						$_SESSION['SearchMuxHash'] = $SearchMuxHash;

						// Get Casting Cart by Identifier
						$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE searchsent.SearchMuxHash = \"". $SearchMuxHash ."\"";
						$results = $wpdb->get_results($query,ARRAY_A) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
						$count =  count($results);
						// Get Casting Cart ID
						foreach($results as $data) {
							$castingcart_id = $data['SearchProfileID'];
						}

						// Return Search
					
						$search_array = array("perpage" => 9999, "include" => $castingcart_id);
						$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);
						
						// Process Form Submission
						echo $search_results = RBAgency_Profile::search_results($search_sql_query, 0);
						
					  // echo  $formatted = RBAgency_Profile::search_formatted($search_array);




					}
					if (empty($SearchMuxHash) || ($count == 0)) {
						echo "<strong>". __("No search results found.  Please check link again.", rb_agency_TEXTDOMAIN) ."</strong>";
					}
		} else {

			if($rb_agency_option_privacy == 3 && is_user_logged_in() && !is_client_profiletype()){
				echo "<h2>This is a restricted page. For Clients only.</h2>";
			} else {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
				if(function_exists('rb_agency_interact_menu')){
					include(rb_agency_interact_BASEREL . "theme/include-login.php");
				} else {
					rb_loginform(rb_current_url());
				}
			}

	}
		echo "  <div style=\"clear: both;\"></div>";
		echo " </div>\n";
		echo "  </div>\n";
		echo "</div>\n";

//get_sidebar(); 
echo $rb_footer = RBAgency_Common::rb_footer(); 
?>