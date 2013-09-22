<?php
header("Content-type: text/xml; charset=utf-8");

	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

// Initialize
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/*
 * Base Plugin
 */
	if (is_plugin_active('rb-agency/rb-agency.php')) {
		$xml .= "<rbagency_core>";
		$xml .= "	<version>";
		$xml .= "		". get_option("rb_agency_version");
		$xml .= "	</version>";
		$xml .= "</rbagency_core>";
	}

/*
 * Interact Plugin
 */
	if (is_plugin_active('rb-agency-interact/rb-agency-interact.php')) {
		$xml .= "<rbagency_interact>";
		$xml .= "	<version>";
		$xml .= "		". get_option("rb_agency_version");
		$xml .= "	</version>";
		$xml .= "</rbagency_interact>";
	} else {
		$xml .= "<rbagency_interact>";
		$xml .= "	<version>";
		$xml .= "		Not Installed";
		$xml .= "	</version>";
		$xml .= "</rbagency_interact>";
	}

/*
 * Casting Plugin
 */
	if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
		$xml .= "<rbagency_casting>";
		$xml .= "	<version>";
		$xml .= "		". get_option("rb_agency_version");
		$xml .= "	</version>";
		$xml .= "</rbagency_casting>";
	} else {
		$xml .= "<rbagency_casting>";
		$xml .= "	<version>";
		$xml .= "		Not Installed";
		$xml .= "	</version>";
		$xml .= "</rbagency_casting>";
	}
?>