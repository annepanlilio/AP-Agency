<?php
/*
Title:  Booking
Author: RB Plugin
Text:   Profile View with Booking button
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/17/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );
/*
 * Layout 
 */

$rb_agency_options_arr 					= get_option('rb_agency_options');
$order 									= isset($rb_agency_options_arr['rb_agency_option_galleryorder'])?$rb_agency_options_arr['rb_agency_option_galleryorder']:0;
$website 								= parse_url($ProfileContactWebsite, PHP_URL_HOST);
$booking_link 							= isset($rb_agency_options_arr['rb_agency_option_bookinglink']) ? $rb_agency_options_arr['rb_agency_option_bookinglink']:false;
$display_gender 						= isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;
$rb_agency_option_unittype 				= isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;
$rb_agency_option_profile_thumb_caption = isset($rb_agency_options_arr['rb_agency_option_profile_thumb_caption'])?$rb_agency_options_arr['rb_agency_option_profile_thumb_caption']:0;

//Photos
$queryImg 	= rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
$countImg 	= $wpdb->num_rows;

// IMDB
$queryLinks 	= "SELECT ProfileMediaType, ProfileMediaURL, ProfileMediaTitle FROM " . table_agency_profile_media . " WHERE ProfileID =  '%d' AND ProfileMediaType <> \"Image\"";
$resultsLinks 	= $wpdb->get_results($wpdb->prepare($queryLinks, $ProfileID),ARRAY_A);
$countLinks 	= $wpdb->num_rows;

echo "	<div id=\"rbprofile\">\n";
echo "		<div id=\"rblayout-seventeen\" class=\"rblayout\">\n";

// Header
echo "			<div class=\"rbcol-12 rbcolumn\">\n";
echo "				<header class=\"profile-header\">";
echo "					<h2 class=\"profile-title\">". __($ProfileContactDisplay, RBAGENCY_TEXTDOMAIN) ."</h2>";
echo "				<div id=\"stats\">\n";
echo "					<ul>\n";

							if (!empty($ProfileAge)) {
								echo "<li class=\"rb_age\" id=\"rb_age\"><strong>". __("Age", RBAGENCY_TEXTDOMAIN). "<span >:</span></strong> ". $ProfileAge. "</li>\n";
							}							
							if (!empty($ProfileGender) and $display_gender == true) {
								$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='%s' ",$ProfileGender),ARRAY_A,0 );
								echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). "<span >:</span></strong> ". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</li>\n";
							}
							if (!empty($ProfileStatHeight)) {
								if ($rb_agency_option_unittype == 0) { // Metric
									echo "<li  class=\"rb_height\" id=\"rb_height\"><strong>". __("Height", RBAGENCY_TEXTDOMAIN). "<span >:</span></strong> ". $ProfileStatHeight ." ". __("cm", RBAGENCY_TEXTDOMAIN). "" ."</li>\n";
								} else { // Imperial
									$heightraw = $ProfileStatHeight;
									$heightfeet = floor($heightraw/12);
									$heightinch = $heightraw - floor($heightfeet*12);
									echo "<li  class=\"rb_height\" id=\"rb_height\"><strong>". __("Height", RBAGENCY_TEXTDOMAIN). "<span >:</span></strong> ". $heightfeet ." ". __("ft", RBAGENCY_TEXTDOMAIN). " ". $heightinch ." ". __("in", RBAGENCY_TEXTDOMAIN). "" ."</li>\n";
								}
							}
							if (!empty($ProfileStatWeight)) {
								if ($rb_agency_option_unittype == 0) { // Metric
									echo "<li   class=\"rb_weight\" id=\"rb_weight\">><strong>". __("Weight", RBAGENCY_TEXTDOMAIN). "<span >:</span></strong> ". $ProfileStatWeight ." ". __("kg", RBAGENCY_TEXTDOMAIN). "</li>\n";
								} else { // Imperial
									echo "<li   class=\"rb_weight\" id=\"rb_weight\">><strong>". __("Weight", RBAGENCY_TEXTDOMAIN). "<span >:</span></strong> ". $ProfileStatWeight ." ". __("lb", RBAGENCY_TEXTDOMAIN). "</li>\n";
								}
							}

							rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
							
							if(isset($rb_agency_option_showcontactpage) && $rb_agency_option_showcontactpage==1){
								echo "<li   class=\"rel rb_contact\" id=\"rb_contact\">><strong>". __("Contact: ", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";
							}
							if($ProfileIsBooking == 0 && $countLinks == 0){
								$site_label = ($website == "") ? $ProfileContactWebsite : $website;
								echo "<li class=\"website\"><a href=\"".$ProfileContactWebsite."\" title=\"\" target=\"_blank\">".__($site_label, RBAGENCY_TEXTDOMAIN)."</a></li>";
							}

echo "					</ul>\n"; // Close ul
						if($ProfileIsBooking == 1 || $countLinks > 0){
							// Insert Custom Fields
							get_social_media_links($ProfileID);
						}

echo "				</div>\n"; // Close Stats
echo "			</header>";
echo "		</div>\n"; // .rbcol-12

if($ProfileIsBooking == 1 || $countLinks > 0){  // Booking Enabled

	echo "		<div class=\"rbcol-6 rbcolumn\">\n";
	echo "			<div id=\"photos\" class=\"booking\">\n";
					
					foreach($resultsImg as $dataImg ){
						if ($countImg > 1) {
							echo "<div class=\"photo\">";
							echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=360\"/></a>";
							if($rb_agency_option_profile_thumb_caption ==1) {
								echo "	<small>".__($dataImg['ProfileMediaURL'], RBAGENCY_TEXTDOMAIN)."</small>\n";
							}
							echo "</div>\n";
						} else {
							echo "<div class=\"photo\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=360\"/></a></div>\n";
						}
					}
	echo "			</div>\n"; // #photos

	echo "		</div>\n"; // .rbcol-6
	echo "		<div class=\"rbcol-6 rbcolumn\">\n";
	echo "			<div id=\"profile-actions\">\n";
						
							echo "<ul>";
								
								$website = parse_url($ProfileContactWebsite, PHP_URL_HOST);
								echo "<li class=\"website\"><a href=\"".$ProfileContactWebsite."\" title=\"\" target=\"_blank\">".__($website, RBAGENCY_TEXTDOMAIN)."</a></li>";
							
								if ($countLinks > 0) {
									foreach ($resultsLinks  as $dataLinks) {
										$linkLabel = ( $dataLinks['ProfileMediaType'] == 'Link') ? $dataLinks['ProfileMediaTitle'] : $dataLinks['ProfileMediaType'];
										echo "	<li>\n";
										echo "		<a href='". $dataLinks['ProfileMediaURL'] ."' target='_blank'>". __($linkLabel, RBAGENCY_TEXTDOMAIN) ."</a>\n";
										echo "	</li>\n";
									}
								}

							echo "</ul>";
							if($ProfileIsBooking == 1){
								echo " <br><a href=\"".$booking_link."\" title=\"\" class=\"book-now\">".__("Book ".$ProfileContactNameFirst."", RBAGENCY_TEXTDOMAIN)."</a>\n"; // #photos
							}
	
	echo "			</div>\n"; // #photos

	echo "		</div>\n"; // .rbcol-6

} else { // Booking Disabled

	echo "		<div class=\"rbcol-12 rbcolumn\">\n";
	echo "			<div id=\"photos\">\n";
					
					foreach($resultsImg as $dataImg ){
						if ($countImg > 1) {
							echo "<div class=\"photo\">";
							echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=360\"/></a>";
							if($rb_agency_option_profile_thumb_caption ==1) {
								echo "	<small>".$dataImg['ProfileMediaURL']."</small>\n";
							}
							echo "</div>\n";
						} else {
							echo "<div class=\"photo\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=360\"/></a></div>\n";
						}
					}
	echo "			</div>\n"; // #photos
	echo "		</div>\n"; // .rbcol-12	

}

echo "		<div class=\"rbclear\"></div>\n"; // Clear All
echo "		</div>\n"; // .rblayout
echo "	</div>\n"; // #rbprofile

?>