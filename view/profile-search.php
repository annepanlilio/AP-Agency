<?php
get_header();

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");

	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "	<div id=\"content\" role=\"main\" class=\"transparent\">\n";
	echo "		<div id=\"profile-search\">\n";

	if ($_REQUEST["action"] == "search_profiles") {
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
		$search_results = RBAgency_Profile::search_results($search_sql_query, 0);
		$formatted = RBAgency_Profile::search_formatted($search_results);


	} else {
	echo "				<strong>". __("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
	}
	echo "			</div><!-- #profile-search-results -->\n"; // #profile-search-results
	echo "			<hr />";

        //do not display on results
	if(!isset($_POST['form_mode'])){
		echo RBAgency_Profile::search_form("", "", 0);
	} else {
		if ( (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) ){
				echo "					<input type=\"button\" name=\"back_search\" value=\"". __("Go Back to Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
		} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ){
				echo "					<input type=\"button\" name=\"back_search\" value=\"". __("Go Back to Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
		}
	}

	echo "		</div><!-- #profile-search -->\n"; // #profile-search
	echo "	</div><!-- #content -->\n"; // #content
	echo "</div><!-- #primary -->\n"; // #primary

get_sidebar(); 
get_footer(); 
?>