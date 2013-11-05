<?php
// Tap into WordPress Database
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

global $wpdb;

// *************************************************************************************************** //
// Get Going
 if ( is_user_logged_in() ) {
	  if ( is_super_admin() ) {
	$result = mysql_query("SHOW COLUMNS FROM  ".$wpdb->prefix."agency_profile");
	$i = 0;
	if (mysql_num_rows($result) > 0) {
	  while ($row = mysql_fetch_assoc($result)) {
		$csv_output .= $row['Field'].", ";
		$i++;
	  }
	}
	$csv_output .= "\n";
	
	$values = mysql_query("SELECT * FROM ".$wpdb->prefix."agency_profile");
	while ($rowr = mysql_fetch_assoc($values)) {
		foreach ($rowr as $key=>$val) {
			if($key=="ProfileStatHeight"){
				$rawValue=$val;
				$feet=intval($rawValue/12);
				$inches=intval($rawValue%12);
				if($feet==0 && $inches==0)
					$val='';
				else
					$val=$feet."ft ".$inches."in";			
			}
		$csv_output .= $val.", ";
		}
		
	  $csv_output .= "\n";
	}
	
	$filename = $_SERVER['SERVER_NAME']."_".date("Y-m-d_H-i",time());	
	header("Content-type: application/vnd.ms-excel");	
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");	
	header( "Content-disposition: filename=".$filename.".csv");		
	print $csv_output;
	exit;
 }
 else
	{
	wp_die( __( 'You do not have sufficient permissions to manage plugins for this site.' ) );
	die;
	}
 }
 else
	{
	wp_redirect(wp_login_url( home_url()));
	die;
	}

?>