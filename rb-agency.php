<?php
/*
Plugin Name: RB Agency
Text Domain: rb-agency
Plugin URI: http://rbplugin.com/wordpress/model-talent-agency-software/
Description: With this plugin you can easily manage models profiles and information.
Author: Rob Bertholf
Author URI: http://rob.bertholf.com/
Version: 2.0.7.2
*/
$rb_agency_VERSION = "2.0.7.2";
/*
License: CF Commercial-to-GPL License
Copyright 2007-2014 Rob Bertholf
This License is a legal agreement between You and the Developer for the use of the Software. 
By installing, copying, or otherwise using the Software, You agree to be bound by the terms of this License. 
If You do not agree to the terms of this License, do not install or use the Software.
See license.txt for full details.
*/

// *************************************************************************************************** //

/*
 * Initialize
 */

	// Start Session
	if (!session_id()) session_start();


/*
 * Security
 */

	// Avoid direct calls to this file, because now WP core and framework has been used
	if ( !function_exists('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}

// *************************************************************************************************** //

/*
 * License
 */

	//If you hardcode a RB Agency License Key here, it will automatically populate on activation.
	$rb_agency_LICENSE = "";
	//-- OR ---//
	//You can also add the RB Agency license key to your wp-config.php file to automatically populate on activation
	//Add the code in the comment below to your wp-config.php to do so:
	//define('RB_AGENCY_LICENSE','YOUR_KEY_GOES_HERE');


// *************************************************************************************************** //

/*
 * Declare Global Constants
 */

	// Version
	define("rb_agency_VERSION", $rb_agency_VERSION); // e.g. 1.0
	// Paths
	define("rb_agency_BASENAME", plugin_basename(__FILE__) );  // rb-agency/rb-agency.php
	$rb_agency_WPURL = get_bloginfo("wpurl"); // http://domain.com/wordpress
	$rb_agency_WPUPLOADARRAY = wp_upload_dir(); // Array  $rb_agency_WPUPLOADARRAY['baseurl'] $rb_agency_WPUPLOADARRAY['basedir']
	define("rb_agency_BASEDIR", plugin_dir_url( __FILE__ ) );
	define("rb_agency_BASEREL", plugin_dir_path( __FILE__ ) );
	define("rb_agency_BASEPATH", rb_agency_BASEREL );
	define("rb_agency_UPLOADREL", str_replace(get_bloginfo('url'), '', $rb_agency_WPUPLOADARRAY['baseurl']) ."/profile-media/" );  // /wordpress/wp-content/uploads/profile-media/
	define("rb_agency_UPLOADDIR", $rb_agency_WPUPLOADARRAY['baseurl'] ."/profile-media/" );  // http://domain.com/wordpress/wp-content/uploads/profile-media/
	define("rb_agency_UPLOADPATH", $rb_agency_WPUPLOADARRAY['basedir'] ."/profile-media/" ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/profile-media/
	define("rb_agency_TEXTDOMAIN", basename(dirname( __FILE__ )) ); //   rb-agency

	// Check for WPMU installation
	if (!defined ('IS_WPMU')){
		global $wpmu_version;
		$is_wpmu = ((function_exists('is_multisite') and is_multisite()) or $wpmu_version) ? 1 : 0;
		define('IS_WPMU', $is_wpmu);
	}


/*
 * Set Dependencies
 */

	// Requires 2.8 or more
	if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '3.2', '<=') ) { // if less than 2.8
		echo "<div class=\"error\" style=\"margin-top:30px;\"><p>This plugin requires WordPress version 3.2 or newer.</p></div>";
		return;
	}


// *************************************************************************************************** //

// TODO: Clean Up:
	$pageURL = '';
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

// *************************************************************************************************** //

/*
 * Declare Global WordPress Database Access
 */

	global $wpdb;


/*
 * Set Table Names
 */

	// Profile Records
	if (!defined("table_agency_profile"))
		define("table_agency_profile", "{$wpdb->prefix}agency_profile");
	if (!defined("table_agency_profile_media"))
		define("table_agency_profile_media", "{$wpdb->prefix}agency_profile_media");
	// Configuration & Dropdown Values
	if (!defined("table_agency_data_gender"))
		define("table_agency_data_gender", "{$wpdb->prefix}agency_data_gender");
	if (!defined("table_agency_data_type"))
		define("table_agency_data_type", "{$wpdb->prefix}agency_data_type");
	if (!defined("table_agency_data_media"))
		define("table_agency_data_media", "{$wpdb->prefix}agency_data_media");
	if (!defined("table_agency_customfields"))
		define("table_agency_customfields", "{$wpdb->prefix}agency_customfields");
	if (!defined("table_agency_customfield_mux"))
		define("table_agency_customfield_mux", "{$wpdb->prefix}agency_customfield_mux");
	if (!defined("table_agency_customfields_types"))
		define("table_agency_customfields_types", "{$wpdb->prefix}agency_customfields_types");
	if (!defined("table_agency_data_country"))
		define("table_agency_data_country", "{$wpdb->prefix}agency_data_country");
	if (!defined("table_agency_data_state"))
		define("table_agency_data_state", "{$wpdb->prefix}agency_data_state");
	// Visitor & Agent Experience
	if (!defined("table_agency_searchsaved"))
		define("table_agency_searchsaved", "{$wpdb->prefix}agency_searchsaved");
	if (!defined("table_agency_searchsaved_mux"))
		define("table_agency_searchsaved_mux", "{$wpdb->prefix}agency_searchsaved_mux");
	if (!defined("table_agency_savedfavorite"))
		define("table_agency_savedfavorite", "{$wpdb->prefix}agency_savedfavorite");
	if (!defined("table_agency_castingcart"))
		define("table_agency_castingcart", "{$wpdb->prefix}agency_castingcart");


// *************************************************************************************************** //


/*
 * Call Function and Language
 */

	// Functions
	require_once(rb_agency_BASEREL ."functions.php");

	// Common Functions
	require_once(rb_agency_BASEREL ."app/common.class.php");

	// Widgets & Shortcodes
	require_once(rb_agency_BASEREL ."extend.php");

	// Now Call the Lanuage
	define("rb_agency_PROFILEDIR", get_bloginfo('wpurl') . rb_agency_getActiveLanguage() ."/profile/" ); // http://domain.com/wordpress/de/profile/


// *************************************************************************************************** //


/*
 * Initialize
 */
	// Call the initialization function
	add_action('init',  array('RBAgency', 'init'));
	// Check if version number changed and upgrade required
	add_action('init',  array('RBAgency', 'check_update_needed'));
	// Check server if software is most current version
	add_action('init',  array('RBAgency', 'check_upgrade_available'));


// *************************************************************************************************** //

/*
 * RB Agency Core Class
 */

class RBAgency {

	/*
	 * Initialization
	 */

		public static function init(){

			/*
			 * Internationalization
			 */

				// Identify Folder for PO files
				load_plugin_textdomain( rb_agency_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . '/translation/' );


			/*
			 * Admin Related
			 */
			if ( is_admin() ){

				// Load Menus
				add_action('admin_menu', array('RBAgency', 'menu_admin'));

				// Load Shortcode Generator
				add_action('admin_menu', array('RBAgency', 'shortcode_display_generator'));

				// Add Link to Settings Page
				add_action('admin_menu', array('RBAgency', 'menu_addlinkto_settings'));

				// Add Link to Plugin Page
				add_action('plugin_action_links', array('RBAgency', 'menu_addlinkto_plugin'),10,2);

				// Add Notification to Plugins Page
				add_action('after_plugin_row_rb-agency/rb-agency.php', array('RBAgency', 'plugin_row'));

				// Register Settings
				add_action('admin_init', array('RBAgency', 'do_register_settings') );

			}

		}


	/*
	 * Plugin Activation
	 * Run when the plugin is installed.
	 */

		public static function activation(){

			// Required for all WordPress database manipulations
			global $wpdb;
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			/*
			 * Check Permissions
			 */
				// Does the user have permission to activate the plugin
				if ( !current_user_can('activate_plugins') )
					return;
				// Check Admin Referer
				$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
				check_admin_referer( "activate-plugin_{$plugin}" );


			/*
			 * Setup Required Directories
			 */

				// Create Upload Directory
				if (!is_dir(rb_agency_UPLOADPATH)) {
					@mkdir(rb_agency_UPLOADPATH, 0755);
					@chmod(rb_agency_UPLOADPATH, 0777);
				}


			/*
			 * Initialize Options
			 */

				// Update the options in the database
				if(!get_option("rb_agency_options")) {

					// Set Default Options
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
						"rb_agency_option_layoutprofilelist" => "0",
						"rb_agency_option_profilelist_sortby" => "1",
						"rb_agency_option_advertise" => "1",
						"rb_agency_option_privacy" => "0",
						"rb_agency_option_agencyimagemaxwidth" => "1000",
						"rb_agency_option_agencyimagemaxheight" => "800",
						"rb_agency_option_profilenaming" => "0",
						"rb_agency_option_showcontactpage" =>"1",
						"rb_agency_option_profiledeletion" => "2",
						"rb_agency_option_customfield_profilepage" => "1",
						"rb_agency_option_customfield_searchpage" => "1",
						"rb_agency_option_customfield_loggedin_all" => "1",
						"rb_agency_option_customfield_loggedin_admin" => "1"
						);
					// Add Options
					//add_option("rb_agency_options",$rb_agency_options_arr);
					update_option("rb_agency_options",$rb_agency_options_arr);
				}

			/*
			 * License Key
			 */

				// Set license key via the RB_AGENCY_LICENSE constant or the $rb_agency_LICENSE variable
				global $rb_agency_LICENSE;
				// Set Constant
				$license_key = defined("RB_AGENCY_LICENSE") && empty($rb_agency_LICENSE) ? RB_AGENCY_LICENSE : $rb_agency_LICENSE;
				if(!empty($license_key)) {
					// Store Key
					update_option("rb_agency_license", md5($license_key));
				}


			/*
			 * Install Schema
			 */

				// Profile
				$sql = "CREATE TABLE IF NOT EXISTS " . table_agency_profile . " (
					ProfileID BIGINT(20) NOT NULL AUTO_INCREMENT,
					ProfileUserLinked BIGINT(20) NOT NULL DEFAULT '0',
					ProfileGallery VARCHAR(255),
					ProfileContactDisplay VARCHAR(255),
					ProfileContactNameFirst VARCHAR(255),
					ProfileContactNameLast VARCHAR(255),
					ProfileGender VARCHAR(255),
					ProfileDateBirth DATE,
					ProfileLocationStreet VARCHAR(255),
					ProfileLocationCity VARCHAR(255),
					ProfileLocationState VARCHAR(255),
					ProfileLocationZip VARCHAR(255),
					ProfileLocationCountry VARCHAR(255),
					ProfileContactEmail VARCHAR(255),
					ProfileContactWebsite VARCHAR(255),
					ProfileContactPhoneHome VARCHAR(255),
					ProfileContactPhoneCell VARCHAR(255),
					ProfileContactPhoneWork VARCHAR(255),
					ProfileContactLinkTwitter VARCHAR(255),
					ProfileContactLinkFacebook VARCHAR(255),
					ProfileContactLinkYoutube VARCHAR(255),
					ProfileContactLinkFlickr VARCHAR(255),
					ProfileDateCreated TIMESTAMP DEFAULT NOW(),
					ProfileDateUpdated TIMESTAMP,
					ProfileDateViewLast TIMESTAMP,
					ProfileType VARCHAR(255),
					ProfileIsActive INT(10) NOT NULL DEFAULT '0',
					ProfileIsFeatured INT(10) NOT NULL DEFAULT '0',
					ProfileIsPromoted INT(10) NOT NULL DEFAULT '0',
					ProfileStatHits INT(10) NOT NULL DEFAULT '0',
					PRIMARY KEY (ProfileID)
					);";
				dbDelta($sql);

				// Profile Media
				$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_profile_media." (
					ProfileID INT(10) NOT NULL DEFAULT '0',
					ProfileMediaID INT(10) NOT NULL AUTO_INCREMENT,
					ProfileMediaType VARCHAR(255),
					ProfileMediaTitle VARCHAR(255),
					ProfileVideoType VARCHAR(255),
					ProfileMediaText TEXT,
					ProfileMediaURL VARCHAR(255),
					ProfileMediaPrimary INT(10) NOT NULL DEFAULT '0',
					ProfileMediaFeatured INT(10) NOT NULL DEFAULT '0',
					ProfileMediaOrder VARCHAR(55),
					PRIMARY KEY (ProfileMediaID)
					);";
				dbDelta($sql);

				// Types of Media
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_data_media." (
					MediaCategoryID BIGINT(20) NOT NULL AUTO_INCREMENT,
					MediaCategoryTitle VARCHAR(255),
					MediaCategoryGender VARCHAR(255),
					MediaCategoryOrder VARCHAR(255),
					PRIMARY KEY (MediaCategoryID)
					);";
				dbDelta($sql);

				// Data Classification
				$sql = "CREATE TABLE IF NOT EXISTS ".table_agency_data_type." (
					DataTypeID INT(10) NOT NULL AUTO_INCREMENT,
					DataTypeTitle VARCHAR(255),
					DataTypeTag VARCHAR(50),
					PRIMARY KEY (DataTypeID)
					);";
				dbDelta($sql);

				// Populate Initial Values
					$data_type_exists = $wpdb->get_var( $wpdb->prepare( "SELECT DataTypeID FROM " . table_agency_data_type . " WHERE DataTypeTitle = %s", 'Model' ) );
					if ( !$data_type_exists ) {
						$insert = $wpdb->query("INSERT INTO " . table_agency_data_type . " (DataTypeID, DataTypeTitle) VALUES ('','Model')");
					}
					$data_type_exists = $wpdb->get_var( $wpdb->prepare( "SELECT DataTypeID FROM " . table_agency_data_type . " WHERE DataTypeTitle = %s", 'Talent' ) );
					if ( !$data_type_exists ) {
						$insert = $wpdb->query("INSERT INTO " . table_agency_data_type . " (DataTypeID, DataTypeTitle) VALUES ('','Talent')");
					}

				// Setup > Taxonomy: Gender
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_data_gender ." (
					GenderID INT(10) NOT NULL AUTO_INCREMENT,
					GenderTitle VARCHAR(255),
					PRIMARY KEY (GenderID)
					);";
				dbDelta($sql);

				// Populate Initial Values
					$data_gender_exists = $wpdb->get_var( $wpdb->prepare( "SELECT GenderID FROM " . table_agency_data_gender . " WHERE GenderTitle = %s", 'Male' ) );
					if ( !$data_gender_exists ) {
						$insert = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Male')");
					}
					$data_gender_exists = $wpdb->get_var( $wpdb->prepare( "SELECT GenderID FROM " . table_agency_data_gender . " WHERE GenderTitle = %s", 'Female' ) );
					if ( !$data_gender_exists ) {
						$insert = $wpdb->query("INSERT INTO " . table_agency_data_gender . " (GenderID, GenderTitle) VALUES ('','Female')");
					}

				// Profile Custom Field Types
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfields." (
					ProfileCustomID BIGINT(20) NOT NULL AUTO_INCREMENT,
					ProfileCustomTitle VARCHAR(255),
					ProfileCustomType INT(10) NOT NULL DEFAULT '0',
					ProfileCustomOptions TEXT,
					ProfileCustomView INT(10) NOT NULL DEFAULT '0',
					ProfileCustomOrder INT(10) NOT NULL DEFAULT '0',
					ProfileCustomShowGender INT(10) NOT NULL DEFAULT '0',
					ProfileCustomShowProfile INT(10) NOT NULL DEFAULT '1',
					ProfileCustomShowSearch INT(10) NOT NULL DEFAULT '1',
					ProfileCustomShowSearchSimple INT(10) NOT NULL DEFAULT '0',
					ProfileCustomShowLogged INT(10) NOT NULL DEFAULT '1',
					ProfileCustomShowRegistration INT(10) NOT NULL DEFAULT '1',
					ProfileCustomShowAdmin INT(10) NOT NULL DEFAULT '1',
					PRIMARY KEY (ProfileCustomID)
					);";
				dbDelta($sql);

				// Populate Initial Values
					$data_custom_exists = $wpdb->get_var( $wpdb->prepare( "SELECT ProfileCustomTitle FROM " . table_agency_customfields . " WHERE ProfileCustomTitle = %s", 'Ethnicity' ) );
					if ( !$data_custom_exists ) {
						// Assume the rest dont exist either
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (1, 'Ethnicity', 	3, '|African American|Caucasian|American Indian|East Indian|Eurasian|Filipino|Hispanic/Latino|Asian|Chinese|Japanese|Korean|Polynesian|Other|', 0, 1, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (2, 'Skin Tone', 	3, '|Fair|Medium|Dark|', 0, 2, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (3, 'Hair Color', 	3, '|Blonde|Black|Brown|Dark Brown|Light Brown|Red|Strawberry|Auburn|', 0, 3, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (4, 'Eye Color', 	3, '|Blue|Brown|Hazel|Green|Black|', 0, 4, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (5, 'Height', 		7, '3', 0, 5, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (6, 'Weight', 		7, '2', 0, 6, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (7, 'Shirt', 		1, '', 0, 8, 1, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (8, 'Waist', 		7, '1', 0, 9, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES (9, 'Hips', 		7, '1', 0, 10, 2, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(10, 'Shoe Size', 	7, '1', 0, 11, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(11, 'Suit', 		3, '|36S|37S|38S|39S|40S|41S|42S|43S|44S|45S|46S|36R|38R|40R|42R|44R|46R|48R|50R|52R|54R|40L|42L|44L|46L|48L|50L|52L|54L|', 0, 7, 1, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(12, 'Inseam', 		7, '1', 0, 10, 1, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(13, 'Dress', 		3, '|2|4|6|8|10|12|14|16|18|', 0, 8, 2, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(14, 'Bust', 		7, '|32A|32B|32C|32D|32DD|34A|34B|34C|34D|34DD|36C|36D|36DD|', 0, 7, 2, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(15, 'Union', 		3, '|SAG/AFTRA|SAG ELIG|NON-UNION|', 0, 20, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(16, 'Experience', 	4, '', 0, 13, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(17, 'Language', 	1, '', 0, 14, 0, 1, 1,0, 0, 1, 0)");
						$insert = $wpdb->query("INSERT INTO " . table_agency_customfields . " VALUES(18, 'Booking', 	4, '', 0, 15, 0, 1, 1,0, 0, 1, 0)");
					}

				// Setup > Custom Field Types > Mux Values
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfield_mux ." (
					ProfileCustomMuxID BIGINT(20) NOT NULL AUTO_INCREMENT,
					ProfileCustomID BIGINT(20) NOT NULL DEFAULT '0',
					ProfileID BIGINT(20) NOT NULL DEFAULT '0',
					ProfileCustomValue TEXT,
					PRIMARY KEY (ProfileCustomMuxID)
					);";
				dbDelta($sql);

				// Setup > Custom Field Types
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_customfields_types." (
					ProfileCustomTypesID BIGINT(20) NOT NULL AUTO_INCREMENT,
					ProfileCustomID BIGINT(20) NOT NULL,
					ProfileCustomTitle VARCHAR(255),
					ProfileCustomTypes VARCHAR(255),
					PRIMARY KEY (ProfileCustomTypesID)
					);";
				dbDelta($sql);

				// Populate Initial Values
                                $data_custom_exists = $wpdb->get_var( $wpdb->prepare( "SELECT ProfileCustomTitle FROM " . table_agency_customfields_types . " WHERE ProfileCustomTitle = %s", 'Ethnicity' ) );
				if ( !$data_custom_exists ) {
					// Assume the rest dont exist either
					$arr_ = array('Ethnicity', 'Skin Tone', 'Hair Color','Eye Color', 'Height', 'Weight', 'Shirt','Waist', 'Hips', 'Shoe Size', 'Suit','Inseam', 'Dress', 'Bust', 'Union', 'Experience','Language', 'Booking');
                                        $wpdb->query("DELETE FROM " . table_agency_customfields_types . " WHERE ProfileCustomTitle IN ('Ethnicity', 'Skin Tone', 'Hair Color','Eye Color', 'Height', 'Weight', 'Shirt','Waist', 'Hips', 'Shoe Size', 'Suit','Inseam', 'Dress', 'Bust', 'Union', 'Experience','Language', 'Booking')");
					$insert_arr = array();
					for($count = 1; $count <= count($arr_); $count++){
						$insert_arr[] = "(" .$count.",".$count.",'".$arr_[$count-1]."','Model,Talent')";
					}
					$insert = "INSERT INTO " . table_agency_customfields_types . " VALUES " . implode(",",$insert_arr);
					$insert = $wpdb->query($insert);
				}

				// Setup > Search Saved
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_searchsaved ." (
					SearchID BIGINT(20) NOT NULL AUTO_INCREMENT,
					SearchTitle VARCHAR(255),
					SearchType INT(10) NOT NULL DEFAULT '0',
					SearchProfileID TEXT,
					SearchOptions VARCHAR(255),
					SearchDate TIMESTAMP DEFAULT NOW(),
					PRIMARY KEY (SearchID)
					);";
				dbDelta($sql);

				// Setup > Custom Field Types > Mux Values
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_searchsaved_mux ." (
					SearchMuxID BIGINT(20) NOT NULL AUTO_INCREMENT,
					SearchID BIGINT(20) NOT NULL DEFAULT '0',
					SearchMuxHash VARCHAR(255),
					SearchMuxToName VARCHAR(255),
					SearchMuxToEmail VARCHAR(255),
					SearchMuxSubject VARCHAR(255),
					SearchMuxMessage TEXT,
					SearchMuxCustomValue VARCHAR(255),
					SearchMuxSent TIMESTAMP DEFAULT NOW(),
					PRIMARY KEY (SearchMuxID)
					);";
				dbDelta($sql);

				// Setup > Define Country
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_data_country ." (
					CountryID INT(10) NOT NULL AUTO_INCREMENT,
					CountryTitle VARCHAR(255),
					CountryCode VARCHAR(20),
					PRIMARY KEY (CountryID)
					);";
				dbDelta($sql);
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
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_data_state ." (
					StateID INT(20) NOT NULL AUTO_INCREMENT,
					CountryID INT(20) NOT NULL,
					StateTitle VARCHAR(255),
					StateCode VARCHAR(255),
					PRIMARY KEY (StateID)
					);";
				dbDelta($sql);
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

				// Setup > Save Favorite
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_savedfavorite." (
					SavedFavoriteID BIGINT(20) NOT NULL AUTO_INCREMENT,
					SavedFavoriteProfileID VARCHAR(255),
					SavedFavoriteTalentID VARCHAR(255),
					PRIMARY KEY (SavedFavoriteID)
					);";
				dbDelta($sql);

				// Setup > Add to Casting Cart
				$sql = "CREATE TABLE IF NOT EXISTS ". table_agency_castingcart." (
					CastingCartID BIGINT(20) NOT NULL AUTO_INCREMENT,
					CastingCartProfileID VARCHAR(255),
					CastingCartTalentID VARCHAR(255),
					PRIMARY KEY (CastingCartID)
					);";
				dbDelta($sql);



			/*
			 * Flush rewrite rules
			 */

				// Flush rewrite rules
				self::flush_rules();
		}


	/*
	 * Plugin Deactivation
	 * Cleanup when complete
	 */

		public static function deactivation(){
			/*
			// Does user have correct permissions?
			if ( ! current_user_can( 'activate_plugins' ) )
				return;

			// Is it coming from the right referer?
			$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			check_admin_referer( "deactivate-plugin_{$plugin}" );
			*/
			// TODO: Enhance

		}


	/*
	 * Plugin Uninstall
	 * Cleanup when complete
	 */

		public static function uninstall(){

			// Permission Granted... Remove
			global $wpdb; // Required for all WordPress database manipulations

			// Drop the tables
			$wpdb->query("DROP TABLE " . table_agency_profile);
			$wpdb->query("DROP TABLE " . table_agency_profile_media);
			$wpdb->query("DROP TABLE " . table_agency_data_gender);
			$wpdb->query("DROP TABLE " . table_agency_data_type);
			$wpdb->query("DROP TABLE " . table_agency_data_media);
			$wpdb->query("DROP TABLE " . table_agency_data_country);
			$wpdb->query("DROP TABLE " . table_agency_data_state);
			$wpdb->query("DROP TABLE " . table_agency_customfields);
			$wpdb->query("DROP TABLE " . table_agency_customfield_mux);
			$wpdb->query("DROP TABLE " . table_agency_customfields_types);
			$wpdb->query("DROP TABLE " . table_agency_searchsaved);
			$wpdb->query("DROP TABLE " . table_agency_searchsaved_mux);
			$wpdb->query("DROP TABLE " . table_agency_savedfavorite);
			$wpdb->query("DROP TABLE " . table_agency_castingcart);

			// Delete Saved Settings
			delete_option('rb_agency_options');

			/*
			// Deactivate Plugin
			$thepluginfile = "rb-agency/rb-agency.php";
			$current = get_settings('active_plugins');
			array_splice($current, array_search( $thepluginfile, $current), 1 );
			update_option('active_plugins', $current);
			do_action('deactivate_' . $thepluginfile );
			*/

			// Redirect back to Plugins
			echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", rb_agency_TEXTDOMAIN) ."</p><h1>". __("please uninstall on plugins page.", rb_agency_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", rb_agency_TEXTDOMAIN) ."</a></h1></div>";
			die;

		}


	/*
	 * Flush Rewrite Rules
	 * Remember to flush_rules() when adding rules
	 */

		public static function flush_rules(){

			global $wp_rewrite;
			$wp_rewrite->flush_rules();

		}


	/*
	 * Update Needed
	 * Is this an updated version of the software and needs database upgrade?
	 */

		public static function check_update_needed(){

			// Hold the version in a seprate option
			if(!get_option("rb_agency_version")) {
				update_option("rb_agency_version", rb_agency_VERSION);
			} else {
				// Version Exists, but is it out of date?
				if(get_option("rb_agency_version") <> rb_agency_VERSION){
					require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/upgrade.php");
				} else {
					// Namaste, version is number is correct
				}
			}
		}


	/*
	 * Upgrade Check
	 * Is there a newer version of the software available to upgrade to?
	 */

		public static function check_upgrade_available(){
			//if(!class_exists("RBAgency_Update"))
				//require_once("update.php");

			//return RBAgency_Update::check_version($update_plugins_option, true);
		}

	/*
	 * Administrative Menu
	 * Create the admin menu items
	 */

		//Create Admin Menu
		public static function menu_admin(){
				add_menu_page( __("Agency", rb_agency_TEXTDOMAIN), __("Agency", rb_agency_TEXTDOMAIN), 1,"rb_agency_menu","rb_agency_dashboard","div");
				add_submenu_page("rb_agency_menu", __("Overview", rb_agency_TEXTDOMAIN), __("Overview", rb_agency_TEXTDOMAIN), 1,"rb_agency_menu", array('RBAgency', 'menu_dashboard'));
				add_submenu_page("rb_agency_menu", __("Manage Profiles", rb_agency_TEXTDOMAIN), __("Manage Profiles", rb_agency_TEXTDOMAIN), 7,"rb_agency_profiles", array('RBAgency', 'menu_profiles'));

			// RB Agency Interact 
			//if(function_exists('rb_agency_interact_menu')){
				// Menu Approve
				add_submenu_page("rb_agency_menu", __("Approve Pending Profiles", rb_agency_TEXTDOMAIN), __("Approve Profiles", rb_agency_TEXTDOMAIN), 7,"rb_agency_interact_approvemembers", array('RBAgencyInteract', 'rb_agency_interact_approvemembers'));
			//}

				add_submenu_page("rb_agency_menu", __("Search &amp; Send Profiles", rb_agency_TEXTDOMAIN), __("Search Profiles", rb_agency_TEXTDOMAIN), 7,"rb_agency_search", array('RBAgency', 'menu_search'));
				add_submenu_page("rb_agency_menu", __("Saved Searches", rb_agency_TEXTDOMAIN), __("Saved Searches", rb_agency_TEXTDOMAIN), 7,"rb_agency_searchsaved", array('RBAgency', 'menu_searchsaved'));

			// RB Agency Casting
			if(function_exists('rb_agency_casting_menu')){
				// saved search for casting
				add_submenu_page("rb_agency_menu", __("Client Activity", rb_agency_casting_TEXTDOMAIN), __("Client Searches", rb_agency_casting_TEXTDOMAIN), 7,"rb_agency_casting_searchsaved", array('RBAgencyCasting', 'rb_agency_casting_searchsaved'));

				// job postings
				add_submenu_page("rb_agency_menu", __("Client Jobs", rb_agency_casting_TEXTDOMAIN), __("Client Jobs", rb_agency_casting_TEXTDOMAIN), 7,"rb_agency_casting_jobpostings", array('RBAgencyCasting', 'rb_agency_casting_jobpostings'));
			}
           


				add_submenu_page("rb_agency_menu", __("Tools &amp; Reports", rb_agency_TEXTDOMAIN), __("Tools &amp; Reports", rb_agency_TEXTDOMAIN), 7,"rb_agency_reports", array('RBAgency', 'menu_reports'));
				add_submenu_page("rb_agency_menu", __("Edit Settings", rb_agency_TEXTDOMAIN), __("Settings", rb_agency_TEXTDOMAIN), 7,"rb_agency_settings", array('RBAgency', 'menu_settings'));

		}

		//Pages
		public static function menu_dashboard(){
			include_once('view/admin-overview.php');
		}
		public static function menu_profiles(){
			include_once('view/admin-profile.php');
		}
		public static function menu_search(){
			include_once('view/admin-search.php');
		}
		public static function menu_searchsaved(){
			include_once('view/admin-searchsaved.php');
		}
		public static function menu_reports(){
			include_once('view/admin-reports.php');
		}
		public static function menu_settings(){
			include_once('view/admin-settings.php');
		}


	/*
	 * Plugin Page
	 * Add links and information to the plugins page
	 */

		// Add Menu Item to Settings Dropdown
		public static function menu_addlinkto_settings() {
			// Check Permissions
			if ( !current_user_can('update_core') )
				return;
			$pagehook = add_options_page( __("RB Agency", rb_agency_TEXTDOMAIN), __("RB Agency", rb_agency_TEXTDOMAIN), 7, "rb_agency_settings", array('RBAgency', 'menu_settings'));
		}

		// Add Link to Settings Page
		public static function menu_addlinkto_plugin( $links, $file ) {
			static $this_plugin;

			if (!$this_plugin) {
				$this_plugin = plugin_basename(__FILE__);
			}

			// check to make sure we are on the correct plugin
			if ($file == $this_plugin) {

				// Create Link for Settings Page
				$settings_link = '<a href="' . admin_url("admin.php") . '?page=rb_agency_settings">Settings</a>';
				// Add link to List
				array_unshift($links, $settings_link);
			}

			return $links;

		}


	/*
	 * Shortcode Generator
	 * Add Custom Meta Box to Posts / Pages
	 */

		public static function shortcode_display_generator(){

			add_meta_box( 'rb_agency_sectionid', __( 'Insert Profile Grid', rb_agency_TEXTDOMAIN), array('RBAgency', 'shortcode_display_generator_form'), 'post', 'advanced' );
			add_meta_box( 'rb_agency_sectionid', __( 'Insert Profile Grid', rb_agency_TEXTDOMAIN), array('RBAgency', 'shortcode_display_generator_form'), 'page', 'advanced' );

		}

		public static function shortcode_display_generator_form(){
			// Use nonce for verification
			echo '<input type="hidden" name="rb_agency_noncename" id="rb_agency_noncename" value="'. wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
			echo "<div class=\"submitbox\" id=\"add_ticket_box\">";
			?><script type="text/javascript">
				function create_profile_grid(){

					var $rbagency = jQuery.noConflict();
					str='';

					gender=$rbagency('#rb_agency_gender').val();
					if(gender!=''&& gender!='')
					str+=' gender="'+gender+'"';

					age_start=$rbagency('#rb_agency_age_start').val();
					if(age_start!=''&& age_start!='')
					str+=' age_start="'+age_start+'"';

					age_stop=$rbagency('#rb_agency_age_stop').val();
					if(age_stop!=''&& age_stop!='')
					str+=' age_stop="'+age_stop+'"';

					type=$rbagency('#rb_agency_type').val();
					if(type!='')
					str+=' type="'+type+'"';

					send_to_editor('[profile_list'+str+']');return;
				}

				function create_profile_search(){
					send_to_editor('[profile_search]');return;
				}
			</script>
			<?php
			echo "<table>\n";
			echo "	<tr><td>Type:</td><td><select id=\"rb_agency_type\" name=\"rb_agency_type\">\n";
					global $wpdb;
					$profileDataTypes = mysql_query("SELECT * FROM ". table_agency_data_type ."");
					echo "<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) ."</option>\n";
					while ($dataType = mysql_fetch_array($profileDataTypes)) {
						if ($_SESSION['ProfileType']) {
							if ($dataType["DataTypeID"] ==  $ProfileType) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
						} else { $selectedvalue = ""; }
						echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ." ". __("Only", rb_agency_TEXTDOMAIN) ."</option>";
					}
					echo "</select></td></tr>\n";
			echo "	<tr><td>". __("Starting Age", rb_agency_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"rb_agency_age_start\" name=\"rb_agency_age_start\" value=\"18\" /></td></tr>\n";
			echo "	<tr><td>". __("Ending Age", rb_agency_TEXTDOMAIN) .":</td><td><input type=\"text\" id=\"rb_agency_age_stop\" name=\"rb_agency_age_stop\" value=\"99\" /></td></tr>\n";
			echo "	<tr><td>". __("Gender", rb_agency_TEXTDOMAIN) .":</td><td>";
			echo "<select id=\"rb_agency_gender\" name=\"rb_agency_gender\">";
			$query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
				
				echo "<option value=\"\">All Gender</option>";
				$queryShowGender = mysql_query($query);
				while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
					echo "<option value=\"".$dataShowGender["GenderID"]."\" >".$dataShowGender["GenderTitle"]."</option>";
				}
			echo "</select>";
			echo "</td></tr>\n";
			
			echo "</table>\n";
			echo "<p><input type=\"button\" onclick=\"create_profile_grid()\" value=\"". __("Insert Profile Grid", rb_agency_TEXTDOMAIN) ."\" /></p>\n";
			echo "<p><input type=\"button\" onclick=\"create_profile_search()\" value=\"". __("Insert Search Form", rb_agency_TEXTDOMAIN) ."\" /></p>\n";
			echo "</div>\n";
		}


	/*
	 * Register Settings
	 * Register Settings group
	 */

		public static function do_register_settings() {
			register_setting('rb-agency-settings-group', 'rb_agency_options'); //, 'rb_agency_options_validate'
			register_setting('rb-agency-dummy-settings-group', 'rb_agency_dummy_options'); //, setup dummy profile options
		}


	/*
	 * License
	 * Updates and License related
	 */

		// Get License Key
		public static function get_key(){
			return get_option("rb_agency_license");
		}
/*
		//Displays message on Plugin's page
		public static function plugin_row($plugin_name){

			$key = RBAgency::get_key();
			$version_info = GFCommon::get_version_info();

			if(!$version_info["is_valid_key"]){

				$plugin_name = "rb-agency/rb-agency.php";

				$new_version = version_compare(GFCommon::$version, $version_info["version"], '<') ? __('There is a new version of Gravity Forms available.', 'gravityforms') .' <a class="thickbox" title="Gravity Forms" href="plugin-install.php?tab=plugin-information&plugin=gravityforms&TB_iframe=true&width=640&height=808">'. sprintf(__('View version %s Details', 'gravityforms'), $version_info["version"]) . '</a>. ' : '';
				echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . __('<a href="admin.php?page=gf_settings">Register</a> your copy of Gravity Forms to receive access to automatic upgrades and support. Need a license key? <a href="http://www.gravityforms.com">Purchase one now</a>.', 'gravityforms') . '</div></td>';
			}
		}
*/





}


// *************************************************************************************************** //

/*
 * Plugin Actions
 */

	// Activate Plugin
	register_activation_hook(__FILE__, array('RBAgency', 'activation'));

	// Deactivate Plugin
	register_deactivation_hook(__FILE__, array('RBAgency', 'deactivation'));

	// Uninstall Plugin
	register_uninstall_hook(__FILE__, array('RBAgency', 'uninstall'));

// *************************************************************************************************** //



?>