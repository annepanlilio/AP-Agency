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

# rb_agency_option_galleryorder
$rb_agency_options_arr = get_option('rb_agency_options');
$order = $rb_agency_options_arr['rb_agency_option_galleryorder'];
?>

<div id="rbprofile">
	<div id="rblayout-eight" class="rblayout">
		<div class="rbcol-12 rbcolumn">
			<div id="layout-head">
				<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
				<a href="">Back to First Page</a>
			</div>
		</div>
		<div class="rbclear"></div>
	    <div class="rbcol-12 rbcolumn">
		    <div id="photobook">		    	
	            <?php

				$queryImg = rb_agency_option_galleryorder_query($order ,$ProfileID,"Image");
				 $resultsImg=  $wpdb->get_results($wpdb->prepare($queryImg),ARRAY_A);			
						$countImg =$wpdb->num_rows;
						foreach($resultsImg as $dataImg ){
						  	echo "<div class=\"page\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></div>\n";
						}
				?>
		    </div><!-- .rbcol-12 -->
	    </div><!-- #photobook -->
	    <div class="rbclear"></div>
	    <div class="rbcol-12 rbcolumn">
		    <div id="photobook-pagination" class="">
				<a href="" id="prev-page">Previous page</a>
				<a href="" id="next-page">Next Page</a>
			</div>
		</div>
		<div class="rbclear"></div>
		<div class="rbcol-12 rbcolumn">
			<ul id="profile-info">
				<?php
				// Insert Custom Fields
				rb_agency_getNewProfileCustomFields($ProfileID, $ProfileGender, $LabelTag="em");
				?>			
			</ul>
		</div>
	</div><!-- #rblayout-eight -->
</div><!-- #profile -->