
            <div id="logged_not">
              <div id="logged_form">
                <form name="loginform" id="login" action="<?php echo network_site_url("/"); ?>profile-login/" method="post">
                <h2>Account Login</h2>
                <p>You must be logged in to see the profile details.</p>
                <label for="login">Login:</label>
                <input type="text" name="user-name" value="" id="login" size="15"/><br />
                <label for="password">Password:</label>
                <input type="password" name="password" value="" id="password" size="10"/>
                <input name="action" value="log-in" type="hidden">
                <input name="lastviewed" value="<?php echo $profileviewed; ?>" type="hidden" />
                <input type="submit" name="submit" value="Login" id="enter" />
                <p><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword">Lost your password?</a></p>
                </form>
              </div>
            </div>