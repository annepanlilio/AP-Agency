<?php
// *************************************************************************************************** //
// Get Category

// This is the Portfolio-Category page 


session_start();
header("Cache-control: private"); //IE 6 Fix

// Get Profile
//$ProfileType = get_query_var('target'); 

if (isset($ProfileType) && !empty($ProfileType)){
	$DataTypeID = 0;
	$DataTypeTitle = "";
	$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeTag = '". $ProfileType ."'";

	$results = mysql_query($query);
	while ($data = mysql_fetch_array($results)) {
		$DataTypeID = $data['DataTypeID'];
		$DataTypeTitle = $data['DataTypeTitle'];
		$filter .= " AND profile.ProfileType=". $DataTypeID ."";
	}
}

get_header(); 



	echo "<div class=\"content_wrapper\">\n"; // Theme Wrapper 
	echo "	<div class=\"PageTitle\"><h1>Favorite Talent</h1></div>\n";	 // Profile Name
	

	// BNE EDIT - Added Account page styles 
	echo "	<div id=\"profile-manage\" class=\"profile-admin overview\">\n";



	// Account Menu Tabs
	echo "<div class=\"profile-manage-menu\">\n";
	echo "   <div id=\"subMenuTab\">\n";



		// Welcome 
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-left tab-". $tabclass ."\">\n";
		echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-member/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Welcome</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";

		// Account Manage
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/account/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-inner tab-". $tabclass ."\">\n";
		echo " 			<a  href=\"". get_bloginfo("wpurl") ."/profile-member/account/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Account & Contact Information</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";

		// Classification Manage Link
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/manage/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-inner tab-". $tabclass ."\">\n";
		echo " 			<a  href=\"". get_bloginfo("wpurl") ."/profile-member/manage/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Classification & Details</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";

		// Search 
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-category/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-left tab-". $tabclass ."\">\n";
		echo " 			<a href=\"". get_bloginfo("wpurl") ."/profile-category/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Search Talents</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";



		// View Favorites
					if ( ($_SERVER["REQUEST_URI"]) == "/profile-member/favorites/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-inner tab-active\">\n";
		echo " 			<a  href=\"". get_bloginfo("wpurl") ."/profile-favorites/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">View Marked Favorites</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";



		// Log OUt
		echo " 		<div class=\"tab-right\">\n";
		echo " 			<a title=\"Logout\" href=\"". wp_logout_url('index.php') ."\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Log Out</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";


		echo "   </div>\n"; // END #profile-manage-menu
		echo " </div>\n";  // END #subMenuTab



	echo "	<div class=\"profile-overview-inner inner\">\n";

	// END EDIT





	// Profile Listings
	echo "		<div id=\"container\" class=\"one-column\">\n";
	echo "			<div id=\"content\" role=\"main\" class=\"transparent\">\n";
	echo "				<div id=\"profile-category\">\n";
	echo "					<div class=\"clear line\"></div>\n";
	echo "					<table class=\"standardTable\">\n";
	echo "						<tbody>\n";
	echo "							<tr>\n";
	echo "	    						<td class=\"profile-category-results-wrapper\">\n";
	echo "									<div class=\"profile-category-results\">\n";
												if (function_exists('rb_agency_profilelist')) { 
													$atts = array("type" => $DataTypeID,"profilefavorite" => true);
													rb_agency_profilelist($atts); 
												}
	echo "									</div>\n";
	echo "	    						</td>\n";
	/*	
	echo "	   							<td class=\"profile-category-filter-wrapper\">\n";
	echo "									<div class=\"profile-category-filter\">\n";
	echo "			  							<h3>". __("Filter Profiles", rb_agency_TEXTDOMAIN) .":</h3>\n";
	 
						 					 	$profilesearch_layout = "condensed";
						 					 	include("include-profile-search.php"); 	
	
	echo "									</div>\n";
	echo "	    						</td>\n";
	*/
	echo "							</tr>\n";
	echo "						</tbody>\n";
	echo "					</table>\n";
	echo "				</div>\n"; // End .profile-category
	echo "				<div class=\"clear line\"></div>\n";
	echo "			</div>\n"; // END #content
	echo "		</div>\n"; // END #container



	// Account page style clsoing div's
	echo "	</div>"; // END .profile-overview-inner
	echo " </div>\n"; // .profile-manage



	echo "</div>\n"; // END .content_wrapper 
       
//get_sidebar(); 
get_footer(); 
?>