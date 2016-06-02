<?php
/*
Title:  Primary Image + Scrolling Thumbnails + Video Player
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Style
 */

	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/18/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

/*
 * Insert Script
 */

	wp_register_script( 'photo-scroller', RBAGENCY_PLUGIN_URL .'view/layout/18/js/jquery.mCustomScrollbar.concat.min.js' );
	wp_enqueue_script( 'photo-scroller' );

	wp_register_script( 'init-scroller', RBAGENCY_PLUGIN_URL .'view/layout/18/js/init-scroller.js' );
	wp_enqueue_script( 'init-scroller' );



/*
 * Layout 
 */


$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
$urlexploade = explode("/", $profileURLString);

if (isset($urlexploade[1])) {
	$subview = $urlexploade[1];
} else {
	$subview = "";
}

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];

$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-eighteen\" class=\"rblayout\">\n";

echo "  		<div class=\"rbcol-5 rbcolumn\">\n";
echo "				<div id=\"profile-picture\">\n";

						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"%s\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1 LIMIT 1";
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg, $ProfileID),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						$primary_image_handler = "";
						foreach($resultsImg as $dataImg ){
							$primary_image_handler = $dataImg['ProfileMediaURL'];
							echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=450\"/></a>\n";
						}
						if($countImg == 0){
							echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/demo-data/Placeholder.jpg&w=400&h=450&a=t\" alt=\"\">\n";
						}

echo "				</div> <!-- #profile-picture -->\n";
echo "			</div>\n"; // .rbcol-5

echo "  		<div class=\"rbcol-7 rbcolumn\">\n";
echo "				<div id=\"scroller\">\n";
echo "					<div id=\"photo-scroller\" class=\"scroller\">";
							// Image Slider

							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
							$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
							$countImg  = $wpdb->num_rows;
							foreach($resultsImg as $dataImg ){
								if ($countImg > 1) {
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=109&h=150\"  /></a>\n";
								} else {
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=109&h=150\" /></a>\n";
								}
							}
echo "					</div><!-- #photo-scroller -->"; //
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"rbclear\"></div>\n";

echo "				<div id=\"info-links\">\n";
echo "						<div id=\"name\" class=\"rbcol-12 rbcolumn\">";
echo "							<h2>". $ProfileContactDisplay ."</h2>";

								$query3 = "SELECT * FROM " . table_agency_data_type . " ORDER BY DataTypeTitle";
								$results3 =  $wpdb->get_results($query3,ARRAY_A);							

								foreach ($results3 as $data3) {
									if(is_array($ProfileType)){
										if (in_array($data3['DataTypeID'], $ProfileType)) {
											$profile_type[] = $data3['DataTypeTitle'];
										}									
									} else {
										if($data3['DataTypeID'] == $ProfileType){
											$profile_type[] = $data3['DataTypeTitle'];	
										}										
									}
								}

								$profile_types = implode(", ", $profile_type);
								echo "<p>".$profile_types."</p>";


echo "						</div>\n";
 
echo "						<div class=\"rbcol-6 rbcolumn\">\n";
echo "							<div id=\"stats\">\n";
	echo "							<ul>\n";

									if (!empty($ProfileGender) and $display_gender == true) {
										$fetchGenderData=  $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='%s' ",$ProfileGender),ARRAY_A);
										$count  = $wpdb->num_rows;
										if($count > 0){
												echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> ". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</li>\n";
										}
									}

									// Insert Custom Fields
									rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
									if(function_exists('get_social_media_links')) {
										get_social_media_links($ProfileID);
									}
									
echo "								</ul>\n";


echo "							</div>\n"; // #stats
echo "				<div id=\"soundcloud\">";
						$querySC = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType = \"SoundCloud\" ORDER BY $orderBy";
						$resultsSC=  $wpdb->get_results($wpdb->prepare($querySC, $ProfileID),ARRAY_A);
						$countSC  = $wpdb->num_rows;
						if($countSC > 0){
							echo "<h3>SoundCloud</h3>";
							foreach( $resultsSC as $dataSC ){
								echo RBAgency_Common::rb_agency_embed_soundcloud($dataSC['ProfileMediaURL']);
							}
						}
echo "				</div>";
echo "						</div>\n"; // .rbcol-6

echo "					<div class=\"rbcol-6 rbcolumn\">\n";
echo "						<div id=\"links\">\n";
if(isset($AgencyName)){
echo "							<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
}
					/*
					 * Include Action Icons
					 */

						include (plugin_dir_path(dirname(__FILE__)) .'/partial/include-profile-actions.php');



echo "						</div>\n";// #links
echo "					</div>\n";// .rbcol-6 ?>


<?php
//Experience
echo "					<div class=\"rbclear\"></div>\n"; // Clear All
echo "				</div> <!-- #info -->\n";//End Info
echo "			</div> <!-- #profile-l -->\n";

$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
													AND (ProfileMediaType = 'Video Slate' OR 
													ProfileMediaType = 'Video Monologue' OR
													ProfileMediaType = 'Demo Reel')";
					$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
					$countMedia  = $wpdb->num_rows;
					if ($countMedia > 0) {
echo "					<div id=\"videos\" class=\"rbcol-12 rbcolumn\">\n";
							//Videos							
							foreach($resultsMedia as $dataMedia ){
								$media_url = $dataMedia['ProfileMediaURL'];
								$embed_string = substr($media_url, strpos($media_url, "=") + 1);
								echo "<div class=\"item video slate\">";
								if($dataMedia['ProfileVideoType'] == "youtube" || $dataMedia['ProfileVideoType'] == ""){
									echo "<iframe style='width:350px; height:200px;'  src='//www.youtube.com/embed/".$embed_string."?showinfo=0' frameborder='0' allowfullscreen></iframe>";
								} elseif($dataMedia['ProfileVideoType'] == "vimeo") {
									echo '<iframe style="width:350px; height:200px;" src="//player.vimeo.com/video/'.$embed_string.'?portrait=0&amp;badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								}
								echo "</div>\n";
							}
echo "					</div>\n";// #videos
					}

echo " 		</div>\n";// Close Profile Layout
echo "	</div>\n";// Close Profile
echo "	<div class=\"cb\"></div>\n"; // Clear All
?>