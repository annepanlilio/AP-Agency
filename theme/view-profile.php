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
		}
	$ProfileContactEmail		=stripslashes($data['ProfileContactEmail']);
	$ProfileType				=$data['ProfileType'];
	$ProfileContactWebsite		=stripslashes($data['ProfileContactWebsite']);
	$ProfileContactPhoneHome	=stripslashes($data['ProfileContactPhoneHome']);
	$ProfileContactPhoneCell	=stripslashes($data['ProfileContactPhoneCell']);
	$ProfileContactPhoneWork	=stripslashes($data['ProfileContactPhoneWork']);
	$ProfileContactParent		=stripslashes($data['ProfileContactParent']);
	$ProfileGender    			=stripslashes($data['ProfileGender']);
	$ProfileDateBirth	    	=stripslashes($data['ProfileDateBirth']);
	$ProfileAge 				= rb_agency_get_age($ProfileDateBirth);
	$ProfileLocationCity		=stripslashes($data['ProfileLocationCity']);
	$ProfileLocationState		=stripslashes($data['ProfileLocationState']);
	$ProfileLocationZip			=stripslashes($data['ProfileLocationZip']);
	$ProfileLocationCountry		=stripslashes($data['ProfileLocationCountry']);
	$ProfileStatEthnicity		=stripslashes($data['ProfileStatEthnicity']);
	$ProfileStatSkinColor		=stripslashes($data['ProfileStatSkinColor']);
	$ProfileStatEyeColor		=stripslashes($data['ProfileStatEyeColor']);
	$ProfileStatHairColor		=stripslashes($data['ProfileStatHairColor']);
	$ProfileStatHeight			=stripslashes($data['ProfileStatHeight']);
	$ProfileStatWeight			=stripslashes($data['ProfileStatWeight']);
	$ProfileStatBust	        =stripslashes($data['ProfileStatBust']);
	$ProfileStatWaist	    	=stripslashes($data['ProfileStatWaist']);
	$ProfileStatHip	        	=stripslashes($data['ProfileStatHip']);
	$ProfileStatShoe		    =stripslashes($data['ProfileStatShoe']);
	$ProfileStatDress			=stripslashes($data['ProfileStatDress']);
	$ProfileUnion				=stripslashes($data['ProfileUnion']);
	$ProfileExperience			=stripslashes($data['ProfileExperience']);
	$ProfileDateUpdated			=stripslashes($data['ProfileDateUpdated']);
	$ProfileIsActive			=stripslashes($data['ProfileIsActive']); // 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
	$ProfileStatHits			=stripslashes($data['ProfileStatHits']);
	$ProfileDateViewLast		=stripslashes($data['ProfileDateViewLast']);
	
	// Update Stats
	$updateStats = $wpdb->query("UPDATE ". table_agency_profile ." SET ProfileStatHits = ProfileStatHits + 1, ProfileDateViewLast = NOW() WHERE ProfileID = '". $ProfileID ."' LIMIT 1");
 
	// Change Title
	add_filter('wp_title', 'rb_agency_override_title', 10, 2);
		function rb_agency_override_title(){
			global $ProfileContactDisplay;
			return bloginfo('name') ." > ". $ProfileContactDisplay ."";
		}
	
	// Remove All Known Scripts which effect
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

	add_action('wp_head', 'rb_agency_inserthead_profile');
		// Call Custom Code to put in header
		function rb_agency_inserthead_profile() {
			global $rb_agency_option_layoutprofile;
			
			if ($rb_agency_option_layoutprofile == "2") {
				echo "<script type=\"text/javascript\" src=\"". rb_agency_BASEDIR ."js/jquery-ui.js\"></script>\n";
				echo "<script type=\"text/javascript\" src=\"". rb_agency_BASEDIR ."js/scroller.js\"></script>\n";
				?>
				<script>
				var $rb = jQuery.noConflict();
				$rb(window).load(function() {
					mCustomScrollbars();
				});
				function mCustomScrollbars(){
					$rb("#photos").mCustomScrollbar("horizontal",1000,"easeOutCirc",1,"fixed","yes","yes",20); 
				}
				$rb.fx.prototype.cur = function(){
					if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
					  return this.elem[ this.prop ];
					}
					var r = parseFloat( jQuery.css( this.elem, this.prop ) );
					return typeof r == 'undefined' ? 0 : r;
				}
				function LoadNewContent(id,file){
					$rb("#"+id+" .customScrollBox .content").load(file,function(){
						mCustomScrollbars();
					});
				}
				</script>
				<?
			// Slider Gallery			
			} elseif ($rb_agency_option_layoutprofile == "3") {
				?>
				<script>
				var $tab = jQuery.noConflict();
				$tab(window).load(function() {
						$tab(".tab").hide();
						$tab(".row-photos").show();
						$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
						$tab("#row-photos").removeClass("tab-inactive").addClass("tab-active");
						$tab(".maintab").click(function(){
							   var idx = this.id;
							   var elem = "." + idx;
							   var elem_id = "#" + idx;
							   if ((idx=="row-all") || (idx=="row-booking")){
										$tab(".tab").hide("slow");
										$tab(".tab").show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
										$tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
										$tab(elem_id).removeClass("tab-inactive").addClass("tab-active");
								} else {
									  $tab(".tab-active").removeClass("tab-active").addClass("tab-inactive");
									  $tab(".tab").css({ opacity: 1.0 }).stop().animate({ opacity: 0.0 }, 2000).hide();
									  $tab(elem).show().css({ opacity: 0.0 }).stop().animate({ opacity: 1.0 }, 2000);
									  $tab(elem_id).removeClass("tab-inactive").addClass("tab-active");
							   }
						});
				});
				</script>
				<?php
			}
			
		}
}
   
// GET HEADER  
	get_header();

	$LayoutType = "";
	if ($rb_agency_option_profilelist_sidebar) {
		echo "	<div id=\"profile-sidebar\">\n";
			$LayoutType = "profile";
			get_sidebar(); 
		echo "	</div>\n";
	}
	
	echo "<div id=\"container\" class=\"one-column\">\n";
	echo "    <div id=\"content\" role=\"main\" class=\"transparent\">\n";
	if ($count > 0) {
		if ( ( $rb_agency_option_privacy > 1 && is_user_logged_in() ) || ( $rb_agency_option_privacy > 1 && isset($_SESSION['SearchMuxHash']) ) || ($rb_agency_option_privacy == 0) ) { 
			//if (isset($_SESSION['SearchMuxHash'])) { echo "Permission Granted"; }
			
		  // Ok, but whats the status of the profile?
		  if ( ($ProfileIsActive == 1) || ($ProfileUserLinked == $CurrentUser) || current_user_can('level_10') ) {
			include ("include-profile-layout". $rb_agency_option_layoutprofile .".php"); 	
		  } else {
			echo "". __("Inactive Profile", rb_agency_TEXTDOMAIN) ."\n";
		  }
		} else {
			// hold last model requested as session so we can return them where we found them 
			$ProfileLastViewed = get_query_var('profile');
			$_SESSION['ProfileLastViewed'] = $ProfileLastViewed;
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