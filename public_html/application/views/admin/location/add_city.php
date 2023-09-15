<?php 
	$state 		= '';
	$city	 	= '';
	
	if(isset($data['id'])){
		$state 		= $data['state'];
		$city		= $data['city'];
	}
	
?>


<div class="col-sm-6">
	<div class="form-group">
		<div class="col-sm-12">
			<label class="col-sm-12 form-control-label" for="city">City<span class="req">*</span></label>
			<select name="city" id="city" class="form-control" style="width:100%;" required>
				<option value="">Select City</option>
			</select>
		</div>
	</div>
</div>



<link href="<?= base_url('assets/css/select2.min.css')?>" rel="stylesheet" />
<script src="<?= base_url('assets/js/select2.min.js')?>"></script>


<script>
jQuery(function(){
	window.setTimeout(
		function(){
			get_cities();
		},
	1000);	
	
	
	jQuery('body').on('change', '#state', function(){
		get_cities();
	});
});



function get_cities(){
	var city			= '<?= $city ?>';
	var state		 	= jQuery('#state').val();
	
	jQuery.ajax({
		type:"POST",
		url:"<?= admin_url("location/locality/get_cities");?>",
		dataType: 'html',
		data:'city='+city+'&state='+state,
		success: function(data){
			jQuery('#city').html(data)
		}
	});		
}

jQuery("#city").select2({
    placeholder: "Select a City",
    allowClear: true
});

jQuery("#state").select2({
    placeholder: "Select a State",
    allowClear: true
});
</script>
