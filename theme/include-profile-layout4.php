<?php
/*

Escort
	Use Age not birthdate
	echo "	<div id=\"profile-sidebar\">\n";
			$LayoutType = "profile";
			get_sidebar(); 
	echo "	</div>\n";

*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"profile-layout-four\">\n";

	echo "	<div id=\"name\">\n";
	echo "	  <h1>". $ProfileContactDisplay ." <a href=\"contact/\">Book Now!</a></h1>\n";
	echo "	  <div class=\"experience\">\n";
	echo			$ProfileExperience;
	echo "	  </div>\n"; // Close Experience
	echo "	</div>\n";

	echo "	<div id=\"info\">\n";
	
		echo "	  <h2>Statistics</h2>\n";
		echo "	  <div class=\"stats\">\n";
	
			if (!empty($ProfileGender)) {
				echo "<div><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileGender ."</div>\n";
			}
			if (!empty($ProfileAge)) {
				echo "<div><strong>". __("Age", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileAge ."</div>\n";
			}
			if (!empty($ProfileStatEthnicity)) {
				echo "<div><strong>". __("Nationality", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatEthnicity ."</div>\n";
			}
			if (!empty($ProfileStatSkinColor)) {
				echo "<div><strong>". __("Skin Tone", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatSkinColor ."</div>\n";
			}
			if (!empty($ProfileStatHairColor)) {
				echo "<div><strong>". __("Hair Color", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHairColor ."</div>\n";
			}
			if (!empty($ProfileStatEyeColor)) {
				echo "<div><strong>". __("Eye Color", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatEyeColor ."</div>\n";
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
			if (!empty($ProfileStatBust)) {
				if($ProfileGender == "Male"){ $ProfileStatBustTitle = __("Chest", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatBustTitle = __("Bust", rb_agency_TEXTDOMAIN); } else { $ProfileStatBustTitle = __("Chest/Bust", rb_agency_TEXTDOMAIN); }
				echo "<div><strong>". $ProfileStatBustTitle ."</strong> ". $ProfileStatBust ."</div>\n";
			}
			if (!empty($ProfileStatWaist)) {
				echo "<div><strong>". __("Waist", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWaist ."</div>\n";
			}
			if (!empty($ProfileStatHip)) {
				if($ProfileGender == "Male"){ $ProfileStatHipTitle = __("Inseam", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatHipTitle = __("Hips", rb_agency_TEXTDOMAIN); } else { $ProfileStatHipTitle = __("Hips/Inseam", rb_agency_TEXTDOMAIN); }
				echo "<div><strong>". $ProfileStatHipTitle ."<span class=\"divider\">:</span></strong> ". $ProfileStatHip ."</div>\n";
			}
			if (!empty($ProfileStatDress) || ($ProfileStatDress == 0)) {
				if($ProfileGender == "Male"){ $ProfileStatDressTitle = __("Suit Size", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatDressTitle = __("Dress Size", rb_agency_TEXTDOMAIN); } else { $ProfileStatDressTitle = __("Suit/Dress Size", rb_agency_TEXTDOMAIN); }
				echo "<div><strong>". $ProfileStatDressTitle ."<span class=\"divider\">:</span></strong> ". $ProfileStatDress ."</div>\n";
			}
			if (!empty($ProfileStatShoe)) {
				echo "<div><strong>". __("Shoe Size", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatShoe ."</div>\n";
			}
	
			$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ."");
			foreach  ($resultsCustom as $resultCustom) {
				echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
			}
	          echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"/profile/".$_GET["target"]."/contact/\">Click Here</a></div>\n";
	
		echo "	  </div>\n"; // Close Stats

		echo "	  <h2 styl=\"margin-top: 20px;\">Prices</h2>\n";
		echo "	  <div class=\"stats\">\n";
			$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." ORDER BY c.ProfileCustomOrder DESC");
		foreach  ($resultsCustom as $resultCustom) {
				echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
			}
			
		echo "	    <div id=\"book-now\"><a href=\"contact/\">Book Now!</a></div>\n"; // Close Stats
		echo "	  </div>\n"; // Close Stats
	
	
	echo "	</div><!-- #info -->\n";

	echo "	<div id=\"photo\">\n";
	
	echo "	  <h2>". __("Call", rb_agency_TEXTDOMAIN). ": <span>". $ProfileContactPhoneWork ."</span></h2>\n";

	echo "	  <div class=\"inner\">\n";
		
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  if ($countImg > 1) { 
				echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". __("Call Now", rb_agency_TEXTDOMAIN). ": ". $ProfileContactPhoneWork ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  } else {
				echo "<div class=\"single\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". __("Call Now", rb_agency_TEXTDOMAIN). ": ". $ProfileContactPhoneWork ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  }
			}

	echo "	  </div>\n";
	echo "	</div><!-- #photo -->\n";
	
	
	
	echo "  <div style=\"clear: both;\"></div>\n"; // Clear All

	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
	
	
?>