<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', plugins_url('/css/style.css', __FILE__) );
	wp_enqueue_style( 'rblayout-style' );
 echo plugins_url('/css/style.css', __FILE__);

/*
 * Layout 
 */

echo "	<div id=\"rbprofile\">\n";
echo "		<div id=\"rblayout-zero\" class=\"rblayout\">\n";

echo "			<div class=\"col_6 column\">\n";
echo "				<div id=\"photos\">\n";

						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							if ($countImg > 1) { 
								echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" class=\"photo\" /></a>\n";
							} else {
								echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" class=\"photo\" /></a>\n";
							}
						}

echo "					<div class=\"rbclear\"></div>\n";
echo "				</div>\n"; // close #photos
echo "			</div>\n"; // close .col_6

echo "			<div class=\"col_3 column\">\n";
echo "				<div id=\"stats\">\n";
echo "					<h2>". $ProfileContactDisplay ."</h2>\n";
echo "					<ul>\n";

							if (!empty($ProfileGender)) {
								$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
								$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
								echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
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
							rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);

							if($rb_agency_option_showcontactpage==1){
								echo "<li class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";
							}
echo "	  				</ul>\n"; // Close ul
echo "	  			</div>\n"; // Close Stats
echo "	  		</div>\n"; // Close .col_3

echo "			<div class=\"col_3 column\">\n";
echo "				<div id=\"links\">\n";

						// Social Link
						rb_agency_getSocialLinks();

echo "					<ul>\n";

						// public view
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
							// Resume
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
							echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Resume</a></li>\n";
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
							echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Slate</a></li>\n";
							  }
							}
							//Video Monologue
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
							echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Monologue</a></li>\n";
							  }
							}
							//Demo Reel
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
							echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Demo Reel</a></li>\n";
							  }
							}
							// Other Media Type not the 
							// default ones
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
														 AND ProfileMediaType NOT IN ('Image','Resume','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
														 ");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video demoreel\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">".$dataMedia['ProfileMediaType']. "</a></li>\n";
								}
							}                            
echo "					</ul>\n";
echo "				</div>\n";  // Close Links
echo "			</div>\n";  // Close .col_3

echo "	  		<div class=\"col_12 column\">\n";
echo "	  			<div id=\"experience\">\n";
echo					$ProfileExperience;
echo "	  			</div>\n"; // Close Experience
echo "	  		</div>\n"; // Close .col_12

echo "		  	<div class=\"rbclear\"></div>\n"; // Clear All
echo "  	</div>\n";  // Close Profile Zero
echo "		<div class=\"rbclear\"></div>\n"; // Clear All
echo "	</div>\n";  // Close Profile
?>
