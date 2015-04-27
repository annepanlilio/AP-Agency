<div class="wrap">
	<?php 

	global $wpdb;

	// Include Admin Menu
	include (RBAGENCY_PLUGIN_DIR ."view/partial/admin-menu.php"); 

	$arrayProfilesRenamedFolders = array();
	$arraySuggestedFolderNames = array();
	$arrayAllFolderNames = array();
	$arrayDuplicateFolders = array();
	$arrayDuplicateFound = array();


if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){ $ConfigID=0;} else { $ConfigID=$_REQUEST['ConfigID']; }

if ($ConfigID <> 0) { ?>
	<a class="button-primary" href="?page=rb_agency_reports&ConfigID=0" title="Overview">Back to Overview</a>
	<?php
}

if ($ConfigID == 0) {
	
	if (function_exists('rb_agency_interact_menu')) {
		// RB Agency Interact Settings
		echo "<div class=\"boxlinkgroup\">\n";
		echo "  <h2>". __("Interactive Reporting", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
		echo "  <p>". __("Run reports on membership and other usage.", RBAGENCY_TEXTDOMAIN) . "</p>\n";

		echo "    <div class=\"boxlink\">\n";
		echo "      <h3>". __("Recent Payments", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
		echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=11\" title=\"". __("Recent Payments", RBAGENCY_TEXTDOMAIN) . "\">". __("Recent Payments", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
		echo "      <p>". __("Payments and membership renewals", RBAGENCY_TEXTDOMAIN) . "</p>\n";
		echo "    </div>\n";
	echo "</div>\n";
	echo "<hr />\n";
	}

	//
	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Initial Setup", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("If you are doing the initial instal of RB Agency you this section will help you get your data inplace", RBAGENCY_TEXTDOMAIN) . "</p>\n";
	echo "</div>\n";
	echo "<hr />\n";

	//
	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Data Integrity", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("Once your data is in place use the tools below to check your records", RBAGENCY_TEXTDOMAIN) . "</p>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Export Data", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=12\" title=\"". __("Export Data", RBAGENCY_TEXTDOMAIN) . "\">". __("Export Data", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Export databases", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Check for Abnormalities", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=5\" title=\"". __("Check for Abnormalities", RBAGENCY_TEXTDOMAIN) . "\">". __("Check for Abnormalities", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Search profile records for fields which seem invalid", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Rename Profile Folder Names", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=8\" title=\"". __("Rename Folders", RBAGENCY_TEXTDOMAIN) . "\">". __("Rename Folders", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("If you created model profiles while under \"First Last\" and wish to switch to Display names, IDs, or First L, you will have to rename the existing folders so that they do not have the models name in it.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Resize Photos", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=13\" title=\"". __("Resize Photos", RBAGENCY_TEXTDOMAIN) . "\">". __("Resize Photos", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Ensure files are not larger than approved size", RBAGENCY_TEXTDOMAIN) . ". (<a href=\"?page=rb_agency_settings&ConfigID=1\" title=\"". __("Configure Sizes", RBAGENCY_TEXTDOMAIN) . "\">". __("Configure Sizes", RBAGENCY_TEXTDOMAIN) . "</a>)</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Orphaned Profile Images", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=7\" title=\"". __("Remove Orphan Images From Database", RBAGENCY_TEXTDOMAIN) . "\">". __("Remove Orphan Images From Database", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("If for any reason you are getting blank images appear it may be because images were added in the database but have been removed via FTP.  Use this tool to remove all images in the databse which do not physically exist.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("FTP Blank Folder Check", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=1\" title=\"". __("Scan for Orphan Folders", RBAGENCY_TEXTDOMAIN) . "\">". __("Scan for Orphan Folders", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Check for any empty folders which do no have models assigned using this tool", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Profile Data Migration", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=83\" title=\"". __("Check for Profile Data Migration", RBAGENCY_TEXTDOMAIN) . "\">". __("Check for Profile Data Migration", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Search profile records for fields migration", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "</div>\n";
	echo "<hr />\n";

	//
	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Profile Management", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("The following reports will help you manage your profile information", RBAGENCY_TEXTDOMAIN) . "</p>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Inactive Users", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=6\" title=\"". __("Check Inactive Users", RBAGENCY_TEXTDOMAIN) . "\">". __("Check Inactive Users", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Find profiles who are currently set as inactive.  Use this tool to set multiple users to active", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Profile Search", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=rb_agency_search\" title=\"". __("Profile Search", RBAGENCY_TEXTDOMAIN) . "\">". __("Profile Search", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("You may search for profiles by using this tool", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Dummy Profiles with Sample Media", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=14\" title=\"". __("Generate Dummy Profiles with Media Content", RBAGENCY_TEXTDOMAIN) . "\">". __("Generate Dummy Profiles with Media Content", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("You may add dummy profiles by using this tool", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
	echo "    </div>\n";

		echo "<hr />\n";

	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Importing Records", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("The following tools will help import records.  DO NOT USE THESE TOOLS IF YOU ALREADY HAVE DATA LOADED", RBAGENCY_TEXTDOMAIN) . "</p>\n";
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 1", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=81\" title=\"". __("Export Now", RBAGENCY_TEXTDOMAIN) . "\">". __("Export Now", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Download this template and load your profile data into this file to import into the database.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
	
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 2", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=80\" title=\"". __("Import Data", RBAGENCY_TEXTDOMAIN) . "\">". __("Import Data", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Upload the model profiles into the database.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
/*
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 1", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <span class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=51\" title=\"". __("Download Excel Template", RBAGENCY_TEXTDOMAIN) . "\">". __("Download Excel Template", RBAGENCY_TEXTDOMAIN) . "</span><br />\n";
	echo "      <p>". __("Download this template and load your profile data into this file to import into the database.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 2", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <span class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=52\" title=\"". __("Upload to Database", RBAGENCY_TEXTDOMAIN) . "\">". __("Upload to Database", RBAGENCY_TEXTDOMAIN) . "</span><br />\n";
	echo "      <p>". __("Upload the model profiles into the database.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
*/
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 3", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=53\" title=\"". __("Generate folder names for profiles", RBAGENCY_TEXTDOMAIN) . "\">". __("Generate folder names for profiles", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Check that all profiles have folder names generated.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 4", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=2\" title=\"". __("Create folders for all profiles", RBAGENCY_TEXTDOMAIN) . "\">". __("Create folders for all profiles", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Check that all profiles have folders created on the server.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 5", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=3&scan=run\" title=\"". __("Scan Folders for Images/Media", RBAGENCY_TEXTDOMAIN) . "\">". __("Scan Folders for Images/Media", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("First upload images directly to folders via FTP then use this tool to sync the images & media to the database.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 6", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=4\" title=\"". __("Set Primary Profile Image", RBAGENCY_TEXTDOMAIN) . "\">". __("Set Primary Profile Image", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Identify which image is the primary image for each profile.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

		$active = get_option('active_plugins');
	foreach($active as $act){
		if(preg_match('/rb-agency-interact\.php/',$act)){
			echo "    <div class=\"boxlink\">\n";
					echo "      <h3>". __("Step 7", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
			echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=99\" title=\"". __("Generate Logins / Passwords", RBAGENCY_TEXTDOMAIN) . "\">". __("Generate Logins / Passwords", RBAGENCY_TEXTDOMAIN) . "</a><br />\n";
			echo "      <p>". __("You may generate login and password for profiles which has been uploaded via importer, using this tool", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
			echo "    </div>\n";
		}
	}
		
	echo "</div>\n";

}
elseif ($ConfigID == 1) {
//////////////////////////////////////////////////////////////////////////////////// ?>
	<h3>Check Galleries</h3>
	<h3>Folders without Models</h3>
	<p>This will determine if a model's profile exists.  Green profiles indicate that the model has a folder linked correctly where red profiles indicate that a model does not yet have a folder created for them yet.  NOTE: That spelling errors could make it seem that a folder does not exist, please check the report above to find folders with no profile assigned which may need to be renamed.</p>
	<?php
	global $wpdb;
	$throw_error = false;
	
	$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
	$results1 =$wpdb->get_results($query1, ARRAY_A);
	$count1 =  $wpdb->num_rows;
	foreach ($results1 as $data1) {
		$dirURL = RBAGENCY_UPLOADPATH . $data1['ProfileGallery'];
		echo $dirURL;
		echo "<div>\n";
		if (is_dir($dirURL)) {
			//echo "  <span style='width: 240px; color: green;'>" . RBAGENCY_UPLOADDIR  . $dirURL . "/</span>\n";
		} else {
			$throw_error = true;
			echo "  <span style='width: 240px; color: red;'>". $dirURL ."/</span>\n";
			echo "  <strong>Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is missing folder.</strong>\n";
		}
		echo "</div>\n";
	}
	if ($count1 < 1) {
		echo "There are currently no profile records.";
	} elseif ($throw_error == false) {
		echo "Congrats!  All folders are as they should be!";
	}


}
elseif ($ConfigID == 2) {
//////////////////////////////////////////////////////////////////////////////////// ?>
  <?php
	$arrayProfilesMissingFolders = array();
	$throw_error = false;

	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'generate') {
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 =$wpdb->get_results($query1, ARRAY_A);
		$count1 =  $wpdb->num_rows;
		foreach ($results1 as $data1) {
			$dirURL = RBAGENCY_UPLOADPATH. $data1['ProfileGallery'];
			if (isset($data1['ProfileGallery']) && !empty($data1['ProfileGallery']) && is_dir($dirURL)) {
			} else {
				// Create Folders
				mkdir($dirURL, 0755); //700
				chmod($dirURL, 0777);
				echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>". $dirURL ."/</strong> has been created for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
			}
		}
	} else {
	?>
	<h3>Check Galleries</h3>
	<h3>Check Profiles against Folders</h3>
	<p>This will determine if a profiles profile exists.  Green profiles indicate that the model has a folder linked correctly where red profiles indicate that a model does not yet have a folder created for them yet.  NOTE: That spelling errors could make it seem that a folder does not exist, please check the report above to find folders with no profile assigned which may need to be renamed.</p>
	<?php

		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 =$wpdb->get_results($query1, ARRAY_A);
		$count1 =  $wpdb->num_rows;
		foreach ($results1 as $data1) {
			$dirURL = RBAGENCY_UPLOADPATH . $data1['ProfileGallery'];
			echo "<div>\n";
			if (isset($data1['ProfileGallery']) && !empty($data1['ProfileGallery']) && is_dir($dirURL)) {
				echo "  <span style='width: 240px; color: green;'>". $dirURL ."/</span>\n";
			} else {
				// Add Profiles to Array to Create later
				$arrayProfilesMissingFolders[] = $dirURL; 
				$throw_error = true;

				echo "  <span style='width: 240px; color: red;'>". $dirURL ."/</span>\n";
				echo "  <strong>Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is missing folder.</strong>\n";
			}
			echo "</div>\n";
		}
		
		// Errors?
		if ($throw_error == true) { ?>
			<a name="generate"></a>
			<h3>Generate Folders for Profiles</h3>
			<p>Click the button below to create folders for all profiles identified as not having a folder created:</p>
			<p><a class="button-primary" href="?page=rb_agency_reports&ConfigID=2&action=generate" title="Generate Missing Folders for Profiles">Generate Missing Folders for Profiles</a>  Clicking this button will generate folders for the following profiles:<p>
			<?php
			foreach ($arrayProfilesMissingFolders as $profileURL) {
				echo $profileURL.", ";
			}
		} else {
			echo "Good to go! No changes needed!";
		}

	} // To Generate or Not to Generate


} // End 2
elseif ($ConfigID == 53) {
//////////////////////////////////////////////////////////////////////////////////// 
	$arrayProfilesMissingFolders = array();
	$throw_error = false;

	if($_REQUEST['action'] == 'generate') {
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 =$wpdb->get_results($wpdb->prepare($query1), ARRAY_A);
		$count1 =  $wpdb->num_rows;
		$arr_folder_names = array();

		foreach($result1 as $data1){
			rb_agency_deldir($data1['ProfileGallery']);
		}

		foreach ($results1 as $data1) {
			$ProfileID = $data1['ProfileID'];

			$ProfileGallery = $data1['ProfileGallery'];

			// Create the right folder name for the profile
			$ProfileGalleryCurrent = generate_foldername($data1['ProfileID'], $data1['ProfileContactNameFirst'], $data1['ProfileContactNameLast'], $data1['ProfileContactDisplay']);
			

			if(!empty($ProfileGallery)){
				$rb_folder = rb_check_duplicate_folder($ProfileGallery,$ProfileGalleryCurrent,$arr_folder_names);
				array_push($arr_folder_names,$rb_folder);
				if ($rb_folder != $ProfileGallery) {
					$ProfileGalleryCurrent = $rb_folder;
						// just rename the existing folder,
					rename(RBAGENCY_UPLOADPATH. $ProfileGallery."/", RBAGENCY_UPLOADPATH. $ProfileGalleryCurrent."/");
				}
			} else {
				$rb_folder = rb_check_duplicate_folder($ProfileGallery,$ProfileGalleryCurrent);
				array_push($arr_folder_names,$rb_folder);
				$ProfileGalleryCurrent = $rb_folder;
					
				// actual folder creation
				$dirURL = RBAGENCY_UPLOADPATH. $ProfileGalleryCurrent;
				mkdir($dirURL, 0755); //700
				chmod($dirURL, 0777);
			}

			// Then Update our DB
			$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryCurrent ."' WHERE ProfileID = \"". $ProfileID ."\"";
			$renamed = $wpdb->query($rename);

			echo "  <div id=\"message\" class=\"updated highlight\">Folder name <strong>/" . $ProfileGalleryCurrent . "/</strong> has been set for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
		}
	} else {
		
		/*
		 * Place sql here to get
		 * generated total count for folders
		 */
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 =$wpdb->get_results($query1, ARRAY_A);
		$count1 =  $wpdb->num_rows;
	
		echo "<h3>". __("Generate folder names for profiles", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
		echo "<p>". __("Check that all profiles have folder names generated.", RBAGENCY_TEXTDOMAIN) . "</p>\n";
		echo "<p>". __("Total Number of Folders Created: <strong>".$count1."</strong>", RBAGENCY_TEXTDOMAIN) . "</p>\n";
            $arr_folder_names = array();
			foreach ($results1 as $data1) {
				$ProfileGallery = $data1['ProfileGallery'];

				// Create the right folder name for the profile
				$ProfileGalleryCurrent = generate_foldername($data1['ProfileID'], $data1['ProfileContactNameFirst'], $data1['ProfileContactNameLast'], $data1['ProfileContactDisplay']);
				echo "<div>\n";
				//$rb_folder = rb_check_duplicate_folder($ProfileGallery,$ProfileGalleryCurrent,$arr_folder_names);
				array_push($arr_folder_names,$rb_folder);
			
				if (isset($ProfileGallery) && !empty($ProfileGallery)) {
					echo "  <span style='width: 240px; color: green;'>". $ProfileGallery ."</span>\n";
					/**if ($rb_folder == $ProfileGallery) {
						echo "  <span style='width: 240px; color: green;'>". $ProfileGallery ."</span>\n";
					} else {
						// Add Profiles to Array to Create later
						$throw_error = true;
						echo "  <span style='width: 240px; color: red;'>". $ProfileGallery ." should be <strong>". $rb_folder ."</strong></span>\n";
					}**/
				} else {
					// Add Profiles to Array to Create later
					$throw_error = true;

					echo "  <span style='width: 240px; color: red;'>". $ProfileGalleryCurrent ." is missing</span>\n";
					echo "  <strong>Folder name for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is blank.</strong>\n";
				}
				echo "</div>\n";
			}

			// Errors?
			if ($throw_error == true) { ?>
				<a name="generate"></a>
				<h3>Generate Folders for Profiles</h3>
				<p>Click the button below to create folders for all profiles identified as not having a folder created:</p>
				<p><a class="button-primary" href="?page=rb_agency_reports&ConfigID=<?php echo $ConfigID; ?>&action=generate" title="Generate Missing Folders for Profiles">Generate Missing Folders for Profiles</a>  Clicking this button will generate folders for the following profiles:<p>
				<?php
				foreach ($arrayProfilesMissingFolders as $profileURL) {
					echo $profileURL.", ";
				}
			} else {
				echo "Good to go! No changes needed!";
			}

	} // To Generate or Not to Generate


} // End 2
elseif ($ConfigID == 3) {
//////////////////////////////////////////////////////////////////////////////////// ?>
	<h3>Manage Galleries</h3>
	<h3>Correct Filenames and Add to Database</h3>
	<p>This script corrects all the filenames of the images uploaded removing special characters and spaces and then adds the images to the database.</p>
	<?php
	global $wpdb;

	$query3 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
	$results3 =$wpdb->get_results($query3, ARRAY_A);
	$count3 =  $wpdb->num_rows;

	$query3a = "SELECT * FROM ". table_agency_profile_media ."";
	$results3a = $wpdb->get_results($query3a, ARRAY_A);
	$arr_media = array();
	foreach($results3a as $media){
		if (!empty($media["ProfileMediaURL"])) {
			array_push($arr_media, $media["ProfileMediaURL"]);
		}
	}

	foreach ($results3 as $data3) {
		$dirURL = RBAGENCY_UPLOADPATH . $data3['ProfileGallery'];
		if (is_dir($dirURL)) {  // Does folder exist?
			echo "<div style=\"background-color: lightYellow; \">\n<h3>". $data3['ProfileContactNameFirst'] ." ". $data3['ProfileContactNameLast'] ."</h3>\n";
			echo "<strong>Directory: ".$dirURL."</strong>";
			echo "<br/>";

			$query4a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID=".$data3["ProfileID"];
			$results4a = $wpdb->get_results($query4a, ARRAY_A);
			//if(!in_array($_GET['action'],array("add"))){
				foreach ($results4a as $key) {
					if(!in_array($key["ProfileMediaType"],array("Video Slate","Video Monologue","Demo Reel","SoundCloud"),true) || empty($key["ProfileMediaURL"]) ){
						if(strpos($key["ProfileMediaTitle"],"migrated") !== true && (!file_exists($dirURL."/".$key["ProfileMediaURL"]) /*|| empty($key["ProfileMediaURL"])*/)){
							if($_GET['action'] == "remove"){
								$wpdb->query("DELETE FROM ".table_agency_profile_media." WHERE ProfileID = ".$key["ProfileID"]." AND  ProfileMediaURL='".$key["ProfileMediaURL"]."'");
								echo "<div style=\"border-color: #E6DB55;\">File: <span style=\"color:red;\">". $key["ProfileMediaURL"] ."</span> is missing in the directory and <span style=\"color:green;\">has been removed from the database</span>.</div>\n";
							}else{
								echo "<div style=\"border-color: #E6DB55;\">File: <span style=\"color:red;\">". $key["ProfileMediaURL"] ."</span> is mssing in the directory.</div>\n";
							}
						}
					}
				}
			//}
			$has_rename = false;
			$_SESSION['renamed_pending'] = false;
			$fileArray = array();
			$open_div = "";
			if ($handle = opendir($dirURL) ) {  //  Open seasame 
				//echo $file;
				while (false !== ($file = readdir($handle))) {
					$arrayFiles[] = $file;
					if (strtolower($file) == "thumbs.db"  || strtolower($file) == "thumbsdb.jpg" || strtolower($file) == "thumbsdbjpg.jpg" || strtolower($file) == "thumbsdbjpgjpg.jpg") {
						if (!unlink($dirURL ."/". $file)) {
						  echo ("Error deleting $file");
						} else {
						  echo ("Deleted $file");
						}
					} elseif ($file != "." && $file != "..") {
						$new_file = str_replace("'","",RBAgency_Common::format_stripchars($file,false));
											
						$result = $wpdb->get_row($wpdb->prepare("SELECT ProfileMediaURL as url FROM ".table_agency_profile_media." WHERE ProfileMediaURL = %s", $file));
						// no need to rename if the file exist with the new filename
						
						//If newly imported files
						if(file_exists($dirURL ."/".$file) && strpos($file , $data3["ProfileID"]) === false){
							
							//Rename the files
							$new_file =  $data3["ProfileID"]."-".$new_file;
							rename($dirURL ."/". $file, $dirURL ."/".$new_file);
							$results = $wpdb->query($wpdb->prepare("UPDATE " . table_agency_profile_media . " SET ProfileMediaURL = %s  WHERE ProfileID = %d AND ProfileMediaURL = %s",$new_file, $data3['ProfileID'],$file));
							
							//Setters
							$has_rename = true;
							$fileArray[$new_file] = $new_file;

							//Message
							$open_div = "<div id='1' style=\"border-color: #E6DB55;\">";
							$fileMessage = "File: ". $file ."";
							$newFile = " has been renamed <strong>". $new_file ."</strong>";
						}
						
						//Else if imported files are renamed
						if(file_exists($dirURL ."/".$file) && strpos($file , $data3["ProfileID"]) !== false){

							if(isset($fileArray[$new_file])){
								$open_div = "<div id='2' style=\"border-color: #E6DB55;display:none;\">";
								unset($fileArray[$k]);
							}else{
								$open_div = "<div id='3' style=\"border-color: #E6DB55;\">";
								$fileMessage = "File: ". $file ."";
								$newFile = "";
							}					
							
						}
						

						/**if($has_rename){
							if($_SESSION['renamed_pending'] === true){
								$open_div = "<div id='1' style=\"border-color: #E6DB55;display:none;\">";
							}else{
								$open_div = "<div id='1' style=\"border-color: #E6DB55;\">";
							}							
							$fileMessage = "File: ". $file ."";
							$newFile = "";				
							
						}else{
							
							$open_div = "<div id='1' style=\"border-color: #E6DB55;\">";
							$fileMessage = "File: ". $file ."";
							$newFile = "";
						}	

						//PENDING
						/**if(!file_exists($dirURL ."/".$file) && strpos($file , $data3["ProfileID"]) === false && $has_rename){
							$open_div = "<div id='3' style=\"border-color: #E6DB55;display:none;\">"; 
						}
						//Display
						if(!file_exists($dirURL ."/".$file) && strpos($file , $data3["ProfileID"]) !== false && $has_rename){
							$open_div = "<div id='3' style=\"border-color: #E6DB55;display:none;\">"; 
						}

						//Scanned, renamed and saved into the database
						if(file_exists($dirURL ."/".$file) && strpos($file , $data3["ProfileID"]) !== false && $has_rename == false){
							$open_div = "<div id='5' style=\"border-color: #E6DB55;\">";
							$fileMessage = "File: ". $file ."";	
						}**/

						
						//echo $renamedFile."=".$file."=".$old_file."<br>";

						
						/**if(empty($hasInRenamed) && $hasInFile === false && $hasOld === false){
							echo "<div id='imported-files' style=\"border-color: #E6DB55;\">";
						} 

						if($hasInRenamed === false && empty($hasInFile) && empty($hasOld)){
							echo "<div id='imported-files' style=\"border-color: #E6DB55;\">";
						}**/
						

						$file_ext = strtolower(rb_agency_filenameextension($file));

						if (($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "bmp")) {
							if (empty($result->url) && !in_array($new_file,$arr_media,true)) {
								if($_GET['action'] == "add") {
									unset($_SESSION['renamed_pending']);
									if(!empty($result->url) && strpos($result->url , $data3["ProfileID"]) === false){
										$results = $wpdb->query($wpdb->prepare("UPDATE " . table_agency_profile_media . " SET ProfileMediaTitle = %s, ProfileMediaURL = %s  WHERE ProfileID = %d AND ProfileMediaURL = %s", $data3['ProfileContactNameFirst'] ."-". $new_file."-migrated",$new_file, $data3['ProfileID'],$file));
									}else{
										$results = $wpdb->query($wpdb->prepare("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES (%s,'Image',%s,%s)", $data3['ProfileID'],$data3['ProfileContactNameFirst'] ."-". $new_file,$new_file));
									}									
									$actionText = ($has_rename ? $newFile." and":"")." <span style=\"color: green;\">added to database</span> ";
								} else{
									$actionText = ($has_rename ? $newFile." and":"")." <strong>PENDING ADDITION TO DATABASE</strong>";
								}
								
							} else {									
								$actionText = ($has_rename ? $newFile." and":"")." exists in database";
							}
						} elseif (($file_ext == "amr" || $file_ext == "m4a" || $file_ext == "mp3" || $file_ext == "wav")) {
							if (empty($result->url) && !in_array($new_file,$arr_media,true)) {
								if($_GET['action'] == "add") {
									unset($_SESSION['renamed_pending']);
									if(!empty($result->url) && strpos($result->url , $data3["ProfileID"]) === false){
										$results = $wpdb->query($wpdb->prepare("UPDATE " . table_agency_profile_media . " SET ProfileMediaTitle = %s, ProfileMediaURL = %s  WHERE ProfileID = %d AND ProfileMediaURL = %s", $data3['ProfileContactNameFirst'] ."-". $new_file."-migrated",$new_file, $data3['ProfileID'],$file));
									}else{
										$results = $wpdb->query($wpdb->prepare("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES (%s,'VoiceDemo',%s,%s)", $data3['ProfileID'],$data3['ProfileContactNameFirst'] ."-". $new_file,$new_file));
									}											
									$actionText = ($has_rename ? $newFile." and":"")." <span style=\"color: green;\">added to database</span> ";
								}else{
									$actionText = ($has_rename ? $newFile." and":"")." <strong>PENDING ADDITION TO DATABASE</strong>";
								}
								
							}else {									
								$actionText = ($has_rename ? $newFile." and":"")." exists in database";
							}
						} elseif (($file_ext == "pdf" || $file_ext == "doc" || $file_ext == "docx")) {
							if (empty($result->url) && !in_array($new_file,$arr_media,true)) {
								if($_GET['action'] == "add") {
									unset($_SESSION['renamed_pending']);
									if(!empty($result->url) && strpos($result->url , $data3["ProfileID"]) === false){
										$results = $wpdb->query($wpdb->prepare("UPDATE " . table_agency_profile_media . " SET ProfileMediaTitle = %s, ProfileMediaURL = %s  WHERE ProfileID = %d AND ProfileMediaURL = %s", $data3['ProfileContactNameFirst'] ."-". $new_file."-migrated",$new_file, $data3['ProfileID'],$file));
									}else{
										$results = $wpdb->query($wpdb->prepare("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES (%s,'Resume',%s,%s)", $data3['ProfileID'],$data3['ProfileContactNameFirst'] ."-". $new_file,$new_file));
									}									
									$actionText = ($has_rename ? $newFile." and":"")." <span style=\"color: green;\">added to database</span> ";
								} else{
									$actionText = ($has_rename ? $newFile." and":"")." <strong>PENDING ADDITION TO DATABASE</strong>";
								}
								
							} else {									
									$actionText = ($has_rename ? $newFile." and":"")." exists in database";
							}
						} elseif(empty($result->url)) {
							    $actionText = " is <span style=\"color: red;\">NOT an allowed file type</span> ";
								$actionText = "";
						}
						//$wpdb->show_errors();
						//$wpdb->print_error();
						
						echo $open_div;

						echo $fileMessage . $actionText;
												

						echo "</div>\n";
						$has_rename = false;		
					}
				}
				closedir($handle);

			}
			echo "</div>\n";
		}
	}
	if ($count3 < 1) {
		echo "There are currently no profile records.";
	}
	echo "<a href='?page=rb_agency_reports&ConfigID=3&action=add' class='button-primary'>Add All Pending Changes</a>";
	echo "<a href='?page=rb_agency_reports&ConfigID=3&action=remove' class='button-primary'>Remove All Missing Files</a>";



} // End 3
elseif ($ConfigID == 4) {
//////////////////////////////////////////////////////////////////////////////////// 

	global $wpdb;

	$stepSize = 100;
	$query4t = "SELECT ProfileID FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
	$results4t =$wpdb->get_results($query4t, ARRAY_A);
	$pageString = "";
	$count4total =  $wpdb->num_rows;

	if (isset($_GET['Step'])) { 
		$currentPage = $_GET['Step']; 
		$step = $currentPage * $stepSize;
	} else { 
		$currentPage = 1; 
		$step = 0;
	}
	
	$totalPages = ceil($count4total/$stepSize);
	  //echo "Total pages:" . $totalPages;
	   if($totalPages >= 1) {
		 for($i = 1; $i <= $totalPages; $i++) {
		   $pageString .= " <a href=\"?page=rb_agency_reports&ConfigID=4&Step={$i}".(!empty($queryVars)?$queryVars:"")."\">Page $i</a>";
		   $pageString .= $i != $totalPages ? " | " : "";
		 }
	   }
	echo $pageString;

	if(isset($_POST['action']) && $_POST['action'] == 'update')
	{
	
		extract($_POST);
		foreach($_POST as $key=>$value) {
			if ($key !== "action" && $key !== "Update") {
				
				$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary = 0 WHERE ProfileID = ". $key);
				$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary = 1 WHERE ProfileID = ". $key ." AND ProfileMediaID = ". $value);

			}
		}

		echo "  <div id=\"message\" class=\"updated highlight\">Primary Images Saved!</div>\n";
	} else {
	?>
		<h3>Manage Galleries</h3>
		<h3>Select Primary Profile Photo</h3>
		<p>Select the checkbox for the model desired.</p>
		<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<?php
			$query4 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC LIMIT %d,%d"; //LIMIT $step,100
			$results4 =$wpdb->get_results($wpdb->prepare($query4,$step,$stepSize), ARRAY_A);
			$count4 =$wpdb->num_rows;
			foreach ($results4 as $data4)  {
				$dirURL = RBAGENCY_UPLOADDIR . $data4['ProfileGallery'];
				$profileID = $data4['ProfileID'];

				$query4b = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = %d AND ProfileMediaType = 'Image' AND ProfileMediaPrimary = 1";
				$results4b = $wpdb->get_results($wpdb->prepare($query4b,$profileID), ARRAY_A);
				$count4b = $wpdb->num_rows;
				//echo $query4b ."<br />". $count4b ."<hr />";
				if ($count4b < 1) {

					echo "<div style=\"background-color: lightYellow; \">\n<h3><a href='?page=rb_agency_profiles&action=editRecord&ProfileID=$profileID' target='_blank'>". $data4['ProfileContactNameFirst'] ." ". $data4['ProfileContactNameLast'] ."</a></h3>\n";
			
					$query4a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = %d AND ProfileMediaType = 'Image' GROUP BY(ProfileMediaURL)";
					$results4a = $wpdb->get_results($wpdb->prepare($query4a,$profileID), ARRAY_A);
					$count4a = $wpdb->num_rows;
					if ($count4a < 1) {
						echo "This profile has no images loaded.";
					} else {
						foreach ($results4a as $data4a) {
							echo "<div style=\"width: 150px; float: left; height: 200px; overflow: hidden; margin: 10px; \"><input type=\"radio\" name=\"". $data4a['ProfileID'] ."\" value=\"". $data4a['ProfileMediaID'] ."\" />&nbsp;&nbsp;Select Primary<br /><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". $dirURL."/". $data4a['ProfileMediaURL'] ."&w=150&h=150\"  style=\"width: 150px;\" /></div>\n";
						}
						echo "<div style=\"clear: both;\"></div>\n";
					}
					echo "</div>\n";
				} else {
					// Primary Image Already Set
				}
			}
			if ($count4 < 1) {
				echo "There are currently no profile records.";
			}
			?>
			<input type="hidden" value="update" name="action" />
			<input type="submit" value="Submit" class="button-primary" name="Update" />
		</form>
	<?php
	}
} // End 4
elseif ($ConfigID == 5) {
//////////////////////////////////////////////////////////////////////////////////// ?>

	<h3>Check for Abnormalities</h3>
	<p>This will determine if a model's profile exists.  Green profiles indicate that the model has a folder linked correctly where red profiles indicate that a model does not yet have a folder created for them yet.  NOTE: That spelling errors could make it seem that a folder does not exist, please check the report above to find folders with no profile assigned which may need to be renamed.</p>
	<?php
	global $wpdb;
	
	$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
	$results1 = $wpdb->get_results($query1, ARRAY_A);
	$count1 = $wpdb->num_rows;

	foreach ($results1 as $data1) {
		$ProfileDateBirth = $data1['ProfileDateBirth'];
		$ProfileAge = rb_agency_get_age($ProfileDateBirth);
		if ($ProfileDateBirth == "0000-00-00" || !isset($ProfileDateBirth) || empty($ProfileDateBirth)) {
			echo "  <div id=\"message\" class=\"error\">Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> has no age!!</div>\n";
		} elseif ($ProfileAge > 90) {
			echo "  <div id=\"message\" class=\"updated highlight\">Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is really old! .... Like ". $ProfileAge ."</div>\n";
		} elseif ($ProfileAge < 2) {
			if ($ProfileAge < 0) {
			echo "  <div id=\"message\" class=\"updated\">Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> was born in the future... amazing!</div>\n";
			} else {
			echo "  <div id=\"message\" class=\"updated highlight\">Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a> is really young! .... Like ". $ProfileAge ."</div>\n";
			}
		}
	}
	if ($count1 < 1) {
		echo "There are currently no profile records.";
	}

	?>


<?php
}	 // End	
elseif ($ConfigID == 6) {
//////////////////////////////////////////////////////////////////////////////////// 
	
	global $wpdb;
	
	if(isset($_POST['action']) && $_POST['action'] == 'update') {
		extract($_POST);
		foreach($_POST as $key=>$value) {
			if ($key !== "action" && $key !== "Update") {
				$results = $wpdb->query("UPDATE " . table_agency_profile . " SET ProfileIsActive = 1 WHERE ProfileID = ". $key);
			}
		}
	}
	?>
	<h3>Set Profiles Active</h3>
	<p>Select the checkbox for the model desired to make active.</p>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<?php
	
	$query6 = "SELECT * FROM ". table_agency_profile ." WHERE ProfileIsActive = '0' ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
	$results6 = $wpdb->get_results($query6, ARRAY_A);
	$count6 = $wpdb->num_rows;

	foreach ($results6 as $data6) {
		echo "<div><input type=\"checkbox\" name=\"". $data6['ProfileID'] ."\" value=\"". $data6['ProfileID'] ."\" class=\"button-primary\" />". $data6['ProfileContactNameFirst'] ." ". $data6['ProfileContactNameLast'] ."</div>\n";
	}
	if ($count6 < 1) {
		echo "There are currently no inactive profile records.";
	}else{
	?>
	<input type="hidden" value="update" name="action" />
	<input type="submit" value="Submit" class="button-primary" name="Update" />
	</form>
	<?php
	}
} // End 6



elseif ($ConfigID == 7) {
//////////////////////////////////////////////////////////////////////////////////// 

	global $wpdb;
	?>
	<h3>Remove Orphans from Database</h3>
	<?php
	$query7 = "SELECT ProfileID, ProfileGallery FROM ". table_agency_profile ."";
	$results7 = $wpdb->get_results($query7, ARRAY_A);
	$count7 = $wpdb->num_rows;

	foreach ($results7 as $data7 ) {
		$ProfileID = $data7['ProfileID'];
		$dirURL = RBAGENCY_UPLOADPATH . $data7['ProfileGallery'];
		if (is_dir(".." . $dirURL)) {  // Does folder exist?
			echo "<div style=\"background-color: lightYellow; margin: 10px; \">\n";
			if ($handle = opendir(".." . $dirURL)) {  //  Open seasame 
			
				$query7a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = ". $ProfileID ." AND ProfileMediaType = 'Image'";
				$results7a = $wpdb->get_results($wpdb->prepare($query7a), ARRAY_A);
				$count7a = $wpdb->num_rows;
				foreach ($results7a as $data7a) {
					$fileCheck = RBAGENCY_UPLOADPATH . $data7['ProfileGallery'] ."/". $data7a['ProfileMediaURL'];
					if (file_exists($fileCheck)) {
					echo "<div style=\"color: green;\">". $fileCheck ."</div>\n";
					} else {
						if($_GET['action'] == "delete") {
							$ProfileMediaID = $data7a['ProfileMediaID'];
							// Remove Orphans
							$query7b = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileMediaID = \"". $ProfileMediaID ."\"";
							$results7b =  $wpdb->query($query7b);
							// echo $query7b;
							
						echo "<div style=\"color: red;\">". $fileCheck ." DELETED</div>\n";
						} else {
						  echo "<div style=\"color: red;\">". $fileCheck ."</div>\n";
						}
					}
				}
			}
			echo "</div>\n";
		}
	}
	echo "<a href='?page=rb_agency_reports&ConfigID=". $ConfigID ."&action=delete'>Remove Orphans</a>";


	?>


	<?php
} // End 6
elseif ($ConfigID == 8) {
//////////////////////////////////////////////////////////////////////////////////// ?>
  <?php

	
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];

		echo "<br/><br/>Current Naming Convention: ";
		if ($rb_agency_option_profilenaming == 0) {
			echo "<strong>First Last</strong>";
		} elseif ($rb_agency_option_profilenaming == 1) {
			echo "<strong>First L</strong>";
		} elseif ($rb_agency_option_profilenaming == 2) {
			echo "<strong>Display Name</strong>";
		} elseif ($rb_agency_option_profilenaming == 3) {
			echo "<strong>Autogenerated ID</strong>";
		}


			$arr_duplicates = array();
	


	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'generate') {

		// LETS DO IT!
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 = $wpdb->get_results($wpdb->prepare($query1), ARRAY_A);
		$count1 = $wpdb->num_rows;
		$arrayReservedFoldername = array();
		$pos = 0;
		

		foreach ($results1 as $data1) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
			

		/*		if ($rb_agency_option_profilenaming == 0) {
					$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($rb_agency_option_profilenaming == 2) {
					$ProfileGalleryFixed = $ProfileContactDisplay;
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileGalleryFixed = "ID ";
				}
*/
				$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
							if ($rb_agency_option_profilenaming == 0) {
								$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
							} elseif ($rb_agency_option_profilenaming == 1) {
								$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
							} elseif ($rb_agency_option_profilenaming == 2) {
								$ProfileGalleryFixed = $ProfileContactNameFirst;
							} elseif ($rb_agency_option_profilenaming == 3) {
								$ProfileGalleryFixed = "ID ". $ProfileID;
							} elseif ($rb_agency_option_profilenaming == 4) {
								$ProfileGalleryFixed = $ProfileContactNameFirst;
							} elseif ($rb_agency_option_profilenaming == 5) {
								$ProfileGalleryFixed = $ProfileContactNameLast;
							}
			

				$ProfileGalleryFixed = RBAgency_Common::format_stripchars($ProfileGalleryFixed); 
			
			  if(in_array($ProfileGallery,$arrayReservedFoldername)){
				$ProfileGalleryFixed =rb_agency_set_directory($ProfileGalleryFixed);
				$arrayReservedFoldername[$pos] = $ProfileGalleryFixed;
			 }
				
				if ($ProfileGallery == $ProfileGalleryFixed ) {
								$ProfileGalleryFixed = $ProfileGallery;
				} else {
							$ProfileGalleryFixed  = rb_agency_set_directory($ProfileGalleryFixed);
				}

				

				

				 array_push($arr_duplicates, $ProfileGalleryFixed);

				 $arr = array_count_values($arr_duplicates);
				 //print_r($arr);
				  
				  $file_rename_count = $arr[$ProfileGalleryFixed];
 
				  if($file_rename_count > 1){
					   $ProfileGalleryFixed = $ProfileGalleryFixed."-".$file_rename_count;
				  }else{
						$ProfileGalleryFixed = $ProfileGalleryFixed;
				  }



			/*if ($ProfileGallery == $ProfileGalleryFixed) {
			} else {*/
				// Folder Exist?
				if (is_dir(RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed)) {
					$finished = false;                       // we're not finished yet (we just started)
					while ( ! $finished ):                   // while not finished
						$ProfileGalleryFixed = $ProfileGalleryFixed .$ProfileID;   // output folder name
						if ( ! is_dir(RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed) ):        // if folder DOES NOT exist...
							rename(RBAGENCY_UPLOADPATH ."/". $ProfileGallery, RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed);

							if (is_dir(RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed)) {
								$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."' WHERE ProfileID = \"". $ProfileID ."\"";
								$renamed = $wpdb->query($rename);
								echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
							} else {
								echo "  <div id=\"message\" class=\"error\">Error renaming <strong>/" . $ProfileGalleryFixed . "/</strong> for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
							}

							$finished = true; // ...we are finished
						endif;
					endwhile;

				} else {
					
					// Create Folders
					rename(RBAGENCY_UPLOADPATH ."/". $ProfileGallery, RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed);
					if (is_dir(RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed) ) { // if folder DOES NOT exist...
						$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."' WHERE ProfileID = \"". $ProfileID ."\"";
						$renamed = $wpdb->query($rename);
						echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					}
				}
			//}
			$pos++;
		}

	} else {

		echo "<h3>Hide Profile Identity</h3>\n";
		echo "<p>If you created model profiles while under \"First Last\" or \"First L\" and wish to switch to Display names or IDs you will have to rename the existing folders so that they do not have the models name in it.</p>\n";
	
	
		/*
		echo "<br />";
		var_dump(is_dir(RBAGENCY_UPLOADPATH . "/john-doe/"));
		echo "<br />";
		echo RBAGENCY_UPLOADREL;
		// Open a known directory, and proceed to read its contents
		$dir = RBAGENCY_UPLOADPATH;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
				}
				closedir($dh);
			}
		}
		*/

		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 = $wpdb->get_results($query1, ARRAY_A);
		$count1 = $wpdb->num_rows;



		$pos = 0;
		$pos_suggested = 0;
		$arrayReservedFoldername = array();
		$throw_error  = false;
			
		foreach ($results1 as $data1 ) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
			$arrayAllFolderNames[$pos] = $ProfileGallery;
			$pos++; // array position start = 0	
			
/*			if ($rb_agency_option_profilenaming == 0) {
				$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
			} elseif ($rb_agency_option_profilenaming == 1) {
				$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
			} elseif ($rb_agency_option_profilenaming == 2) {
				$ProfileGalleryFixed = $ProfileContactDisplay;
			} elseif ($rb_agency_option_profilenaming == 3) {
				$ProfileGalleryFixed = "ID ". $ProfileID;
			}*/

				$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
							if ($rb_agency_option_profilenaming == 0) {
								$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
							} elseif ($rb_agency_option_profilenaming == 1) {
								$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
							} elseif ($rb_agency_option_profilenaming == 2) {
								$ProfileGalleryFixed = $ProfileContactNameFirst;
							} elseif ($rb_agency_option_profilenaming == 3) {
								$ProfileGalleryFixed = "ID ". $ProfileID;
							} elseif ($rb_agency_option_profilenaming == 4) {
								$ProfileGalleryFixed = $ProfileContactNameFirst;
							} elseif ($rb_agency_option_profilenaming == 5) {
								$ProfileGalleryFixed = $ProfileContactNameLast;
							}
			
			$ProfileContactDisplay = $ProfileGalleryFixed;
			$ProfileGalleryFixed = RBAgency_Common::format_stripchars($ProfileGalleryFixed); 
		
			if(in_array($ProfileGallery,$arrayReservedFoldername)){
				$ProfileGalleryFixed = rb_agency_just_checkdir($ProfileGalleryFixed);
				$arrayReservedFoldername[$pos] = $ProfileGalleryFixed;
			}

				array_push($arr_duplicates, $ProfileGalleryFixed);

				 $arr = array_count_values($arr_duplicates);
				 //print_r($arr);
				  
				  $file_rename_count = $arr[$ProfileGalleryFixed];
 
				  if($file_rename_count > 1){
					   $ProfileGalleryFixed = $ProfileGalleryFixed."-".$file_rename_count;
				  }else{
						$ProfileGalleryFixed = $ProfileGalleryFixed;
				  }


			// Check for duplicate
			$query_duplicate = "SELECT ProfileGallery, count(ProfileGallery) as cnt FROM ". table_agency_profile ." WHERE ProfileGallery='%s' GROUP BY ProfileGallery   HAVING cnt > 1";
			$rs = $wpdb->get_results($wpdb->prepare($query_duplicate,$ProfileGalleryFixed), ARRAY_A);
			$count  = $wpdb->num_rows;

			if($count > 0){
					
				// Add Profiles to Array to Create later
				$throw_error = true;
				//$ProfileGalleryFixed =  rb_agency_set_directory($ProfileGalleryFixed);
				echo "  <span style='width: 240px; color: red;'>". RBAGENCY_UPLOADDIR  . $ProfileGallery ."/</span>\n";
				echo "  <strong>Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=". $data1['ProfileID'] ."'>". $data1['ProfileContactNameFirst'] ." ". $data1['ProfileContactNameLast'] ."</a></strong>\n";
				echo "  Should be renamed to /<span style='width: 240px; color: red;'>". $ProfileGalleryFixed ."/</span>\n";

			} elseif ($ProfileGallery == $ProfileGalleryFixed ) {
				echo "<div style=\"padding:10px;border:1px solid #ccc;\">\n";
				echo "  <span style='width: 240px; color: green;'>". RBAGENCY_UPLOADDIR  . $ProfileGalleryFixed ."/</span>\n";
				echo "</div>\n";
			}else{
				  // Create Folders
				 if(!empty($ProfileGallery)){
					rename(RBAGENCY_UPLOADPATH ."/". $ProfileGallery, RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed);
					if (is_dir(RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed) ) { // if folder DOES NOT exist...
						$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."', ProfileContactDisplay = '".$ProfileContactDisplay ."' WHERE ProfileID = \"". $ProfileID ."\"";
						$renamed = $wpdb->query($rename);
						echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					}
				}else{
						// actual folder creation
						$dirURL = RBAGENCY_UPLOADPATH. $ProfileGalleryFixed;
						if (!is_dir(RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed) ) {
							mkdir($dirURL, 0755); //700
							chmod($dirURL, 0777);
						}
						echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>". $dirURL ."/</strong> has been created for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					   $rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."', ProfileContactDisplay = '".$ProfileContactDisplay ."' WHERE ProfileID = \"". $ProfileID ."\"";
					   $renamed = $wpdb->query($rename);
						
				   
				}

					//echo "  <span style='width: 240px; color: green;'>". RBAGENCY_UPLOADDIR  .  $ProfileGalleryFixed ."/</span>\n";
			}
			$pos++;
		
		}//endwhile
			
			
		if ($count1 < 1) {
				
			echo "There are currently no profile records.";
				
		} elseif ($throw_error == true) { ?>
			<a name="generate"></a>
			<h3>Generate Folders for Profiles</h3>
			<p>Click the button below to create folders for all profiles identified as not having a folder created:</p>
			<p><a class="button-primary" href="?page=rb_agency_reports&ConfigID=<?php echo $ConfigID; ?>&action=generate" title="Generate Missing Folders for Profiles">Rename Profiles to match Privacy Settings</a>  Clicking this button will rename folders for the above profiles<p>
			<?php
		}
	} // To Generate or Not to Generate
	  
   
		
}
elseif ($ConfigID == 13) {

// *************************************************************************************************** //
// Manage Settings

	echo "<h2>". __("Resize Images", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
	
	/*********** Max Size *************************************/
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_agencyimagemaxheight 	= $rb_agency_options_arr['rb_agency_option_agencyimagemaxheight'];
			if (empty($rb_agency_option_agencyimagemaxheight) || $rb_agency_option_agencyimagemaxheight < 500) { $rb_agency_option_agencyimagemaxheight = 800; }
		$rb_agency_option_agencyimagemaxwidth 	= $rb_agency_options_arr['rb_agency_option_agencyimagemaxwidth'];
			if (empty($rb_agency_option_agencyimagemaxwidth) || $rb_agency_option_agencyimagemaxwidth < 500) { $rb_agency_option_agencyimagemaxwidth = 1000; }
	
	/*********** Step Size *************************************/
	$stepSize = 20;
	$query4t = "SELECT ProfileID FROM ". table_agency_profile ."";
	$results4t = $wpdb->get_results($query4t, ARRAY_A);
	$count4total =  $wpdb->num_rows;
	$pageString = "";
	
	if (isset($_GET['Step'])) { 
		$currentPage = $_GET['Step']; 
		$step = $currentPage * $stepSize;
	} else { 
		$currentPage = 1; 
		$step = 0;
	}
	
	$totalPages = ceil($count4total/$stepSize);
		//echo "Total pages:" . $totalPages;
		if($totalPages >= 1) {
			for($i = 1; $i <= $totalPages; $i++) {
				$pageString .= " <a href=\"?page=rb_agency_reports&ConfigID=13&Step={$i}".(isset($queryVars)?$queryVars:"")."\">Page $i</a>";
				$pageString .= $i != $totalPages ? " | " : "";
			}
		}
	echo "<div>". $pageString ."</div>\n";


	/*********** Query Database *************************************/
	
		$query = "SELECT ProfileID, ProfileContactNameFirst, ProfileContactNameLast, ProfileGallery FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst LIMIT %d,%d"; //LIMIT $step,100
		$results =  $wpdb->get_results($wpdb->prepare($query,$step,$stepSize), ARRAY_A); 
		$count = $wpdb->num_rows;
		foreach ($results as $data ) {
			
			echo "<div>\n";
			echo "<h3>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h3>\n";
			$ProfileGallery = $data['ProfileGallery'];
			$ProfileID = $data['ProfileID'];


			$queryImg = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = %d AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC, ProfileMediaID DESC";
			$resultsImg = $wpdb->get_results($wpdb->prepare($queryImg,$ProfileID ), ARRAY_A); 
			$countImg = $wpdb->num_rows;
			echo "<div><strong>$countImg total</strong></div>\n";
			foreach ($resultsImg as $dataImg ) {
				$filename = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
				
				$image = new rb_agency_image();
				$image->load($filename);
				echo "<div style=\"float: left; width: 110px;\">\n";
				
				if ($image->orientation() == "landscape") {
					if ($image->getWidth() > $rb_agency_option_agencyimagemaxwidth) {
						$image->resizeToWidth($rb_agency_option_agencyimagemaxwidth);
						echo "RESIZED LANDSCAPE<br />\n";
						$image->save(RBAGENCY_UPLOADPATH . $ProfileGallery ."/". $dataImg['ProfileMediaURL']);
					}
				} else {
					if ($image->getHeight() > $rb_agency_option_agencyimagemaxheight) {
						$image->resizeToHeight($rb_agency_option_agencyimagemaxheight);
						echo "RESIZED PORTRAIT<br />\n";
						$image->save(RBAGENCY_UPLOADPATH . $ProfileGallery ."/". $dataImg['ProfileMediaURL']);
					}
				}
				echo "  <img src=\"". $filename ."\" style=\"width: 100px; z-index: 1; \" />\n";
				echo "W: ". $image->getWidth() ."; H: ". $image->getHeight() ."<br />\n";
				echo "</div>\n";
			}
			if ($countImg < 1) {
				echo "<div>There are no images loaded for this profile yet.</div>\n";
			}
			echo "<div style=\"clear: both; \"></div>\n";
			echo "</div>\n";

		}

} // End 11
elseif ($ConfigID == 12) {
//Export database
// *************************************************************************************************** //
// Manage Settings
		echo '<br /><br /><br />';
		echo "<form action=\"".RBAGENCY_PLUGIN_URL."view/exportDatabase.php\" method=\"post\">";
		echo '<input type="submit" class="button-primary" value='. __('"Export Database"', RBAGENCY_TEXTDOMAIN).'>';
		echo '</form>';

		/*echo "<h2>". __("Export Database", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
		
			echo "<a href=\"". RBAGENCY_PLUGIN_URL ."view/exportDatabase.php\">Export Database</a>\n";
		*/
}
elseif ($ConfigID == 81) 
{
	echo "<h2>". __(" Export Database", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
	echo " <form action=\"".RBAGENCY_PLUGIN_URL."view/export-Profile-Database.php\" method=\"post\">";
	echo "<input checked=\"checked\" type=\"radio\" name=\"export-profile\" value=\"template\">Download Template<br/>";
	$total_profiles = $wpdb->get_row("SELECT count(*) as total_profiles FROM ".table_agency_profile."");
	$from = 1;
	$to = 0;
	$count = isset($_GET["count"]) && !empty($_GET["count"])?$_GET["count"]:100;
	$last = round($total_profiles->total_profiles/$count);
	$loop_count = $count;
	$x = 0;
	for($a = 1; $a<=$last; $a++) {
		$x++;
		$from = ($a==1)?1:($loop_count+1);

		$to = ($a*$count);
		if($x == $last){
			$to = $total_profiles->total_profiles;
		}
		
		echo "<input  required type=\"radio\" name=\"export-profile\" value=\"".($from)."-".$to."\">Export Profiles(".($from)."-".$to.")<br/>";
		
		$loop_count = (($a==1)?$count:$loop_count + $count);
		
	}
	echo "      <select name=\"file_type\" required>";
	echo "          <option value=\"\">Select file format</option>";
	echo "          <option value=\"xls\">XLS</option>";
	echo "          <option value=\"csv\">CSV</option>";
	echo "      </select>";
	echo "      <input type=\"submit\" value=\"Export Now\" class=\"button-primary\">";
	echo "  </form>";    
}
elseif ($ConfigID == 80) {

// *************************************************************************************************** //
// Import CSV or XLS files (NK)

	$error_message = ""; 
	$form_display_flag = true;
	
	// do this only when data has been submitted.	
	if(isset($_POST['submit_importer']) || isset($_POST['submit_importer_to_db'])){
		
		$obj_csv = new RBAgencyCSVXLSImpoterPlugin();
		
		global $wpdb;
	
		$custom_fields_rb_agency = $wpdb->get_results("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder", ARRAY_A);
		$fields_array = array( 0 => array('ProfileContactDisplay','ProfileContactNameFirst','ProfileContactNameLast','ProfileGender','ProfileDateBirth','ProfileContactEmail','ProfileContactWebsite','ProfileContactPhoneHome','ProfileContactPhoneCell','ProfileContactPhoneWork','ProfileLocationStreet','ProfileLocationCity','ProfileLocationState','ProfileLocationZip','ProfileLocationCountry','ProfileType','ProfileIsActive'));
	
		$count = count($fields_array[0]);
				
		// right distribution of header keys
		foreach ($custom_fields_rb_agency as $keys) 
		{	
			foreach ($keys as $key => $c_field) 
				{	
					if($key == 'ProfileCustomTitle'){
					  $fields_array[0][$count] = 'Client'.str_replace(' ', '',$c_field);
					  $count++;
					}
				}
		}

		$target_path = WP_CONTENT_DIR.'/FORMAT.csv';
		$csv_format = fopen($target_path,'w');
		fputcsv($csv_format, $fields_array[0]);  
		fclose($csv_format);
		chmod($target_path, 0777);

	}

	if(isset($_POST['submit_importer']))
	{   
		/*Reading a file type to confirm input of CSV, XLS or XLSX file*/
		if($_FILES['source_file']['name'] == "")
		{
			$error_message = "Empty file!";
		}
		//echo $_FILES['source_file']['type'];die;
		if($_FILES['source_file']['type'] == 'application/octet-stream' || $_FILES['source_file']['type'] == 'application/vnd.ms-excel' || $_FILES['source_file']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') /*CSV and Excel files*/
		{
			$return_result = $obj_csv->match_column_and_table(); /*Display colunm head*/

			if( $return_result == 0)
			{
				$error_message = "Empty header!";
				$form_display_flag = true;
			}
			else
			{
				$form_display_flag = false;
			}
		}
		else
		{
			$error_message = 'Incorrect file format. Only CSV, XLS and XLSX file formats are allowed!';
			$form_display_flag = true;
		}
	}
	else if(isset($_POST['submit_importer_to_db']) && $_POST['submit_importer_to_db'])
	{
		$obj_csv->import_to_db();   /*Store profile data*/
		$form_display_flag = true;

	}
	
	if( $form_display_flag == true )
	{
		echo "<h2>". __("Import CSV / XLS", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
		
		/*File error message*/
		echo "<span class=\"error-message\">$error_message</span> <br>";
		
		/*Form for file selection*/
		echo "  <form action=\"\" method=\"post\" enctype=\"multipart/form-data\">";
		echo "      <label>Select File</label>";
		echo "      <input type=\"file\" name=\"source_file\">  <br>";
		echo "      <input type=\"submit\" id=\"submit\" class=\"button-primary\" value=\"Read Column Head\" name=\"submit_importer\">";
		echo "  </form>";
	}

}




/*
 * Install Dummy Accounts/Profiles with Media Content
 */
elseif ($ConfigID == 14) {

		$trackDummies = array();
		$sample_url = RBAGENCY_PLUGIN_DIR."assets/demo-data"; // Samples' folder
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ? (int)$rb_agency_options_arr['rb_agency_option_profilenaming']:"";

	/*
	 * Sample Data Values
	 */

		// Names  (TODO: Segment Male and Female)
		$userProfileNames = array(
			array('Adele','McKinnon','Female'),
			array('Andre','Wright','Male'),
			array('Andrew','Lazenby','Male'),
			array('Ann','Barr','Female'),
			array('Ann','Taylor','Female'),
			array('Barbara','Shannon','Female'),
			array('Barrion','Fhil','Male'),
			array('Bartholomew','Woodley','Male'),
			array('Bernice','Holmes','Female'),
			array('Bradley','Curtis','Male'),
			array('Bradley','Smith','Male'),
			array('Brenda','Wright','Female'),
			array('Caitlin','Muir','Female'),
			array('Carl','Riaz','Male'),
			array('Carlita','Ellis','Female'),
			array('Carrie','Smith','Female'),
			array('David','Johnson','Male'),
			array('Debra','Ralph','Female'),
			array('Doris','Timber','Female'),
			array('Drucylla','Wood','Female'),
			array('Edward','Marr','Male'),
			array('Eleanor','Taylor','Female'),
			array('Elijah','McCarron','Female'),
			array('Elizabeth','Dickson','Female'),
			array('Eric','Brading','Male'),
			array('Ethel','Onstein','Female'),
			array('Ethel','Robertson','Female'),
			array('Ford','Jones','Male'),
			array('Geoffrey','Goutouski','Male'),
			array('Glenn','Tallyn','Male'),
			array('Grace','Fields','Female'),
			array('Heather','Leonard','Female'),
			array('Inez','Page','Female'),
			array('Janice','Wood','Female'),
			array('Jared','Benton','Male'),
			array('Jason','Smith','Male'),
			array('Jean','Wickson','Female'),
			array('Jeanette','Johnson','Female'),
			array('Jeanette','McKinnon','Female'),
			array('Jeffrey','Jackson','Male'),
			array('Jillian','Ladell','Female'),
			array('Joanne','Bousfield','Female'),
			array('Joanne','Hastman','Female'),
			array('John','Bradley','Male'),
			array('Joseph','Schmitz','Male'),
			array('Joyce','Hearns','Female'),
			array('Joyce','Stanfield','Female'),
			array('Karen','Valdez','Female'),
			array('Kay ','Jesch','Female'),
			array('Kelly','Holmes','Female'),
			array('Lee','Onstein','Male'),
			array('Leonard','Johnson','Male'),
			array('Louise','Sutherland','Male'),
			array('Lynn','Sutherland','Female'),
			array('Marco','Rouse','Male'),
			array('Margaret','Smith','Female'),
			array('Marvette','Fields','Female'),
			array('Millicent','Wilson','Female'),
			array('Monique','Brading','Female'),
			array('Myra','Holmes','Female'),
			array('Nancy','Gullis','Female'),
			array('Otmar','Dales','Male'),
			array('Patrick','Ingles','Male'),
			array('Randy','Cowal','Male'),
			array('Ricardo','Downs','Male'),
			array('Richard','Brading','Male'),
			array('Samantha','Newell','Female'),
			array('Samuel','Kunick','Male'),
			array('Sharon','Leslie','Female'),
			array('Steven','Arvay','Male'),
			array('Theresa','Ezeard','Female'),
			array('Trevor','Childs','Male'),
			array('Valerie','Taylor','Female'),
			array('Victor','Bailey','Male'),
			array('Wycliffe','Rouse','Male'));

		$userMediaVideo = array(
			"http://www.youtube.com/watch?v=0hMzBRM96gk",
			"http://www.youtube.com/watch?v=xNiSREeN-rk",
			"http://www.youtube.com/watch?v=c8YZIL8JZfg",
			"http://www.youtube.com/watch?v=Mx3wQGU862E",
			"http://www.youtube.com/watch?v=p8DYtnBa4a8",
			"http://www.youtube.com/watch?v=y58mkKh_0Gw"
		);

		$userMediaImagesM = array("male_model-01.jpg","male_model-02.jpg","male_model-03.jpg","male_model-04.jpg","male_model-05.jpg","male_model-06.jpg","male_model-07.jpg","male_model-08.jpg","male_model-09.jpg");
		$userMediaImagesF = array("female_model-01.jpg","female_model-02.jpg","female_model-03.jpg","female_model-04.jpg","female_model-05.jpg","female_model-06.jpg","female_model-07.jpg","female_model-08.jpg","female_model-09.jpg");
		$userMediaVideoType = array("Demo Reel","Video Monologue","Video Slate");
		$userMediaHeadshot = array("headshot.jpg","headshot-2.jpg");
		$userMediaResume = array("resume.docx","resume_PDF.pdf");
		$userMediaCompcard = array("comp-card.jpg");
		$userMediaVoicedemo = array("voice-demo.mp3");

	/*
	 * Register dummies to track
	 */  

	   $arr_duplicates = array();
		foreach($userProfileNames as $ProfileContact):
			$ProfileContactDisplay = "";
			$ProfileGallery = "";

			if (empty($ProfileContactDisplay)) {  // Probably a new record... 
				if ($rb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContact[1] . " ". $ProfileContact[0];
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileContactDisplay = $ProfileContact[1] . " ". substr($ProfileContact[0], 0, 1);
				} elseif ($rb_agency_option_profilenaming == 2) {
						$ProfileContactDisplay = $ProfileContact[1] . " ". $ProfileContact[0];
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID ". $ProfileID;
				}
				
				$ProfileGalleryFixed = RBAgency_Common::format_stripchars( $ProfileContactDisplay); 
				

				 array_push($arr_duplicates, $ProfileGalleryFixed);

				 $arr = array_count_values($arr_duplicates);
				 //print_r($arr);
				  
				  $file_rename_count = $arr[$ProfileGalleryFixed];
 
				  if($file_rename_count > 1){
					  $ProfileGallery = $ProfileGalleryFixed."-".$file_rename_count;
				  }else{
					   $ProfileGallery = $ProfileGalleryFixed;
				  }

				 
			}

			if (empty($ProfileGallery)) {  // Probably a new record... 
				$ProfileGallery = RBAgency_Common::format_stripchars($ProfileGallery); 
			}

			$ProfileGallery = rb_agency_just_checkdir($ProfileGallery);
			#DEBBUG echo $ProfileGallery ."<Br/>";
			#DEBBUG echo $ProfileContactDisplay ."<Br/>";
			array_push($trackDummies,$ProfileGallery);
		endforeach;

	/*
	 * Register dummies to track
	 */

		$trackDummies_text = implode(",",$trackDummies);

		echo "<form method=\"post\" action=\"options.php\">\n";
		echo "<br/><br/>";

		settings_fields( 'rb-agency-dummy-settings-group' ); 
		$rb_agency_dummy_options_arr = get_option('rb_agency_dummy_options');

		if (empty($rb_agency_dummy_options_installdummy)) { $rb_agency_dummy_options_installdummy =""; }
		$rb_agency_dummy_options_installdummy = $rb_agency_dummy_options_arr['rb_agency_dummy_options_installdummy'];

		if(empty($rb_agency_dummy_options_installdummy)){
			echo "<input type=\"hidden\" name=\"rb_agency_dummy_options[rb_agency_dummy_options_installdummy]\" value=\"".$trackDummies_text."\" />\n";
			echo "<input type=\"submit\" name=\"generate\" value=\"Generate Dummies Now!\" />\n";
			$_SESSION["trackDummies_text"] = $trackDummies_text;
		}else{
			echo "<input type=\"submit\" name=\"remove\" value=\"Remove All ".count($userProfileNames)." Dummy Accounts generated\" />\n";	
			echo "<input type=\"hidden\" name=\"rb_agency_dummy_options[rb_agency_dummy_options_installdummy]\" value=\"\" />\n";
		}
		echo "</form>\n";

	/*
	 * Next Step
	 */

		if(isset($_GET["settings-updated"]) && empty($rb_agency_dummy_options_installdummy)){
			
			echo "<h2>". __("Removing Dummy Profiles...", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
			echo "<br/>";

			if($rb_agency_option_profilenaming != 3){
				
				// Track dummies to pull out
			   $arr_duplicates = array();
				foreach($userProfileNames as $ProfileContact){
					$ProfileContactDisplay = "";
					$ProfileGallery = "";

								if (empty($ProfileContactDisplay)) {  // Probably a new record... 
									if ($rb_agency_option_profilenaming == 0) {
										$ProfileContactDisplay = $ProfileContact[0] . " ". $ProfileContact[1];
									} elseif ($rb_agency_option_profilenaming == 1) {
										$ProfileContactDisplay = $ProfileContact[0] . " ". substr($ProfileContact[1], 0, 1);
									} elseif ($rb_agency_option_profilenaming == 2) {
										$error .= "<b><i>". __(LabelSingular ." must have a display name identified", RBAGENCY_TEXTDOMAIN) . ".</i></b><br>";
										$have_error = true;
									} elseif ($rb_agency_option_profilenaming == 3) {
										$ProfileContactDisplay = "ID ". $ProfileID;
									}

									$ProfileGalleryFixed = RBAgency_Common::format_stripchars( $ProfileContactDisplay); 
								  
									 array_push($arr_duplicates, $ProfileGalleryFixed);

									 $arr = array_count_values($arr_duplicates);
									 //print_r($arr);
									  
									  $file_rename_count = $arr[$ProfileGalleryFixed];
					 
									  if($file_rename_count > 1){
										  $gallery = $ProfileGalleryFixed."-".$file_rename_count;
									  }else{
										   $gallery = $ProfileGalleryFixed;
									  }

								}
								echo "<strong>/".$gallery."/</strong> linked directory removed.<br/>";
								$getGallary="SELECT ProfileID,ProfileGallery FROM ".table_agency_profile ." WHERE ProfileGallery = %s ";
								$fID =  $wpdb->get_row($wpdb->prepare($getGallary,$gallery), ARRAY_A);
								$pSql="DELETE FROM ".table_agency_profile ." WHERE ProfileID = '%d' ";
								$wpdb->query($wpdb->prepare($pSql,$fID["ProfileID"]));
								$pmSql="DELETE FROM ".table_agency_profile_media ." WHERE ProfileID = '%d' ";
								$wpdb->query($wpdb->prepare($pmSql,$fID["ProfileID"]));
								uninstall_dummy_profile($gallery);
				}// endforeach
					
			  } else{
				$dummy_profile_ids = get_option("rb_agency_dummy_profiles");
				if(isset($dummy_profile_ids) && !empty($dummy_profile_ids)){
					$getGallery="SELECT ProfileID,ProfileGallery,ProfileContactNameFirst,ProfileContactNameLast FROM ".table_agency_profile ." WHERE ProfileID IN(".$dummy_profile_ids.") ";
					$results = $wpdb->get_results($getGallery);	
					foreach ($results as $k) {
						$ProfileGallery = $k->ProfileGallery;
						$ProfileContactNameFirst = $k->ProfileContactNameFirst;
						$ProfileContactNameLast = $k->ProfileContactNameLast;
						$ProfileID = $k->ProfileID;

									if ($rb_agency_option_profilenaming == 0) {
										$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
									} elseif ($rb_agency_option_profilenaming == 1) {
										$ProfileContactDisplay = $ProfileContactNameFirst. " ". substr($ProfileContactNameLast, 0, 1);
									} elseif ($rb_agency_option_profilenaming == 2) {
										$ProfileContactDisplay = $ProfileContactNameFirst. " ". substr($ProfileContactNameLast, 0, 1);
									} elseif ($rb_agency_option_profilenaming == 3) {
										$ProfileContactDisplay = "ID ". $ProfileID;
									}

						$ProfileGalleryFixed = RBAgency_Common::format_stripchars( $ProfileContactDisplay); 
						
						$pSql="DELETE FROM ".table_agency_profile ." WHERE ProfileID = '%d' ";
						$wpdb->query($wpdb->prepare($pSql,$ProfileID));
						$pmSql="DELETE FROM ".table_agency_profile_media ." WHERE ProfileID = '%d' ";
						$wpdb->query($wpdb->prepare($pmSql,$ProfileID));
						$pmSql="DELETE FROM ".table_agency_customfield_mux ." WHERE ProfileID = '%d' ";
						$wpdb->query($wpdb->prepare($pmSql,$ProfileID));
						
						uninstall_dummy_profile($ProfileGalleryFixed);

						echo "<strong>/".$ProfileGalleryFixed."/</strong> linked directory removed.<br/>";
					}
					delete_option("rb_agency_dummy_profiles");
				}
			  }
				
			}


		
		
		if(isset($_GET["settings-updated"]) && !empty($rb_agency_dummy_options_installdummy)){	
			echo "<h2>". __("Installing Dummies...", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
			echo "<br/>";  
			echo "Succesfully created ".count($userProfileNames)." dummy profiles..<br/>";

			$arr_profile_ids = array();
			
			foreach($userProfileNames as $ProfileContact){

				$ProfileContactDisplay = "";
				$ProfileGallery = "";
				$userCategory = "";
				$userGender ="";
				$queryGender = "SELECT * FROM ".table_agency_data_gender."  ";
				$userGender = $wpdb->get_results($queryGender, ARRAY_A);
				foreach ($userGender as $row ) {
					if($ProfileContact[2]==$row['GenderTitle']){
						$userGender["GenderID"]=$row['GenderID']; 
					}
					  
				}

				$queryCategory = "SELECT * FROM ".table_agency_data_type."  WHERE DataTypeID >= (SELECT FLOOR( MAX(DataTypeID) * RAND()) FROM ".table_agency_data_type." ) ORDER BY  RAND() LIMIT 1";
				$userCategory = $wpdb->get_row($queryCategory, ARRAY_A);
				

				echo $ProfileContact[0]." ".$ProfileContact[1]."<br/>";

				if (empty($ProfileContactDisplay)) {  // Probably a new record... 
					if ($rb_agency_option_profilenaming == 0) {
						$ProfileContactDisplay = $ProfileContact[0] . " ". $ProfileContact[1];
					} elseif ($rb_agency_option_profilenaming == 1) {
						$ProfileContactDisplay = $ProfileContact[0] . " ". substr($ProfileContact[1], 0, 1);
					} elseif ($rb_agency_option_profilenaming == 2) {
						$ProfileContactDisplay = $ProfileContact[0] . " ". $ProfileContact[1];
					} elseif ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID ". $ProfileID;
					}

						$ProfileGalleryFixed = RBAgency_Common::format_stripchars( $ProfileContactDisplay); 
							  
								 
						 array_push($arr_duplicates, $ProfileGalleryFixed);

						 $arr = array_count_values($arr_duplicates);
						 //print_r($arr);
						  
						  $file_rename_count = $arr[$ProfileGalleryFixed];
		 
						  if($file_rename_count > 1){
							  $ProfileGallery = $ProfileGalleryFixed."-".$file_rename_count;
						  }else{
							   $ProfileGallery = $ProfileGalleryFixed;
						  }
				}


				if (empty($ProfileGallery)) {  // Probably a new record... 
					$ProfileGallery = RBAgency_Common::format_stripchars($ProfileGallery); 
				}
				if($rb_agency_option_profilenaming != 3){
					$ProfileGallery = rb_agency_createdir($ProfileGallery);
				}
				// Select city and state
				$queryCountry = "SELECT * FROM ".table_agency_data_country." ORDER BY RAND( ) ASC LIMIT 1";
				$userCountry =  $wpdb->get_row($queryCountry, ARRAY_A);

				$queryState = "SELECT * FROM ".table_agency_data_state."  where CountryID = %d ORDER BY RAND( ) ASC LIMIT 1";
				$userState = $wpdb->get_row($wpdb->prepare($queryState,$userCountry['CountryID']), ARRAY_A);
								
				$insert = "INSERT INTO " . table_agency_profile . "(
							ProfileGallery,
							ProfileContactDisplay,
							ProfileContactNameFirst,
							ProfileContactNameLast,
							ProfileIsActive,
							ProfileGender,
							ProfileType,
							ProfileDateBirth,
							ProfileLocationCountry,
							ProfileLocationState,
							ProfileLocationStreet,
							ProfileLocationCity,
							ProfileLocationZip,
							ProfileContactEmail,
							ProfileContactPhoneHome,
							ProfileContactPhoneCell,
							ProfileContactPhoneWork,
							ProfileContactWebsite
						) VALUES (
							'".$ProfileGallery."',
							'".trim($ProfileContactDisplay)."',
							'".trim($ProfileContact[0])."',
							'".trim($ProfileContact[1])."',
							1,
							'".$userGender["GenderID"]."',
							'".$userCategory["DataTypeID"]."',
							'".date('Y-m-d', strtotime(mt_rand(1970,2010).'-'.mt_rand(1,12)."-".mt_rand(1,30)))."',
							'".$userCountry['CountryID']."',
							'".$userState['StateID']."',
							'Street',
							'City',
							'Zip',
							'".$ProfileContact[0]."@modelingagencysoftware.com',
							'000-000-000',
							'000-000-000',
							'000-000-000',
							'http://wwww.modelingagencysoftware.com'
						);"; 
				
				$results = $wpdb->query($insert);
				$ProfileID = $wpdb->insert_id;
				array_push($arr_profile_ids, $ProfileID);

				if ($rb_agency_option_profilenaming == 3) {

					$ProfileGallery = RBAgency_Common::format_stripchars('ID-'.$ProfileID); 
					$ProfileGallery = rb_agency_createdir($ProfileGallery);

					$wpdb->query($wpdb->prepare("UPDATE ".table_agency_profile." SET ProfileContactDisplay = %s,ProfileGallery = %s WHERE ProfileID = %d",'ID-'.$ProfileID,$ProfileGallery,$ProfileID));
				}

				// Inserting Custom Field 
				$queryCustom = $wpdb->get_results("SELECT * FROM ".table_agency_customfields." ", ARRAY_A); 
				foreach ($queryCustom as $rowCustom) {
					if($rowCustom['ProfileCustomType']==3){
						 $customValueArray = explode("|", $rowCustom['ProfileCustomOptions']);
						 $customValue= $customValueArray[1];
					}elseif($rowCustom['ProfileCustomType']==7 || $rowCustom['ProfileCustomType']==1){
						$customValue = rand(0,15) ; 
					}elseif($rowCustom['ProfileCustomType']==4){
						$customValue = "Dummy ".$rowCustom['ProfileCustomTitle']  ; 
					}
					$results =  $wpdb->query($wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " ( ProfileCustomID, ProfileID, ProfileCustomValue) VALUES (%s,%s,%s)",$rowCustom['ProfileCustomID'], $ProfileID ,$customValue));
				}
				

				

				$rand = rand(0,1); // 2
				$randTo6 = rand(0,5); //6
				$randTo4 = rand(0,4); // 5
				$randTo8 = rand(0,8); // 5

				for($a=0; $a<=3; $a++){

					// Copy images
					if($a<=3){
						if ($ProfileContact[2]=='Male') {
							if(!copy(rb_chmod_file_display($sample_url."/".$userMediaImagesM[$a]),rb_chmod_file_display(RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$a]))){
								echo $sample_url."/".$userMediaImagesM[$a]."<br/>".RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$a];
								echo "<br/>";
								die("Failed to Copy files... <br/>".phpinfo());
							}
							$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Image','". $userMediaImagesM[$a] ."','". $userMediaImagesM[$a] ."')");

						} else {
							if(!copy(rb_chmod_file_display($sample_url."/".$userMediaImagesF[$a]),rb_chmod_file_display(RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$a]))){
								echo $sample_url."/".$userMediaImagesF[$a]."<br/>".RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$a];
								echo "<br/>";
								die("Failed to Copy files... <br/>".phpinfo());
							}
							$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Image','". $userMediaImagesF[$a] ."','". $userMediaImagesF[$a] ."')");
						}
					}
					if($a<=3){
						if(isset($userMediaVideoType[$a]) && $userMediaVideoType[$a]!=""){
							$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $userMediaVideoType[$a]."','".rb_agency_get_VideoFromObject($userMediaVideo[$randTo6]) ."','". rb_agency_get_VideoFromObject($userMediaVideo[$randTo6])  ."')");
						}
					}
					if($a==1){

						if (isset($ProfileContact[2]) && $ProfileContact[2]=='Male') {
						// Male
						copy(rb_chmod_file_display($sample_url."/".$userMediaImagesM[$randTo8]),RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$randTo8]);
						$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL,ProfileMediaPrimary) VALUES ('". $ProfileID ."','Image','". $userMediaImagesM[$randTo8]."','". $userMediaImagesM[$randTo8] ."',1)");
						
						} else {
						// Female
						copy(rb_chmod_file_display($sample_url."/".$userMediaImagesF[$randTo8]),RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$randTo8]);
						$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL,ProfileMediaPrimary) VALUES ('". $ProfileID ."','Image','". $userMediaImagesF[$randTo8]."','". $userMediaImagesF[$randTo8] ."',1)");
						}
						  
						// Any Gender
						copy(rb_chmod_file_display($sample_url."/".$userMediaImagesM[$randTo8]),RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaHeadshot[$rand]);
						$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Headshot','". $userMediaHeadshot[$rand]."','". $userMediaHeadshot[$rand] ."')");

						copy(rb_chmod_file_display($sample_url."/".$userMediaVoicedemo[0]),RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaVoicedemo[0]);
						$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','VoiceDemo','". $userMediaVoicedemo[0] ."','".  $userMediaVoicedemo[0] ."')");

						copy(rb_chmod_file_display($sample_url."/".$userMediaCompcard[0]),RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaCompcard[0]);
						$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','CompCard','".$userMediaCompcard[0] ."','". $userMediaCompcard[0]."')");

						copy(rb_chmod_file_display($sample_url."/".$userMediaResume[$rand]),RBAGENCY_UPLOADPATH . $ProfileGallery ."/".$userMediaResume[$rand]);
						$results =  $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Resume','". $userMediaResume[$rand]."','".$userMediaResume[$rand]."')");

					}

				}
			} // End foreach
			$profile_ids = implode(",",$arr_profile_ids);
			if ( get_option("rb_agency_dummy_profiles") !== false ) {

				// The option already exists, so we just update it.
				update_option("rb_agency_dummy_profiles", $profile_ids );

			} else {
				add_option("rb_agency_dummy_profiles", $profile_ids, null,"no" );
			}
			unset($_SESSION["trackDummies_text"]);
		} // if option is empty

		if (isset($_GET["a"])){
			unset($_SESSION["trackDummies_text"]); 
			uninstall_allprofile();
		}



} //END $ConfigID == 14
elseif($ConfigID == '83'){
	?>
	<h3>Check for Profile Data migration errors</h3>

	<?php 
		$query1 = "SELECT * FROM ". table_agency_profile ."  ORDER BY ProfileContactNameFirst,ProfileContactNameLast DESC";
		$results1 = $wpdb->get_results($wpdb->prepare($query1), ARRAY_A);
		$count1 = $wpdb->num_rows;
		$arr_profiles = array();
		$has_no_types = 0;
	

		foreach ($results1 as $data1) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
			$ProfileGender 			=$data1["ProfileGender"];
			$ProfileType 			=$data1["ProfileType"];
		
				$get = $wpdb->get_results( "SELECT DataTypeTitle FROM " . table_agency_data_type . " WHERE DataTypeID IN(".(!empty($ProfileType)?$ProfileType:0).")",ARRAY_A);
				$arr_data_type = array();

				if ($wpdb->num_rows > 0 ){
					foreach($get as $k){
						array_push($arr_data_type,$k['DataTypeTitle']); 
					}
				}

				$query = "SELECT * FROM " . table_agency_data_gender . " WHERE GenderID = '%s' ";
				$fetchProfileGender =  $wpdb->get_row($wpdb->prepare($query,isset($ProfileGender)?$ProfileGender:0),ARRAY_A,0);
				$ProfileGenderTitle = $fetchProfileGender["GenderTitle"];
			
			array_push($arr_profiles, array(
				"fullname" => $ProfileContactNameFirst." ".$ProfileContactNameLast,
				"datatype" => $arr_data_type,
				"datatype_raw" => "",
				"gender_raw" => "",
				"contactdisplay" => $ProfileContactDisplay,
				"gender"   => $ProfileGenderTitle,
				"profileid" => $ProfileID

			));	  
			if(empty($arr_data_type) || empty($ProfileGender) || strpos($ProfileContactDisplay,"  ") !== false){
				$has_no_types++;
			}

		}
		if($has_no_types > 0){
			if($has_no_types > 1){
				echo "<strong>".$has_no_types." profiles have errors.</strong> <a class=\"button button-primary\" href=\"?page=rb_agency_reports&ConfigID=83&action=fix-profiles\">Fix all errors now</a>";
			}else{
				echo "<strong>".$has_no_types." profile has errors.</strong> <a class=\"button button-primary\" href=\"?page=rb_agency_reports&ConfigID=83&action=fix-profiles\">Fix all errors now</a>";
			}
		}else{
			echo "No errors found!";
		}
		echo "<br/><br/>";
		foreach ($arr_profiles as $key ) {
			if(empty($key["datatype"]) || empty($key["gender"]) || strpos($key["contactdisplay"],"  ") !== false){
				echo "<div style=\"border:1px solid #ccc;width:80%;padding:10px;background:#FAFAFA\">";
				echo "<strong style=\"text-transform:capitalize;\">".$key["fullname"]."</strong>";
				echo !empty($key["gender"])?", ".$key["gender"]:"";
				echo "<br/><br/>";
				if(empty($key["datatype"])){
					echo "<span style=\"color:rgb(250, 5, 5);background:#fff;padding:1px;border:1px solid #ccc;\">No <strong>profile types</strong> assigned</span>";
				}
				if(empty($key["gender"])){
					echo "<span style=\"color:rgb(250, 5, 5);background:#fff;padding:1px;border:1px solid #ccc;\">No <strong>gender</strong> assigned</span>";
				}
				if( strpos($key["contactdisplay"],"  ") !== false){
				
					echo "<span style=\"color:rgb(250, 5, 5);background:#fff;padding:1px;border:1px solid #ccc;\">Found double spacing in Contact Display</span>";
				}
				echo "<br/><br/>";
				if( strpos($key["contactdisplay"],"  ") !== true && empty($key["gender"]) || empty($key["datatype"])){
					echo "<strong>Fixes Found based from old data:</strong><br/>";
				}
				$subresult = $wpdb->get_results($wpdb->prepare("SELECT cfields_mux.*, cfields.ProfileCustomShowGender, cfields.ProfileCustomType FROM ". table_agency_customfield_mux ." as cfields_mux INNER JOIN ".table_agency_customfields." as cfields WHERE cfields_mux.ProfileID = %d AND cfields_mux.ProfileCustomID = cfields.ProfileCustomID", $ProfileID),ARRAY_A);
				$arr_gender = array();
				$arr_ptypes = array();
				$arr_data_type = array();
				$arr_data_gender = array();

				foreach($subresult as $s){
					array_push($arr_gender, $s["ProfileCustomShowGender"]);
					array_push($arr_ptypes, $s["ProfileCustomType"]);
				}
				
				$gender = implode(",",array_unique($arr_gender));
				$ptypes = implode(",",array_unique($arr_ptypes));

				$get = $wpdb->get_results( "SELECT DataTypeTitle FROM " . table_agency_data_type . " WHERE DataTypeID IN(".(!empty($ptypes)?$ptypes:0).")",ARRAY_A);
				
				if ($wpdb->num_rows > 0 ){
					foreach($get as $k){
						array_push($arr_data_type,$k['DataTypeTitle']); 
					}
				}
				$query = "SELECT * FROM " . table_agency_data_gender . " WHERE GenderID IN(".(isset($gender)?$gender:0).") ";
				$fetchProfileGender  =   $wpdb->get_row($query,ARRAY_A,0);

				$key["datatype_raw"] = (!empty($ptypes)?$ptypes:0);
				$key["gender_raw"] = $fetchProfileGender["GenderID"];
				
				if ($wpdb->num_rows > 0 ){
					
						array_push($arr_data_gender,$fetchProfileGender["GenderTitle"]); 
					
				}
				if(empty($key["gender"])){
					echo "User must be ".$arr_data_gender[0];
					echo "<br/>";
				}
				if(empty($key["datatype"])){
					echo "Possible profile type(s): ".implode(",",$arr_data_type);
				
					echo "<br/>";
				}
				// Update Profiles Types and Gender
				if(isset($_GET["action"]) && $_GET["action"] == "fix-profiles"){
				   echo "<br/><strong>Fixed errors:</strong><br/><br/>";
				   if(empty($key["gender"])){
					$wpdb->query("UPDATE ".table_agency_profile." SET ProfileGender='".$key["gender_raw"]."' WHERE ProfileID='".$key["profileid"]."'");
					echo "<strong style=\"color:green;\">Succesfully assigned gender to profile.</strong><br/>";
				   }
				   if(empty($key["datatype"])){
					  $wpdb->query("UPDATE ".table_agency_profile." SET ProfileType='".$key["datatype_raw"]."' WHERE ProfileID='".$key["profileid"]."'");
					 echo "<strong style=\"color:green;\">Succesfully assigned profile type to profile.</strong><br/>";
				   }
				   if( strpos($key["contactdisplay"],"  ") !== false){
					  $wpdb->query("UPDATE ".table_agency_profile." SET ProfileContactDisplay='".$key["fullname"]."' WHERE ProfileID='".$key["profileid"]."'");
					 echo "<strong style=\"color:green;\">Removed double spacings from Contact Display.</strong><br/>";
				   
				   }
				}


			echo "</div>";
			}
		}

}
elseif($ConfigID == '99'){
	
		$active = get_option('active_plugins');
	$found = false;
	foreach($active as $act){
		if(preg_match('/rb-agency-interact\.php/',$act)){
			echo "<h2>". __("Generate Login / Passwords", RBAGENCY_TEXTDOMAIN) . "</h2>\n";
			rb_display_profile_list();
			$found = true;
		}
	}
	if(!$found){
		echo "<h3>". __("Activate/Install Rb Agency Interact plugin to use this feature", RBAGENCY_TEXTDOMAIN) . "</h3>\n";
	}
	
}
// End Config == 99





/******************************************************************************************/



function uninstall_dummy_profile($profile){
	
	
	 $dir  = RBAGENCY_UPLOADPATH .$profile;  
	 if(@scandir($dir)){
		 foreach (scandir($dir) as $item) {
				if ($item == '.' || $item == '..') continue;
				if(file_exists($dir.DIRECTORY_SEPARATOR.$item))
				 unlink($dir.DIRECTORY_SEPARATOR.$item);
		 }
		 rmdir($dir);
	}
}

function uninstall_allprofile(){
	  global $wpdb;
	  $wpdb->query("TRUNCATE TABLE ".table_agency_profile ."");
	  $wpdb->query("TRUNCATE TABLE ".table_agency_profile_media ."");
	 $dir  = RBAGENCY_UPLOADPATH."/";  
	 foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if(file_exists($dir.DIRECTORY_SEPARATOR.$item))
				
			 unlink($dir.DIRECTORY_SEPARATOR.$item);
	 }
	
}
// just check directory existence no creation
function rb_agency_just_checkdir($ProfileGallery){
			
		
	$finished = false;      
	$pos = 0;                 // we're not finished yet (we just started)
	while ( ! $finished ):                   // while not finished
	 $pos++;
	  $NewProfileGallery = $ProfileGallery ."-".$pos;   // output folder name
	  if ( ! is_dir(RBAGENCY_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
		  if(($pos-1) <=0){
			$ProfileGallery = $ProfileGallery;  // Set it to the new  thing
		}else{
			$ProfileGallery = $ProfileGallery ."-".($pos-1);  // Set it to the new  thing
		}
		$finished = true;                    // ...we are finished
	  endif;
	endwhile;
	
	return $ProfileGallery;

			
			
}
 
 
function rb_agency_set_directory($ProfileGallery){
	 
			   $finished = false;      
				$pos = 0;                 // we're not finished yet (we just started)
				while ( ! $finished ):                   // while not finished
				 $pos++;
				  $NewProfileGallery = $ProfileGallery ."-".$pos;   // output folder name
				  if ( ! is_dir(RBAGENCY_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
					  if(($pos-1) <=0){
						$ProfileGallery = $ProfileGallery;  // Set it to the new  thing
					}else{
						$ProfileGallery = $ProfileGallery ."-".($pos);  // Set it to the new  thing
					}
					$finished = true;                    // ...we are finished
				  endif;
				endwhile;
				
				return $ProfileGallery;
}


function rb_chmod_file_display($file){
	@chmod($file,0755);	
	return $file;
}



/*Naresh Kumar @ Matrix Infologics*/

class RBAgencyCSVXLSImpoterPlugin {
	var $log = array();
	/**
	 * give the absolute path to the file
	 *
	 * @return file path
	 */

	public function __construct()
	{
		define('WP_CSV_TO_DB_FOLDER', dirname(plugin_basename(__FILE__)));
		define('WP_CSV_TO_DB_URL', plugins_url('',__FILE__));
	}
   
	function csv_to_db_get_abs_path_from_src_file($src_file){
		if(preg_match("/http/",$src_file)){
			$path = parse_url($src_file, PHP_URL_PATH);
			$abs_path = $_SERVER['DOCUMENT_ROOT'].$path;
			$abs_path = realpath($abs_path);
			if(empty($abs_path)){
				$wpurl = get_bloginfo('wpurl');
				$abs_path = str_replace($wpurl,@ABSPATH,$src_file);
				$abs_path = realpath($abs_path);            
			}
		}
		else{
			$relative_path = $src_file;
			$abs_path = realpath($relative_path);
		}
		return $abs_path;
	}
	/**
	 * Match CSV Columns and Custom Field ID
	 *
	 * @return void
	 */

	function match_column_and_table(){
		global $wpdb;

		//have replace file_upload with WP-Content/Uploads/rb-agency/ path
		//create folder new upload path if not yet created
		$rb_upload_dr = wp_upload_dir(); 
		$new_upload_path = $rb_upload_dr['basedir'] . '/rb-agency/'; 
		//if (!is_dir($new_upload_path)) {
			//@mkdir($new_upload_path, 0755);
			//@chmod($new_upload_path, 0777);		
		//}
		
		$get_ext = pathinfo($_FILES['source_file']['name'], PATHINFO_EXTENSION);
		$target_path = $new_upload_path ;
		$target_path = $target_path . basename( $_FILES['source_file']['name']);
		
		if( strtolower($get_ext) == 'csv' )  /*If uploaded file is a CSV*/
		{
			if(move_uploaded_file($_FILES['source_file']['tmp_name'], $target_path))
			{
				$file_name = $target_path;
				update_option('wp_csvtodb_input_file_url', $file_name);
			}
			else
			{
				echo "error uploading the file";
			}
		}
		else    /*If uploaded file is excel*/
		{
			if( strtolower($get_ext) == 'xls' )
			{
				$inputFileType = 'Excel5';  /*XLS File type*/
			} 
			else
			{
				$inputFileType = 'Excel2007';  /*XLS File type*/  
			}
			
			include dirname( __FILE__ ).'/../ext/PHPExcel/IOFactory.php';
			$f_name = date('d_M_Y_h_i_s');
			
			move_uploaded_file($_FILES['source_file']['tmp_name'], $target_path);
			
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			//$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($target_path);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$t_file = date('d_M_Y_h_i_s');
			$csv_file = fopen($target_path.$t_file.'(1).csv','w');
			
			

		
			foreach ($sheetData as $key => $value) 
			{
				if(!empty( $value ))
				fputcsv($csv_file, $value);
			}
			fclose($csv_file);
			$file_name = $target_path.$t_file.'(1).csv';
			$clone = $file_name;
		}
		
		$file_path = $this->csv_to_db_get_abs_path_from_src_file($file_name);   
		$handle = fopen($file_path ,"r");       
		$header = fgetcsv($handle, 4096, ",");
		$total_header = count($header);
		$arr_headers = array();
		$arr_exists = array();
		
		if( strtolower($get_ext) == 'xls' ){
			for($a=0; $a<=$total_header; $a++){
			
				$row = $objPHPExcel->getActiveSheet()->getRowIterator($a)->current();
			   
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				foreach ($cellIterator as $cell) {
					$val = $cell->getValue();
					$value =  str_replace(" ","_",$cell->getValue());
					$value =  str_replace("||",",",$cell->getValue());
					if(!empty($val) && count($arr_headers) < $total_header && !in_array($val,$arr_exists)){
						array_push($arr_exists,$value);
						array_push($arr_headers ,$value);
					}

				}

				
			}
		}elseif( strtolower($get_ext) == 'csv' ){

				while($column = fgetcsv($handle, 4096, ','))
					{
						// This is a great trick, to get an associative row by combining the headrow with the content-rows.
						$column = @array_combine($header, $column);
					   
						foreach($column as $key => $val){
						   $val =  str_replace(" ","_",$key);
						   $val =  str_replace("||",",",$key);
						if(!empty($val) && count($arr_headers) < $total_header && !in_array($val,$arr_exists)){
							array_push($arr_exists,$val);
							array_push($arr_headers ,$val);
						}
						}
					   
					}
		}
		

		//$custom_header = $total_header - 17;//17 are the number of column for the personal profile table
		$custom_header = $total_header;
		if( $custom_header <= 0 ) return 0; /*If no custom field found*/

		/*Column head form*/
		echo "<div class=\"wrap\">";
		echo "<h2>Import ".strtoupper($get_ext)."</h2>";
		echo "<form  method=\"post\" action=\"\">";
		
		echo '<input type="hidden" value ="'.$custom_header.'" name="custom_header"/>
			  <input type="hidden" value ="'.$total_header.'" name="total_header"/>
			  <input type="hidden" value ="'.$file_path.'" name="file_path"/>
			  <input type="hidden" value ="'.(isset($clone)?$clone:'').'" name="clone"/>
			  <input type="hidden" value ="'.implode(",",$arr_headers).'" name="headers"/> ';
		$default = 1;
		$heads = 17;
		$t_head = $custom_header;
		$custom_fields = $wpdb->get_results("SELECT ProfileCustomID,ProfileCustomTitle FROM ". table_agency_customfields." ORDER BY ProfileCustomID ASC");
		echo "<table class=\"form-table\">";
		echo "<tbody>";
		
		/*for($i = 0; $i <= $t_head; $i++){
			if(!empty($header[$heads]) && $header[$heads] != ''){
				echo '<tr><th><label>'.$header[$heads].'</label></th>';
				echo '<td><select name = "select'.$default.'" id="select'.$default.'">';
				foreach ($custom_fields as $custom_fields_result) {
					$custom_field_id = intval($custom_fields_result->ProfileCustomID);
					$custom_field_title = $custom_fields_result->ProfileCustomTitle;
					//if($custom_field_id==$default){
					if($custom_field_title == $header[$heads])
						$is_default = ' selected="selected" ';
					}
					else{
						$is_default =''; 
					}
					echo '<option value="'.$custom_field_id.'"'.$is_default.'>'.$custom_field_title.'</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}*/
			$pos = 0;
			foreach ($arr_headers as $key ) {
				  if(substr($key, 0, 7) != "Profile"){
						echo '<tr><th><label>'.str_replace("_"," ",$key).'</label></th>';	
						echo '<td><select name = "select'.$pos.'">';
						foreach ($custom_fields as $custom_fields_result) {
							$custom_field_id = intval($custom_fields_result->ProfileCustomID);
							$custom_field_title = $custom_fields_result->ProfileCustomTitle;
							if($custom_field_title == str_replace("_"," ",$key)){
								$is_default = ' selected="selected" ';
							}
							else{
								$is_default =''; 
							}
							echo '<option value="'.$custom_field_id.'"'.$is_default.'>'.$custom_field_title.'</option>';
						}
						echo '</select>';
						echo '</td></tr>';	
						$pos++;							
				  }										
			}
			//$custom_header++;
			$heads++;
			$default++;
	//	}
		echo "<tbody>";
		echo "<table>";
		echo "<div style=\"clear:both\"></div>";
		echo "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary\" name=\"submit_importer_to_db\" value=\" Import Data \" /></p>";
		echo "</form>";
		echo "</div>";
		return 1;
	}
	/**
	 * Insert the data into the database
	 *
	 * @return void
	 */ 
	function import_to_db(){
		global $wpdb;
		$rb_agency_options_arr = get_option('rb_agency_options');

		$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?(int) $rb_agency_options_arr['rb_agency_option_profilenaming']:1;
		
		 // We already created a dynamic profile fields validation
		$p_table_fields = "ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast DESC,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive";
		$c_table_fields = "ProfileCustomID,ProfileID,ProfileCustomValue";       
	

		$arr_profile_fields = array(
				"ProfileContactDisplay",
				"ProfileContactNameFirst",
				"ProfileContactNameLast",
				"ProfileGender",
				"ProfileDateBirth",
				"ProfileContactEmail",
				"ProfileContactWebsite",
				"ProfileContactPhoneHome",
				"ProfileContactPhoneCell",
				"ProfileContactPhoneWork",
				"ProfileLocationStreet",
				"ProfileLocationCity",
				"ProfileLocationState",	
				"ProfileLocationZip",
				"ProfileLocationCountry",
				"ProfileType",
				"ProfileIsActive"
		);

		$custom_fields = $wpdb->get_results("SELECT ProfileCustomTitle FROM ". table_agency_customfields." ORDER BY ProfileCustomID ASC", ARRAY_A);
		foreach ($custom_fields  as $key => $value) {
			foreach( $value as $k => $v)
			array_push($arr_profile_fields, str_replace(" ","_",$v));
		}
		
		
		$arr_import_headers = explode(",",$_REQUEST["headers"]);

		// Check for invalid header profile field format
		foreach($arr_import_headers as $pf){
			 if(!in_array($pf,$arr_profile_fields)){
				unset($arr_profile_fields[$pf]);
				//die("<br/><div class='wrap' style='color:#FF0000'>Invalid Column name Format: ".$pf."</div>");
			 }
		}
	
		set_time_limit(0);
		$path_to_file = $_REQUEST['file_path'];
		$handle = fopen($path_to_file ,"r");
		fgets($handle);//read and ignore the first line
		
		$arr_import_data = array();
		
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			  $arr = array();
				  $a = 0;
			 
			 if(!empty($data[$a])){
				 while($a< count($arr_import_headers)){
								$arr[$arr_import_headers[$a]] = $data[$a];
								$a++;
				}
				array_push($arr_import_data, $arr);
			}
		}

		
	
		
		foreach ($arr_import_data  as &$vv) {
				
		  
			
										
										  if(!isset($vv["ProfileContactEmail"])){
												$domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);

												$vv["ProfileContactEmail"] = RBAgency_Common::generate_random_string(8)."@".$domain_name; 
										  }

										  if(empty($vv["ProfileContactDisplay"])){
											  $vv["ProfileContactDisplay"] = $vv["ProfileContactNameFirst"]." ".$vv["ProfileContactNameLast"];
										  }
										
				
									
										
											$queryGenderResult = $wpdb->get_row($wpdb->prepare("SELECT GenderID FROM ".table_agency_data_gender." WHERE GenderTitle ='%s'",$vv["ProfileGender"]), ARRAY_A);
											$ProfileContactDisplay = $wpdb->get_row($wpdb->prepare("SELECT ProfileID FROM ".table_agency_profile." WHERE ProfileContactEmail ='%s'",$vv["ProfileContactEmail"]), ARRAY_A);
											
											$ProfileContactNameFirst = $vv["ProfileContactNameFirst"];
											$ProfileContactNameLast = $vv["ProfileContactNameLast"];

											if(( ! is_plugin_active( 'rb-agency-interact/rb-agency-interact.php' )) || (!email_exists($vv["ProfileContactEmail"]) && is_plugin_active( 'rb-agency-interact/rb-agency-interact.php' ) ) ){
													// parse profile type
													if(strpos($vv["ProfileType"], "|") != -1){
														$ex = explode(" | ",trim($vv["ProfileType"]));
														$vv["ProfileType"] = trim(implode(",",$ex));
													}
													
													// check if country and state are numeric probably code
													// need to get ID's before inserting to DB
													if(!is_numeric($vv["ProfileLocationCountry"])){
														$query ="SELECT CountryID FROM ". table_agency_data_country ." WHERE LOWER(CountryCode) = '" . strtolower(trim($vv["ProfileLocationCountry"])) . "'";
														$result = $wpdb->get_row($query);
														if(count($result) > 0){
															$vv["ProfileLocationCountry"] = $result->CountryID;
														} else {
															// compare to title instead
															$query ="SELECT CountryID FROM ". table_agency_data_country ." WHERE LOWER(CountryTitle) = '" . strtolower(trim($vv["ProfileLocationCountry"])) . "'";
															$result = $wpdb->get_row($query);
															if(count($result) > 0){
																$vv["ProfileLocationCountry"] = $result->CountryID;
															} 
														}
													}
													if(!is_numeric($vv["ProfileLocationState"])){
														$query ="SELECT StateID FROM ". table_agency_data_state ." WHERE LOWER(StateCode) = '" . strtolower(trim($vv["ProfileLocationState"])) . "'";
														$result = $wpdb->get_row($query);
														if(count($result) > 0){
															$vv["ProfileLocationState"] = $result->StateID;
														} else {
															// compare to title instead
															$query ="SELECT StateID FROM ". table_agency_data_state ." WHERE LOWER(StateTitle) = '" . strtolower(trim($vv["ProfileLocationState"])) . "'";
															$result = $wpdb->get_row($query);
															if(count($result) > 0){
																$vv["ProfileLocationState"] = $result->StateID;
															} 
														}
													}

													$p_table_fields = "";
													$p_table_values = "";
													$pos = 0;
													foreach ($arr_import_headers as $key ) {
													   if(substr($key, 0, 7) == "Profile"){
															
															$p_table_fields  .= $key;
															
															if($key == "ProfileGender"){
																
																 $vv["ProfileGender"] = !empty($queryGenderResult['GenderID'])?$queryGenderResult['GenderID']:0;
																  $p_table_values  .= "".$vv[$key]."";
															
															}elseif ($key == "ProfileDateBirth") {
															
																 $vv["ProfileDateBirth"] = !empty($vv["ProfileDateBirth"]) ? date("Y-m-d",strtotime($vv["ProfileDateBirth"])):date("Y-m-d");
																 $p_table_values  .= "'".addslashes($vv[$key])."'";

																
															}else{
														
																 $p_table_values  .= "'".addslashes($vv[$key])."'";
														
															}

															if($pos < count($arr_import_headers)){
															   $p_table_fields  .= ",";
															   $p_table_values  .= ",";
															}
															$pos++;
														}
													}
													$p_table_fields = trim($p_table_fields, ","); 
													$p_table_values = trim($p_table_values, ","); 

													$add_to_p_table = "INSERT INTO ". table_agency_profile ." ($p_table_fields) VALUES ($p_table_values)";
													$wpdb->query($add_to_p_table);
													$last_inserted_id = $wpdb->insert_id ;
													

													if($last_inserted_id){
														$pos = 0;
														foreach ($arr_import_headers as $key ) {
															   if(substr($key, 0, 7) != "Profile"){
																	if(isset($_REQUEST['select'.$pos])){
																	   $select_id = esc_html($_REQUEST['select'.$pos]);
																	   if(strpos($vv[$key], ' ft ') !== FALSE){
																			$cal_height = 0;
																			$height = explode(' ', $vv[$key]);
																			$cal_height = ($height[0] * 12) + $height[2];
																			$vv[$key]  = $cal_height;
																			
																		}
																		
																		$add_to_c_table = $wpdb->prepare("INSERT INTO ". table_agency_customfield_mux ." ($c_table_fields) values(%d,%d,%s)",$select_id,$last_inserted_id,$vv[$key]);
																		$wpdb->query($add_to_c_table);
																		$pos++;
																	}
																}
														}
													}
													
											
															//$ProfileGalleryCurrent = generate_foldername($last_inserted_id, $vv['ProfileContactNameFirst'], $vv['ProfileContactNameLast'], $vv['ProfileContactDisplay']);
																if ($rb_agency_option_profilenaming == 0) {
																	$ProfileContactDisplay = $ProfileContactNameFirst . " " . $ProfileContactNameLast;
																} elseif ($rb_agency_option_profilenaming == 1) {
																	// If John-D already exists, make John-D-1
																	for ($i = 'a', $j = 1; $j <= 26; $i++, $j++) {
																		if (isset($ar) && in_array($i, $ar)){
																			$ProfileContactDisplay = $ProfileContactNameFirst . " " . $i .'-'. $j;
																		} else {
																			$ProfileContactDisplay = $ProfileContactNameFirst . " " . substr($ProfileContactNameLast, 0, 1);
																		}
																	}

																} elseif ($rb_agency_option_profilenaming == 2) {
																	$ProfileContactDisplay = $vv["ProfileContactDisplay"];
																} elseif ($rb_agency_option_profilenaming == 3) {
																	$ProfileContactDisplay = "ID-" . $ProfileID;
																} elseif ($rb_agency_option_profilenaming == 4) {
																	$ProfileContactDisplay = $ProfileContactNameFirst;
																} elseif ($rb_agency_option_profilenaming == 5) {
																	$ProfileContactDisplay = $ProfileContactNameLast;
																}
																$ProfileContactDisplay = RBAgency_Common::format_stripchars($ProfileContactDisplay);

																//create folder
																$ProfileGallery = rb_agency_createdir($ProfileContactDisplay);
																
																/*if(!empty($ProfileGallery)){
																	if($ProfileGallery != $ProfileGalleryCurrent){
																		// just rename the existing folder,
																		rename(RBAGENCY_UPLOADPATH. $ProfileGallery."/", RBAGENCY_UPLOADPATH. $ProfileGalleryCurrent."/");
																	}
																} else {
																	// actual folder creation
																	$dirURL = RBAGENCY_UPLOADPATH. $ProfileGalleryCurrent;
																	mkdir($dirURL, 0755); //700
																	chmod($dirURL, 0777);
																}*/
																
																//$ProfileGallery = check_dir_duplacation($ProfileGallery);
																// Then Update our DB
																$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGallery ."' WHERE ProfileID = \"". $last_inserted_id ."\"";
																$renamed = $wpdb->query($rename);
																//rb_agency_deldir($ProfileGallery);
																echo "<div class='wrap' style='color:#008000'><ul><li> User Name:- <a target='_blank' href='".admin_url("admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=".$last_inserted_id)."'>".$vv["ProfileContactDisplay"]."</a> & Email:- ".$vv["ProfileContactEmail"]."  <b>Successfully Imported Records</b></li></ul></div>";
											
															
											}else{
												 echo "<div class='wrap' style='color:#FF0000'><ul><li> User Name:- ".$vv["ProfileContactDisplay"]." & Email:- ".$vv["ProfileContactEmail"]."  <b>Successfully Not Imported. Email Already Used on site.</b></li></ul></div>";
											}
		}
			
		if(isset($_REQUEST['clone']) && $_REQUEST['clone'] != "") unlink($_REQUEST['clone']);
		
		
		
	}
	/**
	 * Upload Form
	 *
	 * @return void
	 */
	function form() {
		if(isset($_POST['read'])){
			$this->match_column_and_table();
		}
		else
		{
		if(isset($_POST['import_to_db'])){
			$this->import_to_db();
		}
?>
			<div style="clear:both"></div>
			<div class="wrap">
				<h2>Import CSV</h2>
				<form class="add:the-list: validate" method="post" enctype="multipart/form-data" action="">
					<p><label for="csv_import">Only CSV Files are accepted</label><br/></p>
					<input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
					<p class="submit"><input type="submit" class="button" name="read" value=" Read Column Headings " /></p>
				</form>
			</div>
<?php
		}
	}
	/**
	 * Plugin's interface
	 *
	 * @return void
	 */
	function print_messages() {
		if (!empty($this->log)) {
	// messages HTML {{{
?>
			<div class="wrap">
				<?php if (!empty($this->log['error'])): ?>
				<div class="error">
					<?php foreach ($this->log['error'] as $error): ?>
						<p><?php echo $error; ?></p>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<?php if (!empty($this->log['notice'])): ?>
				<div class="updated fade">
					<?php foreach ($this->log['notice'] as $notice): ?>
						<p><?php echo $notice; ?></p>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
<?php
	// end messages HTML }}}
			$this->log = array();
		}
	}
	/**
	 * Format Date
	 *
	 * @return Y-m-d H:i:s
	 */
	function parse_date($data) {
		$timestamp = strtotime($data);
		if (false === $timestamp) {
			return '';
		} else {
			return date('Y-m-d H:i:s', $timestamp);
		}
	}    
}
?>
</div>
