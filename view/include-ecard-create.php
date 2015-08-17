

<div style="clear:both"></div>
	<div class="wrap" style="min-width: 1020px;">
		<div id="rb-overview-icon" class="icon32"></div>
		<h2>Create e-Cards</h2>

	generateEcards
	</div>
	<div style="clear:both"></div>
	
	<?php
	$cartArray = isset($_SESSION['cartArray'])?$_SESSION['cartArray']:array();
				$cartString = implode(",", array_unique($cartArray));
				//$cartString = RBAgency_Common::clean_string($cartString);
				
				
			print_r($cartString );	
			print_r($cartArray);	
			
			
			
			$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (".$cartString.") GROUP BY profile.ProfileID ORDER BY profile.ProfileID ASC";
				//$query = "SELECT profile.ProfileID, profile.ProfileGallery, profile.*, profile.ProfileContactDisplay, profile.ProfileDateBirth, profile.ProfileLocationState, profile.ProfileID as pID,  (SELECT media.ProfileMediaURL FROM ". table_agency_profile_media ." media WHERE profile.ProfileID IN(".$cartString.") AND profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1) AS ProfileMediaURL FROM ". table_agency_profile ." profile GROUP BY profile.ProfileID";

				$results = $wpdb->get_results($query,ARRAY_A);// or  die( "<a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("No profile selected. Try again", RBAGENCY_TEXTDOMAIN) ."</a>"); //die ( __("Error, query failed", RBAGENCY_TEXTDOMAIN ));
				$count = count($results);
				
					print_r($count);	
					print_r($results);	
					
					exit;
				
/* 
				
				
			
			$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
								$resultsImg = $wpdb->get_results($queryImg,ARRAY_A);
								$countImg =$wpdb->num_rows;
								$massDelete = "";
								echo "<div id='wrapper-sortable'><div id='gallery-sortable' style='list-style:none;'>";
								foreach ($resultsImg as $dataImg) {
									if ($dataImg['ProfileMediaPrimary']) {
										$toggleClass = " primary";
										$isChecked = " checked";
										$isCheckedText = " Primary";
										if ($countImg == 1) {
											$toDelete = "<a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
											$massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> Select';
										} else {
											$toDelete = "<a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
											$massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> Select';
										}
									} else {
										$toggleClass = "";
										$isChecked = "";
										$isCheckedText = " Set Primary";
										$toDelete = "<a href=\"javascript:confirmDelete('" . $dataImg['ProfileMediaID'] . "','" . $dataImg['ProfileMediaType'] . "')\" title=\"Delete this Photo\" class=\"rbicon-del icon-small\"><span>Delete</span> &raquo;</a>\n";
										$massDelete = '<input type="checkbox" name="massgaldel" value="' . $dataImg['ProfileMediaID'] . '"> Select';
									}
									echo "<div class=\"item gallery-item".$toggleClass."\">\n";

									echo $toDelete;
									// <img src=\"". get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=".RBAGENCY_UPLOADDIR . $ProfileGallery ."/". $dataMedia['ProfileMediaURL'] ."&a=t&w=120&h=108\" /></a><br />[<a href=\"javascript:confirmDelete('" . $dataMedia['ProfileMediaID'] . "','" . $dataMedia['ProfileMediaType'] . "')\" title=\"Delete this File\" class=\"delete-file\">DELETE</a>]</div>\n";
									$image_path = RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataImg['ProfileMediaURL'];
									$params = array(
										'width' => 100,
										'height' => 150
									);
									$profile_image_src = bfi_thumb( $image_path, $params );
									echo "  <div class=\"photo\"><img src=\"" . $profile_image_src ."\"/></div>\n";
									//echo "  <div class=\"photo\"><img src=\"" . get_bloginfo("url")."/wp-content/plugins/rb-agency/ext/timthumb.php?src=". RBAGENCY_UPLOADDIR . $ProfileGallery . "/" . $dataImg['ProfileMediaURL'] . "&a=t&w=100&h=150\"/></div>\n";
									echo "		<div class=\"item-order\" style='display:none;'>Order: <input type=\"hidden\" name=\"ProfileMediaOrder_" . $dataImg['ProfileMediaID'] . "\" style=\"width: 25px\" value=\"" . $dataImg['ProfileMediaOrder'] . "\" /></div>";
									echo "  	<div class=\"make-primary\"><input type=\"radio\" name=\"ProfileMediaPrimary\" value=\"" . $dataImg['ProfileMediaID'] . "\" " . $isChecked . " /> " . $isCheckedText . "</div>";
									echo "		<div>".$massDelete."</div>";
									echo "  </div>\n";
								}
								echo "</div></div>"; */
								
		?>		
	wow