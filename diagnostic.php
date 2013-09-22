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

			return $has_permission;
		}

	/*
	 * Check Folder Permissions
	 */

		//Tests if the upload folder is writable and displays an error message if not
			public static function check_upload_folder(){
			//check if upload folder is writable
			$folder = rb_agency_UPLOADPATH;
			if(empty($folder))
				echo "<div class='error'>Upload folder is not writable. Export and file upload features will not be functional.</div>";
		}

}
?>