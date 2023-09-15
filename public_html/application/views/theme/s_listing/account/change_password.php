<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Change assword' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					<div class="row">
						 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<?php include('menu.php');?>
						</div>
						
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							
							<form class="form-horizontal" action="" method="post" name="update-form" id="update-form" enctype="multipart/form-data">
								<div class="row form-group <?= (form_error('old_password')) ? 'has-error' : '' ?>">
									<label class="col-sm-4 control-label">Old Password <span class="req">*</span></label>
									<div class="col-sm-5">
										<input type="password" name="old_password" minlength="6"  class="form-control required-entry" id="old_password" placeholder="Old Password">
										<?= form_error('old_password');  ?> 
									</div>
								</div>
							  
								<div class="row form-group <?= (form_error('new_password')) ? 'has-error' : '' ?>">
									<label class="col-sm-4 control-label">New Password <span class="req">*</span></label>
									 <div class="col-sm-5">
										<input type="password" name="new_password" minlength="6"  class="form-control required-entry" id="password" placeholder="New Password">
										<?= form_error('new_password');  ?> 
									</div>
								</div>
							  
								<div class="row form-group <?= (form_error('confirm_password')) ? 'has-error' : '' ?>">
									<label class="col-sm-4 control-label">Confirm Password<span class="req">*</span></label>
									 <div class="col-sm-5">
										<input type="password" name="c_password" minlength="6"  class="form-control required-entry validate-cpassword" id="c_password" placeholder="Confirm Password">
										<?= form_error('c_password');  ?>
									</div>
								</div>
							  
								<div class="row">
									<div class="col-sm-4">&nbsp;</div>
									<div class="col-sm-4">
										<button type="submit" class="btn-new">Submit</button>
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



   






<script type="text/javascript">
jQuery(function(){
	jQuery("#update-form").validate();
});
</script>