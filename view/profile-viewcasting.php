<?php
// *************************************************************************************************** //
// Get Category

// This is the Portfolio-Category page 

session_start();
header("Cache-control: private"); //IE 6 Fix

// Profile Class
include(rb_agency_BASEREL ."app/profile.class.php");

// Get Profile
//$ProfileType = get_query_var('target'); 

if (isset($ProfileType) && !empty($ProfileType)){
	$DataTypeID = 0;
	$DataTypeTitle = "";
	$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeTag = '". $ProfileType ."'";

	$results = mysql_query($query);
	while ($data = mysql_fetch_array($results)) {
		$DataTypeID = $data['DataTypeID'];
		$DataTypeTitle = $data['DataTypeTitle'];
		$filter .= " AND profile.ProfileType=". $DataTypeID ."";
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "sendEmailCastingCart"){

	$SearchID				= time(U);
	$SearchMuxHash			= rb_agency_random(8);
	$SearchMuxToName		= $_POST['SearchMuxToName'];
	$SearchMuxToEmail		= get_option('admin_email');
	$SearchMuxEmailToBcc	= $_POST['SearchMuxEmailToBcc'];
	$SearchMuxSubject		= get_bloginfo('name') . " - ".$_POST['SearchMuxSubject'];
	$SearchMuxMessage		= $_POST['SearchMuxMessage'];
	$SearchMuxCustomValue	= $_POST['SearchMuxCustomValue'];

	// Get Casting Cart
	$query = "SELECT  profile.*, profile.ProfileGallery, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID , cart.CastingCartTalentID, cart.CastingCartTalentID, (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile INNER JOIN  ".table_agency_castingcart."  cart WHERE  cart.CastingCartTalentID = profile.ProfileID   AND cart.CastingCartProfileID = '".rb_agency_get_current_userid()."' AND ProfileIsActive = 1 ORDER BY profile.ProfileContactNameFirst";
	$result = mysql_query($query);
	$pID = "";
	$profileid_arr = array();

	while($fetch = mysql_fetch_assoc($result)){
		$profileid_arr[] = $fetch["pID"];
	}

	$casting = implode(",",$profileid_arr);
	$wpdb->query("INSERT INTO " . table_agency_searchsaved." (SearchProfileID) VALUES('".$casting."')") or die(mysql_error());

	$lastid = $wpdb->insert_id;

	// Create Record
	$insert = "INSERT INTO " . table_agency_searchsaved_mux ." 
			(
			SearchID,
			SearchMuxHash,
			SearchMuxToName,
			SearchMuxToEmail,
			SearchMuxSubject,
			SearchMuxMessage,
			SearchMuxCustomValue
			)" .
			"VALUES
			(
			'" . $wpdb->escape($lastid) . "',
			'" . $wpdb->escape($SearchMuxHash) . "',
			'" . $wpdb->escape($SearchMuxToName) . "',
			'" . $wpdb->escape($SearchMuxToEmail) . "',
			'" . $wpdb->escape($SearchMuxSubject) . "',
			'" . $wpdb->escape($SearchMuxMessage) . "',
			'" . $wpdb->escape($SearchMuxCustomValue) ."'
			)";
	$results = $wpdb->query($insert);

	$SearchMuxMessage = str_replace("[casting-link-placeholder]",network_site_url()."/client-view/".$SearchMuxHash,$SearchMuxMessage);

	add_filter('wp_mail_content_type','rb_agency_set_content_type');
	function rb_agency_set_content_type($content_type){
		return 'text/html';
	}

	// Mail it
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// To send HTML mail, the Content-type header must be set
	$headers .= 'To: '. $rb_agency_option_agencyname .' <'. $SearchMuxToEmail .'>' . "\r\n";
	$headers = 'From: '. $SearchMuxToName .' <'. $_POST['SearchMuxToEmail'] .'>' . "\r\n";

	if(!empty($SearchMuxEmailToBcc)){
		$headers = 'Bcc: '.$SearchMuxEmailToBcc.'' . "\r\n";
	}

	$isSent = wp_mail($SearchMuxToEmail, $SearchMuxSubject, $SearchMuxMessage, $headers);
	if($isSent){
		wp_redirect(network_site_url()."/profile-casting-cart/?emailSent");  exit;	
	}
}

echo $rb_header = RBAgency_Common::rb_header(); ?>

<script type="text/javascript">
		jQuery(document).ready(function(){jQuery(".rblinks").css({display:"block"});});
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = originalContents;
		window.print();
	} 
</script>

<?php

echo "	<div id=\"primary\" class=\"".fullwidth_class()."  clearfix\">\n";
echo "  	<div id=\"content\" role=\"main\" >\n";
echo '			<header class="entry-header">';
echo '				<h1 class="entry-title">Casting Cart</h1>';
echo '			</header>';
echo '			<div class="entry-content">';
echo "				<div id=\"rbcasting-cart\">\n";
echo "					<div class=\"cb\"></div>\n"; ?>

						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('#emailbox').toggle('slow'); 
							jQuery("#sendemail").click(function(){
								jQuery('#emailbox').toggle('slow'); 
							});
						});
						</script>

						<div id="emailbox" >
							<form method="post" enctype="multipart/form-data" action="">
								<input type="hidden" name="action" value="cartEmail" />	      
								<div class="field"><label for="SearchMuxToName">Sender Name:</label><br/><input type="text" id="SearchMuxToName" name="SearchMuxToName" value="" required/></div>
								<div class="field"><label for="SearchMuxToEmail">Sender Email:</label><br/><input type="email" id="SearchMuxToEmail" name="SearchMuxToEmail" value="" required/></div>
								<div class="field"><label for="SearchMuxSubject">Subject:</label><br/><input type="text" id="SearchMuxSubject" name="SearchMuxSubject" value="Casting Cart" required></div>
								<div class="field"><label for="SearchMuxMessage">Message to Admin:</label><br/>
									<textarea id="SearchMuxMessage" name="SearchMuxMessage" style="width: 500px; height: 300px; ">[casting-link-placeholder]</textarea>
								</div>
								<p>(Note: The "[casting-link-placeholder]" will be the link to your casting cart page) </p>
								<div class="field submit">
									<input type="hidden" name="action" value="sendEmailCastingCart" />
									<input type="submit" name="submit" value="Send Email" class="button-primary" /> 
								</div>      
							</form>
						</div>
<?php 
echo "				</div>\n";
echo "			</div>\n";

echo "			<div class=\"cb\"></div>\n";
					
					if (function_exists('rb_agency_profilelist')) { 
						$atts = array("type" => $DataTypeID, "profilecasting" => true);
						$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($atts);
						$view_type = 2; // casting
						echo $search_results = RBAgency_Profile::search_results($search_sql_query, $view_type);						
					}

					if(isset($_GET["emailSent"])) {
echo "					<p id=\"emailSent\">Email Sent Succesfully! Go Back to <a href=\"". get_bloginfo("url")."/search/\">Search</a>.</p>";
					}


echo "			<div class=\"cb\"></div>\n";
echo "			<input type=\"hidden\" name=\"castingcart\" value=\"1\"/>";
echo "  	</div>\n";
echo "  </div>\n";
	  
echo $test = RBAgency_Common::rb_footer(); 
?>