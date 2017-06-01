<?php
/*
Profile View with Thumbnails and Primary Image with Print
*/
# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-eleven\" class=\"rblayout\">\n";
echo "			<div class=\"rbcol-12 rbcolumn\">\n";
echo "				<header class=\"entry-header\">";
echo "					<h1 class=\"entry-title\">". $ProfileContactDisplay ."</h1>";
echo "				</header>";



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




echo "			</div>\n"; // .rbcol-12
echo "			<div class=\"rbcol-4 rbcolumn\">\n";
echo "				<div id=\"profile-picture\">\n";
						// images
						$private_profile_photo = get_user_meta($ProfileUserLinked,'private_profile_photo',true);
						$private_profile_photo_arr = explode(',',$private_profile_photo);
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							if(!in_array($dataImg['ProfileMediaID'],$private_profile_photo_arr)){
								echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
							}
							
						}
echo "				</div>\n"; // #profile-picture
echo "			</div>\n"; // .rbcol-4

echo "			<div class=\"rbcol-5 rbcolumn\">\n";
echo "					<div id=\"profile-info\">\n";
echo "						<div id=\"stats\">\n";
echo "							<ul>\n";
								if (!empty($ProfileGender) and $display_gender == true) {
									$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' "),ARRAY_A,0 	);
									echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</li>\n";
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
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
								get_social_media_links($ProfileID);
echo "							</ul>\n"; // Close Stats ul
echo "						</div>\n"; // #stats

echo "					</div>\n"; // #profile-info
echo "				</div>\n";// .rbcol-5

echo "			<div class=\"rbcol-3 rbcolumn\">\n";
echo "					<div id=\"links\">\n";
echo "					<ul>\n";
					/*
					 * Include Action Icons
					 */
						include (plugin_dir_path(dirname(__FILE__)) .'/partial/include-profile-actions.php');

/*						// Resume
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
								if ($countMedia > 0) {
									foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item resume\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Print Resume</a></li>\n";
									}
							}
							// Comp Card
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Comp Card");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
								if ($countMedia > 0) {
									foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item compcard\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Comp Card</a></li>\n";
									}
							}
							// Headshots
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Headshot");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
									foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item headshot\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Headshot</a></li>\n";
									}
							}
							//Voice Demo
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"VoiceDemo");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item voice\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Listen to Voice Demo</a></li>\n";
									}
							}
							//Video Slate

							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
									echo "<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Slate</a></li>\n";
									}
							}
							//Video Monologue

							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Monologue</a></li>\n";
									}
							}
							//Demo Reel

							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia  = $wpdb->num_rows;
							if ($countMedia > 0) {
								foreach($resultsMedia as $dataMedia ){
									echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Demo Reel</a></li>\n";
									}
							}

							// Other Media Type not the 
							// default ones
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
									                       AND ProfileMediaType NOT IN ('Image','Resume','Polaroid','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
														";
							$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
							$countMedia = $wpdb->num_rows;
							if ($countMedia > 0) {
									foreach($resultsMedia as $dataMedia ){
						              echo "<li class=\"item video demoreel\"><a target=\"_blank\" href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">".$dataMedia['ProfileMediaType']. "</a></li>\n";
									}
							}
							//Contact Profile
							if($rb_agency_option_showcontactpage==1){
								echo "<div class=\"rel\"><strong>". __("Contact: ", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
							}

							echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/\">". __("View Photos", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/images/\">". __("Print Photos", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/polaroids/\">". __("View Polaroids", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30
							echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/print-polaroids/\">". __("Print Polaroids", RBAGENCY_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30

							 */
echo "					</ul>\n";
echo "					</div>\n";// #links
echo "				</div>\n";// .rbcol-8

echo "			<div class=\"rbcol-12 rbcolumn\" style='clear:both;'>\n";

$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
$urlexploade = explode("/", $profileURLString);
$subview=$urlexploade[1];

if(isset($_POST['pdf_all_images']) && $_POST['pdf_all_images']!=""){
	ob_start();
	include_once(ABSPATH . 'wp-content/plugins/rb-agency/theme/pdf-profile.php');
	ob_end_flush();
	exit;
}

if($subview=="images"){ //show all images page  //MODS 2012-11-28 ?>
				<div class="allimages_div">
					<script>  //JS to higlight selected images 
						function selectImg(mid){
						//document.getElementById('selected_image').value=mid+"|"+document.getElementById('selected_image').value;

							if(document.getElementById("p"+mid).value==1){
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=100)";
								img.style.MozOpacity   = "100";
								img.style.opacity      = "100";
								img.style.KhtmlOpacity = "100";
								document.getElementById("p"+mid).value=0;
							} else {
								document.getElementById("p"+mid).value=1;
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=25)";
								img.style.MozOpacity   = "0.25";
								img.style.opacity      = "0.25";
								img.style.KhtmlOpacity = "0.25";
							}

						}

						function validateAllImageForm()
						{
							if (!jQuery(".allImageCheck").is(":checked"))
							{
								alert("Please select atleast one photo!");
								return false;
							}

							return true;
						}
					</script>
					<span class="allimages_text"><?php echo __("Please select photos to print. Maximum is 100 photos only", RBAGENCY_TEXTDOMAIN)?><br /></span><br />
					<form action="../print-images/" method="post" id="allimageform" onsubmit="return validateAllImageForm()">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  

						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							echo '<div style="margin:4px; float:left;width:115px;height:150px;"><a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
							echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/view/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt=\"". $ProfileContactDisplay ."\" /></a><br /><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
						}
						?> <br clear="all" />
						<input type="submit" value="<?php echo __("Next, Select Print Format",RBAGENCY_TEXTDOMAIN); ?>" />
					</form>
					</div><!-- allimages_div-->

					<?php  //load lightbox for images
					}
elseif($subview=="polaroids"){ //show all polaroids page  //MODS 2012-11-28 ?>

				<div class="allimages_div">
					<script>  //JS to higlight selected images 
						function selectImg(mid){
							//document.getElementById('selected_image').value=mid+"|"+document.getElementById('selected_image').value;

							if(document.getElementById("p"+mid).value==1){
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=100)";
								img.style.MozOpacity   = "100";
								img.style.opacity      = "100";
								img.style.KhtmlOpacity = "100";
								document.getElementById("p"+mid).value=0;
							} else {
								document.getElementById("p"+mid).value=1;
								img = document.getElementById(mid);
								img.style.filter       = "alpha(opacity=25)";
								img.style.MozOpacity   = "0.25";
								img.style.opacity      = "0.25";
								img.style.KhtmlOpacity = "0.25";
							}

						}
					</script>
					<?php 

					$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Polaroid");
					$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
					$countImg  = $wpdb->num_rows;
					if($countImg>0){
					?>
					<span class="allimages_text"><br /></span><br />
					<form action="../print-polaroids/" method="post" id="allimageform">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  
						foreach($resultsImg as $dataImg ){
							echo '<a href="'. RBAGENCY_UPLOADDIR . $ProfileGallery ."/polariod/". $dataImg['ProfileMediaURL'] .'" rel="lightbox-mygallery" class="allimages_print" href="javascript:void(0)">'; // onClick="selectImg('.$dataImg["ProfileMediaID"].')"
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/view/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/polariod/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
						}
						?> <br clear="all" />

						<!--	<input type="submit" value="Next, Select Print Format" />-->

					</form> <?php } else { ?>Sorry, there is no available polaroid images for this profile.<?php }?>
				</div><!-- allimages_div-->

			<?php } else if ($subview=="print-polaroids"){ //show print options

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Polaroid");
				$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
				$countImg  = $wpdb->num_rows;
				foreach($resultsImg as $dataImg ){
					if($_POST[$dataImg['ProfileMediaID']]==1){
						$selected.="<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
						$withSelected=1;
					}
						$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
				}
				if($withSelected!=1){$selected="<input type='hidden' value='1' name='".$lasID."'>";}
				?>

				<div class="print_options">
					<span class="allimages_text"><?php echo __("Select Print Format",RBAGENCY_TEXTDOMAIN); ?></span><br /><br />
				</div> 

				<form action="" method="post" target="_blank">
					<?php echo $selected;?>
					<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					<!-- display options-->

					<div id="polaroids" class="rbcol-8 rbcolumn">

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="11" name="print_option" checked="checked" /><h3><?php echo __("Four Polaroids Per Page",RBAGENCY_TEXTDOMAIN); ?></h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-four-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="12" name="print_option" /><h3><?php echo __("One Polaroid Per Page",RBAGENCY_TEXTDOMAIN); ?></h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-one-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

					</div><!-- polariod -->

					<center>
						<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->

						<input type="submit" value="<?php echo __("Print Polaroids",RBAGENCY_TEXTDOMAIN); ?>" name="print_all_images" />
								<input type="submit" value="<?php echo __("Download PDF Polaroids",RBAGENCY_TEXTDOMAIN); ?>" name="pdf_all_images" />
					</center>
				</form>


			<?php } else if($subview=="print-images") { //show print options

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
				$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
				$countImg  = $wpdb->num_rows;
				foreach($resultsImg as $dataImg ){
				if($_POST[$dataImg['ProfileMediaID']]==1){
				$selected.="<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
				$withSelected=1;
				}
				$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
				}
				if($withSelected!=1){$selected="<input type='hidden' value='1' name='".$lasID."'>";}
				?>

				<div class="print_options">
					<span class="allimages_text"><?php echo __("Select Print Format",RBAGENCY_TEXTDOMAIN); ?></span><br /><br />
				</div> 

				<form action="" method="post" target="_blank">
					<?php echo $selected;?>
					<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					<!-- display options-->

					<div id="polaroids" class="rbcol-8 rbcolumn">
						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1" name="print_option" checked="checked" /><h3><?php echo __("Print Large Photos",RBAGENCY_TEXTDOMAIN); ?></h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3" name="print_option" /><h3><?php echo __("Print Medium Size Photos",RBAGENCY_TEXTDOMAIN); ?></h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->


						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1-1" name="print_option" /><h3><?php echo __("Print Large Photos Without Model Info",RBAGENCY_TEXTDOMAIN); ?></h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3-1" name="print_option" /><h3><?php echo __("Print Medium Size Photos Without Model Info",RBAGENCY_TEXTDOMAIN); ?></h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<?php
							if(isset($_POST['pdf_image_id'])) {
								$pdf_image_id=implode(',',$_POST['pdf_image_id']);
						?>
							<input type="hidden" name="pdf_image_id" value="<?php echo($pdf_image_id);?>" />
						<?php
							}
						?>


						</div><!-- polariod -->
					<center>
						<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->

						<input type="submit" value="<?php echo __("Print Pictures",RBAGENCY_TEXTDOMAIN); ?>" name="print_all_images" />&nbsp;
						<input type="submit" value="<?php echo __("Download PDF",RBAGENCY_TEXTDOMAIN); ?>" name="pdf_all_images" />
					</center>
				</form>

			<?php } else {

					echo "					<div id=\"photos\">\n";

											// images
											$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
											$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
											$countImg  = $wpdb->num_rows;
											foreach($resultsImg as $dataImg ){
												echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
											}
											echo "	<div class=\"rbclear\"></div>\n"; // Clear All
					echo "					</div>\n"; // #photos
			}


echo "			</div>\n"; // .rbcol-12

echo "				<div class=\"rbclear\"></div>\n"; // Clear All
echo " 		</div>\n";// Close Profile Layout
echo "	</div>\n";// Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>