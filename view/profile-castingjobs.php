<?php 
	global $user_ID; 
    global $wpdb;

// GET HEADER  
	echo $rb_header = RBAgency_Common::rb_header();

			$CastingJobAudition = ""; 
			$CastingJobRole = "";
			$CastingJobAuditionDate = "";
			$CastingJobAuditionVenue = "";
			$CastingJobAuditionTime = "";
			$CastingJobClothing = "";
			$CastingJobRCallBack = "";
			$CastingJobRWardrobe = "";
			$CastingJobScript = "";
			$CastingJobShootDate = "";
			$CastingJobShootLocation = "";
			$CastingJobShootLocationMap = "";
			$CastingJobRoleFee = "";
			$CastingJobComments = "";
			$CastingJobSelectedFor = "";
			$CastingJobDateCreated = "";
			$CastingJobTalents = "";
			$CastingJobID = "";
		  
            $castingcartJobHash = get_query_var("target");
            $castingcartProfileHash = get_query_var("value");


           if(isset($castingcartJobHash)){
		 	
		 	$sql =  "SELECT * FROM ".table_agency_castingcart_jobs." WHERE CastingJobTalentsHash= %s ";
		 	$data = current($wpdb->get_results($wpdb->prepare($sql, $castingcartJobHash)));

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
			$CastingJobID = $data->CastingJobID;
		   }

		 }
		

	
	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	  
	 		$has_permission = explode(",",$CastingJobTalents);

	 	    $profile_access_id = current($wpdb->get_results("SELECT * FROM ".table_agency_castingcart_profile_hash." WHERE CastingProfileHash = '".$castingcartProfileHash."' AND CastingProfileHashJobID ='".$castingcartJobHash."' ",ARRAY_A));

	 	  

	 		$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID = %d ORDER BY profile.ProfileContactNameFirst ASC";
			
			 $data = current($wpdb->get_results($wpdb->prepare($query,$profile_access_id["CastingProfileHashProfileID"]), ARRAY_A));

			 

    	if(isset($_POST["action"]) && $_POST["action"] == "availability"){
    			    $availability = "available";
    				if($_POST["availability"] == "No, not Available"){
    					$availability = "notavailable";
    				}
					  $query = "INSERT INTO ".table_agency_castingcart_availability." (CastingAvailabilityProfileID, CastingAvailabilityStatus, CastingAvailabilityDateCreated, CastingJobID)
							SELECT * FROM (SELECT '".$data["ProfileID"]."','".esc_attr($availability)."','".date("y-m-d h:i:s")."','".$CastingJobID."') AS tmp
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
			 if( in_array($data["ProfileID"], $has_permission) || current_user_can( 'manage_options' )  ){

				
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
		        <h2><?php echo $data["ProfileContactNameFirst"]." ".$data["ProfileContactNameLast"] ?></h2>	
		        <table>	
		          <?php if(!empty($CastingJobSelectedFor)):?>
				    <tr>
			      	<td style="text-align:right;padding-right:20px;">Selected for:</td>
			      	<td><?php echo $CastingJobSelectedFor; ?></td>
			      	</tr>
			     <?php endif;?>  	      
		        </table> 
		     </div>    
		      <table style="margin-top:20px;">
		            <tr>
		            <td>
		      		<input type="submit" name="availability" value="Yes, Available" class="button-primary"/>
		      		</td>
		      		<td>
		         	<input type="submit" name="availability" value="No, not Available" class="button-primary" />
		         	</td>
		         	</tr>
		        <?php if(!empty( $CastingJobAudition )):?>
			      	<tr>
			      	<td style="text-align:right;padding-right:20px;">Audition:</td>
			      	<td><?php echo $CastingJobAudition; ?></td>
			      	</tr>
		      	<?php endif;?>
		      	<?php if(!empty( $CastingJobRole )):?>
			      	<tr>
			      	<td style="text-align:right;padding-right:20px;">Role:</td>
			      	<td><?php echo $CastingJobRole; ?></td>
			      	</tr>
				<?php endif;?>
		      	<?php if(!empty($CastingJobAuditionDate)):?>
			      	<tr>
			      	<td style="text-align:right;padding-right:20px;">Audition Dates:</td>
			      	<td><?php echo $CastingJobAuditionDate; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobAuditionVenue)):?>
			      	<tr>
			      	<td style="text-align:right;padding-right:20px;">Auditon Venue:</td>
			      	<td><?php echo $CastingJobAuditionVenue; ?></td>
			      	</tr>
			      <?php endif;?>
			     <?php if(!empty($CastingJobAuditionTime)):?>
				    <tr>
			      	<td style="text-align:right;padding-right:20px;">Audition Time</td>
			      	<td><?php echo $CastingJobAuditionTime; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobClothing)):?>
					<tr>
			      	<td style="text-align:right;padding-right:20px;">Audition Clothing:</td>
			      	<td><?php echo $CastingJobClothing; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobRCallBack)):?>
					<tr>
			      	<td  style="text-align:right;padding-right:20px;">Call Back</td>
			      	<td><?php echo $CastingJobRCallBack; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobRWardrobe)):?>
			    	<tr>
			      	<td  style="text-align:right;padding-right:20px;">Wardrobe</td>
			      	<td><?php echo $CastingJobRWardrobe; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobScript)):?>
				    <tr>
			      	<td style="text-align:right;padding-right:20px;">Script:</td>
			      	<td><?php echo $CastingJobScript; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobShootDate)):?>
			    	<tr>
			      	<td  style="text-align:right;padding-right:20px;">Shoot Date:</td>
			      	<td><?php echo $CastingJobShootDate; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobShootLocation)){?>
			      	<tr>
			      	<td  style="text-align:right;padding-right:20px;vertical-align: top;">Shoot Location:</td>
			      	<td style="width:600px;"><?php echo $CastingJobShootLocation; ?><br/>
			      	 <?php if(!empty($CastingJobShootLocationMap)){?>
					      	<?php 
					      		$GoogleMapLocation = explode("data=", $CastingJobShootLocationMap);
					      	?>
				      	 <?php if(!empty($GoogleMapLocation)){?>
					      	 <strong>Shoot Location Map</strong>
					      	  <?php echo do_shortcode("[googleMap name='Shooting Location Map' width='600' height='300' directions_from='true']".$CastingJobShootLocation."[/googleMap]"); ?>
					      	  <a href="<?php echo $CastingJobShootLocationMap;?>" target="_blank">View on Google Map</a>
				      	 <?php }?>
			      	 <?php }?>
			      	</td>
			      	</tr>
			     <?php }?>
			      <?php if(!empty($CastingJobRoleFee)):?>
				    <tr>
			      	<td  style="text-align:right;padding-right:20px;">Role Fee($)</td>
			      	<td><?php echo $CastingJobRoleFee; ?></td>
			      	</tr>
			     <?php endif;?>
			     <?php if(!empty($CastingJobComments)):?>
			     	<tr>
			      	<td  style="text-align:right;padding-right:20px;">Comments</td>
			      	<td><?php echo $CastingJobComments; ?></td>
			      	</tr>
			     <?php endif;?>
			   
		      </table>
		      <input type="hidden" name="action" value="availability">
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