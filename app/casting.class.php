<?php
class RBAgency_Casting {

	/*
	 * Casting Cart
	 * Process Actions
	 */

		public static function cart_process(){

			/*
			 * Setup Requirements
			 */

			// Protect and defend the cart string!
				$cartString = "";
				$action = isset($_GET["action"])? $_GET["action"]:"";
				$actiontwo = isset($_GET["actiontwo"])?$_GET["actiontwo"]:"";


				if ($action == "cartAdd" && !isset($_GET["actiontwo"])) {
					// Add to Cart
					$response = self::cart_process_add();

				} elseif ($action == "formEmpty") {
					// Empty the Form
					extract($_SESSION);
					foreach($_SESSION as $key=>$value) {
						if (substr($key, 0, 7) == "Profile") {
							unset($_SESSION[$key]);
						}
					}

				} elseif ($action == "cartEmpty") {
					
					// Throw the baby out with the bathwater
					unset($_SESSION['cartArray']);

				} elseif ($action == "cartAdd" && $actiontwo ="cartRemove") {
					// Remove ID from Cart
					$id = $_GET["RemoveID"];
					$response = self::cart_process_remove($id);

				} elseif ($action == "searchSave") {
					// Save the Search
					if (isset($_SESSION['cartArray'])) {

						extract($_SESSION);
						foreach($_SESSION as $key=>$value) {
							// TODO: Why is this empty?
						}
						$_SESSION['cartArray'] = $cartArray;

					}

				}

				return true;
		}



	/*
	 * Casting Cart - Add to Cart
	 * @return str $cartString
	 */

		public static function cart_process_add(){

			$cartString = "";

        	// Get String
			if(isset($_GET["ProfileID"]) && count($_GET["ProfileID"]) > 0) {
				foreach($_GET["ProfileID"] as $value) {
					$cartString .= $value .",";
				}
			}

			// Clean It!
			$cartString = RBAgency_Common::clean_string($cartString);

			// Add to Session
			if (isset($_SESSION['cartArray'])) {
				$cartArray = $_SESSION['cartArray'];
				array_push($cartArray, $cartString);
			} else {
				$cartArray = array($cartString);
			}

			// Replace Session
			$_SESSION['cartArray'] = $cartArray;

			return $cartString;

		}


	/*
	 * Casting Cart - Remove from Cart
	 * @return str $cartString
	 */

		public static function cart_process_remove($id){

			// Remove ID from Cart
			if (isset($id)) {
				$cartArray = $_SESSION['cartArray'];
				$cartString = implode(",", $cartArray);

				// TODO: FIX  this, if ID = 3, changes 38 to 8
				$cartString = str_replace($id ."", "", $cartString);
				$cartString = RBAgency_Common::clean_string($cartString);

				// Put it back in the array, and wash your hands
				$_SESSION['cartArray'] = array($cartString);
			}

			// echo "TEST";
		}



	/*
	 * Show Casting Cart
	 */

		public static function cart_show(){
			
			global $wpdb;

			if (isset($_SESSION['cartArray']) && !empty($_SESSION['cartArray'])) {

				$cartArray = $_SESSION['cartArray'];

				

					$cartString = implode(",", array_unique($cartArray));
					$cartString = RBAgency_Common::clean_string($cartString);

				// Show Cart  
				$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") ORDER BY profile.ProfileContactNameFirst ASC";
				//$query = "SELECT profile.ProfileID, profile.ProfileGallery, profile.*, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID,  (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID IN(".$cartString.") AND profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile GROUP BY profile.ProfileID";
					
				$results = $wpdb->get_results($query,ARRAY_A);// or  die( "<a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("No profile selected. Try again", rb_agency_TEXTDOMAIN) ."</a>"); //die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
				$count = count($results);
				echo "<div class=\"boxblock-container\" style=\"float: left; padding-top:24px; width: 49%; min-width: 500px;\">\n";
				echo "<div style=\"float: right; width: 100px; \"><a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("Empty Cart", rb_agency_TEXTDOMAIN) ."</a></div>";
				echo "<div style=\"float: left; line-height: 22px; font-family:Georgia; font-size:13px; font-style: italic; color: #777777; \">". __("Currently", rb_agency_TEXTDOMAIN) ." <strong>". $count ."</strong> ". __("in Cart", rb_agency_TEXTDOMAIN) ."</div>";
				echo "<div style=\"clear: both; border-top: 2px solid #c0c0c0; width:510px;\" class=\"profile\">";

				if ($count == 1) {
					$cartAction = "cartEmpty";
				} elseif ($count < 1) {
					echo "". __("There are currently no profiles in the casting cart", rb_agency_TEXTDOMAIN) .".";
					$cartAction = "cartEmpty";
				} else {
					$cartAction = "cartRemove";
				}
				
				foreach($results as $data) {
					
					$ProfileDateUpdated = $data['ProfileDateUpdated'];
					echo "  <div style=\"position: relative; border: 1px solid #e1e1e1; line-height: 22px; float: left; padding: 10px; width: 210px; margin: 6px; \">";
					echo "    <div style=\"text-align: center; \"><h3>". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</h3></div>"; 
					echo "    <div style=\"float: left; width: 100px; height: 100px; overflow: hidden; margin-top: 2px; \"><img style=\"width: 100px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
					echo "    <div style=\"float: left; width: 100px; height: 100px; overflow: scroll-y; margin-left: 10px; line-height: 11px; font-size: 9px; \">\n";

					if (!empty($data['ProfileDateBirth'])) {
						echo "<strong>Age:</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."<br />\n";
					}
					// TODO: ADD MORE FIELDS

					echo "    </div>";
					echo "    <div style=\"position: absolute; z-index: 20; top: 120px; left: 200px; width: 20px; height: 20px; overflow: hidden; \"><a href=\"?page=". $_GET['page'] ."&actiontwo=cartRemove&action=cartAdd&RemoveID=". $data['ProfileID'] ."&\" title=\"". __("Remove from Cart", rb_agency_TEXTDOMAIN) ."\"><img src=\"". rb_agency_BASEDIR ."style/remove.png\" style=\"width: 20px; \" alt=\"". __("Remove from Cart", rb_agency_TEXTDOMAIN) ."\" /></a></div>";
					echo "    <div style=\"clear: both; \"></div>";
					echo "  </div>";
				}
				echo "  <div style=\"clear: both;\"></div>\n";
				echo "</div>";
				
				if (($cartAction == "cartEmpty") || ($cartAction == "cartRemove")) {
				echo "<a name=\"compose\">&nbsp;</a>"; 
				echo "<div class=\"boxblock\">\n";
				echo "   <h3>". __("Cart Actions", rb_agency_TEXTDOMAIN) ."</h3>\n";
				echo "   <div class=\"inner\">\n";
				echo "      <a href=\"?page=rb_agency_searchsaved&action=searchSave\" title=\"". __("Save Search & Email", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Save Search & Email", rb_agency_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"?page=rb_agency_search&action=massEmail#compose\" title=\"". __("Mass Email", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Mass Email", rb_agency_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"#\" onClick=\"openWindow('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=1')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"#\" onClick=\"openWindow('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=0')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", rb_agency_TEXTDOMAIN) ." - ". __("Without Details", rb_agency_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"?page=rb_agency_castingjobs&action2=addnew&action=informTalent\" title=\"". __("Create Casting Job", rb_agency_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Create Casting Job", rb_agency_TEXTDOMAIN) ."</a>\n";
				echo "   </div>\n";
				echo "</div>\n";
				$cartArray = isset($_SESSION['cartArray'])?$_SESSION['cartArray']:array();
				$cartString = implode(",", array_unique($cartArray));
				$cartString = RBAgency_Common::clean_string($cartString);
						
				 echo "<div class=\"boxblock-container\">";
						 echo "<div class=\"boxblock\" style=\"width:100%\" >";
						 
									echo "<h3>Add to existing Job</h3>";
								 
							 echo "<div class=\"innerr\" style=\"padding: 10px;\">";
			 				 echo "<form class=\"castingtext\" method=\"post\" action=\"?page=rb_agency_castingjobs&action=informTalent&Job_ID=\">";
							   echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
									echo "<div>";
										echo "<select name=\"Job_ID\" style=\"width:80%;\">";
										echo "<option value=\"\">- Select -</option>";
										$castings = $wpdb->get_results("SELECT * FROM ".table_agency_casting_job." ORDER BY Job_ID DESC");
										foreach ($castings as $key) {
											echo "<option attrid=\"".$key->Job_ID."\" value=\"".$key->Job_ID."-".$key->Job_UserLinked."\">".$key->Job_Title."</option>";
										}
										echo "<select>";
									    echo "&nbsp;<input type=\"submit\" class=\"button-primary button\" name=\"addtoexisting\" value=\"Submit\"/>";
									echo "</div>";
								echo "</div>";
								echo "<script type=\"text/javascript\">";
								echo "jQuery(function(){
										 jQuery(\"select[name=Job_ID]\").change(function(){
												var a = jQuery(\"select[name=Job_ID] option:selected\").attr('attrid');
												if(a !== 'undefined'){
													jQuery('.castingtext').attr('action',jQuery('.castingtext').attr('action')+a);
												}
										});
								});";
								echo "</script>";
								echo "<input type=\"hidden\" name=\"addprofiles\" value=\"".$cartString."\"/>";
							 echo "</form>";
							echo "</div>";
						echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "<script type=\"text/javascript\">\n";
				echo "function openWindow(url){ \n";
				echo " window.open(url,'mywindow'+Math.random(),'width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes');";
				echo "}\n";
				echo "</script>\n";
				?>
			

				<?php 
				} // Is Cart Empty 

			} else {

				echo "<p>There are no profiles added to the casting cart.</p>\n";

			}


		}


	/*
	 * Casting Cart - Send Email Process
	 */

		public static function cart_send_process(){
			
			$isSent = false;
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_value_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
			$rb_agency_value_agencyemail = $rb_agency_options_arr['rb_agency_option_agencyemail'];


			$MassEmailSubject = $_POST["MassEmailSubject"];
			$MassEmailMessage = $_POST["MassEmailMessage"];
			$MassEmailRecipient = $_POST["MassEmailRecipient"];
			$MassEmailBccEmail = $_POST["MassEmailBccEmail"];
			
			$SearchMuxHash			= RBAgency_Common::generate_random_string(8);
		
			$SearchMuxToName		=$_POST["MassEmailRecipient"];
			$SearchMuxToEmail		=$_POST["MassEmailRecipient"];
			
			$SearchMuxEmailToBcc	=$_POST['MassEmailBccEmail'];
			$SearchMuxSubject		= $_POST['MassEmailSubject'];
			$SearchMuxMessage		=$_POST['MassEmailMessage'];
			$SearchMuxCustomValue	='';
			$cartArray = $_SESSION['cartArray'];
			
			$cartString = implode(",", array_unique($cartArray));
			$cartString = rb_agency_cleanString($cartString);
			
			global $wpdb;
		$wpdb->query($wpdb->prepare("INSERT INTO " . table_agency_searchsaved." (SearchProfileID,SearchTitle) VALUES('%s','%s')",$cartString,$SearchMuxSubject)) or die($wpdb->print_error());
					
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
				'" . esc_sql($lastid) . "',
				'" . esc_sql($SearchMuxHash) . "',
				'" . esc_sql($SearchMuxToName) . "',
				'" . esc_sql($SearchMuxToEmail) . "',
				'" . esc_sql($SearchMuxSubject) . "',
				'" . esc_sql($SearchMuxMessage) . "',
				'" . esc_sql($SearchMuxCustomValue) ."'
				)";
			$results = $wpdb->query($insert);  

			$profileimage = "";  
			$profileimage .='<p><div style="width:550px;min-height: 170px;">';
			$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE search.SearchID = \"%d\"";
			$qProfiles =  $wpdb->get_results($wpdb->prepare($query,$lastid ),ARRAY_A);
			$data = current($qProfiles);
			$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (%s) ORDER BY ProfileContactNameFirst ASC";
			$results = $wpdb->get_results($wpdb->prepare($query,$data['SearchProfileID']),ARRAY_A);
			$count = count($results);
			foreach ($results as $data2) {
				$profileimage .= " <div style=\"background:black; color:white;float: left; max-width: 100px; height: 150px; margin: 2px; overflow:hidden;  \">";
				$profileimage .= " <div style=\"margin:3px;max-width:250px; max-height:300px; overflow:hidden;\">";
				$profileimage .= stripslashes($data2['ProfileContactNameFirst']) ." ". stripslashes($data2['ProfileContactNameLast']);
				$profileimage .= "<br /><a href=\"". rb_agency_PROFILEDIR . $data2['ProfileGallery'] ."/\" target=\"_blank\">";
				$profileimage .= "<img style=\"max-width:130px; max-height:150px; \" src=\"".rb_agency_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $data2['ProfileMediaURL'] ."\" /></a>";
				$profileimage .= "</div>\n";
				$profileimage .= "</div>\n";
			  }
			 $profileimage .="</div></p>";
			
			// Mail it
			$headers[]  = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = 'From: '.$rb_agency_value_agencyname.' <'. $rb_agency_value_agencyemail .'>';

			/*if(!empty($expMail)){
				$expMail = explode(",",$MassEmailRecipient);
				foreach($expMail as $bccEmail){
						$headers[] = 'Bcc: '.$bccEmail;
				}
			}*/
			
			//For Bcc emails
			if(!empty($MassEmailBccEmail)){
				$bccMail = explode(";",$MassEmailBccEmail);
				foreach($bccMail as $bcc){
						$headers[] = 'Bcc: '.$bcc;
				}
			}
			 $MassEmailMessage = str_replace("[link-place-holder]",site_url()."/client-view/".$SearchMuxHash."<br/><br/>".$profileimage ."<br/><br/>",$MassEmailMessage);
			 $MassEmailMessage	= str_ireplace("[site-url]",get_bloginfo("url"),$MassEmailMessage);
			 $MassEmailMessage	= str_ireplace("[site-title]",get_bloginfo("name"),$MassEmailMessage);
			 $isSent = wp_mail($MassEmailRecipient, $MassEmailSubject, $MassEmailMessage, $headers);
			 $url = admin_url('admin.php?page=rb_agency_searchsaved&m=1');
			if($isSent){?>
			<script type="text/javascript"> 
				window.location="<?php echo $url;?>";
			</script>
			<?php 
			}
			 return $isSent;

		}

			//TODO : need to verify above method used or not 
		public static function cart_email_send_process(){
			
			$isSent = false;
			$email_error=  "" ; 
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_value_agencyname = isset($rb_agency_options_arr['rb_agency_option_agencyname'])?$rb_agency_options_arr['rb_agency_option_agencyname']:"";
			$rb_agency_option_agencyemail = isset($rb_agency_options_arr['rb_agency_option_agencyemail'])?$rb_agency_options_arr['rb_agency_option_agencyemail']:"";
		
			$SearchID			= isset($_GET['SearchID']) ? $_GET['SearchID']: "";
			if(!isset($_POST["resend"]) && empty($_POST["resend"])){
			    $SearchMuxHash	= RBAgency_Common::generate_random_string(8);
			}else{
				$SearchMuxHash = isset($_GET["SearchMuxHash"])?$_GET["SearchMuxHash"]:"";
			}
			$SearchMuxFromName		= isset($_POST["SearchMuxFromName"])?$_POST["SearchMuxFromName"]:"";
			$SearchMuxFromEmail		= isset($_POST["SearchMuxFromEmail"])?$_POST["SearchMuxFromEmail"]:"";
			$SearchMuxToName		= isset($_POST['SearchMuxToName'])?$_POST['SearchMuxToName']:"";
			$SearchMuxToEmail		= isset($_POST['SearchMuxToEmail'])?$_POST['SearchMuxToEmail']:"";
			$SearchMuxBccEmail		= isset($_POST['SearchMuxBccEmail'])?$_POST['SearchMuxBccEmail']:"";
			$SearchMuxSubject		= isset($_POST['SearchMuxSubject'])?$_POST['SearchMuxSubject']:"";
			$SearchMuxMessage		= isset($_POST['SearchMuxMessage'])?$_POST['SearchMuxMessage']:"";
			$SearchMuxCustomValue	='';
			$cartArray = $_SESSION['cartArray'];
			
			$cartString = implode(",", array_unique($cartArray));
			$cartString = rb_agency_cleanString($cartString);
			
			global $wpdb;
			//$wpdb->query("INSERT INTO " . table_agency_searchsaved." (SearchProfileID,SearchTitle) VALUES('".$cartString."','".$SearchMuxSubject."')") or die($wpdb->print_error());
					
		   //$lastid = $wpdb->insert_id;
		if(!isset($_GET["resend"])){
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
								'" . esc_sql($SearchID) . "',
								'" . esc_sql($SearchMuxHash) . "',
								'" . esc_sql($SearchMuxToName) . "',
								'" . esc_sql($SearchMuxToEmail) . "',
								'" . esc_sql($SearchMuxSubject) . "',
								'" . esc_sql($SearchMuxMessage) . "',
								'" . esc_sql($SearchMuxCustomValue) ."'
								)";
							$results = $wpdb->query($insert);  
							$profileimage = "";  
							$profileimage .='<p><div style="width:550px;min-height: 170px;">';
							$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE search.SearchID = \"%d\"";
							$qProfiles =  $wpdb->get_results($wpdb->prepare($query,$SearchID),ARRAY_A );
							$data = count($qProfiles);
							$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (%s) ORDER BY ProfileContactNameFirst ASC";
							$results = $wpdb->get_results($wpdb->prepare($query,$data['SearchProfileID']),ARRAY_A);
							$count = count($results);
							foreach($results as $data2) {
								$profileimage .= " <div style=\"background:black; color:white;float: left; max-width: 100px; height: 150px; margin: 2px; overflow:hidden;  \">";
								$profileimage .= " <div style=\"margin:3px;max-width:250px; max-height:300px; overflow:hidden;\">";
								$profileimage .= stripslashes($data2['ProfileContactNameFirst']) ." ". stripslashes($data2['ProfileContactNameLast']);
								$profileimage .= "<br /><a href=\"". rb_agency_PROFILEDIR . $data2['ProfileGallery'] ."/\" target=\"_blank\">";
								$profileimage .= "<img style=\"max-width:130px; max-height:150px; \" src=\"".rb_agency_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $data2['ProfileMediaURL'] ."\" /></a>";
								$profileimage .= "</div>\n";
								$profileimage .= "</div>\n";
							}
							$profileimage .="</div></p>";
			 } // end is not resend / is new

			// Mail it
			$headers[]  = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			if(!empty($SearchMuxFromEmail)){
				if ( !is_email($SearchMuxFromEmail, true)) {
					$email_error ="<div style='font-weight:bold; padding:5px; color:red'>From Email was invalid. Email was not sent.</div>";
				} else {
					$headers[]  = 'From: '. $SearchMuxFromName .' <'. $SearchMuxFromEmail.'>' . "\r\n";
				}
			} else {
					$headers[]  = 'From: '.$rb_agency_value_agencyname.' <'. $rb_agency_option_agencyemail .'>' . "\r\n";
			}

			//For Bcc emails
			if(!empty($SearchMuxBccEmail)){
				$bccMail = explode(";",$SearchMuxBccEmail);
				foreach($bccMail as $bcc){
						$headers[] = 'Bcc: '.$bcc;
				}
			}
			$SearchMuxMessage = str_replace("[link-place-holder]",site_url()."/client-view/".$SearchMuxHash."<br/><br/>".$profileimage ."<br/><br/>",$SearchMuxMessage);
			$SearchMuxMessage	= str_ireplace("[site-url]",get_bloginfo("url"),$SearchMuxMessage);
			$SearchMuxMessage	= str_ireplace("[site-title]",get_bloginfo("name"),$SearchMuxMessage);
			$isSent = wp_mail($SearchMuxToEmail, $SearchMuxSubject, $SearchMuxMessage, $headers);

			if($isSent){
				if(!empty($SearchMuxFromEmail)){
					$email_error .= "<div style=\"margin:15px;\">";
					$email_error .= "<div id=\"message\" class=\"updated\">";
					$email_error .= "Email successfully sent from <strong>". $SearchMuxFromEmail ."</strong> to <strong>". $SearchMuxToEmail ."</strong><br />";
					$email_error .= "Message sent: <p>". make_clickable($SearchMuxMessage) ."</p>";
					$email_error .= "</div>";
					$email_error .= "</div>";
				} else {
					$email_error .= "<div style=\"margin:15px;\">";
					$email_error .= "<div id=\"message\" class=\"updated\">";
					$email_error .= "Email successfully sent from <strong>". $rb_agency_option_agencyemail ."</strong> to <strong>". $SearchMuxToEmail ."</strong><br />";
					$email_error .= "Message sent: <p>". make_clickable($SearchMuxMessage) ."</p>";
					$email_error .= "</div>";
					$email_error .= "</div>";
				}
			}
			return $email_error ;
		}

	/*
	 * Form to Send Casting Cart
	 */

		public static function cart_send_form(){
			global $wpdb;
		
		/*if(isset($_POST["SendEmail"])){
			// Process Form
			$isSent = RBAgency_Casting::cart_send_process();

		}*/

			if(isset($_GET["action"]) && $_GET["action"] == "massEmail"){
				echo RBAgency_Casting::cart_show();
				// Filter Models Already in Cart
				if (isset($_SESSION['cartArray'])) {
					$cartArray = $_SESSION['cartArray'];
					$cartString = implode(",", $cartArray);
					$cartQuery =  " AND profile.ProfileID IN (". $cartString .")";
				}
				$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_value_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
				$rb_agency_value_agencyemail = $rb_agency_options_arr['rb_agency_option_agencyemail'];
				// Search Results	
				$query = "SELECT profile.*  FROM ". table_agency_profile ." profile WHERE profile.ProfileID > 0 ".$cartQuery;
				$results2 = $wpdb->get_results($query,ARRAY_A);
				$count =count($results2);
				$pos = 0;
				$recipient = "";
				foreach($results2 as $data) {
					$pos ++;
					$ProfileID = $data['ProfileID'];
					$recipient .=$data['ProfileContactEmail'];
					if($count != $pos){
						$recipient .= ";";
					}

				}
			
				// Email
				//echo "Email starts";
				echo "<div style=\"clear:both;\"></div>";
				echo "<form method=\"post\">";
				echo "     <div class=\"boxblock\">\n";
				echo "        <h3>". __("Compose Email", rb_agency_TEXTDOMAIN) ."</h3>\n";
				echo "        <div class=\"inner\">\n";
				/*if($msg!=""){
				echo "          <div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";
				}*/
				echo "          <strong>Recipient:</strong><br/><textarea name=\"MassEmailRecipient\" style=\"width:100%;\">".$rb_agency_value_agencyemail."</textarea><br/>";
				echo "          <strong>Bcc:</strong><br/><textarea name=\"MassEmailBccEmail\" style=\"width:100%;\">".$recipient."</textarea><br/>";
				echo "          <strong>Subject:</strong> <br/><input type=\"text\" name=\"MassEmailSubject\" style=\"width:100%\"/>";
				echo "          <br/>";
			/*	echo "          <strong>Message:</strong><br/>     <textarea name=\"MassEmailMessage\"  style=\"width:100%;height:300px;\">this message was sent to you by ".$rb_agency_value_agencyname." ".network_site_url( '/' )."</textarea>";*/
				//Adding Wp editor
				$content = "Add Message Here<br />Click the following link (or copy and paste it into your browser):<br />
[link-place-holder]<br /><br />This message was sent to you by:<br />[site-title]<br />[site-url]";
				$editor_id = 'MassEmailMessage';
				wp_editor( $content, $editor_id,array("wpautop"=>false,"tinymce"=>true) );
				
				echo "          <input type=\"submit\" value=\"". __("Send Email", rb_agency_TEXTDOMAIN) . "\" name=\"SendEmail\"class=\"button-primary\" />\n";
				echo "        </div>\n";
				echo "     </div>\n";
				echo "</form>";
				echo "     </div>\n";
			}

			return true;

		}

		public static function sendEmailCastingAvailability($Talents_Display_Name,$Availability,$Job_Name,$link){
			// Mail it
		    $headers[]  = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			$headers[]  = 'From: '. get_bloginfo("name") .' <noreply@'.get_bloginfo("siteurl").'>' . "\r\n";
				

			$MassEmailMessage	= "Hi, \n\n".$Talents_Display_Name." has changed the job availability to \"".$Availability."\" for the job \"".$Job_Name."\"."
								 . "\nClick here to review your casting cart: ".$link
								 .  "\n\n-".get_bloginfo("name");
			$isSent = wp_mail(get_bloginfo('admin_email'), get_bloginfo("name").": Job Availability", $MassEmailMessage, $headers);
			
	}

}