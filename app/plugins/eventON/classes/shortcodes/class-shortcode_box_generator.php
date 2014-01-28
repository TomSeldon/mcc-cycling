<?php
/**
 * EventON Admin Include
 *
 * Include for EventON related events in admin.
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin
 * @version     0.1
 */



class eventon_admin_shortcode_box{
	
	function __construct(){
	
	}
	
	public function shortcode_default_field($key){
		
		$SC_defaults = array(
		'cal_id'=>array(
			'name'=>'Calendar ID',
			'type'=>'text',
			'var'=>'cal_id',
			'guide'=>'Unique ID to differentiate this calendar from others',
			'default'=>'0',
			'placeholder'=>'eg. 1'
		),
		'number_of_months'=>array(
			'name'=>'Number of Months',
			'type'=>'text',
			'var'=>'number_of_months',
			'default'=>'0',
			'placeholder'=>'eg. 5'
		),		
		'show_et_ft_img'=>array(
			'name'=>'Show Featured Image',
			'type'=>'YN',
			'var'=>'show_et_ft_img',
			'default'=>'no'
		),
		'hide_past'=>array(
			'name'=>'Hide Past Events',
			'type'=>'YN',
			'var'=>'hide_past',
			'default'=>'no'
		),
		'ft_event_priority'=>array(
			'name'=>'Feature event priority',
			'type'=>'YN',
			'guide'=>'Move featured events above others',
			'var'=>'ft_event_priority',
			'default'=>'no',
		),
		'event_count'=>array(
			'name'=>'Event count limit',
			'placeholder'=>'eg. 3',
			'type'=>'text',
			'guide'=>'Limit number of events per month (integer) eg. 3',
			'var'=>'event_count',
			'default'=>'0'
		),
		'month_incre'=>array(
			'name'=>'Month Increment',
			'type'=>'text',
			'placeholder'=>'eg. +1',
			'guide'=>'Change starting month (eg. +1)',
			'var'=>'month_incre',
			'default'=>'0'
		),
		'event_type'=>array(
			'name'=>'Event Type',
			'type'=>'eventtype',
			'guide'=>'Event Type category IDs - seperate by commas (eg. 3,12)',
			'placeholder'=>'eg. 3, 12',
			'var'=>'event_type',
			'default'=>'0'
		),
		'event_type_2'=>array(
			'name'=>'Event Type 2',
			'type'=>'eventtype',
			'guide'=>'Event Type 2 category IDs - seperate by commas (eg. 3,12)',
			'placeholder'=>'eg. 3, 12',
			'var'=>'event_type_2',
			'default'=>'0'
		),
		'fixed_month'=>array(
			'name'=>'Fixed Month',
			'type'=>'text',
			'guide'=>'Set fixed month for calendar start (integer)',
			'var'=>'fixed_month',
			'default'=>'0',
			'placeholder'=>'eg. 10'
		),
		'fixed_year'=>array(
			'name'=>'Fixed Year',
			'type'=>'text',
			'guide'=>'Set fixed year for calendar start (integer)',
			'var'=>'fixed_year',
			'default'=>'0',
			'placeholder'=>'eg. 2013'
		),
		'event_order'=>array(
			'name'=>'Event Order',
			'type'=>'select',
			'guide'=>'Select ascending or descending order for event',
			'var'=>'event_order',
			'default'=>'ASC',
			'options'=>array('ASC','DESC')
		),
		'lang'=>array(
			'name'=>'Language Variation (<a href="'.get_admin_url().'admin.php?page=eventon&tab=evcal_2">Update Language Text</a>)',
			'type'=>'select',
			'guide'=>'Select which language variation text to use',
			'var'=>'lang',
			'default'=>'L1',
			'options'=>array('L1','L2','L3')
		)
		);
		
		return $SC_defaults[$key];
	
	}
	
	public function shortcode_interpret($var){
		global $eventon;
		ob_start();		
		
		// GUIDE popup
		$guide = (!empty($var['guide']))? $eventon->throw_guide($var['guide'], 'L',false):null;


		switch($var['type']){
			// custom type and its html pluggability
			case has_action("eventon_shortcode_box_interpret_{$var['type']}"):
				do_action("eventon_shortcode_box_interpret_{$var['type']}");
			
			case 'YN':
				echo 
				"<div class='fieldline evoYN_row'>
					<p class='label'><a class='evo_YN_btn ".( ($var['default']=='no')? 'NO':null )."' codevar='".$var['var']."'></a>
					<span >".$var['name'].
					"</span>".$guide."</p>							
				</div>";
			break;
			
			case 'text':
				echo 
				"<div class='fieldline'>
					<p class='label'><input class='evoPOSH_input' type='text' codevar='".$var['var']."' placeholder='".( (!empty($var['placeholder']))?$var['placeholder']:null) ."'/> ".$var['name']."".$guide."</p>
				</div>";
			break;
			
			case 'eventtype':
				
				$terms = get_terms($var['var']);	
				
				$view ='';
				if(!empty($terms) && count($terms)>0){
					foreach($terms as $term){
						$view.= '<em>'.$term->name .' ('.$term->term_id.')</em>';
					}
				}
				
				$view_html = (!empty($view))? '<span class="evoPOSH_tax">Possible Values <span >'. $view .'</span></span>': null;
				
				
				echo 
				"<div class='fieldline'>
					<p class='label'><input class='evoPOSH_input' type='text' codevar='".$var['var']."' placeholder='".( (!empty($var['placeholder']))?$var['placeholder']:null) ."'/> ".$var['name']." {$view_html}</p>
				</div>";
			break;
			
			case 'select':
				echo 
				"<div class='fieldline'>
					<p class='label'>
						<select class='evoPOSH_select' codevar='".$var['var']."'>";
						
						foreach($var['options'] as $val){
							echo "<option value='".$val."'>".$val."</option>";
						}		
						
						echo 
						"</select> ".$var['name']."".$guide."</p>
				</div>";
			break;
			
		}
		
		return ob_get_clean();
	}
	
	public function get_shortcode_field_array(){
		
		$shortcode_guide_array = apply_filters('eventon_shortcode_popup', array(
			array(
				'id'=>'s1',
				'name'=>'Main Calendar',
				'code'=>'add_eventon',
				'variables'=>apply_filters('eventon_basiccal_shortcodebox', array(
					$this->shortcode_default_field('show_et_ft_img')
					,$this->shortcode_default_field('hide_past')
					,$this->shortcode_default_field('ft_event_priority')
					,$this->shortcode_default_field('event_count')
					,$this->shortcode_default_field('month_incre')
					,$this->shortcode_default_field('event_type')
					,$this->shortcode_default_field('event_type_2')
					,$this->shortcode_default_field('fixed_month')
					,$this->shortcode_default_field('fixed_year')
					,$this->shortcode_default_field('cal_id')
					,$this->shortcode_default_field('event_order')
					,$this->shortcode_default_field('lang')
				))
			),
			array(
				'id'=>'s2',
				'name'=>'Events List',
				'code'=>'add_eventon_list',
				'variables'=>array(
					$this->shortcode_default_field('number_of_months')
					,array(
						'name'=>'Event count limit',
						'placeholder'=>'eg. 3',
						'type'=>'text',
						'guide'=>'Limit number of events per month (integer)',
						'var'=>'event_count',
						'default'=>'0'
					),$this->shortcode_default_field('month_incre')
					,$this->shortcode_default_field('fixed_month')
					,$this->shortcode_default_field('fixed_year')
					,$this->shortcode_default_field('cal_id')
					,$this->shortcode_default_field('event_order'),
					array(
						'name'=>'Hide multiple occurence',
						'type'=>'YN',
						'guide'=>'Hide events from showing more than once between months',
						'var'=>'hide_mult_occur',
						'default'=>'no',
					),array(
						'name'=>'Hide empty months',
						'type'=>'YN',
						'guide'=>'Hide months without any events on the events list',
						'var'=>'hide_empty_months',
						'default'=>'no',
					),array(
						'name'=>'Show year',
						'type'=>'YN',
						'guide'=>'Show year next to month name on the events list',
						'var'=>'show_year',
						'default'=>'no',
					),$this->shortcode_default_field('ft_event_priority')
				)
			)
		));
		
		return $shortcode_guide_array;
	}
	
	public function get_content(){
		
	$shortcode_guide_array = $this->get_shortcode_field_array();
	
	ob_start();
	?>
		
		<div id='evoPOSH_outter'>
			<h3 class='notifications '><em id='evoPOSH_back'></em><span bf='Select option below to customize shortcode variable values'>Select option below to customize shortcode variable values</span></h3>
			<div class='evoPOSH_inner'>
				<div class='step1 steps'>
				<?php					
					foreach($shortcode_guide_array as $options){
						$__step_2 = (empty($options['variables']))? ' nostep':null;
						
						echo "<div class='evoPOSH_btn{$__step_2}' step2='".$options['id']."' code='".$options['code']."'>".$options['name']."</div>";
					}	
				?>				
				</div>
				<div class='step2 steps' >
					<?php
						foreach($shortcode_guide_array as $options){
							
							if(!empty($options['variables'])) {
							
								echo "<div id='".$options['id']."' class='step2_in' style='display:none'>";
								
								foreach($options['variables'] as $var){									
									echo $this->shortcode_interpret($var);
								}									
								
								echo "</div>";
							}
						}
						
					?>
					
				</div>
				<div class='clear'></div>
			</div>
			<div class='evoPOSH_footer'>
				<p id='evoPOSH_var_'></p>
				<p id='evoPOSH_code' code='add_eventon' >[add_eventon]</p>
				<span class='evoPOSH_insert' title='Click to insert shortcode'></span>
			</div>
		</div>
	
	<?php
	return ob_get_clean();
	
	}

}

$GLOBALS['evo_shortcode_box'] = new eventon_admin_shortcode_box();


?>