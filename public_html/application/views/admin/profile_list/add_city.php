<?php 
	$state 		= '';
	$city	 	= '';
	$locality 	= '';
	
	if(isset($data['id'])){
		$state 		= $data['state'];
		$city		= $data['city'];
		$locality	= $data['locality'];
	}
?>


<div class="col-sm-6">
	<div class="form-group row">
		<label class="col-sm-12 " for="state">State <span class="req">*</span></label>
		<div class="col-sm-12">
			<select name="state" id="state" class="form-control" style="width:100%;" required>
				<option value="">Select State</option>
				<?php foreach(get_states() as $k => $c){?>
					<option value="<?= $c?>" <?= ($c == $state)? 'selected="selected"': '';?> ><?= $c?></option>
				<?php }?>
			</select>
		</div>
	</div>
</div>


<div class="col-sm-6">
	<div class="form-group row">
		<label class="col-sm-12 " for="city">City </label>
		<div class="col-sm-12">
			<select name="city" id="city" class="form-control" style="width:100%;" >
				<option value="">Select City</option>
			</select>
		</div>
	</div>
</div>

<?php /*?><div class="col-sm-6">
	<div class="form-group row">
		<label class="col-sm-12 " for="locality">Locality </label>
		<div class="col-sm-12">
			<select name="locality" id="locality" class="form-control" style="width:100%;" >
				<option value="">Select Locality</option>
			</select>
			
			
		</div>
	</div>
</div><?php */?>

<?php /*?><button type="button" class="btn btn-primary btn-xs hide-bar" onclick="modal_locality();" id="add_locality">Add Locality</button><?php */?>

<link href="<?= base_url('assets/css/select2.min.css')?>" rel="stylesheet" />
<script src="<?= base_url('assets/js/select2.min.js')?>"></script>


<script>
jQuery(function(){
	jQuery('#state').css('width', '100%');
	
	window.setTimeout(
		function(){
			get_cities();
		},
	1000);	
	
	window.setTimeout(
		function(){
			get_locality();
		},
	1500);	
	
	
	jQuery('body').on('change', '#state', function(){
		get_cities();
	});
	
	jQuery('body').on('change', '#city', function(){
		get_locality();
	});
});



function get_cities(){
	var city			= '<?= $city ?>';
	var state		 	= jQuery('#state').val();
	
	jQuery.ajax({
		type:"POST",
		url:"<?= admin_url("profile_list/get_cities");?>",
		dataType: 'html',
		data:'city='+city+'&state='+state,
		success: function(data){
			jQuery('#city').html(data)
		}
	});		
}

function get_locality(){
	var locality			= '<?= $locality ?>';
	var city			 	= jQuery('#city').val();
	
	jQuery.ajax({
		type:"POST",
		url:"<?= admin_url("profile_list/get_locality");?>",
		dataType: 'html',
		data:'locality='+locality+'&city='+city,
		success: function(data){
			jQuery('#locality').html(data)
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

jQuery("#locality").select2({
    placeholder: "Select a Locality",
    allowClear: true
});
</script>
