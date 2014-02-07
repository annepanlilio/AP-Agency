<?php
/*
Profile View with Thumbnails, Primary Image, & Video Embed
*/
echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-ten\" class=\"rblayout\">\n";
echo "			<header class=\"entry-header\">";
echo "				<h1 class=\"entry-title\">". $ProfileContactDisplay ."</h1>";
echo "			</header>";
echo "			<div class=\"rbcol-4 rbcolumn\">\n";
echo "				<div id=\"profile-picture\">\n";
						// images
						# rb_agency_option_galleryorder
						$rb_agency_options_arr = get_option('rb_agency_options');
						$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}
echo "				</div>\n"; // #profile-picture

echo "			</div>\n"; // .rbcol-4

echo "			<div class=\"rbcol-8 rbcolumn\">\n";
echo "	  			<div id=\"profile-info\">\n";
echo "	  				<div id=\"stats\">\n";
echo "	  					<ul>\n";
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
echo "	  					</ul>\n"; // Close Stats ul
echo "	  				</div>\n"; // #stats
echo "	  			<div id=\"links\">\n";
echo "					<ul>\n";
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
							//Contact Profile
							if($rb_agency_option_showcontactpage==1){
					    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
							}
echo "					</ul>\n";
echo "	  			</div>\n";  // #links

echo "	  			<div id=\"videos\">\n";
echo "					<ul>\n";
							//Videos
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
														 AND (ProfileMediaType = 'Video Slate' OR 
														 ProfileMediaType = 'Video Monologue' OR
														 ProfileMediaType = 'Demo Reel')");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									$desc = explode("<br>",$dataMedia['ProfileMediaTitle']);
									echo "<li class=\"item video slate\" style='float:left'>";
									if($dataMedia['ProfileVideoType'] == "youtube" || $dataMedia['ProfileVideoType'] == ""){	
										echo "<iframe style='width:400px; height:225px;'  src='//www.youtube.com/embed/".$dataMedia['ProfileMediaURL']."' frameborder='0' allowfullscreen></iframe>";
									} elseif($dataMedia['ProfileVideoType'] == "vimeo") {
										echo '<iframe style="width:400px; height:225px;" src="//player.vimeo.com/video/'.$dataMedia['ProfileMediaURL'].'?portrait=0&amp;badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
									}
									echo "<br>".$desc[0]."<br><span style='font-size:12px; font-weight:normal'>".$desc[1]."</span>
									</li>\n";
							  	}
							}
echo "					</ul>\n";
echo "	  			</div>\n";  // #links

echo "					<div id=\"experience\">\n";
echo						$ProfileExperience;
echo "					</div>\n"; // #experience
echo "	  			</div>\n"; // #profile-info
echo "	  		</div>\n";  // .rbcol-8

echo "			<div class=\"rbcol-12 rbcolumn\">\n";
echo "	  			<div id=\"photos\">\n";
	
						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}
						echo "	<div class=\"cb\"></div>\n"; // Clear All
echo "	  			</div>\n"; // #photos
echo "			</div>\n"; // .rbcol-12

echo "	  		<div class=\"cb\"></div>\n"; // Clear All
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"cb\"></div>\n"; // Clear All
?>