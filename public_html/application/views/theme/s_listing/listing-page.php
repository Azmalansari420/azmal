<style>
    .fade{
        display: none;
    }
    .active{
        display: block;
    }
</style>


<?php /*?><?php
for ($x = 1; $x <= 408; $x++) {
  echo "/profiles/new_delhi/new_delhi$x.jpg <br>";
}
?><?php */?>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="search-box">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<select name="type" id="type" class="form-control required-entry" onchange="location = this.value;">
								<option value="">Select Profile Types</option>
								<?php foreach(get_type('no') as $k => $v){?>
									<option <?= (isset($type_slug) && $type_slug==$k)? 'selected="selected"': '';?> value="<?= base_url($k)?>"><?= $v?></option>
								<?php }?>
							</select>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<select name="state" id="state" class="form-control required-entry">
								<option value="" >Select State</option>
								<?php foreach(get_states() as $k => $v){?>
									<?php if($k!=''){?>
										<option <?= (isset($state) && $state==$v)? 'selected="selected"': '';?>  value="<?= $v?>" ><?= $v?></option>
									<?php }?>
								<?php }?>
							</select>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<select name="city" id="city" class="form-control required-entry" onchange="location = this.value;">
								<option value="">Select City</option>
							</select>
						</div>
					</div>
					
					<?php /*?><div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
						<div class="form-group">
							<select name="locality" id="locality" class="form-control required-entry" onchange="location = this.value;">
								<option value="" >Select Locality</option>
							</select>
						</div>
					</div><?php */?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php if(!isset($data->mobile_no) && !isset($data->whatapp_no)){?>
				<?php /*?><div class="heading text-center">Escorts and Call Girls Profile with Photo, Number | Escorts Directory</div>
				<div align="center"><div class="f-line"></div></div><?php */?>
			<?php }?>
		</div>
	</div>
	
	<?php /*?><?php if(isset($data->mobile_no) && isset($data->whatapp_no)){?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
		</div>
		
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<?php if($data->mobile_no!=''){?>
					<a href="tel:<?= $data->mobile_no?>"> 
					    <img src="<?= base_url('assets/images/call2.svg');?>" alt="call" height="35" width="35">
				    </a>
				<?php }?>
			</div>
			
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
				<?php if($data->whatapp_no!=''){?>
					<a href="https://api.whatsapp.com/send?phone=+91<?=$data->whatapp_no?>&amp;text=Hi" target="_blank"> 
					    <img src="<?= base_url('assets/images/whatsapp.svg');?>" align="whatsapp" height="35" width="35">
				    </a>
				<?php }?>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
		</div>
	<?php }?><?php */?>
	
	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id="top_listing-view" ></div>
		</div>
	</div>
	
	
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<div id="left-menu-listing" class="minmam1000">
			
			</div>
			
		</div>
		
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
		    <?php if(isset($meta_title) && $meta_title!=''){//if(isset($data->mobile_no) && isset($data->whatapp_no)){?>
				<?php /*?><div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="heading"><?= $meta_title ?></div>
					</div>
				</div><?php */?>
			<?php }?>
			
			<div id="listing-view" class="minmam1000"></div>
		</div>
		
		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
			<div id="right_listing-view" ></div>
		</div>
	</div>
	
	<?php if(isset($city) && $city!=''){?>
		<div class="row" id="white_div1">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<div class="category-ads">
					<div class="heading">Find Related Category <span>Ads</span></div>
					<ul class="category-list">
						<?php foreach(get_type('no') as $k => $t){?>
							<li><a href="<?= base_url($k).'/'.$city_slug?>"><?= $t?> In <?= $city?> <span class="glyphicon glyphicon-chevron-right"></span></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	<?php }?>
	
	<?php if(isset($type_slug) && $type_slug=='male-escorts' && isset($data->content_male) && $data->content_male!=''){?>
		<div class="row content-white-box-row before cgb" id="white_div">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-white-box-wrapper before cgb">
				<div class="white-box">
					<?= ($data->content_male)?>
				</div>
			</div>
		</div>
	<?php }elseif(isset($type_slug) && $type_slug=='call-girls' && isset($data->content) && $data->content!=''){?>
		<div class="row content-white-box-row before cgb" id="white_div">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-white-box-wrapper before cgb">
				<div class="white-box">
					<?= ($data->content)?>
				</div>
			</div>
		</div>
	<?php }?>
	
	
	
	<?php /*?><?php if(isset($city) && $city!='' && count(get_keywords($city))!=0 && isset($locality) && $locality==''){?>
		<div class="row" id="white_div1">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="white-box">
					<h5>Escorts and Call Girls Services in <?= $city?></h5>
					<ul class="tags-list">
						<?php foreach(get_keywords($city) as $key){?>
							<li><a href="<?= $key->url?>"><?= $key->name?></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	<?php }?><?php */?>
	
	
	
	<?php /*?><?php if(isset($city) && $city!='' && count(get_keywords($city))!=0 && isset($locality) && $locality==''){?>
		<div class="row" id="white_div2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="white-box">
					<h5>Tags</h5>
					<ul class="tags-list">
						<?php foreach(get_tags() as $tag){?>
							<li><a href="<?= $tag->url?>"><?= $tag->name?></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	<?php }?><?php */?>
	
</div>

<?php /*?><?php if(isset($city) && $city!='' && count(get_keywords($city))!=0 && isset($locality) && $locality==''){?>
	<div class="tags-bar">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h3>Escorts and Call Girls Services in <span><?= $city?></span></h3>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<ul class="tags-list">
						<?php foreach(get_keywords($city) as $key){?>
							<li><a href="<?= $key->url?>"><?= $key->name?></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php }?><?php */?>


<?php //print(get_cities());?>
<?php 
	//print_r(get_all_locality());
	//print_r(get_all_cities());
	//print_r(get_all_locality());
?>


<style>
	#white_div, #white_div1, #white_div2, #footerBar, #left-menu-listing, #listing-view, .white-box, .category-ads, .mobile-whatapp-bar{
		display: none;
	}
	
	.minmam1000{min-height:1000px;}
</style>

<script>
jQuery(function(){
	
	get_listing();
	
	window.setTimeout(
		function(){
			get_cities();
		},
	500);	
	
	window.setTimeout(
		function(){
			get_locality();
		},
	1000);
	
	jQuery('body').on('change', '#state', function(){
		get_cities('state');
		get_locality('state');
		get_listing('state');
	});
	
	<?php /*?>jQuery('body').on('change', '#type, #gender, #age, #short', function(){
		get_listing();
	});<?php */?>
});


function get_listing(val){
	var state = jQuery('#state').val();
	
	<?php if(isset($city) && $city!=''){?>
		var city = '<?= $city?>';
	<?php }else{?>
		var city = '';
	<?php }?>
	
	<?php if(isset($locality) && $locality!=''){?>
		var locality = '<?= $locality?>';
	<?php }else{?>
		var locality = '';
	<?php }?>
	
	if(val=='state'){
		var city = '';
		var locality = '';
	}
	
	<?php if(isset($type_slug) && $type_slug!=''){?>
		var type 	= '<?= $type_slug?>';
	<?php }else{?>
		var type 	= '';
	<?php }?>
	
	var gender 	= '';//jQuery('#gender').val();
	var age 	= '';//jQuery('#age').val();
	var short 	= '';//jQuery('#short').val();
	
	<?php /*?>jQuery.ajax({
	    type:"POST",
		url:"<?= base_url("ajax/top_listing");?>",
		dataType: 'html',
		data:'state='+state+'&city='+city+'&locality='+locality+'&type='+type+'&gender='+gender+'&age='+age+'&short='+short,
		success: function(data){
			jQuery('#top_listing-view').html(data);
		}
	});<?php */?>
	
		jQuery.ajax({
			type:"POST",
			url:"<?= base_url("ajax/get_listing");?>",
			dataType: 'html',
			data:'state='+state+'&city='+city+'&locality='+locality+'&type='+type+'&gender='+gender+'&age='+age+'&short='+short,
			success: function(data){
				jQuery('#listing-view').html(data);
				jQuery('#white_div, #white_div1, #white_div2, #footerBar, #left-menu-listing, #listing-view, .white-box, .category-ads, .mobile-whatapp-bar').show();
			}
		});
	<?php /*?>jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/right_listing");?>",
		dataType: 'html',
		data:'state='+state+'&city='+city+'&locality='+locality+'&type='+type+'&gender='+gender+'&age='+age+'&short='+short,
		success: function(data){
			jQuery('#right_listing-view').html(data);
		}
	});<?php */?>
	
	//alert(state);
	
	if(type=='' || state==''){
		jQuery.ajax({
			type:"POST",
			url:"<?= base_url("ajax/right_listing");?>",
			dataType: 'html',
			data:'state='+state+'&city='+city+'&locality='+locality+'&type='+type+'&gender='+gender+'&age='+age+'&short='+short,
			success: function(data){
				jQuery('#left-menu-listing').html(data);
			}
		});
	}else{
		jQuery.ajax({
			type:"POST",
			url:"<?= base_url("ajax/left_menu_listing");?>",
			dataType: 'html',
			data:'state='+state+'&city='+city+'&locality='+locality+'&type='+type+'&gender='+gender+'&age='+age+'&short='+short,
			success: function(data){
				jQuery('#left-menu-listing').html(data);
			}
		});
	}
}


<?php /*?>var pageno = new Array();
$(window).scroll(function() {
   if($(window).scrollTop() == $(document).height() - $(window).height()) {
	    var page_no	= $(".page_no").last().val();
		if(jQuery.inArray(page_no, pageno) !== -1){
		}else{
			if(page_no!=''){
				get_listing2(page_no, 0);
			}
		}
		
		pageno.push(page_no);
		
    }
});<?php */?>


function get_listing2(page, pg){
	var state		 	= jQuery('#state').val();
	//var city		 	= jQuery('#city').val();
	//var locality	 	= jQuery('#locality').val();
	
	<?php if(isset($type_slug) && $type_slug!=''){?>
		var type 	= '<?= $type_slug?>';
	<?php }else{?>
		var type 	= '';
	<?php }?>
	
	var gender		 	= '';//jQuery('#gender').val();
	var age			 	= '';//jQuery('#age').val();
	var short		 	= '';//jQuery('#short').val();
	
	<?php if(isset($city) && $city!=''){?>
		var city		= '<?= $city?>';
	<?php }else{?>
		var city		= '';
	<?php }?>
	
	<?php if(isset($locality) && $locality!=''){?>
		var locality		= '<?= $locality?>';
	<?php }else{?>
		var locality		= '';
	<?php }?>
	
	//alert(city);
	
	jQuery('.pagination_'+pg).hide()
	if(page!=0){
		jQuery('#loading-bar').show()
	}else{
		$('.subscribe-bor, .footer-bar').show();
		
		jQuery('.white-box, .category-ads, .footer-bar').show();
	}
	
	
	window.setTimeout(
		function(){
			jQuery.ajax({
				type:"POST",
				url:"<?= base_url("ajax/get_listing");?>",
				dataType: 'html',
				data:'state='+state+'&city='+city+'&locality='+locality+'&type='+type+'&gender='+gender+'&age='+age+'&short='+short+'&page='+page,
				success: function(data){
					jQuery('.loader-bar').hide()
					jQuery('#listing-view').append(data)
				}
			});		
		},
	100);
	
	
}


function get_cities(val){
	<?php if(isset($city) && $city!=''){?>
		var city = '<?= $city?>';
	<?php }else{?>
		var city = '';
	<?php }?>
	
	var state = jQuery('#state').val();
	
	if(val=='state'){
		var city = '';
	}
	
		
	<?php if(isset($type_slug) && $type_slug!=''){?>
		var l_type 	= '<?= $type_slug?>';
	<?php }else{?>
		var l_type 	= '';
	<?php }?>
	
	jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/get_cities");?>",
		dataType: 'html',
		data:'city='+city+'&state='+state+'&type='+l_type,
		success: function(data){
			jQuery('#city').html(data)
		}
	});		
}

function get_locality(val){
	<?php if(isset($city) && $city!=''){?>
		var city		= '<?= $city?>';
	<?php }else{?>
		var city		= '';
	<?php }?>
	
	<?php if(isset($locality) && $locality!=''){?>
		var locality		= '<?= $locality?>';
	<?php }else{?>
		var locality		= '';
	<?php }?>
	
	if(val=='state'){
		var city		= '';
		var locality		 	= '';
	}
	
	<?php if(isset($type_slug) && $type_slug!=''){?>
		var l_type 	= '<?= $type_slug?>';
	<?php }else{?>
		var l_type 	= '';
	<?php }?>
	
	
	jQuery.ajax({
		type:"POST",
		url:"<?= base_url("ajax/get_locality");?>",
		dataType: 'html',
		data:'locality='+locality+'&city='+city+'&type='+l_type,
		success: function(data){
			jQuery('#locality').html(data)
		}
	});		
}

$(window).scroll(function() {
    $.each($('[data-src]'), function() {
        if ( $(this).attr('data-src') && $(this).offset().top < ($(window).scrollTop() + $(window).height() + 100) ) {
            var source = $(this).data('src');
            $(this).attr('src', source);
            $(this).removeAttr('data-src');
        }
    })
})

$('#freId1').click(function (){
   $('.freId1').toggleClass('fade');
   $('.freId1').toggleClass('active');
});

$('#freId2').click(function (){
   $('.freId2').toggleClass('fade');
   $('.freId2').toggleClass('active');
});
</script>