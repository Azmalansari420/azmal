<div class="animate_up delay-025s animated fadeInUp thumbnail-bar contact-page">
	<div class="container">
		<?php /*?><h1 class="entry-title"><?= $data->title?></h1><?php */?>
		<?php /*?><?= clean_display($data->content)?>				<?php */?>
		
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
				<div class="white-bg"> 
					<div class="row">
						<div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">    
							<h1 class="page-title"><?= $data->title?></h1>
							<div class="page-content"><?= clean_display($data->content)?></div>
						</div>
						
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">    
							<form action="<?= base_url('submit_form/enquiries')?>" id="contact_us-form" class="validate-form"  method="post">
								<input type="hidden" id="current_url" name="current_url" value="<?= base_url(uri_string())?>" class="form-control required-entry">
								<h2 class="page-title"><i class="fa fa-envelope"></i> Contact Form</h2>
								
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label for="name">Your name <samp class="req">*</samp></label>
											<input type="text" id="name" name="name" class="form-control" required="required" />
										</div>
									</div>
									
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label for="number">Contact Numbers <samp class="req">*</samp></label>
											<input type="text" id="number" class="form-control" name="mobile" required="required" />
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label for="email_id">E-mail ID <samp class="req">*</samp></label>
									<input type="email" id="email_id" name="email" class="form-control" required="required" />
								</div>
				
								<div class="form-group">
									<label for="subject">Subject <samp class="req">*</samp></label>
									<input type="text" id="subject" name="subject" class="form-control" required="required" />
								</div>
								
								<div class="form-group">
									<label for="message">Message  <samp class="req">*</samp></label>
									<textarea id="message" class="form-control" rows="3" name="message" required="required"></textarea>
								</div>
								
								<div class="form-group">
									<button class="btn btn-success" type="submit">Send Message</button>
								</div>													
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
			
	</div>
</div>






