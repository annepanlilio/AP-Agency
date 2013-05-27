<?php
/*
Large featured image and scrolling thumbnails
*/

?>
<style>
    #tabstuff:after
    {
        content: "";
        display: table;
        padding: 0;
        margin: 0;
        line-height: 0;
        clear: both;
        float: none;
    }
    embed
    {
        height: 145px !important;
    }
    #profile-links
    {
        position: relative;
        z-index: 99;
    }
    #profile-links div
    {
        display: inline-block;
        float: left;
    }
    #profile-links div a
    {
        color: #fff;
        padding: .7em 1em;
        display: inline-block;
        text-decoration: none;
        text-transform: uppercase;
    }
    
    #profile-links .favorite-casting
    {
        margin: 5px 0 0 315px;
    }
    #profile-links .ui-tabs-nav
    {
        padding: 0;
    }
    #profile-links .ui-tabs .ui-tabs-nav li
    {
        margin-top: 0
    }
    #tabs_handel > ul li a
    {
        text-transform: uppercase;
    }
    ul.ui-tabs-nav li:not(last-child),
    #tabs_handel > ul li.has-border
    {
        border-right: 1px solid #fff;
    }
    .favorite-casting > div
    {
        border-right: 1px solid #fff;
    }
    .portfolio-gallery .ad-nav .ad-thumbs
    {
        width: 100% !important;
    }
    
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/custom-layout6/js/jquery.ad-gallery2.js"></script>
<script type="text/javascript">
	$(function() {
		var galleries = $('.portfolio-gallery').adGallery({
		  // or 'slide-vert', 'resize', 'fade', 'none' or false
		  effect: 'slide-hori',  
		  enable_keyboard_move: true,	
		  // Move to next/previous image with keyboard arrows?
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {

	    $('#division').change(function() {   
	        var qString = 'sub=' +$(this).val();
	        $.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/sub_db_handler.php', qString, processResponse);
	    });

	    function processResponse(data) {
	        $('#resultsGoHere').html(data);
	    }

	});
</script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/custom-layout6/css/styles.css" />

<div class="model-portfolio" style="background-color: #363636; margin-bottom: 0 !important">

<div id="post" <?php post_class(); ?>>


	<div class="cb"></div>

			<div class="portfolio-area" >

                            
                            
                            
                            
                            
                        <?php ////////////////////// INFO ////////////////////// ?>
			<div class="portfolio-info four column">

				<div class="panel" style="border: none; box-shadow: none; background-color: #363636; color: #fff">

					<div id="model-name">
						<h3 style="color: #fff"><?php echo $ProfileContactDisplay; ?></h3>
					</div>

					<div id="model-stats">
						<ul>
							<?php
							if (!empty($ProfileGender)) {
								$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
								$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
                                                                echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<b class=\"divider\">:</b></strong> ". $fetchGenderData["GenderTitle"] . "</li>\n";
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
							rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender);
							
							//$exclude="'16'";
							//rb_agency_getProfileCustomFieldsEcho($ProfileID, $ProfileGender,$exclude);?>
						</ul>
					</div>


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

				</div> <!-- .panel -->

			</div><!-- .portfolio-info -->
                        
                        
                        
                        
                        
                                
                        <div id="slider_new_place" class="eight column" style="padding-right:0;">
				<div class="portfolio-slide" style="margin-right: -1px; padding: 15px; background-color: #fff; box-sizing: border-box; -moz-box-sizing: border-box;">
                                    <div class="portfolio-gallery">
                                      <?php // we will add slider here via jQuery ?>  
                                    </div>
                                </div>
                        </div><!-- #slider_new_place -->
                                
                                
                        
                        
                                
                        <div class="cb"></div>        
                                
                                
                                
                        <div id="profile-links">
                                <?php echo rb_agency_get_new_miscellaneousLinks($ProfileID); ?>
                                <div class="ui-tabs ui-widget ui-widget-content ui-corner-all"></div>
                        </div>       
                                
                                

			</div><!-- .portfolio-area -->
		
                        
                        <div class="cb"></div>
                        
                        
                        
                        <?php ////////////////////// WE RENDER SLIDER HERE AT THE FIRST PACE THEN WE APPEND IT TO  ////////////////////// ?>
                        
                        
                        <div id="slider_place_holder">
                            
                            
                            
                            
                            
                            
                        <?php ////////////////////// SLIDER ////////////////////// ?>

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
						</script>
						<span class="allimages_text"><?php echo __("Next", rb_agency_TEXTDOMAIN)?><br /></span><br />
						<form action="../print-images/" method="post" id="allimageform">
							<input type="hidden" id="selected_image" name="selected_image" />
							<?php  
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							while ($dataImg = mysql_fetch_array($resultsImg)) {
								echo '<a class="allimages_print" href="javascript:void(0)" onClick="selectImg('.$dataImg["ProfileMediaID"].')">';
								echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
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
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
						}
						?> <br clear="all" />

						</form>
					</div><!-- allimages_div-->


				<?php }elseif($subview=="polaroids"){//show all polaroids page  //MODS 2012-11-28 ?>


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
							echo "<img id='".$dataImg["ProfileMediaID"]."' src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt='' class='allimages_thumbs' /></a><input type='hidden'  name='".$dataImg["ProfileMediaID"]."' id='p".$dataImg["ProfileMediaID"]."'>\n";
						}
						?> <br clear="all" />

						<!--	<input type="submit" value="Next, Select Print Format" />-->

					</form> <?php }else{?>Sorry, there is no available polaroid images for this profile.<?php }?>
				</div><!-- allimages_div-->
				<?php }else if($subview=="print-polaroids"){  //show print options
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

					<div id="polaroids" class="eight column">

						<div class="six column">
							<input type="radio" value="11" name="print_option" checked="checked" /><h3>Four Polaroids Per Page</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-four-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .column -->

						<div class="six column">
							<input type="radio" value="12" name="print_option" /><h3>One Polaroid Per Page</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-one-per-page.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .column -->

					</div><!-- polariod -->

					<center>
						<!--<input style="" type="radio" value="5" name="print_option" />&nbsp;Print Division Headshots<br />    -->

						<input type="submit" value="Print Polaroids" name="print_all_images" />
						<input type="submit" value="Download PDF Polaroids" name="pdf_all_images" />
					</center>
				</form>


				<?php }else if($subview=="print-images"){  //show print options
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
				<!-- display options-->

				<form action="" method="post" target="_blank">
					<div class="print_options">
						<span class="allimages_text">Select Print Format</span><br /><br />
						<?php echo $selected;?>
						<input type="hidden" name="print_type" value="<?php echo $subview;?>" />
					</div>       

					<div id="polaroids" class="eight column">
						<div class="six column">
							<input type="radio" value="1" name="print_option" checked="checked" /><h3>Print Large Photos</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .column -->

						<div class="six column">
							<input type="radio" value="3" name="print_option" /><h3>Print Medium Size Photos</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-with-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .column -->


						<div class="six column">
							<input type="radio" value="1-1" name="print_option" /><h3>Print Large Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-large-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .column -->

						<div class="six column">
							<input type="radio" value="3-1" name="print_option" /><h3>Print Medium Size Photos Without Model Info</h3>
							<div class="polaroid">
								<img src="/wp-content/plugins/rb-agency/theme/custom-layout6/images/polariod-medium-photo-without-model-info.png" alt="" />
							</div><!-- polariod -->
						</div><!-- .six .column -->

					</div><!-- polariod -->

					<center>

					<input type="submit" value="Print Pictures" name="print_all_images" />&nbsp;
					<input type="submit" value="Download PDF" name="pdf_all_images" />
					</center>
				</form>

				<?php }else{   //  eight column ?> 
				<div class="portfolio-slide" style="padding: 15px; background-color: #fff; box-sizing: border-box; -moz-box-sizing: border-box;">
				<div class="portfolio-gallery">
					<div class="ad-image-wrapper">
					</div>
					<div class="ad-controls">
					</div>
						<div class="ad-nav">
							<div class="ad-thumbs">
								<ul class="ad-thumb-list">
									<?php  
									// images
									$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY  ProfileMediaPrimary DESC ";
									$resultsImg = mysql_query($queryImg);
									$countImg = mysql_num_rows($resultsImg);
									while ($dataImg = mysql_fetch_array($resultsImg)) {
									?>
									<li>
									<a href="<?php echo  rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'];?>" title=""> 
									<?php echo "<img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt='' style='width:106px; height:130px;' />\n";?></a>
									</li>
									<?php } //$i++; endwhile; ?>
								</ul>
							</div>
						</div>
					</div>
				</div><!-- .portfolio-slide -->
				<?php }?>
                                
                            
                            
                            
                            
                            
                            
                        </div><!-- slider_place_holder -->
                        
                        
                        
                        
                        
                        

	<div class="cb"></div>
</div><!-- #post-## -->
</div><!-- .portfolio -->


<div id="tabstuff" style="background-color: #363636; color: #fff; padding: 15px; margin-bottom: 2em; box-sizing: border-box; -moz-box-sizing: border-box;">
    
    
    

    
    
    
    
    <div id="tabs_handel">
    <ul>
        <li><a href="#empty_stuff" style="padding: 0; line-height: 0; margin: 0;"></a></li>
        <li class="has-border"><a href="#experience_stuff"><?php _e('Experience'); ?></a></li>
        <li><a href="#video_stuff"><?php _e('Videos'); ?></a></li>
    </ul>
    
    <div id="empty_stuff"><p>&#32;</p></div>
        
    <div id="experience_stuff">
                <p><?php echo getExperience($ProfileID); ?></p>
    </div>
    
    <div id="video_stuff">
                <?php
                //Video Slate
                $resultsMedia = mysql_query("SELECT * FROM " . table_agency_profile_media . " media WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType = \"Video Slate\"");
                $countMedia = mysql_num_rows($resultsMedia);
                if ($countMedia > 0) {
                    while ($dataMedia = mysql_fetch_array($resultsMedia)) {
                        $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
                        echo "<div class=\"video slate four column\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/" . $profileVideoEmbed . "?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/" . $profileVideoEmbed . "?fs=1&amp;hl=en_US\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
                    }
                }

                //Video Monologue
                $resultsMedia = mysql_query("SELECT * FROM " . table_agency_profile_media . " media WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType = \"Video Monologue\"");
                $countMedia = mysql_num_rows($resultsMedia);
                if ($countMedia > 0) {
                    while ($dataMedia = mysql_fetch_array($resultsMedia)) {
                        $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
                        echo "<div class=\"video monologue four column\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/" . $profileVideoEmbed . "?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/" . $profileVideoEmbed . "?fs=1&amp;hl=en_US\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
                    }
                }

                //Demo Reel
                $resultsMedia = mysql_query("SELECT * FROM " . table_agency_profile_media . " media WHERE ProfileID =  \"" . $ProfileID . "\" AND ProfileMediaType = \"Demo Reel\"");
                $countMedia = mysql_num_rows($resultsMedia);
                if ($countMedia > 0) {
                    while ($dataMedia = mysql_fetch_array($resultsMedia)) {
                        $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
                        echo "<div class=\"video demoreel four column\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/" . $profileVideoEmbed . "?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/" . $profileVideoEmbed . "?fs=1&amp;hl=en_US\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
                    }
                }
                ?>
    </div>
    </div><!-- tabs_handel -->
    
    
</div><!-- #tabstuff -->

<!--<div id="profile-results">
    <div class="rbprofile-list">
        <?php
            //echo rb_agency_get_miscellaneousLinks($ProfileID);
        ?>
    </div>
</div>-->
<?php

deregister_scripts();

wp_enqueue_style('jQuery_UI_stylesheet', get_template_directory_uri() . '/inc/jquery-ui-1.9.2.custom.css', false, '1.9.2', 'all');
//wp_enqueue_style( 'jQuery_UI_stylesheet');

wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/js/jquery-ui-1.9.2.custom.min.js', array('jquery'), '1.0', all);
//wp_enqueue_script('jquery-ui');
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        jQuery('#tabs_handel').tabs({ active: 0 });
        jQuery('.ad-image-wrapper').appendTo('#slider_new_place .portfolio-slide .portfolio-gallery');
        jQuery('ul.ui-tabs-nav').appendTo('#profile-links .ui-tabs');
    });
</script>