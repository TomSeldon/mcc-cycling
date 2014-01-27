<?php
/**
 * EventON Core Functions
 *
 * Functions available on both the front-end and admin.
 *
 * @author 		AJDE
 * @category 	Core
 * @package 	EventON/Functions
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	
function eventon_is_future_event($current_time, $row_end_unix, $evcal_cal_hide_past){
	$future_event = ($row_end_unix >= $current_time )? true:false;
	
	if( 
		( ($evcal_cal_hide_past=='yes' ) && $future_event )
		|| ( ($evcal_cal_hide_past=='no' ) || ($evcal_cal_hide_past=='' ))
	){
		return true;
	}else{
		return false;
	}
}

function eventon_is_event_in_daterange($Estart_unix, $Eend_unix, $Mstart_unix, $Mend_unix){		
	if(
		($Estart_unix<=$Mstart_unix && $Eend_unix>=$Mstart_unix) ||
		($Estart_unix<=$Mend_unix && $Eend_unix>=$Mend_unix) ||
		($Mstart_unix<=$Estart_unix && $Estart_unix<=$Mend_unix && $Eend_unix=='') ||		
		($Mstart_unix<=$Estart_unix && $Estart_unix<=$Mend_unix && $Eend_unix==$Estart_unix) 	||
		($Mstart_unix<=$Estart_unix && $Estart_unix<=$Mend_unix && $Eend_unix!=$Estart_unix)
	){
		return true;
	}else{
		return false;
	}
}



function eventon_get_formatted_time($row_unix){
	/*
		D = Mon - Sun
		j = 1-31
		l = Sunday - Saturday
		N - day of week 1-7
		S - st, nd rd
		n - month 1-12
		F - January - Decemer
		t - number of days in month
		z - day of the year
		Y - 2000
		g = hours
		i = minute
		a = am/pm
	*/
	$key = array('D','j','l','N','S','n','F','t','z','Y','g','i','a');
	$date = date('D-j-l-N-S-n-F-t-z-Y-g-i-a',$row_unix);
	$date = explode('-',$date);
	
	
	foreach($date as $da=>$dv){
		if($da==6){
			$output[$key[$da]]= eventon_returnmonth_name_by_num($date[5]); 
		}else if($da==2){
			$output[$key[$da]]= eventon_get_event_day_name($date[3]); 
		}else{
			$output[$key[$da]]= $dv;
		}
	}
	
	return $output;
}

function eventon_get_event_day_name($day_number){
	//event day array
	$evcal_opt2= get_option('evcal_options_evcal_2');
	$custom_day_names = $evcal_opt2['evcal_cal_day_cus'];			
	if($custom_day_names == '' || $custom_day_names=='no'){
		$evcal_day_is= array(1=>'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	}else{
		for($x=1; $x<8; $x++){
			$evcal_day_is[$x] = $evcal_opt2['evcal_lang_day'.$x];
		}
	}
	
	return $evcal_day_is[$day_number];
}

function eventon_get_new_monthyear($current_month_number, $current_year, $difference){
	$month_num = $current_month_number + $difference;
	if($month_num>12){
		$next_m_n = $month_num-12;
		$next_y = $current_year+1;
	}else{
		$next_m_n =$month_num;
		$next_y = $current_year;
	}
	
	$ra = array(
		'month'=>$next_m_n, 'year'=>$next_y
	);
	return $ra;
}

function eventon_get_custom_language($evo_lang_opts, $field, $default_val){
	$new_lang_val = (!empty($evo_lang_opts[$field]) )?
		$evo_lang_opts[$field]: $default_val;
	return $new_lang_val;
}


/** SORTING arrangement functions **/
function cmp_esort_startdate($a, $b){
	return $a["event_start_unix"] - $b["event_start_unix"];
}
function cmp_esort_title($a, $b){
	return strcmp($a["event_title"], $b["event_title"]);
}
function cmp_esort_color($a, $b){
	return strcmp($a["event_color"], $b["event_color"]);
}

// Returns a proper form of labeling for custom post type
/**
 * Function that returns an array containing the IDs of the products that are on sale.
 */
if( !function_exists ('eventon_get_proper_labels')){
	function eventon_get_proper_labels($sin, $plu){
		return array(
		'name' => _x($plu, 'post type general name'),
		'singular_name' => _x($sin, 'post type singular name'),
		'add_new' => _x('Add New', $sin),
		'add_new_item' => __('Add New '.$sin),
		'edit_item' => __('Edit '.$sin),
		'new_item' => __('New '.$sin),
		'all_items' => __('All '.$plu),
		'view_item' => __('View '.$sin),
		'search_items' => __('Search '.$plu),
		'not_found' =>  __('No '.$plu.' found'),
		'not_found_in_trash' => __('No '.$plu.' found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => $plu
	  );
	}
}
// Return formatted time 
if( !function_exists ('ajde_evcal_formate_date')){
	function ajde_evcal_formate_date($date,$return_var){	
		$srt = strtotime($date);
		$f_date = date($return_var,$srt);
		return $f_date;
	}
}

if( !function_exists ('returnmonth')){
	function returnmonth($n){
		$timestamp = mktime(0,0,0,$n,1,2013);
		return date('F',$timestamp);
	}
}
if( !function_exists ('eventon_returnmonth_name_by_num')){
	function eventon_returnmonth_name_by_num($n){
		$evcal_val2= get_option('evcal_options_evcal_2');
		
		//get custom month names
		$default_month_names = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
		$month_field_ar = array(1=>'evcal_lang_jan',2=>'evcal_lang_feb',3=>'evcal_lang_mar',4=>'evcal_lang_apr',5=>'evcal_lang_may',6=>'evcal_lang_jun',7=>'evcal_lang_jul',8=>'evcal_lang_aug',9=>'evcal_lang_sep',10=>'evcal_lang_oct',11=>'evcal_lang_nov',12=>'evcal_lang_dec');
		
		$cus_month_name = $evcal_val2[$month_field_ar[$n]];
		$cus_month_name =($cus_month_name!='')?$cus_month_name: $default_month_names[$n];
		
		return $cus_month_name;
	}
}

/**
 * eventon Term Meta API - Get term meta
 *
 * @access public
 * @param mixed $term_id
 * @param mixed $key
 * @param bool $single (default: true)
 * @return mixed
 */
function get_eventon_term_meta( $term_id, $key, $single = true ) {
	return get_metadata( 'eventon_term', $term_id, $key, $single );
}

/**
 * Get template part (for templates like the event-loop).
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 * @return void
 */
function eventon_get_template_part( $slug, $name = '' ) {
	global $eventon;
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/eventon/slug-name.php
	if ( $name )
		$template = locate_template( array ( "{$slug}-{$name}.php", "{$eventon->template_url}{$slug}-{$name}.php" ) );

	// Get default slug-name.php
	if ( !$template && $name && file_exists( AJDE_EVCAL_PATH . "/templates/{$slug}-{$name}.php" ) )
		$template = AJDE_EVCAL_PATH . "/templates/{$slug}-{$name}.php";

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/eventon/slug.php
	if ( !$template )
		$template = locate_template( array ( "{$slug}.php", "{$eventon->template_url}{$slug}.php" ) );

	if ( $template )
		load_template( $template, false );
		
}

if(!function_exists('date_parse_from_format')){
	function date_parse_from_format($_wp_format, $date){
		
		$date_pcs = preg_split('/ (?!.* )/',$_wp_format);
		$time_pcs = preg_split('/ (?!.* )/',$date);
		
		$_wp_date_str = preg_split("/[\s . , \: \- \/ ]/",$date_pcs[0]);
		$_ev_date_str = preg_split("/[\s . , \: \- \/ ]/",$time_pcs[0]);
		
		$check_array = array(
			'Y'=>'year',
			'y'=>'year',
			'm'=>'month',
			'n'=>'month',
			'M'=>'month',
			'F'=>'month',
			'd'=>'day',
			'j'=>'day',
			'D'=>'day',
			'l'=>'day',
		);
		
		foreach($_wp_date_str as $strk=>$str){
			
			if($str=='M' || $str=='F' ){
				$str_value = date('n', strtotime($_ev_date_str[$strk]));
			}else{
				$str_value=$_ev_date_str[$strk];
			}
			
			if(!empty($str) )
				$ar[ $check_array[$str] ]=$str_value;		
			
		}
		
		$ar['hour']= date('H', strtotime($time_pcs[1]));
		$ar['minute']= date('i', strtotime($time_pcs[1]));
		
		
		return $ar;
	}
}

function eventon_get_unix_time($data){
	
	$_wp_date_format = get_option('date_format'); // get default site-wide date format
	//$_wp_date_str = split("[\s|.|,|/|-]",$_wp_date_format);
	
	
	// generate start time unix
	if( !empty($data['evcal_start_time_hour'])  && !empty($data['evcal_start_date']) ){
		
		//get hours minutes am/pm 
		$time_string = $data['evcal_start_time_hour']
			.':'.$data['evcal_start_time_min'].$data['evcal_st_ampm'];
		
		// event start time string
		$date = $data['evcal_start_date'].' '.$time_string;
		
		// parse string to array by time format
		//$__ti = date_parse_from_format($_wp_date_format.' g:ia', $date);
		$__ti = date_parse_from_format($_wp_date_format.' g:ia', $date);
				
		// GENERATE unix time
		$unix_start = mktime($__ti['hour'], $__ti['minute'],0, $__ti['month'], $__ti['day'], $__ti['year'] );
		
		
		//update_post_meta( 233, 'test', $__ti['hour'].' '.$unix_start);
		
	}else{ $unix_start =0; }
	

	if( !empty($data['evcal_end_time_hour'])  && !empty($data['evcal_end_date']) ){
		
		//get hours minutes am/pm 
		$time_string = $data['evcal_end_time_hour']
			.':'.$data['evcal_end_time_min'].$data['evcal_et_ampm'];
		
		// event start time string
		$date = $data['evcal_end_date'].' '.$time_string;
				
		
		// parse string to array by time format
		$__ti = date_parse_from_format($_wp_date_format.' g:ia', $date);
				
		// GENERATE unix time
		$unix_end = mktime($__ti['hour'], $__ti['minute'],0, $__ti['month'], $__ti['day'], $__ti['year'] );		
				
		
	}else{ $unix_end =0; }
		
		
	$unix_end =(!empty($unix_end) )?$unix_end:$unix_start;
	
	// output the unix timestamp
	$output = array(
		'unix_start'=>$unix_start,
		'unix_end'=>$unix_end
	);
	
	return $output;
}

if( !function_exists('date_parse_from_format') ){
	function date_parse_from_format($format, $date) {
	  $dMask = array(
		'H'=>'hour',
		'i'=>'minute',
		's'=>'second',
		'y'=>'year',
		'm'=>'month',
		'd'=>'day'
	  );
	  $format = preg_split('//', $format, -1, PREG_SPLIT_NO_EMPTY); 
	  $date = preg_split('//', $date, -1, PREG_SPLIT_NO_EMPTY); 
	  foreach ($date as $k => $v) {
		if ($dMask[$format[$k]]) $dt[$dMask[$format[$k]]] .= $v;
	  }
	  return $dt;
	}
}

?>