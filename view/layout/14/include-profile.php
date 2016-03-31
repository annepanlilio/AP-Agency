<?php
/*
Title:  Flipbook
Author: RB Plugin
Text:   Flipbook
*/

/*
 * Insert Style
 */

	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/14/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'owl-carousel-css', RBAGENCY_PLUGIN_URL .'view/layout/14/css/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-css' );

/*
 * Insert Scripts
 */

	wp_enqueue_script( 'tabs-js', RBAGENCY_PLUGIN_URL .'view/layout/14/js/tab.js' );
	wp_enqueue_script( 'tabs-js' );

	wp_enqueue_script( 'owl-carousel-js', RBAGENCY_PLUGIN_URL .'view/layout/14/js/owl.carousel.min.js' );
	wp_enqueue_script( 'owl-carousel-js' );


/*
 * Layout 
 */

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
?>

<div id="rbprofile t">
	<div id="rblayout-fourteen" class="rblayout">
		<div class="rbcol-12 rbcolumn">
			<div id="layout-head">
				<?php echo " <h2>". $ProfileContactDisplay ."</h2>\n"; ?>
			</div>
		</div>
		<div class="rbclear"></div>
		<div class="rbcol-12 rbcolumn">
			<ul id="profile-info">

				<?php
				// Insert Custom Fields
				rb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender,array('Experience','Skills and Qualities','Skills'));
				get_social_media_links($ProfileID);
				?>
			</ul>
		</div><!-- #photobook -->
		<div class="rbclear"></div>
		<div class="tabulous">
			<ul class="tabs-menu">
				<li class="current"><a href="#portfolio" title="">Portfolio</a></li>
				<li><a href="#videos" title="">Videos</a></li>
				<li><a href="#biography" title="">Biography</a></li>				
			</ul>
			<div style="clear:both;"></div>
			<div class="tab">
				<div id="portfolio" class="tab-content">
				    <div id="photos" class="owl-carousel">
				    	<?php
						# rb_agency_option_galleryorder
						
						$rb_agency_option_unittype  = $rb_agency_options_arr['rb_agency_option_unittype'];
						$order = isset($rb_agency_options_arr['rb_agency_option_galleryorder']) ? $rb_agency_options_arr['rb_agency_option_galleryorder']:0;
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							if ($countImg > 1) {
								echo "<div class=\"photo\">";
								echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=400&h=600\"/></a>";
								if($rb_agency_option_profile_thumb_caption ==1) {
									echo "	<small>".$dataImg['ProfileMediaURL']."</small>\n";
								}
								echo "</div>\n";
							} else {
								echo "<div class=\"photo\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=400&h=600\"/></a></div>\n";
							}
						}				    	
				    	?>
				    </div>
				</div>

				<div id="videos" class="tab-content">			    
					<?php 
					$queryMedia = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileVideoType IN('youtube','vimeo')";
					$resultsMedia =  $wpdb->get_results($queryMedia,ARRAY_A);
					$countMedia = $wpdb->num_rows;

					if($countMedia > 0) {
						echo "<div id=\"videos\">";

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
								// $outVideoMedia .= "<div class=\"video-col\"><div class=\"video\"><div class=\"video-info\">".rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL'])."<br/>" .$dataMedia["ProfileMediaTitle"]."</div><a href=\"http://vimeo.com/" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">Watch Video</a></div></div>\n";
								// $outVideoMedia .= "<div class=\"video-col\"><div class=\"video\">".rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL'])."<a href=\"" . $dataMedia['ProfileMediaURL'] . "\" target=\"_blank\">Watch Video</a></div></div>\n";


								$vidurl = $dataMedia['ProfileMediaURL'];
								$embed = substr(strrchr($vidurl, '='), 1);								
								$embedid = "https://www.youtube.com/embed/".$embed;
								if(strpos($vidurl,'youtube') !== false ){
									$outVideoMedia .="<div class=\"video-col\"><div class=\"video\"><iframe width=\"100%\" height=\"315\" src=\"".$embedid."?controls=0&showinfo=0\" frameborder=\"0\" allowfullscreen></iframe></div></div>";
								}
								
							}
						}
						echo $outVideoMedia;

						echo "</div><!-- #videos -->";
					} else {
						echo "<p>No Videos Found</p>";
					}
					?>
				</div>



				<?php
				$column = 0;
				$_profile_Experience = '';
				$_profile_SkillsQualities = '';
				$_profile_Skills = '';

				$resultsCustom = $wpdb->get_results($wpdb->prepare("SELECT c.ProfileCustomID,c.ProfileCustomTitle,c.ProfileCustomType,c.ProfileCustomOptions, c.ProfileCustomOrder, 
				cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx 
				LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = %d 
				GROUP BY cx.ProfileCustomID ORDER BY c.ProfileCustomOrder ASC",$ProfileID));
				$title_to_exclude = array();
				foreach ($resultsCustom as $resultCustom) {
					if(!in_array($resultCustom->ProfileCustomTitle, $title_to_exclude)){
						if(!empty($resultCustom->ProfileCustomValue )){
							if(strpos($resultCustom->ProfileCustomTitle,'Experience') !== false){
								$_profile_Experience = $resultCustom->ProfileCustomValue;
							}elseif(strpos($resultCustom->ProfileCustomTitle,'Skills and Qualities') !== false){
								$_profile_SkillsQualities = $resultCustom->ProfileCustomValue;
							}
							elseif(strpos($resultCustom->ProfileCustomTitle,'Skills') !== false){
								$_profile_Skills = $resultCustom->ProfileCustomValue;
							}
						}
					}
				}

				// For dynamic columns
				if(!empty($_profile_Skills)) $column ++;
				if(!empty($_profile_Experience)) $column ++;
				if(!empty($_profile_SkillsQualities)) $column ++;
				?>

				<div id="biography" class="tab-content col-<?php echo $column; ?>">
					
					<?php 
					if(!empty($_profile_Skills)) {
						echo '
						<div class="column">
							<div class="box">
								<h3>SKILLS</h3>
								'. $_profile_Skills .'
							</div>
						</div> ';
					}

					if(!empty($_profile_Experience)) {
						echo '
						<div class="column">
							<div class="box">
								<h3>MODEL EXPRIENCE</h3>
								'. $_profile_Experience .'
							</div>
						</div> ';
					}

					if(!empty($_profile_SkillsQualities)){
						echo '
						<div class="column">
							<div class="box">
								<h3>SKILLS AND&nbsp;QUALITIES</h3>
								'. $_profile_SkillsQualities .'
							</div>
						</div> ';
					}					
						
					/* 
					<div class="description">
						<h3>MODEL EXPRIENCE</h3>
						<h4>2014</h4>
						<h4>Gucci Refines a Nostalgia Now</h4>
						<p>Aliquam mattis iaculis nisl nec finibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						<h4>2013</h4>
						<h4>Faucibus orci luctus et</h4>
						<p>Aliquam mattis iaculis nisl nec finibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
						<h4>2012</h4>
						<h4>Duis quis nisl sit amet</h4>
						<p>Aliquam mattis iaculis nisl nec finibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
					</div>
					<div class="description">
						<h3>SKILLS AND&nbsp;QUALITIES</h3>
						<p>Donec convallis quam vehicula sapien venenatis volutpat.Aliquam mattis iaculis nisl nec finibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
						<ul>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; A&nbsp;pleasant, professional attitude with good ‘people skills’</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; Good time-keeping</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; Patience, stamina and fitness to cope with long, tiring days.</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; The ability to cope with criticism and rejection</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; Good grooming and willingness to look after yourself</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; Fashion sense and awareness of trends</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; Good coordination</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; Confidence, self-reliance and discipline</li>
							<li><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp; The ability to cope with criticism and rejection</li>
						</ul>
					</div> 
					*/
							
					?>
					
					
					
				</div>
				<div style="clear:both;"></div>
			</div><!--End tabs container-->
		
		</div><!--End tabs-->
		<div style="clear:both;"></div>
		<?php if(is_user_logged_in()) : ?>
		<div class="rbcol-12 rbcolumn">
			<div id="view-portfolio">
				<br><a href="http://dev.bajatalent.com/wp-admin/admin.php?page=rb_agency_profiles&action=editRecord&ProfileID=<?php echo $ProfileID; ?>" title="" target="_blank">View User Portfolio</a>
			</div>
		</div><!-- .rbcol-12 -->
		<?php endif; ?>
	</div><!-- #rblayout-eight -->
</div><!-- #profile -->

