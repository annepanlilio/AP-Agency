<?php
/*
Profile View with Thumbnails and Primary Image
*/
echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-one\" class=\"rblayout\">\n";
echo "			<div class=\"col_12 column\">\n";
echo "				<header class=\"entry-header\">";
echo "					<h1 class=\"entry-title\">". $ProfileContactDisplay ."</h1>";
echo "				</header>";
echo "			</div>\n"; // .col_12
echo "			<div class=\"col_4 column\">\n";
echo "				<div id=\"profile-picture\">\n";
						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}
echo "				</div>\n"; // #profile-picture
echo "			</div>\n"; // .col_4

echo "			<div class=\"col_5 column\">\n";
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
echo "					<div id=\"experience\">\n";
echo						$ProfileExperience;
echo "					</div>\n"; // #experience
echo "	  			</div>\n"; // #profile-info
echo "	  		</div>\n";  // .col_5

echo "			<div class=\"col_3 column\">\n";
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
							                             AND ProfileMediaType NOT IN ('Image','Resume','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
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
echo "	  			</div>\n";  // #links
echo "	  		</div>\n";  // .col_8

echo "			<div class=\"col_12 column\">\n";
echo "	  			<div id=\"photos\">\n";
	
						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}
						echo "	<div class=\"rbclear\"></div>\n"; // Clear All
echo "	  			</div>\n"; // #photos
echo "			</div>\n"; // .col_12

echo "	  		<div class=\"rbclear\"></div>\n"; // Clear All
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>