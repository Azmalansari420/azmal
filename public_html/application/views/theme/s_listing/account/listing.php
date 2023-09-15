<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Your Listing' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					
					<div class="row">
						 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<?php include('menu.php');?>
						</div>
						
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="row">
								<div class="col-lg-12">
									<div class="text-right">
										<a href="<?= base_url('account/listing_add');?>" class="btn-new">Add Listing</a> &nbsp;
									</div>
									<?php if(count($listing) > 0){?>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-list" id="dataTables-example">
												<thead>
													<tr class="active">
														<th style="display:none">ID</th>
														<th>Name</th>
														<th>Mobile No.</th>
														<th>Email ID</th>
														<th>Type</th>
														<th>Gender</th>
														<th>City</th>
														<?php /*?><th>Locality</th><?php */?>
														<th>Photo</th>
														<th>Status</th>
														<th></th>
													</tr>
												</thead>
												<tbody>	
												
												
												<?php foreach($listing as $p){?>
													
													<tr>
														<th style="display:none"><?= $p->id;?></th>
														<td><?= $p->name?></td>
														
														<td><?= $p->mobile_no?></td>
														<td><?= $p->email_id?></td>
														
														<td><?= $p->type;?></td>
														<td><?= $p->gender;?></td>
														<td><?= $p->city;?></td>
														<?php /*?><td><?= $p->locality;?></td><?php */?>
														
														
														<td>
															<?php if($p->image!=''){?>
																<img src="<?= base_url('media/uploads').$p->image;?>" class="img-responsive" width="50">
															<?php }?>
														</td>
														
														<td><?= $status_options[$p->status]?></td>
																		
														<td>
													
															<a href="<?= base_url('profile').'/'.$p->slug;?>" target="_blank" class="btn btn-xs btn-success btn-block">
																View
															</a>
															
															<a href="<?= base_url('account/listing_add').'/'.$p->id;?>" class="btn btn-xs btn-success btn-block">
																Edit
															</a>
														</td>
													</tr>
												<?php }?>
											</tbody>  
										  </table>  
										</div>
									<?php }else{?>
										<h3>No Listing found!</h3>
									<?php }?>
								</div>	
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>








    

<style type="text/css">
select.form-control{margin-bottom:5px;}
.dataTables_filter, .dataTables_paginate.paging_simple_numbers{text-align:right;}
</style>

<script src="<?= base_url();?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
	$('#dataTables-example').DataTable({
		responsive: true,
		"aaSorting": [[0, 'desc']]
	});
});
</script>