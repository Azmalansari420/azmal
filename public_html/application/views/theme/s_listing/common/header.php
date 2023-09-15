<?php
if(base_url()=='https://www.sonababes.com/'){
	if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
		$redirect_url = "https://www." . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location: $redirect_url");
		exit();
	}
	
	$c_url	=  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	/*if (strstr( $c_url, 'www.')){
		$redirect_url = str_replace('www.', '', "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		header("Location: $redirect_url");
		exit();
	}*/
	
	if (strpos($c_url, 'www.') !== false){}else{
		$redirect_url = "https://www." . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$redirect_url =  str_replace('www.www.', 'www.', $redirect_url);
		header("Location: $redirect_url");
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php include $_theme->get_template('common/head');?>
	
	<?= get_setting('google-header-code');?>
	
</head>
<body>

<?php $c_userdata	 = $this->session->userdata('customer');?>

<?php if(isset($page) && $page=='home'){?>
	<div class="home-header">	
		<header class="header-bar">
			<div class="container">
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
						<div class="header-logo" align="center">
							<a href="<?= base_url();?>">
								<img width="200" height="50" src="<?= base_url()?>assets/images/logo-home.png?v=1" class="img-responsive" alt="Sonababes">
							</a>
						</div>
					</div>
					
					<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 col-menu">	
						<ul class="header-menu">
							
							<?php if($c_userdata==''){?>
								<li><a href="<?= base_url();?>account/login"> Log In</a></li>
								<li><a href="<?= base_url();?>account/register"> Register</a></li>
								<li><a href="<?= base_url();?>account/login"  class="ad-btn"><span class="glyphicon glyphicon-plus" ></span> Post Your Ad</a></li>
							<?php }else{?>
								<li><a href="<?= base_url('account/dashboard');?>">  <?= $c_userdata['username']?></i></a></li>
								<li><a href="<?= base_url('logout');?>"> Logout</a> </li>
								<li><a href="<?= base_url();?>account/listing_add" class="ad-btn"><span class="glyphicon glyphicon-plus" ></span>  Post Your Ad</a></li>
							<?php }?>
							
							
						</ul>
					</div>
				</div>
			</div>
		</header>
		
		<div class="home-banner-bar">
			<div class="home-banner">
				<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-2 col-xs-12"></div>
					<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
						<div class="banner-heading">Let's Explore our Directory of <span>Call Girls and Male Escorts</span> ads</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
						<div class="home-search-box">
							<form action="" method="post" id="header-search">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<select id="h_type" class="form-control required-entry" required <?php /*?>onChange="location = this.value;"<?php */?>>
												<option value="">Select Profile Types</option>
												<?php foreach(get_type('no') as $k => $v){?>
													<option <?= (isset($type_slug) && $type_slug==$k)? 'selected="selected"': '';?> value="<?= base_url($k)?>"><?= $v?></option>
												<?php }?>
											</select>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<select id="state" class="form-control required-entry" required>
												<option value="" >Select State</option>
												<?php foreach(get_states() as $k => $v){?>
													<?php if($k!=''){?>
														<option <?= (isset($state) && $state==$v)? 'selected="selected"': '';?>  value="<?= $v?>" ><?= $v?></option>
													<?php }?>
												<?php }?>
											</select>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<select id="city" class="form-control required-entry" required <?php /*?>onChange="location = this.value;"<?php */?>>
												<option value="">Select City</option>
											</select>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<button type="submit" onClick="get_list()" class="btn btn-info btn-lg btn-block ">Search</button>
										</div>
									</div>
									
									<?php /*?><div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
										<div class="form-group">
											<select name="locality" id="locality" class="form-control required-entry" onchange="location = this.value;">
												<option value="" >Select Locality</option>
											</select>
										</div>
									</div><?php */?>
								</div>
							</form>
						</div>
					</div>
					
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
					
					</div>
					
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="type-box-bar">
							<p>The most <span>popular categories</span> picked by customers around the world:</p>
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
									<a class="type-box active" href="<?= base_url('call-girls');?>">
										<img src="<?= base_url()?>assets/images/girl.png" width="64" height="64" class="img-responsive" alt="girl">
										<div>Call Girls</div>
									</a>
								</div>
								
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
									<a class="type-box active" href="<?= base_url('male-escorts');?>">
										<img src="<?= base_url()?>assets/images/male.png" width="64" height="64" class="img-responsive" alt="male">
										<div>Male Escorts</div>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
			</div>
			
			</div>
			
			
		</div>
		
		
	</div>
<?php }else{?>
	<div class="<?php /*?>home-header<?php */?>">
		<header class="header-bar">
			<div class="container">
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
						<div class="header-logo" align="center">
							<a href="<?= base_url();?>">
								<img width="200" height="50" src="<?= base_url()?>assets/images/logo.png?v=1" class="img-responsive" alt="Sonababes">
							</a>
						</div>
					</div>
					
					<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 col-menu">	
						<ul class="header-menu">
							
							<?php if($c_userdata==''){?>
								<li><a href="<?= base_url();?>account/login"> Log In</a></li>
								<li><a href="<?= base_url();?>account/register"> Register</a></li>
								<li><a href="<?= base_url();?>account/login"  class="ad-btn"><span class="glyphicon glyphicon-plus" ></span> Post Your Ad</a></li>
							<?php }else{?>
								<li><a href="<?= base_url('account/dashboard');?>">  <?= $c_userdata['username']?></i></a></li>
								<li><a href="<?= base_url('logout');?>"> Logout</a> </li>
								<li><a href="<?= base_url();?>account/listing_add" class="ad-btn"><span class="glyphicon glyphicon-plus" ></span> Post Your Ad</a></li>
							<?php }?>
							
							
						</ul>
					</div>
				</div>
			</div>
		</header>
	</div>
	
	<?php if(isset($breadcrumbs) && is_array($breadcrumbs) && !empty($breadcrumbs)){?>
		<div class="breadcrumb-bar">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<ul class="breadcrumb">
							<?php // if(count($breadcrumbs)!=2){?>
								<li><a href="<?= base_url();?>">Home</a></li>&nbsp; 
							<?php //}?>
							<?php $bi	= 0;?>
							<?php foreach($breadcrumbs as $k_slug => $name){ $bi++;?>
								<?php if($k_slug!='/'){?>
									<li><a href="<?= base_url().$k_slug;?>"><?= $name?></a></li>
								<?php }?>
							<?php }?>
						</ul>
					</div>
					
					<?php /*?><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="text-right">
							<a href="mailto:lucky24024@gmail.com">Lucky24024@gmail.com</a>
						</div> 
					</div><?php */?>
				</div>
			</div>
		</div>
	<?php }?>

<?php }?>