<?php
class RBAgency_Casting {

	/*
	 * Casting Cart
	 * Process Actions
	 */

		public static function Cart_Process(){

			/*
			 * Setup Requirements
			 */

			// Protect and defend the cart string!
				$cartString = "";
				$action = $_GET["action"];

				if ($action == "cartAdd") {
					// Add to Cart
					$this->Cart_Process_Add();

				} elseif ($action == "formEmpty") {
					// Empty the Form
					extract($_SESSION);
					foreach($_SESSION as $key=>$value) {
						if (substr($key, 0, 7) == "Profile") {
							unset($_SESSION[$key]);
						}
					}

				} elseif ($action == "cartEmpty") {
					// Throw the baby out with the bathwater
					unset($_SESSION['cartArray']);

				} elseif ($action == "cartRemove") {
					// Remove ID from Cart

					isset($_GET["RemoveID"]) {
						$cartArray = $_SESSION['cartArray'];
						$cartString = implode(",", $cartArray);
						$cartRemoveID = $_GET["RemoveID"];
						$cartString = str_replace($_GET['RemoveID'] ."", "", $cartString);
						$cartString = RBAgency_Common::Clean_String($cartString);

						// Put it back in the array, and wash your hands
						$_SESSION['cartArray'] = array($cartString);
					}

				} elseif ($action == "searchSave") {
					// Save the Search
					if isset($_SESSION['cartArray']) {

						extract($_SESSION);
						foreach($_SESSION as $key=>$value) {
							// TODO: Why is this empty?
						}
						$_SESSION['cartArray'] = $cartArray;

					}

				} //

				return $action;
		}



	/*
	 * Casting Cart - Add to Cart
	 * @return str $cartString
	 */

		public static function Cart_Process_Add(){

			// Get String
			if(count($_GET["ProfileID"]) > 0) {
				foreach($_GET["ProfileID"] as $value) {
					$cartString .= $value .",";
				}
			}

			// Clean It!
			$cartString = RBAgency_Common::Clean_String($cartString);

			// Add to Session
			if (isset($_SESSION['cartArray'])) {
				$cartArray = $_SESSION['cartArray'];
				array_push($cartArray, $cartString);
			} else {
				$cartArray = array($cartString);
			}

			// Replace Session
			$_SESSION['cartArray'] = $cartArray;

			return $cartString;

		}


	/*
	 * Casting Cart - Add to Cart
	 * @return str $cartString
	 */

		public static function Cart_Show(){

			if (isset($_SESSION['cartArray']) && !empty($_SESSION['cartArray'])) {

				$cartArray = $_SESSION['cartArray'];
					$cartString = implode(",", array_unique($cartArray));
					$cartString = RBAgency_Common::Clean_String($cartString);

				// Show Cart  
				$query = "SELECT  profile.*,media.* FROM ". table_agency_profile ." profile, ". table_agency_profile_media ." media WHERE profile.ProfileID = media.ProfileID AND media.ProfileMediaType = \"Image\" AND media.ProfileMediaPrimary = 1 AND profile.ProfileID IN (". $cartString .") ORDER BY profile.ProfileContactNameFirst ASC";
				$results = mysql_query($query) or  die( "<a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("No profile selected. Try again", rb_agency_TEXTDOMAIN) ."</a>"); //die ( __("Error, query failed", rb_agency_TEXTDOMAIN ));
				$count = mysql_num_rows($results);

				echo "<div style=\"float: right; width: 100px; \"><a href=\"?page=". $_GET['page'] ."&action=cartEmpty\" class=\"button-secondary\">". __("Empty Cart", rb_agency_TEXTDOMAIN) ."</a></div>";
				echo "<div style=\"float: left; line-height: 22px; font-family:Georgia; font-size:13px; font-style: italic; color: #777777; \">". __("Currently", rb_agency_TEXTDOMAIN) ." <strong>". $count ."</strong> ". __("in Cart", rb_agency_TEXTDOMAIN) ."</div>";
				echo "<div style=\"clear: both; border-top: 2px solid #c0c0c0; \" class=\"profile\">";

				if ($count == 1) {
					$cartAction = "cartEmpty";
				} elseif ($count < 1) {
					echo "". __("There are currently no profiles in the casting cart", rb_agency_TEXTDOMAIN) .".";
					$cartAction = "cartEmpty";
				} else {
					$cartAction = "cartRemove";
				}

				while ($data = mysql_fetch_array($results)) {

					$ProfileDateUpdated = $data['ProfileDateUpdated'];

					echo "<div style=\"position: relative; border: 1px solid #e1e1e1; line-height: 22px; float: left; padding: 10px; width: 210px; margin: 6px; \">";
					echo " <div style=\"text-align: center; \"><h3>". stripslashes($data['ProfileContactNameFirst']) ." ". stripslashes($data['ProfileContactNameLast']) . "</h3></div>"; 
					echo " <div style=\"float: left; width: 100px; height: 100px; overflow: hidden; margin-top: 2px; \"><img style=\"width: 100px; \" src=\"". rb_agency_UPLOADDIR ."". $data['ProfileGallery'] ."/". $data['ProfileMediaURL'] ."\" /></div>\n";
					echo " <div style=\"float: left; width: 100px; height: 100px; overflow: scroll-y; margin-left: 10px; line-height: 11px; font-size: 9px; \">\n";

					if (!empty($data['ProfileDateBirth'])) {
						echo "<strong>Age:</strong> ". rb_agency_get_age($data['ProfileDateBirth']) ."<br />\n";
					}
					// TODO: ADD MORE FIELDS

					echo " </div>";
					echo " <div style=\"position: absolute; z-index: 20; top: 120px; left: 200px; width: 20px; height: 20px; overflow: hidden; \"><a href=\"?page=". $_GET['page'] ."&action=". $cartAction ."&RemoveID=". $data['ProfileID'] ."\" title=\"". __("Remove from Cart", rb_agency_TEXTDOMAIN) ."\"><img src=\"". rb_agency_BASEDIR ."style/remove.png\" style=\"width: 20px; \" alt=\"". __("Remove from Cart", rb_agency_TEXTDOMAIN) ."\" /></a></div>";
					echo " <div style=\"clear: both; \"></div>";
					echo "</div>";
				}
				mysql_free_result($results);
				echo "<div style=\"clear: both;\"></div>\n";
				echo "</div>";

			} else {

				echo "<p>There are no profiles added to the casting cart.</p>\n";

			}

		}

}