<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Register' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					<div class="page-content"><?= (isset($description)) ? $description: '' ;?></div>
					
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">&nbsp;</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-box">
								<div class="box-heading">
									<i class="fa fa-sign-in"></i> Register
								</div>
								
								<form class="form-horizontal" action="" method="post" name="register_form" id="egister-form">
									<div class="signsup-forminner">
										
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group <?= (form_error('name')) ? 'has-error' : ''?>">
													<div class="col-sm-12 col-xs-12">
														<label for="name">Name <span class="red">*</span></label>
													   <input type="text" name="name" class="form-control required-entry" value="<?= set_value('name'); ?>" id="name" placeholder="Your Name" required />
													   <?= form_error('name'); ?>
												   </div>
												</div>
											</div>
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group <?= (form_error('mobile_no')) ? 'has-error' : '' ?>">
													<div class="col-sm-12 col-xs-12">
														<label for="mobile">Mobile No. <?php /*?><span class="red">*</span><?php */?></label>
														<input type="text" name="mobile_no" class="form-control <?php /*?>required-entry<?php */?>" value="<?= set_value('mobile_no'); ?>" placeholder="Mobile No." required />
														<?= form_error('mobile_no');  ?>    
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="form-group <?= (form_error('email_id')) ? 'has-error' : '' ?>">
													<div class="col-sm-12 col-xs-12">
														<label for="email_id">Login Email ID <span class="red">*</span></label>
														<input type="email" name="email_id" class="form-control required-entry validate-email" value="<?= set_value('email_id'); ?>" placeholder="Login Email ID" required />
														<?= form_error('email_id');  ?>    
													</div>
												</div>
												
											</div>
											
										</div>
										
										<div class="row">						
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group <?php echo (form_error('password')) ? 'has-error' : ''?>">
													<div class="col-sm-12 col-xs-12">
														<label for="password">Password <span class="req">*</span></label>
														<input type="password" class="form-control required-entry" minlength="6"   id="password" name="password" placeholder="Password" required />
														<?php echo form_error('password');?>
													</div>
												</div>
											</div>
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group <?php echo (form_error('cpassword')) ? 'has-error' : ''?>">
													<div class="col-sm-12 col-xs-12">
														<label for="cpassword">Confirm password <span class="req">*</span></label>
														<input type="password" class="form-control required-entry validate-cpassword" id="cpassword" name="cpassword" placeholder="Confirm password" required />
														<?php echo form_error('cpassword');?>
													</div>
												</div>
											</div>
										</div>
									
										
										<div class="form-group">
											<div class="col-md-3 col-sm-3 col-xs-3">
												<button type="submit" class="btn-new" id="submit_but">Register</button>
											</div>
											<div class="col-md-9 col-sm-9 col-xs-9 text-right"><br />	
												Already have a account?
												<a class="forget-link" href="<?= base_url('account/login'); ?>">Login here!</a>
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







<?php /*?><script type="text/javascript">
jQuery(function(){
	jQuery("#egister-form").validate();
});
</script><?php */?>