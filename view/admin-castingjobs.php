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
	$hash =  "";
	/*
	 * Display Inform Talent
	 */
		if(isset($_SESSION['cartArray']) || isset($_GET["action"]) && $_GET["action"] == "informTalent" || !isset($_GET["action"])){
     ?>
    

	<div style="clear:both"></div>

		<div class="wrap" style="min-width: 1020px;">
		 <div id="rb-overview-icon" class="icon32"></div>
		 <h2>Casting Jobs</h2>
		
		 <?php 
		 // Delete selected profiles
		if(isset($_POST["action2"]) && $_POST["action2"] == "deleteprofile"){
									  	$arr_selected_profile = array();
									  	$data = current($wpdb->get_results($wpdb->prepare("SELECT * FROM ".table_agency_castingcart_jobs." WHERE CastingJobID= %d ", $_GET["CastingJobID"])));
										$arr_profiles = explode(",",$data->CastingJobTalents);
									  		
										foreach($_POST as $key => $val ){
									  		 if(strpos($key, "profiletalent") !== false){
									  		 	  	array_push($arr_selected_profile, $val);
													
									  		 }
									  	}
									  	$new_set_profiles = implode(",",array_diff($arr_profiles,$arr_selected_profile));
									  	$wpdb->query($wpdb->prepare("UPDATE ".table_agency_castingcart_jobs." SET CastingJobTalents=%s WHERE CastingJobID = %d", $new_set_profiles, $_GET["CastingJobID"]));
	 
									  	echo ('<div id="message" class="updated"><p>'.count($arr_selected_profile).(count($arr_selected_profile) <=1?" profile":" profiles").' removed successfully!</p></div>');
		}

		// Add selected profiles
		if(isset($_POST["addprofiles"])){
										$data = current($wpdb->get_results($wpdb->prepare("SELECT * FROM ".table_agency_castingcart_jobs." WHERE CastingJobID= %d ", $_GET["CastingJobID"])));
									  	$add_new_profiles = $data->CastingJobTalents.",".$_POST["addprofiles"];

										$wpdb->query($wpdb->prepare("UPDATE ".table_agency_castingcart_jobs." SET CastingJobTalents=%s WHERE CastingJobID = %d", $add_new_profiles, $_GET["CastingJobID"]));
	 
									  	echo ('<div id="message" class="updated"><p>Added successfully!</p></div>');
	
		}

		  if(isset($_POST["action2"]) && $_POST["action2"] =="add"){
          	   	if (isset($_SESSION['cartArray'])) {

									$cartArray = $_SESSION['cartArray'];
									$cartString = implode(",", array_unique($cartArray));
									$cartString = RBAgency_Common::clean_string($cartString);
									$hash = RBAgency_Common::generate_random_string(10,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
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
											'".$hash."',
											'".esc_attr($_POST["wardrobe"])."',
											'".esc_attr($_POST["shootlocation"])."',
											'".esc_attr($_POST["shootlocationmap"])."'
											
											
											)
									";
									$wpdb->query($sql) or die(mysql_error());



									$results = $wpdb->get_results("SELECT ProfileContactPhoneCell,ProfileContactEmail, ProfileID FROM ".table_agency_profile." WHERE ProfileID IN(".$cartString.")",ARRAY_A);
									/*$arr_mobile_numbers = array();
									$arr_email = array();
									$arr_hash_profileid = array();*/

									
									
									foreach($results as $mobile){
										/*
										array_push($arr_mobile_numbers, $mobile["ProfileContactPhoneCell"]);
										array_push($arr_email, $mobile["ProfileContactEmail"]);
										array_push($arr_hash_profileid, $hash_profile_id);
										*/
										$hash_profile_id = RBAgency_Common::generate_random_string(20,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
					
										$sql = "INSERT INTO ".table_agency_castingcart_profile_hash." VALUES(
										'',
										'".$hash."',
										'".$mobile["ProfileID"]."',
										'".$hash_profile_id."')";
										$wpdb->query($sql);

										RBAgency_Casting::sendText(array($mobile["ProfileContactPhoneCell"]),get_bloginfo("wpurl")."/profile-casting/jobs/".$hash."/".$hash_profile_id);
										RBAgency_Casting::sendEmail(array($mobile["ProfileContactEmail"]),get_bloginfo("wpurl")."/profile-casting/jobs/".$hash."/".$hash_profile_id);
									
									}

											 unset($_SESSION['cartArray']);
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
											CastingJobRCallBack = '".esc_attr($_POST["callback"])."',
											CastingJobWardrobe = '".esc_attr($_POST["wardrobe"])."',
											CastingJobScript = '".esc_attr($_POST["script"])."',
											CastingJobShootDate = '".esc_attr($_POST["shootdate"])."',
											CastingJobShootLocation = '".esc_attr($_POST["shootlocation"])."',
											CastingJobShootLocationMap = '".esc_attr($_POST["shootlocationmap"])."',
											CastingJobRoleFee = '".esc_attr($_POST["rolefee"])."',
											CastingJobComments = '".esc_attr($_POST["comments"])."',
											CastingJobSelectedFor = '".esc_attr($_POST["selectedfor"])."'
											WHERE CastingJobID = ".esc_attr($_GET["CastingJobID"])."
									";

									$wpdb->query($sql);

                              if(isset($_POST["resend"])){
									$results = $wpdb->get_results("SELECT ProfileID,ProfileContactPhoneCell,ProfileContactEmail FROM ".table_agency_profile." WHERE ProfileID IN(".$_POST["profileselected"].")",ARRAY_A);
									$arr_mobile_numbers = array();
									$arr_email = array();
									$castingHash = current($wpdb->get_results("SELECT * FROM ".table_agency_castingcart_jobs." WHERE CastingJobID='".$_GET["CastingJobID"]."'"));
									foreach($results as $mobile){
										array_push($arr_mobile_numbers, $mobile["ProfileContactPhoneCell"]);
										array_push($arr_email, $mobile["ProfileContactEmail"]);
										$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM  ".table_agency_castingcart_profile_hash." as a WHERE  a.CastingProfileHashJobID = %s",$castingHash->CastingJobTalentsHash));
										RBAgency_Casting::sendText(array($mobile["ProfileContactPhoneCell"]),get_bloginfo("wpurl")."/profile-casting/jobs/".$castingHash->CastingJobTalentsHash."/".$results->CastingProfileHash);
										RBAgency_Casting::sendEmail(array($mobile["ProfileContactEmail"]),get_bloginfo("wpurl")."/profile-casting/jobs/".$castingHash->CastingJobTalentsHash."/".$results->CastingProfileHash);
									
									}
	
							  }
							  unset($_SESSION['cartArray']);
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
				  if(empty( $_SESSION['cartArray'] ) && !isset($_GET["action"])){
			      	  echo "Casting cart is empty. Click <a href=\"?page=rb_agency_search\">here</a> to search and add profiles to casting jobs.";
			      }else{
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
						echo "<label for=\"callback\">Call Back</label>";
						echo "<div><input type=\"text\" id=\"callback\" name=\"callback\" value=\"".$CastingJobRCallBack."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"wardrobe\">Wardrobe</label>";
						echo "<div><input type=\"text\" id=\"wardrobe\" name=\"wardrobe\" value=\"".$CastingJobRWardrobe."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"script\">Script</label>";
						echo "<div><input type=\"text\" id=\"script\" name=\"script\" value=\"".$CastingJobScript."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"shootdate\">Shoot Date</label>";
						echo "<div><input type=\"text\" id=\"shootdate\" name=\"shootdate\" value=\"".$CastingJobShootDate."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"shootlocation\">Shoot Location</label>";
						echo "<div><input type=\"text\" id=\"shootlocation\" name=\"shootlocation\" value=\"".$CastingJobShootLocation."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"shootlocationmap\">Google Map URL</label>";
						echo "<div><input type=\"text\" id=\"shootlocationmap\" name=\"shootlocationmap\" value=\"".$CastingJobShootLocationMap."\"></div>";
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
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"comments\">&nbsp;</label>";
						echo "<div>";
						echo "<input type=\"checkbox\" name=\"resend\" value=\"1\"/> &nbsp;Resend notifcation to selected talents \n\n";
					 	echo "</div>";
					echo "</div><br/><br/>";
                   
                    	echo "<input type=\"submit\" value=\"Save\" name=\"castingJob\" class=\"button-primary\" />";
                    	echo "<input type=\"hidden\" name=\"action2\" value=\"edit\"/>";
                    	echo "<input type=\"hidden\" name=\"profileselected\" value=\"".$CastingJobTalents."\"/>";
                    	echo "<input type=\"hidden\" name=\"CastingJobHash\" value=\"".$CastingJobHash."\"/>";
                    	echo "<a href=\"".admin_url("admin.php?page=". $_GET['page'])."\" class=\"button\">Cancel</a>\t";
                    	


                    }else{
						echo "<input type=\"hidden\" name=\"action2\" value=\"add\"/>";
                    	echo "<input type=\"submit\" value=\"Submit\" name=\"castingJob\" class=\"button-primary\" />";
                    	echo "<a href=\"".admin_url("admin.php?page=rb_agency_castingjobs")."\" class=\"button\">Cancel</a>";


                    }
				  	
				  	
				  echo "</form>";
				  } // if casting cart is not empty
				
				  echo "</div>";
				  echo "</div>";

                 
                 $cartArray = null;
				// Set Casting Cart Session
				if (isset($_SESSION['cartArray']) && !isset($_GET["CastingJobID"])) {

					$cartArray = $_SESSION['cartArray'];
			     }elseif(isset($_GET["CastingJobID"])){
			     	$cartArray = explode(",",$CastingJobTalents);
				
				} 
			    ?>
                 <script type="text/javascript">
                 jQuery(document).ready(function(){
                 	 var arr = [];
	                 	
	                 jQuery("#selectall").click(function(){
	                 	 var ischecked = jQuery(this).is(':checked');
	                 	 	jQuery("form[name=formDeleteProfile] input").each(function(i,d){
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

		              jQuery("#shortlisted input[name^=deleteprofiles]").click(function(){
		              	    if(jQuery("#shortlisted input[name^=profiletalent]:checked").length > 0){
			              		 if(confirm("Are you sure that you want to delete the selected profiles? Click 'Yes' to delete, 'Cancel' to exit.")){
				            		jQuery("#shortlisted input[name^=profiletalent]:checked").each(function(){
				            				jQuery("form[name=formDeleteProfile]").submit();
				            		});
				            	}
				            }else{
				            	alert("You must select a profile to delete");
				            }

		             });
		             
		            });
                 </script>
 
			    <?php
                		 echo "<div id=\"shortlisted\" class=\"boxblock-container\" style=\"float: left; width: 39%;\">";
						 echo "<div class=\"boxblock\" style=\"width:490px; \">";
						 echo "<h3>Talents Shortlisted";
						 if(!empty( $_SESSION['cartArray'])): 
						 echo "<span style=\"font-size:12px;float:right;margin-top: -5px;\"><a  href=\"#TB_inline?width=600&height=550&inlineId=add-profiles\" class=\"thickbox button-primary\" title=\"Add profiles to '".$CastingJobAudition."' Job\">Add Profiles</a>".(isset($_GET["CastingJobID"])?"<input type=\"submit\" name=\"deleteprofiles\" class=\"button-primary\" id=\"deleteprofiles\" value=\"Remove selected\" /><input type=\"checkbox\" id=\"selectall\"/>Select all</span>":"");
						 endif;
						 echo "</h3>";
						 echo "<div class=\"innerr\" style=\"padding: 10px;\">";
					

					?>
					<?php add_thickbox(); ?>
					<div id="add-profiles" style="display:none;">
					<table>
					<tr>
					<td><label>First Name:</label> <input type="text" name="firstname"/></td>
					<td><label>Last Name:</label> <input type="text" name="lastname"/></td>
					</tr>
					</table>    
					<div class="results-info" style="width:80%;float:left;border:1px solid #fafafa;padding:5px;background:#ccc;">
				       Loading...
					</div>
					<input type="submit" value="Add to Job" id="addtojob" class="button-primary" style="float:right" />
					
					<div id="profile-search-result">
					    
					 </div>
					<style type="text/css">
 					.profile-search-list{
 						background:#FAFAFA;
 						width: 31.3%;
 						float:left;
 						margin:5px;
 						cursor: pointer;
 						border:1px solid #fff;
 					}
 					.profile-search-list.selected{
 						border:1px solid black;
 					}
 					</style>
 					
					</div>
					<script type="text/javascript">
 					jQuery(function(){
 						    var arr_profiles = [];
 						    var selected_info = "";
 						    var total_selected = 0;
 						    var arr_listed = Array();
							
							jQuery("form[name=formDeleteProfile] div[id^=profile-]").each(function(i,d){
 						    		  arr_listed[i] = jQuery(this).attr("id").split("profile-")[1];
 						    });

 							function get_profiles(){


		 						jQuery.ajax({
										type: 'POST',
								   		dataType: 'json',
								  		url: '<?php echo admin_url('admin-ajax.php'); ?>',
								   		data: { 
								  			'action': 'rb_agency_search_profile'
								  		},
								  		success: function(d){
								  			var profileDisplay = "";
								  			console.log(arr_listed);
								  			jQuery.each(d,function(i,p){
								  				if(jQuery.inArray(p.ProfileID+"",arr_listed) < 0){
										  				
										  				var fullname = p.ProfileContactNameFirst+" "+p.ProfileContactNameLast;
										  				
										  				if(fullname.length > 10) fullname = fullname.substring(0,15)+"[..]";
										  				
										  				profileDisplay = "<table class=\"profile-search-list\" id=\"profile-"+p.ProfileID+"\">"
																		 +"<tr>"
																		   +"<td style=\"width:40px;height:40pxbackground:#ccc;\">"+((p.ProfileMediaURL !="")?"<img src=\"<?php echo  get_bloginfo('url').'/wp-content/plugins/rb-agency/ext/timthumb.php?src='.rb_agency_UPLOADDIR;?>/"+p.ProfileGallery+"/"+p.ProfileMediaURL+"&w=40&h=40\" style=\"width:40px;height:40px;\"/>":"")+"</td>"
																		   +"<td>"
																		   +"<strong>"+fullname+"</strong>"
																		   +"<br/>"
																		   +"<span style=\"font-size: 11px;\">"+getAge(p.ProfileDateBirth)+","+p.GenderTitle+"</span>"
																		   +"<br/>"
																		   +"<a href=\"<?php echo get_bloginfo("wpurl");?>/profile/"+p.ProfileGallery+"/\" target=\"_blank\">View Profile</a>"
																		   +"</td>"
																		 +"</tr>"
																		 +"</table>";
										  				jQuery("#profile-search-result").append(profileDisplay);
										  				arr_profiles.push({name:p.ProfileContactNameFirst.toLowerCase()+" "+p.ProfileContactNameLast.toLowerCase(),profileid:p.ProfileID});
										  		
										  		}
								  			});
											
						 						jQuery("table[class^=profile-search-list]").click(function(){
								 						jQuery(this).toggleClass("selected" );
								 						 total_selected = 0;
								 						jQuery("table.profile-search-list.selected").each(function(){
								 							 total_selected++;
								 							
								 						});
								 						jQuery(".selected-info").remove();
									 					if(total_selected >0){
									 						jQuery("#TB_ajaxWindowTitle").html(jQuery("#TB_ajaxWindowTitle").html()+"<span class=\"selected-info\"> - "+total_selected+" profiles selected.</span>");
									 					}
									 	
								 				});
								 				
								 				jQuery(".results-info").html(arr_profiles.length+ " Profiles found. "+selected_info);
								  			
								  		},
								  		error: function(e){
								  			console.log(e);
								  		}
								});
							}

							get_profiles();

							function getAge(dateString) 
							{
							    var today = new Date();
							    var birthDate = new Date(dateString);
							    var age = today.getFullYear() - birthDate.getFullYear();
							    var m = today.getMonth() - birthDate.getMonth();
							    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
							    {
							        age--;
							    }
							    if(isNaN(age)){
							    	age = "Not Set";
							    	return age;
							    }
							    return age+"y/o";
							}

							  var fname = jQuery("div[id=add-profiles] input[name=firstname]");
				              var lname = jQuery("div[id=add-profiles] input[name=lastname]");
				              jQuery("#add-profiles input[name=firstname],#add-profiles input[name=lastname]").keyup(function(){
				              	  var keyword = fname.val().toLowerCase()+ " " +lname.val().toLowerCase();
				              	 
				              	  var result = find(arr_profiles,keyword);
				              	  
				              	  if(result.length > 0){
				              	  	jQuery("table[id^=profile-]").hide();
				              	  	jQuery("table[id^=profile-][class=selected]").show();

				              	  	jQuery.each(result,function(i,p){
												jQuery("table[id^='profile-"+p.profileid+"']").show();

									});
									jQuery(".results-info").html("Search Result: "+result.length+" "+(result.length>1?"profiles":"profile")+" found. "+selected_info);
				              	  }else{
				              	  	jQuery(".results-info").html("'"+keyword+"' not found. "+selected_info);
				              	  }
				              	 
				              });

				              function find(arr,keyword) {
								    var result = [];

								   jQuery.each(arr,function(i,p){
								   	    if (p.name.indexOf(keyword) >= 0) {
								            result.push({profileid:p.profileid});
								        }
								    });

								    return result;
							}

							jQuery("#addtojob").click(function(){
								  var arr_profiles_selected = [];
								  jQuery("table.profile-search-list.selected").each(function(){
								  	var profiles = jQuery(this).attr("id").split("profile-")[1];
								  		arr_profiles_selected.push(profiles);
								  });
								   jQuery("input[name=addprofiles]").val(arr_profiles_selected.join());
								   window.parent.tb_remove();
								   arr_profiles_selected = [];
								   jQuery("form[name=formAddProfile]").submit();

		
							});

							 						
	 					
	 				});
 					</script>
 				<?php 
 				echo "<form method=\"post\" name=\"formAddProfile\" action=\"".admin_url("admin.php?page=rb_agency_castingjobs&action=informTalent&CastingJobID=".(!empty($_GET["CastingJobID"])?$_GET["CastingJobID"]:0))."\" >\n";								
				echo "<input type=\"hidden\" value=\"\" name=\"addprofiles\"/>";
				echo "</form>";

				?>


								<?php
										   
									$cartString = implode(",", array_unique($cartArray));
									$cartString = RBAgency_Common::clean_string($cartString);

								// Show Cart  
								$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".(!empty($cartString)?$cartString:0).") ORDER BY profile.ProfileContactNameFirst ASC";
								$results = $wpdb->get_results($wpdb->prepare($query,$cartString), ARRAY_A);

								$count = $wpdb->num_rows;

				echo "<form method=\"post\" name=\"formDeleteProfile\" action=\"".admin_url("admin.php?page=rb_agency_castingjobs&action=informTalent&CastingJobID=".(!empty($_GET["CastingJobID"])?$_GET["CastingJobID"]:0))."\" >\n";								
				echo "<input type=\"hidden\" name=\"action2\" value=\"deleteprofile\"/>";
								foreach ($results as $data) {
									echo "<div style=\"width: 48.5%;float:left\" id=\"profile-".$data["ProfileID"]."\">";
									echo "<div style=\"height: 200px; margin-right: 5px; overflow: hidden; \"><span style=\"text-align:center;background:#ccc;color:#000;font-weight:bold;width:100%;padding:10px;display:block;\">".(isset($_GET["CastingJobID"])?"<input type=\"checkbox\" name=\"profiletalent_".$data["ProfileID"]."\" value=\"".$data["ProfileID"]."\"/>":""). stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</span><a href=\"". rb_agency_PROFILEDIR . $data['ProfileGallery'] ."/\" target=\"_blank\"><img style=\"width: 100%; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></a>";
									echo "</div>\n";
									if(isset($_GET["CastingJobID"])){
										$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
										$prepared = $wpdb->prepare($query,$data["ProfileID"],$_GET["CastingJobID"]);
										$availability = current($wpdb->get_results($prepared));
										
										$count2 = $wpdb->num_rows;

										if($count2 <= 0){
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
				echo "</form>\n";

				if($count <= 0){
					echo "No profiles found.";
				}
								?>
						

					<?php
			  	  echo "<div style=\"clear:both;\"></div>";
					echo "</div>";
				    echo "</div>";
				  echo "</div>";
				  
				
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
				$sort = "jobs.CastingJobDateCreated ";
			}

			// Sort Order
			$dir = "";
			if (isset($_GET['dir']) && !empty($_GET['dir'])){
				$dir = $_GET['dir'];
				if ($dir == "desc" || !isset($dir) || empty($dir)){
					$sortDirection = "asc";
				} else {
					$sortDirection = "asc";
				}
			} else {
				$sortDirection = "desc";
				$dir = "desc";
			}

			// Filter
			$filter = "WHERE jobs.CastingJobID > 0 ";
			if (isset($_GET['CastingJobAudition']) && !empty($_GET['CastingJobAudition'])){
				$selectedTitle = isset($_GET['SearchTitle'])?$_GET['CastingJobAudition']:"";
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
					<th class="column" scope="col" style="width:50px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=CastingJobID&dir=<?php echo $sortDirection; ?>">ID</a></th>
					<th class="column" scope="col" style="width:200px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=CastingJobAudition&dir=<?php echo $sortDirection; ?>">Title</a></th>
					<th class="column" scope="col" style="width:80px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=CastingJobDateCreated&dir=<?php echo $sortDirection; ?>">Profiles</a></th>
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

	}