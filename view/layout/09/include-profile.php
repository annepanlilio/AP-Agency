<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/09/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'scroller-style', RBAGENCY_PLUGIN_URL .'view/layout/09/css/jquery.mCustomScrollbar.min.css' );
	wp_enqueue_style( 'scroller-style' );

	wp_register_style( 'fancybox-style', RBAGENCY_PLUGIN_URL .'ext/fancybox/jquery.fancybox.css' );
	wp_enqueue_style( 'fancybox-style' );

/*
 * Insert Script
 */
	wp_deregister_script( 'jquery-latest' );
	wp_register_script( 'jquery-latest', "//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
	wp_enqueue_script( 'jquery-latest' );

	if(!wp_script_is('jquery-lightbox')) {
		wp_enqueue_script( 'lightbox2-footer', RBAGENCY_PLUGIN_URL .'ext/lightbox2/js/lightbox-2.6.min.js', array( 'jquery-latest' ));
		wp_enqueue_script( 'lightbox2-footer' );	
	}	

	wp_register_script( 'photo-scroller', RBAGENCY_PLUGIN_URL .'view/layout/09/js/jquery.mCustomScrollbar.concat.min.js', '', 1, true );
	wp_enqueue_script( 'photo-scroller' );

	wp_register_script( 'init-scroller', RBAGENCY_PLUGIN_URL .'view/layout/09/js/init-scroller.js', '', 1, true );
	wp_enqueue_script( 'init-scroller' );

	wp_enqueue_script( 'fancybox-jquery', RBAGENCY_PLUGIN_URL .'ext/fancybox/jquery.fancybox.pack.js', array( 'jquery-latest' ));
	wp_enqueue_script( 'fancybox-jquery' );

	wp_enqueue_script( 'fancybox-init', RBAGENCY_PLUGIN_URL .'ext/fancybox/fancybox.init.js', array( 'jquery-latest', 'fancybox-jquery' ));
	wp_enqueue_script( 'fancybox-init' );

/*
 * Layout
 */

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
// load script for printing profile pdf
$row = 4 ;
$rb_agency_options_arr = get_option('rb_agency_options');
$logo = $rb_agency_options_arr['rb_agency_option_agencylogo'];

$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;

rb_load_profile_pdf($row,$logo);

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-nine\" class=\"rblayout\">\n";

echo "				<div id=\"scroller\">\n";
echo "					<div id=\"photo-scroller\" class=\"scroller\">";
						// Image Slider

						$private_profile_photo = get_user_meta($ProfileUserLinked,'private_profile_photo',true);
						$private_profile_photo_arr = explode(',',$private_profile_photo);
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							if ($countImg > 1) {
								if(!in_array($dataImg['ProfileMediaID'],$private_profile_photo_arr)){
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"/></a>\n";
								}								
							} else {
								if(!in_array($dataImg['ProfileMediaID'],$private_profile_photo_arr)){
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
								}
							}
						}
echo "					</div><!-- .scroller -->";
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"rbclear\"></div>\n";

echo "				<div id=\"info\" class=\"rb-section container-fluid\">\n";
echo "					<div class=\"row\">\n";
echo "						<header>\n";
echo "							<h2>". $ProfileContactDisplay ."</h2>\n";
echo "						</header>\n";

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



	

							// Social Link
							rb_agency_getSocialLinks();

echo "						<div id=\"stats\" class=\"col-md-12\">\n";
echo "							<ul>\n";
								if (!empty($ProfileGender) and $display_gender == true) {
									$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID=%d ",$ProfileGender),ARRAY_A,0 	);
									$count = $wpdb->num_rows;
									if($count > 0){
										echo "<li class=\"rb_gender\" id=\"rb_gender\"><span class=\"stat-label\">". __("Gender", RBAGENCY_TEXTDOMAIN). ": </span><span class=\"stat-value\">". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</span></li>\n";
									}
								}

								// Insert Custom Fields
								$title_to_exclude = array("");
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender, $table=false, null, $label_tag="span", $value_tag="strong");

echo "							</ul>\n";
echo "						</div>\n";
echo "					</div> <!-- .row -->\n";//End Info
echo "				</div> <!-- #info -->\n";//End Info

					// Links
					$profileSocialLinks = get_social_media_links($ProfileID, true);
					
					if($profileSocialLinks) {
echo "					<div id=\"links\" class=\"rb-section container-fluid\">";
echo "						<div class=\"row\">";
echo "							<header>\n";
echo "								<h3>".__("Social Media Links",RBAGENCY_TEXTDOMAIN)."</h3>";
echo "							</header>\n";
echo								$profileSocialLinks;
echo "						</div> <!-- .row -->";
echo "					</div> <!-- #links -->";
					}
					

					// Files
					$queryResume = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType IN('Resume')";
					$resultsResume =  $wpdb->get_results($queryResume,ARRAY_A);
					$countResume = $wpdb->num_rows;

					if($countResume > 0) {						
						echo "<div id=\"files\" class=\"rb-section container-fluid\">";
						echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\">";
echo "					<header>\n";
echo "						<h3>Files</h3>";
echo "					</header>\n";
						foreach ($resultsResume as $dataResume) {
							echo "<div class=\"media-file resume\"><a href=\"" . RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataResume['ProfileMediaURL'] . "\" target=\"_blank\" title=\"" . $dataResume['ProfileMediaTitle'] . "\">".__("Resume &#8595;",RBAGENCY_TEXTDOMAIN)."</a></div>";
						}
						echo "</div> <!-- .col-md-12 -->";
						echo "</div> <!-- #row -->";
						echo "</div> <!-- #files -->";
					}
					
					
					
					
					// Voice Demo
					$queryVioce = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType = \"VoiceDemo\"";
					$resultsVoice = $wpdb->get_results($wpdb->prepare($queryVioce, $ProfileID),ARRAY_A);
					$countVoice = $wpdb->num_rows;
					
					if($countVoice > 0) {						
						echo "<div id=\"voice-demo\" class=\"rb-section container-fluid\">";
						echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\">";
echo "						<header>\n";
echo "							<h3>Voice Demo</h3>";
echo "						</header>\n";
						
						foreach ($resultsVoice as $dataVoice) {
							
							$audiofile = RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataVoice['ProfileMediaURL'];
							echo "<div class=\"media-file voicedemo\">";
								
								$key_voice = 'voicedemo_' . $dataVoice['ProfileMediaID'];
								$key_voice_caption = 'voicedemocaption_' . $dataVoice['ProfileMediaID'];
								$voiceTitle = get_option($key_voice,'Voice Demo');
								$voiceCaption = get_option($key_voice_caption,'Voice Demo Caption');
								echo  "<h6>".$voiceTitle."</h6>";
								echo "<small>".$voiceCaption."</small><br />";

								// if(defined("SC_AUDIO_PLUGIN_VERSION")){
								// 	echo do_shortcode('[sc_embed_player fileurl="'.site_url($audiofile).'"]');
								// } else {
								// 	echo '<audio><source src="'.site_url($audiofile).'" type="audio/mpeg"/></audio>';
								// }

								echo '<audio controls><source src="'.site_url($audiofile).'" type="audio/mpeg"/></audio>';								
								
								//echo $audiofile;	
							
							echo "</div>";
							
						}
						echo "\n\n";
						echo "</div> <!-- .col-md-12 -->";
						echo "</div> <!-- #row -->";
						echo "</div> <!-- #files -->";
					}
					
					// Videos
					$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType IN('Demo Reel','Video Monologue','Video Slate')";
					$resultsMedia =  $wpdb->get_results($queryMedia,ARRAY_A);
					$countMedia = $wpdb->num_rows;

					if($countMedia > 0) {
echo "					<div id=\"videos\" class=\"rb-section container-fluid\">";
echo "						<div class=\"row\">";
echo "							<header>\n";
echo "								<h3>Videos</h3>";
echo "							</header>\n";
echo "							<div class=\"col-md-12\">\n";
									foreach ($resultsMedia  as $dataMedia) {
										$vid_url = $dataMedia['ProfileMediaURL'];
										$clean_title = stripslashes($dataMedia['ProfileMediaTitle']);
										$vidTitleCaption = explode('<br>',$clean_title);
										if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
											$embed_string = substr($vid_url, strpos($vid_url, "="));
											$outVideoMedia .= "<div class=\"profile-video\">
											<div style=\"margin: 5px;\">".$vidTitleCaption[0]."</div>
											<div class=\"video-wrapper\"><iframe width=\"640\" height=\"360\" src=\"https://www.youtube.com/embed/".$embed_string."\" frameborder=\"0\" allowfullscreen></iframe></div></div>";
										}
									}
									echo $outVideoMedia;

echo "							</div><!-- .col-md-12 -->";
echo "						</div><!-- .row -->";
echo "					</div><!-- #videos -->";
					}
echo "<br>";
echo '<div class="profiledescription">'.(!empty($ProfileDescription) ? $ProfileDescription : "").'</div>';
echo '<div class="profileresume" style="padding:35px;">'.(!empty($ProfileResume) ? str_replace("\n", "<br>", $ProfileResume) : "").'</div>';
echo "			<div class=\"rbclear\"></div>\n";

echo " 		</div> <!-- .rblayout -->\n";
echo "	</div> <!-- #rbprofile -->\n"; 
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>