<div class="wrap">
	<?php 
	// Include Admin Menu
	include ("admin-include-menu.php");

	global $wpdb;

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
	
	if (function_exists(rb_agencyinteract_approvemembers)) {
		// RB Agency Interact Settings
		echo "<div class=\"boxlinkgroup\">\n";
		echo "  <h2>". __("Interactive Reporting", rb_agency_TEXTDOMAIN) . "</h2>\n";
		echo "  <p>". __("Run reports on membership and other usage.", rb_agency_TEXTDOMAIN) . "</p>\n";

		echo "    <div class=\"boxlink\">\n";
		echo "      <h3>". __("Recent Payments", rb_agency_TEXTDOMAIN) . "</h3>\n";
		echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=11\" title=\"". __("Recent Payments", rb_agency_TEXTDOMAIN) . "\">". __("Recent Payments", rb_agency_TEXTDOMAIN) . "</a><br />\n";
		echo "      <p>". __("Payments and membership renewals", rb_agency_TEXTDOMAIN) . "</p>\n";
		echo "    </div>\n";
	echo "</div>\n";
	echo "<hr />\n";
	}

	//
	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Initial Setup", rb_agency_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("If you are doing the initial instal of RB Agency you this section will help you get your data inplace", rb_agency_TEXTDOMAIN) . "</p>\n";
	echo "</div>\n";
	echo "<hr />\n";

	//
	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Data Integrity", rb_agency_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("Once your data is in place use the tools below to check your records", rb_agency_TEXTDOMAIN) . "</p>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Export Data", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=12\" title=\"". __("Export Data", rb_agency_TEXTDOMAIN) . "\">". __("Export Data", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Export databases", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Check for Abnormalities", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=5\" title=\"". __("Check for Abnormalities", rb_agency_TEXTDOMAIN) . "\">". __("Check for Abnormalities", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Search profile records for fields which seem invalid", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Rename Profile Folder Names", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=8\" title=\"". __("Rename Folders", rb_agency_TEXTDOMAIN) . "\">". __("Rename Folders", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("If you created model profiles while under 'First Last' or 'First L' and wish to switch to Display names or IDs you will have to rename the existing folders so that they do not have the models name in it", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Resize Photos", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=13\" title=\"". __("Resize Photos", rb_agency_TEXTDOMAIN) . "\">". __("Resize Photos", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Ensure files are not larger than approved size", rb_agency_TEXTDOMAIN) . ". (<a href=\"?page=rb_agency_settings&ConfigID=1\" title=\"". __("Configure Sizes", rb_agency_TEXTDOMAIN) . "\">". __("Configure Sizes", rb_agency_TEXTDOMAIN) . "</a>)</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Orphaned Profile Images", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=7\" title=\"". __("Remove Orphan Images From Database", rb_agency_TEXTDOMAIN) . "\">". __("Remove Orphan Images From Database", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("If for any reason you are getting blank images appear it may be because images were added in the database but have been removed via FTP.  Use this tool to remove all images in the databse which do not physically exist.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("FTP Blank Folder Check", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=1\" title=\"". __("Scan for Orphan Folders", rb_agency_TEXTDOMAIN) . "\">". __("Scan for Orphan Folders", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Check for any empty folders which do no have models assigned using this tool", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
	echo "</div>\n";
	echo "<hr />\n";

	//
	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Profile Management", rb_agency_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("The following reports will help you manage your profile information", rb_agency_TEXTDOMAIN) . "</p>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Inactive Users", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=6\" title=\"". __("Check Inactive Users", rb_agency_TEXTDOMAIN) . "\">". __("Check Inactive Users", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Find profiles who are currently set as inactive.  Use this tool to set multiple users to active", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Profile Search", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=rb_agency_search\" title=\"". __("Profile Search", rb_agency_TEXTDOMAIN) . "\">". __("Profile Search", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("You may search for profiles by using this tool", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Dummy Profiles with Sample Media", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=14\" title=\"". __("Generate Dummy Profiles with Media Content", rb_agency_TEXTDOMAIN) . "\">". __("Generate Dummy Profiles with Media Content", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("You may add dummy profiles by using this tool", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
	echo "    </div>\n";

        echo "<hr />\n";

	echo "<div class=\"boxlinkgroup\">\n";
	echo "  <h2>". __("Importing Records", rb_agency_TEXTDOMAIN) . "</h2>\n";
	echo "  <p>". __("The following tools will help import records.  DO NOT USE THESE TOOLS IF YOU ALREADY HAVE DATA LOADED", rb_agency_TEXTDOMAIN) . "</p>\n";
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 1", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=81\" title=\"". __("Export Now", rb_agency_TEXTDOMAIN) . "\">". __("Export Now", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Download this template and load your profile data into this file to import into the database.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
	
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 2", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=80\" title=\"". __("Import Data", rb_agency_TEXTDOMAIN) . "\">". __("Import Data", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Upload the model profiles into the database.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
/*
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 1", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <span class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=51\" title=\"". __("Download Excel Template", rb_agency_TEXTDOMAIN) . "\">". __("Download Excel Template", rb_agency_TEXTDOMAIN) . "</span><br />\n";
	echo "      <p>". __("Download this template and load your profile data into this file to import into the database.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 2", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <span class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=52\" title=\"". __("Upload to Database", rb_agency_TEXTDOMAIN) . "\">". __("Upload to Database", rb_agency_TEXTDOMAIN) . "</span><br />\n";
	echo "      <p>". __("Upload the model profiles into the database.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";
*/
	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 3", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=53\" title=\"". __("Generate folder names for profiles", rb_agency_TEXTDOMAIN) . "\">". __("Generate folder names for profiles", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Check that all profiles have folder names generated.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 4", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=2\" title=\"". __("Create folders for all profiles", rb_agency_TEXTDOMAIN) . "\">". __("Create folders for all profiles", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Check that all profiles have folders created on the server.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 5", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=3\" title=\"". __("Scan Folders for Images", rb_agency_TEXTDOMAIN) . "\">". __("Scan Folders for Images", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("First upload images directly to folders via FTP then use this tool to sync the images to the database.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

	echo "    <div class=\"boxlink\">\n";
	echo "      <h3>". __("Step 6", rb_agency_TEXTDOMAIN) . "</h3>\n";
	echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=4\" title=\"". __("Set Primary Profile Image", rb_agency_TEXTDOMAIN) . "\">". __("Set Primary Profile Image", rb_agency_TEXTDOMAIN) . "</a><br />\n";
	echo "      <p>". __("Identify which image is the primary image for each profile.", rb_agency_TEXTDOMAIN) . ".</p>\n";
	echo "    </div>\n";

        $active = get_option('active_plugins');
	foreach($active as $act){
		if(preg_match('/rb-agency-interact\.php/',$act)){
			echo "    <div class=\"boxlink\">\n";
                	echo "      <h3>". __("Step 7", rb_agency_TEXTDOMAIN) . "</h3>\n";
			echo "      <a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=99\" title=\"". __("Generate Logins / Passwords", rb_agency_TEXTDOMAIN) . "\">". __("Generate Logins / Passwords", rb_agency_TEXTDOMAIN) . "</a><br />\n";
			echo "      <p>". __("You may generate login and password for profiles which has been uploaded via importer, using this tool", rb_agency_TEXTDOMAIN) . ".</p>\n";
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
	
	$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
	$results1 = mysql_query($query1);
	$count1 = mysql_num_rows($results1);
	while ($data1 = mysql_fetch_array($results1)) {
		$dirURL = rb_agency_UPLOADPATH . $data1['ProfileGallery'];
		echo $dirURL;
		echo "<div>\n";
		if (is_dir($dirURL)) {
			//echo "  <span style='width: 240px; color: green;'>" . rb_agency_UPLOADDIR  . $dirURL . "/</span>\n";
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

	if($_REQUEST['action'] == 'generate') {
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
			$dirURL = rb_agency_UPLOADPATH. $data1['ProfileGallery'];
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

		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
			$dirURL = rb_agency_UPLOADPATH . $data1['ProfileGallery'];
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
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
			$ProfileID = $data1['ProfileID'];

			$ProfileGallery = $data1['ProfileGallery'];

			// Create the right folder name for the profile
			$ProfileGalleryCurrent = generate_foldername($data1['ProfileID'], $data1['ProfileContactNameFirst'], $data1['ProfileContactNameLast'], $data1['ProfileContactDisplay']);
			
			if(!empty($ProfileGallery)){
				if($ProfileGallery != $ProfileGalleryCurrent){
					// just rename the existing folder,
					rename(rb_agency_UPLOADPATH. $ProfileGallery."/", rb_agency_UPLOADPATH. $ProfileGalleryCurrent."/");
				}
			} else {
				// actual folder creation
				$dirURL = rb_agency_UPLOADPATH. $ProfileGalleryCurrent;
				mkdir($dirURL, 0755); //700
				chmod($dirURL, 0777);
			}

			// Then Update our DB
			$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryCurrent ."' WHERE ProfileID = \"". $ProfileID ."\"";
			$renamed = mysql_query($rename);

			echo "  <div id=\"message\" class=\"updated highlight\">Folder name <strong>/" . $ProfileGalleryCurrent . "/</strong> has been set for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
		}
	} else {
		
		/*
		 * Place sql here to get
		 * generated total count for folders
		 */
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		
		echo "<h3>". __("Generate folder names for profiles", rb_agency_TEXTDOMAIN) . "</h3>\n";
		echo "<p>". __("Check that all profiles have folder names generated.", rb_agency_TEXTDOMAIN) . "</p>\n";
		echo "<p>". __("Total Number of Folders Created: <strong>".$count1."</strong>", rb_agency_TEXTDOMAIN) . "</p>\n";

			while ($data1 = mysql_fetch_array($results1)) {
				$ProfileGallery = $data1['ProfileGallery'];

				// Create the right folder name for the profile
				$ProfileGalleryCurrent = generate_foldername($data1['ProfileID'], $data1['ProfileContactNameFirst'], $data1['ProfileContactNameLast'], $data1['ProfileContactDisplay']);
				echo "<div>\n";
				if (isset($ProfileGallery) && !empty($ProfileGallery)) {
					if ($ProfileGallery == $ProfileGalleryCurrent) {
						echo "  <span style='width: 240px; color: green;'>". $ProfileGallery ."</span>\n";
					} else {
						// Add Profiles to Array to Create later
						$throw_error = true;
						echo "  <span style='width: 240px; color: red;'>". $ProfileGallery ." should be <strong>". $ProfileGalleryCurrent ."</strong></span>\n";
					}
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

	$query3 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
	$results3 = mysql_query($query3);
	$count3 = mysql_num_rows($results3);
	while ($data3 = mysql_fetch_array($results3)) {
		$dirURL = rb_agency_UPLOADPATH . $data3['ProfileGallery'];
		if (is_dir($dirURL)) {  // Does folder exist?
			echo "<div style=\"background-color: lightYellow; \">\n<h3>". $data3['ProfileContactNameFirst'] ." ". $data3['ProfileContactNameLast'] ."</h3>\n";
			if ($handle = opendir($dirURL)) {  //  Open seasame 
				while (false !== ($file = readdir($handle))) {
					if (strtolower($file) == "thumbs.db"  || strtolower($file) == "thumbsdb.jpg" || strtolower($file) == "thumbsdbjpg.jpg" || strtolower($file) == "thumbsdbjpgjpg.jpg") {
						if (!unlink($dirURL ."/". $file)) {
						  echo ("Error deleting $file");
						} else {
						  echo ("Deleted $file");
						}
					} elseif ($file != "." && $file != "..") {
						$new_file = RBAgency_Common::format_stripchars($file);
						rename($dirURL ."/". $file, $dirURL ."/". $new_file);
						
						$file_ext = rb_agency_filenameextension($new_file);
						if ($file_ext == "jpg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "bmp") {
						
							$query3a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileMediaURL = \"". $new_file ."\"";
							$results3a = mysql_query($query3a);
							$count3a = mysql_num_rows($results3a);
							if ($count3a < 1) {
								if($_GET['action'] == "add") {
								$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $data3['ProfileID'] ."','Image','". $data3['ProfileContactNameFirst'] ."-". $new_file ."','". $new_file ."')");
								$actionText = " and <span style=\"color: green;\">added to database</span>";
								} else {
								$actionText = " and <strong>PENDING ADDITION TO DATABASE</strong>";
								}
							} else {
								$actionText = " and exists in database";
							}
						} else {
								$actionText = " is <span style=\"color: red;\">NOT an allowed file type</span> ";
						}

					echo "<div style=\"border-color: #E6DB55;\">File: ". $file ." has been renamed <strong>". $new_file ."</strong>". $actionText ."</div>\n";
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
	echo "<a href='?page=rb_agency_reports&ConfigID=3&action=add'>Add All Pending Changes</a>";



} // End 3
elseif ($ConfigID == 4) {
//////////////////////////////////////////////////////////////////////////////////// 

	global $wpdb;

	$stepSize = 100;
	$query4t = "SELECT ProfileID FROM ". table_agency_profile ."";
	$results4t = mysql_query($query4t);
	$count4total = mysql_num_rows($results4t);
	
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
		   $pageString .= " <a href=\"?page=rb_agency_reports&ConfigID=4&Step={$i}$queryVars\">Page $i</a>";
		   $pageString .= $i != $totalPages ? " | " : "";
		 }
	   }
	echo $pageString;

	if($_POST['action'] == 'update')
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
			
			$query4 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst LIMIT $step,$stepSize"; //LIMIT $step,100
			$results4 = mysql_query($query4);
			$count4 = mysql_num_rows($results4);
			while ($data4 = mysql_fetch_array($results4)) {
				$dirURL = rb_agency_UPLOADDIR . $data4['ProfileGallery'];
				$profileID = $data4['ProfileID'];

				$query4b = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = $profileID AND ProfileMediaType = 'Image' AND ProfileMediaPrimary = 1";
				$results4b = mysql_query($query4b);
				$count4b = mysql_num_rows($results4b);
				//echo $query4b ."<br />". $count4b ."<hr />";
				if ($count4b < 1) {

					echo "<div style=\"background-color: lightYellow; \">\n<h3><a href='?page=rb_agency_profiles&action=editRecord&ProfileID=$profileID' target='_blank'>". $data4['ProfileContactNameFirst'] ." ". $data4['ProfileContactNameLast'] ."</a></h3>\n";
			
					$query4a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = $profileID AND ProfileMediaType = 'Image'";
					$results4a = mysql_query($query4a);
					$count4a = mysql_num_rows($results4a);
					if ($count4a < 1) {
						echo "This profile has no images loaded.";
					} else {
						while ($data4a = mysql_fetch_array($results4a)) {
							echo "<div style=\"width: 150px; float: left; height: 200px; overflow: hidden; margin: 10px; \"><input type=\"radio\" name=\"". $data4a['ProfileID'] ."\" value=\"". $data4a['ProfileMediaID'] ."\" />&nbsp;&nbsp;Select Primary<br /><img src=\"". $dirURL ."/". $data4a['ProfileMediaURL'] ."\" style=\"width: 150px;\" /></div>\n";
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
	
	$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
	$results1 = mysql_query($query1);
	$count1 = mysql_num_rows($results1);
	while ($data1 = mysql_fetch_array($results1)) {
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
	
	if($_POST['action'] == 'update') {
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
	
	$query6 = "SELECT * FROM ". table_agency_profile ." WHERE ProfileIsActive = '0' ORDER BY ProfileContactNameFirst";
	$results6 = mysql_query($query6);
	$count6 = mysql_num_rows($results6);
	while ($data6 = mysql_fetch_array($results6)) {
		echo "<div><input type=\"checkbox\" name=\"". $data6['ProfileID'] ."\" value=\"". $data6['ProfileID'] ."\" class=\"button-primary\" />". $data6['ProfileContactNameFirst'] ." ". $data6['ProfileContactNameLast'] ."</div>\n";
	}
	if ($count6 < 1) {
		echo "There are currently no inactive profile records.";
	}
	?>
	<input type="hidden" value="update" name="action" />
	<input type="submit" value="Submit" class="button-primary" name="Update" />
	</form>
	<?php
} // End 6



elseif ($ConfigID == 7) {
//////////////////////////////////////////////////////////////////////////////////// 

	global $wpdb;
	?>
	<h3>Remove Orphans from Database</h3>
	<?php
	$query7 = "SELECT ProfileID, ProfileGallery FROM ". table_agency_profile ."";
	$results7 = mysql_query($query7);
	$count7 = mysql_num_rows($results7);
	while ($data7 = mysql_fetch_array($results7)) {
		$ProfileID = $data7['ProfileID'];
		$dirURL = rb_agency_UPLOADPATH . $data7['ProfileGallery'];
		if (is_dir(".." . $dirURL)) {  // Does folder exist?
			echo "<div style=\"background-color: lightYellow; margin: 10px; \">\n";
			if ($handle = opendir(".." . $dirURL)) {  //  Open seasame 
			
				$query7a = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID = ". $ProfileID ." AND ProfileMediaType = 'Image'";
				$results7a = mysql_query($query7a);
				$count7a = mysql_num_rows($results7a);
				while ($data7a = mysql_fetch_array($results7a)) {
					$fileCheck = rb_agency_UPLOADPATH . $data7['ProfileGallery'] ."/". $data7a['ProfileMediaURL'];
					if (file_exists($fileCheck)) {
					echo "<div style=\"color: green;\">". $fileCheck ."</div>\n";
					} else {
						if($_GET['action'] == "delete") {
							$ProfileMediaID = $data7a['ProfileMediaID'];
							// Remove Orphans
							$query7b = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileMediaID = \"". $ProfileMediaID ."\"";
							$results7b = mysql_query($query7b);
							echo $query7b;
							
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

		echo "Current Naming Convention:";
		if ($rb_agency_option_profilenaming == 0) {
			echo "<h2>First Last</h2>";
		} elseif ($rb_agency_option_profilenaming == 1) {
			echo "<h2>First L</h2>";
		} elseif ($rb_agency_option_profilenaming == 2) {
			echo "<h2>Display Name</h2>";
		} elseif ($rb_agency_option_profilenaming == 3) {
			echo "<h2>Autogenerated ID</h2>";
		}


	if($_REQUEST['action'] == 'generate') {

		// LETS DO IT!
		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		$arrayReservedFoldername = array();
		$pos = 0;
		while ($data1 = mysql_fetch_array($results1)) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
				if ($rb_agency_option_profilenaming == 0) {
					$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
				} elseif ($rb_agency_option_profilenaming == 2) {
					$ProfileGalleryFixed = $ProfileContactDisplay;
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileGalleryFixed = "ID ";
				}
				$ProfileGalleryFixed = RBAgency_Common::format_stripchars($ProfileGalleryFixed); 
			
			  if(in_array($ProfileGallery,$arrayReservedFoldername)){
				$ProfileGalleryFixed = rb_agency_set_directory($ProfileGalleryFixed);
				$arrayReservedFoldername[$pos] = $ProfileGalleryFixed;
			 }
				
				if ($ProfileGallery == $ProfileGalleryFixed ) {
								$ProfileGalleryFixed = $ProfileGallery;
				} else {
							$ProfileGalleryFixed  = rb_agency_set_directory($ProfileGalleryFixed);
				}

			if ($ProfileGallery == $ProfileGalleryFixed) {
			} else {
				// Folder Exist?
				if (is_dir(rb_agency_UPLOADPATH ."/". $ProfileGalleryFixed)) {
					$finished = false;                       // we're not finished yet (we just started)
					while ( ! $finished ):                   // while not finished
						$ProfileGalleryFixed = $ProfileGalleryFixed .$ProfileID;   // output folder name
						if ( ! is_dir(rb_agency_UPLOADPATH ."/". $ProfileGalleryFixed) ):        // if folder DOES NOT exist...
							rename(rb_agency_UPLOADPATH ."/". $ProfileGallery, rb_agency_UPLOADPATH ."/". $ProfileGalleryFixed);

							if (is_dir(rb_agency_UPLOADPATH ."/". $ProfileGalleryFixed)) {
								$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."' WHERE ProfileID = \"". $ProfileID ."\"";
								$renamed = mysql_query($rename);
								echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
							} else {
								echo "  <div id=\"message\" class=\"error\">Error renaming <strong>/" . $ProfileGalleryFixed . "/</strong> for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
							}

							$finished = true; // ...we are finished
						endif;
					endwhile;

				} else {
					
					// Create Folders
					rename(rb_agency_UPLOADPATH ."/". $ProfileGallery, rb_agency_UPLOADPATH ."/". $ProfileGalleryFixed);
					if (is_dir(rb_agency_UPLOADPATH ."/". $ProfileGalleryFixed) ) { // if folder DOES NOT exist...
						$rename = "UPDATE " . table_agency_profile . " SET ProfileGallery = '". $ProfileGalleryFixed ."' WHERE ProfileID = \"". $ProfileID ."\"";
						$renamed = mysql_query($rename);
						echo "  <div id=\"message\" class=\"updated highlight\">Folder <strong>/" . $ProfileGalleryFixed . "/</strong> has been renamed for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					} else {
						echo "  <div id=\"message\" class=\"error\">Error renaming <strong>/" . $ProfileGalleryFixed . "/</strong> for <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=" . $data1['ProfileID'] . "'>" . $data1['ProfileContactNameFirst'] . " " . $data1['ProfileContactNameLast'] . "</a></div>\n";
					}
				}
			}
			$pos++;
		}

	} else {

		echo "<h3>Hide Profile Identity</h3>\n";
		echo "<p>If you created model profiles while under \"First Last\" or \"First L\" and wish to switch to Display names or IDs you will have to rename the existing folders so that they do not have the models name in it.</p>\n";
	
	
		/*
		echo "<br />";
		var_dump(is_dir(rb_agency_UPLOADPATH . "/john-doe/"));
		echo "<br />";
		echo rb_agency_UPLOADREL;
		// Open a known directory, and proceed to read its contents
		$dir = rb_agency_UPLOADPATH;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
				}
				closedir($dh);
			}
		}
		*/

		$query1 = "SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		
		$pos = 0;
		$pos_suggested = 0;
		$arrayReservedFoldername = array();
		while ($data1 = mysql_fetch_array($results1)) {
			$ProfileID				=$data1["ProfileID"];
			$ProfileContactNameFirst=$data1["ProfileContactNameFirst"];
			$ProfileContactNameLast	=$data1["ProfileContactNameLast"];
			$ProfileContactDisplay	=$data1["ProfileContactDisplay"];
			$ProfileGallery			=$data1["ProfileGallery"];
			$arrayAllFolderNames[$pos] = $ProfileGallery;
			$pos++; // array position start = 0	
			
			if ($rb_agency_option_profilenaming == 0) {
				$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
			} elseif ($rb_agency_option_profilenaming == 1) {
				$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
			} elseif ($rb_agency_option_profilenaming == 2) {
				$ProfileGalleryFixed = $ProfileContactDisplay;
			} elseif ($rb_agency_option_profilenaming == 3) {
				$ProfileGalleryFixed = "ID ". $ProfileID;
			}
			$ProfileGalleryFixed = RBAgency_Common::format_stripchars($ProfileGalleryFixed); 
		
			if(in_array($ProfileGallery,$arrayReservedFoldername)){
				$ProfileGalleryFixed = rb_agency_just_checkdir($ProfileGalleryFixed);
				$arrayReservedFoldername[$pos] = $ProfileGalleryFixed;
			}
			echo "<div>\n";
			// Check for duplicate
			$query_duplicate = "SELECT ProfileGallery, count(ProfileGallery) as cnt FROM ". table_agency_profile ." WHERE ProfileGallery='".$ProfileGallery."' GROUP BY ProfileGallery   HAVING cnt > 1";
			$rs = mysql_query($query_duplicate);
			$count  = mysql_num_rows($rs);
			if($count > 0){
					
				// Add Profiles to Array to Create later
				$throw_error = true;
				$ProfileGalleryFixed =  rb_agency_set_directory($ProfileGalleryFixed);
				echo "  <span style='width: 240px; color: red;'>". rb_agency_UPLOADDIR  . $ProfileGallery ."/</span>\n";
				echo "  <strong>Profile <a href='admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=". $data1['ProfileID'] ."'>". $data1['ProfileContactNameFirst'] ." ". $data1['ProfileContactNameLast'] ."</a></strong>\n";
				echo "  Should be renamed to /<span style='width: 240px; color: red;'>". $ProfileGalleryFixed ."/</span>\n";

			} elseif ($ProfileGallery == $ProfileGalleryFixed ) {
				echo "  <span style='width: 240px; color: green;'>". rb_agency_UPLOADDIR  . $ProfileGallery ."/</span>\n";
			}
			$pos++;
		}//endwhile
			
			echo "</div>\n";
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

	echo "<h2>". __("Resize Images", rb_agency_TEXTDOMAIN) . "</h2>\n";
	
	/*********** Max Size *************************************/
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_agencyimagemaxheight 	= $rb_agency_options_arr['rb_agency_option_agencyimagemaxheight'];
			if (empty($rb_agency_option_agencyimagemaxheight) || $rb_agency_option_agencyimagemaxheight < 500) { $rb_agency_option_agencyimagemaxheight = 800; }
		$rb_agency_option_agencyimagemaxwidth 	= $rb_agency_options_arr['rb_agency_option_agencyimagemaxwidth'];
			if (empty($rb_agency_option_agencyimagemaxwidth) || $rb_agency_option_agencyimagemaxwidth < 500) { $rb_agency_option_agencyimagemaxwidth = 1000; }
	
	/*********** Step Size *************************************/
	$stepSize = 20;
	$query4t = "SELECT ProfileID FROM ". table_agency_profile ."";
	$results4t = mysql_query($query4t);
	$count4total = mysql_num_rows($results4t);
	
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
				$pageString .= " <a href=\"?page=rb_agency_reports&ConfigID=13&Step={$i}$queryVars\">Page $i</a>";
				$pageString .= $i != $totalPages ? " | " : "";
			}
		}
	echo "<div>". $pageString ."</div>\n";


	/*********** Query Database *************************************/
	
		$query = "SELECT ProfileID, ProfileContactNameFirst, ProfileContactNameLast, ProfileGallery FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst LIMIT $step,$stepSize"; //LIMIT $step,100
		$results = mysql_query($query);
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			
			echo "<div>\n";
			echo "<h3>". $data['ProfileContactNameFirst'] ." ". $data['ProfileContactNameLast'] ."</h3>\n";
			$ProfileGallery = $data['ProfileGallery'];
			$ProfileID = $data['ProfileID'];


			$queryImg = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID =  $ProfileID AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC, ProfileMediaID DESC";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			echo "<div><strong>$countImg total</strong></div>\n";
			while ($dataImg = mysql_fetch_array($resultsImg)) {
				$filename = rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
				
				$image = new rb_agency_image();
				$image->load($filename);
				echo "<div style=\"float: left; width: 110px;\">\n";
				
				if ($image->orientation() == "landscape") {
					if ($image->getWidth() > $rb_agency_option_agencyimagemaxwidth) {
						$image->resizeToWidth($rb_agency_option_agencyimagemaxwidth);
						echo "RESIZED LANDSCAPE<br />\n";
						$image->save(rb_agency_UPLOADPATH . $ProfileGallery ."/". $dataImg['ProfileMediaURL']);
					}
				} else {
					if ($image->getHeight() > $rb_agency_option_agencyimagemaxheight) {
						$image->resizeToHeight($rb_agency_option_agencyimagemaxheight);
						echo "RESIZED PORTRAIT<br />\n";
						$image->save(rb_agency_UPLOADPATH . $ProfileGallery ."/". $dataImg['ProfileMediaURL']);
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
		echo "<form action=\"".rb_agency_BASEDIR."view/exportDatabase.php\" method=\"post\">";
		echo '<input type="submit" class="button-primary" value='. __('"Export Database"', rb_agency_TEXTDOMAIN).'>';
		echo '</form>';

		/*echo "<h2>". __("Export Database", rb_agency_TEXTDOMAIN) . "</h2>\n";
		
			echo "<a href=\"". rb_agency_BASEDIR ."view/exportDatabase.php\">Export Database</a>\n";
		*/
}
elseif ($ConfigID == 81) 
{
	echo "<h2>". __(" Export Database", rb_agency_TEXTDOMAIN) . "</h2>\n";
	
	echo " <form action=\"".rb_agency_BASEDIR."view/export-Profile-Database.php\" method=\"post\">";
	echo "      <select name=\"file_type\">";
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
	
		$custom_fields_rb_agency = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0  ORDER BY ProfileCustomOrder", ARRAY_A));
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
	else if($_POST['submit_importer_to_db'])
	{
		$obj_csv->import_to_db();   /*Store profile data*/
		$form_display_flag = true;
	}
	
	if( $form_display_flag == true )
	{
		echo "<h2>". __("Import CSV / XLS", rb_agency_TEXTDOMAIN) . "</h2>\n";
		
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
		$sample_url = rb_agency_BASEPATH."view/samples"; // Samples' folder

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

		foreach($userProfileNames as $ProfileContact):
			$ProfileContactDisplay = "";
			$ProfileGallery = "";

				if (empty($ProfileContactDisplay)) {  // Probably a new record... 
				if ($rb_agency_option_profilenaming == 0) {
					$ProfileContactDisplay = $ProfileContact[1] . " ". $ProfileContact[0];
				} elseif ($rb_agency_option_profilenaming == 1) {
					$ProfileContactDisplay = $ProfileContact[1] . " ". substr($ProfileContact[0], 0, 1);
				} elseif ($rb_agency_option_profilenaming == 2) {
					$error .= "<b><i>". __(LabelSingular ." must have a display name identified", rb_agency_TEXTDOMAIN) . ".</i></b><br>";
					$have_error = true;
				} elseif ($rb_agency_option_profilenaming == 3) {
					$ProfileContactDisplay = "ID ". $ProfileID;
				}
			}

			if (empty($ProfileGallery)) {  // Probably a new record... 
				$ProfileGallery = RBAgency_Common::format_stripchars($ProfileContactDisplay); 
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
			
			echo "<h2>". __("Removing Dummy Profiles...", rb_agency_TEXTDOMAIN) . "</h2>\n";
			echo "<br/>Succesfully removed...";
			echo "<br/>";

			$trackDummies = explode(",",$_SESSION["trackDummies_text"]);

			// Track dummies to pull out
			foreach($trackDummies as $gallery){
				echo "<strong>/".$gallery."/</strong> linked directory removed.<br/>";
				$getGallary="SELECT ProfileID,ProfileGallery FROM ".table_agency_profile ." WHERE ProfileGallery = '".$gallery."' ";
				$qID = mysql_query($getGallary) or die("1".mysql_error());
				$fID = mysql_fetch_assoc($qID);
				$pSql="DELETE FROM ".table_agency_profile ." WHERE ProfileID = '".$fID["ProfileID"]."' ";
				mysql_query($pSql) or die("2".mysql_error());
				$pmSql="DELETE FROM ".table_agency_profile_media ." WHERE ProfileID = '".$fID["ProfileID"]."' ";
				mysql_query($pmSql) or die("3".mysql_error());
				uninstall_dummy_profile($gallery);
			}
			unset($_SESSION["trackDummies_text"]); 
		}
		
		
		if(isset($_GET["settings-updated"]) && !empty($rb_agency_dummy_options_installdummy)){	
			echo "<h2>". __("Installing Dummies...", rb_agency_TEXTDOMAIN) . "</h2>\n";
			echo "<br/>";  
			echo "Succesfully created ".count($userProfileNames)." dummy profiles..<br/>";

			foreach($userProfileNames as $ProfileContact){

				$ProfileContactDisplay = "";
				$ProfileGallery = "";
				$userCategory = "";
				$userGender ="";
				$queryGender = mysql_query("SELECT * FROM ".table_agency_data_gender."  ");
				$userGender = mysql_fetch_assoc($queryGender);
				while ($row = mysql_fetch_array($queryGender)) {
					if($ProfileContact[2]==$row['GenderTitle']){
						$userGender["GenderID"]=$row['GenderID']; 
					}
					  
				}

				$queryCategory = mysql_query("SELECT * FROM ".table_agency_data_type."  WHERE DataTypeID >= (SELECT FLOOR( MAX(DataTypeID) * RAND()) FROM ".table_agency_data_type." ) ORDER BY  RAND() LIMIT 1");
				$userCategory = mysql_fetch_assoc($queryCategory);
				mysql_free_result($queryGender);
				mysql_free_result($queryCategory);

				echo $ProfileContact[0]." ".$ProfileContact[1]."<br/>";

				if (empty($ProfileContactDisplay)) {  // Probably a new record... 
					if ($rb_agency_option_profilenaming == 0) {
						$ProfileContactDisplay = $ProfileContact[1] . " ". $ProfileContact[0];
					} elseif ($rb_agency_option_profilenaming == 1) {
						$ProfileContactDisplay = $ProfileContact[1] . " ". substr($ProfileContact[0], 0, 1);
					} elseif ($rb_agency_option_profilenaming == 2) {
						$error .= "<b><i>". __(LabelSingular ." must have a display name identified", rb_agency_TEXTDOMAIN) . ".</i></b><br>";
						$have_error = true;
					} elseif ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID ". $ProfileID;
					}
				}

				if (empty($ProfileGallery)) {  // Probably a new record... 
					$ProfileGallery = RBAgency_Common::format_stripchars($ProfileContactDisplay); 
				}

				$ProfileGallery = rb_agency_createdir($ProfileGallery);

				// Select city and state
				$queryCountry = mysql_query("SELECT * FROM ".table_agency_data_country." ORDER BY RAND( ) ASC LIMIT 1");
				$userCountry = mysql_fetch_assoc($queryCountry);

				$queryState = mysql_query("SELECT * FROM ".table_agency_data_state."  where CountryID = ".$userCountry['CountryID']." ORDER BY RAND( ) ASC LIMIT 1");
				$userState = mysql_fetch_assoc($queryState);
								
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
				
				$results = $wpdb->query($insert) or die(mysql_error());
				$ProfileID = $wpdb->insert_id;

				// Inserting Custom Field 
				$queryCustom = mysql_query("SELECT * FROM ".table_agency_customfields."  ");
				while ($rowCustom = mysql_fetch_array($queryCustom)) {
					if($rowCustom['ProfileCustomType']==3){
						 $customValueArray = explode("|", $rowCustom['ProfileCustomOptions']);
						 $customValue= $customValueArray[1];
					}elseif($rowCustom['ProfileCustomType']==7 || $rowCustom['ProfileCustomType']==1){
						$customValue = rand(0,15) ; 
					}elseif($rowCustom['ProfileCustomType']==4){
						$customValue = "Dummy ".$rowCustom['ProfileCustomTitle']  ; 
					}
					$results = mysql_query("INSERT INTO " . table_agency_customfield_mux . " ( ProfileCustomID, ProfileID, ProfileCustomValue) VALUES ('". $rowCustom['ProfileCustomID'] ."','". $ProfileID ."','". $customValue ."')");
				}
				

				

				$rand = rand(0,1); // 2
				$randTo6 = rand(0,5); //6
				$randTo4 = rand(0,4); // 5
				$randTo8 = rand(0,8); // 5

				for($a=0; $a<=3; $a++){

					// Copy images
					if($a<=3){
						if ($ProfileContact[2]=='Male') {
							if(!copy(rb_chmod_file_display($sample_url."/".$userMediaImagesM[$a]),rb_chmod_file_display(rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$a]))){
								echo $sample_url."/".$userMediaImagesM[$a]."<br/>".rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$a];
								echo "<br/>";
								die("Failed to Copy files... <br/>".phpinfo());
							}
							$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Image','". $userMediaImagesM[$a] ."','". $userMediaImagesM[$a] ."')");

						} else {
							if(!copy(rb_chmod_file_display($sample_url."/".$userMediaImagesF[$a]),rb_chmod_file_display(rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$a]))){
								echo $sample_url."/".$userMediaImagesF[$a]."<br/>".rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$a];
								echo "<br/>";
								die("Failed to Copy files... <br/>".phpinfo());
							}
							$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Image','". $userMediaImagesF[$a] ."','". $userMediaImagesF[$a] ."')");
						}
					}
					if($a<=3){
						if($userMediaVideoType[$a]!=""){
							$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $userMediaVideoType[$a]."','".rb_agency_get_VideoFromObject($userMediaVideo[$randTo6]) ."','". rb_agency_get_VideoFromObject($userMediaVideo[$randTo6])  ."')");
						}
					}
					if($a==1){

						if ($ProfileContact[2]=='Male') {
						// Male
						copy(rb_chmod_file_display($sample_url."/".$userMediaImagesM[$randTo8]),rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesM[$randTo8]);
						$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL,ProfileMediaPrimary) VALUES ('". $ProfileID ."','Image','". $userMediaImagesM[$randTo8]."','". $userMediaImagesM[$randTo8] ."',1)") or die(mysql_error());
						
						} else {
						// Female
						copy(rb_chmod_file_display($sample_url."/".$userMediaImagesF[$randTo8]),rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaImagesF[$randTo8]);
						$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL,ProfileMediaPrimary) VALUES ('". $ProfileID ."','Image','". $userMediaImagesF[$randTo8]."','". $userMediaImagesF[$randTo8] ."',1)") or die(mysql_error());
						}

						// Any Gender
						copy(rb_chmod_file_display($sample_url."/".$userMediaHeadshot[$rand]),rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaHeadshot[$rand]);
						$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Headshot','". $userMediaHeadshot[$rand]."','". $userMediaHeadshot[$rand] ."')");

						copy(rb_chmod_file_display($sample_url."/".$userMediaVoicedemo[0]),rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaVoicedemo[0]);
						$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','VoiceDemo','". $userMediaVoicedemo[0] ."','".  $userMediaVoicedemo[0] ."')");

						copy(rb_chmod_file_display($sample_url."/".$userMediaCompcard[0]),rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaCompcard[0]);
						$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','CompCard','".$userMediaCompcard[0] ."','". $userMediaCompcard[0]."')");

						copy(rb_chmod_file_display($sample_url."/".$userMediaResume[$rand]),rb_agency_UPLOADPATH . $ProfileGallery ."/".$userMediaResume[$rand]);
						$results = mysql_query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','Resume','". $userMediaResume[$rand]."','".$userMediaResume[$rand]."')");

					}

				}
			} // End foreach

			unset($_SESSION["trackDummies_text"]);
		} // if option is empty

		if (isset($_GET["a"])){
			unset($_SESSION["trackDummies_text"]); 
			uninstall_allprofile();
		}



} //END $ConfigID == 14
elseif($ConfigID == '99'){
	
        $active = get_option('active_plugins');
	$found = false;
	foreach($active as $act){
		if(preg_match('/rb-agency-interact\.php/',$act)){
			echo "<h2>". __("Generate Login / Passwords", rb_agency_TEXTDOMAIN) . "</h2>\n";
			rb_display_profile_list();
			$found = true;
		}
	}
	if(!$found){
		echo "<h3>". __("Activate/Install Rb Agency Interact plugin to use this feature", rb_agency_TEXTDOMAIN) . "</h3>\n";
	}
	
}





/******************************************************************************************/



function uninstall_dummy_profile($profile){
	
	
	 $dir  = rb_agency_UPLOADPATH .$profile;  
	 foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			 unlink($dir.DIRECTORY_SEPARATOR.$item);
	 }
	 rmdir($dir);
}

function uninstall_allprofile(){
	
	  mysql_query("TRUNCATE TABLE ".table_agency_profile ."");
	  mysql_query("TRUNCATE TABLE ".table_agency_profile_media ."");
	 $dir  = rb_agency_UPLOADPATH."/";  
	 foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
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
	  if ( ! is_dir(rb_agency_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
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
				  if ( ! is_dir(rb_agency_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
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
				$abs_path = str_replace($wpurl,ABSPATH,$src_file);
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
		if (!is_dir($new_upload_path)) {
			@mkdir($new_upload_path, 0755);
			@chmod($new_upload_path, 0777);		
		}
		
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
			include WP_CONTENT_DIR.'/plugins/rb-agency/ext/PHPExcel/IOFactory.php';
			$f_name = date('d_M_Y_h_i_s');
			
			move_uploaded_file($_FILES['source_file']['tmp_name'], $target_path);
			
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($target_path);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$t_file = date('d_M_Y_h_i_s');
			$csv_file = fopen($target_path.$t_file.'(1).csv','w');
			
			foreach ($sheetData as $key => $value) 
			{
				fputcsv($csv_file, $value);
			}
			fclose($csv_file);
			$file_name = $target_path.$t_file.'(1).csv';
			$clone = $file_name;
		}
		
		$file_path = $this->csv_to_db_get_abs_path_from_src_file($file_name);   
		$handle = fopen($file_path ,"r");       
		$header=fgetcsv($handle, 4096, ",");
		$total_header = count($header);
		
		$custom_header = $total_header - 17;//17 are the number of column for the personal profile table
		
		if( $custom_header <= 0 ) return 0; /*If no custom field found*/

		/*Column head form*/
		echo "<div class=\"wrap\">";
		echo "<h2>Import CSV</h2>";
		echo "<form  method=\"post\" action=\"\">";
		
		echo '<input type="hidden" value ="'.$custom_header.'" name="custom_header">
			  <input type="hidden" value ="'.$total_header.'" name="total_header">
			  <input type="hidden" value ="'.$file_path.'" name="file_path">
			  <input type="hidden" value ="'.$clone.'" name="clone">';
		$default = 1;
		$heads = 17;
		$t_head = $custom_header;
		$custom_fields = $wpdb->get_results($wpdb->prepare("SELECT ProfileCustomID,ProfileCustomTitle FROM ". table_agency_customfields." ORDER BY ProfileCustomID ASC"));
		echo "<table class=\"form-table\">";
		echo "<tbody>";
		for($i = 0; $i <= $t_head; $i++){
			if(!empty($header[$heads]) && $header[$heads] != ''){
				echo '<tr><th><label>'.$header[$heads].'</label></th>';
				echo '<td><select name = "select'.$default.'" id="select'.$default.'">';
				foreach ($custom_fields as $custom_fields_result) {
					$custom_field_id = intval($custom_fields_result->ProfileCustomID);
					$custom_field_title = $custom_fields_result->ProfileCustomTitle;
					if($custom_field_id==$default){
						$is_default = ' selected="selected" ';
					}
					else{
						$is_default =''; 
					}
					echo '<option value="'.$custom_field_id.'"'.$is_default.'>'.$custom_field_title.'</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}
			//$custom_header++;
			$heads++;
			$default++;
		}
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
		$p_table_fields = "ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileGender,ProfileDateBirth,ProfileContactEmail,ProfileContactWebsite,ProfileContactPhoneHome,ProfileContactPhoneCell,ProfileContactPhoneWork,ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,ProfileType,ProfileIsActive";
		$c_table_fields = "ProfileCustomID,ProfileID,ProfileCustomValue";       
		set_time_limit(0);
		$path_to_file = $_REQUEST['file_path'];
		$handle = fopen($path_to_file ,"r");
		fgets($handle);//read and ignore the first line
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$ctrl_start = 17;
			$ctrl_end = $_REQUEST['total_header'];
			$incre = 1;
			global $wpdb;
			if($data[3]!="" &&  $data[5]!=""){
				$queryGenderResult = $wpdb->get_row("SELECT GenderID FROM ".table_agency_data_gender." WHERE GenderTitle ='".$data[3]."'", ARRAY_A);
				$ProfileContactDisplay = $wpdb->get_row("SELECT ProfileID FROM ".table_agency_profile." WHERE ProfileContactEmail ='".mysql_real_escape_string($data[5])."'", ARRAY_A);
				if(!isset($ProfileContactDisplay['ProfileID']) ||  $ProfileContactDisplay['ProfileID'] ==""){
						// parse profile type
						if(strpos($data[15], "|") != -1){
							$ex = explode(" | ",trim($data[15]));
							$data[15] = trim(implode(",",$ex));
						}
						
						// check if country and state are numeric probably code
						// need to get ID's before inserting to DB
						if(!is_numeric($data[14])){
							$query ="SELECT CountryID FROM ". table_agency_data_country ." WHERE LOWER(CountryCode) = '" . strtolower(trim($data[14])) . "'";
							$result = $wpdb->get_row($query);
							if(count($result) > 0){
								$data[14] = $result->CountryID;
							} else {
								// compare to title instead
								$query ="SELECT CountryID FROM ". table_agency_data_country ." WHERE LOWER(CountryTitle) = '" . strtolower(trim($data[14])) . "'";
								$result = $wpdb->get_row($query);
								if(count($result) > 0){
									$data[14] = $result->CountryID;
								} 
							}
						}
						if(!is_numeric($data[12])){
							$query ="SELECT StateID FROM ". table_agency_data_state ." WHERE LOWER(StateCode) = '" . strtolower(trim($data[12])) . "'";
							$result = $wpdb->get_row($query);
							$data[12] = $result->StateID;
							if(count($result) > 0){
								$data[12] = $result->StateID;
							} else {
								// compare to title instead
								$query ="SELECT StateID FROM ". table_agency_data_state ." WHERE LOWER(StateTitle) = '" . strtolower(trim($data[12])) . "'";
								$result = $wpdb->get_row($query);
								if(count($result) > 0){
									$data[12] = $result->StateID;
								} 
							}
						}

						$add_to_p_table="INSERT INTO ". table_agency_profile ." ($p_table_fields) VALUES ('$data[0]','$data[1]','$data[2]','".$queryGenderResult['GenderID']."','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]','$data[13]','$data[14]','$data[15]','$data[16]')";
						mysql_query($add_to_p_table) or die(mysql_error());

						$last_inserted_mysql_id = mysql_insert_id();
						if($last_inserted_mysql_id){
							while($ctrl_start < $ctrl_end){
								$select_id =  mysql_real_escape_string($_REQUEST['select'.$incre]);
								if(strpos($data[$ctrl_start], ' ft ') !== FALSE){
									$cal_height = 0;
									$height = explode(' ', $data[$ctrl_start]);
									$cal_height = ($height[0] * 12) + $height[2];
									$data[$ctrl_start]  = $cal_height;
									
								}
								
								$add_to_c_table="INSERT INTO ". table_agency_customfield_mux ." ($c_table_fields)values('".$select_id."','".$last_inserted_mysql_id."','".mysql_real_escape_string($data[$ctrl_start])."')";
								mysql_query($add_to_c_table) or die(mysql_error());
								$ctrl_start++;
								$incre++;
							
							}
						
						}
					 echo "<div class='wrap' style='color:#008000'><ul><li> User Name:- ".$data[0]." & Email:- ".$data[5]."  <b>Successfully Imported Records</b></li></ul></div>";
				}else{
					 echo "<div class='wrap' style='color:#FF0000'><ul><li> User Name:- ".$data[0]." & Email:- ".$data[5]."  <b>Successfully Not Imported. Email Already Used on site.</b></li></ul></div>";
				}
			}
		}
		if($_REQUEST['clone'] != "") unlink($_REQUEST['clone']);

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