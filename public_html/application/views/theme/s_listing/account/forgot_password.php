<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Forgot your Password' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
							<div class="form-box">
								<div class="box-heading">
									<i class="fa fa-sign-in"></i> <?= (isset($title)) ? $title: 'Forgot your Password?' ;?>
								</div>
								
								<p>Lost your password? Please enter your email id address. You will receive a link to create a new password via email.</p>
								<form id="forgot-password" action="" autocomplete="off" method="POST">
									<div class="row">
										<div class="col-sm-12">
										  <div class="form-group <?php echo (form_error('email_id')) ? 'has-error' : ''?>">
											<label for="email">Email ID <span class="req">*</span></label>
											<input type="text" class="form-control required-entry validate-email" id="email" value="<?php echo set_value('email_id'); ?>" name="email_id">
											<?php echo form_error('email_id');?>
										  </div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<button type="submit" class="btn-new">Reset password &nbsp; <i class="fa fa-arrow-right"></i></button>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
											<a class="" href="<?php echo base_url('account/login');?>">Login</a>
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
	jQuery("#forgot-password").validate();
});
</script>
    

