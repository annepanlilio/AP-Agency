<?php
/*
Title:  Flipbook
Author: RB Plugin
Text:   Flipbook
*/

/*
 * Insert Javascript into Head
 */
	wp_register_style( 'rblayout-style', plugins_url('/css/style.css', __FILE__) );
	wp_enqueue_style( 'rblayout-style' );


/*
 * Layout 
 */


?>

<div id="rbprofile">
	<div id="rblayout-eight" class="rblayout">
		<div class="col_12 column">
			<div id="layout-head">
				<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
				<a href="">Back to First Page</a>
			</div>
		</div>
		<div class="rbclear"></div>
	    <div class="col_12 column">
		    <div id="photobook">		    	
	            <?php
				$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
							$resultsImg = mysql_query($queryImg);
							$countImg = mysql_num_rows($resultsImg);
							while ($dataImg = mysql_fetch_array($resultsImg)) {
							  	echo "<div class=\"page\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></div>\n";
							}
				?>
		    </div><!-- .col_12 -->
	    </div><!-- #photobook -->
	    <div class="rbclear"></div>
	    <div class="col_12 column">
		    <div id="photobook-pagination" class="">
				<a href="" id="prev-page">Previous page</a>
				<a href="" id="next-page">Next Page</a>
			</div>
		</div>
		<div class="rbclear"></div>
		<div class="col_12 column">
			<ul id="profile-info">
				<?php
				// Insert Custom Fields
				rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender, $LabelTag="em");
				?>			
			</ul>
		</div>
	</div><!-- #rblayout-eight -->
</div><!-- #profile -->