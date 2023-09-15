<?php 
	$name					= '';
	$mobile_no				= $customer_d['mobile_no'];
	$email_id				= $customer_d['email_id'];
	$type					= '';
	$gender					= '';
	$age					= '';
	$bust_waist_hip			= '';
	$image					= '';
	$eye_color				= '';
	$hair_color				= '';
	$height					= '';
	$weight					= '';
	$language				= '';
	$ethnicity				= '';
	$nationality			= '';
	$website				= '';
	$state					= '';
	$city					= '';
	$locality				= '';
	$zip_code				= '';
	$about_us				= '';
	
	$services				= array();
	$availability			= array();
	$rates					= array();
		
	if(isset($listing->id)){
		$name					= $listing->name;
		$mobile_no				= $listing->mobile_no;
		$email_id				= $listing->email_id;
		$type					= $listing->type;
		$gender					= $listing->gender;
		$age					= $listing->age;
		$bust_waist_hip			= $listing->bust_waist_hip;
		$image					= $listing->image;
		$eye_color				= $listing->eye_color;
		$hair_color				= $listing->hair_color;
		$height					= $listing->height;
		$weight					= $listing->weight;
		$language				= $listing->language;
		$ethnicity				= $listing->ethnicity;
		$nationality			= $listing->nationality;
		$website				= $listing->website;
		$state					= $listing->state;
		$city					= $listing->city;
		$locality				= $listing->locality;
		$zip_code				= $listing->zip_code;
		$about_us				= $listing->about_us;		
		
		$services				= explode('=', $listing->services);
		$availability			= unserialize($listing->availability);
		$rates					= unserialize($listing->rates);
	}
	
	if(isset($_POST['name'])){
		$name					= set_value('name');
		$mobile_no				= set_value('mobile_no');
		$email_id				= set_value('email_id');
		$type					= set_value('type');
		$gender					= set_value('gender');
		$age					= set_value('age');
		$bust_waist_hip			= set_value('bust_waist_hip');
		$image					= set_value('image');
		$eye_color				= set_value('eye_color');
		$hair_color				= set_value('hair_color');
		$height					= set_value('height');
		$weight					= set_value('weight');
		$language				= set_value('language');
		$ethnicity				= set_value('ethnicity');
		$nationality			= set_value('nationality');
		$website				= set_value('website');
		$state					= set_value('state');
		$city					= set_value('city');
		$locality				= set_value('locality');
		$zip_code				= set_value('zip_code');
		$about_us				= set_value('about_us');
		
		$services				= set_value('services');
		$availability			= set_value('availability');
		$rates					= set_value('rates');
	}
	
	$type_a				= array('call_girls'=>"Call Girls", 'escorts'=>"Escorts", 'male_escorts'=>"Male Escorts");
	$gender_a			= array('Girl', 'Boy', 'Transgender');
	$availability_a		= array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	$rates_a			= array(1=>'Rate incal 1h', 2=>'Rate incal 2h', 3=>'Rate outcal 1h', 4=>'Rate outcal 2h', 5=>'Rate full night');
?>

<div class="page-account">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">   
				<div class="white-box"> 
					<h1 class="heading text-center"><?= (isset($title)) ? $title: 'Add Listing' ;?></h1>
					<div align="center"><div class="f-line"></div></div>
					
					
					<div class="row">
						 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<?php include('menu.php');?>
						</div>
						
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="form-box">
								<form action="" method="post" id="profile-form" enctype="multipart/form-data">
									
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?php echo (form_error('name')) ? 'has-error' : ''?>">
												<label>Name	<span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $name;?>" name="name" placeholder="Name" required/>
												<?= form_error('name');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?php echo (form_error('type')) ? 'has-error' : ''?>" >
												<label>Type  <span class="req">*</span></label>
												<select name="type" id="type" class="form-control required-entry" required>
													<option value="">Select</option>
													<?php foreach(get_type() as $k => $v){?>
														<option value="<?= $k?>" <?= ($k== $type) ? 'selected="selected"': '';?> ><?= $v?></option>
													<?php }?>
												</select>
												<?= form_error('type');?>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('mobile_no')) ? 'has-error' : ''?>">
												<label>Mobile No. <span class="req">*</span></label>
												<input type="number" class="form-control required-entry" value="<?= $mobile_no;?>" name="mobile_no" placeholder="Mobile No." required />
												<?= form_error('mobile_no');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('email_id')) ? 'has-error' : ''?>">
												<label>Email ID <span class="req">*</span></label>
												<input type="text" class="form-control validate-email required-entry" value="<?= $email_id;?>" name="email_id" placeholder="Email ID"  required/>
												<?= form_error('email_id');?>
											</div>
										</div>
										
									</div>
									
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?php echo (form_error('gender')) ? 'has-error' : ''?>">
												<label>Gender  <span class="req">*</span></label>
												<select name="gender" id="gender" class="form-control required-entry" required>
													<option value="">Select</option>
													<?php foreach($gender_a as $v){?>
														<option value="<?= $v?>" <?= ($v== $gender) ? 'selected="selected"': '';?> ><?= $v?></option>
													<?php }?>
												</select>
												<?= form_error('gender');?>
											</div>
										</div>
										
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
											<div class="form-group <?= (form_error('image')) ? 'has-error' : ''?>">
												<label>Photo</label>
												<input type="file" name="image" <?= ($image=='') ? 'class="required-entry" required': '';?>  />
												<?= form_error('image');?>
											</div>
										</div>
										
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
											<?php if($image!=''){?>
												<img src="<?= base_url('media/uploads').$image;?>" class="img-responsive" width="100">
											<?php }?>
										</div>
									</div>
									
									
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('age')) ? 'has-error' : ''?>">
												<label>Age <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $age;?>" name="age" placeholder="Age" required />
												<?= form_error('age');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('website')) ? 'has-error' : ''?>">
												<label>Website</label>
												<input type="text" class="form-control" value="<?= $website;?>" name="website" placeholder="Website" />
												<?= form_error('website');?>
											</div>
										</div>
										
										<?php /*?><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('bust_waist_hip')) ? 'has-error' : ''?>">
												<label>Bust waist hip</label>
												<input type="text" class="form-control" value="<?= $bust_waist_hip;?>" name="bust_waist_hip" placeholder="Bust waist hip" />
												<?= form_error('bust_waist_hip');?>
											</div>
										</div><?php */?>
									</div>
									
									
									<?php /*?><div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('eye_color')) ? 'has-error' : ''?>">
												<label>Eye color <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $eye_color;?>" name="eye_color" placeholder="Eye color" />
												<?= form_error('eye_color');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('hair_color')) ? 'has-error' : ''?>">
												<label>Hair color <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $hair_color;?>" name="hair_color" placeholder="Hair color" />
												<?= form_error('hair_color');?>
											</div>
										</div>
									</div><?php */?>
									
									<?php /*?><div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('height')) ? 'has-error' : ''?>">
												<label>Height <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $height;?>" name="height" placeholder="Height" />
												<?= form_error('height');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('weight')) ? 'has-error' : ''?>">
												<label>Weight <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $weight;?>" name="weight" placeholder="Weight" />
												<?= form_error('weight');?>
											</div>
										</div>
									</div><?php */?>
									
									<?php /*?><div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('language')) ? 'has-error' : ''?>">
												<label>Language <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $language;?>" name="language" placeholder="Language" />
												<?= form_error('language');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('ethnicity')) ? 'has-error' : ''?>">
												<label>Ethnicity <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $ethnicity;?>" name="ethnicity" placeholder="Ethnicity" />
												<?= form_error('ethnicity');?>
											</div>
										</div>
									</div><?php */?>
									
									<?php /*?><div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('nationality')) ? 'has-error' : ''?>">
												<label>Nationality <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $nationality;?>" name="nationality" placeholder="Nationality" />
												<?= form_error('nationality');?>
											</div>
										</div>
										
										
									</div><?php */?>
									
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group <?php echo (form_error('about_us')) ? 'has-error' : ''?>">
												<label>About Us <span class="req">*</span></label>
												<textarea class="form-control required-entry" name="about_us" placeholder="Product Description" rows="4" required><?= $about_us;?></textarea>
												<?php echo form_error('about_us');?>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h3 align="center">Location Details</h3>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?php echo (form_error('state')) ? 'has-error' : ''?>">
												<label>State  <span class="req">*</span></label>
												<select name="state" id="state" class="form-control required-entry" required>
													<option value="">Select</option>
													<?php foreach(get_states() as $v){?>
														<option value="<?= $v?>" <?= ($v== $state) ? 'selected="selected"': '';?> ><?= $v?></option>
													<?php }?>
												</select>
												<?= form_error('state');?>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?php echo (form_error('city')) ? 'has-error' : ''?>">
												<label>City  <span class="req">*</span></label>
												<select name="city" id="city" class="form-control required-entry" required>
													<option value="">Select</option>
												</select>
												<?= form_error('city');?>
											</div>
										</div>
									</div>
									
									<div class="row">
										<?php /*?><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?php echo (form_error('locality')) ? 'has-error' : ''?>">
												<label>Locality  <span class="req">*</span></label>
												<select name="locality" id="locality" class="form-control required-entry">
													<option value="">Select</option>
												</select>
												<?= form_error('locality');?>
											</div>
										</div><?php */?>
										
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="form-group <?= (form_error('zip_code')) ? 'has-error' : ''?>">
												<label>Zip Code <span class="req">*</span></label>
												<input type="text" class="form-control required-entry" value="<?= $zip_code;?>" name="zip_code" placeholder="Zip Code" required />
												<?= form_error('zip_code');?>
											</div>
										</div>
									</div>
									
									<?php /*?><div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h3 align="center">Availability Details</h3>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group <?php echo (form_error('availability')) ? 'has-error' : ''?>">
												<label>Availability  <span class="req">*</span></label>
												<div class="row">
													<?php if(count($availability)!=0){?>
														<?php foreach($availability as $k => $a){?>
															<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
																<div class="form-group">
																	<b><?= $k?></b>
																	<input type="text" name="availability[<?= $k?>]" value="<?= $a?>" class="form-control"> 
																</div>
															</div>
														<?php }?>
													
													<?php }else{?>
														<?php foreach($availability_a as $k => $a){?>
															<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
																<div class="form-group">
																	<b><?= $a?></b>
																	<input type="text" name="availability[<?= $a?>]" value="" class="form-control">
																</div>
															</div>
														<?php }?>
													<?php }?>
												</div>
												<?= form_error('availability');?>
											</div>
										</div>
									</div><?php */?>
									
									
									<?php /*?><div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h3 align="center">Rates Details</h3>
										</div>
									</div><?php */?>
									
									<?php /*?><div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group <?php echo (form_error('rate')) ? 'has-error' : ''?>">
												<label>Availability  <span class="req">*</span></label>
												<div class="row">
													<?php if(count($rates)!=0){?>
														<?php foreach($rates as $k => $a){?>
															<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
																<div class="form-group">
																	<b><?= $rates_a[$k]?></b>
																	<input type="number" name="rate[<?= $k?>]" value="<?= $a?>" class="form-control"> 
																</div>
															</div>
														<?php }?>
													
													<?php }else{?>
														<?php foreach($rates_a as $k => $a){?>
															<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
																<div class="form-group">
																	<b><?= $a?></b>
																	<input type="text" name="rate[<?= $k?>]" value="" class="form-control">
																</div>
															</div>
														<?php }?>
													<?php }?>
												</div>
												<?= form_error('rate');?>
											</div>
										</div>
									</div><?php */?>
									
									<?php /*?><div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h3 align="center">Availability Details</h3>
										</div>
									</div><?php */?>
									
									<?php /*?><div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group <?php echo (form_error('services')) ? 'has-error' : ''?>">
												<label>Services offered  <span class="req">*</span></label>
												<div class="row">
													<?php foreach($get_services as $k => $a){ ?>
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															<label>
																<input type="checkbox" name="services[]" value="<?= $a?>" <?= (in_array($a, $services))? 'checked="checked"': '';?> style="width:15px; height:15px;">
																<?= $a?>
															</label>
														</div>
													<?php }?>
												</div>
												<?= form_error('services');?>
											</div>
										</div>
									</div><?php */?>
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h3 align="center">Photo Gallery</h3>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<div class="offer-gallery">
													<?php foreach($gallery_images as $gallery_image){?>
														<div style="float:left; margin:10px 10px 10px 0px; border:1px solid #aaa; padding:5px;">
															<div style="max-height:100px; max-width:100px; min-height:100px; min-width:100px; overflow:hidden;">
																<img src="<?= base_url()?>media/uploads<?= $gallery_image->image_url?>" width="100" />
															</div>
															<label style="display:block"><input type="checkbox" value="1" name="delete[<?= $gallery_image->id?>]"> Delete Image</label>
															<input type="hidden" value="<?= $gallery_image->image_url?>" name="delete_url[<?= $gallery_image->id?>]">
															<input type="hidden" value="<?= $gallery_image->id?>" name="saved_image[<?= $gallery_image->id?>]">
														</div>
													<?php }?>
													
												</div>
											</div>
											
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 gallery-bar">
													<div class="form-group">
														<div class="gallery-div">
															<input type="file" name="gallery_1" />
														</div>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form-group">
														<input type="hidden" name="gallery_count" id="gallery_count" value="1" />
														<button class="btn btn-primary" type="button" onclick="return add_gallery_image()">Add New Image</button>
													</div>
												</div>
											</div>
			
										</div>
									</div>
									
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
											<button class="btn-new" type="submit">
												<span>Save Listing</span>
											</button>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="right">&nbsp;</div>
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







<link href="<?= base_url('assets/css/select2.min.css')?>" rel="stylesheet" />
<script src="<?= base_url('assets/js/select2.min.js')?>"></script>


<script>
jQuery(function(){
	jQuery('#state').css('width', '100%');
	
	window.setTimeout(
		function(){
			get_cities();
		},
	1000);	
	
	window.setTimeout(
		function(){
			get_locality();
		},
	1500);	
	
	
	jQuery('body').on('change', '#state', function(){
		get_cities();
	});
	
	jQuery('body').on('change', '#city', function(){
		get_locality();
	});
});



function get_cities(){
	var city			= '<?= $city ?>';
	var state		 	= jQuery('#state').val();
	
	jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/get_cities2");?>",
		dataType: 'html',
		data:'city='+city+'&state='+state,
		success: function(data){
			jQuery('#city').html(data)
		}
	});		
}

function get_locality(){
	var locality			= '<?= $locality ?>';
	var city			 	= jQuery('#city').val();
	
	jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/get_locality2");?>",
		dataType: 'html',
		data:'locality='+locality+'&city='+city,
		success: function(data){
			jQuery('#locality').html(data)
		}
	});		
}


jQuery("#city").select2({
    placeholder: "Select a City",
    allowClear: true
});

jQuery("#state").select2({
    placeholder: "Select a State",
    allowClear: true
});

jQuery("#locality").select2({
    placeholder: "Select a Locality",
    allowClear: true
});
</script>


<script type="text/javascript">
_num = 1;
function add_gallery_image(){
	_num = _num+1;
	html = '<div class="gallery-div" style="margin-bottom:10px;">';
		html += '<input type="file" name="gallery_'+_num+'" />';
	html += '</div>';
	
	jQuery('.gallery-bar').append(html);
	jQuery('#gallery_count').val(_num);
	return false;
}
</script>


<?php /*?><script type="text/javascript">
jQuery(function(){
	$("#profile-form").validate();
});
</script><?php */?>