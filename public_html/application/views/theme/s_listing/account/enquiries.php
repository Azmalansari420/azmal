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
							<div class="row">
								<div class="col-lg-12">
									<?php if(count($enquiries) > 0){?>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-list" id="dataTables-example">
												<thead>
													<tr class="active">
														<th style="display:none">ID</th>
														<th>Profile Name</th>
														<th>Name</th>
														<th>Mobile No.</th>
														<th>Email ID</th>
														<th>Date</th>
														<th></th>
													</tr>
												</thead>
												<tbody>	
												<?php foreach($enquiries as $p){?>
													
													<tr>
														<th style="display:none"><?= $p->id;?></th>
														<td><?= $p->profile_name?></td>
														<td><?= $p->name?></td>
														
														<td><?= $p->mobile_no?></td>
														<td><?= $p->email_id?></td>
														<td><?= date('d-m-Y H:i:s', strtotime($p->added_on));?></td>
														<td>
													
															<a href="<?= base_url('profile/').'/'.$p->slug;?>" target="_blank" class="btn btn-xs btn-success btn-block">
																View Profile
															</a>
														</td>
													</tr>
												<?php }?>
											</tbody>  
										  </table>  
										</div>
									<?php }else{?>
										<h3>No Enquiries found!</h3>
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

<script src="<?php echo base_url();?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
	$('#dataTables-example').DataTable({
		responsive: true,
		"aaSorting": [[0, 'desc']]
	});
});
</script>   
