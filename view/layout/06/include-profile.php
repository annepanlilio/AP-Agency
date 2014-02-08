<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', plugins_url('/css/style.css', __FILE__) );
	wp_enqueue_style( 'rblayout-style' );


/*
 * Layout 
 */


/*
Large featured image and scrolling thumbnails
*/
// Donot Edit this this is for subview 
$profileURLString = get_query_var('target'); //$_REQUEST["profile"];
$urlexploade = explode("/", $profileURLString);
$subview=$urlexploade[1];
if(isset($_POST['pdf_all_images']) && $_POST['pdf_all_images']!=""){
	include 'pdf-profile.php';
	exit;
}



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

<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/custom-layout6/css/styles.css" />

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
								$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
								$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
								echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). ":</strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
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
							rb_agency_getProfileCustomFields($ProfileID, $ProfileGender); ?>
						</ul>
					</div>

					<div id="model-links">

						<?php
						echo "<ul>\n";
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/images/\">". __("Print Photos", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/print-polaroids/\">". __("Print Polaroids", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-28
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/polaroids/\">". __("View Polaroids", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-30
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/\">". __("View Slideshow", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-29
							echo "<li class=\"item resume\"><a href=\"".get_bloginfo('url')."/profile/".$ProfileGallery."/lightbox/\">". __("View in Lightbox", rb_agency_TEXTDOMAIN)."</a></li>\n"; //MODS 2012-11-29

							// Resume
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Print Resume</a></li>\n";
								}
							}
							// Comp Card
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"CompCard\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {$cpCnt++;  
									if($cpCnt == "2"){$cpCount="2nd";}
									elseif($cpCnt == "3"){$cpCount="3rd";}
									else{$cpCount="";}

									echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Download $cpCount Comp Card</a></li>\n";
								}
							}
							// Headshots
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">". __("Download Headshot", rb_agency_TEXTDOMAIN)."</a></li>\n";
								}
							}
							//Voice Demo
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {$vdCnt++;
									if($vdCnt == "2"){$vdCount="2nd";}
									elseif($vdCnt == "3"){$vdCount="3rd";}
									else{$vdCount="";}
									echo "<li class=\"item voice\"><a target='_blank' href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Listen to $vdCount Voice Demo</a></li>\n";
								}
							}
							//Video Slate
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									$profileVideoEmbed = $dataMedia['ProfileMediaURL'];
									echo "<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". __("Watch Video Slate", rb_agency_TEXTDOMAIN)."</a></li>\n";
								}
							}
							//Video Monologue
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Watch Video Monologue</a></li>\n";
								}
							}
							//Demo Reel
							$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
							$countMedia = mysql_num_rows($resultsMedia);
							if ($countMedia > 0) {
								while ($dataMedia = mysql_fetch_array($resultsMedia)) {
									echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">". __("Watch Demo Reel", rb_agency_TEXTDOMAIN)."</a></li>\n";
								}
							}
							if (is_plugin_active('rb-agency-casting/rb-agency-casting.php')) {
								echo rb_agency_get_new_miscellaneousLinks($ProfileID);
							}			
						echo "</ul>\n";?>                      
	            
					</div> <!-- .model-links -->

					<div id="resultsGoHereAddtoCart"></div>
	            	<div id="view_casting_cart" style="display:none;"><a href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></div>
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
							<li class="prev"><a href="<?php  get_bloginfo("url");?>/profile/<?php echo linkPrevNext($ProfileGallery,"previous",$ProfileGender,$divisionDir);?>/" title=""><?php echo __("Previous", rb_agency_TEXTDOMAIN)?></a> </li>
							<li class="back"><a href="<?php  get_bloginfo("url");?>/divisions<?php echo $divisionDir;?>" title=""><?php echo __("Back to", rb_agency_TEXTDOMAIN)?><br />Division</a></li>
							<li class="next"><a href="<?php  get_bloginfo("url");?>/profile/<?php echo linkPrevNext($ProfileGallery,"next",$ProfileGender,$divisionDir);?>/" title=""><?php echo __("Next", rb_agency_TEXTDOMAIN)?></a>  </li>
						</ul>
					</div>
				</div> <!-- #profile-info -->
			</div><!-- .rbcol-4 -->

			<?php  //to load profile page sub pages or just load the main profile page
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
						# rb_agency_option_galleryorder
						$rb_agency_options_arr = get_option('rb_agency_options');
						$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
							echo '<div style="margin:4px; float:left;width:115px;height:150px;"><a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
							echo "<img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/tasks/timthumb.php?src=".rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt=\"". $ProfileContactDisplay ."\" /></a><br /><input class=\"allImageCheck\" type=\"checkbox\" name=\"pdf_image_id[]\" value=\"".$dataImg['ProfileMediaID']."\"><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'></div>";
						}
						?> <br clear="all" />
						<input type="submit" value="Next, Select Print Format" />
					</form>
					</div><!-- allimages_div-->

					<?php  //load lightbox for images
					}elseif($subview=="lightbox"){//show all images page  //MODS 2012-11-28 ?>
						<div class="allimages_div">
						<span class="allimages_text"> <br /></span><br />
						<form action="../print-images/" method="post" id="allimageform">
						<input type="hidden" id="selected_image" name="selected_image" />
						<?php  
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
						echo '<a class="allimages_print" href="'. rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .'" rel="lightbox-mygallery">';
						echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/tasks/timthumb.php?src=".rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
					}
					?> <br clear="all" />

					</form>
				</div><!-- allimages_div-->

			<?php } elseif($subview=="polaroids"){//show all polaroids page  //MODS 2012-11-28 ?>

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
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/tasks/timthumb.php?src=".rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."&w=106&h=130\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
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
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-four-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="12" name="print_option" /><h3>One Polaroid Per Page</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-one-per-page.png" alt="" />
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
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3" name="print_option" /><h3>Print Medium Size Photos</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->


						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="1-1" name="print_option" /><h3>Print Large Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .rbcolumn -->

						<div class="rbcol-6 rbcolumn">
							<input type="radio" value="3-1" name="print_option" /><h3>Print Medium Size Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-without-model-info.png" alt="" />
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
				
			<?php } else { ?> 

				<div id="profile-slide" class="rbcol-8 rbcolumn">
					<div id="layout6-slider" class="flexslider">
						<ul class="slides">
							<?php
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
										$resultsImg = mysql_query($queryImg);
										$countImg = mysql_num_rows($resultsImg);
										while ($dataImg = mysql_fetch_array($resultsImg)) {
										  	echo "<li><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
										}
							?>
						</ul>
					</div>
					<div id="layout6-carousel" class="flexslider rbcol-12 rbcolumn">
						<ul class="slides">
							<?php
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
										$resultsImg = mysql_query($queryImg);
										$countImg = mysql_num_rows($resultsImg);
										while ($dataImg = mysql_fetch_array($resultsImg)) {
										  	echo "<li><figure style=\"background-image: url(". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] .")\" alt=\"". $ProfileContactDisplay ."\" ></figure></li>\n";
										}
							?>
						</ul>
					</div>
				</div><!-- #portfolio-slide -->
			
			<?php }?>
		</div><!-- .rbcol-12 -->
		<div class="rbclear"></div>
		
	</div><!-- #rblayout-six -->
</div><!-- #rbprofile -->