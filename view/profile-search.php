<?php
get_header();

	// Widgets & Shortcodes
	include(rb_agency_BASEREL ."app/profile.class.php");

	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "	<div id=\"content\" role=\"main\" class=\"transparent\">\n";
	echo "		<div id=\"profile-search\">\n";

		if ($_REQUEST["action"] == "search") {
		echo "  <h1 class=\"entry-title\">". __("Search Results", rb_agency_TEXTDOMAIN) ."</h1>\n";
		} else {
		echo "  <h1 class=\"entry-title\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</h1>\n";
		}




		echo "	<div id=\"profile-search-results\">\n";
		if ($_REQUEST["action"] == "search") {

			// Convert Requests to Sessions
			foreach ($_REQUEST as $key => $value) {
				//echo $key .":". $value ."<br />";
				if (substr($key, 0, 9) != "ProfileID") {
					$_SESSION[$key] = $value;  //$$key = $value;
				}
			}

			// Process Form Submission
			$search_array = RBAgency_Profile::search_process($filterArray);

			// Return Search
			$search_sql_where = RBAgency_Profile::search_generate_sqlwhere($search_array);

			// Process Form Submission
			$search_results = RBAgency_Profile::search_results($search_sql_where, 0);
			$formatted = RBAgency_Profile::search_formatted($search_results);

		} else {
			echo "<strong>". __("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
		}
		echo "</div><!-- #profile-search-results -->\n"; // #profile-search-results
		echo "<hr />";

		return RBAgency_Profile::search_form();

	echo "		</div><!-- #profile-search -->\n"; // #profile-search
	echo "	</div><!-- #content -->\n"; // #content
	echo "</div><!-- #primary -->\n"; // #primary

get_sidebar(); 
get_footer(); 
?>