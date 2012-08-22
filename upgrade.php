<?php
global $wpdb;
$rb_agency_storedversion = get_option('rb_agency_version');

// *************************************************************************************************** //
// Set Default Values for Options

	$rb_agency_options_arr = array(
		"rb_agency_option_agencyname" => "",
		"rb_agency_option_agencyemail" => "",
		"rb_agency_option_agencyheader" => "",
		"rb_agency_option_agencylogo" => "",
		"rb_agency_option_unittype" => "1",
		"rb_agency_option_showsocial" => "1",
		"rb_agency_option_galleryorder" => "1",
		"rb_agency_option_gallerytype" => "1",
		"rb_agency_option_layoutprofile" => "0",
		"rb_agency_option_advertise" => "1",
		"rb_agency_option_privacy" => "0",
		"rb_agency_option_agencyimagemaxwidth" => "1000",
		"rb_agency_option_agencyimagemaxheight" => "800",
		"rb_agency_option_profilenaming" => "0",
		"rb_agency_option_showcontactpage" =>"1",
		"rb_agency_option_customfield_profilepage" => "1",
		"rb_agency_option_customfield_searchpage" => "1",
		"rb_agency_option_customfield_loggedin_all" => "1",
		"rb_agency_option_customfield_loggedin_admin" => "1"
		);


// *************************************************************************************************** //
// Set default version #
	//if (!isset($rb_agency_storedversion) && empty($rb_agency_storedversion)) { // Upgrade from ??
	//	update_option('rb_agency_version', "1.0");
	//}

// *************************************************************************************************** //
// Upgrade to 1.8.1
	if (get_option('rb_agency_version') == "1.8") { 

		$results = $wpdb->query("ALTER TABLE ". table_agency_data_type ." ADD DataTypeTag VARCHAR(55)");
		
		$query = "SELECT DataTypeID, DataTypeTitle, DataTypeTag FROM ". table_agency_data_type ."";
		$results = mysql_query($query) or die ( __("Cant load types", rb_agency_TEXTDOMAIN ));
		while ($data = mysql_fetch_array($results)) {
			if (!isset($data['DataTypeTag']) || empty($data['DataTypeTag'])) {
				$DataTypeTag = rb_agency_safenames($data['DataTypeTitle']);
				
				$update = "UPDATE " . table_agency_data_type . " SET DataTypeTag='" . $wpdb->escape($DataTypeTag) . "' WHERE DataTypeID='". $data['DataTypeID'] ."'";
				$updated = $wpdb->query($update);
				echo "- Updated tag ". $DataTypeTag ."<br />\n";
			}
		}

		// Privacy in Custom Fields
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomView INT(10) NOT NULL DEFAULT '0'");

		// Updating version number!
		update_option('rb_agency_version', "1.8.2");
	}
	if (get_option('rb_agency_version') == "1.8.2") { 
	
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." CHANGE ProfileCustomOptions ProfileCustomOptions TEXT");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfield_mux ." CHANGE ProfileCustomValue ProfileCustomValue TEXT");
	
		$results = $wpdb->query("ALTER TABLE ". table_agency_profile ." ADD ProfileIsFeatured  INT(10) NOT NULL DEFAULT '0'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_profile ." ADD ProfileIsPromoted  INT(10) NOT NULL DEFAULT '0'");
		
		// Setup > Save Favorited
		 $results = $wpdb->query("CREATE TABLE ". table_agency_savedfavorite." (
				SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
				SavedFavoriteProfileID VARCHAR(255),
			    SavedFavoriteTalentID VARCHAR(255),
				PRIMARY KEY (SavedFavoriteID)
				);");
		
		// Updating version number!
		update_option('rb_agency_version', "1.8.5");
	}
	if (get_option('rb_agency_version') == "1.8.5") { 

		// Setup > Taxonomy: Gender
		$results = $wpdb->query("CREATE TABLE ". table_agency_data_gender ." (
			GenderID INT(10) NOT NULL AUTO_INCREMENT,
			GenderTitle VARCHAR(255),
			PRIMARY KEY (GenderID)
			);");
	    // Custom Order in Custom Fields
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomOrder INT(10) NOT NULL DEFAULT '0'");
		$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Male')");
		$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Female')");
		
		// Updating version number!
		update_option('rb_agency_version', "1.9");
	}

  if (get_option('rb_agency_version') == "1.9") {
	  
		// Setup > Save Favorited
		$results = $wpdb->query("CREATE TABLE ". table_agency_savedfavorite." (
			SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
			SavedFavoriteProfileID VARCHAR(255),
			SavedFavoriteTalentID VARCHAR(255),
			PRIMARY KEY (SavedFavoriteID)
			);");
			
		$results = $wpdb->query("CREATE TABLE ". table_agency_castingcart." (
				CastingCartID BIGINT(20) NOT NULL AUTO_INCREMENT,
				CastingCartProfileID VARCHAR(255),
			      CastingCartTalentID VARCHAR(255),
				PRIMARY KEY (CastingCartID)
				);");
				
	   	// Custom Order in Custom Fields
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomOrder INT(10) NOT NULL DEFAULT '0'");

	   	// Custom Visibility in Custom Fields
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowGender INT(10) NOT NULL DEFAULT '0'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowProfile INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowSearch INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowLogged INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowRegistration INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowAdmin INT(10) NOT NULL DEFAULT '1'");


	 	// Populate Custom Fields
		$query = mysql_query("SELECT ProfileCustomTitle FROM ". table_agency_customfields ." WHERE ProfileCustomTitle = 'Language'");
		$count = mysql_num_rows($query);
		if ($count < 1) {
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomShowGender,ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Ethnicity',  3,	'|African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other',0, 0, 1, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES ('Skin Tone',  3,	'|Fair|Medium|Dark|no', 0, 0, 2, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES ('Hair Color', 3,	'|Blonde|Black|Brown|Dark Brown|Light Brown|Red|Strawberry|Auburn|',0, 0, 3, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Eye Color',  3,	'|Blue|Brown|Hazel|Green|Black|',0, 0, 4, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Height',     7,	'1', 0, 0, 5,  1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Weight',     7,	'2', 0, 0, 6,  1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Chest',      7,	'1', 0, 0, 6,  1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Bust',       7,	'1', 0, 0, 7,  1, 1, 1, 1, 1");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Waist',      7,	'1', 0, 0, 8,  1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender,ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES   ('Hips',       7,	'1', 0, 0, 9,  1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Shoe Size',  7,	'1', 0, 0, 10, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Cup Size',   1,	'',  0, 0, 11, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView,ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Union',      1,	'',  0, 0, 12, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomShowGender,ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES  ('Experience', 4,	'',  0, 0, 13, 1, 1, 1, 1, 1)");
			$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . "  (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomShowGender, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin, ProfileCustomShowRegistration) VALUES ('Language',   1,	'',  0, 0, 14, 1, 1, 1, 1, 1)");
		
		}

		//Fix Custom Fields compatibility. 1.8 to 1.9.1
		$results = mysql_query("SELECT * FROM ".table_agency_customfields." ");
		while($fetchData = mysql_fetch_assoc($results)):
			if($fetchData["ProfileCustomType"] == 0){
				mysql_query("UPDATE ".table_agency_customfields." SET  ProfileCustomType = 1 WHERE ProfileCustomType = 0 ");
			}
			if($fetchData["ProfileCustomType"] == 3){
				mysql_query("UPDATE ".table_agency_customfields." SET  ProfileCustomOptions = '"."|".$fetchData["ProfileCustomOptions"]."' WHERE ProfileCustomID = ".$fetchData["ProfileCustomID"]."");
			}
		endwhile;
		
		// Fix Gender compatibility
		$results = mysql_query("SELECT ProfileID, ProfileGender FROM ".table_agency_profile." ");
		while($fetchData = mysql_fetch_assoc($results)):
		         $queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE  GenderTitle='".$fetchData["ProfileGender"]."'");
		         $fetchGender = mysql_fetch_assoc($queryGender);
			   $count = mysql_num_rows($queryGender);
			   if($count > 0){
			   	$wpdb->query("UPDATE ".table_agency_profile." SET ProfileGender='".$fetchGender["GenderID"]."' WHERE ProfileID='".$fetchData["ProfileID"]."'");
			   }
		endwhile;
		
		// Updating version number!
		update_option('rb_agency_version', "1.9.1");
	  
  }
  
  
if (get_option('rb_agency_version') == "1.9.1.1") {
  
		// Fix Gender compatibility
		$results = mysql_query("SELECT ProfileID, ProfileGender FROM ".table_agency_profile." ") or die("1".mysql_error());
		while($fetchData = mysql_fetch_assoc($results)):

		         $queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE  GenderTitle='".$fetchData["ProfileGender"]."'")  or die("2".mysql_error());
		         $fetchGender = mysql_fetch_assoc($queryGender);
			   $count = mysql_num_rows($queryGender);
			   if($count > 0){
			   	mysql_query("UPDATE ".table_agency_profile." SET ProfileGender='".(0 + (int)$fetchGender["GenderID"])."' WHERE ProfileID='".$fetchData["ProfileID"]."'")  or die("4".mysql_error());
			   }
			 
		endwhile;
		
   // Updating version number!
		update_option('rb_agency_version', "1.9.1.2");
		
	  
  }	
  if (get_option('rb_agency_version') == "1.9.1.2") {	
  
		$resultsProfile = mysql_query("SELECT * FROM ".table_agency_profile." ");
		
		while($f_Profile = mysql_fetch_assoc($resultsProfile)){

			$ProfileID = $f_Profile["ProfileID"];
			$ProfileGender = $f_Profile["ProfileGender"];
			
			$arr_columns = array(
			"ProfileLanguage" => "Language",
			"ProfileStatEthnicity" => "Ethnicity",
			"ProfileStatSkinColor" => "Skin Tone",
			"ProfileStatHairColor" => "Hair Color",
			"ProfileStatEyeColor" => "Eye Color",
			"ProfileStatHeight" => "Height",
			"ProfileStatWeight" => "Weight",
			"ProfileStatBust" => "Chest",
			"ProfileStatWaist" => "Bust",
			"ProfileStatHip" => "Waist",
			"ProfileStatShoe" => "Hips",
			"ProfileStatDress" => "Shoe Size",
			"ProfileUnion" => "Cup Size",
			"ProfileExperience" => "Experience");
			// old column   to  custom fields 
			
			foreach($arr_columns as $oldColumn => $migrate_data):		
				
				$query ="INSERT INTO " . table_agency_customfield_mux. "(ProfileCustomID,ProfileID,ProfileCustomValue)
				 	   SELECT  ProfileCustomID, '". $ProfileID."','".$f_Profile[$oldColumn]."'
				 	   FROM   " . table_agency_customfields . "  
			       	   WHERE ProfileCustomTitle ='". $migrate_data."'";
				 mysql_query($query) or die(mysql_error());
				 
		      endforeach;
		
		
		}// end while data fetch
  }
// Ensure directory is setup
if (!is_dir(rb_agency_UPLOADPATH)) {
	mkdir(rb_agency_UPLOADPATH, 0755);
	chmod(rb_agency_UPLOADPATH, 0777);
}
?>