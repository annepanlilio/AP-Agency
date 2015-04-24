<hr />
<div class="wrap">
<?php
	// Setup DB connection
	global $wpdb;
	// Include Admin Menu
	include (RBAGENCY_PLUGIN_DIR ."view/partial/admin-menu.php"); 

	// Casting Class
	include(RBAGENCY_PLUGIN_DIR ."app/casting.class.php");

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
		if(isset($_REQUEST["action"]) && $_REQUEST['action'] == 'cartAdd'  ) {
			// Process Cart
			$cart = RBAgency_Casting::cart_process();
		}


	/*
	 * Empty Cart
	 */

		if(isset($_GET["action"]) && $_GET['action'] == 'cartEmpty' ) {
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

		if(isset($_GET["action"]) && $_GET["action"] == "massEmail") {
		// TODO::>> ??????
		$cartHTML= RBAgency_Casting::cart_send_form();
		if($cartHTML!=1)
			echo $cartHTML;	// search result
		}


	/*
	 * Display Search Results
	 */

		if (isset($_POST["form_action"]) && $_POST["form_action"] == "search_profiles"){
			echo "<div id=\"profile-search-results\">\n";
				//$search_array = RBAgency_Profile::search_process();
				//$search_array["limit"] = $rb_agency_option_persearch;
					foreach($_POST as $key=>$value) {
						if($key !="isactive"){
							if ( is_array($value) && !empty($value) ){
									unset( $_POST[$key] );
							} else {
								if ( !isset($value) || empty ($value) ){
									unset( $_POST[$key] );
								}
							}
						}
					}

					// Check something was entered in the form
					if (count($_POST) > 1) {
						$search_array = $_POST;

						// Return SQL string based on fields
						$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);

						// Conduct Search
						echo RBAgency_Profile::search_results($search_sql_query, 1, false, $search_array);

					} else {
						echo "<h2>Please try again</h2><strong>". __("Please enter at least one value to search.", RBAGENCY_TEXTDOMAIN) ."</strong>\n";
					}
				
				echo "<div style=\"clear:both;\"></div>";
			echo "</div><!-- #profile-search-results -->\n"; // #profile-search-results

		}


	/*
	 * Display Search results without limit
	 */

		if(isset($_GET["limit"]) && $_GET["limit"] == "none") {
			$search_array = array();
			$search_array = array_filter($_GET);

			unset($search_array["rb_agency_search"]);
			unset($search_array["limit"]);
			$search_sql_query = RBAgency_Profile::search_generate_sqlwhere($search_array);
			echo RBAgency_Profile::search_results($search_sql_query, 1);
		}


	/*
	 * Display Cart
	 */

		if (isset($_SESSION['cartArray']) || isset($_POST["action"]) && $_POST["action"] == "cartAdd" && $_POST["action"] !== "massEmail" || isset($_REQUEST["action"]) && @$_REQUEST["action"] == "cartEmpty") {

			echo "<div class=\"boxblock-container\" style=\"float: left; padding-top:24px; width: 49%; min-width: 500px;\">\n";
			echo " <div class=\"boxblock\">\n";
			echo "   <h2>". __("Casting Cart", RBAGENCY_TEXTDOMAIN) ."</h2>\n";
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
	if (isset($_REQUEST["action"]) && @$_REQUEST["action"] !== "massEmail"  && @$_REQUEST["action"] !== "cartEmpty"  && !isset($_SESSION['cartArray']) ) {
	} else {

		// Search Form

			echo "    <div class=\"boxblock-container\" style=\"float: left; width: 49%;\">\n";
			echo "     <div class=\"boxblock\">\n";
			echo "      <h3>". __("Advanced Search", RBAGENCY_TEXTDOMAIN) ."</h3>\n";
			echo "      <div class=\"inner\">\n";

			return RBAgency_Profile::search_form('', '', 1, 1);
			
			echo "      </div><!-- .inner -->\n";
			echo "     </div><!-- .boxblock -->\n";
			echo "    </div><!-- .boxblock-container -->\n";
	}

?>

</div>
</div>