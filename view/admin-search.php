<div class="wrap">
<?php

	// Include Admin Menu
	include ("admin-include-menu.php"); 

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");

	// Setup DB connection
	global $wpdb;

	// Define Options
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype =  $rb_agency_options_arr['rb_agency_option_unittype'];
		$rb_agency_option_persearch = (int)$rb_agency_options_arr['rb_agency_option_persearch'];
		$rb_agency_option_agencyemail = (int)$rb_agency_options_arr['rb_agency_option_agencyemail'];
		if ($rb_agency_option_persearch < 0) { $rb_agency_option_persearch = 100; }

// *************************************************************************************************** //
/* 
 * Casting Cart
 */
	// Protect and defend the cart string!
		$cartString = "";
	// Add to Cart
		if ($_GET["action"] == "cartAdd" ) {
			
			if(count($_GET["ProfileID"])>0){
				foreach($_GET["ProfileID"] as $value) {
					$cartString .= $value .",";
				}
			}
			// Clean It!
			$cartString = RBAgency_Common::Clean_String($cartString);
			
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
			$cartString = RBAgency_Common::Clean_String($cartString);
			// Put it back in the array, and wash your hands
			$_SESSION['cartArray'] = array($cartString);

		} elseif (($_GET["action"] == "searchSave") && isset($_SESSION['cartArray'])) {
			extract($_SESSION);
			foreach($_SESSION as $key=>$value) {
				// TODO: Why is this empty?
			}
			$_SESSION['cartArray'] = $cartArray;

		}



// *************************************************************************************************** //
// Get Search Results

	if ($_REQUEST["action"] == "search") {


	/**
	 * Generate SQL Statement
	 */

		// Process Form Submission
		$search_array = RBAgency_Profile::search_process();
echo "<pre>";
print_r($search_array);
echo "</pre>";
		// Exclude IDs in Cart
		$exclude = $_SESSION['cartArray'];
			$exclude = implode(",", array_unique($exclude));
			$exclude = RBAgency_Common::Clean_String($exclude);

		// Return Search
		$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array, $exclude);

		// Build SQL Query
		$query = "
			SELECT 
			profile.*,
			profile.ProfileGallery,
			profile.ProfileContactDisplay, 
			profile.ProfileDateBirth, 
			profile.ProfileLocationState, 
			profile.ProfileID as pID,
				(
				SELECT media.ProfileMediaURL 
				FROM ". table_agency_profile_media ." media
				WHERE profile.ProfileID = media.ProfileID 
					AND media.ProfileMediaType = \"Image\"
					AND media.ProfileMediaPrimary = 1
				)
				AS ProfileMediaURL FROM ". table_agency_profile ." profile
			WHERE ". $search_sql_query ."
			"; // GROUP BY profile.ProfileID  ORDER BY $sort $dir  $limit"

echo $query;
	/**
	 * Return Results
	 */

		$results2 = mysql_query($query);
		$count = mysql_num_rows($results2);

		echo "  <div class=\"boxblock-holder\">\n";
		echo "<h2 class=\"title\">Search Results: " . $count . "</h2>\n";

		if (($count > ($rb_agency_option_persearch -1)) && (!isset($_GET['limit']) && empty($_GET['limit']))) {
		echo "<div id=\"message\" class=\"error\"><p>Search exceeds ". $rb_agency_option_persearch ." records first ". $rb_agency_option_persearch ." displayed below.  <a href=". admin_url("admin.php?page=". $_GET['page']) ."&". $sessionString ."&limit=none><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p></div>\n";
		}
		echo "       <form method=\"get\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        <input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
		echo "        <input type=\"hidden\" name=\"action\" value=\"cartAdd\" />\n";
		echo "        <input type=\"hidden\" name=\"forceCart\" value=\"". $_SESSION['cartArray'] ."\" />\n";
		echo "        <table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "        <thead>\n";
		echo "            <tr class=\"thead\">\n";
		echo "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"admin.php?page=rb_agency_profiles&sort=ProfileID&dir=". $sortDirection ."\">". __("ID", rb_agency_TEXTDOMAIN) ."</a></th>\n";
		echo "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-ProfileImage\" id=\"ProfileImage\" scope=\"col\" style=\"width:150px;\">". __("Headshot", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "            </tr>\n";
		echo "        </thead>\n";
		echo "        <tfoot>\n";
		echo "            <tr class=\"thead\">\n";
		echo "                <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "                <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\">". __("ID", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-ProfileContact\" id=\"ProfileContact\" scope=\"col\">". __("Contact Information", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-ProfileStats\" id=\"ProfileStats\" scope=\"col\">". __("Private Details", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">". __("Public Details", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "                <th class=\"column-image\" id=\"col-image\" scope=\"col\">". __("Headshot", rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "            </tr>\n";
		echo "        </tfoot>\n";
		echo "        <tbody>\n";

		while ($data = mysql_fetch_array($results2)) {
			$ProfileID = $data['pID'];
			$isInactive = '';
			$isInactiveDisable = '';
			if ($data['ProfileIsActive'] == 0 || empty($data['ProfileIsActive'])){
				$isInactive = 'style="background: #FFEBE8"';
				$isInactiveDisable = "disabled=\"disabled\"";
			}
			echo "        <tr ". $isInactive.">\n";
			echo "            <th class=\"check-column\" scope=\"row\" >\n";
			echo "                <input type=\"checkbox\" ". $isInactiveDisable." value=\"". $ProfileID ."\" class=\"administrator\" id=\"ProfileID". $ProfileID ."\" name=\"ProfileID[]\" />\n";
			echo "            </th>\n";
			echo "            <td class=\"ProfileID column-ProfileID\">". $ProfileID ."</td>\n";
			echo "            <td class=\"ProfileContact column-ProfileContact\">\n";
			echo "                <div class=\"detail\">\n";


			echo "                </div>\n";
			echo "                <div class=\"title\">\n";
			echo "                	<h2>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h2>\n";
			echo "                </div>\n";
			echo "                <div class=\"row-actions\">\n";
			echo "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
			echo "                    <span class=\"review\"><a href=\"". rb_agency_PROFILEDIR . $rb_agency_UPLOADDIR . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
			echo "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;deleteRecord&amp;ProfileID=". $ProfileID ."' onclick=\"if ( confirm('You are about to delete the model \'". $ProfileContactNameFirst ." ". $ProfileContactNameLast ."\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;\">Delete</a></span>\n";
			echo "                </div>\n";
			if(!empty($isInactiveDisable)){
			       echo "<div><strong>Profile Status:</strong> <span style=\"color:red;\">Inactive</span></div>\n";
			}
			echo "            </td>\n";

			// private into 
			echo "            <td class=\"ProfileStats column-ProfileStats\">\n";

			if (!empty($data['ProfileContactEmail'])) {
			        echo "<div><strong>Email:</strong> ". $data['ProfileContactEmail'] ."</div>\n";
			}

			if (!empty($data['ProfileLocationStreet'])) {
			        echo "<div><strong>Address:</strong> ". $data['ProfileLocationStreet'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationCity']) || !empty($data['ProfileLocationState'])) {
			        echo "<div><strong>Location:</strong> ". $data['ProfileLocationCity'] .", ". $data['ProfileLocationState'] ." ". $data['ProfileLocationZip'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationCountry'])) {
			        echo "<div><strong>". __("Country", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileLocationCountry'] ."</div>\n";
			}
			if (!empty($data['ProfileDateBirth'])) {
			        echo "<div><strong>". __("Age", rb_agency_TEXTDOMAIN) .":</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."</div>\n";
			}
			if (!empty($data['ProfileDateBirth'])) {
			        echo "<div><strong>". __("Birthdate", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileDateBirth'] ."</div>\n";
			}
			if (!empty($data['ProfileContactWebsite'])) {
			        echo "<div><strong>". __("Website", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactWebsite'] ."</div>\n";
			}
			if (!empty($data['ProfileContactPhoneHome'])) {
			        echo "<div><strong>". __("Phone Home", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneHome'] ."</div>\n";
			}
			if (!empty($data['ProfileContactPhoneCell'])) {
			        echo "<div><strong>". __("Phone Cell", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneCell'] ."</div>\n";
			}
			if (!empty($data['ProfileContactPhoneWork'])) {
			        echo "<div><strong>". __("Phone Work", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneWork'] ."</div>\n";
			}

			$resultsCustomPrivate =  $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, c.ProfileCustomView, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView > 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC"));
			foreach ($resultsCustomPrivate as $resultCustomPrivate) {
			echo "				<div><strong>". $resultCustomPrivate->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustomPrivate->ProfileCustomValue ."</div>\n";
			}

			echo "            </td>\n";

			// public info
			echo "            <td class=\"ProfileDetails column-ProfileDetails\">\n";
			echo "<ul style='margin: 0px;'>" ;
			if (!empty($data['ProfileGender'])) {
				if(rb_agency_getGenderTitle($data['ProfileGender'])){
					echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> ".rb_agency_getGenderTitle($data['ProfileGender'])."</li>\n";
				}else{
					echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> --</li>\n";	
				}
			}
			rb_agency_getProfileCustomFields($ProfileID ,$data['ProfileGender']);
			
			echo "</ul>" ;

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
	echo "      	<input type=\"submit\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','rb_agency_search') ."\" class=\"button-primary\" />\n";
	echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
	echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
	echo "     </p>\n";
	echo "  </form>\n";


	} else {
	// Ok.. now what ???
	// else for if ($_GET["action"] == "search")     

	}

	if (($_GET["action"] == "search") || ($_GET["action"] == "cartAdd") || (isset($_SESSION['cartArray']))) {

		echo "<div class=\"boxblock-container\" style=\"float: left; width: 49%; min-width: 500px;\">\n";
		echo " <div class=\"boxblock\">\n";
		echo "  <h2>". __("Casting Cart", rb_agency_TEXTDOMAIN) ."</h2>\n";
		echo "    <div class=\"inner\">\n";

		if (isset($_SESSION['cartArray']) && !empty($_SESSION['cartArray'])) {

			$cartArray = $_SESSION['cartArray'];
				$cartString = implode(",", array_unique($cartArray));
				$cartString = RBAgency_Common::Clean_String($cartString);

			// Show Cart  
			$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (". $cartString .") ORDER BY profile.ProfileContactNameFirst ASC";
			$results = mysql_query($query) or  die( "<a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("No profile selected. Try again", rb_agency_TEXTDOMAIN) ."</a>"); //die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
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
		echo "<a name=\"compose\">&nbsp;</a>"; 
		echo "     <div class=\"boxblock\">\n";
		echo "        <h3>". __("Cart Actions", rb_agency_TEXTDOMAIN) ."</h3>\n";
		echo "        <div class=\"inner\">\n";
		echo "      	<a href=\"?page=rb_agency_searchsaved&action=searchSave\" title=\"". __("Save Search & Email", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Save Search & Email", rb_agency_TEXTDOMAIN) ."</a>\n";
		echo "      	<a href=\"?page=rb_agency_search&action=massEmail#compose\" title=\"". __("Mass Email", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Mass Email", rb_agency_TEXTDOMAIN) ."</a>\n";
		echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
		echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
		echo "        </div>\n";
		echo "     </div>\n";
		} // Is Cart Empty 


	/**
	 * Send Email
	 */

        $isSent = false;
        if(isset($_POST["SendEmail"])){

            $rb_agency_options_arr = get_option('rb_agency_options');
            $rb_agency_value_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
            $rb_agency_value_agencyemail = $rb_agency_options_arr['rb_agency_option_agencyemail'];


            add_filter('wp_mail_content_type','rb_agency_set_content_type');
            function rb_agency_set_content_type($content_type){
                        return 'text/html';
            }

            $MassEmailSubject = $_POST["MassEmailSubject"];
            $MassEmailMessage = $_POST["MassEmailMessage"];
            $MassEmailRecipient = $_POST["MassEmailRecipient"];

            // Mail it
            $headers[]  = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'From: '.$rb_agency_value_agencyname.' <'. $rb_agency_option_agencyemail .'>';

            if(!empty($expMail)){
                    $expMail = explode(",",$MassEmailRecipient);
                    foreach($expMail as $bccEmail){
                            $headers[] = 'Bcc: '.$bccEmail;
                    }
            }

            $isSent = wp_mail($MassEmailRecipient, $MassEmailSubject, $MassEmailMessage, $headers);

        }

	/**
	 * Send Email
	 */
		if($_GET["action"]== "massEmail"){

			// Filter Models Already in Cart
			if (isset($_SESSION['cartArray'])) {
				$cartArray = $_SESSION['cartArray'];
				$cartString = implode(",", $cartArray);
				$cartQuery =  " AND profile.ProfileID IN (". $cartString .")";
			}

			// Search Results	
			$query = "SELECT profile.*  FROM ". table_agency_profile ." profile WHERE profile.ProfileID > 0 ".$cartQuery;
			$results2 = mysql_query($query);
			$count = mysql_num_rows($results2);
			$pos = 0;
			$recipient = "";
			while ($data = mysql_fetch_array($results2)) {
				$pos ++;
				$ProfileID = $data['ProfileID'];
				$recipient .=$data['ProfileContactEmail'];
				if($count != $pos){
					$recipient .=", ";
				}

			}
			// Email
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_value_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
			$rb_agency_value_agencyemail = $rb_agency_options_arr['rb_agency_option_agencyemail'];
			echo "<form method=\"post\">";
			echo "     <div class=\"boxblock\">\n";
			echo "        <h3>". __("Compose Email", rb_agency_TEXTDOMAIN) ."</h3>\n";
			echo "        <div class=\"inner\">\n";
			if($isSent){
			echo "<div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";	
			}
			echo "          <strong>Recipient:</strong><br/><textarea name=\"MassEmailRecipient\" style=\"width:100%;\">".$recipient."</textarea><br/>";
			echo "        <strong>Subject:</strong> <br/><input type=\"text\" name=\"MassEmailSubject\" style=\"width:100%\"/>";
			echo "<br/>";
			echo "      <strong>Message:</strong><br/>     <textarea name=\"MassEmailMessage\"  style=\"width:100%;height:300px;\">this message was sent to you by ".$rb_agency_value_agencyname." ".network_site_url( '/' )."</textarea>";
			echo "				<input type=\"submit\" value=\"". __("Send Email", rb_agency_TEXTDOMAIN) . "\" name=\"SendEmail\"class=\"button-primary\" />\n";
			echo "        </div>\n";
			echo "     </div>\n";
			echo "</form>";
		}

	echo "    </div><!-- .container -->\n";
	}

	echo "    <div class=\"boxblock-container\" style=\"float: left; width: 49%;\">\n";
	echo "     <div class=\"boxblock\">\n";
	echo "      <h3>". __("Advance Search", rb_agency_TEXTDOMAIN) ."</h3>\n";
	echo "        <div class=\"inner\">\n";

		return RBAgency_Profile::search_form("", "", 1);

	echo "        </div><!-- .inner -->\n";
	echo "     </div><!-- .inner -->\n";
	echo "    </div><!-- .container -->\n";
	echo "  </div>\n";
	echo "</div>\n";
?>
</div>