<?php
header("Content-type: text/xml; charset=utf-8");
// Initialize
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

		$xml .= "<rb_agency>\n";

	/*
	 * Base Plugin
	 */

		$xml .= "<core>\n";
		$xml .= "	<version>\n";
		if (RBAGENCY_CUSTOM == TRUE) {
		$xml .= "		Custom Build (". get_option("rb_agency_version") .")\n";
		} else {
		$xml .= "		". get_option("rb_agency_version") ."\n";
		}
		$xml .= "	</version>\n";
		$xml .= "</core>\n";

	/*
	 * Interact Plugin
	 */
		$xml .= "<interact>\n";
		$xml .= "	<version>";
		if(function_exists('rb_agency_interact_menu')){
		$xml .= "		". get_option("RBAGENCY_interact_VERSION") ."\n";
		} else {
		$xml .= "		Not Installed\n";
		}
		$xml .= "	</version>\n";
		$xml .= "</interact>\n";

	/*
	 * Casting Plugin
	 */
		$xml .= "<casting>\n";
		$xml .= "	<version>\n";
		if(function_exists('rb_agency_casting_menu')){
		$xml .= "		". get_option("RBAGENCY_casting_VERSION") ."\n";
		} else {
		$xml .= "		Not Installed\n";
		}
		$xml .= "	</version>\n";
		$xml .= "</casting>\n";


		$xml .= "</rb_agency>\n";

echo $xml;
?>