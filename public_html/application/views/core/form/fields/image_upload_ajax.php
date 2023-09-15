<?php
$base_media_path = "media/uploads/";
if(isset($attributes['upload_path'])){
	$base_media_path = ''; //image value have complete path
}
?>
<div class="row">
	<div class="col-lg-6">
		<div class="image-preview-container" id="image_preview_container_photo">
			<?php /*<span class="edit image-item-edit" data-id="<?php echo $attributes['id']?>"><span class="ico">create</span></span>*/?>
			<span class="close image-item-remove" id="close_<?php echo $attributes['id']?>" data-id="<?php echo $attributes['id']?>"><span class="ico">clear</span></span>
			<div class="image-preview-box" id="image_preview_<?php echo $attributes['id']?>">
				<?php if(array_key_exists("value", $attributes) && $attributes['value'] != ''){?>
					<img id="preview_image_<?php echo $attributes['id']?>" src="<?php echo base_url().$base_media_path?><?php echo $attributes['value']?>" />
				<?php }else{?>
					<img id="preview_image_<?php echo $attributes['id']?>" src="<?php echo base_url("assets/admin/images")?>/no-image.png" />
				<?php }?>
			</div>
			
			<div class="image-message" id="image_message_<?php echo $attributes['id']?>"></div>
			
			<input type="hidden" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id']?>" value="<?php echo (array_key_exists("value", $attributes) && $attributes['value'] != '') ? $attributes['value'] : ''?>" />

			<?php if(isset($attributes['sizes'])){?>
				<?php foreach($attributes['sizes'] as $size => $sizes){?>
					<input type="hidden" name="<?php echo $size?>" id="<?php echo $size?>" value="<?php echo (is_array($form_data) && array_key_exists($size, $form_data) && $form_data[$size] != '') ? $form_data[$size] : ''?>" />
				<?php }?>
			<?php }?>
			<br clear="all" />
		</div>
	</div>
	<?php if(!isset($attributes['view'])){?>
		<div class="col-lg-6">
			<span class="btn btn-primary image-upload-button btn-xs">
				<i class="ico">add_circle_outline</i>
				<span>Browse File...</span>
				<input id="image_<?php echo $attributes['id']?>" type="file" name="image_tmp_<?php echo $attributes['id']?>" />
			</span>
		</div>
	<?php }?>
</div>

<?php if(!isset($attributes['view'])){?>
	<script type="text/javascript">
	function upload_image_<?php echo $attributes['id']?>(){
		wd_loader(jQuery('#image_message_<?php echo $attributes['id']?>'));

		var _wd_form_<?php echo $attributes['id']?> = document.getElementById('frm');

		var _wd_form_data_<?php echo $attributes['id']?> = new FormData(_wd_form_<?php echo $attributes['id']?>);
		_wd_form_data_<?php echo $attributes['id']?>.set('tmp_file_name', 'image_tmp_<?php echo $attributes['id']?>');
		<?php if(isset($attributes['upload_path'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('upload_path', '<?php echo $attributes['upload_path']?>');
		<?php }?>
		<?php if(isset($attributes['upload_dir'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('upload_dir', '<?php echo $attributes['upload_dir']?>');
		<?php }?>
		<?php if(isset($attributes['allowed_types'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('allowed_types', '<?php echo $attributes['allowed_types']?>');
		<?php }?>

		<?php if(isset($attributes['sizes'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('image_sizes', '<?php echo json_encode($attributes['sizes'])?>');
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
				remove_wd_loader(jQuery('#image_message_<?php echo $attributes['id']?>'));
				if(data.status == 'success'){
					if(data.upload_dir != ''){
						jQuery('#<?php echo $attributes['id']?>').val(data.upload_dir+data.random_dir+data.main_file_name);
					}else{
						jQuery('#<?php echo $attributes['id']?>').val(data.upload_path+data.main_file_name);
					}
					if(data.resized_files){
						jQuery.each(data.resized_files, function(i, v){
							if(data.upload_dir != ''){
								jQuery('#'+i).val(data.upload_dir+data.random_dir+v);
							}else{
								jQuery('#'+i).val(data.upload_path+v);
							}	
						});
					}
					jQuery('#image_message_<?php echo $attributes['id']?>').addClass('success').html(data.message);
					window.setTimeout(function(){
						jQuery('#image_message_<?php echo $attributes['id']?>').html('').removeClass('success');
					}, 3000);
				}else{
					jQuery('#image_message_<?php echo $attributes['id']?>').addClass('error').html(data.message);
					window.setTimeout(function(){
						jQuery('#image_message_<?php echo $attributes['id']?>').html('').removeClass('error');
					}, 3000);
				}
			}
		});
	}

	jQuery(document).ready(function (e) {
		jQuery("#image_upload_button_<?php echo $attributes['id']?>").on('click',(function(e){
			e.preventDefault();
			
		}));

		// Function to preview image after validation
		jQuery(function(){
			jQuery("#image_<?php echo $attributes['id']?>").change(function() {
				jQuery('#image_message_<?php echo $attributes['id']?>').empty();
				var file = this.files[0];
				var imagefile = file.type;

				var allowed_file_types = false;
				<?php if(isset($attributes['allowed_types'])){?>
					allowed_file_types = ["<?php echo implode('","', file_type_mime_details($attributes['allowed_types']));?>"];
				<?php }?>

				wd_loader(jQuery('#image_message_<?php echo $attributes['id']?>'));

				if(jQuery.inArray(imagefile, allowed_file_types) === -1){
					jQuery('#preview_image_<?php echo $attributes['id']?>').attr('src','noimage.png');
					jQuery('#image_message_<?php echo $attributes['id']?>').addClass('error').html("<p id='error'>Invalid file type. Only <?php echo $attributes['allowed_types']?> types are allowed</p>");
					remove_wd_loader(jQuery('#image_message_<?php echo $attributes['id']?>'));
				 	return false;
				}else{
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
					remove_wd_loader(jQuery('#image_message_<?php echo $attributes['id']?>'));
				}
			});
		});

		function imageIsLoaded(e) {
			jQuery('#preview_image_<?php echo $attributes['id']?>').attr('src', e.target.result);
			jQuery('#preview_image_<?php echo $attributes['id']?>').attr('width', '250px');
			jQuery('#preview_image_<?php echo $attributes['id']?>').attr('height', '230px');

			upload_image_<?php echo $attributes['id']?>();
		};
	});
	</script>
<?php }?>	