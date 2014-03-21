<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Stylesheet
 */
	wp_register_style( 'rblayout-style', plugins_url('/css/style.css', __FILE__) );
	wp_enqueue_style( 'rblayout-style' );

/*
 * Insert Script
 */

	wp_register_script( 'layout-tab', plugins_url('/js/layout-tab.js', __FILE__) );
	wp_enqueue_script( 'layout-tab' );

/*
 * Layout 
 */
# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
?>
<script type="text/javascript">

jQuery(document).ready(function(){

	// tab functionality
	jQuery(".maintab").click(function(){
		   var idx = this.id;
		   var elem = "." + idx;
		   var elem_id = "#" + idx;
		   if ((idx=="row-all")){
					jQuery(".tab").hide();
					jQuery(".tab").show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
					jQuery(".tab-active").removeClass("tab-active").addClass("tab-inactive");
			} else {
				  if(idx=="row-bookings"){
						var url = "<?php echo get_permalink(get_page_by_title('booking')); ?>";
						window.location = url;
				  } else {
						  jQuery(".tab-active").removeClass("tab-active").addClass("tab-inactive");
						  jQuery(".tab").css({ opacity: 1.0 }).stop().animate({ opacity: 0.0 }, 2000).hide();
						  jQuery(elem).show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
						  jQuery(elem_id).removeClass("tab-inactive").addClass("tab-active");
				  }
		   }
	});
	
});
</script>
<?php
/*
Expended Profile with Tabs
*/

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-three\" class=\"rblayout\">\n";
echo " 			<div class=\"rbcol-12 rbcolumn\">\n";
echo " 				<div id=\"go-back\">\n";
echo "   				<a href=\"". get_bloginfo("wpurl") ."/profile-category/\">Go Back</a>\n";
echo " 				</div>\n";
echo " 			</div>\n";

echo " 			<div id=\"profile-overview\">\n";

// Column 1
echo "		  		<div class=\"rbcol-4 rbcolumn\">\n";

						echo "<div id=\"profile-picture\">\n";

						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}

						echo "</div> <!-- #profile-picture -->\n";

							
						/*  Use this instead of text;
						 *  this will display heart and star for favorite and casting respectively.
						 *  This can update database for favorites and casting cart
						 */
						echo '<input type="hidden" id="aps12-id" value="'. $ProfileID .' - ' .rb_agency_get_current_userid().'">';
						
						if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
							echo rb_agency_get_new_miscellaneousLinks($ProfileID);
						} 
echo '					<div id="resultsGoHereAddtoCart"></div>';
echo "	  			</div> <!-- #profile-picture -->\n";

// Column 2
echo "	  			<div class=\"rbcol-5 rbcolumn\">\n";
echo "	  				<div id=\"profile-info\">\n";

echo "	      				<h1>". $ProfileContactDisplay ."</h1>\n";
echo "	      				<p>\n";
								if (isset($ProfileDateBirth)) {
echo "								<span class=\"age\">". rb_agency_get_age($ProfileDateBirth) ."</span>\n";
								}
								if (isset($ProfileLocationCity)) {
echo "								from <span class=\"city\"> ". $ProfileLocationCity ."</span>,<span class=\"state\"> ". $ProfileLocationState ."</span>\n";
								}
echo "	      				</p>\n";
echo "		  				<ul>\n";	


								$queryType = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID IN ($ProfileType) ORDER BY DataTypeTitle";
								$resultsType=  $wpdb->get_results($wpdb->prepare($queryType ),ARRAY_A);
								foreach($resultsType as $dataType ){
									echo "<li>". $dataType["DataTypeTitle"] ."</li>";
								}
echo "		  				</ul>\n";

							// Social Link
							rb_agency_getSocialLinks();
echo "		  				<p>". $ProfileExperience ."</p>\n";

echo "	  				</div> <!-- #profile-info -->\n";
echo "	  			</div> <!-- .rbcol-5 -->\n";

					// Column 3
echo "			  	<div class=\"rbcol-3 rbcolumn\">\n";
echo "			  		<div id=\"profile-actions\">\n";

							//Contact Profile
							if (isset($rb_agency_option_agency_urlcontact) && !empty($rb_agency_option_agency_urlcontact)) {
echo "	      					<div id=\"profile-actions-contact\"><span><a href=\"". $rb_agency_option_agency_urlcontact ."\">". __("Contact", rb_agency_TEXTDOMAIN). " ". $ProfileClassification ."</a></span></div>\n";
echo "							<li class=\"item contact\"></li>\n";
							}

echo "	      				<p id=\"profile-views\"><strong>". $ProfileStatHits ."</strong> Profile Views</p>\n";

							// added this links to be positioned here in substitute
							// for the favorited label
echo '	      				<div id="profile-links">';
								if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
								echo rb_agency_get_new_miscellaneousLinks($ProfileID);
							} 		
echo '						</div>';

echo "	  				</div> <!-- #profile-actions -->\n";
echo "	  			</div> <!-- .rbcol-3 -->\n";
echo "				<div class=\"rbclear\"></div>\n"; // Clear All
echo " 			</div>\n"; // #profile-overview

echo "			<div class=\"rbclear\"></div>\n"; // Clear All
echo " 			<div id=\"rb-tabs\">\n";
echo " 				<div class=\"rbcol-12 rbcolumn row-two \">\n";
echo " 					<div id=\"tabs\">\n";
echo "   					<div id=\"subMenuTab\">\n";
echo " 							<div class=\"maintab tab-left tab-active\" id=\"row-all\">\n";
echo " 								<a href=\"#space\">\n";
echo " 			  						<div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">All</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";
echo " 							<div class=\"maintab tab-inner tab-inactive\" id=\"row-photos\">\n";
echo " 								<a href=\"#space\">\n";
echo " 			  						<div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Photos</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";
echo " 							<div class=\"maintab tab-inner tab-inactive\" id=\"row-physical\">\n";
echo " 								<a href=\"#space\">\n";
echo " 			  						<div class=\"subMenuTabBG\" ><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Physical Details</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";
echo " 							<div class=\"maintab tab-inner tab-inactive\" id=\"row-videos\">\n";
echo " 								<a href=\"#space\">\n";
echo " 			  						<div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Videos</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";
echo " 							<div class=\"maintab tab-inner tab-inactive\" id=\"row-experience\">\n";
echo " 								<a href=\"#space\">\n";
echo " 			  						<div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Experience</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";
if(function_exists('rb_agency_casting_menu')){
echo " 							<div class=\"maintab tab-inner tab-inactive\" id=\"row-bookings\">\n";
echo " 								<a href=\"/profile-casting/\">\n";
echo " 			  						<div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Booking</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";
}
echo " 							<div class=\"maintab tab-right tab-inactive\" id=\"row-downloads\">\n";
echo " 								<a href=\"javascript:;\">\n";
echo " 			  						<div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Downloads</div></div></div>\n";
echo " 								</a>\n";
echo " 							</div>\n";	
echo "	   					</div>\n";
echo " 					</div>\n"; // end #tabs
echo " 				</div>\n"; // twelve rbcolumn 2

echo "				<div class=\"rbclear\"></div>\n"; // Clear All					
echo " 				<div class=\"rbcol-12 rbcolumn\">\n";
echo " 					<div id=\"tab-panels\">\n";
echo " 						<div class=\"row-photos tab\">\n";
echo " 							<div class=\"tab-panel\">\n";
									// images
									$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
									$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
									$countImg  = $wpdb->num_rows;
									foreach($resultsImg as $dataImg ){
									  	if ($countImg > 1) { 
											echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\"></a></div>\n";
									  	} else {
											echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\"></a></div>\n";
									  	}
									}
echo " 							</div>\n"; // .tab-panel
echo " 						</div>\n"; // twelve rbcolumn photos

echo " 						<div class=\"row-physical tab\">\n";
echo " 							<div class=\"tab-panel\">\n";
echo "								<ul>";
										if (!empty($ProfileGender)) {
											$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' "),ARRAY_A,0 	 );
											echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
										}

										// Insert Custom Fields
										$title_to_exclude = array("Experience");
										rb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender, $title_to_exclude);


										if($rb_agency_option_showcontactpage==1){
											echo "<li class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";
										}
echo "								</ul>";
echo " 							</div>\n"; // .tab-panel
echo " 						</div>\n"; // twelve rbcolumn physical

echo " 						<div class=\"row-videos tab\">\n";
echo " 							<div class=\"tab-panel\">\n";
									//Video Slate
									$queryMedia = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
									$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryMedia),ARRAY_A);
									$countMedia  = $wpdb->num_rows;

									if ($countMedia > 0) {
									  		foreach($resultsMedia as $dataMedia ){
											$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
											echo"<div class=\"video slate rbcol-4 rbcolumn\"><div class=\"video-container\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div></div>\n";
									  	}
									}

									//Video Monologue
									$queryMedia = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
									$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryMedia),ARRAY_A);
									$countMedia  = $wpdb->num_rows;
									if ($countMedia > 0) {
									  	foreach($resultsMedia as $dataMedia ){
											$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
											echo"<div class=\"video monologue rbcol-4 rbcolumn\"><div class=\"video-container\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div></div>\n";
									  	}
									}

									//Demo Reel
									$queryMedia = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
									$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryMedia),ARRAY_A);
									$countMedia  = $wpdb->num_rows;
									if ($countMedia > 0) {
									  	foreach($resultsMedia as $dataMedia ){
											$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
											echo"<div class=\"video demoreel rbcol-4 rbcolumn\"><div class=\"video-container\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div></div>\n";
									  	}
									}
echo " 							</div>\n"; // .tab-panel								
echo " 						</div>\n"; // twelve rbcolumn videos

echo " 						<div class=\"row-experience tab\">\n";
echo " 							<div class=\"tab-panel\">\n";
									$query1 ="SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." ORDER BY c.ProfileCustomOrder DESC";
									$results1=  $wpdb->get_results($wpdb->prepare($query1),ARRAY_A);
									$count1  = $wpdb->num_rows;
									foreach($results1 as $data1 ){
										if ($data1['ProfileCustomTitle'] == "Experience"){
											echo "    <div class=\"inner experience-". $data1['ProfileCustomTitle'] ." \">\n";
											echo "		<h3>". $data1['ProfileCustomTitle'] ."</h3>\n";
											echo "		<p id=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data1['ProfileCustomID'] 
												."\" class=\"ProfileExperience\">". nl2br($data1['ProfileCustomValue']) ."</p>\n";
											echo "	  </div>\n";
										}
									}
echo " 							</div>\n"; // .tab-panel							
echo " 						</div>\n"; // twelve rbcolumn experience

if(function_exists('rb_agency_casting_menu')){
echo " 						<div class=\"row-bookings tab\">\n";
echo "						<h3>Bookings</h3>";
echo " 						<div class=\"tab-panel\">\n";
echo " 						</div>\n"; // .tab-panel
echo " 						</div>\n"; // Row booking
}
// added this section to be able to display downloadable 
// files attached to a specific profile 
echo "						<div class=\"row-downloads tab\">\n";
echo " 							<div class=\"tab-panel\">\n";
	echo "							<p>". __("The following files (pdf, audio file, etc.) are associated with this profile",
						        	rb_agencyinteract_TEXTDOMAIN) .".</p>\n";
					
									$queryMedia = "SELECT * FROM ". table_agency_profile_media ." 
									              WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType <> \"Image\"";
									
									$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryMedia),ARRAY_A);
									$countImg  = $wpdb->num_rows;
									foreach($resultsMedia as $dataMedia ){
											
										if ($dataMedia['ProfileMediaType'] == "Demo Reel" || 
										    $dataMedia['ProfileMediaType'] == "Video Monologue" || 
											$dataMedia['ProfileMediaType'] == "Video Slate") {
											
											$outVideoMedia .= "<div style=\"float: left; width: 120px; text-align: center; padding: 10px; \">"
											. $dataMedia['ProfileMediaType'] ."<br />". 
											rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) 
											."<br /><a href=\"http://www.youtube.com/watch?v="
											. $dataMedia['ProfileMediaURL'] .
											"\" target=\"_blank\">Link to Video</a><br />[<a href=\"javascript:confirmDelete('".
											 $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType'].
											 "')\">DELETE</a>]</div>\n";
										
										} elseif ($dataMedia['ProfileMediaType'] == "VoiceDemo") {
											
											$outLinkVoiceDemo .= $dataMedia['ProfileMediaType'] .
											": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
											"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
											"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] 
											."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]\n";
										
										} elseif ($dataMedia['ProfileMediaType'] == "Resume") {
										
											$outLinkResume .= $dataMedia['ProfileMediaType'] 
											.": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
											"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
											"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
											$dataMedia['ProfileMediaType']."')\">DELETE</a>]\n";
										
										} elseif ($dataMedia['ProfileMediaType'] == "Headshot") {
										
											$outLinkHeadShot .= $dataMedia['ProfileMediaType'] .": <a href=\"". 
											rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
											"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
											"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
											$dataMedia['ProfileMediaType']."')\">DELETE</a>]\n";
										
										} elseif ($dataMedia['ProfileMediaType'] == "CompCard") {
										
											$outLinkComCard .= $dataMedia['ProfileMediaType'] .": <a href=\"". 
											rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
											"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
											"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
											$dataMedia['ProfileMediaType']."')\">DELETE</a>]\n";
										
										} else{
										
											$outCustomMediaLink .= $dataMedia['ProfileMediaType'] .": <a href=\"".
										 	rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
											"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
										 	"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
										 	$dataMedia['ProfileMediaType']."')\">DELETE</a>]\n";
										}
								  	}

									echo '<ul>';
									echo '<li>';
									echo $outLinkVoiceDemo;
									echo '</li>';
									echo '<li>';
									echo $outLinkResume;
									echo '</li>';
									echo '<li>';
									echo $outLinkHeadShot;
									echo '</li>';
									echo '<li>';
									echo $outLinkComCard;
									echo '</li>';
									echo '</ul>';
echo " 							</div>\n"; // .tab-panel
echo " 						</div>\n"; // Download Tab

echo "						<div class=\"rbclear\"></div>\n"; // Clear All
					
echo "	 				</div>\n";  // Close Tab Panels
echo "	 			</div>\n";  // Close Tab Panels
echo " 			</div>\n";  // Close RB Tabs
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All

?>        