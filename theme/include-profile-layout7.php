<?php


/*


Template Name: Portfoliopage


 * @name		Portfoliopage


 * @type		PHP page


 * @desc		Portfoliopage


*/

?>



<div id="profile">
        <div   class="portfolio-page">  
        
          <div id="alexandra-knight-portfolio" class="portfolio">
            <div class="portfolio-controls">
              <a href="http://www.adambunny.com.au/promo/model-directory/" title="Back" class="back">Back</a>
            </div>
            <a href="" title="Close" class="x-close"></a>
            <div class="cb"></div>
            <div class="portfolio-box">
            
              <div class="info">
        <?php   echo "<div class=\"PageTitle\"><h1>". $ProfileContactDisplay ."</h1></div>\n"; ?>
					   <?php
                                 echo "	  <div class=\"stats\">\n";
                  
                             if (!empty($ProfileGender)) {
						$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID=".$ProfileGender." ");
						$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
						
						echo "<div><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</div>\n";
					}
                              if (!empty($ProfileStatEthnicity)) {
                                    echo "<div><strong>". __("Ethnicity", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatEthnicity ."</div>\n";
                              }
                              if (!empty($ProfileStatSkinColor)) {
                                    echo "<div><strong>". __("Skin Tone", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatSkinColor ."</div>\n";
                              }
                              if (!empty($ProfileStatHairColor)) {
                                    echo "<div><strong>". __("Hair Color", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHairColor ."</div>\n";
                              }
                              if (!empty($ProfileStatEyeColor)) {
                                    echo "<div><strong>". __("Eye Color", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatEyeColor ."</div>\n";
                              }
                              if (!empty($ProfileStatHeight)) {
                                    if ($rb_agency_option_unittype == 0) { // Metric
                                          echo "<div><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatHeight ." ". __("cm", rb_agency_TEXTDOMAIN). "" ."</div>\n";
                                    } else { // Imperial
                                          $heightraw = $ProfileStatHeight;
                                          $heightfeet = floor($heightraw/12);
                                          $heightinch = $heightraw - floor($heightfeet*12);
                                          echo "<div><strong>". __("Height", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $heightfeet ." ". __("ft", rb_agency_TEXTDOMAIN). " ". $heightinch ." ". __("in", rb_agency_TEXTDOMAIN). "" ."</div>\n";
                                    }
                              }
                              if (!empty($ProfileStatWeight)) {
                                    if ($rb_agency_option_unittype == 0) { // Metric
                                          echo "<div><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("kg", rb_agency_TEXTDOMAIN). "</div>\n";
                                    } else { // Imperial
                                          echo "<div><strong>". __("Weight", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWeight ." ". __("lb", rb_agency_TEXTDOMAIN). "</div>\n";
                                    }
                              }
                              if (!empty($ProfileStatBust)) {
                                    if($ProfileGender == "Male"){ $ProfileStatBustTitle = __("Chest", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatBustTitle = __("Bust", rb_agency_TEXTDOMAIN); } else { $ProfileStatBustTitle = __("Chest/Bust", rb_agency_TEXTDOMAIN); }
                                    echo "<div><strong>". $ProfileStatBustTitle ."</strong> ". $ProfileStatBust ."</div>\n";
                              }
                              if (!empty($ProfileStatWaist)) {
                                    echo "<div><strong>". __("Waist", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatWaist ."</div>\n";
                              }
                              if (!empty($ProfileStatHip)) {
                                    if($ProfileGender == "Male"){ $ProfileStatHipTitle = __("Inseam", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatHipTitle = __("Hips", rb_agency_TEXTDOMAIN); } else { $ProfileStatHipTitle = __("Hips/Inseam", rb_agency_TEXTDOMAIN); }
                                    echo "<div><strong>". $ProfileStatHipTitle ."<span class=\"divider\">:</span></strong> ". $ProfileStatHip ."</div>\n";
                              }
                              if (!empty($ProfileStatDress) || ($ProfileStatDress == 0)) {
                                    if($ProfileGender == "Male"){ $ProfileStatDressTitle = __("Suit Size", rb_agency_TEXTDOMAIN); } elseif ($ProfileGender == "Female"){ $ProfileStatDressTitle = __("Dress Size", rb_agency_TEXTDOMAIN); } else { $ProfileStatDressTitle = __("Suit/Dress Size", rb_agency_TEXTDOMAIN); }
                                    echo "<div><strong>". $ProfileStatDressTitle ."<span class=\"divider\">:</span></strong> ". $ProfileStatDress ."</div>\n";
                              }
                              if (!empty($ProfileStatShoe)) {
                                    echo "<div><strong>". __("Shoe Size", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". $ProfileStatShoe ."</div>\n";
                              }
                  
                  
                              $resultsCustom = $wpdb->get_results("SELECT c.ProfileCustomTitle, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ."");
                              foreach  ($resultsCustom as $resultCustom) {
                                    echo "<div><strong>". $resultCustom->ProfileCustomTitle ."<span class=\"divider\">:</span></strong> ". $resultCustom->ProfileCustomValue ."</div>\n";
                              }
                  
                        echo "	  </div>\n"; // Close Stats
                        ?>
                        
              <?php
		  	echo "		<div class=\"links\">\n";
	echo "			<h3>". $AgencyName ." ". $ProfileClassification ."</h3>\n";
	echo "			<ul>\n";

				// Resume
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Resume\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item resume\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Print Resume</a></li>\n";
				  }
				}
			
				// Comp Card
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"CompCard\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item compcard\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Download Comp Card</a></li>\n";
				  }
				}
				// Headshots
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Headshot\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item headshot\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Download Headshot</a></li>\n";
				  }
				}
				
				//Voice Demo
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"VoiceDemo\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "<li class=\"item voice\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."\" target=\"_blank\">Listen to Voice Demo</a></li>\n";
				  }
				}

				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "		<li class=\"item video slate\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev ." target=\"_blank\">Watch Video Slate</a></li>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video monologue\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev ." target=\"_blank\">Watch Video Monologue</a></li>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				echo "		<li class=\"item video demoreel\"><a href=\"http://www.youtube.com/watch?v=". $dataMedia['ProfileMediaURL'] ."\" ". $reltypev ." target=\"_blank\">Watch Demo Reel</a></li>\n";
				  }
				}

				//Contact Profile
				if (isset($rb_agency_option_agency_urlcontact) && !empty($rb_agency_option_agency_urlcontact)) {
				//echo "		<li class=\"item contact\"><a href=\"". $rb_agency_option_agency_urlcontact ."\">". __("Contact", rb_agency_TEXTDOMAIN). " ". $ProfileClassification ."</a></li>\n";
				}
                        // Add to Casting Cart
				if (isset($rb_agency_option_profilelist_castingcart) && !empty($rb_agency_option_profilelist_castingcart)) {
					
											//Execute query - Casting Cart
					     $queryCastingCart = mysql_query("SELECT cart.CastingCartTalentID as cartID FROM ".table_agency_castingcart."  cart WHERE  cart.CastingCartTalentID = ".$ProfileID." ");
					     $dataCastingCart = mysql_fetch_assoc($queryCastingCart); 
					     $countCastingCart = mysql_num_rows($queryCastingCart);
						if($countCastingCart <=0){
							echo "<li class=\"item addtocastingcart\"><div class=\"castingcart\"><a href=\"javascript:;\" id=\"".$ProfileID."\" class=\"save_castingcart\">Add To Casting Cart</a></div></li>\n";
						 }else{
							echo "<li class=\"item addtocastingcart\"><div class=\"castingcart\"><a href=\"javascript:;\" id=\"".$ProfileID."\"  class=\"saved_castingcart\">Added To Casting Cart <a href=\"".get_bloginfo("wpurl")."/profile-casting-cart/\" style=\"font-size:11px;float:right;\" class=\"view_all_castingcart\"><strong>View</strong></a></div></li>\n";
						}
							
				}
				if ($rb_agency_option_profilelist_favorite) {
							
										 //Execute query - Favorite Model
					     $queryFavorite = mysql_query("SELECT fav.SavedFavoriteTalentID as favID FROM ".table_agency_savedfavorite." fav WHERE  fav.SavedFavoriteTalentID = ".$ProfileID." ");
					     $dataFavorite = mysql_fetch_assoc($queryFavorite); 
					     $countFavorite = mysql_num_rows($queryFavorite);
						
									
									if($countFavorite <= 0){
										echo "<li class=\"item addtofavorite\"> <div class=\"favorite\"><a rel=\"nofollow\" href=\"javascript:;\" class=\"save_favorite\" id=\"".$ProfileID."\"><div class=\"favorite-box\"></div>Save as Favorite</a></div>\n";
									}else{
										echo "<li class=\"item addtofavorite\"> <div class=\"favorite\"><a rel=\"nofollow\" href=\"javascript:;\" class=\"favorited\" id=\"".$ProfileID."\">Favorited</a> <a href=\"".get_bloginfo("wpurl")."/profile-favorite/\"  style=\"font-size:12px;float:right;\" class=\"view_all_favorite\"><strong>View</strong></a></div>\n";
									}
									
				}
				//<li class="item"><a href=""><img src="/wp-content/uploads/2010/07/talk.jpg" /></a><a href="">View Video Slate</a></li>
			 	//li class="item"><a href=""><img src="/wp-content/uploads/2010/07/talk.jpg" /></a><a href="">View Monolog</a></li>
			      //<li class="item"><a href=""><img src="/wp-content/uploads/2010/07/download.jpg" /></a><a href="">Download Reel</a></li>
				//echo "<li class=\"cart\"><a href=\"\"><img src=\"". get_bloginfo("wpurl") ."/wp-content/uploads/2010/07/cart.jpg\" /></a><a href=\"\">Add to Casting Cart</a></li>\n";
				
				// URL
				//if($ProfileIsModel){ $returnURL = get_bloginfo("url") ."/models/"; } elseif ($ProfileIsTalent){ $returnURL = get_bloginfo("url") ."/talent/"; }
				//echo "<li class=\"return\"><a href=\"\"><img src=\"". get_bloginfo("url") ."/wp-content/uploads/2010/07/return.jpg\" /></a><a href=\"". $returnURL ."\">Return to ". $ProfileClassification ."</a></li>\n";
				
				// Is Logged?
				if (is_user_logged_in()) { 
				//echo "		<li class=\"return dashboard\"><a href=\"". get_bloginfo("url") ."/dashboard/\">". __("Access Dashboard", rb_agency_TEXTDOMAIN). "</a></li>\n";
				}

	echo "	</ul>\n";

			if (isset($profileVideoEmbed)) {
	//echo "		<div id=\"movie\"><object width=\"250\" height=\"190\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"250\" height=\"190\"></embed></object></div>\n";
			}
		  ?>


              </div>
              
              </div><!-- #info -->

              <div class="portfolio-pic">
               <?php
		   
				// images
				$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
				$resultsImg = mysql_query($queryImg);
				$countImg = mysql_num_rows($resultsImg);
				while ($dataImg = mysql_fetch_array($resultsImg)) {
					echo "		<a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a>\n";
				}
			?>
              </div>
              
              <div class="photos">
               <?php
		    echo "	<div id=\"photos\">\n";
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 0 ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			   //	echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
				echo "<div class=\"multiple\"><a href=\"javascript:;\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			
			}
		   echo "	</div><!-- #photos -->\n";
		 ?>
            </div>
          </div>       
        </div>
        <script type="text/javascript">
	  var $bigImage = jQuery.noConflict();
		$bigImage(window).load(function() {
			  var Primary = "";
			  $bigImage("div[class=multiple] img").click(function(){
				    if(Primary == ""){
					  Primary  = $bigImage("div[class=portfolio-pic] img").attr("src");
				    }
				     $bigImage("div[class=portfolio-pic] img").attr("src",$bigImage(this).attr("src"));
				     
				     setTimeout( function() {
						$bigImage("div[class=portfolio-pic] img").attr("src",Primary);
					}, 60000 );
				  
			  });
		});
	 </script>
        <?php
//******* NEXT / PREVIOUS MODEL *****************/
	   $query = "SELECT * , 
	   (SELECT ProfileGallery FROM ".table_agency_profile." WHERE ProfileID > ". $ProfileID ."  AND ProfileIsActive = 1  LIMIT 1) as n,
	   (SELECT ProfileGallery FROM ".table_agency_profile." WHERE ProfileID < ". $ProfileID ."  AND ProfileIsActive = 1  LIMIT 1) as p 
	    FROM " . table_agency_profile . " WHERE ProfileIsActive = 1  ";
	   $q_get = mysql_query($query) or die(mysql_error());
	   $f_get = mysql_fetch_assoc($q_get);
	  ?>
    <div class="nav">
            <?php if(isset($f_get["p"])){?>
           <a href="<?php echo get_settings('siteurl')."/profile/".$f_get["p"]; ?>/" title="Previous Model" class="nav-prev"></a> 
             <?php } ?>
         <span></span>
         <!-- <a href="http://www.adambunny.com.au/promo/search-model/" title="Search Models"><span></span></a>-->
           <?php if(isset($f_get["n"])){?>
            <a href="<?php echo get_settings('siteurl')."/profile/".$f_get["n"]; ?>/" title="Next Model" class="nav-next"></a> 
            <?php } ?>
        </div>
   <?php echo "</div>\n";  // Close Profile     ?>
   
  <style type="text/css">
    #content { margin: 0; }
  </style>

<script type="text/javascript" src="/wp-content/plugins/rb-agency/js/jquery_003.js"></script>
