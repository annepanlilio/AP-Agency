<?php
/*
Title:  Stats and Availability
Author: RB Plugin
Text:   Stats and Availability with Primary Photo and Thumbnails
*/

/*
 * Insert Style
 */

	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/15/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

/*
 * Insert Script
 */

	wp_register_script( 'photo-scroller', RBAGENCY_PLUGIN_URL .'view/layout/15/js/jquery.mCustomScrollbar.concat.min.js' );
	wp_enqueue_script( 'photo-scroller' );

	wp_register_script( 'init-scroller', RBAGENCY_PLUGIN_URL .'view/layout/15/js/init-scroller.js' );
	wp_enqueue_script( 'init-scroller' );



/*
 * Layout
 */

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];

$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-fifteen\" class=\"rblayout\">\n";

echo "  		<div class=\"rbcol-5 rbcolumn\">\n";
echo "				<div id=\"profile-picture\">\n";

						// images
						$private_profile_photo = get_user_meta($ProfileUserLinked,'private_profile_photo',true);
						$private_profile_photo_arr = explode(',',$private_profile_photo);
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"%s\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1 LIMIT 1";
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg, $ProfileID),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						$primary_image_handler = "";
						foreach($resultsImg as $dataImg ){
							$primary_image_handler = $dataImg['ProfileMediaURL'];
							if(!in_array($dataImg['ProfileMediaID'],$private_profile_photo_arr)){
								echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=450\"/></a>\n";
							}
							
						}
						if($countImg == 0){
							echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/demo-data/Placeholder.jpg&w=400&h=450&a=t\" alt=\"\">\n";
						}
						//get_social_media_links($ProfileID);
echo "				</div> <!-- #profile-picture -->\n";
echo "				<div id=\"profile-social\">";
						rb_agency_getSocialLinks($ProfileID);
echo "				</div>";
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

echo "						<div class=\"rbcol-6 rbcolumn\">\n";
echo "						<h2>". $ProfileContactDisplay ."</h2>\n";



echo '
<style>
div.profiledescription{
    white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word;       /* Internet Explorer 5.5+ */
display:block;
}
</style>
';
echo '<div class="profiledescription">'.$ProfileDescription.'</div>';




echo "							<div id=\"stats\">";
									if (!empty($ProfileGender) and $display_gender == true) {
										$fetchGenderData=  $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='%s' ",$ProfileGender),ARRAY_A);
										$count  = $wpdb->num_rows;
										if($count > 0){
											echo "<tr class=\"rb_gender\" id=\"rb_gender\"><td>". __("Gender", RBAGENCY_TEXTDOMAIN). " :</td><td>". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</td></tr>\n";
										}
									}

									// Insert Custom Fields
									$title_to_exclude = array("Available for");
									rb_agency_getProfileCustomFields($ProfileID, $ProfileGender, $table=true, $title_to_exclude, $label_tag="td", $value_tag="td");
									
echo "							</div> <!-- #stats -->\n";
echo "				<div id=\"soundcloud\">";
						$querySC = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType = \"SoundCloud\" ORDER BY $orderBy";
						$resultsSC=  $wpdb->get_results($wpdb->prepare($querySC, $ProfileID),ARRAY_A);
						$countSC  = $wpdb->num_rows;
						if($countSC > 0){
							echo "<h3>".__("SoundCloud",RBAGENCY_TEXTDOMAIN)."</h3>";
							foreach( $resultsSC as $dataSC ){
								echo RBAgency_Common::rb_agency_embed_soundcloud($dataSC['ProfileMediaURL']);
							}
						}
echo "				</div>";
echo "						</div>\n"; // .rbcol-6

echo "					<div class=\"rbcol-6 rbcolumn\">\n";
echo "						<div id=\"links\">\n";
echo "							<h2>".__("Available for:",RBAGENCY_TEXTDOMAIN)."</h2>";
echo "							<ul>";

									// Specify Hard Coded Custom Field ID#
									$customFieldID = 24;

									// Insert Custom Fields
									$array_do = $wpdb->get_results($wpdb->prepare("SELECT cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND c.ProfileCustomShowProfile = 1 AND cx.ProfileID = %d AND cx.ProfileCustomID = ". $customFieldID ." GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));
									$ProfileCustomValue = $array_do[0]->ProfileCustomValue;
									$array_ProfileCustomValue = explode( ',', $ProfileCustomValue );
									foreach ($array_ProfileCustomValue as $value) {
										echo "<li>". $value ."</li>";
									}

echo "							</ul>";
echo "						</div>\n";// #links
echo "					</div>\n";// .rbcol-6 ?>


<?php
//Experience
echo "					<div class=\"rbclear\"></div>\n"; // Clear All
echo "				</div> <!-- #info -->\n";//End Info
echo "			</div> <!-- #profile-l -->\n";

echo " 		</div>\n";// Close Profile Layout
echo "	</div>\n";// Close Profile
echo "	<div class=\"cb;\"></div>\n"; // Clear All



?>
