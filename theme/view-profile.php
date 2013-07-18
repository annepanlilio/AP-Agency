<?php 
session_start();
header("Cache-control: private"); //IE 6 Fix

// Get User Information
	global $user_ID; 
	global $current_user;
	get_currentuserinfo();
	$CurrentUser = $current_user->id;

// Get Profile
$profileURL = get_query_var('target'); //$_REQUEST["profile"];

$rb_agency_options_arr = get_option('rb_agency_options');
$rb_agency_option_agencyname = $rb_agency_options_arr['rb_agency_option_agencyname'];
$rb_agency_option_privacy = $rb_agency_options_arr['rb_agency_option_privacy'];
$rb_agency_option_unittype = $rb_agency_options_arr['rb_agency_option_unittype'];
$rb_agency_option_galleryorder = $rb_agency_options_arr['rb_agency_option_galleryorder'];
$rb_agency_option_showcontactpage = $rb_agency_options_arr['rb_agency_option_showcontactpage'];

	if ($rb_agency_option_galleryorder == 1) { $orderBy = "ProfileMediaID DESC, ProfileMediaPrimary DESC"; } else { $orderBy = "ProfileMediaID ASC, ProfileMediaPrimary DESC"; }
		$rb_agency_option_layoutprofile = (int)$rb_agency_options_arr['rb_agency_option_layoutprofile'];
		$rb_agency_option_gallerytype = (int)$rb_agency_options_arr['rb_agency_option_gallerytype'];
	if ($rb_agency_option_gallerytype == 1) {
		// Slimbox
		$reltype = "rel=\"lightbox-profile\"";
		$reltypev = "target=\"_blank\"";
	} elseif ($rb_agency_option_gallerytype == 2) {
		// PrettyBox
		$reltype = "rel=\"prettyPhoto\"";
		$reltypev = "rel=\"prettyPhoto\"";
	} else {
		// None
		$reltype = "rel=\"lightbox-profile\"";
		$reltypev = "target=\"_blank\"";
	}
$rb_agency_option_agency_urlcontact = $rb_agency_options_arr['rb_agency_option_agency_urlcontact'];
$rb_agency_option_profilenaming = $rb_agency_options_arr['rb_agency_option_profilenaming'];
$rb_agency_option_profilelist_sidebar = $rb_agency_options_arr['rb_agency_option_profilelist_sidebar'];

global $wpdb;

$query = "SELECT * FROM " . table_agency_profile . " WHERE ProfileGallery='$profileURL'";
$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
$count = mysql_num_rows($results);
while ($data = mysql_fetch_array($results)) {
	$ProfileID					=$data['ProfileID'];
	$ProfileUserLinked			=$data['ProfileUserLinked'];
	$ProfileGallery				=stripslashes($data['ProfileGallery']);
	$ProfileContactDisplay		=stripslashes($data['ProfileContactDisplay']);
	$ProfileContactNameFirst	=stripslashes($data['ProfileContactNameFirst']);
	$ProfileContactNameLast		=stripslashes($data['ProfileContactNameLast']);
		if ($rb_agency_option_profilenaming == 0) {
			$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
		} elseif ($rb_agency_option_profilenaming == 1) {
			$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
		} elseif ($rb_agency_option_profilenaming == 3) {
			$ProfileContactDisplay = "ID ". $ProfileID;
		} elseif ($rb_agency_option_profilenaming == 4) {
			$ProfileContactDisplay = $ProfileContactNameFirst;
		} elseif ($rb_agency_option_profilenaming == 5) {
			$ProfileContactDisplay = $ProfileContactNameLast;
		}
	$ProfileContactEmail		=stripslashes($data['ProfileContactEmail']);
	$ProfileType				=$data['ProfileType'];
	$ProfileContactWebsite		=stripslashes($data['ProfileContactWebsite']);
	$ProfileContactPhoneHome	=stripslashes($data['ProfileContactPhoneHome']);
	$ProfileContactPhoneCell	=stripslashes($data['ProfileContactPhoneCell']);
	$ProfileContactPhoneWork	=stripslashes($data['ProfileContactPhoneWork']);
	$ProfileGender    			=stripslashes($data['ProfileGender']);
	$ProfileDateBirth	    	=stripslashes($data['ProfileDateBirth']);
	$ProfileAge 				= rb_agency_get_age($ProfileDateBirth);
	$ProfileLocationCity		=stripslashes($data['ProfileLocationCity']);
	$ProfileLocationState		=stripslashes($data['ProfileLocationState']);
	$ProfileLocationZip			=stripslashes($data['ProfileLocationZip']);
	$ProfileLocationCountry		=stripslashes($data['ProfileLocationCountry']);
	$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
	$ProfileIsActive			=stripslashes($data['ProfileIsActive']); // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
	$ProfileStatHits			=stripslashes($data['ProfileStatHits']);
	$ProfileDateViewLast		=stripslashes($data['ProfileDateViewLast']);
	
	// Update Stats
	$updateStats = $wpdb->query("UPDATE ". table_agency_profile ." SET ProfileStatHits = ProfileStatHits + 1, ProfileDateViewLast = NOW() WHERE ProfileID = '". $ProfileID ."' LIMIT 1");
} 
	// Change Title
	if(!function_exists("rb_agency_override_title")){
	add_filter('wp_title', 'rb_agency_override_title', 10, 2);
		function rb_agency_override_title(){
			global $ProfileContactDisplay;
			return bloginfo('name') ." > ". $ProfileContactDisplay ."";
		}
	}
	/*
	// Remove All Known Scripts which effect
	if(!function_exists("deregister_scripts")){
	add_action( 'wp_print_scripts', 'deregister_scripts', 100 );
		function deregister_scripts() {
			//lightbox
			wp_deregister_script('woo-shortcodes');
			//jquery
			wp_deregister_script('woocommerce_plugins');
			wp_deregister_script('woocommerce');
			wp_deregister_script('fancybox');
			wp_deregister_script('jqueryui');
			wp_deregister_script('wc_price_slider');
			wp_deregister_script('widgetSlider');
			wp_deregister_script('woo-feedback');
			wp_deregister_script('prettyPhoto');
			wp_deregister_script('general');
		}
	}
	*/

	if(!function_exists("rb_agency_inserthead_profile")){
		add_action('wp_head', 'rb_agency_inserthead_profile');
			// Call Custom Code to put in header
			function rb_agency_inserthead_profile() {
				global $rb_agency_option_layoutprofile;

				$rb_agency_options_arr = get_option('rb_agency_options');
				
				if (isset($rb_agency_options_arr['rb_agency_option_layoutprofile'])) {
					$layouttype = (int)$rb_agency_options_arr['rb_agency_option_layoutprofile'];

					if ($layouttype == 99) {
						// Slimbox
						wp_enqueue_script( 'slimbox2', plugins_url('/js/slimbox2.js', __FILE__) );
						wp_register_style( 'slimbox2', plugins_url('/style/slimbox2.css', __FILE__) );
	        			wp_enqueue_style( 'slimbox2' );

						//wp_register_script( 'jquery', plugins_url( 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', __FILE__ ), false, '1.8.3' );

					} elseif ($layouttype == 99) {
						// Prettyphoto
						wp_register_style( 'prettyphoto', plugins_url('/style/prettyPhoto.css', __FILE__) );
	        			wp_enqueue_style( 'prettyphoto' );
						wp_enqueue_script( 'prettyphoto', plugins_url('/js/prettyPhoto.js', __FILE__) );


					} elseif ($layouttype == 0) {
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/image-resize.js', dirname(__FILE__)) );	
					} elseif ($layouttype == 2) {
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'js-scroller', plugins_url('/js/jquery.mCustomScrollbar.concat.min.js', dirname(__FILE__)) );
						
						wp_enqueue_script( 'jscroller', plugins_url('/js/scroller.js', dirname(__FILE__)), in_footer );
						
					// Slider Gallery			
					} elseif ($rb_agency_option_layoutprofile == "3") {
						?>
						<script>
						var $tab = jQuery.noConflict();
						$tab(window).load(function() {
								/*$tab(".tab").hide();
								$tab(".row-photos").show();
								$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
								$tab("#row-photos").removeClass("tab-inactive").addClass("tab-active");*/
								$tab(".maintab").click(function(){
									   var idx = this.id;
									   var elem = "." + idx;
									   var elem_id = "#" + idx;
									   if ((idx=="row-all")){
												$tab(".tab").hide();
												$tab(".tab").show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
												$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
										} else {
											  if(idx=="row-bookings"){
													
													var url = "<?php echo get_permalink(get_page_by_title('booking')); ?>";
													window.location = url;
													
											  } else {
													   
													  $tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
													  $tab(".tab").css({ opacity: 1.0 }).stop().animate({ opacity: 0.0 }, 2000).hide();
													  $tab(elem).show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
													  $tab(elem_id).removeClass("tab-inactive").addClass("tab-active");
											  }
									   }
								});
						});
						</script>
						<?php
					} elseif ($layouttype == 6) {
						wp_register_style( 'flexslider', plugins_url('/style/flexslider.css', dirname(__FILE__)) );
	        			wp_enqueue_style( 'flexslider' );
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'flexslider', plugins_url('/js/jquery.flexslider.js', dirname(__FILE__)) );						
						wp_enqueue_script( 'initflexslider', plugins_url('/js/initflexslider.js', dirname(__FILE__)), in_footer );
					} elseif ($layouttype == 7)  {
						wp_register_style( 'flexslider', plugins_url('/style/flexslider.css', dirname(__FILE__)) );
	        			wp_enqueue_style( 'flexslider' );
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'flexslider', plugins_url('/js/jquery.flexslider.js', dirname(__FILE__)) );						
						wp_enqueue_script( 'initflexslider', plugins_url('/js/initflexslider.js', dirname(__FILE__)), in_footer );
					} elseif ($layouttype == 8)  {
						wp_register_style( 'booklet', plugins_url('/style/booklet.css', dirname(__FILE__)) );
	        			wp_enqueue_style( 'booklet' );
						wp_enqueue_script( 'jquerys', plugins_url('/js/booklet-jquery.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/booklet-jquery-ui.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'jquery-easing', plugins_url('/js/booklet-jquery.easing.1.3.js', dirname(__FILE__)) );
						wp_enqueue_script( 'flexslider', plugins_url('/js/booklet.min.js', dirname(__FILE__)) );						
						wp_enqueue_script( 'initflexslider', plugins_url('/js/booklet.init.js', dirname(__FILE__)), in_footer );
					} elseif ($layouttype == 9)  {
						wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-1.9.1.min.js', dirname(__FILE__)) );
						wp_enqueue_script( 'js-scroller', plugins_url('/js/jquery.mCustomScrollbar.concat.min.js', dirname(__FILE__)) );
						
						wp_enqueue_script( 'jscroller', plugins_url('/js/scroller.js', dirname(__FILE__)), in_footer );
					}
		        } // end if
          } // function end
    }// if function exist(rb_agency_inserthead_profile)
   
// GET HEADER  
	get_header();

/*	$LayoutType = "";
	if ($rb_agency_option_profilelist_sidebar) {
		if ( ( $rb_agency_option_privacy >= 1 && is_user_logged_in() ) || ( $rb_agency_option_privacy > 1 && isset($_SESSION['SearchMuxHash']) ) || ($rb_agency_option_privacy == 0) ) { 
			echo "	<div id=\"profile-sidebar\">\n";
				$LayoutType = "profile";
				get_sidebar(); 
			echo "	</div>\n";
		 }
	}
*/
	
	echo "<div id=\"container\" "; if ($rb_agency_option_profilelist_sidebar==0) { echo "class=\"one-column\""; } echo">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	if ($count > 0) {
		if ( ( $rb_agency_option_privacy >= 1 && is_user_logged_in() ) || ( $rb_agency_option_privacy > 1 && isset($_SESSION['SearchMuxHash']) ) || ($rb_agency_option_privacy == 0) ) { 
			//if (isset($_SESSION['SearchMuxHash'])) { echo "Permission Granted"; }
			
		  // Ok, but whats the status of the profile?
		  if ( ($ProfileIsActive == 1) || ($ProfileUserLinked == $CurrentUser) || current_user_can('level_10') ) {
			include ("include-profile-layout". $rb_agency_option_layoutprofile .".php"); 	
		  } else {
			/*
			 * display this profile as long as it came
			 * from the page profilesecure else inactive if
                         * directly viewed.
			 */
			if(strpos($_SERVER['HTTP_REFERER'],'client-view') > 0){
				include ("include-profile-layout". $rb_agency_option_layoutprofile .".php"); 	
			} else {
				echo "". __("Inactive Profile", rb_agency_TEXTDOMAIN) ."\n";
			}
		  }
		} else {
			// hold last model requested as session so we can return them where we found them 
			$ProfileLastViewed = get_query_var('profile');
			$profileviewed = get_query_var('target');
			$_SESSION['ProfileLastViewed'] = $profileviewed;
			include("include-login.php"); 	
		}
	} else {
		// There is no record found.
			echo "". __("Invalid Profile", rb_agency_TEXTDOMAIN) ."\n";
	}
	echo "  </div>\n";
	echo "</div>\n";

	get_footer(); 
?>