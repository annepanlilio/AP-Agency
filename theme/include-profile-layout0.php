<?php
/*
Profile View with Scrolling Thumbnails and Primary Image
*/
	# lightbox.	
  	echo "<script type='text/javascript' src='".rb_agency_BASEDIR."js/slimbox2.js'></script>";
	echo '<link rel="stylesheet" href="'.rb_agency_BASEDIR.'style/slimbox2.css" type="text/css" media="screen" />';
	
	echo "<div id=\"profile\">\n";
	echo " <div id=\"rblayout-zero\" class=\"rblayout\">\n";

	echo "	<div id=\"photos\" class=\"col_4 column\">\n";
	echo "	  <div class=\"inner\">\n";
			// images
		
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  if ($countImg > 1) { 
				echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></div>\n";
			  } else {
				echo "<div class=\"photo\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></div>\n";
			  }
			}

	echo "	  <div class=\"cb\"></div>\n";
	echo "	  </div>\n";
	echo "	</div>\n"; // close #photos
	
		echo "	  <div id=\"stats\" class=\"col_4 column\">\n";

		echo "	  <h2>". $ProfileContactDisplay ."</h2>\n";

		echo "	  <ul>\n";

			if (!empty($ProfileGender)) {
				$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
				$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
				echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
			}
		
			if (!empty($ProfileStatHeight)) {
				if ($rb_agency_option_unittype == 0) { // Metric
					echo "<li><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", rb_agency_TEXTDOMAIN). "" ."</li>\n";
				} else { // Imperial
					$heightraw = $ProfileStatHeight;
					$heightfeet = floor($heightraw/12);
					$heightinch = $heightraw - floor($heightfeet*12);
					echo "<li><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", rb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", rb_agency_TEXTDOMAIN). "" ."</li>\n";
				}
			}
			if (!empty($ProfileStatWeight)) {
				if ($rb_agency_option_unittype == 0) { // Metric
					echo "<li><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", rb_agency_TEXTDOMAIN). "</li>\n";
				} else { // Imperial
					echo "<li><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", rb_agency_TEXTDOMAIN). "</li>\n";
				}
			}

			// Insert Custom Fields
			rb_agency_getProfileCustomFields($ProfileID, $ProfileGender);
			

			if($rb_agency_option_showcontactpage==1){
				echo "<li class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";
			}
		echo "	  </ul>\n"; // Close ul
		echo "	  </div>\n"; // Close Stats
	
	echo "		<div id=\"links\" class=\"col_4 column\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";

					 // Social Link
					 rb_agency_getSocialLinks();
	
	echo "			<ul>\n";

$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
			                              ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
			
			$count_favorite = mysql_num_rows($query_favorite);
			$datas_favorite = mysql_fetch_assoc($query_favorite);
			
			$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID
			                                 ."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
			
			$count_castingcart = mysql_num_rows($query_castingcart);
			
			$cl1 = ""; $cl2=""; $tl1="Add to Favorites"; $tl2="Add to Casting Cart";
						 
			if($count_favorite>0){ $cl1 = "fav_bg"; $tl1="Remove from Favorites"; }
			
			if($count_castingcart>0){ $cl2 = "cart_bg"; $tl2="Remove from Casting Cart"; }
			
			echo	'<div class="profile-actions-favorited">
			 					<li class=\"favorite\"><a title="'.$tl1.'" href="javascript:;" class="save_fav '.$cl1.' rb_button" id="'.$ProfileID.'">'.$tl1.'</a></li>
					<li><a title="'.$tl2.'" href="javascript:;" id="mycart" class="save_cart '.$cl2.' rb_button">'.$tl2.'</a></li>
			 					
					</div>';

						echo '<div id="resultsGoHereAddtoCart"></div>';
						?>
        
        <div id="view_casting_cart" style="<?php if($tl2=="Add to Casting Cart"){?>display:none;<?php }else{?>display:block;<?php }?>"><li class="casting"><a class="rb_button" href="<?php echo get_bloginfo('url')?>/profile-casting/"><?php echo __("View Casting Cart", rb_agency_TEXTDOMAIN);?></a></li></div>
    
        
        <div id="view_favorite" style="<?php if($tl1=="Add to Favorites"){?>display:none;<?php }else{?>display:block;<?php }?>"><li class="favorite"><a class="rb_button" href="<?php echo get_bloginfo('url')?>/profile-favorite/"><?php echo __("View favorite", rb_agency_TEXTDOMAIN);?></a></li></div>
    <?php

				// Resume
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Resume</a></li>\n";
				  }
				}
			
				// Comp Card
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Comp Card\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Comp Card</a></li>\n";
				  }
				}
				// Headshots
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Download Headshot</a></li>\n";
				  }
				}
				
				//Voice Demo
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">Listen to Voice Demo</a></li>\n";
				  }
				}

				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Slate</a></li>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Video Monologue</a></li>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\" class=\"rb_button\">Watch Demo Reel</a></li>\n";
				  }
				}

				// Other Media Type not the 
				// default ones
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" 
				                             AND ProfileMediaType NOT IN ('Image','Resume','CompCard','Comp Card','Headshot','VoiceDemo','Voice Demo','Video Slate','Video Monologue','Demo Reel')
											 ");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  	while ($dataMedia = mysql_fetch_array($resultsMedia)) {
                        echo "<li class=\"item video demoreel\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" class=\"rb_button\">".$dataMedia['ProfileMediaType']. "</a></li>\n";
				  	}
				}
                                
				// Is Logged?
				if (is_user_logged_in()) { 
			
					if($rb_agency_options_arr['rb_agency_option_profilelist_castingcart']==1){
			 			if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already	?>
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
						 
							
						} else {
				  			# removed as required
							#echo "<li class=\"add to cart\">". __("", rb_agency_TEXTDOMAIN);						  
						  	#echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"rb_button\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
			          	}
					}	//end if(checkCart(rb_agency_get_current_userid()
					# removed as required.
					#echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\" class=\"rb_button\">". __("Access Dashboard", rb_agency_TEXTDOMAIN). "</a></li>\n";
				}
				

	echo "			</ul>\n";
	echo "		</div>\n";  // Close Links
	
	echo "	  <div id=\"experience\" class=\"col_12 column\">\n";
	echo			$ProfileExperience;
	echo "	  </div>\n"; // Close Experience
/*
			if (isset($profileVideoEmbed)) {
	echo "		<div id=\"movie\" class=\"six column\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";
			}
*/	
	
	echo "	  <div style=\"clear: both;\"></div>\n"; // Clear All
	echo "  </div>\n";  // Close Profile Zero
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All
	echo "</div>\n";  // Close Profile
?>
<script type="text/javascript">

jQuery(document).ready(function(){

	jQuery(".save_fav").click(function(){

		ajax_submit(jQuery(this),"favorite");

	});

	jQuery(".save_cart").click(function(){
		ajax_submit(jQuery(this),"casting");
		
	});	

    function ajax_submit(Obj,type){
                
				if(type == "favorite"){
					
					var action_function = "rb_agency_save_favorite";
						
				} else if(type == "casting"){
				
					var action_function = "rb_agency_save_castingcart";
					
				
				}
				
				jQuery.ajax({type: 'POST',url: '<?php echo get_bloginfo('url') ?>/wp-admin/admin-ajax.php',
		
							 data: {action: action_function,  'talentID': <?php echo $ProfileID ?>},
		
						  success: function(results) {  
		
								if(results=='error'){ 
									alert("Error in query. Try again"); 
								}else if(results==-1){ 
									alert("You're not signed in");
								} else { 
		
									  if(type == "favorite"){
							             
										 if(Obj.hasClass('fav_bg')){
	 										 Obj.removeClass('fav_bg');
											 Obj.attr('title','Add to Favorites'); 
											document.getElementById('<?php echo $ProfileID; ?>').innerHTML="Add to Favorites";
											document.getElementById('view_favorite').style.display="none";
										 } else {
	 										 Obj.addClass('fav_bg');
											 Obj.attr('title','Remove from Favorites'); 
											 document.getElementById('<?php echo $ProfileID; ?>').innerHTML="Remove from Favorites";
											 document.getElementById('view_favorite').style.display="block";

										 }
							  
									 } else if(type == "casting") {
										 
										 if(Obj.hasClass('cart_bg')){
	 										 Obj.removeClass('cart_bg');
											 Obj.attr('title','Add to Casting Cart'); 
											 document.getElementById('mycart').innerHTML="Add to Casting Cart";
											 document.getElementById('view_casting_cart').style.display="none";
										 } else {
										 	Obj.addClass('cart_bg');
										 	Obj.attr('title','Remove from Casting Cart');
											document.getElementById('mycart').innerHTML="Remove from Casting Cart";
											document.getElementById('view_casting_cart').style.display="block";
										 }
									
									 }
		
									
								}
							}
			   }); // ajax submit
	 } // end function
});
</script>