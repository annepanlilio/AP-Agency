<?php
global $wpdb;

/**
 * Ensure directories are setup
 */
	if (!is_dir(rb_agency_UPLOADPATH)) {
		mkdir(rb_agency_UPLOADPATH, 0755);
		chmod(rb_agency_UPLOADPATH, 0777);
	}

/*
 * Enable if we do not have a version number

	// Upgrade from unknonwn version
	if (!isset(get_option('rb_agency_version')) && empty(get_option('rb_agency_version'))) { 
		update_option('rb_agency_version', "1.8");
	}
 */

	// Safe Add column
	function rb_agency_addColumn($tbl = "",$column = "", $atts = ""){
		global $wpdb;
		$debug = debug_backtrace();
		if($wpdb->get_var("SHOW COLUMNS FROM ".trim($tbl)." LIKE '%".trim($column)."%' ") != trim($column)){
			$result = $wpdb->query(" ALTER TABLE ".trim($tbl)." ADD ".trim($column)." ".$atts.";");// or die("rb_agency_addColumn()  - Adding column ".trim($column)." in line ".$debug["line"]." <br/> ".mysql_error());
			return $result;
		}
	}

// *************************************************************************************************** //

/**
 * Upgrades from previous version
 */

	// Upgrade from 1.8
	if (get_option('rb_agency_version') == "1.8" || get_option('rb_agency_version') == "1.8.1") { 

		$results = $wpdb->query("ALTER TABLE rb_agency_data_type ADD DataTypeTag VARCHAR(55)");
		
		$query = "SELECT DataTypeID, DataTypeTitle, DataTypeTag FROM rb_agency_data_type";
		$results = $wpdb->get_results($query,ARRAY_A) or die ( __("Cant load types", rb_agency_TEXTDOMAIN ));
		foreach ($results as $data) {
			if (!isset($data['DataTypeTag']) || empty($data['DataTypeTag'])) {
				$DataTypeTag = RBAgency_Common::Format_StripChars($data['DataTypeTitle']);
				
				$update = "UPDATE rb_agency_data_type SET DataTypeTag='" . $wpdb->escape($DataTypeTag) . "' WHERE DataTypeID='". $data['DataTypeID'] ."'";
				$updated = $wpdb->query($update);
				echo "- Updated tag ". $DataTypeTag ."<br />\n";
			}
		}

		// Privacy in Custom Fields
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomView","INT(10) NOT NULL DEFAULT '0'");

		// Updating version number!
		update_option('rb_agency_version', "1.8.2");
	}

	// Upgradef rom 1.8.2
	if (get_option('rb_agency_version') == "1.8.2") {

		// Change Field Names
		$results = $wpdb->query("ALTER TABLE rb_agency_customfields CHANGE ProfileCustomOptions ProfileCustomOptions TEXT");
		$results = $wpdb->query("ALTER TABLE rb_agency_customfield_mux CHANGE ProfileCustomValue ProfileCustomValue TEXT");

		// Add Column
		rb_agency_addColumn("rb_agency_profile","ProfileIsFeatured","INT(10) NOT NULL DEFAULT '0'");
		rb_agency_addColumn("rb_agency_profile","ProfileIsPromoted","INT(10) NOT NULL DEFAULT '0'");

		// Setup > Save Favorited
		if ($wpdb->get_var("show tables like 'rb_agency_savedfavorite'") !=  "rb_agency_savedfavorite") { 
			 $results = $wpdb->query("CREATE TABLE rb_agency_savedfavorite (
				SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SavedFavoriteProfileID VARCHAR(255),
				SavedFavoriteTalentID VARCHAR(255),
				PRIMARY KEY (SavedFavoriteID)
				);");
		}

		// Updating version number!
		update_option('rb_agency_version', "1.8.5");
	}

	// Upgrade from 1.8.5
	if (get_option('rb_agency_version') == "1.8.5") {

		// Setup > Taxonomy: Gender
		if ($wpdb->get_var("show tables like 'rb_agency_data_gender'") !=  "rb_agency_data_gender" ) { 
			$results = $wpdb->query("CREATE TABLE rb_agency_data_gender (
				GenderID INT(10) NOT NULL AUTO_INCREMENT,
				GenderTitle VARCHAR(255),
				PRIMARY KEY (GenderID)
				);");
			// Populate Initial Values
			$data_type_exists = $wpdb->get_var( $wpdb->prepare( "SELECT DataTypeID FROM rb_agency_data_type WHERE DataTypeTitle = %s", 'Model' ) );
			if ( !$data_type_exists ) {
				$insert = $wpdb->query("INSERT INTO rb_agency_data_type (DataTypeID, DataTypeTitle) VALUES ('','Model')");
			}
			$data_type_exists = $wpdb->get_var( $wpdb->prepare( "SELECT DataTypeID FROM rb_agency_data_type WHERE DataTypeTitle = %s", 'Talent' ) );
			if ( !$data_type_exists ) {
				$insert = $wpdb->query("INSERT INTO rb_agency_data_type (DataTypeID, DataTypeTitle) VALUES ('','Talent')");
			}
		}

		// Custom Order in Custom Fields
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomOrder","INT(10) NOT NULL DEFAULT '0'");
		
		// Updating version number!
		update_option('rb_agency_version', "1.9");
	}

	// Upgrade from 1.8.9
	if (get_option('rb_agency_version') == "1.8.9") {
		update_option('rb_agency_version', "1.9");
	}

	// Upgrade from 1.9
	if (get_option('rb_agency_version') == "1.9") {

		// Setup > Save Favorited
		if ($wpdb->get_var("show tables like 'rb_agency_savedfavorite'") !=  "rb_agency_savedfavorite" ) { 
			$results = $wpdb->query("CREATE TABLE rb_agency_savedfavorite (
				SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SavedFavoriteProfileID VARCHAR(255),
				SavedFavoriteTalentID VARCHAR(255),
				PRIMARY KEY (SavedFavoriteID)
				);");
		}

		// Add Casting Cart
		if ($wpdb->get_var("show tables like 'rb_agency_castingcart'") != "rb_agency_castingcart" ) { 
			$results = $wpdb->query("CREATE TABLE rb_agency_castingcart (
				CastingCartID BIGINT(20) NOT NULL AUTO_INCREMENT,
				CastingCartProfileID VARCHAR(255),
				CastingCartTalentID VARCHAR(255),
				PRIMARY KEY (CastingCartID)
				);");
		}

		// Add Media Category
		if ($wpdb->get_var("show tables like 'rb_agency_mediacategory'") != "rb_agency_mediacategory") { 
			$results = $wpdb->query("CREATE TABLE rb_agency_mediacategory (
				MediaCategoryID BIGINT(20) NOT NULL AUTO_INCREMENT,
				MediaCategoryTitle VARCHAR(255),
				MediaCategoryGender VARCHAR(255),
				MediaCategoryOrder VARCHAR(255),
				PRIMARY KEY (MediaCategoryID)
				);");
		}

		// Add Custom Fields
		if ($wpdb->get_var("show tables like 'rb_agency_customfields'") != "rb_agency_customfields") { 
			$results = $wpdb->query("CREATE TABLE rb_agency_customfields (
				ProfileCustomID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomTitle VARCHAR(255),
				ProfileCustomType INT(10) NOT NULL DEFAULT '0',
				ProfileCustomOptions TEXT,
				ProfileCustomView INT(10) NOT NULL DEFAULT '0',
				ProfileCustomOrder INT(10) NOT NULL DEFAULT '0',
				ProfileCustomShowGender INT(10) NOT NULL DEFAULT '0',
				ProfileCustomShowProfile INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowSearch INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowLogged INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowRegistration INT(10) NOT NULL DEFAULT '1',
				ProfileCustomShowAdmin INT(10) NOT NULL DEFAULT '1',
				PRIMARY KEY (ProfileCustomID)
				);");
		}

		// Custom Order in Custom Fields
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomOrder"," INT(10) NOT NULL DEFAULT '0'");

		// Custom Visibility in Custom Fields
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomShowProfile"," INT(10) NOT NULL DEFAULT '1'");
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomShowGender"," INT(10) NOT NULL DEFAULT '0'");
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomShowSearch"," INT(10) NOT NULL DEFAULT '1'");
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomShowLogged"," INT(10) NOT NULL DEFAULT '1'");
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomShowRegistration"," INT(10) NOT NULL DEFAULT '1'");
		rb_agency_addColumn("rb_agency_customfields","ProfileCustomShowAdmin"," INT(10) NOT NULL DEFAULT '1'");
		
		// Populate Initial Values
		$data_custom_exists = $wpdb->get_var( $wpdb->prepare( "SELECT ProfileCustomTitle FROM rb_agency_customfields WHERE ProfileCustomTitle = %s", 'Ethnicity' ) );
		if ( !$data_custom_exists ) {
			// Assume the rest dont exist either
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (1, 'Ethnicity', 	3, '|African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other|', 0, 1, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (2, 'Skin Tone', 	3, '|Fair|Medium|Dark|', 0, 2, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (3, 'Hair Color', 	3, '|Blonde|Black|Brown|Dark Brown|Light Brown|Red|Strawberry|Auburn|', 0, 3, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (4, 'Eye Color', 	3, '|Blue|Brown|Hazel|Green|Black|', 0, 4, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (5, 'Height', 		7, '3', 0, 5, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (6, 'Weight', 		7, '2', 0, 6, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (7, 'Shirt', 		1, '', 0, 8, 1, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (8, 'Waist', 		7, '1', 0, 9, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES (9, 'Hips', 		7, '1', 0, 10, 2, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(10, 'Shoe Size', 	7, '1', 0, 11, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(11, 'Suit', 		3, '|36S|37S|38S|39S|40S|41S|42S|43S|44S|45S|46S|36R|38R|40R|42R|44R|46R|48R|50R|52R|54R|40L|42L|44L|46L|48L|50L|52L|54L|', 0, 7, 1, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(12, 'Inseam', 		7, '1', 0, 10, 1, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(13, 'Dress', 		3, '|2|4|6|8|10|12|14|16|18|', 0, 8, 2, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(14, 'Bust', 		3, '|32A|32B|32C|32D|32DD|34A|34B|34C|34D|34DD|36C|36D|36DD|', 0, 7, 2, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(15, 'Union', 		3, '|SAG/AFTRA|SAG ELIG|NON-UNION|', 0, 20, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(16, 'Experience', 	4, '', 0, 13, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(17, 'Language', 	1, '', 0, 14, 0, 1, 1, 0, 1, 0)");
			$insert = $wpdb->query("INSERT INTO rb_agency_customfields VALUES(18, 'Booking', 	4, '', 0, 15, 0, 1, 1, 0, 1, 0)");
		}

		//Fix Custom Fields compatibility. 1.8 to 1.9.1
		$q2 = mysql_query("SELECT * FROM rb_agency_customfields ");
		while($fetchData = mysql_fetch_assoc($q2)):
			if($fetchData["ProfileCustomType"] == 0){
				mysql_query("UPDATE rb_agency_customfields SET  ProfileCustomType = 1 WHERE ProfileCustomType = 0 ") or die(mysql_error());
			}
			if($fetchData["ProfileCustomType"] == 3){
				mysql_query("UPDATE rb_agency_customfields SET  ProfileCustomOptions = '"."|".$fetchData["ProfileCustomOptions"]."' WHERE ProfileCustomID = ".$fetchData["ProfileCustomID"]."") or die(mysql_error());
			}
		endwhile;

		// Fix Gender compatibility
		if ($wpdb->get_var("show tables like 'rb_agency_profile'") != "rb_agency_profile") { 
			$q3 = mysql_query("SELECT ProfileID, ProfileGender FROM rb_agency_profile ") or die(mysql_error());
		
			while($fetchData = mysql_fetch_assoc($q3)):
				$queryGender = mysql_query("SELECT GenderID, GenderTitle FROM rb_agency_data_gender WHERE  GenderTitle='".$fetchData["ProfileGender"]."'")  or die(mysql_error());
				$fetchGender = mysql_fetch_assoc($queryGender);
				$count = mysql_num_rows($queryGender);
				if($count > 0){
					$wpdb->query("UPDATE rb_agency_profile SET ProfileGender='".$fetchGender["GenderID"]."' WHERE ProfileID='".$fetchData["ProfileID"]."'");
				}
			endwhile;
		}

		// Updating version number!
		update_option('rb_agency_version', "1.9.1");
	}

	// Upgrade from 1.9.1
	if (get_option('rb_agency_version')== "1.9.1") {

		// Fix Gender compatibility
		$q4 = mysql_query("SELECT ProfileID, ProfileGender FROM rb_agency_profile ") or die("1".mysql_error());
		while($fetchData = mysql_fetch_assoc($q4)):

			$queryGender = mysql_query("SELECT GenderID, GenderTitle FROM rb_agency_data_gender WHERE  GenderTitle='".$fetchData["ProfileGender"]."'")  or die("2".mysql_error());
			$fetchGender = mysql_fetch_assoc($queryGender);
			$count = mysql_num_rows($queryGender);
			if($count > 0){
				mysql_query("UPDATE rb_agency_profile SET ProfileGender='".(0 + (int)$fetchGender["GenderID"])."' WHERE ProfileID='".$fetchData["ProfileID"]."'")  or die("4".mysql_error());
			}

		endwhile;
		// Updating version number!
		update_option('rb_agency_version', "1.9.1.1");
	}

	// Upgrade from 1.9.1.1
	if (get_option('rb_agency_version') == "1.9.1.1") {	
		$resultsProfile = mysql_query("SELECT * FROM rb_agency_profile");
		while($f_Profile = mysql_fetch_assoc($resultsProfile)){
			$ProfileID = $f_Profile["ProfileID"];
			$ProfileGender = $f_Profile["ProfileGender"];

			$arr_profile_features = array(
				"ProfileLanguage" => "Language",
				"ProfileStatEthnicity" => "Ethnicity",
				"ProfileStatSkinColor" => "Skin Tone",
				"ProfileStatHairColor" => "Hair Color",
				"ProfileStatEyeColor" => "Eye Color",
				"ProfileStatHeight" => "Height",
				"ProfileStatWeight" => "Weight",
				"ProfileStatBust" => "Bust",
				"ProfileStatWaist" => "Waist",
				"ProfileStatHip" => "Hips",
				"ProfileStatShoe" => "Shoe Size",
				"ProfileStatDress" => "Dress",
				"ProfileUnion" => "Union",
				"ProfileExperience" => "Experience");

			// old column   to  custom fields 
			foreach($arr_profile_features as $oldColumn => $migrate_data):
				$query ="INSERT INTO rb_agency_customfield_mux (ProfileCustomID,ProfileID,ProfileCustomValue)
						SELECT ProfileCustomID, '". $ProfileID."','".$f_Profile[$oldColumn]."'
						FROM rb_agency_customfields
						WHERE ProfileCustomTitle ='". $migrate_data."'";
				 mysql_query($query) or die(mysql_error());
			endforeach;
		}// end while data fetch

		// Updating version number!
		update_option('rb_agency_version', "1.9.1.2");
	}
	if (get_option('rb_agency_version') == "1.9.1.2") {
		update_option('rb_agency_version', "1.9.1.3");
	}
	if (get_option('rb_agency_version') == "1.9.1.3") {
		update_option('rb_agency_version', "1.9.1.4");
	}
	if (get_option('rb_agency_version') == "1.9.1.4") {
		update_option('rb_agency_version', "1.9.1.5");
	}
	if (get_option('rb_agency_version') == "1.9.1.5") {
		update_option('rb_agency_version', "1.9.1.6");
	}
	if (get_option('rb_agency_version') == "1.9.1.6") {
		update_option('rb_agency_version', "1.9.2");
	}

	// Update from 1.9.2
	if (get_option('rb_agency_version') == "1.9.2") {

		/**
		 *  Update custom fields
		 */
		$resultsProfile = mysql_query("SELECT * FROM rb_agency_profile"); // Get all profiles
		while($f_Profile = mysql_fetch_assoc($resultsProfile)){

			$ProfileID = $f_Profile["ProfileID"];   // set the profile id

			$arr_profile_features = array(
				"ProfileLanguage" => "Language",
				"ProfileStatEthnicity" => "Ethnicity",
				"ProfileStatSkinColor" => "Skin Tone",
				"ProfileStatHairColor" => "Hair Color",
				"ProfileStatEyeColor" => "Eye Color",
				"ProfileStatHeight" => "Height",
				"ProfileStatWeight" => "Weight",
				"ProfileStatBust" => "Bust",
				"ProfileStatWaist" => "Waist",
				"ProfileStatHip" => "Hips",
				"ProfileStatShoe" => "Shoe Size",
				"ProfileStatDress" => "Dress",
				"ProfileUnion" => "Union",
				"ProfileExperience" => "Experience");
				// old column   to  custom fields 
			foreach($arr_profile_features as $oldColumn => $migrate_data):

				$qCustomFieldID = mysql_query("SELECT ProfileCustomID,ProfileCustomTitle FROM rb_agency_customfields WHERE ProfileCustomTitle = '".$migrate_data."'");	
				$count = mysql_num_rows($qCustomFieldID);
				if($count > 0){
					$fCustomFieldID = mysql_fetch_assoc($qCustomFieldID);
					if (isset($f_Profile[$oldColumn])) {
						$query1 ="UPDATE rb_agency_customfield_mux SET  ProfileCustomValue = '". $f_Profile[$oldColumn] ."' WHERE ProfileCustomID = '".$fCustomFieldID["ProfileCustomID"]."' AND ProfileID = '".$ProfileID ."'";
						$q1 =  mysql_query($query1) or die(mysql_error());
						//mysql_free_result($q1);
					}
				} /*else{
					$query ="INSERT INTO " . table_agency_customfield_mux. "(ProfileCustomID,ProfileID,ProfileCustomValue)
					   SELECT  ProfileCustomID, '". $ProfileID."','".$f_Profile[$oldColumn]."'
					   FROM   " . table_agency_customfields . "  
					   WHERE ProfileCustomTitle ='". $migrate_data."'";
					 $q2 = mysql_query($query) or die(mysql_error());
					 mysql_free_result($q2);
				}*/
				mysql_free_result($qCustomFieldID);
			endforeach;
		}// end while data fetch
			
		update_option('rb_agency_version', "1.9.2.1");
	}

	// Update from 1.9.*
	if (substr(get_option('rb_agency_version'), 0, 3) == "1.9") {
		update_option('rb_agency_version', "2.0.0");
	}

	// Update from 2.0.0
	if (get_option('rb_agency_version') == "2.0.0") {

		// Remove unneeded tables
		$results = $wpdb->query("DROP TABLE rb_agency_data_ethnicity");
		$results = $wpdb->query("DROP TABLE rb_agency_data_colorskin");
		$results = $wpdb->query("DROP TABLE rb_agency_data_coloreye");
		$results = $wpdb->query("DROP TABLE rb_agency_data_colorhair");
		$results = $wpdb->query("DROP TABLE rb_agency_rel_taxonomy");

		// Rename tables to include prefix
		$results = $wpdb->query("RENAME TABLE rb_agency_profile TO {$wpdb->prefix}agency_profile");
		$results = $wpdb->query("RENAME TABLE rb_agency_profile_media TO {$wpdb->prefix}agency_profile_media");
		$results = $wpdb->query("RENAME TABLE rb_agency_data_gender TO {$wpdb->prefix}agency_data_gender");
		$results = $wpdb->query("RENAME TABLE rb_agency_data_type TO {$wpdb->prefix}agency_data_type");
		$results = $wpdb->query("RENAME TABLE rb_agency_mediacategory TO {$wpdb->prefix}agency_data_media");
		$results = $wpdb->query("RENAME TABLE rb_agency_customfields TO {$wpdb->prefix}agency_customfields");
		$results = $wpdb->query("RENAME TABLE rb_agency_customfields_types TO {$wpdb->prefix}agency_customfields_types");
		$results = $wpdb->query("RENAME TABLE rb_agency_customfield_mux TO {$wpdb->prefix}agency_customfield_mux");
		$results = $wpdb->query("RENAME TABLE rb_agency_searchsaved TO {$wpdb->prefix}agency_searchsaved");
		$results = $wpdb->query("RENAME TABLE rb_agency_searchsaved_mux TO {$wpdb->prefix}agency_searchsaved_mux");
		$results = $wpdb->query("RENAME TABLE rb_agency_savedfavorite TO {$wpdb->prefix}agency_savedfavorite");
		$results = $wpdb->query("RENAME TABLE rb_agency_castingcart TO {$wpdb->prefix}agency_castingcart");

		// Update Version Number
		update_option('rb_agency_version', "2.0.1");
	}

	// Update from 2.0.0
	if (get_option('rb_agency_version') == "2.0.1") {

		// Do the tables exist?
		if ($wpdb->get_var("show tables like '". table_agency_customfields_types ."'") == table_agency_customfields_types) {
		} else {
			// Setup > Custom Field Types
			$results = $wpdb->query("CREATE TABLE IF NOT EXISTS ". table_agency_customfields_types." (
				ProfileCustomTypesID BIGINT(20) NOT NULL AUTO_INCREMENT,
				ProfileCustomID BIGINT(20) NOT NULL,
				ProfileCustomTitle VARCHAR(255),
				ProfileCustomTypes VARCHAR(255),
				PRIMARY KEY (ProfileCustomTypesID)
				);");
		}

		// Update Version Number
		update_option('rb_agency_version', "2.0.2");
	}

	// Nothing to see here...
	if (get_option('rb_agency_version') == "2.0.1" || get_option('rb_agency_version') == "2.0.2" || get_option('rb_agency_version') == "2.0.3") {

		// Does the media table exist?
		if ($wpdb->get_var("show tables like 'rb_agency_mediacategory'") == 'rb_agency_mediacategory') {
			$results = $wpdb->query("RENAME TABLE rb_agency_mediacategory TO {$wpdb->prefix}agency_data_media");
		} else {
			// TODO WHY THIS LINE? 
			//$results = $wpdb->query("RENAME TABLE {$wpdb->prefix}agency_customfields TO {$wpdb->prefix}agency_data_media");
		}

		update_option('rb_agency_version', "2.0.4");
	}

	// Update from 2.0.4
	if (get_option('rb_agency_version') == "2.0.4") {

		// Setup > Country
		if ($wpdb->get_var("show tables like '". table_agency_data_country."'") != table_agency_data_country) { 
			$results = $wpdb->query("CREATE TABLE IF NOT EXISTS ". table_agency_data_country ." (
			CountryID INT(10) NOT NULL AUTO_INCREMENT,
			CountryTitle VARCHAR(255),
			CountryCode VARCHAR(20),
			PRIMARY KEY (CountryID)
			);");
		}

		// Populate Initial Values
		$data_custom_exists = $wpdb->get_var( $wpdb->prepare( "SELECT CountryTitle FROM " . table_agency_data_country . " WHERE CountryTitle = %s", 'United States' ) );
		if ( !$data_custom_exists ) {
			$results = $wpdb->query("INSERT INTO ". table_agency_data_country ." (CountryTitle, CountryCode) VALUES ('United States','US')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_country ." (CountryTitle, CountryCode) VALUES ('Canada','CA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_country ." (CountryTitle, CountryCode) VALUES ('Mexico','MX')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_country ." (CountryTitle, CountryCode) VALUES ('Australia','AU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_country ." (CountryTitle, CountryCode) VALUES ('United Kingdom','UK')");
		}

		// Setup > States
		if ($wpdb->get_var("show tables like '". table_agency_data_state."'") != table_agency_data_state) { 
			$results = $wpdb->query("CREATE TABLE IF NOT EXISTS ". table_agency_data_state ." (
			StateID INT(20) NOT NULL AUTO_INCREMENT,
			CountryID INT(20) NOT NULL,
			StateTitle VARCHAR(255),
			StateCode VARCHAR(255),
			PRIMARY KEY (StateID)
			);");
		}

		// Populate Initial Values
		$data_custom_exists = $wpdb->get_var( $wpdb->prepare( "SELECT StateTitle FROM " . table_agency_data_state . " WHERE StateTitle = %s", 'Alabama' ) );
		if ( !$data_custom_exists ) {
			// United States
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Alabama','AL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Alaska','AK')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Arizona','AZ')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Arkansas','AR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'California','CA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Colorado','CO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Connecticut','CT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Delaware','DE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'District of Columbia','DC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Florida','FL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Georgia','GA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Hawaii','HI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Idaho','ID')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Illinois','IL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Indiana','IN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Iowa','IA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Kansas','KS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Kentucky','KY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Louisiana','LA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Maine','ME')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Maryland','MD')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Massachusetts','MA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Michigan','MI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Minnesota','MN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Mississippi','MS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Missouri','MO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Montana','MT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Nebraska','NE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Nevada','NV')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'New Hampshire','NH')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'New Jersey','NJ')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'New Mexico','NM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'New York','NY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'North Carolina','NC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'North Dakota','ND')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Ohio','OH')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Oklahoma','OK')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Oregon','OR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Pennsylvania','PA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Rhode Island','RI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'South Carolina','SC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'South Dakota','SD')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Tennessee','TN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Texas','TX')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Utah','UT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Vermont','VT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Virginia','VA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Washington','WA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'West Virginia','WV')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Wisconsin','WI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (1, 'Wyoming','WY')");
			// Canada
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'British Columbia','BC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Ontario','ON')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Newfoundland and Labrador','NL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Nova Scotia','NS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Prince Edward Island','PE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'New Brunswick','NB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Quebec','QC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Manitoba','MB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Saskatchewan','SK')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Alberta','AB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Northwest Territories','NT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Nunavut','NU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (2, 'Yukon Territory','YT')");
			// Mexico
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Aguascalientes','AG')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Baja California','BN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Baja California Sur','BS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Campeche','CM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Chiapas','CP')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Chihuahua','CH')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Coahuila','CA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Colima','CL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Distrito Federal','DF')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Durango','DU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Guanajuato','GJ')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Guerrero','GR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Hidalgo','HI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Jalisco','JA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Mexico State','MX')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Micohancan','MC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Morelos','MR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Nayarit','NA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Nuevo Leon','NL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Oaxaca','OA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Puebla','PU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Queretaro','QE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Quintana Roo','QR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'San Luis Potosi','SL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Sinaloa','SI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Sonora','SO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Tabasco','TB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Tamaulipas','TM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Tlaxcala','TL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Veracruz','VE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Yucatan','YU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (3, 'Zacatecas','ZA')");
			// Australia
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Australian Capital Territory','ACT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Jervis Bay Territory','JBT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'New South Wales','NSW')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Northern Territory','NT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Queensland','QLD')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'South Australia','SA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Tasmania','TAS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Victoria','VIC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (4, 'Western Australia','WA')");
			// Great Britan
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Aberdeenshire','AB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Anglesey',''AN)");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Angus','AG')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Argyll','AR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Ayrshire','AY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Banffshire','BN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Bedfordshire','BD')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Berwickshire','BI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Breconshire','BR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Buckinghamshire','BK')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Bute','BU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Caernarvonshire','CN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Caithness','CT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Cambridgeshire','CM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Cardiganshire','CG')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Carmarthenshire','CR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Cheshire','CH')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Clackmannanshire','CL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Cumbria','CU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Denbighshire','DI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Derbyshire','DB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Devon','DV')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Dorset','DO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Dumbartonshire','DA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Dumfriesshire','DU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Durham','DR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'East Lothian','EL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'East Sussex','ES')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Essex','EX')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Fife','FI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Flintshire','FL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Glamorgan','GM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Gloucestershire','GL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Greater London','GR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Greater Manchester','GM2')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Hampshire','HM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Hertfordshire','HT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Inverness','IV')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Kent','KN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Kincardineshire','KE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Kinross-shire','KP')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Kirkcudbrightshire','KC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Lanarkshire','LA')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Lancashire','LC')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Leicestershire','LE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Lincolnshire','LN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'London','LD')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Merionethshire','ME')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Merseyside','MR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Midlothian','MI')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Monmouthshire','MO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Montgomeryshire','MG')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Moray','MY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Nairnshire','NN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Norfolk','NR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Northamptonshire','NH')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Northumberland','NU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'North Yorkshire','NY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Nottinghamshire','NO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Orkney','OR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Oxfordshire','OX')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Peebleshire','PB')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Pembrokeshire','PM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Perthshire','PT')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Radnorshire','RD')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Renfrewshire','RN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Ross & Cromarty','RS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Roxburghshire','RX')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Selkirkshire','SL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Shetland','SE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Shropshire','SH')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Somerset','SO')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'South Yorkshire','SY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Staffordshire','ST')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Stirlingshire','SN')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Suffolk','SU')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Surrey','SUR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Sutherland','SR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Tyne and Wear','TW')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Warwickshire','WR')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'West Lothian','WE')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'West Midlands','WM')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'West Sussex','WS')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'West Yorkshire','WY')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Wigtownshire','WG')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Wiltshire','WL')");
			$results = $wpdb->query("INSERT INTO ". table_agency_data_state ." (CountryID, StateTitle, StateCode) VALUES (5, 'Worcestershire','WO')");
		}

		update_option('rb_agency_version', "2.0.5");
	}

	if (get_option('rb_agency_version') == "2.0.5") {
		// Updating version number!
		update_option('rb_agency_version', "2.0.6");
	}

	if (get_option('rb_agency_version') == "2.0.6") {
		// Add Column to media file for holding video type media
		rb_agency_addColumn( table_agency_profile_media,"ProfileVideoType","VARCHAR(255) NOT NULL DEFAULT 'youtube'");
		// Updating version number!
		update_option('rb_agency_version', "2.0.7");
	}

	if (substr(get_option('rb_agency_version'), 0, 5) == "2.0.7") {
		// Add Column
		rb_agency_addColumn( table_agency_customfields,"ProfileCustomShowSearchSimple","INT(10) NOT NULL DEFAULT '0'");

		// Updating version number!
		update_option('rb_agency_version', "2.0.8");
	}

?>