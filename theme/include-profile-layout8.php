<?php
/*
Flip Book
*/

?>

<div id="profile">
	<div id="rblayout-eight" class="rblayout">
		<div id="layout-head" class="">
			<?php echo " <h1>". $ProfileContactDisplay ."</h1>\n"; ?>
			<a href="">Back</a>
		</div>
		<div class="cb"></div>
	    <div id="photobook">
	    	<div class="page cover">
	    		<div class="branding" style="background-image: url(<?php bloginfo('stylesheet_directory');?>/images/logo.png)"></div>
	    	</div>
            <?php
			$queryImg = "SELECT * FROM ". table_agency_profile_media ." media WHERE ProfileID =  \"". $ProfileID ."\" AND ProfileMediaType = \"Image\" ORDER BY $orderBy";
						$resultsImg = mysql_query($queryImg);
						$countImg = mysql_num_rows($resultsImg);
						while ($dataImg = mysql_fetch_array($resultsImg)) {
						  	echo "<div class=\"page\"><img src=\"". rb_agency_UPLOADDIR . $ProfileGallery ."/". $dataImg['ProfileMediaURL'] ."\" alt=\"". $ProfileContactDisplay ."\" /></div>\n";
						}
			?>
	    </div><!-- #photobook -->
	    <div class="cb"></div>
	    <div id="photobook-pagination" class="">
			<a href="" id="prev-page">Previous page</a>
			<a href="" id="next-page">Next Page</a>
		</div>
		<div class="cb"></div>
		<div id="modelstats">
			<div class="stat">
				<span>height</span>
				<p>5'10</p>
			</div>
			<div class="stat">
				<span>bust</span>
				<p>34</p>
			</div>
			<div class="stat">
				<span>waist</span>
				<p>25</p>
			</div>
			<div class="stat">
				<span>hips</span>
				<p>34</p>
			</div>
			<div class="stat">
				<span>dress</span>
				<p>2/4</p>
			</div>
			<div class="stat">
				<span>shoe</span>
				<p>9.5</p>
			</div>
			<div class="stat">
				<span>hair</span>
				<p>brown</p>
			</div>
			<div class="stat">
				<span>eyes</span>
				<p>hazel</p>
			</div>
			<div class="stat">
				<span>office(s)</span>
				<p>chicago</p>
			</div>
			<div class="stat">
				<span>division(s)</span>
				<p>fashion</p>
			</div>
		</div>
	</div><!-- #rblayout-eight -->
</div><!-- #profile -->