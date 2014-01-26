<div id="evcal_4" class="postbox evcal_admin_meta">	
	<?php
		//print_r(get_option('eventon_addons'));
		
		// Addon function
		if(isset($_REQUEST['addon']) && isset($_REQUEST['act']) ){
			// Install and activate addon
			if($_REQUEST['act']=='activate'){
				eventon_update_addon_field($_REQUEST['addon'],'status','active');
				$addon_msg = "Successfully activated addon! Please refersh page.";
			}
			
			// deactive addon
			if($_REQUEST['act']=='deactivate'){
				eventon_update_addon_field($_REQUEST['addon'],'status','inactive');
				$addon_msg = "Successfully deactivated addon! Please refersh page.";
			}
		}
		
		//display message
		if(!empty($addon_msg)):
		?><div class="updated fade"><p><?php echo $addon_msg; ?></p></div>
		<?php	endif;		
	?>
	
	<div class="inside">
		
	<?php		
		
		$eventon_addons_opt = get_option('eventon_addons');
		//print_r($eventon_addons_opt);
		
		echo "<a href='http://www.myeventon.com/addons/' target='_blank'><img src='".AJDE_EVCAL_URL."/assets/images/eventon_addon_badge.png'/></a><hr/>";
		
		if(!empty($eventon_addons_opt) && is_array($eventon_addons_opt) ):
		
		
	?>
		<table id='nylon_data_list' class='wp-list-table widefat'>
			<thead>
				<th style='width:150px'><?php _e('Addon Name','eventon')?></th>
				<th><?php _e('Details','eventon');?></th>
			</thead>
			
			<?php foreach($eventon_addons_opt as $addonf=>$addon):	?>
			<tr>
				<?php
					// check for extension type addons
					$is_extension = (!empty($addon['type']) && $addon['type']== 'extension')?true:false;
					
					// check status of addons
					$oppos_status = (!empty($addon['status']) && $addon['status']=='active')? 
						'deactivate':'activate';
					$status = (!empty($addon['status']) && $addon['status']=='active')? 'Active':'Inactive';
					
					$status_html = "Status: ".$status." | <a href='".admin_url('admin.php?page=eventon&tab=evcal_4&addon='.$addonf.'&act='.$oppos_status)."'>".$oppos_status."</a>";
					
					if($is_extension)
						$status_html ="Type: Extension";
				?>
				<?php echo "<td>".$addon['name']."<br/><p><i>".$status_html."</i></p></td>";?>
				<?php 
				
				// guide content
				if(!empty($addon['guide_file'])  ){
					$guide_cnt ="<span class='button-primary eventon_guide_btn eventon_popup_trig' ajax_url='{$addon['guide_file']}'>Guide</span>";
				}else{ $guide_cnt ="";}
				
				echo "<td>
				<p>".$addon['details']."</p>				
				<p>{$guide_cnt} Installed version: ".$addon['version']."</p></td>";?>
			</tr>
			<?php /*
			<tr class='plugin-update-tr'>
				<td colspan='2' class='plugin-update'>
					<div class='update-message'>There is a new version of Auto-Update Plugin available. <a href='http://dev.myeventon.com/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=wptuts-autoupdate-plugin&amp;section=changelog&amp;TB_iframe=true&amp;width=640&amp;height=466' class='thickbox' title='Auto-Update Plugin'>View version 1.3 details</a>. <em>Automatic update is unavailable for this plugin.</em></div>
				</td>	
			</tr>
			*/?>
			<?php endforeach;?>
		</table>
		
		
	<?php 
	
		echo $eventon->output_eventon_pop_window("<p>Loading</p>",'');
	
		else:
			echo "<p>You do not have any addons.</p>";
		endif; 
	?>
		
		<hr></hr>
		<h4><?php _e('What are addons?');?></h4>
		<p><?php _e('Addons are neat extensions you can add to extend the features of the main Calendar.');?></p>		
		<h4><?php _e('More addons are available at <a href="http://www.myeventon.com/addons/" target="_blank">EventON</a> Addons');?></h4>		
	</div>
</div>