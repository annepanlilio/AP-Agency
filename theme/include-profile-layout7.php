<<<<<<< HEAD
<?php
/*
Custom Layout 7
*/

?>

	<div id="profile">
	<div id="rblayout-seven">
	  <div id="info-slide">
		<div id="profile-info" class="col_4 column">
					
					<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
					<ul>
			<?php
							if (!empty($ProfileGender)) {
								$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
								$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
                                                                echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<b class=\"divider\">:</b></strong> ". $fetchGenderData["GenderTitle"] . "</li>\n";
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
							rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender);
							
							//$exclude="'16'";
							// rb_agency_getProfileCustomFieldsEcho($ProfileID, $ProfileGender,$exclude);?>
					</ul>
		</div>
		<div id="profile-slider" class="flexslider col_8 column">
					<ul class="slides">
						<?php
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
									$resultsImg = mysql_query($queryImg);
									$countImg = mysql_num_rows($resultsImg);
									while ($dataImg = mysql_fetch_array($resultsImg)) {
									  	echo "<li><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
									}
						?>
					</ul>
		</div>
		<ul id="profile-links">
				
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
											 document.getElementById('veiw').style.display="block";
						                      document.getElementById('<?php echo $ProfileID; ?>').style.display="none";

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
</script><?php 		
		
	

$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
			                              ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
			
			$count_favorite = mysql_num_rows($query_favorite);
			$datas_favorite = mysql_fetch_assoc($query_favorite);
			
			$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID
			                                 ."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
			
			$count_castingcart = mysql_num_rows($query_castingcart);
			
			$cl1 = ""; $cl2=""; $tl1="Add to Favorites"; $tl2="Add to Casting Cart";$k1="";$k="";
						 
			
			if($count_favorite==0){
			echo	'<div class="profile-actions-favorited">
			 					<li class=\"favorite\" style="'.$k1.'"><a title="'.$tl1.'" href="javascript:;" class="save_fav '.$cl1.'" id="'.$ProfileID.'">'.$tl1.'</a><a href="'.get_bloginfo('url').'/profile-favorite/" style="display:none;" id="veiw" class="btn_gray">View Favorited</a></li>';
					
			 					
			}else{
				echo	'<div class="profile-actions-favorited">
			 					<li class=\"favorite\" style="'.$k1.'"><a href="'.get_bloginfo('url').'/profile-favorite/"  class="btn_gray">View Favorited</a></li>';
				
				}
						?>
                           
        
     
                        
		<?php	 if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already
			 ?>
					<script>
                    function addtoCart(pid){
					 var qString = 'usage=addtocart&pid=' +pid;
					
				     $.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/sub_db_handler.php', qString, processResponseAddtoCart);
                     // alert(qString);
					 }
					 
					function processResponseAddtoCart(data) {
					    document.getElementById('veiw1').style.display="block";
						document.getElementById('addtocart').style.display="none";
						document.getElementById('resultsGoHereAddtoCart').style.display="block";
						document.getElementById('view_casting_cart').style.display="block";
						document.getElementById('resultsGoHereAddtoCart').textContent=data;
						// setTimeout('document.getElementById(\'resultsGoHereAddtoCart\').style.display="none";',3000); 
						//setTimeout('document.getElementById(\'view_casting_cart\').style.display="none";',3000);
						setTimeout('document.getElementById(\'casting_cart_li\').style.display="none";',3000);
						
						
					}
					
                     </script>
                         <?php
						 
							echo "<li id=\"casting_cart_li\"><a style=\"\" id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\" class=\"btn_gray\">". __("Add to Casting Cart" , rb_agency_TEXTDOMAIN). "</a><a href=\"".get_bloginfo('url')."/profile-casting/\" style=\"display:none;\" id=\"veiw1\" class=\"btn_gray\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
							}else{
				  		  echo "<li class=\"add to cart\">". __("", rb_agency_TEXTDOMAIN);
						  
						  echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"btn_gray\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
				          }
			?>
        			<li><a  class="showSingle1" target="1">Pictures</a></li>
					<li><a  class="showSingle2" target="3">Experience</a></li>
					<li><a  class="showSingle3" target="2">Videos</a></li>
     
    
					</div>
<?php
						echo '<div id="resultsGoHereAddtoCart"></div>';?>
					
				</ul>
		
		<div id="profile-carousel" class="flexslider">
			<ul class="slides">
				<?php
					$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							
							while ($dataImg = mysql_fetch_array($resultsImg)) {
							  	echo "<li><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></li>\n";
							}
				?>
			</ul>
		</div>
		<div style="clear:both"></div>
		<div id="videos-carousel" class="flexslider">
			<ul class="slides">
            	<?php   
	
				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					   echo "<li>";
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "<object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></li>";
				   echo "</li>";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					  echo "<li>";
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "<object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object>";
				 echo "</li>";
				  }
				}?>				
			</ul>
		</div>
		<div style="clear:both"></div>
		<div id="experience">
		 	<?php echo rb_agency_getProfileCustomdescription($ProfileID, $ProfileGender, $title_to_exclude); ?>
	  	</div>
	</div>
	
		
		<script>
			jQuery(function(){
					var exp_car = jQuery('#experience');
					var vid_car = jQuery('#videos-carousel');
					var img_car = jQuery('#profile-carousel');

					jQuery(vid_car).hide();
					jQuery(exp_car).hide();

					jQuery('.showSingle1').click(function(){
						  jQuery(img_car).show();
						  jQuery(vid_car).hide();
						  jQuery(exp_car).hide();
						  
					});
					jQuery('.showSingle2').click(function(){
						  jQuery(exp_car).show();
						  jQuery(vid_car).hide();
						  jQuery(img_car).hide();
						  
					});
					jQuery('.showSingle3').click(function(){
						  jQuery(vid_car).show();
						  jQuery(exp_car).hide();
						  jQuery(img_car).hide();
						  
					});
			
			});
	</script>
=======
<?php
/*
Custom Layout 7
*/

?>
<style>
#main-container { background: #000; }
#main { width: 100%; }
#info-slide { width: 940px; max-width: 100%; min-width: 768px; margin: 0 auto; }
</style>
	<div id="profile">
		<div id="rblayout-seven" class="rblayout">
			<div id="info-slide">
				<div id="profile-info" class="four column">
					
					<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
					<ul>
			<?php
							if (!empty($ProfileGender)) {
								$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
								$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
                                                                echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<b class=\"divider\">:</b></strong> ". $fetchGenderData["GenderTitle"] . "</li>\n";
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
							rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender);
							
							//$exclude="'16'";
							//rb_agency_getProfileCustomFieldsEcho($ProfileID, $ProfileGender,$exclude);?>
					</ul>
				</div>
                

			</div><!-- .portfolio-info -->
				<div id="profile-slider" class="flexslider eight column">
					<ul class="slides">
						<?php
						$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
									$resultsImg = mysql_query($queryImg);
									$countImg = mysql_num_rows($resultsImg);
									while ($dataImg = mysql_fetch_array($resultsImg)) {
									  	echo "<li><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\" title=\"". $ProfileContactDisplay ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></a></li>\n";
									}
						?>
					</ul>
				</div>
				<ul id="profile-links">
				
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
											 document.getElementById('veiw').style.display="block";
						                      document.getElementById('<?php echo $ProfileID; ?>').style.display="none";

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
</script><?php 		
		
	

$query_favorite = mysql_query("SELECT * FROM ".table_agency_savedfavorite." WHERE SavedFavoriteTalentID='".$ProfileID
			                              ."'  AND SavedFavoriteProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
			
			$count_favorite = mysql_num_rows($query_favorite);
			$datas_favorite = mysql_fetch_assoc($query_favorite);
			
			$query_castingcart = mysql_query("SELECT * FROM ". table_agency_castingcart."  WHERE CastingCartTalentID='".$ProfileID
			                                 ."'  AND CastingCartProfileID = '".rb_agency_get_current_userid()."'" ) or die("error");
			
			$count_castingcart = mysql_num_rows($query_castingcart);
			
			$cl1 = ""; $cl2=""; $tl1="Add to Favorites"; $tl2="Add to Casting Cart";$k1="";$k="";
						 
			
			if($count_favorite==0){
			echo	'<div class="profile-actions-favorited">
			 					<li class=\"favorite\" style="'.$k1.'"><a title="'.$tl1.'" href="javascript:;" class="save_fav '.$cl1.'" id="'.$ProfileID.'">'.$tl1.'</a><a href="'.get_bloginfo('url').'/profile-favorite/" style="display:none;" id="veiw" class="btn_gray">View Favorited</a></li>';
					
			 					
			}else{
				echo	'<div class="profile-actions-favorited">
			 					<li class=\"favorite\" style="'.$k1.'"><a href="'.get_bloginfo('url').'/profile-favorite/"  class="btn_gray">View Favorited</a></li>';
				
				}
						?>
                           
        
     
                        
		<?php	 if(checkCart(rb_agency_get_current_userid(),$ProfileID)==0 ){ //check if profile is in cart already
			 ?>
					<script>
                    function addtoCart(pid){
					 var qString = 'usage=addtocart&pid=' +pid;
					
				     $.post('<?php echo get_bloginfo("url");?>/wp-content/plugins/rb-agency/theme/sub_db_handler.php', qString, processResponseAddtoCart);
                     // alert(qString);
					 }
					 
					function processResponseAddtoCart(data) {
					    document.getElementById('veiw1').style.display="block";
						document.getElementById('addtocart').style.display="none";
						document.getElementById('resultsGoHereAddtoCart').style.display="block";
						document.getElementById('view_casting_cart').style.display="block";
						document.getElementById('resultsGoHereAddtoCart').textContent=data;
						// setTimeout('document.getElementById(\'resultsGoHereAddtoCart\').style.display="none";',3000); 
						//setTimeout('document.getElementById(\'view_casting_cart\').style.display="none";',3000);
						setTimeout('document.getElementById(\'casting_cart_li\').style.display="none";',3000);
						
						
					}
					
                     </script>
                         <?php
						 
							echo "<li id=\"casting_cart_li\"><a style=\"\" id=\"addtocart\" onclick=\"javascript:addtoCart('$ProfileID');\" href=\"javascript:void(0)\" class=\"btn_gray\">". __("Add to Casting Cart" , rb_agency_TEXTDOMAIN). "</a><a href=\"".get_bloginfo('url')."/profile-casting/\" style=\"display:none;\" id=\"veiw1\" class=\"btn_gray\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
							}else{
				  		  echo "<li class=\"add to cart\">". __("", rb_agency_TEXTDOMAIN);
						  
						  echo " <a href=\"".get_bloginfo('url')."/profile-casting/\" class=\"btn_gray\">". __("View Casting Cart", rb_agency_TEXTDOMAIN)."</a></li>\n";
							
				          }
			?>
        			<li><a  class="showSingle1" target="1">Pictures</a></li>
					<li><a  class="showSingle2" target="3">Experience</a></li>
					<li><a  class="showSingle3" target="2">Videos</a></li>
     
    
					</div>
<?php
						echo '<div id="resultsGoHereAddtoCart"></div>';?>
					
				</ul>
			</div><!-- #info-slide -->
			
			<div id="profile-carousel" class="flexslider">
				<ul class="slides targetDiv1">
					
				<?php
					$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							
							while ($dataImg = mysql_fetch_array($resultsImg)) {
							  	echo "<li><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></li>\n";
							}
					?>
                    
				</ul>
                <ul class="slides targetDiv3" style="display:none">
            <?php   
	
				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					   echo "<li>";
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "<object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></li>";
				   echo "</li>";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					  echo "<li>";
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "<object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object>";
				 echo "</li>";
				  }
				}?>
</ul>
 <ul class=" targetDiv2" style="display:none">
 <li style="width: 950px;">
 <?php 
 			
		echo rb_agency_getProfileCustomdescription($ProfileID, $ProfileGender, $title_to_exclude);
	
		
	?>
    </li>
		</ul>
		
			</div>
			
		
		<script>
			jQuery(function(){
					
					jQuery('.showSingle1').click(function(){
						  jQuery('.targetDiv1').show();
						  jQuery('.targetDiv2').hide();
						  jQuery('.targetDiv3').hide();
						  
					});
					jQuery('.showSingle2').click(function(){
						  jQuery('.targetDiv2').show();
						  jQuery('.targetDiv1').hide();
						  jQuery('.targetDiv3').hide();
						  
					});
					jQuery('.showSingle3').click(function(){
						  jQuery('.targetDiv3').show();
						  jQuery('.targetDiv1').hide();
						  jQuery('.targetDiv2').hide();
						  
					});
			
			});
	</script>
>>>>>>> 6655ec9652959be7511603c7c8caf268016fa170
	</div><!-- #profile -->