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
				$action = isset($_REQUEST["action"])? $_REQUEST["action"]:$_GET["action"];
				$actiontwo = isset($_REQUEST["actiontwo"])?$_REQUEST["actiontwo"]:"";


				if ($action == "cartAdd" && !isset($_REQUEST["actiontwo"])) {
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
			if(isset($_REQUEST["ProfileID"]) && count($_REQUEST["ProfileID"]) > 0) {
				foreach($_REQUEST["ProfileID"] as $value) {
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
			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
				

			if (isset($_SESSION['cartArray']) && !empty($_SESSION['cartArray'])) {

				$cartArray = $_SESSION['cartArray'];

				

					$cartString = implode(",", array_filter(array_unique($cartArray)));
					$cartString = RBAgency_Common::clean_string($cartString);

				// Show Cart  
				$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") GROUP BY profile.ProfileID ORDER BY profile.ProfileContactNameFirst ASC";
				//$query = "SELECT profile.ProfileID, profile.ProfileGallery, profile.*, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID,  (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID IN(".$cartString.") AND profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile GROUP BY profile.ProfileID";
					
				$results = $wpdb->get_results($query,ARRAY_A);// or  die( "<a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("No profile selected. Try again", RBAGENCY_TEXTDOMAIN) ."</a>"); //die ( __("Error, query failed", RBAGENCY_TEXTDOMAIN ));
				$count = count($results);
				
				echo "<div class=\"empty-cart\"><a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("Empty Cart", RBAGENCY_TEXTDOMAIN) ."</a></div>";
				echo "<div class=\"in-cart\">". __("Currently", RBAGENCY_TEXTDOMAIN) ." <strong>". $count ."</strong> ". __("in Cart", RBAGENCY_TEXTDOMAIN) ."</div>";
				echo "<div style=\"clear: both; border-top: 2px solid #c0c0c0;\" class=\"profile\">";

				if ($count == 1) {
					$cartAction = "cartEmpty";
				} elseif ($count < 1) {
					echo "". __("There are currently no profiles in the casting cart", RBAGENCY_TEXTDOMAIN) .".";
					$cartAction = "cartEmpty";
				} else {
					$cartAction = "cartRemove";
				}
					$arr_thumbnail = "";
					if(isset($_SESSION["profilephotos"]))
					$arr_thumbnail = (array)unserialize($_SESSION["profilephotos"]);
				foreach($results as $data) {
					$ProfileContactNameFirst = $data["ProfileContactNameFirst"];
					$ProfileContactNameLast = $data["ProfileContactNameLast"];
					$ProfileID = $data["ProfileID"];
					if ($rb_agency_option_profilenaming == 0) {
						$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
					} elseif ($rb_agency_option_profilenaming == 1) {
						$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
					} elseif ($rb_agency_option_profilenaming == 2) {
						$ProfileContactDisplay = $dataList["ProfileContactDisplay"];
					} elseif ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID-". $ProfileID;
					} elseif ($rb_agency_option_profilenaming == 4) {
						$ProfileContactDisplay = $ProfileContactNameFirst;
					} elseif ($rb_agency_option_profilenaming == 5) {
						$ProfileContactDisplay = $ProfileContactNameLast;
					}


					$ProfileDateUpdated = $data['ProfileDateUpdated'];
					echo "  <div style=\"position: relative; border: 1px solid #e1e1e1; line-height: 22px; float: left; padding: 10px; width: 210px; margin: 6px; \">";
					echo "    <div style=\"text-align: center; \"><h3>". $ProfileContactDisplay  . "</h3></div>"; 
					echo "    <div style=\"float: left; width: 100px; height: 100px; overflow: hidden; margin-top: 2px; \">";
					if(isset($arr_thumbnail[$data["ProfileID"]])){
						$thumbnail = $wpdb->get_row($wpdb->prepare("SELECT ProfileMediaURL FROM ".table_agency_profile_media." WHERE ProfileMediaID =  %d ", $arr_thumbnail[$data["ProfileID"]]));
						echo "<img class=\"img-".$data['ProfileID']."\" style=\"width: 100px; \" src=\"". RBAGENCY_UPLOADDIR ."". $data['ProfileGallery'] ."/". $thumbnail->ProfileMediaURL ."\" /></div>\n";
					}else{
						echo "<img class=\"img-".$data['ProfileID']."\" style=\"width: 100px; \" src=\"". RBAGENCY_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
					}
					echo "    <div style=\"float: left; width: 100px; height: 100px; overflow: scroll-y; margin-left: 10px; line-height: 11px; font-size: 9px; \">\n";

					if (!empty($data['ProfileDateBirth'])) {
						echo "<strong>Age:</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."<br />\n";
					}
					// TODO: ADD MORE FIELDS

					echo "    </div>";
					echo "    <div style=\"position: absolute; z-index: 20; top: 120px; left: 200px; width: 20px; height: 20px; overflow: hidden; \"><a href=\"?page=". $_GET['page'] ."&actiontwo=cartRemove&action=cartAdd&RemoveID=". $data['ProfileID'] ."&\" title=\"". __("Remove from Cart", RBAGENCY_TEXTDOMAIN) ."\"><img src=\"". RBAGENCY_PLUGIN_URL ."assets/img/remove.png\" style=\"width: 20px; \" alt=\"". __("Remove from Cart", RBAGENCY_TEXTDOMAIN) ."\" /></a></div>";
					echo "    <div style=\"clear: both; \"></div>";
					echo "	 <a id=\"".$data['ProfileID']."\" attr-gallery=\"".$data['ProfileGallery']."\"  href=\"#TB_inline?width=600&height=350&inlineId=profilephotos\" class=\"thickbox\" title=\"Change thumbnail\">Change thumbnail</a>";
					echo " 	 <input type=\"hidden\" id=\"thumbnail-".$data['ProfileID']."\"  name=\"thumbnail[".$data['ProfileID']."]\" value=\"\"/>";
					echo "  </div>";
				}
				echo "  <div style=\"clear: both;\"></div>\n";
				

				add_thickbox();

				echo "<div id=\"profilephotos\" class=\"boxblock-container\" >";
				echo "<div class=\"boxblock\">";
				echo "</div>";
				echo "</div>";

				?>
				<script type="text/javascript">

					jQuery(document).ready(function(){

						var arr_thumbnails = [];

							jQuery('a[class^=thickbox]').on('click', function(){
								var id = jQuery(this).attr("id");
								var profile_gallery = "<?php echo get_bloginfo('url').'/wp-content/plugins/rb-agency/ext/timthumb.php?src='.RBAGENCY_UPLOADDIR;?>"+jQuery(this).attr("attr-gallery")+"/";
									jQuery("#profilephotos").empty().html("<center>Loading photos...</center>");
									jQuery.ajax({
										type: 'POST',
										dataType: 'json',
										url: '<?php echo admin_url('admin-ajax.php'); ?>',
										data: { 
											'action': 'rb_agency_profile_photos', 
											'profileID': id, 
										},
										success: function(data){
											jQuery("#TB_ajaxContent").empty();
											for (var i = data.length - 1; i >= 0; i--) {
												jQuery("#TB_ajaxContent").append("<div class=\"\" style=\"width:120px;height:120px;float:left;margin:5px;padding:5px; border:1px solid #ccc;\"><img src=\""+profile_gallery+data[i].ProfileMediaURL+"&h=120&w=120\" style=\"z-index: -33;float: left;\"/><input style=\"float: left;margin-top: -20px;margin-left: 5px;z-index: 12;\" type=\"radio\" name=\"photo\" value=\""+data[i].ProfileID+"\" attr-media-id=\""+data[i].ProfileMediaID+"\" attr-media=\""+data[i].ProfileMediaURL+"\"/></div>");
											};
											if(data.length <= 0){
												jQuery("#TB_ajaxContent").html("<center>No photos found.</center>");
											}
											jQuery("input[name=photo]").on("click",function(){
													jQuery("img[class=img-"+id).attr("src",profile_gallery+jQuery(this).attr("attr-media"));
													jQuery("input[id^=thumbnail-"+id+"]").val(id+":"+jQuery(this).attr("attr-media-id"));
													arr_thumbnails[id] = jQuery(this).attr("attr-media-id");
													jQuery.ajax({
														type: 'POST',
														url: '<?php echo admin_url('admin-ajax.php'); ?>',
														data: { 
															'action': 'rb_agency_save_profile_photos', 
															'profilephotos': arr_thumbnails, 
														},
														success: function(data){
															console.log(data);

														}
													});
													tb_remove();
													jQuery("#TB_ajaxContent").empty();
											});


										}
									});
							});
						});
				</script>
				<?php

				if (($cartAction == "cartEmpty") || ($cartAction == "cartRemove")) {
				echo "<a name=\"compose\">&nbsp;</a>"; 
				echo "<div id=\"cart-actions\" class=\"boxblock\">\n";
				echo "   <h3>". __("Cart Actions", RBAGENCY_TEXTDOMAIN) ."</h3>\n";
				echo "   <div class=\"inner\">\n";
				echo "      <a href=\"?page=rb_agency_searchsaved&action=searchSave\" title=\"". __("Save Search & Email", RBAGENCY_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Save Search & Email", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"?page=rb_agency_search&action=massEmail#compose\" title=\"". __("Mass Email", RBAGENCY_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Mass Email", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"#\" onClick=\"openWindow('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=1')\" title=\"Quick Print\" class=\"button-primary\">". __("Quick Print", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"#\" onClick=\"openWindow('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=0')\" title=\"Quick Print - Without Details\" class=\"button-primary\">". __("Quick Print", RBAGENCY_TEXTDOMAIN) ." - ". __("Without Details", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"#\" onClick=\"openWindow('". get_bloginfo("url") ."/profile-print/?action=castingCart&cD=2')\" title=\"Quick Print - One Profile per Page\" class=\"button-primary\">". __("Quick Print", RBAGENCY_TEXTDOMAIN) ." - ". __("One Profile per Page", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				echo "      <a href=\"?page=rb_agency_castingjobs&action2=addnew&action=informTalent\" title=\"". __("Create Casting Job", RBAGENCY_TEXTDOMAIN) ."\" class=\"button-primary\">". __("Create Casting Job", RBAGENCY_TEXTDOMAIN) ."</a>\n";
				echo "   </div>\n";
				echo "</div>\n";
				$cartArray = isset($_SESSION['cartArray'])?$_SESSION['cartArray']:array();
				$cartString = implode(",", array_unique($cartArray));
				$cartString = RBAgency_Common::clean_string($cartString);

				
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
											jQuery('.castingtext').attr('action','?page=rb_agency_castingjobs&action=informTalent&Job_ID='+a);
										}
								});
						});";
						echo "</script>";
						echo "<input type=\"hidden\" name=\"addprofiles\" value=\"".$cartString."\"/>";
					echo "</form>";
					echo "</div><!-- .inner -->";
				echo "</div><!-- .boxblock -->";
				echo "<div class=\"boxblock\" style=\"width:100%\" >";
					echo "<h3>Add to saved search</h3>";
					echo "<div class=\"innerr\" style=\"padding: 10px;\">";
					echo "<form class=\"savedsearchtext\" method=\"post\" action=\"?page=rb_agency_searchsaved&action=edit&SearchID=\">";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
							echo "<div>";
								echo "<select name=\"SearchID\" style=\"width:80%;\">";
								echo "<option value=\"\">- Select -</option>";
								$castings = $wpdb->get_results("SELECT * FROM ".table_agency_searchsaved." ORDER BY  SearchID DESC");
								foreach ($castings as $key) {
									echo "<option attrid=\"".$key->SearchID."\" value=\"".$key->SearchID."\">".$key->SearchTitle."</option>";
								}
								echo "<select>";
							echo "&nbsp;<input type=\"submit\" class=\"button-primary button\" name=\"addtoexisting\" value=\"Submit\"/>";
							echo "</div>";
						echo "</div>";
						echo "<script type=\"text/javascript\">";
						echo "jQuery(function(){
								jQuery(\"select[name=SearchID]\").change(function(){
										var a = jQuery(\"select[name=SearchID] option:selected\").attr('attrid');
										if(a !== 'undefined'){
											jQuery('.savedsearchtext').attr('action','?page=rb_agency_searchsaved&action=edit&SearchID='+a);
										}
								});
						});";
						echo "</script>";
						echo "<input type=\"hidden\" name=\"addprofiles\" value=\"".$cartString."\"/>";
					echo "</form>";
					echo "</div><!-- .inner -->";
				echo "</div><!-- .boxblock -->";
				echo "</div>";
				echo "<script type=\"text/javascript\">\n";
				echo "function openWindow(url){ \n";
				echo " window.open(url,'mywindow'+Math.random(),'width=930,height=600,left=0,top=50,screenX=0,screenY=50,scrollbars=yes');";
				echo "}\n";
				echo "</script>\n";
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
			$rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;

			$MassEmailSubject = $_POST["MassEmailSubject"];
			$MassEmailMessage = $_POST["MassEmailMessage"]; //preg_replace('/[^A-Za-z0-9 !@#$%^&*().\[\]\\\- \t\n\r\0\x0B]/u','',$_POST["MassEmailMessage"]);
			$MassEmailMessage = preg_replace('/\n(\s*\n)+/', '</p><p>', $MassEmailMessage);
			$MassEmailRecipient = $_POST["MassEmailRecipient"];
			$MassEmailBccEmail = $_POST["MassEmailBccEmail"];

			$SearchMuxHash			= RBAgency_Common::generate_random_string(8);
			$SearchMuxToName		= $_POST["MassEmailRecipient"];
			$SearchMuxToEmail		= $_POST["MassEmailRecipient"];
			
			$SearchMuxEmailToBcc	= $_POST['MassEmailBccEmail'];
			$SearchMuxSubject		= $_POST['MassEmailSubject'];
			$SearchMuxMessage		= isset($_POST['SearchMuxMessage'])?preg_replace('/[^A-Za-z0-9 !@#$%^&*().\[\]\/\- \s\t\n\r\0\x0B]/u','',$_POST['SearchMuxMessage']):"";
			$SearchMuxMessage 		= preg_replace('/\n(\s*\n)+/', '</p><p>', $SearchMuxMessage);
			$SearchMuxCustomValue	='';
			$cartArray = $_SESSION['cartArray'];
			
			$cartString = implode(",", array_filter(array_unique($cartArray)));
			$cartString = rb_agency_cleanString($cartString);
			$cartProfileMedia = isset($_SESSION["profilephotos"]) ? serialize($_SESSION["profilephotos"]):"";

			global $wpdb;

		if(!isset($_GET["SearchID"])){

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
				SearchMuxCustomValue,
				SearchMuxCustomThumbnail
				)" .
				"VALUES
				(
				'" . esc_sql($lastid) . "',
				'" . esc_sql($SearchMuxHash) . "',
				'" . esc_sql($SearchMuxToName) . "',
				'" . esc_sql($SearchMuxToEmail) . "',
				'" . esc_sql($SearchMuxSubject) . "',
				'" . esc_sql($SearchMuxMessage) . "',
				'" . esc_sql($SearchMuxCustomValue) ."',
				'" . $cartProfileMedia ."'
				)";
			$results = $wpdb->query($insert); 
			$SearchID = $results; 

							$profileimage = "";  
							$profileimage .='<p><div style="overflow:hidden;min-height: 170px;">';
							$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE search.SearchID = \"%d\"";
							$data =  $wpdb->get_row($wpdb->prepare($query,$SearchID),ARRAY_A );
							$query = "SELECT * FROM (SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst ASC) as profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") GROUP BY(profile.ProfileID)";
							$results = $wpdb->get_results($query,ARRAY_A);
							$count = count($results);
							$arr_thumbnail = (array)unserialize($cartProfileMedia);
							foreach($results as $data2) {
								$ProfileContactNameFirst = $data2["ProfileContactNameFirst"];
								$ProfileContactNameLast = $data2["ProfileContactNameLast"];
								$ProfileID = $data2["ProfileID"];
								if ($rb_agency_option_profilenaming == 0) {
									$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
								} elseif ($rb_agency_option_profilenaming == 1) {
									$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
								} elseif ($rb_agency_option_profilenaming == 2) {
									$ProfileContactDisplay = $data2["ProfileContactDisplay"];
								} elseif ($rb_agency_option_profilenaming == 3) {
									$ProfileContactDisplay = "ID-". $ProfileID;
								} elseif ($rb_agency_option_profilenaming == 4) {
									$ProfileContactDisplay = $ProfileContactNameFirst;
								} elseif ($rb_agency_option_profilenaming == 5) {
									$ProfileContactDisplay = $ProfileContactNameLast;
								}
								$profileimage .= "<div style=\"color:#020202;float: left; max-width: 100px; height: 175px; margin: 2px; overflow:hidden;  \">";
								$profileimage .= "<div style=\"margin:3px;max-width:250px; max-height:300px; overflow:hidden;\">";								
								$profileimage .= "<a href=\"". RBAGENCY_PROFILEDIR . $data2['ProfileGallery'] ."/\" target=\"_blank\">";
								if(isset($arr_thumbnail[$data2["ProfileID"]])){
									$thumbnail = $wpdb->get_row($wpdb->prepare("SELECT ProfileMediaURL FROM ".table_agency_profile_media." WHERE ProfileMediaID =  %d ", $arr_thumbnail[$data2["ProfileID"]]));
									$profileimage .= "<img style=\"max-width:130px; max-height:150px; \"  \" src=\"". get_bloginfo("siteurl")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $thumbnail->ProfileMediaURL ."&a=t&w=100&h=130\" /></div>\n";
								}else{
									$profileimage .= "<img style=\"max-width:130px; max-height:150px; \" src=\"". get_bloginfo("siteurl")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $data2['ProfileMediaURL'] ."&a=t&w=100&h=130\" /></a>";
								}
								$profileimage .=  $ProfileContactDisplay; //stripslashes($data2['ProfileContactNameFirst']) ." ". stripslashes($data2['ProfileContactNameLast']);
								$profileimage .= "</div>\n";
								$profileimage .= "</div>\n";
							}
							$profileimage .="</div></p>";
		}
			// Mail it
			$headers[]  = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			if(!empty($SearchMuxFromName)){
				$rb_agency_value_agencyname = $SearchMuxFromName;
			}
			if(!empty($SearchMuxFromEmail)){
				$rb_agency_option_agencyemail = $SearchMuxFromEmail;
			}
			
			$headers[] = 'From: "'.$rb_agency_value_agencyname.'" <'. $rb_agency_value_agencyemail .'>';

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
						if(!empty($bcc)){
							$headers[] = 'Bcc: '.$bcc;
						}
				}
			}

			$MassEmailMessage = str_replace("[link-place-holder]",site_url()."/client-view/".$SearchMuxHash."<br/><br/>".$profileimage ."<br/><br/>",$MassEmailMessage);
			$MassEmailMessage	= str_ireplace("[site-url]",get_bloginfo("url"),$MassEmailMessage);
			$MassEmailMessage	= str_ireplace("[site-title]",get_bloginfo("name"),$MassEmailMessage);
			$isSent = wp_mail($MassEmailRecipient, $MassEmailSubject, stripcslashes(make_clickable($MassEmailMessage)), $headers);
			$url = admin_url('admin.php?page=rb_agency_searchsaved&m=1');
			if($isSent){?>
			<script type="text/javascript"> 
				window.location="<?php echo $url;?>";
			</script>
			<?php 
			exit;
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
		   $rb_agency_option_profilenaming = isset($rb_agency_options_arr['rb_agency_option_profilenaming']) ?$rb_agency_options_arr['rb_agency_option_profilenaming']:0;
			
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
			$SearchMuxMessage		= isset($_POST['SearchMuxMessage'])?preg_replace('/[^A-Za-z0-9 !@#$%^&*().\[\]\/\- \s\t\n\r\0\x0B]/u','',$_POST['SearchMuxMessage']):"";
			$SearchMuxMessage 		= preg_replace('/\n(\s*\n)+/', '</p><p>', $SearchMuxMessage);
			$SearchMuxCustomValue	='';
			$cartArray = isset($_SESSION['cartArray'])?$_SESSION['cartArray']:array();
			
			$cartString = implode(",", array_unique($cartArray));
			$cartString = rb_agency_cleanString($cartString);
			$cartProfileMedia = isset($_SESSION["profilephotos"]) ? serialize($_SESSION["profilephotos"]):"";

			
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
								SearchMuxCustomValue,
								SearchMuxCustomThumbnail
								)" .
								"VALUES
								(
								'" . esc_sql($SearchID) . "',
								'" . esc_sql($SearchMuxHash) . "',
								'" . esc_sql($SearchMuxToName) . "',
								'" . esc_sql($SearchMuxToEmail) . "',
								'" . esc_sql($SearchMuxSubject) . "',
								'" . esc_sql($SearchMuxMessage) . "',
								'" . esc_sql($SearchMuxCustomValue) ."',
								'" . $cartProfileMedia . "'
								)";
							$results = $wpdb->query($insert);  
							
			} // end is not resend / is new

							$profileimage = "";  
							$profileimage .='<div id="searchsaved-emailsent" class="searchsaved-profiles">';
							$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE search.SearchID = \"%d\"";
							$data =  $wpdb->get_row($wpdb->prepare($query,$SearchID),ARRAY_A );
							$query = "SELECT * FROM (SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst ASC) as profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".implode(",",array_filter(array_unique(explode(",",$data['SearchProfileID'])))).") GROUP BY(profile.ProfileID)";
							$results = $wpdb->get_results($query,ARRAY_A);
							$count = count($results);
							$arr_thumbnail = (array)unserialize($cartProfileMedia);

							foreach($results as $data2) {
								$ProfileContactNameFirst = $data2["ProfileContactNameFirst"];
								$ProfileContactNameLast = $data2["ProfileContactNameLast"];
								$ProfileID = $data2["ProfileID"];
								if ($rb_agency_option_profilenaming == 0) {
									$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
								} elseif ($rb_agency_option_profilenaming == 1) {
									$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
								} elseif ($rb_agency_option_profilenaming == 2) {
									$ProfileContactDisplay = $data2["ProfileContactDisplay"];
								} elseif ($rb_agency_option_profilenaming == 3) {
									$ProfileContactDisplay = "ID-". $ProfileID;
								} elseif ($rb_agency_option_profilenaming == 4) {
									$ProfileContactDisplay = $ProfileContactNameFirst;
								} elseif ($rb_agency_option_profilenaming == 5) {
									$ProfileContactDisplay = $ProfileContactNameLast;
								}
								$profileimage .= "<div class=\"saved-profile\">";
								$profileimage .= "<div class=\"thumbnail\">\n";
								if(isset($arr_thumbnail[$data2["ProfileID"]])){
									$thumbnail = $wpdb->get_row($wpdb->prepare("SELECT ProfileMediaURL FROM ".table_agency_profile_media." WHERE ProfileMediaID =  %d ", $arr_thumbnail[$data2["ProfileID"]]));
									$profileimage .= "<a href=\"". RBAGENCY_PROFILEDIR . $data2['ProfileGallery'] ."/\" target=\"_blank\">";
									$profileimage .= "<img src=\"". get_bloginfo("siteurl")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $thumbnail->ProfileMediaURL ."&w=110\"/></a>";
								} else {									
									$profileimage .= "<a href=\"". RBAGENCY_PROFILEDIR . $data2['ProfileGallery'] ."/\" target=\"_blank\">";
									$profileimage .= "<img src=\"". get_bloginfo("siteurl")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $data2['ProfileMediaURL'] ."&w=110\"/></a>";
								}
								$profileimage .= "</div>\n";
								$profileimage .= "<span>". $ProfileContactDisplay ."</span>"; //stripslashes($data2['ProfileContactNameFirst']) ." ". stripslashes($data2['ProfileContactNameLast']);								
								$profileimage .= "</div>\n";
							}
							$profileimage .="</div>";
			// Mail it
			$headers[]  = 'MIME-Version: 1.0'. "\r\n";
			$headers[] = 'Content-type: text/html; charset=iso-8859-1'. "\r\n";

			if(!empty($SearchMuxFromName)){
				$rb_agency_value_agencyname = $SearchMuxFromName;
			}
			if(!empty($SearchMuxFromEmail)){
				$rb_agency_option_agencyemail = $SearchMuxFromEmail;
			}
			if(!empty($SearchMuxToName)){
				$SearchMuxToEmail = $SearchMuxToName." <".$SearchMuxToEmail.">";
			}
			
			$headers[]  = 'From: '.$rb_agency_value_agencyname.' <'. $rb_agency_option_agencyemail .'>' . "\r\n";
			
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
			$SearchMuxMessage = $SearchMuxMessage;
			$isSent = wp_mail($SearchMuxToEmail, $SearchMuxSubject,  make_clickable(stripcslashes($SearchMuxMessage)), $headers);

			var_dump(array($headers,$SearchMuxToEmail,$SearchMuxSubject, $SearchMuxSubject, $SearchMuxHash));
			//if($isSent){
				if(!empty($SearchMuxFromEmail)){
					$email_error .= "<div style=\"margin:15px;\">";
					$email_error .= "<div id=\"message\" class=\"updated\">";
					$email_error .= "Email successfully sent from <strong>". $SearchMuxFromEmail ."</strong> to <strong>". $SearchMuxToEmail ."</strong><br />";
					$email_error .= "Message sent: <p>". stripcslashes(make_clickable($SearchMuxMessage)) ."</p>";
					$email_error .= "</div>";
					$email_error .= "</div>";
				} else {
					$email_error .= "<div style=\"margin:15px;\">";
					$email_error .= "<div id=\"message\" class=\"updated\">";
					$email_error .= "Email successfully sent from <strong>". $rb_agency_option_agencyemail ."</strong> to <strong>". $SearchMuxToEmail ."</strong><br />";
					$email_error .= "Message sent: <p>". stripcslashes(make_clickable($SearchMuxMessage)) ."</p>";
					$email_error .= "</div>";
					$email_error .= "</div>";
				}
			/*}else{
				$email_error .= "Error sending email.";
			}*/
			return $email_error ;
		}

	/*
	* Form to Send Casting Cart
	*/

		public static function cart_send_form(){
			global $wpdb;
		
		if(isset($_POST["SendEmail"])){
			// Process Form
			$isSent = RBAgency_Casting::cart_send_process();
			if(isset($isSent)){
				echo "          <div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";
			}
		}

			if(isset($_GET["action"]) && $_GET["action"] == "massEmail"){
				//echo RBAgency_Casting::cart_show();
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
				if($_GET["action"] == "massEmail" && !isset($_GET["SearchID"])){
					$query = "SELECT profile.*  FROM ". table_agency_profile ." profile WHERE profile.ProfileID > 0 ".$cartQuery;
					$results2 = $wpdb->get_results($query,ARRAY_A);
					$count =count($results2);
				}else{
					$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE search.SearchID = \"". $_GET["SearchID"]."\"";
					$data =  $wpdb->get_row($query);
					$profile_list  = (isset($data->SearchProfileID)? implode(",",array_filter( array_unique( explode(",",$data->SearchProfileID) ) ) ):"''");
					$query ="SELECT * FROM (SELECT * FROM ". table_agency_profile ." ORDER BY ProfileContactNameFirst) AS profile, "
							. table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND profile.ProfileID IN ("
							.$profile_list.")  GROUP BY(profile.ProfileID) ";
					$results2 = $wpdb->get_results($query,ARRAY_A);
					$count =count($results2);
				}
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
				echo "        <h3>". __("Compose Email", RBAGENCY_TEXTDOMAIN) ."</h3>\n";
				echo "        <div class=\"inner\">\n";
				
				echo "          <strong>Recipient:</strong><br/><textarea name=\"MassEmailRecipient\" style=\"width:100%;\">".$rb_agency_value_agencyemail."</textarea><br/>";
				echo "          <strong>Bcc:</strong><br/><textarea name=\"MassEmailBccEmail\" style=\"width:100%;\">".$recipient."</textarea><br/>";
				echo "          <strong>Subject:</strong> <br/><input type=\"text\" name=\"MassEmailSubject\" style=\"width:100%\"/>";
				echo "          <br/>";
			/*	echo "          <strong>Message:</strong><br/>     <textarea name=\"MassEmailMessage\"  style=\"width:100%;height:300px;\">this message was sent to you by ".$rb_agency_value_agencyname." ".network_site_url( '/' )."</textarea>";*/
				//Adding Wp editor
				$content = "";
				if(!isset($_GET["SearchID"])){
								$content = "Add Message Here<br />Click the following link (or copy and paste it into your browser):<br />
											[link-place-holder]<br />This message was sent to you by:<br />[site-title]<br />[site-url]";
				}
				$editor_id = 'MassEmailMessage';
				wp_editor( $content, $editor_id,array("wpautop"=>false,"tinymce"=>true) );
				
				echo "          <input type=\"submit\" value=\"". __("Send Email", RBAGENCY_TEXTDOMAIN) . "\" name=\"SendEmail\"class=\"button-primary\" />\n";
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