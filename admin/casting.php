<?php $siteurl = get_option('siteurl'); ?>

<div class="wrap">
 	<div id="rb-overview-icon" class="icon32"></div>
 	<h2>Manage Castings</h2>

<?php
global $wpdb;

if (isset($_POST['action'])) {

	$CastingID					=$_POST['CastingID'];
	$CastingCompany				=$_POST['CastingCompany'];
	$CastingContactNameFirst	=$_POST['CastingContactNameFirst'];
	$CastingContactNameLast		=$_POST['CastingContactNameLast'];
	$CastingContactEmail		=$_POST['CastingContactEmail'];
	$CastingContactWebsite		=$_POST['CastingContactWebsite'];
	$CastingContactPhoneHome	=$_POST['CastingContactPhoneHome'];
	$CastingContactPhoneCell	=$_POST['CastingContactPhoneCell'];
	$CastingContactPhoneWork	=$_POST['CastingContactPhoneWork'];
	$CastingLocationCity		=rb_agency_strtoproper($_POST['CastingLocationCity']);
	$CastingLocationState		=strtoupper($_POST['CastingLocationState']);
	$CastingLocationZip			=$_POST['CastingLocationZip'];
	$CastingLocationCountry		=$_POST['CastingLocationCountry'];
	$CastingDateUpdated			=$_POST['CastingDateUpdated'];
	$CastingIsActive			=$_POST['CastingIsActive'];

	$action = $_POST['action'];
	switch($action) {

		// Add
		case 'addRecord':
			if (!empty($CastingContactNameFirst)) {
				
				// Create Record
				$insert = "INSERT INTO " . table_agency_casting .
				" (CastingContactNameFirst,CastingContactNameLast,
				   CastingContactEmail,CastingContactWebsite,
				   CastingContactPhoneHome, CastingContactPhoneCell, CastingContactPhoneWork,
				   CastingLocationCity,CastingLocationState,CastingLocationZip,CastingLocationCountry,
				   CastingDateUpdated,CastingIsActive,CastingCompany)" .
				"VALUES ('" . $wpdb->escape($CastingContactNameFirst) . "','" . $wpdb->escape($CastingContactNameLast) . "',
					'" . $wpdb->escape($CastingContactEmail) . "','" . $wpdb->escape($CastingContactWebsite) . "',
					'" . $wpdb->escape($CastingContactPhoneHome) . "','" . $wpdb->escape($CastingContactPhoneCell) . "','" . $wpdb->escape($CastingContactPhoneWork) . "',
					'" . $wpdb->escape($CastingLocationCity) . "','" . $wpdb->escape($CastingLocationState) . "','" . $wpdb->escape($CastingLocationZip) . "','" . $wpdb->escape($CastingLocationCountry) . "',
					'" . $wpdb->escape($CastingDateUpdated) . "','" . $wpdb->escape($CastingIsActive) . "','" . $wpdb->escape($CastingCompany) . "')";
			    $results = $wpdb->query($insert);
				$lastid = $wpdb->insert_id;

				echo ('<div id="message" class="updated"><p>'. __("New Casting added successfully!", rb_agency_TEXTDOMAIN) .' <a href="'. admin_url("admin.php?page=". $_GET['page']) .'&action=editRecord&xCastingID='. $lastid .'">'. __("Update and add media", rb_agency_TEXTDOMAIN) .'</a></p></div>'); 
			} else {
	       		echo ('<div id="message" class="error"><p>'. __("Error creating record, please ensure you have filled out all required fields.", rb_agency_TEXTDOMAIN) .'</p></div>'); 
			}
			rb_display_list();
			exit;
		break;
		
		// Edit
		case 'editRecord':
			if (!empty($CastingContactNameFirst) && !empty($CastingID)){
				// Update Record
				$update = "UPDATE " . table_agency_casting . " SET 
				CastingContactNameFirst='" . $wpdb->escape($CastingContactNameFirst) . "',
				CastingContactNameLast='" . $wpdb->escape($CastingContactNameLast) . "',
				CastingContactEmail='" . $wpdb->escape($CastingContactEmail) . "',
				CastingContactWebsite='" . $wpdb->escape($CastingContactWebsite) . "',
				CastingContactPhoneHome='" . $wpdb->escape($CastingContactPhoneHome) . "',
				CastingContactPhoneCell='" . $wpdb->escape($CastingContactPhoneCell) . "',
				CastingContactPhoneWork='" . $wpdb->escape($CastingContactPhoneWork) . "',
				CastingLocationCity='" . $wpdb->escape($CastingLocationCity) . "',
				CastingLocationState='" . $wpdb->escape($CastingLocationState) . "',
				CastingLocationZip ='" . $wpdb->escape($CastingLocationZip) . "',
				CastingLocationCountry='" . $wpdb->escape($CastingLocationCountry) . "',
				CastingDateUpdated='" . $wpdb->escape($CastingDateUpdated) . "',
				CastingIsActive='" . $wpdb->escape($CastingIsActive) . "',
				CastingCompany='" . $wpdb->escape($CastingCompany) . "'
				WHERE CastingID=$CastingID";
			  $results = $wpdb->query($update);

			  echo ('<div id="message" class="updated"><p>Casting updated successfully! <a href="'. admin_url("admin.php?page=". $_GET['page']) .'&action=editRecord&xCastingID='. $CastingID .'">Need to continue editing the record?</a></p></div>');
			} else {
	         	echo ('<div id="message" class="error"><p>Error updating record, please ensure you have filled out all required fields.</p></div>'); 
			}
			
			rb_display_list();
			exit;
		break;

		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $CastingID) {
				mysql_query("DELETE FROM " . table_agency_casting . " WHERE CastingID=$CastingID");
			}
			echo ('<div id="message" class="updated"><p>Casting deleted successfully!</p></div>');
			rb_display_list();
			exit;
		break;	
	}

} elseif (isset($_GET['deleteRecord'])) {

	$CastingID = $_GET['CastingID'];

	// Verify Record
	$queryDelete = "SELECT * FROM ". table_agency_casting ." WHERE CastingID =  \"". $CastingID ."\"";
	$resultsDelete = mysql_query($queryDelete);
	while ($dataDelete = mysql_fetch_array($resultsDelete)) {

		// Remove Casting
		$delete = "DELETE FROM " . table_agency_casting . " WHERE CastingID = \"". $CastingID ."\"";
		$results = $wpdb->query($delete);
		echo ('<div id="message" class="updated"><p>Casting deleted successfully!</p></div>');			
	}
			
	rb_display_list();

} elseif (($_GET['action'] == "editRecord") || ($_GET['action'] == "add")) {
	$action = $_GET['action'];?>

   	<p><a class="button-primary" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ?>">Back to Casting List</a></p>
	<?php

	if (($action == "editRecord") && !empty($_GET['xCastingID'])) {

		$xCastingID = $_GET['xCastingID'];
		$query = "SELECT * FROM " . table_agency_casting . " WHERE CastingID='$xCastingID'";
		$results = mysql_query($query) or die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$CastingID				=$data['CastingID'];
			$CastingContactNameFirst	=stripslashes($data['CastingContactNameFirst']);
			$CastingContactNameLast	=stripslashes($data['CastingContactNameLast']);
			$CastingContactEmail		=stripslashes($data['CastingContactEmail']);
			$CastingContactWebsite	=stripslashes($data['CastingContactWebsite']);
			$CastingContactPhoneHome	=stripslashes($data['CastingContactPhoneHome']);
			$CastingContactPhoneCell	=stripslashes($data['CastingContactPhoneCell']);
			$CastingContactPhoneWork	=stripslashes($data['CastingContactPhoneWork']);
			$CastingLocationCity		=stripslashes($data['CastingLocationCity']);
			$CastingLocationState	=stripslashes($data['CastingLocationState']);
			$CastingLocationZip		=stripslashes($data['CastingLocationZip']);
			$CastingLocationCountry	=stripslashes($data['CastingLocationCountry']);
			$CastingDateUpdated		=stripslashes($data['CastingDateUpdated']);
			$CastingIsActive			=stripslashes($data['CastingIsActive']);
			$CastingCompany		=stripslashes($data['CastingCompany']);
		?>
		<h3 class="title">Edit Casting</h3>
		<p>Make changes in the form below to edit a Casting. <strong>Required fields are marked *</strong></p>
		<?php }

		} else {
		$CastingsModelDate = $date; 
		$CastingIsModel = 1;
		$CastingGender = "Unknown";
		$CastingIsActive = 1;
		?>
		<h3 class="title">Add New Casting</h3>
		<p>Fill in the form below to add a new Casting. <strong>Required fields are marked *</strong></p>
		<?php 
		} 
	?>

	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">

	   	<h3>Contact Information</h3>
		<table class="form-table">
			<tbody>
		       	<tr valign="top">
					<th scope="row">Company Name:</th>
					<td>
						<input type="text" id="CastingCompany" name="CastingCompany" value="<?php echo $CastingCompany; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">First Name</th>
					<td>
						<input type="text" id="CastingContactNameFirst" name="CastingContactNameFirst" value="<?php echo $CastingContactNameFirst; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">Last Name</th>
					<td>
						<input type="text" id="CastingContactNameLast" name="CastingContactNameLast" value="<?php echo $CastingContactNameLast; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">Email Address</th>
					<td>
						<input type="text" id="CastingContactEmail" name="CastingContactEmail" value="<?php echo $CastingContactEmail; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">Phone Numbers</th>
					<td>
						Home: <input type="text" style="width: 100px;" id="CastingContactPhoneHome" name="CastingContactPhoneHome" value="<?php echo $CastingContactPhoneHome; ?>" /><br />
						Cell: <input type="text" style="width: 100px;" id="CastingContactPhoneCell" name="CastingContactPhoneCell" value="<?php echo $CastingContactPhoneCell; ?>" /><br />
						Work: <input type="text" style="width: 100px;" id="CastingContactPhoneWork" name="CastingContactPhoneWork" value="<?php echo $CastingContactPhoneWork; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">Website</th>
					<td>
						<input type="text" id="CastingContactWebsite" name="CastingContactWebsite" value="<?php echo $CastingContactWebsite; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">City</th>
					<td>
						<input type="text" id="CastingLocationCity" name="CastingLocationCity" value="<?php echo rb_agency_strtoproper($CastingLocationCity); ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">State</th>
					<td>
						<input type="text" id="CastingLocationState" name="CastingLocationState" value="<?php echo strtoupper($CastingLocationState); ?>" />
					</td>
				</tr>
		      	<tr valign="top">
					<th scope="row">Zip</th>
					<td>
						<input type="text" id="CastingLocationZip" name="CastingLocationZip" value="<?php echo $CastingLocationZip; ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<th scope="row">Country</th>
					<td>
						<input type="text" id="CastingLocationCountry" name="CastingLocationCountry" value="<?php if (isset($CastingLocationCountry)) { echo $CastingLocationCountry; } else { echo "USA"; } ?>" />
					</td>
				</tr>
		       	<tr valign="top">
					<td colspan2="row"><h3>Account Information</h3></td>
				</tr>
		       	<tr valign="top">
					<th scope="row">Active</th>
					<td>
		           		<select id="CastingIsActive" name="CastingIsActive">
		                   	<option>--</option>
							<option value="1"<?php if ($CastingIsActive == 1) { echo " selected=selected"; }; ?>> Yes, Active</option>
							<option value="0"<?php if ($CastingIsActive == 0) { echo " selected=selected"; }; ?>> No, Inactive</option>
							<option value="2"<?php if ($CastingIsActive == 2) { echo " selected=selected"; }; ?>> No, Declassified</option>
		               	</select>
					</td>
				</tr>
		       	<?php if ($_GET["mode"] == "override") { ?>
		       	<tr valign="top">
					<th scope="row">Date Updated</th>
					<td>
						<input type="text" id="CastingDateUpdated" name="CastingDateUpdated" value="<?php echo $CastingDateUpdated; ?>" />
					</td>
				</tr>
		       	<?php } else { ?>
		       	<tr valign="top">
					<th></th>
					<td>
						<input type="hidden" id="CastingDateUpdated" name="CastingDateUpdated" value="<?php echo $CastingDateUpdated; ?>" />
						<input type="hidden" id="CastingStatHits"  name="CastingStatHits" value="<?php echo $CastingStatHits; ?>" />
					</td>
				</tr>
		       	<?php } ?>
	       	</tbody>
	   	</table>

		<?php if ($_GET['action'] == "editRecord") { ?>
	       	<p class="submit">
	           	<input type="hidden" value="editRecord" name="action" />
	           	<input type="hidden" value="<?php echo $CastingID; ?>" name="CastingID" />
	           	<input type="submit" value="Submit" class="button-primary" name="Update Casting Record" />
	       	</p>
	   <?php } else { ?>
	       	<p class="submit">
	           	<input type="hidden" value="addRecord" name="action" />
	           	<input type="submit" value="Submit" class="button-primary" name="Create Casting Record"  />
	       	</p>
	   <?php } ?>
   	</form>

<?php 
} else {
	
	rb_display_list();
	
}

function rb_display_list() { ?>

   <h3 class="title">All Castings</h3>
   <p><a class="button-primary" href="<?php echo admin_url("admin.php?page=". $_GET['page']) ?>&action=add">Create New Record</a></p>

       	<?php 
       	global $wpdb;
		
		// Sort By
       	$sort = "";
       	if (isset($_GET['sort']) && !empty($_GET['sort'])){
       	    $sort = $_GET['sort'];
       	}
       	else {
           $sort = "CastingContactNameFirst";
       	}
		
		// Sort Order
       	$dir = "";
       	if (isset($_GET['dir']) && !empty($_GET['dir'])){
           	$dir = $_GET['dir'];
           	if ($dir == "desc" || !isset($dir) || empty($dir)){
             	$sortDirection = "asc";
          	} else {
              	$sortDirection = "desc";
           	} 
		} else {
			$sortDirection = "desc";
			$dir = "asc";
		}
 	
		// Filter
		$filter = " WHERE CastingID > 0";
       	if ((isset($_GET['CastingContactNameFirst']) && !empty($_GET['CastingContactNameFirst'])) || isset($_GET['CastingContactNameLast']) && !empty($_GET['CastingContactNameLast'])){
	       	if (isset($_GET['CastingContactNameFirst']) && !empty($_GET['CastingContactNameFirst'])){
				$selectedNameFirst = $_GET['CastingContactNameFirst'];
				$filter .= " AND CastingContactNameFirst='". $selectedNameFirst ."'";
	        }
	       	if (isset($_GET['CastingContactNameLast']) && !empty($_GET['CastingContactNameLast'])){
				$selectedNameLast = $_GET['CastingContactNameLast'];
				$filter .= " AND CastingContactNameLast='". $selectedNameLast ."'";
	        }
		}
		if (isset($_GET['CastingLocationCity']) && !empty($_GET['CastingLocationCity'])){
			$selectedCity = $_GET['CastingLocationCity'];
			$filter .= " AND CastingLocationCity='". $selectedCity ."'";
		}
       ?>
       	<table cellspacing="0" class="widefat fixed">
       		<thead>
				<tr>
					<td style="width: 860px;" nowrap="nowrap">                   
						<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
                        <input type='hidden' name='page_index' id='page_index' value='<?php echo $_GET['page_index']; ?>' />  
                        <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />
                        <input type="hidden" name="type" value="name" />
						 Search by : 
						 First Name: <input type="text" name="CastingContactNameFirst" value="<?php echo $selectedNameFirst; ?>" style="width: 100px;" />
						 Last Name:  <input type="text" name="CastingContactNameLast" value="<?php echo $selectedNameLast; ?>" style="width: 100px;" />
						 Location :  <select name="CastingLocationCity">
							<?php
								global $wpdb;
								table_agency_casting = $wpdb->prefix . "modelagency_casting";

								$CastingLocations = mysql_query("SELECT DISTINCT CastingLocationCity, CastingLocationState FROM ". table_agency_casting ."");
									echo "<option value=\"\">Any Location</option>";
								while ($dataLocation = mysql_fetch_array($CastingLocations)) {
								  	if (isset($_GET['CastingLocationCity']) && !empty($_GET['CastingLocationCity']) && $selectedCity == $dataLocation["CastingLocationCity"]) {
										echo "<option value=\"". $dataLocation["CastingLocationCity"] ."\" selected>". rb_agency_strtoproper($dataLocation["CastingLocationCity"]) .", ". strtoupper($dataLocation["CastingLocationState"]) ."</option>";
								  	} else {
										echo "<option value=\"". $dataLocation["CastingLocationCity"] ."\">". rb_agency_strtoproper($dataLocation["CastingLocationCity"]) .", ". strtoupper($dataLocation["CastingLocationState"]) ."</option>";
								  	}
								}
							?>
							</select>
							<input type="submit" value="Filter" class="button-primary" />
						</form>
					</td>
					<td style="width: 300px;" nowrap="nowrap">
						<form method="GET" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">
                        <input type='hidden' name='page_index' id='page_index' value='<?php echo $_GET['page_index']; ?>' />  
                        <input type='hidden' name='page' id='page' value='<?php echo $_GET['page']; ?>' />
					 	<input type="submit" value="Clear Filters" class="button-secondary" />
						</form>
					</td>
					<td>&nbsp;</td>
				</tr>
       		</thead>
      	</table>
       
   	<form method="post" action="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>">	
       	<table cellspacing="0" class="widefat fixed">
       		<thead>
           		<tr class="thead">
	               	<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
	               	<th class="column-CastingID" id="CastingID" scope="col" style="width:50px;"><a href="admin.php?page=modelagency_casting&sort=CastingID&dir=<?php echo $sortDirection; ?>">ID</a></th>
	               	<th class="column-CastingContactNameFirst" id="CastingContactNameFirst" scope="col" style="width:130px;"><a href="admin.php?page=modelagency_casting&sort=CastingContactNameFirst&dir=<?php echo $sortDirection; ?>">First Name</a></th>
	               	<th class="column-CastingContactNameLast" id="CastingContactNameLast" scope="col" style="width:130px;"><a href="admin.php?page=modelagency_casting&sort=CastingContactNameLast&dir=<?php echo $sortDirection; ?>">Last Name</a></th>
	               	<th class="column-CastingLocationCity" id="CastingLocationCity" scope="col" style="width:100px;"><a href="admin.php?page=modelagency_casting&sort=CastingLocationCity&dir=<?php echo $sortDirection; ?>">City</a></th>
	               	<th class="column-CastingLocationState" id="CastingLocationState" scope="col" style="width:50px;"><a href="admin.php?page=modelagency_casting&sort=CastingLocationState&dir=<?php echo $sortDirection; ?>">State</a></th>
	               	<th class="column-CastingCompany" id="CastingCompany" scope="col">Last Viewed Date</th>
	           </tr>
	       	</thead>
	       	<tbody>
		       	<?php
		       	$query = "SELECT * FROM ". table_agency_casting . $filter ." ORDER BY $sort $dir";
		       	$results2 = mysql_query($query);
		       	$count = mysql_num_rows($results2);
		       	while ($data = mysql_fetch_array($results2)) {
		           
		           	$CastingID = $data['CastingID'];
		           	$CastingCompany = stripslashes($data['CastingCompany']);
		           	$CastingContactNameFirst = stripslashes($data['CastingContactNameFirst']);
		           	$CastingContactNameLast = stripslashes($data['CastingContactNameLast']);
		           	$CastingLocationCity = rb_agency_strtoproper(stripslashes($data['CastingLocationCity']));
		           	$CastingLocationState = stripslashes($data['CastingLocationState']);
					if ($data['CastingIsActive']) { $rowColor = ""; } else { $rowColor = " style=\"background: #FFEBE8\""; } ?>
			       	<tr<?php echo $rowColor; ?>>
			           	<th class="check-column" scope="row">
			               	<input type="checkbox" value="<?php echo $CastingID; ?>" class="administrator" id="<?php echo $CastingID; ?>" name="<?php echo $CastingID; ?>"/>
			           	</th>
			           	<td class="CastingID column-CastingID">
			               	<?php echo $CastingID; ?>
			           	</td>
			           	<td class="CastingID column-CastingCompany">
			               	<?php echo $CastingCompany; ?>
			               	<div class="row-actions">
			                   	<span class="edit"><a href="<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;action=editRecord&amp;xCastingID=<?php echo $CastingID; ?>" title="Edit this post">Edit</a> | </span>
			                   	<span class="delete"><a class='submitdelete' title='Delete this Casting' href='<?php echo admin_url("admin.php?page=". $_GET['page']); ?>&amp;deleteRecord&amp;CastingID=<?php echo $CastingID; ?>' onclick="if ( confirm('You are about to delete the record? \'<?php echo $CastingContactNameFirst ." ". $CastingContactNameLast; ?>\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
			               	</div>
			           	</td>
			           	<td class="CastingContactNameFirst column-CastingContactNameFirst">
			              	<?php echo $CastingContactNameFirst; ?>
			           	</td>
			           	<td class="CastingContactNameLast column-CastingContactNameLast">
			               	<?php echo $CastingContactNameLast; ?>
			           	</td>
			           	<td class="CastingLocationCity column-CastingLocationCity">
			               	<?php echo $CastingLocationCity; ?>
			           	</td>
			           	<td class="CastingLocationCity column-CastingLocationState">
			              	<?php echo $CastingLocationState; ?>
			           	</td>
			       	</tr>
		       	<?php
		       	}
		           	mysql_free_result($results2);
		           	if ($count < 1) {
						if (isset($filter)) { ?>
		       	<tr>
		           	<th class="check-column" scope="row"></th>
		           	<td class="name column-name" colspan="5">
		               	<p>No Castings found with this criteria.</p>
		           	</td>
		       	</tr>
		       	<?php
						} else {
		       	?>
		       	<tr>
		           	<th class="check-column" scope="row"></th>
		           	<td class="name column-name" colspan="5">
	               		<p>There aren't any Castings loaded yet!</p>
		           	</td>
		       	</tr>
		       <?php
							
						}
		       ?>
		       <?php 
		   			} ?>
	       	</tbody>
	       	<tfoot>
	           	<tr class="thead">
	               	<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
	               	<th class="column-CastingID" id="CastingID" scope="col">ID</th>
	               	<th class="column-CastingCompany" id="CastingCompany" scope="col">Last Viewed</th>
	               	<th class="column-CastingContactNameFirst" id="CastingContactNameFirst" scope="col">First Name</th>
	               	<th class="column-CastingContactNameLast" id="CastingContactNameLast" scope="col">Last Name</th>
	               	<th class="column-CastingLocationCity" id="CastingLocationCity" scope="col">City</th>
	               	<th class="column-CastingLocationState" id="CastingLocationState" scope="col">State</th>
	           	</tr>
	       	</tfoot>
   
   		</table>
		<p class="submit">
			<input type="hidden" value="deleteRecord" name="action" />
			<input type="submit" value="<?php echo __('Delete','modelagency_casting'); ?>" class="button-primary" name="submit" />		
		</p>
   </form>
<?php } ?>
</div>