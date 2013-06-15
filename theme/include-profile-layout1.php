<?php
/*
Profile View with Thumbnails and Primary Image
*/
	echo "<div id=\"profile\">\n";
	echo " <div id=\"rblayout-one\" class=\"rblayout\">\n";
	echo "	<div id=\"name\">\n";
	echo "	  <h2>". $ProfileContactDisplay ."</h2>\n";
		
	echo "	</div>\n";
	echo "	<div id=\"profile-picture\" class=\"col_4 column\">\n";
		// images
		$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
		$resultsImg = mysql_query($queryImg);
		$countImg = mysql_num_rows($resultsImg);
		while ($dataImg = mysql_fetch_array($resultsImg)) {
			echo "		<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
		}
	echo "	</div> <!-- #profile-picture -->\n";
	echo "	<div id=\"info\" class=\"col_8 column\">\n";
	echo "	  <div id=\"stats\">\n";
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
		
		
		 
	echo "	  </ul>\n"; // Close Stats ul
	echo "	  </div>\n"; // Close Stats
	echo "	  <div class=\"links\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
       // Social Link
	 rb_agency_getSocialLinks();
	
	echo "			<ul>\n";
				// Resume
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Print Resume</a></li>\n";
				  }
				}
			
				// Comp Card
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Comp Card\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Download Comp Card</a></li>\n";
				  }
				}
				// Headshots
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Download Headshot</a></li>\n";
				  }
				}
				
				//Voice Demo
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Voice Demo\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Listen to Voice Demo</a></li>\n";
				  }
				}
				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Watch Video Slate</a></li>\n";
				  }
				}
				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Watch Video Monologue</a></li>\n";
				  }
				}
				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Watch Demo Reel</a></li>\n";
				  }
				}
				//Contact Profile
				if($rb_agency_option_showcontactpage==1){
		    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";
				}
				//<li class="item"><a href=""><img src="/wp-content/uploads/2010/07/talk.jpg" /></a><a href="">View Video Slate</a></li>
			  //li class="item"><a href=""><img src="/wp-content/uploads/2010/07/talk.jpg" /></a><a href="">View Monolog</a></li>
			  //<li class="item"><a href=""><img src="/wp-content/uploads/2010/07/download.jpg" /></a><a href="">Download Reel</a></li>
				//echo "<li class=\"cart\"><a href=\"\"><img src=\"". get_bloginfo("wpurl") ."/wp-content/uploads/2010/07/cart.jpg\" /></a><a href=\"\">Add to Casting Cart</a></li>\n";
				
				// URL
				//if($ProfileIsModel){ $returnURL = get_bloginfo("url") ."/models/"; } elseif ($ProfileIsTalent){ $returnURL = get_bloginfo("url") ."/talent/"; }
				//echo "<li class=\"return\"><a href=\"\"><img src=\"". get_bloginfo("url") ."/wp-content/uploads/2010/07/return.jpg\" /></a><a href=\"". $returnURL ."\">Return to ". $ProfileClassification ."</a></li>\n";
				
				// Is Logged?
				if (is_user_logged_in()) { 
				//echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\">". __("Access Dashboard", rb_agency_TEXTDOMAIN). "</a></li>\n";
				}
	echo "			</ul>\n";
	echo "	  </div>\n";  // Close Links
		if (isset($profileVideoEmbed)) {
	echo "	  <div id=\"movie\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";
		}
       
	echo "		<div class=\"experience\">\n";
	echo			$ProfileExperience;
	echo "		</div>\n"; // Close Experience
			
	echo "	  </div>\n";  // Close Info
	
	echo "	<div id=\"photos\" class=\"col_12 column\">\n";
	echo "	  <div class=\"inner\">\n";
		
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
				echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			}
	echo "	  </div>\n";
	echo "	</div>\n";
	
	echo "	  <div style=\"clear: both;\"></div>\n"; // Clear All
	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
	
	
?>