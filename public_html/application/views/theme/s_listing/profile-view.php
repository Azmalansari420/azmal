<?php 
$p_image				= base_url('media/uploads').$data->image;
$availability_a			= array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$rates_a				= array(1=>'Rate incal 1h', 2=>'Rate incal 2h', 3=>'Rate outcal 1h', 4=>'Rate outcal 2h', 5=>'Rate full night');
?>

<div class="profile-page">
	<div class="container p-info">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h1 class="heading heading1"><?= $data->name;?></h1>
				<div><div class="f-line f-line1"></div></div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="white-box">
					<div class="xzoom-container" align="center">
						<div class="view" align="center">
							<?php if($data->image!=''){?>
								<img class="xzoom" id="xzoom-default" src="<?= $p_image?>" xoriginal="<?= $p_image?>" alt="<?= $data->name;?>" width="238" height="390" />
							<?php }else{?>
								<?php foreach($gallery as $k => $g){?>
									<?php $image_url = base_url('media/uploads').$g->image_url;?>
									<img class="xzoom" id="xzoom-default" src="<?= $image_url?>" xoriginal="<?= $image_url?>" width="238" height="390" alt="<?= $data->name;?>" />
									<?php if($k==0){ break; }?>
								<?php } ?>
							<?php } ?>
						</div>
						<div class="xzoom-thumbs">
							<?php if($data->image!=''){?>
								<a href="<?= $p_image?>"><img class="xzoom-gallery" width="238" height="390" src="<?= $p_image?>"  xpreview="<?= $p_image?>" title="<?= $data->name?>" alt="<?= $data->name;?>"></a>
							<?php }?>
							
							<?php foreach($gallery as $k => $g){?>
								<?php $i_u = base_url('media/uploads').$g->image_url.'?v=1';?>
								<?php if($k==0 && $data->image==''){?>
									<a href="<?= $i_u?>"><img class="xzoom-gallery" width="238" height="390" src="<?= $i_u?>"  xpreview="<?= $i_u?>" title="<?= $data->name?>" alt="<?= $data->name;?>"></a>
								<?php }else{ ?>
									<a href="<?= $i_u;?>"><img class="xzoom-gallery" width="238" height="390" src="<?= $i_u;?>" title="<?= $data->name?>" alt="<?= $data->name;?>"></a>
								<?php } ?>
							<?php } ?>
					  </div>
					</div>
				</div>
				
			</div>
			
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="white-box">
					<div class="mobile-whatapp_no">
						
						<h3 class="text-center">Contact Advertiser</h3><br />
						<div class="row">
							<?php /*?><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
								<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal_enquiry">Enquiry Now</button>
							</div><?php */?>
								
							<?php if($data->mobile_no!=''){?>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<a class="btn btn-primary btn-block" href="tel:<?= $data->mobile_no?>"> <i class="fa fa-phone"></i> Phone</a>
								</div>
							<?php }?>
							
							<?php if($data->whatapp_no!=''){?>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							
									<a class="btn btn-success btn-block" href="https://api.whatsapp.com/send?phone=<?= $data->whatapp_no?>&amp;text=Hi" target="_blank"> <i class="fa fa-whatsapp"></i> Whatsapp</a>
								</div>
							<?php }?>
						</div>
					</div>
				</div>
				
				<div class="white-box">
					<h2>About <?= $data->name;?></h2>
					<p><?= $data->about_us;?></p>
				</div>
			</div>
		</div>
	</div>
	
</div>




<?php /*?><div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 align="center" class="modal-title" id="myModalLabel">Enquiry</h4>
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<form method="post" id="enquiry-form" name="enquiry-form" action="<?= base_url('enquiry_mail/profile_enquiry');?>">
							<input type="hidden" name="current_url" value="<?= base_url(uri_string()); ?>" />
							
							<input type="hidden" name="customer_id" value="<?= $data->customer_id;?>" />
							<input type="hidden" name="profile_id" value="<?= $data->id;?>" />
							<input type="hidden" name="state" value="<?= $data->state;?>" />
							<input type="hidden" name="city" value="<?= $data->city;?>" />
							<input type="hidden" name="locality" value="<?= $data->locality;?>" />
							
							<input type="hidden" name="profile_name" value="<?= $data->name;?>" />
							<input type="hidden" name="slug" value="<?= $data->slug;?>" />
							
							
						
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div class="form-group">
										<label>Name <span class="req">*</span></label>
										<input type="text" class="form-control required-entry" name="name" placeholder="Full Name">
									</div>
								</div>
								
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div class="form-group">
										<label>Mobile <span class="req">*</span></label>
										<input type="number" class="form-control required-entry" name="mobile_no" placeholder="Mobile Number">
									</div>
								</div>
							</div>
						
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>E-Mail Address</label>
										<input type="text" class="form-control validate-email" name="email_id" placeholder="Enter E-Mail Address">
									</div>
								</div>
							</div>
						
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Message <span class="req">*</span></label>
										<textarea name="message" id="message" placeholder="Enter Your Message" class="form-control required-entry" rows="3"></textarea>
									</div>
								</div>
							</div>
						
					   
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<button type="submit" class="btn-new">Enquiry Now</button>
								</div>
							</div>
						
						</form>
					</div>
			   </div>
        	</div>
        </div>
        <!-- /.modal-content -->
    </div>
</div><?php */?>


<div class="same-list-bar" data-nosnippet>
	<div class="container">
		
		<?php if(count($listing)){?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="heading text-center">Profiles from same <span>location</span></div>
					<div align="center"><div class="f-line"></div></div>
				</div>
			</div>
		<?php }?>
		
		<div class="row" >
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div id="same-list">
					<?php foreach($listing as $s){?>
						<div class="related-box">
							<div class="img" align="center">
								<a href="<?= base_url('profile/').'/'.$s->slug;?>">
									<img src="<?= base_url('media/uploads').$s->image;?>"  class="img-responsive" alt="<?=$s->name?>">
								</a>
							</div>
							
							<?php /*?><div class="name">
								<a href="<?= base_url('profile').'/'.$s->slug;?>"><?= substr($s->name, 0 , 30)?></a>
							</div><?php */?>	
								
							<?php /*?><div class="more-info">	
								<a href="<?= base_url('profile/').'/'.$s->slug;?>">	
									<?= $s->gender?> / <?= $s->age?> Years
								</a>
							</div><?php */?>
						</div>
					<?php }?>
				</div>
			</div>
		</div>
		
	</div>
</div>



<?php //print_r($listing);?>

<script>
jQuery('#same-list').slick({
  //centerMode: true,
  //centerPadding: '60px',
  speed: 300,
  autoplay: true,
  autoplaySpeed: 2000,
  		
  slidesToShow: 5,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
       // centerMode: true,
       // centerPadding: '40px',
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
       // centerMode: true,
       // centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});
</script>




<link rel="stylesheet" href="<?= base_url();?>assets/xzoom/css/xzoom.css?v=1.1" type="text/css" />
<script type="text/javascript" src="<?= base_url();?>assets/xzoom/js/xzoom.min.js?v=2.1.4"></script>


<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.xzoom, .xzoom-gallery').xzoom({zoomWidth: 400, title: false, tint: '#333', Xoffset: 20});
		
});
jQuery(function(){
	<?php /*?>jQuery("#enquiry-form").validate();<?php */?>
	jQuery('#footerBar').show();
    jQuery('#footerCopyright').show();
});
</script>