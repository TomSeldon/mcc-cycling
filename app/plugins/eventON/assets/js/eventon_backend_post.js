jQuery(document).ready(function($){
	
	// repeating events UI
	$('#evd_repeat').click(function(){
		// yes
		if($(this).hasClass('btn_at_no')){
			$('.evcalr_2').slideDown();
		}else{
			$('.evcalr_2').slideUp();
		}
	});
	$('#evcal_rep_freq').change(function(){
		var field = $(this).find("option:selected").attr('field');
		$('#evcal_re').html(field);
	});
	
	//event color
	$('.evcal_color_box').click(function(){
		$('.evcal_color_box').removeClass('selected');
		$(this).addClass('selected');
		$('#evcal_event_color').val( $(this).attr('color') );
		$('#evcal_event_color_n').val( $(this).attr('color_n') );
	});
	
	//date picker on 
	var date_format = $('#evcal_dates').attr('date_format');
	$('.datapicker_on').datepicker({ dateFormat: date_format });
	
	//yes no buttons in event edit page
	$('#meta_tb').delegate('.evcal_yn_btn', 'click',function(){
		// yes
		if($(this).hasClass('btn_at_no')){
			$(this).removeClass('btn_at_no');
			$(this).siblings('input').val('yes');
			
			if($(this).attr('allday_switch')=='1'){
				$('.switch_for_evsdate').hide();
				$('#evcal_start_date_label').html("Event Date");
				$('#evcal_end_date').val('');
				$('.evcal_date_select').val('');
			}
		}else{//no
			$(this).addClass('btn_at_no');
			$(this).siblings('input').val('no');
			
			if($(this).attr('allday_switch')=='1'){
				$('.switch_for_evsdate').show(); $('#evcal_start_date_label').html("Event Start Date");
			}
		}
		
	});
	
	// eventbrite
	$('#evcal_eventb_btn').click(function(){
		$('#evcal_eventb_data').slideToggle();
	});
	$('#evcal_eventb_btn_2').click(function(){
		$('#evcal_eventb_msg').hide();
		var ev= $('#evcal_eventb_ev_id').val();
		if(ev==''){
			$('#evcal_eventb_msg').html('You gotta enter something other than blank space..').show();
		}else{
			
			$.ajax({
				beforeSend: function(){
					$('#evcal_eventb_msg').html('We are connecting to eventbrite..').show();
				},
				type: 'POST',
				url:the_ajax_script.ajaxurl,
				data: {	action:'the_post_ajax_hook_3',	
					event_id:ev
				},
				dataType:'json',
				success:function(data){
					//alert(data);
					if(data.status =='1'){
						$('#evcal_eventb_msg').hide();
						$('#evcal_eventb_data_tb').append(data.code);
						$('#evcal_eventb_s1').delay(400).slideDown();
						$('#evcal_eb1').html(ev);
						$('#evcal_eventb_ev_d2').val(ev);
					}else{
						$('#evcal_eventb_msg').html('Could not retrieve data at this time.').show();
					}
					
				},complete:function(){
					//ev_cal.find('.evcal_events_list').delay(300).fadeIn();
				}
			});
			
		}
	});
	$('#evcal_eventb_data_tb').delegate('.evcal_data_row','click',function(){
		
		var field = $(this).attr('var');
		var p_val = $(this).find('p.value');
		var value = p_val.html();
		var this_makd = $(this).attr('marked');
		
		if(this_makd =='yes'){
		// DESELECT
			// evcal_eb_
			// evcal_ebv_
			$(this).removeClass('evcal_checked_row');
			$(this).attr({'marked':'no'});
			
			if(field =='capacity' || field=='price' || field=='url' ){
				$('.evcal_eb_'+field).slideUp();
				$('#evcal_ebv_'+field).attr({'value':''});
			}else{
				var oldval = $('#'+field).attr('oldval');				
				$('#'+field).val(oldval);				
			}
		}else{
			// SELECT
			$(this).addClass('evcal_checked_row');
			$(this).attr({'marked':'yes'});
			
			if(field =='capacity'|| field=='price' || field=='url'){
				$('.evcal_eb_'+field).slideDown();
				$('#evcal_ebv_'+field).val(value);
			}else{
				var field_cv =$('#'+field).val();
				if(field_cv!=''){
					$('#'+field).attr({'oldval':field_cv});
				}
				$('#'+field).val(value);
			}
			if(field =='capacity' ){
				$('#evcal_eb3').html(value);
			}if(field =='price' ){
				$('#evcal_eb4').html(value);
			}
		}
		$('#evcal_eventb_ev_d1').val('yes');		
	});
	// disconnect event brite
	$('#evcal_eventb_btn_dis').click(function(){
		var val_ar = new Array('evcal_eventb_ev_d2', 'evcal_eventb_ev_d1',
			'evcal_ebv_url','evcal_ebv_capacity','evcal_eventb_tprice');
		
		for(i=0; i<val_ar.length; i++){
			$('#'+val_ar[i]).attr({'value':''});
		}
		
		$('.evcal_eb_r').slideUp();
		$('#evcal_eb5').hide();
	});	
	
	
	// ===================================
	// MEETUP
	$('#evcal_meetup_btn').click(function(){
		$('#evcal_meetup_data').slideToggle();
	});
	
	$('#evcal_meetup_btn_2').click(function(){
		$('#evcal_meetup_msg').hide();
		var ev= $('#evcal_meetup_ev_id').val();
		if(ev==''){
			$('#evcal_meetup_msg').html('You gotta enter something other than blank space..').show();
		}else{
			
			$.ajax({
				beforeSend: function(){
					$('#evcal_meetup_msg').html('We are connecting to Meetup..').show();
				},
				type: 'POST',
				url:the_ajax_script.ajaxurl,
				data: {	action:'the_post_ajax_hook_2',	
					event_id:ev
				},
				dataType:'json',
				success:function(data){
					//alert(data);
					if(data.status =='1'){
						$('#evcal_meetup_msg').hide();
						$('#evcal_meetup_data_tb').append(data.code);
						$('#evcal_meetup_s1').delay(400).slideDown();
						$('#evcal_001').html(ev);
						$('#evcal_meetup_ev_d2').val(ev);
					}else{
						$('#evcal_meetup_msg').html('Could not retrieve data at this time.').show();
					}
					
				},complete:function(){
					//ev_cal.find('.evcal_events_list').delay(300).fadeIn();
				}
			});
			
		}
	});
	
	$('#evcal_meetup_data_tb').delegate('.evcal_data_row','click',function(){
		$(this).addClass('evcal_checked_row');
		
		var field = $(this).attr('var');
		var p_val = $(this).find('p.value');
		var value = p_val.html();
		var this_makd = $(this).attr('marked');
		
		if(this_makd =='yes'){
			// DESELECT
			// evcal_mu_
			// evcal_muv_
			$(this).removeClass('evcal_checked_row');
			$(this).attr({'marked':'no'});
			
			if(field=='url' ){
				//$('.evcal_mu_'+field).slideUp();
				$('#evcal_lmlink').attr({'value':''});
			}else{
				var oldval = $('#'+field).attr('oldval');				
				$('#'+field).val(oldval);				
			}
		}else{
			// SELECT
			$(this).addClass('evcal_checked_row');
			$(this).attr({'marked':'yes'});
			
			if(field=='url'){
				//$('.evcal_mu_'+field).slideDown();
				//$('#evcal_muv_'+field).val(value);
				$('#evcal_lmlink').val(value);
			}else if(field =='time' ){
				$('#evcal_start_date').val( p_val.attr('ftime') );
				$('#evcal_start_time_hour').val( p_val.attr('hr') );
				$('#evcal_start_time_min').val( p_val.attr('min') );
				$('#evcal_st_ampm').val( p_val.attr('ampm') );
			}else{
				var field_cv =$('#'+field).val();
				if(field_cv!=''){
					$('#'+field).attr({'oldval':field_cv});
				}
				$('#'+field).val(value);
			}			
		}
		$('#evcal_meetup_ev_d1').val('yes');		
	});
	
	// disconnect meetup
	$('#evcal_meetup_btn_dis').click(function(){
		// remove values from MU data set and MU id
		var val_ar = new Array('evcal_meetup_ev_d1', 'evcal_meetup_ev_d2');
		
		for(i=0; i<val_ar.length; i++){
			$('#'+val_ar[i]).attr({'value':''});
		}
		
		$('.evcal_meetup_url_field').slideUp();
		$('#evcal_mu2').hide();
	});	
	
});