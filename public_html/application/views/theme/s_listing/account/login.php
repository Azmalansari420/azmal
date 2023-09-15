<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($page_title)) ? $page_title: 'Login' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					<div class="page-content"><?= (isset($description)) ? $description: '' ;?></div>
					
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">&nbsp;</div>
						
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-box">
								<div class="box-heading">
									<i class="fa fa-sign-in"></i> Login
								</div>
								
								<form class="form-horizontal" action="" method="post" name="login_form" id="login-form">
									<div class="signsup-forminner">
										<div class="form-group <?php echo (form_error('email_id')) ? 'has-error' : ''?>">
											<div class="col-sm-12 col-xs-12">
												<label for="email_id">Login Email ID <span class="red">*</span></label>
											   <input type="text" name="email_id" class="form-control required-entry" value="<?php echo set_value('email_id'); ?>" id="email_id" placeholder="Email Id" />
											   <?php echo form_error('email_id'); ?>
										   </div>
										</div>
									  
										<div class="form-group <?php echo (form_error('password')) ? 'has-error' : '' ?>">
											<div class="col-sm-12 col-xs-12">
												<label for="password">Password <span class="red">*</span></label>
												<input type="password" name="password" minlength="6" class="form-control required-entry" id="password" value="<?php echo set_value('password'); ?>" placeholder="Password" />
												<?php echo form_error('password');  ?>    
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3 col-sm-3 col-xs-3">
												<button type="submit" class="btn-new">Login</button>
											</div>
											<div class="col-md-9 col-sm-9 col-xs-9 text-right">
												<a class="forget-link" href="<?php echo base_url('account/forgot_password'); ?>">Forgot your password?</a>
											</div>
										</div>
			
										<div class="row">
											<div class="col-sm-12 col-md-12 col-xs-12" align="right">
												Don't have a account
												<a class="forget-link" href="<?php echo base_url('account/register'); ?>">create account!</a>
											</div>
										</div>
			
									</div>
								</form>
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
	jQuery("#login-form").validate();
});
</script>