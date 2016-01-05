<?php 
	global $user_ID; 
    global $wpdb;
		include (dirname(__FILE__) ."/../app/casting.class.php");
 

// GET HEADER  
	echo $rb_header = RBAgency_Common::rb_header();

				$Job_ID = ""; 
				$Job_Title = ""; 
				$Job_Text = "";
				$Job_Date_Start = "";
				$Job_Date_End = "";
				$Job_Location = "";
				$Job_Region = "";
				$Job_Offering = "";
				$Job_Talents = "";
				$Job_Visibility = "";
				$Job_Criteria = "";
				$Job_Type = "";
				$Job_Talents_Hash = "";
				$Job_Audition_Date_Start = "";
				$Job_Audition_Date_End = "";
				$Job_Audition_Venue = "";
				$Job_Audition_Time = "";

			      $castingcartJobHash = get_query_var("target");
			      $castingcartProfileHash = get_query_var("value");

				  
           if(isset($castingcartJobHash)){

			$sql =  "SELECT * FROM ".table_agency_casting_job." WHERE Job_Talents_Hash= %s ";
			$data = current($wpdb->get_results($wpdb->prepare($sql, $castingcartJobHash)));

			if(!empty($data)){
				$Job_ID = $data->Job_ID; 
				$Job_Title = $data->Job_Title; 
				$Job_Text = $data->Job_Text;
				$Job_Date_Start = $data->Job_Date_Start;
				$Job_Date_End = $data->Job_Date_End;
				$Job_Location = $data->Job_Location;
				$Job_Region = $data->Job_Region;
				$Job_Offering = $data->Job_Offering;
				$Job_Talents = $data->Job_Talents;
				$Job_Visibility = $data->Job_Visibility;
				$Job_Criteria = $data->Job_Criteria;
				$Job_Type = $data->Job_Type;
				$Job_Talents_Hash = $data->Job_Talents_Hash;
				$Job_Audition_Date_Start = $data->Job_Audition_Date_Start;
				$Job_Audition_Date_End = $data->Job_Audition_Date_End;
				$Job_Audition_Venue = $data->Job_Audition_Venue;
				$Job_Audition_Time = $data->Job_Audition_Time;

			}

		}



	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";

			$has_permission = explode(",",$Job_Talents_Hash);

			$profile_access_id = $wpdb->get_row("SELECT * FROM ".table_agency_castingcart_profile_hash." WHERE CastingProfileHash = '".$castingcartProfileHash."' # AND CastingProfileHashJobID ='".$castingcartJobHash."' ",ARRAY_A);

			$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID = %d ORDER BY profile.ProfileContactNameFirst ASC";

			$data = current($wpdb->get_results($wpdb->prepare($query,$profile_access_id["CastingProfileHashProfileID"]), ARRAY_A));
			
			$_profileID = $profile_access_id["CastingProfileHashProfileID"];

				// Is submitted



		if(isset($_POST["action"]) && $_POST["action"] == "availability"){
				$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
							$prepared = $wpdb->prepare($query,$data["ProfileID"],$Job_ID);
						$availability = current($wpdb->get_results($prepared));
								$count2 = $wpdb->num_rows;

					$availability = "available";
					$Availability = "Available";
					if($_POST["availability"] == "No, not Available"){
						$availability = "notavailable";
						$Availability = "Not Available";
					}
					if($count2 <= 0){
							$query = "INSERT INTO ".table_agency_castingcart_availability." (CastingAvailabilityProfileID, CastingAvailabilityStatus, CastingAvailabilityDateCreated, CastingJobID)
							SELECT * FROM (SELECT '".$data["ProfileID"]."','".esc_attr($availability)."','".date("y-m-d h:i:s")."','".$Job_ID."') AS tmp
							WHERE NOT EXISTS (
								SELECT CastingAvailabilityProfileID, CastingJobID FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID='".$data["ProfileID"]."' AND CastingJobID='".$Job_ID."'
							) LIMIT 1;"; 
					} else {
							$query = "UPDATE ".table_agency_castingcart_availability." SET CastingAvailabilityStatus = '".esc_attr($availability)."' WHERE CastingAvailabilityProfileID='".$data["ProfileID"]."' AND CastingJobID='".$Job_ID."'";
					}

					if($availability == "available"){
						$wpdb->get_results("SELECT * FROM ".table_agency_casting_job_application." WHERE Job_ID='".$Job_ID."' AND Job_UserLinked = '".$data["ProfileUserLinked"]."'");
						$is_applied = $wpdb->num_rows;
						if($is_applied <= 0){
							$wpdb->query("INSERT INTO  " . table_agency_casting_job_application . " (Job_ID, Job_UserLinked,Job_UserProfileID) VALUES('".$Job_ID."','".$data["ProfileUserLinked"]."','".$data["ProfileID"]."') ");
						}
					} else {
							$wpdb->query("DELETE FROM  " . table_agency_casting_job_application . " WHERE Job_ID = '".$Job_ID."' AND Job_UserLinked = '".$data["ProfileUserLinked"]."' ");
					}

						$wpdb->query($query);

						$link = get_bloginfo("url")."/profile-casting/?Job_ID=".$Job_ID;

						RBAgency_Casting::sendEmailCastingAvailability($data["ProfileContactDisplay"],$Availability,$Job_Title,$link);

						echo ('<div id="message" class="updated" style="width:50%;margin:auto;"><p>Submitted successfully!</p></div>');
				 
		}
						$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".table_agency_castingcart_availability." WHERE CastingJobID = %d AND CastingAvailabilityProfileID = %d",$Job_ID,$data["ProfileID"]));
			$has_submitted = $wpdb->num_rows;

				$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
							$prepared = $wpdb->prepare($query,$data["ProfileID"],$Job_ID);
						$availability = current($wpdb->get_results($prepared));
								$count2 = $wpdb->num_rows;

			?>
			<style type="text/css">
               input[disabled=disabled]{
           		color: #B4AAAA;
               }
               .job-details td{
           			border:1px solid #ccc;
           			padding:10px;
               }
			</style>

				<div style="width:100%;max-width:450px;">
				
				<?php if($count2 > 0):?>
				<h2>You've submitted your availability.</h2>
			<?php else: ?>
				<h2>You've been submitted for a job.</h2>
			<?php endif;?>
				<strong>We are simply confirming that you are "Available" or "Not Available" for the job dates.</strong>
				<div style="clear:both;"></div>

 
				<table class="job-details" style="margin-top:20px;width:100%;">
				
				<form method="post" action="">
				
				      <tr>
				        <td colspan="2">
				    					  <div style="width:95%;height:220px;padding:10px;text-align:center;background:#ccc;overflow:hidden;">
									  <?php if(!empty($data['ProfileMediaURL'])):?>
									  <?php
									 		$image_path = RBAGENCY_UPLOADDIR . $data["ProfileGallery"] ."/". $data['ProfileMediaURL'];
										$bfi_params = array(
											'crop'=>true,
											'width'=>180,
											'height'=>220
										);
										$image_src = bfi_thumb( $image_path, $bfi_params );
									  ?>
									  <?php 
									  echo "<img style=\"width: 50%;height:99.9% \" src=\"".$image_src ."\" />";
									  //echo "<img style=\"width: 50%;height:99.9% \" src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $data["ProfileGallery"] ."/". $data['ProfileMediaURL'] ."&w=180&h=220\" />"; ?>
									  <?php else:?>
									  		No Image Available.
									  <?php endif; ?>
									  </div>
									  <h2><?php echo $data["ProfileContactNameFirst"]." ".$data["ProfileContactNameLast"] ?></h2>
									 

						</td>
						</tr>
  					<tr>
				      <td style="text-align:right;padding-right:5px;">
						<input type="submit" name="availability" <?php echo isset($availability->status) && $availability->status == "available"?"disabled=\"disabled\"":"" ?> value="Yes, Available" class="button-primary"/>
						</td>
						<td>
				   	<input type="submit" name="availability"  <?php echo isset($availability->status) && $availability->status != "available"?"disabled=\"disabled\"":"" ?>  value="No, not Available" class="button-primary" />
				   	</td>
				   	</tr>

				  <?php if(!empty( $Job_Title )):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Job Title:</td>
						<td><?php echo $Job_Title; ?></td>
						</tr>
					<?php endif;?>
					<?php if(!empty( $Job_Type )):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Job Type:</td>
						<td>
						<?php $get_job_type = $wpdb->get_results("SELECT * FROM " . table_agency_casting_job_type);
									if(count($get_job_type)){
										foreach($get_job_type as $jtype){
											if($jtype->Job_Type_ID == $Job_Type){
												echo $jtype->Job_Type_Title;
											}
										}
									}
					?>
						</td>
						</tr>
				<?php endif;?>
					<?php if(!empty($Job_Date_Start)):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Job Date Start:</td>
						<td><?php echo date("M d, Y",strtotime($Job_Date_Start)); ?></td>
						</tr>
					<?php endif;?>
						<?php if(!empty($Job_Date_End)):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Job Date End:</td>
						<td><?php echo date("M d, Y",strtotime($Job_Date_End)); ?></td>
						</tr>
					<?php endif;?>


					<?php if(!empty($Job_Audition_Date_Start)):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Audition Date Start:</td>
						<td><?php echo date("M d, Y",strtotime($Job_Audition_Date_Start)); ?></td>
						</tr>
					<?php endif;?>
						<?php if(!empty($Job_Audition_Date_End)):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Audition Date End:</td>
						<td><?php echo date("M d, Y",strtotime($Job_Audition_Date_End)); ?></td>
						</tr>
					<?php endif;?>
					<?php if(!empty($Job_Audition_Venue)):?>
						<tr>
						<td style="text-align:right;padding-right:5px;">Auditon Venue:</td>
						<td><?php echo $Job_Audition_Venue; ?></td>
						</tr>
					<?php endif;?>
					<?php if(!empty($Job_Audition_Time)):?>
					<tr>
						<td style="text-align:right;padding-right:5px;">Audition Time</td>
						<td><?php echo $Job_Audition_Time; ?></td>
						</tr>
					<?php endif;?>



					<?php if(!empty($Job_Offering)):?>
					<tr>
						<td  style="text-align:right;padding-right:5px;">Role Fee($)</td>
						<td><?php echo $Job_Offering; ?></td>
						</tr>
					<?php endif;?>

					<?php if(!empty($Job_Text)):?>
						<tr>
						<td  style="text-align:right;padding-right:5px;">Description</td>
						<td><?php echo $Job_Text; ?></td>
						</tr>
					<?php endif;?>
                  <tr>
                     <td colspan="2" style="text-align:center;">
                 		<?php if(!empty($Job_Location)){ ?>
								Location:<br/>
								<?php echo $Job_Location; ?><br/>
							  <?php //echo do_shortcode("[pw_map address='". $Job_Location."']"); ?>
						<?php }?>
					</td>
					</tr>
					
				<input type="hidden" name="action" value="availability">
				</form>
				
					
					<?php
					
				$_AudioFileURL = $profile_access_id["CastingProfile_audio"];
				
				$target_dir = RBAGENCY_UPLOADPATH ."_casting-jobs/";
				if(!is_dir($target_dir)){
					mkdir($target_dir);
				}
				$_valid_name = str_replace(' ','-', basename($_FILES["fileToUpload"]["name"]));
				$_filename = $_profileID . '-'. $_valid_name;
				$target_file = $target_dir . $_filename;
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_POST["submitMP3"])) {
					
					$type = $_FILES["fileToUpload"]["type"];
					$size = $_FILES["fileToUpload"]["size"];
					
					$valid_mp3_mimes = array(
						'audio/mpeg',
						'audio/x-mpeg',
						'audio/mp3',
						'audio/wav',
						'audio/ogg',
						'audio/x-mp3',
						'audio/mpeg3',
						'audio/x-mpeg3',
						'audio/x-mpeg-3',
						'audio/mpg',
						'audio/x-mpg',
						'audio/x-mpegaudio',
					);
					if( ( in_array($type, $valid_mp3_mimes)) && ($size < 20000000)) {
						$uploadOk = 1;
					} else {
						$uploadOk = 0;
					}
			
				}
				
				//delete file audio
				if(isset($_POST["deleteMP3"])) {
					$_deleteFle = $target_dir.$_AudioFileURL;
					
					$results = $wpdb->update(table_agency_castingcart_availability,
								array('CastingProfile_audio' => ''),
								array('CastingAvailabilityProfileID' => $data["ProfileID"],'CastingJobID' => $Job_ID)
							);
					$_AudioFileURL = '';
				}
				
				
				
					
					
					?>
					
					
					<tr>
						<td  style="text-align:right;padding-right:5px;">MP3 Audition</td>
						<td><?php 
				
							// Check if $uploadOk is set to 0 by an error
							if(isset($_POST["submitMP3"])) {
								if( $uploadOk == 0) {
									echo "Sorry, Invalid audio file.";
								// if everything is ok, try to upload file
								} else {
									//remove the prev file first
									$_deleteFle = $target_dir.$_AudioFileURL;
									unset($_deleteFle);
									if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
										echo "Upload Success <br/>";
										$data_custom_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(CastingProfile_audio) FROM " . table_agency_castingcart_availability));
										if ( !$data_custom_exists ) {
											$wpdb->query("ALTER TABLE ".table_agency_castingcart_availability." ADD `CastingProfile_audio` VARCHAR(255) NOT NULL AFTER `CastingJobID`");
										}
										$results = $wpdb->update(table_agency_castingcart_availability,
											array('CastingProfile_audio' => $_filename),
											array('CastingAvailabilityProfileID' => $data["ProfileID"],'CastingJobID' => $Job_ID)
										);
										
										$_AudioFileURL = $_filename;
										
									} else {
										echo "Error Uploading";
									}
								}
							}


			
							$_file_FullURL = site_url() . RBAGENCY_UPLOADREL . '_casting-jobs/'. $_AudioFileURL;
							
							if(!empty($_AudioFileURL)){
								echo '<a href="'.$_file_FullURL.'" target="_blank">Download</a>';
								
								echo '<form action="" method="post" >
										<input type="submit" value="Delete" name="deleteMP3">
									</form>
									';
							}else{
								
								if(isset($availability->status) && $availability->status == "available"){ ?>
								
									<form action="" method="post" enctype="multipart/form-data">
										Select Audio File: <br/>
										<input type="file" name="fileToUpload" id="fileToUpload">
										<input type="submit" value="Upload Audio" name="submitMP3">
										
									</form>
								
								<?php 
								}else{
									echo "You must be available to upload audio file.";
									
								}
							}
							
						?></td>
					</tr>
						
				</table>
			</div>
				
				<?php
				
				
			/*} else {
				echo "You're not allowed to view this Job.";
			}*/


	echo "  </div>\n";
	echo "</div>\n";

	echo $rb_footer = RBAgency_Common::rb_footer(); 

	?>