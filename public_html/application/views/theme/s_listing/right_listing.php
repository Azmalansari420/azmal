<div class="row desktop-bar" data-nosnippet>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<h4 class="heading heading2">Featured Escorts</h4>
		<div class="row">	
			<?php 
			$n=1;
			foreach($right_listing as $p){?>
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
			
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
					<div class="right-listing-box text-center">
						<?php /*?><div class="name"><a href="<?= base_url('profile/'.$p->slug)?>"><?= substr($p->name,0, 20)?></a></div><?php */?>
						<div class="img test-center" align="center">
							<a href="<?= base_url('profile/'.$p->slug)?>">
								<?php if($n <= 2){?>
								<img class="img-responsive" src="<?= base_url('media/uploads').$p->image?>" name="<?= $p_name?>" alt="<?= $p_name?>" title="<?= $p_name?>" />
								<?php }else{?>
								<img class="img-responsive" src="<?= base_url('assets/images/simage.webp');?>" data-src="<?= base_url('media/uploads').$p->image?>" name="<?= $p_name?>" alt="<?= $p_name?>" title="<?= $p_name?>"/>
								<?php }?>
							</a>
						</div>
						
						<div class="name"><a href="<?= base_url('profile/'.$p->slug)?>"><?= $p_name?></a></div>
						<div class="number"><a href="tel:+91 <?= $p->mobile_no;?>"><?= $p->mobile_no;?></a></div>
					</div>
				</div>
			
			<?php $n++;}?>
		</div>
	</div>
</div>