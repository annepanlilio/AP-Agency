<?php
/*
 * RBAgency_Admin Class
 *
 * These are administrative specific functions
 */
class RBAgency_Admin {
	// *************************************************************************************************** //
	/*
	 * Initialize
	 */
		public static function init(){
			/*
			 * Admin Related
			 */
			add_filter( 'option_page_capability_rb-agency-settings-group',array('RBAgency_Admin', 'rb_agency_settings_group_capability' ));
			$editor = get_role('editor');
			$editor->add_cap('rb_agency_settings_group_options');
			$admin = get_role('administrator');
			$admin->add_cap('rb_agency_settings_group_options');
			if ( is_admin() ){
				// Add Primary Menus
				add_action('admin_menu', array('RBAgency_Admin', 'menu_admin'));
				// Add  Menu Item to Settings Page
				add_action('admin_menu', array('RBAgency_Admin', 'menu_addlinkto_settings'));
				// Add Link to Plugin Page
				add_action('plugin_action_links', array('RBAgency_Admin', 'menu_addlinkto_plugin'), 10, 2);
				// Add Settings Page to Header Bar
				/// TODO: BROKEN add_action('after_setup_theme', array('RBAgency_Admin', 'menu_addlinkto_adminbar');
				// Add Styles to Admin Head Section 
				add_action( 'admin_head', array('RBAgency_Admin', 'admin_head_style') );
				// Add Scripts to Admin Head Section 
				add_action( 'admin_head', array('RBAgency_Admin', 'admin_head_scripts'), 0 );
				// Load Shortcode Generator
				add_action('admin_menu', array('RBAgency_Admin', 'shortcode_display_generator'));
				// Add Notification to Plugins Page
				//add_action('after_plugin_row_rb-agency/rb-agency.php', array('RBAgency_Admin', 'plugin_row'));
				// Flash rules to apply RB Agency newrules
				rbflush_rules();
			}
		}
		public static function rb_agency_settings_group_capability($cap){
			return 'rb_agency_settings_group_options';
		}
	// *************************************************************************************************** //
	/*
	 * Administrative Menu
	 * Create the admin menu items
	 */
		//Create Admin Menu
		public static function menu_admin(){
			add_menu_page( 
				__("Agency", RBAGENCY_TEXTDOMAIN), __("Agency", RBAGENCY_TEXTDOMAIN), 
				'edit_posts', "rb_agency_menu", array('RBAgency_Admin', 'menu_dashboard'), "div"
				);
				add_submenu_page("rb_agency_menu", __("Overview", RBAGENCY_TEXTDOMAIN), __("Overview", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_menu", array('RBAgency_Admin', 'menu_dashboard'));
				add_submenu_page("rb_agency_menu", __("Manage Profiles", RBAGENCY_TEXTDOMAIN), __("Manage Profiles", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_profiles", array('RBAgency_Admin', 'menu_profiles'));
			// RB Agency Interact 
			if(function_exists('rb_agency_interact_menu')){
				// Menu Approve
				add_submenu_page("rb_agency_menu", __("Approve Pending Profiles", RBAGENCY_TEXTDOMAIN), __("Approve Profiles", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_interact_approvemembers", array('RBAgency_Admin', 'menu_interact_approvemembers'));
			}
				add_submenu_page("rb_agency_menu", __("Search &amp; Send Profiles", RBAGENCY_TEXTDOMAIN), __("Search Profiles", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_search", array('RBAgency_Admin', 'menu_search'));
				add_submenu_page("rb_agency_menu", __("Saved Searches", RBAGENCY_TEXTDOMAIN), __("Saved Searches", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_searchsaved", array('RBAgency_Admin', 'menu_searchsaved'));
			// RB Agency Casting
			if(function_exists('rb_agency_casting_menu')){
				// Manage casting profiles
				add_submenu_page("rb_agency_menu", __("Manage Clients", RBAGENCY_TEXTDOMAIN), __("Manage Clients", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_casting_manage", array('RBAgency_Admin', 'menu_casting_manageagents'));
				// saved search for casting
				add_submenu_page("rb_agency_menu", __("Approve Pending Clients", RBAGENCY_TEXTDOMAIN), __("Approve Clients", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_casting_approveclients", array('RBAgency_Admin', 'menu_casting_approveclients'));
				// saved search for casting
				add_submenu_page("rb_agency_menu", __("Client Activity", RBAGENCY_casting_TEXTDOMAIN), __("Client Searches", RBAGENCY_casting_TEXTDOMAIN), 'edit_posts',"rb_agency_casting_searchsaved", array('RBAgency_Admin', 'menu_casting_searchsaved'));
				add_submenu_page("rb_agency_menu", __("Client Types", RBAGENCY_casting_TEXTDOMAIN), __("Client Types", RBAGENCY_casting_TEXTDOMAIN), 'edit_posts',"rb_agency_casting_types", array('RBAgency_Admin', 'menu_casting_types'));
				// job postings
				add_submenu_page("rb_agency_menu", __("Job Types", RBAGENCY_casting_TEXTDOMAIN), __("Job Types", RBAGENCY_casting_TEXTDOMAIN), 'edit_posts',"rb_agency_casting_jobpostings", array('RBAgency_Admin', 'menu_casting_jobpostings'));
				add_submenu_page("rb_agency_menu", __("Casting Jobs", RBAGENCY_TEXTDOMAIN), __("Casting Jobs", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_castingjobs", array('RBAgency_Admin', 'menu_castingjob'));
				add_submenu_page("rb_agency_menu", __("Casting Calendar", RBAGENCY_TEXTDOMAIN), __("Casting Calendar", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_castingcalendar", array('RBAgency_Admin', 'menu_castingcalendar'));
			}
				add_submenu_page("rb_agency_menu", __("Tools &amp; Reports", RBAGENCY_TEXTDOMAIN), __("Tools &amp; Reports", RBAGENCY_TEXTDOMAIN), 'edit_posts',"rb_agency_reports", array('RBAgency_Admin', 'menu_reports'));
				add_submenu_page("rb_agency_menu", __("Edit Settings", RBAGENCY_TEXTDOMAIN), __("Settings", RBAGENCY_TEXTDOMAIN), 'rb_agency_settings_group_options',"rb_agency_settings", array('RBAgency_Admin', 'menu_settings'));
		}
	/*
	 * Admin Pages
	 */
		//Core
		public static function menu_dashboard(){
			include_once(RBAGENCY_PLUGIN_DIR .'view/admin-overview.php');
		}
		public static function menu_profiles(){
			include_once(RBAGENCY_PLUGIN_DIR .'view/admin-profile.php');
		}
		public static function menu_search(){
			include_once(RBAGENCY_PLUGIN_DIR .'view/admin-search.php');
		}
		public static function menu_searchsaved(){
			include_once(RBAGENCY_PLUGIN_DIR .'view/admin-searchsaved.php');
		}
		public static function menu_reports(){
			include_once(RBAGENCY_PLUGIN_DIR .'view/admin-reports.php');
		}
		public static function menu_settings(){
			include_once(RBAGENCY_PLUGIN_DIR .'view/admin-settings.php');
		}
		public static function menu_castingjob(){
			rb_agency_casting_jobs();
		}
		public static function menu_casting_manageagents(){
				include_once(RBAGENCY_casting_PLUGIN_DIR .'view/admin-casting-agent.php');
		}
		// Interact
		public static function menu_interact_approvemembers(){
			rb_agency_interact_approvemembers();
		}
		// Casting
		public static function menu_casting_approveclients(){
			rb_agency_casting_approveclients();
		}
		public static function menu_casting_searchsaved(){
			rb_agency_casting_searchsaved();
		}
		public static function menu_casting_types(){
			rb_agency_casting_types();
		}
		public static function menu_casting_jobpostings(){
			rb_agency_casting_jobpostings();
		}
		public static function menu_castingcalendar(){
			rb_agency_casting_calendar();
		}
	/*
	 * Settings Page
	 * Add links and information to the plugins page
	 */
		// Add Menu Item to Settings Dropdown
		public static function menu_addlinkto_settings() {
			// Check Permissions, Allow Editors and above, not just any low user level 
			if ( !current_user_can('edit_posts') )
				return;
			add_options_page( __("RB Agency", RBAGENCY_TEXTDOMAIN), __("RB Agency", RBAGENCY_TEXTDOMAIN), "edit_posts", "rb_agency_settings", array('RBAgency_Admin', 'menu_settings'));
		}
		// Add Link to Settings Page
		public static function menu_addlinkto_plugin( $links, $file ) {
			// check towp_toolbar make sure we are on the correct plugin
			if ($file == RBAGENCY_PLUGIN_NAME .'/'. RBAGENCY_PLUGIN_NAME .'.php' ) {
				// Create Link for Settings Page
				$settings_link = '<a href="' . admin_url("admin.php?page=rb_agency_settings") . '">'.__('Settings',RBAGENCY_TEXTDOMAIN).'</a>';
				// Add link to List
				array_unshift($links, $settings_link);
			}
			return $links;
		}
		// Add Link to Admin Toolbar
		public static function menu_addlinkto_adminbar() {
			if (current_user_can('level_10') && !is_admin()) {
				function menu_addlinkto_adminbar_menu($wp_toolbar) {
					$wp_toolbar->add_node(array(
						'id' => 'rb-agency-toolbar-settings',
						'title' => __('RB Agency Settings',RBAGENCY_TEXTDOMAIN),
						'href' =>  get_admin_url().'admin.php?page=rb_agency_settings',
						'meta' => array('target' => 'rb-agency-toolbar-settings')
					));
				}
				add_action('admin_bar_menu', 'menu_addlinkto_adminbar_menu', 999);
			}
		}
	/*
	 * Admin Links
	 * Embed links for admins to edit easily.
	 */
		// Add Admin Link to Edit Profile from Profile View
		public static function link_profile_edit($profileID){
			if (current_user_can('level_10') && !is_admin()) {
				function prepare_tool($wp_toolbar){
					$arr = array(
						'id' => 'rb-agency-edit-profile',
						'title' => __('Edit this Profile',RBAGENCY_TEXTDOMAIN),
						'href' => admin_url('admin.php?page=rb_agency_profiles&action=editRecord&ProfileID='.get_current_viewingID()),
						'meta' => array('target' => 'rb-agency-edit-profile')
					);
					$wp_toolbar->add_node($arr);
				}
				add_action('admin_bar_menu',"prepare_tool",999,2);
			}
		}
		// TODO: Refactor
		// Admin Toolbar
		/*
		function rb_agency_disableAdminToolbar() {
			add_filter('show_admin_bar', '__return_false');
		}
		$rb_agencyinteract_options_arr = get_option('rb_agencyinteract_options');
			$rb_agencyinteract_option_profilemanage_toolbar =isset($rb_agencyinteract_options_arr["rb_agencyinteract_option_profilemanage_toolbar"]) ? (int)$rb_agencyinteract_options_arr["rb_agencyinteract_option_profilemanage_toolbar"] : 0;
		if($rb_agencyinteract_option_profilemanage_toolbar==1) {
			rb_agency_disableAdminToolbar(); 
		}
		 */
		// TODO: Review
		// Edit Text/Label/Header
		/*
		//add_filter( 'gettext', 'rb_agency_editTitleText', 10, 3 );
		function rb_agency_editTitleText($string){
			return "<span>".$string."<a href=\"javascript:;\" style=\"font-size:11px;color:blue;text-decoration:underline;\">Edit</a></span>";
		}
		 */
	// *************************************************************************************************** //
	/*
	 * Define Admin Interface
	 */
		// Get Admin Styles
		public static function admin_head_style() {
			// Ensure we are in the admin section of wordpress
			if( is_admin() ) {
				// Get Custom Admin Styles
				wp_register_style( 'rbagencyadmin', RBAGENCY_PLUGIN_URL .'assets/css/admin.css' );
				wp_enqueue_style( 'rbagencyadmin' );
				wp_register_style( 'rbagency-formstyle', RBAGENCY_PLUGIN_URL .'assets/css/forms.css' );
				wp_enqueue_style( 'rbagency-formstyle' );
				wp_register_style( 'rbagency-datepicker', RBAGENCY_PLUGIN_URL .'assets/css/jquery-ui/jquery-ui.css' );
				wp_enqueue_style( 'rbagency-datepicker' );
				wp_register_style( 'rbagency-datepicker-theme', RBAGENCY_PLUGIN_URL .'assets/css/jquery-ui/jquery-ui.theme.min.css' );
				wp_enqueue_style( 'rbagency-datepicker-theme' );
			}
		}
		// Get Scripts
		public static function admin_head_scripts() {
			// Ensure we are in the admin section of wordpress
			if( is_admin() ) {
				// Load Jquery if not registered
				if ( ! wp_script_is( 'jquery', 'registered' ) )
					wp_register_script( 'jquery', plugins_url( 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', __FILE__ ), false, '1.8.3' );
					wp_enqueue_script( 'jquery-ui-core' );
					 wp_enqueue_script( 'jquery-ui-widget' );
					 wp_enqueue_script( 'jquery-ui-mouse' );
					 wp_enqueue_script( 'jquery-ui-accordion' );
					 wp_enqueue_script( 'jquery-ui-autocomplete' );
					 wp_enqueue_script( 'jquery-ui-slider' );
					 wp_enqueue_script( 'jquery-ui-tabs' );
					 wp_enqueue_script( 'jquery-ui-sortable' );
					 wp_enqueue_script( 'jquery-ui-draggable' );
					 wp_enqueue_script( 'jquery-ui-droppable' );
					 wp_enqueue_script( 'jquery-ui-datepicker' );
					 wp_enqueue_script( 'jquery-ui-resize' );
					 wp_enqueue_script( 'jquery-ui-dialog' );
					 wp_enqueue_script( 'jquery-ui-button' );
				//TODO: Refactor
				?>
				<script type="text/javascript">
				jQuery(function(){
					jQuery(".rb-datepicker").each(function(){
						jQuery(this).datepicker({dateFormat: "yy-mm-dd" }).val(jQuery(this).val());
					})
					jQuery( "input[id=rb_datepicker_from],input[id=rb_datepicker_to]").datepicker({
						dateFormat: "yy-mm-dd",
						defaultDate: "+1w",
						changeMonth: true,
						numberOfMonths: 3,
						onSelect: function( selectedDate ) {
							if(this.id == 'input[id=rb_datepicker_from]'){
								var dateMin = jQuery('input[id=rb_datepicker_from]').datepicker("getDate");
								var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); // Min Date = Selected + 1d
								var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
								jQuery('input[id=rb_datepicker_from]').datepicker("option","minDate",rMin);
								jQuery('input[id=rb_datepicker_to]').datepicker("option","maxDate",rMax);            
							}
						}
					});
					jQuery( "input[id=rb_datepicker_from_bd],input[id=rb_datepicker_to_bd]").datepicker({
						dateFormat: "yy-mm-dd"
					});
				});
				</script>
				<?php
				// Load custom fields javascript
				//wp_enqueue_script( 'customfields', RBAGENCY_PLUGIN_URL .'assets/js/js-customfields.js' );
			}
		}
	/*
	 * Shortcode Generator
	 * Add Custom Meta Box to Posts / Pages
	 */
		public static function shortcode_display_generator(){
			add_meta_box( 'rb_agency_sectionid', __( 'Insert Profile Grid', RBAGENCY_TEXTDOMAIN), array('RBAgency_Admin', 'shortcode_display_generator_form'), 'post', 'advanced' );
			add_meta_box( 'rb_agency_sectionid', __( 'Insert Profile Grid', RBAGENCY_TEXTDOMAIN), array('RBAgency_Admin', 'shortcode_display_generator_form'), 'page', 'advanced' );
		}
		public static function shortcode_display_generator_form(){
			 global $wpdb;
			// Use nonce for verification
			?>
            <input type="hidden" name="rb_agency_noncename" id="rb_agency_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) )?>" />
			<div class='submitbox' id='add_ticket_box'>
           <div class='rb_meta_box'>
            <script type="text/javascript">
            jQuery(document).ready(function($){
                $("#insertProfile_btn").on('click',function(){
					str='';
					gender=$('#rb_agency_gender').val();
					if(gender!=''&& gender!='')
					str+=' gender="'+gender+'"';
					age_start=$('#rb_agency_age_start').val();
					if(age_start!=''&& age_start!='')
					str+=' age_start="'+age_start+'"';
					age_stop=$('#rb_agency_age_stop').val();
					if(age_stop!=''&& age_stop!='')
					str+=' age_stop="'+age_stop+'"';
					type=$('#rb_agency_type').val();
					if(type!='')
					str+=' type="'+type+'"';
					send_to_editor('[profile_list'+str+']');
				});
                
				$("#insertSearchForm_btn").on('click',function(){
				    
				    var search_profile_type = $("#rb_search_profile_type").val();
                    var search_form_type = $("#rb_search_form_type").val();
                    var search_form_options = "profile_type='"+search_profile_type+"' type='"+search_form_type+"' location='1'";
					send_to_editor('[profile_search '+search_form_options+']');
                    
				});
                
                });
			</script>
			
			<table>
			<tr><td><?php echo __("Type:",RBAGENCY_TEXTDOMAIN)?></td><td>
            <select id="rb_agency_type" name="rb_agency_type">
			<?php	global $wpdb;
					$profileDataTypes = $wpdb->get_results("SELECT * FROM ". table_agency_data_type ."",ARRAY_A);
					echo "<option value=0>". __("Any", RBAGENCY_TEXTDOMAIN) ."</option>";
					foreach( $profileDataTypes as $dataType) {
						if (isset($_SESSION['ProfileType'])) {
							if ($dataType["DataTypeID"] ==  $ProfileType) {$selectedvalue = " selected"; } else {$selectedvalue = ""; }
						} else {$selectedvalue = ""; }
						echo "<option value=". $dataType["DataTypeID"] ."".$selectedvalue.">". $dataType["DataTypeTitle"] ." ". __("Only", RBAGENCY_TEXTDOMAIN) ."</option>";
					}
            ?>
			</select>
            </td>
            </tr>
			<tr><td><?php echo __("Starting Age", RBAGENCY_TEXTDOMAIN) ?>:</td>
            <td><input type="text" id="rb_agency_age_start" name="rb_agency_age_start" value="18" /></td>
            </tr>
			<tr><td><?php echo __("Ending Age", RBAGENCY_TEXTDOMAIN) ?>:</td>
            <td><input type="text" id="rb_agency_age_stop" name="rb_agency_age_stop" value="99" /></td>
            </tr>
			<tr><td><?php echo __("Gender", RBAGENCY_TEXTDOMAIN) ?>:</td><td>
			<select id="rb_agency_gender" name="rb_agency_gender">
			<?php
            $query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
				echo "<option value=>".__("All Gender",RBAGENCY_TEXTDOMAIN)."</option>";
				$queryShowGender = $wpdb->get_results($query,ARRAY_A);
				foreach($queryShowGender as $dataShowGender){
					echo "<option value=".$dataShowGender["GenderID"]." >".__( $dataShowGender["GenderTitle"], RBAGENCY_TEXTDOMAIN)."</option>";
				}
            ?>
			</select>
			</td></tr>
			</table>
			<p><input type="button" id="insertProfile_btn" value="<?php echo __("Insert Profile Grid", RBAGENCY_TEXTDOMAIN)?>" /></p>
			
            </div>
            <div class='rb_meta_box'>
            
            <div class='rb_meta_box_title'>Search Form Options</div>
            
            <div class='rb-form-control'>
            <label>Profile Type :</label>
            <select id='rb_search_profile_type'>
			 <?php	
					$profileDataTypes = $wpdb->get_results("SELECT * FROM ". table_agency_data_type ."",ARRAY_A);
					echo "<option value='0'>". __("All", RBAGENCY_TEXTDOMAIN) ."</option>";
					foreach( $profileDataTypes as $dataType) {
						echo "<option value='". $dataType["DataTypeID"] ."'>". $dataType["DataTypeTitle"] ."</option>";
					}
                ?>
			</select>
            </div>
            <div class='rb-form-control'>
            <label>Search Form Type :</label>
                <select id='rb_search_form_type'>
                    <option value='basic'><?php echo  __("Basic", RBAGENCY_TEXTDOMAIN) ?></option>
                    <option value='advanced'><?php echo __("Advanced", RBAGENCY_TEXTDOMAIN)?></option>
                </select>
            </div>
            <p><input id="insertSearchForm_btn" type="button" value="<?php echo __("Insert Search Form", RBAGENCY_TEXTDOMAIN)?>" /></p>
            </div>
            </div>
            <?php
		}
// TODO: Include Dasboard in Class
}
		/*
		 * Customize WordPress Dashboard
		 */
			// Pull User Identified Settings/Options 
			$rb_agency_options_arr = get_option('rb_agency_options');
			// Can we show the ads? Or keep it clean?
			$rb_agency_option_advertise = isset($rb_agency_options_arr['rb_agency_option_advertise']) ? $rb_agency_options_arr['rb_agency_option_advertise'] : 0;
			if($rb_agency_option_advertise == 0) { // Reversed it, now 1 = Hide Advertising
			add_action('wp_dashboard_setup', 'rb_agency_add_dashboard' );
				// Hoook into the 'wp_dashboard_setup' action to register our other functions
				function rb_agency_add_dashboard() {
					global $wp_meta_boxes;
					// Create Dashboard Widgets
					wp_add_dashboard_widget('rb_agency_dashboard_quicklinks', __("RB Agency Updates", RBAGENCY_TEXTDOMAIN), 'rb_agency_dashboard_quicklinks');
					// reorder the boxes - first save the left and right columns into variables
					$left_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
					$right_dashboard = $wp_meta_boxes['dashboard']['side']['core'];
					// take a copy of the new widget from the left column
					$rb_agency_dashboard_merge_array = array("rb_agency_dashboard_quicklinks" => $left_dashboard["rb_agency_dashboard_quicklinks"]);
					unset($left_dashboard['rb_agency_dashboard_quicklinks']); // remove the new widget from the left column
					$right_dashboard = array_merge($rb_agency_dashboard_merge_array, $right_dashboard); // use array_merge so that the new widget is pushed on to the beginning of the right column's array  
					// finally replace the left and right columns with the new reordered versions
					$wp_meta_boxes['dashboard']['normal']['core'] = $left_dashboard; 
					$wp_meta_boxes['dashboard']['side']['core'] = $right_dashboard;
				}
				// Create the function to output the contents of our Dashboard Widget
				function rb_agency_dashboard_quicklinks() {
					// Display Quicklinks
					$rb_agency_options_arr = get_option('rb_agency_options');
					if (isset($rb_agency_options_arr['dashboardQuickLinks'])) {
						echo $rb_agency_options_arr['dashboardQuickLinks'];
					}
					$rss = fetch_feed("http://rbplugin.com/category/wordpress/rbagency/feed/");
					$num_items = 0;
					// Checks that the object is created correctly 
					if (!is_wp_error($rss)) {
						// Figure out how many total items there are, but limit it to 5. 
						$maxitems = $rss->get_item_quantity($num_items); 
						// Build an array of all the items, starting with element 0 (first element).
						$rss_items = $rss->get_items(0, $maxitems); 
					}
					echo "<div class=\"feed-searchsocial\">\n";
					if (isset($maxitems) && $maxitems == 0) {
						echo __("No Connection",RBAGENCY_TEXTDOMAIN);
						echo "\n";
					} else {
						// Loop through each feed item and display each item as a hyperlink.
						if(isset( $rss_items )){
							foreach ( $rss_items as $item ) {
								echo "  <div class=\"blogpost\">\n";
								echo "    <h4><a href='". $item->get_permalink() ."' title='Posted ". $item->get_date('j F Y | g:i a') ."' target=\"_blank\">". $item->get_title() ."</a></h4>\n";
								echo "    <div class=\"description\">". $item->get_description() ."</div>\n";
								echo "    <div class=\"clear\"></div>\n";
								echo "  </div>\n";
							}
						}
					}
					echo "</div>\n";
					echo "<hr />\n";
					echo __("Need help? Check out RB Agency <a href=\"http://rbplugin.com\" target=\"_blank\" title=\"RB Agency Documentation\">Documentation</a>.<br />", RBAGENCY_TEXTDOMAIN );
				}
			}
