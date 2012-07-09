<?php 

// *************************************************************************************************** //
// Setup Functions 


	// Gobble Up The Variables, Set em' Sessions
		foreach ($_GET as $key => $value) {
		  if (substr($key, 0, 9) != "ProfileID") {
			$_SESSION[$key] = $value;  //$$key = $value;
		  }
		}

	// Protect and defend the cart string!
		$cartString = "";
		function cleanString($string) {
			// Remove trailing dingleberry
			if (substr($string, -1) == ",") {  $string = substr($string, 0, strlen($string)-1); }
			if (substr($string, 0, 1) == ",") { $string = substr($string, 1, strlen($string)-1); }

			// Just Incase
			$string = str_replace(",,", ",", $string);
			return $string;
		}
	

// *************************************************************************************************** //
// Get Actions 

	// Add to Cart
		if ($_GET["action"] == "cartAdd") { 
			extract($_GET);
			foreach($_GET as $key=>$value) {
			  if (substr($key, 0, 9) == "ProfileID") {
				$cartString .= $value .",";
			  }
			}
			// Clean It!
			$cartString = cleanString($cartString);
			
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
			$cartString = cleanString($cartString);
			// Put it back in the array, and wash your hands
			$_SESSION['cartArray'] = array($cartString);
		
		} elseif (($_GET["action"] == "searchSave") && isset($_SESSION['cartArray'])) {
		
			extract($_SESSION);
			foreach($_SESSION as $key=>$value) {
			}
			$_SESSION['cartArray'] = $cartArray;
		
		}







get_header();

if (is_user_logged_in()) { 
	global $current_user;
	get_currentuserinfo();
	$curauth = get_user_by('id', $current_user->ID);

	echo "<div id=\"dashboard\">\n";
	echo "<h1>Welcome ". $current_user->user_firstname ."</h1>\n";

  // Return them where we found them 
  if (isset($_SESSION['ProfileLastViewed']) && ($_SESSION['ProfileLastViewed'])) {
	
	// What do we call them?
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];
	  
	$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='". $_SESSION['ProfileLastViewed'] ."'";
	$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$ProfileGallery			=stripslashes($data['ProfileGallery']);
		$ProfileContactNameFirst=stripslashes($data['ProfileContactNameFirst']);
		$ProfileContactNameLast	=stripslashes($data['ProfileContactNameLast']);
		$ProfileContactDisplay	=stripslashes($data['ProfileContactDisplay']);
		
		// How does it display?

		if ($rb_agency_option_profilenaming == 0) {
			$ProfileContactDisplay = stripslashes($data['ProfileContactNameFirst']) . "". stripslashes($data['ProfileContactNameLast']);
		} elseif ($rb_agency_option_profilenaming == 1) {
			$ProfileContactDisplay = stripslashes($data['ProfileContactNameFirst']) . "". substr(stripslashes($data['ProfileContactNameLast']), 1);
		} elseif ($rb_agency_option_profilenaming == 2) {
			$ProfileContactDisplay = stripslashes($data['ProfileContactDisplay']);
		}

		echo "<div class=\"event\">\n";
		echo "<h3>You have successfully logged in!</h3>\n";
		echo "You may now access the profile data.  You may now return to <strong><a href=\"". rb_agency_PROFILEDIR ."". $ProfileGallery ."\">". $ProfileContactDisplay ."'s</strong></a> profile.\n";
		echo "</div>\n";
		$_SESSION['ProfileLastViewed'] = "";
	}
  } // End Last Model Visit
  /*
	echo "  <div id=\"activity\">\n";
	echo "    <h2>Recent Activity</h2>\n";
	echo "    <p>There is no recent activity.</p>\n";
	echo "    <h2>Saved Searches</h2>\n";
	echo "    <p>There currently no saved searches.</p>\n";
	echo "  </div>\n";
  */
if (isset($curauth->user_login)) {
	echo "  <div id=\"userinfo\" style=\"float: right; width: 250px; margin-right: 30px;\">\n";
	echo "		<h2>Profile</h2>\n";
	echo "		Username: <strong>" . $curauth->user_login . "</strong><br />\n";
	echo "		Company: <strong>" . $curauth->user_company . "</strong><br />\n";
	echo "		First Name: <strong>" . $curauth->user_firstname . "</strong><br />\n";
	echo "		Last Name: <strong>" . $curauth->user_lastname . "</strong><br />\n";
	echo "		User Email: <strong>" . $curauth->user_email . "</strong><br />\n";
	echo "		Work Phone: <strong>" . $curauth->phone_work . "</strong><br />\n";
	echo "		Cell Phone: <strong>" . $curauth->phone_cell . "</strong><br />\n";
	//echo "User level: " . $current_user->user_level . "<br />\n";
	//echo "User display name: " . $current_user->display_name . "<br />\n";
	echo "		<h4><a href=\"". get_bloginfo("url") ."/wp-admin/profile.php\">Edit Information</a></h4>\n";
	echo "		<h4><a href=\"" . wp_logout_url(get_permalink()) . "\">Logout</a></h4>\n";
	echo "  </div>\n";
	
	echo "  <div id=\"search\" style=\"float: left; width: 500px;\">\n";
	echo "    <h2>Search Database</h2>\n";
	include ("include-profile-search.php"); 	
	echo "  </div>\n";
}
	/* GET ROLE
	echo rb_agency_get_userrole();
	*/
	echo "</div>\n";

} else {
	include ("include-login.php"); 	
}
    
    
//get_sidebar(); 
get_footer(); 
?>