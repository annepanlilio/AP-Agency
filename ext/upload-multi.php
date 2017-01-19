<?php

add_action('admin_print_scripts', 'rb_agency_profilesphoto_script');
	function rb_agency_profilesphoto_script() {
		if ( (isset($_GET['page']) && $_GET['page'] == 'rb_agency_profiles') && (isset($_GET['action']) && $_GET['action'] == 'editRecord') ) {
			wp_admin_css('thickbox');
			add_thickbox();

			wp_deregister_script('jquery');
			wp_register_script( 'jquery-new', RBAGENCY_PLUGIN_URL . 'ext/uploadify/jquery.min.js','', '1.7.2');
			wp_register_script( 'jquery-uploadify', RBAGENCY_PLUGIN_URL.'ext/uploadify/jquery.uploadify.min.js');
			wp_register_style( 'uploadify', RBAGENCY_PLUGIN_URL.'ext/uploadify/uploadify.css');

			wp_print_styles('uploadify');

			wp_print_scripts('jquery-new');
			wp_print_scripts('jquery-uploadify');
		}
	}


add_action('admin_footer', 'rb_agency_profilesphoto_admin_foot');
	function rb_agency_profilesphoto_admin_foot(){
		if ( (isset($_GET['page']) && $_GET['page'] == 'rb_agency_profiles') && (isset($_GET['action']) && $_GET['action'] == 'editRecord') ) {
			echo '
			<script>

			</script>';
		}
	}


add_action('wp_ajax_profilesphoto_save','profilesphoto_save');
add_action('wp_ajax_nopriv_profilesphoto_save','profilesphoto_save');
function profilesphoto_save(){

	global $wpdb,$upload_dir;
	/* ;

	$sport = '_temp';
	$root_path = $upload_dir['basedir'];

	$image = wp_get_image_editor($root_path .'/'.$img);
	if ( ! is_wp_error($image)){
		//$return_filaneme = teamphoto::savephoto($root_path .'/'.$img,$sport,'','_thumb-');
		echo $return_filaneme;
	}else{
		echo 'error'.$root_path;
	}
	 */
	$img = trim($_POST['imgpath']);
	$ProfileID = trim($_POST['ProfileID']);
	$uploadMediaType = trim($_POST['ProfileMediaType']);


	$results = "SELECT * FROM " . table_agency_profile_media . " WHERE ProfileID='%d' AND (ProfileMediaType = 'Image' OR ProfileMediaType = 'dvd' OR ProfileMediaType = 'magazine' )";
	$results = $wpdb->get_results($wpdb->prepare($results, $ProfileID),ARRAY_A);
	if ($wpdb->num_rows > 0) {
		$pi = $wpdb->num_rows +1;
	} else {
		$pi = 1;
	}

	flush();
	$safeProfileMediaFilename = basename($img);

	$results = $wpdb->query("INSERT INTO " .
		table_agency_profile_media . " (ProfileID, ProfileMediaType, ProfileMediaTitle, ProfileMediaURL, ProfileMediaOrder)
		VALUES ('" . $ProfileID . "','" . $uploadMediaType . "','" . $safeProfileMediaFilename . "','" . $safeProfileMediaFilename . "','" . $pi . "')");

	$ProfileMediaID = $wpdb->insert_id;

	$dataImg =array();
	$toggleClass = "";
	$isChecked = "";
	$isCheckedText = " Set Primary";
	$toDelete = "<a href=\"javascript:confirmDelete('" . $ProfileMediaID . "','" . $uploadMediaType . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
	$massDelete = '<input type="checkbox" name="massgaldel" value="' . $ProfileMediaID . '"> Select';

	echo "<div class=\"item gallery-item".$toggleClass."\">\n";

	echo $toDelete;

	$params = array(
			'width' => 100,
			'height' => 150,
		);
	//$profile_image_src = bfi_thumb( RBAGENCY_UPLOADREL . $img, $params );

	$profile_image_src =  RBAGENCY_UPLOADREL . $img;

	echo "  <div class=\"photo\"><img src=\"" .site_url()."/". $profile_image_src ."\" width=\"100\" /></div>\n";
	echo "		<div class=\"item-order\" style='display:none;'>Order: <input type=\"hidden\" name=\"ProfileMediaOrder_" . $ProfileMediaID . "\" style=\"width: 25px\" value=\"" . $pi . "\" /></div>";
	echo "  	<div class=\"make-primary\"><input type=\"radio\" name=\"ProfileMediaPrimary\" value=\"" . $ProfileMediaID . "\" " . $isChecked . " /> " . $isCheckedText . "</div>";
	echo "		<div>".$massDelete."</div>";
	echo "  </div>\n";

	exit;
}


////  ajax saved inline edit videos

add_action('wp_ajax_editvideo_inline_save','editvideo_inline_save');
//add_action('wp_ajax_nopriv_editvideo_inline_save','editvideo_inline_save');
function editvideo_inline_save(){

	global $wpdb;
	//print_r($_POST);
	$_id = $_POST['id'];
	$_url = $_POST['url'];
	$_medtype = $_POST['medtype'];
	$_title = addslashes ($_POST['title'].'<br>'.$_POST['caption']);

	$update = $wpdb->query("UPDATE " . table_agency_profile_media . " SET
		ProfileMediaURL='" . $_url . "',
		ProfileMediaTitle='" . $_title . "',
		ProfileMediaType='" . $_medtype . "'
		WHERE ProfileMediaID='".$_id."'"
		);
//echo $wpdb->last_error;

// echo $_medtype;
//echo $_title;
//return the thumbnail
 echo rb_agency_get_videothumbnail($_url, $_medtype);
	exit;
}




add_action('wp_ajax_get_profileInfo','get_profileInfo');
add_action('wp_ajax_nopriv_get_profileInfo','get_profileInfo');
function get_profileInfo(){

	global $wpdb;
	$ProfileID = $_POST['id'];
# rb_agency_option_galleryorder
 $rb_agency_options_arr = get_option('rb_agency_options');
 $order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
 $ModelProfileQuery = "SELECT
					profile.ProfileID,
					profile.ProfileID as pID,
					profile.ProfileGallery,
					profile.ProfileContactNameFirst,
					profile.ProfileContactNameLast,
					profile.ProfileContactDisplay,
										profile.ProfileGender,
					profile.ProfileDateBirth,
					profile.ProfileDateCreated,
					profile.ProfileLocationState,
					cmux.*,
					cust.*
				FROM ". table_agency_profile ." profile
					LEFT JOIN
					".table_agency_customfield_mux." cmux
					ON
					profile.ProfileID = cmux.ProfileID
					LEFT JOIN 
					".table_agency_customfields." cust
					ON cust.ProfileCustomID = cmux.ProfileCustomID
								WHERE profile.ProfileID = ".$ProfileID."
				GROUP BY profile.ProfileID";
$resultsModelList = $wpdb->get_row($ModelProfileQuery,ARRAY_A);
$ProfileGender = $resultsModelList['ProfileGender'];
//print_r($resultsModelList);
$nextprevProfileQuery = "SELECT NextProfileID,PrevProfileID FROM ". table_agency_next_prev_profile ." WHERE ProfileID =".$ProfileID." ORDER BY TempID DESC LIMIT 0,1";
$resultsNextPrevModelID = $wpdb->get_row($nextprevProfileQuery,ARRAY_A);
//echo 'next'.$resultsNextPrevModelID['NextProfileID'];
//echo 'prev'.$resultsNextPrevModelID['PrevProfileID'];
 $NextModelProfileQuery = "SELECT profile.ProfileGallery,profile.ProfileContactDisplay FROM ". table_agency_profile ." profile WHERE profile.ProfileID = ".$resultsNextPrevModelID['NextProfileID']."";
 $NextModelProfileName = $wpdb->get_row($NextModelProfileQuery,ARRAY_A);

$PrevModelProfileQuery = "SELECT profile.ProfileGallery,profile.ProfileContactDisplay FROM ". table_agency_profile ." profile WHERE profile.ProfileID = ".$resultsNextPrevModelID['PrevProfileID']."";
$PrevModelProfileName = $wpdb->get_row($PrevModelProfileQuery,ARRAY_A);


$ProfileContactDisplay = $resultsModelList['ProfileContactDisplay'];
$ProfileGallery = $resultsModelList['ProfileGallery'];

$ProfileContactNameFirst = $resultsModelList['ProfileContactNameFirst'];
$ProfileContactNameLast = $resultsModelList['ProfileContactNameLast'];
$ProfileDateBirth = $resultsModelList['ProfileDateBirth'];

?>
<div class="slider-container">
	<div class="col-md-6">
		<div class="primary-photo">
			<?
			$queryImg = "SELECT ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"%s\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
			$primaryPhoto =  $wpdb->get_var($wpdb->prepare($queryImg, $ProfileID));

			$primarySize = array(300,420);//width - height
			if(!empty($primaryPhoto)){
				echo '<a href="'.RBAGENCY_UPLOADREL. $ProfileGallery.'/'. $primaryPhoto.'" rel="lightbox[rbagency]"><img src="'.RBAGENCY_UPLOADREL. $ProfileGallery.'/'. $primaryPhoto.'"></a>';
				//echo "<img src=\"". RBAGENCY_PLUGIN_URL."ext/timthumb.php?src=". RBAGENCY_PLUGIN_URL ."assets/demo-data/Placeholder.jpg&w=".$primarySize[0]."&h=".$primarySize[1]."&a=t&zc=1\" alt=\"". stripslashes($ProfileContactDisplay) ."\">";
			}else{
				echo "<img src=\"". RBAGENCY_PLUGIN_URL."ext/timthumb.php?src=". RBAGENCY_PLUGIN_URL ."assets/demo-data/Placeholder.jpg&w=".$primarySize[0]."&h=".$primarySize[1]."&a=t&zc=1\" alt=\"". stripslashes($ProfileContactDisplay) ."\">";
			}
			//RBAGENCY_UPLOADREL . $resultsModelList['ProfileGallery']
			?>
		</div><!-- .primary-photo -->
	</div><!-- .col-md-6 -->
	<a href="#" class="close-btn"><img src="<?php echo RBAGENCY_PLUGIN_URL;?>assets/img/remove.png"/></a>
	<div class="col-md-6">
	<div class="model-info">
		<h2>
			<?php echo $ProfileContactNameFirst.' '.$ProfileContactNameLast; ?>
			<?php // echo $ProfileContactDisplay;?>
		</h2>
		<ul class="model-details">			
			<?php 
			$rbAgencyGetAge = rb_agency_get_age($ProfileDateBirth);
			if(!empty($rbAgencyGetAge)){  ?>
			<li><span><?php echo __('Age'); ?>: </span>&nbsp;<?php echo rb_agency_get_age($ProfileDateBirth); ?></li>
			<?php } ?>

			<?php
			#Get custom fields
			$sql = "SELECT cmux.* , cust.* FROM ".table_agency_customfields." cust INNER JOIN ".table_agency_customfield_mux." cmux ON cmux.ProfileCustomID = cust.ProfileCustomID WHERE cmux.ProfileID = ".$resultsModelList["ProfileID"];
			$customFields = $wpdb->get_results($sql,ARRAY_A);
			foreach($customFields as $customfield){
				$customfield_val = !empty($customfield["ProfileCustomDateValue"]) && $customfield["ProfileCustomDateValue"] != '0000-00-00' ? $customfield["ProfileCustomDateValue"] : $customfield["ProfileCustomValue"];

				if(!empty($customfield_val)){
					if(strpos($customfield_val, ",")){
						// $cf_val = explode(", ", $customfield_val);
						// $customfield_val = implode(", ", $cf_val);
						$customfield_val=  str_replace(",", ", ", $customfield_val);
					}
					if($customfield["ProfileCustomType"]==11 ){
						echo "<li><span>". $customfield["ProfileCustomTitle"]. ":</span>&nbsp;<a href=\"".$customfield_val."\" target=\"_blank\">".__('Click Here')."</a></li>";
					}else{
						echo "<li><span>". $customfield["ProfileCustomTitle"]. ":</span>&nbsp;".$customfield_val."</li>";
					}
					
				}

				
			}

			?>
			<li><a href="<?php echo RBAGENCY_PROFILEDIR . $ProfileGallery; ?>" class="view-profile"><?php echo __('View Profile'); ?></a></li>

			<?php
			$arr_favorites = array();
			$arr_castingcart = array();
			$favIcon = "<i class=\"fa fa-heart\"></i>";
			$cartIcon = "<i class=\"fa fa-star\"></i>";
			# Favorites and casting cart button
			$favorites_btn = "<div id=\"profile-favorite\" class=\"favorite\"><a href=\"javascript:;\" title=\""
							.(in_array($resultsModelList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")
							."\" attr-id=\"".$resultsModelList["ProfileID"]."\" class=\""
							.(in_array($resultsModelList["ProfileID"], $arr_favorites)?"active":"inactive")
							." favorite\">$favIcon <span>"
							.(in_array($resultsModelList["ProfileID"], $arr_favorites)?"Remove from Favorites":"Add to Favorites")
							."</span></a></div>";
			$castingcart_btn =  "<div  id=\"profile-casting\"  class=\"casting\"><a href=\"javascript:;\" title=\""
								.(in_array($resultsModelList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")
								."\"  attr-id=\"".$resultsModelList["ProfileID"]."\"  class=\""
								.(in_array($resultsModelList["ProfileID"], $arr_castingcart)?"active":"inactive")
								." castingcart\">$cartIcon <span>"
								.(in_array($resultsModelList["ProfileID"], $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")
								."</span></a></div>";
			?>
		</ul>

		<footer><?php echo $favorites_btn; ?> <?php echo $castingcart_btn; ?></footer>
		
		<br class="clear"/>
		<script type="text/javascript" >
				var layout_favorite = "02";
				jQuery(document).ready(function () {
					jQuery("a.favorite").click(function(){
						var Obj = jQuery(this);
						jQuery.ajax({
							type: 'POST',
							url: '<?php echo admin_url("admin-ajax.php"); ?>',
							data: {
								action: 'rb_agency_save_favorite',
								talentID: Obj.attr("attr-id")
							},
							success: function (results) {
															if(Obj.hasClass("inactive")){
									Obj.attr("title","Remove from Favorites");
									Obj.removeClass("inactive").addClass("active");
									Obj.find("span").text("Remove from Favorites");
								} else if(Obj.hasClass("active")){
									Obj.attr("title","Add to Favorites");
									Obj.removeClass("active").addClass("inactive");
									Obj.find("span").text("Add to Favorites");
								}
														},
							error: function(e){
								console.log(e);
							}
						});
					});
					jQuery(".newfavorite a:first, .newfavorite a").click(function () {
						var Obj = jQuery(this);
						jQuery.ajax({
							type: 'POST',
							url: '<?php echo admin_url("admin-ajax.php"); ?>',
							data: {
								action: 'rb_agency_save_favorite',
								talentID: jQuery(this).attr("id")
							},
							success: function (results) {

								if (results == 'error') {
									Obj.fadeOut().empty().html("Error in query. Try again").fadeIn();
								} else if (results == -1) {
									Obj.fadeOut().empty().html("<span style=\"color:red;font-size:11px;\">You're not signed in.</span><a href=\"<?php echo site_url(); ?>/profile-member/\">Sign In</a>.").fadeIn();
									setTimeout(function () {
										if (Obj.attr("class") == "save_favorite") {
											Obj.fadeOut().empty().html("").fadeIn();
											Obj.attr('title', 'Save to Favorites');
											Obj.find("span").text('Add to Favorites');
										} else {
											Obj.fadeOut().empty().html("Favorited").fadeIn();
											//Obj.attr('title', 'Remove from Favorites');
											Obj.find("span").text('Remove from Favorites');

										}
									}, 2000);
								} else {
																			if(layout_favorite == "00"){
											if (Obj.hasClass("save_favorite") || (Obj.hasClass("favorited") && jQuery.trim(results)=="inserted") ) {
												Obj.removeClass("save_favorite");
												Obj.addClass("favorited");
												Obj.attr('title', 'Remove from Favorites');
												Obj.find("span").text('Remove from Favorites');
											} else {
												Obj.removeClass("favorited");
												Obj.addClass("save_favorite");
												Obj.attr('title', 'Add to Favorites');
												Obj.find("span").text('Add to Favorites');
											}
										} else {
											if (Obj.attr("class") == "save_favorite") {
												Obj.empty().fadeOut().empty().html("").fadeIn();
												Obj.attr("class", "favorited");
												Obj.attr('title', 'Remove from Favorites');
												Obj.text('REMOVE FROM FAVORITES')
											} else {
												Obj.empty().fadeOut().empty().html("").fadeIn();
												Obj.attr('title', 'Save to Favorites');
												jQuery(this).find("a[class=view_all_favorite]").remove();
												Obj.attr("class", "save_favorite");
												Obj.text('SAVE TO FAVORITES');
											}
										}
																}
							}
						})
					});
				});
				</script>

				<script type="text/javascript" >
				jQuery("a.castingcart").click(function(){
					var Obj = jQuery(this);
					jQuery.ajax({
						type: 'POST',
						url: '<?php echo admin_url("admin-ajax.php"); ?>',
						data: {
							action: 'rb_agency_save_castingcart',
							'talentID': Obj.attr("attr-id")
						},
						success: function (results) {
															if(Obj.hasClass("inactive")){
									Obj.attr("title","Remove from Casting Cart");
									Obj.removeClass("inactive").addClass("active");
									Obj.find("span").text("Remove from Casting Cart");
								} else if(Obj.hasClass("active")){
									Obj.attr("title","Add to Casting Cart");
									Obj.removeClass("active").addClass("inactive");
									Obj.find("span").text("Add to Casting Cart");
								}
													}
					});
				});
					var layout_casting = "02";
					jQuery(document).ready(function ($) {
						$(".newcastingcart a").click(function () {
						var Obj = $(this);
							jQuery.ajax({
								type: 'POST',
								url: '<?php echo admin_url("admin-ajax.php"); ?>',
								data: {
									action: 'rb_agency_save_castingcart',
									'talentID': Obj.attr("attr-id")
								},
								success: function (results) {
									console.log(results);
									if (results == 'error') {
										Obj.fadeOut().empty().html("Error in query. Try again").fadeIn();
									} else if (results == -1) {
										Obj.fadeOut().empty().html("<span style=\"color:red;font-size:11px;\">You're not signed in.</span><a href=\"<?php echo site_url(); ?>/profile-member/\">Sign In</a>.").fadeIn();
										setTimeout(function () {
											if (Obj.attr("class") == "save_castingcart") {
												Obj.fadeOut().empty().html("").fadeIn();
											} else {
												Obj.fadeOut().empty().html("").fadeIn();
											}
										}, 2000);
									} else {
																					if(layout_casting == "00"){
												if (Obj.hasClass("save_castingcart") || (Obj.hasClass("saved_castingcart") && jQuery.trim(results)=="inserted")) {
													Obj.removeClass("save_castingcart");
													Obj.addClass("saved_castingcart");
													Obj.attr('title', 'Remove from Casting Cart');
													Obj.find("span").text('Remove from Casting Cart');
												} else {
													Obj.removeClass("saved_castingcart");
													Obj.addClass("save_castingcart");
													Obj.attr('title', 'Add to Casting Cart');
													Obj.find("span").text('Add to Casting Cart');
												}
											} else {
												if (Obj.attr("class") == "save_castingcart") {
													Obj.empty().fadeOut().html("").fadeIn();
													Obj.attr("class", "saved_castingcart");
													Obj.attr('title', 'Remove from Casting Cart');
													//Obj.text("VIEW CASTING CART");
													Obj.find("span").text("href","<?php echo site_url(); ?>/profile-casting/");
												} else {
													Obj.empty().fadeOut().html("").fadeIn();
													Obj.attr("class", "save_castingcart");
													Obj.attr('title', 'Add to Casting Cart');
													//Obj.text("ADD TO CASTING CART");

													$(this).find("a[class=view_all_castingcart]").remove();
												}
											}
																			}
								}
							})
						});
				});
			</script>


		<!--<ul class="model-icons">
			<li>details: <span>test</span></li>
			<li>details: <span>test</span></li>
			<li>details: <span>test</span></li>
			<li>details: <span>test</span></li>
			<li>details: <span>test</span></li>
		</ul>-->
		<br class="clear"/>



		</div><!-- .model-info -->
	</div><!-- .col-md-6 -->

</div>

<script>
	jQuery(".close-btn").click(function(){
		jQuery(".info-panel").slideUp();
		return false;
	});
</script>










<?

exit;

?>



<style type="text/css">
.item img {
	/*max-width: 134px !important;*/
 }
</style>


<div id="<?php echo $ProfileID;?>_div" class="show_models_details_wrapper_margin">
  <div class="clearfix show_models_details_wrapper_inner ">
	<?php if($resultsNextPrevModelID['PrevProfileID']!=0){?>
	<div onclick="get_prev_model_details(<?php echo $resultsNextPrevModelID['PrevProfileID'];?>, this, <?php echo $_REQUEST['index'];?>, '<?php echo $PrevModelProfileName['ProfileGallery'];?>');" class="right_left_big left_model_arrow"> <img src="<?php echo get_bloginfo('template_directory');?>/images/back.png"> </div>
	<?php }?>

	</div>
	<div class="model_details_inner_half padding_l_1_percent">
	  <div onclick="close_model_details_div(this);" class="right_left_big close_model_details_div"> <img src="<?php echo get_bloginfo('template_directory');?>/images/close.png"> </div>
	  <div class="model_details_inner_half_text clearfix">
		<h2 class="model_details_inner_half_text_h2"><?php echo $resultsModelList['ProfileContactDisplay'];?> </h2>
	   <div class="model_details_inner_half_text_other_info" style="margin-bottom:0px!important;">
		   <?php
		   if (!empty($ProfileGender)) {
				$fetchGenderData=  $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='%s' ",$ProfileGender),ARRAY_A);
				$count  = $wpdb->num_rows;
				if($count > 0){
				echo "<div class=\"model_details_inner_half_text_other_info_indv rb_gender\" id=\"rb_gender\"><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span>:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</div>\n";
				}
			}
		   ?>
		  <?php echo rb_agency_getProfileCustomFieldsInAjaxPage($ProfileID, $ProfileGender);?>
		</div>



		<!--<div class="model_details_inner_half_text_other_info"> <a title="Add to Lightbox" alt="Add to Lightbox" class="moddetailsrowlightbox tooltip1" cat="38" id="7435"> <img src="http://www.bmamodels.com/images/lightbox.png" class="lighbox_details hover_details"> </a> <a href="http://www.bmamodels.com/viewshowreels.php?showreel=http://www.bmamodels.com/images/cat_images/videos/v7435hi.m4v&amp;photo=http://www.bmamodels.com/images/cat_images/female_mainboard/l/l7435a.jpg" alt="View Showreel" title="View Showreel" target="_blank" data-type="" class="tooltip1"> <img src="http://www.bmamodels.com/images/showreel.png" class="hover_details"> </a> <a href="http://www.bmamodels.com/images/cat_images/idents/i7435.wmv" alt="View Ident" title="View Ident" target="_blank" class="tooltip1"> <img src="http://www.bmamodels.com/images/ident.png" class="hover_details"> </a> <a href="http://www.bmamodels.com/model-polaroids?modno=7435" alt="Polaroids" title="Polaroids" target="_blank" class="tooltip1"> <img src="http://www.bmamodels.com/images/polaroid.png" class="hover_details"> </a> <a href="http://mars.bmamodels.com/model_cv_open.php?id=7435" alt="CV" title="CV" target="_blank" class="tooltip1"> <img src="http://www.bmamodels.com/images/cv.png" class="hover_details"> </a> <a href="http://www.bmamodels.com/model_card.php?modno=7435&amp;cat=38" alt="Print Model Card" title="Print Model Card" target="_blank" class="tooltip1"> <img src="http://www.bmamodels.com/images/print.png" class="hover_details"> </a> </div>-->
	 <!-- ADDTHIS BUTTON BEGIN -->
		<div class="model_details_inner_half_text_other_info">

		<div id="media-files">
		  <?php
		  $castingcart_results = $wpdb->get_results("SELECT CastingCartTalentID FROM ".table_agency_castingcart." WHERE CastingCartProfileID = '".rb_agency_get_current_userid()."'  AND CastingJobID <= 0");
		  $arr_castingcart = array();
		  foreach ($castingcart_results as $key) {
			array_push($arr_castingcart, $key->CastingCartTalentID);
		  }

		   echo "<style>";
		  echo ".heart-icon{ margin-right:10px;padding: 15px 5px 15px 15px;font-size:35px;}";
		  echo "</style>";



		  if(!empty($rb_agency_options_arr['rb_agency_option_carticonurl'])){
				$icon_heart = "<img src=\"{$rb_agency_options_arr['rb_agency_option_carticonurl']}\" style=\"border:0;\">";
			}else{
				$icon_heart = in_array($ProfileID,$arr_castingcart) ? "<i class='heart-icon fa fa-heart' style='color:#58D3F7;' ></i>" : "<i class='heart-icon fa fa-heart' style='color:#848484;' ></i>";
			}



		  $icon_heart_button = "<div class=\"rb_profile_tool2\"><a href='javascript:;' title=\"".(in_array($ProfileID, $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")."\" attr-id=\"".$ProfileID."\" class=\"".(in_array($ProfileID, $arr_castingcart)?"active":"inactive")." castingcart2\"  style='float:right;'><span class=\"heart-icon2\">".$icon_heart."</span></a></div>";


		  //if not logged check it from session
		  if(!is_user_logged_in()){
		  if(!is_array($_SESSION['cart']))$_SESSION['cart'] = array();


		  if(!empty($rb_agency_options_arr['rb_agency_option_carticonurl'])){
				$icon_heart = "<img src=\"{$rb_agency_options_arr['rb_agency_option_carticonurl']}\" style=\"border:0;\">";
			}else{
				$icon_heart = in_array($ProfileID,$_SESSION['cart']) ? "<i class='heart-icon fa fa-heart' style='color:#58D3F7;' ></i>" : "<i class='heart-icon fa fa-heart' style='color:#848484;' ></i>";
			}
			  $icon_heart_button = "<div class=\"rb_profile_tool2\"><a href='javascript:;' title=\"".(in_array($ProfileID,$_SESSION['cart'])?"Remove from Casting Cart":"Add to Casting Cart")."\" attr-id=\"".$ProfileID."\" class=\"".(in_array($ProfileID,$_SESSION['cart'])?"active":"inactive")." castingcart2\"  style='float:right;'><span class=\"heart-icon2\">".$icon_heart."</span></a></div>";
		  }

		  echo "<ul style='list-style-type: none;'>";

		  echo "<li style='float:left;'>$icon_heart_button</li>";

		  //Polaroids
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Polaroid");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countImg  = $wpdb->num_rows;
		  if($countImg > 0){
			echo "<li style='float:left;'><a href=\"".get_bloginfo('url')."/model-polaroids/profile/".$resultsModelList['ProfileGallery']."/".$resultsModelList['ProfileID']."\" class=\"profile-link polaroid\" title=\"View Polaroid\" target=\"_blank\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/polaroid-icon.png' /></a></li>";
		  }

		  //Resume
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  echo "<li style='float:left;'><a ".rb_get_profilemedia_link_opentype($resultsModelList['ProfileGallery'] ."/". $dataMedia['ProfileMediaURL'],true) ." class=\"profile-link\" title=\"View Resume\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/resume-icon.png' /></a></li>";
			}
		  }

		  // Comp Card
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CompCard");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  echo "<li style='float:left;'><a ".rb_get_profilemedia_link_opentype($resultsModelList['ProfileGallery'] ."/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\" title=\"View Comp Card\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/comp-card-icon.png' />  </a></li>";
			}
		  }

		  // Headshots
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Headshot");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  echo "<li style='float:left;'><a ".rb_get_profilemedia_link_opentype($resultsModelList['ProfileGallery'] ."/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\" title=\"View Headshots\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/headshot-icon.png' /></a></li>";
			}
		  }

		  //Voice Demo
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"VoiceDemo");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  echo "<li style='float:left;'><a ".rb_get_profilemedia_link_opentype($resultsModelList['ProfileGallery'] ."/". $dataMedia['ProfileMediaURL']) ."  class=\"profile-link\" title=\"Voice Demo\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/voice-demo-icon.png' /></a></li>";
			}
		  }

		  //Video Slate
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Slate");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
			  echo "<li style='float:left;'><a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"Video Slate\" target=\"_blank\" class=\"profile-link slate\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/video-icon.png' /></a></li>";
			}
		  }

		  //Video Monologue
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Video Monologue");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  echo "<li style='float:left;'><a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"Video Monologue\" target=\"_blank\" class=\"profile-link monologue\" ><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/video-icon.png' /></a></li>";
			}
		  }

		  //Demo Reel
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			foreach($resultsImg as $dataMedia ){
			  echo "<li style='float:left;'><a href=\"". $dataMedia['ProfileMediaURL'] ."\" title=\"Demo Reel\" target=\"_blank\" class=\"profile-link demoreel\" ><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/video-icon.png' /></a></li>";
			}
		  }

		   //Card Photos
		  $queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"CardPhotos");
		  $resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
		  $countMedia = $wpdb->num_rows;
		  if ($countMedia > 0) {
			//foreach($resultsImg as $dataMedia ){
			  //echo "<li style='float:left;'><a href=\"". get_bloginfo('url')."/profile/".$resultsModelList['ProfileGallery'] ."/cardphotos/\" title=\"Card Photos\" target=\"_blank\" class=\"profile-link CardPhotos\" ><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/printer-icon.png' /></a></li>";
			  echo "<li style='float:left;'><a ".rb_get_profilemedia_link_opentype(get_bloginfo('url')."/profile/".$resultsModelList['ProfileGallery'] ."/cardphotos/",false,true) ." class=\"cardphotos\" title=\"View CardPhotos\"><img src='".get_bloginfo("url")."/wp-content/plugins/rb-agency/view/layout/02/icons/printer-icon.png' /></a></li>";

			//}
		  }


		  echo "</ul>";




		// END MEDIA FILES

		  ?>
		</div>



	  <div id="bottomimages">
		<div class="bottomimages_absolute">
		 <div style="display: block;" title="Previous Image" class="prev_model_image"> <img src="<?php echo get_bloginfo('template_directory');?>/images/back-small.png"> </div>
		  <div class="model_downs_images">
			<div id="owl-demo" style="opacity: 1; display: block;" class="owl-carousel owl-theme">
				<?php
				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
				$resultsImg=  $wpdb->get_results($queryImg,ARRAY_A);
				$countImg  = $wpdb->num_rows;
				$checker = 1;
				foreach($resultsImg as $dataImg ){
					if ($countImg > 1) {
				   // $bigimageurl = get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADREL . $resultsModelList['ProfileGallery'] ."/". $dataImg['ProfileMediaURL'] ."&w=416&zc=2";
					 $smallimageurl = get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADREL . $resultsModelList['ProfileGallery'] ."/". $dataImg['ProfileMediaURL'] ."&w=109&h=150";
					 $bigimageurl = RBAGENCY_UPLOADREL . $resultsModelList['ProfileGallery'] ."/". $dataImg['ProfileMediaURL'];
					//$smallimageurl = RBAGENCY_UPLOADREL . $resultsModelList['ProfileGallery'] ."/". $dataImg['ProfileMediaURL'];

					$Imgsize = getimagesize($bigimageurl);

					$Imgwidth = $Imgsize[0];
					$Imgheight = $Imgsize[1];
					//$Imgaspect = $Imgheight / $Imgwidth;
					if( $Imgwidth > $Imgheight){
					$orientation = "l";
					}else{
					$orientation = "p";
					}
			   ?>

			   <div class="item" onclick="open_image('<?php echo $bigimageurl;?>', this);">
				   <img src="<?php echo $smallimageurl;?>" checker="<?php echo $checker;?>" type="<?php echo $orientation;?>" width_int="<?php echo $Imgwidth;?>" height_int="<?php echo $Imgheight;?>" large_img="<?php echo $bigimageurl;?>"/>
			   </div>

			<?php } $checker++; }?>
			</div>
		  </div>
		  <div style="display: block;" title="Next Image" class="next_model_image"> <img src="<?php echo get_bloginfo('template_directory');?>/images/forward-small.png"> </div>
		</div>
	  </div>
	</div>
	<?php if($resultsNextPrevModelID['NextProfileID']!=0){?>
   <div onclick="get_next_model_details(<?php echo $resultsNextPrevModelID['NextProfileID'];?>, this, <?php echo $_REQUEST['index'];?>, '<?php echo $NextModelProfileName['ProfileGallery'];?>');" class="right_left_big right_model_arrow"> <img src="<?php echo get_bloginfo('template_directory');?>/images/forward.png"> </div>
	<?php }?>
  </div>
</div>
<style>
.rb_profile_tool2 .active .heart-icon2 img,.rb_profile_tool2 .inactive .heart-icon2:hover img,.rb_profile_tool2 .inactive .heart-icon2 img:hover{
	filter: none;
  -webkit-filter: grayscale(0%);
  -moz-filter: grayscale(0%);
  -o-filter: grayscale(0%);

  -webkit-filter: none;
   -moz-filter: none;
   -ms-filter: none;
}
.rb_profile_tool2 .inactive .heart-icon2 img{
   filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 3.5+ */
   filter: gray; /* IE6-9 */

  -webkit-filter: grayscale(100%);
  -webkit-transition: .5s ease-in-out;
  -moz-filter: grayscale(100%);
  -moz-transition: .5s ease-in-out;
  -o-filter: grayscale(100%);
  -o-transition: .5s ease-in-out;
}
</style>
<script type="text/javascript" >
//FOR AJAX PROFILE VIEW
jQuery(".rb_profile_tool2 a.castingcart2").click(function(){
		  var Obj = jQuery(this);
		  var profID_attr = Obj.attr("attr-id");
		  jQuery.ajax({
			type: 'POST',
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: {
			  action: 'rb_agency_save_castingcart',
			  'talentID': Obj.attr("attr-id")
			},
			success: function (results) {
			  <?php
			  if (get_query_var('type') == "casting"){?>
					  jQuery("#rbprofile-"+Obj.attr("attr-id")).hide("slow",function(){jQuery("#rbprofile-"+Obj.attr("attr-id")).remove();});
							var a = jQuery("#profile-results-info-countrecord .count-display");
							var b = jQuery("#profile-results-info-countrecord .items-display");
							var count = parseInt(a.text());
							var item = parseInt(b.text());
							var c = count - 1;
							a.text(c);
							var i = item - 1;
							b.text(i);
							if(c <=0 && i <= 0){
							  window.location.reload();
							}
			<?php } else { ?>


				if(Obj.hasClass("inactive")){
				  Obj.attr("title","Remove from Casting Cart");
				  Obj.removeClass("inactive").addClass("active");
				  <?php
					if(!empty($rb_agency_options_arr['rb_agency_option_carticonurl'])){
						echo "Obj.find(\"span\").html('<img src=\"{$rb_agency_options_arr['rb_agency_option_carticonurl']}\" style=\"border:0;\">');";
					}else{
						echo "Obj.find(\"span\").html('<i class=\"heart-icon fa fa-heart\" style=\"color:#58D3F7;\"></i>');";
					}
					?>

				  //jQuery(".clicked-addcasting").val(2);
				  //return false;

				   jQuery("div.castingcart_div[attr-id='"+profID_attr+"'] a").removeClass("inactive").addClass("active");
				   jQuery("div.castingcart_div[attr-id='"+profID_attr+"'] a").attr("title","Remove from Casting Cart");
				   jQuery("div.castingcart_div[attr-id='"+profID_attr+"'] a span").html("Remove from Casting Cart");

				}else if(Obj.hasClass("active")){
				  Obj.attr("title","Add to Casting Cart");
				  Obj.removeClass("active").addClass("inactive");
				  <?php
					if(!empty($rb_agency_options_arr['rb_agency_option_carticonurl'])){
						echo  "Obj.find(\"span\").html('<img src=\"{$rb_agency_options_arr['rb_agency_option_carticonurl']}\" style=\"border:0;\">');";
					}else{
						echo "Obj.find(\"span\").html('<i class=\"heart-icon fa fa-heart\" style=\"color:#848484;\"></i>');";
					}
					?>
				  jQuery("div.castingcart_div[attr-id='"+profID_attr+"'] a").removeClass("active").addClass("inactive");
				  jQuery("div.castingcart_div[attr-id='"+profID_attr+"'] a").attr("title","Add to Casting Cart");
				  jQuery("div.castingcart_div[attr-id='"+profID_attr+"'] a span").html("Add to Casting Cart");

				  //return false;
				  //jQuery(".clicked-addcasting").val(2);
				  //$("#"+Obj.attr("attr-id")).attr('disabled','disabled');

				}
			  <?php } ?>
			}
		  });
		});

</script>
<?php
	exit;
}
