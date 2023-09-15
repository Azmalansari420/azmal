<!DOCTYPE html>
<html lang="en">
<head>
   	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ? "Admin : ".$title : "Admin"?></title>
   	
   	<script type="text/javascript">
	var admin_url = '<?php echo admin_url()?>';
	var base_url = '<?php echo base_url()?>';
	</script>
   	
	<?php echo add_css()?>
	<?php echo add_js()?>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
</head>
	
<body>
<?php $nav_hidden = (isset($_COOKIE['nav_hidden']) && $_COOKIE['nav_hidden']) ? true : false;?>
<?php
$user_data = $session->userdata('admin');
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
?>
	
    <div class="page <?php echo $segment2; echo ($segment3)? ' '.$segment3: '';?>">
		<?php if(!isset($without_menu)){?>
			<header>
				<nav class="navbar-expand-lg">
					<button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#main_top_nav">&#9776;</button>
					<div class="collapse navbar-collapse" id="main_top_nav" style="justify-content:space-between">
						<a class="navbar-brand <?php echo ($nav_hidden) ? 'hide' : ''?>" href="<?php echo admin_url('dashboard');?>"><?php /*<img src="<?php echo base_url()?>assets/admin/images/logo.png" /><?php */?></a>
						<?php /*<ul class="nav navbar-nav navbar-header">
							<li class="nav-item">
								<a class="main-nav-link ico-link" href="<?php echo admin_url('dashboard');?>"><i class="ico">format_indent_decrease</i> <span class="sr-only">(current)</span></a>
							</li>
						</ul>
						
						<div class="nav notify-row" id="top_menu" style=""></div>
						<ul class="nav navbar-nav navbar-header navbar-alerts">
							<li>
								<a class="ico-link" href=""><i class="ico">hourglass_full</i></a>
							</li>
							<li>    
								<a class="ico-link" href=""><i class="ico">watch_later</i></a>
							</li>
							<li>
								<a class="ico-link" href=""><i class="ico">attach_file</i></a>
							</li>
							<li>
								<a class="ico-link" href=""><i class="ico">notifications</i></a>
							</li>
							<li>
								<a class="ico-link" href="<?php echo admin_url()?>reservations/unapproved"><i class="ico">notifications_active</i></a>
							</li>
						</ul>

						<form class="form-inline search-form">
							<input class="form-control" type="text" placeholder="Search" name="search" />
							<button class="search-button" type="submit"><i class="ico">search</i>Search</button>
						</form>*/?>
						
						<ul class="nav navbar-nav navbar-header admin-header-nav">
							<li class="navbar-notifications dropdown nav-item">
								<a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="ico" id="notification_icon">notifications_none</i>
									<span class="alert-note" id="notifications_note" style="display:none;"></span>
								</a>

								<div class="dash-notifications dropdown-menu dropdown-menu-right">
									
								</div>
							</li>
							<li>
								<a class="profile" href="<?php echo admin_url('profile')?>"><i class="ico">person</i><?php echo ucwords($user_data['first_name']);?></a>
							</li>
							<li><a href="<?php echo admin_url('profile/password')?>"><i class="ico">lock_outline</i> Password</a></li>
							<li><a href="<?php echo admin_url('logout')?>"><i class="ico">arrow_forward</i> Sign out</a></li>
						</ul>
					</div>
				</nav>
			</header>
			
			<?php $module = $this->uri->segment(2);?>
			<?php $module_sub = $this->uri->segment(3);?>
			<?php $module_action = $this->uri->segment(4);?>
			<?php $module_path = $module."/".$module_sub;?>
			<?php
			if($module_action && $module_action != ''){
				$module_path .= "/".$module_action;
			}
			?>
			
			<script type="text/javascript">
			jQuery(function(){
				jQuery('.nav-link.active').closest('li.nav-item').addClass('active');
			});
			</script>
			<div class="navigation-container <?php echo ($nav_hidden) ? 'hide hidden' : ''?>">
				<a href="" class="nav-logo <?php echo ($nav_hidden) ? 'active hide' : ''?>">
					<i class="ico navigation-menu">format_indent_increase</i><?php echo $site_title?>
				</a>
				<ul class="nav main-nav <?php echo ($nav_hidden) ? 'hide' : ''?>">
					<?php foreach($modules as $module_key => $data){?>
						<li data-path="<?php echo $data['path']?>" class="nav-item <?php echo (isset($data['childs'])) ? 'parent' : ''?> <?php echo ($module==$module_key) ? 'active' : ''?>">
							<?php
							if(array_key_exists('menu', $data) && $data['menu'] == false){
								continue;	
							}
							?>
							<?php if(isset($data['childs'])){?>
								
								<a class="nav-link <?php echo ($module==$module_key) ? 'active' : ''?>" href="javascript:void(0)">
									<i class="ico"><?php echo $data['icon']?></i>
									<?php echo $data['title']?>
								</a>
								<div class="sub-menu-container <?php echo array_key_exists('top_stick', $data) ? 'top-stick' : ''?>">
									<ul class="sub-menu">
										<?php foreach($data['childs'] as $child){?>
											<?php if(array_key_exists('heading', $child)){?>
												<li>
													<div class="sm-heading"><?php echo $child['heading']?></div>
												</li>

											<?php }elseif(array_key_exists('seperator', $child)){?>
												<li>
													<div class="sm-seperator"></div>
												</li>

											<?php }else{?>
												<li>
													<a class="nav-link <?php echo (rtrim($module_path, "/") == $child['path']) ? "active" : ''?>" href="<?php echo admin_url($child['path'])?>">
														<i class="ico"><?php echo (isset($child['icon'])) ? $child['icon'] : $data['icon']?></i>
														<?php echo $child['title']?>
													</a>
												</li>

											<?php }?>	
										<?php }?>
									</ul>
								</div>
								
							<?php }else{?>
							
								<a class="nav-link <?php echo ($module==$module_key) ? 'active' : ''?>" href="<?php echo admin_url($data['path'])?>">
									<i class="ico"><?php echo $data['icon']?></i>
									<?php echo $data['title']?>
								</a>
								
							<?php }?>
						</li>
					<?php }?>
				</ul>
			</div>
		<?php }?>
        
        <div class="main-content <?php echo ($nav_hidden) ? 'wide' : ''?>">
        	<div class="main">
            	<?php if(!isset($without_menu)){?>
					<?php if(strtolower($title) != 'dashboard'){?>
						<div class="row">
							<div class="col-lg-12">
								<ul class="breadcrumb">
									<?php $n=0; foreach($breadcrumbs as $link => $breadcrumb){$n++;?>
										<li class="breadcrumb-item <?php echo ($n==count($breadcrumbs)) ? 'active' : ''?>">
											<a href="<?php echo admin_url($link)?>">
												<?php if($n==1){?>
													<i class="ico">home</i>
												<?php }?>
												<?php echo $breadcrumb?>
											</a>
										</li>
									<?php }?>
								</ul>
							</div>
						</div>
					<?php }?>
					
					<div class="header-alert-container" id="header_alert_container">
						<?php if($this->session->flashdata('msg')){ ?>
							<div class="row">
								<div class="col-md-12">
									<div class="header-alert alert alert-success" role="alert">
										<span class="ico">done_all</span>
										<button type="button" class="close close-sm" data-dismiss="alert"><i class="ico">clear</i></button>
										<?php echo $this->session->flashdata('msg');?>
									</div>
								</div>
							</div>    
						<?php }?>
		
						<?php if($this->session->flashdata('error_msg')){ ?>
							<div class="row">
								<div class="col-md-12">
									<div class="header-alert alert alert-danger" role="alert">
										<span class="ico">error_outline</span>
										<button type="button" class="close close-sm" data-dismiss="alert"><i class="ico">clear</i></button>
										<?php echo $this->session->flashdata('error_msg');?>
									</div>
								</div>
							</div>
						<?php }?>
					</div>	
						
					<?php if(strtolower($title) != 'dashboard'){?>
						<div class="row">
							<div class="col-md-12">
								<h1 class="page-heading">
									<?php
									$icon = 'format_list_bulleted';
									if(array_key_exists($module, $modules) && array_key_exists('icon', $modules[$module])){
										$icon = $modules[$module]['icon'];
									}
									?>
									<i class="ico"><?php echo $icon?></i><?php echo $title?>
								</h1>
							</div>
						</div>
					<?php }?>
				<?php }?>
                    
                <div class="row">
                    <div class="col-lg-12">
                        <?php if(isset($form) && $form){?>
                            <?php echo $form?>
                        <?php }?>
            
                        <?php if(isset($grid) && $grid){?>
                            <?php if(isset($add_link) && $add_link){?>
                                <div align="right">
                                <br />
                                <a href="<?php echo $add_link?>" class="buttonM bGreen">Add</a>
                            </div>
                            <?php }?>
                            <?php echo $grid?>
                        <?php }?>