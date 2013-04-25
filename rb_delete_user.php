<?php

/*
 * Delete Current User Passed
 * @parm User Id
 */

 require_once('../../../wp-load.php');	 
 require_once('../../../wp-admin/includes/user.php');	 
 
    global $wpdb;
 	
	$id = (int) $_POST['ID'];
	$option = (int) $_POST['OPT'];
	$user = new WP_User( $id );
    
	if($option == 3){
		
	   /*
		* Just update status to inactive
		*/
                $update = "UPDATE rb_agency_profile 
				           SET ProfileIsActive = 0
				           WHERE ProfileID = " . $id;
                
				$results = $wpdb->query($update) or die(mysql_error());
				
				wp_logout();
		
	} elseif($option == 2) {

			// allow for transaction statement
			do_action('delete_user', $id);
		
			if ( 'novalue' === $reassign || null === $reassign ) {
				$post_types_to_delete = array();
				foreach ( get_post_types( array(), 'objects' ) as $post_type ) {
					if ( $post_type->delete_with_user ) {
						$post_types_to_delete[] = $post_type->name;
					} elseif ( null === $post_type->delete_with_user && post_type_supports( $post_type->name, 'author' ) ) {
						$post_types_to_delete[] = $post_type->name;
					}
				}
		
				$post_types_to_delete = apply_filters( 'post_types_to_delete_with_user', $post_types_to_delete, $id );
				$post_types_to_delete = implode( "', '", $post_types_to_delete );
				$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_author = %d AND post_type IN ('$post_types_to_delete')", $id ) );
				if ( $post_ids ) {
					foreach ( $post_ids as $post_id )
						wp_delete_post( $post_id );
				}
		
				// Clean links
				$link_ids = $wpdb->get_col( $wpdb->prepare("SELECT link_id FROM $wpdb->links WHERE link_owner = %d", $id) );
		
				if ( $link_ids ) {
					foreach ( $link_ids as $link_id )
						wp_delete_link($link_id);
				}
			} else {
				$reassign = (int) $reassign;
				$wpdb->update( $wpdb->posts, array('post_author' => $reassign), array('post_author' => $id) );
				$wpdb->update( $wpdb->links, array('link_owner' => $reassign), array('link_owner' => $id) );
			}
		
			// delete user
			$meta = $wpdb->get_col( $wpdb->prepare( "SELECT umeta_id FROM $wpdb->usermeta WHERE user_id = %d", $id ) );
			foreach ( $meta as $mid )
				delete_metadata_by_mid( 'user', $mid );
		
			$wpdb->delete( $wpdb->users, array( 'ID' => $id ) );
		
			clean_user_cache( $user );
		
			// allow for commit transaction
			do_action('deleted_user', $id);
	}
 	echo $option;
  
?>
