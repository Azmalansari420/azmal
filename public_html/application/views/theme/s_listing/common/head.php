<meta charset="UTF-8">
<meta name='dmca-site-verification' content='ejNUU09VWnBUNW1IQnE4akpKVGl3ZHNSeXFpcjhtNlNuclJVNlFKRlFYbz01' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php /*?><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"><?php */?>


<title><?php
		if(isset($meta_title) && $meta_title != ''){
			echo clean_display($meta_title);
		}else{
			echo (isset($title)) ? "{$site_title} : ".$title : $site_title;
		}?></title>
		
<meta name="description" content="<?= (isset($meta_description)) ? clean_display($meta_description) : ''?>">
<meta name="keywords" content="<?= (isset($meta_keywords)) ? clean_display($meta_keywords) : ''?>">


<?php /*?><link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet"><?php */?>


<link rel="icon" href="<?= base_url('assets/images');?>/favicon.png" type="image/x-png">

<?= $_theme->add_css()?>	

<?php /*?><script type="text/javascript">var _home_url = '<?= home_url()?>';</script><?php */?>

<?= $_theme->add_js()?>


<?php 
	$current_url = str_replace('index.php/', '', current_url());
	$current_url = str_replace('index.php', '', $current_url);
	$current_url = str_replace('.php', '', $current_url);
?>
<?php if(strpos($current_url, 'profile')!=0){?>
	<meta name="robots" content="noindex, nofollow">		
<?php }else{?>
	<meta name="robots" content="index, follow">
<?php }?>

<link href="<?= $current_url;?>" rel="canonical" />