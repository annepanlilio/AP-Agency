<?php
// *************************************************************************************************** //
// Get Category

session_start();
header("Cache-control: private"); //IE 6 Fix

// Get Profile
$ProfileType = get_query_var('target'); 

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

	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	
		echo "<div id=\"profile-category\">\n";

		echo "	<h1 class=\"profile-category-title\">\n";
		echo "	". __("Directory", rb_agency_TEXTDOMAIN) ." ";
				if ($DataTypeTitle) { echo " > ". $DataTypeTitle; }
		echo "	</h1>\n";

		echo "	<div class=\"clear line\"></div>\n";

			if (function_exists('rb_agency_categorylist')) { 
				$atts = array('currentcategory' => $DataTypeID);
				rb_agency_categorylist($atts); }

		echo "	<div class=\"clear line\"></div>\n";
		echo "	<table class=\"standardTable\">\n";
		echo "	 <tbody>\n";
		echo "		<tr>\n";
		echo "	    <td class=\"profile-category-results-wrapper\">\n";
		echo "			<div class=\"profile-category-results\">\n";
	
						if (function_exists('rb_agency_profilelist')) { 
						  $atts = array("type" => $DataTypeID);
						  rb_agency_profilelist($atts); 
						}
									
		echo "			</div>\n";
		echo "	    </td>\n";
		echo "	    <td class=\"profile-category-filter-wrapper\">\n";
		echo "			<div class=\"profile-category-filter\">\n";
		echo "			  <h4>". __("Search Profiles", rb_agency_TEXTDOMAIN) .":</h4>\n";
	 
						  $profilesearch_layout = "condensed";
						  include("include-profile-search.php"); 	
	
		echo "			</div>\n";
		echo "	    </td>\n";
		echo 	" </tbody>\n";
		echo "	</table>\n";
		echo "</div>\n";
		echo "<div class=\"clear line\"></div>\n";
		
	echo "  </div>\n";
	echo "</div>\n";
       
//get_sidebar(); 
get_footer(); 
?>