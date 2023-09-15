<?php 
	$rates	= array();
	
	if(isset($data['rates']) && $data['rates']!='' ){
		$rates 		= unserialize($data['rates']);
		
		//print_r($rates);
	}
	
	$rates_a			= array(1=>'Rate incal 1h', 2=>'Rate incal 2h', 3=>'Rate outcal 1h', 4=>'Rate outcal 2h', 5=>'Rate full night');
?>

<div class="col-sm-6">
	<div class="form-group">
		<label class="col-sm-4" for="rates">Rates <span class="req">*</span></label>
		<div class="col-sm-8">
			
			<table class="table table-striped table-bordered">
				<tr>
					<th>Day</th>
					<th>Rates</th>
				</tr>
				
				<?php if(count($rates)!=0){?>
					
					<?php foreach($rates as $k => $a){?>
						<tr>
							<td><?= $rates_a[$k]?></td>
							<td>
								<input type="text" name="rate[<?= $k?>]" value="<?= $a?>" class="form-control">
							</td>
						</tr>
					<?php }?>
				
				<?php }else{?>
					<?php foreach($rates_a as $k => $a){?>
						<tr>
							<td><?= $a?></td>
							<td>
								<input type="text" name="rate[<?= $k?>]" value="" class="form-control">
							</td>
						</tr>
					<?php }?>
				<?php }?>
			</table>
			
		</div>
	</div>
</div>




