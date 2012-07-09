            <div id="logged_not">
              <div id="logged_form">
                <form name="loginform" id="login" action="<?php echo get_settings('siteurl'); ?>/wp-login.php" method="post">
                <h2>Account Login</h2>
                <p>You must be logged in to see the profile details.</p>
                <label for="login">Login:</label>
                <input type="text" name="log" value="" id="login" size="15"/><br />
                <label for="password">Password:</label>
                <input type="password" name="pwd" value="" id="password" size="10"/>
                <input type="submit" name="submit" value="Login" id="enter" />
                <p><a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword">Lost your password?</a></p>
                </form>
              </div>
            </div>