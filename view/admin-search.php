<div class="wrap">
<?php

	// Setup DB connection
	global $wpdb;

	// Include Admin Menu
	include ("admin-include-menu.php"); 

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");

	// Casting Class
	include(rb_agency_BASEREL ."app/casting.class.php");

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

if(isset($_REQUEST["action"])) {
		// Process Cart
		$cart = RBAgency_Casting::Cart_Process();

}
if(isset($_POST["SendEmail"])){
		// Process Form
		$isSent = RBAgency_Casting::Cart_Send_Process();
		if($isSent){
			wp_redirect(admin_url("admin.php?page=". $_GET['page']));
			die;
			}
}


// *************************************************************************************************** //
// Get Search Results

	if ($_REQUEST["action"] == "search") {

	/**
	 * Generate SQL Statement
	 */

		// Process Form Submission
		$search_array = RBAgency_Profile::search_process();

		// Exclude IDs in Cart
		$exclude = $_SESSION['cartArray'];
			$exclude = implode(",", array_unique($exclude));
			$exclude = RBAgency_Common::Clean_String($exclude);

		// Return Search
		$search_sql_query_where = RBAgency_Profile::search_generate_sqlwhere($search_array, $exclude);
		$search_sql_query_order = RBAgency_Profile::search_generate_sqlorder($search_array);

		// Build SQL Query
		$query = "
			SELECT 
				profile.*,
				profile.ProfileGallery,
				profile.ProfileContactDisplay, 
				profile.ProfileDateBirth, 
				profile.ProfileLocationState, 
				profile.ProfileID as pID,
				media.ProfileMediaURL as ProfileMediaURL
			FROM ". table_agency_profile ." profile
			LEFT JOIN ". table_agency_profile_media ." media ON profile.ProfileID = media.ProfileID 
			WHERE ". $search_sql_query_where ." 
				AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1
				". $search_sql_query_order ."
			";
			//echo "<pre>". $query ."</pre>";

	/**
	 * Return Results
	 */

		$results2 = mysql_query($query);
		$count = mysql_num_rows($results2);

		echo "<div class=\"boxblock-holder\">\n";
		echo "  <h2 class=\"title\">Search Results: " . $count . "</h2>\n";

			if (($count > ($rb_agency_option_persearch -1)) && (!isset($_GET['limit']) && empty($_GET['limit']))) {
			echo "       <div id=\"message\" class=\"error\"><p>Search exceeds ". $rb_agency_option_persearch ." records first ". $rb_agency_option_persearch ." displayed below.  <a href=". admin_url("admin.php?page=". $_GET['page']) ."&". $sessionString ."&limit=none><strong>Click here</strong></a> to expand to all records (NOTE: This may take some time)</p></div>\n";
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
			echo "                  <h2>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h2>\n";
			echo "                </div>\n";
			echo "                <div class=\"row-actions\">\n";
			echo "                    <span class=\"edit\"><a href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"Edit this post\">Edit</a> | </span>\n";
			echo "                    <span class=\"review\"><a href=\"". rb_agency_PROFILEDIR . $rb_agency_UPLOADDIR . $data['ProfileGallery'] ."/\" target=\"_blank\">View</a> | </span>\n";
			echo "                    <span class=\"delete\"><a class=\"submitdelete\" title=\"Remove this Profile\" href=\"". str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=rb_agency_profiles&amp;deleteRecord&amp;ProfileID=". $ProfileID ."' onclick=\"if ( confirm('You are about to delete the model \'". $ProfileContactNameFirst ." ". $ProfileContactNameLast ."\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;\">Delete</a></span>\n";
			echo "                </div>\n";
			if(!empty($isInactiveDisable)){
			echo "                <div><strong>Profile Status:</strong> <span style=\"color:red;\">Inactive</span></div>\n";
			}
			echo "            </td>\n";

			// private into 
			echo "            <td class=\"ProfileStats column-ProfileStats\">\n";

			if (!empty($data['ProfileContactEmail'])) {
			echo "                <div><strong>Email:</strong> ". $data['ProfileContactEmail'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationStreet'])) {
			echo "                <div><strong>Address:</strong> ". $data['ProfileLocationStreet'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationCity']) || !empty($data['ProfileLocationState'])) {
			echo "                <div><strong>Location:</strong> ". $data['ProfileLocationCity'] .", ". $data['ProfileLocationState'] ." ". $data['ProfileLocationZip'] ."</div>\n";
			}
			if (!empty($data['ProfileLocationCountry'])) {
			echo "                <div><strong>". __("Country", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileLocationCountry'] ."</div>\n";
			}
			if (!empty($data['ProfileDateBirth'])) {
			echo "                <div><strong>". __("Age", rb_agency_TEXTDOMAIN) .":</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."</div>\n";
			}
			if (!empty($data['ProfileDateBirth'])) {
			echo "                <div><strong>". __("Birthdate", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileDateBirth'] ."</div>\n";
			}
			if (!empty($data['ProfileContactWebsite'])) {
			echo "                <div><strong>". __("Website", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactWebsite'] ."</div>\n";
			}
			if (!empty($data['ProfileContactPhoneHome'])) {
			echo "                <div><strong>". __("Phone Home", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneHome'] ."</div>\n";
			}
			if (!empty($data['ProfileContactPhoneCell'])) {
			echo "                <div><strong>". __("Phone Cell", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneCell'] ."</div>\n";
			}
			if (!empty($data['ProfileContactPhoneWork'])) {
			echo "                <div><strong>". __("Phone Work", rb_agency_TEXTDOMAIN) .":</strong> ". $data['ProfileContactPhoneWork'] ."</div>\n";
			}

			$resultsCustomPrivate =  $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, c.ProfileCustomView, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView > 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC"));
			foreach ($resultsCustomPrivate as $resultCustomPrivate) {
			echo "                <div><strong>". $resultCustomPrivate->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustomPrivate->ProfileCustomValue ."</div>\n";
			}

			echo "            </td>\n";

			// public info
			echo "            <td class=\"ProfileDetails column-ProfileDetails\">\n";
			echo "                <ul style='margin: 0px;'>" ;
			if (!empty($data['ProfileGender'])) {
				if(RBAgency_Common::Profile_Meta_GenderTitle($data['ProfileGender'])){
					echo "                <li><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> ".RBAgency_Common::Profile_Meta_GenderTitle($data['ProfileGender'])."</li>\n";
				}else{
					echo "                <li><strong>". __("Gender", rb_agency_TEXTDOMAIN) .":</strong> --</li>\n";
				}
			}
			rb_agency_getProfileCustomFields($ProfileID ,$data['ProfileGender']);
			
			echo "                </ul>" ;

			echo "            </td>\n";
			echo "            <td class=\"ProfileImage column-ProfileImage\">\n";

			if (isset($data['ProfileMediaURL']) && !empty($data['ProfileMediaURL'])) {
			echo "                <div class=\"image\"><img style=\"width: 150px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
			} else {
			echo "                <div class=\"image no-image\">NO IMAGE</div>\n";
			}

			echo "            </td>\n";
			echo "        </tr>\n";

		} // End While

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
			echo "          <input type=\"submit\" name=\"CastingCart\" value=\"". __('Add to Casting Cart','rb_agency_search') ."\" class=\"button-primary\" />\n";
			echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=1','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
			echo "          <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=0','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
			echo "     </p>\n";
			echo "  </form>\n";


	} else {
	// Ok.. now what ???
	// else for if ($_GET["action"] == "search")     

	}
	if (($_GET["action"] == "search") || ($_GET["action"] == "cartAdd") || (isset($_SESSION['cartArray']))) {

		echo "<div class=\"boxblock-container\" style=\"float: left; padding-top:24px; width: 49%; min-width: 500px;\">\n";
		echo " <div class=\"boxblock\">\n";
		echo "   <h2>". __("Casting Cart", rb_agency_TEXTDOMAIN) ."</h2>\n";
		echo "   <div class=\"inner\">\n";

		// Show Cart
		$castingcart = RBAgency_Casting::Cart_Show();
		echo $castingcart;

		echo "   </div>\n";
		echo " </div>\n";
		echo "</div>\n";


		// Send Email Form
		$sendemail = RBAgency_Casting::Cart_Send_Form();
		echo $sendemail;


	echo "    </div><!-- .boxblock -->\n";
	}

	echo "    <div class=\"boxblock-container\" style=\"float: left; width: 49%;\">\n";
	echo "     <div class=\"boxblock\">\n";
	echo "      <h3>". __("Advance Search", rb_agency_TEXTDOMAIN) ."</h3>\n";
	echo "      <div class=\"inner\">\n";

		return RBAgency_Profile::search_form("", "", 1);

	echo "      </div><!-- .inner -->\n";
	echo "     </div><!-- .boxblock -->\n";
	echo "    </div><!-- .boxblock-container -->\n";
?>
</div>