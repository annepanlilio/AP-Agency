<?php

// *************************************************************************************************** //
// Gobble Up The Variables, Set em' Sessions
foreach ($_REQUEST as $key => $value) {
  	if (substr($key, 0, 9) != "ProfileID") {
		$_SESSION[$key] = $value;  //$$key = $value;
  	}
}



// *************************************************************************************************** //
// Get Search Results

if ($_REQUEST["action"] == "search") {
		
	// Sort By
	$sort = "";
	if (isset($_REQUEST['sort']) && !empty($_REQUEST['sort'])){
		$sort = $_REQUEST['sort'];
	}
	else {
		$sort = "profile.ProfileContactNameFirst";
	}

	// Limit
	if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])){
	}
	else {
		$limit = " LIMIT 0,100";
	}

	// Sort Order
	$dir = "";
	if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])){
		$dir = $_REQUEST['dir'];
		if ($dir == "desc" || !isset($dir) || empty($dir)){
		   	$sortDirection = "asc";
	   	} else {
		   	$sortDirection = "desc";
		} 
	} else {
	   	$sortDirection = "desc";
	   	$dir = "asc";
	}

	// Filter
	$filterArray = array();

	// Name
	if ((isset($_REQUEST['ProfileContactNameFirst']) && !empty($_REQUEST['ProfileContactNameFirst'])) || isset($_REQUEST['ProfileContactNameLast']) && !empty($_REQUEST['ProfileContactNameLast'])){
	  	if (isset($_REQUEST['ProfileContactNameFirst']) && !empty($_REQUEST['ProfileContactNameFirst'])){
			$filterArray['profilecontactnamefirst'] = $_REQUEST['ProfileContactNameFirst'];
	  	}
	  	if (isset($_REQUEST['ProfileContactNameLast']) && !empty($_REQUEST['ProfileContactNameLast'])){
			$filterArray['profilecontactnamelast'] = $_REQUEST['ProfileContactNameLast'];
	  	}
	}

	// Location
	if (isset($_REQUEST['ProfileLocationCity']) && !empty($_REQUEST['ProfileLocationCity'])){
		$filterArray['profilelocationcity'] = $_REQUEST['ProfileLocationCity'];
	}

	// Type
	if (isset($_REQUEST['ProfileType']) && !empty($_REQUEST['ProfileType'])){
		$filterArray['profiletype'] = $_REQUEST['ProfileType'];
	}

	// Active
	if (isset($_REQUEST['ProfileIsActive'])){
		$filterArray['profileisactive'] = $_REQUEST['ProfileIsActive'];
	}

	// Gender
	if (isset($_REQUEST['ProfileGender']) && !empty($_REQUEST['ProfileGender'])){
		$filterArray['profilegender'] = $_REQUEST['ProfileGender'];
	}
	
	// Height
	if (isset($_REQUEST['ProfileStatHeight_min']) && !empty($_REQUEST['ProfileStatHeight_min'])){
		$filterArray['profilestatheight_min'] = $_REQUEST['ProfileStatHeight_min'];
	}
	if (isset($_REQUEST['ProfileStatHeight_max']) && !empty($_REQUEST['ProfileStatHeight_max'])){
		$filterArray['profilestatheight_max'] = $_REQUEST['ProfileStatHeight_max'];
	}

	// Weight
	if (isset($_REQUEST['ProfileStatWeight_min']) && !empty($_REQUEST['ProfileStatWeight_min'])){
		$filterArray['profilestatweight_min'] = $_REQUEST['ProfileStatWeight_min'];
	}
	if (isset($_REQUEST['ProfileStatWeight_max']) && !empty($_REQUEST['ProfileStatWeight_max'])){
		$filterArray['profilestatweight_max'] = $_REQUEST['ProfileStatWeight_max'];
	}

	// Age
	if (isset($_REQUEST['ProfileDateBirth_min']) && !empty($_REQUEST['ProfileDateBirth_min'])){
		$filterArray['profiledatebirth_min'] = $_REQUEST['ProfileDateBirth_min'];
	}
	if (isset($_REQUEST['ProfileDateBirth_max']) && !empty($_REQUEST['ProfileDateBirth_max'])){
		$filterArray['profiledatebirth_max'] = $_REQUEST['ProfileDateBirth_max'];
	}

	// City
	if (isset($_REQUEST['ProfileCity']) && !empty($_REQUEST['ProfileCity'])){
		$filterArray['profilecity'] = $_REQUEST['ProfileCity'];
	}		

	// State
	if (isset($_REQUEST['ProfileState']) && !empty($_REQUEST['ProfileState'])){
		$filterArray['profilestate'] = $_REQUEST['ProfileState'];
	}		

	// ZIP
	if (isset($_REQUEST['ProfileZip']) && !empty($_REQUEST['ProfileZip'])){
		$filterArray['profilezip'] = $_REQUEST['ProfileZip'];
	}
	
	// Custom Fields
	foreach($_POST as $key =>$val){
	
		if(substr($key,0,15)=="ProfileCustomID"){
	      	if(is_array($val)){
				if(count($val)>1){	
					$filterArray[$key] = implode(",",$val);	
			 	} else {
				 	if(!empty($val)){
						$filterArray[$key] = $val;
					}
			 	}
			} else {
		 		$filterArray[$key] = $val;
			}
		}
	}
	
	// Pagination
	$filterArray['paging'] = 1;
	$filterArray['pagingperpage'] = 1000; 
}

// *************************************************************************************************** //
// GET HEADER 

	get_header();
 /*  $sql = "SELECT * FROM ".table_agency_customfield_mux."";
    $q = mysql_query($sql) or die(mysql_error());
     while($f = mysql_fetch_assoc($q)){
		// print_r($f);
		 //echo "<br/>"; 
	 }
 */
	echo "<div id=\"primary\" class=\"eight column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

		echo "<div id=\"profile-search\">\n";
	    
			if ($_REQUEST["action"] == "search") {
		echo "  <h1 class=\"entry-title\">". __("Search Results", rb_agency_TEXTDOMAIN) ."</h1>\n";
			} else {
		echo "  <h1 class=\"entry-title\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</h1>\n";
		//echo "  <h1 class=\"entry-title\">". __("Search Results", rb_agency_TEXTDOMAIN) ."</h1>\n";
			}
	
		echo "  <div class=\"cb\"></div>\n";
#DEBUG 
#print_r($filterArray);
		echo "		    <div id=\"profile-search-results\">\n";
						if ($_REQUEST["action"] == "search") {
	                                 
							if (function_exists('rb_agency_profilelist')) { 
								rb_agency_profilelist($filterArray); 
							}
						} else {
							echo "<strong>". __("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
						}

/* Phel Comment: Removed Extra Search, We have already a Search widget
		echo "		    </div>\n";
		echo "		    <div id=\"profile-search-filter\" class=\"three column\">\n";
		echo "			<h3 class=\"title\">". __("Search Profiles", rb_agency_TEXTDOMAIN) ."</h3>\n";
		                        $isSearchPage = (get_query_var('type') == "search") ? 1 : 0;
						$profilesearch_layout = "advanced";
						include("include-profile-search.php"); 	
		
		echo "		    </div>\n";
*/
		echo "</div><!-- #profile-search-results -->\n"; // #profile-search-results
		echo "</div><!-- #profile-search -->\n"; // #profile-search
		echo "</div><!-- #content -->\n"; // #content

	echo "  </div><!-- #primary -->\n"; // #primary

get_sidebar(); 
get_footer(); 
?>