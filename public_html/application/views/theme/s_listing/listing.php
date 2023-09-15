<div class="row" data-nosnippet>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 lvw">
		
		<?php if(count($listing)!=0){?>
			<?php if(!isset($_POST['page']) || $_POST['page']==''){?>
				<?php /*?><div class="heading heading2">New Call Girls, Escorts and Dating Girls</div><?php */?>
			<?php }?>
			
			<?php /*?><?php 
				print(get_states());
				print(get_all_cities());
				print(get_all_locality());
			?><?php */?>
			
			<?php foreach($listing as $p){?>
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
					$p_about_us		= $p->about_us;
					/*if($l_locality!=''){
						$p_name			= str_replace(get_all_cities(), $l_locality, $p_name);
						$p_about_us		= str_replace(get_all_cities(), $l_locality, $p_about_us);
					}elseif($l_city!=''){
						$p_name			= str_replace(get_all_locality(), $l_city, $p_name);
						$p_about_us		= str_replace(get_all_locality(), $l_city, $p_about_us);
					}else{
						$p_name			= str_replace(get_all_cities(), $l_state, $p_name);
						$p_about_us		= str_replace(get_all_cities(), $l_state, $p_about_us);
						
						$p_name			= str_replace(get_all_locality(), $l_state, $p_name);
						$p_about_us		= str_replace(get_all_locality(), $l_state, $p_about_us);
					}*/
				?>
			
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 listing-box-wrapper before lbr" id="listingBoxWrapper">
						<div class="listing-box before">
							<div class="row lbr" id="lbrlb">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 right0" id="lbrcl">
									<div class="img test-center" align="center">
										<a href="<?= base_url('profile/'.$p->slug)?>">
											<img class="img-responsive" src="<?= base_url('assets/images/simage.webp');?>" data-src="<?= base_url('media/uploads').$p->image?>" name="<?= $p_name?>" alt="<?= $p_name?>" title="<?= $p_name?>" width="175" height="175" />
											
											<?php /*?><img class="img-responsive" src="<?= base_url('media/uploads').$p->image?>" name="<?= $p_name?>" alt="<?= $p_name?>" /><?php */?>
										</a>
									</div>
								</div>
								
								<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 col-right before lbr" id="lbrcr">
									<div class="contant-box">
										<div class="name"><a href="<?= base_url('profile/'.$p->slug)?>"><?= $p_name?></a></div>
										<div class="contant"><?= substr($p_about_us, 0, 300)?>....</div>
									
										<div class="link">
											<div class="row">
												<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
													<a href="<?= base_url($p->type);?>"><?= ucwords(str_replace('_', ' ', $p->type))?></a> | 
													<?= $p->state?> | 
													<a href="<?= base_url($p->type).'/'.generate_slug($p->city);?>"><?= $p->city?></a>	
												</div>
												
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
													<?php if($p->mobile_no!=''){?>
														<a href="tel:<?= $p->mobile_no?>"> 
															<img src="<?= base_url('assets/images/call2.svg');?>" alt="call" height="25" width="25">
														</a>&nbsp;
													<?php }?>
													
													<?php if($p->whatapp_no!=''){?>
														<a href="https://api.whatsapp.com/send?phone=+91<?=$p->whatapp_no?>&amp;text=Hi" target="_blank"> 
															<img src="<?= base_url('assets/images/whatsapp.svg');?>" alt="whatsapp" height="25" width="25">
														</a>
													<?php }?>
												</div>
											</div>
										</div>
									</div>
									
									<?php /*?><div class="gender">Age :<?= $p->age?> | <?= $p->gender?></div><?php */?>
								</div>
									
							</div>
						</div>
					</div>
				</div>
			<?php }?>
			
		<?php }else{?>
			<h2 class="heading heading2">No Profile Found</h2>
		<?php }?>
	</div>	
</div>


<?php 
//echo count($listing);
//echo $page_start;
//
$count = ceil($total_rows/5);
$_count = $count-$page_start;

$last_number = array();
for($x = ($count-5); $x <= $count; $x++){
	$last_number[] = $x;
}

$first_number = array();
for($x = ($page_start); $x <= ($page_start+6); $x++){
	$first_number[] = $x;
}


?>

<input type="hidden" value="<?= ($page_start==$count)? 0: $page_start+1;?>" class="page_no" />


<?php if(count($listing)!=0){?>
	<div align="center" class="pagination_<?= $page_start+1;?>">
		<ul class="pagination">
			<?php if($page_start!=1){?>
				<li>
					<a href="javascript:void(0)" aria-label="Previous" onclick="get_listing2('<?= $page_start-1?>', '<?= $page_start+1;?>');">
						<span aria-hidden="true">&laquo;</span>
					</a>
				</li>
			<?php }?>
			
			<?php for($x = 1; $x <= $count; $x++){?>
				<?php if($page_start==$x){?>
					<li class="active"><a href="javascript:void(0)"><?= $x?></a></li>
				<?php }else{?>
					<?php if($count>14){?>
						
						<?php if(in_array($x , $first_number)){?>
							<li><a href="javascript:void(0)" onclick="get_listing2('<?= $x?>', '<?= $page_start+1;?>');"><?= $x?></a></li>
						<?php }?>
						
						<?php if(($page_start+6)==$x){?>
							<li class="disabled"><span>...</span></li>
						<?php }?>
						
						<?php if(in_array($x , $last_number)){?>
							<li><a href="javascript:void(0)" onclick="get_listing2('<?= $x?>', '<?= $page_start+1;?>');"><?= $x?></a></li>
						<?php }?>
						
						
					<?php }else{?>
						<li><a href="javascript:void(0)" onclick="get_listing2('<?= $x?>', '<?= $page_start+1;?>');"><?= $x?></a></li>	
					<?php }?>
				<?php }?>
			<?php }?>
			
			<?php if($page_start!=$count){?>
				<li>
					<a href="javascript:void(0)" aria-label="Next" onclick="get_listing2('<?= $page_start+1?>', '<?= $page_start+1;?>');">
						<span aria-hidden="true">&raquo;</span>
					 </a>
				</li>
			<?php }?>
	  	</ul>
	</div>
<?php }?>

