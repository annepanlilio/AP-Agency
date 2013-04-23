<?php
/*
Profile View with Scrolling Thumbnails and Primary Image
*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"rblayout-zero\" class=\"rblayout\">\n";

	echo "	<div id=\"photos\" class=\"four column\">\n";
	echo "	  <div class=\"inner\">\n";
			// images
		
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  if ($countImg > 1) { 
				echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></div>\n";
			  } else {
				echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></div>\n";
			  }
			}

	echo "	  <div class=\"cb\"></div>\n";
	echo "	  </div>\n";
	echo "	</div>\n"; // close #photos
	
		echo "	  <div id=\"stats\" class=\"four column\">\n";

		echo "	  <h2>". $ProfileContactDisplay ."</h2>\n";

		echo "	  <ul>\n";

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
		echo "	  </ul>\n"; // Close ul
		echo "	  </div>\n"; // Close Stats
	
	echo "		<div id=\"links\" class=\"four column\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";

					 // Social Link
					 rb_agency_getSocialLinks();
	
	echo "			<ul>\n";

				// Resume
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"btn_gray\">Print Resume</a></li>\n";
				  }
				}
			
				// Comp Card
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Comp Card\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"btn_gray\">Download Comp Card</a></li>\n";
				  }
				}
				// Headshots
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"btn_gray\">Download Headshot</a></li>\n";
				  }
				}
				
				//Voice Demo
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Voice Demo\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"btn_gray\">Listen to Voice Demo</a></li>\n";
				  }
				}

				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"btn_gray\">Watch Video Slate</a></li>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"btn_gray\">Watch Video Monologue</a></li>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"btn_gray\">Watch Demo Reel</a></li>\n";
				  }
				}

				// Other Media Type not the 
				// default ones
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
				                             AND ProfileMediaType NOT IN ('Image','Resume','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
											 GROUP BY ProfileMediaType");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
                                      echo "<li class=\"item video demoreel\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"btn_gray\">".$dataMedia['ProfileMediaType']. "</a></li>\n";
				  }
				}
                                
				// Is Logged?
				if (is_user_logged_in()) { 
				echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\" class=\"btn_gray\">". __("Access Dashboard", rb_agency_TEXTDOMAIN). "</a></li>\n";
				
				
						if($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']==1){
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
						 
							echo "<li id=\"casting_cart_li\"><a id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\" class=\"btn_gray\">". __("Add to Casting Cart", rb_agency_TEXTDOMAIN). "</a></li>\n";
							}else{
				  		  echo "<li class=\"add to cart\">". __("", rb_agency_TEXTDOMAIN);
						  
						  echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"btn_gray\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
				          }
			}	//end if(checkCart(rb_agency_get_current_userid()

				}

	echo "			</ul>\n";
	echo "		</div>\n";  // Close Links
	?>
        <div id="resultsGoHereAddtoCart"></div>
        <div id="view_casting_cart" style="display:none;"><a href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></div>
    <?php
	echo "	  <div id=\"experience\" class=\"six column\">\n";
	echo			$ProfileExperience;
	echo "	  </div>\n"; // Close Experience
/*
			if (isset($profileVideoEmbed)) {
	echo "		<div id=\"movie\" class=\"six column\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";
			}
*/	
	
	echo "	  <div style=\"clear: both;\"></div>\n"; // Clear All
	echo "  </div>\n";  // Close Profile Zero
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
	echo "</div>\n";  // Close Profile
?>