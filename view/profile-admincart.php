<?php
echo $rb_header = RBAgency_Common::rb_header();
global $wpdb;

	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];

	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

		echo " <div id=\"profile-private\">\n";

		// Get Profile
		$SearchMuxHash = get_query_var('target');
		if (isset($SearchMuxHash)) {

			// Get Identifier
			$_SESSION['SearchMuxHash'] = $SearchMuxHash;
			//$wpdb->query("ALTER TABLE  ". table_agency_searchsaved." ADD INDEX (`SearchProfileID`)");
			//$wpdb->query("ALTER TABLE  ". table_agency_searchsaved_mux." ADD INDEX (`SearchID`)");
			// Get Casting Cart by Identifier
			$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash, searchsent.SearchMuxCustomThumbnail FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE searchsent.SearchMuxHash = \"". $SearchMuxHash ."\"";
			$data = $wpdb->get_row($query,ARRAY_A) or die ( __("Error, query failed", RBAGENCY_TEXTDOMAIN ));
			$count =  $wpdb->num_rows;
			/*$wpdb->show_errors();
			$wpdb->print_error();
			 */
			// Get Casting Cart ID
			$castingcart_id = implode(",",array_unique(array_filter(explode(",",$data['SearchProfileID']))));

			$arr = (array)unserialize($data["SearchMuxCustomThumbnail"]);
			$_SESSION["profilephotos_view"] = is_array($arr[0])?array_filter(array_unique($arr[0])):"";

			$search_array = array("perpage" => 9999, "include" => $castingcart_id);
			//$search_sql_query = RBAgency_Profile::search_generate_sqlwhere(array_filter(array_unique($search_array)));
			$search_sql_query['standard'] = " profile.ProfileID IN(".(!empty($castingcart_id)?$castingcart_id:0).") ";
			// Process Form Submission
			echo $search_results = RBAgency_Profile::search_results($search_sql_query, 3);

			// echo  $formatted = RBAgency_Profile::search_formatted($search_array);

		}
		if (empty($SearchMuxHash) || ($count == 0)) {
			echo "<strong>". __("No search results found.  Please check link again.", RBAGENCY_TEXTDOMAIN) ."</strong>";
		}

		echo "  <div style=\"clear: both;\"></div>";
		echo " </div>\n";
		echo "  </div>\n";
		echo "</div>\n";

//get_sidebar(); 
echo $rb_footer = RBAgency_Common::rb_footer(); 
?>