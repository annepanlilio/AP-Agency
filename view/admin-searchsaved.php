<?php

$siteurl = get_option('siteurl');
	// Casting Class
	include(rb_agency_BASEREL ."app/casting.class.php");
	include(rb_agency_BASEREL ."ext/easytext.php");

	global $wpdb;

	// Get Options
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_agencyname		= $rb_agency_options_arr['rb_agency_option_agencyname'];
		$rb_agency_option_agencyemail	= $rb_agency_options_arr['rb_agency_option_agencyemail'];
		$rb_agency_option_agencyheader	= $rb_agency_options_arr['rb_agency_option_agencyheader'];

	// Declare Hash
	$SearchMuxHash			=  isset($_GET["SearchMuxHash"])?$_GET["SearchMuxHash"]:""; // Set Hash

	if (isset($_POST['action'])) {

		$SearchID			=$_POST['SearchID'];
		$SearchTitle		=$_POST['SearchTitle'];
		$SearchType			=$_POST['SearchType'];
		$SearchProfileID	=$_POST['SearchProfileID'];
		$SearchOptions		=$_POST['SearchOptions'];

		// What is action?
		$action = $_POST['action'];

		switch($action) {

			// Add
			case 'addRecord':

				// Ensure a Title is Created
				if (!empty($SearchTitle)) {

					// Create Record
					$insert = "INSERT INTO " . table_agency_searchsaved . " (
						SearchTitle,
						SearchType,
						SearchProfileID,
						SearchOptions
					)" . "VALUES (
						'" . $wpdb->escape($SearchTitle) . "',
						'" . $wpdb->escape($SearchType) . "',
						'" . $wpdb->escape($SearchProfileID) . "',
						'" . $wpdb->escape($SearchOptions) . "'
					)";

					$results = $wpdb->query($insert);
					$lastid = $wpdb->insert_id;

					echo '<div id="message" class="updated"><p>Search saved successfully! <a href="'. admin_url("admin.php?page=". $_GET['page']) .'&action=emailCompose&SearchID='. $lastid .'&SearchMuxHash='.RBAgency_Common::generate_random_string(8).'">Send Email</a></p></div>'; 

				} else {

					echo ('<div id="message" class="error"><p>Error creating record, please ensure you have filled out all required fields.</p></div>'); 
				}

			break;


			// Delete bulk
			case 'deleteRecord':

				foreach($_POST as $SearchID) {
					if($SearchID !="deleteRecord" &&  $SearchID !="Delete"){
						$wpdb->query("DELETE FROM " . table_agency_searchsaved . " WHERE SearchID=$SearchID");
					}
				}

				echo ('<div id="message" class="updated"><p>Profile deleted successfully!</p></div>');

			break;


			// Email
			case 'emailSend':

				if (!empty($SearchID)) {
					echo RBAgency_Casting::cart_email_send_process();
				}

			break;

		}


	} elseif (isset($_GET['action']) && $_GET['action'] == "deleteRecord") {
	/* 
	 * Delete Email
	 */

		$SearchID = $_GET['SearchID'];

		// Verify Record
		$queryDelete = "SELECT * FROM ". table_agency_searchsaved ." WHERE SearchID =  \"". $SearchID ."\"";
		$resultsDelete = $wpdb->get_results($wpdb->prepare($queryDelete), ARRAY_A);

		foreach ($resultsDelete as $dataDelete) {
			// Remove Casting
			$delete = "DELETE FROM " . table_agency_searchsaved . " WHERE SearchID = \"". $SearchID ."\"";
			$results = $wpdb->query($delete);
			echo ('<div id="message" class="updated"><p>Record deleted successfully!</p></div>');
		}
    } elseif ((isset($_GET['action']) && $_GET['action'] == "emailCompose") && isset($_GET['SearchID'])) {
	/* 
	 * Compose Email
	 */

		$SearchID = $_GET['SearchID'];

		$dataSearchSavedMux = $wpdb->get_row("SELECT * FROM " . table_agency_searchsaved_mux ." WHERE SearchID=".$SearchID." ", ARRAY_A ,0);

		?>
		<div style="width:500px; float:left;">
		 <h2><?php echo __("Search Saved", rb_agency_TEXTDOMAIN); ?></h2>
		  <form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=". $_GET['page'])."&SearchID=".$_GET['SearchID']."&SearchMuxHash=".$_GET["SearchMuxHash"]; ?>">
		   <input type="hidden" name="action" value="cartEmail" />
		   <div><label for="SearchMuxToEmail"><strong>From Name:(Leave as blank to use admin name)</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxFromName" name="SearchMuxFromName" value="<?php echo $dataSearchSavedMux["SearchMuxToName"]; ?>" /></div>
		   <div><label for="SearchMuxToEmail"><strong>From Email:(Leave as blank to use admin email)</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxFromEmail" name="SearchMuxFromEmail" value="<?php echo $dataSearchSavedMux["SearchMuxToEmail"]; ?>" /></div>
		   <div><label for="SearchMuxToName"><strong>Send to Name:</strong></label><br/><input style="width:300px;" type="text" id="SearchMuxToName" name="SearchMuxToName" value="<?php echo $dataSearchSavedMux["SearchMuxToName"]; ?>" /></div>
		   <div><label for="SearchMuxToEmail"><strong>Send to Email:</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxToEmail" name="SearchMuxToEmail" value="<?php echo $dataSearchSavedMux["SearchMuxToEmail"]; ?>" /></div>
		   
		   <div><label for="SearchMuxBccEmail"><strong>Bcc:</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxBccEmail" name="SearchMuxBccEmail" value="" /></div>
		   
		   <div><label for="SearchMuxSubject"><strong>Subject:</strong></label><br/><input  style="width:300px;" type="text" id="SearchMuxSubject" name="SearchMuxSubject" value="<?php echo $rb_agency_option_agencyname; ?> Casting Cart" /></div>
		   <div><label for="SearchMuxMessage"><strong>Message: (copy/paste: [link-place-holder] )</strong></label><br/>
			<textarea id="SearchMuxMessage" name="SearchMuxMessage" style="width: 500px; height: 300px; "><?php if(!isset($_GET["SearchMuxHash"])){ echo @$dataSearchSavedMux["SearchMuxMessage"];}else{echo @"Click the following link (or copy and paste it into your browser): [link-place-holder]";} ?></textarea>
			</div>
		   <p class="submit">
			   <input type="hidden" name="SearchID" value="<?php echo $SearchID; ?>" />
			   <input type="hidden" name="action" value="emailSend" />
			   <input type="submit" name="submit" value="Send Email" class="button-primary" />
		   </p>

		  </form>
		</div>
		<?php

		$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE search.SearchID = \"". $_GET["SearchID"]."\"";
		/*
		TODO: CLeanup
		$SearchMuxHash = $dataSearchSavedMux["SearchMuxHash"];
		$query = "SELECT search.SearchTitle, search.SearchProfileID, search.SearchOptions, searchsent.SearchMuxHash FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = searchsent.SearchID WHERE searchsent.SearchMuxHash = \"". $SearchMuxHash ."\"";
	  	*/
		$data =  $wpdb->get_row($query);
		$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$data['SearchProfileID'].") ORDER BY ProfileContactNameFirst ASC";
		$results = $wpdb->get_results($wpdb->prepare($query), ARRAY_A);
		$count = $wpdb->num_rows;

		 ?>
		<div style="padding:10px;max-width:580px;float:left;">
			<b>Preview: <?php echo  $count." Profile(s)"; ?></b>
				<div style="height:550px; width:580px; overflow-y:scroll;">
					<?php
					foreach ($results as $data2 ) {
					echo " <div style=\"background:black; color:white;float: left; max-width: 100px; height: 180px; margin: 2px; overflow:hidden;  \">";
					echo " <div style=\"margin:3px;max-width:250px; max-height:300px; overflow:hidden;\">";
					echo stripslashes($data2['ProfileContactNameFirst']) ." ". stripslashes($data2['ProfileContactNameLast']);
					echo "<br /><a href=\"". rb_agency_PROFILEDIR . $data2['ProfileGallery'] ."/\" target=\"_blank\">";
					echo "<img style=\"max-width:130px; max-height:150px; \" src=\"". rb_agency_UPLOADDIR ."". $data2['ProfileGallery'] ."/". $data2['ProfileMediaURL'] ."\" /></a>";
					echo "</div>\n";
					echo "</div>\n";
					}
					?>
				</div>
		</div>
		<?php

	}/*
	 * Display Inform Talent
	 */
		elseif(isset($_GET["action"]) && $_GET['action'] == "informTalent"){
      ?>
    

	<div style="clear:both"></div>

		<div class="wrap" style="min-width: 1020px;">
		 <div id="rb-overview-icon" class="icon32"></div>
		 <h2>Casting Jobs</h2>
		 <?php 
		  if(isset($_POST["action2"]) && $_POST["action2"] =="add"){
          	   	if (isset($_SESSION['cartArray'])) {

									$cartArray = $_SESSION['cartArray'];
									$cartString = implode(",", array_unique($cartArray));
									$cartString = RBAgency_Common::clean_string($cartString);
									$hash = RBAgency_Common::generate_random_string(20,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

									$sql = "INSERT INTO ".table_agency_castingcart_jobs." 
										VALUES(
											'',
											'".esc_attr($_POST["audition"])."',
											'".esc_attr($_POST["role"])."',
											'".esc_attr($_POST["auditiondates"])."',
											'".esc_attr($_POST["auditionvenue"])."',
											'".esc_attr($_POST["auditiontime"])."',
											'".esc_attr($_POST["auditionclothing"])."',
											'".esc_attr($_POST["callback"])."',
											'".esc_attr($_POST["script"])."',
											'".esc_attr($_POST["shootdate"])."',
											'".esc_attr($_POST["rolefee"])."',
											'".esc_attr($_POST["comments"])."',
											'".esc_attr($_POST["selectedfor"])."',
											'".date("Y-m-d h:i:s")."',
											'".$cartString."',
											'".$hash."'
											)
									";
									$wpdb->query($sql);
									$results = $wpdb->get_results("SELECT ProfileContactPhoneCell FROM ".table_agency_profile." WHERE ProfileID IN(".$cartString.")",ARRAY_A);
									$arr_mobile_numbers = array();
									foreach($results as $mobile){
										array_push($arr_mobile_numbers, $mobile["ProfileContactPhoneCell"]);
									}
									RBAgency_CastingSMS::sendText($arr_mobile_numbers,get_bloginfo("wpurl")."/profile-casting/jobs/".$hash);

											echo ('<div id="message" class="updated"><p>Added successfully!</p></div>');
	
				}else{
					echo "No profiles selected in Casting cart.";
				}
          }elseif(isset($_POST["action2"]) && $_POST["action2"] =="edit"){

									$sql = "UPDATE ".table_agency_castingcart_jobs." 
										 SET
											CastingJobAudition = '".esc_attr($_POST["audition"])."',
											CastingJobRole = '".esc_attr($_POST["role"])."',
											CastingJobAuditionDate = '".esc_attr($_POST["auditiondates"])."',
											CastingJobAuditionVenue = '".esc_attr($_POST["auditionvenue"])."',
											CastingJobAuditionTime = '".esc_attr($_POST["auditiontime"])."',
											CastingJobClothing = '".esc_attr($_POST["auditionclothing"])."',
											CastingJobRCallBackWardrobe = '".esc_attr($_POST["callback"])."',
											CastingJobScript = '".esc_attr($_POST["script"])."',
											CastingJobShootDate = '".esc_attr($_POST["shootdate"])."',
											CastingJobRoleFee = '".esc_attr($_POST["rolefee"])."',
											CastingJobComments = '".esc_attr($_POST["comments"])."',
											CastingJobSelectedFor = '".esc_attr($_POST["selectedfor"])."'
											WHERE CastingJobID = ".esc_attr($_GET["CastingJobID"])."
									";

									$wpdb->query($sql);

                              if(isset($_POST["resend"])){
									$results = $wpdb->get_results("SELECT ProfileContactPhoneCell FROM ".table_agency_profile." WHERE ProfileID IN(".$_POST["profileselected"].")",ARRAY_A);
									$arr_mobile_numbers = array();
									
									foreach($results as $mobile){
										array_push($arr_mobile_numbers, $mobile["ProfileContactPhoneCell"]);
									}

									RBAgency_CastingSMS::sendText($arr_mobile_numbers,get_bloginfo("wpurl")."/profile-casting/jobs/".$_POST["CastingJobHash"]);
							  }
											echo ('<div id="message" class="updated"><p>Updated successfully!</p></div>');
	

          }elseif(isset($_GET["action2"]) && $_GET["action2"] == "deleteCastingJob"){
          	       $wpdb->query("DELETE FROM ".table_agency_castingcart_jobs." WHERE CastingJobID = '".$_GET["removeCastingJobID"]."'");
          	       echo ('<div id="message" class="updated"><p>Deleted successfully!</p></div>');
          }
			$CastingJobAudition = ""; 
			$CastingJobRole = "";
			$CastingJobAuditionDate = "";
			$CastingJobAuditionVenue = "";
			$CastingJobAuditionTime = "";
			$CastingJobClothing = "";
			$CastingJobRCallBackWardrobe = "";
			$CastingJobScript = "";
			$CastingJobShootDate = "";
			$CastingJobRoleFee = "";
			$CastingJobComments = "";
			$CastingJobSelectedFor = "";
			$CastingJobDateCreated = "";
			$CastingJobTalents = "";
			$CastingJobHash = "";

		   if(isset($_GET["CastingJobID"])){
		 	
		 	$sql =  "SELECT * FROM ".table_agency_castingcart_jobs." WHERE CastingJobID= %d ";
		 	$data = $wpdb->get_results($wpdb->prepare($sql, $_GET["CastingJobID"]));
		 	$data = current($data);
 
		 	$CastingJobAudition = $data->CastingJobAudition; 
			$CastingJobRole = $data->CastingJobRole;
			$CastingJobAuditionDate = $data->CastingJobAuditionDate;
			$CastingJobAuditionVenue = $data->CastingJobAuditionVenue;
			$CastingJobAuditionTime = $data->CastingJobAuditionTime;
			$CastingJobClothing = $data->CastingJobClothing;
			$CastingJobRCallBackWardrobe = $data->CastingJobRCallBackWardrobe;
			$CastingJobScript = $data->CastingJobScript;
			$CastingJobShootDate = $data->CastingJobShootDate;
			$CastingJobRoleFee = $data->CastingJobRoleFee;
			$CastingJobComments = $data->CastingJobComments;
			$CastingJobSelectedFor = $data->CastingJobSelectedFor;
			$CastingJobDateCreated = $data->CastingJobDateCreated;
			$CastingJobTalents = $data->CastingJobTalents;
			$CastingJobHash = $data->CastingJobTalentsHash;
		 }
         
		 ?>


      <?php 
		echo "<style type=\"text/css\">\n
				.castingtext label{
					float: left;
					margin-top: 5px;
					margin-right: 20px;
					width:140px;
				}
				.castingtext input[type=text], .castingtext textarea{
					width:60%;
				}
				</style> ";
				 echo "<div class=\"boxblock-container\" style=\"float: left; width: 39%;margin-right:60px\">";
				 echo "<div class=\"boxblock\" style=\"width:500px; \">";
				 if(isset($_GET["CastingJobID"])){
					echo "<h3>Edit Talent Jobs</h3>";
				 }else{
				 	echo "<h3>Talent Jobs</h3>";
				}
				 echo "<div class=\"innerr\" style=\"padding: 10px;\">";
				 echo "<form class=\"castingtext\" method=\"post\" action=\"\">";
				   echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"audition\">Audition</label>";
						echo "<div><input type=\"text\" id=\"audition\" name=\"audition\" value=\"".$CastingJobAudition."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"role\">Role</label>";
						echo "<div><input type=\"text\" id=\"role\" name=\"role\" value=\"".$CastingJobRole."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"auditiondates\">Audition Dates</label>";
						echo "<div><input type=\"text\" id=\"auditiondates\" name=\"auditiondates\" value=\"".$CastingJobAuditionDate."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"auditionvenue\">Audition Venue</label>";
						echo "<div><input type=\"text\" id=\"auditionvenue\" name=\"auditionvenue\" value=\"".$CastingJobAuditionVenue."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"auditiontime\">Audition Time</label>";
						echo "<div><input type=\"text\" id=\"auditiontime\" name=\"auditiontime\" value=\"".$CastingJobAuditionTime."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"auditionclothing\">Audition Clothing</label>";
						echo "<div><input type=\"text\" id=\"auditionclothing\" name=\"auditionclothing\" value=\"".$CastingJobClothing."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"callback\">Call Back / Wardrobe</label>";
						echo "<div><input type=\"text\" id=\"callback\" name=\"callback\" value=\"".$CastingJobRCallBackWardrobe."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"script\">Script</label>";
						echo "<div><input type=\"text\" id=\"script\" name=\"script\" value=\"".$CastingJobScript."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"shortdate\">Shoot Date</label>";
						echo "<div><input type=\"text\" id=\"shootdate\" name=\"shootdate\" value=\"".$CastingJobShootDate."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"rolefee\">Role Fee($)</label>";
						echo "<div><input type=\"text\" id=\"rolefee\" name=\"rolefee\" value=\"".$CastingJobRoleFee."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"comments\">Comments</label>";
						echo "<div><textarea id=\"comments\" name=\"comments\">".$CastingJobComments."</textarea></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"comments\">Selected For</label>";
						echo "<div>";
						echo "<select name=\"selectedfor\">";
						echo "<option>Client to Confirm</option>";
						echo "</select>";
						echo "</div>";
					echo "</div>";
                    if(isset($_GET["CastingJobID"])){
                    	echo "<input type=\"checkbox\" name=\"resend\" value=\"1\"/> Resend notifcation to selected talents \n\n<br/><br/>";
                    	echo "<input type=\"submit\" value=\"Save\" name=\"castingJob\" class=\"button-primary\" />";
                    	echo "<input type=\"hidden\" name=\"action2\" value=\"edit\"/>";
                    	echo "<input type=\"hidden\" name=\"profileselected\" value=\"".$CastingJobTalents."\"/>";
                    	echo "<input type=\"hidden\" name=\"CastingJobHash\" value=\"".$CastingJobHash."\"/>";
                    	echo "<a href=\"".admin_url("admin.php?page=". $_GET['page'])."&amp;action=informTalent\" class=\"button\">Cancel</a>";


                    }else{
						echo "<input type=\"hidden\" name=\"action2\" value=\"add\"/>";
                    	echo "<input type=\"submit\" value=\"Submit\" name=\"castingJob\" class=\"button-primary\" />";
                    	echo "<a href=\"".admin_url("admin.php?page=rb_agency_search")."\" class=\"button\">Cancel</a>";


                    }
				  	
				  	
				  echo "</form>";
				  echo "</div>";
				  echo "</div>";
                 
                 $cartArray = null;
				// Set Casting Cart Session
				if (isset($_SESSION['cartArray']) && !isset($_GET["CastingJobID"])) {

					$cartArray = $_SESSION['cartArray'];
			     }elseif(isset($_GET["CastingJobID"])){
			     	$cartArray = explode(",",$CastingJobTalents);
				
				} else{
			    		echo "Session expired. Please search again.";


			    }
			    ?>
                 <script type="text/javascript">
                 jQuery(document).ready(function(){
                 	 var arr = [];
	                 	
	                 jQuery("#selectall").click(function(){
	                 	 var ischecked = jQuery(this).is(':checked');
	                 	 	jQuery("#shortlisted input[name=profiletalent]").each(function(i,d){
	                 			if(ischecked){
	                 			 jQuery(this).removeAttr("checked");
	                 			 jQuery(this).attr("checked",true);
	                 			  arr.push(jQuery(this).val());
	                 			}else{
	                 		     jQuery(this).attr("checked",true);
	                 			 jQuery(this).removeAttr("checked");
	                 			 arr = [];
	                 			}
	                 		});
	                 		jQuery("input[name=profileselected]").val(arr.toString());
	                 });
	                 Array.prototype.remove = function(value) {
					  var idx = this.indexOf(value);
					  if (idx != -1) {
					      return this.splice(idx, 1); 
					  }
					  return false;
					}
	           
		             jQuery("#shortlisted input[name^=profiletalent]").click(function(){
		             				if(jQuery(this).is(':checked')){
		                 				arr.push(jQuery(this).val());
		                 			}else{
		                 				arr.remove(jQuery(this).val());
		                 			}
		                 		
		                 		jQuery("input[name=profileselected]").val(arr.toString());
		             });
	               });
                 </script>
 
			    <?php

			    if(isset($cartArray )){

						 echo "<div id=\"shortlisted\" class=\"boxblock-container\" style=\"float: left; width: 39%;\">";
						 echo "<div class=\"boxblock\" style=\"width:490px; \">";
						 echo "<h3>Talents Shortlisted";
						 echo "<span style=\"font-size:12px;float:right;\">".(isset($_GET["CastingJobID"])?"<input type=\"checkbox\" id=\"selectall\"/>Select all talents</span>":"")."</h3>";
						 echo "<div class=\"innerr\" style=\"padding: 10px;\">";
						
					?>
					


								<?php
										   
									$cartString = implode(",", array_unique($cartArray));
									$cartString = RBAgency_Common::clean_string($cartString);

								// Show Cart  
								$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") ORDER BY profile.ProfileContactNameFirst ASC";
								$results = $wpdb->get_results($wpdb->prepare($query,$cartString), ARRAY_A);

												$count = $wpdb->num_rows;

								

								foreach ($results as $data) {
									echo "<div style=\"width: 48.5%;float:left\">";
									echo "<div style=\"height: 200px; margin-right: 5px; overflow: hidden; \"><span style=\"text-align:center;background:#ccc;color:#000;font-weight:bold;width:100%;padding:10px;display:block;\">".(isset($_GET["CastingJobID"])?"<input type=\"checkbox\" name=\"profiletalent\" value=\"".$data["ProfileID"]."\"/>":""). stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</span><a href=\"". rb_agency_PROFILEDIR . $data['ProfileGallery'] ."/\" target=\"_blank\"><img style=\"width: 100%; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></a>";
									echo "</div>\n";
									if(isset($_GET["CastingJobID"])){
										$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
										$prepared = $wpdb->prepare($query,$data["ProfileID"],$_GET["CastingJobID"]);
										$availability = current($wpdb->get_results($prepared));
										
										$count = $wpdb->num_rows;

										if($count <= 0){
											echo "<span style=\"text-align:center;color:#5505FF;font-weight:bold;width:80%;padding:10px;display:block;\">Unconfirmed</span>\n";
										}else{
										   if($availability->status == "available"){
										    echo "<span style=\"text-align:center;color:#2BC50C;font-weight:bold;width:80%;padding:10px;display:block;\">Available</span>\n";
											}else{
											echo "<span style=\"text-align:center;color:#EE0F2A;font-weight:bold;width:80%;padding:10px;display:block;\">Not Available</span>\n";
											}
										}
									}
									echo "</div>\n";
									

								}

								?>
							

					<?php
			  
				    echo "<div style=\"clear:both;\"></div>";
					echo "</div>";
				    echo "</div>";
				  echo "</div>";
				}
		?>
        </div>		
        <div style="float:left;width:50%;margin-left: 20px;">
	          		<h3 class="title">Recently Saved Jobs</h3>

			<?php  
			$sqldata = "";
			$query = "";

			if(isset($_REQUEST["m"]) && $_REQUEST['m'] == '1' ) {
				// Message of successful mail form mass email 
				echo "<div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";
			}

			$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_locationtimezone = (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];

			// Sort By
			$sort = "";
			if (isset($_GET['sort']) && !empty($_GET['sort'])){
				$sort = $_GET['sort'];
			} else {
				$sort = "jobs.CastingJobDateCreated";
			}

			// Sort Order
			$dir = "";
			if (isset($_GET['dir']) && !empty($_GET['dir'])){
				$dir = $_GET['dir'];
				if ($dir == "desc" || !isset($dir) || empty($dir)){
					$sortDirection = "desc";
				} else {
					$sortDirection = "desc";
				}
			} else {
				$sortDirection = "desc";
				$dir = "desc";
			}

			// Filter
			$filter = "WHERE jobs.CastingJobID > 0 ";
			if (isset($_GET['SearchTitle']) && !empty($_GET['SearchTitle'])){
				$selectedTitle = isset($_GET['SearchTitle'])?$_GET['SearchTitle']:"";
				$query .= "&CastingJobAudition=". $selectedTitle ."";
				$filter .= " AND jobs.CastingJobAudition='". $selectedTitle ."'";
			}

			//Paginate
			$sqldata  = "SELECT jobs.*,talents.* FROM ". table_agency_castingcart_jobs ." jobs LEFT JOIN ". table_agency_castingcart_availability ." talents ON jobs.CastingJobID = talents.CastingJobID ". $filter  .""; // number of total rows in the database
			$results=  $wpdb->get_results($sqldata);
			
			$items =$wpdb->num_rows; // number of total rows in the database
			if($items > 0) {

				$p = new rb_agency_pagination;
				$p->items($items);
				$p->limit(50); // Limit entries per page
				$p->target("admin.php?page=". (isset($_GET['page'])?$_GET['page']:"") .$query);
				@$p->currentPage(isset($_GET[$p->paging])?$_GET[$p->paging]:0); // Gets and validates the current page
				$p->calculate(); // Calculates what to show
				$p->parameterName('paging');
				$p->adjacents(1); //No. of page away from the current page

				if(!isset($_GET['paging'])) {
					$p->page = 1;
				} else {
					$p->page = $_GET['paging'];
				}

				//Query for limit paging

				$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

			} else {
				$limit = "";
			}

			?>
			

			<table cellspacing="0" class="widefat fixed">
				<thead>
					<tr>
						<td style="width: 360px;" nowrap="nowrap">
							<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;action=informTalent">
							 <input type='hidden' name='page_index' id='page_index' value='<?php echo isset($_GET['page_index'])?$_GET['page_index']:""; ?>' />  
							 Search by : 
							 Title: <input type="text" name="SearchTitle" value="<?php echo isset($SearchTitle)?$SearchTitle:""; ?>" style="width: 100px;" />
								<input type="submit" value="Filter" class="button-primary" />
								 <input type="hidden" name="action" value="informTalent"/>
								  <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />
							 
							
							</form>
						</td>
						<td style="width: 200px;" nowrap="nowrap">
							<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
							 <input type='hidden' name='page_index' id='page_index' value='<?php echo isset($_GET['page_index'])?$_GET['page_index']:""; ?>' />  
							 <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />
							 <input type="submit" value="Clear Filters" class="button-secondary" />
							 <input type="hidden" name="action" value="informTalent"/>
							</form>
						</td>
						<td>&nbsp;</td>
					</tr>
			</thead>
			</table>

			<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>" style="width: 602px;">	
			<table cellspacing="0" class="widefat fixed">
			<thead>
				<tr class="thead">
					<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
					<th class="column" scope="col" style="width:50px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchID&dir=<?php echo $sortDirection; ?>">ID</a></th>
					<th class="column" scope="col" style="width:200px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchTitle&dir=<?php echo $sortDirection; ?>">Title</a></th>
					<th class="column" scope="col" style="width:80px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchDate&dir=<?php echo $sortDirection; ?>">Profiles</a></th>
					<th class="column" scope="col">Date Created</th>
				</tr>
			</thead>
			<tfoot>
				<tr class="thead">
					<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
					<th class="column" scope="col">ID</th>
					<th class="column" scope="col">Title</th>
					<th class="column" scope="col">Profiles</th>
					<th class="column" scope="col">Date Created</th>
				</tr>
			</tfoot>
			<tbody>

			<?php

			$query2 = "SELECT jobs.* FROM ". table_agency_castingcart_jobs ." jobs ". $filter  ." ORDER BY $sort $dir $limit";
		
			$results2 = $wpdb->get_results($query2, ARRAY_A);
			$count2 = $wpdb->num_rows;

			foreach ($results2 as $data2) {
				$CastingJobAudition = stripslashes($data2['CastingJobAudition']);
				$CastingJobID = stripslashes($data2['CastingJobID']);
				$CastingShortlistedProfile = stripslashes($data2['CastingJobTalents']);
				$CastingShortlistedProfile = explode(",",str_replace("NULL","",$CastingShortlistedProfile));
				
			?>
			<tr>
				<th class="check-column" scope="row">
					<input type="checkbox" value="<?php echo $CastingJobID; ?>" class="administrator" id="<?php echo $CastingJobID; ?>" name="<?php echo $CastingJobID; ?>"/>
				</th>
				<td>
					<?php echo $CastingJobID; ?>
				</td>
				<td>
					<?php echo $CastingJobAudition; ?>
					<div class="row-actions">
					
							<span class="send"><a href="admin.php?page=<?php echo $_GET['page']; ?>&action=informTalent&CastingJobID=<?php echo $CastingJobID; ?>">Edit</a> | </span>
				
							<span class="delete"><a class='submitdelete' title='Delete this Record' href='<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;action=informTalent&amp;action2=deleteCastingJob&amp;removeCastingJobID=<?php echo $CastingJobID; ?>' onclick="if ( confirm('You are about to delete this record\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
					</div>
				</td>
				<td>
					<?php  echo count($CastingShortlistedProfile ); ?>
				</td>
				<td>
					<?php echo date("M d, Y - h:iA",strtotime($data2["CastingJobDateCreated"]));?>
				</td>
			</tr>
			<?php
			}
			//	mysql_free_result($results2);
				if ($count2 < 1) {
					if (isset($filter)) { 
			?>
			<tr>
				<th class="check-column" scope="row"></th>
				<td class="name column-name" colspan="3">
					<p>No profiles found with this criteria.</p>
				</td>
			</tr>
			<?php
					} else {
			?>
			<tr>
				<th class="check-column" scope="row"></th>
				<td class="name column-name" colspan="3">
					<p>There aren't any Profiles loaded yet!</p>
				</td>
			</tr>
			<?php
					}
			?>
			<?php } ?>
			</tbody>
		</table>
		<?php if($items > 0) { ?>
		<div class="tablenav">
			<div class='tablenav-pages'>
				<?php 
				
					echo $p->show();  // Echo out the list of paging. 
				?>
			</div>
		</div>
		<?php } ?>
	</div>

		<?php 		  

	} else {
	/* 
	 * View
	 */

		?>
		<div style="clear:both"></div>

		<div class="wrap" style="min-width: 1020px;">
		 <div id="rb-overview-icon" class="icon32"></div>
		 <h2>Profile Search</h2>

			<?php

			if (isset($_GET["action"]) && $_GET["action"] == "searchSave") { // Add to Cart

				// Set Casting Cart Session
				if (isset($_SESSION['cartArray'])) {

					$cartArray = $_SESSION['cartArray'];
					$cartString = ltrim(implode(",", array_unique($cartArray)),",");

					?>
					<h3 class="title">Save Search and Email</h3>


				   <form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
				   <table class="form-table">
				   <tbody>
					   <tr valign="top">
						   <th scope="row">Group Title:</th>
						   <td>
							   <input type="text" id="SearchTitle" name="SearchTitle" value="<?php echo isset($CastingCompany)?$CastingCompany:""; ?>" />
						   </td>
					   </tr>
					   <tr valign="top">
						   <th scope="row">Profiles:</th>
						   <td>

								<?php
										   
								$query = "SELECT * FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (%s) ORDER BY ProfileContactNameFirst ASC";
								$results = $wpdb->get_results($wpdb->prepare($query,$cartString), ARRAY_A);

								$count = $wpdb->num_rows;

								

								foreach ($results as $data) {

									echo " <div style=\"float: left; width: 80px; height: 100px; margin-right: 5px; overflow: hidden; \">". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "<br /><a href=\"". rb_agency_PROFILEDIR . $data['ProfileGallery'] ."/\" target=\"_blank\"><img style=\"width: 80px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></a></div>\n";

								}

								?>
							<input type="hidden" name="SearchProfileID" value="<?php echo $cartString; ?>" />
						   </td>
					   </tr>
					   </tbody>
				   </table>
				   <p class="submit">
					Click Save to get the email code<br />
					   <input type="hidden" name="action" value="addRecord" />
					   <input type="submit" name="Submit" value="Save Search" class="button-primary" />
				   </p>
				   </form>

					<hr />

					<?php
			   } else {

					echo "Session expired. Please search again.";

				}
		   } // End Serach Save


	?>
  <div style="clear:both"></div>
		<h3 class="title">Recently Saved Searches</h3>

		<?php  
		$sqldata = "";

		if(isset($_REQUEST["m"]) && $_REQUEST['m'] == '1' ) {
			// Message of successful mail form mass email 
			echo "<div id=\"message\" class=\"updated\"><p>Email Messages successfully sent!</p></div>";
		}

		$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_locationtimezone = (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];

		// Sort By
		$sort = "";
		if (isset($_GET['sort']) && !empty($_GET['sort'])){
			$sort = $_GET['sort'];
		} else {
			$sort = "search.SearchDate";
		}

		// Sort Order
		$dir = "";
		if (isset($_GET['dir']) && !empty($_GET['dir'])){
			$dir = $_GET['dir'];
			if ($dir == "desc" || !isset($dir) || empty($dir)){
				$sortDirection = "desc";
			} else {
				$sortDirection = "desc";
			}
		} else {
			$sortDirection = "desc";
			$dir = "desc";
		}

		// Filter
		$filter = "WHERE search.SearchID > 0 ";
		if (isset($_GET['SearchTitle']) && !empty($_GET['SearchTitle'])){
			$selectedTitle = $_GET['SearchTitle'];
			$query .= "&SearchTitle=". $selectedTitle ."";
			$filter .= " AND search.SearchTitle='". $selectedTitle ."'";
		}

		//Paginate
		$sqldata  = "SELECT * FROM ". table_agency_searchsaved ." search LEFT JOIN ". table_agency_searchsaved_mux ." searchsent ON search.SearchID = search.SearchID ". $filter  .""; // number of total rows in the database
		$results=  $wpdb->get_results($sqldata);
		
		$items =$wpdb->num_rows; // number of total rows in the database
		if($items > 0) {

			$p = new rb_agency_pagination;
			$p->items($items);
			$p->limit(50); // Limit entries per page
			$p->target("admin.php?page=". (isset($_GET['page'])?$_GET['page']:"") .$query);
			$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
			$p->calculate(); // Calculates what to show
			$p->parameterName('paging');
			$p->adjacents(1); //No. of page away from the current page

			if(!isset($_GET['paging'])) {
				$p->page = 1;
			} else {
				$p->page = $_GET['paging'];
			}

			//Query for limit paging

			$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

		} else {
			$limit = "";
		}

		?>
		<div class="tablenav">
			<div class='tablenav-pages'>
				<?php
				if($items > 0) {
					echo $p->show();  // Echo out the list of paging. 
				}
				?>
			</div>
		</div>

		<table cellspacing="0" class="widefat fixed">
			<thead>
				<tr>
					<td style="width: 360px;" nowrap="nowrap">
						<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
						 <input type='hidden' name='page_index' id='page_index' value='<?php echo isset($_GET['page_index'])?$_GET['page_index']:""; ?>' />  
						 Search by : 
						 Title: <input type="text" name="SearchTitle" value="<?php echo isset($SearchTitle)?$SearchTitle:""; ?>" style="width: 100px;" />
							<input type="submit" value="Filter" class="button-primary" />
						</form>
					</td>
					<td style="width: 300px;" nowrap="nowrap">
						<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
						 <input type='hidden' name='page_index' id='page_index' value='<?php echo isset($_GET['page_index'])?$_GET['page_index']:""; ?>' />  
						 <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />
						 <input type="submit" value="Clear Filters" class="button-secondary" />
						</form>
					</td>
					<td>&nbsp;</td>
				</tr>
		</thead>
		</table>

		<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">	
		<table cellspacing="0" class="widefat fixed">
		<thead>
			<tr class="thead">
				<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
				<th class="column" scope="col" style="width:50px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchID&dir=<?php echo $sortDirection; ?>">ID</a></th>
				<th class="column" scope="col" style="width:200px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchTitle&dir=<?php echo $sortDirection; ?>">Title</a></th>
				<th class="column" scope="col" style="width:80px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=SearchDate&dir=<?php echo $sortDirection; ?>">Profiles</a></th>
				<th class="column" scope="col">History (Sent/To/Link)</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
				<th class="column" scope="col">ID</th>
				<th class="column" scope="col">Title</th>
				<th class="column" scope="col">Profiles</th>
				<th class="column" scope="col">History</th>
			</tr>
		</tfoot>
		<tbody>

		<?php

		$query2 = "SELECT search.SearchID, search.SearchTitle, search.SearchProfileID, search.SearchDate FROM ". table_agency_searchsaved ." search ". $filter  ." ORDER BY $sort $dir $limit";
		//$query2 = "SELECT search.SearchID, search.SearchTitle, search.SearchProfileID, search.SearchOptions, search.SearchDate FROM ". table_agency_searchsaved_mux ." searchsent LEFT JOIN ". table_agency_searchsaved ." search ON searchsent.SearchID = search.SearchID ". $filter  ." ORDER BY $sort $dir $limit";

		$results2 = $wpdb->get_results($query2, ARRAY_A);
		$count2 = $wpdb->num_rows;

		foreach ($results2 as $data2) {
			$SearchID = $data2['SearchID'];
			$SearchTitle = stripslashes($data2['SearchTitle']);
			$SearchProfileID = stripslashes($data2['SearchProfileID']);
			$SearchDate = stripslashes($data2['SearchDate']);
			$query3 = "SELECT SearchID,SearchMuxHash, SearchMuxToName, SearchMuxToEmail, SearchMuxSent FROM ". table_agency_searchsaved_mux ." WHERE SearchID = %d";
			$results3 = $wpdb->get_results($wpdb->prepare($query3, $SearchID), ARRAY_A);
			$count3 = $wpdb->num_rows;

		?>
		<tr<?php echo $rowColor; ?>>
			<th class="check-column" scope="row">
				<input type="checkbox" value="<?php echo $SearchID; ?>" class="administrator" id="<?php echo $SearchID; ?>" name="<?php echo $SearchID; ?>"/>
			</th>
			<td>
				<?php echo $SearchID; ?>
			</td>
			<td>
				<?php echo $SearchTitle; ?>
				<div class="row-actions">
				<?php
				if($count3<=0){
				?>
					<span class="send"><a href="admin.php?page=<?php echo $_GET['page']; ?>&action=emailCompose&SearchID=<?php echo $SearchID."&SearchMuxHash=".RBAgency_Common::generate_random_string(8); ?>">Create Email</a> | </span>
				<?php
				}else{
				?>
						<span class="send"><a href="admin.php?page=<?php echo $_GET['page']; ?>&action=emailCompose&SearchID=<?php echo $SearchID; ?>">Create Email</a> | </span>
				<?php } ?>
						<span class="delete"><a class='submitdelete' title='Delete this Record' href='<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;action=deleteRecord&amp;SearchID=<?php echo $SearchID; ?>' onclick="if ( confirm('You are about to delete this record\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
				</div>
			</td>
			<td>
				<?php  // echo $SearchProfileID; ?>
			</td>
			<td>
				<?php
				$pos = 0;
				foreach ($results3 as $data3 ) {
				$pos++;
					 if($pos == 1){
						echo "Link: <a href=\"". get_bloginfo("url") ."/client-view/". $data3["SearchMuxHash"] ."/\" target=\"_blank\">". get_bloginfo("url") ."/client-view/". $data3["SearchMuxHash"] ."/</a><br />\n";
					}
					echo "(". rb_agency_makeago(rb_agency_convertdatetime( $data3["SearchMuxSent"]), $rb_agency_option_locationtimezone) .") ";
					echo "<strong>". $data3["SearchMuxToName"]."&lt;".$data3["SearchMuxToEmail"]."&gt;"."</strong> ";
					echo "<br/>";
				}
				//mysql_free_result($results2);
				if ($count3 < 1) {
					echo "Not emailed yet\n";
				}
				?>
			</td>
		</tr>
		<?php
		}
		//	mysql_free_result($results2);
			if ($count2 < 1) {
				if (isset($filter)) { 
		?>
		<tr>
			<th class="check-column" scope="row"></th>
			<td class="name column-name" colspan="3">
				<p>No profiles found with this criteria.</p>
			</td>
		</tr>
		<?php
				} else {
		?>
		<tr>
			<th class="check-column" scope="row"></th>
			<td class="name column-name" colspan="3">
				<p>There aren't any Profiles loaded yet!</p>
			</td>
		</tr>
		<?php
				}
		?>
		<?php } ?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class='tablenav-pages'>
			<?php 
			if($items > 0) {
				echo $p->show();  // Echo out the list of paging. 
			}
			?>
		</div>
	</div>

	<p class="submit">
		<input type="hidden" value="deleteRecord" name="action" />
		<input type="submit" value="<?php echo __('Delete','rb_agency_profiles'); ?>" class="button-primary" name="submit" />		
	</p>
	</form>
	<?php 
	} // End All
	?>