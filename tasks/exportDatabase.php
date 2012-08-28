<?php

// Tap into WordPress Database
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

global $wpdb;

// *************************************************************************************************** //
// Get Going

	$result = mysql_query("SHOW COLUMNS FROM rb_agency_profile");
	$i = 0;
	if (mysql_num_rows($result) > 0) {
	  while ($row = mysql_fetch_assoc($result)) {
		$csv_output .= $row['Field'].", ";
		$i++;
	  }
	}
	$csv_output .= "\n";
	
	$values = mysql_query("SELECT * FROM rb_agency_profile");
	while ($rowr = mysql_fetch_row($values)) {
		for ($j=0;$j<$i;$j++) {
		$csv_output .= $rowr[$j].", ";
		}
	  $csv_output .= "\n";
	}
	
	$filename = $file."_".date("Y-m-d_H-i",time());
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header( "Content-disposition: filename=".$filename.".csv");
	print $csv_output;
	exit;


?>