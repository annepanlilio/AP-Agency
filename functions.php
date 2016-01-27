<?php
// *************************************************************************************************** //
// Admin Head Section 

	add_action('wp_head', 'rb_agency_casting_head');
		function rb_agency_casting_head(){
			if( !is_admin() ) {
				echo "<link rel=\"stylesheet\" href=\"". RBAGENCY_casting_PLUGIN_URL ."css/style.css\" type=\"text/css\" media=\"screen\" />\n";
				echo "<link rel=\"stylesheet\" href=\"". RBAGENCY_PLUGIN_URL ."assets/css/forms.css\" type=\"text/css\" media=\"screen\" />\n";
			}
		}

	/*
	// Remember to flush_rules() when adding rules
	// Todo: Remove lines below. Causes permalink incompatibility with other plugins such as woocommerce

	/*add_filter('init','rbcasting_flushrules');
		function rbcasting_flushRules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
	}*/

	// Adding a new rule
	add_filter('rewrite_rules_array','rb_agency_casting_rewriteRules');
		function rb_agency_casting_rewriteRules($rules) {
			$newrules = array();
			// Casting Agent
			$newrules['casting-register'] = 'index.php?type=castingregister&rbgroup=casting';
			$newrules['casting-login'] = 'index.php?type=castinglogin&rbgroup=casting';
			$newrules['casting-dashboard'] = 'index.php?type=castingoverview&rbgroup=casting';
			$newrules['casting-manage'] = 'index.php?type=castingmanage&rbgroup=casting';
			$newrules['casting-editjob/(.*)$'] = 'index.php?type=castingeditjob&target=$matches[1]&rbgroup=casting';
			$newrules['casting-postjob'] = 'index.php?type=castingpostjob&rbgroup=casting';
			$newrules['view-applicants/(.*)$'] = 'index.php?type=viewapplicants&target=$matches[1]';
			$newrules['view-applicants'] = 'index.php?type=viewapplicants';
			// User/Profile View
			$newrules['browse-jobs/(.*)$'] = 'index.php?type=browsejobpostings&target=$matches[1]';
			$newrules['browse-jobs'] = 'index.php?type=browsejobpostings';
			$newrules['job-detail/(.*)$'] = 'index.php?type=jobdetail&value=$matches[1]';
			$newrules['job-application/(.*)$'] = 'index.php?type=jobapplication&target=$matches[1]';
			$newrules['profile-favorite'] = 'index.php?type=favorite';
			// Casting Agent
			$newrules['profile-casting/jobs/(.*)/(.*)$'] = 'index.php?type=castingjobs&target=$matches[1]&value=$matches[2]&rbgroup=casting';
			$newrules['profile-casting/(.*)$'] = 'index.php?type=casting&target=$matches[1]&rbgroup=casting';
			$newrules['profile-casting'] = 'index.php?type=casting&rbgroup=casting';
			$newrules['client-view/(.*)$'] = 'index.php?type=profilecastingcart&target=$matches[1]&rbgroup=casting';
			$newrules['email-applicant/(.*)/(.*)$'] = 'index.php?type=emailapplicant&target=$matches[1]&value=$matches[2]&rbgroup=casting';
			$newrules['email-applicant/(.*)$'] = 'index.php?type=emailapplicant&target=$matches[1]&rbgroup=casting';
			$newrules['casting-pending'] = 'index.php?type=casting-pending&rbgroup=casting';
			$newrules['casting-inactive-archive'] = 'index.php?type=casting-inactive-archive&rbgroup=casting';
			return $newrules + $rules;
		}

	// Set Custom Template
	add_filter('template_include', 'rb_agency_casting_template_include', 1, 1); 
		function rb_agency_casting_template_include( $template ) {
			if ( get_query_var( 'type' )) {

				if(get_query_var( 'rbgroup' ) == "casting"){

					rb_agency_group_permission(get_query_var( 'rbgroup' ));

					if (get_query_var( 'type' ) == "castingoverview") {
						return dirname(__FILE__) . '/view/casting-overview.php'; 
					} elseif (get_query_var( 'type' ) == "castingmanage") {
						return dirname(__FILE__) . '/view/casting-manage.php'; 
					} elseif (get_query_var( 'type' ) == "castinglogin") {
						return dirname(__FILE__) . '/view/casting-login.php'; 
					} elseif (get_query_var( 'type' ) == "castingregister") {
						return dirname(__FILE__) . '/view/casting-register.php'; 
					} elseif (get_query_var( 'type' ) == "casting") {
						return dirname(__FILE__) . '/view/profile-viewcasting.php';
					} elseif (get_query_var( 'type' ) == "profilecastingcart") {
						return dirname(__FILE__) . '/view/profile-castingcart.php';
					} elseif (get_query_var( 'type' ) == "castingpostjob") {
						return dirname(__FILE__) . '/view/casting-postjob.php';
					} elseif (get_query_var( 'type' ) == "castingeditjob") {
						return dirname(__FILE__) . '/view/casting-editjob.php';
					} elseif (get_query_var( 'type' ) == "emailapplicant") {
						return dirname(__FILE__) . '/view/casting-emailapplicant.php';
					} elseif (get_query_var( 'type' ) == "casting-pending") {
						return dirname(__FILE__) . '/view/casting-pending.php';
					} elseif (get_query_var( 'type' ) == "casting-inactive-archive") {
						return dirname(__FILE__) . '/view/casting-inactive-archive.php';
					}
				} else {
					if (get_query_var( 'type' ) == "browsejobpostings") {
						return dirname(__FILE__) . '/view/browse-jobpostings.php';
					} elseif (get_query_var( 'type' ) == "jobdetail") {
						return dirname(__FILE__) . '/view/casting-jobdetails.php';
					} elseif (get_query_var( 'type' ) == "jobapplication") {
						return dirname(__FILE__) . '/view/casting-jobapplication.php';
					} elseif (get_query_var( 'type' ) == "viewapplicants") {
						return dirname(__FILE__) . '/view/view-jobapplicants.php';
					} elseif (get_query_var( 'type' ) == "favorite") {
						return dirname(__FILE__) . '/view/profile-favorite.php';
					}
				}

			}
			return $template;
		}

	function get_state_json(){
		global $wpdb;
		$states=array();
		$country=isset($_POST['countryid'])?$_POST['countryid']:$_POST['country'];
		$query_get ="SELECT * FROM ".table_agency_data_state." WHERE CountryID='".$country."'";
		$result_query_get = $wpdb->get_results($query_get);
		echo json_encode($result_query_get);
		die();
	}
	add_action('wp_ajax_get_state_json', 'get_state_json');
	add_action('wp_ajax_nopriv_get_state_json', 'get_state_json');

	/*
	 * rate applicant
	 */

	function rate_applicant(){

		global $wpdb;

		$application_id = $_POST['application_id'];
		$rating = $_POST['clients_rating'];

		$update = "UPDATE " . table_agency_casting_job_application .
				" SET Job_Client_Rating = " . $rating . " WHERE Job_Application_ID = " . $application_id;

		$wpdb->query($update);

		die();
	}
	add_action('wp_ajax_rate_applicant', 'rate_applicant');
	add_action('wp_ajax_rate_applicant', 'rate_applicant');

	/*/
	 * ======================== Get Favorite & Casting Cart Links ===============
	 * @Returns links
	/*/
	function rb_agency_get_miscellaneousLinks($ProfileID = ""){

		global $wpdb;
		rb_agency_checkExecution();

		$disp = "";
		$disp .= "<div class=\"favorite-casting\">";

		if (is_permitted('favorite')) {
			if(!empty($ProfileID)){
				$queryFavorite = $wpdb->get_row("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE ".rb_agency_get_current_userid()." = fav.SavedFavoriteProfileID AND fav.SavedFavoriteTalentID = '".$ProfileID."' ",ARRAY_A);
				$dataFavorite = $queryFavorite; 
				$countFavorite = $wpdb->num_rows;
				if($countFavorite <= 0){
					$disp .= "    <div class=\"favorite\"><a title=\"Save to Favorites\" rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$ProfileID."\"></a></div>\n";
				} else {
					$disp .= "<div class=\"favorite\"><a rel=\"nofollow\" title=\"Remove from Favorites\" href=\"javascript:;\" class=\"favorited\" id=\"".$ProfileID."\"></a></div>\n";
				}
			}
		}

		if (is_permitted('casting')) {
			if(!empty($ProfileID)){
				$queryCastingCart = $wpdb->get_row("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE ".rb_agency_get_current_userid()." = cart.CastingCartProfileID AND cart.CastingCartTalentID = '".$ProfileID."' ",ARRAY_A);
				$dataCastingCart = $queryCastingCart;
				$countCastingCart = $wpdb->num_rows;
				if($countCastingCart <=0){
						$disp .= "<div class=\"castingcart\"><a title=\"Add to Casting Cart\" href=\"javascript:;\" id=\"".$ProfileID."\"  class=\"save_castingcart\"></a></div></li>";
				} else {
						if(get_query_var('type')=="casting"){ //hides profile block when icon is click
							$divHide="onclick=\"javascript:document.getElementById('div$ProfileID').style.display='none';\"";
						}
						$disp .= "<div class=\"castingcart\"><a ".(isset($divHide)?$divHide:"")." href=\"javascript:void(0)\"  id=\"".$ProfileID."\" title=\"Remove from Casting Cart\"  class=\"saved_castingcart\"></a></div>";
				}
			}
		}

		$disp .= "</div><!-- .favorite-casting -->";
		return $disp; 
	}


	/*/
	 * ======================== NEW Get Favorite & Casting Cart Links ===============
	 * @Returns links
	/*/
	function rb_agency_get_new_miscellaneousLinks($ProfileID = ""){

		global $user_ID, $wpdb;

		$rb_agency_options_arr 				= get_option('rb_agency_options');
		$rb_agency_option_profilelist_favorite		= isset($rb_agency_options_arr['rb_agency_option_profilelist_favorite']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_favorite'] : 0;
		$rb_agency_option_profilelist_castingcart 	= isset($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']) ? (int)$rb_agency_options_arr['rb_agency_option_profilelist_castingcart'] : 0;
		rb_agency_checkExecution();
		$castingcart_results = array();
		$favorites_results = array();

		$disp = "";

		if ($rb_agency_option_profilelist_favorite) {
			//Execute query - Favorite Model
			if(!empty($ProfileID)){
				$favorites_results = $wpdb->get_results("SELECT SavedFavoriteTalentID FROM ".table_agency_savedfavorite." WHERE SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'");
			}
		}

		if ($rb_agency_option_profilelist_castingcart) {
			//Execute query - Casting Cart
			if(!empty($ProfileID)){
				$castingcart_results = $wpdb->get_results("SELECT CastingCartTalentID FROM ".table_agency_castingcart." WHERE CastingCartProfileID = '".rb_agency_get_current_userid()."' AND CastingJobID <= 0");
			}
		}

		$arr_castingcart = array();
		foreach ($castingcart_results as $key) {
			array_push($arr_castingcart, $key->CastingCartTalentID);
		}

		$arr_favorites = array();
		foreach ($favorites_results  as $key) {
			array_push($arr_favorites, $key->SavedFavoriteTalentID);
		}

		$displayActions = "";
		$displayActions = "<div id=\"profile-single-view\" class=\"rb_profile_tool\">";
		
		//admin icon settings 
		if(!empty($rb_agency_options_arr['rb_agency_option_carticonurl'])){
			$cartIcon = "<img src=\"{$rb_agency_options_arr['rb_agency_option_carticonurl']}\" style=\"border:0;\">";
		}else{
			$cartIcon = "<i class=\"fa fa-star\"></i>";
		} 
		
		if(!empty($rb_agency_options_arr['rb_agency_option_faviconurl'])){
			$favIcon = "<img src=\"{$rb_agency_options_arr['rb_agency_option_faviconurl']}\" style=\"border:0;\">";
		}else{
			$favIcon = "<i class=\"fa fa-heart\"></i>";
		}
		
		

		if(rb_get_casting_profileid() > 0 || current_user_can("manage_options")){

			if ($rb_agency_option_profilelist_favorite) {
				$displayActions .= "<div id=\"profile-favorite\" class=\"rbbtn-group\">";
				$displayActions .= "<a href=\"javascript:;\" title=\""
					.(in_array($ProfileID, $arr_favorites)?"Remove from Favorites":"Add to Favorites")
					."\" attr-id=\"".$ProfileID."\" class=\""
					.(in_array($ProfileID, $arr_favorites)?"active":"inactive")
					." favorite\">$favIcon&nbsp;<span>"
					.(in_array($ProfileID, $arr_favorites)?"Remove from Favorite":"Add to Favorite")
					."</span></a>";
				// $displayActions .= "<a href=\"".get_bloginfo("url")."/profile-favorite/\">View Favorites</a>";
				$displayActions .= "</div>";
			}
			if ($rb_agency_option_profilelist_castingcart) {
					$displayActions .= "<div id=\"profile-casting\" class=\"rbbtn-group\">";
					$displayActions .= "<a href=\"javascript:;\" title=\""
						.(in_array($ProfileID, $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")
						."\"  attr-id=\"".$ProfileID."\"  class=\""
						.(in_array($ProfileID, $arr_castingcart)?"active":"inactive")
						." castingcart\">$cartIcon&nbsp;<span>"
						.(in_array($ProfileID, $arr_castingcart)?"Remove from Casting Cart":"Add to Casting Cart")
						."</span></a>";
						
				//  $displayActions .= "<a href=\"".get_bloginfo("url")."/profile-casting/\">View Casting Cart</a>";
					$displayActions .= "</div>";
			}
		}
				$displayActions .= "</div>";

			if(isset($is_model_or_talent) && $is_model_or_talent > 0){
				$displayActions .= "<div class=\"rb-goback-link\"><a href=\"".get_bloginfo("url")."/casting-dashboard/\">Go Back to My Dashboard</a></div>";
			}

		$disp = $displayActions;

		/*if ($rb_agency_option_profilelist_castingcart) {
			if($countCastingCart <=0){
				$disp .= "<div class=\"newcastingcart\"><a title=\"Add to Casting Cart\" href=\"javascript:;\" id=\"".$ProfileID."\"  class=\"save_castingcart\">ADD TO CASTING CART</a></div></li>";
			} else {
				if(get_query_var('type')=="casting"){ //hides profile block when icon is click
					$divHide="onclick=\"javascript:document.getElementById('div$ProfileID').style.display='none';\"";
				}
				$disp .= "<div class=\"gotocastingcard\"><a ".(isset($divHide)?$divHide:"")." href=\"". get_bloginfo("wpurl") ."/profile-casting/\"  title=\"Go to Casting Cart\">VIEW CASTING CART</a></div>";
			}
		}

		if ($rb_agency_option_profilelist_favorite) {

			if($countFavorite <= 0){
				$disp .= "<div class=\"newfavorite\"><a title=\"Save to Favorites\" rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$ProfileID."\">SAVE TO FAVORITES</a></div>\n";
			} else {
				$disp .= "<div class=\"viewfavorites\"><a rel=\"nofollow\" title=\"View Favorites\" href=\"".  get_bloginfo("wpurl") ."/profile-favorite/\"/>VIEW FAVORITES</a></div>\n";
			}
		}*/

		return $disp;
	}




	/* 
	 * Profile Favorite Front End
	 */
	function rb_agency_save_favorite() {
		global $wpdb;
		if(is_user_logged_in()){
			if(isset($_POST["talentID"])){
				$query_favorite = $wpdb->get_results("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$_POST["talentID"]."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ,ARRAY_A);
				$datas_favorite = $query_favorite;
				$count_favorite = $wpdb->num_rows;

				if($count_favorite<=0){ //if not exist insert favorite!

					$wpdb->query("INSERT INTO ".table_agency_savedfavorite."(SavedFavoriteID,SavedFavoriteProfileID,SavedFavoriteTalentID) VALUES('','".rb_agency_get_current_userid()."','".$_POST["talentID"]."')");
					echo "inserted";

				} else { // favorite model exist, now delete!

					$wpdb->query("DELETE FROM  ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$_POST["talentID"]."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'");
					echo "deleted";

				}
			}
		}
		else {
			echo "not_logged";
		}
		die();
	}

	function rb_agency_save_favorite_javascript() {

			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_layoutprofile = (int)$rb_agency_options_arr['rb_agency_option_layoutprofile'];
			$rb_agency_option_layoutprofile = sprintf("%02s", $rb_agency_option_layoutprofile);

			?>

				<!--RB Agency Favorite -->
				<script type="text/javascript" >
				var layout_favorite = "<?php echo $rb_agency_option_layoutprofile; ?>";
				jQuery(document).ready(function () {
					jQuery(".rb_profile_tool a.favorite").click(function(){
						var Obj = jQuery(this);
						jQuery.ajax({
							type: 'POST',
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							data: {
								action: 'rb_agency_save_favorite',
								talentID: Obj.attr("attr-id")
							},
							success: function (results) {
							<?php if (get_query_var('type') == "favorite"){ ?>
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
									Obj.attr("title","Remove from Favorites");
									Obj.removeClass("inactive").addClass("active");
									Obj.find("span").text("Remove from Favorites");
								} else if(Obj.hasClass("active")){
									Obj.attr("title","Add to Favorites");
									Obj.removeClass("active").addClass("inactive");
									Obj.find("span").text("Add to Favorites");
								}
							<?php }?>
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
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							data: {
								action: 'rb_agency_save_favorite',
								talentID: jQuery(this).attr("id")
							},
							success: function (results) {

								if (results == 'error') {
									Obj.fadeOut().empty().html("Error in query. Try again").fadeIn();
								} else if (results == -1) {
									Obj.fadeOut().empty().html("<span style=\"color:red;font-size:11px;\">You're not signed in.</span><a href=\"<?php echo get_bloginfo('wpurl'); ?>/profile-member/\">Sign In</a>.").fadeIn();
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
									<?php
									if (get_query_var('type') == "favorite"){ ?>
										jQuery("#rbprofile-"+Obj.attr("attr-id")).hide("slow",function(){jQuery("#rbprofile-"+Obj.attr("attr-id")).remove();});
									<?php } else { ?>
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
								<?php }?>
								}
							}
						})
					});
				});
				</script>
				<!--END RB Agency Favorite -->

		<!-- [class=profile-list-layout<?php echo (int)$rb_agency_option_layoutprofile; ?>]-->
		<?php
	}

	add_action('wp_footer', 'rb_agency_save_favorite_javascript');
	add_action('wp_ajax_rb_agency_save_favorite', 'rb_agency_save_favorite');

	/* 
	 * Profile Casting Front End
	 */

		function rb_agency_save_castingcart() {
			global $wpdb;

			if(is_user_logged_in()){
				if(isset($_POST["talentID"])){
					//$query_castingcart = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."' AND CastingCartProfileID = '".rb_agency_get_current_userid()."' AND (CastingJobID='' OR CastingJobID <= 0)") ,ARRAY_A);
					$query_castingcart = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."' AND CastingCartProfileID = '".rb_agency_get_current_userid()."' AND (CastingJobID<= 0 OR CastingJobID IS NULL) ") ,ARRAY_A);
					//$query_castingcart = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."' AND CastingCartProfileID = '".rb_agency_get_current_userid()."'") ,ARRAY_A);
					$count_castingcart = $wpdb->num_rows;
					$datas_castingcart = $query_castingcart;
					if($count_castingcart<=0){ //if not exist insert favorite!
						$wpdb->insert(table_agency_castingcart, array('CastingCartProfileID'=>rb_agency_get_current_userid(), 'CastingCartTalentID'=>$_POST["talentID"]));
						echo "inserted";
					} else { // favorite model exist, now delete!
						$wpdb->query("DELETE FROM  ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."' AND (CastingJobID<= 0 OR CastingJobID IS NULL) ");
						//$wpdb->query("DELETE FROM  ". table_agency_castingcart."  WHERE CastingCartTalentID='".$_POST["talentID"]."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'");
						
						
						/* $query_castingcartx = $wpdb->get_results($wpdb->prepare("DELETE FROM ". table_agency_castingcart));
						print_r($query_castingcartx);
						echo "cart format."; */
						echo "deleted";
					}
					
				}/* 
				
				$query_castingcartx = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". table_agency_castingcart));
				print_r($query_castingcartx); */
			}
			else {
				echo "not_logged";
			}
			die();
		}


		function rb_agency_save_castingcart_javascript() {

			$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_layoutprofile = (int)$rb_agency_options_arr['rb_agency_option_layoutprofile'];
			$rb_agency_option_layoutprofile = sprintf("%02s", $rb_agency_option_layoutprofile);

		?>
				<!--RB Agency CastingCart -->
				<script type="text/javascript" >
				jQuery(".rb_profile_tool a.castingcart").click(function(){
					var Obj = jQuery(this);
					jQuery.ajax({
						type: 'POST',
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: {
							action: 'rb_agency_save_castingcart',
							'talentID': Obj.attr("attr-id")
						},
						success: function (results) {
							<?php 
							if (get_query_var('type') == "casting"){ ?>
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
									Obj.find("span").text("Remove from Casting Cart");
								} else if(Obj.hasClass("active")){
									Obj.attr("title","Add to Casting Cart");
									Obj.removeClass("active").addClass("inactive");
									Obj.find("span").text("Add to Casting Cart");
								}
							<?php }?>
						}
					});
				});
					var layout_casting = "<?php echo $rb_agency_option_layoutprofile; ?>";
					jQuery(document).ready(function ($) {
						$(".newcastingcart a").click(function () {
						var Obj = $(this);
							jQuery.ajax({
								type: 'POST',
								url: '<?php echo admin_url('admin-ajax.php'); ?>',
								data: {
									action: 'rb_agency_save_castingcart',
									'talentID': Obj.attr("attr-id")
								},
								success: function (results) {
									console.log(results);
									if (results == 'error') {
										Obj.fadeOut().empty().html("Error in query. Try again").fadeIn();
									} else if (results == -1) {
										Obj.fadeOut().empty().html("<span style=\"color:red;font-size:11px;\">You're not signed in.</span><a href=\"<?php echo get_bloginfo('wpurl'); ?>/profile-member/\">Sign In</a>.").fadeIn();
										setTimeout(function () {
											if (Obj.attr("class") == "save_castingcart") {
												Obj.fadeOut().empty().html("").fadeIn();
											} else {
												Obj.fadeOut().empty().html("").fadeIn();
											}
										}, 2000);
									} else {
										<?php 
										if (get_query_var('type') == "casting"){ ?>
											$("#rbprofile-"+Obj.attr("attr-id")).hide("slow",function(){$("#rbprofile-"+Obj.attr("attr-id")).remove();});
											console.log("#rbprofile-"+Obj.attr("attr-id"));
										<?php
										} else {
										?>
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
													Obj.find("span").text("href","<?php echo get_bloginfo('url');?>/profile-casting/");
												} else {
													Obj.empty().fadeOut().html("").fadeIn();
													Obj.attr("class", "save_castingcart");
													Obj.attr('title', 'Add to Casting Cart');
													//Obj.text("ADD TO CASTING CART");

													$(this).find("a[class=view_all_castingcart]").remove();
												}
											}
										<?php }?>
									}
								}
							})
						});
				});
			</script>
			
			
			

<style>

.active.castingcart img,.inactive.castingcart:hover img,.inactive.castingcart img:hover,
.active.favorite img,.inactive.favorite:hover img,.inactive.favorite img:hover
{
    filter: none;
  -webkit-filter: grayscale(0%);
  -moz-filter: grayscale(0%);
  -o-filter: grayscale(0%);
  
  -webkit-filter: none;
   -moz-filter: none;
   -ms-filter: none;
   /* width: 22px !important;height:auto; */
}
.inactive.castingcart img, .inactive.favorite img{
   filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 3.5+ */
   filter: gray; /* IE6-9 */
  
  -webkit-filter: grayscale(100%);
  -webkit-transition: .5s ease-in-out;
  -moz-filter: grayscale(100%); 
  -moz-transition: .5s ease-in-out;
  -o-filter: grayscale(100%); 
  -o-transition: .5s ease-in-out;
  
 /*  width: 22px !important;height:auto; */
}
</style>
			
			
			
		<?php
		}

		add_action('wp_ajax_rb_agency_save_castingcart', 'rb_agency_save_castingcart');
		add_action('wp_footer', 'rb_agency_save_castingcart_javascript');


	function load_criteria_fields(){

		$data = isset($_POST['value'])?trim($_POST['value']):"";

		include (dirname(__FILE__) ."/app/casting.class.php");

		//load ajax functions
		RBAgency_Casting::load_criteria_fields($data);

	}

	add_action('wp_ajax_load_criteria_fields', 'load_criteria_fields');
	add_action('wp_ajax_nopriv_load_criteria_fields', 'load_criteria_fields');

   /*
	 *  add to casting cart
	 */
	function client_add_casting(){

		$profile_id = $_POST['talent_id'];
		$job_id = $_POST['job_id'];

		include (dirname(__FILE__) ."/app/casting.class.php");

		//load ajax functions
		RBAgency_Casting::rb_update_castingcart($profile_id,$job_id);

	}

	add_action('wp_ajax_client_add_casting', 'client_add_casting');
	add_action('wp_ajax_client_add_casting', 'client_add_casting');


	/*/
	 *  Fix form post url for multi language.
	/*/
/*
	function rb_agency_casting_postURILanguage($request_URI){
		if(!in_array(substr($_SERVER['REQUEST_URI'],1,2), array("en","nl"))){
			if (function_exists('trans_getLanguage')) {
				if(qtrans_getLanguage()=='nl') {
					return "/".qtrans_getLanguage();

				} elseif(qtrans_getLanguage()=='en') {
					return "/".qtrans_getLanguage();
				}
			}
		}
	}
	 */

// *************************************************************************************************** //
// Handle Emails

	// Make Directory for new profile
/*     function rb_agency_casting_checkdir($ProfileGallery){

			if (!is_dir(RBAGENCY_UPLOADPATH . $ProfileGallery)) {
				mkdir(RBAGENCY_UPLOADPATH . $ProfileGallery, 0755);
				chmod(RBAGENCY_UPLOADPATH . $ProfileGallery, 0777);
			}
			return $ProfileGallery;
	}*/



// *************************************************************************************************** //
// Functions

	// Move Login Page
/*	add_filter("login_init", "rb_agency_casting_login_movepage", 10, 2);
		function rb_agency_casting_login_movepage( $url ) {
			global $action;

			if (empty($action) || 'login' == $action) {
				wp_safe_redirect(get_bloginfo("wpurl"). "/profile-login/");
				die;
			}
		}

	// Redirect after Login
	add_filter('login_redirect', 'rb_agency_casting_login_redirect', 10, 3);
		function rb_agency_casting_login_redirect() {
			global $user_ID, $current_user, $wp_roles;
			if( $user_ID ) {
				$user_info = get_userdata( $user_ID ); 

				if( current_user_can( 'edit_posts' )) {
					header("Location: ". get_bloginfo("wpurl"). "/wp-admin/");
				} elseif ( strtotime( $user_info->user_registered ) > ( time() - 172800 ) ) {
					// If user_registered date/time is less than 48hrs from now
					// Message will show for 48hrs after registration
					header("Location: ". get_bloginfo("wpurl"). "/profile-member/account/");
				} else {
					header("Location: ". get_bloginfo("wpurl"). "/profile-member/");
				}
			}
		}*/


		/**
		 * Switch casting-login sidebars to widget
		 *
		 */
		function rb_castinglogin_widgets_init() {
			register_sidebar( array(
					'name' => 'RB Agency Casting: Login Sidebar',
					'id' => 'rb-agency-casting-login-sidebar',
					'before_widget' => '<div>',
					'after_widget' => '</div>',
					'before_title' => '<h3>',
					'after_title' => '</h3>',
				) );
		}
		add_action( 'widgets_init', 'rb_castinglogin_widgets_init' );

		function rb_check_casting_status($userID){
			global $current_user, $wpdb;
			get_currentuserinfo();
		
			$query = "SELECT CastingIsActive FROM ". table_agency_casting ." WHERE CastingUserLinked =  ". $current_user->ID;
			$CastingIsActive = $wpdb->get_var($query);
			if($CastingIsActive != 1){
				return false;
			}
			return true;
		}
		function rb_check_profile_status($userID){
			global $current_user, $wpdb;
			get_currentuserinfo();
		
			$query = "SELECT ProfileIsActive FROM ". table_agency_profile ." WHERE ProfileUserLinked =  ". $current_user->ID;
			$CastingIsActive = $wpdb->get_var($query);
			if($CastingIsActive != 1){
				return false;
			}
			return true;
		}
		
		
		
		function rb_agency_casting_jobs(){
			include_once(dirname(__FILE__) .'/view/admin-castingjobs.php');
		}

		function rb_get_customfields_castingjobs_func($result){
			   $ProfileCustomID = $result['ProfileCustomID'];
		       $ProfileCustomTitle = $result['ProfileCustomTitle'];
			   $ProfileCustomType  = $result['ProfileCustomType'];
			   $ProfileCustomOptions = $result['ProfileCustomOptions'];
			   
			   if($ProfileCustomType == 1 || $ProfileCustomType == 7){
			   	    echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
							$custom_value = rb_agency_get_casting_job_custom_value($_GET['Job_ID'],$ProfileCustomID);
						}else{
							$custom_value = "";
						}
											
						$value = !empty($custom_value)?$custom_value:"";					
						echo "<div><input type=\"text\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></div>";
					echo "</div>\n";
			   }elseif($ProfileCustomType == 3){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	echo "<option>--Select--</option>";
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
									$custom_value = rb_agency_get_casting_job_custom_value($_GET['Job_ID'],$ProfileCustomID);
								}else{
									$custom_value = "";
								}
			   	    		    $selected = $parse[$idx] == $custom_value ? "selected='selected'" : "";		   	    			
			   	    			echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 4){
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						echo "<div><textarea name=\"ProfileCustom2_".$ProfileCustomID."[]\" ></textarea></div>";
					echo "</div>\n";

			   }elseif($ProfileCustomType == 5){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
									$custom_value = rb_agency_get_casting_job_custom_value($_GET['Job_ID'],$ProfileCustomID);
								}else{
									$custom_value = "";
								}			   	    		
			   	    			$checked = strpos($custom_value,$parse[$idx]) !== false ? "checked" : "";	
			   	    			echo "<input type=\"checkbox\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 6){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
									$custom_value = rb_agency_get_casting_job_custom_value($_GET['Job_ID'],$ProfileCustomID);
								}else{
									$custom_value = "";
								}
			   	    			$checked = $parse[$idx] == $custom_value ? "checked" : "";			   	    			
			   	    			echo "<input type=\"radio\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 9){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" multiple >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	$temp_arr = array();
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    				   	    		
			   	    		if(!empty($parse[$idx])){
			   	    			if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
									$custom_value = rb_agency_get_casting_job_custom_value($_GET['Job_ID'],$ProfileCustomID);
								}else{
									$custom_value = "";
								}
			   	    			$selected = strpos($custom_value,$parse[$idx]) !== false ? "selected='selected'" : "";
			   	    		    echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";		   	    		    	   	    			
			   	    			
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 10){
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
							$custom_value = rb_agency_get_casting_job_custom_value($_GET['Job_ID'],$ProfileCustomID);
						}else{
							$custom_value = "";
						}
						$value = !empty($custom_value)?$custom_value:"";
						echo "<div><input type=\"text\" id=\"custom_castingjob\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></div>";
					echo "</div>\n";

			   	    echo '<script type="text/javascript">
							jQuery(function(){

								jQuery( "input[id=custom_castingjob]").datepicker({
									dateFormat: "yy-mm-dd"
								});

							});
							</script>';
			   }
		}
		
		function rb_get_customfields_castingjobs(){
		   
		   global $wpdb;		    

		   if(isset($_GET['Job_ID']) && !empty($_GET['Job_ID'])){
		   	    $query_get ="SELECT * FROM ".$wpdb->prefix."agency_casting_job_customfields WHERE Job_ID = ".$_GET['Job_ID'];
		   }else{
		   		$query_get ="SELECT * FROM ".$wpdb->prefix."agency_casting_job_customfields";
		   }
		   
		   $result_query_get = $wpdb->get_results($query_get,ARRAY_A);
		   $temp_arr = array();
		   foreach( $result_query_get as $result){

		   	$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingJob = 1 OR ProfileCustomID = ".$result['Customfield_ID'] ." ORDER BY ProfileCustomOrder ASC";
		    $result_query_get2 = $wpdb->get_results($query_get,ARRAY_A);
		    foreach($result_query_get2 as $res)
		    	if(!in_array($res['ProfileCustomID'],$temp_arr)){
		    		 $current_user = wp_get_current_user();
					 $userLevel = get_user_meta($current_user->ID, 'wp_user_level', true); 
					 if($result['ProfileCustomView'] == 0){

					 	rb_get_customfields_castingjobs_func($res);
					 }else{
					 	if($userLevel > 0){

						 	rb_get_customfields_castingjobs_func($res);
						}else{
							return false;
						}
					 }			 
		    		
		    		$temp_arr[] = $res['ProfileCustomID'];
		    	}	

		   }	   

		   

	    }

	    function rb_get_customfields_admin_castingjobs(){
	    	global $wpdb;
	    	$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingJob = 1 ORDER BY ProfileCustomOrder ASC";
	    	$result_query_get = $wpdb->get_results($query_get,ARRAY_A);
	    	//print_r($result_query_get);
	    	$current_user = wp_get_current_user();
			$userLevel = get_user_meta($current_user->ID, 'wp_user_level', true);
			$temp_arr = array();
	    	foreach($result_query_get as $res){
	    		if(!in_array($res['ProfileCustomID'],$temp_arr)){
		    		if($res['ProfileCustomView'] == 0){
						 rb_get_customfields_admin_castingjobs_func($res);
					}else{
					 	if($userLevel > 0){
						 	rb_get_customfields_admin_castingjobs_func($res);
						}else{
							return false;
						}
	    		    }
	    		    $temp_arr[] = $res['ProfileCustomID'];
    			}
	    		//rb_get_customfields_admin_castingjobs_func($res);
	    	}
	    }

	    function rb_get_customfields_admin_castingjobs_func($result){
	    	$ProfileCustomID = $result['ProfileCustomID'];
		       $ProfileCustomTitle = $result['ProfileCustomTitle'];
			   $ProfileCustomType  = $result['ProfileCustomType'];
			   $ProfileCustomOptions = $result['ProfileCustomOptions'];
			   
			   if($ProfileCustomType == 1 || $ProfileCustomType == 7){
			   	    echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						echo "<div><input type=\"text\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" ></div>";
					echo "</div>\n";
			   }elseif($ProfileCustomType == 3){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	echo "<option>--Select--</option>";
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 4){
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						echo "<div><textarea name=\"ProfileCustom2_".$ProfileCustomID."[]\" ></textarea></div>";
					echo "</div>\n";

			   }elseif($ProfileCustomType == 5){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			echo "<input type=\"checkbox\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 6){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			 	    			echo "<input type=\"radio\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" >".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 9){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" multiple >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	for($idx=0;$idx<count($parse);$idx++){			   	    				   	    		
			   	    		if(!empty($parse[$idx])){
			   	    			echo "<option value=\"".$parse[$idx]."\" >".$parse[$idx]."</option>";   			
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 10){
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						echo "<div><input type=\"text\" id=\"custom_castingjob\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" ></div>";
					echo "</div>\n";

			   	    echo '<script type="text/javascript">
							jQuery(function(){

								jQuery( "input[id=custom_castingjob]").datepicker({
									dateFormat: "yy-mm-dd"
								});

							});
							</script>';
			   }
	    }



	    function rb_get_customfields_castingpostjobs_func($result){
	    					$ProfileCustomID = $result['ProfileCustomID'];
					       $ProfileCustomTitle = $result['ProfileCustomTitle'];
						   $ProfileCustomType  = $result['ProfileCustomType'];
						   $ProfileCustomOptions = $result['ProfileCustomOptions'];
						   
						   if($ProfileCustomType == 1 || $ProfileCustomType == 7){
						   	    echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
									echo "<label>".$ProfileCustomTitle."</label>\n";
									echo "<div><input type=\"text\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" ></div>";
									
								echo "</div>\n";
						   }elseif($ProfileCustomType == 3){
						   	    echo "<div class=\"rbfield rbselect rbsingle\" >";
						   	    	echo "<label>".$ProfileCustomTitle."</label>";
						   	    	echo "<div>";
						   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" >";
						   	    	$parse3 = explode("|",$ProfileCustomOptions);
						   	    	echo "<option>--Select--</option>";
						   	    	for($idx=0;$idx<count($parse3);$idx++){
						   	    		if(!empty($parse3[$idx])){			   	    			
						   	    			echo "<option value=\"".$parse3[$idx]."\" >".$parse3[$idx]."</option>";
						   	    		}			   	    		
						   	    	}
						   	    	echo "</select>";
						   	    	echo "</div>";
						   	    echo "</div>\n";
						   }elseif($ProfileCustomType == 4){
						   		echo "<div class=\"rbfield rbtextarea rbsingle \" id=\"\">\n";
									echo "<label>".$ProfileCustomTitle."</label>\n";
									echo "<div><textarea name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" ></textarea></div>";
								echo "</div>\n";

						   }elseif($ProfileCustomType == 5){
						   		echo "<div class=\"rbfield rbtext rbsingle\" >";
						   	    	echo "<label>".$ProfileCustomTitle."</label>";
						   	    	echo "<div>";
						   	    	$parse5 = explode("|",$ProfileCustomOptions);			   	    	
						   	    	for($idx=0;$idx<count($parse5);$idx++){
						   	    		if(!empty($parse5[$idx])){			   	    			
						   	    			echo "<input type=\"checkbox\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse5[$idx]."\" >".$parse5[$idx]."\n";
						   	    		}			   	    		
						   	    	}
						   	    	echo "</div>";
						   	    echo "</div>\n";
						   }elseif($ProfileCustomType == 6){
						   		echo "<div class=\"rbfield rbtext rbsingle\" >";
						   	    	echo "<label>".$ProfileCustomTitle."</label>";
						   	    	echo "<div>";
						   	    	$parse6 = explode("|",$ProfileCustomOptions);			   	    	
						   	    	for($idx=0;$idx<count($parse6);$idx++){
						   	    		if(!empty($parse6[$idx])){			   	    			
						   	    			echo "<input type=\"radio\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse6[$idx]."\" >".$parse6[$idx]."\n";
						   	    		}			   	    		
						   	    	}
						   	    	echo "</div>";
						   	    echo "</div>\n";
						   }elseif($ProfileCustomType == 9){
						   	    echo "<div class=\"rbfield rbselect rbsingle\" >";
						   	    	echo "<label>".$ProfileCustomTitle."</label>";
						   	    	echo "<div><select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" multiple >";
						   	    	$parse9 = explode("|",$ProfileCustomOptions);
						   	    	for($idx=0;$idx<count($parse9);$idx++){
						   	    		if(!empty($parse9[$idx])){			   	    			
						   	    			echo "<option value=\"".$parse9[$idx]."\" >".$parse9[$idx]."</option>";
						   	    		}			   	    		
						   	    	}
						   	    	echo "</select></div>";
						   	    echo "</div>\n";
						   }elseif($ProfileCustomType == 10){
						   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
									echo "<label>".$ProfileCustomTitle."</label>\n";
									echo "<div><input type=\"text\" id=\"custom_castingjob\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" ></div>";
								echo "</div>\n";

						   	    echo '<script type="text/javascript">
										jQuery(function(){

											jQuery( "input[id=custom_castingjob]").datepicker({
												dateFormat: "yy-mm-dd"
											});

										});
										</script>';
						   }
	    }

	    function rb_get_customfields_castingpostjobs(){
		   global $wpdb;
		   
		   $query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingJob = 1 ORDER BY ProfileCustomOrder ASC";
		   $result_query_get = $wpdb->get_results($query_get,ARRAY_A);	  
		   $current_user = wp_get_current_user();
		   $userLevel = get_user_meta($current_user->ID, 'wp_user_level', true);
		  
		   echo "<br>";
		   echo "<h3>Additional Details</h3>";
		   $temp_arr = array();
		   foreach( $result_query_get as $result){
		   			if($result["ProfileCustomView"] == 0 || $result["ProfileCustomView"] == 1){
		   				rb_get_customfields_castingpostjobs_func($result);
		   			}else{
		   				if($userLevel == 0){

		   				}else{
		   					rb_get_customfields_castingpostjobs_func($result);
		   				}
		   			}	   		
		    		  	   

		   }	



	    }

	    function rb_get_customfields_castingregister_func($result){
	    	   global $wpdb;
			   $current_user = wp_get_current_user();
			   $castingIDFromTable = "";
			   if(isset($_GET['CastingID'])){
			   	  $WHERE = "CastingID = ".$_GET['CastingID'];
			   }else{
			   	   $query_get_profile = "SELECT * FROM ".$wpdb->prefix."agency_casting WHERE CastingContactEmail = '".$current_user->user_email."' ";
			   	   $result_query_get_profile = $wpdb->get_results($query_get_profile,ARRAY_A); 
			   	   foreach($result_query_get_profile as $casting_profile)
			   	   	$castingIDFromTable = $casting_profile["CastingID"];
			   }
				
			   $castID = isset($_GET['CastingID']) ? $_GET['CastingID'] : $castingIDFromTable; 
	    	
	    	   $ProfileCustomID = $result['ProfileCustomID'];
		       $ProfileCustomTitle = $result['ProfileCustomTitle'];
			   $ProfileCustomType  = $result['ProfileCustomType'];
			   $ProfileCustomOptions = $result['ProfileCustomOptions'];	
			  
			   
			   if($ProfileCustomType == 1 || $ProfileCustomType == 7){
			   	    echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";					
						echo "<div><input type=\"text\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></div>";
					echo "</div>\n";
			   }elseif($ProfileCustomType == 3){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div><select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	echo "<option>--Select--</option>";
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
			   	    		    $selected = $parse[$idx] == $custom_value ? "selected='selected'" : "";		   	    			
			   	    			echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select></div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 4){
			   	$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";	
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						echo "<div><textarea name=\"ProfileCustom2_".$ProfileCustomID."[]\" >".$value."</textarea></div>";
					echo "</div>\n";

			   }elseif($ProfileCustomType == 5){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);			   	    		
			   	    			$checked = strpos($custom_value,$parse[$idx]) !== false ? "checked" : "";	
			   	    			echo "<input type=\"checkbox\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 6){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
			   	    			$checked = $parse[$idx] == $custom_value ? "checked" : "";			   	    			
			   	    			echo "<input type=\"radio\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 9){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" multiple >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	$temp_arr = array();
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    				   	    		
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
			   	    			$selected = strpos($custom_value,$parse[$idx]) !== false ? "selected='selected'" : "";
			   	    		    echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";		   	    		    	   	    			
			   	    			
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 10){
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";
						echo "<div><input type=\"text\" id=\"custom_castingjob\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></div>";
					echo "</div>\n";

			   	    echo '<script type="text/javascript">
							jQuery(function(){

								jQuery( "input[id=custom_castingjob]").datepicker({
									dateFormat: "yy-mm-dd"
								});

							});
							</script>';
			   }
	    }

	    function rb_get_customfields_castingregister(){
		   global $wpdb;
		   $current_user = wp_get_current_user();
		   $castingIDFromTable = "";
		   if(isset($_GET['CastingID'])){
		   	  $WHERE = "CastingID = ".$_GET['CastingID'];
		   }else{
		   	   $query_get_profile = "SELECT * FROM ".$wpdb->prefix."agency_casting WHERE CastingContactEmail = '".$current_user->user_email."' ";
		   	   $result_query_get_profile = $wpdb->get_results($query_get_profile,ARRAY_A); 
		   	   foreach($result_query_get_profile as $casting_profile)
		   	   	$castingIDFromTable = $casting_profile["CastingID"];
		   }
	
		   $castID = isset($_GET['CastingID']) ? $_GET['CastingID'] : $castingIDFromTable; 
		   	    
		   $temp_arr = array();
		   $query_get ="SELECT * FROM ".$wpdb->prefix."agency_casting_register_customfields WHERE CastingID = '".$castID."'";
		   $result_query_get = $wpdb->get_results($query_get,ARRAY_A);
		   if($wpdb->num_rows > 0){
		   		foreach( $result_query_get as $result){
		   			if(!isset($_GET['CastingID'])){
		   				$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 AND ProfileCustomView != 2 OR ProfileCustomID = ".$result['Customfield_ID'] ." ORDER BY ProfileCustomOrder ASC";
		   			}else{
		   				$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 OR ProfileCustomID = ".$result['Customfield_ID'] ." ORDER BY ProfileCustomOrder ASC";
		   			}
			   	
			    $result_query_get2 = $wpdb->get_results($query_get,ARRAY_A);
			    foreach($result_query_get2 as $res)
			    	if(!in_array($res['ProfileCustomID'],$temp_arr)){

			    		 $current_user = wp_get_current_user();
						 $userLevel = get_user_meta($current_user->ID, 'wp_user_level', true); 
						 if($res['ProfileCustomView'] == 0 && $res['ProfileCustomShowCastingManager'] == 0){

						 	rb_get_customfields_castingregister_func($res);
						 }	 
			    		
			    		$temp_arr[] = $res['ProfileCustomID'];
			    	}	

			   } 
		   }else{
		   		if(!isset($_GET['CastingID'])){
		   			$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 AND ProfileCustomView != 2 ORDER BY ProfileCustomOrder ASC";
		   		}else{
		   			$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 ORDER BY ProfileCustomOrder ASC";
		   		}
		   		
			    $result_query_get2 = $wpdb->get_results($query_get,ARRAY_A);
			    foreach($result_query_get2 as $res){
			    	if(!in_array($res['ProfileCustomID'],$temp_arr)){
			    		 $current_user = wp_get_current_user();
						 $userLevel = get_user_meta($current_user->ID, 'wp_user_level', true); 
						 if($res['ProfileCustomView'] == 0 && $res['ProfileCustomShowCastingManager'] == 0){
						 	rb_get_customfields_castingregister_func($res);
						 }		 
			    		
			    		$temp_arr[] = $res['ProfileCustomID'];
			    	}
			    }
			    		

			   
		   }
		      
	    }

	    function rb_agency_update_castingjob_func($result,$JobID){
	    	$ProfileCustomID = $result['ProfileCustomID'];
		       $ProfileCustomTitle = $result['ProfileCustomTitle'];
			   $ProfileCustomType  = $result['ProfileCustomType'];
			   $ProfileCustomOptions = $result['ProfileCustomOptions'];
			   
			    if($ProfileCustomType == 1 || $ProfileCustomType == 7){
			   	    echo "<tr>";
						echo "<td>".$ProfileCustomTitle."</td>";
						$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);											
						$value = !empty($custom_value)?$custom_value:"";					
						echo "<td><input type=\"text\" name=\"UpdateJob_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></td>";
					echo "</tr>\n";
			   }elseif($ProfileCustomType == 3){
			   	    echo "<tr>";
			   	    	echo "<td>".$ProfileCustomTitle."</td>";
			   	    	echo "<td><select name=\"UpdateJob_".$ProfileCustomID."_".$ProfileCustomType."[]\" >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	echo "<option>--Select--</option>";
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);
			   	    		    $selected = $parse[$idx] == $custom_value ? "selected='selected'" : "";		   	    			
			   	    			echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select></td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 4){
			   	$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);											
						$value = !empty($custom_value)?$custom_value:"";
			   		echo "<tr>";
						echo "<td>".$ProfileCustomTitle."</td>\n";
						echo "<td><textarea name=\"UpdateJob_".$ProfileCustomID."[]\" >".$value."</textarea></td>";
					echo "</tr>";

			   }elseif($ProfileCustomType == 5){
			   		echo "<tr>";
			   	    	echo "<td>".$ProfileCustomTitle."</td>";
			   	    	echo "<td>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);			   	    		
			   	    			$checked = strpos($custom_value,$parse[$idx]) !== false ? "checked" : "";	
			   	    			echo "<input type=\"checkbox\" name=\"UpdateJob_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 6){
			   		echo "<tr>";
			   	    	echo "<td>".$ProfileCustomTitle."</td>";
			   	    	echo "<td>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);
			   	    			$checked = $parse[$idx] == $custom_value ? "checked" : "";			   	    			
			   	    			echo "<input type=\"radio\" name=\"UpdateJob_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 9){
			   	    echo "<tr>";
			   	    	echo "<td>".$ProfileCustomTitle."</td>";
			   	    	echo "<td><select name=\"UpdateJob_".$ProfileCustomID."_".$ProfileCustomType."[]\" multiple >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	$temp_arr = array();
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    				   	    		
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);
			   	    			$selected = strpos($custom_value,$parse[$idx]) !== false ? "selected='selected'" : "";
			   	    		    echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";		   	    		    	   	    			
			   	    			
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select></td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 10){
			   		echo "<tr>";
						echo "<td>".$ProfileCustomTitle."</td>\n";
						$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";
						echo "<td><input type=\"text\" id=\"custom_castingjob\" name=\"UpdateJob_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></td>";
					echo "</tr>\n";

			   	    echo '<script type="text/javascript">
							jQuery(function(){

								jQuery( "input[id=custom_castingjob]").datepicker({
									dateFormat: "yy-mm-dd"
								});

							});
							</script>';
			   }
	    }

	    function rb_agency_update_castingjob(){
	    	global $wpdb;

	    	//Get Job ID
	    	$JobID = get_query_var('target');

	    	$sql = "SELECT * FROM ".$wpdb->prefix."agency_casting_job_customfields job INNER JOIN ".$wpdb->prefix."agency_customfields cust ON cust.ProfileCustomID = job.Customfield_ID WHERE job.Job_ID = ".$JobID;
	    	$custom_fields = $wpdb->get_results($sql,ARRAY_A);
	    	$current_user = wp_get_current_user();
			$userLevel = get_user_meta($current_user->ID, 'wp_user_level', true);

	    	echo "<tr>
					<td><h3>Other Details</h3></td><td></td>
				</tr>";
	    	foreach($custom_fields as $custom_field){
	    		if($custom_field["ProfileCustomView"] == 0 || $custom_field["ProfileCustomView"] == 1){
		   				rb_agency_update_castingjob_func($custom_field,$JobID);
		   		}else{
		   			if($userLevel == 0){

	   				}else{
	   					rb_agency_update_castingjob_func($custom_field,$JobID);
	   				}
	   			}
	    		
	    	}

	    }

	    function rb_agency_detail_castingjob_func($result,$JobID){
	    	$ProfileCustomID = $result['ProfileCustomID'];
		       $ProfileCustomTitle = $result['ProfileCustomTitle'];
			   $ProfileCustomType  = $result['ProfileCustomType'];
			   $ProfileCustomOptions = $result['ProfileCustomOptions'];
			   
			    if($ProfileCustomType == 1 || $ProfileCustomType == 7){
			   	    echo "<tr>";
						echo "<td><strong>".$ProfileCustomTitle."</strong></td>";
						$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);											
						$value = !empty($custom_value)?$custom_value:"";					
						echo "<td>".$value."</td>";
					echo "</tr>\n";
			   }elseif($ProfileCustomType == 3){
			   	    echo "<tr>";
			   	    	echo "<td><strong>".$ProfileCustomTitle."</strong></td>";
			   	    	echo "<td>".rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID)."</td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 4){
			   		echo "<tr>";
						echo "<td><strong>".$ProfileCustomTitle."</strong></td>\n";
						echo "<td>".rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID)."</td>";
					echo "</tr>";

			   }elseif($ProfileCustomType == 5){
			   		echo "<tr>";
			   	    	echo "<td><strong>".$ProfileCustomTitle."</strong></td>";
			   	    	echo "<td>".rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID)."</td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 6){
			   		echo "<tr>";
			   	    	echo "<td><strong>".$ProfileCustomTitle."</strong></td>";
			   	    	echo "<td>".rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID)."</td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 9){
			   	    echo "<tr>";
			   	    	echo "<td><strong>".$ProfileCustomTitle."</strong></td>";
			   	    	echo "<td>".rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID)."</td>";
			   	    echo "</tr>";
			   }elseif($ProfileCustomType == 10){
			   		echo "<tr>";
						echo "<td><strong>".$ProfileCustomTitle."</strong></td>\n";
						$custom_value = rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";
						echo "<td>".rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID)."</td>";
					echo "</tr>\n";

			   	    echo '<script type="text/javascript">
							jQuery(function(){

								jQuery( "input[id=custom_castingjob]").datepicker({
									dateFormat: "yy-mm-dd"
								});

							});
							</script>';
			   }
	    }

	    function rb_agency_detail_castingjob(){
	    	global $wpdb;

	    	//Get Job ID
	    	$JobID = get_query_var('value');

	    	$sql = "SELECT * FROM ".$wpdb->prefix."agency_casting_job_customfields job INNER JOIN ".$wpdb->prefix."agency_customfields cust ON cust.ProfileCustomID = job.Customfield_ID WHERE job.Job_ID = ".$JobID;
	    	$custom_fields = $wpdb->get_results($sql,ARRAY_A);
	    	$current_user = wp_get_current_user();
			$userLevel = get_user_meta($current_user->ID, 'wp_user_level', true);
			
	    	echo "<tr>
					<td><h3>Other Details</h3></td><td></td>
				</tr>";
	    	foreach($custom_fields as $custom_field){
	    		if($custom_field["ProfileCustomView"] == 0 || $custom_field["ProfileCustomView"] == 1){
		   				rb_agency_detail_castingjob_func($custom_field,$JobID);
		   		}else{
		   			if($userLevel == 0){

	   				}else{
	   					rb_agency_detail_castingjob_func($custom_field,$JobID);
	   				}
	   			}
	    		
	    	}

	    }

	    function rb_agency_get_casting_register_custom_value($CastingID,$ProfileCustomID){
	    	global $wpdb;
	    	$query_get ="SELECT * FROM ".$wpdb->prefix."agency_casting_register_customfields WHERE CastingID = ".$CastingID." AND Customfield_ID = ".$ProfileCustomID;
		    $result_query_get = $wpdb->get_results($query_get,ARRAY_A);
		    $custom_value = null;
		    foreach($result_query_get as $result){
		    	$custom_value = $result['Customfield_value'];
		    }
		    return !empty($custom_value) ? $custom_value : "";
	    }

	     function rb_agency_get_casting_job_custom_value($JobID,$ProfileCustomID){
	    	global $wpdb;
	    	$query_get ="SELECT * FROM ".$wpdb->prefix."agency_casting_job_customfields WHERE Job_ID = ".$JobID." AND Customfield_ID = ".$ProfileCustomID;
		    $result_query_get = $wpdb->get_results($query_get,ARRAY_A);
		    $custom_value = null;
		    foreach($result_query_get as $result){
		    	$custom_value = $result['Customfield_value'];
		    }
		    return !empty($custom_value) ? $custom_value : "";
	    }

	    function rb_agency_get_casting_dashboard_customfields(){
	    	global $wpdb;
		    $current_user = wp_get_current_user();

		    $query_get_profile = "SELECT * FROM ".$wpdb->prefix."agency_casting WHERE CastingContactEmail = '".$current_user->user_email."' ";
		   	$result_query_get_profile = $wpdb->get_results($query_get_profile,ARRAY_A); 
		   	$casting_ID = null;
		   	foreach($result_query_get_profile as $casting_profile){
		   		$casting_ID = $casting_profile["CastingID"];
		   	}		   	   	

		    $query = "SELECT * FROM ".$wpdb->prefix."agency_casting_register_customfields reg INNER JOIN ".$wpdb->prefix."agency_customfields cust ON cust.ProfileCustomID = reg.Customfield_ID WHERE reg.CastingID = ".$casting_ID." AND cust.ProfileCustomView != 2 ORDER BY cust.ProfileCustomOrder ASC";
	    	$profiles = $wpdb->get_results($query,ARRAY_A);
	    	$temp_arr = array();
	    	foreach($profiles as $profile){	    		
	    		if(!in_array($profile['ProfileCustomID'],$temp_arr)){
	    			echo "<li>".$profile['ProfileCustomTitle']." : <strong>". $profile['Customfield_value'] ."</strong></li>";
	    			$temp_arr[] = $profile['ProfileCustomID'];
	    		}    			    		
	    	}
	    }

//for admin
	    function rb_get_admin_customfields_castingregister_func($result){
	    	   global $wpdb;
			   $current_user = wp_get_current_user();
			   $castingIDFromTable = "";
			   if(isset($_GET['CastingID'])){
			   	  $WHERE = "CastingID = ".$_GET['CastingID'];
			   }else{
			   	   $query_get_profile = "SELECT * FROM ".$wpdb->prefix."agency_casting WHERE CastingContactEmail = '".$current_user->user_email."' ";
			   	   $result_query_get_profile = $wpdb->get_results($query_get_profile,ARRAY_A); 
			   	   foreach($result_query_get_profile as $casting_profile)
			   	   	$castingIDFromTable = $casting_profile["CastingID"];
			   }
				
			   $castID = isset($_GET['CastingID']) ? $_GET['CastingID'] : $castingIDFromTable; 
	    	
	    	   $ProfileCustomID = $result['ProfileCustomID'];
		       $ProfileCustomTitle = $result['ProfileCustomTitle'];
			   $ProfileCustomType  = $result['ProfileCustomType'];
			   $ProfileCustomOptions = $result['ProfileCustomOptions'];	
			  
			   
			   if($ProfileCustomType == 1 || $ProfileCustomType == 7){
			   	    echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";					
						echo "<div><input type=\"text\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></div>";
					echo "</div>\n";
			   }elseif($ProfileCustomType == 3){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div><select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	echo "<option>--Select--</option>";
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
			   	    		    $selected = $parse[$idx] == $custom_value ? "selected='selected'" : "";		   	    			
			   	    			echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select></div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 4){
			   	$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";	
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						echo "<div><textarea name=\"ProfileCustom2_".$ProfileCustomID."[]\" >".$value."</textarea></div>";
					echo "</div>\n";

			   }elseif($ProfileCustomType == 5){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);			   	    		
			   	    			$checked = strpos($custom_value,$parse[$idx]) !== false ? "checked" : "";	
			   	    			echo "<input type=\"checkbox\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 6){
			   		echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<div>";
			   	    	$parse = explode("|",$ProfileCustomOptions);			   	    	
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
			   	    			$checked = $parse[$idx] == $custom_value ? "checked" : "";			   	    			
			   	    			echo "<input type=\"radio\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$parse[$idx]."\" ".$checked.">".$parse[$idx]."\n";
			   	    		}			   	    		
			   	    	}
			   	    	echo "</div>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 9){
			   	    echo "<div class=\"rbfield rbtext\" >";
			   	    	echo "<label>".$ProfileCustomTitle."</label>";
			   	    	echo "<select name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" multiple >";
			   	    	$parse = explode("|",$ProfileCustomOptions);
			   	    	$temp_arr = array();
			   	    	for($idx=0;$idx<count($parse);$idx++){
			   	    				   	    		
			   	    		if(!empty($parse[$idx])){
			   	    			$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
			   	    			$selected = strpos($custom_value,$parse[$idx]) !== false ? "selected='selected'" : "";
			   	    		    echo "<option value=\"".$parse[$idx]."\" ".$selected.">".$parse[$idx]."</option>";		   	    		    	   	    			
			   	    			
			   	    		}			   	    		
			   	    	}
			   	    	echo "</select>";
			   	    echo "</div>";
			   }elseif($ProfileCustomType == 10){
			   		echo "<div class=\"rbfield rbtext rbsingle \" id=\"\">\n";
						echo "<label>".$ProfileCustomTitle."</label>\n";
						$custom_value = rb_agency_get_casting_register_custom_value($castID,$ProfileCustomID);
						$value = !empty($custom_value)?$custom_value:"";
						echo "<div><input type=\"text\" id=\"custom_castingjob\" name=\"ProfileCustom2_".$ProfileCustomID."_".$ProfileCustomType."[]\" value=\"".$value."\"></div>";
					echo "</div>\n";

			   	    echo '<script type="text/javascript">
							jQuery(function(){

								jQuery( "input[id=custom_castingjob]").datepicker({
									dateFormat: "yy-mm-dd"
								});

							});
							</script>';
			   }
	    }

	    function rb_get_admin_customfields_castingregister(){
		   global $wpdb;
		   $current_user = wp_get_current_user();
		   $castingIDFromTable = "";
		   if(isset($_GET['CastingID'])){
		   	  $WHERE = "CastingID = ".$_GET['CastingID'];
		   }else{
		   	   $query_get_profile = "SELECT * FROM ".$wpdb->prefix."agency_casting WHERE CastingContactEmail = '".$current_user->user_email."' ";
		   	   $result_query_get_profile = $wpdb->get_results($query_get_profile,ARRAY_A); 
		   	   foreach($result_query_get_profile as $casting_profile)
		   	   	$castingIDFromTable = $casting_profile["CastingID"];
		   }
	
		   $castID = isset($_GET['CastingID']) ? $_GET['CastingID'] : $castingIDFromTable; 
		   	    
		   $temp_arr = array();
		   $query_get ="SELECT * FROM ".$wpdb->prefix."agency_casting_register_customfields WHERE CastingID = ".$castID;
		   $result_query_get = $wpdb->get_results($query_get,ARRAY_A);
		   if($wpdb->num_rows > 0){
		   		foreach( $result_query_get as $result){
		   			if(!isset($_GET['CastingID'])){
		   				$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 OR ProfileCustomShowCastingManager = 1 AND ProfileCustomView != 2 OR ProfileCustomID = ".$result['Customfield_ID'] ." ORDER BY ProfileCustomOrder ASC";
		   			}else{
		   				$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 OR ProfileCustomShowCastingManager = 1 OR ProfileCustomID = ".$result['Customfield_ID'] ." ORDER BY ProfileCustomOrder ASC";
		   			}
			   	
			    $result_query_get2 = $wpdb->get_results($query_get,ARRAY_A);
			    foreach($result_query_get2 as $res)
			    	if(!in_array($res['ProfileCustomID'],$temp_arr)){

			    		 $current_user = wp_get_current_user();
						 $userLevel = get_user_meta($current_user->ID, 'wp_user_level', true); 
						 if($res['ProfileCustomView'] == 0){

						 	rb_get_admin_customfields_castingregister_func($res);
						 }else{

						 	if($userLevel > 0){
							 	rb_get_admin_customfields_castingregister_func($res);
							}else{
								return false;
							}
						 }			 
			    		
			    		$temp_arr[] = $res['ProfileCustomID'];
			    	}	

			   } 
		   }else{
		   		if(!isset($_GET['CastingID'])){
		   			$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 OR ProfileCustomShowCastingManager = 1 AND ProfileCustomView != 2 ORDER BY ProfileCustomOrder ASC";
		   		}else{
		   			$query_get ="SELECT * FROM ".table_agency_customfields." WHERE ProfileCustomShowCastingRegister = 1 OR ProfileCustomShowCastingManager = 1 ORDER BY ProfileCustomOrder ASC";
		   		}
		   		
			    $result_query_get2 = $wpdb->get_results($query_get,ARRAY_A);
			    foreach($result_query_get2 as $res){
			    	if(!in_array($res['ProfileCustomID'],$temp_arr)){
			    		 $current_user = wp_get_current_user();
						 $userLevel = get_user_meta($current_user->ID, 'wp_user_level', true); 
						 if($res['ProfileCustomView'] == 0){
						 	rb_get_admin_customfields_castingregister_func($res);
						 }else{
						 	if($userLevel > 0){
							 	rb_get_admin_customfields_castingregister_func($res);
							}else{
								return false;
							}
						 }			 
			    		
			    		$temp_arr[] = $res['ProfileCustomID'];
			    	}
			    }
			    		

			   
		   }
		      
	    }
?>