<?php

/*

Title:  Scrolling

Author: RB Plugin

Text:   Profile View with Scrolling Thumbnails and Primary Image

*/



/*

 * Insert Style

 */



	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/13/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'owl-carousel-css', RBAGENCY_PLUGIN_URL .'view/layout/13/css/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-css' );



/*

 * Insert Script

 */



	// wp_register_script( 'photo-scroller', RBAGENCY_PLUGIN_URL .'view/layout/13/js/jquery.mCustomScrollbar.concat.min.js' );
	// wp_enqueue_script( 'photo-scroller' );

	// wp_register_script( 'init-scroller', RBAGENCY_PLUGIN_URL .'view/layout/13/js/init-scroller.js' );
	// wp_enqueue_script( 'init-scroller' );

	wp_register_script( 'owl-carousel-js', RBAGENCY_PLUGIN_URL .'view/layout/13/js/owl.carousel.min.js' );
	wp_enqueue_script( 'owl-carousel-js' );







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

echo " 		<div id=\"rblayout-thirteen\" class=\"rblayout\">\n";



// echo "  		<div id=\"profilepic-scroller\">\n";

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
								echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=340&a=t\"/></a>\n";
							}
							
						
						}

						if($countImg == 0){

							echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". get_bloginfo("url")."/wp-content/plugins/rb-agency/assets/demo-data/Placeholder.jpg&h=340&a=t\" alt=\"\">\n";

						}



echo "				</div> <!-- #profile-picture -->\n";

echo "				<div id=\"profile-info\">\n";

echo "					<h2>". $ProfileContactDisplay ."</h2>";

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


echo "							<div id=\"stats\">\n";

echo "							<ul>\n";



								if (!empty($ProfileGender) and $display_gender == true) {

									$fetchGenderData=  $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='%s' ",$ProfileGender),ARRAY_A);

									$count  = $wpdb->num_rows;

									if($count > 0){

											echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong  class='label'>". __("Gender", RBAGENCY_TEXTDOMAIN). " <span>:</span></strong> <span class='value'>". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</span></li>\n";

									}

								}



								// Insert Custom Fields

								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
								get_social_media_links($ProfileID);

echo "								</ul>\n";

echo "							</div>\n"; // #stats

echo "				</div> <!-- #profile-info -->\n";//End Info

echo "			</div>\n"; // .rbcol-5



echo "  		<div class=\"rbcol-7 rbcolumn\">\n";

echo "				<div id=\"scroller\">\n";

echo "					<div id=\"photo-scroller\" class=\"owl-carousel\">";

							// Image Slider



							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");

							$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);

							$countImg  = $wpdb->num_rows;
							$ctr = 1;
							foreach($resultsImg as $dataImg ){

								if ($countImg > 1) {

									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ." data-hash=\"slide-".$ctr."\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=600\"  /></a>\n";
									// echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"  /></a>\n";

								} else {

									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ." data-hash=\"slide-".$ctr."\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=600\" /></a>\n";
									// echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ." ". $reltarget ."><img src=\"".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";

								}
							$ctr++;
							}

echo "					</div><!-- #photo-scroller -->"; //

echo "				</div><!-- #scroller -->\n";

echo "				<div id=\"photos\" class=\"lightbox-enabled profile-photos\">\n";



							// images

							//$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType IN(\"Image\")  AND ProfileMediaPrimary = 0 ORDER BY $orderBy";

							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");

							$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);

							$countImg = $wpdb->num_rows;

							$ctr = 1;
							foreach($resultsImg as $dataImg ){

								if($primary_image_handler != $dataImg['ProfileMediaURL']){

									echo "<div class=\"photo\">";

									// echo "	<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=150\" /></a>";
									echo "	<a href=\"#slide-".$ctr."\" class=\"url\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=150\" /></a>";

									if($rb_agency_option_profile_thumb_caption ==1) {

										echo "	<small>".$dataImg['ProfileMediaURL']."</small>\n";
									
									}

									echo "</div>\n";

								}
								$ctr++;
							}



echo "				</div>\n"; // #photos

echo "			</div><!-- #.rbcol-7 -->\n";

// echo "			</div><!-- #profilepic-scroller -->\n";



echo "			<div class=\"rbclear\"></div>\n";



echo "			<div class=\"rbcol-5 rbcolumn\">\n";



echo "			</div> <!-- .rbcol-5 -->\n";



echo "			<div class=\"rbcol-7 rbcolumn\">\n";



echo "			</div> <!-- .rbcol-7 -->\n";



echo " 		</div>\n";// Close Profile Layout

echo "	</div>\n";// Close Profile

echo "	<div class=\"cb;\"></div>\n"; // Clear All

?>

