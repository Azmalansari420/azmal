<?php
$file_name = '';
$file_extension = '';
if(isset($attributes['value']) && $attributes['value'] != ''){
	$file_details = pathinfo($attributes['value']);
	$file_extension = $file_details['extension'];
	$file_name = $file_details['basename'];
}
?>	
<div class="row">
	<div class="col-lg-4">
		<div class="upload-container clearer" id="upload_container_<?php echo $attributes['id']?>">
			<?php if(!isset($attributes['view'])){?>
				<span class="btn btn-primary file-upload-button btn-xs">
					<span>Browse File...</span>
					<input id="file_<?php echo $attributes['id']?>" type="file" name="file_tmp_<?php echo $attributes['id']?>" />
				</span>
			<?php }?>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="file-preview-container">
			<div class="file-preview-block">
				<span class="remove-file"><i class="ico">clear</i></span>
				<a id="uploaded_file_link_<?php echo $attributes['id']?>" href="<?php echo (isset($attributes['value']) && $attributes['value'] != '') ? media_url()."uploads/".$attributes['value'] : ''?>" target="_blank">
					<div class="file-icon <?php if(isset($attributes['value']) && $attributes['value'] != ''){?>show<?php }?>" id="file_icon_<?php echo $attributes['id']?>">
						<span class="file-flip"></span>
						<span class="ext"><?php echo $file_extension?></span>
					</div>
					<span class="file-name" id="file_name_<?php echo $attributes['id']?>"><?php echo $file_name?></span>
				</a>
			</div>	
		</div>

		<div class="image-message clearer" id="file_message_<?php echo $attributes['id']?>"></div>

		<input type="hidden" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id']?>" value="<?php echo (array_key_exists("value", $attributes) && $attributes['value'] != '') ? $attributes['value'] : ''?>" />
	</div>
</div>

<?php if(!isset($attributes['view'])){?>
	<script type="text/javascript">
	jQuery(document).ready(function (e) {
		jQuery(function(){
			jQuery("#file_<?php echo $attributes['id']?>").change(function() {
				jQuery('#file_message_<?php echo $attributes['id']?>').removeClass("error").removeClass("success").empty();
				var file = this.files[0];
				var imagefile = file.type;
				var allowed_file_types = false;
				<?php if(isset($attributes['allowed_types'])){?>
					allowed_file_types = ["<?php echo implode('","', file_type_mime_details($attributes['allowed_types']));?>"];
				<?php }?>

				if(jQuery.inArray(imagefile, allowed_file_types) === -1){
				 	jQuery('#file_message_<?php echo $attributes['id']?>').addClass('error').html("<p id='error'>Invalid file type. Only <?php echo $attributes['allowed_types']?> types are allowed</p>");
				 	return false;
				}else{

					wd_loader(jQuery('#upload_container_<?php echo $attributes['id']?>'));

					var _wd_form_<?php echo $attributes['id']?> = document.getElementById('frm');

					var _wd_form_data_<?php echo $attributes['id']?> = new FormData(_wd_form_<?php echo $attributes['id']?>);
					_wd_form_data_<?php echo $attributes['id']?>.set('tmp_file_name', 'file_tmp_<?php echo $attributes['id']?>');
					_wd_form_data_<?php echo $attributes['id']?>.set('upload_dir', '<?php echo $attributes['upload_path']?>');
					<?php if(isset($attributes['allowed_types'])){?>
						_wd_form_data_<?php echo $attributes['id']?>.set('allowed_types', '<?php echo $attributes['allowed_types']?>');
					<?php }?>
			
					jQuery.ajax({
						url: "<?php echo base_url("imageuploader/index")?>",
						type: "POST",
						data: _wd_form_data_<?php echo $attributes['id']?>,
						contentType: false,
						dataType: 'json',
						cache: false,
						processData:false,
						success: function(data){
							remove_wd_loader(jQuery('#upload_container_<?php echo $attributes['id']?>'));
							if(data.status == 'success'){
								jQuery('#file_icon_<?php echo $attributes['id']?>').addClass('show').find('.ext').text(data.extension);
								jQuery('#file_message_<?php echo $attributes['id']?>').removeClass("error").addClass('success').html(data.message);
								jQuery('#<?php echo $attributes['id']?>').val('<?php echo $attributes['upload_path']?>'+data.random_dir+data.main_file_name);
								jQuery('#file_name_<?php echo $attributes['id']?>').text(data.main_file_name);
								jQuery('#uploaded_file_link_<?php echo $attributes['id']?>').removeAttr('href');
								window.setTimeout(function(){
									jQuery('#file_message_<?php echo $attributes['id']?>').removeClass("error").removeClass('success').empty().fadeOut();
								}, 2000);
							}else{
								jQuery('#file_message_<?php echo $attributes['id']?>').removeClass("success").addClass('error').html(data.message);
							}
						}
					});
				}
			});
		});
	});
	</script>
<?php }?>	