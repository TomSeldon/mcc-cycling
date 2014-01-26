/*
	Script that runs on all over the backend pages
*/
jQuery(document).ready(function($){
	
	// Popup guide
	$('.eventon_close_pop_btn').click(function(){
		$('#eventon_popup').animate({'margin-top':'70px','opacity':0}).fadeOut();
	});
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
		
		$('#eventon_popup').find('.message').removeClass('bad good').hide();
		$('#eventon_popup').show().animate({'margin-top':'0px','opacity':1}).fadeIn();
	});
	
	
	// licenses verification and saving
	$('#eventon_popup').delegate('.eventon_submit_license','click',function(){
		
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
						$('#'+lic_div).html(data.new_content).addClass('activated');
						
						show_pop_good_msg('License key verified and saved.');
						$('#eventon_popup').delay(5000).queue(function(n){
							$(this).animate({'margin-top':'70px','opacity':0}).fadeOut();
							n();
						});
						
					}else{
						show_pop_bad_msg('Could not verify the License key. Please try again.');
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
});