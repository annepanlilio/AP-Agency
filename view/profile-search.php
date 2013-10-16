<?php
get_header();

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");

	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "	<div id=\"content\" role=\"main\" class=\"transparent\">\n";
	echo "		<div id=\"profile-search\">\n";

	if ($_REQUEST["action"] == "search") {
	echo "			<h1 class=\"entry-title\">". __("Search Results", rb_agency_TEXTDOMAIN) ."</h1>\n";
	} else {
	echo "			<h1 class=\"entry-title\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</h1>\n";
	}

	echo "			<div id=\"profile-search-results\">\n";
	if ($_REQUEST["action"] == "search") {


		// Process Form Submission & catch variables
		$search_array = RBAgency_Profile::search_process();

		// Return SQL string based on fields
		$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);

		// Process Form Submission
		$search_results = RBAgency_Profile::search_results($search_sql_query, 0);
		$formatted = RBAgency_Profile::search_formatted($search_results);


	} else {
	echo "				<strong>". __("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
	}
	echo "			</div><!-- #profile-search-results -->\n"; // #profile-search-results
	echo "			<hr />";

	return RBAgency_Profile::search_form("", "", 0);

	echo "		</div><!-- #profile-search -->\n"; // #profile-search
	echo "	</div><!-- #content -->\n"; // #content
	echo "</div><!-- #primary -->\n"; // #primary

get_sidebar(); 
get_footer(); 
?>