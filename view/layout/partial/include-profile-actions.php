<?php
/*
 * Social Links
 */

// Social Link
echo "<div id=\"profile-social\">";
	rb_agency_getSocialLinks();
echo "</div>";

echo "<div id=\"profile-actions\">";
	if (is_plugin_active('rb-agency-casting/rb-agency-casting.php') && is_user_logged_in()) {
		echo rb_agency_get_new_miscellaneousLinks($ProfileID);
	}
echo "</div>";


echo "<div id=\"profile-links\">\n";
	if(isset($rb_agency_options_arr["rb_agency_option_layoutprofile"]) && $rb_agency_options_arr["rb_agency_option_layoutprofile"] != 2){
		
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countImg  = $wpdb->num_rows;

		if($countImg  > 0){
			echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/images/\" class=\"profile-link\">". __("Print Photos", rb_agency_TEXTDOMAIN)."</a>\n"; //MODS 2012-11-28			
		}
	}

	// Polaroid	
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Polaroid");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countImg  = $wpdb->num_rows;

		if($countImg  > 0){
			echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/print-polaroids/\" class=\"profile-link polaroid\">". __("Print Polaroids", rb_agency_TEXTDOMAIN)."</a>\n"; //MODS 2012-11-28
		    echo "<a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/polaroids/\" class=\"profile-link polaroid\">". __("View Polaroids", rb_agency_TEXTDOMAIN)."</a>\n"; //MODS 2012-11-30
		}
		
	// Resume
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/". $dataMedia['ProfileMediaURL']) ." class=\"profile-link\">".rb_get_profile_link_label()." Resume</a>\n";
			}
		}

	// Comp Card
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CompCard");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\">".rb_get_profile_link_label()." Comp Card</a>\n";
			}
		}

	// Headshots
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Headshot");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\">".rb_get_profile_link_label()." Headshot</a>\n";
			}
		}

	//Voice Demo
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"VoiceDemo");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				echo "<a ".rb_get_profilemedia_link_opentype($ProfileGallery ."/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\">".rb_get_profile_link_label()." Voice Demo</a>\n";
			}
		}

	//Video Slate
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "<a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link slate\">Watch Video Slate</a>\n";
			}
		}

	//Video Monologue
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				echo "<a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link monologue\">Watch Video Monologue</a>\n";
			}
		}

	//Demo Reel
		$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
		$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				echo "<a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\" class=\"profile-link\">Watch Demo Reel</a>\n";
			}
		}

	// Other Media Type not the default ones
		/*$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType NOT IN ('Image','Resume','Polaroid','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')";
		$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg, $ProfileID),ARRAY_A);
		$countMedia = $wpdb->num_rows;
		if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
				if (!empty($dataMedia['ProfileMediaType']) && isset($dataMedia['ProfileMediaType'])) {
					echo "<li class=\"item video custom\"><a target=\"_blank\" href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">". $dataMedia['ProfileMediaType'] . "</a></li>\n";
				}
			}
		}*/
		rb_agency_showMediaCategories($ProfileID, $ProfileGallery);

echo "	</div>\n";

/*
 * Contact

	//Contact Profile
	if($rb_agency_option_showcontactpage==1){
		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
	}
 */

?>