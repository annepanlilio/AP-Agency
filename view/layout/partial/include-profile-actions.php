<?php
/*
 * Social Links
 */

						// Social Link
						rb_agency_getSocialLinks();


echo "					<ul>\n";


/*
 * Casting Cart
 */



									// Other links - Favorite, Casting cart...
									// does not need this anymore
									//rb_agency_get_miscellaneousLinks($ProfileID);
									
									// Is Logged?
									if (is_user_logged_in()) { 

										if(is_permitted('casting')){
											if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already
											?>
												<script>
													function addtoCart(pid){
														var qString = 'usage=addtocart&pid=' +pid;
													
														$.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/sub_db_handler.php', qString, processResponseAddtoCart);
														// alert(qString);
													}				 
													function processResponseAddtoCart(data) {
														document.getElementById('resultsGoHereAddtoCart').style.display="block";
														document.getElementById('view_casting_cart').style.display="block";
														document.getElementById('resultsGoHereAddtoCart').textContent=data;
														setTimeout('document.getElementById(\'resultsGoHereAddtoCart\').style.display="none";',3000); 
														//setTimeout('document.getElementById(\'view_casting_cart\').style.display="none";',3000);
														setTimeout('document.getElementById(\'casting_cart_li\').style.display="none";',3000);					
													}
													
												</script>
												<?php
												echo "<li id=\"casting_cart_li\" class=\"item cart\"><a id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\" class=\"rb_button\">". __("Add to Casting Cart", rb_agency_TEXTDOMAIN). "</a></li>\n";
											} else {
												echo "<li class=\"item cart\">". __("", rb_agency_TEXTDOMAIN);
												echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"rb_button\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";						
											}
										}	//end if(checkCart(rb_agency_get_current_userid()


										// add save to favorites
										$rb_agency_option_profilelist_favorite	= isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;

										if(is_permitted('favorite')){
												$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
																 ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
												
												$count_favorite = mysql_num_rows($query_favorite);				 
												
												if($count_favorite>0){
													echo "<li class=\"item cart\">". __("", rb_agency_TEXTDOMAIN);
													echo " <a id='save_fav_li' onclick=\"javascript:addtofv('$ProfileID');\" href=\"javascript:void(0)\" class=\"rb_button\">". __("Remove from Favorites", rb_agency_TEXTDOMAIN)."</a></li>\n";						
												} else {
													echo "<li class=\"item cart\">". __("", rb_agency_TEXTDOMAIN);
													echo " <a id='save_fav_li' onclick=\"javascript:addtofv('$ProfileID');\" href=\"javascript:void(0)\" class=\"rb_button\">". __("Add to Favorites", rb_agency_TEXTDOMAIN)."</a></li>\n";						
												}				
										}

									}
echo "								<li id=\"resultsGoHereAddtoCart\"></li>";
echo "								<li id=\"view_casting_cart\" style=\"display:none;\"><a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"rb_button\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>";



/*
 * Favorite
 */

// add to fave script
?><script type="text/javascript">
			function addtofv(ids){
					jQuery.ajax({type: 'POST',url: '<?php echo get_bloginfo('url') ?>/wp-admin/admin-ajax.php',
									 data: {action: 'rb_agency_save_favorite',  talentID: ids},
								  success: function(results) {  
										if(results=='error'){ 
											alert("Error in query. Try again"); 
										}else if(results==-1){ 
											alert("You're not signed in");
										} else { 
												 if(jQuery("#save_fav_li").text() == 'Add to Favorites'){
													jQuery("#save_fav_li").text('<?php echo __("Remove from Favorites", rb_agency_TEXTDOMAIN); ?>');
												 } else {
													jQuery("#save_fav_li").text('<?php echo __("Add to Favorites", rb_agency_TEXTDOMAIN); ?>');
												}
										}
									}
					   }); // ajax submit
			}
</script><?php

echo						'<li class="profile-actions-favorited">';
							$cl1 = ""; $cl2=""; $tl1="Add to Favorites"; $tl2="Add to Casting Cart";

							if(is_permitted("casting")){
								$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID
																 ."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
								$count_castingcart = mysql_num_rows($query_castingcart);

								if($count_castingcart>0){ $cl2 = "cart_bg"; $tl2="Remove from Casting Cart"; }
								echo '<li><a title="'.$tl2.'" href="javascript:;" id="mycart" class="save_cart '.$cl2.' rb_button">'.$tl2.'</a></li>';
							}
							
							if(is_permitted("favorite")){
								$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
															  ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
								$count_favorite = mysql_num_rows($query_favorite);
								$datas_favorite = mysql_fetch_assoc($query_favorite);				

								if($count_favorite>0){ $cl1 = "fav_bg"; $tl1="Remove from Favorites"; }
								echo '<li class=\"favorite\"><a title="'.$tl1.'" href="javascript:;" class="save_fav '.$cl1.' rb_button" id="'.$ProfileID.'">'.$tl1.'</a></li>';				
							}

	echo 					'</li>';
	echo 					'<li id="resultsGoHereAddtoCart"></li>'; ?>    
							<li id="view_casting_cart" style="<?php if($tl2=="Add to Casting Cart"){?>display:none;<?php }else{?>display:block;<?php }?>"><li class="casting"><a class="rb_button" href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></li>    
							<li id="view_favorite" style="<?php if($tl1=="Add to Favorites"){?>display:none;<?php }else{?>display:block;<?php }?>"><li class="favorite"><a class="rb_button" href="<?php echo get_bloginfo('url')?>/profile-favorite/"><?php echo __("View favorite", rb_agency_TEXTDOMAIN);?></a></li>
							<?php





/*
 * Favorite
 */




						
						echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/images/\">". __("Print Photos", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
						echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/print-polaroids/\">". __("Print Polaroids", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
						echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/polaroids/\">". __("View Polaroids", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30
						// Resume
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Print Resume</a></li>\n";
								}
							}						
							// Comp Card
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Comp Card\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Comp Card</a></li>\n";
								}
							}
							// Headshots
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Headshot</a></li>\n";
								}
							}
							//Voice Demo
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Listen to Voice Demo</a></li>\n";
								}
							}
							//Video Slate
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
									echo "<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Slate</a></li>\n";
								}
							}
							//Video Monologue
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Monologue</a></li>\n";
								}
							}
							//Demo Reel
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Demo Reel</a></li>\n";
								}
							}

							// Other Media Type not the 
							// default ones
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
													AND ProfileMediaType NOT IN ('Image','Resume','Polaroid','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
														 ");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video demoreel\"><a target=\"_blank\" href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">".$dataMedia['ProfileMediaType']. "</a></li>\n";
								}
							}
							//Contact Profile
							if($rb_agency_option_showcontactpage==1){
								echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
							}
echo "					</ul>\n";






/*
 * Contact
 */


									//Contact Profile
									if($rb_agency_option_showcontactpage==1){
										echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
									}

?>