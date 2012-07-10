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
		// Race
		if (isset($_REQUEST['ProfileStatEthnicity']) && !empty($_REQUEST['ProfileStatEthnicity'])){
			$filterArray['profilestatethnicity'] = $_REQUEST['ProfileStatEthnicity'];
		}
		// Skin
		if (isset($_REQUEST['ProfileStatSkinColor']) && !empty($_REQUEST['ProfileStatSkinColor'])){
			$filterArray['profilestatskincolor'] = $_REQUEST['ProfileStatSkinColor'];
		}
		// Eye
		if (isset($_REQUEST['ProfileStatEyeColor']) && !empty($_REQUEST['ProfileStatEyeColor'])){
			$filterArray['profilestateyecolor'] = $_REQUEST['ProfileStatEyeColor'];
		}
		// Hair
		if (isset($_REQUEST['ProfileStatHairColor']) && !empty($_REQUEST['ProfileStatHairColor'])){
			$filterArray['profilestathaircolor'] = $_REQUEST['ProfileStatHairColor'];
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
		// Bust/Chest
		if (isset($_REQUEST['ProfileStatBust_min']) && !empty($_REQUEST['ProfileStatBust_min'])){
			$filterArray['profilestatbust_min'] = $_REQUEST['ProfileStatBust_min'];
		}
		if (isset($_REQUEST['ProfileStatBust_max']) && !empty($_REQUEST['ProfileStatBust_max'])){
			$filterArray['profilestatbust_max'] = $_REQUEST['ProfileStatBust_max'];
		}
		// Waist
		if (isset($_REQUEST['ProfileStatWaist_min']) && !empty($_REQUEST['ProfileStatWaist_min'])){
			$filterArray['profilestatwaist_min'] = $_REQUEST['ProfileStatWaist_min'];
		}
		if (isset($_REQUEST['ProfileStatWaist_max']) && !empty($_REQUEST['ProfileStatWaist_max'])){
			$filterArray['profilestatwaist_max'] = $_REQUEST['ProfileStatWaist_max'];
		}
		// Hip
		if (isset($_REQUEST['ProfileStatHip_min']) && !empty($_REQUEST['ProfileStatHip_min'])){
			$filterArray['profilestathip_min'] = $_REQUEST['ProfileStatHip_min'];
		}
		if (isset($_REQUEST['ProfileStatHip_max']) && !empty($_REQUEST['ProfileStatHip_max'])){
			$filterArray['profilestathip_max'] = $_REQUEST['ProfileStatHip_max'];
		}
		// Shoe
		if (isset($_REQUEST['ProfileStatShoe_min']) && !empty($_POST['ProfileStatShoe_min'])){
			$filterArray['profilestatshoe_min'] = $_REQUEST['ProfileStatShoe_min'];
		}
		if (isset($_REQUEST['ProfileStatShoe_max']) && !empty($_POST['ProfileStatShoe_max'])){
			$filterArray['profilestatshoe_max'] = $_REQUEST['ProfileStatShoe_max'];
		}
		// Age
		if (isset($_REQUEST['ProfileDateBirth_min']) && !empty($_REQUEST['ProfileDateBirth_min'])){
			$filterArray['profiledatebirth_min'] = $_REQUEST['ProfileDateBirth_min'];
		}
		if (isset($_REQUEST['ProfileDateBirth_max']) && !empty($_REQUEST['ProfileDateBirth_max'])){
			$filterArray['profiledatebirth_max'] = $_REQUEST['ProfileDateBirth_max'];
		}
		
		// Custom Fields
		foreach($_REQUEST as $key =>$val){
		
				if(substr($key,0,15)=="ProfileCustomID"){
					if(isset($val) && !empty($val)){
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
   $sql = "SELECT * FROM ".table_agency_customfield_mux."";
    $q = mysql_query($sql) or die(mysql_error());
     while($f = mysql_fetch_assoc($q)){
		// print_r($f);
		 //echo "<br/>"; 
	 }
   echo $filter;
	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

		echo "<div id=\"profile-search\">\n";
	
			if ($_REQUEST["action"] == "search") {
		echo "  <h1 class=\"profile-search-title\">". __("Search Results", rb_agency_TEXTDOMAIN) ."</h1>\n";
			} else {
		echo "  <h1 class=\"profile-search-title\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</h1>\n";
			}
	
		echo "  <div class=\"clear line\"></div>\n";
		
		echo "  <table class=\"standardTable\">\n";
		echo "   <tbody>\n";
		echo "  	<tr>\n";
		echo "      <td class=\"profile-search-results-wrapper\">\n";
		echo "		    <div class=\"profile-search-results\">\n";
						if ($_REQUEST["action"] == "search") {
	
							if (function_exists('rb_agency_profilelist')) { 
								rb_agency_profilelist($filterArray); 
							}
						} else {
							echo "<strong>". __("No search chriteria selected, please initiate your search.", rb_agency_TEXTDOMAIN) ."</strong>";
						}
									
		echo "		    </div>\n";
		echo "      </td>\n";
		echo "      <td class=\"profile-search-filter-wrapper\">\n";
		echo "		    <div class=\"profile-search-filter\">\n";
		echo "			<h3 class=\"title\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</h3>\n";
		
						$profilesearch_layout = "advanced";
						include("include-profile-search.php"); 	
		
		echo "		    </div>\n";
		echo "      </td>\n";
		echo "   </tbody>\n";
		echo "  </table>\n";
		
		echo "</div>\n"; // #profile-search

	echo "  </div>\n";
	echo "</div>\n";
	

//get_sidebar(); 
get_footer(); 
?>