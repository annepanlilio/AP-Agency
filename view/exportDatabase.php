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
	$result = $wpdb->get_results("SHOW COLUMNS FROM  ".$wpdb->prefix."agency_profile",ARRAY_A);
	$i = 0;
	$total_rows = $result->num_rows;
	if ($total_rows > 0) {
	 foreach($result as $row){
		$csv_output .= $row['Field'].", ";
		$i++;
	  }
	}
	$csv_output .= "\n";
	
	$values = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."agency_profile");
	foreach($values as $rowr) {
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
	}
 }
 else
	{
	wp_redirect(wp_login_url( home_url()));
	die;
	}

?>