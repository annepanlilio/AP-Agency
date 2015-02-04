<?php
// *************************************************************************************************** //
// Add Widgets



// *************************************************************************************************** //
// Add Short Codes



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