<?php echo validation_errors(); ?>
<div class="form-tabs">
	
    <div class="wd-form">	
        <div class="tab-content">
            <div role="tabpanel" id="form_tab_1" class="tab-pane active">
                
                <div class="row">
                	<?php if($page_data){?>
	                	<div class="col-lg-5">
                        	<?php //print_r($page_data)?>
                            <?php if($page_data->image_original && $page_data->image_original != ''){?>
                            	<div style="width:377px; height:700px; overflow:auto">
                                	<img src="<?php echo media_url().$page_data->image_original?>" />
                                </div>
                            <?php }?>
                            <br />
                            <form method="post" id="image_form" name="image_form" enctype="multipart/form-data" action="<?php echo admin_url('pages/upload_image')?>">
                            	<input type="hidden" name="pages_id" id="pages_id" value="<?php echo $page_data->id?>" />
                                <div class="form-group row">
                                	<div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="file" name="website_image" id="website_image" class="required-entry" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div align="right" class="button_set">
                                            <button type="submit" class="btn btn-success pull-right btn-xs" onclick="if(!confirm('Are you sure?')){ return false; }">Upload</button>
                                            <div class="clear"></div>
                                        </div>
                                    </div>    
                                </div>
                            </form>
                        </div>
                    <?php }?>    
                    <div class="<?php if($page_data){?> col-lg-7<?php }else{?> col-lg-12<?php }?>">
                    	<form method="post" id="frm" name="frm" enctype="multipart/form-data">
                        	<input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id?>" />
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="page_type" class="col-sm-12 form-control-label">Page Type <span class="req">*</span></label>
                                    <div class="input-group">
                                        <select name="page_type" id="page_type" class="form-control required-entry" onchange="select_page_type(this)">
                                            <option value="">Select Page Type</option>
                                            <?php foreach($page_types as $page_type_data){?>
                                                <option <?php if(($page_type_to_use && $page_type_to_use->id != '' && $page_type_to_use->id == $page_type_data->id) || ($page_type && $page_type != '' && $page_type == $page_type_data->id )){?> selected="selected"<?php }?> value="<?php echo $page_type_data->id?>"><?php echo stripslashes($page_type_data->name)?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="title" class="col-sm-12 form-control-label">Title <span class="req">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control required-entry" id="title" value="<?php echo $title?>" name="title" />
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                	<?php if($page_data){?>
                                		<button class="btn btn-success btn-xs push-save-btn" type="button" onclick="push_save(this, 'title', '<?php echo $page_data->id?>')">Save</button>
                                    <?php }?>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="host" class="col-sm-12 form-control-label">Website <span class="req">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control required-entry" id="host" value="<?php echo $host?>" name="host" />
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                	<?php if($page_data){?>
                                		<button class="btn btn-success btn-xs push-save-btn" type="button" onclick="push_save(this, 'host', '<?php echo $page_data->id?>')">Save</button>
                                    <?php }?>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="url" class="col-sm-12 form-control-label">URL <span class="req">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control required-entry" id="url" value="<?php echo $url?>" name="url" />
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                	<?php if($page_data){?>
                                		<button class="btn btn-success btn-xs push-save-btn" type="button" onclick="push_save(this, 'url', '<?php echo $page_data->id?>')">Save</button>
                                    <?php }?>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="image_original" class="col-sm-12 form-control-label">Image <span class="req">*</span></label>
                                    <div class="input-group">
                                        <input type="file" name="image_original" id="image_original" class="required-entry" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="is_responsive_mobile" class="col-sm-12 form-control-label">Responsive </label>
                                    <div class="input-group">
                                        <select name="is_responsive_mobile" id="is_responsive_mobile" class="form-control">
                                            <option value="">Select Responsive/Mobile Theme</option>
                                            <option value="responsive">Responsive Theme</option>
                                            <option value="mobile">Mobile Theme</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                	<?php if($page_data){?>
                                		<button class="btn btn-success btn-xs push-save-btn" type="button" onclick="push_save(this, 'is_responsive_mobile', '<?php echo $page_data->id?>')">Save</button>
                                    <?php }?>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="colors" class="col-sm-12 form-control-label">Colors</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="colors" name="colors" value="<?php echo $colors;?>" />
                                        <p class="help-text">Enter comma (,) seperated colors</p>
                                        
                                        <div id="selected_colors">
                                            <ul id="make_sortable">
                                            	<?php if($page_data && $page_data->colors != ''){?>
                                                    <?php $selected_colors = explode(',', $page_data->colors)?>
                                                    <?php foreach($selected_colors as $color){?>
                                                        <?php if($color != ''){?>
                                                            <li rel="<?php echo $color?>">
                                                            	<span class="selected_color_item" rel="<?php echo $color?>" style="background-color:#<?php echo $color?>"></span>
                                                                <span class="rmo_clr" onclick="remove_clr('<?php echo $color?>')">x</span>
                                                            </li>
                                                        <?php }?>
                                                    <?php }?>
                                                <?php }?>    
                                            </ul>
                                        </div>
                                       
                                        <div class="color_codes" id="color_codes" style="clear:both; margin-top:5px;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                	<?php if($page_data){?>
                                		<button class="btn btn-success btn-xs push-save-btn" type="button" onclick="push_save(this, 'colors', '<?php echo $page_data->id?>')">Save</button>
                                    <?php }?>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <label for="is_half_page" class="col-sm-12 form-control-label">Is Half Page ?</label>
                                    <div class="input-group">
                                        <select name="is_half_page" id="is_half_page" class="form-control">
                                        	<option value="">Select is half or full page</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                	<?php if($page_data){?>
                                		<button class="btn btn-success btn-xs push-save-btn" type="button" onclick="push_save(this, 'is_half_page', '<?php echo $page_data->id?>')">Save</button>
                                    <?php }?>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div align="right" class="button_set">
                                        <?php if(!$page_data){?>
											<button id="wd_form_submit" class="btn btn-success btn-small" type="submit">Save</button>
                                        <?php }?>    
                                        <a class="btn btn-info btn-small" href="<?php echo admin_url('pages')?>">Back</a>
                                        <div class="clear"></div>
                                    </div>
                                </div>    
                            </div>
                        </form>    
                    </div>
                </div>
                
                <?php if($page_data){?>
	                <div class="row">
    	            	<div class="col-lg-12">
        	            	<h6>Visit Website</h6>
                            <a href="<?php echo clean_display($page_data->host)?>" target="_blank"><?php echo clean_display($page_data->host)?></a>
                            <br />
                            <br />
							<?php if($website_id && $website_id != ''){?>
                            	<script type="text/javascript">
								jQuery(function(){
									load_page_types('<?php echo $website_id?>');
								});	
								</script>
                            <?php }?>
							<div id="page_type_container"></div>
            	        </div>
                	</div>
                <?php }?>    
                
            </div>
        </div>
    </div>
</div>

<style>
.colors_grid{float:left; width:100%; margin-top:10px}
.colors_grid div{width:10%; border:2px solid #fff; display:block; float:left; height:20px; margin-right:5px; margin-bottom:5px; cursor:pointer;}
.colors_grid div:hover{border-color:inherit}

.push-save-btn{display:none; margin-top:30px; transition:0.3s all ease-in}
.form-group:hover .push-save-btn{display:block}

#make_sortable{list-style-type:none; margin:0; padding:0}
#make_sortable li{margin-bottom:0px; cursor:pointer; width:75px; height:15px;}
#make_sortable li .selected_color_item{display:inline-block; width:30px; height:15px;}

#page_type_container{}
#page_type_container ul{list-style-type:none; margin:0; padding:0}
#page_type_container ul li{float:left; margin:0 5px 5px 0; display:block;}
#page_type_container ul li a{display:block; padding:5px 10px; color:#333333; background-color:#eee}
#page_type_container ul li a:hover{background-color:#ddd; text-decoration:none}

#page_type_container ul.used-popup li{background-color:#66FF99; background-color:#66CCFF; color:#003300; display:block; padding:5px 10px; margin:0 5px 5px 0;}
</style>

<script type="text/javascript">
function load_page_types(website_id){
	
	jQuery.ajax({
		url: 'http://localhost/mplci/mplmanager/websites/index/load_page_types/',
		data: "website_id="+website_id,
		type: 'post',
		dataType: 'json',
		success: function(a){
			jQuery('#page_type_container').html(a.template);
		}
	});
}
</script>

<div class="modal fade" id="page_type_modal" tabindex="-1" role="dialog" aria-labelledby="page_typeLabel">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="page_typeLabel">Page Types</h4>
      		</div>
      		<div class="modal-body">
            	<div id="page_type_container">
                
                </div>
      		</div>
    	</div>
  	</div>
</div>

<script type="text/javascript">

jQuery(function(){
	<?php /*?><?php if($page_data && $page_data->colors != ''){?>
		jQuery("#make_sortable").sortable({
			axis: 'y',
			stop: function (event, ui) {
				s_order = [];
				a_colors = jQuery('#colors').val();
				c = a_colors.split(',');
				jQuery("#make_sortable li").each(function(){
					var color = jQuery(this).attr("rel");
					s_order.push(color);
				});
				s_colors = s_order;
				jQuery.each(c, function(n,r){
					if(jQuery.inArray(r, s_order) == -1){
						s_colors.push(r);
					}
				});
				jQuery('#colors').val(s_order);
			}		
		});
	<?php }?><?php */?>
	colors_sorter();
});

function make_colors_sortable(){
	if(jQuery('#colors').val() != ''){
		_colors = jQuery('#colors').val().split(',');
		_colors_html = '';
		jQuery.each(_colors, function(r,j){
			_colors_html += '<li rel="'+j+'">';
				_colors_html += '<span class="selected_color_item" rel="'+j+'" style="background-color:#'+j+'"></span>';
				_colors_html += '<span class="rmo_clr" onclick="remove_clr("\"'+j+'\"")">x</span>';
			_colors_html += '</li>';
		});
		jQuery("#make_sortable").html(_colors_html);
		colors_sorter();
	}
}

function colors_sorter(){
	jQuery("#make_sortable").sortable({
		axis: 'y',
		stop: function (event, ui) {
			s_order = [];
			a_colors = jQuery('#colors').val();
			c = a_colors.split(',');
			jQuery("#make_sortable li").each(function(){
				var color = jQuery(this).attr("rel");
				s_order.push(color);
			});
			s_colors = s_order;
			jQuery.each(c, function(n,r){
				if(jQuery.inArray(r, s_order) == -1){
					s_colors.push(r);
				}
			});
			jQuery('#colors').val(s_order);
		}		
	});
}

function select_page_type(i){
	if(jQuery(i).val() != ''){
		
	}
}

function fill_colors(color){
	a_colors = jQuery('#colors').val();
	
	if(a_colors != ''){
		c = a_colors.split(',');
		if(color != ''){
			if(jQuery.inArray(color, c) == -1){
				c.push(color);
			}
			jQuery('#colors').val(c);
		}
	}else{
		if(color != ''){
			jQuery('#colors').val(color);
		}
	}	
	
}
function remove_clr(color){
	if(confirm('Do you really want to remove this color?')){
		
		a_colors = jQuery('#colors').val();
		c = a_colors.split(',');
		
		n_colors = [];
		jQuery.each(c, function(a,b){
			if(b != ''){
				if(b != color){
					n_colors.push(b);
				}
			}
		});
		jQuery('.'+color).remove();
		jQuery('#colors').val(n_colors);
	}
}

is_saving = false;

function push_save(it, field, id){
	_e = jQuery('#'+field);
	if(_e.hasClass('required-entry') && _e.val() == ''){
		alert("Value is required!");
		return false;
	}
	
	if(is_saving){
		return false;
	}
	
	_e_val = _e.val();
	_e_field_name = _e.attr('name');
	
	is_saving = true;
	
	jQuery(it).addClass('btn-danger');
	jQuery(it).text('Saving');
	jQuery.ajax({
		url: '<?php echo admin_url()?>pages/index/push_save/'+id,
		dataType: 'json',
		data: 'field='+_e_field_name+'&value='+_e_val,
		type: 'post',
		success: function(e){
			jQuery(it).addClass('btn-warning').removeClass('btn-danger');
			jQuery(it).text('Saved');
			window.setTimeout(function(){
				jQuery(it).removeClass('btn-warning');
				jQuery(it).text('Save');
				is_saving = false;
				
				if(field == 'colors'){
					//make sortable
					make_colors_sortable();
				}
				
			}, 1000);
		}
	});
}

<?php if($page_data){?>
	jQuery(function(){
		jQuery.ajax({
			url: '<?php echo admin_url()?>pages/index/color_codes/<?php echo $page_data->id?>',
			dataType: 'json',
			success: function(e){
				if(e.status = 'success'){
					html = '<div class="colors_grid">';
						jQuery.each(e.colors, function(a,b){
							html += '<div onclick="fill_colors(\''+b+'\')" style="background-color:#'+b+';"></div>';
						});
					html += '</div>';
					jQuery('#color_codes').html(html);
				}
			}
		});
	});
<?php }?>

jQuery(function(){
	jQuery('#frm').validate();
	if(jQuery('#image_form').length){
		jQuery('#image_form').validate();
	}	
});
</script>