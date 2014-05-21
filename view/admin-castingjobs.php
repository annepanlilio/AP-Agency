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
									  	$data = current($wpdb->get_results($wpdb->prepare("SELECT * FROM ".table_agency_casting_job." WHERE Job_ID= %d ", $_GET["Job_ID"])));
										$arr_profiles = explode(",",$data->Job_Talents);
									  		
										foreach($_POST as $key => $val ){
									  		 if(strpos($key, "profiletalent") !== false){
									  		 		$wpdb->query($wpdb->prepare("DELETE FROM ".table_agency_castingcart_profile_hash." WHERE CastingProfileHashProfileID = %s",$val));
									  	
									  		 	  	array_push($arr_selected_profile, $val);
													
									  		 }
									  	}
									  	$new_set_profiles = implode(",",array_diff($arr_profiles,$arr_selected_profile));
									  	$wpdb->query($wpdb->prepare("UPDATE ".table_agency_casting_job." SET Job_Talents=%s WHERE Job_ID = %d", $new_set_profiles, $_GET["Job_ID"]));
	 									echo ('<div id="message" class="updated"><p>'.count($arr_selected_profile).(count($arr_selected_profile) <=1?" profile":" profiles").' removed successfully!</p></div>');
		}

		// Add selected profiles
		if(isset($_POST["addprofiles"])){
										$data = current($wpdb->get_results($wpdb->prepare("SELECT * FROM ".table_agency_casting_job." WHERE Job_ID= %d ", $_GET["Job_ID"])));
									  	$add_new_profiles = $data->Job_Talents.",".$_POST["addprofiles"];
									  	$hash_profile_id = RBAgency_Common::generate_random_string(20,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
										$castingHash = current($wpdb->get_results("SELECT * FROM ".table_agency_casting_job." WHERE Job_ID='".$_GET["Job_ID"]."'"));
									
										$profiles = $_POST["addprofiles"];
										
										if(strpos($profiles,",") !== false){
											$profiles = explode(",",$profiles);
											foreach($profiles as $profileid){
												$sql = "INSERT INTO ".table_agency_castingcart_profile_hash." VALUES(
												'',
												'".$castingHash->Job_Talents_Hash."',
												'".$profileid."',
												'".$hash_profile_id."')";
												echo $sql;
												$wpdb->query($sql);
											}
										}else{
											$sql = "INSERT INTO ".table_agency_castingcart_profile_hash." VALUES(
												'',
												'".$castingHash->Job_Talents_Hash."',
												'".str_replace(",",$_POST["addprofiles"])."',
												'".$hash_profile_id."')";
												$wpdb->query($sql);
										}
										$wpdb->query($wpdb->prepare("UPDATE ".table_agency_casting_job." SET Job_Talents=%s WHERE Job_ID = %d", $add_new_profiles, $_GET["Job_ID"]));
	 
									  	echo ('<div id="message" class="updated"><p>Added successfully!</p></div>');
	
		}

		  if(isset($_POST["action2"]) && $_POST["action2"] =="add"){
          	   	if (isset($_SESSION['cartArray'])) {

									$cartArray = $_SESSION['cartArray'];
									$cartString = implode(",", array_unique($cartArray));
									$cartString = RBAgency_Common::clean_string($cartString);
									$hash = RBAgency_Common::generate_random_string(10,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
									$sql = "INSERT INTO ".table_agency_casting_job." (
												Job_Title, 
												Job_Text,
												Job_Date_Start,
												Job_Date_End,
												Job_Location,
												Job_Region,
												Job_Offering,
												Job_Talents,
												Job_Visibility,
												Job_Criteria,
												Job_Type,
												Job_Talents_Hash,	
												Job_Audition_Date,
												Job_Audition_Venue,
												Job_Audition_Time
										)
										VALUES(
												'".esc_attr($_POST["Job_Title"])."', 
												'".esc_attr($_POST["Job_Text"])."',
												'".esc_attr($_POST["Job_Date_Start"])."',
												'".esc_attr($_POST["Job_Date_End"])."',
												'".esc_attr($_POST["Job_Location"])."',
												'".esc_attr($_POST["Job_Region"])."',
												'".esc_attr($_POST["Job_Offering"])."',
												'".$cartString."',
												'".esc_attr($_POST["Job_Visibility"])."',
												'".esc_attr($_POST["Job_Criteria"])."',
												'".esc_attr($_POST["Job_Type"])."',
												'".$hash."',	
												'".esc_attr($_POST["Job_Audition_Date"])."',
												'".esc_attr($_POST["Job_Audition_Venue"])."',
												'".esc_attr($_POST["Job_Audition_Time"])."'
											)
									";
									$wpdb->query($sql) or die(mysql_error());
									
									$results = $wpdb->get_results("SELECT ProfileContactPhoneCell,ProfileContactEmail, ProfileID FROM ".table_agency_profile." WHERE ProfileID IN(".$cartString.")",ARRAY_A);
								
									foreach($results as $mobile){
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

									$sql = "UPDATE ".table_agency_casting_job." 
										 SET
												Job_Title = '".esc_attr($_POST["Job_Title"])."', 
												Job_Text = '".esc_attr($_POST["Job_Text"])."',
												Job_Date_Start = '".esc_attr($_POST["Job_Date_Start"])."',
												Job_Date_End = '".esc_attr($_POST["Job_Date_End"])."',
												Job_Location = '".esc_attr($_POST["Job_Location"])."',
												Job_Region = '".esc_attr($_POST["Job_Region"])."',
												Job_Offering = '".esc_attr($_POST["Job_Offering"])."',
												Job_Talents = '".esc_attr($_POST["Job_Talents"])."',
												Job_Visibility = '".esc_attr($_POST["Job_Visibility"])."',
												Job_Criteria = '".esc_attr($_POST["Job_Criteria"])."',
												Job_Type = '".esc_attr($_POST["Job_Type"])."',
												Job_Talents_Hash = '".esc_attr($_POST["Job_Talents_Hash"])."',	
												Job_Audition_Date = '".esc_attr($_POST["Job_Audition_Date"])."',
												Job_Audition_Venue = '".esc_attr($_POST["Job_Audition_Venue"])."',
												Job_Audition_Time = '".esc_attr($_POST["Job_Audition_Time"])."'
											WHERE Job_ID = ".esc_attr($_GET["Job_ID"])."
									";

									$wpdb->query($sql);

                              if(isset($_POST["resend"])){
									$results = $wpdb->get_results("SELECT ProfileID,ProfileContactPhoneCell,ProfileContactEmail FROM ".table_agency_profile." WHERE ProfileID IN(". implode(",",array_filter(explode(",",$_POST["Job_Talents"]))).")",ARRAY_A);
									$arr_mobile_numbers = array();
									$arr_email = array();
									$castingHash = current($wpdb->get_results("SELECT * FROM ".table_agency_casting_job." WHERE Job_ID='".$_GET["Job_ID"]."'"));
									foreach($results as $mobile){
										array_push($arr_mobile_numbers, $mobile["ProfileContactPhoneCell"]);
										array_push($arr_email, $mobile["ProfileContactEmail"]);
										$results = current($wpdb->get_results($wpdb->prepare("SELECT * FROM  ".table_agency_castingcart_profile_hash." as a WHERE  a.CastingProfileHashProfileID = %s",$mobile["ProfileID"])));
										RBAgency_Casting::sendText(array($mobile["ProfileContactPhoneCell"]),get_bloginfo("wpurl")."/profile-casting/jobs/".$castingHash->Job_Talents_Hash."/".$results->CastingProfileHash);
										RBAgency_Casting::sendEmail(array($mobile["ProfileContactEmail"]),get_bloginfo("wpurl")."/profile-casting/jobs/".$castingHash->Job_Talents_Hash."/".$results->CastingProfileHash);
									
									}
	
							  }
							  unset($_SESSION['cartArray']);
											echo ('<div id="message" class="updated"><p>Updated successfully!</p></div>');
	

          }elseif(isset($_GET["action2"]) && $_GET["action2"] == "deleteCastingJob"){
          	       $wpdb->query("DELETE FROM ".table_agency_casting_job." WHERE Job_ID = '".$_GET["removeJob_ID"]."'");
          	       echo ('<div id="message" class="updated"><p>Deleted successfully!</p></div>');
          }

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
				$Job_Audition_Date = "";
				$Job_Audition_Venue = "";
				$Job_Audition_Time = "";

		   if(isset($_GET["Job_ID"])){
		 	
			 	$sql =  "SELECT * FROM ".table_agency_casting_job." WHERE Job_ID= %d ";
			 	$data = $wpdb->get_results($wpdb->prepare($sql, $_GET["Job_ID"]));
			 	$data = current($data);

	 			$Job_ID = $data->Job_ID; 
			 	$Job_Title = $data->Job_Title; 
				$Job_Text = $data->Job_Text;
				$Job_Date_Start = $data->Job_Date_Start;
				$Job_Date_End = $data->Job_Date_End;
				$Job_Location = $data->Job_Location;
				$Job_Region = $data->Job_Region;
				$Job_Offering = $data->Job_Offering;
				$Job_Talents = implode(",",array_filter(explode(",",$data->Job_Talents)));
				$Job_Visibility = $data->Job_Visibility;
				$Job_Criteria = $data->Job_Criteria;
				$Job_Type = $data->Job_Type;
				$Job_Talents_Hash = $data->Job_Talents_Hash;	
				$Job_Audition_Date = $data->Job_Audition_Date;
				$Job_Audition_Venue = $data->Job_Audition_Venue;
				$Job_Audition_Time = $data->Job_Audition_Time;
			
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
				 
						 if(isset($_GET["Job_ID"])){
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
						echo "<label for=\"Job_Title\">Job Title</label>";
						echo "<div><input type=\"text\" id=\"Job_Title\" name=\"Job_Title\" value=\"".$Job_Title."\"></div>";
					echo "</div>";
					 echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Text\">Description</label>";
						echo "<div><input type=\"text\" id=\"Job_Title\" name=\"Job_Text\" value=\"".$Job_Text."\"></div>";
					echo "</div>";
					 echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Offering\">Offer</label>";
						echo "<div><input type=\"text\" id=\"Job_Offering\" name=\"Job_Offering\" value=\"".$Job_Offering."\"></div>";
					echo "</div>";
					 echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Date_Start\">Job Date Start</label>";
						echo "<div><input type=\"text\" id=\"Job_Date_Start\" name=\"Job_Date_Start\" value=\"".$Job_Date_Start."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Date_End\">Job Date End</label>";
						echo "<div><input type=\"text\" id=\"Job_Date_End\" name=\"Job_Date_End\" value=\"".$Job_Date_End."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Location\">Location</label>";
						echo "<div><input type=\"text\" id=\"Job_Location\" name=\"Job_Location\" value=\"".$Job_Location."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Region\">Region</label>";
						echo "<div><input type=\"text\" id=\"Job_Region\" name=\"Job_Region\" value=\"".$Job_Region."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Type\">Job Type</label>";
						echo "<div>";
						echo "<select id='Job_Type' name='Job_Type'>
									<option value=''>-- Select Type --</option>";

									$get_job_type = $wpdb->get_results("SELECT * FROM " . table_agency_casting_job_type); // or die(mysql_error()
									if(count($get_job_type)){
										foreach($get_job_type as $jtype){
											echo "<option value='".$jtype->Job_Type_ID."' ".selected($jtype->Job_Type_ID,$Job_Type,false).">".$jtype->Job_Type_Title."</option>";
										}
									}

		 				echo "	</select> ";
						echo "</div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Criteria\">Job Criteria</label>";
						echo "<div><input type=\"text\" id=\"Job_Criteria\" name=\"Job_Criteria\" value=\"".$Job_Criteria."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Visibility\">Job Visibility</label>";
						echo "<div>";
						echo "<select id='Job_Visibility' name='Job_Visibility'>
									<option value=''>-- Select Type --</option>
									<option value='0' ".selected(isset($Job_Visibility)?$Job_Visibility:"","0",false).">Invite Only</option>
									<option value='1' ".selected(isset($Job_Visibility)?$Job_Visibility:"","1",false).">Open to All</option>
									<option value='2' ".selected(isset($Job_Visibility)?$Job_Visibility:"","2",false).">Matching Criteria</option>
								</select>";
						echo "</div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Audition_Date\">Audition Date</label>";
						echo "<div><input type=\"text\" id=\"Job_Audition_Date\" name=\"Job_Audition_Date\" value=\"".$Job_Audition_Date."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Audition_Time\">Audition Time</label>";
						echo "<div><input type=\"text\" id=\"Job_Audition_Time\" name=\"Job_Audition_Time\" value=\"".$Job_Audition_Time."\"></div>";
					echo "</div>";
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"Job_Audition_Venue\">Audition Venue</label>";
						echo "<div><input type=\"text\" id=\"Job_Audition_Venue\" name=\"Job_Audition_Venue\" value=\"".$Job_Audition_Venue."\"></div>";
					echo "</div>";
	
					 if(isset($_GET["Job_ID"])){
					echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">";
						echo "<label for=\"comments\">&nbsp;</label>";
						echo "<div>";
						echo "<input type=\"checkbox\" name=\"resend\" value=\"1\"/> &nbsp;Resend notifcation to selected talents \n\n";
					 	echo "</div>";
					echo "</div><br/><br/>";
                   
                    	echo "<input type=\"submit\" value=\"Save\" name=\"castingJob\" class=\"button-primary\" />";
                    	echo "<input type=\"hidden\" name=\"action2\" value=\"edit\"/>";
                    	echo "<input type=\"hidden\" name=\"Job_Talents\" value=\"".$Job_Talents."\"/>";
                    	echo "<input type=\"hidden\" name=\"Job_Talents_Hash\" value=\"".$Job_Talents_Hash."\"/>";
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
				if (isset($_SESSION['cartArray']) && !isset($_GET["Job_ID"])) {

					$cartArray = $_SESSION['cartArray'];
			     }elseif(isset($_GET["Job_ID"])){
			     	$cartArray = explode(",",$Job_Talents);
				
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
						 if(!empty( $_SESSION['cartArray']) || isset($_GET["Job_ID"])): 
						 echo "<span style=\"font-size:12px;float:right;margin-top: -5px;\"><a  href=\"#TB_inline?width=600&height=550&inlineId=add-profiles\" class=\"thickbox button-primary\" title=\"Add profiles to '".$Job_Title."' Job\">Add Profiles</a>".(isset($_GET["Job_ID"])?"<input type=\"submit\" name=\"deleteprofiles\" class=\"button-primary\" id=\"deleteprofiles\" value=\"Remove selected\" /><input type=\"checkbox\" id=\"selectall\"/>Select all</span>":"");
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
 				echo "<form method=\"post\" name=\"formAddProfile\" action=\"".admin_url("admin.php?page=rb_agency_castingjobs&action=informTalent&Job_ID=".(!empty($_GET["Job_ID"])?$_GET["Job_ID"]:0))."\" >\n";								
				echo "<input type=\"hidden\" value=\"\" name=\"addprofiles\"/>";
				echo "</form>";

				?>


								<?php
								$cartString = "";
							   if(!empty($cartArray)){		   
									$cartString = implode(",", array_unique($cartArray));
									$cartString = RBAgency_Common::clean_string($cartString);
							    }
								// Show Cart  
								$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".(!empty($cartString)?$cartString:0).") ORDER BY profile.ProfileContactNameFirst ASC";
								$results = $wpdb->get_results($wpdb->prepare($query,$cartString), ARRAY_A);

								$count = $wpdb->num_rows;

				echo "<form method=\"post\" name=\"formDeleteProfile\" action=\"".admin_url("admin.php?page=rb_agency_castingjobs&action=informTalent&Job_ID=".(!empty($_GET["Job_ID"])?$_GET["Job_ID"]:0))."\" >\n";								
				echo "<input type=\"hidden\" name=\"action2\" value=\"deleteprofile\"/>";
								foreach ($results as $data) {
									echo "<div style=\"width: 48.5%;float:left\" id=\"profile-".$data["ProfileID"]."\">";
									echo "<div style=\"height: 200px; margin-right: 5px; overflow: hidden; \"><span style=\"text-align:center;background:#ccc;color:#000;font-weight:bold;width:100%;padding:10px;display:block;\">".(isset($_GET["Job_ID"])?"<input type=\"checkbox\" name=\"profiletalent_".$data["ProfileID"]."\" value=\"".$data["ProfileID"]."\"/>":""). stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</span><a href=\"". rb_agency_PROFILEDIR . $data['ProfileGallery'] ."/\" target=\"_blank\"><img style=\"width: 100%; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></a>";
									echo "</div>\n";
									if(isset($_GET["Job_ID"])){
										$query = "SELECT CastingAvailabilityStatus as status FROM ".table_agency_castingcart_availability." WHERE CastingAvailabilityProfileID = %d AND CastingJobID = %d";
										$prepared = $wpdb->prepare($query,$data["ProfileID"],$_GET["Job_ID"]);
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
				$sort = "jobs.Job_Date_Start ";
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
			$filter = "WHERE jobs.Job_ID > 0 ";
			if (isset($_GET['Job_Title']) && !empty($_GET['Job_Title'])){
				$selectedTitle = isset($_GET['SearchTitle'])?$_GET['Job_Title']:"";
				$query .= "&Job_Title". $selectedTitle ."";
				$filter .= " AND jobs.Job_Title'". $selectedTitle ."'";
			}

			//Paginate
			$sqldata  = "SELECT jobs.*,talents.* FROM ". table_agency_casting_job ." jobs LEFT JOIN ". table_agency_castingcart_availability ." talents ON jobs.Job_ID = talents.CastingAvailabilityID ". $filter  .""; // number of total rows in the database
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
					<th class="column" scope="col" style="width:50px;"><a href="admin.php?page=<?php echo $_GET['page']; ?>&sort=Job_ID&dir=<?php echo $sortDirection; ?>">ID</a></th>
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

			$query2 = "SELECT jobs.* FROM ". table_agency_casting_job ." jobs ". $filter  ." ORDER BY $sort $dir $limit";
		
			$results2 = $wpdb->get_results($query2, ARRAY_A);
			$count2 = $wpdb->num_rows;

			foreach ($results2 as $data2) {
				$Job_Title = stripslashes($data2['Job_Title']);
				$Job_ID = stripslashes($data2['Job_ID']);
				$Job_Talents = stripslashes($data2['Job_Talents']);
				$Job_Talents = explode(",",str_replace("NULL","",$Job_Talents));
				
			?>
			<tr>
				<th class="check-column" scope="row">
					<input type="checkbox" value="<?php echo $Job_ID; ?>" class="administrator" id="<?php echo $Job_ID; ?>" name="<?php echo $Job_ID; ?>"/>
				</th>
				<td>
					<?php echo $Job_ID; ?>
				</td>
				<td>
					<?php echo $Job_Title; ?>
					<div class="row-actions">
					
							<span class="send"><a href="admin.php?page=<?php echo $_GET['page']; ?>&action=informTalent&Job_ID=<?php echo $Job_ID; ?>">Edit</a> | </span>
				
							<span class="delete"><a class='submitdelete' title='Delete this Record' href='<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;action=informTalent&amp;action2=deleteCastingJob&amp;removeJob_ID=<?php echo $Job_ID; ?>' onclick="if ( confirm('You are about to delete this record\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
					</div>
				</td>
				<td>
					<?php  echo count($Job_Talents); ?>
				</td>
				<td>
					<?php echo date("M d, Y - h:iA",strtotime($data2["Job_Date_Start"]));?>
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