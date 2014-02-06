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

rb_header(); 

echo "	<div id=\"primary\" class=\"".fullwidth_class()." column\">\n";
echo "  	<div id=\"content\" role=\"main\" class=\"transparent\">\n";
echo '			<header class="entry-header">';
echo '				<h1 class="entry-title">Favorites</h1>';
echo '			</header>';
echo '			<div class="entry-content">';
echo "				<div id=\"profile-favorites\">\n";

						if (function_exists('rb_agency_profilelist')) { 
						  $atts = array("type" => $DataTypeID,"profilefavorite" => true);
						  rb_agency_profilelist($atts); 
						}							

echo "				</div>\n";
echo "				<div class=\"cb\"></div>\n";
echo "			</div><!-- .entry-content -->\n"; // .entry-content
echo "			<input type=\"hidden\" name=\"favorite\" value=\"1\"/>";
echo "  	</div><!-- #content -->\n"; // #content
echo "	</div><!-- #primary -->\n"; // #primary

//	get_sidebar();        

rb_footer(); ?>