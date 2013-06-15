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
		
							 data: {action: action_function,  'talentID': Obj.attr("id")},
		
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
										 } else {
	 										 Obj.addClass('fav_bg');
											 Obj.attr('title','Remove from Favorites'); 
										 }
							  
									 } else if(type == "casting") {
										 
										 if(Obj.hasClass('cart_bg')){
	 										 Obj.removeClass('cart_bg');
											 Obj.attr('title','Add to Casting Cart'); 
										 } else {
										 	Obj.addClass('cart_bg');
										 	Obj.attr('title','Remove from Casting Cart');
										 }
									
									 }
		
									
								}
							}
			   }); // ajax submit
	 } // end function
});
</script>
<?php
/*
Expended Profile with Tabs
*/

	echo "<div id=\"profile\">\n";
	echo " <div id=\"rblayout-three\" class=\"rblayout\">\n";

	echo " <div id=\"go-back\" class=\"col_12 column\">\n";
	//echo "   <a href=\"". get_bloginfo("wpurl") ."/profile-category/\">Directory</a><span class=\"divider\"> > </span>". $ProfileContactDisplay ."\n";
	echo "   <a href=\"". get_bloginfo("wpurl") ."/profile-category/\">Go Back</a>\n";
	echo " </div>\n";
	
	echo " <div class=\"col_12 column row-one clear\">\n";

	// Column 1
 	echo "	  <div id=\"profile-picture-wrap\" class=\"three column\">\n";

			// Profile Image
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" AND ProfileMediaPrimary = 1";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
	echo "	  		<div id=\"profile-picture\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			}
		
			/*  Use this instead of text;
			 *  this will display heart and star for favorite and casting respectively.
			 *  This can update database for favorites and casting cart
			 */
			echo '<input type="hidden" id="aps12-id" value="'. $ProfileID .' - ' .rb_agency_get_current_userid().'">';
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

			echo	'<div class="casting-bottom">
			 					<a title="'.$tl1.'" href="javascript:;" class="save_fav '.$cl1.'" id="'.$ProfileID.'"></a>
			 					<a title="'.$tl2.'" href="javascript:;" id="'.$ProfileID.'" class="save_cart '.$cl2.'"></a>
					</div>';

						echo '<div id="resultsGoHereAddtoCart"></div>';
	echo "	  </div> <!-- #profile-picture -->\n";

	// Column 2
 	echo "	  <div id=\"profile-overview\" class=\"col_6 column\">\n";

	echo "	      <div id=\"profile-name\"><h2>". $ProfileContactDisplay ."</h2></div>\n";
	echo "	      <div id=\"profile-liner\">\n";
					if (isset($ProfileDateBirth)) {
	echo "			<div class=\"profile-overview-age\">". rb_agency_get_age($ProfileDateBirth) ."</div>\n";
					}
					if (isset($ProfileLocationCity)) {
	echo "			<div class=\"profile-overview-from\"> from ". $ProfileLocationCity .", ". $ProfileLocationState ."</div>\n";
					}
	echo "	      </div>\n";
	echo "		  <div class=\"profile-overview-category\">\n";
		
					$queryType = "SELECT DataTypeTitle FROM ". table_agency_data_type ." WHERE DataTypeID IN ($ProfileType) ORDER BY DataTypeTitle";
					$resultsType = mysql_query($queryType);
					$countType = mysql_num_rows($resultsType);
					while ($dataType = mysql_fetch_array($resultsType)) {
						echo "<div class=\"profile-overview-category-type\">". $dataType["DataTypeTitle"] ."</div>";
					}
	
	echo "		  </div>\n";
        // Social Link
	 rb_agency_getSocialLinks();
	echo "		  <div class=\"profile-overview-experience\">". $ProfileExperience ."</div>\n";

	echo "	  </div> <!-- #profile-overview -->\n";


	// Column 3
 	echo "	  <div id=\"profile-actions\" class=\"col_3 column\">\n";

		//Contact Profile
		if (isset($rb_agency_option_agency_urlcontact) && !empty($rb_agency_option_agency_urlcontact)) {
	echo "	      <div id=\"profile-actions-contact\"><span><a href=\"". $rb_agency_option_agency_urlcontact ."\">". __("Contact", rb_agency_TEXTDOMAIN). " ". $ProfileClassification ."</a></span></div>\n";
		echo "		<li class=\"item contact\"></li>\n";
		}
	//echo "	      <div id=\"profile-actions-print\"><span>Print Friendly</span></div>\n";
	echo "	      <div id=\"profile-actions-profileviews\"><strong>". $ProfileStatHits ."</strong> Profile Views</div>\n";
	//echo "	      <div id=\"profile-actions-favorited\"><strong>".$count_favorite."</strong> favorited</div>\n";
	
    // added this links to be positioned here in substitute
    // for the favorited label
    echo '	      <div id="profile-actions-favorited">
					<a href="'.get_bloginfo('wpurl').'/profile-favorite">View Favorites</a> <br/>
					<a href="'.get_bloginfo('wpurl').'/profile-casting">View Casting Cart</a> 	
				  </div>';

	
	//echo "	      <div id=\"profile-actions-castings\"><strong>0</strong> castings</div>\n";
	//echo "	      <div id=\"profile-actions-recommendation\"><strong>0</strong> recommendation</div>\n";

	echo "	  </div> <!-- #profile-actions -->\n";

	echo " </div>\n"; // twelve column 1
	echo ' <div name="space" style="visibility:hidden">text</div>'; // twelve column 1
	echo " <div class=\"twelve column row-two clear\">\n";
	echo "   <div id=\"subMenuTab\">\n";
	echo " 		<div class=\"maintab tab-left tab-active\" id=\"row-all\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">All</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-photos\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Photos</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-physical\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\" ><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Physical Details</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-videos\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Videos</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-experience\">\n";
	echo " 			<a href=\"#space\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Experience</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-inner tab-inactive\" id=\"row-bookings\">\n";
	echo " 			<a href=\"javascript:;\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Booking</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";
	echo " 		<div class=\"maintab tab-right tab-inactive\" id=\"row-downloads\">\n";
	echo " 			<a href=\"javascript:;\">\n";
	echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">Downloads</div></div></div>\n";
	echo " 			</a>\n";
	echo " 		</div>\n";	
 	echo "   </div>\n";
	echo " </div>\n"; // twelve column 2

	echo " <div class=\"col_12 column row-photos clear tab\">\n";
	
			// images
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
			$resultsImg = mysql_query($queryImg);
			$countImg = mysql_num_rows($resultsImg);
			while ($dataImg = mysql_fetch_array($resultsImg)) {
			  if ($countImg > 1) { 
				echo "<div class=\"multiple\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  } else {
				echo "<div class=\"single\"><a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" rel=\"lightbox-profile". $ProfileID ."\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" /></a></div>\n";
			  }
			}

	echo " </div>\n"; // twelve column photos

	echo " <div class=\"col_12 column row-physical clear tab\">\n";
	echo "<ul>";
		if (!empty($ProfileGender)) {
			$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." WHERE GenderID='".$ProfileGender."' ");
			$fetchGenderData = mysql_fetch_assoc($queryGenderResult);
			echo "<li><strong>". __("Gender", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> ". __($fetchGenderData["GenderTitle"], rb_agency_TEXTDOMAIN). "</li>\n";
		}
	

		// Insert Custom Fields
		$title_to_exclude = array("Experience");
		rb_agency_getProfileCustomFieldsExTitle($ProfileID, $ProfileGender, $title_to_exclude);


		if($rb_agency_option_showcontactpage==1){
				echo "<li class=\"rel\"><strong>". __("Contact: ", rb_agency_TEXTDOMAIN). "<span class=\"divider\">:</span></strong> <a href=\"". get_bloginfo("wpurl") ."/profile/".$ProfileGallery	."/contact/\">Click Here</a></li>\n";
		}
	echo "</ul>";
	echo " </div>\n"; // twelve column physical

	echo " <div class=\"col_12 column row-videos clear tab\">\n";
	
				//Video Slate
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Slate\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "	  <div class=\"video slate col_4 column\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
				  }
				}

				//Video Monologue
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Video Monologue\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "	  <div class=\"video monologue col_4 column\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
				  }
				}

				//Demo Reel
				$resultsMedia = mysql_query("SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Demo Reel\"");
				$countMedia = mysql_num_rows($resultsMedia);
				if ($countMedia > 0) {
				  while ($dataMedia = mysql_fetch_array($resultsMedia)) {
					 $profileVideoEmbed = $dataMedia['ProfileMediaURL'];
				echo "	  <div class=\"video demoreel col_4 column\"><object width=\"350\" height=\"220\"><param name=\"movie\" value=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US&rel=0&showsearch=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/". $profileVideoEmbed ."?fs=1&amp;hl=en_US\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"350\" height=\"220\"></embed></object></div>\n";
				  }
				}


	echo " </div>\n"; // twelve column videos

	echo " <div class=\"col_12 column row-experience clear tab\">\n";

	$query1 ="SELECT c.ProfileCustomTitle, c.ProfileCustomOrder, cx.ProfileCustomValue FROM ". table_agency_customfield_mux ." cx LEFT JOIN ". table_agency_customfields ." c ON c.ProfileCustomID = cx.ProfileCustomID WHERE c.ProfileCustomView = 0 AND cx.ProfileID = ". $ProfileID ." ORDER BY c.ProfileCustomOrder DESC";
	$results1 = mysql_query($query1);
	$count1 = mysql_num_rows($results1);
	while ($data1 = mysql_fetch_array($results1)) {
	
			if ($data1['ProfileCustomTitle'] == "Experience"){
					
					echo "    <div class=\"inner experience-". $data1['ProfileCustomTitle'] ." clear\">\n";
					echo "		<h3>". $data1['ProfileCustomTitle'] ."</h3>\n";
					echo "		<p id=\"ProfileCustomID". $data1['ProfileCustomID'] ."\" name=\"ProfileCustomID". $data1['ProfileCustomID'] 
						 ."\" class=\"ProfileExperience\">". nl2br($data1['ProfileCustomValue']) ."</p>\n";
					echo "	  </div>\n";
			}
	}
	echo " </div>\n"; // twelve column experience


echo " <div class=\"row-bookings col_12 column clear tab\">\n";
echo " </div>\n"; // Row booking

    // added this section to be able to display downloadable 
	// files attached to a specific profile 
	echo " <div class=\"row-downloads col_12 column clear tab\">\n";

		echo "		<p>". __("The following files (pdf, audio file, etc.) are associated with this profile",
		        rb_agencyinteract_TEXTDOMAIN) .".</p>\n";
	
		$queryMedia = "SELECT * FROM ". table_agency_profile_media ." 
		              WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType <> \"Image\"";
		
		$resultsMedia = mysql_query($queryMedia);
		$countMedia = mysql_num_rows($resultsMedia);
		
		while ($dataMedia = mysql_fetch_array($resultsMedia)) {
				
				if ($dataMedia['ProfileMediaType'] == "Demo Reel" || 
				    $dataMedia['ProfileMediaType'] == "Video Monologue" || 
					$dataMedia['ProfileMediaType'] == "Video Slate") {
					
				$outVideoMedia .= "<div style=\"float: left; width: 120px; text-align: center; padding: 10px; \">"
				. $dataMedia['ProfileMediaType'] ."<br />". 
				rb_agency_get_videothumbnail($dataMedia['ProfileMediaURL']) 
				."<br /><a href=\"http://www.youtube.com/watch?v="
				. $dataMedia['ProfileMediaURL'] .
				"\" target=\"_blank\">Link to Video</a><br />[<a href=\"javascript:confirmDelete('".
				 $dataMedia['ProfileMediaID'] ."','".$dataMedia['ProfileMediaType'].
				 "')\">DELETE</a>]</div>\n";
				
				} elseif ($dataMedia['ProfileMediaType'] == "VoiceDemo") {
				
				$outLinkVoiceDemo .= "<div>". $dataMedia['ProfileMediaType'] .
				": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
				"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
				"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] 
				."','".$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
				
				} elseif ($dataMedia['ProfileMediaType'] == "Resume") {
				
				$outLinkResume .= "<div>". $dataMedia['ProfileMediaType'] 
				.": <a href=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
				"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
				"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
				$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
				
				} elseif ($dataMedia['ProfileMediaType'] == "Headshot") {
				
				$outLinkHeadShot .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"". 
				rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
				"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
				"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
				$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
				
				} elseif ($dataMedia['ProfileMediaType'] == "CompCard") {
				
				$outLinkComCard .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"". 
				rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
				"\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
				"</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
				$dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
				
				} else{
				
				$outCustomMediaLink .= "<div>". $dataMedia['ProfileMediaType'] .": <a href=\"".
				 rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] .
				 "\" target=\"_blank\">". $dataMedia['ProfileMediaTitle'] .
				 "</a> [<a href=\"javascript:confirmDelete('". $dataMedia['ProfileMediaID'] ."','".
				 $dataMedia['ProfileMediaType']."')\">DELETE</a>]</div>\n";
				
				}
		  }

		  echo '<div style=\"width:500px;\">';
		  echo $outLinkVoiceDemo;
		  echo '</div>';
		  echo '<div style=\"width:500px;\">';
		  echo $outLinkResume;
		  echo '</div>';
		  echo '<div style=\"width:500px;\">';
		  echo $outLinkHeadShot;
		  echo '</div>';
		  echo '<div style=\"width:500px;\">';
		  echo $outLinkComCard;
		  echo '</div>';
		  echo '<div style=\"width:500px;\">';
		  echo $outCustomMediaLink;
		  echo '</div>';
		  echo $outVideoMedia;
					
		  if ($countMedia < 1) {
			      echo "<div><em>". 
				       __("There are no additional media linked", rb_agencyinteract_TEXTDOMAIN) .
					   "</em></div>\n";
		  }

	echo " </div>\n"; // Download Tab

	echo "<div class=\"cb\"></div>\n"; // Clear All
	
	
	echo " </div>\n";  // Close Profile Layout
	echo "</div>\n";  // Close Profile
	echo "<div style=\"clear: both;\"></div>\n"; // Clear All

?>        