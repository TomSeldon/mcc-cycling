jQuery(document).ready(function($){
	
	
	//yes no buttons in event edit page
	$('#evcal_settings').delegate('.evcal_yn_btn', 'click',function(){
		// yes
		if($(this).hasClass('btn_at_no')){
			$(this).removeClass('btn_at_no');
			$(this).siblings('input').val('yes');
			
			$('#'+$(this).attr('afterstatement')).show();
			
		}else{//no
			$(this).addClass('btn_at_no');
			$(this).siblings('input').val('no');
			
			$('#'+$(this).attr('afterstatement')).hide();
		}
		
	});
	
	
	// language tab
	$('.eventon_cl_input').focus(function(){
		$(this).parent().addClass('onfocus');
	});
	$('.eventon_cl_input').blur(function(){
		$(this).parent().removeClass('onfocus');
	});
	
	
	
});