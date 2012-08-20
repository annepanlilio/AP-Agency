<?php 
$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_agencylogo = $rb_agency_options_arr['rb_agency_option_agencylogo'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php bloginfo('name'); ?> | Print</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="noindex, nofollow" />
	<link rel="stylesheet" type="text/css" media="screen, print" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" />
	<script language="Javascript1.2">
      <!--
      function printpage() {
      window.print();
      }
      //-->
    </script>
</head>
<body onload="printpage()" style="background: #fff;">
<div id="print_wrapper" style="width: 887px;">
<div id="print_logo" style="float: left; width: 50%;">
<?php if(!empty($rb_agency_option_agencylogo)){ ?>
  <img src="<?php echo $rb_agency_option_agencylogo; ?>" title="<?php echo $rb_agency_option_agencyname; ?>" />
<?php }else{ ?>
<?php echo $rb_agency_option_agencyname; ?>
<?php } ?>
</div>
  <div id="print_actions" style="float: left; text-align: right; width: 50%;"><a href="#" onclick="printpage();">Print</a> | <a href="javascript:window.opener='x';window.close();">Close</a></div>
  <div style="clear: both;"></div>
<?php

global $wpdb;
$hasQuery = false;

// Set Casting Cart Session
if ($_GET['action'] == "quickPrint") {
	$hasQuery = true;
	extract($_SESSION);
	foreach($_SESSION as $key=>$value) {
		  $$key = $value;
	}
	
	//// Filter
	$filter = " WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1";
	// Name
	if ((isset($ProfileContactNameFirst) && !empty($ProfileContactNameFirst)) || isset($ProfileContactNameLast) && !empty($ProfileContactNameLast)){
		if (isset($ProfileContactNameFirst) && !empty($ProfileContactNameFirst)){
		$filter .= " AND profile.ProfileContactNameFirst='". $ProfileContactNameFirst ."'";
		}
		if (isset($ProfileContactNameLast) && !empty($ProfileContactNameLast)){
		$filter .= " AND profile.ProfileContactNameLast='". $ProfileContactNameLast ."'";
		}
	}
	// Location
	if (isset($ProfileLocationCity) && !empty($ProfileLocationCity)){
		$filter .= " AND profile.ProfileLocationCity='". $ProfileLocationCity ."'";
	}
	// Type
	if (isset($ProfileType) && !empty($ProfileType)){
	  if ($ProfileType == "Model") {
		$selectedIsModel = " selected";
		$filter .= " AND profile.ProfileIsModel='1'";
	  } elseif ($ProfileType == "Talent") {
		$selectedIsTalent = " selected";
		$filter .= " AND profile.ProfileIsTalent='1'";
	  }
	}
	// Active
	if (isset($ProfileIsActive)){
	  if ($ProfileIsActive == "1") {
		$selectedActive = "active";
		$filter .= " AND profile.ProfileIsActive=1";
	  } elseif ($ProfileIsActive == "0") {
		$selectedActive = "inactive";
		$filter .= " AND profile.ProfileIsActive=0";
	  }
	} else {
		$selectedActive = "";
	}
	// Gender
	if (isset($ProfileGender) && !empty($ProfileGender)){
	  
		$filter .= " AND profile.ProfileGender='".$ProfileGender."'";
	  
	} else {
		$ProfileGender = "";
	}
	// Race
	if (isset($ProfileStatEthnicity) && !empty($ProfileStatEthnicity)){
		$filter .= " AND profile.ProfileStatEthnicity='". $ProfileStatEthnicity ."'";
	}
	// Skin
	if (isset($ProfileStatSkinColor) && !empty($ProfileStatSkinColor)){
		$filter .= " AND profile.ProfileStatSkinColor='". $ProfileStatSkinColor ."'";
	}
	// Eye
	if (isset($ProfileStatEyeColor) && !empty($ProfileStatEyeColor)){
		$filter .= " AND profile.ProfileStatEyeColor='". $ProfileStatEyeColor ."'";
	}
	// Hair
	if (isset($ProfileStatHairColor) && !empty($ProfileStatHairColor)){
		$filter .= " AND profile.ProfileStatHairColor='". $ProfileStatHairColor ."'";
	}
	// Height
	if (isset($ProfileStatHeight_min) && !empty($ProfileStatHeight_min)){
		$filter .= " AND profile.ProfileStatHeight >= '". $ProfileStatHeight_min ."'";
	}
	if (isset($ProfileStatHeight_max) && !empty($ProfileStatHeight_max)){
		$filter .= " AND profile.ProfileStatHeight <= '". $ProfileStatHeight_max ."'";
	}
	// Weight
	if (isset($ProfileStatWeight_min) && !empty($ProfileStatWeight_min)){
		$filter .= " AND profile.ProfileStatWeight >= '". $ProfileStatWeight_min ."'";
	}
	if (isset($ProfileStatWeight_max) && !empty($ProfileStatWeight_max)){
		$filter .= " AND profile.ProfileStatWeight <= '". $ProfileStatWeight_max ."'";
	}
	// Bust/Chest
	if (isset($ProfileStatBust_min) && !empty($ProfileStatBust_min)){
		$filter .= " AND profile.ProfileStatBust >= '". $ProfileStatBust_min ."'";
	}
	if (isset($ProfileStatBust_max) && !empty($ProfileStatBust_max)){
		$filter .= " AND profile.ProfileStatBust <= '". $ProfileStatBust_max ."'";
	}
	// Waist
	if (isset($ProfileStatWaist_min) && !empty($ProfileStatWaist_min)){
		$filter .= " AND profile.ProfileStatWaist >= '". $ProfileStatWaist_min ."'";
	}
	if (isset($ProfileStatWaist_max) && !empty($ProfileStatWaist_max)){
		$filter .= " AND profile.ProfileStatWaist <= '". $ProfileStatWaist_max ."'";
	}
	// Hip
	if (isset($ProfileStatHip_min) && !empty($ProfileStatHip_min)){
		$filter .= " AND profile.ProfileStatHip >= '". $ProfileStatHip_min ."'";
	}
	if (isset($ProfileStatHip_max) && !empty($ProfileStatHip_max)){
		$filter .= " AND profile.ProfileStatHip <= '". $ProfileStatHip_max ."'";
	}
	// Age
	$timezone_offset = -10; // Hawaii Time
	$dateInMonth = gmdate('d', time() + $timezone_offset *60 *60);
	$format = 'Y-m-d';
	$date = gmdate($format, time() + $timezone_offset *60 *60);
	if (isset($ProfileDateBirth_min) && !empty($ProfileDateBirth_min)){
		$selectedYearMin = date($format, strtotime('-'. $ProfileDateBirth_min .' year'. $date));
		$filter .= " AND profile.ProfileDateBirth <= '$selectedYearMin'";
	}
	if (isset($ProfileDateBirth_max) && !empty($ProfileDateBirth_max)){
		$selectedYearMax = date($format, strtotime('-'. $ProfileDateBirth_max-1 .' year'. $date));
		$filter .= " AND profile.ProfileDateBirth >= '$selectedYearMax'";
	}
	
	// Filter Models Already in Cart
	if (isset($_SESSION['cartArray'])) {
		$cartArray = $_SESSION['cartArray'];
		$cartString = implode(",", $cartArray);
		$filter .=  " AND profile.ProfileID NOT IN (". $cartString .")";
	}
	
	// Show Cart
	$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media $filter ORDER BY ProfileContactNameFirst";
	$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
	$count = mysql_num_rows($results);
	if ($count < 1) {
		echo "There are currently no profiles in the casting cart.";
		$hasQuery = false;
	}

// Call Casting Cart Session
} elseif (($_GET['action'] == "castingCart") && (isset($_SESSION['cartArray']))) {
	$cartArray = $_SESSION['cartArray'];
	$cartString = implode(",", $cartArray);
	$hasQuery = true;

	// Show Cart
	$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (". $cartString .") ORDER BY ProfileContactNameFirst ASC";
	$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
	$count = mysql_num_rows($results);
	
	if ($count < 1) {
		echo "There are currently no profiles in the casting cart.";
		$hasQuery = false;
	}
} else {
	echo "<p>Nothing to display.  <a href=\"javascript:window.opener='x';window.close();\">Close</a></div></p>";
	$hasQuery = false;
}

if ($hasQuery) {
	echo "<div style=\"clear: both; border-top: 2px solid #c0c0c0; width: 887px; \" class=\"profile\">";
	while ($data = mysql_fetch_array($results)) {
	  if (1 == 1) {
		echo "<div style=\"float: left; width: 420px; min-height: 220px; overflow: hidden; margin: 5px; padding: 5px; border: 1px solid #e1e1e1; \">";
		echo " <div style=\"float: left; width: 150px; height: 180px; margin-right: 5px; overflow: hidden; \"><img style=\"width: 150px; \" src=\"". rb_agency_UPLOADDIR ."". $data["ProfileGallery"] ."/". $data["ProfileMediaURL"] ."\" /></div>\n";
		echo " <div style=\"float: left; width: 230px; padding: 15px; \">";

		if ($_GET['cD'] == "1") {
			echo "	<h2 style=\"margin-top: 15px; \">". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</h2>"; 

			if (!empty($data['ProfileContactEmail'])) {
				echo "<div><strong>Email:</strong> ". $data['ProfileContactEmail'] ."</div>\n";
			}
			if (!empty($data['ProfileContactWebsite'])) {
				echo "<div><strong>Website:</strong> ". $data['ProfileContactWebsite'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationStreet'])) {
				echo "<div><strong>Street:</strong> ". $data['ProfileLocationStreet'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationCity'])) {
				echo "<div><strong>Address:</strong> ". $data['ProfileLocationCity'] .", ". $data['ProfileLocationState'] ." ". $data['ProfileLocationZip'] ."</div>\n";
			}
			if (!empty($data['ProfileLanguage'])) {
				echo "<div><strong>Language:</strong> ". $data['ProfileLanguage'] ."</div>\n";
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
			if (!empty($data['ProfileUnion'])) {
				echo "<div><strong>Union:</strong> ". $data['ProfileUnion'] ."</div>\n";
			}
				
				$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ".$data['ProfileID'] ." AND  c.ProfileCustomView = 0 GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC");
		foreach  ($resultsCustom as $resultCustom) {
			if(!empty($resultCustom->ProfileCustomValue )){
				if(rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
					echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
				}
			}
		}
			echo " </div>";
			echo " <div style=\"clear: both; text-align: center; padding: 5px; \">\n";
		} else {
			echo "	<h2 style=\"text-align: center; margin-top: 30px; \">". stripslashes($data['ProfileContactDisplay']) . "</h2>"; 
		}
		
		
			
		
		echo " </div>";
		echo "</div>";
	  } // elseif (layout style is another value......) {
	  // 
	  // }
	}
	mysql_free_result($results);
	echo "<div style=\"clear: both;\"></div>";
	echo "</div>";
}
?>
<p style="text-align: center;">Property of <?php echo $rb_agency_option_agencyname; ?>.  All rights reserved.</p>
</div>
</body>
</html>