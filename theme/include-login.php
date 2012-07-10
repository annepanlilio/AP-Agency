		<div class="login-panel">
            
			
			
			<div id="logged_not">
				<div id="logged_form">
					<form name="loginform" id="login" action="<?php echo get_settings('siteurl'); ?>/wp-login.php" method="post">
						<h2>Account Login</h2>
						<p>You must be logged in to see the profile details.</p>
						
						<label for="login">Login:</label><br>
						<input type="text" name="log" value="" id="login" size="15"/><br>
						
						<label for="password">Password:</label><br>
						<input type="password" name="pwd" value="" id="password" size="10"/>
						
						
						<a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword">Lost your password?</a> <input type="submit" class="button orange" name="submit" value="Login" id="enter" style="float:right;"/>
						
					
					
					</form>
				</div>
			</div>
            
		
		
		
		</div>
		
		
		
		
		
