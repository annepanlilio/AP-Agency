<?php

/*
 * RBAgency_Extends Class
 *
 * These are shortcode and widget specific functions
 */

class RBAgency_Extends {

	// *************************************************************************************************** //

	/*
	 * Initialize
	 */
		public static function init(){

			// Assign shortcodes
			add_shortcode( 'profile_list', array("RBAgency_Extends","profile_list_shortcode") );
			add_shortcode( 'profile_search', array("RBAgency_Extends","profile_search_shortcode") );
			add_shortcode( 'category_list', array("RBAgency_Extends","category_list_shortcode") );

			// Assign Widgets
			add_action( 'widgets_init', create_function('', 'return register_widget("profile_search_widget_construct");') );
			add_action( 'widgets_init', create_function('', 'return register_widget("profile_featured_widget_construct");') );
		}

	// *************************************************************************************************** //
	// * SHORTCODES 
	// *************************************************************************************************** //



	/*
	 * Profile List
	 * Shortcode:  [profile_list]
	 */
		public static function get_activity($atts, $content = null){

			ob_start();





			$output_string  = ob_get_contents();
			ob_end_clean();

			return $output_string;
		}




	/*
	 * Profile List
	 * Shortcode: [profile_list]
	 */
		public static function profile_list_shortcode($atts, $content = null){

			ob_start();

			// Get Shortcode Attributes
			extract(shortcode_atts(array(
					"mode" => null
				), $atts));

			// Get Options
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

				// Return SQL string based on fields
				$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($atts);

				// Conduct Search
				echo RBAgency_Profile::search_results($search_sql_query, 0, false, $atts);

			} else {
				if(is_user_logged_in()){
					// TODO: Convert to class
					rb_get_profiletype();
				} else {
					echo "<div class='restricted'>\n";
					echo "	<h2>Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/casting-login/\">login or register</a>.</h2>";
					echo "</div><!-- #content -->\n";
				}
			}

			$output_string=ob_get_contents();;
			ob_end_clean();
			return $output_string;

		}


	/*
	 * Profile Search
	 * Shortcode: [profile_search type="simple|advanced"]
	 */

		public static function profile_search_shortcode($atts, $content = null){

			// Get Shortcode Attributes
			extract(shortcode_atts(array(
				"type" => "simple",
				"count" => 1
			), $atts));

			ob_start();

			$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_privacy  = $rb_agency_options_arr['rb_agency_option_privacy'];
				$rb_agency_option_formhide_advancedsearch_button = isset($rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button'])?$rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button']:0;


			if (  // Public
				($rb_agency_option_privacy == 0) ||
				//Admin users
				(is_user_logged_in() && current_user_can( 'edit_posts' )) ||
				//  Must be logged as "Client" to view model list and profile information
				($rb_agency_option_privacy == 3 && is_user_logged_in() && is_client_profiletype()  )
				) {

				// Select Type
				$isSearchPage = 1;
				if(!isset($_POST['form_mode'])){
					$type = get_query_var( 'type' );
					if(!in_array($type, array("search-advanced","search-basic")) ) { 
						echo RBAgency_Profile::search_form("", "", $type, $rb_agency_option_formhide_advancedsearch_button);
					}
				} elseif ($rb_agency_option_formhide_advancedsearch_button  == 0 ){
					if ( (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) ){
						echo "					<input type=\"button\" name=\"back_search rb-s1\" value=\"". __("Go Back to Advanced Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
					} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ){
						echo "					<input type=\"button\" name=\"back_search rb-s1\" value=\"". __("Go Back to Basic Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
					}
				}

			} else {

				echo "<div class='restricted'>\n";
				echo "	<h2>Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/casting-login/\">login or register</a>.</h2>";
				echo "</div><!-- #content -->\n";

			}

			$output_string=ob_get_contents();;
			ob_end_clean();
			return $output_string;

		}



	/*
	 * List Categories
	 * Shortcode: [category_list]
	 */
		public static function category_list_shortcode($atts, $content = null){

			ob_start();

			// Get Categories
			echo RBAgency_Profile::view_categories($atts);

			$output_string=ob_get_contents();;
			ob_end_clean();
			return $output_string;
		}


} // End class RBAgency_Extends


	// *************************************************************************************************** //
	// * WIDGETS 
	// *************************************************************************************************** //



class profile_search_widget_construct extends WP_Widget {

	// Setup
	function profile_search_widget_construct() {
		$widget_ops = array('classname' => 'rb_agency_widget', 'description' => __("Displays profile search fields", rb_agency_TEXTDOMAIN) );
		$this->WP_Widget('rb_agency_widget_showsearch', __("RB Agency : Search", rb_agency_TEXTDOMAIN), $widget_ops);
	}

	// What Displays
	function widget($args, $instance) {

		// Get Options
		$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_privacy  = $rb_agency_options_arr['rb_agency_option_privacy'];
			$rb_agency_option_formhide_advancedsearch_button = isset($rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button'])?$rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button']:0;

		// Get Settings from Widget
		extract($args, EXTR_SKIP);
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			$showlayout = $instance['showlayout'];
				if ( empty( $showlayout ) ) { $showlayout = "condensed"; };

		// Output Widget
		echo $before_widget;
		if (class_exists('RBAgency_Profile')) { 
			echo RBAgency_Profile::search_form("", "", 0, $showlayout, $rb_agency_option_formhide_advancedsearch_button);
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

} // RBAgency_Extends_Widget_Search



/*
 * Featured Profiles
 * Widget: View featured profiles, option to show one or several randomly
 */

	class profile_featured_widget_construct extends WP_Widget {
		// Setup
		function profile_featured_widget_construct() {
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
				$atts = array('profilesearch_layout' => $showlayout,"is_widget"=> true);
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

/*
 * Search Profiles
 * Widget: Search
 */

	class profile_featured_widget extends WP_Widget {

		// Setup
		function profile_featured_widget() {
			$widget_ops = array('classname' => 'rb_agency_widget', 'description' => __("Displays profile search fields", rb_agency_TEXTDOMAIN) );
			$this->WP_Widget('rb_agency_widget_showsearch', __("RB Agency : Featured", rb_agency_TEXTDOMAIN), $widget_ops);
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

	} // RBAgency_Extends_Widget_Profile_Featured