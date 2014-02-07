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
echo "		<div id=\"rblayout-zero\" class=\"rblayout\">\n";

echo "			<div class=\"rbcol-6 rbcolumn\">\n";
echo "				<div id=\"photos\">\n";

						# rb_agency_option_galleryorder
						$rb_agency_options_arr = get_option('rb_agency_options');
						$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
											
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							if ($countImg > 1) { 
								echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" class=\"photo\" /></a>\n";
							} else {
								echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" class=\"photo\" /></a>\n";
							}
						}

echo "					<div class=\"rbclear\"></div>\n";
echo "				</div>\n"; // close #photos
echo "			</div>\n"; // close .rbcol-6

echo "			<div class=\"rbcol-3 rbcolumn\">\n";
echo "				<div id=\"stats\">\n";
echo "					<h2>". $ProfileContactDisplay ."</h2>\n";
echo "					<ul>\n";

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
echo "	  				</ul>\n"; // Close ul
echo "	  			</div>\n"; // Close Stats
echo "	  		</div>\n"; // Close .rbcol-3

echo "			<div class=\"rbcol-3 rbcolumn\">\n";
echo "				<div id=\"links\">\n";

					/*
					 * Include Action Icons
					 */
						include (plugin_dir_path(dirname(__FILE__)) .'/partial/include-profile-actions.php');



echo "				</div>\n";  // Close Links
echo "			</div>\n";  // Close .rbcol-3

echo "	  		<div class=\"rbcol-12 rbcolumn\">\n";
echo "	  			<div id=\"experience\">\n";
echo					$ProfileExperience;
echo "	  			</div>\n"; // Close Experience
echo "	  		</div>\n"; // Close .rbcol-12

echo "		  	<div class=\"rbclear\"></div>\n"; // Clear All
echo "  	</div>\n";  // Close Profile Zero
echo "		<div class=\"rbclear\"></div>\n"; // Clear All
echo "	</div>\n";  // Close Profile
?>
