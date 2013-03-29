<?php
// *************************************************************************************************** //
// Get Category

// This is the Portfolio-Category page 

session_start();
header("Cache-control: private"); //IE 6 Fix

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
	$SearchMuxToName		=$_POST['SearchMuxToName'];
	$SearchMuxToEmail		=$_POST['SearchMuxToEmail'];
	
	$SearchMuxEmailToBcc		=$_POST['SearchMuxEmailToBcc'];
	$SearchMuxSubject		=$_POST['SearchMuxSubject'];
	$SearchMuxMessage		=$_POST['SearchMuxMessage'];
	$SearchMuxCustomValue	=$_POST['SearchMuxCustomValue'];

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
	$headers .= 'To: '. $SearchMuxToName .' <'. $SearchMuxToEmail .'>' . "\r\n";
	$headers = 'From: '. $rb_agency_option_agencyname .' <'. $rb_agency_option_agencyemail .'>' . "\r\n";

	if(!empty($SearchMuxEmailToBcc)){
		$headers = 'Bcc: '.$SearchMuxEmailToBcc.'' . "\r\n";
	}
 
  	$isSent = wp_mail($SearchMuxToEmail, $SearchMuxSubject, $SearchMuxMessage, $headers);
    if($isSent){
		wp_redirect(network_site_url()."/profile-casting-cart/?emailSent");  exit;	
	}	
}

get_header(); ?>

<script type="text/javascript">
	function printDiv(divName) {
     	var printContents = document.getElementById(divName).innerHTML;
     	var originalContents = document.body.innerHTML;

     	//document.body.innerHTML = printContents;
	   	//  window.print();
     	document.body.innerHTML = originalContents;	     
     	window.print();
	} 
</script>

<?php

	echo "<div id=\"primary\" class=\"eight column\">\n";
	echo "    <div id=\"content\" role=\"main\" >\n";
	
		echo "<div id=\"profile-category\">\n";
		echo "	<div class=\"clear line\"></div>\n";
		?>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
            <script type="text/javascript">
			$(document).ready(function(){
				$('#emailbox').toggle('slow'); 
				$("#sendemail").click(function(){
				    $('#emailbox').toggle('slow'); 
				});			 
			// $("#emailSent").fadeOut(4000);
			});
			</script>

		<div id="mail-print" class="twelve column">
            <?php
            echo "  <a href=\"javascript:;\" id=\"sendemail\">Send Email</a>  - ";
			echo " <a href=\"#\" onClick=\"window.open('". get_bloginfo("url") ."/profile-print/?action=quickPrint&cD=1&typePage=public','mywindow','width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." </a>\n";
		?>
		</div>
            <div id="emailbox" >
              <form method="post" enctype="multipart/form-data" action="">
	              <input type="hidden" name="action" value="cartEmail" />
	      
	              <div><label for="SearchMuxToName">Send to Name:</label><br/><input type="text" id="SearchMuxToName" name="SearchMuxToName" value="" /></div>
	              <div><label for="SearchMuxToEmail">Send to Email:</label><br/><input type="text" id="SearchMuxToEmail" name="SearchMuxToEmail" value="" /></div>
	              <div><label for="SearchMuxToBcc">Bcc:</label><br/><input type="text" id="SearchMuxToEmail" name="SearchMuxEmailToBcc" value="" /></div>
	              <div><label for="SearchMuxSubject">Subject:</label><br/><input type="text" id="SearchMuxSubject" name="SearchMuxSubject" value="Casting Cart" /></div>
	              <div><label for="SearchMuxMessage">Message:</label>(copy: [casting-link-placeholder])<br/>
	              <textarea id="SearchMuxMessage" name="SearchMuxMessage" style="width: 500px; height: 300px; ">[casting-link-placeholder]</textarea></div>
	              <p class="submit">
	                  <input type="hidden" name="action" value="sendEmailCastingCart" />
	                  <input type="submit" name="submit" value="Send Email" class="button-primary" /> 
	              </p>      
              </form>
            </div>
            <?php
		  if(isset($_GET["emailSent"])){ echo "<div id=\"emailSent\">Email Sent Succesfully! Go Back to <a href=\"". get_bloginfo("url")."/search-model/\">Search Model</a>.</div>";    }
		echo "			<div class=\"profile-category-results\" id=\"profile-category-results\">\n";
	
						if (function_exists('rb_agency_profilelist')) { 
						  $atts = array("type" => $DataTypeID,"profilecastingcart" => true);
						  rb_agency_profilelist($atts); 
						}
									
		echo "			</div>\n";
          
	/*	
		
		echo "			<div class=\"profile-category-filter\">\n";
		echo "			  <h3>". __("Filter Profiles", rb_agency_TEXTDOMAIN) .":</h3>\n";
	 
						  $profilesearch_layout = "condensed";
						  include("include-profile-search.php"); 	
	
		echo "			</div>\n";
		*/
		echo "</div>\n";
		echo "<div class=\"clear line\"></div>\n";
		echo "<input type=\"hidden\" name=\"castingcart\" value=\"1\"/>";
	echo "  </div>\n";
	echo "  </div>\n";
 ?>

<?php

get_sidebar(); 
      
get_footer(); 
?>