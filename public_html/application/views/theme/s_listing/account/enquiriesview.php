<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Enquiry' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					<div class="row">
						<!-- Sidebar Column -->
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<?php include('menu.php');?>
						</div>
						<!-- Content Column -->
							
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<h2 class="heading">Your Institute Enquiry Details</h2>
							
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<b>Enquiry Type</b>
									<div><?php echo ucfirst($enquiries['enquiry_type']);?></div>
								</div>
								
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<b>Enquiry For</b>
									<div><?php echo ucfirst($enquiries['enquiry_for']);?></div>
								</div>	
								
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>				
							</div>
							
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<b>User Name</b>
									<div><?php echo ucfirst($enquiries['name']);?></div>
								</div>
								
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<b>Mobile No.</b>
									<?php if($enquiries['paid']==1){?>
										<div><?php echo $enquiries['mobile'];?></div>
									<?php }else{?>
										<div>91XXXXXXXXXX</div>
									<?php }?>
								</div>
								
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>						
							</div>
							
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<b>Email ID</b>
									<?php if($enquiries['paid']==1){?>
										<div><?php echo $enquiries['email_id'];?></div>
									<?php }else{?>
										<div>XXXXXXXXXX@gmail.com</div>
									<?php }?>
								</div>
								
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<b>City</b>
									<div><?php echo ucfirst($enquiries['city']);?></div>
								</div>
								
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>						
							</div>
							
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<b>Message</b>
									<div><?php echo $enquiries['message'];?></div>
								</div>
							</div>
						</div>
					 </div>
					
				</div>
			</div>
		</div>
	</div>
</div>

