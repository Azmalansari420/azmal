<?php 
	$services	= array();
	if(!isset($data['id'])){
		$services	= array_rand($get_services, 8);
	}
	if(isset($data['services']) && $data['services']!='' ){
		$services 		= explode('=', $data['services']);
	}
?>

<div class="col-sm-12">
	<div class="form-group">
		<label class="col-sm-2" for="availability">Services offered <span class="req">*</span></label>
		<div class="col-sm-8">
			<div class="row">
				<?php $k2 = 1;?>
				<?php foreach($get_services as $k => $s){ $k2 ++; ?>
					<div class="col-sm-6">
						<label>
							<input type="checkbox" name="services[<?= $k2?>]" value="<?= $s?>" <?= (in_array($s, $services))? 'checked="checked"': '';?> style="width:15px; height:15px;">
							<?= $s?>
						</label>
					</div>
				<?php }?>
			</div>
			
		</div>
	</div>
</div>




