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
			$folder = rb_agency_UPLOADPATH;
			if(empty($folder)) {
				echo "<div class='error'>Upload folder is not writable. Export and file upload features will not be functional.</div>";
			} else {
				echo "<div class='success'>Upload folder is writable.</div>";
			}
		}

}
?>