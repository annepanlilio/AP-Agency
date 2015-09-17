<?php 
/*if (session_status() == PHP_SESSION_NONE) {
	session_start();
}*/
//header("Cache-control: private"); //IE 6 Fix

	/*
		 * EasyText API
		 */

if ( class_exists("RBAgencyCasting") ) {
		echo " <tr valign=\"top\" id=\"CastingDashboardSetting\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><h2>". __('Casting Dashboard Setting', rb_agency_TEXTDOMAIN) ."</h2></th>\n";
		echo " </tr>\n";
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\">". __('Buttons to Display', rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "   <td>";
		
			
		echo "     <input type=\"checkbox\" name=\"rb_agency_options[rb_agency_option_castingbutton_postnewjob]\" value=\"1\" ".checked(isset($rb_agency_options_arr['rb_agency_option_castingbutton_postnewjob'])?$rb_agency_options_arr['rb_agency_option_castingbutton_postnewjob']:0, 1,false)."/> ". __("Post a New Job", rb_agency_TEXTDOMAIN) ."<br />\n";	
		echo "     <input type=\"checkbox\" name=\"rb_agency_options[rb_agency_option_castingbutton_viewjobposting]\" value=\"1\" ".checked(isset($rb_agency_options_arr['rb_agency_option_castingbutton_viewjobposting'])?$rb_agency_options_arr['rb_agency_option_castingbutton_viewjobposting']:0, 1,false)."/> ". __("View Your Job Postings", rb_agency_TEXTDOMAIN) ."<br />\n";	
		echo "     <input type=\"checkbox\" name=\"rb_agency_options[rb_agency_option_castingbutton_viewapplicants]\" value=\"1\" ".checked(isset($rb_agency_options_arr['rb_agency_option_castingbutton_viewapplicants'])?$rb_agency_options_arr['rb_agency_option_castingbutton_viewapplicants']:0, 1,false)."/> ". __("View Your Applicants", rb_agency_TEXTDOMAIN) ."<br />\n";	
	
			

		echo "   </td>\n";
		echo " </tr>\n";
			echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><br/></th>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\" id=\"CartFavIconSettings\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><h2>". __('Cart and Favorite Icon Setting', rb_agency_TEXTDOMAIN) ."</h2></th>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\">". __('Cart Icon URL', rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "   <td>";
		echo "     <input type=\"text\" style=\"width:400px;\" name=\"rb_agency_options[rb_agency_option_carticonurl]\" value=\"".$rb_agency_options_arr['rb_agency_option_carticonurl']."\" /> <br />". __("Leave blank for default", rb_agency_TEXTDOMAIN) ."<br />\n";	
		echo "   </td>\n";
		echo " </tr>\n";
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\">". __('Favorite Icon URL', rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "   <td>";
		echo "     <input type=\"text\" style=\"width:400px;\" name=\"rb_agency_options[rb_agency_option_faviconurl]\" value=\"".$rb_agency_options_arr['rb_agency_option_faviconurl']."\" /> <br />". __("Leave blank for default", rb_agency_TEXTDOMAIN) ."<br />\n";	
		echo "   </td>\n";
		echo " </tr>\n";
		
		
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><br/></th>\n";
		echo " </tr>\n";
		
		}
		
		
		echo " <tr valign=\"top\" id=\"CardPhotoSettings\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><h2>". __('Card Photo Settings', rb_agency_TEXTDOMAIN) ."</h2></th>\n";
		echo " </tr>\n";
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\">". __('Logo URL', rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "   <td>";
		
		echo "     <input type=\"text\" style=\"width:400px;\" name=\"rb_agency_options[rb_agency_option_cardphoto_logo_url]\" value=\"".$rb_agency_options_arr['rb_agency_option_cardphoto_logo_url']."\" /> <br />"
			. __("full url(include http:// ) / exact Height: 100px width: 200px;", rb_agency_TEXTDOMAIN) ."<br />\n";	
	
		echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\">". __('Model Card Layout', rb_agency_TEXTDOMAIN) ."</th>\n";
		echo "   <td>";
		
		echo " <select name=\"rb_agency_options[rb_agency_option_cardphoto_layout]\">\n";
		echo "       <option value=\"0\" ". selected(isset($rb_agency_options_arr['rb_agency_option_cardphoto_layout'])?$rb_agency_options_arr['rb_agency_option_cardphoto_layout']:0, 0,false) ."> ". __("Layout 0", rb_agency_TEXTDOMAIN) ." - Portrait</option>\n";
		echo "       <option value=\"1\" ". selected(isset($rb_agency_options_arr['rb_agency_option_cardphoto_layout'])?$rb_agency_options_arr['rb_agency_option_cardphoto_layout']:0, 1,false) ."> ". __("Layout 1", rb_agency_TEXTDOMAIN) ." - Landscape</option>\n";
		echo "     </select>\n";
		

		echo "   </td>\n";
		echo " </tr>\n";
			echo " <tr valign=\"top\">\n";
		echo "   <th scope=\"row\" colspan=\"2\"><br/></th>\n";
		echo " </tr>\n";
		
		
		
			
		
?>