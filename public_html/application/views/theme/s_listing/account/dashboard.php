<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Dashboard' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					
					<div class="row">
						 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<?php include('menu.php');?>
						</div>
						
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="dashboard">
									<div class="row">
										<div class="col-lg-6 col-xs-6">
											<a href="<?= base_url('account/profile');?>">
												<div class="small-box bg-red">
													<div class="inner">
														<h3>Your Profile</h3>
														<p>&nbsp;</p>
													</div>
													<div class="icon"><i class="ion fa fa-user"></i></div>
													<b class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></b>
												</div>
											</a>
										</div>
										
										<div class="col-lg-6 col-xs-6">
											<a href="<?= base_url('account/change_password');?>">
												<div class="small-box bg-green">
													<div class="inner">
														<h3>Change Password</h3>
														<p>&nbsp;</p>
													</div>
													<div class="icon"><i class="ion fa fa-users"></i></div>
													<b class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></b>
												</div>
											</a>
										</div>
										
										<div class="col-lg-6 col-xs-6">
											<a href="<?= base_url('account/listing');?>">
												<div class="small-box bg-yellow">
													<div class="inner">
														<h3><?= ($total_profiles)?></h3>
														<p>Total Your Listing</p>
													</div>
													<div class="icon"><i class="ion fa fa-list"></i></div>
													<b class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></b>
												</div>
											</a>
										</div>
										
										<div class="col-lg-6 col-xs-6">
											<a href="<?= base_url('account/enquiries');?>">
												<div class="small-box bg-red">
													<div class="inner">
														<h3><?= $total_enquiries;?></h3>
														<p>Total Your Enquiries</p>
													</div>
													<div class="icon"><i class="ion fa fa-envelope"></i></div>
													<b class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></b>
												</div>
											</a>
										</div>
										
									</div>
									
								</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




