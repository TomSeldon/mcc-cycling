<?php
/**
 * EventON Widget
 *
 * @author 		AJDE
 * @category 	Widget
 * @package 	EventON/Classes
 * @version     1.1
 */
class EvcalWidget extends WP_Widget{
	
	function EvcalWidget(){
		$widget_ops = array('classname' => 'EvcalWidget', 
			'description' => 'Display EventON Event Calendar on side bar' );
		$this->WP_Widget('EvcalWidget', 'Event Calendar Widget', $widget_ops);
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'ev_count' => '','ev_type' =>'', 'ev_title'=>'' ) );
		$ev_count = $instance['ev_count'];
		$ev_type = $instance['ev_type'];
		$ev_title = $instance['ev_title'];
		$ev_upcomingevents = (!empty($instance['ev_upcomingevents']) )?$instance['ev_upcomingevents']:null;
		$ev_month_count = (!empty($instance['ev_month_count']))? $instance['ev_month_count'] : null;
		?>
		<div id='eventon_widget_settings'>
			<div class='eventon_widget_top'><p></p></div>
			
			<p><label for="<?php echo $this->get_field_id('ev_title'); ?>">Title:</label><br/> 
			<input class="widefat" id="<?php echo $this->get_field_id('ev_title'); ?>" 
			name="<?php echo $this->get_field_name('ev_title'); ?>" type="text" 
			value="<?php echo attribute_escape($ev_title); ?>" /></p>
				
			<p><label for="<?php echo $this->get_field_id('ev_count'); ?>">Event Count:</label><br/> 
			<input class="widefat" id="<?php echo $this->get_field_id('ev_count'); ?>" 
			name="<?php echo $this->get_field_name('ev_count'); ?>" type="text" 
			value="<?php echo attribute_escape($ev_count); ?>" /><br/><em style='padding-top:4px; display:block;'>(If left blank - will display all events for that month.)</em></p>  
			  
			<p class='divider'></p>
			<p><label for="<?php echo $this->get_field_id('ev_upcomingevents'); ?>"><b>Upcoming Events</b></label><br/> 
			<?php $checked = (attribute_escape($ev_count) =='yes')?'checked="checked"':null; ?>
			<input class="checkbox" 
			id="<?php echo $this->get_field_id('ev_upcomingevents'); ?>" 
			name="<?php echo $this->get_field_name('ev_upcomingevents'); ?>" 
			type="checkbox" 
			<?php checked( $ev_upcomingevents, 'on' ); ?>/>
			<label for='<?php echo $this->get_field_id('ev_upcomingevents'); ?>'>Show upcoming events</label></p>
			  
			  
			<p><label for="<?php echo $this->get_field_id('ev_month_count'); ?>">Number of months:</label><br/>		  
			<input class="widefat" 
			id="<?php echo $this->get_field_id('ev_month_count'); ?>" 
			name="<?php echo $this->get_field_name('ev_month_count'); ?>" 
			type="text" 
			value="<?php echo attribute_escape($ev_month_count); ?>" /><br/>
			<em style='padding-top:4px; display:block;'>(Use this field to set the number of upcoming months to show)</em></p> 
				
			 
			<p class='divider'></p>
			  
			<p><label for="<?php echo $this->get_field_id('ev_type'); ?>">Event Type(s):</label><br/> 
			<input class="widefat" id="<?php echo $this->get_field_id('ev_type'); ?>" 
			name="<?php echo $this->get_field_name('ev_type'); ?>" type="text" 
			value="<?php echo attribute_escape($ev_type); ?>" /><br/>
			<em style='padding-top:4px; display:block;'>(Leave blank for all event types, else type <a href='edit-tags.php?taxonomy=event_type&post_type=ajde_events'>event type ID</a> separated by commas)</em></p>
		</div>
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['ev_title'] = strip_tags($new_instance['ev_title']);
		$instance['ev_count'] = strip_tags($new_instance['ev_count']);
		$instance['ev_type'] = strip_tags($new_instance['ev_type']);
		$instance['ev_upcomingevents'] = strip_tags($new_instance['ev_upcomingevents']);
		$instance['ev_month_count'] = strip_tags($new_instance['ev_month_count']);
		return $instance;
	}
	
	/**
	 * The actuval widget
	 */
	public function widget($args, $instance) {
		global $eventon;
		
		$evcal_val1= get_option('evcal_options_evcal_1');
		$evcal_val2= get_option('evcal_options_evcal_2');
		
		extract($args, EXTR_SKIP);		
		
		$event_count = empty($instance['ev_count']) ? '0' : $instance['ev_count'];
		$event_type = empty($instance['ev_type']) ? 'all' : $instance['ev_type'];
		$ev_month_count = empty($instance['ev_month_count']) ? 'all' : $instance['ev_month_count'];
		$event_type_2 ='all';
		
		// Upcoming months
		if(!empty($instance['ev_upcomingevents']) && $instance['ev_upcomingevents']=='on' 
			&& !empty($instance['ev_month_count'])){
			$upcoming_ar = array(
				'show_upcoming'=> 1,
				'number_of_months'=>$instance['ev_month_count']
			);
			$show_upcoming=1;
		}else{$upcoming_ar=array();
			$show_upcoming=0;
		}
		
		$args = array(
			'cal_id'=>'eventon_widget',
			'event_count'=>$event_count,
			'show_upcoming'=>$show_upcoming,
			'number_of_months'=>$ev_month_count,
			'event_type'=> $event_type,
			'event_type_2'=> 'all',
		);
		
		echo $before_widget;
		
		// widget title
		if(!empty($instance['ev_title']) ){
			echo "<h3 class='widget-title'>".$instance['ev_title']."</h3>";
		}
		
		
		$content =$eventon->evo_generator->eventon_generate_calendar($args);
		echo "<div id='evcal_widget'>".$content."</div>";
		
		
		echo $after_widget;
		
	}
}