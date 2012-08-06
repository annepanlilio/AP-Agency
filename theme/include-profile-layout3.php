<?php
/*
Expended Profile with Tabs
*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"profile-layout-three\">\n";

	echo " <div class=\"row row-zero\">\n";
	echo "   <a href=\"". get_bloginfo("wpurl") ."/profile-category/\">Directory</a><span class=\"divider\"> > </span>". $ProfileContactDisplay ."\n";
	echo " </div>\n";
	
	echo " <div class=\"row row-one clear\">\n";

	// Column 1
 	echo "	  <div id=\"profile-picture-wrap\">\n";

			// Profile Image
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
	echo "	  		<div id=\"profile-picture\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			}

	echo "	  		<div class=\"profile-picture-favorite\"><a rel=\"nofollow\" href=\"\"><div class=\"favoriteSquare\"></div>Save to Favorites</a></div>\n";
	echo "	  </div> <!-- #profile-picture -->\n";

	// Column 2
 	echo "	  <div id=\"profile-overview\">\n";

	echo "	      <div id=\"profile-name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";
	echo "	      <div id=\"profile-liner\">\n";
					if (isset($ProfileDateBirth)) {
	echo "			<div class=\"profile-overview-age\">". rb_agency_get_age($ProfileDateBirth) ."</div>\n";
					}
					if (isset($ProfileLocationCity)) {
	echo "			<div class=\"profile-overview-from\"> from ". $ProfileLocationCity .", ". $ProfileLocationState ."</div>\n";
					}
	echo "	      </div>\n";
	echo "		  <div class=\"profile-overview-category\">\n";
		
					$queryType = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID IN ($ProfileType) ORDER BY DataTypeTitle";
					$resultsType = mysql_query($queryType);
					$countType = mysql_num_rows($resultsType);
					while ($dataType = mysql_fetch_array($resultsType)) {
						echo "<div class=\"profile-overview-category-type\">". $dataType["DataTypeTitle"] ."</div>";
					}
	
	echo "		  </div>\n";
	echo "		  <div class=\"profile-overview-experience\">". $ProfileExperience ."</div>\n";

	echo "	  </div> <!-- #profile-overview -->\n";


	// Column 3
 	echo "	  <div id=\"profile-actions\">\n";

		//Contact Profile
		if (isset($rb_agency_option_agency_urlcontact) && !empty($rb_agency_option_agency_urlcontact)) {
	echo "	      <div id=\"profile-actions-contact\"><span><a href=\"". $rb_agency_option_agency_urlcontact ."\">". __("Contact", rb_agency_TEXTDOMAIN). " ". $ProfileClassification ."</a></span></div>\n";
		echo "		<li class=\"item contact\"></li>\n";
		}
	//echo "	      <div id=\"profile-actions-print\"><span>Print Friendly</span></div>\n";
	echo "	      <div id=\"profile-actions-profileviews\"><strong>". $ProfileStatHits ."</strong> Profile Views</div>\n";
	echo "	      <div id=\"profile-actions-favorited\"><strong>0</strong> favorited</div>\n";
	//echo "	      <div id=\"profile-actions-castings\"><strong>0</strong> castings</div>\n";
	//echo "	      <div id=\"profile-actions-recommendation\"><strong>0</strong> recommendation</div>\n";

	echo "	  </div> <!-- #profile-actions -->\n";

	echo " </div>\n"; // Row 1
	echo ' <div name="space" style="visibility:hidden">text</div>'; // Row 1
	echo " <div class=\"row row-two clear\">\n";
	echo "   <div id=\"subMenuTab\">\n";
	echo " 		<div class=\"maintab tab-left tab-active\" id=\"row-all\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">All</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-photos\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Photos</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-physical\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\" ><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Physical Details</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-videos\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Videos</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-experience\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Experience</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-right tab-inactive\" id=\"row-booking\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Booking</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
 	echo "   </div>\n";
	echo " </div>\n"; // Row 2

	echo " <div class=\"row row-photos clear tab\">\n";
	
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  if ($countImg > 1) { 
				echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  } else {
				echo "<div class=\"single\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  }
			}

	echo " </div>\n"; // Row photos

	echo " <div class=\"row row-physical clear tab\">\n";
	
 		if (!empty($ProfileGender)) {
			$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID=".$ProfileGender." ");
			$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
			echo "<div><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</div>\n";
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
		
	

		$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC");
		foreach  ($resultsCustom as $resultCustom) {
			if(!empty($resultCustom->ProfileCustomValue )){
				echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
			}
		}
	          	if($rb_agency_option_showcontactpage==1){
		    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";
			}
	echo " </div>\n"; // Row physical

	echo " <div class=\"row row-videos clear tab\">\n";
	
				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "	  <div class=\"item video slate\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "	  <div class=\"item video monologue\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "	  <div class=\"item video demoreel\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
				  }
				}


	echo " </div>\n"; // Row videos

	echo " <div class=\"row row-experience clear tab\">\n";

	$query1 ="SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." ORDER BY c.ProfileCustomOrder DESC";
	$results1 = mysql_query($query1);
	$count1 = mysql_num_rows($results1);
	while ($data1 = mysql_fetch_array($results1)) {
		
	echo "    <div class=\"inner experience-". $data1['ProfileCustomTitle'] ." clear\">\n";
	echo "		<h3>". $data1['ProfileCustomTitle'] ."</h3>\n";
	echo "		<p id=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" class=\"ProfileExperience\">". $data1['ProfileCustomValue'] ."</p>\n";
	echo "	  </div>\n";
	}
	echo " </div>\n"; // Row experience


	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All

?>