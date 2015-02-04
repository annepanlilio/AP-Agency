<?php
class RBAgency_Diagnostic {

	/*
	 * Check if most recent URL
	 */

	public static function remote_check_upgrade($cache=true){

		$raw_response = get_transient("rbagency_update_info");
		if(!$cache)
			$raw_response = null;

		if(!$raw_response){
			//Getting version number
			$options = array('method' => 'POST', 'timeout' => 20);
			$options['headers'] = array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
				'User-Agent' => 'WordPress/' . get_bloginfo("version"),
				'Referer' => get_bloginfo("url")
			);
			$request_url = RBPLUGIN_URL . "/version-rb-agency/?" . self::get_remote_request_params();
			$raw_response = wp_remote_request($request_url, $options);
echo $raw_response;
			//caching responses.
			set_transient("rbagency_update_info", $raw_response, 86400); //caching for 24 hours
		}
/*
		if ( is_wp_error( $raw_response ) || 200 != $raw_response['response']['code']) {
			return array("is_valid_key" => "1", "version" => "", "url" => "");
		} else {

			list($is_valid_key, $version, $url, $exp_time) = array_pad(explode("||", $raw_response['body']), 4, false);
			$info = array("is_valid_key" => $is_valid_key, "version" => $version, "url" => $url);
			if($exp_time)
				$info["expiration_time"] = $exp_time;

			return $info;

		}
*/
	}


	/*
	 * Check Database Permissions
	 */

		private static function has_database_permission(&$error){
			global $wpdb;

			$wpdb->hide_errors();

			$has_permission = true;

			$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rb_test ( col1 int )";
			$wpdb->query($sql);
			$error = "Current database user does not have necessary permissions to create tables.";
			if(!empty($wpdb->last_error))
				$has_permission = false;

			$sql = "ALTER TABLE {$wpdb->prefix}rb_test ADD COLUMN a" . uniqid() ." int";
			$wpdb->query($sql);
			$error = "Current database user does not have necessary permissions to modify (ALTER) tables.";
			if(!empty($wpdb->last_error))
				$has_permission = false;

			$sql = "DROP TABLE {$wpdb->prefix}rb_test";
			$wpdb->query($sql);

			$wpdb->show_errors();

			// REsponse
			if($has_permission == true) {
				echo "<div class='success'>Database is writable.</div>";
			} else {
				echo "<div class='error'>Database is not writable.  Will not be able to create or save data.</div>";
			}
		}


	/*
	 * Check Folder Permissions
	 */

		//Tests if the upload folder is writable and displays an error message if not
		public static function check_upload_folder(){
			//check if upload folder is writable
			$folder = RBAGENCY_UPLOADPATH;
			if(empty($folder)) {
				return false; //echo "<div class='error'>Upload folder is not writable. Export and file upload features will not be functional.</div>";
			} else {
				return true; //echo "<div class='success'>Upload folder is writable.</div>";
			}
		}


	/*
	 * Stylesheet Exists
	 */

		public static function check_stylesheet_exists(){
			// Get the file
			$rb_agency_stylesheet = RBAGENCY_PLUGIN_DIR ."style/style.css";

			if (file_exists($rb_agency_stylesheet)) {
				return true; 
				// "<div id=\"message\" class=\"updated\"><p>Style last updated on " . date ("F d Y H:i:s.", filemtime($rb_agency_stylesheet)) .".</p></div>";
			} else {
				//$rb_agency_stylesheet = RBAGENCY_PLUGIN_DIR ."style/style_base.css";
				//echo "<div id=\"message\" class=\"error\"><p>Stylesheet not setup, please click <strong>Save Changes</strong> below to initialize.</p></div>";
				return false; 
			}
		}

	/*
	 * Stylesheet Writeable
	 */
/*
		// Update File
		if (isset($_POST["action"]) && $_POST["action"] == "saveChanges") {
			$rb_agency_stylesheet_file = fopen($rb_agency_stylesheet,"w") or exit("<p>Unable to open file to write!  Please edit via FTP</p>");
			$rb_agency_stylesheet_string = stripslashes($_POST["rb_agency_stylesheet_string"]);
			fwrite($rb_agency_stylesheet_file,$rb_agency_stylesheet_string,strlen($rb_agency_stylesheet_string));
		}
*/


}
?>