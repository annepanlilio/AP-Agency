<?php
/*
Custom Layout 7
*/
?>
<style>
#main-container { background: #000; padding: 0px; }
</style>
<div id="rbprofile">
	<div id="rblayout-seven" class="rblayout">
		<div id="info-slide">
			<div class="col_4 column">
				<div id="stats">
					
					<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
					<ul>
						<?php
						if (!empty($ProfileGender)) {
							$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
							$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
	                                                        echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<b class=\"divider\">:</b></strong> ". $fetchGenderData["GenderTitle"] . "</li>\n";
						}								
						if (!empty($ProfileStatHeight)) {
							if ($rb_agency_option_unittype == 0) { // Metric
								echo "<li><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", rb_agency_TEXTDOMAIN). "" ."</li>\n";
							} else { // Imperial
								$heightraw = $ProfileStatHeight;
								$heightfeet = floor($heightraw/12);
								$heightinch = $heightraw - floor($heightfeet*12);
								echo "<li><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", rb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", rb_agency_TEXTDOMAIN). "" ."</li>\n";
							}
						}
						if (!empty($ProfileStatWeight)) {
							if ($rb_agency_option_unittype == 0) { // Metric
								echo "<li><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", rb_agency_TEXTDOMAIN). "</li>\n";
							} else { // Imperial
								echo "<li><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", rb_agency_TEXTDOMAIN). "</li>\n";
							}
						}
								
						// Insert Custom Fields
						rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender); ?>
					</ul>
				</div><!-- .portfolio-info -->
			</div><!-- .portfolio-info -->

			<div class="col_8 column">
				<div id="profile-slider" class="flexslider">
					<ul class="slides" id="img_slde">
						<?php
						// this will be a flag in the future. for enabling
						// two images in the slider.
						$option_two_image = 1;
						$ProfileMediaPrimary = ""; 
						$ProfileMediaSecondry= "";
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
									$resultsImg = mysql_query($queryImg);
									$countImg = mysql_num_rows($resultsImg);
									$open = 1;
									$close = false;
									while ($dataImg = mysql_fetch_array($resultsImg)) {
									   // option for two images
									   if($option_two_image){	
											   if($open==1){
												    $close = false;
													echo "<li>";
											   } 

												echo "<figure class=\"multi\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></figure>";

											   $open++;
											   if($open == 3){
												  $open = 1;
												  $close = true;
												  echo "</li>\n";	
											   }	
									   } else {
				                              
											   if($dataImg['ProfileMediaPrimary']==1){
													$ProfileMediaPrimary= 	"<li><figure><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></figure></li>\n";
												} else {
													$ProfileMediaSecondry .= "<li><figure><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></figure></li>\n";
												}
												echo $ProfileMediaPrimary; 
												echo $ProfileMediaSecondry; 
									   }
									}
									if($option_two_image && !$close){
										echo "</li>\n";
									}

						?>
					</ul>
				</div>
			 <div id="video_player" class="col_8 column" style="display:none; position:relative; ">	
				<?php
				//Video Slate
				$count_video = 0;
				$profileVideoEmbed1 = "";
				$profileVideoEmbed2 = "";
				$profileVideoEmbed3 = "";
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed1 = $dataMedia['ProfileMediaURL'];
						$count_video++;
						echo "<div class='act_vids'><div id='v_slate' style='width:100%; height:100%'></div></div>";
					}
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed2 = $dataMedia['ProfileMediaURL'];
						$count_video++;
	
							echo "<div class='vids' style='display:none;'><div id='v_mono' style='width:100%; height:100%'></div></div>";
						}
				}
				//Demoreel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed3 = $dataMedia['ProfileMediaURL'];
						$count_video++;
							echo "<div class='vids' style='display:none;'><div id='d_reel' style='width:100%; height:100%'></div></div>";
						}
				}
				 ?>
				 
			</div>	
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
			
			<script>
						var tag = document.createElement('script');
						tag.src = "//www.youtube.com/iframe_api";
						var firstScriptTag = document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
						var yPlayer1;
						var yPlayer2;
						var yPlayer3;
						function onYouTubeIframeAPIReady() {
						<?php
							$embed = "profileVideoEmbed";
							$e = array("","v_slate","v_mono","d_reel");
							for($x = 1; $x <=$count_video ; $x++){
								$ytube = $embed . $x;
								if(!empty($$ytube)){
						?>	
								yPlayer<?php echo $x; ?> = new YT.Player('<?php echo $e[$x]; ?>',{
									height: '100%',
									width: '100%',
									videoId: '<?php echo $$ytube; ?>',
									playerVars: {
									  wmode: "opaque"
									},
									events: {
										'onReady': onYReady<?php echo $x; ?>
									}
								});
								
						<?php } }?>		
		
							}
						<?php 
						for($x = 1; $x <=$count_video ; $x++){
						?>	
						function onYReady<?php echo $x; ?>(event) {
									yPlayer<?php echo $x;?>.stopVideo();
									e.preventDefault();
							}
						<?php } ?>	

	
					jQuery(document).ready(function(){
						  
						  jQuery("#videos-carousel").find("li").click(function(){
							  <?php 
								for($x = 1; $x <=$count_video ; $x++){
								?>	
									yPlayer<?php echo $x;?>.pauseVideo();
							  <?php } ?>	
						
					  		  var _next = "#" + jQuery(this).attr("class");
							  var _curr = jQuery("#video_player").find(".act_vids");
							  _curr.removeClass("act_vids");
								  _curr.addClass("vids");	
								  jQuery(_next).parent().show();
								  jQuery(_next).parent().addClass("act_vids");
						  });
						  
						  jQuery("#vid_changer").width(jQuery("#video_player").width()+"px");
						  	
						 
						
						jQuery("a.showSingle1").click(function(){
							jQuery("#profile-slider").show();
							jQuery("#video_player").hide();
						});
						jQuery("a.showSingle3").click(function(){
							jQuery("#profile-slider").hide();
							jQuery("#video_player").show();
						});
						jQuery(".video_player").click(function(){
							jQuery("#profile-slider").hide();
							jQuery("#video_player").show();
						});
						 
						  
						
						jQuery("#profile-carousel li").click(function(){
							jQuery("#profile-slider").show();
							jQuery("#video_player").hide();
						});
						
						jQuery(".save_fav").click(function(){
							ajax_submit(jQuery(this),"favorite");
						});

						jQuery(".save_cart").click(function(){
							ajax_submit(jQuery(this),"casting");	
						});	

						function ajax_submit(Obj,type){
						    if(type == "favorite"){
								var action_function = "rb_agency_save_favorite";
							} else if(type == "casting"){
								var action_function = "rb_agency_save_castingcart";
							}
							
							jQuery.ajax({type: 'POST',url: '<?php echo get_bloginfo('url') ?>/wp-admin/admin-ajax.php',
										 data: {action: action_function,  'talentID': <?php echo $ProfileID ?>},
									  	success: function(results) {  
											if(results=='error'){ 
												alert("Error in query. Try again"); 
											}else if(results==-1){ 
												alert("You're not signed in");
											} else { 
												if(type == "favorite"){
										        	document.getElementById('myfav_add').style.display="none";	
													document.getElementById('myfav_view').style.display="inline-block";					  
												} else if(type == "casting") {
													document.getElementById('mycart_add').style.display="none";	
													document.getElementById('mycart_view').style.display="inline-block";											
												}						
											}
										}
						   }); // ajax submit
						} // end function
					});
				</script>
		</div><!-- #info-slide -->

		<div class="col_12 column">
			<ul id="profile-links">

				<?php if (is_user_logged_in()) { 	

					$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
		                              ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
		
					$count_favorite = mysql_num_rows($query_favorite);
					$datas_favorite = mysql_fetch_assoc($query_favorite);
					
					$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID
													 ."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
					
					$count_castingcart = mysql_num_rows($query_castingcart);
		
					if($count_castingcart>0 && is_permitted('casting')){	 ?>

						<li class="casting"><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>
						<?php }else{ ?>
						<li><a  class="save_cart" id="mycart_add" href="javascript:;" id="mycart" title="<?php echo __("Add to Casting Cart", rb_agency_TEXTDOMAIN);?>" ><?php echo __("Add to Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>
						<li id="mycart_view" style="display:none" ><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>
						<?php } 
						if($count_favorite>0 && is_permitted('favorite')){	 ?>
						<li class="favorite"><a  href="<?php echo get_bloginfo('url')?>/profile-favorites/"><?php echo __("View Favorites", rb_agency_TEXTDOMAIN);?></a></li>
						<?php }else{ ?>
						<li><a  class="save_fav" id="myfav_add" href="javascript:;" id="mycart" title="<?php echo __("Add to Favorites", rb_agency_TEXTDOMAIN);?>" ><?php echo __("Add to Favorites", rb_agency_TEXTDOMAIN);?></a></li>
						<li id="myfav_view" style="display:none" ><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View to Favorites", rb_agency_TEXTDOMAIN);?></a></li>
					<?php
					} ?> 
				
				<?php
				} ?>
				
					<li><a href="javascript:;" class="showSingle1" >Pictures</a></li>
					<li><a href="javascript:;" class="showSingle2" >Experience</a></li>
					<li><a href="javascript:;" class="showSingle3" >Videos</a></li>
                      <?php
				$resultsHeadshot = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
				$countHeadshot = mysql_num_rows($resultsHeadshot);
				$resultsVoiceDemo = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
				$countVoiceDemo = mysql_num_rows($resultsVoiceDemo);
				$resultsCompCard = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"CompCard\"");
				$countCompCard = mysql_num_rows($resultsCompCard);
				$resultsResume = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
				$countResume = mysql_num_rows($resultsResume);
				
				if($countHeadshot>0 || $countVoiceDemo>0 || $countCompCard>0 || $countResume>0 ){?>
                    <li><a href="javascript:;" class="showSingle4" >Media</a></li>
                    
					<?php }
					echo '<li id="resultsGoHereAddtoCart"></li>';?>				
			</ul>
		</div>		
		
		<div class="col_12 column targetpictures">
			<div id="profile-carousel" class="flexslider">
				<ul class="slides">
					<?php
					$ProfileMediaPrimary = ""; 
					$ProfileMediaSecondry= "";
					$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
								$resultsImg = mysql_query($queryImg);
								$countImg = mysql_num_rows($resultsImg);
								$open = 1;
								while ($dataImg = mysql_fetch_array($resultsImg)) {
									// testing
									if($option_two_image){	
													if($open==1){
														  $close = false;
														  echo "<li><figure class=\"multi\">";
													} 
									
														echo "<span style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" title=\"". $ProfileContactDisplay ."\" ></span>";
									
													$open++;
													if($open == 3){
															$open = 1;
															$close = true;
															echo "</figure></li>\n";	
													}	
									} else {
									
													if($dataImg['ProfileMediaPrimary']==1){
																$ProfileMediaPrimary= 	"<li><figure><span style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" title=\"". $ProfileContactDisplay ."\" ></span></figure></li>\n";
														} else {
																$ProfileMediaSecondry .= "<li><figure><span style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" title=\"". $ProfileContactDisplay ."\" ></span></figure></li>\n";
														}
														echo $ProfileMediaPrimary; 
														echo $ProfileMediaSecondry; 
									}
								}
								if($option_two_image && !$close){
									echo "</li>\n";
								}
					?>			
				</ul>			
			</div>
		</div>
				
		<div id="videos-carousel" class="flexslider col_12 column targetvideo" style="display:none"  >
			<ul class="slides">
				<?php
				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
						echo "<li class='v_slate'><figure><span class='video_player' style='background-image: url(http://img.youtube.com/vi/".$profileVideoEmbed."/default.jpg)' title='Video Slate'></span></li>";
					}
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
							echo "<li class='v_mono'><figure><span class='video_player' style='background-image: url(http://img.youtube.com/vi/".$profileVideoEmbed."/default.jpg)' title='Video Monologue'></span></li>";
						}
				}
				//Demoreel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
							echo "<li class='d_reel'><figure><span class='video_player' style='background-image: url(http://img.youtube.com/vi/".$profileVideoEmbed."/default.jpg)' title='Demo Reel'></span></li>";
						}
				}
				 ?>
			</ul>
		</div>
        <div id="media-carousel" class="flexslider col_12 column targetmedia" style="display:none"  >
			<ul class="slides">
				<?php
				//Headshot
				
				if ($countHeadshot > 0) {
				  	while ($dataHeadshot = mysql_fetch_array($resultsHeadshot)) {
						$profileHeadshotUrl = $dataHeadshot['ProfileMediaURL'];
						echo "<li><a target='_blank' href=".rb_agency_UPLOADDIR.$ProfileGallery.'/'.$profileHeadshotUrl.">Download Headshot</a></li>";
					}
				}

				//VoiceDemo
				
				if ($countVoiceDemo > 0) {
				  	while ($dataVoiceDemo = mysql_fetch_array($resultsVoiceDemo)) {
						$profileVoiceDemo = $dataVoiceDemo['ProfileMediaURL'];
							echo "<li><a target='_blank' href=".rb_agency_UPLOADDIR.$ProfileGallery.'/'.$profileVoiceDemo.">Download VoiceDemo</a></li>";
						}
				}
				//CompCard
				
				if ($countCompCard > 0) {
				  	while ($dataCompCard = mysql_fetch_array($resultsCompCard)) {
						$profileCompCardUrl = $dataCompCard['ProfileMediaURL'];
							echo "<li><a target='_blank' href=".rb_agency_UPLOADDIR.$ProfileGallery.'/'.$profileCompCardUrl.">Download CompCard</a></li>";
						}
				}
				//Resume
				
				if ($countResume > 0) {
				  	while ($dataResume = mysql_fetch_array($resultsResume)) {
						$profileResumeUrl = $dataResume['ProfileMediaURL'];
							echo "<li><a target='_blank' href=".rb_agency_UPLOADDIR.$ProfileGallery.'/'.$profileResumeUrl.">Download Resume</a></li>";
						}
				}
				 ?>
			</ul>
		</div>
        

		<div class="col_12 column targetexperience" style="display:none">
			<div id="experience">
				<?php
				rb_agency_getSocialLinks();
				$title_to_exclude = array("Experience");
				print_r(rb_agency_getProfileCustomFieldsExperienceDescription($ProfileID, $ProfileGender, 'Experience(s):')); ?>
			</div>
		</div>
		<div class="rbclear"></div>
	</div>
	
	<script>
		jQuery(function(){
				
			jQuery('.showSingle1').click(function(){
				  jQuery('.targetpictures').show();
				  jQuery('.targetexperience').hide();
				  jQuery('.targetvideo').hide();
				  jQuery('.targetmedia').hide();
			});
			jQuery('.showSingle2').click(function(){
				  jQuery('.targetexperience').show();
				  jQuery('.targetpictures').hide();
				  jQuery('.targetvideo').hide();	
				  jQuery('.targetmedia').hide();
			});
			jQuery('.showSingle3').click(function(){
				  jQuery('.targetvideo').show();
				  jQuery('.targetpictures').hide();
				  jQuery('.targetexperience').hide();
				  jQuery('.targetmedia').hide();
				  
			});
			jQuery('.showSingle4').click(function(){
				  jQuery('.targetmedia').show();
				  jQuery('.targetvideo').hide();
				  jQuery('.targetpictures').hide();
				  jQuery('.targetexperience').hide();				  
			});
		
		
		});
	</script>
	<div class="rbclear"></div>
</div><!-- #profile -->