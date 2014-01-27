<?php
	// Licenses tab in myEventON Settings
	// version: 0.1
?>
<div id="evcal_5" class="evcal_admin_meta">
	<div class='licenses_list' id='eventon_licenses'>
		<?php
			
			
			
			$show_license_msg = true;
			//delete_option('_evo_licenses');
			$evo_licenses = get_option('_evo_licenses');
			//print_r( get_option('_evo_licenses') );
			//echo AJDE_EVCAL_BASENAME;
			
			// running for the first time
			if(empty($evo_licenses)){
				
				$lice = array(
					'eventon'=>array(
						'name'=>'EventON',
						'current_version'=>$eventon->version,
						'type'=>'plugin',
						'status'=>'inactive',
						'key'=>'',
					));
				update_option('_evo_licenses', $lice);
				
				$evo_licenses = get_option('_evo_licenses');				
			}
			
			// render existing licenses
			if(!empty($evo_licenses) && count($evo_licenses)>0){
				foreach($evo_licenses as $slug=>$evl){
					if($evl['status']=='active'){
						
						$new_update_text = (!empty($evl['has_new_update']) && $evl['has_new_update'])?
							"<p>New Version: ".$evl['remote_version']."</p>":null;
						$new_update_details_btn = (!empty($evl['has_new_update']) && $evl['has_new_update'])?
							"<p><a class='button-primary thickbox' href='".BACKEND_URL."plugin-install.php?tab=plugin-information&plugin=eventon&section=changelog&TB_iframe=true&width=600&height=400'>New Version Details</a></p>":"<p><i>You have the latest version.</i></p>";
						
						echo "<div class='license activated' id='license_{$evl['name']}'>
							<h2>{$evl['name']}</h2>
							<p>Version: {$evl['current_version']}</p>".$new_update_text."
							<p class='license_key'>{$evl['key']}</p>".$new_update_details_btn."		
						</div>";
						
						$show_license_msg = false;
					}else{
						
						$new_update_text = (!empty($evl['has_new_update']) && $evl['has_new_update'])?
							"<p>New Version: ".$evl['remote_version']."</p>":null;
							
						echo "<div class='license' id='license_{$evl['name']}'>
							<h2>{$evl['name']}</h2>
							<p>Version: {$evl['current_version']}</p>".$new_update_text."
							<a class='button eventon_popup_trig' dynamic_c='1' content_id='eventon_pop_content_001'>Activate Now</a>							
						</div>
						<div id='eventon_pop_content_001' class='evo_hide_this'>
							<h2>Activate License</h2><p>Product: <strong>{$evl['name']}</strong></p><p>License Key <br/>
							<input class='eventon_license_key_val' type='text' style='width:100%'/>
							<input class='eventon_slug' type='hidden' value='{$slug}' /></p>
							<input class='eventon_license_div' type='hidden' value='license_{$evl['name']}' /></p>
							<p><a class='button eventon_submit_license'>Activate Now</a></p>
						</div>";
						
					}
				}
			}
		?>
		
	
		<?php /*<div class='license_blank'><p>+</p></div>*/?>
		<div class='clear'></div>
		
		<?php if($show_license_msg):?>
		<p><?php _e('Activate your copy of EventON to get free automatic plugin updates direct to your site!'); ?></p>
		<?php endif;?>
		
		<?php
			// Throw the output popup box html into this page
			echo $eventon->output_eventon_pop_window('Loading...');
		?>
	</div>
</div>