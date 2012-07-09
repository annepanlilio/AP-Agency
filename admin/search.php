<?php
session_start();

global $wpdb;

global $current_user;
get_currentuserinfo();
$unique = $current_user->ID;

$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_unittype =  $rb_agency_options_arr['rb_agency_option_unittype'];
	$rb_agency_option_persearch = (int)$rb_agency_options_arr['rb_agency_option_persearch'];
		if ($rb_agency_option_persearch < 0) { $rb_agency_option_persearch = 100; }

echo "<div class=\"wrap\" style=\"min-width: 1020px;\">\n";
echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
echo "  <h2>". __("Profile Search", rb_agency_TEXTDOMAIN) . "</h2>\n";


// *************************************************************************************************** //
// Setup Functions 

	$sessionString = "";
	// Gobble Up The Variables, Set em' Sessions
		foreach ($_GET as $key => $value) {
		  if (substr($key, 0, 9) != "ProfileID") {
			$_SESSION[$key] = $value;  //$$key = $value;
			$sessionString .= $key ."=". $value ."&";
		  }
		}
		// Clean It!
		$sessionString = rb_agency_cleanString($sessionString);

// *************************************************************************************************** //
// Get Actions 

	// Protect and defend the cart string!
		$cartString = "";
	// Add to Cart
		if ($_GET["action"] == "cartAdd") { 
			extract($_GET);
			foreach($_GET as $key=>$value) {
			  if (substr($key, 0, 9) == "ProfileID") {
				$cartString .= $value .",";
			  }
			}
			// Clean It!
			$cartString = rb_agency_cleanString($cartString);
			
			if (isset($_SESSION['cartArray'])) {
				$cartArray = $_SESSION['cartArray'];
				array_push($cartArray, $cartString);
			} else {
				$cartArray = array($cartString);
			}
		   
		   $_SESSION['cartArray'] = $cartArray;
		
		} elseif ($_GET["action"] == "formEmpty") {  // Handle Form Empty 
			extract($_SESSION);
			foreach($_SESSION as $key=>$value) {
			  if (substr($key, 0, 7) == "Profile") {
				unset($_SESSION[$key]);
			  }
			}
		} elseif ($_GET["action"] == "cartEmpty") {  // Handle Cart Removal
			// Throw the baby out with the bathwater
			unset($_SESSION['cartArray']);
			
		} elseif (($_GET["action"] == "cartRemove") && (isset($_GET["RemoveID"]))) {
			$cartArray = $_SESSION['cartArray'];
			$cartString = implode(",", $cartArray);
			$cartRemoveID = $_GET["RemoveID"];
			$cartString = str_replace($_GET['RemoveID'] ."", "", $cartString);
			$cartString = rb_agency_cleanString($cartString);
			// Put it back in the array, and wash your hands
			$_SESSION['cartArray'] = array($cartString);
		
		} elseif (($_GET["action"] == "searchSave") && isset($_SESSION['cartArray'])) {
		
			extract($_SESSION);
			foreach($_SESSION as $key=>$value) {
			}
			$_SESSION['cartArray'] = $cartArray;
		
		}





// *************************************************************************************************** //
// Get Search Results

	if ($_GET["action"] == "search") {
		
		// Sort By
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		}
		else {
			$sort = "profile.ProfileContactNameFirst";
		}
	
		// Limit
		if (isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = "";
		} else {
			if ($rb_agency_option_persearch > 1) {
			$limit = " LIMIT 0,". $rb_agency_option_persearch;
			}
		}
	
		// Sort Order
		$dir = "";
		if (isset($_GET['dir']) && !empty($_GET['dir'])){
			$dir = $_GET['dir'];
			if ($dir == "desc" || !isset($dir) || empty($dir)){
			   $sortDirection = "asc";
			   } else {
			   $sortDirection = "desc";
			} 
		} else {
			   $sortDirection = "desc";
			   $dir = "asc";
		}
	
		//// Filter
		$filter = " WHERE profile.ProfileID > 0";
		// Name
		if ((isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) || isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])){
			$ProfileContactNameFirst = $_GET['ProfileContactNameFirst'];
			$filter .= " AND profile.ProfileContactNameFirst='". $ProfileContactNameFirst ."'";
			}
			if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			$ProfileContactNameLast = $_GET['ProfileContactNameLast'];
			$filter .= " AND profile.ProfileContactNameLast='". $ProfileContactNameLast ."'";
			}
		}
		// Location
		if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])){
			$ProfileLocationCity = $_GET['ProfileLocationCity'];
			$filter .= " AND profile.ProfileLocationCity='". $ProfileLocationCity ."'";
		}
		// Type
		if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])){
			$ProfileType = $_GET['ProfileType'];
			$filter .= " AND profile.ProfileType=". $ProfileType ."";
		} else {
			$ProfileType = "";
		}
		// Active
		if (isset($_GET['ProfileIsActive'])){
		  if ($_GET['ProfileIsActive'] == "1") {
			$selectedActive = "active";
			$filter .= " AND profile.ProfileIsActive=1";
		  } elseif ($_GET['ProfileIsActive'] == "0") {
			$selectedActive = "inactive";
			$filter .= " AND profile.ProfileIsActive=0";
		  } elseif ($_GET['ProfileIsActive'] == "2") {
			$selectedActive = "declassified";
			$filter .= " AND profile.ProfileIsActive=2";
		  }
		} else {
			$selectedActive = "";
			$filter .= " AND (profile.ProfileIsActive=1 OR profile.ProfileIsActive=0)";
		}
		// Gender
		if (isset($_GET['ProfileGender']) && !empty($_GET['ProfileGender'])){
			$ProfileGender = $_GET['ProfileGender'];
		  if ($_GET['ProfileGender'] == "Female") {
			$filter .= " AND profile.ProfileGender='female'";
		  } elseif ($_GET['ProfileGender'] == "Male") {
			$filter .= " AND profile.ProfileGender='male'";
		  }
		} else {
			$ProfileGender = "";
		}
		// Race
		if (isset($_GET['ProfileStatEthnicity']) && !empty($_GET['ProfileStatEthnicity'])){
			$ProfileStatEthnicity = $_GET['ProfileStatEthnicity'];
			$filter .= " AND profile.ProfileStatEthnicity='". $ProfileStatEthnicity ."'";
		}
		// Skin
		if (isset($_GET['ProfileStatSkinColor']) && !empty($_GET['ProfileStatSkinColor'])){
			$ProfileStatSkinColor = $_GET['ProfileStatSkinColor'];
			$filter .= " AND profile.ProfileStatSkinColor='". $ProfileStatSkinColor ."'";
		}
		// Eye
		if (isset($_GET['ProfileStatEyeColor']) && !empty($_GET['ProfileStatEyeColor'])){
			$ProfileStatEyeColor = $_GET['ProfileStatEyeColor'];
			$filter .= " AND profile.ProfileStatEyeColor='". $ProfileStatEyeColor ."'";
		}
		// Hair
		if (isset($_GET['ProfileStatHairColor']) && !empty($_GET['ProfileStatHairColor'])){
			$ProfileStatHairColor = $_GET['ProfileStatHairColor'];
			$filter .= " AND profile.ProfileStatHairColor='". $ProfileStatHairColor ."'";
		}
		// Height
		if (isset($_GET['ProfileStatHeight_min']) && !empty($_GET['ProfileStatHeight_min'])){
			$ProfileStatHeight_min = $_GET['ProfileStatHeight_min'];
			$filter .= " AND profile.ProfileStatHeight >= '". $ProfileStatHeight_min ."'";
		}
		if (isset($_GET['ProfileStatHeight_max']) && !empty($_GET['ProfileStatHeight_max'])){
			$ProfileStatHeight_max = $_GET['ProfileStatHeight_max'];
			$filter .= " AND profile.ProfileStatHeight <= '". $ProfileStatHeight_max ."'";
		}
		// Weight
		if (isset($_GET['ProfileStatWeight_min']) && !empty($_GET['ProfileStatWeight_min'])){
			$ProfileStatWeight_min = $_GET['ProfileStatWeight_min'];
			$filter .= " AND profile.ProfileStatWeight >= '". $ProfileStatWeight_min ."'";
		}
		if (isset($_GET['ProfileStatWeight_max']) && !empty($_GET['ProfileStatWeight_max'])){
			$ProfileStatWeight_max = $_GET['ProfileStatWeight_max'];
			$filter .= " AND profile.ProfileStatWeight <= '". $ProfileStatWeight_max ."'";
		}
		// Bust/Chest
		if (isset($_GET['ProfileStatBust_min']) && !empty($_GET['ProfileStatBust_min'])){
			$ProfileStatBust_min = $_GET['ProfileStatBust_min'];
			$filter .= " AND profile.ProfileStatBust >= '". $ProfileStatBust_min ."'";
		}
		if (isset($_GET['ProfileStatBust_max']) && !empty($_GET['ProfileStatBust_max'])){
			$ProfileStatBust_max = $_GET['ProfileStatBust_max'];
			$filter .= " AND profile.ProfileStatBust <= '". $ProfileStatBust_max ."'";
		}
		// Waist
		if (isset($_GET['ProfileStatWaist_min']) && !empty($_GET['ProfileStatWaist_min'])){
			$ProfileStatWaist_min = $_GET['ProfileStatWaist_min'];
			$filter .= " AND profile.ProfileStatWaist >= '". $ProfileStatWaist_min ."'";
		}
		if (isset($_GET['ProfileStatWaist_max']) && !empty($_GET['ProfileStatWaist_max'])){
			$ProfileStatWaist_max = $_GET['ProfileStatWaist_max'];
			$filter .= " AND profile.ProfileStatWaist <= '". $ProfileStatWaist_max ."'";
		}
		// Hip
		if (isset($_GET['ProfileStatHip_min']) && !empty($_GET['ProfileStatHip_min'])){
			$ProfileStatHip_min = $_GET['ProfileStatHip_min'];
			$filter .= " AND profile.ProfileStatHip >= '". $ProfileStatHip_min ."'";
		}
		if (isset($_GET['ProfileStatHip_max']) && !empty($_GET['ProfileStatHip_max'])){
			$ProfileStatHip_max = $_GET['ProfileStatHip_max'];
			$filter .= " AND profile.ProfileStatHip <= '". $ProfileStatHip_max ."'";
		}
		// Shoe
		if (isset($_POST['ProfileStatShoe_min']) && !empty($_POST['ProfileStatShoe_min'])){
			$ProfileStatShoe_min = $_POST['ProfileStatShoe_min'];
			$filter .= " AND profile.ProfileStatShoe >= '". $ProfileStatShoe_min ."'";
		}
		if (isset($_POST['ProfileStatShoe_max']) && !empty($_POST['ProfileStatShoe_max'])){
			$ProfileStatShoe_max = $_POST['ProfileStatShoe_max'];
			$filter .= " AND profile.ProfileStatShoe <= '". $ProfileStatShoe_max ."'";
		}
		// Age
		$timezone_offset = -10; // Hawaii Time
		$dateInMonth = gmdate('d', time() + $timezone_offset *60 *60);
		$format = 'Y-m-d';
		$date = gmdate($format, time() + $timezone_offset *60 *60);
		
		if (isset($_GET['ProfileDateBirth_min']) && !empty($_GET['ProfileDateBirth_min'])){
			$ProfileDateBirth_min = $_GET['ProfileDateBirth_min'];
			$selectedYearMin = date($format, strtotime('-'. $ProfileDateBirth_min .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth <= '$selectedYearMin'";
		}
		if (isset($_GET['ProfileDateBirth_max']) && !empty($_GET['ProfileDateBirth_max'])){
			$ProfileDateBirth_max = $_GET['ProfileDateBirth_max'];
			$selectedYearMax = date($format, strtotime('-'. $ProfileDateBirth_max-1 .' year'. $date));
			$filter .= " AND profile.ProfileDateBirth >= '$selectedYearMax'";
		}


        echo "  <div class=\"boxblock-holder\">\n";
        echo "    <h3 class=\"title\">Search Results</h3>\n";

		// Filter Models Already in Cart
        if (isset($_SESSION['cartArray'])) {
            $cartArray = $_SESSION['cartArray'];
            $cartString = implode(",", $cartArray);
			$cartQuery =  " AND profile.ProfileID NOT IN (". $cartString .")";
		}
		// Search Results	
        $query = "SELECT profile.*, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile $filter $cartQuery ORDER BY $sort $dir $limit";
		$results2 = mysql_query($query);
        $count = mysql_num_rows($results2);
		if (($count > ($rb_agency_option_persearch -1)) && (!isset($_GET['limit']) && empty($_GET['limit']))) {
			echo "<div id=\"message\" class=\"error\"><p>Search exceeds ". $rb_agency_option_persearch ." records first ". $rb_agency_option_persearch ." displayed below.  <a href=". admin_url("admin.php?page=". $_GET['page']) ."&". $sessionString ."&limit=none><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p></div>\n";
		}

        echo "       <form method=\"get\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
        echo "        <input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
        echo "        <input type=\"hidden\" name=\"action\" value=\"cartAdd\" />\n";
        //echo "        <input type=\"hidden\" name=\"forceQuery\" value=\"". $cartString ."\" />\n";
        echo "        <input type=\"hidden\" name=\"forceCart\" value=\"". $_SESSION['cartArray'] ."\" />\n";
        echo "        <table cellspacing=\"0\" class=\"widefat fixed\">\n";
        echo "        <thead>\n";
        echo "            <tr class=\"thead\">\n";
        echo "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
        echo "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"admin.php?page=rb_agency_menu_profiles&sort=ProfileID&dir=". $sortDirection ."\">". __("ID", rb_agency_TEXTDOMAIN) ."</a></th>\n";
        echo "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Details", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Stats", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileImage\" id=\"ProfileImage\" scope=\"col\" style=\"width:150px;\">". __("Headshot", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "            </tr>\n";
        echo "        </thead>\n";
        echo "        <tfoot>\n";
        echo "            <tr class=\"thead\">\n";
        echo "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
        echo "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\">". __("ID", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Details", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Stats", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "                <th class=\"column-image\" id=\"col-image\" scope=\"col\">". __("Headshot", rb_agency_TEXTDOMAIN) ."</th>\n";
        echo "            </tr>\n";
        echo "        </tfoot>\n";
        echo "        <tbody>\n";

        while ($data = mysql_fetch_array($results2)) {
            $ProfileID = $data['ProfileID'];
        echo "        <tr>\n";
        echo "            <th class=\"check-column\" scope=\"row\">\n";
        echo "                <input type=\"checkbox\" value=\"". $ProfileID ."\" class=\"administrator\" id=\"ProfileID". $ProfileID ."\" name=\"ProfileID". $ProfileID ."\" />\n";
        echo "            </th>\n";
        echo "            <td class=\"ProfileID column-ProfileID\">". $ProfileID ."</td>\n";
        echo "            <td class=\"ProfileContact column-ProfileContact\">\n";
        echo "                <div class=\"detail\">\n";
				if (!empty($data['ProfileContactEmail'])) {
					echo "<div><strong>Email:</strong> ". $data['ProfileContactEmail'] ."</div>\n";
				}
				if (!empty($data['ProfileContactWebsite'])) {
					echo "<div><strong>Website:</strong> ". $data['ProfileContactWebsite'] ."</div>\n";
				}
				if (!empty($data['ProfileLocationStreet'])) {
					echo "<div><strong>Street:</strong> ". $data['ProfileLocationStreet'] ."</div>\n";
				}
				if (!empty($data['ProfileLocationStreet'])) {
					echo "<div><strong>Address:</strong> ". $data['ProfileLocationCity'] .", ". $data['ProfileLocationState'] ." ". $data['ProfileLocationZip'] ."</div>\n";
				}
				if (!empty($data['ProfileContactParent'])) {
					echo "<div><strong>Parent:</strong> ". $data['ProfileContactParent'] ."</div>\n";
				}
				if (!empty($data['ProfileContactPhoneWork'])) {
					echo "<div><strong>Work Phone:</strong> ". $data['ProfileContactPhoneWork'] ."</div>\n";
				}
				if (!empty($data['ProfileContactPhoneCell'])) {
					echo "<div><strong>Cell Phone:</strong> ". $data['ProfileContactPhoneCell'] ."</div>\n";
				}
				if (!empty($data['ProfileContactPhoneHome'])) {
					echo "<div><strong>Home Phone:</strong> ". $data['ProfileContactPhoneHome'] ."</div>\n";
				}

        echo "                </div>\n";
        echo "                <div class=\"title\">\n";
        echo "                	<h2>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h2>\n";
        echo "                </div>\n";
        echo "                <div class=\"row-actions\">\n";
        echo "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_menu_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
        echo "                    <span class=\"review\"><a href=\"". rb_agency_PROFILEDIR . $rb_agency_UPLOADDIR . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
        echo "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_menu_profiles&amp;deleteRecord&amp;ProfileID=". $ProfileID ."' onclick=\"if ( confirm('You are about to delete the model \'". $ProfileContactNameFirst ." ". $ProfileContactNameLast ."\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;\">Delete</a></span>\n";
        echo "                </div>\n";
        echo "            </td>\n";
        echo "            <td class=\"ProfileDetails column-ProfileDetails\">\n";

				if (!empty($data['ProfileUnion'])) {
					echo "<div><strong>Union:</strong> ". $data['ProfileUnion'] ."</div>\n";
				}
				if (!empty($data['ProfileLanguage'])) {
					echo "<div><strong>Language:</strong> ". $data['ProfileLanguage'] ."</div>\n";
				}
				if (!empty($data['ProfileGender'])) {
					echo "<div><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileGender'] ."</div>\n";
				}
				if (!empty($data['ProfileDateBirth'])) {
					echo "<div><strong>". __("Age", rb_agency_TEXTDOMAIN) .":</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."</div>\n";
				}
				if (!empty($data['ProfileDateBirth'])) {
					echo "<div><strong>". __("Birthdate", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileDateBirth'] ."</div>\n";
				}
				$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView > 0 AND cx.ProfileID = ". $ProfileID ."");
				foreach  ($resultsCustom as $resultCustom) {
					echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
				}

        echo "            </td>\n";
        echo "            <td class=\"ProfileStats column-ProfileStats\">\n";

				if (!empty($data['ProfileStatEthnicity'])) {
					echo "<div><strong>". __("Ethnicity", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatEthnicity'] ."</div>\n";
				}
				if (!empty($data['ProfileStatSkinColor'])) {
					echo "<div><strong>". __("Skin Tone", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatSkinColor'] ."</div>\n";
				}
				if (!empty($data['ProfileStatHairColor'])) {
					echo "<div><strong>". __("Hair Color", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatHairColor'] ."</div>\n";
				}
				if (!empty($data['ProfileStatEyeColor'])) {
					echo "<div><strong>". __("Eye Color", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatEyeColor'] ."</div>\n";
				}
				if (!empty($data['ProfileStatHeight'])) {
				  if ($rb_agency_option_unittype == 1) {
						$heightraw = $data['ProfileStatHeight'];
						$heightfeet = floor($heightraw/12);
						$heightinch = $heightraw - floor($heightfeet*12);
						echo "<div><strong>". __("Height", rb_agency_TEXTDOMAIN) .":</strong> ". $heightfeet ." ft ". $heightinch ." in" ."</div>\n";
				  } else {
						echo "<div><strong>". __("Height", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatHeight'] ." cm" ."</div>\n";
				  }
				}
				if (!empty($data['ProfileStatWeight'])) {
				  if ($rb_agency_option_unittype == 1) {
					echo "<div><strong>". __("Weight", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatWeight'] ." lb</div>\n";
				  } else {
					echo "<div><strong>". __("Weight", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatWeight'] ." kg</div>\n";
				  }
				}
				if (!empty($data['ProfileStatBust'])) {
					if($data['ProfileGender'] == "Male"){ $ProfileStatBustTitle = "Chest"; } elseif ($data['ProfileGender'] == "Female"){ $ProfileStatBustTitle = "Bust"; } else { $ProfileStatBustTitle = "Chest/Bust"; }
					echo "<div><strong>". $ProfileStatBustTitle ."</strong> ". $data['ProfileStatBust'] ."</div>\n";
				}
				if (!empty($data['ProfileStatWaist'])) {
					echo "<div><strong>". __("Waist", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatWaist'] ."</div>\n";
				}
				if (!empty($data['ProfileStatHip'])) {
					if($data['ProfileGender'] == "Male"){ $ProfileStatHipTitle = "Inseam"; } elseif ($data['ProfileGender'] == "Female"){ $ProfileStatHipTitle = "Hips"; } else { $ProfileStatHipTitle = "Hips/Inseam"; }
					echo "<div><strong>". $ProfileStatHipTitle ."</strong> ". $data['ProfileStatHip'] ."</div>\n";
				}
				if (!empty($data['ProfileStatDress'])) {
					if($data['ProfileGender'] == "Male"){ $ProfileStatDressTitle = "Suit Size"; } elseif ($data['ProfileGender'] == "Female"){ $ProfileStatDressTitle = "Dress Size"; } else { $ProfileStatDressTitle = "Suit/Dress Size"; }
					echo "<div><strong>". $ProfileStatDressTitle ."</strong> ". $data['ProfileStatDress'] ."</div>\n";
				}
				if (!empty($data['ProfileStatShoe'])) {
					echo "<div><strong>". __("Shoe Size", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileStatShoe'] ."</div>\n";
				}
				$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ."");
				foreach  ($resultsCustom as $resultCustom) {
					echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
				}

        echo "            </td>\n";
        echo "            <td class=\"ProfileImage column-ProfileImage\">\n";
							if (isset($data['ProfileMediaURL']) && !empty($data['ProfileMediaURL'])) {
		echo "				<div class=\"image\"><img style=\"width: 150px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
							} else {
		echo "				<div class=\"image no-image\">NO IMAGE</div>\n";
							}
        echo "            </td>\n";
        echo "        </tr>\n";
        }
            mysql_free_result($results2);
            if ($count < 1) {
				if (isset($filter)) { 
        echo "        <tr>\n";
        echo "            <th class=\"check-column\" scope=\"row\"></th>\n";
        echo "            <td class=\"name column-name\" colspan=\"5\">\n";
        echo "                <p>". __("No profiles found with this criteria!", rb_agency_TEXTDOMAIN) .".</p>\n";
        echo "            </td>\n";
        echo "        </tr>\n";
				} else {
        echo "        <tr>\n";
        echo "            <th class=\"check-column\" scope=\"row\"></th>\n";
        echo "            <td class=\"name column-name\" colspan=\"5\">\n";
        echo "                <p>". __("There aren't any Profiles loaded yet!", rb_agency_TEXTDOMAIN) ."</p>\n";
        echo "            </td>\n";
        echo "        </tr>\n";
				} 
        } 
        echo "        </tbody>\n";
        echo "    </table>\n";

    
        echo "     <p>\n";
        echo "      	<input type=\"submit\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','rb_agency_menu_search') ."\" class=\"button-primary\" />\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"". __("Quick Print", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=2','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Images", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Images", rb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
		echo "     </p>\n";
		echo "  </form>\n";
      
    
} else {
// Ok.. now what ???
}

if (($_GET["action"] == "search") || ($_GET["action"] == "cartAdd") || (isset($_SESSION['cartArray']))) {

	echo "<div class=\"boxblock-container\" style=\"float: left; width: 49%; min-width: 500px;\">\n";
	echo " <div class=\"boxblock\">\n";
	echo "  <h3>". __("Casting Cart", rb_agency_TEXTDOMAIN) ."</h3>\n";
	echo "    <div class=\"inner\">\n";

         if (isset($_SESSION['cartArray']) && !empty($_SESSION['cartArray'])) {
			 
            $cartArray = $_SESSION['cartArray'];
            	$cartString = implode(",", array_unique($cartArray));
				$cartString = rb_agency_cleanString($cartString);
            
			// Show Cart  
            $query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (". $cartString .") ORDER BY ProfileContactNameFirst ASC";
			$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
            $count = mysql_num_rows($results);
            
            echo "<div style=\"float: right; width: 100px; \"><a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("Empty Cart", rb_agency_TEXTDOMAIN) ."</a></div>";
            echo "<div style=\"float: left; line-height: 22px; font-family:Georgia; font-size:13px; font-style: italic; color: #777777; \">". __("Currently", rb_agency_TEXTDOMAIN) ." <strong>". $count ."</strong> ". __("in Cart", rb_agency_TEXTDOMAIN) ."</div>";
            echo "<div style=\"clear: both; border-top: 2px solid #c0c0c0; \" class=\"profile\">";
			
            if ($count == 1) {
                $cartAction = "cartEmpty";
            } elseif ($count < 1) {
                echo "". __("There are currently no profiles in the casting cart", rb_agency_TEXTDOMAIN) .".";
                $cartAction = "cartEmpty";
            } else {
                $cartAction = "cartRemove";
            }
            while ($data = mysql_fetch_array($results)) {
				
                $ProfileDateUpdated = $data['ProfileDateUpdated'];
				
                echo "<div style=\"position: relative; border: 1px solid #e1e1e1; line-height: 22px; float: left; padding: 10px; width: 210px; margin: 6px; \">";
                echo " <div style=\"text-align: center; \"><h3>". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</h3></div>"; 
                echo " <div style=\"float: left; width: 100px; height: 100px; overflow: hidden; margin-top: 2px; \"><img style=\"width: 100px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
                echo " <div style=\"float: left; width: 100px; height: 100px; overflow: scroll-y; margin-left: 10px; line-height: 11px; font-size: 9px; \">\n";
                
                    if (!empty($data['ProfileDateBirth'])) {
                        echo "<strong>Age:</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."<br />\n";
                    }
                    if (!empty($data['ProfileStatSkinColor'])) {
                        echo "<strong>Skin:</strong> ". $data['ProfileStatSkinColor'] ."<br />\n";
                    }
                    if (!empty($data['ProfileStatHairColor'])) {
                        echo "<strong>Hair:</strong> ". $data['ProfileStatHairColor'] ."<br />\n";
                    }
                    if (!empty($data['ProfileStatEyeColor'])) {
                        echo "<strong>Eye:</strong> ". $data['ProfileStatEyeColor'] ."<br />\n";
                    }

                    if (!empty($data['ProfileStatHeight'])) {
					  if ($ModelAgencyDisplayUnit == 1) {
                          $heightraw = $data['ProfileStatHeight'];
                          $heightfeet = floor($heightraw/12);
                          $heightinch = $heightraw - floor($heightfeet*12);
                        echo "<strong>Height:</strong> ". $heightfeet ." ft ". $heightinch ." in" ."<br />\n";
					  } else {
                        echo "<strong>Height:</strong> ". $data['ProfileStatHeight'] ." cm" ."<br />\n";
					  }
                    }
                    if (!empty($data['ProfileStatWeight'])) {
					  if ($ModelAgencyDisplayUnit == 1) {
                        echo "<strong>Weight:</strong> ". $data['ProfileStatWeight'] ." lb<br />\n";
					  } else {
                        echo "<strong>Weight:</strong> ". $data['ProfileStatWeight'] ." kg" ."<br />\n";
					  }
                    }
                    if (!empty($data['ProfileStatBust'])) {
                        if($data['ProfileGender'] == "Male"){ $ProfileStatBustTitle = "Chest"; } elseif ($data['ProfileGender'] == "Female"){ $ProfileStatBustTitle = "Bust"; } else { $ProfileStatBustTitle = "Chest/Bust"; }
                        echo "<strong>". $ProfileStatBustTitle ."</strong> ". $data['ProfileStatBust'] ."<br />\n";
                    }
                    if (!empty($data['ProfileStatWaist'])) {
                        echo "<strong>Waist:</strong> ". $data['ProfileStatWaist'] ."<br />\n";
                    }
                    if (!empty($data['ProfileStatHip'])) {
                        if($data['ProfileGender'] == "Male"){ $ProfileStatHipTitle = "Inseam"; } elseif ($data['ProfileGender'] == "Female"){ $ProfileStatHipTitle = "Hips"; } else { $ProfileStatHipTitle = "Hips/Inseam"; }
                        echo "<strong>". $ProfileStatHipTitle ."</strong> ". $data['ProfileStatHip'] ."<br />\n";
                    }
                    
                echo " </div>";
                echo " <div style=\"position: absolute; z-index: 20; top: 120px; left: 200px; width: 20px; height: 20px; overflow: hidden; \"><a href=\"?page=". $_GET['page'] ."&action=". $cartAction ."&RemoveID=". $data['ProfileID'] ."\" title=\"". __("Remove from Cart", rb_agency_TEXTDOMAIN) ."\"><img src=\"". rb_agency_BASEDIR ."style/remove.png\" style=\"width: 20px; \" alt=\"". __("Remove from Cart", rb_agency_TEXTDOMAIN) ."\" /></a></div>";
                echo " <div style=\"clear: both; \"></div>";
                echo "</div>";
            }
            mysql_free_result($results);
            echo "<div style=\"clear: both;\"></div>\n";
            echo "</div>";
            
         } else {
            //$cartAction = "cartRemove";
            echo "<p>There are no profiles added to the casting cart.</p>\n";
         }

	echo " </div>\n";
	echo "</div>\n";

	 if (($cartAction == "cartEmpty") || ($cartAction == "cartRemove")) {
        echo "     <div class=\"boxblock\">\n";
        echo "        <h3>". __("Cart Actions", rb_agency_TEXTDOMAIN) ."</h3>\n";
        echo "        <div class=\"inner\">\n";
        echo "      	<a href=\"?page=rb_agency_menu_searchsaved&action=searchSave\" title=\"". __("Save Search & Email", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Save Search & Email", rb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"". __("Quick Print", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=2','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Images", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Images", rb_agency_TEXTDOMAIN) ."</a>\n";
        echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";

		echo "        </div>\n";
		echo "     </div>\n";
	} // Is Cart Empty 
    
		echo "    </div><!-- .container -->\n";
} 

		echo "    <div class=\"boxblock-container\" style=\"float: left; width: 49%;\">\n";
		echo "     <div class=\"boxblock\">\n";
		echo "      <h3>". __("Profile Search", rb_agency_TEXTDOMAIN) ."</h3>\n";
		echo "        <div class=\"inner\">\n";
		echo "        	<form method=\"GET\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"rb_agency_menu_search\" />\n";
		echo "        		<input type=\"hidden\" name=\"action\" value=\"search\" />\n";
		echo "				<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "				  <thead>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("First Name", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $_SESSION['ProfileContactNameFirst'] ."\" />\n";               
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Last Name", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION['ProfileContactNameLast'] ."\" />\n";               
		echo "				        </td>\n";
		echo "				    </tr>\n";
		
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Classification", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileType\" id=\"ProfileType\">\n";               
		echo "							<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
										$profileDataTypes = mysql_query("SELECT * FROM ". table_agency_data_type ."");
										while ($dataType = mysql_fetch_array($profileDataTypes)) {
											if ($_SESSION['ProfileType']) {
												if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
											} else { $selectedvalue = ""; }
											echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Gender", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileGender\" id=\"ProfileGender\">\n";               
		echo "							<option value=\"\">". __("Both Male and Female", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"Male\"". selected($_SESSION['ProfileGender'], "Male") .">". __("Male", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"Female\"". selected($_SESSION['ProfileGender'], "Female") .">". __("Female", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Age", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_GET['ProfileDateBirth_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_GET['ProfileDateBirth_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Height", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
									if ($rb_agency_option_unittype == 1) {
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<select name=\"ProfileStatHeight_min\" id=\"ProfileStatHeight_min\">\n";               
										//if (empty($_SESSION['ProfileStatHeight_min'])) {
		echo "							<option value=\"\" selected>". __("No Minimum", rb_agency_TEXTDOMAIN) . "</option>\n";
										//}
                        				// Lets Convert It
										$i=36;$heightraw = 0; $heightfeet = 0; $heightinch = 0;
										while($i<=90) { 
											$heightraw = $i;
											$heightfeet = floor($heightraw/12);
											$heightinch = $heightraw - floor($heightfeet*12);
		echo "								<option value=\"". $i ."\" ". selected($_SESSION['ProfileStatHeight_min'], $i) .">". $heightfeet ." ft ". $heightinch ." in" . "</option>\n";
										  $i++;
										}
		echo "				        	</select><br />\n";

		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<select name=\"ProfileStatHeight_max\" id=\"ProfileStatHeight_max\">\n";               
										//if (empty($_SESSION['ProfileStatHeight_max'])) {
		echo "							<option value=\"\" selected>". __("No Maximum", rb_agency_TEXTDOMAIN) . "</option>\n";
										//}
										// Lets Convert It
										$i=36; $heightraw = 0; $heightfeet = 0; $heightinch = 0;
										while($i <= 90) {
											$heightraw = $i;
											$heightfeet = floor($heightraw/12);
											$heightinch = $heightraw - floor($heightfeet*12);
	echo "									<option value=\"". $i ."\" ". selected($_SESSION['ProfileStatHeight_max'], $i) .">". $heightfeet ." ft ". $heightinch ." in" . "</option>\n";
										  $i++;
										}
		echo "				        	</select>\n";

									} else {
									// Metric
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . " (". __("cm", rb_agency_TEXTDOMAIN) . "):\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHeight_min\" name=\"ProfileStatHeight_min\" value=\"". $_SESSION['ProfileStatHeight_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . " (". __("cm", rb_agency_TEXTDOMAIN) . "):\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHeight_max\" name=\"ProfileStatHeight_max\" value=\"". $_SESSION['ProfileStatHeight_max'] ."\" />\n";
									}

		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Weight", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWeight_min\" name=\"ProfileStatWeight_min\" value=\"". $_SESSION['ProfileStatWeight_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWeight_max\" name=\"ProfileStatWeight_max\" value=\"". $_SESSION['ProfileStatWeight_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Ethnicity", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileStatEthnicity\" id=\"ProfileStatEthnicity\">\n";               
										//if (empty($ProfileStatEthnicity)) {
		echo "							<option value=\"\" selected>". __("Any Ethnicity", rb_agency_TEXTDOMAIN) . "</option>\n";
										//}
								
										$query1 = "SELECT EthnicityTitle FROM ". table_agency_data_ethnicity ." ORDER BY EthnicityTitle";
										$results1 = mysql_query($query1);
										$count1 = mysql_num_rows($results1);
										while ($data1 = mysql_fetch_array($results1)) {
		echo "							<option value=\"". $data1['EthnicityTitle'] ."\""; if ($_SESSION['ProfileStatEthnicity'] == $data1['EthnicityTitle']) { echo " selected"; } echo ">". $data1['EthnicityTitle'] ."</option>\n";
										}
										mysql_free_result($results1);
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Skin Color", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileStatSkinColor\" id=\"ProfileStatSkinColor\">\n";               
										//if (empty($ProfileStatSkinColor)) {
		echo "							<option value=\"\" selected>". __("Any Skin Color", rb_agency_TEXTDOMAIN) . "</option>\n";
										//}
		
										$query = "SELECT * FROM ". table_agency_data_colorskin ." ORDER BY ColorSkinTitle";
										$results = mysql_query($query);
										while ($data = mysql_fetch_array($results)) {
		echo "							<option value=\"". $data['ColorSkinTitle'] ."\""; if ($_SESSION['ProfileStatSkinColor'] == $data['ColorSkinTitle']) { echo " selected"; } echo ">". $data['ColorSkinTitle'] ."</option>\n";
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
	
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Eye Color", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileStatEyeColor\" id=\"ProfileStatEyeColor\">\n";               
										//if (empty($ProfileStatEyeColor)) {
		echo "							<option value=\"\" selected>". __("Any Eye Color", rb_agency_TEXTDOMAIN) . "</option>\n";
										//}
	
										$query = "SELECT * FROM ". table_agency_data_coloreye ." ORDER BY ColorEyeTitle";
										$results = mysql_query($query);
										while ($data = mysql_fetch_array($results)) {
		echo "							<option value=\"". $data['ColorEyeTitle'] ."\""; if ($_SESSION['ProfileStatEyeColor'] == $data['ColorEyeTitle']) { echo "selected=\"selected\""; } echo ">". $data['ColorEyeTitle'] ."</option>\n";
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
	
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Hair Color", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileStatHairColor\" id=\"ProfileStatHairColor\">\n";               
										//if (empty($ProfileStatHairColor)) {
		echo "							<option value=\"\" selected>". __("Any Hair Color", rb_agency_TEXTDOMAIN) . "</option>\n";
										//}
	
										$query = "SELECT * FROM ". table_agency_data_colorhair ." ORDER BY ColorHairTitle";
										$results = mysql_query($query);
										while ($data = mysql_fetch_array($results)) {
		echo "							<option value=\"". $data['ColorHairTitle'] ."\""; if ($_SESSION['ProfileStatHairColor'] == $data['ColorHairTitle']) { echo "selected=\"selected\""; } echo ">". $data['ColorHairTitle'] ."</option>\n";
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
	
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Chest", rb_agency_TEXTDOMAIN) . "/". __("Bust", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatBust_min\" name=\"ProfileStatBust_min\" value=\"". $_SESSION['ProfileStatBust_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatBust_max\" name=\"ProfileStatBust_max\" value=\"". $_SESSION['ProfileStatBust_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
	
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Waist", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWaist_min\" name=\"ProfileStatWaist_min\" value=\"". $_SESSION['ProfileStatWaist_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatWaist_max\" name=\"ProfileStatWaist_max\" value=\"". $_SESSION['ProfileStatWaist_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Hips", rb_agency_TEXTDOMAIN) . "/". __("Inseam", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHip_min\" name=\"ProfileStatHip_min\" value=\"". $_SESSION['ProfileStatHip_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatHip_max\" name=\"ProfileStatHip_max\" value=\"". $_SESSION['ProfileStatHip_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Shoe", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td>\n";               
		echo "				        	". __("Minimum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatShoe_min\" name=\"ProfileStatShoe_min\" value=\"". $_SESSION['ProfileStatShoe_min'] ."\" /><br />\n";
		echo "				        	". __("Maximum", rb_agency_TEXTDOMAIN) . ":\n";
		echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileStatShoe_max\" name=\"ProfileStatShoe_max\" value=\"". $_SESSION['ProfileStatShoe_max'] ."\" />\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Location", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileLocationCity\" id=\"ProfileLocationCity\">\n";               
		echo "							<option value=\"\">". __("Any Location", rb_agency_TEXTDOMAIN) . "</option>";
										$profileLocations = mysql_query("SELECT DISTINCT ProfileLocationCity, ProfileLocationState FROM ". table_agency_profile ."");
										while ($dataLocation = mysql_fetch_array($profileLocations)) {
										  if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity']) && $_SESSION['ProfileLocationCity'] == $dataLocation["ProfileLocationCity"]) {
											echo "<option value=\"". $dataLocation["ProfileLocationCity"] ."\" selected>". rb_agency_strtoproper($dataLocation["ProfileLocationCity"]) .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>";
										  } else {
											echo "<option value=\"". $dataLocation["ProfileLocationCity"] ."\">". rb_agency_strtoproper($dataLocation["ProfileLocationCity"]) .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>";
										  }
										}
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";


								$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions FROM ". table_agency_customfields ." ORDER BY ProfileCustomTitle";
								$results1 = mysql_query($query1);
								$count1 = mysql_num_rows($results1);
								while ($data1 = mysql_fetch_array($results1)) {
						
		echo "				    <tr valign=\"top\">\n";
		echo "				        <th scope=\"row\">". $data1['ProfileCustomTitle'] .":</th>\n";
		echo "				        <td>\n";               
						
									$ProfileCustomType = $data1['ProfileCustomType'];
									if ($ProfileCustomType == 1) {
										$ProfileCustomOptions_Array = explode( "|", $data1['ProfileCustomOptions']);
										foreach ($ProfileCustomOptions_Array as &$value) {
										//echo "	<input type=\"checkbox\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $value ."\" ". checked($ProfileCustomValue, $value) ." /> ". $value ."\n";
										} 
									} elseif ($ProfileCustomType == 2) {
										$ProfileCustomOptions_Array = explode( "|", $data1['ProfileCustomOptions']);
										foreach ($ProfileCustomOptions_Array as &$value) {
										//echo "	<input type=\"radio\"  name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $value ."\" ". checked($ProfileCustomValue, $value) ." /> ". $value ."\n";
										} 
									} elseif ($ProfileCustomType == 3) {
										$ProfileCustomOptions_Array = explode( "|", $data1['ProfileCustomOptions']);
										echo "<select name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\">\n";
										echo "	<option value=\"\"> -- </option>\n";
										foreach ($ProfileCustomOptions_Array as &$value) {
										echo "	<option value=\"". $value ."\" ". selected($ProfileCustomValue, $value) ."> ". $value ." </option>\n";
										} 
										echo "</select>\n";
									} else {
										echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
									}
									
		echo "				        </td>\n";
		echo "				    </tr>\n";
			}
   
		echo "				    <tr>\n";
		echo "				        <th scope=\"row\">". __("Status", rb_agency_TEXTDOMAIN) . ":</th>\n";
		echo "				        <td><select name=\"ProfileIsActive\" id=\"ProfileIsActive\">\n";               
		echo "							<option value=\"\">". __("Show only Active", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"1\"". selected($_SESSION['ProfileIsActive'], 1) .">". __("Show only Active", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"0\"". selected($_SESSION['ProfileIsActive'], 0) .">". __("Show only Inactive", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "							<option value=\"2\"". selected($_SESSION['ProfileIsActive'], 2) .">". __("Show only Declassified", rb_agency_TEXTDOMAIN) . "</option>\n";
		echo "				        	</select>\n";
		echo "				        </td>\n";
		echo "				    </tr>\n";

		echo "				  </thead>\n";
		echo "				</table>\n";
		echo "				<div>\n";
		echo "				<input type=\"submit\" value=\"". __("Search Profiles", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "				<input type=\"reset\" value=\"". __("Reset Form", rb_agency_TEXTDOMAIN) . "\" class=\"button-secondary\" />\n";
		echo "				<a href=\"?page=". $_GET['page'] ."&action=formEmpty\" class=\"button-secondary\">". __("Empty Form", rb_agency_TEXTDOMAIN) . "</a>\n";
		echo "				</div>\n";
		echo "        	<form>\n";

		echo "        	<div>\n";
     
		echo "    </div><!-- .container -->\n";
		echo "  </div>\n";
		echo "</div>\n";
?>