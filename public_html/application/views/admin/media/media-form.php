<div class="form_container">
	<form action="" method="post">
    <div class="span7">
	  	<span class="btn btn-success fileinput-button">
    	   	<i class="icon-plus icon-white"></i>
            <span>Add files...</span>
            <input type="file" id="media_upload" name="userfile" multiple>
            <input type="hidden" name="media_index" id="media_index" value="1" />
        </span>
    </div>
    
    <div class="span10">
    	<ul class="que-files">
        	<?php /*?><li id="upload_1">
            	<span class="span3">
                	<img src="http://localhost/gadgetoos/media/26042012844.jpg">
                </span>
                <div class="span9">
                	<div class="span12">
                    	<div class="progress-extended span2"><span class="badge badge-success">100%</span></div>
                		<div class="span10 fileupload-progress">
                		   	<div aria-valuemax="100" aria-valuemin="0" role="progressbar" id="progress_upload_1" class="progress progress-success progress-striped">
                		       	<div style="width: 100%;" class="bar"></div>
                		    </div>
                		</div>
            		</div>
                	<div class="form-horizontal span11">
                		<div class="control-group">
                    		<label class="control-label">Title</label>
                    		<div class="controls"><input type="text" class="input-xlarge" placeholder="Image Title"></div>
                    	</div>
                    	<div class="control-group">
                    		<label class="control-label">Caption</label>
                    		<div class="controls"><input type="text" class="input-xlarge" placeholder="Caption"></div>
                    	</div>
                    	<div class="control-group">
                    		<label class="control-label">Alt</label>
                    		<div class="controls"><input type="text" class="input-xlarge" placeholder="Alt Text"></div>
                    	</div>
                	</div>
            	</div>
            </li><?php */?>
        </ul>
    </div>
    </form>
</div>

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
	$('#media_upload').fileupload({
        url: '<?php echo base_url()?>admin/media/index/upload_img'
    });

	$('#media_upload').fileupload({
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
					var u_file = '<img class="media_list img-polaroid" src="<?php echo media_url()?>'+file.name+'" />';
				}else{
					var u_file = file.name;
				}
				
				var h = '<li id="upload_'+media+'">';
            		h += '<span class="span3">'+u_file+'</span>';
                	h += '<div class="span9">';
                		h += '<div class="span12">';
                    		h += '<div class="progress-extended span2"><span class="badge badge-success">&nbsp;</span></div>';
                			h += '<div class="span10 fileupload-progress">';
                		   		h += '<div aria-valuemax="100" aria-valuemin="0" role="progressbar" id="progress_upload_'+media+'" class="progress progress-success progress-striped">';
                		       		h += '<div style="width: 100%;" class="bar"></div>';
                		    	h += '</div>';
                			h += '</div>';
            			h += '</div>';
                		h += '<div class="form-horizontal span11">';
                			h += '<div class="control-group">';
                    			h += '<label class="control-label">Title</label>';
                    			h += '<div class="controls"><input type="text" name="image_title['+media+'][]" class="input-xlarge" placeholder="Image Title"></div>';
                    		h += '</div>';
                    		h += '<div class="control-group">';
                    			h += '<label class="control-label">Caption</label>';
                    			h += '<div class="controls"><input type="text" name="image_caption['+media+'][]" class="input-xlarge" placeholder="Caption"></div>';
                    		h += '</div>';
                    		h += '<div class="control-group">';
                    			h += '<label class="control-label">Alt</label>';
                    			h += '<div class="controls"><input type="text" name="image_alt['+media+'][]" class="input-xlarge" placeholder="Alt Text"></div>';
                    		h += '</div>';
                		h += '</div>';
            		h += '</div>';
            	h += '</li>';
					
				$('.que-files').append(h);
				data.submit().success(function(a){
					//console.log(a);
					//alert(
					$.each($('img.media_list'), function(a, b){
						var src_ = $(b).attr('src');
						$(b).removeAttr('src').attr('src', src_+'?ver=' + new Date().getTime());
					});
					$('#upload_'+media+' .badge').html('100%');
					$('#progress_upload_'+a[0]['media_index']).removeClass('active');
					$('#progress_upload_'+a[0]['media_index']+' .bar').css('width', '100%');
				});
				$('#media_index').val(media+1);
			});
			
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
        	$('#upload_'+media+' .badge').html(progress+'%');
			$('#progress_upload_'+media+' .bar').css(
        	    'width',
        	    progress + '%'
        	);
		},
		done: function (e, data){
			$.each(data.files, function(index, file){
				alert(file.size);
				/*if(file.type.indexOf('image') == 0){
					var u_file = '<img class="media_list img-polaroid" src="<?php echo media_url()?>'+file.name+'" />';
				}else{
					var u_file = file.name;
				}*/
			});
			/*$.each($('img.media_list'), function(a, b){
				$(b).load();
				alert('k');
			});*/
			//alert(data);
			//$.each(data.result, function (index, file) {
            //	alert(file.media_index);
				//$('#uploaded').append('<li>'+file.name+'</li>');
            //});
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
</script>