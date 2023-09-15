<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Reset Password' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<form class="form-horizontal" action="" method="post" name="signin-form" id="signin-form">
							<div class="signin-form-title">
							  <div class="col-lg-3 col-md-3 col-sm-3"> </div>
							  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="row">
								  <div class="entry-title">
									<h1><i class="fa fa-retweet"></i> <?php  echo $page_name?></h1>
								  </div>
								</div>
							  </div>
							  <div class="col-lg-3 col-md-3 col-sm-3"> </div>
							</div>
							<div class="signin-forminner col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
								<div class="form-group <?php echo (form_error('email_id')) ? 'has-error' : ''?>">
								<div class="col-md-12 col-sm-12 col-xs-12">
										<input type="text" name="email_id" class="form-control required-entry validate-email" value="<?php echo set_value('email_id'); ?>" placeholder="Email Id" />
									<?php echo form_error('email_id'); ?>
								</div>
								</div>
							  <div class="form-group">
								<div class="col-md-12 col-sm-12 col-xs-12">
								  <button type="submit" class="btn btn-primary">Submit</button>
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


<script type="text/javascript">
jQuery(function(){
	jQuery("#signin-form").validate();
});
</script>