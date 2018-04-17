<?php
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
    //removed the version update check to disable update notification
	?>

	<div id="rb-overview-icon" class="icon32"></div>
	<h2>
		RB Agency
	</h2>

	<h2 class="nav-tab-wrapper">
		<a href="?page=rb_agency_menu" class="nav-tab <?php echo $active_page == 'rb_agency_menu' ? 'nav-tab-active' : ''; ?>"><?php echo __('Overview',RBAGENCY_TEXTDOMAIN); ?></a>
		<a href="?page=rb_agency_profiles" class="nav-tab <?php echo $active_page == 'rb_agency_profiles' ? 'nav-tab-active' : ''; ?>"><?php echo __('Manage Profiles',RBAGENCY_TEXTDOMAIN); ?></a>
		<a href="?page=rb_agency_search" class="nav-tab <?php echo $active_page == 'rb_agency_search' ? 'nav-tab-active' : ''; ?>"><?php echo __('Search Profiles',RBAGENCY_TEXTDOMAIN); ?></a>
		<a href="?page=rb_agency_reports" class="nav-tab <?php echo $active_page == 'rb_agency_reports' ? 'nav-tab-active' : ''; ?>"><?php echo __('Tools',RBAGENCY_TEXTDOMAIN); ?></a>
		<a href="?page=rb_agency_settings" class="nav-tab <?php echo $active_page == 'rb_agency_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Settings',RBAGENCY_TEXTDOMAIN); ?></a>
	</h2>

	<?php
	if ($active_page == 'rb_agency_settings'){
	echo "  <p id=\"settings-menu\">\n";
	$purl = $_SERVER["REQUEST_URI"];
	for ($i=0; $i<=8;$i++){
		$button_state[$i] = $purl == '/wp-admin/admin.php?page=rb_agency_settings&ConfigID='.$i.'' ? 'button-primary' : 'button-secondary';
	}
	echo "      <a class=\"". $button_state[0] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=0\">". __("Overview", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[1] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=1\">". __("Configuration", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[2] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=2\">". __("Style", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[3] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=3\">". __("Gender", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[4] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=4\">". __("Profile Types", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[5] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=5\">". __("Custom Fields", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[6] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=6\">". __("Media Categories", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[7] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=8\">". __("Social Media", RBAGENCY_TEXTDOMAIN) . "</a> | \n";
	echo "      <a class=\"". $button_state[7] ."\" href=\"?page=". $_GET["page"] ."&ConfigID=7\">". __("Manage Locations", RBAGENCY_TEXTDOMAIN) . "</a> \n";
	echo "  </p>\n";
	}
	?>

	<div class="clear"></div>
