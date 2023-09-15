<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Forgot your Password?' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
							<div class="form-box">
								<?php
									$date 		= date('Y-m-d H:i:s'); 
									$today_date =  date('Y-m-d H:i:s', strtotime($date. ' - 48 hours'));
									$today_date =  strtotime($today_date);
								?>	
								
								 <?php if(!isset($customer) || strtotime($customer->reset_datetime) <= $today_date){?>
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="entry-title">
												<h1>
													<i class="fa fa-retweet"></i> 
													The link has expired. Please try again with a new link <a href="<?php echo base_url('account/login');?>">Go Back</a>
												</h1>
											</div>
										</div>
									</div>
								   
								<?php }else{?>
								
									<div class="box-heading">
										<i class="fa fa-sign-in"></i> <?= (isset($page_title)) ? $page_title: 'Reset your password?' ;?>
									</div>
								
									<form id="signin-form" name="signin-form" action="" autocomplete="off" method="POST">
										<div class="row">
											<div class="col-sm-12">
											  <div class="form-group">
												<b>Email Address</b>	  
												<div><?php echo $customer->email_id;?></div>
											  </div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-12">
											  <div class="form-group <?php echo (form_error('new_password')) ? 'has-error' : ''?>">
												<b>New Password <span class="req">*</span></b>	
												<input type="password" name="new_password" id="password" class="form-control required-entry" value="<?php echo set_value('new_password'); ?>" minlength="6"  maxlength="25" placeholder="New Password" />
												<?php echo form_error('new_password');?>
											  </div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-12">
											  <div class="form-group <?= (form_error('new_password')) ? 'has-error' : ''?>">
												<b>Confirm Password <span class="req">*</span></b>
												<input type="password" name="confirm_password" id="confirm_password" class="form-control required-entry validate-cpassword" value="<?= set_value('confirm_password'); ?>" minlength="6" maxlength="25" placeholder="Confirm Password" />
												 <?= form_error('confirm_password'); ?>
											  </div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<button type="submit" class="btn-new">Change Password &nbsp; <i class="fa fa-arrow-right"></i></button>
											</div>
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
												<a class="" href="<?php echo base_url('account/login');?>">Login</a>
											</div>
										</div>
									</form>
								<?php }?>
							</div>
						</div>
			
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript">
jQuery(function(){
	jQuery("#signin-form").validate();
});
</script>