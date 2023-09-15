<?php 
if($permissions && $permissions != ''){
	$permissions = json_decode($permissions, true);
}
if($fields && $fields != ''){
	$fields = json_decode($fields, true);
}
if($actions && $actions != ''){
	$actions = json_decode($actions, true);
}
?>	
	<style>
    .dd-list{list-style-type:none}
    </style>
    <div class="tab-pane" id="form_tab_2" rel="tab_head_2">
    	<div class="form-group">
        	<div id="nestable_list_1" class="roles-list">
            	<ol class="dd-list">
            		
					<?php $i = 0; ?>
                    <?php $c = 0;?>
    				<?php foreach($modules as $module => $data){?>
        				
                        <li class="dd-item">
                    		<?php if(isset($data['permissions'])){ $i++;?>
							
                            	<div class="dd-handle">
                                	<i class="ico"><?php echo $data['icon']?></i>&nbsp;<label><?php echo $data['title'];?></label>
                                </div>
                            	<ol class="dd-list">
                            		<?php $n=0;?>
									<?php foreach($data['permissions'] as $permission){?>
										<?php $i++;?>
										<?php $n++;?>
                                		<li class="dd-item">
                                    		<div class="dd-handle">
												<?php $c++;?>
												<?php $is_checked = false?>
                                                <input data-num="<?php echo $c?>" data-module="<?php echo $module?>" class="roles_permission_checkbox" id="permission_<?php echo $c?>" type="checkbox" name="permissions[]" value="<?php echo $permission['path'];?>" <?php echo ($permissions && (in_array($permission['path'], $permissions))) ? 'checked="checked"' : ''?> />&nbsp;
												<label for="permission_<?php echo $c?>"><?php echo $permission['title'];?></label>
												
												<?php $is_checked = ($permissions && (in_array($permission['path'], $permissions))) ? true : false?>

                                            </div>

											<?php if(array_key_exists('fields', $permission)){?>

												<?php
												$selected_fields = [];
												if( ($fields && is_array($fields)) && array_key_exists($permission['path'], $fields)){
													$selected_fields = $fields[$permission['path']];
												}
												?>

												<div class="permission-fields-block clearer <?php echo (count($selected_fields) > 0 || $is_checked) ? 'show' : ''?>" id="permission_fields_<?php echo $c?>_<?php echo $module?>">
													<div class="pfb-header-block">
														<strong>Data Permissions</strong>
														<span class="pfb-check <?php echo (count($selected_fields) > 0) ? 'checked' : ''?>">
															<?php if(count($selected_fields) > 0){?>
																Unselect All
															<?php }else{?>
																Select All
															<?php }?>
														</span>
														<span class="pfb-control"></span>
													</div>

													<div class="permission-fields-container">
														<ul class="permission-fields">
															<?php foreach($permission['fields'] as $field_key => $field_title){?>
																<li>
																	<label>
																		<input type="checkbox" name="fields[<?php echo $permission['path'];?>][]" value="<?php echo $field_key;?>" <?php echo ((in_array($field_key, $selected_fields))) ? 'checked="checked"' : ''?>/> <?php echo $field_title?>
																	</label>
																</li>
															<?php }?>
														</ul>
													</div>	
												</div>
											<?php }?>

											<?php if(array_key_exists('actions', $permission)){?>

												<?php
												$selected_actions = [];
												if( ($actions && is_array($actions)) && array_key_exists($permission['path'], $actions)){
													$selected_actions = $actions[$permission['path']];
												}
												?>

												<div class="permission-fields-block clearer <?php echo (count($selected_actions) > 0 || $is_checked) ? 'show' : ''?>" id="action_fields_<?php echo $c?>_<?php echo $module?>">
													<div class="pfb-header-block">
														<strong>Action Permissions</strong>
														<span class="pfb-check">
															<?php if(count($selected_actions) > 0){?>
																Unselect All
															<?php }else{?>
																Select All
															<?php }?>
														</span>
														<span class="pfb-control"></span>
													</div>

													<div class="permission-fields-container">
														<ul class="permission-fields wide">
															<?php foreach($permission['actions'] as $action_key => $action_title){?>
																<li>
																	<label>
																		<input type="checkbox" name="actions[<?php echo $permission['path'];?>][]" value="<?php echo $action_key;?>" <?php echo ((in_array($action_key, $selected_actions))) ? 'checked="checked"' : ''?>/> <?php echo $action_title?>
																	</label>
																</li>
															<?php }?>
														</ul>
													</div>	
												</div>
											<?php }?>

                              			</li>
                           			<?php } ?>
                       			</ol>

                   			<?php }?>
                    	
                        </li>
        			<?php }?>
        		</ol>
        	</div>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(function(){
		jQuery(document).on('click', '.pfb-control', function(){
			_parent = jQuery(this).closest('.permission-fields-block');
			if(jQuery(_parent).hasClass('minimized')){
				jQuery(_parent).removeClass('minimized');
			}else{
				jQuery(_parent).addClass('minimized');
			}
		});

		jQuery(document).on('click', '.pfb-check', function(){
			_pfb_ele = jQuery(this);
			_checkboxes = jQuery(_pfb_ele).closest('.permission-fields-block').find('.permission-fields').find('input');
			
			if(jQuery(_pfb_ele).hasClass('checked')){
				jQuery(_checkboxes).prop("checked", false);
				jQuery(_pfb_ele).removeClass('checked');
				jQuery(_pfb_ele).text('Select All');
			}else{
				jQuery(_checkboxes).prop("checked", true);
				jQuery(_pfb_ele).addClass('checked');
				jQuery(_pfb_ele).text('Unselect All');
			}
		});

		jQuery(document).on('click', '.roles_permission_checkbox', function(e){
			_num = jQuery(this).data('num');
			_module = jQuery(this).data('module');
			_alert_shown = false;

			if(jQuery(this).is(':checked')){
				if(jQuery('#permission_fields_'+_num+'_'+_module).length > 0){
					jQuery('#permission_fields_'+_num+'_'+_module).addClass('show');
				}
				if(jQuery('#action_fields_'+_num+'_'+_module).length > 0){
					jQuery('#action_fields_'+_num+'_'+_module).addClass('show');
				}
			}else{

				if(jQuery('#permission_fields_'+_num+'_'+_module).length > 0 || jQuery('#action_fields_'+_num+'_'+_module).length > 0){
					if(confirm('All selected Data Permissions and Actions will be unselected. Do you really want to uncheck this?')){
						if(jQuery('#permission_fields_'+_num+'_'+_module).length > 0){
							jQuery('#permission_fields_'+_num+'_'+_module).removeClass('show');
							_checkboxes = jQuery('#permission_fields_'+_num+'_'+_module).find('.permission-fields').find('input');
							jQuery(_checkboxes).prop("checked", false);
							jQuery('#permission_fields_'+_num+'_'+_module).find('.pfb-check').text('Select All').removeClass('checked');
						}

						if(jQuery('#permission_fields_'+_num+'_'+_module).length > 0){
							jQuery('#action_fields_'+_num+'_'+_module).removeClass('show');
							_checkboxes = jQuery('#action_fields_'+_num+'_'+_module).find('.permission-fields').find('input');
							jQuery(_checkboxes).prop("checked", false);
							jQuery('#action_fields_'+_num+'_'+_module).find('.pfb-check').text('Select All').removeClass('checked');
						}
					}else{
						jQuery(this).prop("checked", true);
					}
				}
			}
		});
	});
	</script>