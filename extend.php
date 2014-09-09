<?php
// *************************************************************************************************** //
// Add Widgets

	/*
	 * Featured Profiles
	 * View featured profiles, option to show one or several randomly
	 */

	add_action('widgets_init', create_function('', 'return register_widget("rb_agency_widget_showpromoted");'));

		class rb_agency_widget_showpromoted extends WP_Widget {

			// Setup
			function rb_agency_widget_showpromoted() {
				$widget_ops = array('classname' => 'rb_agency_widget_showpromoted', 'description' => __("Displays promoted profiles", rb_agency_TEXTDOMAIN) );
				$this->WP_Widget('rb_agency_widget_showpromoted', __("RB Agency : Featured", rb_agency_TEXTDOMAIN), $widget_ops);
			}

			// What Displays
			function widget($args, $instance) {
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
				$count = $instance['count'];
					if ( empty( $count ) ) { $count = 1; };

				if (function_exists('rb_agency_profilefeatured')) { 
					$atts = array('count' => $count);
					rb_agency_profilefeatured($atts); 
				} else {
					echo "Invalid Function.";
				}
				echo $after_widget;
			}

			// Update
			function update($new_instance, $old_instance) {
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['count'] = strip_tags($new_instance['count']);
				return $instance;
			}

			// Form
			function form($instance) {
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$count = esc_attr($instance['count']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number Shown:'); ?> <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label></p>
				<?php 
			}

		} // Featured


	/*
	 * Profile Search
	 * Embed profile search form
	 */

	add_action('widgets_init', create_function('', 'return register_widget("rb_agency_widget_showsearch");'));

		class rb_agency_widget_showsearch extends WP_Widget {

			// Setup
			function rb_agency_widget_showsearch() {
				$widget_ops = array('classname' => 'rb_agency_widget_showsearch', 'description' => __("Displays profile search fields", rb_agency_TEXTDOMAIN) );
				$this->WP_Widget('rb_agency_widget_showsearch', __("RB Agency : Search", rb_agency_TEXTDOMAIN), $widget_ops);
			}

			// What Displays
			function widget($args, $instance) {
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
					if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
				$showlayout = $instance['showlayout'];
					if ( empty( $showlayout ) ) { $showlayout = "condensed"; };
						
				if (function_exists('rb_agency_profilesearch')) { 
					$atts = array('profilesearch_layout' => $showlayout);
					rb_agency_profilesearch($atts);
				} else {
					echo "Invalid Function";
				}
				echo $after_widget;
			}

			// Update
			function update($new_instance, $old_instance) {
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['showlayout'] = strip_tags($new_instance['showlayout']);
				return $instance;
			}

			// Form
			function form($instance) {
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				$showlayout = isset($instance['showlayout']) ? esc_attr($instance['showlayout']):"";
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
					<p><label for="<?php echo $this->get_field_id('showlayout'); ?>"><?php _e('Type:'); ?> <select id="<?php echo $this->get_field_id('showlayout'); ?>" name="<?php echo $this->get_field_name('showlayout'); ?>"><option value="advanced" <?php selected($showlayout, "advanced"); ?>>Advanced Search</option><option value="condensed" <?php selected($showlayout, "condensed"); ?>>Condensed Search</option></select></label></p>
				<?php 
			}

		} // class


// *************************************************************************************************** //
// Add Short Codes

	/*
	 * List Categories
	 */
		add_shortcode("category_list","rb_agency_shortcode_categorylist");
			function rb_agency_shortcode_categorylist($atts, $content = null){
				ob_start();
				rb_agency_categorylist($atts);
				$output_string=ob_get_contents();;
				ob_end_clean();
				return $output_string;
			}

	/*
	 * Profile Grid
	 */
		add_shortcode("profile_list","rb_agency_shortcode_profilelist");
			function rb_agency_shortcode_profilelist($atts, $content = null){
				$rb_agency_options_arr = get_option('rb_agency_options');
				// Can we show the pages?
				if((is_user_logged_in() && $rb_agency_options_arr['rb_agency_option_privacy']==2)||
				// Must be logged to view model list and profile information
				($rb_agency_options_arr['rb_agency_option_privacy']==1) ||
				// Model list public. Must be logged to view profile information
				($rb_agency_options_arr['rb_agency_option_privacy']==0) ||
				//admin users
				(is_user_logged_in() && current_user_can( 'edit_posts' )) ||
				// Model list public. Must be logged to view profile information
				($rb_agency_options_arr['rb_agency_option_privacy'] == 3 && is_user_logged_in() && is_client_profiletype()))
				{

				ob_start();
				rb_agency_profilelist($atts);
				$output_string=ob_get_contents();;
				ob_end_clean();
				return $output_string;
				}else{
					echo "	<div class='restricted'>\n";
					echo "<h2>Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login or register</a>.</h2>";
					echo "  </div><!-- #content -->\n";
				}

			}

	/*
	 * Profile Search
	 */
		add_shortcode("profile_search","rb_agency_shortcode_profilesearch");
			function rb_agency_shortcode_profilesearch($atts, $content = null){

				$rb_agency_options_arr = get_option('rb_agency_options');
				// Can we show the pages?
				if((is_user_logged_in() && $rb_agency_options_arr['rb_agency_option_privacy']==2)||
				// Must be logged to view model list and profile information
				($rb_agency_options_arr['rb_agency_option_privacy']==1) ||
				// Model list public. Must be logged to view profile information
				($rb_agency_options_arr['rb_agency_option_privacy']==0) ||
				// Model list public. Must be logged to view profile information
				($rb_agency_options_arr['rb_agency_option_privacy'] == 3 && is_user_logged_in() && is_client_profiletype()))
				{
					ob_start();
					rb_agency_profilesearch($atts);
					$output_string=ob_get_contents();;
					ob_end_clean();
					return $output_string;

				}else{
					echo "	<div class='restricted'>\n";
					echo "<h2>Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login or register</a>.</h2>";
					echo "  </div><!-- #content -->\n";
				}

			}



// *************************************************************************************************** //
// Tool Tips


	if( is_admin() ) {

		/*
		 * just not to get the tooltip error
		 */
		$rb_agency_options_arr = get_option('rb_agency_options');
		if($rb_agency_options_arr == ""){
				 $rb_agency_options_arr["rb_agency_options_showtooltip"] = 1;
				 update_option('rb_agency_options',$rb_agency_options_arr);
		}

		if( $rb_agency_options_arr != "" || is_array($rb_agency_options_arr)){
			$rb_agency_options_showtooltip = $rb_agency_options_arr["rb_agency_options_showtooltip"];

			if(!@in_array("rb_agency_options_showtooltip",$rb_agency_options_arr) && $rb_agency_options_showtooltip == 0){
				$rb_agency_options_arr["rb_agency_options_showtooltip"] = 1;
				update_option('rb_agency_options',$rb_agency_options_arr);
				wp_enqueue_style('wp-pointer');
				wp_enqueue_script('wp-pointer');
				function  add_js_code(){
					?>
					<script type="text/javascript">
					jQuery(document).ready( function($) {

					var options = {"content":"<h3>RB Agency Plugin</h3><p>Thanks for installing RB Plugin, we hope you find it useful.  Lets <a href=\'<?php echo admin_url("admin.php?page=rb_agency_settings&ConfigID=1"); ?>\'>check your settings</a> before we get started.</p>","position":{"edge":"left","align":"center"}};
					if ( ! options )
						return;
						options = $.extend( options, {
							close: function() {
							//to do
							}
						});
						<?php if(isset($_GET["page"])!="rb_agency_menu" && isset($_GET["page"]) !="rb_agency_settings") { ?>
						$('#toplevel_page_rb_agency_menu').pointer( options ).pointer("open");
						<?php } elseif(isset($_GET["page"])=="rb_agency_menu" && isset($_GET["page"]) !="rb_agency_settings") { ?>
						$('#toplevel_page_rb_agency_menu li a').each(function(){
							if($(this).text() == "Settings"){
								$(this).fadeOut().pointer( options ).pointer("open").fadeIn();
								$(this).css("background","#EAF2FA");
							}
						});
						<?php } ?>
					});
					</script>
					';
					<?php
				}
				add_action("admin_footer","add_js_code");
			}
		}
	}



?>