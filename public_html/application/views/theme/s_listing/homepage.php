
<?php if(isset($data->content)){?>
	<div class="page-cms">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">   
					<div class="white-box"> 
						<h1 class="heading text-center"><?php echo utf8_decode(stripslashes($data->title))?></h1>
						<div align="center"><div class="f-line"></div></div>
						<div class="page-content"><?php echo utf8_decode(stripslashes($data->content))?></div>
					</div>
				</div>
			</div>
		</div>
	</div>	
<?php }?>

<div class="container home-page">
	
	<?php foreach(get_type('no') as $tk => $tv){?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="type-heading"><span>Most Searched <?= $tv?> India</span></div>
			</div>
		</div>
		<br /><br />
		<div class="row">
			<?php foreach(get_states('no') as $k => $v){?>
				<?php if(count(get_cities_most_searched($v))!=0){?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="searched-home">
							<div class="state-name"><?= $v?> <?= $tv?></div>
							
							<ul>
								<?php foreach(get_cities_most_searched($v) as $c){?>
									<li><a target="_blank" href="<?= base_url().$tk.'/'.$c->slug?>"> <?= $tv?> in <?= $c->name?> </a></li>
								<?php }?>
							</ul>
						</div>
					</div>
				<?php }?>
			<?php }?>
		</div>
		
		<?php /*?><div class="row">
			<?php foreach(get_cities_home_view() as $c){?>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
					<a target="_blank" href="<?= base_url().$k.'/'.$c->slug?>" class="type-box"><?= $c->name?> <?= $v?> </a>
				</div>
			<?php }?>
		 </div><?php */?>
			
	<?php }?>
	
	<?php /*?><div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3>Search Or Post Your Adult Advertisement</h3>
		</div>
	</div><?php */?>

	<?php /*?><div class="row">
		<?php foreach(get_type('no') as $k => $v){?>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="type-home">
					<div class="img">
						<a href="<?= base_url().$k?>">
							<img src="<?= base_url()?>assets/images/<?= $k?>.jpg" alt="<?= $v?>">
							<div class="type-name"><span class="glyphicon glyphicon-heart"></span> <?= $v?></div>
						</a>
					</div>
					
					<div class="type-body">
						<p><?= $v?> adult classified to help you locate independent <?= $v?> to ful...</p>
						<ul>
							<?php foreach(get_cities_home_view() as $c){?>
								<li><a target="_blank" href="<?= base_url().$k.'/'.$c->slug?>"><?= $c->name?> <?= $v?> </a></li>
							<?php }?>
						</ul>
					 </div>
				</div>
			</div>
		<?php }?>
	</div><?php */?>
	
	<?php /*?><br />
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3>Most Searched locations in <span>India</span></h3>
		</div>
	</div>
	<br /><br /><?php */?>

	<?php /*?><div class="row">
		<?php foreach(get_states('no') as $k => $v){?>
			<?php if(count(get_cities_most_searched($v))!=0){?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="searched-home">
						<div class="state-name"><?= $v?></div>
						
						<ul>
							<?php foreach(get_cities_most_searched($v) as $c){?>
								<li><a target="_blank" href="<?= base_url().'call-girls/'.$c->slug?>"><?= $c->name?> Call Girls </a></li>
							<?php }?>
						</ul>
					</div>
				</div>
			<?php }?>
		<?php }?>
	</div><?php */?>
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
			<a href="<?= base_url('locations');?>" class="see-more">See more cities</a>
		</div>
	</div>
	
	
</div>	
	


<br />


<script>
jQuery(function(){
	window.setTimeout(
		function(){
			get_cities();
		},
	500);	
	
	window.setTimeout(
		function(){
			get_locality();
		},
	1000);
	
	jQuery('body').on('change', '#state', function(){
		get_cities('state');
		get_locality('state');
	});
});

function get_cities(val){
	<?php if(isset($city) && $city!=''){?>
		var city = '<?= $city?>';
	<?php }else{?>
		var city = '';
	<?php }?>
	
	var state = jQuery('#state').val();
	
	if(val=='state'){
		var city = '';
	}
	
		
	<?php if(isset($type_slug) && $type_slug!=''){?>
		var l_type 	= '<?= $type_slug?>';
	<?php }else{?>
		var h_type = jQuery('#h_type').val(); 
		var l_type 	= h_type.replace("<?= base_url();?>", "");
	<?php }?>
	
	jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/get_cities");?>",
		dataType: 'html',
		data:'city='+city+'&state='+state+'&type='+l_type,
		success: function(data){
			jQuery('#city').html(data)
		}
	});		
}

function get_locality(val){
	<?php if(isset($city) && $city!=''){?>
		var city		= '<?= $city?>';
	<?php }else{?>
		var city		= '';
	<?php }?>
	
	<?php if(isset($locality) && $locality!=''){?>
		var locality		= '<?= $locality?>';
	<?php }else{?>
		var locality		= '';
	<?php }?>
	
	if(val=='state'){
		var city		= '';
		var locality		 	= '';
	}
	
	<?php if(isset($type_slug) && $type_slug!=''){?>
		var l_type 	= '<?= $type_slug?>';
	<?php }else{?>
		var l_type 	= '';
	<?php }?>
	
	
	jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/get_locality");?>",
		dataType: 'html',
		data:'locality='+locality+'&city='+city+'&type='+l_type,
		success: function(data){
			jQuery('#locality').html(data)
		}
	});		
}
</script>