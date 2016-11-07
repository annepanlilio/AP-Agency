<?php
		echo $rb_header = RBAgency_Common::rb_header();
		echo "<div>
			   <div id=\"rbcontent\">
					<div class='restricted'>";
		echo __("Woops! The profile you are looking for no longer exists!")."\n";

		echo __("Please use the search facility below to try find the right person.")."\n";



		echo do_shortcode('[profile_search]');				
		echo"			</div><!-- #content -->
			   </div>
			</div>\n";

		echo $rb_footer = RBAgency_Common::rb_footer();
?>
