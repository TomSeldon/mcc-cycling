<?php
/**
 * EventON Admin Functions
 *
 * Hooked-in functions for EventON related events in admin.
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin
 * @version     1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly




/**
 * Prevent non-admin access to backend
 *
 * @access public
 * @return void
 */
add_action( 'init', 'eventon_add_shortcode_button' );
add_filter( 'tiny_mce_version', 'eventon_refresh_mce' ); 
 
function eventon_prevent_admin_access() {
	if ( get_option('eventon_lock_down_admin') == 'yes' && ! is_ajax() && ! ( current_user_can('edit_posts') || current_user_can('manage_eventon') ) ) {
		//wp_safe_redirect(get_permalink(woocommerce_get_page_id('myaccount')));
		exit;
	}
}

/**
 * Add a button for shortcodes to the WP editor.
 */
function eventon_add_shortcode_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') :
		add_filter('mce_external_plugins', 'eventon_add_shortcode_tinymce_plugin');
		add_filter('mce_buttons', 'eventon_register_shortcode_button');	
		
	endif;
}
/**
 * Register the shortcode button.
 */
function eventon_register_shortcode_button($buttons) {
	eventon_shortcode_pop_content();
	array_push($buttons, "|", "EventONShortcodes");
	return $buttons;
}

/**
 * Short code popup content
 */
function eventon_shortcode_pop_content(){
	global $eventon;
	$shortcode_btns = array(
		'Basic Calendar'=>'[add_eventon]',
		'Calendar - unique ID'=>'[add_eventon cal_id="1"]',
		'Calendar - event type'=>'[add_eventon cal_id="1" event_type=""]',
		'Calendar - event type 2'=>'[add_eventon cal_id="1" event_type_2=""]',
		'Calendar - event count limit'=>'[add_eventon cal_id="1" event_count=""]',
		'Calendar - different start month'=>'[add_eventon cal_id="1" month_incre=""]',
		'Upcoming Month List'=>'[add_eventon cal_id="1" show_upcoming="1" number_of_months=""]',
	);
	
	// hook for addons
	if(has_filter('eventon_shortcode_options')){
		$shortcode_btns = apply_filters('eventon_shortcode_options',$shortcode_btns);
	}
	
	$content='<h2>Select EventON Shortcode Options</h2>';
	foreach($shortcode_btns as $sc_f=>$sc_v){
		$content.= "<p class='eventon_shortcode_btn' scode='{$sc_v}'>".$sc_f."</p>";
	}
	$content.="<div class='clear'></div>";
	
	echo $eventon->output_eventon_pop_window($content, 'eventon_shortcode');
}




/**
 * Add the shortcode button to TinyMCEy
 */
function eventon_add_shortcode_tinymce_plugin($plugin_array) {
	
	$plugin_array['EventONShortcodes'] = AJDE_EVCAL_URL . '/assets/js/editor_plugin.js';
	return $plugin_array;
}

/**
 * Force TinyMCE to refresh.
 *
 * @access public
 * @param mixed $ver
 * @return int
 */
function eventon_refresh_mce( $ver ) {
	$ver += 3;
	return $ver;
}


// ==========================
//	ADDON

// Check for addons
function eventon_check_addons() {
	global $eventon, $wpdb;
	
	// Get addon options array - if any
	$eventon_addons_opt = get_option('eventon_addons');
	$physical_addons_array='';
	$eventon_addons= array();
	
	$addon_path = AJDE_EVCAL_DIR.'/'.EVENTON_BASE.'/addons';
		
	
	$addon_dirs = scandir($addon_path);
	
	// run through each addon directory
	foreach ($addon_dirs as $addon_dir) {
		
		if ($addon_dir === '.' or $addon_dir === '..') continue;
		if (is_dir($addon_path . '/' . $addon_dir)) {
			$eventon_addons[]=  $addon_dir;	
			
			$pd = get_plugin_data($addon_path.'/'.$addon_dir.'/index.php');
			
			
			$array_1 = array(
				'name'=> 		$pd['Name'],
				'details'=> 	$pd['Description'],
				'version'=> 	$pd['Version'],
				'path'=>		$addon_path.'/'.$addon_dir.'/index.php',				
				'slug'=>		$addon_dir,
				'guide_file'=>		( file_exists($addon_path.'/'.$addon_dir.'/guide.php') )? 
					AJDE_EVCAL_URL.'/addons/'.$addon_dir.'/guide.php':null
			);
			
			
			// treatment for addons that DONT exist
			if( (!empty($eventon_addons_opt) && !array_key_exists($addon_dir, $eventon_addons_opt) ) || 
				(empty($eventon_addons_opt))	){
				
				$array_1 = array_merge($array_1, array('status'=>		'inactive'));
			
			// addons that exist with status values
			}else if( !empty($eventon_addons_opt)) {
				$status = $eventon_addons_opt[$addon_dir]['status'];
				$array_1 = array_merge($array_1, array('status'=>		$status));
			}
			
			$physical_addons_array[$addon_dir] = $array_1;
		}
		
	}
	
	
	// compare addons in physical directory VS saved in options
	if(!empty($eventon_addons_opt) && is_array($eventon_addons_opt)){
		foreach($eventon_addons_opt as $addonf=>$addon){
			if(empty($addon['type']) || $addon['type']!='extension'){
				
				// remove the addons that are in the options that arent physically present
				if(is_array($physical_addons_array) && !array_key_exists($addonf, $physical_addons_array) ){
					// remove from options values array
					unset($eventon_addons_opt[$addonf]);	
					
				
				// no physical addons
				}else if(!is_array($physical_addons_array)){
					unset($eventon_addons_opt[$addonf]);
					
				}
			}
		}	
	}
	
	// update options
	if(is_array($physical_addons_array) && is_array($eventon_addons_opt) ){
		$new_eventon_addons = array_merge($eventon_addons_opt, $physical_addons_array);
		
	}elseif(is_array($physical_addons_array)  && !is_array($eventon_addons_opt)){
		// no saved eventon_addons in get_option
		$new_eventon_addons =$physical_addons_array;
		
	}elseif(!is_array($physical_addons_array)  && is_array($eventon_addons_opt)){
		// No new addons found but addon details exist on get_options so resave that.
		$new_eventon_addons =$eventon_addons_opt;	
	}else{
		$new_eventon_addons='';
	}
	update_option('eventon_addons',$new_eventon_addons);
	

	
}

/**
 * update a field for addon
 */
function eventon_update_addon_field($addon_name, $field_name, $new_value){
	$eventon_addons_opt = get_option('eventon_addons');
	
	$newarray = array();
	
	// the array that contain addon details in array
	$addon_array = $eventon_addons_opt[$addon_name];
	
	foreach($addon_array as $field=>$val){
		if($field==$field_name){ 
			$val=$new_value;
		}
		$newarray[$field]=$val;
	}
	$new_ar[$addon_name] = $newarray;
	
	$merged=array_merge($eventon_addons_opt,$new_ar);
	
	
	update_option('eventon_addons',$merged);
}




?>