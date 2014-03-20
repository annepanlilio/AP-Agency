<?php
/*
Title:  Flipbook
Author: RB Plugin
Text:   Flipbook
*/

/*
 * Insert Style
 */

	wp_register_style( 'rblayout-style', plugins_url('/css/style.css', __FILE__) );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'rblayout-style-custom', plugins_url('/css/booklet.css', __FILE__) );
	wp_enqueue_style( 'rblayout-style-custom' );

/*
 * Insert Scripts
 */

	wp_enqueue_script( 'photo-booklet-ui', plugins_url('/js/booklet-jquery-ui.min.js', __FILE__) );
	wp_enqueue_script( 'photo-booklet-ui' );

	wp_enqueue_script( 'photo-booklet-easing', plugins_url('/js/booklet-jquery.easing.1.3.js', __FILE__) );
	wp_enqueue_script( 'photo-booklet-easing' );

	wp_enqueue_script( 'photo-booklet', plugins_url('/js/booklet.min.js', __FILE__) );
	wp_enqueue_script( 'photo-booklet' );

	wp_enqueue_script( 'init-booklet', plugins_url('/js/booklet.init.js', __FILE__) );
	wp_enqueue_script( 'init-booklet' );



/*
 * Layout 
 */

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
?>

<div id="rbprofile t">
	<div id="rblayout-eight" class="rblayout">
		<div class="rbcol-12 rbcolumn">
			<div id="layout-head">
				<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
				<a href="">Back</a>
			</div>
		</div>
		<div class="rbclear"></div>
	    <div class="rbcol-12 rbcolumn">
		    <div id="photobook">		    	
	            <?php

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
				 $resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);			
						$countImg =$wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
						  	echo "<div class=\"page\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></div>\n";
						}
				?>
		    </div><!-- .rbcol-12 -->
	    </div><!-- #photobook -->
	    <div class="rbclear"></div>
	    <div class="rbcol-12 rbcolumn">
		    <div id="photobook-pagination" class="">
				<a href="" id="prev-page">Previous page</a>
				<a href="" id="next-page">Next Page</a>
			</div>
		</div>
		<div class="rbclear"></div>
		<div class="rbcol-12 rbcolumn">
			<ul id="profile-info">
				<?php
				// Insert Custom Fields
				rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender, $LabelTag="em", $LabelSeparator=" ");
				?>			
			</ul>
			<div id="videos">
			<h4>Videos</h4>
			<?php 
			$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileVideoType IN('youtube','vimeo')";
			$resultsMedia =  $wpdb->get_results($wpdb->prepare($queryMedia),ARRAY_A);
			$countMedia = $wpdb->num_rows;
			foreach ($resultsMedia  as $dataMedia) {
				if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
					if($dataMedia['ProfileVideoType'] == "" || $dataMedia['ProfileVideoType'] == "youtube"){
						$outVideoMedia .= "<div class=\"video-col\"><div class=\"video\"><a href=\"http://www.youtube.com/watch?v=" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" .$dataMedia["ProfileMediaTitle"]."</a></div></div>\n";
					}elseif($dataMedia['ProfileVideoType'] == "vimeo"){
						$json = file_get_contents('http://vimeo.com/api/v2/video/'.$dataMedia['ProfileMediaURL'].'.json');
						$data = json_decode($json,true);
							$outVideoMedia .= "<div class=\"video-col\"><div class=\"video\"><a href=\"http://vimeo.com/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" .$dataMedia["ProfileMediaTitle"]."</a></div></div>\n";
						}
					} 
				}
				echo $outVideoMedia;
			?>
			</div>			
			
		</div>
		<div class="rbcol-12 rbcolumn">
			<a href="" title=""></a>
		</div>
	</div><!-- #rblayout-eight -->
</div><!-- #profile -->