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
echo "          <h1>". __("Members Sign in", rb_agency_interact_TEXTDOMAIN). "</h1>\n";

	// Which Login Form should we send the user to?
	if(function_exists('rb_agency_interact_menu')){
		$login_post_to = network_site_url("/"). "profile-login/";

echo "          <form name=\"loginform\" id=\"login\" action=\"". $login_post_to ."\" method=\"post\">\n";
echo " 			<input type=\"hidden\" name=\"redirect_to\" value=\"".network_site_url("/")."dashboard/\">\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"user-name\">". __("Username", rb_agency_interact_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". wp_specialchars( isset($_POST['user-name'])?$_POST['user-name']:"", 1 ) ."\" id=\"user-name\" />\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <label for=\"password\">". __("Password", rb_agency_interact_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", rb_agency_interact_TEXTDOMAIN). "?</a>\n";
echo "            </div>\n";
echo "            <div class=\"field-row\">\n";
echo "              <input type=\"checkbox\" name=\"remember-me\" value=\"forever\" /> ". __("Keep me signed in", rb_agency_interact_TEXTDOMAIN). "\n";
echo "            </div>\n";
echo "            <div class=\"field-row submit-row\">\n";
echo "              <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
echo "                <input name=\"lastviewed\" value=\"".$profileviewed."\" type=\"hidden\" />\n";
echo "              <input type=\"submit\" value=\"". __("Sign In", rb_agency_interact_TEXTDOMAIN). "\" /><br />\n";
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

echo "      <div class=\"clear line\"></div>\n";
echo "      </div>\n";
?>