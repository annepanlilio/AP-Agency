<div class="wrap">        
    <?php 
    // Include Admin Menu
    include ("admin-menu.php"); ?>


<?php
	global $wpdb;
	$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype = $rb_agency_options_arr['rb_agency_option_unittype'];
	get_currentuserinfo(); global $user_level;
?>


<div id="welcome-panel" class="welcome-panel">
	<div class="welcome-panel-content">


		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<h3><?php echo __("Welcome to RB Agency", rb_agency_TEXTDOMAIN ) ?>!</h3>
				<p class="about-description"><?php echo __("We have added some resources below to help you get started.", rb_agency_TEXTDOMAIN ) ?></p>
				<h4><?php echo __("Quick Links", rb_agency_TEXTDOMAIN ) ?></h4>
				<ul>
					<?php
					if ($user_level >= 7) {
						echo "<li><a href='?page=rb_agency_profiles' class=\"button-secondary\">". __("Manage Profiles", rb_agency_TEXTDOMAIN) . "</a> - ". __("Manage existing profiles", rb_agency_TEXTDOMAIN) . ".</li>";

						$queryGenderResult = mysql_query("SELECT GenderID, GenderTitle FROM ".table_agency_data_gender." ");
						$queryGenderCount = mysql_num_rows($queryGenderResult);

						while($fetchGender = mysql_fetch_assoc($queryGenderResult)){
						 echo "<li><a class=\"button-secondary\" href=\"". admin_url("admin.php?page=rb_agency_profiles&action=add&ProfileGender=".$fetchGender["GenderID"])."\">". __("Create New ".ucfirst($fetchGender["GenderTitle"])."", rb_agency_TEXTDOMAIN) ."</a></li>\n";
						}
						if($queryGenderCount < 1){
						echo "<li>". __("No Gender Found. <a href=\"". admin_url("admin.php?page=rb_agency_settings&ampConfigID=5")."\">Create New Gender</a>", rb_agency_TEXTDOMAIN) ."</li>\n";
						} 

						echo "<li><a href='?page=rb_agency_search' class=\"button-secondary\">". __("Search Profiles", rb_agency_TEXTDOMAIN) . "</a> - ". __("Find profiles", rb_agency_TEXTDOMAIN) . ".</li>";
					}
					?>
				</ul>
			</div>


			<div class="welcome-panel-column" style="margin-left: 50px;">
				<iframe src="http://player.vimeo.com/video/27752740" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			</div>

		</div>
	</div>
</div>


<div id="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder columns-2">

		<div id="postbox-container-1" class="postbox-container">
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">

				<div id="dashboard_right_now" class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Quick Search", rb_agency_TEXTDOMAIN ) ?></span></h3>
					<div class="inside">
						<?php
						   if ($user_level >= 7) {
						   	?>
							<form method="GET" action="<?php echo admin_url("admin.php?page=rb_agency_search") ?>">
							<input type="hidden" name="page" id="page" value="rb_agency_search" />
							<input type="hidden" name="action" value="search" />
							<table class="form-table">
							<tbody>
							  <tr valign="top">
								<th scope="row"><label for="blogname"><?php echo  __("First Name", rb_agency_TEXTDOMAIN); ?></label></th>
								<td><input type="text" name="ProfileContactNameFirst" value="<?php echo $_SESSION['ProfileContactNameFirst']; ?>" class="regular-text" /></td>
							  </tr>

							<?php
							// @ToDo
							echo "				    <tr>\n";
							echo "				        <th scope=\"row\">". __("Last Name", rb_agency_TEXTDOMAIN) . ":</th>\n";
							echo "				        <td><input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $_SESSION['ProfileContactNameLast'] ."\" />\n";               
							echo "				        </td>\n";
							echo "				    </tr>\n";
							
							echo "				    <tr>\n";
							echo "				        <th scope=\"row\">". __("Classification", rb_agency_TEXTDOMAIN) . ":</th>\n";
							echo "				        <td><select name=\"ProfileType\" id=\"ProfileType\">\n";               
							echo "							<option value=\"\">". __("Any Profile Type", rb_agency_TEXTDOMAIN) . "</option>";
															$query = "SELECT DataTypeID, DataTypeTitle FROM ". table_agency_data_type ." ORDER BY DataTypeTitle";
															$results2 = mysql_query($query);
															while ($dataType = mysql_fetch_array($results2)) {
																if ($_SESSION['ProfileType']) {
																	if ($dataType["DataTypeID"] ==  $_SESSION['ProfileType']) { $selectedvalue = " selected"; } else { $selectedvalue = ""; } 
																} else { $selectedvalue = ""; }
																echo "<option value=\"". $dataType["DataTypeID"] ."\"".$selectedvalue.">". $dataType["DataTypeTitle"] ."</option>";
															}
							echo "				        	</select></td>\n";
							echo "				        </td>\n";
							echo "				    </tr>\n";
							echo "				    <tr>\n";
							echo "				        <th scope=\"row\">". __("Gender", rb_agency_TEXTDOMAIN) . ":</th>\n";
							echo "				        <td><select name=\"ProfileGender\" id=\"ProfileGender\">\n";       
										$query1 = "SELECT GenderID, GenderTitle FROM ". table_agency_data_gender ."";
										$results1 = mysql_query($query1);
										$count1 = mysql_num_rows($results1);
										if ($count1 > 0) {
											echo " <option value=\"\">All Gender</option>";
											while ($data1 = mysql_fetch_array($results1)) {
												
												echo " <option value=\"". $data1["GenderID"] ."\" ". selected( $_SESSION['ProfileGender'], $data1["GenderID"]) .">". $data1["GenderTitle"] ."</option>\n";
											}
											echo "</select>\n";
										} else {
											echo "". __("No items to select", rb_restaurant_TEXTDOMAIN) .".";
										}
							echo "				        </td>\n";
							echo "				    </tr>\n";
							echo "				    <tr>\n";
							echo "				        <th scope=\"row\">". __("Age", rb_agency_TEXTDOMAIN) . ":</th>\n";
							echo "				        <td>\n";
							echo "				        <fieldset>\n";
							echo "				        	<div><label>". __("Min", rb_agency_TEXTDOMAIN) . "</label>\n";
							echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_min\" name=\"ProfileDateBirth_min\" value=\"". $_SESSION['ProfileDateBirth_min'] ."\" /><br /></div>\n";
							echo "				        	<div><label>". __("Max", rb_agency_TEXTDOMAIN) . "</label>\n";
							echo "				        	<input type=\"text\" class=\"stubby\" id=\"ProfileDateBirth_max\" name=\"ProfileDateBirth_max\" value=\"". $_SESSION['ProfileDateBirth_max'] ."\" /></div>\n";
							echo "				        </fieldset>\n";
							echo "				        </td>\n";
							echo "				    </tr>\n";
							echo "				  </thead>\n";
							echo "				</table>\n";
							echo "				<p class=\"submit\">\n";
							echo "				<input type=\"submit\" value=\"". __("Quick Search", rb_agency_TEXTDOMAIN) . "\" class=\"button-primary\" />\n";
							echo "				<a href=\"?page=rb_agency_search\" class=\"button-secondary\">". __("Advanced Search", rb_agency_TEXTDOMAIN) . "</a></p>\n";
							echo "				</p>\n";
							echo "        	<form>\n";
							
						   } // Editor  
						?>
					</div>
				</div>

			</div>
		</div>

		<div id="postbox-container-2" class="postbox-container">
			<div id="side-sortables" class="meta-box-sortables ui-sortable">

				<div id="dashboard_recent_drafts" class="postbox" style="display: block;">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Recently Updated Profiles", rb_agency_TEXTDOMAIN ) ?></span></h3>
					<div class="inside">
						<ul>
						<?php
						if ($user_level >= 7) {
							// Recently Updated
							$query = "SELECT ProfileID, ProfileContactNameLast, ProfileContactNameLast, ProfileDateUpdated FROM ". table_agency_profile ." ORDER BY ProfileDateUpdated DESC LIMIT 0,10";
							$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
							$count = mysql_num_rows($results);
							while ($data = mysql_fetch_array($results)) { ?>
								<li>
									<a href="?page=rb_agency_profiles&action=editRecord&ProfileID=<?php echo $data['ProfileID']; ?>"><?php echo stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) ?></a>
							    	<span class="add-new-h2">Updated <?php echo rb_agency_makeago(rb_agency_convertdatetime($data['ProfileDateUpdated'])); ?></span>
								</li><?php
							}
							mysql_free_result($results);
							if ($count < 1) {
								echo "". __("There are currently no profiles added", rb_agency_TEXTDOMAIN) . ".";
							}
						}
						?>
						</ul>
					</div>
				</div>

				<div id="dashboard_recent_drafts" class="postbox" style="display: block;">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php echo __("Recently Viewed Profiles", rb_agency_TEXTDOMAIN ) ?></span></h3>
					<div class="inside">
						<ul>
						<?php
						if ($user_level >= 7) {
							// Recently Viewed
							$query = "SELECT ProfileID, ProfileContactNameFirst, ProfileContactNameLast, ProfileDateViewLast, ProfileStatHits FROM ". table_agency_profile ." ORDER BY ProfileDateViewLast DESC LIMIT 0,10";
							$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
							$count = mysql_num_rows($results);
							while ($data = mysql_fetch_array($results)) { 
								//$data['ProfileDateViewLast']
								?>
								<li>
									<a href="?page=rb_agency_profiles&action=editRecord&ProfileID=<?php echo $data['ProfileID']; ?>"><?php echo stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']); ?></a>
							    	<span class="add-new-h2"><?php echo $data['ProfileStatHits']; ?> <?php echo __("Views", rb_agency_TEXTDOMAIN ) ?></span>
							    	<span class="add-new-h2">Last viewed <?php echo rb_agency_makeago(rb_agency_convertdatetime($data['ProfileDateViewLast'])); ?></span>
								</li><?php
							}
							mysql_free_result($results);
							if ($count < 1) {
								echo "". __("There are currently no profiles added", rb_agency_TEXTDOMAIN) . ".";
							}
						}
						?>
						</ul>
					</div>
				</div>

			</div>
		</div>
		<div class="clear"></div>

	</div>
</div>