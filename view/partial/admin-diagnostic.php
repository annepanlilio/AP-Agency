<?php


	require_once(rb_agency_BASEREL ."diagnostic.php");
/*

			//$remote_check_upgrade = RBAgency_Diagnostic::remote_check_upgrade();
			$params = sprintf("of=RBAgency&key=%s&v=%s&wp=%s&php=%s&mysql=%s", urlencode(rb_agency_LICENSE), urlencode(rb_agency_VERSION), urlencode(get_bloginfo("version")), urlencode(phpversion()), urlencode($wpdb->db_version()));
			$request_url = RBPLUGIN_URL . "/version-rb-agency/?" . $params;

			//Getting version number
			$options = array('method' => 'POST', 'timeout' => 20);
			$options['headers'] = array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
				'User-Agent' => 'WordPress/' . get_bloginfo("version"),
				'Referer' => get_bloginfo("url")
			);
			// Request Remote
			$raw_response = wp_remote_request($request_url, $options);
			// Get Body
			$response = $raw_response["body"];
			// Remove Line Breaks so we can pregmatch it
			$response = preg_replace( "/\r|\n/", "", $response );
			// Pull Versions
			preg_match_all('/<version>(.*?)</version>/', $response, $matches);

			echo "<pre>". $matches . "</pre>";

			//print_r(array_map('intval',$matches[1]));

			//



	// Check Database
		$database = RBAgency_Diagnostic::has_database_permission();
		echo $database;

	// Check Folder
		$folder = RBAgency_Diagnostic::check_upload_folder();
		echo $folder;
*/

?>

			  <table class="form-table">

				<tr valign="top">
				   <th scope="row"><label><?php _e("PHP Version", rb_agency_TEXTDOMAIN); ?></label></th>
					<td class="installation_item_cell">
						<strong><?php echo phpversion(); ?></strong>
					</td>
					<td>
						<?php
							if(version_compare(phpversion(), '5.0.0', '>')){
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/checked.png"/>
								<?php
							}
							else{
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/remove.png"/>
								<span class="installation_item_message"><?php _e("RB Agency requires PHP 5 or above.", rb_agency_TEXTDOMAIN); ?></span>
								<?php
							}
						?>
					</td>
				</tr>
				<tr valign="top">
				   <th scope="row"><label><?php _e("MySQL Version", rb_agency_TEXTDOMAIN); ?></label></th>
					<td class="installation_item_cell">
						<strong><?php echo $wpdb->db_version();?></strong>
					</td>
					<td>
						<?php
							if(version_compare($wpdb->db_version(), '5.0.0', '>')){
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/checked.png"/>
								<?php
							}
							else{
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/remove.png"/>
								<span class="installation_item_message"><?php _e("RB Agency requires MySQL 5 or above.", rb_agency_TEXTDOMAIN); ?></span>
								<?php
							}
						?>
					</td>
				</tr>
				<tr valign="top">
				   <th scope="row"><label><?php _e("WordPress Version", rb_agency_TEXTDOMAIN); ?></label></th>
					<td class="installation_item_cell">
						<strong><?php echo get_bloginfo("version"); ?></strong>
					</td>
					<td>
						<?php
							if(version_compare(get_bloginfo("version"), '3.0', '>')){
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/checked.png"/>
								<?php
							}
							else{
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/remove.png"/>
								<span class="installation_item_message"><?php printf(__("RB Agency requires WordPress v%s or greater. You must upgrade WordPress in order to use this version of Gravity Forms.", rb_agency_TEXTDOMAIN), rb_agency_VERSION_WP_MIN); ?></span>
								<?php
							}
						?>
					</td>
				</tr>
				 <tr valign="top">
				   <th scope="row"><label><?php _e("RB Agency Version", rb_agency_VERSION_WP_MIN); ?></label></th>
					<td class="installation_item_cell">
						<strong><?php echo rb_agency_VERSION ?></strong>
					</td>
					<td>
						<?php
							if(version_compare(rb_agency_VERSION, isset($version_info["version"])?$version_info["version"]:"", '>=')){
								?>
								<img src="<?php echo RBAgency_Common::get_base_url() ?>/style/checked.png"/>
								<?php
							}
							else{
								echo sprintf(__("New version %s available. Automatic upgrade available on the %splugins page%s", rb_agency_TEXTDOMAIN), $version_info["version"], '<a href="plugins.php">', '</a>');
							}
						?>
					</td>
				</tr>
			</table>
           
			<?php 


