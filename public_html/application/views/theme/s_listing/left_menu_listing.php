<?php 
	$l_type			= '';
	$l_state		= '';
	$l_city			= '';
	$l_locality		= '';
	
	if(isset($_POST['type'])){
		$l_type		= $_POST['type'];
	}
	
	if(isset($_POST['state'])){
		$l_state	= $_POST['state'];
	}
	
	if(isset($_POST['city'])){
		$l_city	= $_POST['city'];
	}
	
	if(isset($_POST['locality'])){
		$l_locality	= $_POST['locality'];
	}
?>
<?php /*?><?php if($l_state=='' && $l_city==''){?>
	<div class="name"><?= ucwords(str_replace('_', ' ', $l_type))?></div>
<?php }?><?php */?>

	
	
<?php /*?><?php if($l_state!=''){?>
	<div class="near-box">
		<div class="name"><?= ucwords(str_replace('_', ' ', $l_type))?> in in <?= $l_state?></div>
	
		<ul class="near-menu">
			<?php foreach(get_cities($l_state) as $k => $c){?>
				<li><a class="<?= ($c->name==$l_city)? 'active': '';?>" href="<?= base_url($l_type.'/'.$c->slug);?>"><?= ucwords(str_replace('_', ' ', $l_type))?> in <?= $c->name?></a></li>
			<?php }?>
		</ul>
	</div>
<?php }?><?php */?>

<?php /*?><?php if($l_state!='' && $l_city==''){?>
	<h4 class="heading heading2">Frequent Searches</h4>
	<ul class="keywords-list">
		<?php foreach(get_keywords_by_state($l_state) as $key){?>
			<li><a href="<?= $key->url?>"><?= $key->name?></a></li>
		<?php }?>
	</ul>
<?php }?><?php */?>

<?php if($l_state!='' && $l_city!=''){?>
	<h4 class="heading heading2">Frequent Searches</h4>
	<ul class="keywords-list">
		<?php foreach(get_keywords($l_city) as $key){?>
			<li><a href="<?= $key->url?>"><?= $key->name?></a></li>
		<?php }?>
	</ul>
<?php }?>

	
<?php /*?><?php if($l_state!='' && $l_city!=''){?>
	<div class="near-box">
		<div class="name"><?= $l_city?></div>
	
		<ul class="near-menu">
			<?php foreach(get_locality($l_city) as $k => $c){?>
				<li><a class="<?= ($c->name==$l_locality)? 'active': '';?>" href="<?= base_url($l_type.'/'.$c->slug);?>"><?= ucwords(str_replace('_', ' ', $l_type))?> in <?= $c->name?></a></li>
			<?php }?>
		</ul>
	</div>
		
	<?php if(count(get_locality($l_city)) < 10){?>
		<div class="near-box">
			<div class="name"><?= $l_state?></div>
	
			<ul class="near-menu">
				<?php foreach(get_cities($l_state) as $k => $c){?>
					<li><a href="<?= base_url($l_type.'/'.$c->slug);?>"><?= ucwords(str_replace('_', ' ', $l_type))?> in <?= $c->name?></a></li>
				<?php }?>
			</ul>
		</div>
	<?php }?>
<?php }?><?php */?>
	
