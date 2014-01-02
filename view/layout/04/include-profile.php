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


/*
 * Layout 
 */


echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-four\" class=\"rblayout\">\n";

echo "			<div class=\"col_12 column\">";
echo "				<header class=\"entry-header\">";
echo "	  				<h1 class=\"entry-title\">". $ProfileContactDisplay ." <a href=\"/contact/\">Book Now!</a></h1>\n";
echo "				</header>";
echo "			</div>";

echo "			<div class=\"col_4 column\">\n";
echo "				<div id=\"profile-info\">\n";

echo "	  			<h3>Statistics</h3>\n";
echo "	  				<div class=\"stats\">\n";
echo "	  					<ul>\n";
								if (!empty($ProfileGender)) {
									$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
									$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
								echo "						<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
								}

								// Insert Custom Fields
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);

echo "	  					</ul>\n"; // Close ul
echo "	    				<div id=\"book-now\"><a href=\"contact/\" title=\"Book Now!\" class=\"rb_button\">Book Now!</a></div>\n"; // Close Stats
echo "	  				</div>\n"; // Close Stats	
echo "				</div><!-- #profile-info -->\n";
echo "			</div><!-- .col_4 -->\n";

echo "			<div class=\"col_8 column\">\n";	
echo "	  			<h3>". __("Call", rb_agency_TEXTDOMAIN). ": <span>". $ProfileContactPhoneWork ."</span></h3>\n";
echo "	  			<div id=\"photos\">\n";
	
						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
						  	if ($countImg > 1) { 
								echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". __("Call Now", rb_agency_TEXTDOMAIN). ": ". $ProfileContactPhoneWork ."\" style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\"></a></div>\n";
						  	} else {
								echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". __("Call Now", rb_agency_TEXTDOMAIN). ": ". $ProfileContactPhoneWork ."\" style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\"></a></div>\n";
						  	}
						}
echo "	  			</div><!-- #photos -->\n";
echo "			</div><!-- .col_8 -->\n";	

echo "  		<div class=\"rbclear\"></div>\n"; // Clear All

echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>