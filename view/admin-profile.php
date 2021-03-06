<?php
global $wpdb;
define("LabelPlural", "Profile");
define("LabelSingular", "Profiles");


//altering the main profile table for private fields...
	$queryAlterCheck = "SELECT isPrivate FROM " . table_agency_profile_media ." LIMIT 1";
	$resultsDataAlter = $wpdb->get_results($queryAlterCheck,ARRAY_A);
	$count_alter = $wpdb->num_rows;
	if($count_alter == 0){
		// sometimes upgrade script wasnt execute.. so we just want to be sure.
		$queryAlter = "ALTER TABLE " . table_agency_profile_media ." ADD isPrivate boolean NOT NULL default false";
		$resultsDataAlter = $wpdb->get_results($queryAlter,ARRAY_A);
		/* echo "<h2>Table Altered for Private profile option. Please refresh the page.</h2>";
		exit; */
	}
	$queryAlterCheck = "SELECT ProfileVideoType FROM " . table_agency_profile_media ." LIMIT 1";
	$resultsDataAlter = $wpdb->get_results($queryAlterCheck,ARRAY_A);
	$count_alter = $wpdb->num_rows;
	if($count_alter == 0){
		// sometimes upgrade script wasnt execute.. so we just want to be sure.
		$queryAlter = "ALTER TABLE " . table_agency_profile_media ." ADD ProfileVideoType varchar(255) default NULL";
		$resultsDataAlter = $wpdb->get_results($queryAlter,ARRAY_A);
		/* echo "<h2>Table Altered for Private profile option. Please refresh the page.</h2>";
		exit; */
	}
	$sql = "SELECT ProfileResume FROM ".$wpdb->prefix."agency_profile LIMIT 1";
	$r = $wpdb->get_results($sql);
	if(count($r) == 0){
		//create column
			$queryAlter = "ALTER TABLE " . $wpdb->prefix ."agency_profile ADD ProfileResume TEXT default NULL";
			$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
	}
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
	$ProfileID = isset($_POST['ProfileID']) ? $_POST['ProfileID']:0;
	$ProfileUserLinked = isset($_POST['ProfileUserLinked'])?$_POST['ProfileUserLinked']:"";
	$ProfileContactNameFirst = isset($_POST['ProfileContactNameFirst']) ? trim(preg_replace('!\s+!', ' ',$_POST['ProfileContactNameFirst'])):"";
	$ProfileContactNameLast = isset($_POST['ProfileContactNameLast']) ? trim(preg_replace('!\s+!', ' ',$_POST['ProfileContactNameLast'])):"";
	$ProfileContactDisplay = isset($_POST['ProfileContactDisplay']) ? trim(preg_replace('!\s+!', ' ',$_POST['ProfileContactDisplay'])):"";
	$ProfileDescription = isset($_POST['ProfileDescription']) ? trim($_POST['ProfileDescription']):"";
if (empty($ProfileContactDisplay)) { // Probably a new record...
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
	}
	$ProfileGallery = isset($_POST['ProfileGallery']) ? $_POST['ProfileGallery']:"";
	if (empty($ProfileGallery)) { // Probably a new record...
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
	//socila media links
	$ProfileContactLinkFacebook = isset($_POST['ProfileContactLinkFacebook'])?$_POST['ProfileContactLinkFacebook']:"";
	$ProfileContactLinkTwitter = isset($_POST['ProfileContactLinkTwitter'])?$_POST['ProfileContactLinkTwitter']:"";
	$ProfileContactLinkYouTube = isset($_POST['ProfileContactLinkYouTube'])?$_POST['ProfileContactLinkYouTube']:"";
	$ProfileContactLinkFlickr = isset($_POST['ProfileContactLinkFlickr'])?$_POST['ProfileContactLinkFlickr']:"";
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
	$ProfileIsBooking = isset($_POST['ProfileIsBooking'])?$_POST['ProfileIsBooking']:"";
	$ProfileStatHits = isset($_POST['ProfileStatHits'])?$_POST['ProfileStatHits']:"";
	$isPrivate = isset($_POST['isPrivate'])?$_POST['isPrivate']:"";
	$CustomOrder = isset($_POST['CustomOrder']) ? $_POST['CustomOrder'] : '';
	$ProfileResume = isset($_POST['profile_resume']) ? $_POST['profile_resume'] : "";
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
		//set customfields value to sessions
				$_SESSION['profileCustomValue'] = array();
				foreach($_POST as $k=>$v){
					if(substr($k,0,15) == 'ProfileCustomID' ){
						$ProfileCustomValue = $v;
						if(is_array($ProfileCustomValue)){
							$ProfileCustomValue = implode(',',$ProfileCustomValue);
						}else{
							$ProfileCustomValue = $v;
						}
						$_SESSION['profileCustomValue'][$k] = $ProfileCustomValue;						
					}					
				}
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

	// Get Post State
	$action = $_POST['action'];
	switch ($action) {
		// *************************************************************************************************** //
		// Add Record
		case 'addRecord':
			if (!$have_error) {
				//check for profile custom date
				$sql = "SELECT ProfileCustomDateValue FROM ".table_agency_customfield_mux." LIMIT 1";
				$r = $wpdb->get_results($sql);
				if(count($r) == 0){
					//create column
					$queryAlter = "ALTER TABLE " . table_agency_customfield_mux ." ADD ProfileCustomDateValue DATETIME default NULL";
					$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
				}
				//check for parentid column and level
				$sql = "SELECT CustomOrder FROM ".$wpdb->prefix."agency_profile LIMIT 1";
				$r = $wpdb->get_results($sql);
				if(count($r) == 0){
					//create column
					$q1 = "SELECT * FROM ".table_agency_profile;
					$rda = $wpdb->get_results($q1,ARRAY_A);
					$qnumrows = $wpdb->num_rows;
					$queryAlter = "ALTER TABLE " . $wpdb->prefix ."agency_profile ADD CustomOrder INT(10) default $qnumrows";
					$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
				}
                    $prow = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE table_name = '".table_agency_profile."' AND column_name = 'ProfileRating'"  );
						if(empty($prow)){
							$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileRating VARCHAR(20) NOT NULL";
							$wpdb->query($queryAlter);
						}
				// Bug Free!
				if ($have_error == false) {
					if (function_exists('rb_agency_interact_menu')) {
						$new_user = wp_insert_user($userdata);
					}
					// Check Directory - create directory if does not exist, rename if does
					$ProfileGallery = rb_agency_createdir($ProfileGallery);
					//create ProfileIsBooking column if not exists
						global $wpdb;
						$wpdb->get_results("SELECT ProfileIsBooking FROM ".table_agency_profile." WHERE ProfileID = $ProfileID");
						if($wpdb->num_rows == 0){
							$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileIsBooking boolean NOT NULL default 0";
							$wpdb->query($queryAlter);
						}
					//check for Profile-Description column
					$sql = "SELECT ProfileDescription FROM ". table_agency_profile ." LIMIT 1";
					$r = $wpdb->get_results($sql);
					if(count($r) == 0){
						//create column
						$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileDescription TEXT NOT NULL AFTER `ProfileContactNameLast`";
						$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
					}
                    
                    $delete1 = "DELETE FROM " . table_agency_customfield_mux . " WHERE ProfileID = \"" . $ProfileID . "\"";
					$wpdb->query($delete1);
					// Create Record
					$insert = "INSERT INTO " . table_agency_profile .
						" (
							ProfileGallery,
							ProfileContactDisplay,
							ProfileUserLinked,
							ProfileContactNameFirst,
							ProfileContactNameLast,
							ProfileDescription,
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
							ProfileContactLinkTwitter,
							ProfileContactLinkFacebook,
							ProfileContactLinkYoutube,
							ProfileContactLinkFlickr,
							ProfileDateUpdated,
							ProfileType,
							ProfileIsActive,
							isPrivate,
							ProfileIsFeatured,
							ProfileIsPromoted,
							ProfileIsBooking,
							ProfileStatHits,
							ProfileDateViewLast,
							CustomOrder,ProfileResume)" .
						"VALUES (
							'" . esc_attr($ProfileGallery) . "',
							'" . esc_attr($ProfileContactDisplay) . "',
							'" . esc_attr(isset($new_user)?$new_user:"") . "',
							'" . esc_attr($ProfileContactNameFirst) . "',
							'" . esc_attr($ProfileContactNameLast) . "',
							'" . esc_attr($ProfileDescription) . "',
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
							'" . esc_attr($ProfileContactLinkTwitter) . "',
							'" . esc_attr($ProfileContactLinkFacebook) . "',
							'" . esc_attr($ProfileContactLinkYoutube) . "',
							'" . esc_attr($ProfileContactLinkFlickr) . "',
							now(),
							'" . $ProfileType . "',
							'" . esc_attr($ProfileIsActive) . "',
							'" . esc_attr($isPrivate) . "',
							'" . esc_attr($ProfileIsFeatured) . "',
							'" . esc_attr($ProfileIsPromoted) . "',
							'" . esc_attr($ProfileIsBooking) . "',
							'" . esc_attr($ProfileStatHits) . "',
							'" . esc_attr($ProfileDateViewLast) . "',
							'" . esc_attr($CustomOrder) . "',
							'" . esc_attr($ProfileResume) . "'
						)";
					$results = $wpdb->query($insert);
                    
					$ProfileID = $wpdb->insert_id;
					add_user_meta( $new_user, 'rb_agency_interact_profiletype',true);
					// Notify admin and user
					if ($ProfileNotifyUser <> "yes" && function_exists('rb_agency_interact_menu')) {
						wp_new_user_notification(isset($new_user)?$new_user:"", $ProfilePassword);
					}
					// Set Display Name as Record ID (We have to do this after so we know what record ID to use... right ;)
					if ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID-" . $ProfileID;
						$nProfileGallery = "ID-" . $ProfileID;
						$update = $wpdb->query("UPDATE " . table_agency_profile . " SET ProfileContactDisplay='" . $ProfileContactDisplay . "', ProfileGallery='" . $nProfileGallery . "' WHERE ProfileID='" . $ProfileID . "'");
						$updated = $wpdb->query($update);
						//Generate folder
						//rb_agency_createdir($ProfileContactDisplay,true);
                        $oldDir =RBAGENCY_UPLOADPATH ."/". $ProfileGallery;
						$newDir =RBAGENCY_UPLOADPATH ."/". $nProfileGallery;
						rename($oldDir,$newDir );
						@rmdir($oldDir);
					}
					//display on profile view
					$ShowProfileContactLinkTwitter = isset($_POST['ShowProfileContactLinkTwitter']) ? true : false;
					$ShowProfileContactLinkFacebook = isset($_POST['ShowProfileContactLinkFacebook']) ? true : false;
					$ShowProfileContactLinkYouTube = isset($_POST['ShowProfileContactLinkYouTube']) ? true : false;
					$ShowProfileContactLinkFlickr = isset($_POST['ShowProfileContactLinkFlickr']) ? true : false;
					//user meta social media
					foreach($_POST['profile_social_media_name'] as $k=>$v){
						if(!empty($v)){
							add_user_meta($_GET['ProfileID'],'SocialMediaName_'.$v,$v);
						}
					}
					foreach($_POST['profile_social_media_url'] as $k=>$v){
						if(!empty($v)){
							add_user_meta($_GET['ProfileID'],'SocialMediaURL_'.$v,$v);
						}
					}
					add_user_meta( $ProfileID, 'ShowProfileContactLinkTwitter',$ShowProfileContactLinkTwitter);
					add_user_meta( $ProfileID, 'ShowProfileContactLinkFacebook',$ShowProfileContactLinkFacebook);
					add_user_meta( $ProfileID, 'ShowProfileContactLinkYouTube',$ShowProfileContactLinkYouTube);
					add_user_meta( $ProfileID, 'ShowProfileContactLinkFlickr',$ShowProfileContactLinkFlickr);
					// Add Custom Field Values stored in Mux
					foreach ($_POST as $key => $value) {
						if ((substr($key, 0, 15) == "ProfileCustomID" || substr($key, 0, 22) == "ProfileCustomID_other_") && (isset($value) && !empty($value) || $value == 0)) {
						  
							$ProfileCustomID = substr($key, 15);
							if (is_array($value)) {
								$value = implode(",", $value);
							}
                            
							$profilecustomfield_date = explode("_",$key);
							if(count($profilecustomfield_date) == 2){ // customfield date
								$value = !empty($value) ? date("y-m-d h:i:s",strtotime($value)) : "";
								$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomDateValue) VALUES (%d,%d,%s)", $ProfileID , $ProfileCustomID, $value);
							} else {
								if(!is_numeric($ProfileCustomID)){
										$ProfileCustomID = substr($key, 22);
										$value = !empty($_POST["ProfileCustomID_other_".$ProfileCustomID]) ? $_POST["ProfileCustomID_other_".$ProfileCustomID] : "" ;
									}
								$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue) VALUES (%d,%d,%s)", $ProfileID , $ProfileCustomID, $value);
							}
							$results1 = $wpdb->query($insert1);
						}
					}
                    
					foreach($_SESSION['profileCustomValue'] as $k=>$v){
						unset($_SESSION['profileCustomValue']);
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
            $_SESSION['profileCustomValue'] = [];
    				foreach($_POST as $k=>$v){
    					if(substr($k,0,15) == 'ProfileCustomID' ){
    						$ProfileCustomValue = $v;
    						if(is_array($ProfileCustomValue)){
    							$ProfileCustomValue = implode(',',$ProfileCustomValue);
    						}else{
    							$ProfileCustomValue = $v;
    						}
    						$_SESSION['profileCustomValue'][$k] = $ProfileCustomValue;						
    					}					
    				}
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
            
			if (!empty($ProfileContactNameFirst) && !empty($ProfileID)) {
				if($have_error == false){
						// no need to pending this account because your the admin.
						if($rb_agency_option_inactive_profile_on_update == 1){
							//
							if(is_user_logged_in() && current_user_can( 'edit_posts' )){
							}else{
								$ProfileIsActive = 3;
							}
						}
						/*
						echo 'settings pending';
						*/
						//notify via email the user if his account was pending to activate.
						(int)$currentStatus = $wpdb->get_var("SELECT ProfileIsActive FROM " . table_agency_profile ." WHERE ProfileID=$ProfileID");
						if($currentStatus != 1){
							//means account wasn't active
							if($ProfileIsActive == 1){
								//admin decide to activate the account.
								wp_new_user_notification_approve($ProfileID);
							}
						}
						//create ProfileIsBooking column if not exists
						global $wpdb;
						$wpdb->get_results("SELECT ProfileIsBooking FROM ".table_agency_profile." WHERE ProfileID = $ProfileID");
						if($wpdb->num_rows == 0){
							$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileIsBooking boolean NOT NULL default 0";
							$wpdb->query($queryAlter);
						}
                        //create if not exists CustomOrder
                        //$wpdb->get_results("SELECT CustomOrder FROM ".table_agency_profile." WHERE ProfileID = $ProfileID");
                        $cOrderrow = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE table_name = '".table_agency_profile."' AND column_name = 'CustomOrder'"  );
						if(empty($cOrderrow)){
							$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD CustomOrder int NOT NULL default 0";
							$wpdb->query($queryAlter);
						}
                        
                        $prow = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE table_name = '".table_agency_profile."' AND column_name = 'ProfileRating'"  );
						if(empty($prow)){
							$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileRating VARCHAR(20) NOT NULL";
							$wpdb->query($queryAlter);
						}
                        
						//check for Profile-Description column
						$sql = "SELECT ProfileDescription FROM ". table_agency_profile ." LIMIT 1";
						$r = $wpdb->get_results($sql);
						if(count($r) == 0){
							//create column
							$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD ProfileDescription TEXT NOT NULL AFTER `ProfileContactNameLast`";
							$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
						}
							# get current profile contact display
							$ProfileData = $wpdb->get_row($wpdb->prepare("SELECT ProfileContactDisplay FROM ".table_agency_profile." WHERE ProfileID = %d",$ProfileID),ARRAY_A);
							//echo $ProfileData['ProfileContactDisplay']."=".$_POST['ProfileContactDisplay'];
							$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
							#check and generate the right gallery folder
							if($ProfileData['ProfileContactDisplay'] != stripslashes_deep($_POST['ProfileContactDisplay'])){
								if ($rb_agency_option_profilenaming == 0) {
									$ProfileGalleryFixed = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
								} elseif ($rb_agency_option_profilenaming == 1) {
									$ProfileGalleryFixed = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
								} elseif ($rb_agency_option_profilenaming == 2) {
									$ProfileGalleryFixed = $ProfileContactDisplay;
								} elseif ($rb_agency_option_profilenaming == 3) {
									$ProfileGalleryFixed = "ID-". $ProfileID;
								} elseif ($rb_agency_option_profilenaming == 4) {
									$ProfileGalleryFixed = $ProfileContactNameFirst;
								} elseif ($rb_agency_option_profilenaming == 5) {
									$ProfileGalleryFixed = $ProfileContactNameLast;
								}
								//Remove non-alphanumberic characters like apostrophe
								$ProfileGalleryFixed = preg_replace("/[^A-Za-z0-9 ]/", '', $ProfileGalleryFixed);
								$ProfileGalleryFixed = str_replace(' ', '-', strtolower($ProfileGalleryFixed));
								$ProfileGalleryFixed = str_replace(' ', '-', strtolower($ProfileGalleryFixed));
								$ProfileGalleryFixed = rb_agency_createdir($ProfileGalleryFixed);	
								$oldDir =RBAGENCY_UPLOADPATH ."/". $ProfileGallery;
								$newDir =RBAGENCY_UPLOADPATH ."/". $ProfileGalleryFixed;
								rename($oldDir,$newDir );
								@rmdir($oldDir);
								$ProfileGallery = $ProfileGalleryFixed;
							}
						// Update Record
						$update = "UPDATE " . table_agency_profile . " SET
							ProfileGallery='" . esc_attr($ProfileGallery) . "',
							ProfileContactDisplay='" . esc_attr($ProfileContactDisplay) . "',
							ProfileContactNameFirst='" . esc_attr($ProfileContactNameFirst) . "',
							ProfileContactNameLast='" . esc_attr($ProfileContactNameLast) . "',
							ProfileDescription='" . esc_attr($ProfileDescription) . "',
							ProfileContactEmail='" . esc_attr($ProfileContactEmail) . "',
							ProfileContactWebsite='" . esc_attr($ProfileContactWebsite) . "',
							ProfileContactPhoneHome='" . esc_attr($ProfileContactPhoneHome) . "',
							ProfileContactPhoneCell='" . esc_attr($ProfileContactPhoneCell) . "',
							ProfileContactPhoneWork='" . esc_attr($ProfileContactPhoneWork) . "',
							ProfileContactLinkTwitter='" . esc_attr($ProfileContactLinkTwitter) . "',
							ProfileContactLinkFacebook='" . esc_attr($ProfileContactLinkFacebook) . "',
							ProfileContactLinkYoutube='" . esc_attr($ProfileContactLinkYouTube) . "',
							ProfileContactLinkFlickr='" . esc_attr($ProfileContactLinkFlickr) . "',
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
							isPrivate='" . esc_attr($isPrivate) . "',
							ProfileIsFeatured='" . esc_attr($ProfileIsFeatured) . "',
							ProfileIsPromoted='" . esc_attr($ProfileIsPromoted) . "',
							ProfileIsBooking='" . esc_attr($ProfileIsBooking) . "',
							CustomOrder='" . esc_attr($CustomOrder) . "',
							ProfileResume = '".$ProfileResume."'
							WHERE ProfileID=$ProfileID";
                            
						$results = $wpdb->query($update);
                        if (false === $results) {
                           error_log($wpdb->last_error);
                           echo $wpdb->last_error;
                           exit();
                        }
                        
                        
                        
							update_user_meta(isset($_REQUEST['wpuserid'])?$_REQUEST['wpuserid']:"", 'rb_agency_interact_profiletype', $ProfileType);
							update_user_meta(isset($_REQUEST['wpuserid'])?$_REQUEST['wpuserid']:"", 'rb_agency_interact_pgender', esc_attr($ProfileGender));
							//clear first the user meta social media name and links
										global $wpdb;
										$list = array();
										$custom_social_media = rb_get_custom_social_media();
										foreach($custom_social_media as $social){
											array_push($list,$social["SocialMedia_Name"]);
										}
										foreach($list as $k=>$v){
											$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'SocialMediaURL_".$v."' AND user_id = ".$_GET['ProfileID']);
											$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'SocialMediaName_".$v."' AND user_id = ".$_GET['ProfileID']);
										}
							//user meta social media name
							foreach($_POST['profile_social_media_name'] as $k=>$v){
								if(!empty($v) && !empty($_POST['profile_social_media_url'][$k])){
									add_user_meta($_GET['ProfileID'],'SocialMediaName_'.$v,$v);
								}
							}
							//user meta social media url
							foreach($_POST['profile_social_media_url'] as $k=>$v){
								if(!empty($v) && !empty($_POST['profile_social_media_name'][$k])){
									add_user_meta($_GET['ProfileID'],'SocialMediaURL_'.$_POST['profile_social_media_name'][$k],$v);
								}
							}
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
					//display on profile view
					$ShowProfileContactLinkTwitter = isset($_POST['ShowProfileContactLinkTwitter']) ? true : false;
					$ShowProfileContactLinkFacebook = isset($_POST['ShowProfileContactLinkFacebook']) ? true : false;
					$ShowProfileContactLinkYouTube = isset($_POST['ShowProfileContactLinkYouTube']) ? true : false;
					$ShowProfileContactLinkFlickr = isset($_POST['ShowProfileContactLinkFlickr']) ? true : false;
					update_user_meta( $ProfileID, 'ShowProfileContactLinkTwitter',$ShowProfileContactLinkTwitter);
					update_user_meta( $ProfileID, 'ShowProfileContactLinkFacebook',$ShowProfileContactLinkFacebook);
					update_user_meta( $ProfileID, 'ShowProfileContactLinkYouTube',$ShowProfileContactLinkYouTube);
					update_user_meta( $ProfileID, 'ShowProfileContactLinkFlickr',$ShowProfileContactLinkFlickr);
					//save other account urls here
					$otherURLS = [];
					for($idx=0;$idx<count($_POST["otherAccountURLs"]);$idx++){
						if(!empty($_POST["otherAccountURLs"][$idx])){
							$otherURLS[] = $_POST["otherAccountURLs"][$idx];
						}						
					}
					update_user_meta($ProfileID,"otherAccountURLs_".$ProfileID,implode("|",$otherURLS));
						//Personal expanded detail
						$hide_age_year = isset($_REQUEST['hide_age_year']) ? true : false;
						$hide_age_month = isset($_REQUEST['hide_age_month']) ? true : false;
						$hide_age_day = isset($_REQUEST['hide_age_day']) ? true : false;
						$hide_state = isset($_REQUEST['hide_state']) ? true : false;
						update_user_meta( $_REQUEST['ProfileID'], 'rb_agency_hide_age_year', $hide_age_year);
						update_user_meta( $_REQUEST['ProfileID'], 'rb_agency_hide_age_month',$hide_age_month );
						update_user_meta( $_REQUEST['ProfileID'], 'rb_agency_hide_age_day',$hide_age_day );
						update_user_meta( $_REQUEST['ProfileID'], 'rb_agency_hide_state',$hide_state );
						//echo $hide_age_year .' - ' .$_REQUEST['ProfileID'] .'-'.get_user_meta($_REQUEST['ProfileID'],"rb_agency_hide_age_year",true);
						// Remove Old Custom Field Values
						$delete1 = "DELETE FROM " . table_agency_customfield_mux . " WHERE ProfileID=$ProfileID";
						$results1 = $wpdb->query($delete1);
                        
                        
						// Add New Custom Field Values
						foreach ($_POST as $key => $value) {
						  
							if (is_numeric(substr($key, 15)) && (isset($value) && !empty($value) || $value == 0)) { 
								$ProfileCustomID = substr($key, 15);
								if (is_array($value)) {
									$value = implode(",", $value);
								}
								//Check if link
								$profilecustomfield_date = explode("_",$key);
								if(count($profilecustomfield_date) == 2){ // customfield date
									rb_send_notif_due_date_reached_edit($ProfileID,$ProfileCustomID,$value);
									$value = !empty($value) ? date("y-m-d h:i:s",strtotime($value)) : "";
									//$insert1 = $wpdb->prepare("INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomDateValue)" . " VALUES (%d,%d,%s)",$ProfileID,$ProfileCustomID,stripslashes($value));
                                    
								} else {
									
									$wpdb->insert( table_agency_customfield_mux, array("ProfileID"=>$ProfileID,"ProfileCustomID"=>$ProfileCustomID,"ProfileCustomValue"=>stripslashes($value)) ); 
								}                                
   
							}
                            
                            if (is_numeric(substr($key,22)) && $value !="") {

									$ProfileCustomID = substr($key, 22);
									$wpdb->insert( table_agency_customfield_mux, array("ProfileID"=>$ProfileID,"ProfileCustomID"=>$ProfileCustomID,"ProfileCustomOtherValue"=>stripslashes($value)) ); 
                            }
						}
                        
                        
						foreach($_SESSION['profileCustomValue'] as $k=>$v){
							unset($_SESSION['profileCustomValue']);
						}
						// Check Directory - create directory if does not exist
						$ProfileGallery = rb_agency_checkdir($ProfileGallery);
						// Upload Image & Add to Database
						$i = 1;
						// Check how many images currently exist
						
						while ($i <= 10) {
							if (isset($_FILES['profileMedia' . $i]['tmp_name']) && $_FILES['profileMedia' . $i]['tmp_name'] != "") {
								$uploadMediaType = $_POST['profileMedia' . $i . 'Type'];
								if ($have_error != true) {
									// Upload if it doesnt exist already
									
									$file = pathinfo($_FILES['profileMedia' . $i]['name']);
									$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d1' AND ProfileMediaURL = '%d2'";
									$results = $wpdb->get_results($wpdb->prepare($query, $ProfileID, $path_parts['filename']),ARRAY_A);
									$count =  $wpdb->num_rows;
									if ($count < 1) {
										if ($uploadMediaType == "Polaroid") {
											if ($_FILES['profileMedia' . $i]['type'] == "image/pjpeg" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
												$image = new rb_agency_image();
												$image->load($_FILES['profileMedia' . $i]['tmp_name']);
												if ($image->getHeight() > $rb_agency_option_agencyimagemaxheight) {
													$image->resizeToHeight($rb_agency_option_agencyimagemaxheight);
												}
												$polariodir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/polaroid/";
                                                if(!is_dir($polariodir)){
                                                    mkdir($polariodir);
                                                }
                                                $safeProfileMediaFilename = RBAgency_Common::generateFilename($polariodir,$file);
                                                $mediaTitle = pathinfo($safeProfileMediaFilename,PATHINFO_FILENAME);
                                                $image->save($polariodir . $safeProfileMediaFilename);
												// Add to database
												$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileMediaOrder) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $mediaTitle . "','" . $safeProfileMediaFilename . "',0)");
												
											} else {
												$errorValidation['profileMedia'] = "<b><i>"._("Please upload an image file only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
										} elseif ($uploadMediaType == "VoiceDemo") {
											// Add to database
											$MIME = array('audio/mpeg', 'audio/mp3'); 
											if (in_array($_FILES['profileMedia' . $i]['type'], $MIME)) {
											    $voicedemodir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/voicedemo/";
												$safeProfileMediaFilename = RBAgency_Common::generateFilename($voicedemodir,$file);
                                                $mediaTitle = pathinfo($safeProfileMediaFilename,PATHINFO_FILENAME);
                                                $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $mediaTitle . "','" . $safeProfileMediaFilename . "')");
												$_voidemodID = $wpdb->insert_id;
												if(!empty($_voidemodID)){
													update_option("voicedemo_".$_voidemodID ,  $path_parts['filename'] );
												}    
                                                
                                                if(!is_dir($voicedemodir)){
                                                    mkdir($voicedemodir);
                                                } 
												move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], $voicedemodir.$safeProfileMediaFilename);
											} else {
												$errorValidation['profileMedia'] = "<b><i>"._("Please upload a mp3 file only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
										} elseif ($uploadMediaType == "Resume") {
											// Add to database
											if ($_FILES['profileMedia' . $i]['type'] == "application/msword" || $_FILES['profileMedia' . $i]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES['profileMedia' . $i]['type'] == "application/pdf" || $_FILES['profileMedia' . $i]['type'] == "application/rtf") {
												
												$resumedir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/resume/" ;
                                                $safeProfileMediaFilename = RBAgency_Common::generateFilename($resumedir,$file);
                                                $mediaTitle = pathinfo($safeProfileMediaFilename,PATHINFO_FILENAME);
                                                if(!is_dir($resumedir)){
                                                    mkdir($resumedir);
                                                }
                                                $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $mediaTitle . "','" . $safeProfileMediaFilename . "')");
												
                                                move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], $resumedir. $safeProfileMediaFilename);
											} else {
												$errorValidation['profileMedia'] = "<b><i>"._("Please upload PDF/MSword/RTF files only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
										} elseif ($uploadMediaType == "Headshot") {
											// Add to database
											if ($_FILES['profileMedia' . $i]['type'] == "application/msword" || $_FILES['profileMedia' . $i]['type'] == "application/pdf" || $_FILES['profileMedia' . $i]['type'] == "application/rtf" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
												$headshotdir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/headshot/" ;
                                                if(!is_dir($headshotdir)){
                                                    mkdir($headshotdir);
                                                }
                                                $safeProfileMediaFilename = RBAgency_Common::generateFilename($headshotdir,$file);
                                                $mediaTitle = pathinfo($safeProfileMediaFilename,PATHINFO_FILENAME);
                                                $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $mediaTitle . "','" . $safeProfileMediaFilename . "')");
												
                                                move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], $headshotdir. $safeProfileMediaFilename);
											} else {
												$errorValidation['profileMedia'] = "<b><i>"._("Please upload PDF/MSWord/RTF/Image files only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
                                            
										} elseif ($uploadMediaType == "CompCard") {
											// Add to database
											if ($_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
												$CompCarddir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/compcard/" ;
												 if(!is_dir($CompCarddir)){
                                                    mkdir($CompCarddir);
                                                }
                                                $safeProfileMediaFilename = RBAgency_Common::generateFilename($CompCarddir,$file);
                                                $mediaTitle = pathinfo($safeProfileMediaFilename,PATHINFO_FILENAME);
                                                $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $mediaTitle . "','" . $safeProfileMediaFilename . "')");
												
                                                move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], $CompCarddir. $safeProfileMediaFilename);
											} else {
												$errorValidation['profileMedia'] = "<b><i>"._("Please upload jpeg or png files only",RBAGENCY_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
										}
										elseif ($uploadMediaType == "CardPhotos") {
											// Add to database
											if ($_FILES['profileMedia' . $i]['type'] == "image/pjpeg" || $_FILES['profileMedia' . $i]['type'] == "image/jpeg" || $_FILES['profileMedia' . $i]['type'] == "image/gif" || $_FILES['profileMedia' . $i]['type'] == "image/png") {
												
												$CardPhotos = RBAGENCY_UPLOADPATH . $ProfileGallery . "/cardphotos/" ;
                                                if(!is_dir($CardPhotos)){
                                                    mkdir($CardPhotos);
                                                }
                                                $safeProfileMediaFilename = RBAgency_Common::generateFilename($CardPhotos,$file);
                                                $mediaTitle = pathinfo($safeProfileMediaFilename,PATHINFO_FILENAME);
                                                $results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $mediaTitle . "','" . $safeProfileMediaFilename . "')");
                                                move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], $CardPhotos. $safeProfileMediaFilename);
											} else {
												$errorValidation['profileMedia'] = "<b><i>"._("Please upload an image file only",rb_agency_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
										}
										// Custom Media Categories
										elseif (strpos($uploadMediaType,"rbcustommedia") !== false) {
											// Add to database
                                            $customMedia = RBAGENCY_UPLOADPATH . $ProfileGallery . "/custom/" ;
                                            if(!is_dir($customMedia)){
                                                    mkdir($customMedia);
                                                }
											$custom_media_info = explode("_",$uploadMediaType);
											$custom_media_title = $custom_media_info[1];
											$custom_media_type = $custom_media_info[2];
											$custom_media_extenstion = $custom_media_info[3];
											$arr_extensions = array();
											array_push($arr_extensions, $custom_media_extenstion);
											if($custom_media_extenstion == "doc"){
												array_push($arr_extensions,"application/octet-stream");
												array_push($arr_extensions,"docx");
											} elseif($custom_media_extenstion == "mp3"){
												array_push($arr_extensions,"audio/mpeg");
												array_push($arr_extensions,"audio/mp3");
											} elseif($custom_media_extenstion == "pdf"){
												array_push($arr_extensions,"application/pdf");
											} elseif($custom_media_extenstion == "jpg"){
												array_push($arr_extensions,"image/jpeg");
												array_push($arr_extensions,"jpeg");
											}elseif($custom_media_extenstion == "avi"){
												array_push($arr_extensions,"avi");
											}
											if(strpos($_FILES['profileMedia' . $i]['type'],"video") !== false){
												$_FILES['profileMedia' . $i]['type'] = str_replace("video/", "", $_FILES['profileMedia' . $i]['type']);
											}
                                            $safeProfileMediaFilename = RBAgency_Common::generateFilename($customMedia,$file);
											if (in_array($_FILES['profileMedia' . $i]['type'], $arr_extensions)) {
												$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "')");
												move_uploaded_file($_FILES['profileMedia' . $i]['tmp_name'], $customMedia . $safeProfileMediaFilename);
											} else {
												$errorValidation['profileMedia'] = "<b><i>".__("Please upload ".$custom_media_extenstion." files only", RBAGENCY_TEXTDOMAIN)."</i></b><br />";
												$have_error = true;
											}
										}
									}// End count
								}// End have error = false
							}//End:: if profile media is not empty.
							$i++;
						}// endwhile
						// Upload Videos to Database
						for ($k = 1 ; $k < 4; $k++){
							if (isset($_POST['profileMediaV'. $k]) && !empty($_POST['profileMediaV'. $k])) {
								$profileMediaType = $_POST['profileMediaV'.$k.'Type'];
								$profileMediaTitle = $_POST['media'.$k.'_title'] ."<br>". $_POST['media'.$k.'_caption'];
								$profileMediaURL = rb_agency_get_VideoFromObject($_POST['profileMediaV'. $k]);
								$profileVideoType = rb_agency_get_videotype($_POST['profileMediaV'. $k]);
								$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileVideoType) VALUES ('" . $ProfileID . "','" . $profileMediaType . "','" . $profileMediaTitle . "','" . $profileMediaURL . "','".$profileVideoType."')");
							}
						}
						// Add Custom URL/Links to Database
						for ($k = 1 ; $k < 4; $k++){
							if (isset($_POST['profileLinkTitleV'. $k]) && !empty($_POST['profileLinkTitleV'. $k]) && isset($_POST['profileLinkURLV'. $k]) && !empty($_POST['profileLinkURLV'. $k])) {
								$profileLinkTitle = $_POST['profileLinkTitleV'. $k];
								$profileLinkURL = $_POST['profileLinkURLV'. $k];
								$results = $wpdb->query("INSERT INTO " . table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL) VALUES ('" . $ProfileID . "','Link','" . $profileLinkTitle . "','" . $profileLinkURL . "')");
							}
						}
						// Set Primary Image
						// TODO: Refactor
						$results = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaType = 'Image' AND ProfileMediaPrimary='1'";
						$results = $wpdb->get_results($wpdb->prepare($results, $ProfileID),ARRAY_A);
						$count = $wpdb->num_rows;
						// Do we have a custom image yet?
						if ($count < 1) { // No, let's set the first one as primary
							$query = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND ProfileMediaType = 'Image' ORDER BY ProfileMediaID ASC LIMIT 0, 1";
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
						//check IMDB privacy
						$wpdb->query("UPDATE ".table_agency_profile_media." SET isPrivate = 0 WHERE ProfileMediaType = 'Link'");
						if(isset($_POST['set_imdb_private'])){
							foreach($_POST['set_imdb_private'] as $k=>$v){
								if(isset($_POST['set_imdb_private'][$k])){
									$wpdb->query("UPDATE ".table_agency_profile_media." SET isPrivate = 1 WHERE ProfileMediaID = ".$_POST['set_imdb_private'][$k]);
								}else{
									$wpdb->query("UPDATE ".table_agency_profile_media." SET isPrivate = 0 WHERE ProfileMediaID = ".$_POST['set_imdb_private'][$k]);
								}
							}
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
									} else {
										$ProfileMediaOrder2[(int)$pieces[1]] = (int) $val ;
									}
								}
							}
						}
						asort($ProfileMediaOrder1);
						$imedia=1;
						if(is_array($ProfileMediaOrder1) && count($ProfileMediaOrder1)>0){
							foreach($ProfileMediaOrder1 as $key => $val){
								$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaOrder='".$val."' WHERE ProfileID=$ProfileID AND ProfileMediaID=$key");
								$imedia++;
							}
						}
						if(is_array($ProfileMediaOrder2) && count($ProfileMediaOrder2)>0){
							foreach($ProfileMediaOrder2 as $key => $val){
								$results = $wpdb->query("UPDATE " . table_agency_profile_media . " SET ProfileMediaOrder='".$val."' WHERE ProfileID=$ProfileID AND ProfileMediaID=$key");
								$imedia++;
							}
						}
						// set private photos
						$private_photos_arr = array();
                        if(isset($_POST['setprivate']) && $_POST['setprivate']!=""){
						foreach($_POST['setprivate'] as $k=>$v){
							$private_photos_arr[] = $v;
						}
						$imploded_private_photos = implode(",",$private_photos_arr);
						update_user_meta($ProfileUserLinked,"private_profile_photo",$imploded_private_photos);
                        }
					}// if have_error == false
						/* TODO: ------------ CLEAN THIS UP -------------- */
						if(!$have_error){
								echo ("<div id=\"message\" class=\"updated\"><p>" . __("Profile updated successfully", RBAGENCY_TEXTDOMAIN) . "! </a></p></div>");
						} else {
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
							wp_delete_user($dataDelete["ProfileUserLinked"]);
								if (isset($ProfileGallery) && $ProfileGallery!="") {
									// Remove Folder
                                    //$mediatype = strtolower($dataDelete['ProfileMediaType']);
//                                    $mediatype = $mediatype!='image' ? $mediatype:"";
//									$dir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/".$mediatype;
//									$mydir = @opendir($dir);
//									while (false !== ($file = @readdir($mydir))) {
//										if ($file != "." && $file != "..") {
//											if(@is_file($dir . $file))
//											@unlink($dir . $file);
//										}
//									}
//									// Remove Directory
//									//if (@is_dir($dir)) {
//									//	@rmdir($dir);
//									//}
//									@closedir($mydir);
                                    $imgdir = RBAGENCY_UPLOADPATH.$ProfileGallery;
                                    delete_directory($imgdir);
								} else {
									echo ("<div id=\"message\" class=\"error\"><p>" . __("No Valid Record Found.", RBAGENCY_TEXTDOMAIN) . "</p></div>");
								}
                                
                                // Remove Profile
							     $delete = "DELETE FROM " . table_agency_profile . " WHERE ProfileID = %d ";
							     $results = $wpdb->query($wpdb->prepare($delete,$ProfileID));
							     // Remove Media
							     $delete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = %d ";
    							 $results = $wpdb->query($wpdb->prepare($delete,$ProfileID));
    							/// Now delete
								//---------- Delete users but re-assign to Admin User -------------//
								// Gimme an admin:
								/*$AdminID = $wpdb->prepare("SELECT $wpdb->users.ID FROM $wpdb->users WHERE user_login = %s","admin");
								if ($AdminID > 0) {
								} else {
									$AdminID = 1;
								}*/
						}// foreach || is there record?
				}// is numeric
			}
			if($profiles_count >  1){
					echo ('<div id="message" class="updated"><p>' . __("$profiles_count Profiles deleted successfully!", RBAGENCY_TEXTDOMAIN) . '</p></div>');
			} else {
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
		// Remove User if Exists
		if ($dataDelete["ProfileUserLinked"] > 0) {
			// TODO: Check if admin user, do not delete admin user accounts
			wp_delete_user($dataDelete["ProfileUserLinked"]);
		}
		if (isset($ProfileGallery)) {
			// Remove Folder
            //$mediatype = strtolower($dataDelete['ProfileMediaType']);
//            $mediatype = $mediatype!='image' ? $mediatype:"";
//			$dir = RBAGENCY_UPLOADPATH . $ProfileGallery . "/".$mediatype;
//			$mydir = @opendir($dir);
//			while (false !== ($file = @readdir($mydir))) {
//				if ($file != "." && $file != "..") {
//					@unlink($dir . $file); // or DIE("couldn't delete $dir$file<br />");
//				}
//			}
//			// remove dir
//			//if (is_dir($dir)) {
//			//	rmdir($dir);// or DIE("couldn't delete $dir$file folder not exist<br />");
//			//}
//			closedir($mydir);
            $imgdir = RBAGENCY_UPLOADPATH.$ProfileGallery;
            delete_directory($imgdir);
		} else {
			echo __("No valid record found.", RBAGENCY_TEXTDOMAIN);
		}
		echo ('<div id="message" class="updated"><p>' . __("Profile deleted successfully!", RBAGENCY_TEXTDOMAIN) . '</p></div>');
	}// is there record?
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
	// Resume Editor
	$rb_agency_option_resume_editor = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_resume_editor'])?(int) $rb_agency_options_arr['rb_agency_option_viewdisplay_resume_editor']:0;
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
			$ProfileDescription = stripslashes($data['ProfileDescription']);
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
			$ProfileIsBooking = stripslashes($data['ProfileIsBooking']);
			//$ProfileIsPrivate = stripslashes($data['ProfileIsPrivate']);
			$ProfileStatHits = stripslashes($data['ProfileStatHits']);
			$ProfileDateViewLast = stripslashes($data['ProfileDateViewLast']);
			$ProfileDateCreated = stripslashes($data['ProfileDateCreated']);
			$isPrivate = stripslashes($data['isPrivate']);
			$CustomOrder = stripslashes($data['CustomOrder']);
			$ProfileRating = stripslashes($data['ProfileRating']);
			$ProfileResume = stripslashes($data['ProfileResume']);
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
			$ProfileDescription = trim($_POST['ProfileDescription']);
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
			$isPrivate = $_POST['isPrivate'];
			$ProfileIsFeatured = $_POST['ProfileIsFeatured'];
			$ProfileIsPromoted = $_POST['ProfileIsPromoted'];
			$ProfileIsBooking = $_POST['ProfileIsBooking'];
			//$ProfileIsPrivate = $_POST['ProfileIsPrivate'];
			$ProfileStatHits = $_POST['ProfileStatHits'];
			$CustomOrder = $_POST['CustomOrder'];
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
	<h3><strong><?php echo $caption_header; ?></strong></h3>
    <em><?php echo $caption_text; ?> <strong><?php echo __("Required fields are marked", RBAGENCY_TEXTDOMAIN); ?> *</strong></em>
    <div id="welcome-panel" class="welcome-panel">
		
			<?php if (!empty($ProfileID) && ($ProfileID > 0)) { ?>
			<a class="button button-primary button-hero" style="float: right; margin-top: 0px;" href="<?php echo RBAGENCY_PROFILEDIR  . $ProfileGallery; ?>" target="_blank">Preview Model</a>
			<?php }?>
			<p class="about-description"> <a class="button button-primary" href="<?php echo admin_url("admin.php?page=" . $_GET['page']); ?>"><?php echo __("Back to " . LabelSingular . " List", RBAGENCY_TEXTDOMAIN); ?></a></p>
			
            </br>
	</div>
    
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">
			<!-- Row 1: Column Left Start -->
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<!-- CONTACT INFORMATION -->
                    <div id="dashboard_rbagency_profile_glance" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo __("Contact Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php
								$fields = [
								'updated_ProfileContactDisplay'=>'ProfileContactDisplay',
								'updated_ProfileContactNameFirst'=>'ProfileContactNameFirst',
								'updated_ProfileContactNameLast'=>'ProfileContactNameLast',
								'updated_ProfileContactEmail'=>'ProfileContactEmail',
								'updated_ProfileContactWebsite'=>'ProfileContactWebsite',
								'updated_ProfileContactLinkFacebook'=>'ProfileContactLinkFacebook',
								'updated_ProfileContactLinkTwitter'=>'ProfileContactLinkTwitter',
								'updated_ProfileContactLinkYoutube'=>'ProfileContactLinkYoutube',
								'updated_ProfileContactLinkFlickr'=>'ProfileContactLinkFlickr',
								'updated_ProfileContactPhoneHome'=>'ProfileContactPhoneHome',
								'updated_ProfileContactPhoneCell'=>'ProfileContactPhoneCell',
								'updated_ProfileContactPhoneWork'=>'ProfileContactPhoneWork',
								'updated_ProfileGender'=>'ProfileGender',
								'updated_ProfileDateBirth'=>'ProfileDateBirth',
								'updated_ProfileLocationStreet'=>'ProfileLocationStreet',
								'updated_ProfileLocationCity'=>'ProfileLocationCity',
								'updated_ProfileLocationState'=>'ProfileLocationState',
								'updated_ProfileLocationZip'=>'ProfileLocationZip',
								'updated_ProfileLocationCountry'=>'ProfileLocationCountry',
								'updated_ProfileType'=>'ProfileType'
								];
								$fields_val = [];
								if($ProfileIsActive == 3){
									foreach($fields as $k=>$v){
										$fields_val[$k] = get_user_meta($ProfileUserLinked, "updated_".$v, true);
									}
								}elseif($ProfileIsActive == 1){
									foreach($fields as $k=>$v){
										unset($fields_val[$k]);
										delete_user_meta($ProfileUserLinked, "updated_".$v, "");
									}
									$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%updated_ProfileCustomID%' OR meta_key LIKE '%MediaFileName%' OR meta_key LIKE '%VideoFileName%' AND user_id = ".$ProfileUserLinked);
								}
								echo " <table class=\"form-table\">\n";
								echo "  <tbody>\n";
								if ((!empty($ProfileID) && ($ProfileID > 0)) || ($rb_agency_option_profilenaming == 2)) { // Editing Record
									echo "    <tr valign=\"top\">\n";
									echo "      <th scope=\"row\">" . __("Display Name", RBAGENCY_TEXTDOMAIN) . "</th>\n";
									echo "      <td>\n";
									echo "          <input type=\"text\" id=\"ProfileContactDisplay\" name=\"ProfileContactDisplay\" value=\"" . (isset($ProfileContactDisplay)?$ProfileContactDisplay:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactDisplay']) ? 'marked_changed' : "")."\"/>\n";
									if(isset($errorValidation['rb_agency_option_profilenaming'])){echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['rb_agency_option_profilenaming']."</p>\n";}
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
								echo "          <input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"" . (isset($ProfileContactNameFirst)?$ProfileContactNameFirst:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactNameFirst']) ? 'marked_changed' : "")."\"/>\n";
								if(isset($errorValidation['ProfileContactNameFirst'])){echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['ProfileContactNameFirst']."</p>\n";}
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Last Name", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"" . (isset($ProfileContactNameLast)?$ProfileContactNameLast:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactNameLast']) ? 'marked_changed' : "")."\"/>\n";
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
											echo "          <input type=\"text\" id=\"ProfileUsername\" name=\"ProfileUsername\" value=\"".(isset($_POST["ProfileUsername"]) ? $_POST["ProfileUsername"] : "")."\"/>\n";
											if(isset($errorValidation['user_login'])){echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['user_login']."</p>\n";}
											echo "      </td>\n";
											echo "    </tr>\n";
											echo "    <tr valign=\"top\">\n";
											echo "      <th scope=\"row\">" . __("Password", RBAGENCY_TEXTDOMAIN) . "</th>\n";
											echo "      <td>\n";
											echo "          <input type=\"text\" id=\"ProfilePassword\" name=\"ProfilePassword\" />\n";
											echo "          <input type=\"button\" onclick=\"javascript:document.getElementById('ProfilePassword').value=Math.random().toString(36).substr(2,6);\" value=\"Generate Password\"  name=\"GeneratePassword\" />\n";
											if(isset($errorValidation['user_password'])){echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['user_password']."</p>\n";}
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
								//ProfileDescription
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Profile Description", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <textarea type=\"text\" class=\"rb-textarea\"  id=\"ProfileDescription\" name=\"ProfileDescription\" ".(!empty($fields_val['updated_ProfileDescription']) ? "class=\"marked_changed\"" : "").">";
								echo	esc_attr( $ProfileDescription );
								echo "</textarea>";
								echo "</td>\n";
								echo "    </tr>\n";
								echo " </table>\n";
								?>
							</div>
						</div>
					</div>
					<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
							<script type="text/javascript">
								//jQuery(document).ready(function(){
//									jQuery( ".datepicker-bd" ).datepicker();
//									jQuery(".datepicker-bd").datepicker("option","dateFormat","yy-mm-dd");
//									jQuery(".datepicker-bd").datepicker("setDate", "<?php echo $ProfileDateBirth != '0000-00-00' ? $ProfileDateBirth : ''; ?>");
//								});
							</script>
                    <!-- END CONTACT INFORMATION -->        
					<!-- PRIVATE INFORMATION -->
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
								echo "          <input type=\"text\" id=\"ProfileDateBirth\" name=\"ProfileDateBirth\" class=\"datepicker-bd ".(!empty($fields_val['updated_ProfileDateBirth']) ? "marked_changed" : "")."\" value=\"" . $ProfileDateBirth . "\" value=\"" . (isset($ProfileContactEmail)?$ProfileContactEmail:"") . "\" />\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Email Address", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"" . (isset($ProfileContactEmail)?$ProfileContactEmail:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactEmail']) ? 'marked_changed' : "")."\"/>\n";
								if(isset($errorValidation['ProfileContactEmail'])){echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['ProfileContactEmail']."</p>\n";}
									echo "          <input type=\"hidden\" id=\"ProfileContactEmail\" name=\"HiddenContactEmail\" value=\"" . (isset($ProfileContactEmail)?$ProfileContactEmail:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactEmail']) ? 'marked_changed' : "")."\"/>\n";
									echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Website", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileContactWebsite\" name=\"ProfileContactWebsite\" value=\"" . (isset($ProfileContactWebsite)?$ProfileContactWebsite:"") . "\"  class=\"".(!empty($fields_val['updated_ProfileContactWebsite']) ? 'marked_changed' : "")."\"/>\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Phone", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "      <fieldset>\n";
								echo "          <label class='floatlabel'>Home:</label><input type=\"text\" id=\"ProfileContactPhoneHome\" name=\"ProfileContactPhoneHome\" value=\"" . (isset($ProfileContactPhoneHome)?$ProfileContactPhoneHome:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactPhoneHome']) ? 'marked_changed' : "")."\"/><br />\n";
								echo "          <label class='floatlabel'>Cell:</label><input type=\"text\" id=\"ProfileContactPhoneCell\" name=\"ProfileContactPhoneCell\" value=\"" . (isset($ProfileContactPhoneCell)?$ProfileContactPhoneCell:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactPhoneCell']) ? 'marked_changed' : "")."\"/><br />\n";
								echo "          <label class='floatlabel'>Work:</label><input type=\"text\" id=\"ProfileContactPhoneWork\" name=\"ProfileContactPhoneWork\" value=\"" . (isset($ProfileContactPhoneWork)?$ProfileContactPhoneWork:"") . "\" class=\"".(!empty($fields_val['updated_ProfileContactPhoneWork']) ? 'marked_changed' : "")."\"/><br />\n";
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
								echo "<select name=\"ProfileLocationCountry\" id=\"ProfileLocationCountry\"  onchange='javascript:populateStates(\"ProfileLocationCountry\",\"ProfileLocationState\");' class=\"".(!empty($fields_val['updated_ProfileLocationCountry']) ? 'marked_changed' : "")."\">";
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
								echo '<select name="ProfileLocationState" id="ProfileLocationState" class="'.(!empty($fields_val['updated_ProfileLocationState']) ? 'marked_changed' : "").'">';
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
								echo "          <input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" value=\"" . (isset($ProfileLocationStreet)?$ProfileLocationStreet:"") . "\" class=\"".(!empty($fields_val['updated_ProfileLocationStreet']) ? 'marked_changed' : "")."\"/>\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("City", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" value=\"" . (isset($ProfileLocationCity)?$ProfileLocationCity:"") . "\" class=\"".(!empty($fields_val['updated_ProfileLocationCity']) ? 'marked_changed' : "")."\"/>\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Zip", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>\n";
								echo "          <input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" value=\"" . (isset($ProfileLocationZip)?$ProfileLocationZip:"") . "\" class=\"".(!empty($fields_val['updated_ProfileLocationZip']) ? 'marked_changed' : "")."\"/>\n";
								echo "      </td>\n";
								echo "    </tr>\n";
								// Custom Admin Fields
								// ProfileCustomView = 1 , Private
								if (isset($_GET["ProfileGender"])) {
									$ProfileGender = $_GET["ProfileGender"];
									//$rbagencyCustomfieldsClass = new RBAgency_Customfields();
									//$rbagencyCustomfieldsClass->getCustomFieldsProfileManagerPrivate($ProfileGender);
									//rb_custom_fields(1, 0, $ProfileGender, true);
									echo "  <tbody class=\"tbody-table-customfields-private\">\n";
									echo "  </tbody>\n";
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
									//rb_custom_fields(1, $ProfileID, $ProfileGender, true);
									echo "  <tbody class=\"tbody-table-customfields-private\">\n";
									echo "  </tbody>\n";
								}
								echo " </table>\n";
								?>
							</div>
						</div>
					</div>
                    <!-- END PRIVATE INFORMATION -->
					<!-- SOCIAL MEDIA LINKS -->
					<div id="dashboard_social_media_links" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Social Media Links", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<table class="social-media-links-table">
									<?php
									$social_media_arr_list = array();
									$custom_social_media = rb_get_custom_social_media();
									foreach($custom_social_media as $social){
										array_push($social_media_arr_list,$social["SocialMedia_Name"]);
									}
									foreach($social_media_arr_list as $k=>$v){
										$socialMediaName = get_user_meta($_GET['ProfileID'],'SocialMediaName_'.$v,true);
										$socialMediaURL = get_user_meta($_GET['ProfileID'],'SocialMediaURL_'.$v,true);
										if(!empty($socialMediaName)){
											echo "<tr class=\"social-media-links-row-".$socialMediaName."\">
												<td><select name=\"profile_social_media_name[]\">
													<option value=\"".$v."\" ".($socialMediaName == $v ? "selected" : "").">".$v."</option>";
													foreach($social_media_arr_list as $k=>$v){
														if($socialMediaName !== $v){
															echo "<option value=\"".$v."\">".$v."</option>";
														}
													}
											echo "</select></td>
												<td><input type=\"text\" name=\"profile_social_media_url[]\" class=\"profile_social-media-links-row-".$socialMediaName."\" value=\"".(!empty($socialMediaURL) ? $socialMediaURL : "")."\" style=\"width:220px;\">
												<a class=\"button-primary remove-social-media\" id=\"social-media-links-row-".$socialMediaName."\" >remove</a>
												</td>
											</tr>";
										}
									}
									?>
									<tr>
										<td>
											<select name="profile_social_media_name[]">
												<?php
												$social_media_arr = array();
												$custom_social_media = rb_get_custom_social_media();
												foreach($custom_social_media as $social){
													array_push($social_media_arr,$social["SocialMedia_Name"]);
												}
												echo "<option>".__("Select Social Media")."</option>";
												foreach($social_media_arr as $k=>$v){
													echo "<option value=\"".$v."\" >".$v."</option>";
												}
												 ?>
											</select>
										</td>
										<td>
											<input type="text" name="profile_social_media_url[]" placeholder="Insert URL here" style="width:220px;"/>
										</td>
									</tr>
								</table>
								<input type="button" name="add_social" class="button-primary add_social" value="Add More"/>
								<input type="button" name="remove_social" class="button-primary remove_social" value="Remove Field"/>
							</div>
						</div>
					</div>
                    <!-- END SOCIAL MEDIA LINKS -->
                    <!-- RESUME EDITOR-->
            			<div id="resume_edito" class="postbox <?php echo $rb_agency_option_resume_editor == 0 ? 'hide
            			' : ''; ?>">
    						<h3 class="hndle"><span><?php echo __("Resume Editor", RBAGENCY_TEXTDOMAIN); ?></span></h3>
    						<div class="inside">
    							<div class="main">
    								<?php
    								$profile_resume = $ProfileResume;
    								$content = !empty($profile_resume) ? $profile_resume : "";
    								$editor_id = 'profile_resume'; 
    								wp_editor( $content, $editor_id,array("wpautop"=>false,"tinymce"=>true) ); 
    								?>
    							</div>
    						</div>
    					</div>
                    <!-- END RESUME EDITOR-->
				</div>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				var idx = 1;
				jQuery('.add_social').click(function(){
					var media_links_row = jQuery('.social-media-links-table tr:last').html();
					jQuery('.social-media-links-table input:last').attr('class','social-media-links-input');
					jQuery('.social-media-links-table').append('<tr>'+media_links_row+'</tr>');
					jQuery('.social-media-links-table tr:last').attr('class','added_social-media-links-row');
					idx++;
					jQuery('.remove_social').removeAttr('disabled');
				});
				jQuery('.remove-social-media').on('click',function(){
					jQuery(".social-media-links-table tr."+jQuery(this).attr('id')).remove();
				});
				jQuery('.remove_social').click(function(){
					jQuery('.social-media-links-table .added_social-media-links-row:last').remove();
				});
			});
			</script>
			<!-- Row 1: Column Left End -->
			<!-- Row 1: Column Right Start -->
			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div id="dashboard_account_information" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Account Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
							<?php
							//check for parentid column and level
							$sql = "SELECT DataTypeParentID FROM ".$wpdb->prefix."agency_data_type LIMIT 1";
							$r = $wpdb->get_results($sql);
							if(count($r) == 0){
								//create column
								$queryAlter = "ALTER TABLE " . $wpdb->prefix ."agency_data_type ADD DataTypeParentID INT(10) default 0";
								$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
							}
							$sql = "SELECT DataTypeLevel FROM ".$wpdb->prefix."agency_data_type LIMIT 1";
							$r = $wpdb->get_results($sql);
							if(count($r) == 0){
								//create column
								$queryAlter = "ALTER TABLE " . $wpdb->prefix ."agency_data_type ADD DataTypeLevel INT(10) default 0";
								$resultsDataAlter = $wpdb->query($queryAlter,ARRAY_A);
							}
							echo "<table class=\"form-table\">\n";
							echo " <tbody>\n";
							echo "    <tr valign=\"top\">\n";
							echo "      <th scope=\"row\" data=\"this_".$ProfileType."\">" . __("Classification", RBAGENCY_TEXTDOMAIN) . "</th>\n";
							echo "      <td>\n";
							echo "      <fieldset>\n";
							//$ProfileType = (@strpos(",", $ProfileType)!= -1) ? explode(",", $ProfileType) : $ProfileType;
							//if(strpo)
							//repopulate - GENDER Controller
							$data_gender_exists = $wpdb->get_var( "SELECT DataTypeGenderID FROM " . table_agency_data_type );
							if ( !$data_gender_exists ) {
								$wpdb->query("ALTER TABLE ".table_agency_data_type." ADD DataTypeGenderID int(10) DEFAULT 0"); //zero means all gender
							}
							$query3 = "SELECT * FROM " . table_agency_data_type . " WHERE DataTypeParentID = 0 ORDER BY DataTypeTitle";
							$results3=  $wpdb->get_results($query3,ARRAY_A);
							?>
							<?php
							if(isset($_GET['action']) && $_GET['action'] == "editRecord"){
							?>
							 <script>var ProfileID = '<?php echo $ProfileID;?>';</script>
							<?php
							}
							if(isset($_GET['action']) && $_GET['action'] == "add") { 
							?>
                           
							 <script>var ProfileID = '<?php echo $ProfileID;?>';</script>
							
							
							<?php
						      }
							# get gender title
							$DataTypeGenderTitle = rbGetDataTypeGenderTitleByID($ProfileGender);
							$count3  = $wpdb->num_rows;
							$action = @$_GET["action"];
							$ProfileTypeArr = [];
							if(strpos($ProfileType, '|')>-1){
								$ExplodedProfileType = explode("|",$ProfileType);
							}else{
								$ExplodedProfileType = explode(",",$ProfileType);
							}
							foreach($ExplodedProfileType as $p){
								$ProfileTypeArr[] = $p;
							}
							$updated_ProfileTypesArr = explode(",",$fields['updated_ProfileType']);
							foreach ($results3 as $data3) {
								#Get the gender id allowed for each datatype
								$DataTypeOptionValue = get_option("DataTypeID_".$data3["DataTypeID"]);
										if ($action == "add") {
											if(strpos($DataTypeOptionValue, $DataTypeGenderTitle)>-1 || strpos($DataTypeOptionValue, 'All Gender')>-1 || empty($DataTypeOptionValue)){
												$checked_profiletypes_arr = [];
												if($_POST['ProfileType']){
														foreach($_POST['ProfileType'] as $k=>$v){
														$checked_profiletypes_arr[] = isset($_POST['ProfileType'][$k]) ? $_POST['ProfileType'][$k] : "";
														}
														$checked = in_array($data3['DataTypeID'], $checked_profiletypes_arr) ? "checked=\"checked\"" : "";
												}
												echo "<input type=\"checkbox\" name=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\" id=\"ProfileType[]\" profile-type-title=\"".$data3['DataTypeTitle']."\" class=\"userProfileType\" $checked>".$data3['DataTypeTitle']. "<br />\n";
											}
										}
										if ($action == "editRecord") {
											echo "<input type=\"checkbox\" name=\"ProfileType[]\" id=\"ProfileType[]\" value=\"" . $data3['DataTypeID'] . "\" profile-type-title=\"".$data3['DataTypeTitle']."\"  class=\"userProfileType ".(in_array($data3['DataTypeID'], $updated_ProfileTypesArr) ? "marked_changed" : "" )."\"";
											if(is_array($ProfileTypeArr)){
													if (in_array($data3['DataTypeID'], $ProfileTypeArr)) {
														echo " checked=\"checked\"";
													}echo " /> " . $data3['DataTypeTitle'] . "<br />\n";
											} else {
													if ($data3['DataTypeID'] == $ProfileTypeArr) {
														echo " checked=\"checked\"";
													}echo " /> " . $data3['DataTypeTitle'] . "<br />\n";
											}
										}
										if(@strpos($DataTypeOptionValue, $DataTypeGenderTitle)>-1 || @strpos($DataTypeOptionValue, 'All Gender')>-1 || @empty($DataTypeOptionValue)){
											do_action('rb_get_profile_type_childs_checkbox_display_profilemanage_display',$data3['DataTypeID'],$action,$ProfileType,$DataTypeGenderTitle,$fields);
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
							if(function_exists('rb_agency_interact_menu')){
							echo "            <option value=\"3\"" . selected(3, $ProfileIsActive) . ">" . __("Pending Approval", RBAGENCY_TEXTDOMAIN) . "</option>\n";
							}
							echo "          </select></td>\n";
							echo "    </tr>\n";
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Promotion", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td>\n";
							echo "          <input type=\"checkbox\" name=\"ProfileIsFeatured\" id=\"ProfileIsFeatured\" value=\"1\"". checked(isset($ProfileIsFeatured)?$ProfileIsFeatured:0, 1, false) . " /> Featured<br />\n";
							echo "        </td>\n";
							echo "    </tr>\n";
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Custom Order", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td>\n";
										$q1 = "SELECT * FROM ".table_agency_profile;
										$rda = $wpdb->get_results($q1,ARRAY_A);
										$qnumrows = $wpdb->num_rows;
										$customorder = $qnumrows > 1 && $CustomOrder == $qnumrows ? '' : $CustomOrder;
							echo "          <input type=\"text\" name=\"CustomOrder\" id=\"CustomOrder\" value=\"".(isset($customorder) ? $customorder : '')."\" /><br />\n";
							echo "        </td>\n";
							echo "    </tr>\n";
							//rate feature
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Rate Profile", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td>\n";
							if(isset($_GET['action']) && !empty($_GET["ProfileID"]) && $_GET["action"] == 'editRecord') { 
							
	                        }
							?>
							<?php if($_GET['action'] == 'add' && !isset($_POST['action'])) { ?>
								<script type="text/javascript">
									//jQuery(document).ready(function(){
//										jQuery.ajax({
//											type: "POST",
//											url: "<?php echo admin_url('admin-ajax.php') ?>",
//											data: {
//												action: "rb_get_customfields_load",
//												'gender': "<?php echo $_GET["ProfileGender"]; ?>"
//											},
//											success: function (results) {
//												jQuery(".tbody-table-customfields").html(results);
//												console.log(results);
//											}
//										});
										//jQuery.ajax({
//											type: "POST",
//											url: "<?php echo admin_url('admin-ajax.php') ?>",
//											data: {
//												action: "rb_get_customfields_load_private",
//												'gender': "<?php echo $_GET["ProfileGender"]; ?>"
//											},
//											success: function (results) {
//												jQuery(".tbody-table-customfields-private").html(results);
//												console.log(results);
//											}
//										});
//									});
								</script>
							<?php } ?>
							<script type="text/javascript">
							jQuery(document).ready(function(){
									//hover stars
									//rate profile
									jQuery(".rate_profile").click(function(){
										// TODO PATH INVALID
										var loader = "<?php echo plugins_url('rb-agency/view/imgs/loader.gif'); ?>";
										var check = "<?php echo plugins_url('rb-agency/view/imgs/check.png'); ?>";
										jQuery(".loading").html("<img src='"+loader+"'>");
										var profile_id = jQuery(".Profile_ID").val();
										var rating = jQuery(".Profile_Rating").val();
										jQuery.ajax({
												type: "POST",
												url: "<?php echo admin_url('admin-ajax.php') ?>",
												data: {
													action: "rate_profile",
													'profile_id': profile_id,
													'profile_rating': rating
												},
												success: function (results) {
													jQuery(".loading").html("<img src='"+check+"'>");
												}
										});
									});
							});
							</script>
							<?php
							echo "			<input type=\"hidden\" class=\"Profile_ID\" value=\"".$ProfileID."\">";
							echo "  		<input type=\"text\" class=\"Profile_Rating\" value=\"".$ProfileRating."\" style=\"width:50px;\"> ";
							echo "			<input type='button' class='rate_profile' value='Rate' style='clear:both;'> <div class='loading' style='float:right; margin-right:15px; margin-top:5px; width:20px; height:20px'></div>";
							echo "        </td>\n";
							echo "    </tr>\n";
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Set this Profile to Private", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td>\n";
									$CheckedIfPrivate = $isPrivate > 0 ? "checked" : "";
							echo "          <input type=\"checkbox\" name=\"isPrivate\" id=\"isPrivate\" value=\"1\"". $CheckedIfPrivate . " />\n";
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
							echo "    <tr valign=\"top\">\n";
							echo "      <th scope=\"row\">" . __("Date Registered", RBAGENCY_TEXTDOMAIN) . "</th>\n";
							echo "      <td>\n";
							echo "        " .$ProfileDateCreated;
							echo "      </td>\n";
							echo "    </tr>\n";
							echo "    <tr valign=\"top\">\n";
							echo "        <th scope=\"row\">" . __("Enable Booking", RBAGENCY_TEXTDOMAIN) . ":</th>\n";
							echo "        <td>\n";
							echo "          <input type=\"checkbox\" name=\"ProfileIsBooking\" id=\"ProfileIsBooking\" value=\"1\"". checked(isset($ProfileIsBooking)?$ProfileIsBooking:0, 1, false) . " />\n";
							echo "        </td>\n";
							echo "    </tr>\n";
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
					<div id="dashboard_public_information" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Public Information", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
							<!-- This is generated via ajax call -->
							<?php
							if(isset($_GET['action']) && $_GET['action'] == "add") { 
								//$getGender = $ProfileGender;
                                $ProfileGender1 = get_user_meta(isset($ProfileUserLinked)?$ProfileUserLinked:0, "rb_agency_interact_pgender", true);
								if($ProfileGender==""){
									$ProfileGender = isset($_GET["ProfileGender"])?$_GET["ProfileGender"]:$ProfileGender;
								} elseif($ProfileGender1!=""){
									$ProfileGender =$ProfileGender;
								}
								echo "<table class=\"rbform-table table-customfields\">\n";
								echo "<tbody>
										<tr valign=\"top\">
									      <th scope=\"row\">Gender</th>
									      <td><select name=\"ProfileGender\" id=\"ProfileGender\">";
													      	
							$sql = "SELECT GenderID, GenderTitle FROM " . table_agency_data_gender;
							$results=  $wpdb->get_results($sql,ARRAY_A);
							$count  = $wpdb->num_rows;
							if ($count > 0) {
								foreach ($results as $data) {
									echo " <option value=\"" . $data["GenderID"] . "\" " . selected($ProfileGender, $data["GenderID"]) . ">" . $data["GenderTitle"] . "</option>\n";
								}
								echo "</select>\n";
							} else {
								echo "" . __("No items to select", rb_restaurant_TEXTDOMAIN) . ".";
							}
								echo "		 </select>
									      </td>
									    </tr>
									  </tbody>";
								echo "  <tbody class=\"tbody-table-customfields\">\n";
                                if ($ProfileGender) {
									//$ProfileGender = $_GET["ProfileGender"];
									// -1 make sure that theres no exist profile in DB
									//rb_custom_fields(0, $ProfileID, $ProfileGender, true);
								} else { // onload for edit
									$param = array();
									$param['operation'] = "editProfile";
									$param['ProfileID'] = $ProfileID;
									//$rbagencyCustomfieldsClass = new RBAgency_Customfields();
									//$rbagencyCustomfieldsClass->getCustomFieldsProfileManager($ProfileGender,$param);
									//rb_custom_fields(0, $ProfileID, $ProfileGender, true);
								}
								echo "  </tbody>\n";
								echo " </table>\n";
							}else{
								// Public Information
								echo "    <table class=\"rbform-table table-customfields\">\n";
								echo "    <tr valign=\"top\">\n";
								echo "      <th scope=\"row\">" . __("Gender", RBAGENCY_TEXTDOMAIN) . "</th>\n";
								echo "      <td>";
								echo "			<select name=\"ProfileGender\" id=\"ProfileGender\">\n";
								$ProfileGender1 = get_user_meta(isset($ProfileUserLinked)?$ProfileUserLinked:0, "rb_agency_interact_pgender", true);
								if($ProfileGender==""){
									$ProfileGender = isset($_GET["ProfileGender"])?$_GET["ProfileGender"]:$ProfileGender;
								} elseif($ProfileGender1!=""){
									$ProfileGender =$ProfileGender;
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
								echo "  <tbody class=\"tbody-table-customfields\">\n";
								// Load custom fields , Public  = 0, ProfileCustomGender = true
								// ProfileCustomView = 1 , Private
								if ($ProfileGender) {
									//$ProfileGender = $_GET["ProfileGender"];
									// -1 make sure that theres no exist profile in DB
									rb_custom_fields(0, $ProfileID, $ProfileGender, true);
								} else { // onload for edit
									$param = array();
									$param['operation'] = "editProfile";
									$param['ProfileID'] = $ProfileID;
									//$rbagencyCustomfieldsClass = new RBAgency_Customfields();
									//$rbagencyCustomfieldsClass->getCustomFieldsProfileManager($ProfileGender,$param);
									rb_custom_fields(0, $ProfileID, $ProfileGender, true);
								}
								//$reflFunc = new ReflectionFunction('rb_custom_fields');
								//print $reflFunc->getFileName() . ':' . $reflFunc->getStartLine();
								echo "  </tbody>\n";
								echo " </table>\n";
							}
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
            </div>
			<!-- Row 1: Column Right End -->		
		<?php
		if (!empty($ProfileID) && ($ProfileID > 0)) { // Editing Record
		?>
		<div id="dashboard-widgets" class="metabox-holder columns-1">
			<!-- Row 2: Column Left Start -->
			<div id="postbox-container-3" class="postbox-container">
			<!--	<div id="normal-sortables" class="meta-box-sortables ui-sortable">-->
					<?php 
					$updated_ProfileImage = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_Image%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedImages = [];
					foreach($updated_ProfileImage as $file){
						$newlyUploadedImages[] = $file['meta_value'];
					}
					?>
					<div id="dashboard_gallery" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Gallery</span></h3>
						<div class="inside">
							<div id='notify-gallery' style="display:none;background-color:#fcf8e3;border: 1px solid #faebcc;padding: 3px 7px;color:#8a6d3b">
								<p>Please click the <b>Update Record</b> button to save the order</p></div>
							<div class="main">
							<?php
								if(!empty($updated_ProfileImage)){
									echo "<span class=\"marked_changed_txt\" >".__('New image/s has been uploaded!')."</span>";
								}
								echo "<script type='text/javascript'>\n";
								echo "function confirmDelete(delMedia,mediaType) {\n";
								echo "  if (confirm('Are you sure you want to delete this '+mediaType+'?')) {\n";
								echo "  document.location= '" . admin_url("admin.php?page=" . $_GET['page']) . "&action=editRecord&ProfileID=" . $ProfileID . "&actionsub=photodelete&targetid='+delMedia;";
								echo "  }\n";
								echo "}\n";
								echo "</script>\n";
								// Mass delete
								if (isset($_GET["actionsub"]) && $_GET["actionsub"] == "massphotodelete" && is_array($_GET['targetids'])) {
									$massmediaids = '';
									$massmediaids = implode(",", $_GET['targetids']);
									//get all the images
									$queryImgConfirm = "SELECT ProfileMediaID,ProfileMediaURL FROM " . table_agency_profile_media . " WHERE ProfileID = %d AND ProfileMediaID IN ($massmediaids) AND (ProfileMediaType = 'Image' OR ProfileMediaType = 'dvd' OR ProfileMediaType = 'magazine')";
									$resultsImgConfirm = $wpdb->get_results($wpdb->prepare($queryImgConfirm, $ProfileID),ARRAY_A);
									$countImgConfirm = $wpdb->num_roAws;
									$mass_image_data = array();
									foreach ($resultsImgConfirm as $dataImgConfirm) {
										$mass_image_data[$dataImgConfirm['ProfileMediaID']] = $dataImgConfirm['ProfileMediaURL'];
									}
									//delete all the images from database
									$massmediaids = implode(",", array_keys($mass_image_data));
									$queryMassImageDelete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = $ProfileID AND ProfileMediaID IN ($massmediaids) AND (ProfileMediaType = 'Image' OR ProfileMediaType = 'dvd' OR ProfileMediaType = 'magazine') ";
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
										if ($ProfileMediaType == "Demo Reel" || $ProfileMediaType == "Demo Reel" || $ProfileMediaType == "Video Monologue" || $ProfileMediaType == "Video Slate") {
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
									}// is there record?
								}
								// Go about our biz-nazz
								# rb_agency_option_galleryorder
								# 1 - recent 0 - chronological
								
                               // echo "<div id='wrapper-sortable'><div id='gallery-sortable' style='list-style:none;'>";
                                
                                $rb_agency_options_arr = get_option('rb_agency_options');
								$order = isset( $rb_agency_options_arr['rb_agency_option_galleryorder']) ? $rb_agency_options_arr['rb_agency_option_galleryorder']:0;
								$queryImg = rb_agency_option_galleryorder_query(0 ,$ProfileID,"Image"); 
								$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
								$countImg =$wpdb->num_rows;
								$massDelete = "";
								$private_profile_photo = get_user_meta($ProfileUserLinked,'private_profile_photo',true);
								$private_profile_photo_arr = explode(',',$private_profile_photo);
								$upload_dir = wp_upload_dir();
                                
                                $uploadir = $upload_dir['baseurl']; 			
                                				
                                foreach ($resultsImg as $k=>$dataImg) {
									if ($dataImg['ProfileMediaPrimary']) {
									
									} 		
                                    
                                    $ProfileMediaURL = $dataImg['ProfileMediaURL'];						
									$image_thumbpath = $uploadir."/profile-media/". $ProfileGallery . "/thumb/". $ProfileMediaURL;
                                    $image_path = $uploadir."/profile-media/" . $ProfileGallery . "/" . $ProfileMediaURL;
                                                                  
                                        
                                        $image_path = @getimagesize($image_thumbpath) ? $image_thumbpath:$image_path;
                                        
                                        $pic = array();    
                                        $imageinfo = pathinfo($image_path);    
                                        $imagesize = @getimagesize($image_path); 
                                        $imgtype = $imagesize['mime'] ? $imagesize['mime']:"";
                                        
                                        if($imageinfo){
                                                               
                                            $pic['file'] = $image_path;
                                            $pic['url'] = $image_path;          
                                            $pic['name'] = $dataImg['ProfileMediaTitle'];
                                            $pic['id'] = $dataImg['ProfileMediaID'];
                                            $pic['primary'] = $dataImg['ProfileMediaPrimary'];
                                            $pic['private'] = $dataImg['isPrivate'];
                                            $pic['mediatype'] = $dataImg['ProfileMediaType'];
                                           
                                            $pic['type'] = $imgtype;
                                          
                                            $arr[] = $pic;
                                        }
                                    
                                   
                                    
								}
                                
                                ?>
                                    <script>

                                        var files = <?php echo json_encode($arr);?>;
                                        var profilegallery = "<?php echo $ProfileGallery;?>";
                                        var profileid = "<?php echo $ProfileID;?>";
                                        
                                    </script>
                                    <div id="files" class="files"></div>
                                    <input id="file_upload" name="rba_imgupload[]" type="file">
                                    <?php
        								if ($countImg >= 1) {
        									$btnclass = '';
        								}else{
        								    $btnclass = 'hide';
        								}
        							?>
                                    <button class='button-primary <?php echo $btnclass;?>' id='deleteProfileMedia'>Delete Selected</button>
								    </div>
                                </div>
								
								
							<div style="clear: both;"></div>
					</div>
			<!--	</div>-->
			</div>
			<!-- Row 1: Column Right End -->
		</div>
		<div id="dashboard-widgets" class="metabox-holder columns-1">
			<!-- Row 2: Column Left Start -->
			<div id="postbox-container-3" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<?php 
					# headshots
					$updated_ProfileHeadshot = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_Headshot%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedHeadshots = [];
					foreach($updated_ProfileHeadshot as $file){
						$newlyUploadedHeadshots[] = $file['meta_value'];
					}
					# images
					$updated_ProfileImage = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_Image%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedImages = [];
					foreach($updated_ProfileImage as $file){
						$newlyUploadedImages[] = $file['meta_value'];
					}
					# compcard
					$updated_CompCard = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_ComCard%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedCompCards = [];
					foreach($updated_CompCard as $file){
						$newlyUploadedCompCards[] = $file['meta_value'];
					}
					# resume
					$updated_Resume = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_Resume%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedResumes = [];
					foreach($updated_Resume as $file){
						$newlyUploadedResumes[] = $file['meta_value'];
					}
					# voicedemo
					$updated_VoiceDemo = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_VoiceDemo%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedVoiceDemos = [];
					foreach($updated_VoiceDemo as $file){
						$newlyUploadedVoiceDemos[] = $file['meta_value'];
					}
					# polariod
					$updated_Polaroid = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%MediaFileName_Polaroid%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$newlyUploadedPolaroids = [];
					foreach($updated_Polaroid as $file){
						$newlyUploadedPolaroids[] = $file['meta_value'];
					}
					# Video Files
					$updated_VideoFile = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%VideoFileName%' AND user_id = ".$ProfileUserLinked,ARRAY_A);
					$updated_VideoFiles = [];
					foreach($updated_VideoFile as $file){
						$updated_VideoFiles[] = $file['meta_value'];
					}
					?>
					<div id="dashboard_media" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Media &amp; Links</span></h3>
						<div class="inside">
							<div class="main">
							<?php
							if(!empty($updated_VideoFiles)){
								echo "<span class=\"marked_changed_txt\" >".__('New video/s has been uploaded!')."</span>";
							}
							if(isset($_GET['actionsub']) && $_GET['actionsub'] == 'del_media'){
									global $wpdb;
									$massmediaids = array();
									$uploadedaudios = array();
									foreach($_GET['media_ids'] as $k => $v){
										if(is_numeric($v)){
											$massmediaids[] = $v;
										}else{
											$uploadedaudios[] = $v;
										}
									}
									$imploded_massmediaids = implode(',',$massmediaids);
									$ProfileID = $_GET['ProfileID'];
									//delete media files here upload in profile manager
									$media_urls = array();
									$media_types = array();
									foreach($massmediaids as $k=>$v){
										$q = "SELECT ProfileMediaURL,ProfileMediaType FROM ". table_agency_profile_media ." WHERE ProfileID = $ProfileID AND ProfileMediaID = $v";
										$res = $wpdb->get_results($q);
										foreach($res as $r){
											$media_urls[] = $r->ProfileMediaURL;
											$media_types[] = $r->ProfileMediaType;
										}
									}
									//delete from uploads folder
									$dirURL = RBAGENCY_UPLOADPATH . $ProfileGallery;
									foreach($media_urls as $k=>$v){
										@unlink($dirURL . "/" . $v);
										if($media_types[$k] == 'Video Monologue' || $media_types[$k] == 'Demo Reel' || $media_types[$k] == 'Video Slate' || $media_types[$k] == 'SoundCloud' || $media_types[$k] == 'IMDB'){
											echo ("<div id=\"message\" class=\"updated\"><p> " . __("Media link <b>".$v."</b> successfully removed", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
										}else{
											echo ("<div id=\"message\" class=\"updated\"><p> " . __("Media file <b>".$v."</b> successfully removed", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
										}
									}
									foreach($uploadedaudios as $k=>$v){
										if(file_exists(RBAGENCY_UPLOADPATH.'/_casting-jobs/'.$v)){
											unlink(RBAGENCY_UPLOADPATH.'/_casting-jobs/'.$v);
											echo ("<div id=\"message\" class=\"updated\"><p> " . __("Media file <b>".$v."</b> successfully removed", RBAGENCY_TEXTDOMAIN) . ".</p></div>");
										}
									}
									//delete from the database
									$queryMassMediaDelete = "DELETE FROM " . table_agency_profile_media . " WHERE ProfileID = $ProfileID AND ProfileMediaID IN ($imploded_massmediaids) ";
									$resultsMassMediaDelete = $wpdb->query($queryMassMediaDelete);
								}
							echo "      <p>" . __("The following files (pdf, audio file, etc.) are associated with this record", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
							$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType <> \"Link\" AND ProfileMediaType <> \"Image\"";
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
									$markedClass = "";
									if(in_array($dataMedia["ProfileMediaURL"], $updated_VideoFiles)){
										$markedClass = "marked_changed";
									}
									//this will determine the source of the video id if not full url
									$CleanVideoID = str_replace(array("https://vimeo.com/","https://www.youtube.com/watch?v="),"",$dataMedia['ProfileMediaURL']);
									$headers = @get_headers("https://vimeo.com/".$CleanVideoID);
									if(strpos($headers[0],'200')===false){
										$FullVideoURL = "https://www.youtube.com/watch?v=".$CleanVideoID;
									}else{
										$FullVideoURL = "https://vimeo.com/".$CleanVideoID;
									}
									$clean_title = stripslashes($dataMedia['ProfileMediaTitle']);
									$vidTitleCaption = explode('<br>',$clean_title);
									$outVideoMedia .= "<div class=\"media-file voice-demo ".$markedClass."\" video_place_id=\"" . $dataMedia['ProfileMediaID'] . "\"><span class=\"video-type media-file-title\">" . $dataMedia['ProfileMediaType'] . "</span><br /><span class=\"video-thumb\">" . rb_agency_get_videothumbnail($FullVideoURL, $dataMedia['ProfileVideoType']) . "</span><br /><b><span class='video-title'>".ucfirst($vidTitleCaption[0])."</span></b><br><span class='video-caption'>".$vidTitleCaption[1]."</span><a href=\"" . $FullVideoURL . "\" title=\"".ucfirst($dataMedia['ProfileVideoType'])."\" target=\"_blank\"><br> Watch Video</a><br />
									[<a href=\"#inline-edit-video\" title=\"Edit Video Information\" ".
										"video_type=\"" . $dataMedia['ProfileMediaType'] .
										"\" video_id=\"" . $dataMedia['ProfileMediaID'] .
										"\" video_url=\"" . $FullVideoURL .
										"\" video_title=\"" . ucfirst($vidTitleCaption[0]) .
										"\" video_caption=\"" . $vidTitleCaption[1] .
										"\" class=\"prepare-edit-video\">EDIT</a>]
									[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\">DELETE</a>]&nbsp;<input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
								}
								 elseif ($dataMedia['ProfileMediaType'] == "VoiceDemo") {
								 	$markedClass = "";
								 	if(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedVoiceDemos)){
										$markedClass = "marked_changed";
									}
									 $voiceDemoProfileMediaID = get_option("voicedemo_".$dataMedia['ProfileMediaID']);
								 	$voicedemo = empty($voiceDemoProfileMediaID) ? "TITLE" : $voiceDemoProfileMediaID;
								 	$voiceDemoCaptionProfileMediaID = get_option("voicedemocaption_".$dataMedia['ProfileMediaID']);
								 	$voicedemocaption = empty($voiceDemoCaptionProfileMediaID) ? "" : $voiceDemoCaptionProfileMediaID;
									$medialink_option = $rb_agency_options_arr['rb_agency_option_profilemedia_links'];
									if($medialink_option == 2){
										$outLinkVoiceDemo .= "<div class=\"media-file voice-demo ".$markedClass."\" voicedemo_place_id=\"voicedemo_".$dataMedia['ProfileMediaID']."\"><span>" . $dataMedia['ProfileMediaType'] . "</span><br />
										<a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/voicedemo/" . $dataMedia['ProfileMediaURL'] . "\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"link-icon\">mp3</a>
										<span class='voicedemocaption_label'> ".$voicedemocaption."</span>
										 <br> <a href=\"".RBAGENCY_UPLOADDIR . $ProfileGallery . "/voicedemo/" . $dataMedia['ProfileMediaURL']."\" target=\"_blank\"><span class=\"voicedemo-caption\">".$voicedemo."</span></a><br><a href=\"#edit-voice-demo\" id=\"".$dataMedia['ProfileMediaID']."\" class=\"voice-demo-mp3 thickbox\" voice_demo_name_key=\"voicedemo_".$dataMedia['ProfileMediaID']."\" voice_demo_name_val=\"".$voicedemo."\"									voice_demo_caption_key=\"voicedemocaption_".$dataMedia['ProfileMediaID']."\" voice_demo_caption_val=\"".$voicedemocaption."\">&nbsp[EDIT]</a>&nbsp;<input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
									}elseif($medialink_option == 3){
										$force_download_url = wpfdl_dl($ProfileGallery . "/voicedemo/" . $dataMedia['ProfileMediaURL'],get_option('wpfdl_token'),'dl');
										$outLinkVoiceDemo .= "<div class=\"media-file voice-demo ".$markedClass."\" voicedemo_place_id=\"voicedemo_".$dataMedia['ProfileMediaID']."\"><span class='media-file-title'>" . $dataMedia['ProfileMediaType'] . "</span><br /><a ".$force_download_url." title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"link-icon\">mp3</a> <span class='voicedemocaption_label'>".$voicedemocaption."</span> <br><a ".$force_download_url."><span class=\"voicedemo-caption\">".$voicedemo."</span></a>&nbsp;<a href=\"#edit-voice-demo\" id=\"".$dataMedia['ProfileMediaID']."\" class=\"voice-demo-mp3 thickbox\" voice_demo_name_key=\"voicedemo_".$dataMedia['ProfileMediaID']."\" voice_demo_name_val=\"".$voicedemo."\" 
											voice_demo_caption_key=\"voicedemocaption_".$dataMedia['ProfileMediaID']."\" 
											voice_demo_caption_val=\"".$voicedemocaption."\">&nbsp[EDIT]</a>&nbsp;<input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
									} 
								} elseif ($dataMedia['ProfileMediaType'] == "Resume") {
									$markedClass = "";
									if(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedResumes)){
										$markedClass = "marked_changed";
									}
									$outLinkResume .= "<div class=\"media-file resume ".$markedClass."\"><span class='media-file-title'>" .$dataMedia['ProfileMediaType'] . "</span><br /><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/resume/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\" title=\"" . $dataMedia['ProfileMediaTitle'] . "\" class=\"link-icon\">pdf</a><br /><span><input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
								} elseif ($dataMedia['ProfileMediaType'] == "Headshot") {
									$markedClass = "";
									if(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedHeadshots)){
										$markedClass = "marked_changed";
									}
									$headshot_image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/headshot/". $dataMedia['ProfileMediaURL'];
                                    $headshot_file = RBAGENCY_UPLOADPATH.$ProfileGallery.DIRECTORY_SEPARATOR."headshot".DIRECTORY_SEPARATOR.$dataMedia['ProfileMediaURL'];
									$headshot_params = array(
										'crop'=>true,
										'width'=>120,
										'height'=>108,
										'crop_width'=>'500',
										'crop_height'=>'500',
										'crop_only'=>true,
										'crop_y'=>'0'
									);
                                    if(file_exists($headshot_file)){
                                        $headshot_image_src = bfi_thumb( $headshot_image_path, $headshot_params );
							     		$outLinkHeadShot .= "<div class=\"media-file ".$markedClass."\"><span class='media-file-title'>" . $dataMedia['ProfileMediaType'] . "</span><br /><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/headshot/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\"><img src=\"".$headshot_image_src ."\" /></a><br /><input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
                                    
                                    }
									
								
								} elseif ($dataMedia['ProfileMediaType'] == "CardPhotos" || 
                                $dataMedia['ProfileMediaType'] == "Polaroid" ) {
									$markedClass = "";
									if(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedHeadshots)){
										$markedClass = "marked_changed";
									}elseif(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedImages)){
										$markedClass = "marked_changed";
									}elseif(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedResumes)){
										$markedClass = "marked_changed";
									}elseif(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedVoiceDemos)){
										$markedClass = "marked_changed";
									}elseif(in_array($dataMedia['ProfileMediaURL'], $newlyUploadedPolaroids)){
										$markedClass = "marked_changed";
									}
									$polaroid_image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/polaroid/". $dataMedia['ProfileMediaURL'];
									$polaroid_path_params = array(
										'crop'=>true,
										'width'=>120,
										'height'=>108,
										'crop_width'=>'500',
										'crop_height'=>'500',
										'crop_only'=>true,
										'crop_y'=>'0'
									);
									$image_src = bfi_thumb( $polaroid_image_path, $polaroid_path_params );
									$outLinkPolaroid .= "<div class=\"media-file ".$markedClass."\"><span class='media-file-title'>" . $dataMedia['ProfileMediaType'] . "</span><br /><img src=\"".$image_src."\" /><br/><a href=\"" . $polaroid_image_path . "\" target=\"_blank\"></a><input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
								} elseif ($dataMedia['ProfileMediaType'] == "CompCard" ) {
									$markedClass = "";
									$compcard_image_path = RBAGENCY_UPLOADDIR . $ProfileGallery ."/compcard/". $dataMedia['ProfileMediaURL'];
									$compcard_path_params = array(
										'crop'=>true,
										'width'=>120,
										'height'=>108,
										'crop_width'=>'500',
										'crop_height'=>'500',
										'crop_only'=>true,
										'crop_y'=>'0'
									);
                                    $markedClass = "marked_changed"; 
									$image_src = bfi_thumb( $compcard_image_path, $compcard_path_params );
									$outLinkComCard .= "<div class='media-file com-card'><span class='media-file-title'>" . $dataMedia['ProfileMediaType'] . "</span><br /><img src=\"".$image_src."\" /><br/><a href=\"" . $compcard_image_path . "\" target=\"_blank\"></a><input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
								} else if (strpos($dataMedia['ProfileMediaType'] ,"rbcustommedia") !== false) {
									$custom_media_info = explode("_",$dataMedia['ProfileMediaType']);
									$custom_media_title = str_replace("-"," ",$custom_media_info[1]);
									$custom_media_type = $custom_media_info[2];
									$custom_media_id = $custom_media_info[4];
									$query = current($wpdb->get_results("SELECT MediaCategoryTitle, MediaCategoryFileType FROM  ".table_agency_data_media." WHERE MediaCategoryID='".$custom_media_id."'",ARRAY_A));
									$outCustomMediaLink .= "<div class=\"media-file\"><span style=\"text-transform: capitalize !important;\">".(isset($query["MediaCategoryTitle"])?$query["MediaCategoryTitle"]:$custom_media_title) ." </span> <a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataMedia['ProfileMediaURL'] . "\" class=\"custom_media-box-a\" target=\"_blank\"><div class=\"custom_media-box\"><span>" .  (isset($query["MediaCategoryFileType"])?$query["MediaCategoryFileType"]:$custom_media_type)."</span></div></a> <input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></div>\n";
								} elseif ($dataMedia['ProfileMediaType'] == "SoundCloud") {
									$outSoundCloud .= "<div style=\"width:600px;float:left;padding:10px;\">";
									$outSoundCloud .= RBAgency_Common::rb_agency_embed_soundcloud($dataMedia['ProfileMediaURL']);
									$outSoundCloud .= "<span>" . $dataMedia['ProfileMediaType'] . " - <a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>&nbsp;<input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$dataMedia['ProfileMediaID']."\"></span> \n";
                                    $outSoundCloud .= "</div>";
								}
							}
							//display audition demos
							$dir = RBAGENCY_UPLOADPATH ."_casting-jobs/";
							@$files = scandir($dir, 0);
							$medialink_option = $rb_agency_options_arr['rb_agency_option_profilemedia_links'];
							for($i = 0; $i < count($files); $i++){
								$parsedFile = explode('-',$files[$i]);
								if(count($parsedFile)>1){ 
								    if($ProfileID == $parsedFile[1]){
									$au = get_option("auditiondemo_".str_replace('.mp3','',$files[$i]));
									$auditiondemo = empty($au) ? "TITLE" : $au;
									 $path = '_casting-jobs/'.$files[$i];
									 if($medialink_option == 2){
										$outLinkVoiceDemo .= "<div class=\"media-file voice-demo\" audiodemo_place_id=\"auditiondemo_".str_replace('.mp3','',$files[$i])."\"><span>".__('VoiceDemo')."</span><br /><a href=\"".site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i]."\"  target=\"_blank\" class=\"link-icon\">mp3</a>[<a href=\"#\" onclick=\"deleteAuditionDemo('".$path."')\"  title=\"Delete this File\" class=\"delete-file\">DELETE</a>] <br><a href=\"".site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i]."\" target=\"_blank\"><span class=\"auditiondemo-caption\">".$auditiondemo."</span></a>&nbsp; <a href=\"#edit-audition-demo\" id=\"".(str_replace('.mp3','',$files[$i]))."\" class=\"audition-mp3 thickbox\" audition_demo_name_key=\"auditiondemo_".str_replace('.mp3','',$files[$i])."\"  audition_demo_name_val=\"".$auditiondemo."\">&nbsp[EDIT]</a>&nbsp;<input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$files[$i]."\"></div>\n";
									}elseif($medialink_option == 3){
										$force_download_url = wpfdl_dl('_casting-jobs/'.$files[$i],get_option('wpfdl_token'),'dl');
										$outLinkVoiceDemo .= "<div class=\"media-file voice-demo\" audiodemo_place_id=\"auditiondemo_".str_replace('.mp3','',$files[$i])."\"><span>".__('VoiceDemo')."</span><br /><a ".$force_download_url."  target=\"_blank\" class=\"link-icon\">mp3</a>[<a href=\"#\" onclick=\"deleteAuditionDemo('".$path."')\"  title=\"Delete this File\" class=\"delete-file\">DELETE</a>] <br><a ".$force_download_url." ><span class=\"auditiondemo-caption\">".$auditiondemo."</span></a>&nbsp; <a href=\"#edit-audition-demo\" id=\"".(str_replace('.mp3','',$files[$i]))."\" class=\"audition-mp3 thickbox\" audition_demo_name_key=\"auditiondemo_".str_replace('.mp3','',$files[$i])."\"  audition_demo_name_val=\"".$auditiondemo."\">&nbsp[EDIT]</a>&nbsp;<input type=\"checkbox\" class=\"media-files-checkbox\" name=\"media_files\" value=\"".$files[$i]."\"></div>\n";
									}
                                    }
								}
							}
							?>
							
							<div id="edit-audition-demo" style="display:none;">
								 <input type="hidden" name="auditiondemoname_key" class="auditiondemoname_key" >
								 <input type="hidden" name="auditiondemoname_val" class="auditiondemoname_val" >
								 <input type="hidden" name="new_auditiondemoname" class="new_auditiondemoname" >
								 <input type="hidden" name="old_auditiondemoname" class="old_auditiondemoname" >
								 <p style="padding:15px;">Title:&nbsp;<input type="text" name="auditiondemoname" class="auditiondemoname" style="width:300px;">
								 <input type='button' value="Save Changes" name='update_auditiondemoname' id="auditiondemoname_id" class='button-primary update_auditiondemoname' ></p>
							</div>
							<div id="edit-voice-demo" style="display:none;">
								 <input type="hidden" name="voicedemoname_key" class="voicedemoname_key" >
								 <input type="hidden" name="voicedemoname_val" class="voicedemoname_val" >
								 <input type="hidden" name="new_voicedemoname" class="new_voicedemoname" >
								 <input type="hidden" name="old_voicedemoname" class="old_voicedemoname" >
								 <input type="hidden" name="voicedemocaption_key" class="voicedemocaption_key" >
								 <input type="hidden" name="voicedemocaption_val" class="voicedemocaption_val" >
								 <input type="hidden" name="new_voicedemocaption" class="new_voicedemocaption" >
								 <input type="hidden" name="old_voicedemocaption" class="old_voicedemocaption" >
								 <table>
								 	<tr>
								 		<td>Title</td>
								 		<td><input type="text" name="voicedemoname" class="voicedemoname" style="width:300px;"></td>
								 	</tr>
								 	<tr>
								 		<td>Caption</td>
								 		<td><input type="text" name="voicedemocaption" class="voicedemocaption" style="width:300px;"></td>
								 	</tr>
								 	<tr>
								 		<td></td>
								 		<td><input type='button' value="Save Changes" name='update_voicedemoname' id="voicedemoname_id" class='button-primary update_voicedemoname' ></td>
								 	</tr>
								 </table>
							</div>
							<div style="display:none" class="inline-edit-video">
							<div style="" id="inline-edit-video">
								<table class="rbform-table">
										<td><br/>
											<table>
												<tr><td>Type:
													</td><td><select name="profileMediaVType" class="profileMediaVType">
														<option value="Video Slate"><?php echo __("Video Slate", RBAGENCY_TEXTDOMAIN);?></option>
														<option value="Video Monologue"><?php echo __("Video Monologue", RBAGENCY_TEXTDOMAIN);?></option>
														<option value="Demo Reel"><?php echo __("Demo Reel", RBAGENCY_TEXTDOMAIN);?></option>
														<option value="SoundCloud"><?php echo __("SoundCloud", RBAGENCY_TEXTDOMAIN);?></option>
														<option value="IMDB"><?php echo __("IMDB", RBAGENCY_TEXTDOMAIN);?></option>
													</select>
												</td></tr>
												<tr><td>Media URL: </td><td><input type='text' name='media_url'class='profilemedia_url full-width-text' ></td></tr>
												<tr><td>Title: </td><td><input type='text' name='media_title' class='profilemedia_title full-width-text'></td></tr>
												<tr><td>Caption </td><td><input type='text' name='media_caption' class='profilemedia_caption full-width-text'></td></tr>
												<tr><td>  </td><td><br><input type='button' value="Save Changes" name='save_media_inline' class='button-primary save_media_inline'></td></tr>
											</table>
										</td>
										</tr>
										</table><input type='hidden' name='media_id' class='profilemedia_id'>
							</div>
							</div>
							
							<?php
							echo '<div class="media-files">';
								if(!empty($outLinkVoiceDemo)):
									echo $outLinkVoiceDemo;
									endif;
								if(!empty($outLinkResume)):
									echo $outLinkResume;
								endif;
								if(!empty($outLinkComCard)):
									echo $outLinkComCard;
									
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
                                
                                $delbtn = '<div class="bulk_delete_media"><button class="button-primary" id="bulk_delete_media">Delete Selected</button></div>';
								if ($countMedia < 1) {
									echo "<div><em>" . __("There are no additional media linked", RBAGENCY_TEXTDOMAIN) . "</em></div>\n";
                                    $delbtn = "";
								}
							echo '</div>';
						echo '</div>';
						echo '<br><br>';
						echo $delbtn;
					echo '</div>';
					/*
					 * Get Links
					 */
						$queryLinks = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType = \"Link\"";
						$resultsLinks =  $wpdb->get_results($wpdb->prepare($queryLinks, $ProfileID),ARRAY_A);
						$countLinks = $wpdb->num_rows;
						if ($countLinks > 0) {
							echo "<h3>Links</h3>\n";
							echo "<ul>\n";
							foreach ($resultsLinks  as $dataLinks) {
								$checked = $dataLinks["isPrivate"] == 1 ? "checked" : "";
							echo "	<li>\n";
							echo "		<a href='". $dataLinks['ProfileMediaURL'] ."' target='_blank'>". $dataLinks['ProfileMediaTitle'] ."\n";
							echo "		[<a href=\"javascript:confirmDelete('" . $dataLinks['ProfileMediaID'] . "','Link')\" title=\"Delete this Link\" class=\"delete-file\">DELETE</a>]&nbsp;<input type=\"checkbox\" name=\"set_imdb_private[]\" value=\"".$dataLinks['ProfileMediaID']."\" $checked>&nbsp;Click to set private\n";
							echo "	</li>\n";
							}
							echo "</ul>\n";
						}
						?>
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
								//include_once(RBAGENCY_PLUGIN_DIR .'view/include-photouploadmulti.php');
											// Upload Images
								echo "      <p>" . __("Upload new media using the forms below", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
								if(isset($errorValidation['profileMedia'])){echo "<p style='background-color: #FFEBE8; border-color: #CC0000;margin: 5px 0 15px;' >".$errorValidation['profileMedia']."</p>\n";}
								echo "<table class=\"rbform-table\">";
								for ($i = 1; $i < 10; $i++) {
									//echo "<tr><th colspan=\"2\">Type:</th></tr>\n";
									echo "<tr><th>Type: <select name=\"profileMedia" . $i . "Type\">\n";
									echo "<option value=\"\">--Please Select--</option>\n";
									//echo "<option value=\"Image\">Photo</option>\n";
									echo "<option value=\"Headshot\">Headshot</option>\n";
									echo "<option value=\"CompCard\">Comp Card</option>\n";
									echo "<option value=\"Resume\">Resume</option>\n";
									echo "<option value=\"VoiceDemo\">Voice Demo</option>\n";
									echo "<option value=\"Polaroid\">Polaroid</option>\n";
									echo "<option value=\"CardPhotos\">Card Photos</option>";
									rb_agency_getMediaCategories($ProfileGender);
									echo "</select>\n";
									echo "</th>\n";
									echo "<td><input type='file' id='profileMedia" . $i . "' name='profileMedia" . $i . "' /></td>\n";
									echo "</tr>\n";
								}
								echo "</table>";
								?>
							</div>
						</div>
					</div>
				</div>
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="dashboard_line_to_links" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Link to Other Accounts", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main" >
							<div id="other-account-url-wrapper">
								<?php 
								$OtherProfileAccountUserMeta = get_user_meta($_GET["ProfileID"],"otherAccountURLs_".$_GET["ProfileID"],true);
								if(empty($OtherProfileAccountUserMeta)){
									echo "<input type=\"text\" class=\"rb-url-input add-other-account-url-txt\" name=\"otherAccountURLs[]\" placeholder=\"Add URL Here\"/><br/>";
								}else{
									$OtherProfileAccountUserMeta = explode("|",$OtherProfileAccountUserMeta);
									foreach($OtherProfileAccountUserMeta as $url){
										echo "<input type=\"text\" class=\"rb-url-input add-other-account-url-txt\" name=\"otherAccountURLs[]\" placeholder=\"Add URL Here\" value=\"".$url."\"/><br/>";
									}
								}
								?>
							</div>							
							<input type="button" class="button-primary add-account-url-btn" value="Add URL">
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
								// Loop through form
								for ($k = 1 ; $k < 4; $k++){
									echo "<table class=\"rbform-table\">\n
										<tr valign=\"top\">\n
										<th>\n
										Type:\n
											<select name=\"profileMediaV". $k ."Type\">\n
												<option value='Video Slate' selected>" . __("Video Slate", RBAGENCY_interact_TEXTDOMAIN) . "</option>\n
												<option value='Video Monologue'>" . __("Video Monologue", RBAGENCY_interact_TEXTDOMAIN) . "</option>\n
												<option value='Demo Reel'>" . __("Demo Reel", RBAGENCY_interact_TEXTDOMAIN) . "</option>\n
												<option value='SoundCloud'>" . __("SoundCloud", RBAGENCY_interact_TEXTDOMAIN) . "</option>\n
												<option value='IMDB'>" . __("IMDB", RBAGENCY_interact_TEXTDOMAIN) . "</option>\n
											</select>\n
										</th>\n
										<td>\n
											<table>\n
												<tr><td>Media URL: </td><td><input type='text' id='profileMediaV". $k ."' name='profileMediaV". $k ."'></td></tr>\n
												<tr><td>Title: </td><td><input type='text' name='media". $k ."_title'></td></tr>\n
												<tr><td>Caption </td><td><input type='text' name='media". $k ."_caption'></td></tr>\n
												<!--<tr><td>Video Type </td><td><input type='radio' name='media". $k ."_vtype' value='youtube' checked>&nbsp; Youtube <br/>\n
													<input type='radio' name='media". $k ."_vtype' value='vimeo' >&nbsp; Vimeo</td></tr>-->\n
											</table>\n
										</td>\n
										</tr>\n
										</table>\n";
								}
							?>
							</div>
						</div>
					</div>
					<!-- Custom Links -->
					<div id="dashboard_line_to_links" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Link to URLs", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php
								// Add Videos
								echo "      <p>" . __("Specify the URL of a custom link.", RBAGENCY_TEXTDOMAIN) . ".</p>\n";
								// Loop through form
								for ($k = 1 ; $k < 4; $k++){
								echo "<table class=\"rbform-table\">\n
										<tr valign=\"top\">\n
											<th>\n
												Title:\n
												<input type='text' id='profileLinkTitleV". $k ."' name='profileLinkTitleV". $k ."' placeholder='IMDB' />\n
											</th>\n
											<td>\n
												URL:\n
												<input type='text' id='profileLinkURLV". $k ."' name='profileLinkURLV". $k ."' placeholder='http://imdb.com/rob' />\n
											</td>\n
										</tr>\n
									</table>\n";
								}
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--box cover gallary-->
			<div id="postbox-container-3" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					
					<div id="dashboard_gallery" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Box Cover</span></h3>
						<div class="inside">
							<div class="main">
                            <?php 
                                $queryImg = rb_agency_option_galleryorder_boxcover_query($order ,$ProfileID,"BoxCover"); 
                               
								$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
								$countImg =$wpdb->num_rows;
								$massDelete = "";
								$private_profile_photo = get_user_meta($ProfileUserLinked,'private_profile_photo',true);
								$private_profile_photo_arr = explode(',',$private_profile_photo);
								
                                $arr = array();								
                                foreach ($resultsImg as $k=>$dataImg) {
									if ($dataImg['ProfileMediaPrimary']) {
									
									} 		
                                    
                                    $ProfileMediaURL = $dataImg['ProfileMediaURL'];						
									$image_thumbpath = RBAGENCY_UPLOADDIR. $ProfileGallery . "/thumb/". $ProfileMediaURL;
                                    $image_path = RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $ProfileMediaURL;
                                                                  
                                     //if(pathinfo($image_thumbpath,PATHINFO_EXTENSION ) || pathinfo($image_path,PATHINFO_EXTENSION ))  { 
                                        
                                        $image_path = @getimagesize($image_thumbpath) ? $image_thumbpath:$image_path;
                                        
                                        $pic = array();    
                                        $imageinfo = pathinfo($image_path);    
                                        $imagesize = @getimagesize($image_path); 
                                        $imgtype = $imagesize['mime'] ? $imagesize['mime']:"";
                                        
                                        if($imageinfo){
                                        //$realpath =  realpath($image_path);                           
                                        $pic['file'] = $image_path;
                                        $pic['url'] = $image_path;          
                                        $pic['name'] = $dataImg['ProfileMediaTitle'];
                                        $pic['id'] = $dataImg['ProfileMediaID'];
                                        $pic['primary'] = $dataImg['ProfileMediaPrimary'];
                                        $pic['private'] = $dataImg['isPrivate'];
                                        $pic['mediatype'] = strtoupper($dataImg['ProfileMediaType']);
                                        $pic['type'] = $imgtype;
                                        //$pic['size'] = filesize($realpath);
                                        $arr[] = $pic;
                                        }
                                    //}
                                   
                                    
								}
                                ?>
                            <script>

                            var boxcoverfiles = <?php echo json_encode($arr);?>;
                            var profilegallery = "<?php echo $ProfileGallery;?>";
                            var profileid = "<?php echo $ProfileID;?>";
                                                            
                            </script>
                            <div id="boxcoverfiles" class="boxcoverfiles"></div>
                            <select name="profileMediaType" id="boxcovertype" class="boxcovertype">
                            <option value="dvd">DVD</option>
                            <option value="magazine">Magazine</option>
                            </select>
                            <input id="boxcover_upload" data-mediatype="dvd" name="rba_boxcover_upload[]" type="file">
                            <?php
                                if ($countImg >= 1) {
									$btnclass = '';
								}else{
								    $btnclass = 'hide';
                                    echo "No Box Cover uploaded for this profile yet";
								}
								
							?>
                            <button class='button-primary <?php echo $btnclass;?>' id='deleteBoxCover'>Delete Selected</button>
                            </div>
                       </div>     
					</div> <!-- #dashboard_gallery -->
				</div> <!-- #normal-sortables -->
			</div> <!-- end box cover gallary-->
			
            <!--Expanded Model Detail-->
			<div id="postbox-container-6" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<!-- Custom Links -->
					<div id="dashboard_line_to_links" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo  __("Custom Display Settings", RBAGENCY_TEXTDOMAIN); ?></span></h3>
						<div class="inside">
							<div class="main">
								<?php
								$edit_hide_age_year = get_user_meta($_REQUEST['ProfileID'],'rb_agency_hide_age_year',true) == true ? "checked='checked'" : "";
								$edit_hide_age_month = get_user_meta($_REQUEST['ProfileID'],'rb_agency_hide_age_month',true)== true ? "checked='checked'" : "";
								$edit_hide_age_day = get_user_meta($_REQUEST['ProfileID'],'rb_agency_hide_age_day',true)== true ? "checked='checked'" : "";
								$edit_hide_state = get_user_meta($_REQUEST['ProfileID'],'rb_agency_hide_state',true)== true ? "checked='checked'" : "";
								?>
							<table>
								<tr>
									<th>Expanded Model Detail</th>
									<td>
										<table>
										<tr>
											<td><?php echo "<input type=\"checkbox\" name=\"hide_age_year\" ".$edit_hide_age_year."/> &nbsp; Hide Age (Year)"; ?></td>
										</tr>
										<tr>
											<td><?php echo "<input type=\"checkbox\" name=\"hide_age_month\" ".$edit_hide_age_month."/> &nbsp; Hide Age (Month)"; ?></td>
										</tr>
										<tr>
											<td><?php echo "<input type=\"checkbox\" name=\"hide_age_day\" ".$edit_hide_age_day."/> &nbsp; Hide Age (Day)"; ?></td>
										</tr>
										<tr>
											<td><?php echo "<input type=\"checkbox\" name=\"hide_state\" ".$edit_hide_state."/> &nbsp; Hide State"; ?></td>
										</tr>
									</table>
									</td>
								</tr>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Row 2: Column Right End -->
			<!--Job Audition -->
			<?php
			if ( is_plugin_active( 'rb-agency-casting/rb-agency-casting.php' ) ) {
			?>
			<div class="postbox-container" id="postbox-container-6">
				<div class="meta-box-sortables ui-sortable" id="side-sortables">
				<?php
					$cartArray = isset($_SESSION['cartArray'])?$_SESSION['cartArray']:array();
					$cartString = implode(",", array_unique($cartArray));
					$cartString = RBAgency_Common::clean_string($cartString);
					//echo '<pre>';print_r($_SESSION);echo '<pre>';
					$ProfileID = isset($_REQUEST['ProfileID'])?$_REQUEST['ProfileID']:0;
					$query = "SELECT cs_job.*, avail.* FROM  ".table_agency_casting_job." AS cs_job LEFT JOIN ".table_agency_castingcart_availability."
					AS avail ON cs_job.Job_ID = avail.CastingJobID WHERE avail.CastingAvailabilityProfileID = ".$ProfileID."
					";
					
					$job_data = $wpdb->get_results($query);
				
					$count = $wpdb->num_rows;
				
				?>
					<div class="postbox " id="dashboard_line_to_links">
						<div title="Click to toggle" class="handlediv"><br></div>
						<h3 class="hndle"><span>Job Auditions</span></h3>
						<div class="inside">
							<div class="main">
							<table cellpadding="10">
								<tbody>
									<tr>
										<th>Job ID</th>
										<th>Job Title</th>
										<th>Date Confirmed</th>
										<th>MP3 Audition Files</th>
										<th>Availability</th>
									</tr>
									<?php
									//audio files
									?>
									<?php
									if(count($job_data) > 0)
									{
										foreach($job_data as $job)
										{
										?><tr>
											<td><?php echo $job->Job_ID ; ?> </td>
											<td><a href="<?php echo site_url(); ?>/job-detail/<?php echo $job->Job_ID ?>"><?php echo $job->Job_Title ; ?> </a></td>
											<td><?php echo $job->CastingAvailabilityDateCreated ; ?> </td>
											<td>
												<?php
												$dir = RBAGENCY_UPLOADPATH ."_casting-jobs/";
												@$files = scandir($dir, 0);
												//print_r($files);
												$medialink_option = $rb_agency_options_arr['rb_agency_option_profilemedia_links'];
												for($i = 0; $i < count($files); $i++){
												$parsedFile = explode('-',$files[$i]);
													if($parsedFile[0] == $job->Job_ID && $ProfileID == $parsedFile[1]){
														//$mp3_file = str_replace(array($parsedFile[0].'-',$parsedFile[1].'-'),'',$files[$i]);
														if($medialink_option == 2){
															//open in new window and play
															$auditiondemo = get_option("auditiondemo_".str_replace('.mp3','',$files[$i]));
															$auditiondemo = !empty($auditiondemo) ? $auditiondemo : 'Play Audio';
															echo '<div class="media-file voice-demo" audaudiodemo_place_id="auditiondemo_'.str_replace('.mp3','',$files[$i]).'"><a href="'.site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i].'" target="_blank" class="audvoicedemo-caption">'.$auditiondemo.'</a>&nbsp;<a href="#aud-edit-voice-demo" id="'.$auditiondemo.'" class="aud-audition-mp3 thickbox" aud_voice_demo_name_key="auditiondemo_'.trim(str_replace('.mp3','',$files[$i])).'" aud_voice_demo_name_val="'.trim($auditiondemo).'">&nbsp;[rename]</a></div><br>';
														}elseif($medialink_option == 3){
															//open in new window and download
															$force_download_url = wpfdl_dl('_casting-jobs/'.$files[$i],get_option('wpfdl_token'),'dl');
															$auditiondemo = get_option("auditiondemo_".str_replace('.mp3','',$files[$i]));
															$auditiondemo = !empty($auditiondemo) ? $auditiondemo : 'Play Audio';
															echo '<div class="media-file voice-demo" audaudiodemo_place_id="auditiondemo_'.str_replace('.mp3','',$files[$i]).'"><a '.$force_download_url.' target="_blank" class="audvoicedemo-caption">'.$auditiondemo.'</a>&nbsp;<a href="#aud-edit-voice-demo" id="'.(str_replace('.mp3','',$files[$i])).'" class="aud-audition-mp3 thickbox" aud_voice_demo_name_key="auditiondemo_'.str_replace('.mp3','',$files[$i]).'" aud_voice_demo_name_val="'.$auditiondemo.'">&nbsp;[rename]</div></div><br>';
														}
													}
												}
												
												?>
											</td>
											<div id="aud-edit-voice-demo" style="display:none;">
												  <input type="hidden" name="audvoicedemoname_key" class="audvoicedemoname_key" >
												 <input type="hidden" name="audvoicedemoname_val" class="audvoicedemoname_val" >
												 <input type="hidden" name="audnew_voicedemoname" class="audnew_voicedemoname">
												 <input type="hidden" name="audold_voicedemoname" class="audold_voicedemoname" >
												 <p style="padding:15px;">Title:&nbsp;<input type="text" name="audvoicedemoname" class="audvoicedemoname" style="width:300px;">
												 <input type='button' value="Save Changes" name='audupdate_voicedemoname' id="audvoicedemoname_id" class='button-primary audupdate_voicedemoname' ></p>
											</div>
											<script type="text/javascript">
											jQuery(document).ready(function(){
												//voice demo
												jQuery('.aud-audition-mp3').click(function(){
													var aud_voice_demo_name_key = jQuery(this).attr('aud_voice_demo_name_key');
													var aud_voice_demo_name_val = jQuery(this).attr('aud_voice_demo_name_val');
													jQuery('.audvoicedemoname').val(aud_voice_demo_name_val);
													jQuery('.audold_voicedemoname').val(aud_voice_demo_name_val);
													jQuery('.audvoicedemoname_key').val(aud_voice_demo_name_key);
													jQuery('.audvoicedemoname_val').val(aud_voice_demo_name_val);
													tb_show('Edit Audition Demo','#TB_inline?width=500&height=100&inlineId=aud-edit-voice-demo');
													return false;
												});
												jQuery('.audvoicedemoname').keyup(function(){
													jQuery('.audnew_voicedemoname').val(jQuery(this).val());
												});
												jQuery('.audupdate_voicedemoname').click(function(){
													var new_val = jQuery('.audnew_voicedemoname').val();
													var old_val = jQuery('.audold_voicedemoname').val();
													var audvoicedemoname_key = jQuery('.audvoicedemoname_key').val();
													jQuery.post("<?php echo admin_url('admin-ajax.php');?>", {
														demo_name_key:audvoicedemoname_key,
														old_value: old_val,
														new_value:new_val,
														action: 'audeditvoicedemo'
													}).done(function(data) {
														console.log(data);
														jQuery(".audvoicedemo-caption" , '.media-file[audaudiodemo_place_id='+audvoicedemoname_key+']').html('');
														jQuery(".audvoicedemo-caption" , '.media-file[audaudiodemo_place_id='+audvoicedemoname_key+']').html(new_val);
														jQuery(".auditiondemo-caption", '.media-file[audiodemo_place_id='+audvoicedemoname_key+']').html('');
														jQuery(".auditiondemo-caption", '.media-file[audiodemo_place_id='+audvoicedemoname_key+']').html(new_val);
													});
													tb_remove();
													return false;
												});
											});
											</script>
											<td>
												<?php
													$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
													$prepared = $wpdb->prepare($query,$_GET['ProfileID'],$job->Job_ID);
													$availability = current($wpdb->get_results($prepared));
													if($availability->status == 'notavailable'){
														echo 'Not Available';
													}else{
														echo ucfirst($availability->status);
													}
												?>
											</td>
										</tr>
									<?php
										}
									}
									else
									{
										?>
										<tr>
											<td colspan="3"> No Record Found !</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<!-- Row 3: Column Right End -->
			<?php }?>
		</div>
	</div>
	<?php
	if($_GET["action"] == "add"){
	} elseif($_GET["action"] == "editRecord"){
		echo "</div>";
	}
		//echo "<div class=\"postbox\">\n";
		//echo "	<div class=\"inside\">\n";
	if (!empty($ProfileID) && ($ProfileID > 0)) {
		echo "     <input type=\"hidden\" name=\"ProfileID\" value=\"" . $ProfileID . "\" />\n";
		echo "     <input type=\"hidden\" name=\"ProfileUserLinked\" value=\"" . $ProfileUserLinked. "\" />\n";
		echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
		echo "     <input type=\"submit\" name=\"submit\" value=\"" . __("Update Record", RBAGENCY_TEXTDOMAIN) . "\" class=\"button button-primary button-hero\" />\n";
		//echo "" . __("Last updated ", RBAGENCY_TEXTDOMAIN) . " " . rb_make_ago($ProfileDateUpdated) . "\n";
	} else {
		echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
		echo "     <input type=\"submit\" name=\"submit\" value=\"" . __("Create Record", RBAGENCY_TEXTDOMAIN) . "\" class=\"button button-primary button-hero\" />\n";
	}
		//echo "	</div>\n";
		//echo "</div>\n";
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
			$selectedNameFirst = stripcslashes($_GET['ProfileContactNameFirst']) ;
			$selectedNameFirst = preg_replace("/[^A-Za-z0-9 ]/", '%', $selectedNameFirst);
			$query .= "&ProfileContactNameFirst=". $selectedNameFirst ."";
				if(strpos($filter,'profile') > 0){
					$filter .= " AND profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
				} else {
					$filter .= " profile.ProfileContactNameFirst LIKE '". $selectedNameFirst ."%'";
				}
			}
			if (isset($_GET['ProfileContactNameLast']) && !empty($_GET['ProfileContactNameLast'])){
			$selectedNameLast = stripcslashes($_GET['ProfileContactNameLast']);
            $selectedNameLast = preg_replace("/[^A-Za-z0-9 ]/", '%', $selectedNameLast);
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
        if (isset($_GET['registerdatefr']) && isset($_GET['registerdateto'])){
            $registerdatefr = $_GET['registerdatefr'];
            $registerdateto = $_GET['registerdateto'];
           
            if($registerdatefr!="" && $registerdateto!=""){
			$query .= "&registerdatefr=". $registerdatefr ."";
            $query .= "&registerdateto=". $registerdateto ."";
            
			if(strpos($filter,'profile') > 0){
						 
            $filter .= " AND profile.ProfileDateCreated > '$registerdatefr 00:00:00' AND profile.ProfileDateCreated < '$registerdateto 23:59:59'";
                           
	           }else{
	               
            $filter .= " profile.ProfileDateCreated > '$registerdatefr 00:00:00' AND profile.ProfileDateCreated < '$registerdateto 23:59:59'";
            }	
					
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
		} else {
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
	echo "              <span><label>" . __("First Name:", RBAGENCY_TEXTDOMAIN) . "</label><input type=\"text\" name=\"ProfileContactNameFirst\" value=\"" . $selectedNameFirst . "\" /></span>\n";
	echo "              <span><label>" . __("Last Name:", RBAGENCY_TEXTDOMAIN) . "</label><input type=\"text\" name=\"ProfileContactNameLast\" value=\"" . $selectedNameLast . "\" /></span>\n";
	echo "              <span><label>" . __("Category:", RBAGENCY_TEXTDOMAIN) . "</label>\n";
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
    echo "              <span class='w50'><label>" . __("Date Registered:", RBAGENCY_TEXTDOMAIN) . "</label><input id='registerdatefr' type=\"text\" placeholder='From' name=\"registerdatefr\" value=\"" . $registerdatefr . "\" />";
    echo "              <input type=\"text\" placeholder='To' name=\"registerdateto\" id='registerdateto' value=\"" . $registerdateto . "\" /></span>";
	echo "              <span class=\"submit\"><input type=\"submit\" id='filter-btn' value=\"" . __("Filter", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" /></span>\n";
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
		echo $p->show();// Echo out the list of paging.
	}
	echo "  </div>\n";
	echo "</div>\n";
	echo "<div id='rbplugin'>\n";
	echo "<form method=\"post\" action=\"" . admin_url("admin.php?page=" . $_GET['page']) . "\">\n";
	echo "<table cellspacing=\"0\" class=\"widefat fixed\">\n";
	echo " <thead>\n";
	echo "    <tr class=\"thead\">\n";
	echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
	echo "        <th class=\"column-ProfileID\" id=\"ProfileID\" scope=\"col\" style=\"width:50px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileID&dir=" . $sortDirection."&".$build_query) . "\">ID</a></th>\n";
	if($rb_agency_option_formshow_displayname == 1){
	echo "        <th class=\"column-ProfileContactDisplay\" id=\"ProfileContactDisplay\" scope=\"col\" style=\"width:150px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileContactDisplay&dir=" . $sortDirection."&".$build_query) . "\">Display Name</a></th>\n";
	}
	echo "        <th class=\"column-ProfileContactNameFirst\" id=\"ProfileContactNameFirst\" scope=\"col\" style=\"width:150px;\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileContactNameFirst,ProfileContactNameLast&dir=" . $sortDirection."&".$build_query) . "\">".__("First Name",RBAGENCY_TEXTDOMAIN)."</a></th>\n";
	echo "        <th class=\"column-ProfileContactNameLast\" id=\"ProfileContactNameLast\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileContactNameLast,ProfileContactNameFirst&dir=" . $sortDirection."&".$build_query) . "\">".__("Last Name",RBAGENCY_TEXTDOMAIN)."</a></th>\n";
	echo "        <th class=\"column-ProfileGender\" id=\"ProfileGender\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileGender&dir=" . $sortDirection."&".$build_query) . "\">".__("Gender",RBAGENCY_TEXTDOMAIN)."</a></th>\n";
	echo "        <th class=\"column-ProfilesProfileDate\" id=\"ProfilesProfileDate\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileDateBirth&dir=" . $sortDirection."&".$build_query) . "\">".__("Age",RBAGENCY_TEXTDOMAIN)."</a></th>\n";
	echo "        <th class=\"column-ProfileLocationCity\" id=\"ProfileLocationCity\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileLocationCity&dir=" . $sortDirection."&".$build_query) . "\">".__("City",RBAGENCY_TEXTDOMAIN)."</a></th>\n";
	echo "        <th class=\"column-ProfileLocationState\" id=\"ProfileLocationState\" scope=\"col\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page'] . "&sort=ProfileLocationState&dir=" . $sortDirection."&".$build_query) . "\">".__("State",RBAGENCY_TEXTDOMAIN)."</a></th>\n";
	echo "        <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">".__("Category",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column-ProfileDetails\" id=\"ProfileDetails\" scope=\"col\">".__("Images",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column-ProfileStatHits\" id=\"ProfileStatHits\" scope=\"col\">".__("Views",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column-isPrivate\" id=\"isPrivate\" scope=\"col\">".__("Private",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column-ProfileDateCreated\" id=\"ProfileDateCreated\" scope=\"col\">".__("Register Date",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column-ProfileDateViewLast\" id=\"ProfileDateViewLast\" scope=\"col\" style=\"width:100px;\">".__("Last Viewed Date",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "    </tr>\n";
	echo " </thead>\n";
	echo " <tfoot>\n";
	echo "    <tr class=\"thead\">\n";
	echo "        <th class=\"manage-column column-cb check-column\" id=\"cb\" scope=\"col\"><input type=\"checkbox\"/></th>\n";
	echo "        <th class=\"column\" scope=\"col\">ID</th>\n";
	if($rb_agency_option_formshow_displayname == 1){
		echo "        <th class=\"column\" scope=\"col\">Display Name</th>\n";
	}
	echo "        <th class=\"column\" scope=\"col\">".__("First Name",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Last Name",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Gender",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Age",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("City",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("State",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Category",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Images",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Views",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Private",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "        <th class=\"column\" scope=\"col\">".__("Last Viewed",RBAGENCY_TEXTDOMAIN)."</th>\n";
	echo "    </tr>\n";
	echo " </tfoot>\n";
	echo " <tbody>\n";
	echo "</div>\n";
	//xyr code
	//altering the main profile table for private fields...
	$queryAlterCheck = "SELECT isPrivate FROM " . table_agency_profile ." LIMIT 1";
	$resultsDataAlter = $wpdb->get_results($queryAlterCheck,ARRAY_A);
	$count_alter = $wpdb->num_rows;
	if($count_alter == 0){
		// sometimes upgrade script wasnt execute.. so we just want to be sure.
		$queryAlter = "ALTER TABLE " . table_agency_profile ." ADD isPrivate boolean NOT NULL default false";
		$resultsDataAlter = $wpdb->get_results($queryAlter,ARRAY_A);
		/* echo "<h2>Table Altered for Private profile option. Please refresh the page.</h2>";
		exit; */
	}
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
        $ProfileDateCreated = stripslashes($data['ProfileDateCreated']);
		$isPrivate = stripslashes($data['isPrivate']);
		$CustomOrder = stripslashes($data['CustomOrder']);
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
		echo "    <tr id='$ProfileID' class=\"".$statusClass."\">\n";
		echo "        <td class=\"check-column\" scope=\"row\">\n";
		echo "          <input type=\"checkbox\" value=\"" . $ProfileID . "\"  data-name=\"".$ProfileContactNameFirst." ". $ProfileContactNameLast."\" class=\"administrator\" name=\"" . $ProfileID . "\"/>\n";
		echo "        </td>\n";
		echo "        <td class=\"ProfileID column-ProfileID\">" . $ProfileID . "</td>\n";
		if($rb_agency_option_formshow_displayname == 1){
			echo "        <td class=\"ProfileID column-ProfileContactDisplay data-profile-name-type='".$rb_agency_option_profilenaming."'\">" . $ProfileContactDisplay;
			echo "          <div class=\"row-actions\">\n";
			echo "            <span class=\"edit\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&amp;action=editRecord&amp;ProfileID=" . $ProfileID . "\" title=\"" . __("Edit this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Edit", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"edit\"><a href=\"" . RBAGENCY_PROFILEDIR .  $ProfileGallery . "/\" title=\"" . __("View", RBAGENCY_TEXTDOMAIN) . "\" target=\"_blank\">" . __("View", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=deleteRecord&ProfileID=" . $ProfileID . "\"  onclick=\"if ( confirm('" . __("You are about to delete the profile for ", RBAGENCY_TEXTDOMAIN) . " " . $ProfileContactNameFirst . " " . $ProfileContactNameLast . "? \'" . __("Cancel", RBAGENCY_TEXTDOMAIN) . "\' " . __("to stop", RBAGENCY_TEXTDOMAIN) . ", \'" . __("OK", RBAGENCY_TEXTDOMAIN) . "\' " . __("to delete", RBAGENCY_TEXTDOMAIN) . ".') ) {return true;}return false;\" title=\"" . __("Delete this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Delete", RBAGENCY_TEXTDOMAIN) . "</a> </span>\n";
			echo "          </div>\n";
			echo "	</td>\n";
		}
		echo "        <td class=\"ProfileContactNameFirst column-ProfileContactNameFirst\">\n";
		echo "          " . $ProfileContactNameFirst . "\n";
		if($rb_agency_option_formshow_displayname == 0){
			echo "          <div class=\"row-actions\">\n";
			echo "            <span class=\"edit\"><a href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&amp;action=editRecord&amp;ProfileID=" . $ProfileID . "\" title=\"" . __("Edit this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Edit", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"edit\"><a href=\"" . RBAGENCY_PROFILEDIR .  $ProfileGallery . "/\" title=\"" . __("View", RBAGENCY_TEXTDOMAIN) . "\" target=\"_blank\">" . __("View", RBAGENCY_TEXTDOMAIN) . "</a> | </span>\n";
			echo "            <span class=\"delete\"><a class=\"submitdelete\" href=\"" . admin_url("admin.php?page=" . $_GET['page']) . "&action=deleteRecord&ProfileID=" . $ProfileID . "\"  onclick=\"if ( confirm('" . __("You are about to delete the profile for ", RBAGENCY_TEXTDOMAIN) . " " . $ProfileContactNameFirst . " " . $ProfileContactNameLast . "? \'" . __("Cancel", RBAGENCY_TEXTDOMAIN) . "\' " . __("to stop", RBAGENCY_TEXTDOMAIN) . ", \'" . __("OK", RBAGENCY_TEXTDOMAIN) . "\' " . __("to delete", RBAGENCY_TEXTDOMAIN) . ".') ) {return true;}return false;\" title=\"" . __("Delete this Record", RBAGENCY_TEXTDOMAIN) . "\">" . __("Delete", RBAGENCY_TEXTDOMAIN) . "</a> </span>\n";
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
		echo "        <td class=\"isPrivate column-isPrivate\">". (!empty($isPrivate) ? "<b>Private</b>": " ") . "</td>\n";
			if(isset($ProfileDateCreated)){
			 $createdate=date_create($ProfileDateCreated);
             $ProfileDateCreated = date_format($createdate,"M. j, Y");
				echo "        <td class=\"ProfileDateCreated column-ProfileDateCreated\" id=\"ProfileDateCreated\">".$ProfileDateCreated."</td>\n";
			}
		echo "        <td class=\"ProfileDateViewLast column-ProfileDateViewLast\" attr_lastview=\"".strtotime($ProfileDateViewLast)."\" attr_timezone=\"". $rb_agency_option_locationtimezone."\">\n";
		//echo "           " . rb_agency_makeago(rb_agency_convertdatetime($ProfileDateViewLast), $rb_agency_option_locationtimezone);
		echo human_time_diff(date('U',strtotime($ProfileDateViewLast)), current_time('timestamp') ) . ' ago'; 
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
		echo $p->show();// Echo out the list of paging.
	}
	echo "  </div>\n";
	echo "</div>\n";
	// Show Actions
	echo "<p class=\"submit\">\n";
	echo "  <button class='delete-profiles button button-primary button-hero'>" . __('Delete Selected Profiles',RBAGENCY_TEXTDOMAIN) . "</button>";
	echo "</p>\n";
	echo "</form>\n";
}
if(isset($_GET['action']) && $_GET['action'] == "add"){
} elseif(isset($_GET['action']) && $_GET['action'] == "editRecord"){
	echo "</div>\n";
}
?>