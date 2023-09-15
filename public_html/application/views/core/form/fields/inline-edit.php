<?php
if(!isset($attributes['row_id']) || $attributes['row_id'] == ''){
	echo "<span class='text-danger'><span class='ico'>error</span> 'row_id' attribute missing or empty for field '".$attributes['name']."' !!</span>";
	return;
}
if(!isset($attributes['table_name']) || $attributes['table_name'] == ''){
	echo "<span class='text-danger'><span class='ico'>error</span> 'table_name' attribute missing or empty for field '".$attributes['name']."' !!</span>";
	return;
}
if(!isset($attributes['table_index']) || $attributes['table_index'] == ''){
	echo "<span class='text-danger'><span class='ico'>error</span> 'table_index' attribute missing or empty for field '".$attributes['name']."' !!</span>";
	return;
}

?>
<div class="">
	<label class="col-sm-12 form-control-label">
		<?php echo $attributes['label']?>
		<button type="button" class="pull-lg-right btn btn-xs btn-info" id="in_edit_<?php echo $attributes['id']?>">Edit</button>
		<div class="btn-group pull-lg-right" style="display:none" id="in_edit_btngrp_<?php echo $attributes['id']?>">
			<button type="button" class="btn btn-xs btn-warning" id="in_edit_cancel_<?php echo $attributes['id']?>">Cancel</button>
			<button type="button" class="btn btn-xs btn-success" id="in_edit_save_<?php echo $attributes['id']?>">Save</button>
		</div>	
	</label>
	<?php
	if(isset($form_data[$element])){
		$attributes['value'] = isset($form_data[$element]) ? clean_display($form_data[$element]) : "";
	}
	
	if($attributes['type'] == 'view'){
		if(array_key_exists("value", $attributes) && $attributes['value'] != ''){
			if(array_key_exists('options', $attributes) && array_key_exists($attributes['value'], $attributes['options'])){
				echo '<div id="in_view_'.($attributes['id']).'" class="view">'.clean_display($attributes['options'][$attributes['value']]).'</div>';
			}else{
				echo '<div id="in_view_'.($attributes['id']).'" class="view">'.clean_display($attributes['value']).'</div>';
			}
		}
	}	
	?>
	
	<input type="text" name="in_edit_field_<?php echo $attributes['id']?>" id="in_edit_field_<?php echo $attributes['id']?>" disabled="disabled" style="display:none" class="form-control" value="<?php echo (isset($attributes['value'])) ? $attributes['value'] : ''?>" rel="<?php echo trim($attributes['name'])?>" />
	
</div>
<script type="text/javascript">
jQuery(function(){
	jQuery("#in_edit_<?php echo $attributes['id']?>").on('click', function(){

		jQuery('#wd_form_submit').attr('disabled', 'disabled');
		
		if(jQuery("#loader_<?php echo $attributes['id']?>").length > 0){
			jQuery("#loader_<?php echo $attributes['id']?>").remove();
		}

		jQuery("#in_view_<?php echo $attributes['id']?>").hide();
		
		jQuery('#in_edit_btngrp_<?php echo $attributes['id']?>').show();
		jQuery("#in_edit_<?php echo $attributes['id']?>").hide();

		var validation_classes = false;	
		<?php if(isset($attributes['validation']) && $attributes['validation'] != ''){?>
			<?php $validation_classes = validation_classes($attributes);?>
			<?php if(count($validation_classes)){?>
				validation_classes = '<?php echo implode(" ", $validation_classes)?>';
				jQuery('#in_edit_field_<?php echo $attributes['id']?>').addClass(validation_classes);
			<?php }?>	
		<?php }?>
		
		jQuery('#in_edit_field_<?php echo $attributes['id']?>').show().removeAttr('disabled').focus();
		
	});

	jQuery('#in_edit_cancel_<?php echo $attributes['id']?>').on('click', function(){

		jQuery('#wd_form_submit').removeAttr('disabled');
		
		if(jQuery("#loader_<?php echo $attributes['id']?>").length > 0){
			jQuery("#loader_<?php echo $attributes['id']?>").remove();
		}

		jQuery("#in_view_<?php echo $attributes['id']?>").show();
		
		jQuery('#in_edit_btngrp_<?php echo $attributes['id']?>').hide();
		jQuery("#in_edit_<?php echo $attributes['id']?>").show();

		jQuery('#in_edit_field_<?php echo $attributes['id']?>').hide().attr('disabled', 'disabled');
	});

	jQuery('#in_edit_save_<?php echo $attributes['id']?>').on('click', function(e){
		if(jQuery('#in_edit_field_<?php echo $attributes['id']?>').valid()){
			inline_edit("<?php echo $attributes['table_name']?>", "<?php echo $attributes['table_index']?>", "<?php echo $attributes['row_id']?>", "<?php echo $attributes['id']?>");		
		}
		e.preventDefault();
	});
});
</script>