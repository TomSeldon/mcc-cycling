<?php
/**
 * Meta boxes for ajde_events
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin/ajde_events
 * @version     1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Init the meta boxes.
 */
function eventon_meta_boxes(){
	// ajde_events meta boxes
	add_meta_box('ajdeevcal_mb2','Event Color', 'ajde_evcal_show_box_2','ajde_events', 'normal', 'high');
	add_meta_box('ajdeevcal_mb1','Event Settings', 'ajde_evcal_show_box','ajde_events', 'normal', 'high');	
	
	do_action('eventon_add_meta_boxes');
}
add_action( 'add_meta_boxes', 'eventon_meta_boxes' );
add_action( 'save_post', 'eventon_save_meta_data', 1, 2 );
	
	
/**
 * Event Color Meta box.
 */	
function ajde_evcal_show_box_2(){
		// DATA ARRAY
		$color_array=array(
			1=>'#5484ED','#46D6DB','#51B749','#fbc406','#ff690f','#ed0f16','#b6b6b6'
		);		
		do_action_ref_array('eventon_metab2_init', array(&$color_array));
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename_2' );
		$p_id = get_the_ID();
		$ev_vals = get_post_custom($p_id);
		
			
?>		
		<table id="meta_tb2" class="form-table meta_tb" >
		<tr>
			<td>
			<div id='evcal_colors'>
				<?php foreach($color_array as $cf=>$color){
					$selected_color = (!empty($ev_vals["evcal_event_color"]) && $ev_vals["evcal_event_color"][0] == $color)? ' selected':null;
					echo "<div class='evcal_color_box".$selected_color."' style='background-color:".$color."' 
					color_n='".$cf."' color='".$color."'></div>";
				}?>				
			</div>
			<div class='clear'></div>
			<p><i>Note: If an event color is not selected, the event color will be set to default color in <a href='<?php bloginfo('url')?>/wp-admin/admin.php?page=eventon'>Settings</a></i></p>
			<input id='evcal_event_color' type='hidden' name='evcal_event_color' 
				value='<?php echo (!empty($ev_vals["evcal_event_color"]) )? $ev_vals["evcal_event_color"][0]: null; ?>'/>
			<input id='evcal_event_color_n' type='hidden' name='evcal_event_color_n' 
				value='<?php echo (!empty($ev_vals["evcal_event_color_n"]) )? $ev_vals["evcal_event_color_n"][0]: null ?>'/>
			</td>
		</tr>
		<?php do_action('eventon_metab2_end'); ?>
		</table>
<?php }
	
/**
 * Main meta box.
 */
	function ajde_evcal_show_box(){
		global $eventon;
		
		// repeat frequency array
		$repeat_freq= array('weekly'=>'weeks','monthly'=>'months');
		
		do_action_ref_array('eventon_metab1_init', array(&$repeat_freq) );
		
		$evcal_opt1= get_option('evcal_options_evcal_1');
		$evcal_opt2= get_option('evcal_options_evcal_2');
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		
		// The actual fields for data entry
		$p_id = get_the_ID();
		$ev_vals = get_post_custom($p_id);
		
		$evcal_allday = (!empty($ev_vals["evcal_allday"]))? $ev_vals["evcal_allday"][0]:null;
		
			$show_style_code = ($evcal_allday=='yes') ? "style='display:none'":null;
			$start_date_text = ($evcal_allday=='yes') ? "Event Date":"Event Start Date";
			$evcal_end_date_value = ($evcal_allday=='yes') ? "":$ev_vals["evcal_end_date"][0];

		$select_a_arr= array('AM','PM');
		
		
	?>
		
		<table id="meta_tb" class="form-table meta_tb" >
		<?php
			// (---) hook for addons
			if(has_action('eventon_post_settings_metabox_table'))
				do_action('eventon_post_settings_metabox_table');
		?>
		<tr>
			<td><label for=''><?php _e('All Day Event'); ?></label></td>
			<td>
				<a id='evcal_allday_yn_btn' allday_switch='1' class='evcal_yn_btn <?php echo ($evcal_allday=='yes')?null:'btn_at_no'?>'></a>
				<input type='hidden' name='evcal_allday' value="<?php echo ($evcal_allday=='yes')?'yes':'no';?>"/>
			</td>
		</tr>
		<?php
			// (---) hook for addons
			if(has_action('eventon_post_time_settings'))
				do_action('eventon_post_time_settings');
		?>
		<tr><td colspan='2'>
			<?php
				/** get date formate and convert to JQ datepicker format**/
				$wp_date_format = get_option('date_format');
				$format_str = str_split($wp_date_format);
				
				foreach($format_str as $str){
					switch($str){							
						case 'j': $nstr = 'd'; break;
						case 'd': $nstr = 'dd'; break;	
						case 'D': $nstr = 'D'; break;	
						case 'l': $nstr = 'DD'; break;	
						case 'm': $nstr = 'mm'; break;
						case 'M': $nstr = 'M'; break;
						case 'n': $nstr = 'm'; break;
						case 'F': $nstr = 'MM'; break;							
						case 'Y': $nstr = 'yy'; break;
						case 'y': $nstr = 'y'; break;
												
						default :  $nstr = ''; break;							
					}
					$jq_date_format[] = (!empty($nstr))?$nstr:$str;
					
				}
				$jq_date_format = implode('',$jq_date_format);
			?>
			<div id='evcal_dates' date_format='<?php echo $jq_date_format;?>'>
				<?php
					// START TIME					
				?>
				<div class='evcal_data_col1'>
					<p id='evcal_start_date_label'><?php _e($start_date_text)?></p>
					<input class='evcal_data_picker datapicker_on' type='text' id='evcal_start_date' name='evcal_start_date' value='<?php echo $ev_vals["evcal_start_date"][0]?>'/>
					<span>Select a Date</span>
					<div class='evcal_date_time switch_for_evsdate' <?php echo $show_style_code?>>
						<div class='evcal_select'>
							<select id='evcal_start_time_hour' class='evcal_date_select' name='evcal_start_time_hour'>
								<?php
									//echo "<option value=''>--</option>";
									$start_time_h = (!empty($ev_vals['evcal_start_time_hour']) )?$ev_vals['evcal_start_time_hour'][0]:null;						
								for($x=1; $x<13;$x++){									
									echo "<option value='$x'".(($start_time_h==$x)?'selected="selected"':'').">$x</option>";
								}?>
							</select>
						</div><p style='display:inline; font-size:24px;padding:4px'>:</p>
						<div class='evcal_select'>
							<select id='evcal_start_time_min' class='evcal_date_select' name='evcal_start_time_min'>
								<?php	
									//echo "<option value=''>--</option>";
									$start_time_m = (!empty($ev_vals['evcal_start_time_min']) )?	$ev_vals['evcal_start_time_min'][0]: null;
								for($x=0; $x<12;$x++){
									$min = ($x<2)?('0'.$x*5):$x*5;
									echo "<option value='$min'".(($start_time_m==$min)?'selected="selected"':'').">$min</option>";
								}?>
							</select>
						</div>
						<div class='evcal_select evcal_ampm_sel'>
							<select name='evcal_st_ampm' id='evcal_st_ampm' >
								<?php
									$evcal_st_ampm = $ev_vals['evcal_st_ampm'][0];
									foreach($select_a_arr as $sar){
										echo "<option value='".$sar."' ".(($evcal_st_ampm==$sar)?'selected="selected"':'').">".$sar."</option>";
									}
								?>								
							</select>
						</div>						
						<br/>
						<span><?php _e('Select a Time')?></span>
					</div>
				</div>
				<?php
					// END TIME
				?>
				<div class='evcal_data_col1 switch_for_evsdate' <?php echo $show_style_code?>>
					<p><?php _e('Event End Date')?></p>
					<input class='evcal_data_picker datapicker_on' type='text' id='evcal_end_date' name='evcal_end_date' value='<?php echo $evcal_end_date_value?>'/>
					<span>Select a Date</span>
					<div class='evcal_date_time'>
						<div class='evcal_select'>
							<select class='evcal_date_select' name='evcal_end_time_hour'>
								<?php	
									//echo "<option value=''>--</option>";
									$end_time_h = (!empty($ev_vals['evcal_end_time_hour']) )?
										$ev_vals['evcal_end_time_hour'][0]:null;
									for($x=1; $x<13;$x++){
										echo "<option value='$x'".(($end_time_h==$x)?'selected="selected"':'').">$x</option>";
									}
								?>
							</select>
						</div><p style='display:inline; font-size:24px;padding:4px'>:</p>
						<div class='evcal_select'>
							<select class='evcal_date_select' name='evcal_end_time_min'>
								<?php	
									//echo "<option value=''>--</option>";
									$end_time_m = (!empty($ev_vals['evcal_end_time_min']) )? 
										$ev_vals['evcal_end_time_min'][0]:null;
									for($x=0; $x<12;$x++){
										$min = ($x<2)?('0'.$x*5):$x*5;
										echo "<option value='$min'".(($end_time_m==$min)?'selected="selected"':'').">$min</option>";
									}
								?>
							</select>
						</div>
						<div class='evcal_select evcal_ampm_sel'>
							<select name='evcal_et_ampm'>
								<?php
									$evcal_et_ampm = $ev_vals['evcal_et_ampm'][0];
									
									foreach($select_a_arr as $sar){
										echo "<option value='".$sar."' ".(($evcal_et_ampm==$sar)?'selected="selected"':'').">".$sar."</option>";
									}
								?>								
							</select>
						</div>						
						<br/>
						<span>Select the Time</span>
					</div>
				</div>
				<div style='clear:both'></div>
				
				<?php // Recurring events 
					$evcal_repeat = (!empty($ev_vals["evcal_repeat"]) )? $ev_vals["evcal_repeat"][0]:null;
				?>
				<div id='evcal_rep' class='evd'>
					<div class='evcalr_1'>
						<p class='yesno_leg_line '>
							<a id='evd_repeat' class='evcal_yn_btn <?php echo ( $evcal_repeat=='yes')?null:'btn_at_no'?>'></a>
							<input type='hidden' name='evcal_repeat' value="<?php echo ($evcal_repeat=='yes')?'yes':'no';?>"/>
							<label for='evcal_repeat'><?php _e('Repeating event')?></label>
						</p>
						<p style='clear:both'></p>
					</div>
					<p class='eventon_ev_post_set_line'></p>
					<?php
						$display = (!empty($ev_vals["evcal_repeat"]) && $evcal_repeat=='yes')? '':'none';
					?>
					<div class='evcalr_2' style='display:<?php echo $display ?>'>
						<p class='evcalr_2_freq evcalr_2_p'><em></em>Frequency: <select id='evcal_rep_freq' name='evcal_rep_freq'>
						<?php
							$evcal_rep_freq = (!empty($ev_vals['evcal_rep_freq']))?$ev_vals['evcal_rep_freq'][0]:null;
							foreach($repeat_freq as $refv=>$ref){
								echo "<option field='".$ref."' value='".$refv."' ".(($evcal_rep_freq==$refv)?'selected="selected"':'').">".$refv."</option>";
							}
							
						?></select></p>
						<p class='evcalr_2_rep evcalr_2_p'><em></em>Repeat Every: <select name='evcal_rep_gap'>
						<?php	
							$evcal_rep_gap = (!empty($ev_vals['evcal_rep_gap']) )? 
									$ev_vals['evcal_rep_gap'][0]:null;
							
							for($x=1; $x<11;$x++){
								echo "<option value='$x'".(($evcal_rep_gap==$x)?'selected="selected"':'').">$x</option>";
							}
							
							$freq = (!empty($ev_vals["evcal_rep_freq"]) )?
								 ($repeat_freq[ $ev_vals["evcal_rep_freq"][0] ])
								: null;
						?></select> <span id='evcal_re'><?php echo $freq;?></span></p>
						<p class='evcalr_2_numr evcalr_2_p'><em></em>Number of repeats: <select name='evcal_rep_num'>
						<?php	
							$evcal_rep_num = (!empty($ev_vals['evcal_rep_num']) )? 
									$ev_vals['evcal_rep_num'][0]:null;
							
							for($x=1; $x<11;$x++){
								echo "<option value='$x'".(($evcal_rep_num==$x)?'selected="selected"':'').">$x</option>";
							}
						?></select></p>
					</div>
				</div>	
				
			</div>
		</td></tr>
		<tr>
		<?php
			// LOCATION
		?>
			<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon evcal_edb_map'></p>
					<div class='evcal_db_data'><label for='evcal_location'><?php _e('Event Location')?> <i>(eg. 123 Main St. Mainland, AB 12345)</i></label><br/>			
						<input type='text' id='evcal_location' name='evcal_location' value='<?php echo (!empty($ev_vals["evcal_location"]) )? $ev_vals["evcal_location"][0]:null?>' style='width:100%'/><br/>
						<p class='yesno_leg_line'>
							<a class='evcal_yn_btn <?php echo ($ev_vals["evcal_gmap_gen"][0]=='yes')?null:'btn_at_no'?>'></a>
							<input type='hidden' name='evcal_gmap_gen' value="<?php echo ($ev_vals["evcal_gmap_gen"][0]=='yes')?'yes':'no';?>"/>
							<label for='evcal_gmap_gen'><?php _e('Generate Google Map from the Event location address')?></label>
						</p>
						<p style='clear:both'></p>
					</div>
				</div>
			</td>
		</tr>
		<tr class='divide'><td colspan='2'><p class='div_bar'></p></td></tr>
		<?php
			// ORGANIZER
		?>
		<tr>
			<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon evcal_edb_organizer'></p>
					<div class='evcal_db_data'><label for='evcal_organizer'><?php _e('Event Organizer')?></label><br/>			
						<input type='text' id='evcal_organizer' name='evcal_organizer' value='<?php echo (!empty($ev_vals["evcal_organizer"]) )? $ev_vals["evcal_organizer"][0]:null?>' style='width:100%'/><br/>						
					</div>
				</div>
			</td>			
		</tr>
		<?php 
		// Event brite
		if($evcal_opt1['evcal_evb_events']=='yes'
			&& !empty($evcal_opt1['evcal_evb_api']) ):?>
			<tr class='divide'><td colspan='2'><p class='div_bar'></p></td></tr>
			<tr>
				<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon'><img src='<?php echo AJDE_EVCAL_URL ?>/assets/images/backend_post/eventbrite_icon.png'/></p>
					
					<p class='evcal_db_data'>
					<?php
						if(!empty($ev_vals["evcal_evb_id"]) ){
							echo "<span id='evcal_eb5'>Currently Connected to <b id='evcal_eb2'>".$ev_vals["evcal_evb_id"][0]."</b><br/></span>";
							$html_eb2 = "  <input type='button' class='button' id='evcal_eventb_btn_dis' value='Disconnect this'/>";
						}else{
							$html_eb2='';
						}
						$html_eb1 = 'Connect to Eventbrite Event';
					?>	
					<input type='button' class='button' id='evcal_eventb_btn' value='<?php echo $html_eb1?>'/><?php echo $html_eb2?></p>
					
					<input type='hidden' name='evcal_evb_id' id='evcal_eventb_ev_d2' value='<?php echo (!empty($ev_vals["evcal_evb_id"]))? $ev_vals["evcal_evb_id"][0]: null; ?>'/>
					<input type='hidden' name='evcal_eventb_data_set' id='evcal_eventb_ev_d1' value='<?php echo (!empty($ev_vals["evcal_eventb_data_set"]))? $ev_vals["evcal_eventb_data_set"][0]: null; ?>'/>
				</div>	
				</td>
			</tr>
			<?php
				// URL
				$display = (!empty($ev_vals["evcal_eventb_url"]) )? '':'none';
			?>
			<tr class='divide evcal_eb_url evcal_eb_r' style='display:<?php echo $display ?>'>
				<td colspan='2'><p class='div_bar div_bar_sm'></p></td></tr>
			<tr class='evcal_eb_url evcal_eb_r' style='display:<?php echo $display?>'>
				<td colspan='2'>
					<p style='margin-bottom:2px'>Eventbrite Buy Ticket URL</p>
					<input style='width:100%' id='evcal_ebv_url' type='text' name='evcal_eventb_url' value='<?php echo (!empty($ev_vals["evcal_eventb_url"]))? $ev_vals["evcal_eventb_url"][0]: null; ?>' />
				</td>
			</tr>
			<?php
				// CAPACITY
				$display = (!empty($ev_vals["evcal_eventb_capacity"]) )? '':'none';
			?>
			<tr class='divide evcal_eb_capacity evcal_eb_r' style='display:<?php echo $display ?>'>
				<td colspan='2'><p class='div_bar div_bar_sm'></p></td></tr>
			<tr class='evcal_eb_capacity evcal_eb_r' style='display:<?php echo $display?>'>
				<td colspan='2'>
					<?php $evcal_eventb_capacity = (!empty($ev_vals["evcal_eventb_capacity"]))? $ev_vals["evcal_eventb_capacity"][0]: null; ?>
					<p style='margin-bottom:2px'>Eventbrite Event Capacity: <b id='evcal_eb3'><?php echo $evcal_eventb_capacity?></b></p>
					<input id='evcal_ebv_capacity' type='hidden' name='evcal_eventb_capacity' value='<?php echo $evcal_eventb_capacity?>' />
				</td>
			</tr>
			<?php
				// TICKET PRICE
				$display = (!empty($ev_vals["evcal_eventb_tprice"]) )? '':'none';
			?>
			<tr class='divide evcal_eb_price evcal_eb_r' style='display:<?php echo $display ?>'>
				<td colspan='2'><p class='div_bar div_bar_sm'></p></td></tr>
			<tr class='evcal_eb_price evcal_eb_r' style='display:<?php echo $display?>'>
				<td colspan='2'>
					<?php $evcal_eventb_tprice = (!empty($ev_vals["evcal_eventb_tprice"]))? $ev_vals["evcal_eventb_tprice"][0]: null; ?>
					<p style='margin-bottom:2px'>Eventbrite Ticket Price: <b id='evcal_eb4'><?php echo $evcal_eventb_tprice?></b></p>
					<input id='evcal_ebv_price' type='hidden' name='evcal_eventb_tprice' value='<?php echo $evcal_eventb_tprice?>' />
				</td>
			</tr>
			
			<tr id='evcal_eventb_data' style='display:none'><td colspan='2'>
				<div class='evcal_row_dark' >
					<p id='evcal_eventb_msg' class='event_api_msg' style='display:none'>Message</p>
					<div class='col50'>
						<p><input type='text' id='evcal_eventb_ev_id' value='' style='width:100%'/></p>
						<p class='legend'>Enter Eventbrite Event ID</p>
					</div>
					<div class='col50'>
						<div class='padl20'>
							<p><input id='evcal_eventb_btn_2' style='margin-left:10px'type='button' class='button' value='Get Event Data from Eventbrite'/></p>
						</div>
					</div>			
					
					<p class='clear'></p>					
					<p class='divider'></p>					
					<div id='evcal_eventb_s1' style='display:none'>
						<h5 class='mu_ev_id'>Retrived Event Data for: <b id='evcal_eb1'>321786</b></h5>
						<p class='legend'>Click on each eventbrite event data section to connect to this event.</p>
						
						<div id='evcal_eventb_data_tb'></div>
					</div>
				</div>
			</td></tr>
		
		
		<?php endif;?>
		
		<?php 
			// MEETUP
			
			if($evcal_opt1['evcal_api_meetup']=='yes' 
				&& !empty($evcal_opt1['evcal_api_mu_key']) ):
		?>
			<tr class='divide'><td colspan='2'><p class='div_bar'></p></td></tr>
			<tr>
				<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon'><img src='<?php echo AJDE_EVCAL_URL ?>/assets/images/backend_post/meetup_icon.png'/></p>
					
					<p class='evcal_db_data'>
					<?php
						if(!empty($ev_vals["evcal_meetup_ev_id"]) ){
							echo "<span id='evcal_mu2'>Currently Connected to <b id='evcal_002'>".$ev_vals["evcal_meetup_ev_id"][0]."</b><br/></span>";
							$html_mu2 = "  <input type='button' class='button' id='evcal_meetup_btn_dis' value='Disconnect this'/>";
						}else{
							$html_mu2 ='';
						}
						$html_mu1 = 'Connect to Meetup Event';
					?>	
					<input type='button' class='button' id='evcal_meetup_btn' value='<?php echo $html_mu1?>'/><?php echo $html_mu2?></p>
					
					<input type='hidden' name='evcal_meetup_data_set' id='evcal_meetup_ev_d1' value='<?php echo (!empty($ev_vals["evcal_meetup_data_set"]))? $ev_vals["evcal_meetup_data_set"][0]: null; ?>'/>
					<input type='hidden' name='evcal_meetup_ev_id' id='evcal_meetup_ev_d2' value='<?php echo (!empty($ev_vals["evcal_meetup_ev_id"]))? $ev_vals["evcal_meetup_ev_id"][0]: null; ?>'/>
				</div>	
				</td>
			</tr>
			
			
			<tr id='evcal_meetup_data' style='display:none'><td colspan='2'>
				<div class='evcal_row_dark' >
					<p id='evcal_meetup_msg' class='event_api_msg' style='display:none'>Message</p>
					<div class='col50'>
						<p><input type='text' id='evcal_meetup_ev_id' value='' style='width:100%'/></p>
						<p class='legend'>Enter Meetup Event ID</p>
					</div>
					<div class='col50'>
						<div class='padl20'>
							<p><input id='evcal_meetup_btn_2' style='margin-left:10px'type='button' class='button' value='Get Event Data from Meetup'/></p>
						</div>
					</div>			
					
					<p class='clear'></p>					
					<p class='divider'></p>					
					<div id='evcal_meetup_s1' style='display:none'>
						<h5 class='mu_ev_id'>Retrived Event Data for: <b id='evcal_001'>321786</b></h5>
						<p class='legend'>Click on each meetup event data section to populate this event with meetup event information.</p>						
						<div id='evcal_meetup_data_tb'></div>
					</div>
				</div>
			</td></tr>
		<?php endif; ?>
		
		<?php
			// PAYPAL
			if($evcal_opt1['evcal_paypal_pay']=='yes'):
		?>
		<tr class='divide'><td colspan='2'><p class='div_bar'></p></td></tr>
		<tr>
			<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon evcal_edb_paypal'></p>
					<p class='evcal_db_data'><label for='evcal_paypal_link'><?php _e('Paypal Link to purchase event tickets.')?></label><br/>			
						<input type='text' id='evcal_paypal_link' name='evcal_paypal_link' value='<?php echo (!empty($ev_vals["evcal_paypal_link"]) )? $ev_vals["evcal_paypal_link"][0]:null?>' style='width:100%'/>
					</p>
				</div>				
			</td>			
		</tr>
		<?php endif;?>
		<?php
			// Learn More Link 
		?>
		<tr class='divide'><td colspan='2'><p class='div_bar'></p></td></tr>
		<tr>
			<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon evcal_edb_exlink'></p>
					<div class='evcal_db_data'>
						<label for='evcal_lmlink'><?php _e('Learn More About Event Link','eventon')?> <i>(This will create a learn more link in the event card)</i></label><br/>		
						<input type='text' id='evcal_lmlink' name='evcal_lmlink' value='<?php echo (!empty($ev_vals["evcal_lmlink"]) )? $ev_vals["evcal_lmlink"][0]:null?>' style='width:100%'/><br/>
						<input type='checkbox' name='evcal_lmlink_target' value='yes' <?php echo (!empty($ev_vals["evcal_lmlink_target"]) && $ev_vals["evcal_lmlink_target"][0]=='yes')? 'checked="checked"':null?>/> <?php _e('Open in New window','eventon'); ?>
					</div>
				</div>
			</td>			
		</tr>
		<?php
			// External Link
		?>
		<tr class='divide'><td colspan='2'><p class='div_bar'></p></td></tr>
		<tr id='eventon_event_settings_externallink_metaboxrow'>
			<td colspan='2'>
				<div class='evcal_data_block_style1'>
					<p class='edb_icon evcal_edb_exlink'></p>
					<div class='evcal_db_data'>
						<label for='evcal_exlink'><?php _e('Event Link')?> <i>(Use this to link event to other urls instead of opening event card)</i></label><br/>		
						<input type='text' id='evcal_exlink' name='evcal_exlink' value='<?php echo (!empty($ev_vals["evcal_exlink"]) )? $ev_vals["evcal_exlink"][0]:null?>' style='width:100%'/><br/><input type='checkbox' name='evcal_exlink_target' value='yes' <?php echo (!empty($ev_vals["evcal_exlink_target"]) && $ev_vals["evcal_exlink_target"][0]=='yes')? 'checked="checked"':null?>/> <?php _e('Open in New window','eventon'); ?>						
					</div>
				</div>
			</td>			
		</tr>
		
		<?php 
			// (---) hook for addons
			if(has_action('eventon_metab1_end'))
				do_action('eventon_metab1_end');
		?>
	</table>
	

<?php }
	
	
// ------------------------------------------
// Save events custom meta box values
function eventon_save_meta_data($post_id, $post){
	if($post->post_type!='ajde_events')
		return;
		
	// Stop WP from clearing custom fields on autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	// Prevent quick edit from clearing custom fields
	if (defined('DOING_AJAX') && DOING_AJAX)
		return;

	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if( isset($_POST['myplugin_noncename']) ){
		if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) ){
			return;
		}
	}
	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ) )
		return;		
	//save the post meta values
	$fields_ar = array(
		'evcal_allday','evcal_event_color','evcal_event_color_n',
		'evcal_location','evcal_organizer','evcal_exlink','evcal_lmlink',
		'evcal_gmap_gen','evcal_mu_id','evcal_paypal_link',
		'evcal_eventb_data_set','evcal_evb_id','evcal_eventb_url','evcal_eventb_capacity','evcal_eventb_tprice',
		'evcal_meetup_data_set','evcal_meetup_url','evcal_meetup_ev_id',
		'evcal_repeat','evcal_rep_freq','evcal_rep_gap','evcal_rep_num',
		'evcal_lmlink_target','evcal_exlink_target',
		
		'evcal_start_date','evcal_end_date', 'evcal_start_time_hour','evcal_start_time_min','evcal_st_ampm',
		'evcal_end_time_hour','evcal_end_time_min','evcal_et_ampm',
	);
	
	// field names that pertains only to event date information
	$fields_sub_ar = array('evcal_start_date','evcal_end_date', 'evcal_start_time_hour','evcal_start_time_min','evcal_st_ampm',
		'evcal_end_time_hour','evcal_end_time_min','evcal_et_ampm');
	
	
	
	$date_POST_values='';
	// run through all the custom meta fields
	foreach($fields_ar as $f_val){
		global $AJDE_ev_cal;
		if(!empty ($_POST[$f_val])){						
			update_post_meta( $post_id, $f_val, $_POST[$f_val]);
			
			if(in_array($f_val, $fields_sub_ar)){
				$date_POST_values[$f_val]=$_POST[$f_val];
			}
		}else{
			if(defined('DOING_AUTOSAVE') && !DOING_AUTOSAVE){
				// if the meta value is set to empty, then delete that meta value
				delete_post_meta($post_id, $f_val);
			}
			delete_post_meta($post_id, $f_val);
		}
		
	}
	
	// DELETE unused meta values in older versions
	delete_post_meta($post_id, 'evcal_start_month_s');
	delete_post_meta($post_id, 'evcal_start_month_n');
	delete_post_meta($post_id, 'evcal_start_day_num');	
	
	
	// convert the post times into proper unix time stamps
	$proper_time = eventon_get_unix_time($date_POST_values);
	
	
	// full time converted to unix time stamp
	if ( !empty($proper_time['unix_start']) )
		update_post_meta( $post_id, 'evcal_srow', $proper_time['unix_start']);
	
	if ( !empty($proper_time['unix_end']) )
		update_post_meta( $post_id, 'evcal_erow', $proper_time['unix_end']);
	
	//set event color code to 1 for none select colors
	if ( !isset( $_POST['evcal_event_color_n'] ) )
		update_post_meta( $post_id, 'evcal_event_color_n',1);
		
	
	// (---) hook for addons
	do_action('eventon_save_meta', $fields_ar, $post_id);	
		
}
	
	
	

?>