/*
	Script that runs on all over the backend pages
	ver: 1.3
*/
jQuery(document).ready(function($){
	
	// ----------
	// EventON Sitewide POPUP
	// ----------
	// hide		
	$('#eventon_popup').on('click','.eventon_close_pop_btn', function(){
		var obj = $(this);
		hide_popupwindowbox();
	});
	
	$('.eventon_popup_text').on('click',' .evo_close_pop_trig',function(){
		var obj = $(this).parent();
		hide_popupwindowbox();
	});
	
	$(document).mouseup(function (e){
		var container=$('#eventon_popup');
		
		if(container.hasClass('active')){
			if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) // ... nor a descendant of the container
			{
				container.animate({'margin-top':'70px','opacity':0}).fadeOut().removeClass('active');
			}
		}
	});
	
	// function to hide popup that can be assign to click actions
	function hide_popupwindowbox(){
		
		var container=$('#eventon_popup');
		var clear_content = container.attr('clear');
		
		if(container.hasClass('active')){
			container.animate({'margin-top':'70px','opacity':0},300).fadeOut().
				removeClass('active')
				.delay(300)
				.queue(function(n){
					if(clear_content=='true')					
						$(this).find('.eventon_popup_text').html('');
						
					n();
				})				
				
		}
	}
	
	
	
	/*
		DISPLAY Eventon in-window popup box
		Usage: <a class='button eventon_popup_trig' content_id='is_for_content' dynamic_c='yes'>Click</a>
	*/
	$('.eventon_popup_trig').click(function(){
		
		// dynamic content within the site
		var dynamic_c = $(this).attr('dynamic_c');
		if(typeof dynamic_c !== 'undefined' && dynamic_c !== false){
			
			var content_id = $(this).attr('content_id');
			var content = $('#'+content_id).html();
			
			$('#eventon_popup').find('.eventon_popup_text').html( content);
		}
		
		// if content coming from a AJAX file
		var attr_ajax_url = $(this).attr('ajax_url');
		
		if(typeof attr_ajax_url !== 'undefined' && attr_ajax_url !== false){
			
			$.ajax({
				beforeSend: function(){
					show_pop_loading();
				},
				url:attr_ajax_url,
				success:function(data){
					$('#eventon_popup').find('.eventon_popup_text').html( data);			
					
				},complete:function(){
					hide_pop_loading();
				}
			});
		}
		
		// change title if present		
		var poptitle = $(this).attr('poptitle');
		if(typeof poptitle !== 'undefined' && poptitle !== false){
			$('#evoPOP_title').html(poptitle);
		}
		
		
		$('#eventon_popup').find('.message').removeClass('bad good').hide();
		$('#eventon_popup').addClass('active').show().animate({'margin-top':'0px','opacity':1}).fadeIn();
	});
	
	
	// licenses verification and saving
	$('#eventon_popup').on('click','.eventon_submit_license',function(){
		
		$('#eventon_popup').find('.message').removeClass('bad good');
		
		var parent_pop_form = $(this).parent().parent();
		var license_key = parent_pop_form.find('.eventon_license_key_val').val();
		
		if(license_key==''){
			show_pop_bad_msg('License key can not be blank! Please try again.');
		}else{
			
			var slug = parent_pop_form.find('.eventon_slug').val();
			
			var data_arg = {
				action:'eventon_verify_lic',
				key:license_key,
				slug:slug
			};					
			
			$.ajax({
				beforeSend: function(){
					show_pop_loading();
				},
				type: 'POST',
				url:the_ajax_script.ajaxurl,
				data: data_arg,
				dataType:'json',
				success:function(data){
					if(data.status=='success'){
						var lic_div = parent_pop_form.find('.eventon_license_div').val();
						$('#'+lic_div).addClass('activated').find('.license_in').html(data.new_content);
						
						show_pop_good_msg('License key verified and saved.');
						$('#eventon_popup').delay(3000).queue(function(n){
							$(this).animate({'margin-top':'70px','opacity':0}).fadeOut();
							n();
						});
						
					}else{
						show_pop_bad_msg(data.error_msg);
					}					
					
				},complete:function(){
					hide_pop_loading();
				}
			});
		}
	});
	
	function show_pop_bad_msg(msg){
		$('#eventon_popup').find('.message').removeClass('bad good').addClass('bad').html(msg).fadeIn();
	}
	function show_pop_good_msg(msg){
		$('#eventon_popup').find('.message').removeClass('bad good').addClass('good').html(msg).fadeIn();
	}
	
	function show_pop_loading(){
		$('.eventon_popup_text').css({'opacity':0.3});
		$('#eventon_loading').fadeIn();
	}
	function hide_pop_loading(){
		$('.eventon_popup_text').css({'opacity':1});
		$('#eventon_loading').fadeOut(20);
	}
	
	
	
	
	
	// widget
	$('.widgets-sortables').on('click','.evowig_chbx', function(){
		
		if($(this).hasClass('selected')){
			$(this).removeClass('selected');
			
			$(this).siblings('input').val('no');
			$(this).parent().siblings('.evo_wug_hid').slideUp('fast');
		}else{
			$(this).addClass('selected');
			
			$(this).siblings('input').val('yes');
			$(this).parent().siblings('.evo_wug_hid').slideDown('fast');
		}
		
		
	});
	

	
	
	// ==========================
	// shortcode popup box
	// evoPOSH
	evoPOSH_go_back();
	evoPOSH_show_legends();
	var shortcode;
	
	$('#evoPOSH_outter').on('click','.evoPOSH_btn',function(){
		var section = $(this).attr('step2');
		var code = $(this).attr('code');
		

		// no 2nd step
		if($(this).hasClass('nostep') ){
			$('#evoPOSH_code').html('['+code+']').attr({'code':code});
		}else{
			$(this).parent().parent().find('#'+section).show();
			$('.evoPOSH_inner').animate({'margin-left':'-470px'});
			
			evoPOSH_show_back_btn();
			
			$('#evoPOSH_code').html('['+code+']').attr({'code':code});
		}
	});
	
	// legends
	function evoPOSH_show_legends(){
		$('.fieldline').on('mouseover','p.label',function(){
			if($(this).hasClass('instruct')){
				
				var el = $('h3.notifications span');				
				el.html( $(this).attr('instruct') );
				//console.log($(this).attr('instruct'));
			}			
		});
		$('.fieldline').on('mouseleave','p.label',function(){
			var el = $('h3.notifications span');
			var el_ = el.attr('bf');
			
			el.html( el_);
		});
	}
	
	// yes no buttons
	$('body').on('click','.evo_YN_btn', function(){
		var codevar = $(this).attr('codevar');
		var value;
		
		if($(this).hasClass('NO')){
			$(this).removeClass('NO');	
			value = 'yes';
		}else{
			$(this).addClass('NO');	value = 'no';
		}
		
		evoPOSH_update_codevars(codevar,value);
		
		evoPOSH_update_shortcode();
	});
	
	// input and select fields
	$('.evoPOSH_inner').on('change','.evoPOSH_input, .evoPOSH_select', function(){
		var value = $(this).val();
		var codevar = $(this).attr('codevar');
		
		if(value!='' && value!='undefined'){			
			evoPOSH_update_codevars(codevar,value);
			evoPOSH_update_shortcode();
		}else if(!value){
			evoPOSH_remove_codevars(codevar);			
		}		
	});
	
	
	
	// whenever new variables are changed update it for getting
	function evoPOSH_update_codevars(codevar,value){
		if($('#evoPOSH_var_').find('.'+codevar).length==0){
			$('#evoPOSH_var_').append("<a class='"+codevar+"' val='"+value+"'></a>");			
		}else{
			$('#evoPOSH_var_').find('.'+codevar).attr({'val':value});
		}
	}
	function evoPOSH_remove_codevars(codevar){
		if($('#evoPOSH_var_').find('.'+codevar).length!=0){
			$('#evoPOSH_var_').find('.'+codevar).remove();
			evoPOSH_update_shortcode();			
		}
		
	}
	// show back button
	function evoPOSH_show_back_btn(){
		$('#evoPOSH_back').animate({'left':'0px'});
		
		$('h3.notifications').addClass('back');
	}
	// go back button on the shortcode popup
	function evoPOSH_go_back(){
		$('#evoPOSH_back').click(function(){		
			$(this).animate({'left':'-20px'},'fast');	
			
			$('h3.notifications').removeClass('back');
		
			$('.evoPOSH_inner').animate({'margin-left':'0px'}).find('.step2_in').fadeOut();
			
			// clear varianles
			$('#evoPOSH_var_').html('');
			$('#evoPOSH_code').html('['+$('#evoPOSH_code').attr('code')+']');
		});
	}	
	
	// update shortcode based on new selections
	function evoPOSH_update_shortcode(){
		
		var el = $('#evoPOSH_code');
		var el_v = $('#evoPOSH_var_');
		var code = el.attr('code')+' ';
		var string = el.attr('code')+' ';
		
		if(el_v.find('a').length==0){
			string=string;
		}else{
			el_v.find('a').each(function(){
				string += $(this).attr('class')+'="'+ $(this).attr('val')+'" ';
			});
		}
		
		
		string = '['+string+']';
		
		el.html(string);
		
	}
	
	
	// insert code into text editor
	$('.evoPOSH_footer').on('click','.evoPOSH_insert',function(){
		var shortcode = $('#evoPOSH_code').html();
		
		
		tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
		
		hide_popupwindowbox();
	});
	
	
});