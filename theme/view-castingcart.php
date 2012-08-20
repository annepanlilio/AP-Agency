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
	echo "<div class=\"PageTitle\"><h1>Casting Cart</h1></div>\n";	 // Profile Name
	




	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	
		echo "<div id=\"profile-category\">\n";


		echo "	<div class=\"clear line\"></div>\n";
		echo "	<table class=\"standardTable\">\n";
		echo "	 <tbody>\n";
		echo "		<tr>\n";
		echo "	    <td class=\"profile-category-results-wrapper\">\n";
		echo "			<div class=\"profile-category-results\">\n";
	
						if (function_exists('rb_agency_profilelist')) { 
						  $atts = array("type" => $DataTypeID,"profilecastingcart" => true);
						  rb_agency_profilelist($atts); 
						}
									
		echo "			</div>\n";
		echo "	    </td>\n";
		echo "	    <td class=\"profile-category-filter-wrapper\">\n";
		get_sidebar(); 
	/*	
		
		echo "			<div class=\"profile-category-filter\">\n";
		echo "			  <h3>". __("Filter Profiles", rb_agency_TEXTDOMAIN) .":</h3>\n";
	 
						  $profilesearch_layout = "condensed";
						  include("include-profile-search.php"); 	
	
		echo "			</div>\n";
		*/
		echo "	    </td>\n"; 
		
		echo 	" </tbody>\n";
		echo "	</table>\n";
		echo "</div>\n";
		echo "<div class=\"clear line\"></div>\n";
		echo "<input type=\"hidden\" name=\"castingcart\" value=\"1\"/>";
	echo "  </div>\n";
	echo "</div>\n";
	echo "</div>\n"; // END .content_wrapper 
       

get_footer(); 
?>