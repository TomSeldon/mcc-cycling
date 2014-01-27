<?php
/**
 * inline dynamic styles for front end
 *
 * @version		1.1
 * @package		EventON/Styles
 * @author 		AJDE
 */


/*
* The Front-end Event Calendar Code DISPLAY
*/

function eventon_dynamic_inline_styles(){
	// Load variables
	$evcal_val1= get_option('evcal_options_evcal_1');
	
	// get icons for the event card
	for($x=1; $x<6; $x++){
		if(!empty($evcal_val1['evcal_icon_00'.$x]) )
			$icon_array[$x] = wp_get_attachment_image_src($evcal_val1['evcal_icon_00'.$x], 'full');
	}
	
	
	echo "<style type='text/css'>";
	
	// (---) Hook for addons
	if(has_action('eventon_inline_styles')){
		do_action('eventon_inline_styles');
	}
	
	echo get_option('evcal_styles');
	
	echo "
		.ajde_evcal_calendar .calendar_header p, .eventon_sort_line p, .eventon_filter_line p, .eventon_events_list .eventon_list_event .evcal_cblock, .eventon_events_list .eventon_list_event .evcal_desc span.evcal_desc2, .evcal_evdata_row .evcal_evdata_cell h2, .evcal_evdata_row .evcal_evdata_cell h3, .evcal_month_line p{
			font-family:".( (!empty($evcal_val1['evcal_font_fam']))? $evcal_val1['evcal_font_fam']:"oswald, 'arial narrow'" )."; 
		}
		
		/*-- arrow --*/		
		.evcal_evdata_row .evcal_evdata_icons.evcalicon_1{
			".( (!empty($icon_array[1]))? 'background:url('.$icon_array[1][0].') center center no-repeat':'background-position:0 0px' )."
		}.evcal_evdata_row .evcal_evdata_icons.evcalicon_2{
			".( (!empty($icon_array[2]))? 'background:url('.$icon_array[2][0].') center center no-repeat':'background-position:0 -31px' )."
		}.evcal_evdata_row .evcal_evdata_icons.evcalicon_3{
			".( (!empty($icon_array[3]))? 'background:url('.$icon_array[3][0].') center center no-repeat':'background-position:0 -125px' )."
		}.evcal_evdata_row .evcal_evdata_icons.evcalicon_4{
			".( (!empty($icon_array[4]))? 'background:url('.$icon_array[4][0].') center center no-repeat':'background-position:0 -64px' )."
		}.evcal_evdata_row .evcal_evdata_icons.evcalicon_5{
			".( (!empty($icon_array[5]))? 'background:url('.$icon_array[5][0].') center center no-repeat':'background-position:0 -96px' )."
		}.evcal_evdata_row .evcal_evdata_icons.evcalicon_6{
			".( (!empty($icon_array[6]))? 'background:url('.$icon_array[5][0].') center center no-repeat':'background-position:0 -190px' )."
		}.evcal_evdata_row .evcal_evdata_icons.evcalicon_7{
			".( (!empty($icon_array[7]))? 'background:url('.$icon_array[5][0].') center center no-repeat':'background-position:0 -225px' )."
		}
		
		
		
		#evcal_list .eventon_list_event .event_description .evcal_btn{
			color:#".( (!empty($evcal_val1['evcal_gen_btn_fc']))? $evcal_val1['evcal_gen_btn_fc']:'fff' ).";
			font:bold 18px Helvetica, arial;
			display:inline-block;
			padding:8px 12px!important; margin-top:4px;
			background-color:#".( (!empty($evcal_val1['evcal_gen_btn_bgc']))? $evcal_val1['evcal_gen_btn_bgc']:'237ebd' ).";
			-moz-border-radius: 3px; 
			-webkit-border-radius: 3px;
			border-radius: 3px;
		}
		#evcal_list .eventon_list_event .event_description .evcal_btn:hover{
			color:#".( (!empty($evcal_val1['evcal_gen_btn_fcx']))? $evcal_val1['evcal_gen_btn_fcx']:'fff' ).";
			background-color:#".( (!empty($evcal_val1['evcal_gen_btn_bgcx']))? $evcal_val1['evcal_gen_btn_bgcx']:'237ebd' ).";
		}
		
		/*-- font color match --*/
		.ajde_evcal_calendar #evcal_head.calendar_header #evcal_cur{
			color:#".( (!empty($evcal_val1['evcal_header1_fc']))? $evcal_val1['evcal_header1_fc']:'C6C6C6' ).";
		}
		#evcal_list .eventon_list_event .evcal_cblock{
			color:#".( (!empty($evcal_val1['evcal__fc2']))? $evcal_val1['evcal__fc2']:'ABABAB' ).";
		}
		#evcal_list .eventon_list_event .evcal_desc span.evcal_event_title{
			color:#".( (!empty($evcal_val1['evcal__fc3']))? $evcal_val1['evcal__fc3']:'6B6B6B' ).";
		}
		.evcal_evdata_row .evcal_evdata_cell h2, .evcal_evdata_row .evcal_evdata_cell h3{
			color:#".( (!empty($evcal_val1['evcal__fc4']))? $evcal_val1['evcal__fc4']:'6B6B6B' ).";
		}
		#evcal_list .eventon_list_event .evcal_eventcard p{
			color:#".( (!empty($evcal_val1['evcal__fc5']))? $evcal_val1['evcal__fc5']:'656565' ).";
		}
		.eventon_events_list .eventon_list_event .evcal_eventcard, .evcal_evdata_row{
			background-color:#".( (!empty($evcal_val1['evcal__bc1']))? $evcal_val1['evcal__bc1']:'EAEAEA' ).";
		}
					
		#eventon_loadbar{
			background-color:#".( (!empty($evcal_val1['evcal_header1_fc']))? $evcal_val1['evcal_header1_fc']:'6B6B6B' )."; height:2px; width:0%}
		
		.evcal_event_details .evcal_evdata_cell.shorter_desc{
			height:150px; overflow:hidden;
		}	
		
		
		</style>";
	
}