<?php
// *************************************************************************************************** //
// Get Category

/*session_start();
header("Cache-control: private"); //IE 6 Fix*/
global $wpdb;

// Get Profile
$ProfileType = get_query_var('target'); 

if($ProfileType=="print"){  // print by custom
	//$ProfileType=$_GET['cname'];
    $division=$_GET['gd'];
	$ageStart=$_GET['ast'];
	$ageStop=$_GET['asp'];
	$type=$_GET['t'];
	 include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="pdf"){  // print by custom
    //$ProfileType=$_GET['cname'];
    $division=$_GET['gd'];
	$ageStart=$_GET['ast'];
	$ageStop=$_GET['asp'];
	$type=$_GET['t'];
	 include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}

if($ProfileType=="women-print"){  //request to print women division page
    $division="2";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}


if($ProfileType=="women-pdf"){  //request to PDF women division page
    $division="2";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="men-print"){  //request to print men division page
    $division="1";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="men-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="18";
	$ageStop="99";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="teen-boys-print"){  //request to print men division page
    $division="1";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="teen-boys-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="teen-girls-print"){  //request to print men division page
    $division="2";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="teen-girls-pdf"){  //request to PDF men division page
    $division="2";
	$ageStart="13";
	$ageStop="18";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="boys-print"){  //request to print men division page
    $division="1";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="boys-pdf"){  //request to PDF men division page
    $division="1";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	 include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}


if($ProfileType=="girls-print"){  //request to print men division page
    $division="2";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include(RBAGENCY_PLUGIN_DIR ."theme/printable-division.php"); 
	 die();
}

if($ProfileType=="girls-pdf"){  //request to PDF men division page
    $division="2";
	$ageStart="1";
	$ageStop="12";
	$type="1";
	include(RBAGENCY_PLUGIN_DIR ."theme/pdf-division.php"); 
	 die();
}



if (isset($ProfileType) && !empty($ProfileType)){
	$DataTypeID = 0;
	$DataTypeTitle = "";
	$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeTag = '". $ProfileType ."'";

	$results = $wpdb->get_results($query,ARRAY_A);
	foreach ($results as $data) {
		$DataTypeID = $data['DataTypeID'];
		$DataTypeTitle = $data['DataTypeTitle'];
		$filter .= " AND profile.ProfileType=". $DataTypeID ."";
	}
}

/*
 * send email
 */
if(isset($_POST["action"]) && $_POST["action"] == "sendEmailCastingCart"){
		
	
	$SearchID				= time(U);
	$SearchMuxHash			= RBAgency_Common::generate_random_string(8);
	$SearchMuxToName		=$_POST['SearchMuxToName'];
	$SearchMuxToEmail		=get_option('admin_email');
	
	if(isset($_POST['SearchMuxEmailToBcc']))
		$SearchMuxEmailToBcc	=$_POST['SearchMuxEmailToBcc'];
	$SearchMuxSubject		= get_bloginfo('name') . " - ".$_POST['SearchMuxSubject'];
	$SearchMuxMessage		=$_POST['SearchMuxMessage'];
	$SearchMuxCustomValue	=$_POST['SearchMuxCustomValue'];
	
	if($ProfileType == 'all'){
		$ProfileType = "";
	}
	
	// Get Category View
	$SearchMuxMessage = str_replace("[category-link-placeholder]",network_site_url()."/profile-category/".$ProfileType, $SearchMuxMessage);
	
	add_filter('wp_mail_content_type','set_content_type');
	function set_content_type($content_type){
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
		wp_redirect(network_site_url()."/profile-category/email_sent");  exit;	
	}	
} 


echo $rb_header = RBAgency_Common::rb_header(); 

	echo "<div id=\"primary\" class=\"".primary_class()." column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	// Can we show the pages?
	if((is_user_logged_in() && $rb_agency_options_arr['rb_agency_option_privacy']==2)||
	// Must be logged to view model list and profile information
	($rb_agency_options_arr['rb_agency_option_privacy']==1) ||
	// Model list public. Must be logged to view profile information
	($rb_agency_options_arr['rb_agency_option_privacy']==0) ||
	//admin users
	(is_user_logged_in() && current_user_can( 'edit_posts' )) ||
	// Model list public. Must be logged to view profile information
	($rb_agency_options_arr['rb_agency_option_privacy'] == 3 && is_user_logged_in() && is_client_profiletype()))
	{
	
		echo "<div id=\"profile-category\">\n";

		echo "	<h1 class=\"profile-category-title entry-title\">\n";
		echo "	". __("Directory", RBAGENCY_TEXTDOMAIN) ." ";
				if ($DataTypeTitle) { echo " > ". $DataTypeTitle; }
		echo "	</h1>\n";

		echo "	<div class=\"cb\"></div>\n";
		if(get_query_var('value') == 'email_sent'){
			echo '<p id="emailSent">Email Sent Succesfully!</p>';
		} else {
			if(isset($_POST["action"]) && $_POST["action"] == "sendEmailCastingCart"){
				echo '<p id="emailSent">Email was not sent.</p>';
			}
		}
		echo "	<div class=\"cb\"></div>\n";
		
		/*
		 * Loopp to category list
		 */ 
		$queryList = "SELECT dt.DataTypeID, dt.DataTypeTitle, dt.DataTypeTag, 
				      COUNT(profile.ProfileID) AS CategoryCount 
					  FROM ".table_agency_data_type." dt,".table_agency_profile." profile 
					  WHERE  FIND_IN_SET(dt.DataTypeID, profile.ProfileType) and profile.ProfileIsActive = 1 
					  GROUP BY dt.DataTypeID ORDER BY dt.DataTypeTitle ASC";
		$resultsList = $wpdb->get_results($queryList,ARRAY_A);
		$countList = count($resultsList);
		foreach($resultsList as $dataList) {
			echo "<div class=\"profile-category\">\n";
			if ($DataTypeID == $dataList["DataTypeID"]) {
				echo "  <div class=\"name\"><strong>". $dataList["DataTypeTitle"] ."</strong> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			} else {
				echo "  <div class=\"name\"><a href=\"".get_bloginfo('wpurl')."/profile-category/". $dataList["DataTypeTag"] ."/\">". $dataList["DataTypeTitle"] ."</a> <span class=\"count\">(". $dataList["CategoryCount"] .")</span></div>\n";
			}
			echo "</div>\n";
		}
		if ($countList < 1) {
			echo __("No Categories Found", RBAGENCY_TEXTDOMAIN);
		}

 
		/*
		 * Email to admin
		 */ 

	
		echo "				<div id=\"rbcasting-cart\">\n";
		echo "					<div class=\"cb\"></div>\n"; ?>
								<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
								<script type="text/javascript">
								$(document).ready(function(){
									$('#emailbox').toggle('slow'); 
									$("#sendemail").click(function(){
										$('#emailbox').toggle('slow'); 
									});
								});
								</script> 
		

							<!-- 	<div id="emailbox" >
									<form method="post" enctype="multipart/form-data" action="">
										<input type="hidden" name="action" value="cartEmail" />	      
										<div class="field"><label for="SearchMuxToName">Sender Name:</label><br/><input type="text" id="SearchMuxToName" name="SearchMuxToName" value="" required/></div>
										<div class="field"><label for="SearchMuxToEmail">Sender Email:</label><br/><input type="email" id="SearchMuxToEmail" name="SearchMuxToEmail" value="" required/></div>
										<div class="field"><label for="SearchMuxSubject">Subject:</label><br/><input type="text" id="SearchMuxSubject" name="SearchMuxSubject" value="Casting Category" required></div>
										<div class="field"><label for="SearchMuxMessage">Message to Admin:</label><br/>
											<textarea id="SearchMuxMessage" name="SearchMuxMessage" style="width: 500px; height: 300px; ">[category-link-placeholder]</textarea>
										</div>
										<p>(Note: The "[category-link-placeholder]" will be the link to your casting cart page) </p>
										<div class="field submit">
											<input type="hidden" name="action" value="sendEmailCastingCart" />
											<input type="submit" name="submit" value="Send Email" class="button-primary" /> 
										</div>
									</form>
								</div> -->
		<?php
       
		/*
		 * Get Profile Results
		 */ 
		echo "	<div id=\"profile-category-results\">\n";
		$atts = array("profiletype" => $DataTypeID, "profilecasting" => true);
		$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($atts);
		echo $search_results = RBAgency_Profile::search_results($search_sql_query, 0);
		echo "	</div><!-- #profile-category-results -->\n";
		
		echo "				</div>\n";

		echo "</div><!-- #profile-category -->\n";
		echo "<div class=\"cb\"></div>\n";
		
		}else{
			if(is_user_logged_in()){
						rb_get_profiletype();
			}else{
						echo "	<div class='restricted'>\n";
						if ( class_exists("RBAgencyCasting") ) {
						echo "<h2>Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/casting-login/\">login or register</a>.</h2>";
						}else{
						echo "<h2>Page restricted. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login</a> or <a href=\"".get_bloginfo("url")."/profile-register/\">register</a>.</h2>";
						}
						echo "  </div><!-- #content -->\n";
			}
			
		}
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #primary -->\n";
	

get_sidebar();
echo $rb_footer = RBAgency_Common::rb_footer(); 

?>