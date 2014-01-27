/*
	Javascript code that is associated with the front end of the calendar
	version: 1.5
*/
jQuery(document).ready(function($){
	
	
		
	//event full description
	$('.eventon_events_list').delegate('a.desc_trig', 'click', function(){
		
		var exlk = $(this).attr('exlk');
		if(exlk=='1'){
			return;
		}else{
			// event not connecting to a link
			var obj = $(this);
			var cal = $(this).closest('div.ajde_evcal_calendar ');
			if(cal.hasClass('evcal_widget')){
				cal.find('.evcal_gmaps').each(function(){
					var gmap_id = $(this).attr('id');
					var new_gmal_id =gmap_id+'_widget';
					$(this).attr({'id':new_gmal_id})
				});
			}			
			
			obj.siblings('.event_description').slideToggle();
			
			if( obj.attr('gmtrig')=='1' && obj.attr('gmap_status')!='null'){
				var mapformat = cal.attr('mapformat');
				var address = obj.find('.evcal_location').attr('add_str');
				var map_canvas_id= obj.siblings('.event_description').find('.evcal_gmaps').attr('id');
				var zoom = cal.attr('mapzoom');
				var zoomlevel = (typeof zoom !== 'undefined' && zoom !== false)? parseInt(zoom):12;
				
				//obj.siblings('.event_description').find('.evcal_gmaps').html(address);
				initialize(map_canvas_id, address, mapformat, zoomlevel);				
			}			
			
			
			return false;
		}
	});
	
	// close event card
	$('.eventon_events_list').delegate('.evcal_close','click',function(){
		$(this).parent().slideUp();
	});
	
	
	//===============================
	// SORT BAR SECTION
	// ==============================
	
	// sorting section	
	$('.sorting_set_val').click(function(){		
		$('.eventon_sortbar_selection').fadeToggle();
	});
	
		// update calendar based on the sorting selection
		$('.eventon_sortbar_selection').delegate('.evs_btn','click',function(){		
				
			var cal_head = $(this).closest('.eventon_sorting_section').siblings('.calendar_header');
			var cur_m = parseInt( cal_head.attr('cur_m'));
			var cur_y = parseInt( cal_head.attr('cur_y'));	
			var sort_by = $(this).attr('val');
			var new_sorting_name = $(this).html();
			var cal_id = cal_head.parent().attr('id');	
						
			ajax_post_content(cur_m,cur_y,sort_by,cal_id);
			
			// update new values everywhere
			cal_head.attr({'sort_by':sort_by});		
			$(this).parent().find('p').removeClass('evs_hide');
			$(this).addClass('evs_hide');		
			$(this).parent().siblings('.eventon_sf_cur_val').find('p').html(new_sorting_name);
			$(this).parent().hide();
		});
	
	
	// filtering section
	$('.filtering_set_val').click(function(){
		$(this).siblings('.eventon_filter_dropdown').fadeIn();
	});	
	
		// selection on filter dropdown list
		$('.eventon_filter_dropdown').delegate('p','click',function(){
			var new_filter_val = $(this).attr('filter_val');
			var filter = $(this).closest('.eventon_filter');
			var filter_current_set_val = filter.attr('filter_val');
			
			if(filter_current_set_val == new_filter_val){
				$(this).parent().fadeOut();
			}else{
				// set new filtering changes				
				var cal_head = $(this).closest('.eventon_sorting_section').siblings('.calendar_header');
				var cur_m = parseInt( cal_head.attr('cur_m'));
				var cur_y = parseInt( cal_head.attr('cur_y'));	
				var sort_by = cal_head.attr('sort_by');
				var cal_id = cal_head.parent().attr('id');				
				
				// make changes
				filter.attr({'filter_val':new_filter_val});	
				cal_head.attr({'filters_on':'true'});
				
				ajax_post_content(cur_m,cur_y,sort_by,cal_id);
				
				// reset the new values				
				var new_filter_name = $(this).html();
				$(this).parent().fadeOut();
				$(this).parent().siblings('.filtering_set_val').html(new_filter_name);
			}
		});
	

	
	// previous month
	$('.evcal_btn_prev').click(function(){
		var cal_head = $(this).parents('.calendar_header');
		var parent_bar = cal_head.siblings('div.evcal_sort');
				
		var sort_by=parent_bar.attr('sort_by');
		var cur_m = parseInt( cal_head.attr('cur_m'));
		var cur_y = parseInt( cal_head.attr('cur_y'));
		var new_m = (cur_m==1)?12:cur_m-1;
		var new_y = (cur_m==1)?cur_y-1:cur_y;
		
		cal_id = $(this).closest('.ajde_evcal_calendar').attr('id');
		
		ajax_post_content(new_m,new_y,sort_by,cal_id);
		
	});
	
	// next month
	$('.evcal_btn_next').click(function(){	
		
		var cal_head = $(this).parents('.calendar_header');
		var parent_bar = cal_head.siblings('div.evcal_sort');
		
		var sort_by=parent_bar.attr('sort_by');
		var cur_m = parseInt(cal_head.attr('cur_m'));
		var cur_y = parseInt(cal_head.attr('cur_y'));
		var new_m = (cur_m==12)?1: cur_m+ 1 ;
		var new_y = (cur_m==12)? cur_y+1 : cur_y;
		
		cal_id = $(this).closest('.ajde_evcal_calendar').attr('id');
		
		ajax_post_content(new_m,new_y,sort_by, cal_id);
		
	});
	
	
	function ajax_post_content(new_m, new_y,sort_by,cal_id){
		
		// identify the calendar and its elements.
		var ev_cal = $('#'+cal_id); 
		var cal_head = ev_cal.find('.calendar_header');	
		var filters_on = ( cal_head.attr('filters_on')=='true')?'true':'false';
		
		// creat the filtering data array if exist
		if(filters_on =='true'){
			var filter_section = ev_cal.find('.eventon_filter_line');
			var filter_array = [];
			
			filter_section.find('.eventon_filter').each(function(index){
				var filter_val = $(this).attr('filter_val');
				
				if(filter_val !='all'){
					var filter_ar = {};
					filter_ar['filter_type'] = $(this).attr('filter_type');
					filter_ar['filter_name'] = $(this).attr('filter_field');
					filter_ar['filter_val'] = filter_val;
					filter_array.push(filter_ar);
				}
			});			
		}else{
			var filter_array ='';
		}
		
			
		
		// category filtering for the calendar
		var cat = ev_cal.find('.evcal_sort').attr('cat');
		var event_count = parseInt(cal_head.attr('ev_cnt'));
		
		var data_arg = {
			action:'the_ajax_hook',
			next_m:new_m,	
			next_y:new_y,	
			sort_by:sort_by, 
			event_count:event_count,
			filters:filter_array
		};
		
		cal_head.find('.eventon_other_vals').each(function(){
			if($(this).val()!=''){
				data_arg[$(this).attr('name')] = $(this).val();
			}
		});
		
		
		$.ajax({
			beforeSend: function(){
				ev_cal.find('.eventon_events_list').slideUp('fast');
				ev_cal.find('#eventon_loadbar').show().css({width:'0%'}).animate({width:'100%'});
			},
			type: 'POST',
			url:the_ajax_script.ajaxurl,
			data: data_arg,
			dataType:'json',
			success:function(data){
				//alert(data);
				ev_cal.find('.eventon_events_list').html(data.content);
				ev_cal.find('#evcal_cur').html(data.new_month_year+', '+new_y);
				ev_cal.find('#evcal_head').attr({'cur_m':new_m,'cur_y':new_y});
			},complete:function(){
				ev_cal.find('#eventon_loadbar').css({width:'100%'}).fadeOut();
				ev_cal.find('.eventon_events_list').delay(300).slideDown();
			}
		});
		
	}
	
	// show more and less of event details
	$('.eventon_events_list').delegate('.eventon_shad_p','click',function(){
		
		var content = $(this).attr('content');
		var current_text = $(this).find('.ev_more_text').html();
		var changeTo_text = $(this).find('.ev_more_text').attr('txt');
		
		if(content =='less'){			
			
			var hei = $(this).parent().siblings('.eventon_full_description').height();
			var orhei = $(this).closest('.evcal_evdata_cell').height();
			
			$(this).closest('.evcal_evdata_cell').attr({'orhei':orhei}).animate({height: (parseInt(hei)+40) });
			
			$(this).attr({'content':'more'});
			$(this).find('.ev_more_arrow').addClass('less');
			$(this).find('.ev_more_text').attr({'txt':current_text}).html(changeTo_text);
			
		}else{
			var orhei = parseInt($(this).closest('.evcal_evdata_cell').attr('orhei'));
			
			$(this).closest('.evcal_evdata_cell').animate({height: orhei });
			
			$(this).attr({'content':'less'});
			$(this).find('.ev_more_arrow').removeClass('less');
			$(this).find('.ev_more_text').attr({'txt':current_text}).html(changeTo_text);
		}
		
	});	
	
});