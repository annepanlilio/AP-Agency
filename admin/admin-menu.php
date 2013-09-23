  <div id="rb-overview-icon" class="icon32"></div>
    <h2>
    	RB Agency
    	<a href="http://rbplugin.com" class="add-new-h2">Base Version <?php echo get_option('rb_agency_version'); ?></a>
        <?php if(get_option('rb_agency_interact_version')) { ?>
        <a href="http://rbplugin.com" class="add-new-h2">Interact Version <?php echo get_option('rb_agencyinteract_version'); ?></a>
        <?php } ?>
        <?php if(get_option('rb_agency_core_version')) { ?>
        <a href="http://rbplugin.com" class="add-new-h2">Interact Version <?php echo get_option('rb_agency_core_version'); ?></a>
        <?php } ?>
    </h2>
    <?php 
        // Just a backup check if version numbers are off
        if(get_option('rb_agency_version') <> rb_agency_VERSION) {
        echo "Upgrade Needed";
        }
        // Are there errors?
        settings_errors();

		if( isset( $_GET['page'] ) ) {  
		    $active_page = isset( $_GET['page'] ) ? $_GET['page'] : 'display_options';
		} // end if  
	?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=rb_agency_menu" class="nav-tab <?php echo $active_page == 'rb_agency_menu' ? 'nav-tab-active' : ''; ?>">Overview</a>  
        <a href="?page=rb_agency_profiles" class="nav-tab <?php echo $active_page == 'rb_agency_profiles' ? 'nav-tab-active' : ''; ?>">Manage Profiles</a>  
        <a href="?page=rb_agency_search" class="nav-tab <?php echo $active_page == 'rb_agency_search' ? 'nav-tab-active' : ''; ?>">Search Profiles</a>  
        <a href="?page=rb_agency_reports" class="nav-tab <?php echo $active_page == 'rb_agency_reports' ? 'nav-tab-active' : ''; ?>">Tools</a>  
        <a href="?page=rb_agency_settings" class="nav-tab <?php echo $active_page == 'rb_agency_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>  
    </h2>