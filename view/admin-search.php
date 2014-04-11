<hr />
<div class="wrap">
<?php
	// Setup DB connection
	global $wpdb;
	// Include Admin Menu
	include ("admin-include-menu.php"); 

	// Profile Class
	include(rb_agency_BASEREL ."app/profile.class.php");

	// Casting Class
	include(rb_agency_BASEREL ."app/casting.class.php");

	// Define Options
		$rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype =  isset($rb_agency_options_arr['rb_agency_option_unittype']) ?$rb_agency_options_arr['rb_agency_option_unittype']:1;
		$rb_agency_option_persearch = isset($rb_agency_options_arr['rb_agency_option_persearch'])?(int)$rb_agency_options_arr['rb_agency_option_persearch']:1;
		$rb_agency_option_agencyemail = isset($rb_agency_options_arr['rb_agency_option_agencyemail'])?(int)$rb_agency_options_arr['rb_agency_option_agencyemail']:"";
		if ($rb_agency_option_persearch < 0) { $rb_agency_option_persearch = 100; }

// *************************************************************************************************** //
/* 
 * Casting Cart
 */


	/*
	 * Add to Cart
	 */

		if(isset($_REQUEST["action"]) && $_REQUEST['action'] == 'cartAdd' ) {
			// Process Cart
			$cart = RBAgency_Casting::cart_process();
		}


	/*
	 * Empty Cart
	 */

		if(isset($_REQUEST["action"]) && $_REQUEST['action'] == 'cartEmpty' ) {
			// Empty Cart
			unset($_SESSION['cartArray']);
		}


	/*
	 * Send Email
	 */

		if(isset($_POST["SendEmail"])){
				// Process Form
				$isSent = RBAgency_Casting::cart_send_process();
		}

		// TODO::>> ??????
		$cartHTML= RBAgency_Casting::cart_send_form();
		if($cartHTML!=1)
			echo $cartHTML;	// search result


	/*
	 * Display Search Results
	 */

		if (isset($_POST["form_action"]) && $_POST["form_action"] == "search_profiles") {

			echo "<div id=\"profile-search-results\">\n";

				$search_array = RBAgency_Profile::search_process();
				$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);

				echo RBAgency_Profile::search_results($search_sql_query, 0);

			echo "</div><!-- #profile-search-results -->\n"; // #profile-search-results

		}


	/*
	 * Display Cart
	 */

		if (isset($_SESSION['cartArray']) || isset($_GET["action"]) && $_GET["action"] == "cartAdd") {

			echo "<div class=\"boxblock-container\" style=\"float: left; padding-top:24px; width: 49%; min-width: 500px;\">\n";
			echo " <div class=\"boxblock\">\n";
			echo "   <h2>". __("Casting Cart", rb_agency_TEXTDOMAIN) ."</h2>\n";
			echo "   <div class=\"inner\">\n";

			// Show Cart
			echo RBAgency_Casting::cart_show();

			echo "   </div>\n";
			echo " </div>\n";
			echo "</div>\n";
			// Send Email Form
			echo "    </div><!-- .boxblock -->\n";
		}
  

	 

	/*
	 * Display Search Form
	 */

		// Search Form

			echo "    <div class=\"boxblock-container\" style=\"float: left; width: 49%;\">\n";
			echo "     <div class=\"boxblock\">\n";
			echo "      <h3>". __("Advance Search", rb_agency_TEXTDOMAIN) ."</h3>\n";
			echo "      <div class=\"inner\">\n";

			return RBAgency_Profile::search_form("", "", 1);

			echo "      </div><!-- .inner -->\n";
			echo "     </div><!-- .boxblock -->\n";
			echo "    </div><!-- .boxblock-container -->\n";


?>
</div>