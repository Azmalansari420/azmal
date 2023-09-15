<form action="" method="post">
	<div class="row">
    	<div class="col-lg-6">
	  		<span class="btn btn-info fileinput-button">
    		   	<i class="fa fa-plus-square"></i>
        	    <span><strong>Add Multiple Photos</strong></span>
        	    <input type="file" id="media_upload" name="userfile" multiple>
        	    <input type="hidden" name="media_index" id="media_index" value="1" />
                <input type="hidden" name="media_type" id="media_type" value="photo" />
        	</span>
            
            <a href="<?php echo adv_url()?>media/add" class="btn btn-warning">Add Single Photo</a>
            
            <button type="submit" id="save_photos" class="btn btn-success" style="display:none">Save Photos</button>
    	</div>
        <div class="col-lg-6">
	  		
    	</div>
    </div>
    
    <div class="row">
        <div class="col-lg-12 que-files"></div>
	</div>
</form>

<ul id="uploaded"></ul>
<div class="fileupload-loading"></div>

<style>
.que-files{list-style-type:decimal; margin:20px 0;}
.que-files li{padding:5px 10px; border:1px solid #ccc; margin-bottom:15px; width:100%; float:left;}
</style>

<script type="text/javascript">
//media = $('#media_index').val();
media = '';
$(function(){
	/*$('#media_upload').fileupload({
        url: '<?php echo adv_url()?>media/upload_img'
    });*/

	$('#media_upload').fileupload({
		url: '<?php echo adv_url()?>media/upload_img',
		singleFileUploads: true,
		replaceFileInput: false,
		sequentialUploads: true,
        <?php //form data: sending current media index ?>
		formData: { media_index: $('#media_index').val() },
        dataType: 'json',
        add: function (e, data) {
			media = parseInt($('#media_index').val());
			$.each(data.files, function(index, file){
				if(file.type.indexOf('image') == 0){
					var u_file = '<img class="media_list img-polaroid" height="150" src="" />';
				}else{
					var u_file = file.name;
				}
				
				var h = '<div class="row media-list-row" id="upload_'+media+'">';
            		h += '<span class="col-lg-2" style="text-align:center; overflow:hidden">'+u_file+'</span>';
                	h += '<div class="col-lg-10">';
                		h += '<div class="row">';
							h += '<div class="col-lg-12">';
								h += '<div class="progress progress-striped progress-media">';
									h += '<div class="progress-bar progress-bar-success" role="progressbar" id="progress_upload_'+media+'" aria-valuemin="0" aria-valuemax="100"></div>';	
								h += '</div>';
	            			h += '</div>';
    					h += '</div>';
						
		           		h += '<div class="form-inline">';
                			h += '<div class="form-group form-group-media">';
                    			//h += '<label>Title</label>';
                    			h += '<input type="text" name="image_title['+media+']" class="form-control" placeholder="Image Title">';
                    		h += '</div>';
                    		h += '<div class="form-group form-group-media">';
                    			//h += '<label>Caption</label>';
                    			h += '<input type="text" name="image_caption['+media+']" class="form-control" placeholder="Caption">';
                    		h += '</div>';
                    		/*h += '<div class="form-group form-group-media">';
                    			//h += '<label>Alt</label>';
                    			h += '<input type="text" name="image_alt['+media+'][]" class="form-control" placeholder="Alt Text">';
                    		h += '</div>';*/
							h += '<div class="form-group form-group-media">';
                    			//h += '<label>Alt</label>';
                    			h += '<select name="image_group['+media+']" class="form-control">';
									<?php foreach($groups as $id => $group){?>
										h += '<option value="<?php echo $id?>"><?php echo $group?></option>';
									<?php }?>
								h += '</select>';
                    		h += '</div>';
							h += '<div class="form-group form-group-media">';
                    			//h += '<label>Alt</label>';
                    			h += '<input type="text" style="width:50px" name="image_order['+media+']" class="form-control" placeholder="Sort Order">';
                    		
								h += '<input type="hidden" name="image_name['+media+']" id="image_name_'+media+'">';
								h += '<input type="hidden" id="image_remove_path_'+media+'">';
							
							h += '</div>';
							h += '<div class="form-group form-group-media">';
                    			//h += '<label>Alt</label>';
                    			h += "<button type='button' onclick='remove_image(\""+media+"\")' id='remove_btn_"+media+"' rel='"+media+"' class='btn btn-danger btn-xs remove_btn'>&times;</button>";
							h += '</div>';
                		h += '</div>';
            		h += '</div>';
            	h += '</div>';
					
				$('.que-files').append(h);
				data.submit().success(function(a){
					$.each($('img.media_list'), function(a, b){
						var src_ = $(b).attr('src');
						$(b).removeAttr('src').attr('src', src_+'?ver=' + new Date().getTime());
					});
					$('#upload_'+media+' .badge').html('100%');
					//$('#progress_upload_'+a[0]['media_index']).removeClass('active');
					//$('#progress_upload_'+a[0]['media_index']+' .bar').css('width', '100%');
				});
				$('#media_index').val(media+1);
			});
			
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
        	$('#upload_'+media+' .badge').html(progress+'%');
			$('#progress_upload_'+media).css('width', progress+'%');
			$('#progress_upload_'+media).html(progress+' %');
		},
		done: function (e, data){
			$('#upload_'+data.result.media_index+' .img-polaroid').attr('src', "<?php echo base_url()?>"+data.result.name);
			$('#progress_upload_'+data.result.media_index).css('width', '100%');
			$('#progress_upload_'+media).html('100 %');
			
			$('#image_name_'+media).val(data.result.path);
			$('#image_remove_path_'+media).val(data.result.name);
			
			$('#save_photos').show();
		}
	});
	
	$('#media_upload').bind('fileuploadsubmit', function (e, data) {
    	var input = $('#media_index');
    	data.formData = {media_index: input.val(), new_name: ''};
    	if (!data.formData.media_index) {
    		input.focus();
    		return false;
    	}
	});
});

function remove_image(m){
	if(confirm("Do you really want to remove this photo?")){
		d_path = $('#image_remove_path_'+m).val();
		
		$.ajax({
			url: '<?php echo adv_url('media/remove_upload_image')?>',
			data: "src="+d_path,
			type: 'post',
			dataType: 'json',
			success: function(a){
				if(a.status == 'success'){
					$('#upload_'+m).remove();
				}
				if(a.status == 'fail'){
					alert("There was an error. Please try again.");
				}
			},
			failure: function(a){
				alert("There was an error. Please try again.");
			}
		});
	}
}
</script>