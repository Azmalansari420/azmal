<div class="col-lg-12">
	<div class="offer-gallery">
		<?php foreach($gallery_images as $gallery_image){?>
			<div class="">
				<div style="float:left; margin-right:10px; border:1px solid #aaa; padding:5px;">
					<div style="max-height:100px; max-width:100px; min-height:100px; min-width:100px; overflow:hidden;">
						<img src="<?= base_url()?>media/uploads<?php echo $gallery_image->image_url?>" width="100" />
					</div>
					<?php /*?><label style="display:block">Status:</label>
					<select id="image_status" name="image_status[<?php echo $gallery_image->id?>]">
						<option value="">Select Status</option>
						<option value="approved" <?php echo ($gallery_image->image_status == 'approved')? 'selected="selected"': ''?>>Approved</option>
						<option value="unapproved" <?php echo ($gallery_image->image_status == 'unapproved')? 'selected="selected"': ''?>>Unapproved</option>
					</select><?php */?>
					<label style="display:block"><input type="checkbox" value="1" name="delete[<?php echo $gallery_image->id?>]"> Delete Image</label>
					<input type="hidden" value="<?php echo $gallery_image->image_url?>" name="delete_url[<?php echo $gallery_image->id?>]">
					<input type="hidden" value="<?php echo $gallery_image->id?>" name="saved_image[<?php echo $gallery_image->id?>]">
				</div>
			</div>
		<?php }?>
		<br clear="all" />
		<br />
		<div class="form-group">
			<div class="col-sm-12">
				<input type="file" name="gallery_1" />
			</div>
		</div>
	</div>
	<input type="hidden" name="gallery_count" id="gallery_count" value="1" />
	<button class="btn btn-primary" onclick="return add_gallery_image()">Add New Image</button>
</div>

<script type="text/javascript">
_num = 1;
function add_gallery_image(){
	_num = _num+1;
	html = '<div class="form-group">';
		html += '<div class="col-sm-12">';
			html += '<input type="file" name="gallery_'+_num+'" />';
		html += '</div>';
	html += '</div>';
	
	jQuery('.offer-gallery').append(html);
	jQuery('#gallery_count').val(_num);
	return false;
}
</script>