<?php

// Tap into WordPress
require('../../../../wp-blog-header.php');

	// Get Reseller API Key
	$rb_empower_options_arr = get_option('rb_empower_options');
	$rb_agency_option_agencyname = $rb_empower_options_arr['rb_agency_option_agencyname'];
	$rb_agency_option_agencyemail = $rb_empower_options_arr['rb_agency_option_agencyemail'];
	$rb_agency_option_agencylogo = $rb_empower_options_arr['rb_agency_option_agencylogo'];

/* Prepare Request ----------------------------------------------- */

	// Get Form Data
	$intakeLeadNameFirst = $_POST['intakeLeadNameFirst'];
	$intakeLeadNameLast = $_POST['intakeLeadNameLast'];
	$intakeLeadEmail = $_POST['intakeLeadEmail'];
	$intakeLeadMessage = $_POST['intakeLeadMessage'];
	
	
		$intakeLeadNameFirst = "Rob";
		$intakeLeadNameLast = "Bertholf";
		$intakeLeadEmail = "rob@bertholf.com";
		$intakeLeadMessage = "Test maven media message";
	// Initialize Email Message
	$intakeLeadMailTo = "rob1@bertholf.com";
	$intakeLeadMailFrom  = "notify@e.mp";
	$intakeLeadSubject = "Quote request from Maven Media Marketing";
	$intakeLeadDetails = "You have received a quote request from <a href=\"mailto:". $intakeLeadEmail ."\">". $intakeLeadNameFirst ." ". $intakeLeadNameLast ."</a>:<br /><blockquote>". $intakeLeadMessage ."</blockquote><br /> <a href=\"https://e.mp\">View in EMP</a>";
	// Add Headers
	$headers = 'From: E.MP Notifications <'. $intakeLeadMailFrom . ">\r\n" .
		'Reply-To: no-reply@e.mp' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	// To send HTML mail, the Content-type header must be set
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
	//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
	
	// Do it to it...
	mail($intakeLeadMailTo, $intakeLeadSubject, $intakeLeadDetails, $headers);

?>