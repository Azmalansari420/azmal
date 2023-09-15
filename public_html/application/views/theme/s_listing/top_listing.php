<div class="row" data-nosnippet>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?php if(count($top_listing)!=0){?>
			<h2 class="heading">Premium Call Girls, Escorts and Dating Girls</h2>
			
			<div class="row">
				<?php foreach($top_listing as $p){?>
					<?php 
						//print_r($_POST);
						$l_state		= '';
						$l_city			= '';
						$l_locality		= '';
						
						if(isset($_POST['state'])){
							$l_state	= $_POST['state'];
						}
						
						if(isset($_POST['city'])){
							$l_city	= $_POST['city'];
						}
						
						if(isset($_POST['locality'])){
							$l_locality	= $_POST['locality'];
						}
						
						$p_name			= $p->name;
						if($l_locality!=''){
							$p_name			= str_replace(get_all_cities(), $l_locality, $p_name);
						}elseif($l_city!=''){
							$p_name			= str_replace(get_all_locality(), $l_city, $p_name);
						}else{
							$p_name			= str_replace(get_all_cities(), $l_state, $p_name);
							
							$p_name			= str_replace(get_all_locality(), $l_state, $p_name);
						}
					?>
				
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
						<div class="top-listing-box text-center">
							<div class="img">
								<?php /*?><span class="verified-out"><span class="verified">Top</span></span><?php */?>
								<a href="<?= base_url('profile/'.$p->slug)?>">
									<?php /*?><img class="img-responsive" src="<?= base_url('media/uploads').$p->image?>" name="<?= $p_name?>" title="<?= $p_name?>" alt="<?= $p_name?>"/><?php */?>
									<img class="img-responsive" src="<?= base_url('assets/images/simage.webp');?>" data-src="<?= base_url('media/uploads').$p->image?>" name="<?= $p_name?>" alt="<?= $p_name?>" title="<?= $p_name?>"/>
								</a>
							</div>
							<div class="name"><a href="<?= base_url('profile/'.$p->slug)?>"><?= $p_name?></a></div>
							<div class="number"><a href="tel:+91 <?= $p->mobile_no;?>"><?= $p->mobile_no;?></a></div>
							
							<?php /*?><div class="more-info">
								<div class="info">
									<?= $p->gender?>
									<div><?= $p->age?> Years</div>
									<?= $p->type?>
								</div>
							</div><?php */?>
							<?php /*?><div class="name"><a href="<?= base_url('profile/'.$p->slug)?>"><?= $p->city ?></a></div><?php */?>
						</div>
					</div>
				<?php }?>
				
			</div>
		<?php }?>	
	</div>
</div>
