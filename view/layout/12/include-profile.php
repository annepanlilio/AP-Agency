<?php
/*
Profile View with Thumbnails and Primary Image with Print
*/

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-eleven\" class=\"rblayout\">\n";
echo "			<div class=\"rbcol-12 rbcolumn\">\n";
echo "				<header class=\"entry-header\">";
echo "					<h1 class=\"entry-title\">". $ProfileContactDisplay ."</h1>";
echo "				</header>";
echo "			</div>\n"; // .rbcol-12
echo "			<div class=\"rbcol-4 rbcolumn\">\n";
echo "				<div id=\"profile-picture\">\n";
						// images
						# rb_agency_option_galleryorder
						$rb_agency_options_arr = get_option('rb_agency_options');
						$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
						}
echo "				</div>\n"; // #profile-picture
echo "			</div>\n"; // .rbcol-4

echo "			<div class=\"rbcol-5 rbcolumn\">\n";
echo "	  			<div id=\"profile-info\">\n";
echo "	  				<div id=\"stats\">\n";
echo "	  					<ul>\n";
								if (!empty($ProfileGender)) {
									$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
									$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
									echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
								}

								
								if (!empty($ProfileStatHeight)) {
									if ($rb_agency_option_unittype == 0) { // Metric
										echo "<li><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", rb_agency_TEXTDOMAIN). "" ."</li>\n";
									} else { // Imperial
										$heightraw = $ProfileStatHeight;
										$heightfeet = floor($heightraw/12);
										$heightinch = $heightraw - floor($heightfeet*12);
										echo "<li><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", rb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", rb_agency_TEXTDOMAIN). "" ."</li>\n";
									}
								}
								if (!empty($ProfileStatWeight)) {
									if ($rb_agency_option_unittype == 0) { // Metric
										echo "<li><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", rb_agency_TEXTDOMAIN). "</li>\n";
									} else { // Imperial
										echo "<li><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", rb_agency_TEXTDOMAIN). "</li>\n";
									}
								}

								// Insert Custom Fields
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
echo "	  					</ul>\n"; // Close Stats ul
echo "	  				</div>\n"; // #stats
echo "					<div id=\"experience\">\n";
echo						$ProfileExperience;
echo "					</div>\n"; // #experience
echo "	  			</div>\n"; // #profile-info
echo "	  		</div>\n";  // .rbcol-5

echo "			<div class=\"rbcol-3 rbcolumn\">\n";
echo "	  			<div id=\"links\">\n";
echo "					<ul>\n";
						
						// Resume
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Print Resume</a></li>\n";
							  	}
							}						
							// Comp Card
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Comp Card\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Comp Card</a></li>\n";
							  	}
							}
							// Headshots
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Headshot</a></li>\n";
							  	}
							}			
							//Voice Demo
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							 	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Listen to Voice Demo</a></li>\n";
							  	}
							}
							//Video Slate
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
									echo "<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Slate</a></li>\n";
							  	}
							}
							//Video Monologue
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Monologue</a></li>\n";
							  	}
							}
							//Demo Reel
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" title=\"". $dataMedia['ProfileMediaTitle'] ."\" target=\"_blank\"></a><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Demo Reel</a></li>\n";
							  	}
							}

							// Other Media Type not the 
							// default ones
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
							                             AND ProfileMediaType NOT IN ('Image','Resume','Polaroid','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
														 ");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
							  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				                    echo "<li class=\"item video demoreel\"><a target=\"_blank\" href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">".$dataMedia['ProfileMediaType']. "</a></li>\n";
							  	}
							}
							//Contact Profile
							if($rb_agency_option_showcontactpage==1){
					    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\" class=\"rb_button\">Click Here</a></div>\n";
							}
				
							echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/\">". __("View Photos", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/images/\">". __("Print Photos", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
						    echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/polaroids/\">". __("View Polaroids", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30
						    echo "<li class=\"item resume\"><a class='rb_button' href=\"".get_bloginfo('wpurl')."/profile/".$ProfileGallery."/print-polaroids/\">". __("Print Polaroids", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30
						
							
echo "					</ul>\n";
echo "	  			</div>\n";  // #links
echo "	  		</div>\n";  // .rbcol-8

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

if($subview=="images"){//show all images page  //MODS 2012-11-28 ?>
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
							}else{
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
					<span class="allimages_text"><?php echo __("Please select photos to print. Maximum is 100 photos only", rb_agency_TEXTDOMAIN)?><br /></span><br />
					<form action="../print-images/" method="post" id="allimageform" onsubmit="return validateAllImageForm()">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo '<div style="margin:4px; float:left;width:115px;height:150px;"><a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
							echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/view/timthumb.php?src=".rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt=\"". $ProfileContactDisplay ."\" /></a><br /><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
						}
						?> <br clear="all" />
						<input type="submit" value="Next, Select Print Format" />
					</form>
					</div><!-- allimages_div-->

					<?php  //load lightbox for images
					}
elseif($subview=="polaroids"){//show all polaroids page  //MODS 2012-11-28 ?>

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
							}else{
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
					$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Polaroid\" ORDER BY $orderBy";
					$resultsImg = mysql_query($queryImg);
					$countImg = mysql_num_rows($resultsImg);?>
					<?php if($countImg>0){?>
					<span class="allimages_text"><br /></span><br />
					<form action="../print-polaroids/" method="post" id="allimageform">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo '<a href="'. rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .'" rel="lightbox-mygallery" class="allimages_print" href="javascript:void(0)">'; // onClick="selectImg('.$dataImg["ProfileMediaID"].')"
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/view/timthumb.php?src=".rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
						}
						?> <br clear="all" />

						<!--	<input type="submit" value="Next, Select Print Format" />-->

					</form> <?php }else{?>Sorry, there is no available polaroid images for this profile.<?php }?>
				</div><!-- allimages_div-->

			<?php } else if ($subview=="print-polaroids"){  //show print options

				$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Polaroid\" ORDER BY $orderBy";
				$resultsImg = mysql_query($queryImg);
				$countImg = mysql_num_rows($resultsImg);
				while ($dataImg = mysql_fetch_array($resultsImg)){
					if($_POST[$dataImg['ProfileMediaID']]==1){
						$selected.="<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
						$withSelected=1;
					}
						$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
				}
				if($withSelected!=1){$selected="<input type='hidden' value='1' name='".$lasID."'>";}
				?>

				<div class="print_options">
					<span class="allimages_text">Select Print Format</span><br /><br />
				</div> 

				<form action="" method="post" target="_blank">
					<?php echo $selected;?>
					<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					<!-- display options-->

					<div id="polaroids" class="rbcol-8 rbcolumn">

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="11" name="print_option" checked="checked" /><h3>Four Polaroids Per Page</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-four-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="12" name="print_option" /><h3>One Polaroid Per Page</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-one-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

					</div><!-- polariod -->

					<center>
						<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->

						<input type="submit" value="Print Polaroids" name="print_all_images" />
						<input type="submit" value="Download PDF Polaroids" name="pdf_all_images" />
					</center>
				</form>


			<?php } else if($subview=="print-images") {  //show print options
				 $queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
				$resultsImg = mysql_query($queryImg);
				$countImg = mysql_num_rows($resultsImg);
				while ($dataImg = mysql_fetch_array($resultsImg)){
				if($_POST[$dataImg['ProfileMediaID']]==1){
				$selected.="<input type='hidden' value='1' name='".$dataImg['ProfileMediaID']."'>";
				$withSelected=1;
				}
				$lasID=$dataImg['ProfileMediaID']; //make sure it will display picture even nothing weere selected
				}
				if($withSelected!=1){$selected="<input type='hidden' value='1' name='".$lasID."'>";}
				?>
				
				<div class="print_options">
					<span class="allimages_text">Select Print Format</span><br /><br />
				</div> 

				<form action="" method="post" target="_blank">
					<?php echo $selected;?>
					<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					<!-- display options-->

					<div id="polaroids" class="rbcol-8 rbcolumn">
						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1" name="print_option" checked="checked" /><h3>Print Large Photos</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3" name="print_option" /><h3>Print Medium Size Photos</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->


						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1-1" name="print_option" /><h3>Print Large Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3-1" name="print_option" /><h3>Print Medium Size Photos Without Model Info</h3>
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

						<input type="submit" value="Print Pictures" name="print_all_images" />&nbsp;
						<input type="submit" value="Download PDF" name="pdf_all_images" />
					</center>
				</form>
				
			<?php } else {

					echo "	  			<div id=\"photos\">\n";
						
											// images
											$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
											$resultsImg = mysql_query($queryImg);
											$countImg = mysql_num_rows($resultsImg);
											while ($dataImg = mysql_fetch_array($resultsImg)) {
												echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
											}
											echo "	<div class=\"rbclear\"></div>\n"; // Clear All
					echo "	  			</div>\n"; // #photos
			}


echo "			</div>\n"; // .rbcol-12

echo "	  		<div class=\"rbclear\"></div>\n"; // Clear All
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>