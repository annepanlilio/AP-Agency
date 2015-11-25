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

	<?
	if(isset($_GET['confirm_version'])){
		$_oldVersion = get_update_option("rb_agency_version");
		update_option("rb_agency_version", RBAGENCY_VERSION);
		update_option("rb_agency_version_old", $_oldVersion);

		//upgrade SQL should be here..
	}
	?>
	<?php // TODO: Display new version available ?>
	<?php $rb_remote_version = rb_get_remote_version(); ?>
	<?php if($rb_remote_version <> get_option('rb_agency_version') && $rb_remote_version > get_option('rb_agency_version')):?>
	<div class="update-message">
	There is a new version of RB Agency available. 
	<a href="<?php echo admin_url("plugin-install.php?tab=plugin-information&amp;plugin=rb-agency&amp;section=changelog&amp;TB_iframe=true&amp;width=772&amp;height=317"); ?>" class="thickbox" title="RB Agency">
	View version <?php echo $rb_remote_version;?> details
	</a> or 
	<?php 
	$action = 'upgrade-plugin';
	$slug = 'rb-agency';
	$update_url = wp_nonce_url(
		add_query_arg(
			rray(
				'action' => $action,
				'plugin' => $slug
			),
			admin_url( 'update.php' )
		),
		$action.'_'.$slug
	);
	?>
	
	<a href="<?php echo $update_url;?>">update now</a>. Otherwise, <a href="?page=rb_agency_menu&confirm_version">confirmed <?php echo RBAGENCY_VERSION;?></a> im running the latest version.
	</div>
	<?php endif; ?>
	<?php 
		// Just a backup check if version numbers are off
		if(get_option('rb_agency_version') <> RBAGENCY_VERSION) {
		echo "Upgrade Needed";
		}

		if( isset( $_GET['page'] ) ) { 
			$active_page = isset( $_GET['page'] ) ? $_GET['page'] : 'display_options';
		}// end if  
	?>

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