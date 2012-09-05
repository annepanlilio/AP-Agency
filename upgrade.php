<?php

global $wpdb;



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


/*
// *************************************************************************************************** //

// Set default version #

	if (!isset($rb_agency_storedversion) && empty($rb_agency_storedversion)) { // Upgrade from ??

		update_option('rb_agency_version', "1.9");

	}*/

 // Safe Add column
   function rb_agency_addColumn($tbl = "",$column = "", $atts = ""){
	 global $wpdb;
	$debug = debug_backtrace();
      if($wpdb->get_var("SHOW COLUMNS FROM ".trim($tbl)." LIKE '%".trim($column)."%' ") != trim($column)){
	
		$result = mysql_query(" ALTER TABLE ".trim($tbl)." ADD ".trim($column)." ".$atts.";");// or die("rb_agency_addColumn()  - Adding column ".trim($column)." in line ".$debug["line"]." <br/> ".mysql_error());	
		 
		return $result;
	}
   }

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

		 rb_agency_addColumn(table_agency_customfields,"ProfileCustomView","INT(10) NOT NULL DEFAULT '0'");


		// Updating version number!

		update_option('rb_agency_version', "1.8.2");

	}

	elseif (get_option('rb_agency_version') == "1.8.2") { 

	

		$results = $wpdb->query("ALTER TABLE ". table_agency_customfields ." CHANGE ProfileCustomOptions ProfileCustomOptions TEXT");

		$results = $wpdb->query("ALTER TABLE ". table_agency_customfield_mux ." CHANGE ProfileCustomValue ProfileCustomValue TEXT");

	 
		rb_agency_addColumn( table_agency_profile,"ProfileIsFeatured","INT(10) NOT NULL DEFAULT '0'");
		
		rb_agency_addColumn( table_agency_profile,"ProfileIsPromoted","INT(10) NOT NULL DEFAULT '0'");

		// Setup > Save Favorited
		if ($wpdb->get_var("show tables like '".  table_agency_savedfavorite ."'") !=  table_agency_savedfavorite ) { 
			 $results = $wpdb->query("CREATE TABLE ". table_agency_savedfavorite." (
	
					SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
	
					SavedFavoriteProfileID VARCHAR(255),
	
				    SavedFavoriteTalentID VARCHAR(255),
	
					PRIMARY KEY (SavedFavoriteID)
	
					);");

		}

		// Updating version number!

		update_option('rb_agency_version', "1.8.5");

	}

	elseif (get_option('rb_agency_version') == "1.8.5") { 



		// Setup > Taxonomy: Gender
          if ($wpdb->get_var("show tables like '".  table_agency_data_gender ."'") !=  table_agency_data_gender ) { 
			$results = $wpdb->query("CREATE TABLE ". table_agency_data_gender ." (
	
				GenderID INT(10) NOT NULL AUTO_INCREMENT,
	
				GenderTitle VARCHAR(255),
	
				PRIMARY KEY (GenderID)
	
				);");
				
				
			$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Male')");
	
			$results = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Female')");
	    }
	    // Custom Order in Custom Fields


 		 rb_agency_addColumn(table_agency_customfields,"ProfileCustomOrder","INT(10) NOT NULL DEFAULT '0'");
		

		// Updating version number!

		update_option('rb_agency_version', "1.9");

	}



  elseif (get_option('rb_agency_version') == "1.9") {

	 

		// Setup > Save Favorited
		if ($wpdb->get_var("show tables like '".  table_agency_savedfavorite ."'") !=  table_agency_savedfavorite ) { 
				$results = $wpdb->query("CREATE TABLE ". table_agency_savedfavorite." (
		
					SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
		
					SavedFavoriteProfileID VARCHAR(255),
		
					SavedFavoriteTalentID VARCHAR(255),
		
					PRIMARY KEY (SavedFavoriteID)
		
					);");
		}
			
		if ($wpdb->get_var("show tables like '".  table_agency_castingcart ."'") != table_agency_castingcart ) { 
				$results = $wpdb->query("CREATE TABLE ". table_agency_castingcart." (
		
						CastingCartID BIGINT(20) NOT NULL AUTO_INCREMENT,
		
						CastingCartProfileID VARCHAR(255),
		
						CastingCartTalentID VARCHAR(255),
		
						PRIMARY KEY (CastingCartID)
		
						);");
		}
		
		if ($wpdb->get_var("show tables like '".  table_agency_mediacategory ."'") != table_agency_mediacategory) { 
				$sql13 = "CREATE TABLE ". table_agency_mediacategory." (
		
						MediaCategoryID BIGINT(20) NOT NULL AUTO_INCREMENT,
		
						MediaCategoryTitle VARCHAR(255),
		
						MediaCategoryGender VARCHAR(255),
		
						MediaCategoryOrder VARCHAR(255),
		
						PRIMARY KEY (MediaCategoryID)
		
						);";
		
					mysql_query($sql13);			
			}
		if ($wpdb->get_var("show tables like '". table_agency_customfields."'") != table_agency_customfields) { 
				$sql14 = "CREATE TABLE ". table_agency_customfields." (

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

				);";

		
					mysql_query($sql14);			
			}	
	   	// Custom Order in Custom Fields

		 rb_agency_addColumn(table_agency_customfields,"ProfileCustomOrder"," INT(10) NOT NULL DEFAULT '0'");



	   	// Custom Visibility in Custom Fields
		
		rb_agency_addColumn(table_agency_customfields,"ProfileCustomShowProfile"," INT(10) NOT NULL DEFAULT '1'");
		
		rb_agency_addColumn(table_agency_customfields,"ProfileCustomShowGender"," INT(10) NOT NULL DEFAULT '0'");
		
		rb_agency_addColumn(table_agency_customfields,"ProfileCustomShowSearch"," INT(10) NOT NULL DEFAULT '1'");
		
		rb_agency_addColumn(table_agency_customfields,"ProfileCustomShowLogged"," INT(10) NOT NULL DEFAULT '1'");
		
		rb_agency_addColumn(table_agency_customfields,"ProfileCustomShowRegistration"," INT(10) NOT NULL DEFAULT '1'");
		
		rb_agency_addColumn(table_agency_customfields,"ProfileCustomShowAdmin"," INT(10) NOT NULL DEFAULT '1'");
		
		
	 	// Populate Custom Fields

		$q1 = mysql_query("SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomTitle = 'Language'");

		$count = mysql_num_rows($q1);

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

		$q2 = mysql_query("SELECT * FROM ".table_agency_customfields." ");

		while($fetchData = mysql_fetch_assoc($q2)):

			if($fetchData["ProfileCustomType"] == 0){

				mysql_query("UPDATE ".table_agency_customfields." SET  ProfileCustomType = 1 WHERE ProfileCustomType = 0 ") or die(mysql_error());

			}

			if($fetchData["ProfileCustomType"] == 3){

				mysql_query("UPDATE ".table_agency_customfields." SET  ProfileCustomOptions = '"."|".$fetchData["ProfileCustomOptions"]."' WHERE ProfileCustomID = ".$fetchData["ProfileCustomID"]."") or die(mysql_error());

			}

		endwhile;

		

		// Fix Gender compatibility
		if ($wpdb->get_var("show tables like '".table_agency_profile."'") != table_agency_profile) { 
				$q3 = mysql_query("SELECT ProfileID, ProfileGender FROM ".table_agency_profile." ") or die(mysql_error());
		
				while($fetchData = mysql_fetch_assoc($q3)):
		
					   $queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE  GenderTitle='".$fetchData["ProfileGender"]."'")  or die(mysql_error());
		
					   $fetchGender = mysql_fetch_assoc($queryGender);
		
					   $count = mysql_num_rows($queryGender);
		
					   if($count > 0){
		
						$wpdb->query("UPDATE ".table_agency_profile." SET ProfileGender='".$fetchGender["GenderID"]."' WHERE ProfileID='".$fetchData["ProfileID"]."'");
		
					   }
		
				endwhile;
		}
			     
		

		// Updating version number!
           update_option('rb_agency_version', "1.9.1");
	    

  }

  

  

elseif (get_option('rb_agency_version')== "1.9.1") {

  
           
		// Fix Gender compatibility

		$q4 = mysql_query("SELECT ProfileID, ProfileGender FROM ".table_agency_profile." ") or die("1".mysql_error());

		while($fetchData = mysql_fetch_assoc($q4)):



		         $queryGender = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE  GenderTitle='".$fetchData["ProfileGender"]."'")  or die("2".mysql_error());

		         $fetchGender = mysql_fetch_assoc($queryGender);

			   $count = mysql_num_rows($queryGender);

			   if($count > 0){

			   	mysql_query("UPDATE ".table_agency_profile." SET ProfileGender='".(0 + (int)$fetchGender["GenderID"])."' WHERE ProfileID='".$fetchData["ProfileID"]."'")  or die("4".mysql_error());

			   }

			 

		endwhile;
         
		

   // Updating version number!

		update_option('rb_agency_version', "1.9.1.1");

		

	  

  }	

  elseif (get_option('rb_agency_version') == "1.9.1.1") {	

  

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

		

		 // Updating version number!

		update_option('rb_agency_version', "1.9.1.2");

  }  elseif (get_option('rb_agency_version') == "1.9.1.2") {	
        update_option('rb_agency_version', "1.9.1.3");
  }

// Ensure directory is setup
	
if (!is_dir(rb_agency_UPLOADPATH)) {

	mkdir(rb_agency_UPLOADPATH, 0755);

	chmod(rb_agency_UPLOADPATH, 0777);

}

?>