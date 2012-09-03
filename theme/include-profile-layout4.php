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
      // Social Link
	 rb_agency_getSocialLinks();
	echo "	  <div class=\"experience\">\n";

	echo			$ProfileExperience;

	echo "	  </div>\n"; // Close Experience

	echo "	</div>\n";



	echo "	<div id=\"info\">\n";

	

		echo "	  <h2>Statistics</h2>\n";

		echo "	  <div class=\"stats\">\n";

	

 				if (!empty($ProfileGender)) {

			$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");

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

		

	



			$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomID,c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder DESC");

		foreach  ($resultsCustom as $resultCustom) {

			if(!empty($resultCustom->ProfileCustomValue )){

				if(rb_agency_filterfieldGender($resultCustom->ProfileCustomID, $ProfileGender)){

					echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";

				}

			}

		}

	          	if($rb_agency_option_showcontactpage==1){

		    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";

			}

		echo "	  </div>\n"; // Close Stats



	

			$resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." ORDER BY c.ProfileCustomOrder DESC");

		foreach  ($resultsCustom as $resultCustom) {

			   if(!empty($resultCustom->ProfileCustomValue)){

				echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";

			   }

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