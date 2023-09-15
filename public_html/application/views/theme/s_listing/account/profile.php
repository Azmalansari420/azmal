<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Profile' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					
					<div class="row">
						 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<?php include('menu.php');?>
						</div>
						
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
				
							<form action="" method="post" id="profile-form" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-sm-6 col-xs-12">
										<div class="form-group <?= (form_error('name')) ? 'has-error' : ''?>">
											<label for="name">Name <span class="red">*</span></label>
											<input type="text" name="name" id="name" value="<?= $customer_d['name']; ?>" class="form-control required-entry" />
											<?= form_error('name'); ?>
										</div>
									</div>
									
									<div class="col-sm-6 col-xs-12">
										<div class="form-group <?= (form_error('mobile_no')) ? 'has-error' : ''?>">
											<label for="mobile_no">Mobile No. <span class="red">*</span></label>
											<input type="text" name="mobile_no" class="form-control required-entry" id="mobile_no" value="<?= $customer_d['mobile_no'];?>" />
											<?= form_error('mobile_no');  ?>    
										</div>
									</div>
									
								</div>
								
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group <?= (form_error('email_id')) ? 'has-error' : ''?>">
											<label for="email_id">Login Email ID</label>
											<input type="text" name="email_id" id="email_id" value="<?= $customer_d['email_id']; ?>" class="form-control required-entry" readonly="yes" />
											<?= form_error('email_id'); ?>
										</div>
									</div>
									
								</div>
								
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<button class="btn-new" id="f_save" type="submit">Update Profile</button> 
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
	jQuery("#profile-form").validate();
});


