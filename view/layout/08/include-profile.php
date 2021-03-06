<?php
/*
Title:  Flipbook
Author: RB Plugin
Text:   Flipbook
*/

/*
 * Insert Style
 */

	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/08/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'rblayout-style-custom', RBAGENCY_PLUGIN_URL .'view/layout/08/css/booklet.css' );
	wp_enqueue_style( 'rblayout-style-custom' );

/*
 * Insert Scripts
 */

	wp_enqueue_script( 'photo-booklet-ui', RBAGENCY_PLUGIN_URL .'assets/js/booklet-jquery-ui.min.js' );
	wp_enqueue_script( 'photo-booklet-ui' );

	wp_enqueue_script( 'photo-booklet-easing', RBAGENCY_PLUGIN_URL .'assets/js/booklet-jquery.easing.1.3.js' );
	wp_enqueue_script( 'photo-booklet-easing' );

	wp_enqueue_script( 'photo-booklet', RBAGENCY_PLUGIN_URL .'view/layout/08/js/booklet.min.js' );
	wp_enqueue_script( 'photo-booklet' );

	wp_enqueue_script( 'init-booklet', RBAGENCY_PLUGIN_URL .'view/layout/08/js/booklet.init.js' );
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
			
			
<?php
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


?>
		</div>
		<div class="rbclear"></div>
		<div class="rbcol-12 rbcolumn">
			<div id="photobook">
			      <?php
			    $private_profile_photo = get_user_meta($ProfileUserLinked,'private_profile_photo',true);
				$private_profile_photo_arr = explode(',',$private_profile_photo); 
				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
				$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
						$countImg =$wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							if(!in_array($dataImg['ProfileMediaID'],$private_profile_photo_arr)){
								echo "<div class=\"page\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&a=t&w=350&h=450&zc=3\" alt=\"". $ProfileContactDisplay ."\" /></div>\n";

							}
								
						}
				?>
			</div><!-- .rbcol-12 -->
		</div><!-- #photobook -->
		<div class="rbclear"></div>
		<div class="rbcol-12 rbcolumn">
			<div id="photobook-pagination" class="">
				<a href="" id="prev-page"><?php echo __("Previous page",RBAGENCY_TEXTDOMAIN); ?></a>
				<a href="" id="next-page"><?php echo __("Next Page",RBAGENCY_TEXTDOMAIN); ?></a>
			</div>
		</div>
		<div class="rbclear"></div>
		<div class="rbcol-12 rbcolumn">
			<ul id="profile-info">
				<?php
				// Insert Custom Fields
				rb_agency_getProfileCustomFields($ProfileID, $ProfileGender,$label_tag="em", $value_tag="strong");
				get_social_media_links($ProfileID);
				?>
			</ul>

			<?php 
			$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileVideoType IN('youtube','vimeo')";
			$resultsMedia =  $wpdb->get_results($queryMedia,ARRAY_A);
			$countMedia = $wpdb->num_rows;

			if($countMedia > 0) {
				echo "<div id=\"videos\">";
				echo "<h4>Videos</h4>";

				foreach ($resultsMedia  as $dataMedia) {
					if ($dataMedia['ProfileMediaType'] == "Demo Reel" || $dataMedia['ProfileMediaType'] == "Video Monologue" || $dataMedia['ProfileMediaType'] == "Video Slate") {
						// if($dataMedia['ProfileVideoType'] == "" || $dataMedia['ProfileVideoType'] == "youtube"){
						// 	$outVideoMedia .= "<div class=\"video-col\"><div class=\"video\"><a href=\"http://www.youtube.com/watch?v=" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" .$dataMedia["ProfileMediaTitle"]."</a></div></div>\n";
						// } elseif($dataMedia['ProfileVideoType'] == "vimeo"){
						// 	$json = file_get_contents('http://vimeo.com/api/v2/video/'.$dataMedia['ProfileMediaURL'].'.json');
						// 	$data = json_decode($json,true);
						// 		$outVideoMedia .= "<div class=\"video-col\"><div class=\"video\"><a href=\"http://vimeo.com/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">" .$dataMedia["ProfileMediaTitle"]."</a></div></div>\n";
						// 	}
						// }
						$outVideoMedia .= "<div class=\"video-col\"><div class=\"video\"><div class=\"video-info\">".rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL'])."<br/>" .$dataMedia["ProfileMediaTitle"]."</div><a href=\"" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">".__("Watch Video",RBAGENCY_TEXTDOMAIN)."</a></div></div>\n";
					}
				}
					echo $outVideoMedia;

				echo "</div><!-- #videos -->";
			}
			?>

		</div>
		<div class="rbcol-12 rbcolumn">
			<a href="" title=""></a>
		</div>
	</div><!-- #rblayout-eight -->
</div><!-- #profile -->