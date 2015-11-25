<?
		// Sync if version #s are off
		if(get_option('rb_agency_version') <> RBAGENCY_VERSION) {
			update_option("rb_agency_version", RBAGENCY_VERSION);
		}

	// Check Page
		if( isset( $_GET['page'] ) ) { 
			$active_page = isset( $_GET['page'] ) ? $_GET['page'] : 'display_options';
		} // end if  


	/* 
	 * Check Version
	 */

		// Find Remote Version:
		$rb_remote_version = get_transient( 'rb_remote_version' );
		if (false === $rb_remote_version) {
			// Transient expired, refresh the data
			$response = rb_get_remote_version();
			set_transient( 'rb_remote_version', $response, DAY_IN_SECONDS );
		}

		// Reconcile Versions
		if ($rb_remote_version <> get_option('rb_agency_version')
		 && $rb_remote_version > get_option('rb_agency_version')) { 

			// Build Preview URL
			$update_preview_url = add_query_arg(
				array(
					'plugin' => RBAGENCY_PLUGIN_NAME.'/'.RBAGENCY_PLUGIN_NAME.'.php',
					'tab' => 'plugin-information',
					'section' => 'changelog',
					'TB_iframe' => true,
					'width' => 600,
					'width' => 317,
				),
				admin_url('plugin-install.php')
			);

			// Build Update URL
			$update_url = wp_nonce_url(
				add_query_arg(
					array(
						'plugin' => RBAGENCY_PLUGIN_NAME.'/'.RBAGENCY_PLUGIN_NAME.'.php',
						'action' => 'upgrade-plugin',
					),
					admin_url('update.php')
				),
				$action.'_'.RBAGENCY_PLUGIN_NAME
			);
			// Build Update URL
			$update_url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => 'upgrade-plugin',
						'plugin' => RBAGENCY_PLUGIN_NAME.'/'.RBAGENCY_PLUGIN_NAME.'.php',
					),
					admin_url('update.php')
				),
				$action.'_'.RBAGENCY_PLUGIN_NAME
			);
			/*
			TODO: not sure why the nonce isnt working, going to just hard code to /wp-admin/plugins.php instead temporarily
			//$update_preview_url = esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . RBAGENCY_PLUGIN_NAME . '&TB_iframe=true&width=600&height=550' ) );
			*/
			$update_preview_url = '/wp-admin/plugins.php';
			// END TEMP OVERRIDE

			// Show Message ?>
			<div class="update-message">
				There is a new version of RB Agency available. 
				<a href="<?php echo $update_preview_url; ?>" class="thickbox" title="RB Agency">Update to Version <?php echo $rb_remote_version; ?></a>
			</div>
			<?php

		} // Reconcile Versions
	?>

	<div id="rb-overview-icon" class="icon32"></div>
	<h2>
		RB Agency
		<a href="http://rbplugin.com" class="add-new-h2">Base Version <?php echo get_option('rb_agency_version'); ?></a>
		<?php if (function_exists('rb_agency_interact_menu') && get_option('RBAGENCY_interact_VERSION')) { ?>
		<a href="http://rbplugin.com" class="add-new-h2">Interact Version <?php echo get_option("RBAGENCY_interact_VERSION"); ?></a>
		<?php }?>
		<?php if (function_exists('rb_agency_casting_menu') && get_option('RBAGENCY_casting_VERSION')) { ?>
		<a href="http://rbplugin.com" class="add-new-h2">Casting Cart Version <?php echo get_option('RBAGENCY_casting_VERSION'); ?></a>
		<?php }?>
	</h2>

	<h2 class="nav-tab-wrapper">
		<a href="?page=rb_agency_menu" class="nav-tab <?php echo $active_page == 'rb_agency_menu' ? 'nav-tab-active' : ''; ?>">Overview</a>  
		<a href="?page=rb_agency_profiles" class="nav-tab <?php echo $active_page == 'rb_agency_profiles' ? 'nav-tab-active' : ''; ?>">Manage Profiles</a>  
		<a href="?page=rb_agency_search" class="nav-tab <?php echo $active_page == 'rb_agency_search' ? 'nav-tab-active' : ''; ?>">Search Profiles</a>  
		<a href="?page=rb_agency_reports" class="nav-tab <?php echo $active_page == 'rb_agency_reports' ? 'nav-tab-active' : ''; ?>">Tools</a>  
		<a href="?page=rb_agency_settings" class="nav-tab <?php echo $active_page == 'rb_agency_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>  
	</h2>

	<?php
	if ($active_page == 'rb_agency_settings'){
	echo "  <p id=\"settings-menu\">\n";
	$purl = $_SERVER["REQUEST_URI"];
	for ($i=0; $i<=7;$i++){
		$button_state[$i] = $purl == '/wp-admin/admin.php?page=rb_agency_settings&ConfigID='.$i.'' ? 'button-primary' : 'button-secondary';
	}
	echo "      <a class=\"". $button_state[0] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=0\">". __("Overview", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[1] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=1\">". __("Configuration", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[2] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=2\">". __("Style", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[3] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=3\">". __("Gender", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[4] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=4\">". __("Profile Types", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[5] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=5\">". __("Custom Fields", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[6] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=6\">". __("Media Categories", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[7] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=7\">". __("Manage Locations", RBAGENCY_TEXTDOMAIN) . "</a> \n";
	echo "  </p>\n";
	}
	?>

	<div class="clear"></div>