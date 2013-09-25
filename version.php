<?php
header("Content-type: text/xml; charset=utf-8");

	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

		$xml .= "<rb_agency>\n";

// Initialize
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/*
 * Base Plugin
 */
		$xml .= "<core>\n";
		$xml .= "	<version>\n";
		$xml .= "		". get_option("rb_agency_version") ."\n";
		$xml .= "	</version>\n";
		$xml .= "</core>\n";

/*
 * Interact Plugin
 */
	if (is_plugin_active('rb-agency-interact/rb-agency-interact.php')) {
		$xml .= "<interact>\n";
		$xml .= "	<version>";
		$xml .= "		". get_option("rb_agency_interact_version") ."\n";
		$xml .= "	</version>\n";
		$xml .= "</interact>\n";
	} else {
		$xml .= "<interact>\n";
		$xml .= "	<version>\n";
		$xml .= "		Not Installed\n";
		$xml .= "	</version>\n";
		$xml .= "</interact>\n";
	}

/*
 * Casting Plugin
 */
	if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
		$xml .= "<casting>\n";
		$xml .= "	<version>\n";
		$xml .= "		". get_option("rb_agency_casting_version") ."\n";
		$xml .= "	</version>\n";
		$xml .= "</casting>\n";
	} else {
		$xml .= "<casting>\n";
		$xml .= "	<version>\n";
		$xml .= "		Not Installed\n";
		$xml .= "	</version>\n";
		$xml .= "</casting>\n";
	}
		$xml .= "</rb_agency>\n";

echo $xml;
?>