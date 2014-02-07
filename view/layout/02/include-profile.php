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
echo " 		<div id=\"rblayout-two\" class=\"rblayout\">\n";

echo "  		<div class=\"rbcol-7 rbcolumn\">\n";
echo "				<div id=\"scroller\">\n";
echo "					<div id=\"photo-scroller\" class=\"scroller\">";
							// Image Slider
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							while ($dataImg = mysql_fetch_array($resultsImg)) {
								if ($countImg > 1) { 
									echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"/></a>\n";
								} else {
									echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
								}
							}
echo "					</div><!-- #photo-scroller -->"; //
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"rbclear\"></div>\n";

echo "				<div id=\"info-links\">\n";
echo "	  				<div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";

 
echo "	  				<div class=\"rbcol-6 rbcolumn\">\n";
echo "	  					<div id=\"stats\">\n";
	echo "	  					<ul>\n";

									if (!empty($ProfileGender)) {
										$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
										$count = mysql_num_rows($queryGenderResult);
										if($count > 0){
											$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
											echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
										}
									}

									// Insert Custom Fields
									rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);    
echo "	  						</ul>\n";
echo "	  					</div>\n"; // #stats
echo "	  				</div>\n"; // .rbcol-6

echo "					<div class=\"rbcol-6 rbcolumn\">\n";
echo "						<div id=\"links\">\n";
echo "							<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";

					/*
					 * Include Action Icons
					 */

						include (plugin_dir_path(dirname(__FILE__)) .'/partial/include-profile-actions.php');



echo "						</div>\n";// #links
echo "					</div>\n";// .rbcol-6 ?>


<?php
//Experience
echo "		  			<div id=\"experience\" class=\"rbcol-12 rbcolumn\">\n";
echo						$ProfileExperience;
echo "		  			</div>\n";
echo "					<div class=\"rbclear\"></div>\n"; // Clear All					
echo "				</div> <!-- #info -->\n";//End Info
echo "			</div> <!-- #profile-l -->\n";

echo "  		<div class=\"rbcol-5 rbcolumn\">\n";
echo "				<div id=\"profile-picture\">\n";

						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}

echo "				</div> <!-- #profile-picture -->\n";
echo "			</div>\n"; // .rbcol-5
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"cb;\"></div>\n"; // Clear All
?>