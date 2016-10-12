<?php
/*
Plugin Name: RB Agency
Text Domain: rb-agency
Plugin URI: http://rbplugin.com/wordpress/model-talent-agency-software/
Description: With this plugin you can easily manage models profiles and information.
Author: Rob Bertholf
Author URI: http://rob.bertholf.com/
Version: 2.4.8
*/
$RBAGENCY_VERSION = "2.4.9";
/* If you modify the plugin set the following to TRUE */
$RBAGENCY_CUSTOM = FALSE;
/*
License: CF Commercial-to-GPL License
Copyright 2007-2014 Rob Bertholf
This License is a legal agreement between You and the Developer for the use of the Software.
By installing, copying, or otherwise using the Software, You agree to be bound by the terms of this License.
If You do not agree to the terms of this License, do not install or use the Software.
See license.txt for full details.
*/

// *************************************************************************************************** //

	// Start Session
	if (!session_id()) session_start();

	// Access Data
	global $wpdb;


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
 * Declare Global Constants
 */

	// RB Agency Version
	define("RBAGENCY_VERSION", $RBAGENCY_VERSION); // e.g. 1.0

	// Are there custom modifications? If so, restrict upgrades.
	define('RBAGENCY_CUSTOM', $RBAGENCY_CUSTOM);

	// WordPress Version
	if (!defined('RBAGENCY_VERSION_WP_MIN') )
		define('RBAGENCY_VERSION_WP_MIN', '3.2');
		define('RBAGENCY_VERSION_SUPPORTED', version_compare(get_bloginfo("version"), RBAGENCY_VERSION_WP_MIN, '>=') );

	// Active Theme Path
	if (!defined('RBAGENCY_THEME_DIR')) // httdocs/domain/wp-content/themes/twentythirteen
		define('RBAGENCY_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

	/* Paths */
	if ( ! function_exists( 'is_ssl' ) ) {
	  function is_ssl() {
		if ( isset($_SERVER['HTTPS']) ) {
		  if ( 'on' == strtolower($_SERVER['HTTPS']) )
			 return true;
		  if ( '1' == $_SERVER['HTTPS'] )
			 return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		  return true;
		}
		return false;
	  }
	}

	if ( is_ssl() ) {
		$wp_content_url = str_replace( 'http://' , 'https://' , get_option( 'siteurl' ) );
	} else {
		$wp_content_url = get_option( 'siteurl' );
	}
	$wp_content_url .= '/wp-content';
	$wp_content_dir = ABSPATH . 'wp-content';
	$wp_plugin_url = $wp_content_url . '/plugins';
	$wp_plugin_dir = $wp_content_dir . '/plugins';
	$wpmu_plugin_url = $wp_content_url . '/mu-plugins';
	$wpmu_plugin_dir = $wp_content_dir . '/mu-plugins';


	// RB Agency Plugin Path
	if (!defined('RBAGENCY_PLUGIN_NAME')) // rb-agency
		define('RBAGENCY_PLUGIN_NAME', strtolower(trim(dirname(plugin_basename(__FILE__)), '/')));

	if (!defined('RBAGENCY_PLUGIN_DIR')) // httdocs/domain/wp-content/plugins/rb-agency/
		define('RBAGENCY_PLUGIN_DIR', $wp_plugin_dir . '/' . RBAGENCY_PLUGIN_NAME . '/');

	if (!defined('RBAGENCY_PLUGIN_URL')) // http://localhost/wp-content/plugins/rb-agency/
		define('RBAGENCY_PLUGIN_URL', $wp_plugin_url . '/' . RBAGENCY_PLUGIN_NAME . '/');

	// Upload Directory
	$upload_dir = wp_upload_dir();

	if (!defined('RBAGENCY_UPLOADREL')) // /wp-content/uploads/profile-media/
		define("RBAGENCY_UPLOADREL", str_replace(get_bloginfo('url'), '', $upload_dir['baseurl']) ."/profile-media/" );

	if (!defined('RBAGENCY_UPLOADDIR')) // http://domain.com/wp-content/uploads/profile-media/
		define("RBAGENCY_UPLOADDIR", $upload_dir['baseurl'] ."/profile-media/" );

	if (!defined('RBAGENCY_UPLOADPATH')) // /httdocs/wordpress/wp-content/uploads/profile-media/
		define("RBAGENCY_UPLOADPATH", $upload_dir['basedir'] ."/profile-media/" );

	// Define Text Domain
	if (!defined('RBAGENCY_TEXTDOMAIN')) // rb-agency
		define('RBAGENCY_TEXTDOMAIN', RBAGENCY_PLUGIN_NAME ); //

	if(!defined( 'RBAGENCY_SLUG'))
		define( 'RBAGENCY_SLUG', plugin_basename(__FILE__) );

/*
 * License
 */

	// If you hardcode a RB Agency License Key here, it will automatically populate on activation.
	$RBAGENCY_LICENSE = "";
	define('RBAGENCY_LICENSE', $RBAGENCY_LICENSE);

	if(!defined( 'RBAGENCY_UPDATE_PATH'))
	define( 'RBAGENCY_UPDATE_PATH', 'http://rbplugin.com/update/'. RBAGENCY_PLUGIN_NAME );


// *************************************************************************************************** //


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
	if (!defined("table_agency_casting_job_customfields"))
		define("table_agency_casting_job_customfields", "{$wpdb->prefix}agency_casting_job_customfields");
	if (!defined("table_agency_casting_register_customfields"))
		define("table_agency_casting_register_customfields", "{$wpdb->prefix}agency_casting_register_customfields");

// *************************************************************************************************** //

/*
 * Initialize
 */

	// TODO: Sort Functions
	include_once(RBAGENCY_PLUGIN_DIR ."functions.php");
		define("RBAGENCY_PROFILEDIR", get_bloginfo('wpurl') . rb_agency_getActiveLanguage() ."/profile/" ); // http://domain.com/wordpress/de/profile/

	include_once( RBAGENCY_PLUGIN_DIR .'lib/RBAgency-Init.php');
		add_action( 'init', array('RBAgency_Init', 'init'), 0, 1 );

	include_once( RBAGENCY_PLUGIN_DIR .'lib/RBAgency-Update.php'); // Update Specific
		add_action( 'init', 'update_check' );
			function update_check() {
				new RBAgency_Update (RBAGENCY_VERSION, RBAGENCY_UPDATE_PATH, RBAGENCY_SLUG);
			}

	// Profile Class
	include_once(RBAGENCY_PLUGIN_DIR .'lib/RBAgency-Profile.php');

	// Common Functions
	include_once(RBAGENCY_PLUGIN_DIR ."app/common.class.php");

	include_once( RBAGENCY_PLUGIN_DIR .'lib/RBAgency-Admin.php');
		add_action( 'init', array('RBAgency_Admin', 'init'), 0, 1 );
		add_action('init',  array('RBAgency_Init', 'update_check')); // Check if version number changed and upgrade required
		add_action('init',  array('RBAgency_Init', 'upgrade_check')); // Check server if software is most current version

	// Widgets & Shortcodes
	include_once( RBAGENCY_PLUGIN_DIR .'lib/RBAgency-Extends.php');
		add_action( 'init', array('RBAgency_Extends', 'init'), 0, 1 );

	include_once( RBAGENCY_PLUGIN_DIR .'lib/RBAgency-App.php');
		add_action( 'init', array('RBAgency_App', 'init'), 0, 1 );

	include_once(RBAGENCY_PLUGIN_DIR.'ext/BFI_Thumb.php');


	include_once(RBAGENCY_PLUGIN_DIR.'ext/upload-multi.php');
	include_once(RBAGENCY_PLUGIN_DIR.'ext/ecard-create.php');

	require_once(RBAGENCY_PLUGIN_DIR."lib/RBAgency-Customfields.php");

// *************************************************************************************************** //


/*
 * Edit posts capabilities bypass for twentytwelve themes
 */
	function twentyeleven_option_page_capability( $capability ) {
		return 'edit_posts';
	}
	add_filter( 'option_page_capability_baw-settings-group', 'twentyeleven_option_page_capability' );


// *************************************************************************************************** //


/*
 * Internationalization
 */

	// Identify Folder for PO files
	load_plugin_textdomain( RBAGENCY_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . '/assets/translation/' );


// *************************************************************************************************** //

/*
 * Hooks
 */

	// Activate Plugin
	register_activation_hook(__FILE__, array('RBAgency_Init', 'activation'));

	// Deactivate Plugin
	register_deactivation_hook(__FILE__, array('RBAgency_Init', 'deactivation'));

	// Uninstall Plugin
	register_uninstall_hook(__FILE__, array('RBAgency_Init', 'uninstall'));



// *************************************************************************************************** //

/*
 * Diagnostics
 */

	// Check Permalinks
	add_action('admin_notices', array('RBAgency_Init', 'setup_check') );
	add_action('admin_notices', array('RBAgency_Init', 'permalinks_check') );


	// Set Dependencies
	// Requires 2.8 or more
	if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '3.2', '<=') ) { // if less than 2.8
		echo "<div class=\"error\" style=\"margin-top:30px;\"><p>This plugin requires WordPress version 3.2 or newer.</p></div>";
		return;
	}



?>
