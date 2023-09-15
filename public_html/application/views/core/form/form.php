<?php if(validation_errors()){?>
	<div class="form-errors">
		<?php echo validation_errors('<div class="error">', '</div>'); ?>
	</div>	
<?php }?>

<?php if(isset($before_form_html) && $before_form_html != ''){?>
	<?php echo $before_form_html;?>
<?php }?>
	
<?php if(isset($before_buttons) && $before_buttons!=''){?>
	<div class="btn-row before-btn">
		<div class="btn-group">
			<?php foreach($before_buttons as $before_button){?>
				<a href="<?php echo $before_button['link']?>" class="btn <?php echo isset($before_button['class']) ? $before_button['class'] : 'btn-info'?> btn-small"><?php echo $before_button['label']?></a>
			<?php }?>
		</div>
	</div>            
<?php }?>

<div class="form-tabs">        
	<form class="wd-form" name="frm" id="frm" method="post" enctype="multipart/form-data" <?php if(isset($post_url) && $post_url != ''){?> action="<?php echo $post_url?>"<?php }?>>
        <ul class="nav nav-tabs" role="tablist">
            <?php $n = 0; foreach($form_sections as $section => $elements){ $n++; ?>
                
                <li class="nav-item" id="tab_head_<?php echo $n?>">
                    <a class="nav-link <?php echo ($n==1) ? 'active' : ''?>" data-toggle="tab" href="#form_tab_<?php echo $n?>" role="tab">
                        <i class="ico">fiber_manual_record</i>
                        <?php echo $section?>
                    </a>
                </li>
                
            <?php }?>
        </ul>
        
        <div class="tab-content">
            <?php $form_fields_js_validations = array();?>
            
            <?php $th = 0; foreach($form_sections as $section => $elements){ $th++; ?>
                <div class="tab-pane <?php echo ($th==1) ? 'active' : ''?>" id="form_tab_<?php echo $th?>" role="tabpanel" rel="tab_head_<?php echo $th?>">
                    
					<div class="row">
					<?php $add_f = 0;?>
						<?php foreach($elements as $cols => $element_items){?>
							
							<?php if(isset($element_items['type']) && $element_items['type']=='hidden'){?>
								<?php
								$value = '';
								if(isset($form_data[$element_items['name']])){
									$value = isset($form_data[$element_items['name']]) ? clean_display($form_data[$element_items['name']]) : "";
								}elseif(array_key_exists('value', $element_items)){
									$value = $element_items['value'];
								}
								?>
								<input type="hidden" name="<?php echo $element_items['name'] ?>" id="<?php echo isset($element_items['id']) ? $element_items['id'] : $element_items['name']?>" value="<?php echo $value?>" />
							<?php }elseif(isset($element_items['type']) && $element_items['type']=='fieldset'){?>
								<div class="fieldset-row form-group <?php echo (array_key_exists("row_class", $element_items)) ? $element_items['row_class'] : ''?>">
									<div class="col-sm-12">
										<h4 class="fieldset"><span><?php echo $element_items['label']?></span></h4>
									</div>
								</div>
							<?php }elseif(isset($element_items['type']) && $element_items['type']=='separator'){?>
								<div class="separator-row form-group <?php echo (array_key_exists("row_class", $element_items)) ? $element_items['row_class'] : ''?>">
									<div class="col-sm-12">
										<div class="separator"></div>
									</div>
								</div>	    
							<?php }elseif(isset($element_items['type']) && $element_items['type']=='html'){?>
								<?php if(isset($element_items['html'])){?>
									<?php echo $element_items['html']?>
								<?php }?>
							<?php }else{?>
							
								<?php if(array_key_exists('fields', $element_items)){?>
									
									<?php $_attributes = $element_items;?>
									
									<?php foreach($element_items['fields'] as $element_item){?>
										<?php
										//js validations
										$validations = field_validations($element_item);
										if($validations != false){
											$form_fields_js_validations[$element_item['name']] = $validations;
										}
										?>
									<?php }?>
									
									<?php //if(count($_attributes)>0){?>
										<?php include "form_row.php";?>
									<?php //}?>
									
								<?php }else{?>
									<?php
									$_attributes = $element_items;
									//js validations
									$validations = field_validations($_attributes);
									if($validations != false){
										$_attribute_field_name = $_attributes['name'];
										$form_fields_js_validations[$_attribute_field_name] = $validations;
									}
									
									/*if(count($_attributes)>0){
										include "form_row.php";
									}*/
									?>
									
									<?php if(count($_attributes)>0){ $add_f++;?>
										<div class="<?php if($_attributes['type']=='texteditor' || $_attributes['type']=='texteditor' || (isset($_attributes['class']) && $_attributes['class']=='col-sm-12')){ echo 'col-sm-12';}else{ echo 'col-sm-6';}?>">									
											<?php include "form_row.php"; ?>
										</div>
									<?php }?>
									
								<?php }?>
							<?php }?>    
							<?php 
								if($add_f%2 == 0){ 
								echo '</div> <div class="row">';
							}?>
						<?php }?>
					</div>
                </div>
            <?php }?>
            
            <div class="button_set" id="wd_form_button_set" align="right">
                <?php if($save_link){?>
                    <button class="btn btn-success btn-sm" id="wd_form_submit"><?php echo (isset($form_data[$remove_index])) ? $update_title : $save_title?></button>
                <?php }?>
                <?php if(isset($cancel_link) && $cancel_link!=''){?>
                    <a href="<?php echo $cancel_link?>" class="btn btn-info btn-sm form-back-link"><?php echo $cancel_title?></a>
                <?php }?>
                                
                <?php if(isset($remove_index) && isset($form_data[$remove_index])){?>
                    <?php $remove_callback = (isset($remove_js_callback) && $remove_js_callback != '') ? $remove_js_callback : "if(!confirm('Do you really want to remove this data!!.')){return false;}" ;?>
                    <a href="<?php echo $remove_link?><?php echo $form_data[$remove_index]?>" onclick="<?php echo $remove_callback?>" class="btn btn-danger btn-sm">Remove</a>	
                <?php }?>
                            
                <?php if(isset($buttons) && $buttons!=''){?>
                    <?php foreach($buttons as $button){?>
                        <a 
                        	<?php echo (array_key_exists('target', $button)) ? "target='{$button['target']}'" : ''?>
                        	href="<?php echo $button['link']?>"
                        	class="btn btn-info btn-sm <?php echo (array_key_exists('class', $button)) ? $button['class'] : ''?>">
                        	<?php echo $button['label']?>
                        </a>
                    <?php }?>
                <?php }?>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
jQuery(function(){
	jQuery.each(jQuery('.fieldset-row'), function(i, _e){
		_n_element = jQuery(_e).next();
		if(!jQuery(_n_element).hasClass('fieldset-row')){
			
			if(jQuery(_n_element).children().size() == 0){
				jQuery(_e).hide();
			}
		}else{
			jQuery(_e).hide();
		}
	});
});
</script>

<?php if(isset($pop_form)){?>
	<script type="text/javascript">
	var wd_form = new WdForm('<?php echo admin_url()?>');
	jQuery(function(){
		jQuery(document).on('click', '.add-new-form-button', function(e){
			e.preventDefault();
			wd_form.create();
		});

		jQuery(document).on('click', '.form-back-link', function(e){
			e.preventDefault();
			wd_form.hide();
		});

		jQuery(document).on('click', '.wd-popup-form-edit', function(e){
			e.preventDefault();
			_id = jQuery(this).attr('data-index');
			wd_form.edit('<?php echo $form_data_url?>', _id);
		});

	    _url = window.location.href;
		if(_url.indexOf('#') > -1){
			_url_parts = _url.split('#')[1];
			if(_url_parts.indexOf('/') > -1){
				_id = _url_parts.split('/')[1];
				wd_form.edit('<?php echo $form_data_url?>', _id);
			}else{
				wd_form.create();
			}
		}
	});
	</script>
<?php }?>

<?php if(isset($js)){?>
	<?php foreach($js as $_js){?>
    	<script type="text/javascript" src="<?php add_js($_js)?>"></script>
    <?php }?>
<?php }?>

<?php
$ci =& get_instance();
$url = implode("/", $ci->uri->segment_array());
$url . "/" . $ci->router->class . "/" . $this->router->method;
?>

<script type="text/javascript">
var _controller_name = '<?php echo (isset($post_url) && ($post_url != '')) ? $post_url : ''?>';
jQuery(function(){
	jQuery('.help-block-pop').closest('.form-group').addClass('has-error');
	
	jQuery("#frm").validate({
		ignore: "",
		rules: {
			<?php foreach($form_fields_js_validations as $field => $vals){?>
				<?php if(!stristr($field, '[]')){?>
					<?php echo $field." : " .str_replace('"', "", $vals).",\r"?>
				<?php }?>
			<?php }?>
		},	
		highlight: function(element) {
  	        jQuery(element).closest('.form-group').addClass('has-error');
			jQuery(element).addClass('form-control-error');
			
			jQuery('#'+''+jQuery(element).closest('.tab-pane').attr('rel')+'').addClass('error');
			
			_tab_panes = jQuery('.wd-form .tab-content .tab-pane').length; 
			for(i = _tab_panes; i >= 0; i--){
				_tab = jQuery('#'+'form_tab_'+''+i);
			//jQuery.each(, function(n,r){
				_tab_pane_errors = jQuery(_tab).find('.form-group.has-error');
				_head_panel = jQuery(_tab).attr('rel');
				_head_a = jQuery('#'+''+_head_panel+'').find('a').attr('href');
				if(_tab_pane_errors.length > 0){
					jQuery('.nav-tabs a[href="'+_head_a+'"]').tab('show');
				}
			}
    	},
		unhighlight: function(element) {
			jQuery(element).removeClass('form-control-error');	
			_parent_form_group = jQuery(element).closest('.form-group');
            _child_error_elements = jQuery(_parent_form_group).find('.form-control-error');
            if(_child_error_elements.length == 0){
            	jQuery(element).closest('.form-group').removeClass('has-error');
			}
			//jQuery('#'+jQuery(element).closest('.tab-pane').attr('rel')+'').removeClass('error');
    	},
		errorElement: 'span',
    	errorClass: 'help-block help-block-pop',
    	errorPlacement: function(error, element) {
    		
			element.closest('.input-group').append(error);
			/*if(element.parent('.input-group').length) {
    			error.insertAfter(element.parent());
    	    } else {
    	        error.insertAfter(element);
    	    }*/
       	},
		<?php if(isset($pop_form) && $pop_form == true){?>
			submitHandler: function(form){
				wd_form.save();
				return false;
			}
		<?php }?>	
	});
	
	jQuery.validator.addMethod("alpha_space", function(value, element) {
		regex = /^[a-zA-Z\s]+$/;
  		return this.optional( element ) || regex.test( value );
	}, 'Please enter valid alphabets and white space only.');

	jQuery.validator.addMethod("alphabet", function(value, element) {
		regex = /^[a-zA-Z]+$/;
  		return this.optional( element ) || regex.test( value );
	}, 'Please enter valid alphabets. White space not allowed');
	
	jQuery.validator.addMethod("alpha_dash", function(value, element) {
		regex = /^[a-zA-Z\-\_]+$/;
  		return this.optional( element ) || regex.test( value );
	}, 'Please enter valid alphabets, -, _ only. White space not allowed');
	
	jQuery.validator.addMethod("alpha_underscore", function(value, element) {
		regex = /^[a-z\_]+$/;
  		return this.optional( element ) || regex.test( value );
	}, 'Please enter valid alphabets, _ only.<br />White space not allowed.<br />Please enter lowercase only.');
	
	jQuery.validator.addMethod("alpha_numeric", function(value, element) {
		regex = /^[a-zA-Z0-9]+$/;
  		return this.optional( element ) || regex.test( value );
	}, 'Please enter valid alphabets and numbers only. White space not allowed');

	jQuery.validator.addMethod("alpha_numeric_space", function(value, element) {
		regex = /^[a-zA-Z0-9\s]+$/;
  		return this.optional( element ) || regex.test( value );
	}, 'Please enter valid alphabets, numbers and white spaces only.');
	
});
</script>