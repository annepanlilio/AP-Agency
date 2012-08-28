<?php
/*
Profile View with Sliding Thumbnails and Primary Image
*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"profile-layout-two\">\n";
	
	echo "  <div id=\"profile-l\">\n";
	echo "	<div id=\"photos\">\n";
	echo "	  <div class=\"customScrollBox\">\n";
	echo "		<div class=\"horWrapper\"> \n";
	echo "			<div style=\"left: 0px;\" class=\"container\">\n";
	echo "				<div class=\"content\">\n";
	echo "                <div class=\"inner\">\n";
	echo "				    <div id=\"photos-slide\">\n";

			// Image Slider
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  if ($countImg > 1) { 
				echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  } else {
				echo "<div class=\"single\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  }
			}

	echo "				    </div> <!-- #photos-slide -->\n";	
	echo "                </div><!-- .inner -->\n";
	echo "				</div><!-- .content -->\n";
	echo "			</div><!-- .container -->\n";
	echo "			<div style=\"display: block;\" class=\"dragger_container\">\n";
	echo "			  <div style=\"display: block; left: 0px;\" class=\"dragger ui-draggable\"></div>\n";
	echo "			</div>\n";
	echo "		</div>\n";

	echo "		<a style=\"display: inline-block;\" href=\"#\" class=\"scrollUpBtn\"> </a> <a style=\"display: inline-block;\" href=\"#\" class=\"scrollDownBtn\"> </a>\n";

	echo "	  </div><!-- .customScrollBox -->\n";
	echo "	</div><!-- #photos -->\n";
	
	echo "	<div id=\"info\">\n";
	echo "	  <h2>". $ProfileContactDisplay ."</h2>\n";
	echo "	  <div class=\"action\">\n";
	echo "		<div class=\"social addthis_toolbox addthis_default_style\">\n";
	echo "			<a href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c4d7ce67dde9ce7\" class=\"addthis_button_compact\">". __("Share", rb_agency_TEXTDOMAIN). "</a>\n";
	echo "			<span class=\"addthis_separator\">|</span>\n";
	echo "			<a class=\"addthis_button_facebook\"></a>\n";
	echo "			<a class=\"addthis_button_myspace\"></a>\n";
	echo "			<a class=\"addthis_button_google\"></a>\n";
	echo "			<a class=\"addthis_button_twitter\"></a>\n";
	echo "		</div><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c4d7ce67dde9ce7\"></script>\n";
	echo "		<div class=\"links\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
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
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Compcard\"");
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
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
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
				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch Video Slate</a></li>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch Video Monologue</a></li>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch Demo Reel</a></li>\n";
				  }
				}

				//Contact Profile
				if($rb_agency_option_showcontactpage==1){
		    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";
				}
                        // Other links - Favorite, Casting cart...
			      rb_agency_get_miscellaneousLinks($ProfileID);
				
				// Is Logged?
				if (is_user_logged_in()) { 
				//echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\">". __("Access Dashboard", rb_agency_TEXTDOMAIN). "</a></li>\n";
				}

	echo "	</ul>\n";

			if (isset($profileVideoEmbed)) {
	echo "		<div id=\"movie\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";
			}

	echo "		  <div class=\"experience\">\n";
	echo			$ProfileExperience;
	echo "		  </div>\n";

			
	echo "		</div>\n";
	echo "	  </div>\n";

	echo "	  <div class=\"stats\">\n";

		if (!empty($ProfileGender)) {
			$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
			$count = mysql_num_rows($queryGenderResult);
			if($count > 0){
				$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
				echo "<div><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</div>\n";
			}
		}
	
		if (!empty($ProfileStatHeight)) {
			if ($rb_agency_option_unittype == 0) { // Metric
				echo "<div><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", rb_agency_TEXTDOMAIN). "" ."</div>\n";
			} else { // Imperial
				$heightraw = $ProfileStatHeight;
				$heightfeet = floor($heightraw/12);
				$heightinch = $heightraw - floor($heightfeet*12);
				echo "<div><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", rb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", rb_agency_TEXTDOMAIN). "" ."</div>\n";
			}
		}
		if (!empty($ProfileStatWeight)) {
			if ($rb_agency_option_unittype == 0) { // Metric
				echo "<div><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", rb_agency_TEXTDOMAIN). "</div>\n";
			} else { // Imperial
				echo "<div><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", rb_agency_TEXTDOMAIN). "</div>\n";
			}
		}

		$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC");
		foreach  ($resultsCustom as $resultCustom) {
			if(!empty($resultCustom->ProfileCustomValue )){
				if(rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){
					echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
				}
			}
		}
	
		  
        
	echo "	  </div>\n";


	echo "	</div> <!-- #info -->\n";
	echo "	</div> <!-- #profile-l -->\n";
	echo "  <div id=\"profile-r\">\n";
	echo "			<div id=\"profile-picture\">\n";

                    // images
                    $queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
                    $resultsImg = mysql_query($queryImg);
                    $countImg = mysql_num_rows($resultsImg);
                    while ($dataImg = mysql_fetch_array($resultsImg)) {
						echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
                    }

	echo "			</div> <!-- #profile-picture -->\n";
	echo "	</div>\n";
	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
?>

<script type="text/javascript" src="/wp-content/plugins/rb-agency/js/jquery_003.js"></script>