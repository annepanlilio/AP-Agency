<?php
class RBAgency_Diagnostic {

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
			// Exists?
			$rb_agency_options_arr = get_option('rb_agency_layout_options');

			// Set Default Values
			$rb_agency_value_stylesheet = $rb_agency_options_arr['rb_agency_value_stylesheet'];
			if (isset($rb_agency_value_stylesheet) && !empty($rb_agency_value_stylesheet)) {
				return true;
			} else {
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
