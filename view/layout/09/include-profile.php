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

						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							if ($countImg > 1) {
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"/></a>\n";
								} else {
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
								}
							}
echo "					</div><!-- .scroller -->";
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"rbclear\"></div>\n";

echo "				<div id=\"info\">\n";
echo "					<div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";

							// Social Link
							rb_agency_getSocialLinks();

echo "						<div id=\"stats\" class=\"rbcol-12 rbcolumn\">\n";
echo "							<ul>\n";

								if (!empty($ProfileGender) and $display_gender == true) {
									$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' "),ARRAY_A,0 	);
									$count = $wpdb->num_rows;
									if($count > 0){
										echo "<li class=\"rb_gender\" id=\"rb_gender\"><span class=\"stat-label\">". __("Gender", RBAGENCY_TEXTDOMAIN). ": </span><span class=\"stat-value\">". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</span></li>\n";
									}
								}

								// Insert Custom Fields
								$title_to_exclude = array("");
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender, $table=false, null);								

echo "							</ul>\n";
echo "						</div>\n";
echo "				</div> <!-- #info -->\n";//End Info

					// Links
					echo "<div id=\"links\">";
					echo "<div class=\"row\">";
					get_social_media_links($ProfileID);
					echo "</div> <!-- .row -->";
					echo "</div> <!-- #links -->";

					// Files
					$queryResume = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType IN('Resume')";
					$resultsResume =  $wpdb->get_results($queryResume,ARRAY_A);
					$countResume = $wpdb->num_rows;

					if($countResume > 0) {						
						echo "<div id=\"files\">";
						echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\">";
						echo "<h3>Files</h3>";
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
						echo "<div id=\"files\">";
						echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\">";
						echo "\n\n";
						echo "<h3>Voice Demo</h3>";
						
						foreach ($resultsVoice as $dataVoice) {
							
								$audiofile = RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataVoice['ProfileMediaURL'];
							echo "<div class=\"media-file voicedemo\" style=\"width:50%;\">";
								
								$key_voice = 'voicedemo_' . $dataVoice['ProfileMediaID'];
								$voiceTitle = get_option($key_voice,'Voice Demo');
								echo  $voiceTitle;
								if(defined("SC_AUDIO_PLUGIN_VERSION")){
									echo do_shortcode('[sc_embed_player_template1 fileurl="'.site_url($audiofile).'"]');
								}else{
									echo '<audio><source src="'.site_url($audiofile).'" /></audio><br>';
								}
								
								//echo $audiofile;	
							
							echo "\n\n";
							echo "</div>";
							
						}
						echo "\n\n";
						echo "</div> <!-- .col-md-12 -->";
						echo "</div> <!-- #row -->";
						echo "</div> <!-- #files -->";
					}
					
					
						

					// Videos
					$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileVideoType IN('youtube','vimeo')";
					$resultsMedia =  $wpdb->get_results($queryMedia,ARRAY_A);
					$countMedia = $wpdb->num_rows;					

					$viditem_class = array("profile-video");
					if($countMedia >= 3) {
						$viditem_class[] = "col-md-4";
					} else {
						$viditem_class[] = "col-md-6";
					}
					$vidcol_class = implode(" ",$viditem_class);

					if($countMedia > 0) {
						echo "<div id=\"videos\">";
						echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\"><h3>Videos</h3></div>";
						foreach ($resultsMedia  as $dataMedia) {
							$vid_url = $dataMedia['ProfileMediaURL'];
							$clean_title = stripslashes($dataMedia['ProfileMediaTitle']);
							$vidTitleCaption = explode('<br>',$clean_title);
							if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
								$embed_string = substr($vid_url, strpos($vid_url, "=")+1);
								$outVideoMedia .= "<div class=\"".$vidcol_class."\"><div class=\"video-wrapper\"><iframe width=\"640\" height=\"360\" src=\"https://www.youtube.com/embed/".$embed_string."\" frameborder=\"0\" allowfullscreen></iframe></div><div>".$vidTitleCaption[0]."</div></div>";
							}
						}
						echo $outVideoMedia;

						echo "</div><!-- .row -->";
						echo "</div><!-- #videos -->";
					}
echo "			<div class=\"rbclear\"></div>\n";

echo " 		</div> <!-- .rblayout -->\n";
echo "	</div> <!-- #rbprofile -->\n"; 
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>