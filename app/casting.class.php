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

				switch ($action) {
					case 0:
					break;
					case 1:
					break;
				} // Switch

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

}