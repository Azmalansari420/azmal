<?php $type_a				= array('call_girls'=>"Call Girls", 'escorts'=>"Escorts", 'male_escorts'=>"Male Escorts");?>
<div class="locations-bar">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				
				<?php if(isset($data->title) && $data->title!=''){?> 
					<div class="white-box">
						<h1 class="heading text-center"><?php echo utf8_decode(stripslashes($data->title))?></h1>
						<div align="center"><div class="f-line"></div></div>
						<div class="page-content"><?php echo utf8_decode(stripslashes($data->content))?></div>
					</div>
				<?php }?>
				
				
				<?php foreach(get_type('no') as $ts => $t){?>	
					<?php foreach(get_states() as $k => $v){?>	
						<?php if($k!='' && count(get_cities($v))!=0){?>
							<div class="white-box">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="gray-box">
											<div class="heading"><?= $t?> in <?= $v?></div>
											<div class="f-line"></div>
											<div class="row">
												<?php foreach(get_cities($v) as $city){?>
														<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
															<a class="city" href="<?= base_url($ts.'/'.$city->slug);?>"> <?= $city->name;?></a>
														</div>
												<?php }?>
											</div>											
										</div>
									</div>
								</div>
							</div>
						<?php }?>		
					<?php }?>	
				<?php }?>	
					
			</div>
		</div>
	</div>
</div>


