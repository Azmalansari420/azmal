<?php 
	$content 		= '';
	if(isset($data['id'])){
		$content 	= $data['content'];
	}
?>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="<?= base_url('assets/admin/js/editor.js')?>"></script>
<script>
	$(document).ready(function() {
		$("#txtEditor").Editor();
	});
</script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link href="<?= base_url('assets/admin/css/editor.css')?>" type="text/css" rel="stylesheet"/>


<div class="col-sm-12">
	<div class="form-group row">
		<label class="col-sm-12 " for="content">Content <span class="req">*</span></label>
		<div class="col-sm-12">
			<textarea id="txtEditor" name="content" style="width:100%; height:200px;"><?= $content?></textarea>
		</div>
	</div>
</div>





<?php /*?><link href="<?= base_url('assets/admin/css/editor.css')?>" rel="stylesheet" />
<script src="<?= base_url('assets/admin/js/editor.js')?>"></script>




<script>
$(document).ready(function() {
	$("#txtEditor").Editor();
});
</script><?php */?>
