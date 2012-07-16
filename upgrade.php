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
		"rb_agency_option_profilenaming" => "0"
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
				
	   	// Custom Order in Custom Fields
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomOrder INT(10) NOT NULL DEFAULT '0'");
	   	// Custom Visibility in Custom Fields
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowGender INT(10) NOT NULL DEFAULT '0'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowProfile INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowSearch INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowLogged INT(10) NOT NULL DEFAULT '1'");
		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." ADD ProfileCustomShowAdmin INT(10) NOT NULL DEFAULT '1'");
		
	   	// Populate Custom Fields
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Language', 0, 0, ,'', 0, 1, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Ethnicity', 3, 0, 'African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other', 0, 2, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Skin Tone', 3, 0, 'Fair|Medium|Dark', 0, 3, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Hair Color', 3, 0, 'Blonde|Black|Brown|Dark Brown|Light Brown|Red|Strawberry|Auburn', 0, 4, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Eye Color', 3, 0, 'Blue|Brown|Hazel|Green|Black', 0, 5, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Height', 0, 0, '', 0, 6, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Weight', 0, 0, '', 0, 7, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Bust', 0, 0, '', 0, 8, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Waist', 0, 0, '', 0, 9, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Hip', 0, 0, '', 0, 10, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Shoe', 0, 0, '', 0, 11, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Dress', 0, 0, '', 0, 12, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Union', 0, 0, '', 0, 13, 1, 1, 1, 1)");
		$results = $wpdb->query("INSERT INTO " . table_agency_customfields . " (ProfileCustomTitle, ProfileCustomType, ProfileCustomOptions, ProfileCustomView, ProfileCustomOrder, ProfileCustomShowProfile, ProfileCustomShowSearch, ProfileCustomShowLogged, ProfileCustomShowAdmin) VALUES ('Experience', 4, 0, '', 0, 14, 1, 1, 1, 1)");
		
		// Updating version number!
		update_option('rb_agency_version', "1.9.1");
	  
  }
// Ensure directory is setup
if (!is_dir(rb_agency_UPLOADPATH)) {
	mkdir(rb_agency_UPLOADPATH, 0755);
	chmod(rb_agency_UPLOADPATH, 0777);
}
?>