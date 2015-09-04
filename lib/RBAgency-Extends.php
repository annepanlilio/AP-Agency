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
			add_action( 'widgets_init', 'RBAgency_Extends_Widgets' );
				function RBAgency_Extends_Widgets() {
					register_widget( 'profile_search_widget' );
					register_widget( 'profile_featured_widget' );
				}

		}



	// *************************************************************************************************** //
	// * SHORTCODES 
	// *************************************************************************************************** //


	/*
	 * Profile List
	 * Shortcode: [profile_list]
	 */
		public static function profile_list_shortcode($atts, $content = null){

			ob_start();


			// Get Shortcode Attributes
			extract(shortcode_atts(array(
					"mode" => null,
					"show_profile_type_filter" => null,
					"show_media_category_filter" => null,
					"list_layout" => null,
				), $atts));

			if(empty($atts)){
				$atts[1] = 1;
			}
			
			// retrieve active profiles only
			$atts['isactive'] = 1;

			// Get Options
			$rb_agency_options_arr = get_option('rb_agency_options');

			$rb_agency_option_layoutprofilelistlayout = (int)$rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout'];

								
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
				
				global $_list_my_profiles,$wpdb;
				// Profile List Layout "Voiceover (no image)"
				
				//print_r($rb_agency_option_layoutprofilelistlayout);
				//echo 'xxxxx';
				
				// make shortcode as priority
			/* 	if($_list_my_profiles == 'voiceover'){
					$rb_agency_option_layoutprofilelistlayout = 1;
				}else{
					$rb_agency_option_layoutprofilelistlayout = 0;
				}
				 */
				
				if(isset($atts['list_layout'])){
					if($atts['list_layout']=='voiceover'){
						$_list_my_profiles ='voiceover';
					}elseif($atts['list_layout']=='lightbox'){
						$_list_my_profiles ='lightbox';
					}else{
						$_list_my_profiles ='';
					}
				}else{
					if($rb_agency_option_layoutprofilelistlayout == 1){
						$_list_my_profiles ='voiceover';
					}
				}
				
				//echo $rb_agency_option_layoutprofilelistlayout.$_list_my_profiles;
				//$rb_agency_options_arr = get_option('rb_agency_options');
				
				//print_r($rb_agency_options_arr);
				//$rb_agency_options_arr['rb_agency_option_layoutprofilelistlayout']
				
				 
				if($_list_my_profiles =='voiceover'){
					echo '
					<audio id="voice-over-player" controls style="visibility:hidden">
						<p>Your browser does not support the <code>audio</code> element.</p>
					</audio>
					';
				}
				
				if(isset($atts['type']) and is_numeric($atts['type'])){
					$atts['profiletype'] = $atts['type'];
				}elseif(isset($atts['type']) and !is_numeric($atts['type'])){
					//means the profile is multiple select like 1,3,5,6
					$_arrTypeAr = explode(',',$atts['type']);
					//bec the exist script have waiting single quote on start and end==.. 
					$_arrTypeTemp = str_replace(',','|',$atts['type']);
					unset($atts['type']);
					$atts['profileumltitype'] = $_arrTypeTemp;
				}
				
				
				if(isset($atts['show_profile_type_filter']) or $atts['show_profile_type_filter'] == true){
				
					$all_profileType = "SELECT * FROM " . table_agency_data_type;
					$results_profileType = $wpdb->get_results($all_profileType,ARRAY_A);
					
					$_allMedType = array();
					foreach($results_profileType as $key => $val){
						$_te = 'profile_type_'. $val['DataTypeID'];
						$_allMedType[$val['DataTypeID']] = array(
							'DataTypeTitle' => $val['DataTypeTitle'] ,
							'ID' => $_te
						);
					}
					if(is_array($_arrTypeAr)){
						$_allMedLink='';
						foreach($_arrTypeAr as $key){
							$_allMedLink .= '<li><a href="#" media-cate-id="'.$_allMedType[$key]['ID'].'">'.$_allMedType[$key]['DataTypeTitle'].'</a></li>';
						}
						
						echo '
						<ul class="media-categories-link">
							<li><a href="#" media-cate-id="all">All</a></li>
							'.$_allMedLink.'
						</ul>';
						
					}else{
						//not found. no need to display the filter profile
						echo '';
					}
					
					
					
					
					
				}
				
				
				if(isset($atts['show_media_category_filter']) or $atts['show_media_category_filter'] == true){
				
					$resultsP = $wpdb->get_results("SELECT med.*,dat.* FROM ".table_agency_data_media ." as med
					INNER JOIN ".table_agency_data_type." as dat ON med.MediaCategoryTitle = dat.DataTypeTitle",ARRAY_A);
					
					$_titleattr = '';
					$_allMedLink = '';
					foreach($resultsP as $key => $val){
						$_te = 'custom_mp3_'. $val['MediaCategoryID'];
						$_allMedLink .= '<li><a href="#" media-cate-id="'.$_te.'">'.$val['MediaCategoryTitle'].'</a></li>';
					}
					echo '
					<ul class="media-categories-link2">
						<li><a href="#" media-cate-id="all">All</a></li>
						<li><a href="#" media-cate-id="voicedemo">Voice Demo</a></li>
						'.$_allMedLink.'
					</ul>
					';
					
				}
				echo '
				<style>
					ul.media-categories-link, ul.media-categories-link2{widh:95%;}
					ul.media-categories-link li,ul.media-categories-link2 li{list-style:none;display: inline-block; margin: 8px 2px;}
						ul.media-categories-link li a,ul.media-categories-link2 li a{background: #eee;padding: 5px;}
						ul.media-categories-link li a.active,ul.media-categories-link2 li a.active {background: #bbb;color:#fff;}
						
					ul.media-categories-link2 li{list-style:none;display: inline-block; margin: 3px 1px;}
					ul.media-categories-link2 li a{font-size:0.8em;background: #eee;padding: 4px;}	
					
					  .audiojs { height: 22px; background: #555; margin: 10px 0 15px 0;
					    -webkit-box-shadow: none; -moz-box-shadow: none;  -o-box-shadow: none; box-shadow:none;}
				      .audiojs .play-pause { width: 25px; height: 20px; padding: 0px 8px 0px 0px;border-color:rgba(0,0,0,0.3);  }
				      .audiojs p { width: 25px; height: 20px; margin: -3px 0px 0px -1px; }
				      .audiojs .scrubber { rgba(50,50,50,0.8); width: 310px; height: 10px; margin: 5px; }
				      .audiojs .progress { height: 10px; width: 0px; background: #ccc;}
				      .audiojs .loaded { height: 10px; background: rgba(0,0,0,0.7); }
				      .audiojs .time { float: left; height: 25px; line-height: 25px;border-color:rgba(0,0,0,0.3); }
				      .audiojs .error-message { height: 24px;line-height: 24px; }
				      .audiojs .loading{background-position: -2px -1px}
				      /*background: rgba(0, 0, 0, 0) url("/wp-content/plugins/rb-agency/ext/audiojs/player-graphics.gif") no-repeat scroll -2px -1px;*/
				</style>
				
				';
				
				
				
				
				$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($atts);
				// Conduct Search
				$shortcode = true;
				
				echo RBAgency_Profile::search_results($search_sql_query, 0, false, $atts, $shortcode);

			} else {
				if(is_user_logged_in()){
					// TODO: Convert to class
					rb_get_profiletype();
				} else {
					echo "<div class='restricted'>\n";
						if ( class_exists("RBAgencyCasting") ) {
						echo "Page restricted. Only Admin & Casting Agent can view this page.<br />Please <a href=\"".get_bloginfo("url")."/casting-login/\">login</a> or <a href=\"".get_bloginfo("url")."/casting-register/\">register</a>.";
						} else {
						echo "Page restricted. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login</a> or <a href=\"".get_bloginfo("url")."/profile-register/\">register</a>.";
						}

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
				"type" => 0
			), $atts));

			ob_start();

			$rb_agency_options_arr = get_option('rb_agency_options');
				$rb_agency_option_privacy  = $rb_agency_options_arr['rb_agency_option_privacy'];
				$rb_agency_option_formhide_advancedsearch_button = isset($rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button'])?$rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button']:0;

			// Handle Legacy
			if ($type == "simple" || $type == "basic" ) {
				$type = 0;
			} elseif ($type == "advanced" || $type == "admin" ) {
				$type = 1;
			}

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
					echo RBAgency_Profile::search_form('', '', $type, 0);
				} elseif ($rb_agency_option_formhide_advancedsearch_button  == 0 ){
					if ( (isset($_POST['form_mode']) && $_POST['form_mode'] == "full" ) ){
						echo "	<input type=\"button\" name=\"back_search rb-s1\" value=\"". __("Go Back to Advanced Search", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javasctipt:window.location.href='".get_bloginfo("wpurl")."/search-advanced/'\"/>";
					} elseif ( (get_query_var("type") == "search-advanced")|| (isset($_POST['form_mode']) && $_POST['form_mode'] == "simple" ) ){
						echo "	<input type=\"button\" name=\"back_search rb-s1\" value=\"". __("Go Back to Basic Search", RBAGENCY_TEXTDOMAIN) . "\" class=\"button-primary\" onclick=\"javascript:window.location.href='".get_bloginfo("wpurl")."/search-basic/'\"/>";
					}
				}

			} else {

				echo "	<div class='restricted'>\n";
				if ( class_exists("RBAgencyCasting") ) {
						echo "Page restricted. Only Admin & Casting Agent can view this page. Please <a href=\"".get_bloginfo("url")."/casting-login/\">login</a> or <a href=\"".get_bloginfo("url")."/casting-register/\">register</a>.";
				} else {
						echo "Page restricted. Please <a href=\"".get_bloginfo("url")."/profile-login/\">login</a> or <a href=\"".get_bloginfo("url")."/profile-register/\">register</a>.";
				}
				echo "  </div><!-- #content -->\n";
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

}// End class RBAgency_Extends


	// *************************************************************************************************** //
	// * WIDGETS 
	// *************************************************************************************************** //




/*
 * Featured Profiles
 * Widget: View featured profiles, option to show one or several randomly
 */
class profile_featured_widget extends WP_Widget {


	/*
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'rbagency_widget_featured', // Base ID
			__( 'RB Agency: Featured', RBAGENCY_TEXTDOMAIN ), // Name
			array( 'description' => __( 'Displays featured profiles', RBAGENCY_TEXTDOMAIN ), 'classname' => 'rbagency_widget') // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		// Get Settings from Widget
		extract($args, EXTR_SKIP);
			$type = $instance['type'];
				if ( empty( $type ) ) {$type = 1; };
			$count = $instance['count'];
				if ( empty( $count ) ) {$count = 1; };

		echo $before_widget;
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		if (class_exists('RBAgency_Profile')) {
			$atts = array('type' => $type,"count"=> $count);
			echo RBAgency_Profile::view_featured($atts);
		} else {
			echo "Invalid Widget (Featured)";
		}
		echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$type = ! empty( $instance['type'] ) ? $instance['type'] : 0;
		$count = ! empty( $instance['count'] ) ? $instance['count'] : 1;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Type:'); ?></label>
			<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
				<option value="0" <?php selected($type, 0); ?>>All Profiles</option>
				<option value="1" <?php selected($type, 1); ?>>Featured Profiles Only</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Count:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';

		return $instance;
	}
}// class profile_featured_widget_construct


/*
 * Featured Profiles
 * Widget: View featured profiles, option to show one or several randomly
 */
class profile_search_widget extends WP_Widget {


	/*
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'rbagency_widget_searchform', // Base ID
			__( 'RB Agency: Search Form', RBAGENCY_TEXTDOMAIN ), // Name
			array( 'description' => __( 'Displays profile profiles', RBAGENCY_TEXTDOMAIN ), 'classname' => 'rbagency_widget') // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$type = ! empty( $instance['type'] ) ? $instance['type'] : 0;

		// Get Options
		$rb_agency_options_arr = get_option('rb_agency_options');
			$rb_agency_option_privacy  = $rb_agency_options_arr['rb_agency_option_privacy'];
			$rb_agency_option_formhide_advancedsearch_button = isset($rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button'])?$rb_agency_options_arr['rb_agency_option_formhide_advancedsearch_button']:0;

		if ( $rb_agency_option_privacy == 0 || is_user_logged_in()) {

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			// Show Search Form
			if (class_exists('RBAgency_Profile')) {
				echo RBAgency_Profile::search_form('', '', $type, 0);
			} else {
				echo "Invalid Function (Profile Search)";
			}
			echo $args['after_widget'];
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$type = ! empty( $instance['type'] ) ? $instance['type'] : 0;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Type:'); ?></label>
			<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
				<option value="0" <?php selected($type, 0); ?>>Simple</option>
				<option value="1" <?php selected($type, 1); ?>>Advanced</option>
			</select>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';

		return $instance;
	}
}// class profile_featured_widget_construct
