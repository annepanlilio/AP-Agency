<?php
/*
 * Social Links
 */

						// Social Link
						rb_agency_getSocialLinks();


echo "					<ul>\n";

						if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
							echo rb_agency_get_new_miscellaneousLinks($ProfileID);
						}


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