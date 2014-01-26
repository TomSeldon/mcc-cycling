<?php
/**
 * Admin functions for the ajde_events post type
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventON/Admin/ajde_events
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Columns for events page
 *
 * @access public
 * @param mixed $columns
 * @return array
 */
function eventon_edit_event_columns( $existing_columns ) {
	global $eventon;
	
	// GET event type custom names
	$evcal_opt1= get_option('evcal_options_evcal_1');
	$evt_name = (!empty($evcal_opt1['evcal_eventt']))?$evcal_opt1['evcal_eventt']:'Event Type';
	$evt_name2 = (!empty($evcal_opt1['evcal_eventt2']))?$evcal_opt1['evcal_eventt2']:'Event Type 2';
	
	if ( empty( $existing_columns ) && ! is_array( $existing_columns ) )
		$existing_columns = array();

	unset( $existing_columns['title'], $existing_columns['comments'], $existing_columns['date'] );

	$columns = array();
	$columns["cb"] = "<input type=\"checkbox\" />";
	//$columns["thumb"] = '<img src="' . AJDE_EVCAL_URL . '/assets/images/image.png" alt="' . __( 'Image', 'eventon' ) . '" class="tips" data-tip="' . __( 'Image', 'eventon' ) . '" width="14" height="14" />';

	//$columns["title"] = __( 'Event Name', 'eventon' );
	$columns["name"] = __( 'Event Name', 'eventon' );

	$columns["event_type"] = __( $evt_name, 'eventon' );
	$columns["event_type_2"] = __( $evt_name2, 'eventon' );
	$columns["event_start_date"] = __( 'Event Start Date', 'eventon' );
	$columns["event_end_date"] = __( 'Event End Date', 'eventon' );
	$columns["repeat"] = '<img src="' . AJDE_EVCAL_URL . '/assets/images/icons/evo_repeat.png" alt="' . __( 'Event Repeat', 'eventon' ) . '" title="' . __( 'Event Repeat', 'eventon' ) . '" class="tips" />';;
	$columns["date"] = __( 'Date', 'eventon' );

	return array_merge( $columns, $existing_columns );
}

add_filter( 'manage_edit-ajde_events_columns', 'eventon_edit_event_columns' );

/**
 * Custom Columns for Products page
 *
 * @access public
 * @param mixed $column
 * @return void
 */
function eventon_custom_event_columns( $column ) {
	global $post;

	//if ( empty( $ajde_events ) || $ajde_events->id != $post->ID )
		//$ajde_events = get_product( $post );

	switch ($column) {
		case "thumb" :
			//echo '<a href="' . get_edit_post_link( $post->ID ) . '">' . $ajde_events->get_image() . '</a>';
		break;
		case "name" :
			$edit_link = get_edit_post_link( $post->ID );
			$title = _draft_or_post_title();
			$post_type_object = get_post_type_object( $post->post_type );
			$can_edit_post = current_user_can( $post_type_object->cap->edit_post, $post->ID );

			echo '<strong><a class="row-title" href="'.$edit_link.'">' . $title.'</a>';

			_post_states( $post );

			echo '</strong>';
			
			if ( $post->post_parent > 0 )
				echo '&nbsp;&nbsp;&larr; <a href="'. get_edit_post_link($post->post_parent) .'">'. get_the_title($post->post_parent) .'</a>';

			// Excerpt view
			if (isset($_GET['mode']) && $_GET['mode']=='excerpt') echo apply_filters('the_excerpt', $post->post_excerpt);

			// Get actions
			$actions = array();

			//$actions['id'] = 'ID: ' . $post->ID;

			if ( $can_edit_post && 'trash' != $post->post_status ) {
				$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';
				$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
			}
			if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
				if ( 'trash' == $post->post_status )
					$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-post_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
				elseif ( EMPTY_TRASH_DAYS )
					$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
				if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
					$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
			}
			if ( $post_type_object->public ) {
				if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
					if ( $can_edit_post )
						$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
				} elseif ( 'trash' != $post->post_status ) {
					$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
				}
			}

			$actions = apply_filters( 'post_row_actions', $actions, $post );

			echo '<div class="row-actions">';

			$i = 0;
			$action_count = sizeof($actions);

			foreach ( $actions as $action => $link ) {
				++$i;
				( $i == $action_count ) ? $sep = '' : $sep = ' | ';
				echo "<span class='$action'>$link$sep</span>";
			}
			echo '</div>';
			
		break;
		
		case "event_type" :		
			if ( ! $terms = get_the_terms( $post->ID, $column ) ) {
				echo '<span class="na">&ndash;</span>';
			} else {
				foreach ( $terms as $term ) {
					$termlist[] = '<a href="' . admin_url( 'edit.php?' . $column . '=' . $term->slug . '&post_type=ajde_events' ) . ' ">' . $term->name . '</a>';
				}

				echo implode( ', ', $termlist );
			}
		break;
		case "event_type_2" :		
			if ( ! $terms = get_the_terms( $post->ID, $column ) ) {
				echo '<span class="na">&ndash;</span>';
			} else {
				foreach ( $terms as $term ) {
					$termlist[] = '<a href="' . admin_url( 'edit.php?' . $column . '=' . $term->slug . '&post_type=ajde_events' ) . ' ">' . $term->name . '</a>';
				}

				echo implode( ', ', $termlist );
			}
		break;
		case "event_start_date":	
			echo get_post_meta($post->ID, 'evcal_start_date', true);		
		break;		
		
		case "event_end_date":	
			echo get_post_meta($post->ID, 'evcal_end_date', true);		
		break;
		case 'repeat':
			
			$repeat = get_post_meta($post->ID, 'evcal_repeat',true);		
			
			if(!empty($repeat) && $repeat=='yes'){
				$repeat_freq = get_post_meta($post->ID, 'evcal_rep_freq',true);
				$output_repeat = '<span>'.$repeat_freq.'</span>';
			}else{
				$output_repeat = '<span class="na">&ndash;</span>';
			}
			
			echo $output_repeat;
		break;
	}
}
add_action('manage_ajde_events_posts_custom_column', 'eventon_custom_event_columns', 2 );

/**
 * Duplicate a event link on events list
 *
 * @access public
 * @param mixed $actions
 * @param mixed $post
 * @return array
 */
function eventon_duplicate_event_link_row($actions, $post) {

	if ( function_exists( 'duplicate_post_plugin_activation' ) )
		return $actions;
	
	if ( $post->post_type != 'ajde_events' )
		return $actions;

	$actions['duplicate'] = '<a href="' . wp_nonce_url( admin_url( 'admin.php?action=duplicate_event&amp;post=' . $post->ID ), 'eventon-duplicate-event_' . $post->ID ) . '" title="' . __( 'Make a duplicate from this event', 'eventon' )
		. '" rel="permalink">' .  __( 'Duplicate', 'eventon' ) . '</a>';

	return $actions;
}

add_filter( 'post_row_actions', 'eventon_duplicate_event_link_row',10,2 );
add_filter( 'page_row_actions', 'eventon_duplicate_event_link_row',10,2 );

/**
 *  Duplicate a product link on edit screen
 *
 * @access public
 * @return void
 */
function eventon_duplicate_event_post_button() {
	global $post;

	if ( function_exists( 'duplicate_post_plugin_activation' ) ) return;
	
	if ( ! is_object( $post ) ) return;

	if ( $post->post_type != 'ajde_events' ) return;

	if ( isset( $_GET['post'] ) ) {
		$notifyUrl = wp_nonce_url( admin_url( "admin.php?action=duplicate_event&post=" . absint( $_GET['post'] ) ), 'eventon-duplicate-event_' . $_GET['post'] );
		?>
		<div class="misc-pub-section" >
		<div id="duplicate-action"><a class="submitduplicate duplication button" href="<?php echo esc_url( $notifyUrl ); ?>"><?php _e( 'Duplicate this event', 'eventon' ); ?></a></div>
		</div>
		<?php
	}
}

add_action( 'post_submitbox_misc_actions', 'eventon_duplicate_event_post_button' );


/**
 * Make event columns sortable
 */
function eventon_custom_event_sort($columns) {
	$custom = array(
		'event_start_date'			=> 'evcal_start_date',
		'event_end_date'		=> 'evcal_end_date'
	);
	return wp_parse_args( $custom, $columns );
}
add_filter( 'manage_edit-ajde_events_sortable_columns', 'eventon_custom_event_sort');

/**
 * Product column orderby
 */
function eventon_custom_event_orderby( $vars ) {
	if (isset( $vars['orderby'] )) :
		if ( 'evcal_start_date' == $vars['orderby'] ) :
			$vars = array_merge( $vars, array(
				'meta_key' 	=> 'evcal_start_date',
				'orderby' 	=> 'meta_value_num'
			) );
		endif;
		if ( 'evcal_end_date' == $vars['orderby'] ) :
			$vars = array_merge( $vars, array(
				'meta_key' 	=> 'evcal_end_date',
				'orderby' 	=> 'meta_value'
			) );
		endif;
		
	endif;

	return $vars;
}
add_filter( 'request', 'eventon_custom_event_orderby' );

?>