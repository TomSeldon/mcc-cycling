<?php
	// ======================================================================
	// AJDE BACKENDER
	$cutomization_pg_array = array(
		array(
			'id'=>'evcal_001',
			'name'=>'General Calendar Settings',
			'display'=>'show',
			'tab_name'=>'General Settings',
			'top'=>'4',
			'fields'=>array(
				array('id'=>'evcal_cal_hide','type'=>'yesno','name'=>'Hide Calendar from front-end',),
				array('id'=>'evcal_arrow_hide','type'=>'yesno','name'=>'Hide Front-end arrow navigation',),
				array('id'=>'evcal_cal_hide_past','type'=>'yesno','name'=>'Hide past events in the calendar','afterstatement'=>'evcal_cal_hide_past'),	
										
				array('id'=>'evcal_cal_hide_past','type'=>'begin_afterstatement'),
				array('id'=>'evcal_past_ev','type'=>'radio','name'=>'Select a precise timing for the cut off time for past events','width'=>'full',
					'options'=>array(
						'local_time'=>'Hide events past current local time',
						'today_date'=>'Hide events past today\'s date')
				),
				array('id'=>'evcal_cal_hide_past','type'=>'end_afterstatement'),
				array('id'=>'evcal_hide_sort','type'=>'yesno','name'=>'Hide sort bar on calendar'),
				array('id'=>'evcal_hide_empty_um','type'=>'yesno','name'=>'Hide Empty months in upcoming months list'),
				
								
				array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Event Type Taxonomies'),
				array('id'=>'evcal_fcx','type'=>'note','name'=>'Use this to assign custom names for the event type taxonomies which you can use to categorize events. Note: Once you update these custom taxonomies refresh the page for the values to show up.'),
				array('id'=>'evcal_eventt','type'=>'text','name'=>'Custom name for Event Type #1',),
				array('id'=>'evcal_eventt2','type'=>'text','name'=>'Custom name for Event Type #2',),
				
				
		)),
		array(
			'id'=>'evcal_005',
			'name'=>'Google Maps API Settings',
			'tab_name'=>'Google Maps API',
			'top'=>'4',
			'fields'=>array(
				array('id'=>'evcal_cal_gmap_api','type'=>'yesno','name'=>'Disable Google Maps API','legend'=>'This will stop gmaps API from loading on frontend and will stop google maps from generating on event locations.','afterstatement'=>'evcal_cal_gmap_api'),
				array('id'=>'evcal_cal_gmap_api','type'=>'begin_afterstatement'),
				array('id'=>'evcal_gmap_disable_section','type'=>'radio','name'=>'Select which part of Google gmaps API to disable','width'=>'full',
					'options'=>array(
						'complete'=>'Completely disable google maps',
						'gmaps_js'=>'Google maps javascript file only (If the js file is already loaded with another gmaps program)')
				),
				array('id'=>'evcal_cal_gmap_api','type'=>'end_afterstatement'),
				
				
				array('id'=>'evcal_gmap_format', 'type'=>'dropdown','name'=>'Google maps display type:',
					'options'=>array(
						'roadmap'=>'ROADMAP Displays the normal default 2D',
						'satellite'=>'SATELLITE Displays photographic tiles',
						'hybrid'=>'HYBRID Displays a mix of photographic tiles and a tile layer',
					)),
				array('id'=>'evcal_gmap_zoomlevel', 'type'=>'dropdown','name'=>'Google starting zoom level:',
					'options'=>array(
						'18'=>'18',
						'16'=>'16',
						'14'=>'14',
						'12'=>'12',
						'10'=>'10',
						'8'=>'8',
					)),
		)),
		array(
			'id'=>'evcal_001a',
			'name'=>'Calendar front-end Sorting and filtering options',
			'tab_name'=>'Sorting and Filtering',
			'top'=>'4',
			'fields'=>array(
				array('id'=>'evcal_sort_options', 'type'=>'checkboxes','name'=>'Event sorting options to show on Calendar <i>(Note: Event Date will be default sorting option that will be always on)</i>',
					'options'=>array(
						'title'=>'Event Main Title',
						'color'=>'Event Color',									
					)),
				array('id'=>'evcal_filter_options', 'type'=>'checkboxes','name'=>'Event filtering options to show on the calendar</i>',
					'options'=>array(
						'event_type'=>$evt_name,
						'event_type_2'=>$evt_name2.' (This is only for filtering purposes will not show in individual events)',
					)),
		)),
		array(
			'id'=>'evcal_002',
			'name'=>'General Frontend Calendar Appearance',
			'tab_name'=>'Appearance',
			'top'=>'40',
			'fields'=>array(
				array('id'=>'evcal_hexcode','type'=>'color','name'=>'Primary Calendar Color'),
				array('id'=>'evcal_font_fam','type'=>'text','name'=>'Primary Calendar Font family <i>(Note: type the name of the font that is supported in your website. eg. Arial)</i>'),
				
				array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Font Colors'),
				array('id'=>'evcal_header1_fc','type'=>'color','name'=>'Calendar month and year name font color'),
				array('id'=>'evcal__fc2','type'=>'color','name'=>'Date font color'),
				array('id'=>'evcal__fc3','type'=>'color','name'=>'Event Title font color'),
				array('id'=>'evcal__fc4','type'=>'color','name'=>'Event card section title font color (eg. Event Detials)'),
				array('id'=>'evcal__fc5','type'=>'color','name'=>'Event card general font color'),
				
				array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Background Colors'),
				array('id'=>'evcal__bc1','type'=>'color','name'=>'Event Card background color'),
				
				array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Buttons'),
				array('id'=>'evcal_gen_btn_bgc','type'=>'color','name'=>'Button Color'),
				array('id'=>'evcal_gen_btn_fc','type'=>'color','name'=>'Button Text Color'),
				array('id'=>'evcal_gen_btn_bgcx','type'=>'color','name'=>'Button Color (Hover State)'),
				array('id'=>'evcal_gen_btn_fcx','type'=>'color','name'=>'Button Text Color (Hover State)'),
				
			)
		),
		array(
			'id'=>'evcal_004',
			'name'=>'Custom Icons for Calendar',
			'tab_name'=>'Icons',
			'top'=>'76',
			'fields'=>array(
				array('id'=>'evcal_icons','type'=>'note','name'=>'32 x 32 pixel icons are recommended for replacement icons.'),
				array('id'=>'evcal_icon_001','type'=>'image','name'=>'Event Detail Icon'),
				array('id'=>'evcal_icon_002','type'=>'image','name'=>'Event Organizer Icon'),
				array('id'=>'evcal_icon_003','type'=>'image','name'=>'Event Capacity Icon'),
				array('id'=>'evcal_icon_004','type'=>'image','name'=>'Event Learn More Icon'),	
				array('id'=>'evcal_icon_005','type'=>'image','name'=>'Event Ticket Icon'),														
			
			)
		),array(
			'id'=>'evcal_003',
			'name'=>'Third Party API Support for Event Calendar',
			'tab_name'=>'Third Party APIs',
			'top'=>'112',
			'fields'=>array(
				// eventbrite
				array('id'=>'evcal_s','type'=>'subheader','name'=>'EventBrite'),
				array('id'=>'evcal_evb_events','type'=>'yesno','name'=>'Enable EventBrite data fetching for calendar events','legend'=>'Once enabled, this will allow you to connect eventbrite to event calendar and populate event data such as event name, event location, ticket price for events, event capacity & link to buy ticket','afterstatement'=>'evcal_evb_events'),
				array('id'=>'evcal_evb_events','type'=>'begin_afterstatement'),
				array('id'=>'evcal_evb_api','type'=>'text','name'=>'EventBrite API Key'),
				array('id'=>'evcal_evb_api_note','type'=>'note','name'=>'(In order to get your eventbrite API key <a href=\'https://www.eventbrite.com/api/key/\' target=\'_blank\'>open this</a> and login to your eventbrite account, fill in the required information in this page and click "create key". Once approved, you will receive the API key.)'),
				array('id'=>'evcal_eb_hide','type'=>'end_afterstatement'),
				array('id'=>'evcal_api_note','type'=>'note',
				'name'=>'<i>(Please stay tune for more third party API support coming soon in the big version 2.0 update!)</i>'),
				
				// meetup
				array('id'=>'evcal_s','type'=>'subheader','name'=>'Meetup'),
				array('id'=>'evcal_api_meetup','type'=>'yesno','name'=>'Enable Meetup data fetching for calendar events','legend'=>'Once enabled, this will allow your to connect meetup events and populate calendar events with meetup event data such as event name, event time, location, & meetup event url','afterstatement'=>'evcal_api_meetup'),
				array('id'=>'evcal_api_meetup','type'=>'begin_afterstatement'),
				array('id'=>'evcal_api_mu_key','type'=>'text','name'=>'Meetup API Key'),
				array('id'=>'evcal_api_mu_note','type'=>'note','name'=>'(In order to get your meetup API key, login to meetup and <a href=\'http://www.meetup.com/meetup_api/key/\' target=\'_blank\'>open this</a>.)'),
				array('id'=>'evcal_mu_settings','type'=>'end_afterstatement'),
				
				// paypal
				array('id'=>'evcal_s','type'=>'subheader','name'=>'Paypal'),
				array('id'=>'evcal_paypal_pay','type'=>'yesno','name'=>'Enable PayPal event ticket payments','afterstatement'=>'evcal_paypal_pay', 'legend'=>'This will allow you to add a paypal direct link to each event that will allow visitors to pay for event via paypal.'),
			)
		)
	);			
?>