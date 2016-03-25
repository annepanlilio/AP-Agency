<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/07/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'flexslider-style', RBAGENCY_PLUGIN_URL .'view/layout/07/css/flexslider.css' );
	wp_enqueue_style( 'flexslider-style' );

	wp_register_script( 'flexslider-js', RBAGENCY_PLUGIN_URL .'view/layout/07/js/jquery.flexslider-min.js', '', 1, true );
	wp_enqueue_script( 'flexslider-js' );

	wp_register_script( 'init-flexslider', RBAGENCY_PLUGIN_URL .'view/layout/07/js/init-flexslider.js', '', 1, true );
	wp_enqueue_script( 'init-flexslider' );

/*
 * Layout 
 */
# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;
?>
<div id="rbprofile">
	<div id="rblayout-seven" class="rblayout">
		<div id="info-slide">
			<div class="rbcol-4 rbcolumn">
				<div id="stats">

					<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
					<ul>
						<?php
						if (!empty($ProfileGender) and $display_gender == true) {
							$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' "),ARRAY_A,0 	);
							echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). "<b class=\"divider\">:</b></strong> ". $fetchGenderData["GenderTitle"] . "</li>\n";
						}
						if (!empty($ProfileStatHeight)) {
							if ($rb_agency_option_unittype == 0) { // Metric
								echo "<li class=\"rb_height\" id=\"rb_height\"><strong>". __("Height", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", RBAGENCY_TEXTDOMAIN). "" ."</li>\n";
							} else { // Imperial
								$heightraw = $ProfileStatHeight;
								$heightfeet = floor($heightraw/12);
								$heightinch = $heightraw - floor($heightfeet*12);
								echo "<li class=\"rb_height\" id=\"rb_height\"><strong>". __("Height", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", RBAGENCY_TEXTDOMAIN). " ". $heightinch ." ". __("in", RBAGENCY_TEXTDOMAIN). "" ."</li>\n";
							}
						}
						if (!empty($ProfileStatWeight)) {
							if ($rb_agency_option_unittype == 0) { // Metric
								echo "<li class=\"rb_weight\" id=\"rb_weight\"><strong>". __("Weight", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", RBAGENCY_TEXTDOMAIN). "</li>\n";
							} else { // Imperial
								echo "<li class=\"rb_weight\" id=\"rb_weight\"><strong>". __("Weight", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", RBAGENCY_TEXTDOMAIN). "</li>\n";
							}
						}

						// Insert Custom Fields
						rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender); ?>
						get_social_media_links($ProfileID);
					</ul>
				</div><!-- .portfolio-info -->
			</div><!-- .portfolio-info -->

			<div class="rbcol-8 rbcolumn">
				<div id="profile-slider" class="flexslider">
					<ul class="slides" id="img_slde">
						<?php
						// this will be a flag in the future. for enabling
						// two images in the slider.
						$option_two_image = 1;
						$ProfileMediaPrimary = ""; 
						$ProfileMediaSecondry= "";

						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
						$countImg  = $wpdb->num_rows;

						$open = 1;
						$close = false;
						foreach($resultsImg as $dataImg ){
								// option for two images
								if($option_two_image){
										if($open==1){
										$close = false;
										echo "<li>";
										}

									echo "<figure class=\"multi\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&a=t&w=400&h=600&zc=3\" alt=\"". $ProfileContactDisplay ."\" /></a></figure>";

										$open++;
										if($open == 3){
										$open = 1;
											$close = true;
											echo "</li>\n";
										}
								} else {

										if($dataImg['ProfileMediaPrimary']==1){
										$ProfileMediaPrimary= 	"<li><figure><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></figure></li>\n";
									} else {
										$ProfileMediaSecondry .= "<li><figure><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></figure></li>\n";
									}
									echo $ProfileMediaPrimary; 
									echo $ProfileMediaSecondry; 
								}
						}
						if($option_two_image && !$close){
							echo "</li>\n";
						}

						?>
					</ul>
				</div>
			<div id="video_player" class="rbcol-8 rbcolumn" style="display:none; position:relative; ">
				<?php
				//Video Slate
				$count_video = 0;
				$profileVideoEmbed1 = "";
				$profileVideoEmbed2 = "";
				$profileVideoEmbed3 = "";

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
				$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
				$countMedia  = $wpdb->num_rows;
				if ($countMedia > 0) {
					foreach($resultsMedia as $dataMedia ){
						$profileVideoEmbed1 = $dataMedia['ProfileMediaURL'];
						$count_video++;
						echo "<div class='act_vids'><div id='v_slate' style='width:100%; height:100%'></div></div>";
					}
				}

				//Video Monologue
				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
				$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
				$countMedia  = $wpdb->num_rows;
				if ($countMedia > 0) {
					foreach($resultsMedia as $dataMedia ){
						$profileVideoEmbed2 = $dataMedia['ProfileMediaURL'];
						$count_video++;
							echo "<div class='vids' style='display:none;'><div id='v_mono' style='width:100%; height:100%'></div></div>";
						}
				}
				//Demoreel

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
				$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
				$countMedia  = $wpdb->num_rows;
				if ($countMedia > 0) {
					foreach($resultsMedia as $dataMedia ){
						$profileVideoEmbed3 = $dataMedia['ProfileMediaURL'];
						$count_video++;
							echo "<div class='vids' style='display:none;'><div id='d_reel' style='width:100%; height:100%'></div></div>";
						}
				}
				?>

			</div>
<!--			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
-->
			<script>
				var tag = document.createElement('script');
				tag.src = "//www.youtube.com/iframe_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				var yPlayer1;
				var yPlayer2;
				var yPlayer3;
				function onYouTubeIframeAPIReady() {
				<?php
					$embed = "profileVideoEmbed";
					$e = array("","v_slate","v_mono","d_reel");
					for($x = 1; $x <=$count_video ; $x++){
						$ytube = $embed . $x;
						if(!empty($$ytube)){ ?>
							yPlayer<?php echo $x; ?> = new YT.Player('<?php echo $e[$x]; ?>',{
							height: '100%',
							width: '100%',
							videoId: '<?php echo $$ytube; ?>',
							playerVars: {
								wmode: "opaque"
							},
							events: {
								'onReady': onYReady<?php echo $x; ?>
							}
						});

				<?php }}?>

				}
				<?php 
				for($x = 1; $x <=$count_video ; $x++){ ?>

					function onYReady<?php echo $x; ?>(event) {
								yPlayer<?php echo $x;?>.stopVideo();
								e.preventDefault();
					}
				<?php
				}?>

				jQuery(document).ready(function(){

						jQuery("#videos-carousel").find("li").click(function(){
							<?php 
							for($x = 1; $x <=$count_video ; $x++){
							?>
								yPlayer<?php echo $x;?>.pauseVideo();
							<?php }?>

							var _next = "#" + jQuery(this).attr("class");
							var _curr = jQuery("#video_player").find(".act_vids");
							_curr.removeClass("act_vids");
								_curr.addClass("vids");
								jQuery(_next).parent().show();
								jQuery(_next).parent().addClass("act_vids");
						});

						jQuery("#vid_changer").width(jQuery("#video_player").width()+"px");



					jQuery("a.showSingle1").click(function(){
						jQuery("#profile-slider").show();
						jQuery("#video_player").hide();
					});
					jQuery("a.showSingle3").click(function(){
						jQuery("#profile-slider").hide();
						jQuery("#video_player").show();
					});
					jQuery(".video_player").click(function(){
						jQuery("#profile-slider").hide();
						jQuery("#video_player").show();
					});



					jQuery("#profile-carousel li").click(function(){
						jQuery("#profile-slider").show();
						jQuery("#video_player").hide();
					});

					jQuery(".save_fav").click(function(){
						ajax_submit(jQuery(this),"favorite");
					});

					jQuery(".save_cart").click(function(){
						ajax_submit(jQuery(this),"casting");
					});

					function ajax_submit(Obj,type){
						if(type == "favorite"){
							var action_function = "rb_agency_save_favorite";
						} else if(type == "casting"){
							var action_function = "rb_agency_save_castingcart";
						}

						jQuery.ajax({type: 'POST',url: '<?php echo get_bloginfo('url') ?>/wp-admin/admin-ajax.php',
									data: {action: action_function,  'talentID': <?php echo $ProfileID ?>},
									success: function(results) { 
										if(results=='error'){
											alert("Error in query. Try again"); 
										} else if(results==-1){
											alert("You're not signed in");
										} else {
											if(type == "favorite"){
												document.getElementById('myfav_add').style.display="none";
												document.getElementById('myfav_view').style.display="inline-block";
											} else if(type == "casting") {
												document.getElementById('mycart_add').style.display="none";
												document.getElementById('mycart_view').style.display="inline-block";
											}
										}
									}
						}); // ajax submit
					}// end function
				});
			</script>
		</div><!-- #info-slide -->

		<div class="rbcol-12 rbcolumn">
			<ul id="profile-links">
					<?php  
						if(function_exists('rb_agency_casting_menu')){
							echo rb_agency_get_new_miscellaneousLinks($ProfileID);
						}
					?>

					<li><a href="javascript:;" class="showSingle1" >Pictures</a></li>
					<li><a href="javascript:;" class="showSingle2" >Experience</a></li>
					<li><a href="javascript:;" class="showSingle3" >Videos</a></li>
						<?php
				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Headshot");
				$countHeadshot = $wpdb->num_rows;

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"VoiceDemo");
				$countVoiceDemo = $wpdb->num_rows;

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CompCard");
				$countCompCard = $wpdb->num_rows;

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
				$countResume = $wpdb->num_rows;


				if($countHeadshot>0 || $countVoiceDemo>0 || $countCompCard>0 || $countResume>0 ){ ?>
					<li><a href="javascript:;" class="showSingle4" >Media</a></li>

					<?php }
					echo '<li id="resultsGoHereAddtoCart"></li>';?>
			</ul>
		</div>

		<div class="rbcol-12 rbcolumn targetpictures rbtab-content">
			<div id="profile-carousel" class="flexslider">
				<ul class="slides">
					<?php
					$ProfileMediaPrimary = ""; 
					$ProfileMediaSecondry= "";
					$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");


								$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
								$countImg =$wpdb->num_rows;
								$open = 1;
								foreach($resultsImg as $dataImg ){
									// testing
									if($option_two_image){
													if($open==1){
															$close = false;
															echo "<li><figure class=\"multi\">";
													}

														echo "<span style=\"background-image: url(". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" title=\"". $ProfileContactDisplay ."\" ></span>";

													$open++;
													if($open == 3){
															$open = 1;
															$close = true;
															echo "</figure></li>\n";
													}
									} else {

													if($dataImg['ProfileMediaPrimary']==1){
																$ProfileMediaPrimary= 	"<li><figure><span style=\"background-image: url(". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" title=\"". $ProfileContactDisplay ."\" ></span></figure></li>\n";
														} else {
																$ProfileMediaSecondry .= "<li><figure><span style=\"background-image: url(". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" title=\"". $ProfileContactDisplay ."\" ></span></figure></li>\n";
														}
														echo $ProfileMediaPrimary; 
														echo $ProfileMediaSecondry; 
									}
								}
								if($option_two_image && !$close){
									echo "</li>\n";
								}
					?>
				</ul>
			</div>
		</div>

		<div class="rbcol-12 rbcolumn rbtab-content targetvideo" style="display:none"  >
			<div id="videos-carousel" class="flexslider">
				<ul class="slides">
					<?php
					//Video Slate

					$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
					$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
					$countMedia = $wpdb->num_rows;
					if ($countMedia > 0) {
						foreach($resultsMedia as $dataMedia ){
							$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
							echo "<li class='v_slate'><img src='http://img.youtube.com/vi/".$profileVideoEmbed."/default.jpg' alt='Video Slate' /></li>";
						}
					}

					//Video Monologue
					$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
					$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
					$countMedia = $wpdb->num_rows;
					if ($countMedia > 0) {
						foreach($resultsMedia as $dataMedia ){
							$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
								echo "<li class='v_mono'><img src='http://img.youtube.com/vi/".$profileVideoEmbed."/default.jpg' alt='Video Monologue' /></li>";
							}
					}
					//Demoreel
					$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
					$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
					$countMedia =  $wpdb->num_rows;
					if ($countMedia > 0) {
							foreach($resultsMedia as $dataMedia ){
							$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
								echo "<li class='d_reel'><img src='http://img.youtube.com/vi/".$profileVideoEmbed."/default.jpg' alt='Demo Reel' /></li>";
							}
					}
					?>
				</ul>
			</div>
		</div>
		<div class="rbcol-12 rbcolumn rbtab-content targetmedia" style="display:none"  >
			<ul id="media-tab">
				<?php
				//Headshot

				if ($countHeadshot > 0) {
					foreach($resultsHeadshot as $dataHeadshot ){
						$profileHeadshotUrl = $dataHeadshot['ProfileMediaURL'];
						echo "<li><a target='_blank' href=".RBAGENCY_UPLOADDIR.$ProfileGallery.'/'.$profileHeadshotUrl.">".__("Download Headshot",RBAGENCY_TEXTDOMAIN)."</a></li>";
					}
				}

				//VoiceDemo

				if ($countVoiceDemo > 0) {
					foreach($resultsVoiceDemo as $dataVoiceDemo ){
						$profileVoiceDemo = $dataVoiceDemo['ProfileMediaURL'];
							echo "<li><a target='_blank' href=".RBAGENCY_UPLOADDIR.$ProfileGallery.'/'.$profileVoiceDemo.">".__("Download VoiceDemo",RBAGENCY_TEXTDOMAIN)."</a></li>";
						}
				}
				//CompCard

				if ($countCompCard > 0) {
					foreach($resultsCompCard as $dataCompCard ){
						$profileCompCardUrl = $dataCompCard['ProfileMediaURL'];
							echo "<li><a target='_blank' href=".RBAGENCY_UPLOADDIR.$ProfileGallery.'/'.$profileCompCardUrl.">".__("Download CompCard",RBAGENCY_TEXTDOMAIN)."</a></li>";
						}
				}
				//Resume

				if ($countResume > 0) {
					foreach($resultsResume as $dataResume ){
						$profileResumeUrl = $dataResume['ProfileMediaURL'];
							echo "<li><a target='_blank' href=".RBAGENCY_UPLOADDIR.$ProfileGallery.'/'.$profileResumeUrl.">".__("Download Resume",RBAGENCY_TEXTDOMAIN)."</a></li>";
						}
				}
				?>
			</ul>
		</div>


		<div class="rbcol-12 rbcolumn  rbtab-content targetexperience" style="display:none">
			<div id="experience">
				<?php
				rb_agency_getSocialLinks();
				$title_to_exclude = array("Experience");
				print_r(rb_agency_getProfileCustomFieldsExperienceDescription($ProfileID, $ProfileGender, 'Experience(s):')); 
				get_social_media_links($ProfileID);
				?>
				
			</div>
		</div>
		<div class="rbclear"></div>
	</div>

	<script>
		jQuery(function(){

			jQuery('.showSingle1').click(function(){
					jQuery('.targetpictures').show();
					jQuery('.targetexperience').hide();
					jQuery('.targetvideo').hide();
					jQuery('.targetmedia').hide();
			});
			jQuery('.showSingle2').click(function(){
					jQuery('.targetexperience').show();
					jQuery('.targetpictures').hide();
					jQuery('.targetvideo').hide();
					jQuery('.targetmedia').hide();
			});
			jQuery('.showSingle3').click(function(){
					jQuery('.targetvideo').show();
					jQuery('.targetpictures').hide();
					jQuery('.targetexperience').hide();
					jQuery('.targetmedia').hide();

			});
			jQuery('.showSingle4').click(function(){
					jQuery('.targetmedia').show();
					jQuery('.targetvideo').hide();
					jQuery('.targetpictures').hide();
					jQuery('.targetexperience').hide();
			});


		});
	</script>
	<div class="rbclear"></div>
</div><!-- #profile -->
