<?php
/*
Title:  Scrolling
Author: RB Plugin
Text:   Profile View with Scrolling Thumbnails and Primary Image
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', RBAGENCY_PLUGIN_URL .'view/layout/09/css/style.css' );
	wp_enqueue_style( 'rblayout-style' );

	wp_register_style( 'scroller-style', RBAGENCY_PLUGIN_URL .'view/layout/09/css/jquery.mCustomScrollbar.min.css' );
	wp_enqueue_style( 'scroller-style' );


/*
 * Insert Script
 */
	wp_deregister_script( 'jquery-latest' ); 
	wp_register_script( 'jquery-latest', "//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
	wp_enqueue_script( 'jquery-latest' );

	wp_deregister_script( 'lightbox2' ); 
	wp_enqueue_script( 'lightbox2-footer', RBAGENCY_PLUGIN_URL .'ext/lightbox2/js/lightbox-2.6.min.js', array( 'jquery-latest' ));
	wp_enqueue_script( 'lightbox2-footer' );

	wp_register_script( 'photo-scroller', RBAGENCY_PLUGIN_URL .'view/layout/09/js/jquery.mCustomScrollbar.concat.min.js', '', 1, true );
	wp_enqueue_script( 'photo-scroller' );

	wp_register_script( 'init-scroller', RBAGENCY_PLUGIN_URL .'view/layout/09/js/init-scroller.js', '', 1, true );
	wp_enqueue_script( 'init-scroller' );


/*
 * Layout 
 */

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
// load script for printing profile pdf
$row = 4 ;
$rb_agency_options_arr = get_option('rb_agency_options');
$logo = $rb_agency_options_arr['rb_agency_option_agencylogo'];
rb_load_profile_pdf($row,$logo);

echo "	<div id=\"rbprofile\">\n";
echo " 		<div id=\"rblayout-nine\" class=\"rblayout\">\n";

echo "  		<div class=\"rbcol-12 rbcolumn\">\n";
echo "				<div id=\"scroller\">\n";
echo "					<div id=\"photo-scroller\" class=\"scroller\">";
						// Image Slider
						
						$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
						$resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
						$countImg  = $wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
							if ($countImg > 1) { 
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"/></a>\n";
								} else {
									echo "<a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
								}
							}
echo "					</div><!-- .scroller -->";
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"rbclear\"></div>\n";

echo "				<div id=\"info\">\n";
echo "	  				<div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";

							// Social Link
							rb_agency_getSocialLinks();
 
echo "	  				<div id=\"stats\" class=\"rbcol-12 rbcolumn\">\n";
echo "	  					<ul>\n";

								if (!empty($ProfileGender)) {
									$fetchGenderData = $wpdb->get_row($wpdb->prepare("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' "),ARRAY_A,0 	 );
									$count = $wpdb->num_rows;
									if($count > 0){
										echo "<li class=\"rb_gender\" id=\"rb_gender\"><strong>". __("Gender", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], RBAGENCY_TEXTDOMAIN). "</li>\n";
									}
								}

								
								// Insert Custom Fields
								rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);		  
								

echo "	  					</ul>\n";
echo "	  				</div>\n";	

echo "					<div id=\"links\">\n";
	/*
					 * Include Action Icons
					 */

						//include (plugin_dir_path(dirname(__FILE__)) .'/partial/include-profile-actions.php');

echo "						<ul>\n";
								
				
								
								// Is Logged?
								if (is_user_logged_in()) { 

									if(is_permitted('casting')){
										if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already ?>
											<script>
												function addtoCart(pid){
													var qString = 'usage=addtocart&pid=' +pid;
												
													$.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/sub_db_handler.php', qString, processResponseAddtoCart);
													// alert(qString);
												}										 
												function processResponseAddtoCart(data) {
													document.getElementById('resultsGoHereAddtoCart').style.display="block";
													document.getElementById('view_casting_cart').style.display="block";
													document.getElementById('resultsGoHereAddtoCart').textContent=data;
													setTimeout('document.getElementById(\'resultsGoHereAddtoCart\').style.display="none";',3000); 
													//setTimeout('document.getElementById(\'view_casting_cart\').style.display="none";',3000);
													setTimeout('document.getElementById(\'casting_cart_li\').style.display="none";',3000);
												}						
											</script>
										<?php
											echo "<li id=\"casting_cart_li\" class=\"add to cart\"><a id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\">". __("+ Shortlist", RBAGENCY_TEXTDOMAIN). "</a></li>\n";
										} else {
											echo "<li class=\"add to cart\">";
											echo " <a href=\"".get_bloginfo('url')."/profile-casting/\">". __("View Shortlist", RBAGENCY_TEXTDOMAIN)."</a></li>\n";
										}
									}	//end if(checkCart(rb_agency_get_current_userid() ?>

									<li id="resultsGoHereAddtoCart"></li>
									<li id="view_casting_cart" style="display:none;"><a href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Shortlist", RBAGENCY_TEXTDOMAIN);?></a></li>
								<?php
								}
								//Demo Reel
								$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Demo Reel");
								$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
								$countMedia  = $wpdb->num_rows;
								if ($countMedia > 0) {
									foreach($resultsMedia as $dataMedia ){
										echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltarget .">Watch ShowReel</a></li>\n";
									}
								}
								
								// print details of profiles
								echo "<li class=\"item resume\"><a id='print_pr_pdf' href='javascript:;'>Print PDF</a></li>\n";
		
								// Resume
								
								$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Resume");
								$resultsMedia=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);
								$countMedia  = $wpdb->num_rows;
								if ($countMedia > 0) {
									foreach($resultsMedia as $dataMedia ){
										echo "<li class=\"item resume\"><a href=\"". RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Print Resume PDF</a></li>\n";
									}
								}							
								//Contact Profile
								if($rb_agency_option_showcontactpage==1){
									echo "<div class=\"rel\"><strong>". __("Contact: ", RBAGENCY_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";
								}
echo "						</ul>\n";	
echo "					</div>\n";// Links
echo "				</div> <!-- #info -->\n";//End Info
echo "			</div> <!-- #profile-l -->\n";
echo "			<div class=\"rbclear\"></div>\n";
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"rbclear\"></div>\n"; // Clear All
?>
