<?php

class RBAgency_AdminSettings {

	/*
	 * Overview
	 */
		static function Overview() {
		?>
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<h3><?php echo __("Configuration", RBAGENCY_TEXTDOMAIN ) ?>:</h3>
						<ul>
							<li><a href="?page=<?php echo $_GET["page"]; ?>&ConfigID=1"><strong><?php _e("Settings", RBAGENCY_TEXTDOMAIN); ?></strong></a> - <?php _e("Access this area to manage all of the core settings including layout types, privacy settings and more", RBAGENCY_TEXTDOMAIN); ?></li>
							<li><a href="?page=<?php echo $_GET["page"]; ?>&ConfigID=2"><strong><?php _e("Style", RBAGENCY_TEXTDOMAIN); ?></strong></a> - <?php _e("Access this area to manage all of the core settings including layout types, privacy settings and more", RBAGENCY_TEXTDOMAIN); ?></li>
						</ul>
					</div>

					<div class="welcome-panel-column" style="margin-left: 50px;">
						<h3><?php echo __("Diagnostics", RBAGENCY_TEXTDOMAIN ) ?>:</h3>
							<?php
							echo "No Diagnostic has been run.  Please run now.";
							// Diagnostic Tests
							//require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/diagnostic.php");

							// TODO: Add Diagnostic
							?>
					</div>

				</div>
			</div>
		</div>

		<?php
		}


	/*
	 * Configuration
	 */
		static function Configuration() {

		}

	/*
	 * Style
	 */
		static function Style() {

			echo 'Method Variable called';



		}



	/*
	 * Data: Gender
	 */
		static function DataGender() {

			echo 'Method Variable called';



		}



	/*
	 * Data: Profile Types
	 */
		static function DataProfileType() {

			echo 'Method Variable called';



		}


	/*
	 * Data: Custom Fields
	 */
		static function DataCustomFields() {

			echo 'Method Variable called';



		}




	/*
	 * Data: Media Types
	 */
		static function DataMediaType() {

			echo 'Method Variable called';



		}




	/*
	 * Data: Media Types
	 */
		static function DataMediaType() {

			echo 'Method Variable called';

			if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {
				if($_REQUEST['action'] == 'douninstall') {
					return RBAgency::Uninstall();
				}
			}

		}


}


?>