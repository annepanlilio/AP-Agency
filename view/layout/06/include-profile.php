<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/06/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );


/*
 * Layout 
 */
# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];

/*
Large featured image and scrolling thumbnails
*/
// Donot Edit this this is for subview 


?>

<script type="text/javascript">
	jQuery(document).ready(function() {

	    jQuery('#division').change(function() {   
	        var qString = 'sub=' +$(this).val();
	        jQuery.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/sub_db_handler.php', qString, processResponse);
	    });

	    function processResponse(data) {
	        jQuery('#resultsGoHere').html(data);
	    }

	});
</script>


<div id="rbprofile" class="model-portfolio">

	<div id="rblayout-six" class="rblayout">
		<div class="rbcol-12 rbcolumn">
			<header class="entry-header">
				<div id="profile-filter">
					<div class="filters">
						<div>
							<select name="division" id="division">
								<option value="">Select Division</option>
								<option value="men">Men</option>
								<option value="women">Women</option>
								<option value="teen_girls">Teen Girls</option>
								<option value="teen_boys">Teen Boys</option>
								<option value="boys">Boys</option>
								<option value="girls">Girls</option>
							</select>
						</div>
						<div id="resultsGoHere">
							<select>
								<option>Select Division First</option>
							</select>
						</div>
					</div><!-- .filters -->
				</div>
				<div class="rbclear"></div>
			</header>
		</div>

		<div class="rbclear"></div>

		<?php
		$experience = getExperience($ProfileID);
		if(!empty($experience)) : ?>
			<div class="rbcol-12 rbcolumn">
				<pre id="description">
					<?php echo getExperience($ProfileID); ?>
				</pre>
			</div>
		<?php endif; ?>

		<div class="rbclear"></div>

		<div class="rbcol-12 rbcolumn">
			<div id="profile-info" class="rbcol-4 rbcolumn">

				<div class="panel">

					<h2><?php echo $ProfileContactDisplay; ?></h2>

					<div id="model-stats">
						<ul>
							<?php
							if (!empty($ProfileGender)) {
								$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='%s' ",$ProfileGender),ARRAY_A,0 	 );
								echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). ":</strong> ". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</li>\n";
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
							rb_agency_getProfileCustomFields($ProfileID, $ProfileGender); ?>
						</ul>
					</div>

					<div id="model-links">

						<?php
						echo "<ul>\n";
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/images/\">". __("Print Photos", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/print-polaroids/\">". __("Print Polaroids", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/polaroids/\">". __("View Polaroids", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/\">". __("View Slideshow", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-29
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/lightbox/\">". __("View in Lightbox", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-29

							// Resume
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
											
										
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							
								if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item resume\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Print Resume</a></li>\n";
								}
							}
							// Comp Card
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CompCard");
											
										
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							$cpCount="";
							if ($countMedia > 0) {
								$cpCnt = 0;
								foreach($resultsMedia as $dataMedia ){
									$cpCnt++;  
									if($cpCnt == "2"){$cpCount="2nd";}
									elseif($cpCnt == "3"){$cpCount="3rd";}
									else{$cpCount="";}

									echo "<li class=\"item compcard\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Download $cpCount Comp Card</a></li>\n";
								}
							}
							// Headshots
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Headshot");
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item headshot\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">". __("Download Headshot", RBAGENCY_TEXTDOMAIN)."</a></li>\n";
								}
							}
							//Voice Demo
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"VoiceDemo");
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							$vdCount = "";
							if ($countMedia > 0) {
								$vdCnt = 0;
								foreach($resultsMedia as $dataMedia ){
									$vdCnt++;
									if($vdCnt == "2"){$vdCount="2nd";}
									elseif($vdCnt == "3"){$vdCount="3rd";}
									else{$vdCount="";}
									echo "<li class=\"item voice\"><a target='_blank' href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Listen to $vdCount Voice Demo</a></li>\n";
								}
							}
							//Video Slate
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
									echo "<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". __("Watch Video Slate", RBAGENCY_TEXTDOMAIN)."</a></li>\n";
								}
							}
							//Video Monologue
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Watch Video Monologue</a></li>\n";
								}
							}
							//Demo Reel
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
							$resultsMedia=  $wpdb->get_results($queryImg,ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
										echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". __("Watch Demo Reel", RBAGENCY_TEXTDOMAIN)."</a></li>\n";
								}
							}
							if(function_exists('rb_agency_casting_menu')){
								echo rb_agency_get_new_miscellaneousLinks($ProfileID);
							}			
						echo "</ul>\n";?>                      
	            
					</div> <!-- .model-links -->

					<div id="resultsGoHereAddtoCart"></div>
	            	<div id="view_casting_cart" style="display:none;"><a href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", RBAGENCY_TEXTDOMAIN);?></a></div>
					<?php
					//decides what division URL should be
					$age = floor( (strtotime(date('Y-m-d')) - strtotime($ProfileDateBirth)) / 31556926);	 //calculate age
					if($age > 17 AND $age <99 AND $ProfileGender==2){ $divisionDir="/women/";}
					elseif($age > 17 AND $age <99 AND $ProfileGender==1){ $divisionDir="/men/";}
					elseif($age > 12 AND $age <=17 AND $ProfileGender==2){ $divisionDir="/teen-girls/";}
					elseif($age > 12 AND $age <=17 AND $ProfileGender==1){ $divisionDir="/teen-boys/";}
					elseif($age > 1 AND $age <=12 AND $ProfileGender==2){ $divisionDir="/girls/";}
					elseif($age > 1 AND $age <=12 AND $ProfileGender==1){ $divisionDir="/boys/";}
					else{ $divisionDir="/";}
					?>
					<div id="model-nav">
						<ul>
							<li class="prev"><a href="<?php  get_bloginfo("url");?>/profile/<?php echo linkPrevNext($ProfileGallery,"previous",$ProfileGender,$divisionDir);?>/" title=""><?php echo __("Previous", RBAGENCY_TEXTDOMAIN)?></a> </li>
							<li class="back"><a href="<?php  get_bloginfo("url");?>/divisions<?php echo $divisionDir;?>" title=""><?php echo __("Back to", RBAGENCY_TEXTDOMAIN)?><br />Division</a></li>
							<li class="next"><a href="<?php  get_bloginfo("url");?>/profile/<?php echo linkPrevNext($ProfileGallery,"next",$ProfileGender,$divisionDir);?>/" title=""><?php echo __("Next", RBAGENCY_TEXTDOMAIN)?></a>  </li>
						</ul>
					</div>
				</div> <!-- #profile-info -->
			</div><!-- .rbcol-4 -->
                 <?php RBAgency_Common::print_profile($ProfileID,$ProfileGallery, $ProfileContactDisplay); ?>
		</div><!-- .rbcol-12 -->
		<div class="rbclear"></div>
		
	</div><!-- #rblayout-six -->
</div><!-- #rbprofile -->