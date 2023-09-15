<?php 
	$availability	= array();
	
	if(isset($data['availability']) && $data['availability']!='' ){
		$availability 		= unserialize($data['availability']);
		
		//print_r($availability);
	}
	
	$availability_a			= array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
?>

<div class="col-sm-6">
	<div class="form-group">
		<label class="col-sm-4" for="availability">Availability <span class="req">*</span></label>
		<div class="col-sm-8">
			
			<table class="table table-striped table-bordered">
				<tr>
					<th>Day</th>
					<th>Availability</th>
				</tr>
				
				<?php if(count($availability)!=0){?>
					
					<?php foreach($availability as $k => $a){?>
						<tr>
							<td><?= $k?></td>
							<td>
								<input type="text" name="availability[<?= $k?>]" value="<?= $a?>" class="form-control"> 
							</td>
						</tr>
					<?php }?>
				
				<?php }else{?>
					<?php foreach($availability_a as $k => $a){?>
						<tr>
							<td><?= $a?></td>
							<td>
								<?php /*?><input type="hidden" name="availability[<?= $k?>][day]" value="<?= $a?>" class="form-control"><?php */?>
								<input type="text" name="availability[<?= $a?>]" value="Yes" class="form-control">
							</td>
						</tr>
					<?php }?>
				<?php }?>
			</table>
			
		</div>
	</div>
</div>




