<?php 
session_start();
get_header();
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

			// Get Casting Cart by Identifier
			$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE searchsent.SearchMuxHash = \"". $SearchMuxHash ."\"";
			$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
			$count = mysql_num_rows($results);

			// Get Casting Cart ID
			while ($data = mysql_fetch_array($results)) {
				$castingcart_id = $data['SearchProfileID'];
			}

			// Return Search
			echo "castingcart_id".$castingcart_id;
			$search_array = array("perpage" => 9999, "include" => $castingcart_id);
			$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);
echo "<pre>";
print_r($search_sql_query);
echo "</pre>";
				// Process Form Submission
			//	$search_results = RBAgency_Profile::search_results($search_sql_query, 0);
			//	$formatted = RBAgency_Profile::search_formatted($search_results);




		}
		if (empty($SearchMuxHash) || ($count == 0)) {
			echo "<strong>". __("No search results found.  Please check link again.", rb_agency_TEXTDOMAIN) ."</strong>";
		}

		echo "  <div style=\"clear: both;\"></div>\n";
		echo " </div>\n";
	echo "  </div>\n";
	echo "</div>\n";

//get_sidebar(); 
get_footer(); 
?>