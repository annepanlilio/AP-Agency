<?php
/*
Template Name: 	Member Details
 * @name		Member Details
 * @type		PHP page
 * @desc		Member Details
*/

if (!headers_sent()) {
header("Cache-control: private"); //IE 6 Fix
}
global $wpdb;
global $current_user;
get_currentuserinfo();

// Change Title
add_filter('wp_title', 'rb_agencyinteractive_override_title', 10, 2);
	function rb_agencyinteractive_override_title(){
		return "Profile Pending";
	}
$profile_gallery = $wpdb->get_row($wpdb->prepare("SELECT ProfileGallery FROM ".table_agency_profile." WHERE ProfileUserLinked = %d",$current_user->ID));
		
/* Display Page ******************************************/ 


// Call Header
echo $rb_header = RBAgency_Common::rb_header();
	
	echo "	<div id=\"primary\" class=\"rb-agency-interact rb-agency-interact-overview\">\n";
	echo "  	<div id=\"rbcontent\">\n";
         if(is_user_logged_in()){
			echo "	<div id=\"profile-manage\" class=\"profile-overview\">\n";

					echo " <div class=\"manage-overview manage-content\">\n";
					echo sprintf(__("<h3>Thanks for joining %s!</h3>"), get_option('blogname'));
					echo "<br/>";
					echo "Your account is pending for approval. You will receive and email once your registration is approved.";
					echo " </div>\n";

			echo " </div>\n"; // .welcome
			  
		
		} else {

			// Show Login Form
			include("include-login.php");
		}
		
	echo "  </div><!-- #rbcontent -->\n";
	echo "</div><!-- #primary -->\n";

// Call Footer
echo $rb_footer = RBAgency_Common::rb_footer();
?>			