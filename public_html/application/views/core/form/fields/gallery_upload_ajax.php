<?php
$base_media_path = "media/uploads/";
if(isset($attributes['upload_path'])){
	$base_media_path = ''; //image value have complete path
}
$allow_youtube_video = false;

if(isset($attributes['allow_youtube_video'])){
	$this->load->helper('other_helper');
	$allow_youtube_video = true;
}?>
<div class="row">
	<div class="col-lg-12">
		<div class="wd-gallery-manager">
			<div class="gallery-preview-section-<?php echo $attributes['id']?>">
				<?php
				//json_decode(stripslashes(html_entity_decode($attributes['value'])), true)
				?>
				<?php $gallery_items = ($attributes['value'] != '') ? json_decode(html_entity_decode(trim(rtrim($attributes['value'], "\0"))), true) : [];?>

				<?php if(is_array($gallery_items) && count($gallery_items) > 0){?>
					<?php foreach($gallery_items as $k => $v){?>
						<div class="image-preview-container" id="image_preview_container_<?php echo $attributes['id']?>_<?php echo $k;?>">
							
							<?php if(array_key_exists("file", $v)){?>
								<span class="edit gallery-item-edit" data-id="<?php echo $attributes['id']?>" data-count="<?php echo $k;?>"><span class="ico">create</span></span>
							<?php }?>
							<span class="close gallery-item-remove" id="close_<?php echo $attributes['id']?>_<?php echo $k;?>" data-id="<?php echo $attributes['id']?>" data-count="<?php echo $k;?>"><span class="ico">clear</span></span>
							<div class="image-preview-box" id="image_preview_<?php echo $attributes['id']?>_<?php echo $k;?>">
								<?php if(array_key_exists("file", $v)){?>
									<img id="preview_image_<?php echo $attributes['id']?>_<?php echo $k;?>" src="<?php echo base_url().$base_media_path?><?php echo $v['file']?>" />
								<?php }elseif(array_key_exists("url", $v)){
									$youtube_url = $v['url'];
									$youtube_id = youtube_id($youtube_url);
									$thumb = 'https://img.youtube.com/vi/'.$youtube_id.'/0.jpg';?>
									<img id="preview_image_<?php echo $attributes['id']?>_<?php echo $k;?>" src="<?php echo $thumb?>" />
								<?php }else{?>
									<img id="preview_image_<?php echo $attributes['id']?>_<?php echo $k;?>" src="<?php echo base_url("assets/admin/images")?>/no-image.png" />
								<?php }?>
							</div>
							<div class="image-message" id="image_message_<?php echo $attributes['id']?>_<?php echo $k;?>"></div>
							<br clear="all" />
						</div>
					<?php }
				}?>
			</div>

			<?php if(!isset($attributes['view'])){?>
				<span class="btn btn-primary image-upload-button btn-xs" id="image_<?php echo $attributes['id']?>_button">
					<i class="ico">add_circle_outline</i>
					<span>Browse File...</span>
					<input id="image_<?php echo $attributes['id']?>" type="file" name="image_tmp_<?php echo $attributes['id']?>" />
				</span>

				<?php if($allow_youtube_video){?>
					<span class="btn btn-primary image-upload-button video-upload-button btn-xs" id="video_<?php echo $attributes['id']?>_button" data-modal="youtube_video_modal_<?php echo $attributes['id']?>">
						<i class="ico">add_circle_outline</i>
						<span>Upload Youtube Video</span>
					</span>
				<?php }?>
			<?php }?>
		</div>	
	</div>
</div>

<div class="modal fade" id="youtube_video_modal_<?php echo $attributes['id']?>" tabindex="-1" role="dialog" aria-labelledby="youtube_video_modal_<?php echo $attributes['id']?>Label" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="youtube_video_modal_<?php echo $attributes['id']?>Label">Upload Youtube Video</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
         	 		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group row">
							<div class="col-lg-12">
								<label class="col-sm-12 form-control-label" for="title">Enter Youtube Url</label>
								<div class="input-group text">
									<input name="youtube_video_url_<?php echo $attributes['id']?>" id="youtube_video_url_<?php echo $attributes['id']?>" class="form-control" type="text">
								</div>
							</div>	
						</div>
					</div>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
        		<button type="button" class="btn btn-primary btn-xs upload-video" onclick="_<?php echo $attributes['id']?>_save_youtube_url_();">Save</button>
      		</div>
    	</div>
  	</div>
</div>

<textarea style="display:none !important" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id']?>"><?php echo (array_key_exists("value", $attributes) && $attributes['value'] != '') ? stripslashes(html_entity_decode($attributes['value'])) : ''?></textarea>

<div class="modal fade" id="gallery_caption_modal_<?php echo $attributes['id']?>" tabindex="-1" role="dialog" aria-labelledby="gallery_caption_modal_<?php echo $attributes['id']?>Label" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="gallery_caption_modal_<?php echo $attributes['id']?>Label">Edit Image</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
         	 		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
				<div class="row">
					<div class="col-lg-6">
						<img id="gallery_edit_image_<?php echo $attributes['id']?>" class="img-fluid" />
					</div>
					<div class="col-lg-6">
						<div class="form-group row">
							<div class="col-lg-12">
								<label class="col-sm-12 form-control-label" for="title">Alt Text</label>
								<div class="input-group text">
									<input name="gallery_image_alt_<?php echo $attributes['id']?>" id="gallery_image_alt_<?php echo $attributes['id']?>" class="form-control" type="text">
								</div>
							</div>	
						</div>
						<div class="form-group row">
							<div class="col-lg-12">
								<label class="col-sm-12 form-control-label" for="title">Caption</label>
								<div class="input-group text">
									<input name="gallery_image_caption_<?php echo $attributes['id']?>" id="gallery_image_caption_<?php echo $attributes['id']?>" class="form-control" type="text">
								</div>
							</div>	
						</div>
					</div>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
        		<button type="button" class="btn btn-primary btn-xs" onclick="_<?php echo $attributes['id']?>_save_gallery_caption_();">Save changes</button>
      		</div>
    	</div>
  	</div>
</div>

<?php if(!isset($attributes['view'])){?>
	<script type="text/javascript">

	// Image Upload
	jQuery(function(){
		jQuery(document).on('click', '.gallery-item-edit', function(){
			_count_n = jQuery(this).attr('data-count');
			jQuery('#gallery_caption_modal_<?php echo $attributes['id']?>').modal({backdrop: 'static', keyboard: false});
			_gal_img_src = jQuery('#preview_image_<?php echo $attributes['id']?>_'+_count_n).attr('src');
			jQuery('#gallery_edit_image_<?php echo $attributes['id']?>').attr('src', _gal_img_src);
			if(_count_n in _<?php echo $attributes['id']?>_data){
				if('alt' in _<?php echo $attributes['id']?>_data[_count_n]){
					jQuery('#gallery_image_alt_<?php echo $attributes['id']?>').val(_<?php echo $attributes['id']?>_data[_count_n]['alt']);
				}
				if('caption' in _<?php echo $attributes['id']?>_data[_count_n]){
					jQuery('#gallery_image_caption_<?php echo $attributes['id']?>').val(_<?php echo $attributes['id']?>_data[_count_n]['caption']);
				}
			}	
		});

		jQuery('#gallery_caption_modal_<?php echo $attributes['id']?>').on('hidden.bs.modal', function (e) {
			_count_n = false;
			jQuery('#gallery_image_alt_<?php echo $attributes['id']?>').val('');
			jQuery('#gallery_image_caption_<?php echo $attributes['id']?>').val('');
		});
	});

	function _<?php echo $attributes['id']?>_save_gallery_caption_(){
		_alt_text = jQuery('#gallery_image_alt_<?php echo $attributes['id']?>').val();
		_caption_text = jQuery('#gallery_image_caption_<?php echo $attributes['id']?>').val();
		if(_count_n in _<?php echo $attributes['id']?>_data){
			_file_obj_text = _<?php echo $attributes['id']?>_data[_count_n]['file'];
			_alt_obj_text = '';
			_caption_obj_text = '';
			_caption_thumbs = '';
			_caption_size = '';
			_caption_width = '';
			_caption_height = '';

			if('alt' in _<?php echo $attributes['id']?>_data[_count_n]){
				_alt_obj_text = _<?php echo $attributes['id']?>_data[_count_n]['alt'];
			}
			if('caption' in _<?php echo $attributes['id']?>_data[_count_n]){
				_caption_obj_text = _<?php echo $attributes['id']?>_data[_count_n]['caption'];
			}
			if('thumbs' in _<?php echo $attributes['id']?>_data[_count_n]){
				_caption_thumbs = _<?php echo $attributes['id']?>_data[_count_n]['thumbs'];
			}
			if('size' in _<?php echo $attributes['id']?>_data[_count_n]){
				_caption_size = _<?php echo $attributes['id']?>_data[_count_n]['size'];
			}
			if('width' in _<?php echo $attributes['id']?>_data[_count_n]){
				_caption_width = _<?php echo $attributes['id']?>_data[_count_n]['width'];
			}
			if('height' in _<?php echo $attributes['id']?>_data[_count_n]){
				_caption_height = _<?php echo $attributes['id']?>_data[_count_n]['height'];
			}

			_alt_obj_text = _alt_text;
			_caption_obj_text = _caption_text;
			_<?php echo $attributes['id']?>_data[_count_n] = {'file': _file_obj_text, 'alt': _alt_obj_text, 'caption': _caption_obj_text, 'thumbs': _caption_thumbs};

			jQuery.each(_<?php echo $attributes['id']?>_data, function(i, items){
				if('thumbs' in _<?php echo $attributes['id']?>_data[i]){
					if(typeof items.thumbs == 'string'){
						_<?php echo $attributes['id']?>_data[i]['thumbs'] = JSON.parse(items.thumbs);
					}else{
						_<?php echo $attributes['id']?>_data[i]['thumbs'] = JSON.parse(JSON.stringify(items.thumbs));
					}
				}		
			});

			jQuery('#<?php echo $attributes['id']?>').text(JSON.stringify(_<?php echo $attributes['id']?>_data));
			window.setTimeout(function(){
				jQuery('#gallery_caption_modal_<?php echo $attributes['id']?>').modal('hide');
			}, 200);
		}
	}
	</script>
	<script type="text/javascript">

	_<?php echo $attributes['id']?>_data = {};
	<?php if(is_array($gallery_items) && count($gallery_items) > 0){?>
		<?php foreach($gallery_items as $k => $v){?>

			<?php if(array_key_exists('file', $v)){?>

				_<?php echo $attributes['id']?>_data[<?php echo $k?>] = {'file': '<?php echo $v['file']?>', 'alt': '<?php echo (array_key_exists('alt', $v)) ? clean_display($v['alt']) : ''?>', 'caption': '<?php echo (array_key_exists('caption', $v)) ? clean_display($v['caption']) : ''?>', 'thumbs': <?php echo (array_key_exists('thumbs', $v)) ? json_encode($v['thumbs']) : ''?>, 'size': '<?php echo (array_key_exists('size', $v)) ? clean_display($v['size']) : ''?>', 'height': '<?php echo (array_key_exists('height', $v)) ? clean_display($v['height']) : ''?>', 'width': '<?php echo (array_key_exists('width', $v)) ? clean_display($v['width']) : ''?>'};
			<?php }elseif(array_key_exists('url', $v)){?>
				
				_<?php echo $attributes['id']?>_data[<?php echo $k?>] = {'url': '<?php echo $v['url']?>'};
			<?php }?>
		<?php }?>
	<?php }?>

	jQuery(document).ready(function (e) {

		<?php
		//Last key from gallery as count id
		$item_count_id = 0;
		if(is_array($gallery_items) && count($gallery_items) > 0){
			foreach($gallery_items as $gk => $gallery_item){
				$item_count_id = $gk;
			}
		}?>

		_count_<?php echo $attributes['id']?> = <?php echo $item_count_id?>;
		_current_img_<?php echo $attributes['id']?> = 1;
		_processing_<?php echo $attributes['id']?> = false;

		_<?php echo $attributes['id']?>_new_gallery_item();

		jQuery(function(){
			jQuery("#image_<?php echo $attributes['id']?>_button").on('click', function(){
				if(_processing_<?php echo $attributes['id']?> == true){
					return false;
				}				
			});

			jQuery("#image_<?php echo $attributes['id']?>").change(function(){
				if(_processing_<?php echo $attributes['id']?> == true){
					return false;
				}

				jQuery('#image_message_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'').empty();
				
				var file = this.files[0];
				var imagefile = file.type;
				
				var allowed_file_types = false;
				<?php if(isset($attributes['allowed_types'])){?>
					allowed_file_types = ["<?php echo implode('","', file_type_mime_details($attributes['allowed_types']));?>"];
				<?php }?>

				if(jQuery.inArray(imagefile, allowed_file_types) === -1){
					jQuery('#image_message_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'').addClass('error').html("<p id='error'>Invalid file type. Only <?php echo $attributes['allowed_types']?> types are allowed</p>");
					remove_wd_loader(jQuery('#image_preview_container_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+''));
				 	return false;
				}else{
					
					_processing_<?php echo $attributes['id']?> = true;
					
					wd_loader(jQuery('#image_preview_container_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+''));
					_current_img_<?php echo $attributes['id']?> = _count_<?php echo $attributes['id']?>;

					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
					upload_image_<?php echo $attributes['id']?>();
				}
			});
		});

		function imageIsLoaded(e) {
			jQuery('#preview_image_<?php echo $attributes['id']?>_'+_current_img_<?php echo $attributes['id']?>+'').attr('src', e.target.result);
			jQuery('#preview_image_<?php echo $attributes['id']?>_'+_current_img_<?php echo $attributes['id']?>+'').attr('width', '250px');
			jQuery('#preview_image_<?php echo $attributes['id']?>_'+_current_img_<?php echo $attributes['id']?>+'').attr('height', '230px');
		};
	});

	function upload_image_<?php echo $attributes['id']?>(){
		var _wd_form_<?php echo $attributes['id']?> = document.getElementById('frm');

		var _wd_form_data_<?php echo $attributes['id']?> = new FormData(_wd_form_<?php echo $attributes['id']?>);
		_wd_form_data_<?php echo $attributes['id']?>.set('tmp_file_name', 'image_tmp_<?php echo $attributes['id']?>');
		<?php if(isset($attributes['upload_dir'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('upload_dir', '<?php echo $attributes['upload_dir']?>');
		<?php }?>
		<?php if(isset($attributes['upload_path'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('upload_path', '<?php echo $attributes['upload_path']?>');
		<?php }?>	
		<?php if(isset($attributes['allowed_types'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('allowed_types', '<?php echo $attributes['allowed_types']?>');
		<?php }?>
		<?php if(isset($attributes['thumbs'])){?>
			_wd_form_data_<?php echo $attributes['id']?>.set('image_sizes', JSON.stringify(<?php echo json_encode($attributes['thumbs'])?>));
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
				remove_wd_loader(jQuery('#image_preview_container_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+''));
				if(data.status == 'success'){

					_uploaded_files = {};
					jQuery.each(data.resized_files, function(_type, _file){
						_uploaded_files[_type] = data.upload_dir + data.random_dir +_file;
					});

					jQuery.each(_<?php echo $attributes['id']?>_data, function(i, items){
						if('thumbs' in _<?php echo $attributes['id']?>_data[i]){
							if(typeof items.thumbs == 'string'){
								_<?php echo $attributes['id']?>_data[i]['thumbs'] = JSON.parse(items.thumbs);
							}else{
								_<?php echo $attributes['id']?>_data[i]['thumbs'] = JSON.parse(JSON.stringify(items.thumbs));
							}
						}		
					});

					if(data.upload_dir != ''){
						_<?php echo $attributes['id']?>_data[_count_<?php echo $attributes['id']?>] = {'file': data.upload_dir+data.random_dir+data.main_file_name, 'size': data.size, 'width': data.width, 'height': data.height, 'thumbs': _uploaded_files};
					}else{
					 	_<?php echo $attributes['id']?>_data[_count_<?php echo $attributes['id']?>] = {'file': data.upload_path+data.random_dir+data.main_file_name, 'size': data.size, 'width': data.width, 'height': data.height, 'thumbs': _uploaded_files};
					}

					jQuery('#<?php echo $attributes['id']?>').text(JSON.stringify(_<?php echo $attributes['id']?>_data));

					jQuery('#close_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>).show();
					jQuery('#edit_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>).show();
					_<?php echo $attributes['id']?>_new_gallery_item();
					_processing_<?php echo $attributes['id']?> = false;
				}else{
					jQuery('#image_message_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'').addClass('error').html(data.message);
				}
			}
		});
	}

	function _<?php echo $attributes['id']?>_new_gallery_item(){
		_count_<?php echo $attributes['id']?> = parseInt(_count_<?php echo $attributes['id']?>) + 1;
		_html = '<div class="image-preview-container" id="image_preview_container_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'">';
			_html += '<div class="image-preview-box" id="image_preview_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'">';
				_html += '<span class="edit gallery-item-edit" data-id="<?php echo $attributes['id']?>" data-count="'+_count_<?php echo $attributes['id']?>+'" id="edit_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'" style="display:none"><span class="ico">create</span></span>';
				_html += '<span class="close gallery-item-remove" id="close_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'" data-id="<?php echo $attributes['id']?>" data-count="'+_count_<?php echo $attributes['id']?>+'" style="display:none"><span class="ico">clear</span></span>';
				_html += '<img id="preview_image_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'" src="<?php echo base_url("assets/admin/images")?>/no-image.png" />';
			_html += '</div>';
			_html += '<div class="image-message" id="image_message_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>+'"></div>';
			_html += '<br clear="all" />';
		_html += '</div>';
		jQuery('.gallery-preview-section-<?php echo $attributes['id']?>').append(_html);
	}


	// Youtube Video
	jQuery(function(){
		jQuery(document).on('click', '.video-upload-button', function(){
			_modal = jQuery(this).data('modal');
			jQuery('#'+_modal).modal('show');
		});
	});

	function _<?php echo $attributes['id']?>_save_youtube_url_(e){

		var _url = jQuery('#youtube_video_url_<?php echo $attributes['id']?>').val();
		
		if(url != ''){

			var _obj_data = _<?php echo $attributes['id']?>_data;

			// var _thumb = getScreen(_url, 'big');
			_last_key = Object.keys(_<?php echo $attributes['id']?>_data)[Object.keys(_<?php echo $attributes['id']?>_data).length - 1];
			_new_key = parseInt(_last_key)+1;

			_<?php echo $attributes['id']?>_data[_new_key] = {"url":_url};	//, "thumbs":_thumb

			jQuery('#<?php echo $attributes['id']?>').text(JSON.stringify(_<?php echo $attributes['id']?>_data));

			<?php /*jQuery('#preview_image_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>).attr('src', _thumb);*/?>
			jQuery('#preview_image_<?php echo $attributes['id']?>_'+_count_<?php echo $attributes['id']?>).attr('src', '<?php echo base_url("assets/admin/images")?>/video-img.png');

			// remove value and hide modal
			jQuery('#youtube_video_url_<?php echo $attributes['id']?>').val('');
			jQuery('#youtube_video_modal_<?php echo $attributes['id']?>').modal('hide');

			_<?php echo $attributes['id']?>_new_gallery_item();
		}
	}

	<?php /*function getScreen( url, size ){
		if(url === null){
			return "";
		}
		
		size = (size === null) ? "big" : size;
		
		var vid;
		var results;
		
		results = url.match("[\\?&]v=([^&#]*)");
		vid = ( results === null ) ? url : results[1];
		
		if(size == "small"){
			return "http://img.youtube.com/vi/"+vid+"/2.jpg";
		}else{
			return "http://img.youtube.com/vi/"+vid+"/0.jpg";
		}
    }*/?>
	</script>
<?php }?>	