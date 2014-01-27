<?php
/**
 * EVO_generator class.
 *
 * @class 		EVO_generator
 * @version		1.8
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */

class EVO_generator {
	
	private $google_maps_load, 
		$evopt1, 
		$evopt2, 
		$evcal_hide_sort;
		
		
	public $wp_arguments='';
	
	/**
	 *	Construction function
	 */
	public function __construct(){
		
		/** set class wide variables **/
		$this->evopt1= get_option('evcal_options_evcal_1');
		$this->evopt2= get_option('evcal_options_evcal_2');
		
		// set reused values
		$this->evcal_hide_sort = (!empty($this->evopt1['evcal_hide_sort']))? $this->evopt1['evcal_hide_sort']:null;
		
		// google maps loading conditional statement
		if( !empty($this->evopt1['evcal_cal_gmap_api']) && ($this->evopt1['evcal_cal_gmap_api']=='yes') 	){
			if(!empty($this->evopt1['evcal_gmap_disable_section']) && $this->evopt1['evcal_gmap_disable_section']=='complete'){
				$this->google_maps_load= false;
				wp_enqueue_script( 'eventon_init_gmaps', AJDE_EVCAL_URL. '/assets/js/eventon_init_gmap_blank.js', array('jquery'),'1.0',true ); // load a blank initiate gmap javascript
			}else{
				$this->google_maps_load= true;
				wp_enqueue_script( 'eventon_init_gmaps', AJDE_EVCAL_URL. '/assets/js/eventon_init_gmap.js', array('jquery'),'1.0',true );
			}
			
		}else {
			$this->google_maps_load= true;
			wp_enqueue_script( 'evcal_gmaps', 'https://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'),'1.0',true);
			wp_enqueue_script( 'eventon_init_gmaps', AJDE_EVCAL_URL. '/assets/js/eventon_init_gmap.js', array('jquery'),'1.0',true );
		}
		
		
	}
	
	/**
	 * function to build the entire event calendar
	 */
	public function eventon_generate_calendar($args){
		global $EventON, $wpdb;		
		
		// extract the variable values 
		$ar_def = array(			
			'cal_id'=>'1',
			'event_count'=>0,
			'month_incre'=>0,
			'show_upcoming'=>0,
			'number_of_months'=>3,
			'event_type'=> 'all',
			'event_type_2'=> 'all',
			'focus_start_date_range'=>'',
			'focus_end_date_range'=>'',
			'filters'=>''
		);
		
		if(!empty($args) ){
			extract(array_merge($ar_def, $args));
		}else{	extract($ar_def);	}
		
					
		// If settings set to hide calendar
		if($this->evopt1['evcal_cal_hide']=='no'||$this->evopt1['evcal_cal_hide']==''):			
						
			$evcal_plugin_url= AJDE_EVCAL_URL;			
			$content = $content_li='';	
			
			// fix month_incre empty values messing up the calendar date
			$month_incre = (!empty($month_incre))? $month_incre:0;
			
			// current focus month calculation
			$current_timestamp =  current_time('timestamp');
			$focused_month_num_raw = date('n',$current_timestamp);	
			
			$focused_month_num = date('n', strtotime($month_incre.' month', $current_timestamp) );
			$focused_year = date('Y', strtotime($month_incre.' month', $current_timestamp) );
			
			
			$cal_version =  get_option('eventon_plugin_version');			
			
			//BASE settings to pass to calendar
			$evcal_gmap_format = ($this->evopt1['evcal_gmap_format']!='')?$this->evopt1['evcal_gmap_format']:'roadmap';	
			$evcal_gmap_zooml = ($this->evopt1['evcal_gmap_zoomlevel']!='')?$this->evopt1['evcal_gmap_zoomlevel']:'12';		
			
			
			// Calendar SHELL
			$content.="<div id='evcal_calendar_".$cal_id."' class='ajde_evcal_calendar' cal_ver='".$cal_version."' mapformat='".$evcal_gmap_format."' mapzoom='".$evcal_gmap_zooml."' cur_m='".$focused_month_num."' cur_y='".$focused_year."'>";
			
			
			// ========================================
			// HEADER with month and year name	
			if($show_upcoming==0){
			
				$focus_month_name = eventon_returnmonth_name_by_num($focused_month_num);								
				$hide_arrows_check = ($this->evopt1['evcal_arrow_hide']=='yes')?"style='display:none'":null;
				$sort_class = ($this->evcal_hide_sort=='yes')?'evcal_nosort':null;
				$filters_status = (!empty($filters))?'true':'false';
				
				$content.="<div id='evcal_head' class='calendar_header ".$sort_class."' cur_m='".$focused_month_num."' cur_y='".$focused_year."' ev_cnt='".$event_count."' sort_by='sort_date' filters_on='{$filters_status}'>					
					<a id='evcal_prev' class='evcal_arrows evcal_btn_prev' ".$hide_arrows_check."></a>
					<p id='evcal_cur'> ".$focus_month_name.", ".$focused_year."</p>
					<a id='evcal_next' class='evcal_arrows evcal_btn_next' ".$hide_arrows_check."></a>";	
				
				// (---) Hook for addon
				if(has_filter('eventon_calendar_header_content')){
					$content.= apply_filters('eventon_calendar_header_content', $content);
				}
				$content.="<div class='clear'></div>
				</div>";
				
				// SORT BAR
				$content.= $this->eventon_get_cal_sortbar($event_type, $event_type_2);
			}
			
						
			
			// upcoming events display format
			// check repeating months
			$number_of_months = ($show_upcoming==1)?$number_of_months:1;
			$defined_date_ranges = ( empty($focus_start_date_range) && empty($focus_end_date_range) )?1:0;
			
			
			// for each month
			for($x=0; $x<$number_of_months; $x++){				
				
				
				// check if date ranges present
				if( $defined_date_ranges==1){	
				
					// default start end date range -- for month view
					$get_new_monthyear = eventon_get_new_monthyear($focused_month_num, $focused_year,$x);
					$active_month_name = eventon_returnmonth_name_by_num($get_new_monthyear['month']);
					
					$focus_start_date_range = mktime( 0,0,0,$get_new_monthyear['month'],1,$get_new_monthyear['year'] );
					$time_string = $get_new_monthyear['year'].'-'.$get_new_monthyear['month'].'-1';		
					$focus_end_date_range = mktime(23,59,59,($get_new_monthyear['month']),(date('t',(strtotime($time_string) ))), ($get_new_monthyear['year']));
				}
				
				
				
				// generate events within the focused date range
				$eve_args = array(
					'focus_start_date_range'=>$focus_start_date_range,
					'focus_end_date_range'=>$focus_end_date_range,
					'sort_by'=>'sort_date', // by default sort events by start date					
					'event_count'=>$event_count,
					'ev_type'=>$event_type,
					'ev_type_2'=>$event_type_2,
					'filters'=>$filters,
					'number_months'=>$number_of_months // to determine empty label 
				);
				$content_li = $this->eventon_generate_events($eve_args);	
				
				
				// Construct months exterior 
				if($show_upcoming==1 && $content_li != 'empty'){
					$content.= "<div class='evcal_month_line'><p>".$active_month_name."</p></div>";
				}		
				if($content_li != 'empty'){
					// ## Eventon Calendar events list
					$content.="<div id='evcal_list' class='eventon_events_list'>";
				}
				
				if($content_li != 'empty'){		
					$content.=$content_li;
				}
				if($content_li != 'empty'){
					$content.="</div>"; 
				}
			}
			
			
			$content.="<div class='clear'></div></div>";
				
			return  $content;			
		
		endif;
	}
	
	
	
	/**
	 * MAIN function to generate individual events.
	 *
	 * @access public
	 * @return void
	 /*
		possible values
		array(
			'focus_start_date_range'
			'focus_end_date_range'
			'sort_by'=>sort_date,sort_title,sort_color,event_type, event_type_2
			'ev_type'
			'ev_type_2'
			'event_count'
			'number_months'
			'filters'
		)
	*/	 
	public function eventon_generate_events($args){
		
		global $EventON;
		
		$no_event_text = (!empty($this->evopt2['evcal_lang_noeve']))? $this->evopt2['evcal_lang_noeve']:"No Events";
		
		
		// Default array values for event generation
		$defaults = array(
			'sort_by'=>'sort_date',
			'event_count'=>0,
			'ev_type'=>'',
			'ev_type_2'=>'',
		);
		$ecv = array_merge($defaults,$args);//event calendar values = ecv
		
		
		// ===========================
		// WPQUery Arguments
		$wp_arguments = array (
			'post_type' 		=> 'ajde_events' ,
			'posts_per_page'	=>-1 ,
			'order'				=>'ASC',					
		);
		
		// apply other filters to wp argument
		$wp_arguments = $this->apply_evo_filters_to_wp_argument($wp_arguments, $ecv['filters'],$ecv['ev_type'],$ecv['ev_type_2']);
		
		
		
		/*
		$wp_argument= array(
			'post_type'=>'ajde_events',
			'posts_per_page'=>-1,
			'order'=>'ASC',
			'meta_query'=>array(
				array(
					'key'=>'evcal_location',
					'value'=>'5982 Emil Ct. Plainfield, IN 46168'
				)
			)
		);
		print_r($wp_argument);
		*/
		//print_r($wp_arguments);
				
		// -----------------------------
		// hook for addons
		if(has_filter('eventon_wp_query_args')){
			$wp_arguments = apply_filters('eventon_wp_query_args',$wp_arguments);
		}
				
		//print_r($wp_arguments);
		
		$this->wp_arguments = $wp_arguments;
		
		// ========================		
		$event_list_array = $this->wp_query_event_cycle($wp_arguments, $ecv['focus_start_date_range'], $ecv['focus_end_date_range']);
		
		
		
		// primary sorting mechanism
		if(is_array($event_list_array)){			
			switch($ecv['sort_by']){
				case has_action("eventon_event_sorting_{$ecv['sort_by']}"):
					do_action("eventon_event_sorting_{$ecv['sort_by']}", $event_list_array);
					
				break;
				case 'sort_date':
					usort($event_list_array, 'cmp_esort_startdate' );
				break;case 'sort_title':
					usort($event_list_array, 'cmp_esort_title' );
				break; case 'sort_color':
					usort($event_list_array, 'cmp_esort_color' );
				break;
				
			}
		}
		//print_r($event_list_array);
		
		$months_event_array = $this->generate_event_data($event_list_array, $ecv['focus_start_date_range']);
		//print_r($months_event_array);
		
		
		// ========================
		// RETURN VALUES
		$content_li='';
		if( is_array($months_event_array) && count($months_event_array)>0){
			if($ecv['event_count']==0 ){
				foreach($months_event_array as $event){
					$content_li.= $event['content'];
				}
				
			}else if($ecv['event_count']>0){
				for($x=0; $x<$ecv['event_count']; $x++){
					$content_li.= $months_event_array[$x]['content'];
				}
			}
		}else{	
			$evcal_hide_empty_um = $this->evopt1['evcal_hide_empty_um']; // settings val - hide empty upcoming months
			
			// if its upcoming events list, no events 
			if(!empty($evcal_hide_empty_um) && $evcal_hide_empty_um=='yes' && !empty($ecv['number_months']) && $ecv['number_months']>0){
				$content_li = "empty";
				
			}else{
				$content_li = "<div class='eventon_list_event'><p class='no_events'>".$no_event_text."</p></div>";
			}
			
		}
		return $content_li;
		
	}// END evcal_generate_events()
	
	
	
	/**
	 * WP_Query function to generate relavent events for a given month
	 * return events list within start - end date range for WP_Query arg.
	 * return array
	 */
	public function wp_query_event_cycle($wp_arguments, $focus_month_beg_range, $focus_month_end_range){
		
		//echo $focus_month_beg_range.' '. $focus_month_end_range.'ff';
		
		
		$event_list_array='';
		$wp_arguments= (!empty($wp_arguments))?$wp_arguments: $this->wp_arguments;
		
		
		/** RUN through all events **/
		$events = new WP_Query( $wp_arguments);
		if ( $events->have_posts() ) :
			
			// Define option values for the front-end
			$cur_time_basis = (!empty($this->evopt1['evcal_past_ev']) )? $this->evopt1['evcal_past_ev'] : null;
			$evcal_cal_hide_past= $this->evopt1['evcal_cal_hide_past'];
			
			
			//date_default_timezone_set($tzstring);	
			if($evcal_cal_hide_past=='yes' && $cur_time_basis=='today_date'){
				// this is based on local time
				$current_time = strtotime( date_i18n("m/j/Y") );	
			}else{
				// this is based on UTC time zone
				$current_time = current_time('timestamp');		
			}
			
			
			
			// Pre variables
			$content_li='';
			
			while( $events->have_posts()): $events->the_post();
			
				$p_id = get_the_ID();
				$ev_vals = get_post_custom($p_id);
				
				
				$is_recurring_event = (!empty($ev_vals['evcal_repeat']) )? $ev_vals['evcal_repeat'][0]: null;
				//$__is_all_day_event = (!empty($ev_vals['evcal_allday']) && $ev_vals['evcal_allday'][0]=='yes')?true:false;
				
				// initial event start and end UNIX
				$row_start = (!empty($ev_vals['evcal_srow']))? $ev_vals['evcal_srow'][0] :null;
				$row_end = ( !empty($ev_vals['evcal_erow'])  && !empty($ev_vals['evcal_end_date']) )? 
					$ev_vals['evcal_erow'][0]:$row_start;
				
				
				// check for recurring event 
				if($is_recurring_event=='yes'){
					$frequency = $ev_vals['evcal_rep_freq'][0];
					$repeat_gap_num = $ev_vals['evcal_rep_gap'][0];
					$repeat_num = (int)$ev_vals['evcal_rep_num'][0];
					
					
					// each repeating instance	
					$monthly_row_start = $row_start;
					for($x=0; $x<=($repeat_num); $x++){
												
						$repeat_multiplier = ((int)$repeat_gap_num) * $x;
						//$multiply_term = '+'.$repeat_multiplier.' '.$term;
						
						// Get repeat terms for different frequencies
						switch($frequency){
							// Additional frequency filters
							case has_filter("eventon_event_frequency_{$frequency}"):
								$terms = apply_filters("eventon_event_frequency_{$frequency}", $repeat_multiplier);								
								$term = $terms['term'];
								$term_ar = $terms['term_ar'];
							break;
							case 'yearly':
								$term = 'year';	$term_ar = 'ry';
							break;
							case 'monthly':
								$term = 'month';	$term_ar = 'rm';
							break; 
							case 'weekly':
								$term = 'week';	$term_ar = 'rw';
							break;							
							default: $term = $term_ar = ''; break;
						}
						
						
						$E_start_unix = strtotime('+'.$repeat_multiplier.' '.$term, $row_start);
						$E_end_unix = strtotime('+'.$repeat_multiplier.' '.$term, $row_end);
									
						
						$fe = eventon_is_future_event($current_time, $E_end_unix, $evcal_cal_hide_past);
						$me = eventon_is_event_in_daterange($E_start_unix,$E_end_unix, $focus_month_beg_range,$focus_month_end_range);
						
						if($fe && $me){
							$event_list_array[]= array(
								'event_id' => $p_id,
								'event_start_unix'=>$E_start_unix,
								'event_end_unix'=>$E_end_unix,
								'event_title'=>get_the_title(),
								'event_color'=>$ev_vals['evcal_event_color_n'][0],
								'event_type'=>$term_ar
							);
						}						
					}	
					
				}else{
				// Non recurring event
					$fe = eventon_is_future_event($current_time, $row_end, $evcal_cal_hide_past);
					$me = eventon_is_event_in_daterange($row_start,$row_end, $focus_month_beg_range,$focus_month_end_range);
					
					if($fe && $me){
						$event_list_array[]= array(
							'event_id' => $p_id,
							'event_start_unix'=>$row_start,
							'event_end_unix'=>$row_end,
							'event_title'=>get_the_title(),
							'event_color'=>$ev_vals['evcal_event_color_n'][0],
							'event_type'=>'nr'
						);						
					}		
				}
			endwhile;
		
		endif;
		wp_reset_query();
		
		return $event_list_array;
	}
	
	
	/**
	 *	output single event data
	 */
	public function get_single_event_data($event_id){
		$emv = get_post_custom($event_id);
		
		$event_array[] = array(
			'event_id' => $event_id,
			'event_start_unix'=>$emv['evcal_srow'][0],
			'event_end_unix'=>$emv['evcal_erow'][0],
			'event_title'=>get_the_title($event_id),
			'event_color'=>$emv['evcal_event_color_n'][0],
			'event_type'=>'nr'
		);
		
		$month_int = date('n', time() );
		
		return $this->generate_event_data($event_array, '', $month_int);
	}
	
	
	/**
	 * GENERATE individual event data
	 */
	public function generate_event_data($event_list_array, $focus_month_beg_range='', $FOCUS_month_int='', $FOCUS_year_int=''){
		
		
		$months_event_array='';
		
		// Initial variables
		$wp_time_format = get_option('time_format');
		$default_event_color = (!empty($this->evopt1['evcal_hexcode']))?$this->evopt1['evcal_hexcode']:'#ffa800';
		$evt_name = (!empty($this->evopt1['evcal_eventt']))?$this->evopt1['evcal_eventt']:'Event Type';
		$evt_name2 = (!empty($this->evopt1['evcal_eventt2']))?$this->evopt1['evcal_eventt2']:'Event Type 2';
		
		
		$CURRENT_month_INT = (!empty($FOCUS_month_int))?$FOCUS_month_int: date('n', $focus_month_beg_range ); // 1 through 12
		//$CURRENT_year_INT = (!empty($FOCUS_year_int))?$FOCUS_year_int: date('Y', $focus_month_beg_range ); // 1 through 12
		
		$print ='';
		
		// EACH EVENT
		if(is_array($event_list_array) ){
		foreach($event_list_array as $event):
			
			$event_id = $event['event_id'];
			$event_start_unix = $event['event_start_unix'];
			$event_end_unix = $event['event_end_unix'];
			$event_type = $event['event_type'];
			
			$event = get_post($event_id);
			$ev_vals = get_post_custom($event_id);
			
			// define variables
			$ev_other_data = $ev_other_data_top = $html_event_type_info= $_event_date_HTML=$_event_datarow='';	
			$_is_end_date=true;
			
			$DATE_start_val=eventon_get_formatted_time($event_start_unix);
			if(empty($event_end_unix)){
				$_is_end_date=false;
				$DATE_end_val= $DATE_start_val;
			}else{
				$DATE_end_val=eventon_get_formatted_time($event_end_unix);
			}
			
			// Unique ID generation
			$unique_varied_id = 'evc'.$event_start_unix.(uniqid()).$event_id;
			$unique_id = 'evc_'.$event_start_unix.$event_id;
			
			
			
			
			/** EVENT TYPE = ALL DAY **/			
			if(!empty($ev_vals['evcal_allday']) && $ev_vals['evcal_allday'][0]=='yes'){				
				//set as "All day event"				
				$evcal_lang_allday = (!empty($this->evopt2['evcal_lang_allday']) )?
					$this->evopt2['evcal_lang_allday']: 'All Day';
				
				$_event_date_HTML = array(
					'html_fromto'=>"<em class='evcal_alldayevent_text'>(".$evcal_lang_allday.": ".$DATE_start_val['l'].")</em>",
					'html_date'=>$DATE_start_val['j'],
					'html_prettytime'=>$evcal_lang_allday.' ('.$DATE_start_val['l'].')',
					'class_daylength'=>"sin_val"
				);			
				
			}else{
				
				/** NOT ALL DAY
				
				/** EVENT TYPE = start an end in SAME MONTH **/
				if($DATE_start_val['n'] == $DATE_end_val['n']){
					
					/** EVENT TYPE = start and end in SAME DAY **/
					if($DATE_start_val['j'] == $DATE_end_val['j']){
						
						$__from_to = date($wp_time_format,($event_start_unix)).' - '. date($wp_time_format,($event_end_unix));
						
						$_event_date_HTML = array(
							'html_date'=>$DATE_start_val['j'],
							'html_fromto'=>$__from_to,
							'html_prettytime'=> '('.$DATE_start_val['l'].') '.$__from_to,
							'class_daylength'=>"sin_val"
						);	
						
					}else{
						// different start end date
						
						$_event_date_HTML = array(							
							'html_date'=>$DATE_start_val['j'].'<span> - '.$DATE_end_val['j'].'</span>',
							'html_fromto'=>date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['j'].' '.date($wp_time_format,($event_end_unix)),
							
							'html_prettytime'=> $DATE_start_val['j'].' ('.$DATE_start_val['l'].') '.date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['j'].' ('.$DATE_end_val['l'].') '.date($wp_time_format,($event_end_unix)),
							'class_daylength'=>"mul_val"
						);	
					}					
				}else{
					/** EVENT TYPE = different start and end months **/
					
					/** EVENT TYPE = start month is before current month **/
					if($CURRENT_month_INT != $DATE_start_val['n']){
						$html_fromto =
							$DATE_start_val['F'].' '.$DATE_start_val['j'].' '.date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['F'].' '.$DATE_end_val['j'].' '.date($wp_time_format,($event_end_unix));
												
					}else{
						/** EVENT TYPE = start month is current month **/
						$html_fromto =
							date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['F'].' '.$DATE_end_val['j'].' '.date($wp_time_format,($event_end_unix));						
					}
					$html_prettytime = 
							$DATE_start_val['F'].' '.$DATE_start_val['j'].' ('.$DATE_start_val['l'].') '.date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['F'].' '.$DATE_end_val['j'].' ('.$DATE_end_val['l'].') '.date($wp_time_format,($event_end_unix));	
					
					$_event_date_HTML = array(							
						'html_date'=>$DATE_start_val['j'].'<span> - '.$DATE_end_val['j'].'</span>',
						'html_fromto'=>$html_fromto,
						'html_prettytime'=>$html_prettytime,
						'class_daylength'=>"mul_val"
					);
				}
			}		
			
			// (---) hook for addons
			if(has_filter('eventon_eventcard_date_html'))
				apply_filters('eventon_eventcard_date_html', $_event_date_HTML, $event_id);
		
			
			
			// EVENT FEATURES IMAGE
			$img_id =get_post_thumbnail_id($event_id);
			if($img_id!=''){				
				$img_src = wp_get_attachment_image_src($img_id,'full');
				$ev_img_code = "<div class='evcal_evdata_img evo_metarow_fimg' style='background-image: url(".$img_src[0].")'></div>";				
				$_event_datarow['fimage'] = $ev_img_code;
			}
			
			// EVENT DESCRIPTION
			$evcal_event_content =apply_filters('the_content', $event->post_content);
			if(!empty($evcal_event_content) ){
				$event_full_description = $evcal_event_content;
			}else{
				// event description compatibility from older versions.
				$event_full_description =(!empty($ev_vals['evcal_description']))?$ev_vals['evcal_description'][0]:null;
			}			
			if(!empty($event_full_description) ){
				
				// check if character length of description is longer than X size
				if( (strlen($event_full_description) )>600 ){
					$more_code = "<div class='eventon_details_shading_bot'>
								<p class='eventon_shad_p' content='less'><span class='ev_more_text' txt='".eventon_get_custom_language($this->evopt2, 'evcal_lang_less','less')."'>".eventon_get_custom_language($this->evopt2, 'evcal_lang_more','more')."</span><span class='ev_more_arrow'></span></p>
							</div>";
					$evo_more_active_class = 'shorter_desc';
				}else{$more_code=''; $evo_more_active_class = '';}
				
				$_event_datarow['description'] ="<div class='evcal_evdata_row bordb evcal_event_details'>
						<span class='evcal_evdata_icons evcalicon_1'></span>
						<div class='evcal_evdata_cell ".$evo_more_active_class."'>".$more_code."<div class='eventon_full_description'>
								<h2 class='padb10'>".eventon_get_custom_language($this->evopt2, 'evcal_evcard_details','Event Details')."</h2>
								".apply_filters('the_content',$event_full_description)."
							</div>
						</div>
					</div>";
			}
			
			
			// EVENT TIME
			$_event_datarow['time'] =  
				"<div class='evcal_evdata_row bordb evcal_evrow_sm evo_metarow_time'>
					<span class='evcal_evdata_icons evcalicon_6'></span>
					<div class='evcal_evdata_cell'>							
						<h3>".eventon_get_custom_language($this->evopt2, 'evcal_lang_time','Time')."</h3><p>".$_event_date_HTML['html_prettytime']."</p>
					</div>
				</div>";	
			
			
			// EVENT LOCATION
			if(!empty($ev_vals['evcal_location'])){
				$_event_datarow['location']=
					"<div class='evcal_evdata_row bordb evcal_evrow_sm evo_metarow_location'>
						<span class='evcal_evdata_icons evcalicon_7'></span>
						<div class='evcal_evdata_cell'>							
							<h3>".eventon_get_custom_language($this->evopt2, 'evcal_lang_location','Location')."</h3><p>".$ev_vals['evcal_location'][0]."</p>
						</div>
					</div>";
			}
			
			
			// GOOGLE maps			
			if( ($this->google_maps_load) && !empty($ev_vals['evcal_location']) && ($ev_vals['evcal_gmap_gen'][0]=='yes') ){
				$_event_datarow['gmap']="<div class='evcal_gmaps bordb evo_metarow_gmap' id='".$unique_varied_id."_gmap'></div>";
				$gmap_api_status='';
			}else{	$gmap_api_status = 'gmap_status="null"';	}
			
			
			
			// EVENT BRITE
			// check if eventbrite actually used in this event
			if(!empty($ev_vals['evcal_eventb_data_set'] ) && $ev_vals['evcal_eventb_data_set'][0]=='yes'){			
				// Event brite capacity
				if( 
					!empty($ev_vals['evcal_eventb_tprice'] ) &&				
					!empty($ev_vals['evcal_eventb_url'] ) )
				{					
					
					// GET Custom language text
					$evcal_tx_1 = eventon_get_custom_language($this->evopt2, 'evcal_evcard_tix2','Ticket for the event');
					$evcal_tx_2 = eventon_get_custom_language($this->evopt2, 'evcal_evcard_btn2','Buy Now');
					$evcal_tx_3 = eventon_get_custom_language($this->evopt2, 'evcal_evcard_cap','Event Capacity');
					
					
					// EVENTBRITE with event capacity
					if(!empty($ev_vals['evcal_eventb_capacity'] )){
						$_event_datarow['eventbrite'] = "<div class='bordb'>
						<div class='evcal_col50'>
							<div class='evcal_evdata_row bordr '>
								<span class='evcal_evdata_icons evcalicon_3'></span>
								<div class='evcal_evdata_cell'>
									<h2 class='bash'>".$ev_vals['evcal_eventb_tprice'][0]."</h2>
									<p>".$evcal_tx_1."</p>
									<a href='".$ev_vals['evcal_eventb_url'][0]."' class='evcal_btn'>".$evcal_tx_2."</a>
								</div>
							</div>
						</div><div class='evcal_col50'>
							<div class='evcal_evdata_row'>
								<span class='evcal_evdata_icons evcalicon_4'></span>
								<div class='evcal_evdata_cell'>
									<h2 class='bash'>".$ev_vals['evcal_eventb_capacity'][0]."</h2>
									<p>".$evcal_tx_3."</p>
								</div>
							</div>
						</div><div class='clear'></div>
						</div>";
					}else{	
						// No event capacity
						$_event_datarow['eventbrite']= "<div class='bordb'>
							<div class='evcal_evdata_row bordr '>
								<span class='evcal_evdata_icons evcalicon_3'></span>
								<div class='evcal_evdata_cell'>
									<h2 class='bash'>".$ev_vals['evcal_eventb_tprice'][0]."</h2>
									<p>".$evcal_tx_1."</p>
									<a href='".$ev_vals['evcal_eventb_url'][0]."' class='evcal_btn'>".$evcal_tx_2."</a>
								</div>
							</div>
						<div class='clear'></div>
						</div>";
					}
				}				
			}
			
			
			// MEETUP & Learn More Link
			// check for learn more link
			if(!empty($ev_vals['evcal_lmlink'] ) ){
								
				// target
				$target = (!empty($ev_vals['evcal_lmlink_target'])  && $ev_vals['evcal_lmlink_target'][0]=='yes')? 'target="_blank"':null;
				$_event_datarow['learn_more'] = "<div class='evcal_evdata_row bordb evcal_evrow_sm'>
					<span class='evcal_evdata_icons evcalicon_5'></span>
					<div class='evcal_evdata_cell'>
						<p style='padding-top:4px'>".eventon_get_custom_language($this->evopt2, 'evcal_evcard_learnmore','Learn more about this event')." <a ".$target." href='".$ev_vals['evcal_lmlink'][0]."'>".eventon_get_custom_language($this->evopt2, 'evcal_evcard_learnmore2','Learn More')."</a></p>
					</div>
				</div>";
			}
			
				
			
			// PAYPAL Code
			if(!empty($ev_vals['evcal_paypal_link'][0]) && $this->evopt1['evcal_paypal_pay']=='yes'){
							
				$_event_datarow['paypal'] = "<div class='evcal_evdata_row bordb'>
						<span class='evcal_evdata_icons evcalicon_3'></span>
						<div class='evcal_evdata_cell'>
							<p>".eventon_get_custom_language($this->evopt2, 'evcal_evcard_tix1','Buy ticket via Paypal')."</p>
							<a href='".$ev_vals['evcal_paypal_link'][0]."' class='evcal_btn'>".eventon_get_custom_language($this->evopt2, 'evcal_evcard_btn1','Buy Now')."</a>
						</div>
					</div>";
			}
			
			
			// Event Organizer
			if(!empty($ev_vals['evcal_organizer'] )){				
				$_event_datarow['organizer']=
					"<div class='evcal_evdata_row bordb evcal_evrow_sm evo_metarow_organizer'>
						<span class='evcal_evdata_icons evcalicon_2'></span>
						<div class='evcal_evdata_cell'>							
							<h3>".eventon_get_custom_language($this->evopt2, 'evcal_evcard_org','Organisor')."</h3><p>".$ev_vals['evcal_organizer'][0]."</p>
						</div>
					</div>";
			}
			
			
			
			
			// =======================
			/** CONSTRUCT the EVENT CARD	 **/		
			if(!empty($_event_datarow) && count($_event_datarow)>0){
				
				// (---) hook for addons
				if(has_filter('eventon_event_detail_card')){
					$_event_datarow= apply_filters('eventon_event_detail_card', $event_id, $_event_datarow);
				}
				
				$__eventcard_data = implode('',$_event_datarow);
				
				$html_event_detail_card = "<div class='event_description evcal_eventcard' style='display:none'>".	
					$__eventcard_data."<div class='evcal_evdata_row bordb evcal_close'><p>".eventon_get_custom_language($this->evopt2, 'evcal_lang_close','Close')."</p></div></div>";
			}else{
				$html_event_detail_card=null;
			}
			
			
			
			/** Trigger attributes **/
			$event_description_trigger = (!empty($html_event_detail_card))? "desc_trig":null;
			$gmap_trigger = ($ev_vals['evcal_gmap_gen'][0]=='yes')? 'gmtrig="1"':'gmtrig="0"';
			
			
			//event color			
			$event_color = (!empty($ev_vals['evcal_event_color']) )? 
				$ev_vals['evcal_event_color'][0] : $default_event_color;
				
			//event type taxonomies #1
			$evcal_terms = wp_get_post_terms($event_id,'event_type');
				if($evcal_terms){					
					$html_event_type_info .="<span class='evcal_event_types'><em>".$evt_name.":</em>";
					foreach($evcal_terms as $termA):
						$html_event_type_info .="<em>".$termA->name."</em>";
					endforeach; $html_event_type_info .="</span>";
				}
			
			// event ex link
			$href = (!empty($ev_vals['evcal_exlink']) )? 'exlk="1" href="'.$ev_vals['evcal_exlink'][0].'"': 'exlk="0"';
			// target
			$target_ex = (!empty($ev_vals['evcal_exlink_target'])  && $ev_vals['evcal_exlink_target'][0]=='yes')? 'target="_blank"':null;
			
			// EVENT LOCATION
			$ev_location = (!empty($ev_vals['evcal_location']))?
				'<em class="evcal_location" add_str="'.$ev_vals['evcal_location'][0].'">'.eventon_get_custom_language($this->evopt2, 'evcal_lang_at','at').' '.$ev_vals['evcal_location'][0].'</em>'
				:null;
			
			//	=====================================
			// 	HTML
		
			// 	Short Event info line
			$html_info_line = 
				"<p class='evcal_cblock' style='background-color:".$event_color."' bgcolor='".$event_color."'>".$_event_date_HTML['html_date']."</p><p class='evcal_desc'><span class='evcal_desc_info'>".$_event_date_HTML['html_fromto']." ".$ev_location."</span>".$html_event_type_info."<span class='evcal_desc2 evcal_event_title'>".$event->post_title.$print."</span></p>";
			
			// (---) hook for addons
			if(has_filter('eventon_event_cal_short_info_line') ){
				$html_info_line = apply_filters('eventon_event_cal_short_info_line', $html_info_line);
			}
			
			
			
			// ## Eventon Calendar events list -- single event
			$event_html_code="<div class='eventon_list_event'>
			<a id='".$unique_id."' ".$href." ".$target_ex." style='border-left-color: ".$event_color."' class='evcal_list_a ".$event_description_trigger." ".$_event_date_HTML['class_daylength']." ".(($event_type!='nr')?'event_repeat':null)."' ".$gmap_trigger." ".(!empty($gmap_api_status)?$gmap_api_status:null).">{$html_info_line}</a>".$html_event_detail_card."<div class='clear'></div></div>";	
			
			
			
			// prepare output
			$months_event_array[]=array(
				'srow'=>$event_start_unix,
				'erow'=>$event_end_unix,
				'content'=>$event_html_code
			);
			
			
		endforeach;
		
		}else{
			$months_event_array;
		}
		
		return $months_event_array;
	}
	
	/**
	 *	 Add other filters to wp_query argument
	 */
	public function apply_evo_filters_to_wp_argument($wp_arguments, $filters='', $ev_type='', $ev_type_2=''){
		// -----------------------------
		// FILTERING events	
		
		// values from filtering events
		if(!empty($filters)){			
			
			// build out the proper format for filtering with WP_Query
			$cnt =0;
			foreach($filters as $filter){
				if($filter['filter_type']=='tax'){
					$filter_val = explode(',', $filter['filter_val']);
					$filter_tax[] = array(
						'taxonomy'=>$filter['filter_name'],
						'field'=>'id',
						'terms'=>$filter_val						
					);
					$cnt++;
				}else{				
					$filter_meta[] = array(
						'key'=>$filter['filter_name'],				
						'value'=>$filter['filter_val'],				
					);
				}				
			}
			
			
			if(!empty($filter_tax)){
				
				if($cnt>1){
					$filters_tax_wp_argument = array(
						'tax_query'=>array(
							'relation'=>'AND',$filter_tax
						)
					);
				}else{
					$filters_tax_wp_argument = array(
						'tax_query'=>$filter_tax
					);
				}
				$wp_arguments = array_merge($wp_arguments, $filters_tax_wp_argument);
			}
			if(!empty($filter_meta)){
				$filters_meta_wp_argument = array(
					'meta_query'=>$filter_meta
				);
				$wp_arguments = array_merge($wp_arguments, $filters_meta_wp_argument);
			}		
		}else{
			// to support event_type and event_type_2 variables from older version
			if(!empty($ev_type) && $ev_type !='all'){
				$ev_type = explode(',', $ev_type);
				$ev_type_ar = array(
						'tax_query'=>array( 
						array('taxonomy'=>'event_type','field'=>'id','terms'=>$ev_type) )	
					);
				
				$wp_arguments = array_merge($wp_arguments, $ev_type_ar);
			}
			
			//event type 2
			if(!empty($ev_type_2) && $ev_type_2 !='all'){
				$ev_type_2 = explode(',', $ev_type_2);
				$event_type_2 = array(
						'tax_query'=>array( 
						array('taxonomy'=>'event_type_2','field'=>'id','terms'=>$ev_type_2) )	
					);
				$wp_arguments = array_merge($wp_arguments, $ev_type_ar_2);
			}
		}
		
		return $wp_arguments;
	}
	
	/**
	 *	 out put just the sort bar for the calendar
	 */
	public function eventon_get_cal_sortbar($default_event_type='all', $default_event_type_2='all'){
		
		// define variable values
		$evt_name = (!empty($this->evopt1['evcal_eventt']))?$this->evopt1['evcal_eventt']:'Event Type';
		$evt_name2 = (!empty($this->evopt1['evcal_eventt2']))?$this->evopt1['evcal_eventt2']:'Event Type 2';		
		$sorting_options = (!empty($this->evopt1['evcal_sort_options']))?$this->evopt1['evcal_sort_options']:null;
		$filtering_options = (!empty($this->evopt1['evcal_filter_options']))?$this->evopt1['evcal_filter_options']:array();
		$content='';
			
		ob_start();
		
		echo "<div class='eventon_sorting_section'>";
		if( $this->evcal_hide_sort!='yes' ){ // if sort bar is set to show	
		
		// sorting section
			echo "
			<div class='eventon_sort_line'>
				<div class='eventon_sf_field'>
					<p>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sort','Sort By').":</p>
				</div>
				<div class='eventon_sf_cur_val evs'>
					<p class='sorting_set_val'>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sdate','Date')."</p>
				</div>
				<div class='eventon_sortbar_selection evs_3 evs' style='display:none'>
					<p val='sort_date' type='date' class='evs_btn evs_hide'>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sdate','Date')."</p>";
				
				$evsa1 = array(	'title'=>'Title','color'=>'Color');
				$cnt =1;
				if(is_array($sorting_options) ){
					foreach($evsa1 as $so=>$sov){
						if(in_array($so, $sorting_options) ){	
															
							echo "<p val='sort_".$so."' type='".$so."' class='evs_btn' >"
								.eventon_get_custom_language($this->evopt2, 'evcal_lang_s'.$so,$sov)
								."</p>";						
						}
						$cnt++;
					}
				}
			echo "</div><div class='clear'></div></div>";
		}
		
		
		// filtering section
		echo "
			<div class='eventon_filter_line'>";
			
			// event_type line
			if(in_array('event_type', $filtering_options) && $default_event_type=='all'){
				echo "
				<div class='eventon_filter' filter_field='event_type' filter_val='all' filter_type='tax'>
					<div class='eventon_sf_field'><p>".$evt_name.":</p></div>				
				
					<div class='eventon_filter_selection'>
						<p class='filtering_set_val' opts='evs4_in'>All</p>
						<div class='eventon_filter_dropdown' style='display:none'>";
					
						$cats = get_categories(array( 'taxonomy'=>'event_type'));
						echo "<p filter_val='all'>All</p>";
						foreach($cats as $ct){
							echo "<p filter_val='".$ct->term_id."' filter_slug='".$ct->slug."'>".$ct->name."</p>";
						}				
					echo "</div>
					</div><div class='clear'></div>
				</div>";
			}else if($default_event_type!='all'){
				echo "<div class='eventon_filter' filter_field='event_type' filter_val='{$default_event_type}' filter_type='tax'></div>";
			}
			
			// event_type_2 line
			if(in_array('event_type_2', $filtering_options) && $default_event_type_2=='all'){
				echo "
				<div class='eventon_filter' filter_field='event_type_2' filter_val='all' filter_type='tax'>
					<div class='eventon_sf_field'><p>".$evt_name2.":</p></div>				
				
					<div class='eventon_filter_selection'>
						<p class='filtering_set_val' opts='evs4_in'>All</p>
						<div class='eventon_filter_dropdown' style='display:none'>";
					
						$cats = get_categories(array( 'taxonomy'=>'event_type_2'));
						echo "<p filter_val='all'>All</p>";
						foreach($cats as $ct){
							echo "<p filter_val='".$ct->term_id."' filter_slug='".$ct->slug."'>".$ct->name."</p>";
						}				
					echo "</div>
					</div><div class='clear'></div>
				</div>";
			}else if($default_event_type_2!='all'){
				echo "<div class='eventon_filter' filter_field='event_type_2' filter_val='{$default_event_type_2}' filter_type='tax'></div>";
			}
			
			// (---) Hook for addon
			if(has_action('eventon_sorting_filters')){
				echo  do_action('eventon_sorting_filters', $content);
			}
				
			echo "</div>"; // #eventon_filter_line
		
		echo "</div>"; // #eventon_sorting_section
		
	
		
		// load bar for calendar
		echo "<div id='eventon_loadbar_section'><div id='eventon_loadbar'></div></div>";				
		
		
		return ob_get_clean();
	}
	
	
} // class EVO_generator


?>