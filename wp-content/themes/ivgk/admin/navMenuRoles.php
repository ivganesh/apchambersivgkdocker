<?php
//add_filter( 'wp_edit_nav_menu_walker',  'edit_nav_menu_walker'  );
//function edit_nav_menu_walker( $walker ) {
	//require_once( plugin_dir_path( __FILE__ ) . 'inc/class.Walker_Nav_Menu_Edit_Roles.php' );
	//return 'Walker_Nav_Menu_Edit_Roles'; 
//}
// Add new fields via hook.
add_action( 'wp_nav_menu_item_custom_fields',  'custom_fields' , 10, 4 );
function custom_fields( $item_id, $item, $depth, $args ) {
		global $wp_roles;
		
		//update_post_meta( $item->ID, '_nav_menu_role', 'in' );
		//update_post_meta( $item->ID, '_nav_menu_action', array( 'ADD' ,'UPDATE','TRASH','RESTORE','VIEW','DELETE') );
		
		$display_roles = apply_filters( 'nav_menu_roles', $wp_roles->role_names, $item );
		$display_rights = array('ADD'=>'ADD','UPDATE'=>'UPDATE','TRASH'=>'TRASH','RESTORE'=>'RESTORE','VIEW'=>'VIEW','DELETE'=>'DELETE');
		//update_post_meta( $item->ID, '_nav_menu_action', array( 'ADD' ,'UPDATE','TRASH','RESTORE','VIEW','DELETE') );
		if( ! $display_roles ) return;

		/* Get the roles saved for the post. */
		$roles = get_post_meta( $item->ID, '_nav_menu_role', true );
		$actions = get_post_meta( $item->ID, '_nav_menu_action', true );
		$shown = get_post_meta( $item->ID, '_shown_nav_menu', true );
		$icon = get_post_meta( $item->ID, '_icon', true );
		// By default nothing is checked (will match "everyone" radio).
		$logged_in_out = 'in';
		$logged_in_out_shown = 'shown';
		// Specific roles are saved as an array, so "in" or an array equals "in" is checked.
		if( is_array( $roles ) || $roles == 'in' ){
			$logged_in_out = 'in';
		} else if ( $roles == 'out' ){
			$logged_in_out = 'out';
		}else {
			$logged_in_out = '';
		}

		if(  $shown == 'dont' ){
			$logged_in_out_shown = 'dont';
		} 

		// The specific roles to check.
		$checked_roles = is_array( $roles ) ? $roles : false;
		$checked_actions = is_array( $actions ) ? $actions : false;

		// Whether to display the role checkboxes.
		$hidden = $logged_in_out == 'in' ? '' : 'display: none;';

		?>

		<input type="hidden" name="nav-menu-role-nonce" value="<?php echo wp_create_nonce( 'nav-menu-nonce-name' ); ?>" />

		<div class="field-nav_menu_role nav_menu_logged_in_out_field description-wide" style="margin: 5px 0;">

			<div class="logged-input-holder" style="float: left; width: 50%;">
		        <input type="radio" class="nav-menu-logged-in-out selectedMenu-this-item" name="show-nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_logged_in-for-<?php echo $item->ID ;?>" <?php checked( 'shown', $logged_in_out_shown ); ?> value="shown" />
		        <label for="nav_menu_logged_in-for-<?php echo $item->ID ;?>">
		            <?php _e( 'Display in Menu', 'nav-menu-roles'); ?>
		        </label>
		    </div>

		    <div class="logged-input-holder" style="float: left; width: 50%;">
		        <input type="radio" class="nav-menu-logged-in-out selectedMenu-not-item" name="show-nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_logged_out-for-<?php echo $item->ID ;?>" <?php checked( 'dont', $logged_in_out_shown ); ?> value="dont" />
		        <label for="nav_menu_logged_out-for-<?php echo $item->ID ;?>">
		            <?php _e( 'Dont Display in Menu', 'nav-menu-roles'); ?>
		        </label>
		    </div>

			  <div class="logged-input-holder" style="float: left; width: 100%;">
		        <input placeholder="icon" type="text" class="nav-menu-logged-in-out selectedMenu-not-item" name="show-nav-menu-icon[<?php echo $item->ID ;?>]" id="nav_menu_icon-<?php echo $item->ID ;?>"  value="<?php echo $icon; ?>" />
		     
		    </div>
		</div>
		
		<div class="field-nav_menu_role nav_menu_logged_in_out_field description-wide" style="margin: 5px 0;">
		    <span class="description"><?php _e( "Display Mode", 'nav-menu-roles' ); ?></span>
		    <br />

		    <input type="hidden" class="nav-menu-id" value="<?php echo $item->ID ;?>" />

		    <div class="logged-input-holder" style="float: left; width: 35%;">
		        <input type="radio" class="nav-menu-logged-in-out selectedMenu-this-item" name="nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_logged_in-for-<?php echo $item->ID ;?>" <?php checked( 'in', $logged_in_out ); ?> value="in" />
		        <label for="nav_menu_logged_in-for-<?php echo $item->ID ;?>">
		            <?php _e( 'Log-In Users', 'nav-menu-roles'); ?>
		        </label>
		    </div>

		    <div class="logged-input-holder" style="float: left; width: 35%;">
		        <input type="radio" class="nav-menu-logged-in-out selectedMenu-not-item" name="nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_logged_out-for-<?php echo $item->ID ;?>" <?php checked( 'out', $logged_in_out ); ?> value="out" />
		        <label for="nav_menu_logged_out-for-<?php echo $item->ID ;?>">
		            <?php _e( 'Log-Out Users', 'nav-menu-roles'); ?>
		        </label>
		    </div>

		    <div class="logged-input-holder" style="float: left; width: 30%;">
		        <input type="radio" class="nav-menu-logged-in-out selectedMenu-not-item" name="nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_by_role-for-<?php echo $item->ID ;?>" <?php checked( '', $logged_in_out ); ?> value="" />
		        <label for="nav_menu_by_role-for-<?php echo $item->ID ;?>">
		            <?php _e( 'Everyone', 'nav-menu-roles'); ?>
		        </label>
		    </div>

		</div>
		
		<div class="field-nav_menu_role nav_menu_role_field description-wide" style="margin: 5px 0; <?php echo $hidden;?>">
		    <span class="description"><?php _e( "Restrict menu item to a minimum role", 'nav-menu-roles' ); ?></span>
		    <br />

		    <?php

		    $i = 1;

		    /* Loop through each of the available roles. */
		    foreach ( $display_roles as $role => $name ) {
		        /* If the role has been selected, make sure it's checked. */
		        $checked = checked( true, ( is_array( $checked_roles ) && in_array( $role, $checked_roles ) ), false );

		        ?>

		        <div class="role-input-holder" style="float: left; width: 33.3%; margin: 2px 0;">
		        <input type="checkbox" name="nav-menu-role[<?php echo $item->ID ;?>][<?php echo $i; ?>]" id="nav_menu_role-<?php echo $role; ?>-for-<?php echo $item->ID ;?>" <?php echo $checked; ?> value="<?php echo $role; ?>" />
		        <label for="nav_menu_role-<?php echo $role; ?>-for-<?php echo $item->ID ;?>">
		        <?php echo esc_html( $name ); ?>
		        <?php $i++; ?>
		        </label>
		        </div>

		<?php } ?>

		</div>
		<?php //if( $item->menu_item_parent != 0 )
		{			?>
		<div class="field-nav_menu_role nav_menu_role_field description-wide" style="margin: 5px 0; <?php echo $hidden;?>">
		    <span class="description"><?php _e( "Allowed Action", 'nav-menu-roles' ); ?></span>
		    <br />

		    <?php

		    $i = 1;

		    foreach ( $display_rights as $role => $name ) {
		        $checked = checked( true, ( is_array( $checked_actions ) && in_array( $role, $checked_actions ) ), false );

		        ?>

		        <div class="role-input-holder" style="float: left; width: 33.3%; margin: 2px 0;">
		        <input type="checkbox" name="nav-menu-action[<?php echo $item->ID ;?>][<?php echo $i; ?>]" id="nav_menu_action-<?php echo $role; ?>-for-<?php echo $item->ID ;?>" <?php echo $checked; ?> value="<?php echo $role; ?>" />
		        <label for="nav_menu_action-<?php echo $role; ?>-for-<?php echo $item->ID ;?>">
		        <?php echo esc_html( $name ); ?>
		        <?php $i++; ?>
		        </label>
		        </div>

		<?php } ?>

		</div>
		<?php } ?>
		<?php
	}
	
	
// Add some JS.
add_action( 'admin_enqueue_scripts' ,  'enqueue_scripts'  );
function enqueue_scripts( $hook ){
		if ( $hook == 'nav-menus.php' ){
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'nav-menu-roles', get_template_directory_uri(). '/js/nav-menu-roles.js' , array(), false, true );
		}  
	}
// Save the menu item meta.
add_action( 'wp_update_nav_menu_item',  'nav_update', 10, 2 );
function nav_update( $menu_id, $menu_item_db_id ) {
		global $wp_roles;

		$allowed_roles = apply_filters( 'nav_menu_roles', $wp_roles->role_names );

		// Verify this came from our screen and with proper authorization.
		if ( ! isset( $_POST['nav-menu-role-nonce'] ) || ! wp_verify_nonce( $_POST['nav-menu-role-nonce'], 'nav-menu-nonce-name' ) ){
			return;
		}
		
		$saved_data_shown= $saved_data = $saved_data_icon  = $saved_actions =  false;

		if ( isset( $_POST['nav-menu-logged-in-out'][$menu_item_db_id]  )  && $_POST['nav-menu-logged-in-out'][$menu_item_db_id] == 'in' && ! empty ( $_POST['nav-menu-role'][$menu_item_db_id] ) ) 
		{
			
			$custom_roles = array();
			
			// Only save allowed roles.
			foreach( (array) $_POST['nav-menu-role'][$menu_item_db_id] as $role ) {
				if ( array_key_exists ( $role, $allowed_roles ) ) {
					$custom_roles[] = $role;
				}
			}
			if ( ! empty ( $custom_roles ) ) {
				$saved_data = $custom_roles;
			}
		} else if ( isset( $_POST['nav-menu-logged-in-out'][$menu_item_db_id]  ) && in_array( $_POST['nav-menu-logged-in-out'][$menu_item_db_id], array( 'in', 'out' , 'ev' ) ) ) {
			$saved_data = $_POST['nav-menu-logged-in-out'][$menu_item_db_id];
		}
		
			$custom_actions = array();
			
			// Only save allowed roles.
			if( isset ( $_POST['nav-menu-action'][$menu_item_db_id] ) )
			{
				foreach( (array) $_POST['nav-menu-action'][$menu_item_db_id] as $action ) 
				{
					$custom_actions[] = $action;
					
				}
			}
			if ( ! empty ( $custom_actions ) ) 
				$saved_actions = $custom_actions;
			
		
		if ( isset( $_POST['show-nav-menu-logged-in-out'][$menu_item_db_id]  ) && in_array( $_POST['show-nav-menu-logged-in-out'][$menu_item_db_id], array( 'shown', 'dont' ) ) ) {
			$saved_data_shown = $_POST['show-nav-menu-logged-in-out'][$menu_item_db_id];
		}
		if ( isset( $_POST['show-nav-menu-icon'][$menu_item_db_id]  ) )  {
			$saved_data_icon = $_POST['show-nav-menu-icon'][$menu_item_db_id];
		}
		
		if ( $saved_actions ) {
			update_post_meta( $menu_item_db_id, '_nav_menu_action', $saved_actions );
		} else {
			delete_post_meta( $menu_item_db_id, '_nav_menu_action' );
		}
		
		if ( $saved_data ) {
			update_post_meta( $menu_item_db_id, '_nav_menu_role', $saved_data );
		} else {
			delete_post_meta( $menu_item_db_id, '_nav_menu_role' );
		}
		
		if ( $saved_data_shown ) {
			update_post_meta( $menu_item_db_id, '_shown_nav_menu', $saved_data_shown );
		} else {
			delete_post_meta( $menu_item_db_id, '_shown_nav_menu' );
		}
		if ( $saved_data_shown ) {
			update_post_meta( $menu_item_db_id, '_icon', $saved_data_icon );
		} else {
			delete_post_meta( $menu_item_db_id, '_icon' );
		}
	}
// Add meta to menu item.
add_filter( 'wp_setup_nav_menu_item',  'setup_nav_item'  );
function setup_nav_item( $menu_item ) {

		if( is_object( $menu_item ) && isset( $menu_item->ID ) ) {

			$roles = get_post_meta( $menu_item->ID, '_nav_menu_role', true );

			if ( ! empty( $roles ) ) {
				$menu_item->roles = $roles;
			}
			
			$actions = get_post_meta( $menu_item->ID, '_nav_menu_action', true );

			if ( ! empty( $actions ) ) {
				$menu_item->actions = $actions;
			}
			
			$shown = get_post_meta( $menu_item->ID, '_shown_nav_menu', true );

			if ( ! empty( $shown ) ) {
				$menu_item->shown = $shown;
			}
			$icon = get_post_meta( $menu_item->ID, '_icon', true );

			if ( ! empty( $icon ) ) {
				$menu_item->icon = $icon;
			}
		}
		return $menu_item;
	}
// Exclude items via filter instead of via custom Walker.
if ( ! is_admin() ) {
	// Because WP_Customize_Nav_Menu_Item_Setting::filter_wp_get_nav_menu_items() runs at 10.
	add_filter( 'wp_get_nav_menu_items', 'exclude_menu_items' , 20 );
	function exclude_menu_items( $items ) {

		$hide_children_of = array();

		if( ! empty( $items ) ) {

			// Iterate over the items to search and destroy.
			foreach ( $items as $key => $item ) {

				$visible = true;

				if( isset( $item->menu_item_parent ) && in_array( $item->menu_item_parent, $hide_children_of ) ){
					$visible = false;
				}

				if( $visible && isset( $item->roles ) ) {

					switch( $item->roles ) {
						case 'in' :

							$visible = is_user_member_of_blog() || is_super_admin() ? true : false;
							break;
						case 'out' :
					
							$visible = ! is_user_member_of_blog() && ! is_super_admin() ? true : false;
							break;
						default:
							$visible = false;
							if ( is_array( $item->roles ) && ! empty( $item->roles ) ) {
								foreach ( $item->roles as $role ) {
									if ( current_user_can( $role ) ) {
										$visible = true;
									}
								}
							}

							break;
					}

				}

		
				$visible = apply_filters( 'nav_menu_roles_item_visibility', $visible, $item );

				if ( ! $visible ) {
					if( isset( $item->ID ) ) {
						$hide_children_of[] = $item->ID; // Store ID of item to hide it's children.
					}
					unset( $items[$key] ) ;
				}

			}

		}

		return $items;
	}
}
?>