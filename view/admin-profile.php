<?php

global $wpdb;
define("LabelPlural", "Profile");
define("LabelSingular", "Profiles");

/*
 * Pull Options
 */

	$rb_agency_options_arr = get_option('rb_agency_options');

		$rb_agency_option_showsocial = isset($rb_agency_options_arr['rb_agency_option_showsocial'])?$rb_agency_options_arr['rb_agency_option_showsocial']:0;
		$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;
		$rb_agency_option_agencyimagemaxheight = isset($rb_agency_options_arr['rb_agency_option_agencyimagemaxheight'])?$rb_agency_options_arr['rb_agency_option_agencyimagemaxheight']:800;
			if (empty($rb_agency_option_agencyimagemaxheight) || $rb_agency_option_agencyimagemaxheight < 500) {
				$rb_agency_option_agencyimagemaxheight = 800;
			}

		$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?(int) $rb_agency_options_arr['rb_agency_option_profilenaming']:1;
		$rb_agency_option_locationtimezone = isset($rb_agency_options_arr['rb_agency_option_locationtimezone'])?(int) $rb_agency_options_arr['rb_agency_option_locationtimezone']:0;

		if (function_exists('rb_agency_interact_menu')) {
			// Load Interact Settings
			$rb_agencyinteract_options_arr = get_option('rb_agencyinteract_options');
			$rb_agency_option_useraccountcreation = isset($rb_agency_options_arr['rb_agency_option_useraccountcreation']) ?(int) $rb_agency_options_arr['rb_agency_option_useraccountcreation']:0;
		}

		$rb_agency_option_inactive_profile_on_update = isset($rb_agency_options_arr['rb_agency_option_inactive_profile_on_update'])? $rb_agency_options_arr['rb_agency_option_inactive_profile_on_update']:0;
	

 

// *************************************************************************************************** //
// Handle Post Actions
$errorValidation = array();
if (isset($_POST['action'])) {

	/*
	 * Pull Post Values
	 */

	$ProfileID = isset($_POST['ProfileID'])?$_POST['ProfileID']:0;
	$ProfileUserLinked = isset($_POST['ProfileUserLinked'])?$_POST['ProfileUserLinked']:"";
	$ProfileContactNameFirst = isset($_POST['ProfileContactNameFirst']) ? trim(preg_replace('!\s+!', ' ',$_POST['ProfileContactNameFirst'])):"";
	$ProfileContactNameLast = isset($_POST['ProfileContactNameLast']) ? trim(preg_replace('!\s+!', ' ',$_POST['ProfileContactNameLast'])):"";
	$ProfileContactDisplay = isset($_POST['ProfileContactDisplay']) ? trim(preg_replace('!\s+!', ' ',$_POST['ProfileContactDisplay'])):"";
//	if (empty($ProfileContactDisplay)) {  // Probably a new record... 
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
			$errorValidation['rb_agency_option_profilenaming'] = "<b><i>" . __(LabelSingular . " must have a display name identified", RBAGENCY_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		} elseif ($rb_agency_option_profilenaming == 3) {
			$ProfileContactDisplay = "ID-" . $ProfileID;
		} elseif ($rb_agency_option_profilenaming == 4) {
			$ProfileContactDisplay = $ProfileContactNameFirst;
		} elseif ($rb_agency_option_profilenaming == 5) {
			$ProfileContactDisplay = $ProfileContactNameLast;
		}
	//}

	$ProfileGallery = isset($_POST['ProfileGallery']) ? $_POST['ProfileGallery']:"";

	if (empty($ProfileGallery)) {  // Probably a new record... 
		$ProfileGallery = RBAgency_Common::format_stripchars($ProfileContactDisplay);
	}

	$ProfileGender = isset($_POST['ProfileGender'])?$_POST['ProfileGender']:"";
	$ProfileDateBirth = isset($_POST['ProfileDateBirth'])?$_POST['ProfileDateBirth']:"";
	$ProfileContactEmail = isset($_POST['ProfileContactEmail'])?$_POST['ProfileContactEmail']:"";
	$ProfileUsername = isset($_POST["ProfileUsername"])?$_POST["ProfileUsername"]:"";
	$ProfilePassword = isset($_POST['ProfilePassword'])?$_POST['ProfilePassword']:"";
	$ProfileContactWebsite = isset($_POST['ProfileContactWebsite'])?$_POST['ProfileContactWebsite']:"";
	$ProfileContactPhoneHome = isset($_POST['ProfileContactPhoneHome'])?$_POST['ProfileContactPhoneHome']:"";
	$ProfileContactPhoneCell = isset($_POST['ProfileContactPhoneCell'])?$_POST['ProfileContactPhoneCell']:"";
	$ProfileContactPhoneWork = isset($_POST['ProfileContactPhoneWork'])?$_POST['ProfileContactPhoneWork']:"";
	$ProfileLocationStreet = isset($_POST['ProfileLocationStreet'])?$_POST['ProfileLocationStreet']:"";
	$ProfileLocationCity = RBAgency_Common::format_propercase(isset($_POST['ProfileLocationCity'])?$_POST['ProfileLocationCity']:"");
	$ProfileLocationState = strtoupper(isset($_POST['ProfileLocationState'])?$_POST['ProfileLocationState']:"");
	$ProfileLocationZip = isset($_POST['ProfileLocationZip'])?$_POST['ProfileLocationZip']:"";
	$ProfileLocationCountry = isset($_POST['ProfileLocationCountry'])?$_POST['ProfileLocationCountry']:"";
	$ProfileLanguage = isset($_POST['ProfileLanguage'])?$_POST['ProfileLanguage']:"";
	$ProfileDateUpdated = isset($_POST['ProfileDateUpdated'])?$_POST['ProfileDateUpdated']:"";
	$ProfileDateViewLast = isset($_POST['ProfileDateViewLast'])?$_POST['ProfileDateViewLast']:"";
	$ProfileType = isset($_POST['ProfileType'])?$_POST['ProfileType']:"";

	if (is_array($ProfileType)) {
		$ProfileType = implode(",", $ProfileType);
	}

	$ProfileIsActive = isset($_POST['ProfileIsActive'])?$_POST['ProfileIsActive']:""; // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
	$ProfileIsFeatured = isset($_POST['ProfileIsFeatured'])?$_POST['ProfileIsFeatured']:"";
	$ProfileIsPromoted = isset($_POST['ProfileIsPromoted'])?$_POST['ProfileIsPromoted']:"";
	$ProfileStatHits = isset($_POST['ProfileStatHits'])?$_POST['ProfileStatHits']:"";

	// Get Primary Image
	$ProfileMediaPrimaryID = isset($_POST['ProfileMediaPrimary'])?$_POST['ProfileMediaPrimary']:"";

	// Notify User and Admin
	$ProfileNotifyUser = isset($_POST["ProfileNotifyUser"])?$_POST["ProfileNotifyUser"]:"";

	// Error checking

	$have_error = false;
	if (trim($ProfileContactNameFirst) == "") {
		$errorValidation['ProfileContactNameFirst']= "<b><i>The " . LabelSingular . " must have a name.</i></b><br>";
		$have_error = true;
	}

	if (isset($_GET["action"]) && $_GET["action"] == "add") {
		$userdata = array(
			'user_pass' => esc_attr($ProfilePassword),
			'user_login' => esc_attr($ProfileUsername),
			'first_name' => esc_attr($ProfileContactNameFirst),
			'last_name' => esc_attr($ProfileContactNameLast),
			'user_email' => esc_attr($ProfileContactEmail),
			'role' => get_option('default_role')
		);

		if(function_exists('rb_agency_interact_menu')){
				if (empty($userdata['user_login'])) {
						$errorValidation['user_login'] = __("A username is required for registration.<br />", RBAGENCY_TEXTDOMAIN);
						$have_error = true;
				}
				if (username_exists($userdata['user_login'])) {
						$errorValidation['user_login'] = __("Sorry, that username already exists!<br />", RBAGENCY_TEXTDOMAIN);
						$have_error = true;
				}
				if (isset($userdata['user_password']) && !$userdata['user_password'] && count($userdata['user_password']) > 5) {
						$errorValidation['user_password']= __("A password is required for registration and must have 6 characters.<br />", RBAGENCY_TEXTDOMAIN);
						$have_error = true;
				}

			// Validate Email
				if (!is_email($userdata['user_email'])) {
					$errorValidation['ProfileContactEmail']= __("You must enter a valid email address.<br />", RBAGENCY_TEXTDOMAIN);
					$have_error = true;
				}
				if (email_exists($userdata['user_email'])) {
					$errorValidation['ProfileContactEmail']= __("Sorry, that email address is already used!<br />", RBAGENCY_TEXTDOMAIN);
					$have_error = true;
				} else {
					if (rb_check_exists($ProfileContactEmail,'ProfileContactEmail','text')) {
						$errorValidation['ProfileContactEmail']= __("Sorry, that email address is already used!<br />", RBAGENCY_TEXTDOMAIN);
						$have_error = true;
					}
				}

		}

	}

	if ($_POST["action"] == "editRecord") {
		if($ProfileContactEmail != $_POST['HiddenContactEmail']){
			if (!is_email($ProfileContactEmail)) {
				$errorValidation['ProfileContactEmail']= __("You must enter a valid email address.<br />", RBAGENCY_TEXTDOMAIN);
				$have_error = true;
			}
			if (rb_check_exists($ProfileContactEmail,'ProfileContactEmail','text')) {
				$errorValidation['ProfileContactEmail']= __("Sorry, that email address is already used!<br />", RBAGENCY_TEXTDOMAIN);
				$have_error = true;
			}
		}
	}

	// Get Post State
	$action = $_POST['action'];
	switch ($action) {

		// *************************************************************************************************** //
		// Add Record
		case 'addRecord':
			if (!$have_error) {

				// Bug Free!
				if ($have_error == false) {
					if (function_exists('rb_agency_interact_menu')) {
						$new_user = wp_insert_user($userdata);
					}

					// Check Directory - create directory if does not exist, rename if does
					$ProfileGallery = rb_agency_createdir($ProfileGallery);

					// Create Record
					$insert = "INSERT INTO " . table_agency_profile .
						" (ProfileGallery,
						   ProfileContactDisplay,
						   ProfileUserLinked,
						   ProfileContactNameFirst,
						   ProfileContactNameLast,
						   ProfileContactEmail,
						   ProfileContactWebsite,
						   ProfileGender,
						   ProfileDateBirth,
						   ProfileLocationStreet,
						   ProfileLocationCity,
						   ProfileLocationState,
						   ProfileLocationZip,
						   ProfileLocationCountry,
						   ProfileContactPhoneHome, 
						   ProfileContactPhoneCell, 
						   ProfileContactPhoneWork,
						   ProfileDateUpdated,
						   ProfileType,
						   ProfileIsActive,
						   ProfileIsFeatured,
						   ProfileIsPromoted,
						   ProfileStatHits,
						   ProfileDateViewLast)" .
						"VALUES (
							'" . esc_attr($ProfileGallery) . "',
							'" . esc_attr($ProfileContactDisplay) . "',
							'" . esc_attr(isset($new_user)?$new_user:"") . "',
							'" . esc_attr($ProfileContactNameFirst) . "',
							'" . esc_attr($ProfileContactNameLast) . "',
							'" . esc_attr($ProfileContactEmail) . "',
							'" . esc_attr($ProfileContactWebsite) . "',
							'" . esc_attr($ProfileGender) . "',
							'" . esc_attr($ProfileDateBirth) . "',
							'" . esc_attr($ProfileLocationStreet) . "',
							'" . esc_attr($ProfileLocationCity) . "',
							'" . esc_attr($ProfileLocationState) . "',
							'" . esc_attr($ProfileLocationZip) . "',
							'" . esc_attr($ProfileLocationCountry) . "',
							'" . esc_attr($ProfileContactPhoneHome) . "',
							'" . esc_attr($ProfileContactPhoneCell) . "',
							'" . esc_attr($ProfileContactPhoneWork) . "',
							now(),
							'" . $ProfileType . "',
							'" . esc_attr($ProfileIsActive) . "',
							'" . esc_attr($ProfileIsFeatured) . "',
							'" . esc_attr($ProfileIsPromoted) . "',
							'" . esc_attr($ProfileStatHits) . "',
							'" . esc_attr($ProfileDateViewLast) . "'
						)";
					$results = $wpdb->query($insert);
					$ProfileID = $wpdb->insert_id;
					add_user_meta( $ProfileID, 'rb_agency_interact_profiletype',true);
					


					// Notify admin and user
					if ($ProfileNotifyUser <> "yes" && function_exists('rb_agency_interact_menu')) {
						wp_new_user_notification(isset($new_user)?$new_user:"", $ProfilePassword);
					}
					// Set Display Name as Record ID (We have to do this after so we know what record ID to use... right ;)
					if ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID-" . $ProfileID;
						$ProfileGallery = "ID" . $ProfileID;

						$update = $wpdb->query("UPDATE " . table_agency_profile . " SET ProfileContactDisplay='" . $ProfileContactDisplay . "', ProfileGallery='" . $ProfileGallery . "' WHERE ProfileID='" . $ProfileID . "'");
						$updated = $wpdb->query($update);
					}

					// Add Custom Field Values stored in Mux
					foreach ($_POST as $key => $value) {
						if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {
							$ProfileCustomID = substr($key, 15);
							if (is_array($value)) {
								$value = implode(",", $value);
							}
							$profilecustomfield_date = explode("_",$key);
							
							if(count($profilecustomfield_date) == 2){ // customfield date
								$value = date("y-m-d h:i:s",strtotime($value));
								$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomDateValue) VALUES (%d,%d,%s)", $ProfileID , $ProfileCustomID, $value);
							}else{
								$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue) VALUES (%d,%d,%s)", $ProfileID , $ProfileCustomID, $value);
							}
							$results1 = $wpdb->query($insert1);
						}
					}

					echo ('<div id="message" class="updated"><p>' . __("New Profile added successfully!", RBAGENCY_TEXTDOMAIN) . ' <a href="' . admin_url("admin.php?page=" . $_GET['page']) . '&action=editRecord&ProfileID=' . $ProfileID . '">' . __("Update and add media", RBAGENCY_TEXTDOMAIN) . '</a></p></div>');
					// We can edit it now
					// rb_display_manage($ProfileID);
					// exit;
				}
			} else {
				echo ('<div id="message" class="error"><p>' . __("Error creating record, please ensure you have filled out all required fields.", RBAGENCY_TEXTDOMAIN) . '</p></div>');
				rb_display_manage($ProfileID,$errorValidation);

			}

			break;

		// *************************************************************************************************** //
		// Edit Record
		case 'editRecord':
			if (!empty($ProfileContactNameFirst) && !empty($ProfileID)) {
                
               if($have_error == false){    
               	        if($rb_agency_option_inactive_profile_on_update == 1){
               	        	$ProfileIsActive = 3;
               	        }
										// Update Record
										$update = "UPDATE " . table_agency_profile . " SET 
											ProfileGallery='" . esc_attr($ProfileGallery) . "',
											ProfileContactDisplay='" . esc_attr($ProfileContactDisplay) . "',
											ProfileContactNameFirst='" . esc_attr($ProfileContactNameFirst) . "',
											ProfileContactNameLast='" . esc_attr($ProfileContactNameLast) . "',
											ProfileContactEmail='" . esc_attr($ProfileContactEmail) . "',
											ProfileContactWebsite='" . esc_attr($ProfileContactWebsite) . "',
											ProfileContactPhoneHome='" . esc_attr($ProfileContactPhoneHome) . "',
											ProfileContactPhoneCell='" . esc_attr($ProfileContactPhoneCell) . "',
											ProfileContactPhoneWork='" . esc_attr($ProfileContactPhoneWork) . "',
											ProfileGender='" . esc_attr($ProfileGender) . "',
											ProfileDateBirth ='" . esc_attr($ProfileDateBirth) . "',
											ProfileLocationStreet='" . esc_attr($ProfileLocationStreet) . "',
											ProfileLocationCity='" . esc_attr($ProfileLocationCity) . "',
											ProfileLocationState='" . esc_attr($ProfileLocationState) . "',
											ProfileLocationZip ='" . esc_attr($ProfileLocationZip) . "',
											ProfileLocationCountry='" . esc_attr($ProfileLocationCountry) . "',
											ProfileDateUpdated=now(),
											ProfileType='" . $ProfileType . "',
											ProfileIsActive='" . esc_attr($ProfileIsActive) . "',
											ProfileIsFeatured='" . esc_attr($ProfileIsFeatured) . "',
											ProfileIsPromoted='" . esc_attr($ProfileIsPromoted) . "',
											ProfileStatHits='" . esc_attr($ProfileStatHits) . "'
											WHERE ProfileID=$ProfileID";
										$results = $wpdb->query($update);
										
											update_user_meta(isset($_REQUEST['wpuserid'])?$_REQUEST['wpuserid']:"", 'rb_agency_interact_profiletype', $ProfileType);
											update_user_meta(isset($_REQUEST['wpuserid'])?$_REQUEST['wpuserid']:"", 'rb_agency_interact_pgender', esc_attr($ProfileGender));

										if ($ProfileUserLinked > 0) {
											/* Update WordPress user information. */
											update_user_meta($ProfileUserLinked, 'first_name', esc_attr($ProfileContactNameFirst));
											update_user_meta($ProfileUserLinked, 'last_name', esc_attr($ProfileContactNameLast));
											update_user_meta($ProfileUserLinked, 'nickname', esc_attr($ProfileContactDisplay));
											update_user_meta($ProfileUserLinked, 'display_name', esc_attr($ProfileContactDisplay));
											//update_usermeta($ProfileUserLinked, 'user_email', esc_attr($ProfileContactEmail));
											wp_update_user( array( 'ID' => $ProfileUserLinked,  'user_email' => esc_attr($ProfileContactEmail) ) );
											update_user_meta( $ProfileUserLinked, 'rb_agency_interact_profiletype',true);
					
										}

										// Remove Old Custom Field Values
										$delete1 = "DELETE FROM " . table_agency_customfield_mux . " WHERE ProfileID = \"" . $ProfileID . "\"";
										$results1 = $wpdb->query($delete1);

										// Add New Custom Field Values
										foreach ($_POST as $key => $value) {
											if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value) || $value == 0)) {
												$ProfileCustomID = substr($key, 15);
												if (is_array($value)) {
													$value = implode(",", $value);
												}
												
												$profilecustomfield_date = explode("_",$key);
												
												if(count($profilecustomfield_date) == 2){ // customfield date
													$value = date("y-m-d h:i:s",strtotime($value));
													$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomDateValue)" . " VALUES (%d,%d,%s)",$ProfileID,$ProfileCustomID,$value);
												}else{
													$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue)" . " VALUES (%d,%d,%s)",$ProfileID,$ProfileCustomID,$value);
												}
												$results1 = $wpdb->query($insert1);
											}
										}

										// Check Directory - create directory if does not exist
										$ProfileGallery = rb_agency_checkdir($ProfileGallery);

										// Upload Image & Add to Database
										$i = 1;

										while ($i <= 10) {

											if (isset($_FILES['profileMedia' . $i]['tmp_name']) && $_FILES['profileMedia' . $i]['tmp_name'] != "") {
												$uploadMediaType = $_POST['profileMedia' . $i . 'Type'];
												if ($have_error != true) {
													// Upload if it doesnt exist already
													$path_parts = pathinfo($_FILES['profileMedia' . $i]['name']);
													$safeProfileMediaFilename = RBAgency_Common::format_stripchars($path_parts['filename'] ."_". RBAgency_Common::generate_random_string(6) . "." . $path_parts['extension']);
													$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d1' AND ProfileMediaURL = '%d2'";
													$results = $wpdb->get_results($wpdb->prepare($query, $ProfileID, $safeProfileMediaFilename),ARRAY_A);
													$count =  $wpdb->num_rows;

													if ($count < 1) {
														if ($uploadMediaType == "Image" || $uploadMediaType == "Polaroid") {

															if ($_FILES['profileMedia' . $i]['type'] == "image/pjpeg" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {

																$image = new rb_agency_image();
																$image->load($_FILES['profileMedia' . $i]['tmp_name']);

																if ($image->getHeight() > $rb_agency_option_agencyimagemaxheight) {
																	$image->resizeToHeight($rb_agency_option_agencyimagemaxheight);
																}
																$image->save(RBAGENCY_UPLOADPATH . $ProfileGallery . "/" . $safeProfileMediaFilename);

																// Add to database
																$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
															} else {
																$errorValidation['profileMedia'] = "<b><i>"._("Please upload an image file only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
																$have_error = true;
															}
														} else if ($uploadMediaType == "VoiceDemo") {
															// Add to database
															$MIME = array('audio/mpeg', 'audio/mp3');
															if (in_array($_FILES['profileMedia' . $i]['type'], $MIME)) {
																$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
																move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], RBAGENCY_UPLOADPATH . $ProfileGallery . "/" . $safeProfileMediaFilename);
															} else {
																$errorValidation['profileMedia'] = "<b><i>"._("Please upload a mp3 file only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
																$have_error = true;
															}
														} else if ($uploadMediaType == "Resume") {
															// Add to database
															if ($_FILES['profileMedia' . $i]['type'] == "application/msword" || $_FILES['profileMedia' . $i]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES['profileMedia' . $i]['type'] == "application/pdf" || $_FILES['profileMedia' . $i]['type'] == "application/rtf") {
																$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
																move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], RBAGENCY_UPLOADPATH . $ProfileGallery . "/" . $safeProfileMediaFilename);
															} else {
																$errorValidation['profileMedia'] = "<b><i>"._("Please upload PDF/MSword/RTF files only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
																$have_error = true;
															}
														} else if ($uploadMediaType == "Headshot") {
															// Add to database
															if ($_FILES['profileMedia' . $i]['type'] == "application/msword" || $_FILES['profileMedia' . $i]['type'] == "application/pdf" || $_FILES['profileMedia' . $i]['type'] == "application/rtf" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
																$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
																move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], RBAGENCY_UPLOADPATH . $ProfileGallery . "/" . $safeProfileMediaFilename);
															} else {
																$errorValidation['profileMedia'] = "<b><i>"._("Please upload PDF/MSWord/RTF/Image files only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
																$have_error = true;
															}
														} else if ($uploadMediaType == "CompCard") {
															// Add to database
															if ($_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
																$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
																move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], RBAGENCY_UPLOADPATH . $ProfileGallery . "/" . $safeProfileMediaFilename);
															} else {
																$errorValidation['profileMedia'] = "<b><i>"._("Please upload jpeg or png files only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
																$have_error = true;
															}
														} 
														// Custom Media Categories
														else if (strpos($uploadMediaType,"rbcustommedia") !== false) {
															// Add to database
															$custom_media_info = explode("_",$uploadMediaType);
															$custom_media_title = $custom_media_info[1];
															$custom_media_type = $custom_media_info[2];
															$custom_media_extenstion = $custom_media_info[3];
															$arr_extensions = array();

															array_push($arr_extensions, $custom_media_extenstion);
															
															if($custom_media_extenstion == "doc"){
																array_push($arr_extensions,"application/octet-stream");
																array_push($arr_extensions,"docx");
															}elseif($custom_media_extenstion == "mp3"){
																array_push($arr_extensions,"audio/mpeg");
																array_push($arr_extensions,"audio/mp3");
															}elseif($custom_media_extenstion == "pdf"){
																array_push($arr_extensions,"application/pdf");
															}elseif($custom_media_extenstion == "jpg"){
																array_push($arr_extensions,"image/jpeg");
																array_push($arr_extensions,"jpeg");
															}

															if (in_array($_FILES['profileMedia' . $i]['type'], $arr_extensions)) {
																$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
																move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], RBAGENCY_UPLOADPATH . $ProfileGallery . "/" . $safeProfileMediaFilename);
															} else {
																$errorValidation['profileMedia'] = "<b><i>".__("Please upload ".$custom_media_extenstion." files only", RBAGENCY_TEXTDOMAIN)."</i></b><br />";
																$have_error = true;

															}
														}
													} // End count
												} // End have error = false
											} //End:: if profile media is not empty.
											$i++;
										} // endwhile           
										// Upload Videos to Database
														if (isset($_POST['profileMediaV1']) && !empty($_POST['profileMediaV1'])) {
															$profileMediaType = $_POST['profileMediaV1Type'];
															$profileMediaTitle = $_POST['media1_title'] ."<br>". $_POST['media1_caption'];
															$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV1']);
															$profileVideoType = rb_agency_get_videotype($_POST['profileMediaV1']);
															$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileVideoType) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaTitle . "','" . $profileMediaURL . "','".$profileVideoType."')");
															
														}
														if (isset($_POST['profileMediaV2']) && !empty($_POST['profileMediaV2'])) {
															$profileMediaType = $_POST['profileMediaV2Type'] ;
															$profileMediaTitle = $_POST['media2_title'] ."<br>". $_POST['media2_caption'];
															$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV2']);
															$profileVideoType =rb_agency_get_videotype($_POST['profileMediaV2']);
															$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileVideoType) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaTitle . "','" . $profileMediaURL . "','".$profileVideoType."')");
														}
														if (isset($_POST['profileMediaV3']) && !empty($_POST['profileMediaV3'])) {
															$profileMediaType = $_POST['profileMediaV3Type'] ;
															$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV3']);
															$profileMediaTitle = $_POST['media3_title'] ."<br>". $_POST['media3_caption'];
															$profileVideoType = rb_agency_get_videotype($_POST['profileMediaV3']);
															$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileVideoType) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaTitle . "','" . $profileMediaURL . "','".$profileVideoType."')");
														}

										/* --------------------------------------------------------- CLEAN THIS UP -------------- */
										// Do we have a custom image yet? Lets just set the first one as primary.
										$results = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaType = 'Image' AND ProfileMediaPrimary='1'";
										$results=  $wpdb->get_results($wpdb->prepare($results, $ProfileID),ARRAY_A);
										$count =  $wpdb->num_rows;
										if ($count < 1) {

											$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaType = 'Image' LIMIT 0, 1";
											$resultsNeedOne=  $wpdb->get_results($wpdb->prepare($query, $ProfileID),ARRAY_A);
											$count =  $wpdb->num_rows;
											foreach ($resultsNeedOne as $dataNeedOne) {
												$resultsFoundOne = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='1' WHERE ProfileID='" . $ProfileID . "' AND ProfileMediaID = '" . $dataNeedOne['ProfileMediaID'] . "'");
												break;
											}
										}
										if ($ProfileMediaPrimaryID > 0) {
											// Update Primary Image
											$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='0' WHERE ProfileID=$ProfileID");
											$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='1' WHERE ProfileID=$ProfileID AND ProfileMediaID=$ProfileMediaPrimaryID");
										}
										// Update Image order 
										$ProfileMediaOrder1 =  array();
										$ProfileMediaOrder2 =  array();
										foreach ($_POST as $key => $val) {
											if (substr($key,0,18) == "ProfileMediaOrder_") {
												 $pieces = explode("_", $key);
												if($pieces[1]>0){
													if($val!=""){
														$ProfileMediaOrder1[(int)$pieces[1]] = (int) $val ; 
													}else{
														$ProfileMediaOrder2[(int)$pieces[1]] = (int) $val ; 
													}
														
												}
											}

										}
										asort($ProfileMediaOrder1);
										$imedia=1; 
										if(is_array($ProfileMediaOrder1) && count($ProfileMediaOrder1)){
											foreach($ProfileMediaOrder1 as $key => $val){
												$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaOrder='".$val."' WHERE ProfileID=$ProfileID AND ProfileMediaID=$key");
												 $imedia++; 
											}
										}
										if(is_array($ProfileMediaOrder2) && count($ProfileMediaOrder2)){
											foreach($ProfileMediaOrder2 as $key => $val){
												 $results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaOrder='".$val."' WHERE ProfileID=$ProfileID AND ProfileMediaID=$key");
												 $imedia++; 
											}
										}

				 }// if have_error == false
									
										/* --------------------------------------------------------- CLEAN THIS UP -------------- */
										if(!$have_error){
												echo ("<div id=\"message\" class=\"updated\"><p>" . __("Profile updated successfully", RBAGENCY_TEXTDOMAIN) . "! </a></p></div>");
										}else{
											foreach($errorValidation as $Error => $error){
												echo ("<div id=\"message\" class=\"error\"><p>" . __($error, RBAGENCY_TEXTDOMAIN) . "</p></div>");
											}
										}
												
			 	
			} else {
				echo ("<div id=\"message\" class=\"error\"><p>" . __("Error updating record, please ensure you have filled out all required fields.", RBAGENCY_TEXTDOMAIN) . "</p></div>");
			
			}

			if($have_error == false && isset($_GET["action"]) && $_GET["action"] !="editRecord"){
					$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaPrimary = 1";
					$results = $wpdb->get_results($wpdb->prepare($query, $ProfileID),ARRAY_A);
					$count =  $wpdb->num_rows;
					if($count <= 0){
						$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID=%d";
						$results = current($wpdb->get_results($wpdb->prepare($query, $ProfileID),ARRAY_A));
						$wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='0' WHERE ProfileID=".($ProfileID)." ");
					    $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaPrimary='1' WHERE ProfileID=".($ProfileID)." AND ProfileMediaID=".(isset($results["ProfileMediaID"])?$results["ProfileMediaID"]:"0"));
					}
				
					
			}
			rb_display_manage($ProfileID,$errorValidation);
			break;

		// *************************************************************************************************** //
		// Delete bulk
		case 'deleteRecord':
		    $profiles_count = 0;
			foreach ($_POST as $ProfileID) {
                
                if(is_numeric($ProfileID)){
						// Verify Record
						$queryDelete = "SELECT * FROM " . table_agency_profile . " WHERE ProfileID =  '%d'";
						
						$resultsDelete=  $wpdb->get_results($wpdb->prepare($queryDelete, $ProfileID),ARRAY_A);
						

						foreach ($resultsDelete as $dataDelete) {
							$profiles_count++;
							$ProfileGallery = $dataDelete['ProfileGallery'];

							// Remove Profile
							$delete = "DELETE FROM " . table_agency_profile . " WHERE ProfileID = %d ";
							$results = $wpdb->query($wpdb->prepare($delete,$ProfileID));
							// Remove Media
							$delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = %d ";
							$results = $wpdb->query($wpdb->prepare($delete,$ProfileID));

								if (isset($ProfileGallery)) {
									// Remove Folder
									$dir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/";
									$mydir = @opendir($dir);
									while (false !== ($file = @readdir($mydir))) {
										if ($file != "." && $file != "..") {
											if(@is_file($dir . $file))
											@unlink($dir . $file);// or die("<div id=\"message\" class=\"error\"><p>" . __("Error removing file:", RBAGENCY_TEXTDOMAIN) . $dir . $file . "</p></div>");
										}
									}
									// Remove Directory
									if (@is_dir($dir)) {
										@rmdir($dir);// or die("<div id=\"message\" class=\"error\"><p>" . __("Error removing directory:", RBAGENCY_TEXTDOMAIN) . $dir . $file . "</p></div>");
									}
									@closedir($mydir);
								} else {
									echo ("<div id=\"message\" class=\"error\"><p>" . __("No Valid Record Found.", RBAGENCY_TEXTDOMAIN) . "</p></div>");
								}

								//---------- Delete users but re-assign to Admin User -------------//
								// Gimme an admin:
								/*$AdminID = $wpdb->prepare("SELECT $wpdb->users.ID FROM $wpdb->users WHERE user_login = %s","admin");
								if ($AdminID > 0) {

								} else {
									$AdminID = 1;
								}*/
								/// Now delete
								wp_delete_user($dataDelete["ProfileUserLinked"]);
						} // foreach || is there record?
				} // is numeric
			}
			if($profiles_count >  1){
					echo ('<div id="message" class="updated"><p>' . __("$profiles_count Profiles deleted successfully!", RBAGENCY_TEXTDOMAIN) . '</p></div>');
			}else{
					echo ('<div id="message" class="updated"><p>' . __("Profile deleted successfully!", RBAGENCY_TEXTDOMAIN) . '</p></div>');
			}
			rb_display_list();
			break;
	}
}

// *************************************************************************************************** //
// Delete Single
elseif (isset($_GET['action']) && $_GET['action'] == "deleteRecord") {

	$ProfileID = $_GET['ProfileID'];
	// Verify Record
	$queryDelete = "SELECT * FROM " . table_agency_profile . " WHERE ProfileID =  '%d'";
	$resultsDelete=  $wpdb->get_results($wpdb->prepare($queryDelete, $ProfileID),ARRAY_A);
	foreach ($resultsDelete as $dataDelete) {
		$ProfileGallery = $dataDelete['ProfileGallery'];

		// Remove Profile
		$delete = "DELETE FROM " . table_agency_profile . " WHERE ProfileID = \"" . $ProfileID . "\"";
		$results = $wpdb->query($delete);
		// Remove Media
		$delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = \"" . $ProfileID . "\"";
		$results = $wpdb->query($delete);

		if (isset($ProfileGallery)) {
			// Remove Folder
			$dir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/";
			$mydir = @opendir($dir);
			while (false !== ($file = @readdir($mydir))) {
				if ($file != "." && $file != "..") {
					@unlink($dir . $file); // or DIE("couldn't delete $dir$file<br />");
				}
			}
			// remove dir
			if (is_dir($dir)) {
				rmdir($dir);// or DIE("couldn't delete $dir$file folder not exist<br />");
			}
			closedir($mydir);
		} else {
			echo __("No valid record found.", RBAGENCY_TEXTDOMAIN);
		}

		wp_delete_user($dataDelete["ProfileUserLinked"]);
		echo ('<div id="message" class="updated"><p>' . __("Profile deleted successfully!", RBAGENCY_TEXTDOMAIN) . '</p></div>');
	} // is there record?
	rb_display_list();
}

// *************************************************************************************************** //
// Show Edit Record
elseif ((isset($_GET['action']) && $_GET['action'] == "editRecord") || (isset($_GET['action']) && $_GET['action'] == "add")) {

	$action = $_GET['action'];
	$ProfileID = isset($_GET['ProfileID'])?$_GET['ProfileID']:0;

	rb_display_manage($ProfileID,$errorValidation);
} else {
// *************************************************************************************************** //
// Show List
	rb_display_list();
}

// *************************************************************************************************** //
// Manage Record
function rb_display_manage($ProfileID, $errorValidation) {

	global $wpdb;

	$rb_agency_options_arr = get_option('rb_agency_options');

	// Unit Type
	$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;

	// Social
	if (isset($rb_agency_options_arr['rb_agency_option_showsocial'])) {
		$rb_agency_option_showsocial = $rb_agency_options_arr['rb_agency_option_showsocial'];
	} else {
		$rb_agency_option_showsocial = false;
	}

	// Maximum Height
	$rb_agency_option_agencyimagemaxheight = isset($rb_agency_options_arr['rb_agency_option_agencyimagemaxheight'])?$rb_agency_options_arr['rb_agency_option_agencyimagemaxheight']:0;
	if (empty($rb_agency_option_agencyimagemaxheight) || $rb_agency_option_agencyimagemaxheight < 500) {
		$rb_agency_option_agencyimagemaxheight = 800;
	}

	// Naming Convention
	$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?(int) $rb_agency_options_arr['rb_agency_option_profilenaming']:0;

	// Default Country
	$rb_agency_option_locationcountry = isset($rb_agency_options_arr['rb_agency_option_locationcountry'])?$rb_agency_options_arr['rb_agency_option_locationcountry']:0;
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.imperial_metrics').keyup(function(){
			var vals = jQuery(this).val();
			var new_val = extractNumber(vals,2,false);
			if(new_val !== true){
				jQuery(this).nextAll('.error_msg').eq(0).html('*Non numeric value is not accepted');
				new_val.replace(/[^/\d*\.*]/g,'');
				jQuery(this).val(new_val);
			}
		});
		jQuery('.imperial_metrics').focusout(function(){
			var vals = jQuery(this).val();
			var new_val = extractNumber(vals,2,false);
			if(new_val !== true){
				jQuery(this).nextAll('.error_msg').eq(0).html('*Non numeric value is not accepted');
				new_val.replace(/[^/\d*\.*]/g,'');
				jQuery(this).val(new_val);
			} else {
				jQuery(this).nextAll('.error_msg').eq(0).html('');
			}
		});		
	});
	function extractNumber(obj, decimalPlaces, allowNegative)
	{
		var temp = obj; var reg0Str = '[0-9]*';
		if (decimalPlaces > 0) { 
			reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
		} else if (decimalPlaces < 0) {
			reg0Str += '\\.?[0-9]*';
		}
		reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
		reg0Str = reg0Str + '$';
		var reg0 = new RegExp(reg0Str);
		if (reg0.test(temp)) return true;
		var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
		var reg1 = new RegExp(reg1Str, 'g');
		temp = temp.replace(reg1, '');
		if (allowNegative) {
			var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
			var reg2 = /-/g;
			temp = temp.replace(reg2, '');
			if (hasNegative) temp = '-' + temp;
		}
		if (decimalPlaces != 0) {
			var reg3 = /\./g;
			var reg3Array = reg3.exec(temp);
			if (reg3Array != null) {
				var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
				reg3Right = reg3Right.replace(reg3, '');
				reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
				temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
			}
		}
		
		return temp;
	}
	</script>
	<?php
	// Add Header
	echo "<div class=\"wrap\">\n";
	// Include Admin Menu
	include (RBAGENCY_PLUGIN_DIR ."view/partial/admin-menu.php"); 

	if (!empty($ProfileID) && ($ProfileID > 0)) {

		$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileID='$ProfileID'";
	
		$results=  $wpdb->get_results($query,ARRAY_A);
		$count  = $wpdb->num_rows;
		foreach ($results as $data) {

			$ProfileID = $data['ProfileID'];
			$ProfileUserLinked = $data['ProfileUserLinked'];
			$ProfileGallery = stripslashes($data['ProfileGallery']);
			$ProfileContactDisplay = stripslashes($data['ProfileContactDisplay']);
			$ProfileContactNameFirst = stripslashes($data['ProfileContactNameFirst']);
			$ProfileContactNameLast = stripslashes($data['ProfileContactNameLast']);
			$ProfileContactEmail = stripslashes($data['ProfileContactEmail']);
			$ProfileContactWebsite = stripslashes($data['ProfileContactWebsite']);
			$ProfileContactLinkFacebook = stripslashes($data['ProfileContactLinkFacebook']);
			$ProfileContactLinkTwitter = stripslashes($data['ProfileContactLinkTwitter']);
			$ProfileContactLinkYouTube = stripslashes($data['ProfileContactLinkYoutube']);
			$ProfileContactLinkFlickr = stripslashes($data['ProfileContactLinkFlickr']);
			$ProfileContactPhoneHome = stripslashes($data['ProfileContactPhoneHome']);
			$ProfileContactPhoneCell = stripslashes($data['ProfileContactPhoneCell']);
			$ProfileContactPhoneWork = stripslashes($data['ProfileContactPhoneWork']);
			$ProfileGender = stripslashes($data['ProfileGender']);
			$ProfileTypeArray = stripslashes($data['ProfileType']);
			$ProfileDateBirth = stripslashes($data['ProfileDateBirth']);
			$ProfileLocationStreet = stripslashes($data['ProfileLocationStreet']);
			$ProfileLocationCity = stripslashes($data['ProfileLocationCity']);
			$ProfileLocationState = stripslashes($data['ProfileLocationState']);
			$ProfileLocationZip = stripslashes($data['ProfileLocationZip']);
			$ProfileLocationCountry = stripslashes($data['ProfileLocationCountry']);
			$ProfileDateUpdated = stripslashes($data['ProfileDateUpdated']);
			$ProfileType = $data['ProfileType'];
			$ProfileIsActive = stripslashes($data['ProfileIsActive']);
			$ProfileIsFeatured = stripslashes($data['ProfileIsFeatured']);
			$ProfileIsPromoted = stripslashes($data['ProfileIsPromoted']);
			$ProfileStatHits = stripslashes($data['ProfileStatHits']);
			$ProfileDateViewLast = stripslashes($data['ProfileDateViewLast']);
		}

		$caption_header = __("Edit", RBAGENCY_TEXTDOMAIN) . " " . LabelSingular;
		$caption_text = __("Make changes in the form below to edit a", RBAGENCY_TEXTDOMAIN) . " " . LabelSingular . ".";
	} else {

		// Set default values for new records
		$ProfilesModelDate = isset($date)?$date:"";
		$ProfileType = 1;
		$ProfileGender = "Unknown";
		$ProfileIsActive = 1;

		/*
		 * Pull Post Values and  Form value should not lost on error @Satya 12/12/2013
		 */
		if (isset($_POST['action'])) {
			$ProfileID = $_POST['ProfileID'];
			$ProfileUserLinked = $_POST['ProfileUserLinked'];
			$ProfileContactNameFirst = trim($_POST['ProfileContactNameFirst']);
			$ProfileContactNameLast = trim($_POST['ProfileContactNameLast']);
			$ProfileContactDisplay = trim($_POST['ProfileContactDisplay']);
			$ProfileGallery = $_POST['ProfileGallery'];
			$ProfileGender = $_POST['ProfileGender'];
			$ProfileDateBirth = $_POST['ProfileDateBirth'];
			$ProfileContactEmail = $_POST['ProfileContactEmail'];
			$ProfileUsername = $_POST["ProfileUsername"];
			$ProfilePassword = $_POST['ProfilePassword'];
			$ProfileContactWebsite = $_POST['ProfileContactWebsite'];
			$ProfileContactPhoneHome = $_POST['ProfileContactPhoneHome'];
			$ProfileContactPhoneCell = $_POST['ProfileContactPhoneCell'];
			$ProfileContactPhoneWork = $_POST['ProfileContactPhoneWork'];
			$ProfileLocationStreet = $_POST['ProfileLocationStreet'];
			$ProfileLocationCity = RBAgency_Common::format_propercase($_POST['ProfileLocationCity']);
			$ProfileLocationState = strtoupper($_POST['ProfileLocationState']);
			$ProfileLocationZip = $_POST['ProfileLocationZip'];
			$ProfileLocationCountry = $_POST['ProfileLocationCountry'];
			$ProfileLanguage = $_POST['ProfileLanguage'];
			$ProfileDateUpdated = $_POST['ProfileDateUpdated'];
			$ProfileDateViewLast = $_POST['ProfileDateViewLast'];
			$ProfileType = $_POST['ProfileType'];
			if (is_array($ProfileType)) {
				$ProfileType = implode(",", $ProfileType);
			}
			$ProfileIsActive = $_POST['ProfileIsActive']; // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
			$ProfileIsFeatured = $_POST['ProfileIsFeatured'];
			$ProfileIsPromoted = $_POST['ProfileIsPromoted'];
			$ProfileStatHits = $_POST['ProfileStatHits'];

			// Get Primary Image
			$ProfileMediaPrimaryID = $_POST['ProfileMediaPrimary'];

			// Notify User and Admin
			$ProfileNotifyUser = $_POST["ProfileNotifyUser"];

		}

		$caption_header = __("Add New ", RBAGENCY_TEXTDOMAIN) . " " . LabelSingular;
		$caption_text = __("Fill in the form below to add a new", RBAGENCY_TEXTDOMAIN) . " " . LabelSingular . ".";
	}
	if ($_GET["action"] == "add") {
		echo "<form id=\"formAddProfile\" method=\"post\" enctype=\"multipart/form-data\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=add&ProfileGender=" . $_GET["ProfileGender"] . "\">\n";
	} else {
		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&ProfileID=".$_GET["ProfileID"]."\">\n";
	}
?>
	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">
			<?php if (!empty($ProfileID) && ($ProfileID > 0)) { ?>
			<a class="button button-primary button-hero" style="float: right; margin-top: 0px;" href="<?php echo RBAGENCY_PROFILEDIR  . $ProfileGallery; ?>" target="_blank">Preview Model</a>
			<?php } ?>
			<h3><?php echo $caption_header; ?> <a class="button button-secondary" href="<?php echo admin_url("admin.php?page=" . $_GET['page']); ?>"><?php echo __("Back to " . LabelSingular . " List", RBAGENCY_TEXTDOMAIN); ?></a></h3>
			<p class="about-description"><?php echo $caption_text; ?> <strong><?php echo __("Required fields are marked", RBAGENCY_TEXTDOMAIN); ?> *</strong></p>
		</div>
	</div>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">

			<!-- Row 1: Column Left Start -->

			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div id="dashboard_rbagency_profile_glance" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo __("Contact Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php

								echo " <table class=\"form-table\">\n";
								echo "  <tbody>\n";
								if ((!empty($ProfileID) && ($ProfileID > 0)) || ($rb_agency_option_profilenaming == 2)) { // Editing Record
									echo "    <tr valign=\"top\">\n";
									echo "      <th scope=\"row\">" . __("Display Name", RBAGENCY_TEXTDOMAIN) . "</th>\n";
									echo "      <td>\n";
									echo "          <input type=\"text\" id=\"ProfileContactDisplay\" name=\"ProfileContactDisplay\" value=\"" . (isset($ProfileContactDisplay)?$ProfileContactDisplay:"") . "\" />\n";
									if(isset($errorValidation['rb_agency_option_profilenaming'])){ echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['rb_agency_option_profilenaming']."</p>\n";} 
									echo "      </td>\n";
									echo "    </tr>\n";
								}
								if (!empty($ProfileID) && ($ProfileID > 0)) { // Editing Record
									echo "    <tr valign=\"top\">\n";
									echo "      <th scope=\"row\">" . __("Gallery Folder", RBAGENCY_TEXTDOMAIN) . "</th>\n";
									echo "      <td>\n";

									if (!empty($ProfileGallery) && is_dir(RBAGENCY_UPLOADPATH . $ProfileGallery)) {
										echo "<div id=\"message\"><span class=\"updated\">" . __("Folder", RBAGENCY_TEXTDOMAIN) . " <strong>" . $ProfileGallery . "</strong> " . __("Exists", RBAGENCY_TEXTDOMAIN) . "</span></div>\n";
										echo "<input type=\"hidden\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"" . $ProfileGallery . "\" />\n";
									} else {
										echo "<input type=\"text\" id=\"ProfileGallery\" name=\"ProfileGallery\" value=\"" . $ProfileGallery . "\" />\n";
										echo "<div id=\"message\"><span class=\"error\">" . __("No Folder Exists", RBAGENCY_TEXTDOMAIN) . "</span>\n";
									}
									echo "              </div>\n";
									echo "      </td>\n";
									echo "  </tr>\n";
								}
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("First Name", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"" . (isset($ProfileContactNameFirst)?$ProfileContactNameFirst:"") . "\" />\n";
								if(isset($errorValidation['ProfileContactNameFirst'])){ echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['ProfileContactNameFirst']."</p>\n";} 
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Last Name", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"" . (isset($ProfileContactNameLast)?$ProfileContactNameLast:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";

								// password
								if ((isset($_GET["action"]) && $_GET["action"] == "add")) {
									$active = get_option('active_plugins');
									foreach($active as $act){
										if(preg_match('/rb-agency-interact\.php/',$act)){
											echo "    <tr valign=\"top\">\n";
											echo "      <th scope=\"row\">" . __("Username", RBAGENCY_TEXTDOMAIN) . "</th>\n";
											echo "      <td>\n";
											echo "          <input type=\"text\" id=\"ProfileUsername\" name=\"ProfileUsername\" />\n";
											if(isset($errorValidation['user_login'])){ echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['user_login']."</p>\n";} 
											echo "      </td>\n";
											echo "    </tr>\n";
											echo "    <tr valign=\"top\">\n";
											echo "      <th scope=\"row\">" . __("Password", RBAGENCY_TEXTDOMAIN) . "</th>\n";
											echo "      <td>\n";
											echo "          <input type=\"text\" id=\"ProfilePassword\" name=\"ProfilePassword\" />\n";
											echo "          <input type=\"button\" onclick=\"javascript:document.getElementById('ProfilePassword').value=Math.random().toString(36).substr(2,6);\" value=\"Generate Password\"  name=\"GeneratePassword\" />\n";
											if(isset($errorValidation['user_password'])){ echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['user_password']."</p>\n";} 
											echo "      </td>\n";
											echo "    </tr>\n";
											echo "    <tr valign=\"top\">\n";
											echo "      <th scope=\"row\">" . __("Send Login details?", RBAGENCY_TEXTDOMAIN) . "</th>\n";
											echo "      <td>\n";
											echo "          <input type=\"checkbox\"  name=\"ProfileNotifyUser\" /> Send login details to the new user and admin by email.\n";
											echo "      </td>\n";
											echo "    </tr>\n";
											break;
										}
									}
								}
								echo " </table>\n";

								?>
							</div>
						</div>
					</div>

					<div id="dashboard_account_information" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Account Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
							<?php
							echo "<table class=\"form-table\">\n";
							echo " <tbody>\n";

							echo "    <tr valign=\"top\">\n";
							echo "      <th scope=\"row\" data=\"this_".$ProfileType."\">" . __("Classification", RBAGENCY_TEXTDOMAIN) . "</th>\n";
							echo "      <td>\n";
							echo "      <fieldset>\n";
							$ProfileType = (@strpos(",", $ProfileType)!= -1) ? explode(",", $ProfileType) : $ProfileType;

							$query3 = "SELECT * FROM " . table_agency_data_type . " ORDER BY DataTypeTitle";
							$results3=  $wpdb->get_results($query3,ARRAY_A);
							$count3  = $wpdb->num_rows;
							$action = @$_GET["action"];
							foreach ($results3 as $data3) {
								if ($action == "add") {
									echo "<input type=\"checkbox\" name=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\" id=\"ProfileType[]\"";
									if(is_array($ProfileType)){
											if (in_array($data3['DataTypeID'], $ProfileType)) {
												echo " checked=\"checked\"";
											} echo "/> " . $data3['DataTypeTitle'] . "<br />\n";
									} else {
											if ($data3['DataTypeID'] == $ProfileType) {
												echo " checked=\"checked\"";
											} echo "/> " . $data3['DataTypeTitle'] . "<br />\n";
									}
								}
								if ($action == "editRecord") {
									echo "<input type=\"checkbox\" name=\"ProfileType[]\" id=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\"";
									if(is_array($ProfileType)){
											if (in_array($data3['DataTypeID'], $ProfileType)) {
												echo " checked=\"checked\"";
											} echo "/> " . $data3['DataTypeTitle'] . "<br />\n";
									} else {
											if ($data3['DataTypeID'] == $ProfileType) {
												echo " checked=\"checked\"";
											} echo "/> " . $data3['DataTypeTitle'] . "<br />\n";
									}
								}
							}
							echo "      </fieldset>\n";
							if ($count3 < 1) {
								echo "" . __("No items to select", RBAGENCY_TEXTDOMAIN) . ". <a href='" . admin_url("admin.php?page=rb_agency_settings&ConfigID=5") . "'>" . __("Setup Options", RBAGENCY_TEXTDOMAIN) . "</a>\n";
							}

							echo "      </td>\n";
							echo "    </tr>\n";
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Status", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td><select id=\"ProfileIsActive\" name=\"ProfileIsActive\">\n";
							echo "            <option value=\"1\"" . selected(1, $ProfileIsActive) . ">" . __("Active", RBAGENCY_TEXTDOMAIN) . "</option>\n";
							echo "            <option value=\"4\"" . selected(4, $ProfileIsActive) . ">" . __("Active - Not Visible On Website", RBAGENCY_TEXTDOMAIN) . "</option>\n";
							echo "            <option value=\"0\"" . selected(0, $ProfileIsActive) . ">" . __("Inactive", RBAGENCY_TEXTDOMAIN) . "</option>\n";
							echo "            <option value=\"2\"" . selected(2, $ProfileIsActive) . ">" . __("Archived", RBAGENCY_TEXTDOMAIN) . "</option>\n";
							echo "            <option value=\"3\"" . selected(3, $ProfileIsActive) . ">" . __("Pending Approval", RBAGENCY_TEXTDOMAIN) . "</option>\n";
							echo "          </select></td>\n";
							echo "    </tr>\n";
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Promotion", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td>\n";
							echo "          <input type=\"checkbox\" name=\"ProfileIsFeatured\" id=\"ProfileIsFeatured\" value=\"1\"". checked(isset($ProfileIsFeatured)?$ProfileIsFeatured:0, 1, false) . " /> Featured<br />\n";
							echo "        </td>\n";
							echo "    </tr>\n";
							/*
							if (function_exists('rb_agency_interact_menu')) {
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Membership", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"checkbox\" name=\"ProfileIsPromoted\" id=\"ProfileIsPromoted\" value=\"1\"". checked($ProfileIsPromoted, 1, false) ." /> Rising Star<br />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
							}
							*/

							if (isset($ProfileUserLinked) && $ProfileUserLinked > 0) {
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("WordPress User", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "        <a href=\"". admin_url("user-edit.php") ."?user_id=". $ProfileUserLinked ."&wp_http_referer=%2Fwp-admin%2Fadmin.php%3Fpage%3Drb_agency_profiles\">ID# ". $ProfileUserLinked ."</a>";
								echo "        <input type='hidden' name='wpuserid' value='".$ProfileUserLinked."' />";
								echo "      </td>\n";
								echo "    </tr>\n";
							}


							// Hidden Settings
							if (isset($_GET["mode"]) && $_GET["mode"] == "override") {
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Date Updated", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileDateUpdated\" name=\"ProfileDateUpdated\" value=\"" . $ProfileDateUpdated . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Profile Views", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileStatHits\" name=\"ProfileStatHits\" value=\"" . $ProfileStatHits . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Profile Viewed Last", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileDateViewLast\" name=\"ProfileDateViewLast\" value=\"" . $ProfileDateViewLast . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
							} else {
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\"></th>\n";
								echo "      <td>\n";
								echo "          <input type=\"hidden\" id=\"ProfileDateUpdated\" name=\"ProfileDateUpdated\" value=\"" . (isset($ProfileDateUpdated)?$ProfileDateUpdated:"") . "\" />\n";
								echo "          <input type=\"hidden\" id=\"ProfileStatHits\" name=\"ProfileStatHits\" value=\"" . (isset($ProfileStatHits)?$ProfileStatHits:"") . "\" />\n";
								echo "          <input type=\"hidden\" id=\"ProfileDateViewLast\" name=\"ProfileDateViewLast\" value=\"" . (isset($ProfileDateViewLast)?$ProfileDateViewLast:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
							}
							echo "  </tbody>\n";
							echo "</table>\n";

?>
							</div>
						</div>
					</div>

					<div id="dashboard_private_information" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Private Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php

								echo " <table class=\"form-table\">\n";
								// Private Information
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Birthdate", RBAGENCY_TEXTDOMAIN) . " <em>YYYY-MM-DD</em></th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileDateBirth\" name=\"ProfileDateBirth\" value=\"" . (isset($ProfileDateBirth) && $ProfileDateBirth !== "0000-00-00"?$ProfileDateBirth:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Email Address", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"" . (isset($ProfileContactEmail)?$ProfileContactEmail:"") . "\" />\n";
								if(isset($errorValidation['ProfileContactEmail'])){ echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['ProfileContactEmail']."</p>\n";} 
									echo "          <input type=\"hidden\" id=\"ProfileContactEmail\" name=\"HiddenContactEmail\" value=\"" . (isset($ProfileContactEmail)?$ProfileContactEmail:"") . "\" />\n";
									echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Website", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactWebsite\" name=\"ProfileContactWebsite\" value=\"" . (isset($ProfileContactWebsite)?$ProfileContactWebsite:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Phone", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "      <fieldset>\n";
								echo "          <label>Home:</label><br /><input type=\"text\" id=\"ProfileContactPhoneHome\" name=\"ProfileContactPhoneHome\" value=\"" . (isset($ProfileContactPhoneHome)?$ProfileContactPhoneHome:"") . "\" /><br />\n";
								echo "          <label>Cell:</label><br /><input type=\"text\" id=\"ProfileContactPhoneCell\" name=\"ProfileContactPhoneCell\" value=\"" . (isset($ProfileContactPhoneCell)?$ProfileContactPhoneCell:"") . "\" /><br />\n";
								echo "          <label>Work:</label><br /><input type=\"text\" id=\"ProfileContactPhoneWork\" name=\"ProfileContactPhoneWork\" value=\"" . (isset($ProfileContactPhoneWork)?$ProfileContactPhoneWork:"") . "\" /><br />\n";
								echo "      </fieldset>\n";
								echo "      </td>\n";
								echo "    </tr>\n";

								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Country", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";

								$query_get ="SELECT * FROM `". table_agency_data_country ."`" ;
								$result_query_get = $wpdb->get_results($query_get);
								$location= site_url();

								echo '<input type="hidden" id="url" value="'.$location.'">';
								echo "<select name=\"ProfileLocationCountry\" id=\"ProfileLocationCountry\"  onchange='javascript:populateStates(\"ProfileLocationCountry\",\"ProfileLocationState\");'>";
								echo '<option value="">'. __("Select country", RBAGENCY_TEXTDOMAIN) .'</option>';
								 foreach($result_query_get as $r){
									  $selected = isset($ProfileLocationCountry) && $ProfileLocationCountry==$r->CountryID?"selected=selected":"";
									echo '<option '.$selected.' value='.$r->CountryID.' >'.$r->CountryTitle.'</option>';
								 }
								echo '</select>';
								echo "      </td>\n";
								echo "    </tr>\n";


								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("State", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								$query_get ="SELECT * FROM `".table_agency_data_state."` WHERE CountryID='".(isset($ProfileLocationCountry)?$ProfileLocationCountry:"")."'" ;
								$result_query_get = $wpdb->get_results($query_get);
								echo '<select name="ProfileLocationState" id="ProfileLocationState">';
								echo '<option value="">'. __("Select state", RBAGENCY_TEXTDOMAIN) .'</option>';
								 foreach($result_query_get as $r){
									 $selected = isset($ProfileLocationState) && $ProfileLocationState==$r->StateID?"selected=selected":"";
									echo '<option '.$selected.' value='.$r->StateID.' >'.$r->StateTitle.'</option>';
								 }
								echo '</select>';

								echo "      </td>\n";
								echo "    </tr>\n";

								// Address
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Street", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" value=\"" . (isset($ProfileLocationStreet)?$ProfileLocationStreet:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("City", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" value=\"" . (isset($ProfileLocationCity)?$ProfileLocationCity:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";


								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Zip", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" value=\"" . (isset($ProfileLocationZip)?$ProfileLocationZip:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";

								// Custom Admin Fields
								// ProfileCustomView = 1 , Private
								if (isset($_GET["ProfileGender"])) {
									$ProfileGender = $_GET["ProfileGender"];
									rb_custom_fields(1, 0, $ProfileGender, true);
									if(!isset($_POST)){
									echo"<script type=\"text/javascript\">
										jQuery(document).ready(function(){
											jQuery(':input,#formAddProfile')
											 .not(':button, :submit, :reset, :hidden')
											 .val('')
											 .removeAttr('checked')
											 .removeAttr('selected');
											jQuery('#ProfileGender option[value=".$ProfileGender."]').attr('selected','selected');
										});
										</script>";
									}
								} else {
									rb_custom_fields(1, $ProfileID, $ProfileGender, true);
								}
								echo " </table>\n";

								?>
							</div>
						</div>
					</div>


				</div>
			</div>

			<!-- Row 1: Column Left End -->

			<!-- Row 1: Column Right Start -->

			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">

					<div id="dashboard_public_information" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Public Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
<?php
							// Public Information
							echo "    <table class=\"rbform-table\">\n";
							echo "    <tr valign=\"top\">\n";
							echo "      <th scope=\"row\">" . __("Gender", RBAGENCY_TEXTDOMAIN) . "</th>\n";
							echo "      <td>";
							echo "			<select name=\"ProfileGender\" id=\"ProfileGender\">\n";

							$ProfileGender1 = get_user_meta(isset($ProfileUserLinked)?$ProfileUserLinked:0, "rb_agency_interact_pgender", true);

							if($ProfileGender==""){
								$ProfileGender = isset($_GET["ProfileGender"])?$_GET["ProfileGender"]:"";
							}elseif($ProfileGender1!=""){
								$ProfileGender =$ProfileGender1 ;
							}

							$query1 = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . "";
							$results1=  $wpdb->get_results($query1,ARRAY_A);
							$count1  = $wpdb->num_rows;
							if ($count1 > 0) {
								if (empty($GenderID) || ($GenderID < 1)) {
									echo " <option value=\"0\" selected>--</option>\n";
								}
								foreach ($results1 as $data1) {
									echo " <option value=\"" . $data1["GenderID"] . "\" " . selected($ProfileGender, $data1["GenderID"]) . ">" . $data1["GenderTitle"] . "</option>\n";
								}
								echo "</select>\n";
							} else {
								echo "" . __("No items to select", rb_restaurant_TEXTDOMAIN) . ".";
							}
							echo "        </td>\n";
							echo "    </tr>\n";
							// Load custom fields , Public  = 0, ProfileCustomGender = true
							// ProfileCustomView = 1 , Private
							if (isset($_GET["ProfileGender"])) {
								$ProfileGender = $_GET["ProfileGender"];
								rb_custom_fields(0, 0, $ProfileGender, true);
							} else {
								rb_custom_fields(0, $ProfileID, $ProfileGender, true);
								
							}

							echo "  </tbody>\n";
							echo " </table>\n";

?>
							</div>
						</div>
					</div>

				</div>
			</div>

			<!-- Row 1: Column Right End -->


		</div>
<?php 	if (!empty($ProfileID) && ($ProfileID > 0)) { // Editing Record ?>
		<div id="dashboard-widgets" class="metabox-holder columns-1">

			<!-- Row 2: Column Left Start -->

			<div id="postbox-container-3" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div id="dashboard_gallery" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Gallery</span></h3>
						<div class="inside">
							<div class="main">
							<?php
						
								//echo "      <h3>" . __("Gallery", RBAGENCY_TEXTDOMAIN) . "</h3>\n";

												echo "<script type='text/javascript'>\n";
												echo "function confirmDelete(delMedia,mediaType) {\n";
												echo "  if (confirm('Are you sure you want to delete this '+mediaType+'?')) {\n";
												echo "  document.location= '" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&ProfileID=" . $ProfileID . "&actionsub=photodelete&targetid='+delMedia;";
												echo "  }\n";
												echo "}\n";
												echo "</script>\n";

												//mass delte
												if (isset($_GET["actionsub"]) && $_GET["actionsub"] == "massphotodelete" && is_array($_GET['targetids'])) {
													$massmediaids = '';
													$massmediaids = implode(",", $_GET['targetids']);
													//get all the images

													$queryImgConfirm = "SELECT ProfileMediaID,ProfileMediaURL FROM " . table_agency_profile_media . " WHERE ProfileID = %d AND ProfileMediaID IN ($massmediaids) AND ProfileMediaType = 'Image' ";
													$resultsImgConfirm = $wpdb->get_results($wpdb->prepare($queryImgConfirm, $ProfileID),ARRAY_A);
													$countImgConfirm = $wpdb->num_roAws;
													$mass_image_data = array();
													foreach ($resultsImgConfirm as $dataImgConfirm) {
														$mass_image_data[$dataImgConfirm['ProfileMediaID']] = $dataImgConfirm['ProfileMediaURL'];
													}
													//delete all the images from database
													$massmediaids = implode(",", array_keys($mass_image_data));
													$queryMassImageDelete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = $ProfileID AND ProfileMediaID IN ($massmediaids) AND ProfileMediaType = 'Image' ";
													$resultsMassImageDelete = $wpdb->query($queryMassImageDelete);
													//delete images on the disk
													$dirURL = RBAGENCY_UPLOADPATH . $ProfileGallery;
													foreach ($mass_image_data as $mid => $ProfileMediaURL) {
														if (!unlink($dirURL . "/" . $ProfileMediaURL)) {
															echo ("<div id=\"message\" class=\"error\"><p>" . __("Error removing", RBAGENCY_TEXTDOMAIN) . " <strong>" . $ProfileMediaURL . "</strong>. " . __("File did not exist.", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
														} else {
															echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> " . __("successfully removed", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
														}
													}
												}

											// Are we deleting?
											if (isset($_GET["actionsub"]) && $_GET["actionsub"] == "photodelete") {
												$deleteTargetID = $_GET["targetid"];

												// Verify Record
												$queryImgConfirm = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaID =  '%d'";
												$resultsImgConfirm = $wpdb->get_results($wpdb->prepare($queryImgConfirm, $ProfileID, $deleteTargetID),ARRAY_A);
												$countImgConfirm = $wpdb->num_rows;
												foreach ($resultsImgConfirm  as $dataImgConfirm) {

													$ProfileMediaID = $dataImgConfirm['ProfileMediaID'];
													$ProfileMediaType = $dataImgConfirm['ProfileMediaType'];
													$ProfileMediaURL = $dataImgConfirm['ProfileMediaURL'];

													// Remove Record
													$delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaID=$ProfileMediaID";
													$results = $wpdb->query($delete);

													if ($ProfileMediaType == "Demo Reel" || $ProfileMediaType == "Video Monologue" || $ProfileMediaType == "Video Slate") {
														echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> " . __("successfully removed", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
													} else {
														// Remove File
														$dirURL = RBAGENCY_UPLOADPATH . $ProfileGallery;
														if (!unlink($dirURL . "/" . $ProfileMediaURL)) {
															echo ("<div id=\"message\" class=\"error\"><p>" . __("Error removing", RBAGENCY_TEXTDOMAIN) . " <strong>" . $ProfileMediaURL . "</strong>. " . __("File did not exist.", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
														} else {
															echo ("<div id=\"message\" class=\"updated\"><p>File <strong>'. $ProfileMediaURL .'</strong> " . __("successfully removed", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
														}
													}
												} // is there record?
											}
											// Go about our biz-nazz
											# rb_agency_option_galleryorder
											# 1 - recent 0 - chronological
											$rb_agency_options_arr = get_option('rb_agency_options');
											$order = isset( $rb_agency_options_arr['rb_agency_option_galleryorder'])?$rb_agency_options_arr['rb_agency_option_galleryorder']:0;
											$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
											$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
											$countImg =$wpdb->num_rows;
											$massDelete = "";

											foreach ($resultsImg as $dataImg) {
												if ($dataImg['ProfileMediaPrimary']) {
													$toggleClass = " primary";
													$isChecked = " checked";
													$isCheckedText = " Primary";
													if ($countImg == 1) {
														$toDelete = "<a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
														$massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> Select';
													} else {
														$toDelete = "<a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
														$massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> Select';
													}
												} else {
													$toggleClass = "";
													$isChecked = "";
													$isCheckedText = " Set Primary";
													$toDelete = "<a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
													$massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> Select';
												}
												echo "<div class=\"gallery-item".$toggleClass."\">\n";
												echo $toDelete;
												// <img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."&a=t&w=120&h=108\" /></a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
												echo "  <div class=\"photo\"><img src=\"" . get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataImg['ProfileMediaURL'] . "&a=t&w=100&h=150\"/></div>\n";
												echo "    	<div class=\"item-order\">Order: <input type=\"text\" name=\"ProfileMediaOrder_" . $dataImg['ProfileMediaID'] . "\" style=\"width: 25px\" value=\"" . $dataImg['ProfileMediaOrder'] . "\" /></div>";
												echo "  	<div class=\"make-primary\"><input type=\"radio\" name=\"ProfileMediaPrimary\" value=\"" . $dataImg['ProfileMediaID'] . "\" " . $isChecked . " /> " . $isCheckedText . "</div>";																		
												echo "    	<div>".$massDelete."</div>";
												echo "  </div>\n";

											}
												if ($countImg < 1) {
													echo "<div>" . __("There are no images loaded for this profile yet.", RBAGENCY_TEXTDOMAIN) . "</div>\n";
												}

								echo "      <div style=\"clear: both;\"></div>\n";
								echo '<a href="javascript:confirm_mass_gallery_delete();">Delete Selected Images</a>';
								echo '<script language="javascript">';
								echo 'function confirm_mass_gallery_delete(){';
								echo 'jQuery(document).ready(function() {';
								echo "var mas_del_ids = '&';";
								echo 'jQuery("input:checkbox[name=massgaldel]:checked").each(function() {';
								echo "if(mas_del_ids != '&'){";
								echo "mas_del_ids += '&';";
								echo '}';

								echo "mas_del_ids += 'targetids[]='+jQuery(this).val();";
								echo "});";

								echo "if( mas_del_ids != '&'){ ";
								echo 'if(confirm("Do you want to delete all the selected images?")){';



								echo "urlmassdelete = '" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&ProfileID=" . $ProfileID . "&actionsub=massphotodelete' + mas_del_ids;";
								echo 'document.location = urlmassdelete;';
								echo '}
								}
								else{
									alert("You have to select images to delete");
								}
							});

							}
							</script>';
					
							?>
							</div>
						</div>
					</div>

				</div>
			</div>

			<!-- Row 1: Column Right End -->


		</div>

		<div id="dashboard-widgets" class="metabox-holder columns-1">

			<!-- Row 2: Column Left Start -->

			<div id="postbox-container-3" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div id="dashboard_media" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Media</span></h3>
						<div class="inside">
							<div class="main">

							<?php
							echo "      <p>" . __("The following files (pdf, audio file, etc.) are associated with this record", RBAGENCY_TEXTDOMAIN) . ".</p>\n";

							$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType <> \"Image\"";
							$resultsMedia =  $wpdb->get_results($wpdb->prepare($queryMedia, $ProfileID),ARRAY_A);
							$countMedia = $wpdb->num_rows;
							$outVideoMedia = "";
							$outLinkVoiceDemo = "";
							$outLinkResume = "";
							$outLinkHeadShot = "";
							$outLinkPolaroid = "";
							$outLinkComCard = "";
							$outCustomMediaLink = "";
							$outSoundCloud = "";
							
							foreach ($resultsMedia  as $dataMedia) {
								if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
									if($dataMedia['ProfileVideoType'] == "" || $dataMedia['ProfileVideoType'] == "youtube"){
										$outVideoMedia .= "<div class=\"media-file voice-demo\">" . $dataMedia['ProfileMediaType'] . "<br />" . rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) . "<br /><a href=\"" . $dataMedia['ProfileMediaURL'] . "\" title=\"".ucfirst($dataMedia['ProfileVideoType'])."\" target=\"_blank\">".ucfirst($dataMedia['ProfileVideoType'])." Video</a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
									}elseif($dataMedia['ProfileVideoType'] == "vimeo"){
											$outVideoMedia .=  "<div class=\"media-file\">" . $dataMedia['ProfileMediaType'] . "<br />" . rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) . "<br /><a href=\"" . $dataMedia['ProfileMediaURL'] . "\" title=\"".ucfirst($dataMedia['ProfileVideoType'])."\" target=\"_blank\">".ucfirst($dataMedia['ProfileVideoType'])." Video</a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
									}else{
											$outVideoMedia .=  "<div class=\"media-file\">" . $dataMedia['ProfileMediaType'] . "<br />" . rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) . "<br /><a href=\"" . $dataMedia['ProfileMediaURL'] . "\" title=\"Watch Video\" target=\"_blank\">Watch Video</a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]</div>\n";
									}
			 					} elseif ($dataMedia['ProfileMediaType'] == "VoiceDemo") {
									$outLinkVoiceDemo .= "<div class=\"media-file resume\"><span>" . $dataMedia['ProfileMediaType'] . "</span><br /><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"link-icon\">mp3</a>[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
								} elseif ($dataMedia['ProfileMediaType'] == "Resume") {
									$outLinkResume .= "<div class=\"media-file resume\"><span>" .$dataMedia['ProfileMediaType'] . "</span><br /><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\" title=\"" . $dataMedia['ProfileMediaTitle'] . "\" class=\"link-icon\">pdf</a><br /><span>[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
								} elseif ($dataMedia['ProfileMediaType'] == "Headshot") {
									$outLinkHeadShot .= "<div class=\"media-file\"><span>" . $dataMedia['ProfileMediaType'] . "</span><br /><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."&a=t&w=120&h=108\" /></a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
								} elseif ($dataMedia['ProfileMediaType'] == "Polaroid" || $dataMedia['ProfileMediaType'] == "CompCard" ) {
									$outLinkPolaroid .= "<div class=\"media-file\"><span>" . $dataMedia['ProfileMediaType'] . "</span><br /><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."&w=120&h=108\" /><br/><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\"></a>[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
								}else if (strpos($dataMedia['ProfileMediaType'] ,"rbcustommedia") !== false) { 
									 $custom_media_info = explode("_",$dataMedia['ProfileMediaType']);
									$custom_media_title = str_replace("-"," ",$custom_media_info[1]);
									 $custom_media_type = $custom_media_info[2];
									   $custom_media_id = $custom_media_info[4];
									             $query = current($wpdb->get_results("SELECT MediaCategoryTitle, MediaCategoryFileType FROM  ".table_agency_data_media." WHERE MediaCategoryID='".$custom_media_id."'",ARRAY_A));
									
									$outCustomMediaLink .= "<div class=\"media-file\"><span style=\"text-transform: capitalize !important;\">".(isset($query["MediaCategoryTitle"])?$query["MediaCategoryTitle"]:$custom_media_title) ." </span> <a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" class=\"custom_media-box-a\" target=\"_blank\"><div class=\"custom_media-box\"><span>" .  (isset($query["MediaCategoryFileType"])?$query["MediaCategoryFileType"]:$custom_media_type)."</span></div></a> [<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
								}elseif ($dataMedia['ProfileMediaType'] == "SoundCloud") {
									    $outSoundCloud .= "<div style=\"width:600px;float:left;padding:10px;\">";
										$outSoundCloud .= "<span>" . $dataMedia['ProfileMediaType'] . " - <a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a></span> \n";
										$outSoundCloud .= "<object height=\"81\" width=\"100%\">";
										$outSoundCloud .= "<param name=\"movie\" value=\"http://player.soundcloud.com/player.swf?&url=".$dataMedia['ProfileMediaURL']."\"></param>";
										$outSoundCloud .=  "<param name=\"allowscriptaccess\" value=\"always\"></param>";
										$outSoundCloud .=  "<embed";
										$outSoundCloud .=  "src=\"http://player.soundcloud.com/player.swf?&url=".$dataMedia['ProfileMediaURL']."\"";
										$outSoundCloud .=  "allowscriptaccess=\"always\" height=\"81\"  type=\"application/x-shockwave-flash\" width=\"100%\">";
										$outSoundCloud .=  "</embed>";
										$outSoundCloud .=  "</object>";
										$outSoundCloud .= "</div>";
								
								}
							}
							?>
							<style type="text/css">
							.custom_media-box{
								height: 108px;
								background: #FFF;
								text-align: center;
								}
							.custom_media-box span {
								display: block;
								background: #A5A4A4;
								width: 34px;
								text-align: center;
								border-radius: 5px;
								height: 26px;
								padding-top: 10px;
								margin-top: 38px;
								float: left;
								margin-left: 43px;
							}
							.custom_media-box-a {
								color: #FFF !important;
								text-transform: uppercase;
								font-size: 9px;
							}
							</style>
							<?php 
							echo '<div class="media-files">';
							  	if(!empty($outLinkVoiceDemo)):
									echo $outLinkVoiceDemo;
								 	endif;
							   	if(!empty($outLinkResume)):
									echo $outLinkResume;
								endif;
							   
								if(!empty($outLinkComCard)):
									echo '<div class="media-file com-card">';
									echo $outLinkComCard;
									echo '</div>';
								endif;

							   	if(!empty($outCustomMediaLink)):
									echo $outCustomMediaLink;
								endif;

								if(!empty($outLinkHeadShot)):
									echo $outLinkHeadShot;
							   	endif;

							   	if(!empty($outLinkPolaroid)):
									echo $outLinkPolaroid;
							   	endif;

							   	if(!empty($outVideoMedia)):							   		
									echo $outVideoMedia;
								endif; 

								if(!empty($outSoundCloud)):
									echo $outSoundCloud;
								endif;							

								if ($countMedia < 1) {
									echo "<div><em>" . __("There are no additional media linked", RBAGENCY_TEXTDOMAIN) . "</em></div>\n";
								}
							
							echo '</div>';
							
							?>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>

		<div id="dashboard-widgets" class="metabox-holder columns-2">

			<!-- Row 4: Column Left Start -->

			<div id="postbox-container-5" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div id="dashboard_upload_images" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo __("Upload Images", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php
											// Upload Images
								echo "      <p>" . __("Upload new media using the forms below", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
								if(isset($errorValidation['profileMedia'])){ echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['profileMedia']."</p>\n";} 
								echo "<table class=\"rbform-table\">";
								for ($i = 1; $i < 10; $i++) {
									echo "<tr><th colspan=\"2\">Type:</th></tr><tr><td><select name=\"profileMedia" . $i . "Type\"><option value=\"\">--Please Select--</option><option value=\"Image\">Photo</option><option value=\"Headshot\">Headshot</option><option value=\"CompCard\">Comp Card</option><option value=\"Resume\">Resume</option><option value=\"VoiceDemo\">Voice Demo</option><option value=\"Polaroid\">Polaroid</option>";
									rb_agency_getMediaCategories($ProfileGender);
									echo"</select></td><td><input type='file' id='profileMedia" . $i . "' name='profileMedia" . $i . "' /></td></tr>\n";
								}
								echo "</table>";

								?>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div id="postbox-container-6" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">


					<div id="dashboard_line_to_videos" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Link to Videos", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php
								// Add Videos
								echo "      <p>" . __("Paste the video URL below", RBAGENCY_TEXTDOMAIN) . ".</p>\n";

								echo "<table class=\"rbform-table\">
										<tr valign=\"top\">
										<th>
										Type: 
											<select name=\"profileMediaV1Type\">
												<option selected>" . __("Video Slate", RBAGENCY_TEXTDOMAIN) . "</option>
												<option>" . __("Video Monologue", RBAGENCY_TEXTDOMAIN) . "</option>
												<option>" . __("Demo Reel", RBAGENCY_TEXTDOMAIN) . "</option>
												<option>" . __("SoundCloud", RBAGENCY_TEXTDOMAIN) . "</option>
											</select>
										</th>
										<td>
											<table>
												<tr><td>Media URL: </td><td><input type='text' id='profileMediaV1' name='profileMediaV1'></td></tr>
												<tr><td>Title: </td><td><input type='text' name='media1_title'></td></tr>
												<tr><td>Caption </td><td><input type='text' name='media1_caption'></td></tr>
												<!--<tr><td>Video Type </td><td><input type='radio' name='media1_vtype' value='youtube' checked>&nbsp; Youtube <br/>
													<input type='radio' name='media1_vtype' value='vimeo' >&nbsp; Vimeo</td></tr>-->
											</table>
										</td>
										</tr>
											</table>";

								echo "<table class=\"rbform-table\">
										<tr valign=\"top\">
										<th>
										Type: 
											<select name=\"profileMediaV2Type\">
												<option>" . __("Video Slate", RBAGENCY_TEXTDOMAIN) . "</option>
												<option selected>" . __("Video Monologue", RBAGENCY_TEXTDOMAIN) . "</option>
												<option>" . __("Demo Reel", RBAGENCY_TEXTDOMAIN) . "</option>
												<option>" . __("SoundCloud", RBAGENCY_TEXTDOMAIN) . "</option>
											</select>
										
										</th>	
										<td>
											<table>
												<tr><td>Media URL: </td><td><input type='text' id='profileMediaV2' name='profileMediaV2'></td></tr>
												<tr><td>Title: </td><td><input type='text' name='media2_title'></td></tr>
												<tr><td>Caption</td><td><input type='text' name='media2_caption'></td></tr>
												<!--<tr><td>Video Type</td><td><input type='radio' name='media2_vtype' value='youtube' checked>&nbsp; Youtube <br/>
													<input type='radio' name='media2_vtype' value='vimeo'>&nbsp; Vimeo </td>
												</tr>-->				
											</table>
										</td>
										</table>";
								echo "<table class=\"rbform-table\">
										<tr valign=\"top\">
										<th>
										Type: 
											<select name=\"profileMediaV3Type\">
												<option>" . __("Video Slate", RBAGENCY_TEXTDOMAIN) . "</option>
												<option selected>" . __("Video Monologue", RBAGENCY_TEXTDOMAIN) . "</option>
												<option>" . __("Demo Reel", RBAGENCY_TEXTDOMAIN) . "</option>
										   	    <option>" . __("SoundCloud", RBAGENCY_TEXTDOMAIN) . "</option>
										   	</select>
										
										</th>
										<td>
											<table>
												<tr><td>Media URL: </td><td><input type='text' id='profileMediaV3' name='profileMediaV3'></td></tr>
												<tr><td>Title: </td><td><input type='text' name='media3_title'></td></tr>
												<tr><td>Caption</td><td><input type='text' name='media3_caption'></td></tr>
												<!--<tr><td>Video Type</td><td><input type='radio' name='media3_vtype' value='youtube' checked>&nbsp; Youtube <br/>
													<input type='radio' name='media3_vtype' value='vimeo'>&nbsp; Vimeo </td></tr>-->
											</table>
										</td>
										</table>";
								
							?>
							</div>
						</div>
					</div>

					<!--  -->

				</div>
			</div>

			<!-- Row 2: Column Right End -->
			<?php } ?>

		</div>




<?php


	if (!empty($ProfileID) && ($ProfileID > 0)) {

		echo "<div class=\"rbtool-box\">\n";
		echo "<p class=\"submit\">\n";
		echo "" . __("Last updated on", RBAGENCY_TEXTDOMAIN) . ": " . $ProfileDateUpdated . "\n";
		echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"" . $ProfileID . "\" />\n";
		echo "     <input type=\"hidden\" name=\"ProfileUserLinked\" value=\"" . $ProfileUserLinked. "\" />\n";
		echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
		echo "     <br /><br /><input type=\"submit\" name=\"submit\" value=\"" . __("Update Record", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		echo "</div>\n";
	} else {
		echo "<div class=\"rbtool-box\">\n";
		echo "<p class=\"submit\">\n";
		echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
		echo "     <input type=\"submit\" name=\"submit\" value=\"" . __("Create Record", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
		echo "</p>\n";
		echo "</div>\n";
	}
	echo "</form>\n";
}

// End Manage



/* List Records **************************************************** */

function rb_display_list() {
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_locationtimezone = isset( $rb_agency_options_arr['rb_agency_option_locationtimezone'] )?(int) $rb_agency_options_arr['rb_agency_option_locationtimezone']:0;
	$rb_agency_option_formshow_displayname = isset($rb_agency_options_arr['rb_agency_option_formshow_displayname'])?(int) $rb_agency_options_arr['rb_agency_option_formshow_displayname']:0;



	echo "<div class=\"wrap\">\n";
	// Include Admin Menu
	include (RBAGENCY_PLUGIN_DIR ."view/partial/admin-menu.php"); 

	// Sort By
	$sort = "";
	if (isset($_GET['sort']) && !empty($_GET['sort'])) {
		$sort = $_GET['sort'];
	} else {
		$sort = "profile.ProfileContactNameFirst,profile.ProfileContactNameLast";
	}

	// Sort Order
	$dir = "";
	if (isset($_GET['dir']) && !empty($_GET['dir'])) {
		$dir = $_GET['dir'];
		if ($dir == "desc" || !isset($dir) || empty($dir)) {
			$sortDirection = "asc";
		} else {
			$sortDirection = "desc";
		}
	} else {
		$sortDirection = "desc";
		$dir = "asc";
	}
	  // Query 
	  $query = "";
	  $selectedNameFirst = "";
	  $selectedNameLast =  "";

	  // Filter
	  $filter = "WHERE ";
		if ((isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])) || isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			if (isset($_GET['ProfileContactNameFirst']) && !empty($_GET['ProfileContactNameFirst'])){
			$selectedNameFirst = $_GET['ProfileContactNameFirst'];
			$query .= "&ProfileContactNameFirst=". $selectedNameFirst ."";

			  if(strpos($filter,'profile') > 0){
					$filter .= " AND profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
			  } else {
					$filter .= " profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
			  }
			}
			if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			$selectedNameLast = $_GET['ProfileContactNameLast'];
			$query .= "&ProfileContactNameLast=". $selectedNameLast ."";
				if(strpos($filter,'profile') > 0){
					   $filter .= " AND profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
				} else {
					   $filter .= " profile.ProfileContactNameLast LIKE '". $selectedNameLast ."%'";
				}
			}
		}
		if (isset($_GET['ProfileLocationCity']) && !empty($_GET['ProfileLocationCity'])){
			$selectedCity = $_GET['ProfileLocationCity'];
			$query .= "&ProfileLocationCity=". $selectedCity ."";
			if(strpos($filter,'profile') > 0){
					$filter .= " AND profile.ProfileLocationCity='". $selectedCity ."'";
			} else {
					$filter .= " profile.ProfileLocationCity='". $selectedCity ."'";
			}
		}
		if (isset($_GET['ProfileType']) && !empty($_GET['ProfileType'])){
			$selectedType = strtolower($_GET['ProfileType']);
			$query .= "&ProfileType=". $selectedType ."";
						if(strpos($filter,'profile') > 0){
							$filter .= " AND FIND_IN_SET('". $selectedType ."', profile.ProfileType)";
						} else {
							$filter .= " FIND_IN_SET('". $selectedType ."', profile.ProfileType)";
						}
		}
		if (isset($_GET['ProfileVisible'])){
			$selectedVisible = $_GET['ProfileVisible'];
			$query .= "&ProfileVisible=". $selectedVisible ."";
			if($_GET['ProfileVisible'] != ""){
					if(strpos($filter,'profile') > 0){
							$filter .= " AND profile.ProfileIsActive = '". $selectedVisible ."'" ;
					} else {
							$filter .= " profile.ProfileIsActive = '". $selectedVisible . "'" ;
					}
			}
		}
		if (isset($_GET['ProfileGender']) && !empty($_GET['ProfileGender'])){
			$ProfileGender = (int)$_GET['ProfileGender'];
			if($ProfileGender)
			  if(strpos($filter,'profile') > 0){
					$filter .= " AND profile.ProfileGender='".$ProfileGender."'";

			  } else {
					$filter .= " profile.ProfileGender='".$ProfileGender."'";
			  }

		}

		/*
		 * Trap WHERE 
		 */
		if(!strpos($filter, 'profile') > 0){
				$filter = "";
		}


	//Paginate
		$sqldata = "SELECT * FROM " . table_agency_profile . " profile LEFT JOIN " . table_agency_data_type . " profiletype ON profile.ProfileType = profiletype.DataTypeID " . $filter . "" ;
		$results=  $wpdb->get_results($sqldata);

		$to_paginate = $_GET;
		$to_paginate = array_unique($to_paginate);
	    unset($to_paginate["page"]);
	    if(!empty($dir)){
		    unset($to_paginate["dir"]);
		}
		$build_query = http_build_query($to_paginate);

		$items =$wpdb->num_rows; // number of total rows in the database
	if ($items > 0) {
		if(isset($_GET['page'])) {
			$page = $_GET['page'];
		}else{
			$page = "";
		}
		if(isset($_GET["ProfileGender"]) && !empty($_GET['ProfileGender'])){
			$ProfileGender = (int)$_GET['ProfileGender'];
			 $query .= "&ProfileGender=".$ProfileGender;
		}
		$p = new RBAgency_Pagination;
		$p->items($items);
		$p->limit(50); // Limit entries per page
		$p->target("admin.php?page=" . $page . $query."&".$build_query);
		if(isset($p->paging)){
			$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
		}
		$p->calculate(); // Calculates what to show
		$p->parameterName('paging');
		$p->adjacents(1); //No. of page away from the current page

		if (!isset($_GET['paging'])) {
			$p->page = 1;
		} else {
			$p->page = $_GET['paging'];
		}

		//Query for limit paging
		$limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;
	} else {
		$limit = "";
	}

	/*
	 * Add New Records
	 */
?>
<script type="text/javascript">
jQuery(document).ready(function(){
		jQuery('.imperial_metrics').keyup(function(){
				var vals = jQuery(this).val();
				var new_val = extractNumber(vals,2,false);
				if(new_val !== true){
						jQuery(this).nextAll('.error_msg').eq(0).html('*Non numeric value is not accepted');
						new_val.replace(/[^/\d*\.*]/g,'');
						jQuery(this).val(new_val);
				}
		});
		jQuery('.imperial_metrics').focusout(function(){
				var vals = jQuery(this).val();
				var new_val = extractNumber(vals,2,false);
				if(new_val !== true){
						jQuery(this).nextAll('.error_msg').eq(0).html('*Non numeric value is not accepted');
						new_val.replace(/[^/\d*\.*]/g,'');
						jQuery(this).val(new_val);
				} else {
						jQuery(this).nextAll('.error_msg').eq(0).html('');
				}
		});		
});
function extractNumber(obj, decimalPlaces, allowNegative)
{
		var temp = obj; var reg0Str = '[0-9]*';
		if (decimalPlaces > 0) { 
				reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
		} else if (decimalPlaces < 0) {
				reg0Str += '\\.?[0-9]*';
		}
		reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
		reg0Str = reg0Str + '$';
		var reg0 = new RegExp(reg0Str);
		if (reg0.test(temp)) return true;
		var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
		var reg1 = new RegExp(reg1Str, 'g');
		temp = temp.replace(reg1, '');
		if (allowNegative) {
				var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
				var reg2 = /-/g;
				temp = temp.replace(reg2, '');
				if (hasNegative) temp = '-' + temp;
		}
		if (decimalPlaces != 0) {
				var reg3 = /\./g;
				var reg3Array = reg3.exec(temp);
				if (reg3Array != null) {
						var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
						reg3Right = reg3Right.replace(reg3, '');
						reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
						temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
				}
		}

		return temp;
}
</script>

	<div id="dashboard-widgets" class="metabox-holder columns-2">

		<div id="postbox-container-1" class="postbox-container" style="width: 29%;">
			<div id="normal-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">

				<div id="dashboard_right_now" class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Create New Profile", RBAGENCY_TEXTDOMAIN); ?></span></h3>
					<div class="inside-x" style="padding: 10px 10px 0px 10px; ">
						<?php echo __("Currently " . $items . " Profiles", RBAGENCY_TEXTDOMAIN); ?><br />
						<?php

							$query = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . " ";
							$queryGenderResult=  $wpdb->get_results($query,ARRAY_A);
							$queryGenderCount  = $wpdb->num_rows;
							echo "<p>";
							foreach ($queryGenderResult as $fetchGender) {
								echo "<a class=\"button-primary\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=add&ProfileGender=" . $fetchGender["GenderID"] . "\">" . __("Create New " . ucfirst($fetchGender["GenderTitle"]) . "", RBAGENCY_TEXTDOMAIN) . "</a>\n";
							}
							echo "</p>";
							if ($queryGenderCount < 1) {
								echo "<p>" . __("No Gender Found. <a href=\"" . admin_url("admin.php?page=rb_agency_settings&ampConfigID=5") . "\">Create New Gender</a>", RBAGENCY_TEXTDOMAIN) . "</p>\n";
							}
						?>

					</div>
				</div>

			</div>
		</div>

		<div id="postbox-container-2" class="postbox-container" style="width: 70%">
			<div id="side-sortables" class="meta-box-sortables ui-sortable" style="margin: 0px;">

				<div id="dashboard_recent_drafts" class="postbox" style="display: block;">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Filter Profiles", RBAGENCY_TEXTDOMAIN ) ?></span></h3>
					<div class="inside">

<?php

	/*
	 * Filtering Records
	 */
	$page_index = "";
	if(isset($_GET['page_index'])){
		$page_index = $_GET['page_index'];
	}

	echo "          <form style=\"display: inline;\" method=\"GET\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
	echo "              <input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"" . $page_index  . "\" />\n";
	echo "              <input type=\"hidden\" name=\"page\" id=\"page\" value=\"" . $_GET['page'] . "\" />\n";
	echo "              <input type=\"hidden\" name=\"type\" value=\"name\" />\n";
	echo "              <p id=\"filter-profiles\">\n";
	echo "              <span>" . __("<label>First Name:</label>", RBAGENCY_TEXTDOMAIN) . "<input type=\"text\" name=\"ProfileContactNameFirst\" value=\"" . $selectedNameFirst . "\" /></span>\n";
	echo "              <span>" . __("<label>Last Name:</label>", RBAGENCY_TEXTDOMAIN) . "<input type=\"text\" name=\"ProfileContactNameLast\" value=\"" . $selectedNameLast . "\" /></span>\n";

	echo "              <span>" . __("<label>Category:</label>", RBAGENCY_TEXTDOMAIN) . "\n";
	echo "              <select name=\"ProfileType\">\n";
	echo "                <option value=\"\">" . __("Any Category", RBAGENCY_TEXTDOMAIN) . "</option>";

	$query = "SELECT DataTypeID, DataTypeTitle FROM " . table_agency_data_type . " ORDER BY DataTypeTitle ASC";
	$results =$wpdb->get_results($query,ARRAY_A);
	$count = $wpdb->num_rows;
	foreach ($results as $data) {
		echo "<option value=\"" . $data['DataTypeID'] . "\" " . selected(isset($_GET['ProfileType'])?$_GET['ProfileType']:"", $data["DataTypeID"]) . "\">" . $data['DataTypeTitle'] . "</option>\n";
	}
	if(!isset($selectedVisible)){
		$selectedVisible = "";
	}
	echo "              </select></span>\n";
	echo "              <span>" . __("Status", RBAGENCY_TEXTDOMAIN) . ":\n";
	echo "              <select name=\"ProfileVisible\">\n";
	echo "                <option value=\"\">" . __("Any Status", RBAGENCY_TEXTDOMAIN) . "</option>";
	echo "                <option value=\"1\"" . selected(1, $selectedVisible) . ">" . __("Active", RBAGENCY_TEXTDOMAIN) . "</option>\n";
	echo "                <option value=\"4\"" . selected(4, $selectedVisible) . ">" . __("Active - Not Visible on Front End", RBAGENCY_TEXTDOMAIN) . "</option>\n";
	echo "                <option value=\"0\"" . selected(0, $selectedVisible) . ">" . __("Inactive", RBAGENCY_TEXTDOMAIN) . "</option>\n";
	echo "                <option value=\"2\"" . selected(2, $selectedVisible) . ">" . __("Archived", RBAGENCY_TEXTDOMAIN) . "</option>\n";
	echo "                <option value=\"3\"" . selected(3, $selectedVisible) . ">" . __("Pending Approval", RBAGENCY_TEXTDOMAIN) . "</option>\n";
	
	echo "              </select></span>\n";
	echo "              <span>" . __("Location", RBAGENCY_TEXTDOMAIN) . ": \n";
	echo "              <select name=\"ProfileLocationCity\">\n";
	echo "                <option value=\"\">" . __("Any Location", RBAGENCY_TEXTDOMAIN) . "</option>";

	echo "<LI>".$query = "SELECT DISTINCT ProfileLocationCity, ProfileLocationState FROM " . table_agency_profile . " ORDER BY ProfileLocationState, ProfileLocationCity ASC";
	$results =  $wpdb->get_results($query,ARRAY_A);
	$count = $wpdb->num_rows;
	foreach ($results as $data) {
		if (isset($data['ProfileLocationCity']) && !empty($data['ProfileLocationCity'])) {
			if(!isset($selectedCity)){
				$selectedCity = "";
			}
			echo "<option value=\"" . $data['ProfileLocationCity'] . "\" " . selected($selectedCity, $data["ProfileLocationCity"]) . "\">" . $data['ProfileLocationCity'] . ", " . strtoupper(isset($dataLocation["ProfileLocationState"])?$dataLocation["ProfileLocationState"]:"") . "</option>\n";
		}
	}
	echo "              </select></span>\n";
	echo "              <span>" . __("Gender", RBAGENCY_TEXTDOMAIN) . ":\n";
	echo "              <select name=\"ProfileGender\">\n";
	echo "                  <option value=\"\">" . __("Any Gender", RBAGENCY_TEXTDOMAIN) . "</option>\n";
	$query2 = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender . " ORDER BY GenderID";
	$results2 =$wpdb->get_results($query2,ARRAY_A);
	foreach ($results2 as  $dataGender) {
		echo "<option value=\"" . $dataGender["GenderID"] . "\"" . selected(isset($_GET["ProfileGender"])?$_GET["ProfileGender"]:"", $dataGender["GenderID"], false) . ">" . $dataGender["GenderTitle"] . "</option>";
	}
	echo "              </select></span>\n";
	echo "              <span class=\"submit\"><input type=\"submit\" value=\"" . __("Filter", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" /></span>\n";
	echo "          </p></form>\n";
	echo "          <form style=\"display: inline; float: left; margin: 17px 5px 0px 0px;\" method=\"GET\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
	echo "              <input type=\"hidden\" name=\"page_index\" id=\"page_index\" value=\"" . $page_index  . "\" />  \n";
	echo "              <input type=\"hidden\" name=\"page\" id=\"page\" value=\"" . $_GET['page'] . "\" />\n";
	echo "              <input type=\"submit\" value=\"" . __("Clear Filters", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-secondary\" />\n";
	echo "          </form>\n";
	echo "          <a  style=\"display: inline; float: left; margin: 17px 5px 0px 0px;\" href=\"" . admin_url("admin.php?page=rb_agency_search") . "\" class=\"button-secondary\">" . __("Advanced Search", RBAGENCY_TEXTDOMAIN) . "</a>\n";


?>

					</div>
				</div>

			</div>
		</div>
		</div>
<?php
	// Show Pagination
	echo "<div class=\"tablenav\">\n";
	echo "  <div class='tablenav-pages'>\n";
	if ($items > 0) {
		echo $p->show();  // Echo out the list of paging. 
	}
	echo "  </div>\n";
	echo "</div>\n";


	
	echo "<form method=\"post\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
	echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
	echo " <thead>\n";
	echo "    <tr class=\"thead\">\n";
	echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
	echo "        <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileID&dir=" . $sortDirection."&".$build_query) . "\">ID</a></th>\n";
	if($rb_agency_option_formshow_displayname == 1){
	echo "        <th class=\"column-ProfileContactDisplay\" id=\"ProfileContactDisplay\" scope=\"col\" style=\"width:150px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileContactDisplay&dir=" . $sortDirection."&".$build_query) . "\">Display Name</a></th>\n";
	}
	echo "        <th class=\"column-ProfileContactNameFirst\" id=\"ProfileContactNameFirst\" scope=\"col\" style=\"width:150px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileContactNameFirst,ProfileContactNameLast&dir=" . $sortDirection."&".$build_query) . "\">First Name</a></th>\n";
	echo "        <th class=\"column-ProfileContactNameLast\" id=\"ProfileContactNameLast\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileContactNameLast,ProfileContactNameFirst&dir=" . $sortDirection."&".$build_query) . "\">Last Name</a></th>\n";
	echo "        <th class=\"column-ProfileGender\" id=\"ProfileGender\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileGender&dir=" . $sortDirection."&".$build_query) . "\">Gender</a></th>\n";
	echo "        <th class=\"column-ProfilesProfileDate\" id=\"ProfilesProfileDate\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileDateBirth&dir=" . $sortDirection."&".$build_query) . "\">Age</a></th>\n";
	echo "        <th class=\"column-ProfileLocationCity\" id=\"ProfileLocationCity\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileLocationCity&dir=" . $sortDirection."&".$build_query) . "\">City</a></th>\n";
	echo "        <th class=\"column-ProfileLocationState\" id=\"ProfileLocationState\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileLocationState&dir=" . $sortDirection."&".$build_query) . "\">State</a></th>\n";
	echo "        <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">Category</th>\n";
	echo "        <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">Images</th>\n";
	echo "        <th class=\"column-ProfileStatHits\" id=\"ProfileStatHits\" scope=\"col\">Views</th>\n";
	echo "        <th class=\"column-ProfileDateViewLast\" id=\"ProfileDateViewLast\" scope=\"col\" style=\"width:100px;\">Last Viewed Date</th>\n";
	echo "    </tr>\n";
	echo " </thead>\n";
	echo " <tfoot>\n";
	echo "    <tr class=\"thead\">\n";
	echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
	echo "        <th class=\"column\" scope=\"col\">ID</th>\n";
	if($rb_agency_option_formshow_displayname == 1){
		echo "        <th class=\"column\" scope=\"col\">Display Name</th>\n";
	}
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

	$queryData1 = "SELECT * FROM " . table_agency_profile . " profile LEFT JOIN " . table_agency_data_type . " profiletype ON profile.ProfileType = profiletype.DataTypeID " . $filter . " ORDER BY $sort $dir $limit";
	$resultsData1 = $wpdb->get_results($queryData1,ARRAY_A);
	$count = $wpdb->num_rows;
	$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming'])?(int) $rb_agency_options_arr['rb_agency_option_profilenaming']:1;
		
	foreach ($resultsData1 as $data) {
		$ProfileID = $data['ProfileID'];
		$ProfileContactDisplay = $data['ProfileContactDisplay'];

		$ProfileGallery = stripslashes($data['ProfileGallery']);
		$ProfileContactNameFirst = stripslashes($data['ProfileContactNameFirst']);
		$ProfileContactNameLast = stripslashes($data['ProfileContactNameLast']);
		$ProfileLocationCity = RBAgency_Common::format_propercase(stripslashes($data['ProfileLocationCity']));
		$ProfileLocationState = stripslashes(rb_agency_getStateTitle($data['ProfileLocationState']));
		$ProfileGender = stripslashes($data['ProfileGender']);
		$ProfileDateBirth = stripslashes($data['ProfileDateBirth']);
		$ProfileStatHits = stripslashes($data['ProfileStatHits']);
		$ProfileDateViewLast = stripslashes($data['ProfileDateViewLast']);

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
			$errorValidation['rb_agency_option_profilenaming'] = "<b><i>" . __(LabelSingular . " must have a display name identified", RBAGENCY_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		} elseif ($rb_agency_option_profilenaming == 3) {
			$ProfileContactDisplay = "ID-" . $ProfileID;
		} elseif ($rb_agency_option_profilenaming == 4) {
			$ProfileContactDisplay = $ProfileContactNameFirst;
		} elseif ($rb_agency_option_profilenaming == 5) {
			$ProfileContactDisplay = $ProfileContactNameLast;
		}

		/*
		 * Profile Type Row Color
		 */
		$statusClass = '';
		$profileStatus = $data['ProfileIsActive'];
		if($profileStatus == 0){
			$statusClass = "inactive";
		}
		if($profileStatus == 1){
			$statusClass = "active";
		}
		if($profileStatus == 2){
			$statusClass = "archived";
		}
		if($profileStatus == 3){
			$statusClass = "pending";
		}
		if($profileStatus == 4){
			$statusClass = "active-notvisible";
		}

		/*
		 * Get Data Type Title
		 */
		if(strpos($data['ProfileType'], ",") != -1){
			$title = explode(",",$data['ProfileType']);
			$new_title = "";
			foreach($title as $t){
				$id = (int)$t;
				$get_title = "SELECT DataTypeTitle FROM " . table_agency_data_type . " WHERE DataTypeID = %d";   
				$get = $wpdb->get_row($wpdb->prepare($get_title, $id),ARRAY_A,0);
				if ($wpdb->num_rows > 0 ){
					$new_title .= "," . $get['DataTypeTitle']; 
				}
			}
			$new_title = substr($new_title,1);
		} else {
				$new_title = "";
				$id = (int)$data['ProfileType'];
				$get_title = "SELECT DataTypeTitle FROM " . table_agency_data_type . " WHERE DataTypeID = %d";   
				$get = $wpdb->get_row($wpdb->prepare($get_title, $id),ARRAY_A,0);
				if ($wpdb->num_rows > 0 ){
					$new_title = $get['DataTypeTitle']; 
				}
		}

		$DataTypeTitle = $new_title;

		$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaType = 'Image' GROUP BY(ProfileMediaURL)";
		$resultsImg=  $wpdb->get_results($wpdb->prepare($query,$ProfileID),ARRAY_A);
		$profileImageCount = $wpdb->num_rows;

		$query = "SELECT * FROM " . table_agency_data_gender . " WHERE GenderID = '%s' ";
		$fetchProfileGender =  $wpdb->get_row($wpdb->prepare($query,$ProfileGender),ARRAY_A,0);
  
		$ProfileGender = $fetchProfileGender["GenderTitle"];

		echo "    <tr class=\"".$statusClass."\">\n";
		echo "        <td class=\"check-column\" scope=\"row\">\n";
		echo "          <input type=\"checkbox\" value=\"" . $ProfileID . "\"  data-name=\"".$ProfileContactNameFirst." ". $ProfileContactNameLast."\" class=\"administrator\" id=\"" . $ProfileID . "\" name=\"" . $ProfileID . "\"/>\n";
		echo "        </td>\n";
		echo "        <td class=\"ProfileID column-ProfileID\">" . $ProfileID . "</td>\n";
		if($rb_agency_option_formshow_displayname == 1){
			echo "        <td class=\"ProfileID column-ProfileContactDisplay data-profile-name-type='".$rb_agency_option_profilenaming."'\">" . $ProfileContactDisplay;
			echo "          <div class=\"row-actions\">\n";
			echo "            <span class=\"edit\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&amp;action=editRecord&amp;ProfileID=" . $ProfileID . "\" title=\"" . __("Edit this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Edit", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"edit\"><a href=\"" . RBAGENCY_PROFILEDIR .  $ProfileGallery . "/\" title=\"" . __("View", RBAGENCY_TEXTDOMAIN) . "\" target=\"_blank\">" . __("View", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=deleteRecord&ProfileID=" . $ProfileID . "\"  onclick=\"if ( confirm('" . __("You are about to delete the profile for ", RBAGENCY_TEXTDOMAIN) . " " . $ProfileContactNameFirst . " " . $ProfileContactNameLast . "? \'" . __("Cancel", RBAGENCY_TEXTDOMAIN) . "\' " . __("to stop", RBAGENCY_TEXTDOMAIN) . ", \'" . __("OK", RBAGENCY_TEXTDOMAIN) . "\' " . __("to delete", RBAGENCY_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"" . __("Delete this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Delete", RBAGENCY_TEXTDOMAIN) . "</a> </span>\n";
			echo "          </div>\n";
			echo "	</td>\n";
		}
		echo "        <td class=\"ProfileContactNameFirst column-ProfileContactNameFirst\">\n";
		echo "          " . $ProfileContactNameFirst . "\n";
		if($rb_agency_option_formshow_displayname == 0){
			echo "          <div class=\"row-actions\">\n";
			echo "            <span class=\"edit\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&amp;action=editRecord&amp;ProfileID=" . $ProfileID . "\" title=\"" . __("Edit this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Edit", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"edit\"><a href=\"" . RBAGENCY_PROFILEDIR .  $ProfileGallery . "/\" title=\"" . __("View", RBAGENCY_TEXTDOMAIN) . "\" target=\"_blank\">" . __("View", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=deleteRecord&ProfileID=" . $ProfileID . "\"  onclick=\"if ( confirm('" . __("You are about to delete the profile for ", RBAGENCY_TEXTDOMAIN) . " " . $ProfileContactNameFirst . " " . $ProfileContactNameLast . "? \'" . __("Cancel", RBAGENCY_TEXTDOMAIN) . "\' " . __("to stop", RBAGENCY_TEXTDOMAIN) . ", \'" . __("OK", RBAGENCY_TEXTDOMAIN) . "\' " . __("to delete", RBAGENCY_TEXTDOMAIN) . ".') ) { return true;}return false;\" title=\"" . __("Delete this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Delete", RBAGENCY_TEXTDOMAIN) . "</a> </span>\n";
			echo "          </div>\n";
		}
		echo "        </td>\n";
		echo "        <td class=\"ProfileContactNameLast column-ProfileContactNameLast\">" . $ProfileContactNameLast . "</td>\n";
		echo "        <td class=\"ProfileGender column-ProfileGender\">" . $ProfileGender . "</td>\n";
		echo "        <td class=\"ProfilesProfileDate column-ProfilesProfileDate\">" . rb_agency_get_age($ProfileDateBirth) . "</td>\n";
		echo "        <td class=\"ProfileLocationCity column-ProfileLocationCity\">" . $ProfileLocationCity . "</td>\n";
		echo "        <td class=\"ProfileLocationCity column-ProfileLocationState\">" . $ProfileLocationState . "</td>\n";
		echo "        <td class=\"ProfileDetails column-ProfileDetails\">" . $DataTypeTitle . "</td>\n";
		echo "        <td class=\"ProfileDetails column-ProfileDetails\">" . $profileImageCount . "</td>\n";
		echo "        <td class=\"ProfileStatHits column-ProfileStatHits\">" . $ProfileStatHits . "</td>\n";
		echo "        <td class=\"ProfileDateViewLast column-ProfileDateViewLast\" attr_lastview=\"".strtotime($ProfileDateViewLast)."\" attr_timezone=\"". $rb_agency_option_locationtimezone."\">\n";
		echo "           " . rb_agency_makeago(rb_agency_convertdatetime($ProfileDateViewLast), $rb_agency_option_locationtimezone);
		echo "        </td>\n";
		echo "    </tr>\n";
	}

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

	// Show Pagination
	echo "<div class=\"tablenav\">\n";
	echo "  <div class='tablenav-pages'>\n";
	if ($items > 0) {
		echo $p->show();  // Echo out the list of paging. 
	}
	echo "  </div>\n";
	echo "</div>\n";

	// Show Actions
	echo "<p class=\"submit\">\n";
	echo "  <input type=\"hidden\" value=\"deleteRecord\" name=\"action\" />\n";
	echo "  <input type=\"submit\" value=\"" . __('Delete Profiles') . "\" class=\"delete-profiles button-primary\" name=\"submit\" />   \n";
	echo "</p>\n";
	echo "</form>\n";
	echo "<script  type=\"text/javascript\">\n\n";
    echo "jQuery('.delete-profiles').click(function(){\n\n";
    echo "    var profiles = '';\n\n";
    echo "       jQuery('tbody .check-column input[type=checkbox]:checked').each(function(){ \n\n";
    echo "           profiles = profiles +'\\n'+jQuery(this).attr('data-name'); \n\n";
    echo "       });   \n\n";
    echo "  if(profiles !=='') {if(!confirm('Are you sure that you want to delete the following profiles? \\n'+profiles)){ \n\n";
    echo "           return false; \n\n";
    echo "  }}else{\n\n";
    echo "   alert('Please \'check\' the profiles that you want to delete.'); return false;\n\n";
    echo "  }\n\n";
    echo "});\n\n";
    echo "</script>";
}

echo "</div>\n";
?>