<?php /*?><div class="tags-bar">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h3>Top Searches in <span>India</span></h3>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<ul class="tags-list">
					<?php foreach(get_tags() as $tag){?>
						<li><a href="<?= $tag->url?>"><?= $tag->name?></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
	</div>
</div><?php */?>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-white-box-wrapper before cgb">
			<div class="white-box footer-content">
				<?= get_setting('footer-content');?>
			</div>
		</div>
	</div>
</div>


<?php /*?><?php if(isset($city) && $city!=''){?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-white-box-wrapper before cgb">
			<div class="white-box font10">
				<?= get_setting('city-footer-content');?>
			</div>
		</div>
	</div>
</div>
<?php }?><?php */?>



<footer class="footer-bar">
	<div class="container" id="footerBar">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="heading">Top Searches India</div>
				<?php foreach(get_tags() as $tag){?>
					<?php if($tag->url!=''){?>
						<a class="f-tags" href="<?= $tag->url?>"><?= $tag->name?></a>, &nbsp;
					<?php }?>
				<?php }?>
			</div>
			
			
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				<div class="heading">Useful Links</div>
				<ul class="f-menu">
					<li><a href="<?= base_url()?>">Home</a></li>
					<li><a href="<?= base_url('about-us')?>">About Us</a></li>
					<li><a href="<?= base_url('locations')?>">Locations</a></li>
										
					<?php /*?><li><a href="<?= base_url();?>account/login"> Log In</a></li>
					<li><a href="<?= base_url();?>account/register"> Post your Ad</a></li><?php */?>
				</ul>
			</div>
			
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				<div class="heading">Help / Info</div>
				<ul class="f-menu">
					<li><a href="<?= base_url('contact-us')?>">Contact Us</a></li>
					<li><a href="<?= base_url('terms-and-conditions')?>">Terms And Conditions</a></li>
					<li><a href="<?= base_url('privacy-policy')?>">Privacy Policy</a></li>
				</ul>
				
				<a href="//www.dmca.com/Protection/Status.aspx?ID=01924044-a7c4-41b1-822e-41628294942f" title="DMCA.com Protection Status" class="dmca-badge"> 
					<img width="121" height="24" src ="https://images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=01924044-a7c4-41b1-822e-41628294942f"  alt="DMCA.com Protection Status" />
				</a>  
				<?php /*?><script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script><?php */?>
			</div>
			
		</div>
		
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="top-boder">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="copyright-text">&copy; Copyrights &copy; 2022 <a href="<?= base_url();?>">Sonababes.com</a> | All Rights Reserved</div>
						</div>
						
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<ul class="social-icons">
								<li><a href="<?= get_setting('facebook-page-link');?>"><img height="25" width="25" src="<?= base_url('assets/images')?>/facebook.png" alt="facebook"></a></li>
								<li><a href="<?= get_setting('twitter-page-link');?>"><img height="25" width="25" src="<?= base_url('assets/images')?>/twitter.png" alt="twitter"></a></li>
								<li><a href="<?= get_setting('youtube-page-link');?>"><img height="25" width="25" src="<?= base_url('assets/images')?>/youtube.png" alt="youtube"></a></li>
								<li><a href="<?= get_setting('instagram-page-link');?>"><img height="25" width="25" src="<?= base_url('assets/images')?>/instagram.png" alt="instagram"></a></li>
								<li><a href="<?= get_setting('pinterest-page-link');?>"><img height="25" width="25" src="<?= base_url('assets/images')?>/pinterest.png" alt="pinterest"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>


<?php if(isset($data->mobile_no) && isset($data->whatapp_no)){?>
<div class="mobile-whatapp-bar">
	<table width="100%">
		<tr align="center">
			<?php if($data->mobile_no!=''){?>
				<td width="50%">
					<a href="tel:<?= $data->mobile_no?>" class="mobile_no">  
						<img src="<?= base_url('assets/images/call2.svg');?>" alt="call" height="35" width="35"> Mobile No.
					</a>
				</td>
			<?php }?>
			
			<?php if($data->whatapp_no!=''){?>
				<td width="50%">
					<a href="https://api.whatsapp.com/send?phone=+91<?=$data->whatapp_no?>&amp;text=Hi" target="_blank" class="whatapp_no"> 
						<img src="<?= base_url('assets/images/whatsapp.svg');?>" alt="whatsapp" height="35" width="35"> Whatsapp No.
					</a>
				</td>
			<?php }?>
		</tr>
	</table>	
</div>
<?php }?>

<?= get_setting('google-footer-code');?>


<script>
function get_list(){
	var h_type 	= $('#h_type').val();
	var city 	= $('#city').val();
	
	//alert(city);
	
	if(h_type!='' || city!=''){
		if(city!=''){
			$('#header-search').attr('action', city);
		}else{
			$('#header-search').attr('action', h_type);
		}
		
		$("#header-search").submit();
	}
}
</script>

<?php /*?><script> 
function disableselect(e){  
	return false  
}  

function reEnable(){  
	return true  
}  

//if IE4+  
document.onselectstart=new Function ("return false")  
document.oncontextmenu=new Function ("return false")  
//if NS6  
if (window.sidebar){  
	document.onmousedown=disableselect  
	document.onclick=reEnable  
}


<!-- 
//Disable right click script 
//visit http://www.rainbow.arch.scriptmania.com/scripts/ 
var message="Sorry, right-click has been disabled"; 
/////////////////////////////////// 
function clickIE() {if (document.all) {(message);return false;}} 
function clickNS(e) {if 
(document.layers||(document.getElementById&&!document.all)) { 
if (e.which==2||e.which==3) {(message);return false;}}} 
if (document.layers) 
{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;} 
else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;} 
document.oncontextmenu=new Function("return false") 
// --> 
</script><?php */?>



</body>
</html>