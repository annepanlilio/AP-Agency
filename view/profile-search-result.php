<?php
    header("Expires: 0");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

	// Call Header
	echo $rb_header = RBAgency_Common::rb_header();
	
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
		echo '<header class="entry-header">';
				echo "<h1 class=\"entry-title\">". __("Search Results", RBAGENCY_TEXTDOMAIN) ."</h1>\n";
			
		echo '</header>';

		/*
		 * IF: Search Results
		 */

			echo "	<div id=\"profile-search-results\" class=\"entry-content\">\n";

			//if ( isset($_REQUEST["form_action"]) && $_REQUEST["form_action"] == "search_profiles" ) {
			if ( isset($_SESSION["search_array"]) ) {
			        $search_array = $_SESSION['search_array'];
                    //$search_array = RBAgency_Profile::search_process();
					$search_array["limit"] = $rb_agency_option_persearch;
					
					
					// Return SQL string based on fields
					$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);

					if(!empty($search_sql_query['custom'])){
						unset($_SESSION['custom_search']);
						$_SESSION['custom_search'] = $search_sql_query['custom'];
					}else{
						unset($_SESSION['custom_search']);
						$_SESSION['custom_search'] = '';
					}

					$search_sql_query['custom'] = $_SESSION['custom_search'];

					// Conduct Search
					echo RBAgency_Profile::search_results($search_sql_query, 0, false, $search_array);
					
			
			}
			else {
				echo "<strong>". _e("No search criteria selected, please initiate your search.", RBAGENCY_TEXTDOMAIN) ."</strong>";
			}
			echo "	</div><!-- #profile-search-results -->\n"; // #profile-search-results
			

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