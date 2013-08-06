<?php
/*
Custom Layout 7
*/

?>
<style>
#main-container { background: #000; padding: 0px; }
</style>
<div id="profile">
	<div id="rblayout-seven" class="rblayout">
		<div id="info-slide">
			<div id="profile-info" class="col_4 column">
				
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

			<div id="profile-slider" class="flexslider col_8 column">
				<ul class="slides">
					<?php
					$ProfileMediaPrimary = ""; 
					$ProfileMediaSecondry= "";
					$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
								$resultsImg = mysql_query($queryImg);
								$countImg = mysql_num_rows($resultsImg);
								$open = 1;
								$close = false;
								while ($dataImg = mysql_fetch_array($resultsImg)) {
								   // testing
								   if($ProfileID == 4){	
										   if($open==1){
											    $close = false;
												echo "<li>";
										   } 

											echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a>";

										   $open++;
										   if($open == 3){
											  $open = 1;
											  $close = true;
											  echo "</li>\n";	
										   }	
								   } else {
			                              
										   if($dataImg['ProfileMediaPrimary']==1){
												$ProfileMediaPrimary= 	"<li><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
											} else {
												$ProfileMediaSecondry .= "<li><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
											}
											echo $ProfileMediaPrimary; 
											echo $ProfileMediaSecondry; 
								   }
								}
								if($ProfileID == 4 && !$close){
									echo "</li>\n";
								}

					?>
				</ul>
			</div>

			<ul id="profile-links">

				<script type="text/javascript">

					jQuery(document).ready(function(){

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

				<?php if (is_user_logged_in()) { 	

					$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
		                              ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
		
					$count_favorite = mysql_num_rows($query_favorite);
					$datas_favorite = mysql_fetch_assoc($query_favorite);
					
					$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID
													 ."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
					
					$count_castingcart = mysql_num_rows($query_castingcart);
		
					if($count_castingcart>0){ ?>

						<li class="casting"><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>
						<?php }else{ ?>
						<li><a  class="save_cart" id="mycart_add" href="javascript:;" id="mycart" title="<?php echo __("Add to Casting Cart", rb_agency_TEXTDOMAIN);?>" ><?php echo __("Add to Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>
						<li id="mycart_view" style="display:none" ><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>
						<?php } 
						if($count_favorite>0){	 ?>
						<li class="favorite"><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View to Favorites", rb_agency_TEXTDOMAIN);?></a></li>
						<?php }else{ ?>
						<li><a  class="save_fav" id="myfav_add" href="javascript:;" id="mycart" title="<?php echo __("Add to Favorites", rb_agency_TEXTDOMAIN);?>" ><?php echo __("Add to Favorites", rb_agency_TEXTDOMAIN);?></a></li>
						<li id="myfav_view" style="display:none" ><a  href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View to Favorites", rb_agency_TEXTDOMAIN);?></a></li>
					<?php
					} ?> 
				
				<?php
				} ?>
				
					<li><a  class="showSingle1" target="1">Pictures</a></li>
					<li><a   class="showSingle2" target="3">Experience</a></li>
					<li><a  class="showSingle3" target="2">Videos</a></li>
					<?php echo '<li id="resultsGoHereAddtoCart"></li>';?>				
			</ul>

		</div><!-- #info-slide -->
		
		<div id="profile-carousel" class="flexslider col_12 column targetpictures">
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
								if($ProfileID == 4){	
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
							if($ProfileID == 4 && !$close){
								echo "</li>\n";
							}
				?>			
			</ul>
			
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
						echo "<li style='margin-left: 20px;margin-right: 20px;'>	  <object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param> <param name=\"wmode\" value=\"transparent\" /><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" wmode=\"transparent\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></li>";
				  	}
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
						$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
						echo "<li style='margin-left: 20px;margin-right: 20px;'>  <object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param> <param name=\"wmode\" value=\"transparent\" /><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" wmode=\"transparent\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></li>";
				  	}
				} ?>
			</ul>
		</div>

		<div id="experience" class="col_12 column targetexperience" style="display:none">
			<?php
			rb_agency_getSocialLinks();
			$title_to_exclude = array("Experience");
			print_r(rb_agency_getProfileCustomFieldsExperienceDescription($ProfileID, $ProfileGender, 'Experience(s):')); ?>
		</div>
	</div>
	
	<script>
		jQuery(function(){
				
			jQuery('.showSingle1').click(function(){
				  jQuery('.targetpictures').show();
				  jQuery('.targetexperience').hide();
				  jQuery('.targetvideo').hide();				  
			});
			jQuery('.showSingle2').click(function(){
				  jQuery('.targetexperience').show();
				  jQuery('.targetpictures').hide();
				  jQuery('.targetvideo').hide();				  
			});
			jQuery('.showSingle3').click(function(){
				  jQuery('.targetvideo').show();
				  jQuery('.targetpictures').hide();
				  jQuery('.targetexperience').hide();				  
			});
		
		});
	</script>
</div><!-- #profile -->