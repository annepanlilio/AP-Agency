<?php 
	global $user_ID; 
    global $wpdb;

// GET HEADER  
	echo $rb_header = RBAgency_Common::rb_header();

			$CastingJobAudition = "-"; 
			$CastingJobRole = "-";
			$CastingJobAuditionDate = "-";
			$CastingJobAuditionVenue = "-";
			$CastingJobAuditionTime = "-";
			$CastingJobClothing = "-";
			$CastingJobRCallBack = "-";
			$CastingJobRWardrobe = "-";
			$CastingJobScript = "-";
			$CastingJobShootDate = "-";
			$CastingJobShootLocation = "-";
			$CastingJobShootLocationMap = "-";
			$CastingJobRoleFee = "-";
			$CastingJobComments = "-";
			$CastingJobSelectedFor = "-";
			$CastingJobDateCreated = "-";
			$CastingJobTalents = "-";
		  
            $castingcartJobHash = get_query_var("target");
           
           if(isset($castingcartJobHash)){
		 	
		 	$sql =  "SELECT * FROM ".table_agency_castingcart_jobs." WHERE CastingJobTalentsHash= %s ";
		 	$data = $wpdb->get_results($wpdb->prepare($sql, $castingcartJobHash));
		 	$data = current($data);
		   if(!empty($data)){
		 	$CastingJobAudition = $data->CastingJobAudition; 
			$CastingJobRole = $data->CastingJobRole;
			$CastingJobAuditionDate = $data->CastingJobAuditionDate;
			$CastingJobAuditionVenue = $data->CastingJobAuditionVenue;
			$CastingJobAuditionTime = $data->CastingJobAuditionTime;
			$CastingJobClothing = $data->CastingJobClothing;
			$CastingJobRCallBack = $data->CastingJobRCallBack;
			$CastingJobRWardrobe = $data->CastingJobWardrobe;
			$CastingJobScript = $data->CastingJobScript;
			$CastingJobShootDate = $data->CastingJobShootDate;
			$CastingJobShootLocation = $data->CastingJobShootLocation;
			$CastingJobShootLocationMap = $data->CastingJobShootLocationMap;
			$CastingJobRoleFee = $data->CastingJobRoleFee;
			$CastingJobComments = $data->CastingJobComments;
			$CastingJobSelectedFor = $data->CastingJobSelectedFor;
			$CastingJobDateCreated = $data->CastingJobDateCreated;
			$CastingJobTalents = $data->CastingJobTalents;
		
		   }

		 }
	
	
	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";



		  
	 $has_permission = explode(",",$CastingJobTalents);

	 		$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileUserLinked = %d ORDER BY profile.ProfileContactNameFirst ASC";
			 $data = current($wpdb->get_results($wpdb->prepare($query,$user_ID), ARRAY_A));

    	if(isset($_POST["action"]) && $_POST["action"] == "availability"){
					  $query = "INSERT INTO ".table_agency_castingcart_availability." (CastingAvailabilityProfileID, CastingAvailabilityStatus, CastingAvailabilityDateCreated, CastingJobID)
							SELECT * FROM (SELECT '".$data["ProfileID"]."','".esc_attr($_POST["availability"])."','".date("y-m-d h:i:s")."','".$CastingJobID."') AS tmp
							WHERE NOT EXISTS (
							    SELECT CastingAvailabilityProfileID, CastingJobID FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID='".$data["ProfileID"]."' AND CastingJobID='".$CastingJobID."'
							) LIMIT 1;"; 
					   $wpdb->query($query);
						   	       echo ('<div id="message" class="updated"><p>Submitted successfully!</p></div>');
		       
		}
		   		   $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".table_agency_castingcart_availability." WHERE CastingJobID = %d AND CastingAvailabilityProfileID = %d",$CastingJobID,$data["ProfileID"]));
		    $has_submitted = $wpdb->num_rows;

			
      if(empty($has_submitted)){ // if not submitted
            /*var_dump($has_permission);
            var_dump($data["ProfileID"])*/
			 if( in_array($data["ProfileID"], $has_permission)){

				
			 ?>
		     <form method="post" action="" style="width: 900px;">
			  <h2>You have been submitted for a job</h2>
			  <strong>We are simply confirming that you are "Available" or "Not Available" for the job dates.</strong>
			  <div style="clear:both;"></div>

		     <div style="width:20%;float:left;margin-top:20px;margin-right:30px;padding-bottom:30px;">
		        <div style="width:100%;height:220px;padding:10px;text-align:center;background:#ccc;overflow:hidden;">
		        <?php if(!empty($data['ProfileMediaURL'])):?>
		        <?php echo "<img style=\"width: 100%;height:99.9% \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" />"; ?>
		        <?php else:?>
		        		No Image Available.
		        <?php endif; ?>
		        </div>
		        <strong>Availability:</strong>
		        <br/>
		        <?php echo $data["ProfileContactNameFirst"]." ".$data["ProfileContactNameLast"] ?>
		         <br/>
		         <select name="availability">
		            <option value="">-</option>
		         	<option value="available">Available</option>
		         	<option value="notavailable">Not Available</option>
		         </select>
		     </div>    
		      <table style="margin-top:20px;">
		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Audition:</td>
		      	<td><?php echo $CastingJobAudition; ?></td>
		      	</tr>

		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Role:</td>
		      	<td><?php echo $CastingJobRole; ?></td>
		      	</tr>

		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Audition Dates:</td>
		      	<td><?php echo $CastingJobAuditionDate; ?></td>
		      	</tr>

		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Auditon Venue:</td>
		      	<td><?php echo $CastingJobAuditionVenue; ?></td>
		      	</tr>

		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Audition Time</td>
		      	<td><?php echo $CastingJobAuditionTime; ?></td>
		      	</tr>


		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Audition Clothing:</td>
		      	<td><?php echo $CastingJobClothing; ?></td>
		      	</tr>

		      	<tr>
		      	<td  style="text-align:right;padding-right:20px;">Call Back</td>
		      	<td><?php echo $CastingJobRCallBack; ?></td>
		      	</tr>

				<tr>
		      	<td  style="text-align:right;padding-right:20px;">Wardrobe</td>
		      	<td><?php echo $CastingJobRWardrobe; ?></td>
		      	</tr>

		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Script:</td>
		      	<td><?php echo $CastingJobScript; ?></td>
		      	</tr>

		      	<tr>
		      	<td  style="text-align:right;padding-right:20px;">Shoot Date:</td>
		      	<td><?php echo $CastingJobShootDate; ?></td>
		      	</tr>
		      	<?php 
		      		$GoogleMapLocation = explode("data=", $CastingJobShootLocationMap);
		      	?>
		      	<tr>
		      	<td  style="text-align:right;padding-right:20px;vertical-align: top;">Shoot Location:</td>
		      	<td style="width:600px;"><?php echo $CastingJobShootLocation; ?><br/>
		      	 <?php if(!empty($GoogleMapLocation)){?>
		      	 <strong>Shoot Location Map</strong>
		      	  <?php echo do_shortcode("[googleMap name='Shooting Location Map' width='600' height='300' directions_from='true']".$CastingJobShootLocation."[/googleMap]"); ?>
		      	  <a href="<?php echo $CastingJobShootLocationMap;?>" target="_blank">View on Google Map</a>
		      	 <?php }?>
		      	</td>
		      	</tr>
		      	

		      	<tr>
		      	<td  style="text-align:right;padding-right:20px;">Role Fee($)</td>
		      	<td><?php echo $CastingJobRoleFee; ?></td>
		      	</tr>

		      	<tr>
		      	<td  style="text-align:right;padding-right:20px;">Comments</td>
		      	<td><?php echo $CastingJobComments; ?></td>
		      	</tr>

		      	<tr>
		      	<td style="text-align:right;padding-right:20px;">Selected for:</td>
		      	<td><?php echo $CastingJobSelectedFor; ?></td>
		      	</tr>

		      </table>
		      <input type="hidden" name="action" value="availability">
		      <input type="submit" name="submit" value="Submit" class="button-primary" style="float:right;" />
		      </form>
			 <?php 
			}else{
				echo "You're not allowed to view this Job.";
			}

		}else{
			echo "You've submitted your availability.";
		}
	echo "  </div>\n";
	echo "</div>\n";

	echo $rb_footer = RBAgency_Common::rb_footer(); 

	?>