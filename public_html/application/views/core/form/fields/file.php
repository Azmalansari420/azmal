<div class="">

	<?php if(isset($attributes['value']) && $attributes['value'] != ''){?>
		<a class="file-view-link" href="<?php echo base_url()?><?php echo $attributes['value']?>" target="_blank"><b>View File <span class="ico">attachment<span></b></a>
		<label class="delete-label">Delete file <input type="checkbox" name="delete_<?php echo $attributes['name']?>" value="1" /></label>
		<input type="hidden" name="url_<?php echo $attributes['name']?>" value="<?php echo $attributes['value']?>" />
	<?php }?>
	<?php
	if(isset($attributes['value']) && $attributes['value'] == ''){
		unset($attributes['value']);
	}
	?>

	<input type="file" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id']?>" />

	<?php if(isset($attributes['upload_path']) && $attributes['upload_path'] != ''){?>
		<input type="hidden" name="<?php echo $attributes['id']?>_upload_path" value="<?php echo trim($attributes['upload_path'])?>" />
	<?php }else{?>
		<span class="error-text field-error">Field Error: upload_path is not defined!!</span>
	<?php }?>	

</div>
<?php if(isset($attributes['allowed_files']) && is_array($attributes['allowed_files'])){?>
	<?php //server validation ?>
	<input type="hidden" name="<?php echo $attributes['id']?>_allowed_file_ext" value="<?php echo implode("|", $attributes['allowed_files'])?>" />

	<script type="text/javascript">
	jQuery(function(){
		jQuery("#<?php echo $attributes['id']?>").on("change", function(e){
			_file_ext_<?php echo $attributes['id']?> = jQuery('#<?php echo $attributes['id']?>').val().split('.').pop().toLowerCase();
			if(jQuery.inArray(_file_ext_<?php echo $attributes['id']?>, <?php echo json_encode($attributes['allowed_files'])?>) == -1) {
				jQuery('#<?php echo $attributes['id']?>').val('');
				jQuery('#file_ext_error_<?php echo $attributes['id']?>').show();
			}else{
				jQuery('#file_ext_error_<?php echo $attributes['id']?>').hide();
			}
		});
	});
	</script>
	<span for="<?php echo $attributes['name']?>" id="file_ext_error_<?php echo $attributes['id']?>" class="text-danger" style="display:none; font-size:11px;">
	Invalid file type. Only "<?php echo implode(", ", $attributes['allowed_files'])?>" files are allowed.
	</span>
<?php }?>	