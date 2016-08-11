<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/01/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );
/*
 * Layout 
 */

$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
$urlexploade = explode("/", $profileURLString);

if (isset($urlexploade[1])){
	$subview = $urlexploade[1];
} else {
	$subview = "";
}


# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = isset($rb_agency_options_arr['rb_agency_option_galleryorder'])?$rb_agency_options_arr['rb_agency_option_galleryorder']:0;
$rb_agency_option_unittype = isset($rb_agency_options_arr['rb_agency_option_unittype'])?$rb_agency_options_arr['rb_agency_option_unittype']:0;
$rb_agency_option_profile_thumb_caption = isset($rb_agency_options_arr['rb_agency_option_profile_thumb_caption'])?$rb_agency_options_arr['rb_agency_option_profile_thumb_caption']:0;

$display_gender = isset($rb_agency_options_arr['rb_agency_option_viewdisplay_gender']) ? $rb_agency_options_arr['rb_agency_option_viewdisplay_gender']:false;

echo "	<div id=\"rbprofile\">\n";
echo "		<div id=\"rblayout-one\" class=\"rblayout\">\n";
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
echo "			<div class=\"rbcol-8 rbcolumn\">\n";
echo "				<div id=\"profile-audio\" >\n";
echo "<h2>".__('Voice Demos')."</h2>";
echo "<style>
.entry-header{ float:right!important; }
.entry-header h1{font-size:19px!important; }
.sc_player_container1{margin-top:-20px; display:block!important;}
.sc_player_container1 .myButton_stop,.sc_player_container1 .myButton_play{display:block!important;}
.sc_player_container1 .myButton_stop{margin-left:35px!important;margin-top:-32px!important;}
.sc_player_container1 .myButton_play{margin-top:5px!important;}
.demoname-border{ height:5px;}
</style>";
						// images
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType = \"VoiceDemo\"";
						$resultsImg = $wpdb->get_results($wpdb->prepare($queryImg, $ProfileID),ARRAY_A);
						$countImg = $wpdb->num_rows;
						foreach($resultsImg as $dataImg){	
							$audiofile = RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];
							$key_voice_caption = 'voicedemocaption_' . $dataVoice['ProfileMediaID'];
							$voiceCaption = get_option($key_voice_caption,'Voice Demo Caption');
							$profileMediaID = get_option("voicedemo_".$dataImg['ProfileMediaID']);
							$voicedemo = empty($profileMediaID) ? "" : $profileMediaID;
							echo "<br>";
							echo $voicedemo."<br>";
							echo $voiceCaption;
							echo "<hr class='demoname-border'>";
							echo  do_shortcode('[sc_embed_player fileurl="'.$audiofile.'"]');
							//echo '<audio><source src="'.$audiofile.'" /></audio><br>';
						}

						$dir = RBAGENCY_UPLOADPATH ."_casting-jobs/";
						$files = scandir($dir, 0);
						//print_r($files);
						$medialink_option = $rb_agency_options_arr['rb_agency_option_profilemedia_links'];

						for($i = 0; $i < count($files); $i++){
						$parsedFile = explode('-',$files[$i]);
												
						if($ProfileID == $parsedFile[1]){

							//$mp3_file = str_replace(array($parsedFile[0].'-',$parsedFile[1].'-'),'',$files[$i]);
								if($medialink_option == 2){
									//open in new window and play
									$au = get_option("auditiondemo_".str_replace('.mp3','',$files[$i]));
									$auditiondemo = empty($au) ? "Play Audio" : $au;
									echo "<br>";
									echo $auditiondemo."<br>";
									echo "<hr class='demoname-border'>";
									echo  do_shortcode('[sc_embed_player fileurl="'.site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i].'"]');
									//echo '<audio><source src="'.site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i].'" /></audio><br>';
								}elseif($medialink_option == 3){
									//open in new window and download															
															

									$force_download_url = wpfdl_dl('_casting-jobs/'.$files[$i],get_option('wpfdl_token'),'dl');
									$au = get_option("auditiondemo_".str_replace('.mp3','',$files[$i]));
									$auditiondemo = empty($au) ? "Play Audio" : $au;
									echo "<br>";
									echo $auditiondemo."<br>";
									echo "<hr class='demoname-border'>";
									echo  do_shortcode('[sc_embed_player fileurl="'.site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i].'"]');
									//echo '<audio><source src="'.site_url().'/wp-content/uploads/profile-media/_casting-jobs/'.$files[$i].'" /></audio><br>';
								}
														
							}
						}

echo "				</div>\n"; // #profile-picture
echo "				<div id=\"soundcloud\">";

						$querySC = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType = \"SoundCloud\" ORDER BY $orderBy";
						$resultsSC = $wpdb->get_results($wpdb->prepare($querySC, $ProfileID),ARRAY_A);
						$countSC = $wpdb->num_rows;

						if($countSC > 0){
							echo "<h3>".__("SoundCloud",RBAGENCY_TEXTDOMAIN)."</h3>";
							foreach($resultsSC as $dataSC){
								echo RBAgency_Common::rb_agency_embed_soundcloud($dataSC['ProfileMediaURL']);
							}
						}

echo "				</div>";
echo "			</div>\n"; // .rbcol-4

echo "			<div class=\"rbcol-4 rbcolumn\">\n";
echo "				<div id=\"profile-info\">\n";
echo "					<div id=\"stats\">\n";
echo "						<ul>\n";

								$ParseAge = explode(' ',$ProfileAge);

								$hideTheAgeYear = 0;
								$hideTheAgeMonth = 0;
								$hideTheAgeDay = 0;

								$theYear = str_replace('>','',$ParseAge[1]);
								$theMonth = str_replace('>','',$ParseAge[4]);
								$theDay = str_replace('>','',$ParseAge[7]);

								if((empty($theYear) || !is_numeric($theYear)) || $hideY == 1){
									$hideTheAgeYear = 1;
								}

								if((empty($theMonth) || !is_numeric($theMonth)) || $hideM == 1){
									$hideTheAgeMonth = 1;
								}

								if((empty($theDay) || !is_numeric($theDay)) || $hideD == 1){
									$hideTheAgeDay = 1;
								}


								if($hideTheAgeYear == 1 && $hideTheAgeMonth == 1 && $hideTheAgeDay == 1){
									$hideAgeLabel = 'style="display:none!important;"';
								}

								if(!empty($ProfileAge) && $ProfileDateBirth != '0000-00-00'){
									echo "<li class=\"rb_gender\" id=\"rb_age\" ".$hideAgeLabel." ><strong>". __("Age", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> ". __($ProfileAge, RBAGENCY_TEXTDOMAIN). "</li>\n";
								}

								if(!empty($ProfileGender) and $display_gender == true){
									$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID=%d", $ProfileGender),ARRAY_A,0);
									echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> ". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</li>\n";
								}

								if(!empty($ProfileStatHeight)){
										echo "<li class=\"rb_height\" id=\"rb_height\"><strong>". __("Height", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> ".rb_get_height($ProfileStatHeight)."</li>\n";
								}

								if(!empty($ProfileStatWeight)){
									if ($rb_agency_option_unittype == 0){ // Metric
										echo "<li class=\"rb_weight\" id=\"rb_weight\"><strong>". __("Weight", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> ". $ProfileStatWeight ." ". __("kg", RBAGENCY_TEXTDOMAIN). "</li>\n";
									} else { // Imperial
										echo "<li class=\"rb_weight\" id=\"rb_weight\"><strong>". __("Weight", RBAGENCY_TEXTDOMAIN). "<span>:</span></strong> ". $ProfileStatWeight ." ". __("lb", RBAGENCY_TEXTDOMAIN). "</li>\n";
									}
								}

								// Insert Custom Fields
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender, $table=false);
								get_social_media_links($ProfileID);
echo "						</ul>\n"; // Close Stats ul
echo "					</div>\n"; // #stats

echo "				</div>\n"; // #profile-info
echo "			</div>\n";// .rbcol-5

echo "			<div class=\"rbcol-3 rbcolumn\">\n";

					/*
					 * Include Action Icons
					 */
					include (plugin_dir_path(dirname(__FILE__)) .'/partial/include-profile-actions-16.php');

echo "			</div>\n";// .rbcol-3

echo "			<div class=\"rbcol-12 rbcolumn\">\n";

					if($subview == ""){
echo "					<div id=\"photos\" class=\"lightbox-enabled profile-photos\">\n";

							// images
							//$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID = %d AND ProfileMediaType IN(\"Image\")  AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
							$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
							$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
							$countImg = $wpdb->num_rows;
							foreach($resultsImg as $dataImg ){
								if($primary_image_handler != $dataImg['ProfileMediaURL']){
									echo "<div class=\"photo\">";
									echo "	<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=150\" /></a>";
									if($rb_agency_option_profile_thumb_caption ==1) {
										echo "	<small>".$dataImg['ProfileMediaURL']."</small>\n";
									}
									echo "</div>\n";
								}
							}

echo "					</div>\n"; // #photos
					}

					if($subview == "images"){ //show all images page  //MODS 2012-11-28 ?>
						<div id="print-photos" class="profile-photos">
							<script>  //JS to higlight selected images 
								function selectImg(mid){
								//document.getElementById('selected_image').value=mid+"|"+document.getElementById('selected_image').value;

									if(document.getElementById("p" + mid).value == 1){
										img = document.getElementById(mid);
										img.style.filter       = "alpha(opacity=100)";
										img.style.MozOpacity   = "100";
										img.style.opacity      = "100";
										img.style.KhtmlOpacity = "100";
										document.getElementById("p"+mid).value = 0;
									} else {
										document.getElementById("p"+mid).value = 1;
										img = document.getElementById(mid);
										img.style.filter       = "alpha(opacity=25)";
										img.style.MozOpacity   = "0.25";
										img.style.opacity      = "0.25";
										img.style.KhtmlOpacity = "0.25";
									}
								}

								function validateAllImageForm()	{

									if(!jQuery(".allImageCheck").is(":checked")){
										alert("Please select atleast one photo!");
										return false;
									}
									return true;
								}
							</script>

							<p class="allimages_text"><?php echo __("Please select photos to print. Maximum is 100 photos only", RBAGENCY_TEXTDOMAIN)?><br /></p><br />
							<form action="../print-images/" method="post" id="allimageform" onsubmit="return validateAllImageForm()">
								<input type="hidden" id="selected_image" name="selected_image" />

								<?php
								$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
								$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
								$countImg = $wpdb->num_rows;

								foreach($resultsImg as $dataImg ){
									echo '<div class="photo print-photo">';
									echo '	<a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
									echo "	<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=150\" alt=\"". $ProfileContactDisplay ."\" /></a>";
									echo "	<div class=\"print-action\"><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
									echo "</div>";
								}
								?>

								<br clear="all" />
								<input type="submit" value="<?php echo __("Next, Select Print Format",RBAGENCY_TEXTDOMAIN); ?>" onclick='jQuery("#allimageform").attr("action","../print-images/");'/><?php echo __("or",RBAGENCY_TEXTDOMAIN); ?>
								<input type="submit" value="<?php echo __("Next, Select PDF Format",RBAGENCY_TEXTDOMAIN); ?>" onclick='jQuery("#allimageform").attr("action","../print-pdf/");' />
							</form>
						</div><!-- allimages_div-->

					<?php

					} elseif($subview == "polaroids"){ //show all polaroids page  //MODS 2012-11-28 ?>

						<div id="polariods" class="profile-photos">
							<script>  //JS to higlight selected images 
								function selectImg(mid){
									//document.getElementById('selected_image').value=mid+"|"+document.getElementById('selected_image').value;

									if(document.getElementById("p"+mid).value == 1){
										img = document.getElementById(mid);
										img.style.filter       = "alpha(opacity=100)";
										img.style.MozOpacity   = "100";
										img.style.opacity      = "100";
										img.style.KhtmlOpacity = "100";
										document.getElementById("p"+mid).value = 0;
									} else {
										document.getElementById("p"+mid).value = 1;
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
							$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
							$countImg = $wpdb->num_rows;

							if($countImg>0){ ?>
							<form action="../print-polaroids/" method="post" id="allimageform">
								<input type="hidden" id="selected_image" name="selected_image" />

								<?php  
								foreach($resultsImg as $dataImg ){
									echo '<div class="photo">';
									echo '	<a href="'. RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .'" rel="lightbox-mygallery" class="allimages_print" href="javascript:void(0)">'; // onClick="selectImg('.$dataImg["ProfileMediaID"].')"
									echo "	<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&h=150\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
									echo '</div>';
								}
								?> <br clear="all" />

								<!--	<input type="submit" value="Next, Select Print Format" />-->
							</form>

							<?php
							} else { ?>
								<p>Sorry, there is no available polaroid images for this profile.</p>
							<?php
							}
							?>
						</div><!-- allimages_div-->

					<?php

					} else if ($subview == "print-polaroids"){ //show print options

						if(isset($_POST["print_type"])){
							include_once(RBAGENCY_PLUGIN_DIR."theme/pdf-profile.php");
						}

						$queryImg = rb_agency_option_galleryorder_query($orderBy ,$ProfileID,"Polaroid");
						$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
						$countImg = $wpdb->num_rows;

						foreach($resultsImg as $dataImg ){
							if($_POST[$dataImg['ProfileMediaID']] == 1){
								$selected .= "<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
								$withSelected = 1;
							}
								$lasID = $dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
						}
						if($withSelected != 1){
							$selected = "<input type='hidden' value='1' name='".$lasID."'>";
						}?>

						<div class="print_options">
							<span class="allimages_text"><?php echo __("Select Print Format",RBAGENCY_TEXTDOMAIN); ?></span><br /><br />
						</div> 

						<form action="" method="post">
							<?php echo $selected;?>
							<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
							<!-- display options-->

							<div id="polaroids" class="rbcol-12 rbcolumn">

								<div class="rbcol-6 rbcolumn">
									<input type="radio" value="11" name="print_option" checked="checked" /><h3><?php echo __("Four Polaroids Per Page",RBAGENCY_TEXTDOMAIN); ?></h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-four-per-page.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<div class="rbcol-6 rbcolumn">
									<input type="radio" value="12" name="print_option" /><h3><?php echo __("One Polaroid Per Page",RBAGENCY_TEXTDOMAIN); ?></h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-one-per-page.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

							</div><!-- polariod -->

							<center>
								<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->
								<input type="submit" value="<?php echo __("Print Polaroids",RBAGENCY_TEXTDOMAIN); ?>" name="print_all_images" />
								<input type="submit" value="<?php echo __("Download PDF Polaroids",RBAGENCY_TEXTDOMAIN); ?>" name="pdf_all_images" />
							</center>
						</form>

					<?php

					} else if($subview == "print-pdf"){

						if(isset($_POST["print_type"])){
							include_once(RBAGENCY_PLUGIN_DIR."theme/pdf-profile.php");
						}

						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
						$countImg = $wpdb->num_rows;
						$withSelected = 0;
						$selected = "";

						foreach($resultsImg as $dataImg ){

							if(isset($_POST[$dataImg['ProfileMediaID']]) && $_POST[$dataImg['ProfileMediaID']] == 1){
								$selected .= "<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
								$withSelected = 1;
							}
							$lasID = $dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
						}

						if($withSelected != 1){
							$selected = "<input type='hidden' value='1' name='".$lasID."'>";
						}?>

						<div class="print_options">
							<span class="allimages_text"><?php echo __("Select Print Format",RBAGENCY_TEXTDOMAIN); ?></span><br /><br />
						</div> 

						<form action="" method="post">
							<?php echo $selected;?>
							<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
							<!-- display options-->

							<div id="polaroids" class="rbcol-12 rbcolumn">
								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="1" name="print_option" checked="checked" /><?php echo __("Print Large Photos",RBAGENCY_TEXTDOMAIN); ?> </h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-large-photo-with-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="3" name="print_option" /><?php echo __("Print Medium Size Photos",RBAGENCY_TEXTDOMAIN); ?> </h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-medium-photo-with-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->


								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="1-1" name="print_option" /> <?php echo __("Print Large Photos Without Model Info",RBAGENCY_TEXTDOMAIN); ?></h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-large-photo-without-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="3-1" name="print_option" /> <?php echo __("Print Medium Size Photos Without Model Info",RBAGENCY_TEXTDOMAIN); ?></h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-medium-photo-without-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<?php

								if(isset($_POST['pdf_image_id']) && !empty($_POST['pdf_image_id'])){
									if(is_array($_POST['pdf_image_id'])){
										$pdf_image_id = implode(',',$_POST['pdf_image_id']);
									} else {
										$pdf_image_id = $_POST['pdf_image_id'];
									}
								?>
									<input type="hidden" name="pdf_image_id" value="<?php echo($pdf_image_id);?>" />
								<?php
								}
								?>
							</div><!-- #polariods -->
							<center>
								<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->
								<input type="submit" value="<?php echo __("Download PDF",RBAGENCY_TEXTDOMAIN); ?>" name="pdf_all_images" />
							</center>
						</form>

					<?php
					} else if($subview == "print-images"){ //show print options

						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
						$countImg = $wpdb->num_rows;
						$withSelected = 0;

						foreach($resultsImg as $dataImg ){
							if(isset($_POST[$dataImg['ProfileMediaID']]) && $_POST[$dataImg['ProfileMediaID']] == 1){
							$selected .= "<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
							$withSelected = 1;
							}
							$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
						}

						if($withSelected != 1){$selected = "<input type='hidden' value='1' name='".$lasID."'>"; }
						?>

						<div class="print_options">
							<span class="allimages_text"><?php echo __("Select Print Format",RBAGENCY_TEXTDOMAIN); ?></span><br /><br />
						</div> 

						<form action="" method="post" target="_blank">
							<?php echo $selected;?>
							<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
							<!-- display options-->

							<div id="polaroids" class="rbcol-12 rbcolumn">
								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="1" name="print_option" checked="checked" /> <?php echo __("Print Large Photos",RBAGENCY_TEXTDOMAIN); ?></h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-large-photo-with-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="3" name="print_option" /><?php echo __("Print Medium Size Photos",RBAGENCY_TEXTDOMAIN); ?> </h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-medium-photo-with-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->


								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="1-1" name="print_option" /> <?php echo __("Print Large Photos Without Model Info",RBAGENCY_TEXTDOMAIN); ?></h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-large-photo-without-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<div class="rbcol-6 rbcolumn">
									<h3><input type="radio" value="3-1" name="print_option" /><?php echo __("Print Medium Size Photos Without Model Info",RBAGENCY_TEXTDOMAIN); ?> </h3>
									<div class="polaroid">
										<img src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/view/layout/06/images/polariod-medium-photo-without-model-info.png" alt="" />
									</div><!-- polariod -->
								</div><!-- .six .rbcolumn -->

								<?php
									if(isset($_POST['pdf_image_id'])){
										$pdf_image_id = implode(',',$_POST['pdf_image_id']);
								?>
									<input type="hidden" name="pdf_image_id" value="<?php echo($pdf_image_id);?>" />
								<?php
									}
								?>


							</div><!-- polariod -->
							<center>
								<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->
								<input type="submit" value="<?php echo __("Print Pictures",RBAGENCY_TEXTDOMAIN); ?>" name="print_all_images" />&nbsp;
							</center>
						</form>
					<?php
					}

echo "				<div class=\"rbclear\"></div>\n"; // Clear All
echo "			</div>\n"; // .rbcol-12

echo "			<div class=\"rbclear\"></div>\n"; // Clear All
echo "		</div>\n";// Close Profile Layout
echo "	</div>\n";// Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All

?>
<script>
  audiojs.events.ready(function() {
    var as = audiojs.createAll();
  });
</script>
