<?php
/*
Profile View with Sliding Thumbnails and Primary Image
*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"rblayout-two\" class=\"rblayout\">\n";
	
	echo "  <div class=\"col_7 column\">\n";
	echo "	<div id=\"scroller\">\n";
	echo "		<div id=\"photo-scroller\" class=\"scroller\">";
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
	echo "		</div><!-- .scroller -->";
	echo "	</div><!-- #scroller -->\n";
	
	echo "	<div class=\"cb\"></div>\n";
	
	echo "	<div id=\"info\">\n";
	echo "	  <div id=\"name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";
	
	 // Social Link
	 rb_agency_getSocialLinks();
	 
	echo "	  <div id=\"stats\" class=\"col_6 column\">\n";
	echo "	  <ul>\n";

		if (!empty($ProfileGender)) {
			$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
			$count = mysql_num_rows($queryGenderResult);
			if($count > 0){
				$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
				echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
			}
		}
	
		// Insert Custom Fields
		rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
	
		  
        
	echo "	  </ul>\n";
	echo "	  </div>\n";
	
	
	echo "		<div id=\"links\" class=\"col_6 column\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
	echo "			<ul>\n";

				// Resume
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Print Resume</a></li>\n";
				  }
				}
			
				// Comp Card
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Compcard\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Download Comp Card</a></li>\n";
				  }
				}
				// Headshots
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Download Headshot</a></li>\n";
				  }
				}
				
				//Voice Demo
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\">Listen to Voice Demo</a></li>\n";
				  }
				}

				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch Video Slate</a></li>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch Video Monologue</a></li>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev .">Watch Demo Reel</a></li>\n";
				  }
				}

				//Contact Profile
				if($rb_agency_option_showcontactpage==1){
		    		echo "<div class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></div>\n";
				}
                        // Other links - Favorite, Casting cart...
			      rb_agency_get_miscellaneousLinks($ProfileID);
				
				// Is Logged?
				if (is_user_logged_in()) { 
				//echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\">". __("Access Dashboard", rb_agency_TEXTDOMAIN). "</a></li>\n";
				
		
		if($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']==1){
			 if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already
			 ?>
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
						 
							echo "<li id=\"casting_cart_li\" class=\"add to cart\"><a id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\">". __("Add to Casting Cart", rb_agency_TEXTDOMAIN). "</a></li>\n";
							}else{
				  		  echo "<li class=\"add to cart\">". __("", rb_agency_TEXTDOMAIN);
						  
						  echo " <a href=\"".get_bloginfo('url')."/profile-casting/\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
				          }
			}	//end if(checkCart(rb_agency_get_current_userid()

				}

	echo "	</ul>\n";	
	echo "		</div>\n";// Links
	?>
    <div id="resultsGoHereAddtoCart"></div>
                <div id="view_casting_cart" style="display:none;"><a href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></div>
    <?php
	//Experience
	echo "		  <div id=\"experience\" class=\"col_12 column\">\n";
	echo			$ProfileExperience;
	echo "		  </div>\n";
/*	
	//Movie
				if (isset($profileVideoEmbed)) {
	echo "		<div id=\"movie\" class=\"twelve column\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";
			}

*/
	# removed as required
	#if (isset($profileVideoEmbed)) {
	#	echo "<a href='http://www.youtube.com/watch?v=". $profileVideoEmbed ."'>Watch Movie Video </a>";
	#}
	echo "	</div> <!-- #info -->\n";//End Info
	echo "	</div> <!-- #profile-l -->\n";
	echo "  <div class=\"col_5 column\">\n";
	echo "			<div id=\"profile-picture\">\n";

                    // images
                    $queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
                    $resultsImg = mysql_query($queryImg);
                    $countImg = mysql_num_rows($resultsImg);
                    while ($dataImg = mysql_fetch_array($resultsImg)) {
						echo "<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" ". $reltype ."><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
                    }

	echo "			</div> <!-- #profile-picture -->\n";
	echo "	</div>\n";
	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
?>

