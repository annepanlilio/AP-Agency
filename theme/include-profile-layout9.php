<?php
/*
Large Scroller
*/

echo "	<div id=\"profile\">\n";
echo " 		<div id=\"rblayout-nine\" class=\"rblayout\">\n";

echo "  		<div class=\"col_12 column\">\n";
echo "				<div id=\"scroller\">\n";
echo "					<div id=\"photo-scroller\" class=\"scroller\">";
							// Image Slider
							$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY ProfileMediaPrimary DESC";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							while ($dataImg = mysql_fetch_array($resultsImg)) {
						    	if ($countImg > 1) { 
									echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\"/></a>\n";
							  	} else {
									echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
							  	}
							}
echo "					</div><!-- .scroller -->";
echo "				</div><!-- #scroller -->\n";

echo "				<div class=\"cb\"></div>\n";

echo "				<div id=\"info\">\n";
echo "	  				<div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";

							// Social Link
							rb_agency_getSocialLinks();
 
echo "	  				<div id=\"stats\" class=\"col_12 column\">\n";
echo "	  					<ul>\n";

								if (!empty($ProfileGender)) {
									$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
									$count = mysql_num_rows($queryGenderResult);
									if($count > 0){
										$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
										echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
									}
								}

								// Insert Custom Fields
								rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender);		  
    
echo "	  					</ul>\n";
echo "	  				</div>\n";	

echo "					<div id=\"links\">\n";
echo "						<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
echo "						<ul>\n";

								// Other links - Favorite, Casting cart...
							    rb_agency_get_miscellaneousLinks($ProfileID);
								
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
											echo "<li id=\"casting_cart_li\" class=\"add to cart\"><a id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\">". __("+ Shortlist", rb_agency_TEXTDOMAIN). "</a></li>\n";
										} else {
								  		  	echo "<li class=\"add to cart\">". __("", rb_agency_TEXTDOMAIN);
										  	echo " <a href=\"".get_bloginfo('url')."/profile-casting/\">". __("View Shortlist", rb_agency_TEXTDOMAIN)."</a></li>\n";
							          	}
									}	//end if(checkCart(rb_agency_get_current_userid() ?>

				    				<li id="resultsGoHereAddtoCart"></li>
				                	<li id="view_casting_cart" style="display:none;"><a href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Shortlist", rb_agency_TEXTDOMAIN);?></a></li>
				    			<?php
								}
								//Demo Reel
								$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
								$countMedia = mysql_num_rows($resultsMedia);
								if ($countMedia > 0) {
								  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
										echo "<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch ShowReel</a></li>\n";
								  	}
								}
								// Resume
								$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
								$countMedia = mysql_num_rows($resultsMedia);
								if ($countMedia > 0) {
								  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
										echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Print PDF</a></li>\n";
								  	}
								}							
								//Contact Profile
								if($rb_agency_option_showcontactpage==1){
						    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";
								}
echo "						</ul>\n";	
echo "					</div>\n";// Links
echo "				</div> <!-- #info -->\n";//End Info
echo "			</div> <!-- #profile-l -->\n";
echo "			<div class=\"cb\"></div>\n";
echo " 		</div>\n";  // Close Profile Layout
echo "	</div>\n";  // Close Profile
echo "	<div class=\"cb\"></div>\n"; // Clear All
?>
