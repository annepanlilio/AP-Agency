<?php
/*
 * Configure
 */

	// Last Profile Viewed
	$profileviewed = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];


/*
 * Output
 */


echo "	<div id=\"rbsignin-register\" class=\"rbinteract\">\n";

			if ( $error ) {
				echo "<p class=\"error\">". $error ."</p>\n";
			}

echo "        <div id=\"rbsign-in\" class=\"inline-block\">\n";
echo "          <h1>". __("Members Sign in", RBAGENCY_interact_TEXTDOMAIN). "</h1>\n";

	// Which Login Form should we send the user to?
	if(function_exists('rb_agency_interact_menu')){
		$login_post_to = network_site_url("/"). "profile-login/";

echo "          <form name=\"loginform\" id=\"login\" action=\"". $login_post_to ."\" method=\"post\">\n";
echo " 			<input type=\"hidden\" name=\"redirect_to\" value=\"".network_site_url("/")."dashboard/\">\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"user-name\">". __("Username", RBAGENCY_interact_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". esc_html( isset($_POST['user-name'])?$_POST['user-name']:"", 1 ) ."\" id=\"user-name\" />\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"password\">". __("Password", RBAGENCY_interact_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", RBAGENCY_interact_TEXTDOMAIN). "?</a>\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <input type=\"checkbox\" name=\"remember-me\" value=\"forever\" /> ". __("Keep me signed in", RBAGENCY_interact_TEXTDOMAIN). "\n";
echo "            </div>\n";
echo "            <div class=\"field-row submit-row\">\n";
echo "              <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
echo "                <input name=\"lastviewed\" value=\"".$profileviewed."\" type=\"hidden\" />\n";
echo "              <input type=\"submit\" value=\"". __("Sign In", RBAGENCY_interact_TEXTDOMAIN). "\" /><br />\n";
echo "            </div>\n";
echo "          </form>\n";
	} else {
		// Show WP Login
		$args = array(
			'echo' => true,
			'redirect' => $profileviewed, 
			'form_id' => 'loginform',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in' => __( 'Log In' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
			'value_remember' => false );
		wp_login_form( $args );

	}

echo "        </div> <!-- rbsign-in -->\n";	
	
	
if(isset($rb_agencyinteract_option_switch_sidebar) && $rb_agencyinteract_option_switch_sidebar == 1){
			echo "        <div id=\"rbsign-up\" class=\"inline-block\">\n";
			if (( current_user_can("create_users") || $rb_agencyinteract_option_registerallow == 1)) {

				echo "          <div id=\"talent-register\" class=\"register\">\n";
				echo "            <h1>". __("Not a member", RBAGENCY_interact_TEXTDOMAIN). "?</h1>\n";
				echo "            <h3>". __("Talent", RBAGENCY_interact_TEXTDOMAIN). " - ". __("Register here", RBAGENCY_interact_TEXTDOMAIN). "</h3>\n";
				echo "            <ul>\n";
				echo "              <li>". __("Create your free profile page", RBAGENCY_interact_TEXTDOMAIN). "</li>\n";
				echo "              <li>". __("Apply to Auditions & Jobs", RBAGENCY_interact_TEXTDOMAIN). "</li>\n";
				echo "            </ul>\n";
				echo "              <input type=\"button\" onClick=\"location.href='". get_bloginfo("wpurl") ."/profile-register/'\" value=\"". __("Register Now", RBAGENCY_interact_TEXTDOMAIN). "\" />\n";
				echo "          </div> <!-- talent-register -->\n";
				echo "          <div class=\"clear line\"></div>\n";

						/*
						 * Casting Integratino
						 */
						/*
						if (function_exists('rb_agency_casting_menu')) {
								echo "          <div id=\"agent-register\" class=\"register\">\n";
								echo "            <h3>". __("Casting Agents & Producers", RBAGENCY_interact_TEXTDOMAIN). "</h3>\n";
								echo "            <ul>\n";
								echo "              <li>". __("List Auditions & Jobs free", RBAGENCY_interact_TEXTDOMAIN). "</li>\n";
								echo "              <li>". __("Contact People in the Talent Directory", RBAGENCY_interact_TEXTDOMAIN). "</li>\n";
								echo "              <li><a href=\"". get_bloginfo("wpurl") ."/casting-register/\" class=\"rb_button\">". __("Register as Agent / Producer", RBAGENCY_interact_TEXTDOMAIN). "</a></li>\n";
								echo "            </ul>\n";
								echo "          </div> <!-- talent-register -->\n";
						}*/

				}
			echo "        </div> <!-- rbsign-up -->\n";
}
else {
	echo "        <div id=\"rbsign-up\" class=\"inline-block\">\n";
	echo "          <div id=\"talent-register\" class=\"register\">\n";
	if ( dynamic_sidebar('rb-agency-interact-login-sidebar') ) :endif; 
	echo "          </div> <!-- talent-register -->\n";
	echo "          <div class=\"clear line\"></div>\n";
	echo "        </div> <!-- rbsign-up -->\n";

}
	

global $_viewcasting_login;
if($_viewcasting_login == true){
	include(RBAGENCY_PLUGIN_DIR .'theme/include-login-casting.php');
}

echo "      <div class=\"clear line\"></div>\n";
echo "      </div>\n";
	
?>