<?php 
global $wpdb;
define("LabelPlural", "Profile");
define("LabelSingular", "Profiles");

$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_unittype  			= $rb_agency_options_arr['rb_agency_option_unittype'];
	$rb_agency_option_showsocial 			= $rb_agency_options_arr['rb_agency_option_showsocial'];
	$rb_agency_option_agencyimagemaxheight 	= $rb_agency_options_arr['rb_agency_option_agencyimagemaxheight'];
		if (empty($rb_agency_option_agencyimagemaxheight) || $rb_agency_option_agencyimagemaxheight < 500) { $rb_agency_option_agencyimagemaxheight = 800; }
	$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
	$rb_agency_option_locationtimezone 		= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];

// *************************************************************************************************** //
// Handle Post Actions

if (isset($_POST['action'])) {

	$ProfileID					=$_POST['ProfileID'];
	$ProfileUserLinked			=$_POST['ProfileUserLinked'];
	$ProfileContactNameFirst	=trim($_POST['ProfileContactNameFirst']);
	$ProfileContactNameLast		=trim($_POST['ProfileContactNameLast']);
	$ProfileContactDisplay		=trim($_POST['ProfileContactDisplay']);
	  if (empty($ProfileContactDisplay)) {  // Probably a new record... 
		if ($rb_agency_option_profilenaming == 0) {
			$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
		} elseif ($rb_agency_option_profilenaming == 1) {
			$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
		} elseif ($rb_agency_option_profilenaming == 2) {
			$error .= "<b><i>". __(LabelSingular ." must have a display name identified", rb_agency_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		} elseif ($rb_agency_option_profilenaming == 3) {
			$ProfileContactDisplay = "ID ". $ProfileID;
		}
	  }
	$ProfileGallery				=$_POST['ProfileGallery'];
	  if (empty($ProfileGallery)) {  // Probably a new record... 
		$ProfileGallery = rb_agency_safenames($ProfileContactDisplay); 
	  }
	$ProfileContactParent		=$_POST['ProfileContactParent'];
	$ProfileSSN    				=$_POST['ProfileSSN'];
	$ProfileContactEmail		=$_POST['ProfileContactEmail'];
	$ProfileContactWebsite		=$_POST['ProfileContactWebsite'];
	$ProfileContactLinkFacebook	=$_POST['ProfileContactLinkFacebook'];
	$ProfileContactLinkTwitter	=$_POST['ProfileContactLinkTwitter'];
	$ProfileContactLinkYouTube	=$_POST['ProfileContactLinkYouTube'];
	$ProfileContactLinkFlickr	=$_POST['ProfileContactLinkFlickr'];
	$ProfileContactPhoneHome	=$_POST['ProfileContactPhoneHome'];
	$ProfileContactPhoneCell	=$_POST['ProfileContactPhoneCell'];
	$ProfileContactPhoneWork	=$_POST['ProfileContactPhoneWork'];
	$ProfileGender    			=$_POST['ProfileGender'];
	$ProfileDateBirth	    	=$_POST['ProfileDateBirth'];
	$ProfileLocationStreet		=$_POST['ProfileLocationStreet'];
	$ProfileLocationCity		=rb_agency_strtoproper($_POST['ProfileLocationCity']);
	$ProfileLocationState		=strtoupper($_POST['ProfileLocationState']);
	$ProfileLocationZip			=$_POST['ProfileLocationZip'];
	$ProfileLocationCountry		=$_POST['ProfileLocationCountry'];
	$ProfileLanguage			=$_POST['ProfileLanguage'];
	$ProfileStatEthnicity		=$_POST['ProfileStatEthnicity'];
	$ProfileStatSkinColor		=$_POST['ProfileStatSkinColor'];
	$ProfileStatEyeColor		=$_POST['ProfileStatEyeColor'];
	$ProfileStatHairColor		=$_POST['ProfileStatHairColor'];
	$ProfileStatHeight			=$_POST['ProfileStatHeight'];
	$ProfileStatWeight			=$_POST['ProfileStatWeight'];
	$ProfileStatBust	        =$_POST['ProfileStatBust'];
	$ProfileStatWaist	   		=$_POST['ProfileStatWaist'];
	$ProfileStatHip	       	 	=$_POST['ProfileStatHip'];
	$ProfileStatShoe		    =$_POST['ProfileStatShoe'];
	$ProfileStatDress			=$_POST['ProfileStatDress'];
	$ProfileExperience			=$_POST['ProfileExperience'];
	//$ProfileDateUpdated		=$_POST['ProfileDateUpdated'];
	$ProfileDateViewLast		=$_POST['ProfileDateViewLast'];
	$ProfileType				=$_POST['ProfileType'];
	  if (is_array($ProfileType)) { 
		$ProfileType = implode(",", $ProfileType);
	  } 	
	$ProfileIsActive			=$_POST['ProfileIsActive']; // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
	$ProfileIsFeatured			=$_POST['ProfileIsFeatured'];
	$ProfileIsPromoted			=$_POST['ProfileIsPromoted'];
	$ProfileStatHits			=$_POST['ProfileStatHits'];

	// Get Primary Image
	$ProfileMediaPrimaryID		=$_POST['ProfileMediaPrimary'];

	// Error checking
	$error = "";
	$have_error = false;
	if(trim($ProfileContactNameFirst) == ""){
		$error .= "<b><i>The ". LabelSingular ." must have a name.</i></b><br>";
		$have_error = true;
	}

	// Get Post State
	$action = $_POST['action'];
	switch($action) {

	// *************************************************************************************************** //
	// Add Record
	case 'addRecord':
		if(!$have_error){
			
			// Create Record
			$insert = "INSERT INTO " . table_agency_profile .
			" (ProfileGallery,ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,ProfileContactParent,
			   ProfileContactEmail,ProfileContactWebsite,ProfileGender,ProfileDateBirth,
			   ProfileContactLinkFacebook,ProfileContactLinkTwitter,ProfileContactLinkYouTube,ProfileContactLinkFlickr,
			   ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,
			   ProfileStatEthnicity,ProfileStatSkinColor,ProfileStatEyeColor,ProfileStatHairColor,
			   ProfileStatHeight,ProfileStatWeight,ProfileStatBust,ProfileStatWaist,ProfileStatHip,ProfileStatShoe,ProfileStatDress,
			   ProfileExperience,ProfileContactPhoneHome, ProfileContactPhoneCell, ProfileContactPhoneWork,
			   ProfileDateUpdated,ProfileType,ProfileIsActive,ProfileIsFeatured,ProfileIsPromoted,ProfileStatHits,ProfileDateViewLast)" .
			"VALUES ('" . $wpdb->escape($ProfileGallery) . "','" . $wpdb->escape($ProfileContactDisplay) . "','" . $wpdb->escape($ProfileContactNameFirst) . "','" . $wpdb->escape($ProfileContactNameLast) . "','" . $wpdb->escape($ProfileContactParent) . "',
				'" . $wpdb->escape($ProfileContactEmail) . "','" . $wpdb->escape($ProfileContactWebsite) . "','" . $wpdb->escape($ProfileGender) . "','" . $wpdb->escape($ProfileDateBirth) . "',
			    '" . $wpdb->escape($ProfileContactLinkFacebook) . "','" . $wpdb->escape($ProfileContactLinkTwitter) . "','" . $wpdb->escape($ProfileContactLinkYouTube) . "','" . $wpdb->escape($ProfileContactLinkFlickr) . "',
				'" . $wpdb->escape($ProfileLocationStreet) . "','" . $wpdb->escape($ProfileLocationCity) . "','" . $wpdb->escape($ProfileLocationState) . "','" . $wpdb->escape($ProfileLocationZip) . "','" . $wpdb->escape($ProfileLocationCountry) . "',
				'" . $wpdb->escape($ProfileStatEthnicity) . "','" . $wpdb->escape($ProfileStatSkinColor) . "','" . $wpdb->escape($ProfileStatEyeColor) . "','" . $wpdb->escape($ProfileStatHairColor) . "',
				'" . $wpdb->escape($ProfileStatHeight) . "','" . $wpdb->escape($ProfileStatWeight) . "','" . $wpdb->escape($ProfileStatBust) . "','" . $wpdb->escape($ProfileStatWaist) . "','" . $wpdb->escape($ProfileStatHip) . "','" . $wpdb->escape($ProfileStatShoe) . "','" . $wpdb->escape($ProfileStatDress) . "',
				'" . $wpdb->escape($ProfileExperience) . "','" . $wpdb->escape($ProfileContactPhoneHome) . "','" . $wpdb->escape($ProfileContactPhoneCell) . "','" . $wpdb->escape($ProfileContactPhoneWork) . "',
				now(),'" . $wpdb->escape($ProfileType) . "','" . $wpdb->escape($ProfileIsActive) . "','" . $wpdb->escape($ProfileIsFeatured) . "','" . $wpdb->escape($ProfileIsPromoted) . "','" . $wpdb->escape($ProfileStatHits) . "','" . $wpdb->escape($ProfileDateViewLast) . "')";
		    $results = $wpdb->query($insert);
			$ProfileID = $wpdb->insert_id;

			// Set Display Name as Record ID (We have to do this after so we know what record ID to use... right ;)
			if ($rb_agency_option_profilenaming == 3) {
				$ProfileContactDisplay = "ID-". $ProfileID;
				$ProfileGallery = "ID". $ProfileID;

				$update = $wpdb->query("UPDATE " . table_agency_profile . " SET ProfileContactDisplay='". $ProfileContactDisplay. "', ProfileGallery='". $ProfileGallery. "' WHERE ProfileID='". $ProfileID ."'");
				$updated = $wpdb->query($update);
			}
			
			// Make Directory for new profile
			if (!is_dir(rb_agency_UPLOADPATH . $ProfileGallery)) {
				mkdir(rb_agency_UPLOADPATH . $ProfileGallery, 0755);
				chmod(rb_agency_UPLOADPATH . $ProfileGallery, 0777);
			} else {
				$finished = false;                       // we're not finished yet (we just started)
				while ( ! $finished ):                   // while not finished
				  $NewProfileGallery = $ProfileGallery ."-". rand(1, 15);   // output folder name
				  if ( ! is_dir(rb_agency_UPLOADPATH . $NewProfileGallery) ):        // if folder DOES NOT exist...
					mkdir(rb_agency_UPLOADPATH . $NewProfileGallery, 0755);
					chmod(rb_agency_UPLOADPATH . $NewProfileGallery, 0777);
					$ProfileGallery = $NewProfileGallery;  // Set it to the new  thing
					$finished = true;                    // ...we are finished
				  endif;
				endwhile;
			}

			// Add Custom Field Values stored in Mux
			foreach($_POST as $key => $value) {
				if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {
					$ProfileCustomID = substr($key, 15);
					
					$insert1 = "INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue)" . "VALUES ('" . $ProfileID . "','" . $ProfileCustomID . "','" . $value . "')";
					$results1 = $wpdb->query($insert1);
				}
			}
			
			echo ('<div id="message" class="updated"><p>'. __("New Profile added successfully!", rb_agency_TEXTDOMAIN) .' <a href="'. admin_url("admin.php?page=". $_GET['page']) .'&action=editRecord&ProfileID='. $ProfileID .'">'. __("Update and add media", rb_agency_TEXTDOMAIN) .'</a></p></div>'); 
		} else {
        	echo ('<div id="message" class="error"><p>'. __("Error creating record, please ensure you have filled out all required fields.", rb_agency_TEXTDOMAIN) .'</p></div>'); 
		}

		// We can edit it now
		rb_display_manage($ProfileID);
		exit;
	break;
	
		// *************************************************************************************************** //
	// Edit Record
	case 'editRecord':
		if (!empty($ProfileContactNameFirst) && !empty($ProfileID)){
			
			// Update Record
			$update = "UPDATE " . table_agency_profile . " SET 
			ProfileGallery='" . $wpdb->escape($ProfileGallery) . "',
			ProfileContactDisplay='" . $wpdb->escape($ProfileContactDisplay) . "',
			ProfileContactNameFirst='" . $wpdb->escape($ProfileContactNameFirst) . "',
			ProfileContactNameLast='" . $wpdb->escape($ProfileContactNameLast) . "',
			ProfileContactEmail='" . $wpdb->escape($ProfileContactEmail) . "',
			ProfileContactWebsite='" . $wpdb->escape($ProfileContactWebsite) . "',
			ProfileContactLinkFacebook='" . $wpdb->escape($ProfileContactLinkFacebook) . "',
			ProfileContactLinkTwitter='" . $wpdb->escape($ProfileContactLinkTwitter) . "',
			ProfileContactLinkYouTube='" . $wpdb->escape($ProfileContactLinkYouTube) . "',
			ProfileContactLinkFlickr='" . $wpdb->escape($ProfileContactLinkFlickr) . "',
			ProfileContactPhoneHome='" . $wpdb->escape($ProfileContactPhoneHome) . "',
			ProfileContactPhoneCell='" . $wpdb->escape($ProfileContactPhoneCell) . "',
			ProfileContactPhoneWork='" . $wpdb->escape($ProfileContactPhoneWork) . "',
			ProfileContactParent='" . $wpdb->escape($ProfileContactParent) . "',
			ProfileGender='" . $wpdb->escape($ProfileGender) . "',
			ProfileDateBirth ='" . $wpdb->escape($ProfileDateBirth) . "',
			ProfileLocationStreet='" . $wpdb->escape($ProfileLocationStreet) . "',
			ProfileLocationCity='" . $wpdb->escape($ProfileLocationCity) . "',
			ProfileLocationState='" . $wpdb->escape($ProfileLocationState) . "',
			ProfileLocationZip ='" . $wpdb->escape($ProfileLocationZip) . "',
			ProfileLocationCountry='" . $wpdb->escape($ProfileLocationCountry) . "',
			ProfileStatEthnicity='" . $wpdb->escape($ProfileStatEthnicity) . "',
			ProfileStatSkinColor='" . $wpdb->escape($ProfileStatSkinColor) . "',
			ProfileStatEyeColor='" . $wpdb->escape($ProfileStatEyeColor) . "',
			ProfileStatHairColor='" . $wpdb->escape($ProfileStatHairColor) . "',
			ProfileStatHeight='" . $wpdb->escape($ProfileStatHeight) . "',
			ProfileStatWeight='" . $wpdb->escape($ProfileStatWeight) . "',
			ProfileStatBust='" . $wpdb->escape($ProfileStatBust) . "',
			ProfileStatWaist='" . $wpdb->escape($ProfileStatWaist) . "',
			ProfileStatHip='" . $wpdb->escape($ProfileStatHip) . "',
			ProfileStatShoe='" . $wpdb->escape($ProfileStatShoe) . "',
			ProfileStatDress='" . $wpdb->escape($ProfileStatDress) . "',
			ProfileUnion='" . $wpdb->escape($ProfileUnion) . "',
			ProfileExperience='" . $wpdb->escape($ProfileExperience) . "',
			ProfileDateUpdated=now(),
			ProfileType='" . $wpdb->escape($ProfileType) . "',
			ProfileIsActive='" . $wpdb->escape($ProfileIsActive) . "',
			ProfileIsFeatured='" . $wpdb->escape($ProfileIsFeatured) . "',
			ProfileIsPromoted='" . $wpdb->escape($ProfileIsPromoted) . "',
			ProfileStatHits='" . $wpdb->escape($ProfileStatHits) . "'
			WHERE ProfileID=$ProfileID";
		  $results = $wpdb->query($update);

		  if ($ProfileUserLinked > 0) {
			/* Update WordPress user information. */
			update_usermeta( $ProfileUserLinked, 'first_name', esc_attr( $ProfileContactNameFirst ) );
			update_usermeta( $ProfileUserLinked, 'last_name', esc_attr( $ProfileContactNameLast ) );
			update_usermeta( $ProfileUserLinked, 'nickname', esc_attr( $ProfileContactDisplay ) );
			update_usermeta( $ProfileUserLinked, 'display_name', esc_attr( $ProfileContactDisplay ) );
			update_usermeta( $ProfileUserLinked, 'user_email', esc_attr( $ProfileContactEmail ) );
		  }
		  
			// Remove Old Custom Field Values
			$delete1 = "DELETE FROM " . table_agency_customfield_mux . " WHERE ProfileID = \"". $ProfileID ."\"";
			$results1 = $wpdb->query($delete1);
			
			// Add New Custom Field Values
			foreach($_POST as $key => $value) {
				if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {
					$ProfileCustomID = substr($key, 15);
					
					$insert1 = "INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue)" . "VALUES ('" . $ProfileID . "','" . $ProfileCustomID . "','" . $value . "')";
					$results1 = $wpdb->query($insert1);
				}
			}

			// If the directory Doesnt Exist, make it.
			if (!is_dir(rb_agency_UPLOADPATH . $ProfileGallery)) {
				mkdir(rb_agency_UPLOADPATH . $ProfileGallery, 0755);
				chmod(rb_agency_UPLOADPATH . $ProfileGallery, 0777);
			}

			// Upload Image & Add to Database
			$i = 1;
		while ($i <= 10) {
				if($_FILES['profileMedia'. $i]['tmp_name'] != ""){
					$uploadMediaType = $_POST['profileMedia'. $i .'Type'];
					
					if ($have_error != true) {
					// Upload if it doesnt exist already
					 $safeProfileMediaFilename = rb_agency_safenames($_FILES['profileMedia'. $i]['name']);
					 $results = mysql_query("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='". $ProfileID ."' AND ProfileMediaURL = '". $safeProfileMediaFilename ."'");
					 $count = mysql_num_rows($results);

					 if ($count < 1) {
						if($uploadMediaType == "Image") { 
						    if($_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/gif" || $_FILES['profileMedia'. $i]['type'] == "image/png"){
						
									$image = new rb_agency_image();
									$image->load($_FILES['profileMedia'. $i]['tmp_name']);
			
									if ($image->getHeight() > $rb_agency_option_agencyimagemaxheight) {
										$image->resizeToHeight($rb_agency_option_agencyimagemaxheight);
									}
									$image->save(rb_agency_UPLOADPATH . $ProfileGallery ."/". $safeProfileMediaFilename);
									// Add to database
								$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
						    }else{
								$error .= "<b><i>Please upload an image file only</i></b><br />";
						        $have_error = true;
							}
						}
						else if($uploadMediaType =="VoiceDemo"){
							// Add to database
							 if($_FILES['profileMedia'. $i]['type'] == "audio/mp3"){
								 $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
			                 	 move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], rb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
							 }else{
								 $error .= "<b><i>Please upload a mp3 file only</i></b><br />";
								 $have_error = true;
							 }
						}
						else if($uploadMediaType =="Resume"){
							// Add to database
							 if ($_FILES['profileMedia'. $i]['type'] == "application/msword" || $_FILES['profileMedia'. $i]['type'] == "application/pdf" || $_FILES['profileMedia'. $i]['type'] == "application/rtf")
							{
							  $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
			                  move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], rb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
							}else{
							   	$error .= "<b><i>Please upload PDF/MSword/RTF files only</i></b><br />";
						        $have_error = true;	
							}
						}
						else if($uploadMediaType =="Headshot"){
							// Add to database
							 if ($_FILES['profileMedia'. $i]['type'] == "application/msword"|| $_FILES['profileMedia'. $i]['type'] == "application/pdf" || $_FILES['profileMedia'. $i]['type'] == "application/rtf" || $_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/gif" || $_FILES['profileMedia'. $i]['type'] == "image/png")
							{
							  $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
			                  move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], rb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
							}else{
							   	$error .= "<b><i>Please upload PDF/MSWord/RTF/Image files only</i></b><br />";
						        $have_error = true;	
							}
						}
						else if($uploadMediaType =="Compcard"){
							// Add to database
							 if ($_FILES['profileMedia'. $i]['type'] == "image/jpeg" || $_FILES['profileMedia'. $i]['type'] == "image/png")
							{
							  $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $uploadMediaType ."','". $safeProfileMediaFilename ."','". $safeProfileMediaFilename ."')");
			                  move_uploaded_file($_FILES['profileMedia'. $i]['tmp_name'], rb_agency_UPLOADPATH . $ProfileGallery ."/".$safeProfileMediaFilename);
							}else{
							   	$error .= "<b><i>Please upload jpeg or png files only</i></b><br />";
								$have_error = true;	
							}
						}
						
					 }
					}
				}
				$i++;
			}			


			// Upload Videos to Database
			if (isset($_POST['profileMediaV1']) && !empty($_POST['profileMediaV1'])) {
				$profileMediaType = $_POST['profileMediaV1Type'];
				$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV1']);
				$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $profileMediaType ."','". $profileMediaType ."','". $profileMediaURL ."')");
			}
			if (isset($_POST['profileMediaV2']) && !empty($_POST['profileMediaV2'])) {
				$profileMediaType	=$_POST['profileMediaV2Type'];
				$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV2']);
				$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $profileMediaType ."','". $profileMediaType ."','". $profileMediaURL ."')");
			}
			if (isset($_POST['profileMediaV3']) && !empty($_POST['profileMediaV3'])) {
				$profileMediaType	=$_POST['profileMediaV3Type'];
				$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV3']);
				$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('". $ProfileID ."','". $profileMediaType ."','". $profileMediaType ."','". $profileMediaURL ."')");
			}

			/* --------------------------------------------------------- CLEAN THIS UP -------------- */
			// Do we have a custom image yet? Lets just set the first one as primary.
			 $results = mysql_query("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='". $ProfileID ."' AND ProfileMediaType = 'Image' AND ProfileMediaPrimary='1'");
			 $count = mysql_num_rows($results);
			 if ($count < 1) {
			 	$resultsNeedOne = mysql_query("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='". $ProfileID ."' AND ProfileMediaType = 'Image' LIMIT 0, 1");
				while ($dataNeedOne = mysql_fetch_array($resultsNeedOne)) {
					$resultsFoundOne = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='1' WHERE ProfileID='". $ProfileID ."' AND ProfileMediaID = '". $dataNeedOne['ProfileMediaID'] . "'");
					break;
				}
			 }
	  		 if ($ProfileMediaPrimaryID > 0) {
			  // Update Primary Image
			  $results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='0' WHERE ProfileID=$ProfileID");
			  $results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='1' WHERE ProfileID=$ProfileID AND ProfileMediaID=$ProfileMediaPrimaryID");
			 }
			/* --------------------------------------------------------- CLEAN THIS UP -------------- */
			
			echo ("<div id=\"message\" class=\"updated\"><p>". __("Profile updated successfully", rb_agency_TEXTDOMAIN) ."! <a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&ProfileID=". $ProfileID ."\">". __("Continue editing the record", rb_agency_TEXTDOMAIN) ."?</a></p></div>");
		} else {
			echo ("<div id=\"message\" class=\"error\"><p>". __("Error updating record, please ensure you have filled out all required fields.", rb_agency_TEXTDOMAIN) ."</p></div>"); 
		}
		
		rb_display_list();
		exit;
	break;

	// *************************************************************************************************** //
	// Delete bulk
	case 'deleteRecord':
		foreach($_POST as $ProfileID) {

			// Verify Record
			$queryDelete = "SELECT * FROM ". table_agency_profile ." WHERE ProfileID =  \"". $ProfileID ."\"";
			$resultsDelete = mysql_query($queryDelete);
			while ($dataDelete = mysql_fetch_array($resultsDelete)) {
				$ProfileGallery = $dataDelete['ProfileGallery'];
		
				// Remove Profile
				$delete = "DELETE FROM " . table_agency_profile . " WHERE ProfileID = \"". $ProfileID ."\"";
				$results = $wpdb->query($delete);
				// Remove Media
				$delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = \"". $ProfileID ."\"";
				$results = $wpdb->query($delete);
					
				if (isset($ProfileGallery)) {
					// Remove Folder
					$dir = rb_agency_UPLOADPATH . $ProfileGallery ."/";
					$mydir = opendir($dir);
					while(false !== ($file = readdir($mydir))) {
						if($file != "." && $file != "..") {
							unlink($dir.$file) or DIE("<div id=\"message\" class=\"error\"><p>". __("Error removing:", rb_agency_TEXTDOMAIN) . $dir . $file."</p></div>"); 
						}
					}
					// Remove Directory
					if(is_dir($dir)) {
						rmdir($dir) or DIE("<div id=\"message\" class=\"error\"><p>". __("Error removing:", rb_agency_TEXTDOMAIN) . $dir . $file."</p></div>");
					}
					closedir($mydir);
					
				} else {
					echo ("<div id=\"message\" class=\"error\"><p>". __("No Valid Record Found.", rb_agency_TEXTDOMAIN) ."</p></div>");
				}
					
			echo ('<div id="message" class="updated"><p>'. __("Profile deleted successfully!", rb_agency_TEXTDOMAIN) .'</p></div>');
			} // is there record?
			
		}
		rb_display_list();
		exit;
	break;
	// *************************************************************************************************** //
	// UPDATE bulk
	case 'updateRecord':
				// ********Bulk Action
  			/*/
		    0 = Inactive
			1 = Active
			2 = Archived
			3 = Pending
			4 = Active - Not visible on website
		   */
		$isActive = '';
		
		if(isset($_POST['BulkAction_ProfileApproval1']) || isset($_POST['BulkAction_ProfileApproval2'])){
		       
			 if(isset($_POST['profileID']) && $_POST['BulkAction_ProfileApproval1'] !='' || $_POST['BulkAction_ProfileApproval2'] !=''){
				
				 $totalSelectedProfile = 0;
				
				 foreach($_POST['profileID'] as $key_profileID){
					 
					$totalSelectedProfile++;
					 
					$update = "UPDATE ".table_agency_profile. " SET ProfileIsActive='".$_POST['BulkAction_ProfileApproval1'].$_POST['BulkAction_ProfileApproval2']."' WHERE ProfileID=$key_profileID";
					$results = $wpdb->query($update);
				 }
				  $LabelProfile = '';
				  $totalSelectedProfile > 1 ? $LabelProfile = "$totalSelectedProfile Profiles updated successfully!" : $LabelProfile = "Profile updated successfully! <a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&ProfileID=". $key_profileID ."\">". __("Continue editing the record", rb_agency_TEXTDOMAIN) ."?</a>";
				 
				echo ("<div id=\"message\" class=\"updated\"><p>". __("$LabelProfile ", rb_agency_TEXTDOMAIN) ." </p></div>");
		
			 }
		 
		
		}
	
	
	}
}
// *************************************************************************************************** //
// Delete Single
elseif ($_GET['action'] == "deleteRecord") {

	$ProfileID = $_GET['ProfileID'];
	// Verify Record
	$queryDelete = "SELECT * FROM ". table_agency_profile ." WHERE ProfileID =  \"". $ProfileID ."\"";
	$resultsDelete = mysql_query($queryDelete);
	while ($dataDelete = mysql_fetch_array($resultsDelete)) {
		$ProfileGallery = $dataDelete['ProfileGallery'];

		// Remove Profile
		$delete = "DELETE FROM " . table_agency_profile . " WHERE ProfileID = \"". $ProfileID ."\"";
		$results = $wpdb->query($delete);
		// Remove Media
		$delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = \"". $ProfileID ."\"";
		$results = $wpdb->query($delete);
			
		if (isset($ProfileGallery)) {
			// Remove Folder
			$dir = rb_agency_UPLOADPATH . $ProfileGallery ."/";
			$mydir = opendir($dir);
			while(false !== ($file = readdir($mydir))) {
				if($file != "." && $file != "..") {
					unlink($dir.$file) or DIE("couldn't delete $dir$file<br />");
				}
			}
			// remove dir
			if(is_dir($dir)) {
				rmdir($dir) or DIE("couldn't delete $dir$file<br />");
			}
			closedir($mydir);
			
		} else {
			echo __("No valid record found.", rb_agency_TEXTDOMAIN);
		}
			
	echo ('<div id="message" class="updated"><p>'. __("Profile deleted successfully!", rb_agency_TEXTDOMAIN) .'</p></div>');
	} // is there record?
	rb_display_list();

}
// *************************************************************************************************** //
// Show Edit Record
elseif (($_GET['action'] == "editRecord") || ($_GET['action'] == "add")) {
	
	$action = $_GET['action'];
	$ProfileID = $_GET['ProfileID'];

	rb_display_manage($ProfileID);

} else {
// *************************************************************************************************** //
// Show List
	rb_display_list();
}


// *************************************************************************************************** //
// Manage Record
function rb_display_manage($ProfileID) {
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype  			= $rb_agency_options_arr['rb_agency_option_unittype'];
		$rb_agency_option_showsocial 			= $rb_agency_options_arr['rb_agency_option_showsocial'];
		$rb_agency_option_agencyimagemaxheight 	= $rb_agency_options_arr['rb_agency_option_agencyimagemaxheight'];
			if (empty($rb_agency_option_agencyimagemaxheight) || $rb_agency_option_agencyimagemaxheight < 500) { $rb_agency_option_agencyimagemaxheight = 800; }
		$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
		$rb_agency_option_locationcountry 		= $rb_agency_options_arr['rb_agency_option_locationcountry'];

  echo "<div class=\"wrap\">\n";
  echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
  echo "  <h2>". __("Manage ". LabelSingular, rb_agency_TEXTDOMAIN) ."</h2>\n";
  echo "  <p><a class=\"button-primary\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."\">". __("Back to ". LabelSingular ." List", rb_agency_TEXTDOMAIN) ."</a></p>\n";

	if ( !empty($ProfileID) && ($ProfileID > 0) ) {
	
		$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileID='$ProfileID'";
		$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$ProfileID					=$data['ProfileID'];
			$ProfileUserLinked			=$data['ProfileUserLinked'];
			$ProfileGallery				=stripslashes($data['ProfileGallery']);
			$ProfileContactDisplay		=stripslashes($data['ProfileContactDisplay']);
			$ProfileContactNameFirst	=stripslashes($data['ProfileContactNameFirst']);
			$ProfileContactNameLast		=stripslashes($data['ProfileContactNameLast']);
			$ProfileContactEmail		=stripslashes($data['ProfileContactEmail']);
			$ProfileContactWebsite		=stripslashes($data['ProfileContactWebsite']);
			$ProfileContactLinkFacebook	=stripslashes($data['ProfileContactLinkFacebook']);
			$ProfileContactLinkTwitter	=stripslashes($data['ProfileContactLinkTwitter']);
			$ProfileContactLinkYouTube	=stripslashes($data['ProfileContactLinkYouTube']);
			$ProfileContactLinkFlickr	=stripslashes($data['ProfileContactLinkFlickr']);
			$ProfileContactPhoneHome	=stripslashes($data['ProfileContactPhoneHome']);
			$ProfileContactPhoneCell	=stripslashes($data['ProfileContactPhoneCell']);
			$ProfileContactPhoneWork	=stripslashes($data['ProfileContactPhoneWork']);
			$ProfileContactParent		=stripslashes($data['ProfileContactParent']);
			$ProfileGender    			=stripslashes($data['ProfileGender']);
			$ProfileDateBirth	    	=stripslashes($data['ProfileDateBirth']);
			$ProfileLocationStreet		=stripslashes($data['ProfileLocationStreet']);
			$ProfileLocationCity		=stripslashes($data['ProfileLocationCity']);
			$ProfileLocationState		=stripslashes($data['ProfileLocationState']);
			$ProfileLocationZip			=stripslashes($data['ProfileLocationZip']);
			$ProfileLocationCountry		=stripslashes($data['ProfileLocationCountry']);
			$ProfileStatEthnicity		=stripslashes($data['ProfileStatEthnicity']);
			$ProfileStatSkinColor		=stripslashes($data['ProfileStatSkinColor']);
			$ProfileStatEyeColor		=stripslashes($data['ProfileStatEyeColor']);
			$ProfileStatHairColor		=stripslashes($data['ProfileStatHairColor']);
			$ProfileStatHeight			=stripslashes($data['ProfileStatHeight']);
			$ProfileStatWeight			=stripslashes($data['ProfileStatWeight']);
			$ProfileStatBust	        =stripslashes($data['ProfileStatBust']);
			$ProfileStatWaist	    	=stripslashes($data['ProfileStatWaist']);
			$ProfileStatHip	        	=stripslashes($data['ProfileStatHip']);
			$ProfileStatShoe		    =stripslashes($data['ProfileStatShoe']);
			$ProfileStatDress			=stripslashes($data['ProfileStatDress']);
			$ProfileUnion				=stripslashes($data['ProfileUnion']);
			$ProfileExperience			=stripslashes($data['ProfileExperience']);
			$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
			$ProfileType				=stripslashes($data['ProfileType']);
			$ProfileIsActive			=stripslashes($data['ProfileIsActive']);
			$ProfileIsFeatured			=stripslashes($data['ProfileIsFeatured']);
			$ProfileIsPromoted			=stripslashes($data['ProfileIsPromoted']);
			$ProfileStatHits			=stripslashes($data['ProfileStatHits']);
			$ProfileDateViewLast		=stripslashes($data['ProfileDateViewLast']);
		
		echo "<h3 class=\"title\">". __("Edit", rb_agency_TEXTDOMAIN) ." ". LabelSingular ."</h3>\n";
		echo "<p>". __("Make changes in the form below to edit a", rb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_agency_TEXTDOMAIN) ."Required fields are marked *</strong></p>\n";
		echo "<p><a href=\"". rb_agency_PROFILEDIR . $rb_agency_UPLOADDIR . $ProfileGallery ."/\" target=\"_blank\">View Profile</a></p>\n";
		}
	
	
	} else {
		// Set default values for new records
			$ProfilesModelDate = $date; 
			$ProfileType = 1;
			$ProfileGender = "Unknown";
			$ProfileIsActive = 1;
			$ProfileLocationCountry = $rb_agency_option_locationcountry;
		
		echo "<h3 class=\"title\">Add New ". LabelSingular ."</h3>\n";
		echo "<p>". __("Fill in the form below to add a new", rb_agency_TEXTDOMAIN) ." ". LabelSingular .". <strong>". __("Required fields are marked", rb_agency_TEXTDOMAIN) ." *</strong></p>\n";
	} 
	
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
	
	echo "<div style=\"float: left; width: 50%; \">\n";
	echo " <table class=\"form-table\">\n";
	echo "  <tbody>\n";
	echo "    <tr colspan=\"2\">\n";
	echo "		<th scope=\"row\"><h3>". __("Contact Information", rb_agency_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	if ( (!empty($ProfileID) && ($ProfileID > 0)) || ($rb_agency_option_profilenaming == 2) ) { // Editing Record
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Display Name", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactDisplay\" name=\"ProfileContactDisplay\" value=\"". $ProfileContactDisplay ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	}
	if ( !empty($ProfileID) && ($ProfileID > 0) ) { // Editing Record
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Gallery Folder", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";

					if (!empty($ProfileGallery) && is_dir(rb_agency_UPLOADPATH .$ProfileGallery)) { 
						echo "<div id=\"message\"><span class=\"updated\">". __("Folder", rb_agency_TEXTDOMAIN) ." <strong>". $ProfileGallery ."</strong> ". __("Exists", rb_agency_TEXTDOMAIN) ."</span></div>\n";
						echo "<input type=\"hidden\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"". $ProfileGallery ."\" />\n";
					} else {
						echo "<input type=\"text\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"". $ProfileGallery ."\" />\n";
						echo "<div id=\"message\"><span class=\"error\">". __("No Folder Exists", rb_agency_TEXTDOMAIN) ."</span>\n";
					}
	echo "             	</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	}
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("First Name", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $ProfileContactNameFirst ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Last Name", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $ProfileContactNameLast ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Private Information
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\" colspan=\"2\"><h3>". __("Private Information", rb_agency_TEXTDOMAIN) ."</h3>". __("The following information will NOT appear in public areas and is for administrative use only.", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Parent (if minor)", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactParent\" name=\"ProfileContactParent\" value=\"". $ProfileContactParent ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Email Address", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"". $ProfileContactEmail ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Birthdate", rb_agency_TEXTDOMAIN) ." <em>YYYY-MM-DD</em></th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileDateBirth\" name=\"ProfileDateBirth\" value=\"". $ProfileDateBirth ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Address
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Street", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" value=\"". $ProfileLocationStreet ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("City", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" value=\"". $ProfileLocationCity ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("State", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationState\" name=\"ProfileLocationState\" value=\"". $ProfileLocationState ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Zip", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" value=\"". $ProfileLocationZip ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Country", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationCountry\" name=\"ProfileLocationCountry\" value=\"". $ProfileLocationCountry ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Custom Admin Fields

	$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions FROM ". table_agency_customfields ." WHERE ProfileCustomView = 1 ORDER BY ProfileCustomView, ProfileCustomTitle";
	$results1 = mysql_query($query1);
	$count1 = mysql_num_rows($results1);
	while ($data1 = mysql_fetch_array($results1)) {
	
	echo "  <tr valign=\"top\">\n";
	echo "    <th scope=\"row\">". $data1['ProfileCustomTitle'] ."</th>\n";
	echo "    <td>\n";
		  if ( !empty($ProfileID) && ($ProfileID > 0) ) {

			$subresult = mysql_query("SELECT ProfileCustomValue FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = ". $data1['ProfileCustomID'] ." AND ProfileID = ". $ProfileID);
			$subcount = mysql_num_rows($subresult);
			if ($subcount > 0) { 
			  while ($row = mysql_fetch_object($subresult)) {
				$ProfileCustomValue = $row->ProfileCustomValue;
			  }
			} else {
				$ProfileCustomValue = "";
			}
			mysql_free_result($subresult);
			
		  } /// End 
		  
		  
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
				foreach ($ProfileCustomOptions_Array as &$value) {
				echo "	<option value=\"". $value ."\" ". selected($ProfileCustomValue, $value) ."> ". $value ." </option>\n";
				} 
				echo "</select>\n";
			} else {
				echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
			}
			
			// END Query2
	echo "    </td>\n";
	echo "  </tr>\n";
	}
	
	// Links	
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Phone", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			Home: <input type=\"text\" style=\"width: 100px;\" id=\"ProfileContactPhoneHome\" name=\"ProfileContactPhoneHome\" value=\"". $ProfileContactPhoneHome ."\" /><br />\n";
	echo "			Cell: <input type=\"text\" style=\"width: 100px;\" id=\"ProfileContactPhoneCell\" name=\"ProfileContactPhoneCell\" value=\"". $ProfileContactPhoneCell ."\" /><br />\n";
	echo "			Work: <input type=\"text\" style=\"width: 100px;\" id=\"ProfileContactPhoneWork\" name=\"ProfileContactPhoneWork\" value=\"". $ProfileContactPhoneWork ."\" /><br />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Website", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactWebsite\" name=\"ProfileContactWebsite\" value=\"". $ProfileContactWebsite ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Show Social Media Links
	if ($rb_agency_option_showsocial == "1") { 
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\" colspan=\"2\"><h3>". __("Social Media Profiles", rb_agency_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Facebook", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactLinkFacebook\" name=\"ProfileContactLinkFacebook\" value=\"". $ProfileContactLinkFacebook ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Twitter", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactLinkTwitter\" name=\"ProfileContactLinkTwitter\" value=\"". $ProfileContactLinkTwitter ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("YouTube", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactLinkYouTube\" name=\"ProfileContactLinkYouTube\" value=\"". $ProfileContactLinkYouTube ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Flickr", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactLinkFlickr\" name=\"ProfileContactLinkFlickr\" value=\"". $ProfileContactLinkFlickr ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	} 
	// Public Information
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\" colspan=\"2\"><h3>". __("Public Information", rb_agency_TEXTDOMAIN) ."</h3>The following information may appear in profile pages.</th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Gender", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td><select name=\"ProfileGender\" id=\"ProfileGender\">\n";
	echo "			<option value=\"\" ". selected($ProfileGender, "") .">". __("Not Specified", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "			<option value=\"Male\" ". selected($ProfileGender, "Male") .">". __("Male", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "			<option value=\"Female\" ". selected($ProfileGender, "Female") .">". __("Female", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "		  </select>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Ethnicity", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "        <td><select name=\"ProfileStatEthnicity\" id=\"ProfileStatEthnicity\">\n";

					$queryData = "SELECT EthnicityTitle FROM ". table_agency_data_ethnicity ." ORDER BY EthnicityTitle";
					$resultsData = mysql_query($queryData);
					$countData = mysql_num_rows($resultsData);
					if ($countData > 0) {
						if (empty($ProfileStatEthnicity)) {
							echo " <option value=\"0\" selected>--</option>\n";
						}
						while ($dataData = mysql_fetch_array($resultsData)) {
							echo " <option value=\"". $dataData["EthnicityTitle"] ."\" ". selected($ProfileStatEthnicity, $dataData["EthnicityTitle"]) .">". $dataData["EthnicityTitle"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						echo "". __("No items to select", rb_agency_TEXTDOMAIN) .". <a href=\"". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=5") ."\">". __("Setup Options", rb_agency_TEXTDOMAIN) ."</a>";
					}
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Skin Color", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "        <td><select name=\"ProfileStatSkinColor\" id=\"ProfileStatSkinColor\">\n";

					$queryData = "SELECT ColorSkinTitle FROM ". table_agency_data_colorskin ." ORDER BY ColorSkinTitle";
					$resultsData = mysql_query($queryData);
					$countData = mysql_num_rows($resultsData);
					if ($countData > 0) {
						if (empty($ProfileStatSkinColor)) {
							echo " <option value=\"0\" selected>--</option>\n";
						}
						while ($dataData = mysql_fetch_array($resultsData)) {
							echo " <option value=\"". $dataData["ColorSkinTitle"] ."\" ". selected($ProfileStatSkinColor, $dataData["ColorSkinTitle"]) .">". $dataData["ColorSkinTitle"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						echo "". __("No items to select", rb_agency_TEXTDOMAIN) .". <a href='". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=2") ."'>". __("Setup Options", rb_agency_TEXTDOMAIN) ."</a>";
					}
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Eye Color", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "        <td><select name=\"ProfileStatEyeColor\" id=\"ProfileStatEyeColor\">\n";

					$queryData = "SELECT ColorEyeTitle FROM ". table_agency_data_coloreye ." ORDER BY ColorEyeTitle";
					$resultsData = mysql_query($queryData);
					$countData = mysql_num_rows($resultsData);
					if ($countData > 0) {
						if (empty($ProfileStatEyeColor)) {
							echo " <option value=\"0\" selected>--</option>\n";
						}
						while ($dataData = mysql_fetch_array($resultsData)) {
							echo " <option value=\"". $dataData["ColorEyeTitle"] ."\" ". selected($ProfileStatEyeColor, $dataData["ColorEyeTitle"]) .">". $dataData["ColorEyeTitle"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						echo "". __("No items to select", rb_agency_TEXTDOMAIN) .". <a href='". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=3") ."'>". __("Setup Options", rb_agency_TEXTDOMAIN) ."</a>";
					}
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Hair Color", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "        <td><select name=\"ProfileStatHairColor\" id=\"ProfileStatHairColor\">\n";

					$queryData = "SELECT ColorHairTitle FROM ". table_agency_data_colorhair ." ORDER BY ColorHairTitle";
					$resultsData = mysql_query($queryData);
					$countData = mysql_num_rows($resultsData);
					if ($countData > 0) {
						if (empty($ProfileStatHairColor)) {
							echo " <option value=\"0\" selected>--</option>\n";
						}
						while ($dataData = mysql_fetch_array($resultsData)) {
							echo " <option value=\"". $dataData["ColorHairTitle"] ."\" ". selected($ProfileStatHairColor, $dataData["ColorHairTitle"]) .">". $dataData["ColorHairTitle"] ."</option>\n";
						}
						echo "</select>\n";
					} else {
						echo "". __("No items to select", rb_agency_TEXTDOMAIN) .". <a href='". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=4") ."'>". __("Setup Options", rb_agency_TEXTDOMAIN) ."</a>";
					}
	echo "        </td>\n";
	echo "    </tr>\n";
			  // Metric or Imperial?
			  if ($rb_agency_option_unittype == 1) {
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("In Inches", rb_agency_TEXTDOMAIN) .")</em></th>\n";
	echo "        <td><select name=\"ProfileStatHeight\" id=\"ProfileStatHeight\">\n";
					if (empty($ProfileStatHeight)) {
	echo " 				<option value=\"\" selected>--</option>\n";
					}
					
					$i=36;
					$heightraw = 0;
					$heightfeet = 0;
					$heightinch = 0;
					while($i<=90)  { 
					  $heightraw = $i;
					  $heightfeet = floor($heightraw/12);
					  $heightinch = $heightraw - floor($heightfeet*12);
	echo " 				<option value=\"". $i ."\" ". selected($ProfileStatHeight, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
					  $i++;
					}
	echo " 			</select>\n";
	echo "        </td>\n";
	echo "    </tr>\n";
			  } else {
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Height", rb_agency_TEXTDOMAIN) ." <em>(". __("cm", rb_agency_TEXTDOMAIN) .")</em></th>\n";
	echo "        <td>\n";
	echo "			<input type=\"text\" id=\"ProfileStatHeight\" name=\"ProfileStatHeight\" value=\"". $ProfileStatHeight ."\" />\n";
	echo "        </td>\n";
	echo "    </tr>\n";
			  }
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Weight", rb_agency_TEXTDOMAIN) ." \n";
				  if ($rb_agency_option_unittype == 1) { echo "<em>(". __("In Pounds", rb_agency_TEXTDOMAIN) .")</em>"; } else { echo "<em>(". __("In Kilo", rb_agency_TEXTDOMAIN) .")</em></th>\n"; }
	echo "        </th>\n";
	echo "        <td>\n";
	echo "			<input type=\"text\" id=\"ProfileStatWeight\" name=\"ProfileStatWeight\" value=\"". $ProfileStatWeight ."\" />\n";
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Measurements", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "        <td>\n";
					if ($ProfileGender == "Male") { _e("Chest", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ _e("Bust", rb_agency_TEXTDOMAIN); } else { echo "". __("Bust", rb_agency_TEXTDOMAIN) ."/". __("Bust", rb_agency_TEXTDOMAIN); } 
						echo "<input type=\"text\" style=\"width: 80px;\" id=\"ProfileStatBust\" name=\"ProfileStatBust\" value=\"". $ProfileStatBust ."\" /><br />\n";
					echo "". __("Waist", rb_agency_TEXTDOMAIN) .": \n";
						echo "<input type=\"text\" style=\"width: 80px;\" id=\"ProfileStatWaist\" name=\"ProfileStatWaist\" value=\"". $ProfileStatWaist ."\" /><br />\n";
					if ($ProfileGender == "Male") { _e("Inseam", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ _e("Hips", rb_agency_TEXTDOMAIN); } else { echo "". __("Hips", rb_agency_TEXTDOMAIN) ."/". __("Inseam", rb_agency_TEXTDOMAIN); } 
						echo "<input type=\"text\" style=\"width: 80px;\" id=\"ProfileStatHip\" name=\"ProfileStatHip\" value=\"". $ProfileStatHip ."\" /><br />\n";
	echo "        </td>\n";
	echo "    </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Shoe Size", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileStatShoe\" name=\"ProfileStatShoe\" value=\"". $ProfileStatShoe ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">";
					if($ProfileGender == "Male"){ echo __("Suit Size", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ echo __("Dress Size", rb_agency_TEXTDOMAIN); } else { echo __("Suit", rb_agency_TEXTDOMAIN) ."/". __("Dress Size", rb_agency_TEXTDOMAIN); } 
	echo "      </th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileStatDress\" name=\"ProfileStatDress\" value=\"". $ProfileStatDress ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "	</tbody>\n";
	echo " </table>\n";
	echo "</div>\n";
	
	
	echo "<div id=\"profile-manage-media\" style=\"float: left; width: 50%; \">\n";

		if ( !empty($ProfileID) && ($ProfileID > 0) ) { // Editing Record
	echo "		<h3>". __("Gallery", rb_agency_TEXTDOMAIN) ."</h3>\n";
			
			echo "<script>\n";
			echo "function confirmDelete(delPhoto,mediaTitle) {\n";
			echo "  if (confirm(\"Are you sure you want to delete this \"+mediaTitle+\"?\")) {\n";
			echo "	document.location = \"". admin_url("admin.php?page=". $_GET['page']) ."&action=editRecord&ProfileID=". $ProfileID ."&actionsub=photodelete&targetid=\"+delPhoto;\n";
			echo "  }\n";
			echo "}\n";
			echo "function ImageRotate(id,ImgSrc,name){ \n";
			echo "document.getElementById(\"imgloader\").src='".rb_agency_BASEDIR."tasks/imageRotate.php?&imgsrc='+ ImgSrc + '&' + Math.random()+'&name='+name;\n";
			echo "setTimeout(function(){ document.getElementById(id).src='". rb_agency_UPLOADDIR . $ProfileGallery ."/'+ImgSrc+'?&' + Math.random(); },1000);\n";
			echo "}\n";
		    echo "</script>\n";
			
			// Are we deleting?
			if ($_GET["actionsub"] == "photodelete") {
				$deleteTargetID = $_GET["targetid"];
				
				// Verify Record
				$queryImgConfirm = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaID =  \"". $deleteTargetID ."\"";
				$resultsImgConfirm = mysql_query($queryImgConfirm);
				$countImgConfirm = mysql_num_rows($resultsImgConfirm);
				while ($dataImgConfirm = mysql_fetch_array($resultsImgConfirm)) {
					$ProfileMediaID = $dataImgConfirm['ProfileMediaID'];
					$ProfileMediaType = $dataImgConfirm['ProfileMediaType'];
					$ProfileMediaURL = $dataImgConfirm['ProfileMediaURL'];
					
					// Remove Record
					$delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaID=$ProfileMediaID";
					$results = $wpdb->query($delete);
					
					if ($ProfileMediaType == "Demo Reel" || $ProfileMediaType == "Video Monologue" || $ProfileMediaType == "Video Slate") {
						// Nothing to Remove
					} else {
						// Remove File
						$dirURL = rb_agency_UPLOADPATH . $ProfileGallery;
						if (!unlink($dirURL ."/". $ProfileMediaURL)) {
						  echo ("<div id=\"message\" class=\"error\"><p>". __("Error removing", rb_agency_TEXTDOMAIN) ." <strong>". $ProfileMediaURL ."</strong>. ". __("File did not exist.", rb_agency_TEXTDOMAIN) .".</p></div>");
						} else {
						  echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> ". __("successfully removed", rb_agency_TEXTDOMAIN) .".</p></div>");
						}
					}
				} // is there record?
			}
				// Go about our biz-nazz
				$queryImg = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC, ProfileMediaID DESC";
				$resultsImg = mysql_query($queryImg);
				$countImg = mysql_num_rows($resultsImg);
				
				echo "<img src=\"\" id=\"imgloader\" style=\"display:none;\"/>"; //image rotate buffer
				
				while ($dataImg = mysql_fetch_array($resultsImg)) {
				  if ($dataImg['ProfileMediaPrimary']) {
					  $styleBackground = "#900000";
					  $isChecked = " checked";
					  $isCheckedText = " Primary";
					if ($countImg == 1) {
					  $toDelete = "  <div class=\"delete\"><a href=\"javascript:confirmDelete('". $dataImg['ProfileMediaID'] ."')\"><span>Delete</span> &raquo;</a>  </div>\n";
					} else {
					  $toDelete = "";
					}
				  } else {
					  $styleBackground = "#000000";
					  $isChecked = "";
					  $isCheckedText = " Select";
					  $toDelete = "  <div class=\"delete\"><a href=\"javascript:confirmDelete('". $dataImg['ProfileMediaID'] ."')\"><span>Delete</span> &raquo;</a> </div>\n";
				  }
					echo "<div class=\"profileimage\" style=\"background: ". $styleBackground ."; \">\n". $toDelete ."";
					echo "  <img  id=\"".$dataImg['ProfileMediaID']."\" src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" style=\"width: 100px; z-index: 1; \" />\n";
					echo "  <div class=\"primary\" style=\"background: ". $styleBackground ."; \"><a href=\"javascript:ImageRotate(".$dataImg['ProfileMediaID'].",'".$dataImg['ProfileMediaURL']."','".$ProfileGallery."')\" title=\"Rotate image\" class=\"rotateimage\"></a><input type=\"radio\" name=\"ProfileMediaPrimary\" value=\"". $dataImg['ProfileMediaID'] ."\" class=\"button-primary\"". $isChecked ." /> ". $isCheckedText ." </div>\n";
					echo "</div>\n";
				}
				if ($countImg < 1) {
					echo "<div>". __("There are no images loaded for this profile yet.", rb_agency_TEXTDOMAIN) ."</div>\n";
				}
				
				
	echo "		<div style=\"clear: both;\"></div>\n";
		echo "		<br><br><h3>". __("Media", rb_agencyinteract_TEXTDOMAIN) ."</h3>\n";
		echo "		<p>". __("The following files (pdf, audio file, etc.) are associated with this record", rb_agencyinteract_TEXTDOMAIN) .".</p>\n";
	
					$queryMedia = "SELECT * FROM ". table_agency_profile_media ." WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType <> \"Image\"";
					$resultsMedia = mysql_query($queryMedia);
					$countMedia = mysql_num_rows($resultsMedia);
					while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
							$outVideoMedia .= "<div style=\"float: left; width: 120px; text-align: center; padding: 10px; \">". $dataMedia['ProfileMediaType'] ."<br />". rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) ."<br /><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Link to Video</a><br />[<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
						} 
						 elseif ($dataMedia['ProfileMediaType'] == "VoiceDemo") {
							$outLinkVoiceDemo .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] ."</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
						}
						 elseif ($dataMedia['ProfileMediaType'] == "Resume") {
							$outLinkResume .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] ."</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
						}
						 elseif ($dataMedia['ProfileMediaType'] == "Headshot") {
							$outLinkHeadShot .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] ."</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
						}
						 elseif ($dataMedia['ProfileMediaType'] == "Compcard") {
							$outLinkCompCard .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] ."</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
						}
					}
					echo '<div style=\"width:500px;\">';
					echo $outLinkVoiceDemo;
					echo '</div>';
					echo '<div style=\"width:500px;\">';
					echo $outLinkResume;
					echo '</div>';
					echo '<div style=\"width:500px;\">';
					echo $outLinkHeadShot;
					echo '</div>';
					echo '<div style=\"width:500px;\">';
					echo $outLinkCompCard;
					echo '</div>';
					echo $outVideoMedia;
					if ($countMedia < 1) {
						echo "<div><em>". __("There are no additional media linked", rb_agencyinteract_TEXTDOMAIN) ."</em></div>\n";
					}
	echo "		<div style=\"clear: both;\"></div>\n";
	echo "		<h3>". __("Upload", rb_agency_TEXTDOMAIN) ."</h3>\n";
	echo "		<p>". __("Upload new media using the forms below", rb_agency_TEXTDOMAIN) .".</p>\n";

			for( $i=1; $i<10; $i++ ) {
			echo "<div>Type: <select name=\"profileMedia". $i ."Type\"><option value='Image'>Image</option><option value='Headshot'>Headshot</option><option value='Compcard'>Comp Card</option><option value='Resume'>Resume</option><option value=\"VoiceDemo\">Voice Demo</option></select><input type='file' id='profileMedia". $i ."' name='profileMedia". $i ."' /></div>\n";
			}
	echo "		<p>". __("Paste the YouTube video URL below", rb_agency_TEXTDOMAIN) .".</p>\n";

			echo "<div>Type: <select name=\"profileMediaV1Type\"><option selected>". __("Video Slate", rb_agency_TEXTDOMAIN) ."</option><option>". __("Video Monologue", rb_agency_TEXTDOMAIN) ."</option><option>". __("Demo Reel", rb_agency_TEXTDOMAIN) ."</option></select><textarea id='profileMediaV1' name='profileMediaV1'></textarea></div>\n";
			echo "<div>Type: <select name=\"profileMediaV2Type\"><option>". __("Video Slate", rb_agency_TEXTDOMAIN) ."</option><option selected>". __("Video Monologue", rb_agency_TEXTDOMAIN) ."</option><option>". __("Demo Reel", rb_agency_TEXTDOMAIN) ."</option></select><textarea id='profileMediaV2' name='profileMediaV2'></textarea></div>\n";
			echo "<div>Type: <select name=\"profileMediaV3Type\"><option>". __("Video Slate", rb_agency_TEXTDOMAIN) ."</option><option>". __("Video Monologue", rb_agency_TEXTDOMAIN) ."</option><option selected>". __("Demo Reel", rb_agency_TEXTDOMAIN) ."</option></select><textarea id='profileMediaV3' name='profileMediaV3'></textarea></div>\n";
	
		}
		echo "</div>\n";

		echo "<div style=\"clear: both; \"></div>\n";

		echo "<table class=\"form-table\">\n";
		echo " <tbody>\n";

	
		$query1 = "SELECT ProfileCustomID, ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions FROM ". table_agency_customfields ." WHERE ProfileCustomView IN (0,2) AND ProfileCustomType < 4 ORDER BY ProfileCustomView, ProfileCustomTitle";
		$results1 = mysql_query($query1);
		$count1 = mysql_num_rows($results1);
		while ($data1 = mysql_fetch_array($results1)) {
		
		echo "  <tr valign=\"top\">\n";
		echo "    <th scope=\"row\">". $data1['ProfileCustomTitle'] ."</th>\n";
		echo "    <td>\n";
			  if ( !empty($ProfileID) && ($ProfileID > 0) ) {

				$subresult = mysql_query("SELECT ProfileCustomValue FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = ". $data1['ProfileCustomID'] ." AND ProfileID = ". $ProfileID);
				$subcount = mysql_num_rows($subresult);
				if ($subcount > 0) { 
				  while ($row = mysql_fetch_object($subresult)) {
					$ProfileCustomValue = $row->ProfileCustomValue;
				  }
				} else {
					$ProfileCustomValue = "";
				}
				mysql_free_result($subresult);
				
			  } /// End 
			  
			  
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
					foreach ($ProfileCustomOptions_Array as &$value) {
					echo "	<option value=\"". $value ."\" ". selected($ProfileCustomValue, $value) ."> ". $value ." </option>\n";
					} 
					echo "</select>\n";
				} else {
					echo "<input type=\"text\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
				}
				
				// END Query2
		echo "    </td>\n";
		echo "  </tr>\n";
		}
		if ($count1 < 1) {
		echo "  <tr valign=\"top\">\n";
		echo "    <th scope=\"row\">". __("There are no custom fields loaded", rb_agency_TEXTDOMAIN) .".  <a href=". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=7") ."'>". __("Setup Custom Fields", rb_agency_TEXTDOMAIN) ."</a>.</th>\n";
		echo "  </tr>\n";
		}
	// Description
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\" colspan=\"2\"><h3>". __("Details", rb_agency_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Description", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<textarea style=\"width: 100%; min-height: 300px;\" id=\"ProfileExperience\" name=\"ProfileExperience\" class=\"ProfileExperience\">". $ProfileExperience ."</textarea>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";

	$query1 = "SELECT ProfileCustomID, ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomView IN (0,2) AND ProfileCustomType = 4 ORDER BY ProfileCustomView, ProfileCustomTitle";
	$results1 = mysql_query($query1);
	$count1 = mysql_num_rows($results1);
	while ($data1 = mysql_fetch_array($results1)) {
		
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". $data1['ProfileCustomTitle'] ."</th>\n";
	echo "		<td>\n";
			  if ( !empty($ProfileID) && ($ProfileID > 0) ) {

				$subresult = mysql_query("SELECT ProfileCustomValue FROM ". table_agency_customfield_mux ." WHERE ProfileCustomID = ". $data1['ProfileCustomID'] ." AND ProfileID = ". $ProfileID);
				$subcount = mysql_num_rows($subresult);
				if ($subcount > 0) { 
				  while ($row = mysql_fetch_object($subresult)) {
					$ProfileCustomValue = $row->ProfileCustomValue;
				  }
				} else {
					$ProfileCustomValue = "";
				}
				mysql_free_result($subresult);
				
			  } /// End 
			  
	echo "			<textarea style=\"width: 100%; min-height: 300px;\" id=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" class=\"ProfileExperience\">". $ProfileCustomValue ."</textarea>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	}
	
	// Account Information	
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\" colspan=\"2\"><h3>". __("Classification", rb_agency_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Classification", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
		
				$ProfileTypeArray = explode(",", $ProfileType);
	
				$query3 = "SELECT * FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
				$results3 = mysql_query($query3);
				$count3 = mysql_num_rows($results3);
				while ($data3 = mysql_fetch_array($results3)) {
					echo "<input type=\"checkbox\" name=\"ProfileType[]\" id=\"ProfileType[]\" value=\"". $data3['DataTypeID'] ."\""; if ( in_array($data3['DataTypeID'], $ProfileTypeArray)) { echo " checked=\"checked\""; } echo "> ". $data3['DataTypeTitle'] ."<br />\n";
				}
				if ($count3 < 1) {
					echo "". __("No items to select", rb_agency_TEXTDOMAIN) .". <a href='". admin_url("admin.php?page=rb_agency_menu_settings&ConfigID=5") ."'>". __("Setup Options", rb_agency_TEXTDOMAIN) ."</a>\n";
				}

	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "        <th scope=\"row\">". __("Status", rb_agency_TEXTDOMAIN) .":</th>\n";
	echo "        <td><select id=\"ProfileIsActive\" name=\"ProfileIsActive\">\n";
	echo "			  <option value=\"1\"". selected(1, $ProfileIsActive) .">". __("Active", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "			  <option value=\"4\"". selected(4, $ProfileIsActive) .">". __("Active - Not Visible On Website", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "			  <option value=\"0\"". selected(0, $ProfileIsActive) .">". __("Inactive", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "			  <option value=\"2\"". selected(2, $ProfileIsActive) .">". __("Archived", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "			  <option value=\"3\"". selected(3, $ProfileIsActive) .">". __("Pending Approval", rb_agency_TEXTDOMAIN) ."</option>\n";
	echo "          </select></td>\n";
	echo "    </tr>\n";
	if (isset($ProfileUserLinked) && $ProfileUserLinked > 0) {
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("WordPress User", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
					echo $ProfileUserLinked;
	echo "		</td>\n";
	echo "	  </tr>\n";
	}
	if (function_exists(rb_agencyinteract_menu_approvemembers)) {
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Membership", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"checkbox\" name=\"ProfileIsFeatured\" id=\"ProfileIsFeatured\" value=\"1\""; checked($ProfileIsFeatured, 1); echo " /> Featured<br />\n";
	echo "			<input type=\"checkbox\" name=\"ProfileIsPromoted\" id=\"ProfileIsPromoted\" value=\"1\""; checked($ProfileIsPromoted, 1); echo " /> Rising Star<br />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	}

	// Hidden Settings
	if ($_GET["mode"] == "override") {
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Date Updated", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileDateUpdated\" name=\"ProfileDateUpdated\" value=\"". $ProfileDateUpdated ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Profile Views", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileStatHits\" name=\"ProfileStatHits\" value=\"". $ProfileStatHits ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\">". __("Profile Viewed Last", rb_agency_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileDateViewLast\" name=\"ProfileDateViewLast\" value=\"". $ProfileDateViewLast ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	} else {
	echo "    <tr valign=\"top\">\n";
	echo "		<th scope=\"row\"></th>\n";
	echo "		<td>\n";
	echo "			<input type=\"hidden\" id=\"ProfileDateUpdated\" name=\"ProfileDateUpdated\" value=\"". $ProfileDateUpdated ."\" />\n";
	echo "			<input type=\"hidden\" id=\"ProfileStatHits\" name=\"ProfileStatHits\" value=\"". $ProfileStatHits ."\" />\n";
	echo "			<input type=\"hidden\" id=\"ProfileDateViewLast\" name=\"ProfileDateViewLast\" value=\"". $ProfileDateViewLast ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	}
	echo "  </tbody>\n";
	echo "</table>\n";
	echo "". __("Last updated on", rb_agency_TEXTDOMAIN) .": ". $ProfileDateUpdated ."\n";

	if ( !empty($ProfileID) && ($ProfileID > 0) ) {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"". $ProfileID ."\" />\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Update Record", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} else {
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Create Record", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	} 
	echo "</form>\n";
	
	
} // End Manage



/* List Records *****************************************************/

function rb_display_list(){  
  global $wpdb;
  $rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_locationtimezone 		= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];

  echo "<div class=\"wrap\">\n";
  echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
  echo "  <h2>". __("List", rb_agency_TEXTDOMAIN) ." ". LabelPlural ."</h2>\n";
	
  echo "  <h3 class=\"title\">". __("All Records", rb_agency_TEXTDOMAIN) ."</h3>\n";
		
		// Sort By
        $sort = "";
        if (isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        else {
            $sort = "profile.ProfileContactNameFirst";
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
  	
		// Filter
		if(isset($_GET['ProfileVisible']) && $_GET['ProfileVisible'] !=""){
			
			$filter = "WHERE profile.ProfileIsActive='".$_GET['ProfileVisible']."' ";
			
		}else{
			
		    $filter = "WHERE profile.ProfileIsActive IN (0,1,4) ";
	
		}
		
	
		if ((isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) || isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
        	if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])){
			$selectedNameFirst = $_GET['ProfileContactNameFirst'];
			$query .= "&ProfileContactNameFirst=". $selectedNameFirst ."";
			$filter .= " AND profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
	        }
        	if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			$selectedNameLast = $_GET['ProfileContactNameLast'];
			$query .= "&ProfileContactNameLast=". $selectedNameLast ."";
			$filter .= " AND profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
	        }
		}
		if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])){
			$selectedCity = $_GET['ProfileLocationCity'];
			$query .= "&ProfileLocationCity=". $selectedCity ."";
			$filter .= " AND profile.ProfileLocationCity='". $selectedCity ."'";
		}
		if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])){
			$selectedType = $_GET['ProfileType'];
			$query .= "&ProfileType=". $selectedType ."";
			$filter .= " AND profiletype.DataTypeID='". $selectedType ."'";
		}
		if (isset($_GET['ProfileVisible']) && !empty($_GET['ProfileVisible'])){
			$selectedVisible = $_GET['ProfileVisible'];
			$query .= "&ProfileVisible=". $selectedVisible ."";
			$filter .= " AND profile.ProfileIsActive='". $selectedVisible ."'";
		}
		
	
		//Paginate
		$items = mysql_num_rows(mysql_query("SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID ". $filter  ."")); // number of total rows in the database
		if($items > 0) {
			$p = new rb_agency_pagination;
			$p->items($items);
			$p->limit(50); // Limit entries per page
			$p->target("admin.php?page=". $_GET['page'] .$query);
			$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
			$p->calculate(); // Calculates what to show
			$p->parameterName('paging');
			$p->adjacents(1); //No. of page away from the current page
	 
			if(!isset($_GET['paging'])) {
				$p->page = 1;
			} else {
				$p->page = $_GET['paging'];
			}
	 
			//Query for limit paging
			$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
		} else {
			$limit = "";
		}
		
        echo "<div class=\"tablenav\">\n";
 		echo "	<div style=\"float: left; \"><a class=\"button-primary\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&action=add\">". __("Create New Record", rb_agency_TEXTDOMAIN) ."</a></div>\n";
        echo "  <div class=\"tablenav-pages\">\n";
				if($items > 0) {
					echo $p->show();  // Echo out the list of paging. 
				}
        echo "  </div>\n";
        echo "</div>\n";
      
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo "  <thead>\n";
		echo "    <tr>\n";
		echo "        <td style=\"width: 50px;\">\n";               
		echo "        		<strong>". __("Filter By", rb_agency_TEXTDOMAIN) .":</strong>\n";
		echo "        </td>\n";               
		echo "        <td nowrap=\"nowrap\">\n";         
		    
		echo "        	<form style=\"display: inline;\" method=\"GET\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"". $_GET['page_index'] ."\" />\n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
		echo "        		<input type=\"hidden\" name=\"type\" value=\"name\" />\n";
		echo "        		". __("First Name", rb_agency_TEXTDOMAIN) .": <input type=\"text\" name=\"ProfileContactNameFirst\" value=\"". $selectedNameFirst ."\" style=\"width: 100px;\" />\n";
		echo "        		". __("Last Name", rb_agency_TEXTDOMAIN) .": <input type=\"text\" name=\"ProfileContactNameLast\" value=\"". $selectedNameLast ."\" style=\"width: 100px;\" />\n";
		echo "        		". __("Location", rb_agency_TEXTDOMAIN) .": \n";
		echo "        		<select name=\"ProfileLocationCity\">\n";
		echo "				  <option value=\"\">". __("Any Location", rb_agency_TEXTDOMAIN) ."</option>";

								$query = "SELECT DISTINCT ProfileLocationCity, ProfileLocationState FROM ". table_agency_profile ." ORDER BY ProfileLocationState, ProfileLocationCity ASC";
								$results = mysql_query($query);
								$count = mysql_num_rows($results);
								while ($data = mysql_fetch_array($results)) {
									if (isset($data['ProfileLocationCity']) && !empty($data['ProfileLocationCity'])) {
									echo "<option value=\"". $data['ProfileLocationCity'] ."\" ". selected($selectedCity, $data["ProfileLocationCity"]) ."\">". $data['ProfileLocationCity'] .", ". strtoupper($dataLocation["ProfileLocationState"]) ."</option>\n";
									}
								} 
		echo "        		</select>\n";
		echo "        		(<a href=\"". admin_url("admin.php?page=rb_agency_menu_search") ."\">". __("Advanced Search", rb_agency_TEXTDOMAIN) ."</a>)<br />\n";
		echo "        		". __("Status", rb_agency_TEXTDOMAIN) .":\n";
		echo "        		<select name=\"ProfileVisible\">\n";
		echo "				  <option value=\"\">". __("Any Status", rb_agency_TEXTDOMAIN) ."</option>";
		echo "				  <option value=\"1\"". selected(1, $selectedVisible) .">". __("Active", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "				  <option value=\"4\"". selected(4, $selectedVisible) .">". __("Not Visible", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "				  <option value=\"0\"". selected(0, $selectedVisible) .">". __("Inactive", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "				  <option value=\"2\"". selected(2, $selectedVisible) .">". __("Archived", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "        		</select>\n";
		echo "        		". __("Category", rb_agency_TEXTDOMAIN) .":\n";
		echo "        		<select name=\"ProfileType\">\n";
		echo "				  <option value=\"\">". __("Any Category", rb_agency_TEXTDOMAIN) ."</option>";

								$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle ASC";
								$results = mysql_query($query);
								$count = mysql_num_rows($results);
								while ($data = mysql_fetch_array($results)) {
									echo "<option value=\"". $data['DataTypeID'] ."\" ". selected($selectedCity, $data["DataTypeTitle"]) ."\">". $data['DataTypeTitle'] ."</option>\n";
								} 
		echo "        		</select>\n";
		echo "        		<input type=\"submit\" value=\"". __("Filter", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
		echo "          </form>\n";
		echo "        	<form style=\"display: inline;\" method=\"GET\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";
		echo "        		<input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"". $_GET['page_index'] ."\" />  \n";
		echo "        		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"". $_GET['page'] ."\" />\n";
		echo "        		<input type=\"submit\" value=\"". __("Clear Filters", rb_agency_TEXTDOMAIN) ."\" class=\"button-secondary\" />\n";
		echo "        	</form>\n";
		echo "        </td>\n";

		
		
		echo "    </tr>\n";
		echo "  </thead>\n";
		echo "</table>\n";
        
		echo "<form method=\"post\" action=\"". admin_url("admin.php?page=". $_GET['page']) ."\">\n";	
			  // Bulk action form
		echo "        		<select name=\"BulkAction_ProfileApproval1\">\n";
		echo "              <option value=\"\"> ". __("Bulk Action", rb_agencyinteract_TEXTDOMAIN) ."<option\>\n";
		echo "			  <option value=\"1\"". selected(1, $ProfileIsActive) .">". __("Active", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"4\"". selected(4, $ProfileIsActive) .">". __("Active - Not Visible On Website", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"0\"". selected(0, $ProfileIsActive) .">". __("Inactive", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"2\"". selected(2, $ProfileIsActive) .">". __("Archived", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"3\"". selected(3, $ProfileIsActive) .">". __("Pending Approval", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "              </select>"; 
		echo "    <input type=\"submit\" value=\"". __("Apply", rb_agencyinteract_TEXTDOMAIN) ."\" name=\"ProfileBulkAction\" class=\"button-secondary\"  />\n";
		    
		echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
		echo " <thead>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileID&dir=". $sortDirection) ."\">ID</a></th>\n";
		echo "        <th class=\"column-ProfileContactNameFirst\" id=\"ProfileContactNameFirst\" scope=\"col\" style=\"width:130px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileContactNameFirst&dir=". $sortDirection) ."\">First Name</a></th>\n";
		echo "        <th class=\"column-ProfileContactNameLast\" id=\"ProfileContactNameLast\" scope=\"col\" style=\"width:130px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileContactNameLast&dir=". $sortDirection) ."\">Last Name</a></th>\n";
		echo "        <th class=\"column-ProfileGender\" id=\"ProfileGender\" scope=\"col\" style=\"width:65px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileGender&dir=". $sortDirection) ."\">Gender</a></th>\n";
		echo "        <th class=\"column-ProfilesProfileDate\" id=\"ProfilesProfileDate\" scope=\"col\" style=\"width:50px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileDateBirth&dir=". $sortDirection) ."\">Age</a></th>\n";
		echo "        <th class=\"column-ProfileLocationCity\" id=\"ProfileLocationCity\" scope=\"col\" style=\"width:100px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileLocationCity&dir=". $sortDirection) ."\">City</a></th>\n";
		echo "        <th class=\"column-ProfileLocationState\" id=\"ProfileLocationState\" scope=\"col\" style=\"width:50px;\"><a href=\"". admin_url("admin.php?page=". $_GET['page'] ."&sort=ProfileLocationState&dir=". $sortDirection) ."\">State</a></th>\n";
		echo "        <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\" style=\"width:100px;\">Category</th>\n";
		echo "        <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\" style=\"width:65px;\">Images</th>\n";
		echo "        <th class=\"column-ProfileStatHits\" id=\"ProfileStatHits\" scope=\"col\" style=\"width:60px;\">Views</th>\n";
		echo "        <th class=\"column-ProfileDateViewLast\" id=\"ProfileDateViewLast\" scope=\"col\">Last Viewed Date</th>\n";
		echo "    </tr>\n";
		echo " </thead>\n";
		echo " <tfoot>\n";
		echo "    <tr class=\"thead\">\n";
		echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
		echo "        <th class=\"column\" scope=\"col\">ID</th>\n";
		echo "        <th class=\"column\" scope=\"col\">First Name</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Last Name</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Gender</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Age</th>\n";
		echo "        <th class=\"column\" scope=\"col\">City</th>\n";
		echo "        <th class=\"column\" scope=\"col\">State</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Category</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Images</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Views</th>\n";
		echo "        <th class=\"column\" scope=\"col\">Last Viewed</th>\n";
		echo "    </tr>\n";
		echo " </tfoot>\n";
		echo " <tbody>\n";

        $query = "SELECT * FROM ". table_agency_profile ." profile LEFT JOIN ". table_agency_data_type ." profiletype ON profile.ProfileType = profiletype.DataTypeID ". $filter  ." ORDER BY $sort $dir $limit";
        $results2 = mysql_query($query);
        $count = mysql_num_rows($results2);
        while ($data = mysql_fetch_array($results2)) {
            
            $ProfileID = $data['ProfileID'];
            $ProfileGallery = stripslashes($data['ProfileGallery']);
            $ProfileContactNameFirst = stripslashes($data['ProfileContactNameFirst']);
            $ProfileContactNameLast = stripslashes($data['ProfileContactNameLast']);
            $ProfileLocationCity = rb_agency_strtoproper(stripslashes($data['ProfileLocationCity']));
            $ProfileLocationState = stripslashes($data['ProfileLocationState']);
            $ProfileGender = stripslashes($data['ProfileGender']);
            $ProfileDateBirth = stripslashes($data['ProfileDateBirth']);
            $ProfileStatHits = stripslashes($data['ProfileStatHits']);
            $ProfileDateViewLast = stripslashes($data['ProfileDateViewLast']);
			if ($data['ProfileIsActive'] == 0) {
				// Inactive
				$rowColor = " style=\"background: #FFEBE8\""; 
			} elseif ($data['ProfileIsActive'] == 1) {
				// Active
				$rowColor = ""; 
			} elseif ($data['ProfileIsActive'] == 2) {
				// Archived
				$rowColor = " style=\"background: #dadada\""; 
			} elseif ($data['ProfileIsActive'] == 3) {
				// Pending Approval
				$rowColor = " style=\"background: #DD4B39\""; 
			}
            $DataTypeTitle = stripslashes($data['DataTypeTitle']);
			
			$resultImageCount = mysql_query("SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='". $ProfileID ."' AND ProfileMediaType = 'Image'");
			$profileImageCount = mysql_num_rows($resultImageCount);

		echo "    <tr". $rowColor .">\n";
		echo "        <th class=\"check-column\" scope=\"row\">\n";
		echo "          <input type=\"checkbox\" value=\"". $ProfileID ."\" class=\"administrator\" id=\"". $ProfileID ."\" name=\"profileID[". $ProfileID ."]\"/>\n";
		echo "        </th>\n";
		echo "        <td class=\"ProfileID column-ProfileID\">". $ProfileID ."</td>\n";
		echo "        <td class=\"ProfileContactNameFirst column-ProfileContactNameFirst\">\n";
		echo "          ". $ProfileContactNameFirst ."\n";
		echo "          <div class=\"row-actions\">\n";
		echo "            <span class=\"edit\"><a href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=editRecord&amp;ProfileID=". $ProfileID ."\" title=\"". __("Edit this Record", rb_agency_TEXTDOMAIN) . "\">". __("Edit", rb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"edit\"><a href=\"". rb_agency_PROFILEDIR . $rb_agency_UPLOADDIR . $ProfileGallery ."/\" title=\"". __("View", rb_agency_TEXTDOMAIN) . "\" target=\"_blank\">". __("View", rb_agency_TEXTDOMAIN) . "</a> | </span>\n";
		echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"". admin_url("admin.php?page=". $_GET['page']) ."&amp;action=deleteRecord&amp;ProfileID=". $ProfileID ."\"  onclick=\"if ( confirm('". __("You are about to delete the profile for ", rb_agency_TEXTDOMAIN) ." ". $ProfileContactNameFirst ." ". $ProfileContactNameLast ."'". __("Cancel", rb_agency_TEXTDOMAIN) . "\' ". __("to stop", rb_agency_TEXTDOMAIN) . ", \'". __("OK", rb_agency_TEXTDOMAIN) . "\' ". __("to delete", rb_agency_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"". __("Delete this Record", rb_agency_TEXTDOMAIN) . "\">". __("Delete", rb_agency_TEXTDOMAIN) . "</a> </span>\n";
		echo "          </div>\n";
		echo "        </td>\n";
		echo "        <td class=\"ProfileContactNameLast column-ProfileContactNameLast\">". $ProfileContactNameLast ."</td>\n";
		echo "        <td class=\"ProfileGender column-ProfileGender\">". $ProfileGender ."</td>\n";
		echo "        <td class=\"ProfilesProfileDate column-ProfilesProfileDate\">". rb_agency_get_age($ProfileDateBirth) ."</td>\n";
		echo "        <td class=\"ProfileLocationCity column-ProfileLocationCity\">". $ProfileLocationCity ."</td>\n";
		echo "        <td class=\"ProfileLocationCity column-ProfileLocationState\">". $ProfileLocationState ."</td>\n";
		echo "        <td class=\"ProfileDetails column-ProfileDetails\">". $DataTypeTitle ."</td>\n";
		echo "        <td class=\"ProfileDetails column-ProfileDetails\">". $profileImageCount ."</td>\n";
		echo "        <td class=\"ProfileStatHits column-ProfileStatHits\">". $ProfileStatHits ."</td>\n";
		echo "        <td class=\"ProfileDateViewLast column-ProfileDateViewLast\">\n";
		echo "           ". rb_agency_makeago(rb_agency_convertdatetime($ProfileDateViewLast), $rb_agency_option_locationtimezone);
		echo "        </td>\n";
		echo "    </tr>\n";

        }
            mysql_free_result($results2);
            if ($count < 1) {
				if (isset($filter)) { 
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"name column-name\" colspan=\"5\">\n";
		echo "           <p>No profiles found with this criteria.</p>\n";
		echo "        </td>\n";
		echo "    </tr>\n";
				} else {
		echo "    <tr>\n";
		echo "        <th class=\"check-column\" scope=\"row\"></th>\n";
		echo "        <td class=\"name column-name\" colspan=\"5\">\n";
		echo "            <p>There aren't any profiles loaded yet!</p>\n";
		echo "        </td>\n";
		echo "    </tr>\n";
				}
        } 
		echo " </tbody>\n";
		echo "</table>\n";
		
		  // Bulk action form
		echo "        		<select name=\"BulkAction_ProfileApproval2\">\n";
		echo "              <option value=\"\"> ". __("Bulk Action", rb_agencyinteract_TEXTDOMAIN) ."<option\>\n";
		echo "			  <option value=\"1\"". selected(1, $ProfileIsActive) .">". __("Active", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"4\"". selected(4, $ProfileIsActive) .">". __("Active - Not Visible On Website", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"0\"". selected(0, $ProfileIsActive) .">". __("Inactive", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"2\"". selected(2, $ProfileIsActive) .">". __("Archived", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "			  <option value=\"3\"". selected(3, $ProfileIsActive) .">". __("Pending Approval", rb_agency_TEXTDOMAIN) ."</option>\n";
		echo "              </select>"; 
		echo " <input type=\"hidden\" name=\"action\" value=\"updateRecord\" />\n";
		echo "    <input type=\"submit\" value=\"". __("Apply", rb_agencyinteract_TEXTDOMAIN) ."\" name=\"ProfileBulkAction\" class=\"button-secondary\"  />\n";
		  
		
		
		echo "<div class=\"tablenav\">\n";
		echo "  <div class='tablenav-pages'>\n";

			if($items > 0) {
				echo $p->show();  // Echo out the list of paging. 
			}

		echo "  </div>\n";
		echo "</div>\n";
    
		echo "<p class=\"submit\">\n";
		//echo "  <input type=\"hidden\" value=\"deleteRecord\" name=\"action\" />\n";
		//echo "  <input type=\"submit\" value=\"". __('Delete') ."\" class=\"button-primary\" name=\"submit\" />	\n";	
		echo "</p>\n";
		echo "</form>\n";
}

echo "</div>\n";




?>