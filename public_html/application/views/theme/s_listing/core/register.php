<?php $segment1 = $this->uri->segment(1);?>
<div class="<?php echo (isset($bg_img))? 'bg-img': 'bg-color'?>">
    <div class="row">
    	<div class="col-lg-12">        	
			<?php if($this->session->flashdata('msg') && ($segment1 == 'setup')){ ?>
                <div class="row_plans">
                    <div class="container alert-container">  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-block fade in">
                                    <button type="button" class="close close-sm" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                    <p><?php echo $this->session->flashdata('msg');?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            <?php }?>
            <?php if($this->session->flashdata('error_msg') && ($segment1 == 'setup')){ ?>
                <div class="row_plans">
                    <div class="container alert-container">  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-block alert-danger fade in">
                                    <button type="button" class="close close-sm" data-dismiss="alert"><i class="fa fa-times"></i></button>
                                    <p><?php echo $this->session->flashdata('error_msg');?></p>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            <?php }?>            
        </div>
    </div>

    <?php if($title == 'G2G Member Registration'){?>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-subtitle"><?php echo 'Fill in the details below:';?></h1>
            </div>
        </div>
    <?php }?>
    <?php /*?><div class="row">
        <div class="col-lg-12">
            <h1 class="page-title"><?php echo $title;?></h1>
        </div>
    </div><?php */?>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form?>
        </div>
    </div>    
    <div class="row">
        <div class="col-lg-12">
            <div class="r_fields">* Required Fields</div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="welcome" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
    </div>
  </div>
</div>

<script type="text/javascript">
function type_individual(){
	jQuery('.applicant_details h4 span').html('Applicant Details');
	jQuery('.applicant_name').show();
	jQuery('.individual_type').show();
	jQuery('.individual_type').find('input[type="radio"]').addClass('required-entry');
	
	indi_type = jQuery('input[name="individual_type"]:checked').val();
	
	if(indi_type != 'undefined'){
		jQuery('input[name="individual_type"]').filter('[value="'+indi_type+'"]').prop('checked', true);
		
	}else{		
		jQuery('input[name="individual_type"]').filter('[value="Sole Applicant"]').prop('checked', true);
	}
	
	if(indi_type == 'Co-Applicant'){
		show_co_applicant();
	}else{
		hide_co_applicant();
	}
	
	jQuery('.fr_co').hide();
	jQuery('input[name="company_name"]').removeClass('required-entry');
	
	jQuery('.nationality > label').html('Nationality<em>*</em>');
	
	jQuery('.cor_em').find('label').html('Correspondence Email Id');
	jQuery('input[name="correspondence_email"]').removeClass('required-entry');
	
	jQuery('.company').hide();
	jQuery('input[name="contact_person"]').removeClass('required-entry');
}

function hide_individual_type(){
	jQuery('#ca_first_name').removeClass('required-entry');
	jQuery('#ca_email').removeClass('required-entry');
	jQuery('#ca_nationality').removeClass('required-entry');
	jQuery('#ca_address_1').removeClass('required-entry');
	jQuery('#ca_cc_mobile').removeClass('required-entry');
	jQuery('#ca_mobile').removeClass('required-entry');

	jQuery('.ca').hide();
}

function show_co_applicant(){
	jQuery('.ca').show();
}

function hide_co_applicant(){
	jQuery('.ca').hide();
}

function type_firm(){
	//alert(jQuery('input[name="individual_type"]').val());
	jQuery('.applicant_details h4 span').html('Firm Details');
	jQuery('input[name="individual_type"]').removeClass('required-entry');
	jQuery('#first_name').removeClass('required-entry');
	jQuery('.applicant_name').hide();
	
	hide_individual_type();
	same_as_check();
	
	jQuery('.fr_co').show();
	jQuery('.fr_co').find('label').html('Firm Name<em>*</em>');
	jQuery('input[name="company_name"]').addClass('required-entry');
	
	jQuery('.nationality > label').html('Residential Status<em>*</em>');
	
	jQuery('.cor_em').find('label').html('Correspondence Email Id<em>*</em>');
	jQuery('input[name="correspondence_email"]').addClass('required-entry');	
	
	jQuery('.company').show();
	jQuery('input[name="contact_person"]').addClass('required-entry');
}

function type_company(){
	jQuery('.applicant_details h4 span').html('Company Details');
	jQuery('input[name="individual_type"]').removeClass('required-entry');
	jQuery('#first_name').removeClass('required-entry');
	jQuery('.applicant_name').hide();
	
	hide_individual_type();
	same_as_check();
	
	jQuery('.fr_co').show();
	jQuery('.fr_co').find('label').html('Company Name<em>*</em>');
	jQuery('input[name="company_name"]').addClass('required-entry');
	
	jQuery('.nationality > label').html('Residential Status<em>*</em>');
	
	jQuery('.cor_em').find('label').html('Correspondence Email Id<em>*</em>');
	jQuery('input[name="correspondence_email"]').addClass('required-entry');

	jQuery('.company').show();
	jQuery('input[name="contact_person"]').addClass('required-entry');
}


function reference_self(){
	jQuery('input[name="reference_source"]').filter('[value="Self"]').prop('checked', true);
	jQuery('.reference_member').hide();
	jQuery('.reference_partner').hide();
	jQuery('.reference_member').find('input[type="text"]').removeClass('required-entry');
	jQuery('.reference_partner').find('input[type="text"]').removeClass('required-entry');
}

function reference_g2g_member(){
	jQuery('input[name="reference_source"]').filter('[value="G2G Member"]').prop('checked', true);
	jQuery('.reference_member').show();
	jQuery('.reference_partner').hide();
	jQuery('.reference_member').find('input[type="text"]').addClass('required-entry');			
	jQuery('.reference_partner').find('input[type="text"]').removeClass('required-entry');
}

function reference_channel_partner(){
	jQuery('input[name="reference_source"]').filter('[value="Channel Partner"]').prop('checked', true);
	jQuery('.reference_member').hide();
	jQuery('.reference_partner').show();
	jQuery('.reference_member').find('input[type="text"]').removeClass('required-entry');			
	jQuery('.reference_partner').find('input[type="text"]').addClass('required-entry');
}

jQuery(function(){
	<?php if($this->session->userdata('welcome') != FALSE){?>
		jQuery('#welcome').modal('show');
	<?php $this->session->unset_userdata('welcome');
	}?>
	
	jQuery('#welcome').on('click', function(){
		jQuery('#welcome').modal('hide');
	});
	//Stop Cut, Copy & Paste
	jQuery('#email').bind("cut copy paste",function(e){
		e.preventDefault();
	});
	
	jQuery('#con_email').bind("cut copy paste",function(e){
		e.preventDefault();
	});
	
	// Reference Source
	jQuery('#first_name').addClass('required-entry');
	jQuery('.individual_type').hide();
	jQuery('.reference').hide();
	//jQuery('.cor_check').hide();
	
	jQuery('.company').hide();
	jQuery('.fr_co').hide();	
	reference_self();
	jQuery('.ca').hide();
	
	if(jQuery('input[name="type"]:checked').val() != ''){
		type = jQuery('input[name="type"]:checked').val();
		jQuery('input[name="type"]').filter('[value="'+type+'"]').prop('checked', true);
		if(type == 'Individual'){
			type_individual();			
		}else if(type == 'Firm'){
			type_firm();
		}else if(type == 'Company'){
			type_company();
		}
	}else{
		jQuery('input[name="type"]').filter('[value="Individual"]').prop('checked', true);
		jQuery('input[name="individual_type"]').filter('[value="Sole Applicant"]').prop('checked', true);
	}

	if(jQuery('input[name="reference_source"]:checked').val() != ''){
		ref = jQuery('input[name="reference_source"]:checked').val();
		if(ref == 'Self'){
			reference_self();
		}else if(ref == 'G2G Member'){
			reference_g2g_member();
		}else if(ref == 'Channel Partner'){
			reference_channel_partner();
		}
	}else{
		jQuery('input[name="reference_source"]').filter('[value="Self"]').prop('checked', true);
		reference_self();
	}

	jQuery('input[name="type"]').on('click', function(){
		val = jQuery('input[name="type"]:checked').val();
		if(val == 'Individual'){
			type_individual();
		
		}else if(val == 'Company'){
			type_company();
			jQuery('.individual_type').hide();
			jQuery('.individual_type').removeClass('required-entry');
			jQuery('.individual_type').find('input[type="radio"]').removeAttr('checked');
			
		}else if(val == 'Firm'){
			type_firm();
			jQuery('.individual_type').hide();
			jQuery('.individual_type').removeClass('required-entry');
			jQuery('.individual_type').find('input[type="radio"]').removeAttr('checked');
		}
	});
	
		
	jQuery('input[name="reference_source"]').on('click', function(){
		val3 = jQuery(this).val();
		if(val3 == 'Self'){
			reference_self();			
		}else if(val3 == 'G2G Member'){
			reference_g2g_member();
		}else if(val3 == 'Channel Partner'){
			reference_channel_partner();
		}
	});
	
	jQuery("input[name='individual_type']").on('click', function(){
		val6 = jQuery(this).val();
		if(val6 == 'Co-Applicant'){
			jQuery('.ca').show();
			same_as_uncheck();
		}else{
			jQuery('.ca').hide();
			hide_individual_type();
		}
	});
	
	if(jQuery("input[name='individual_type']:checked").val() == 'Co-Applicant'){
		same_as_uncheck();
	}
	
	jQuery('#cor_email_check').on('click', function(){
		if(jQuery(this).is(':checked')){
			_vl = jQuery('input[name="email"]').val();	
			jQuery('input[name="correspondence_email"]').val(_vl);
			jQuery('input[name="correspondence_email"]').attr('readonly', 'readonly');
		}else{
			jQuery('input[name="correspondence_email"]').removeAttr('readonly');
			jQuery('input[name="correspondence_email"]').val('');
			jQuery(this).removeAttr('checked');
		}
	});
	
	jQuery('#ca_cor_email_check').on('click', function(){
		if(jQuery(this).is(':checked')){
			if(jQuery('input[name="ca_email"]').val() != ''){
				_vl = jQuery('input[name="ca_email"]').val();
				jQuery('input[name="ca_correspondence_email"]').val(_vl);
				jQuery('input[name="ca_correspondence_email"]').attr('readonly', 'readonly');
			}else{
				jQuery('input[name="ca_email"]').focus();				
				jQuery(this).removeAttr('checked');
			}
			//jQuery('input[name="ca_correspondence_email"]').attr('disabled', 'disabled');
		}else{
			jQuery('input[name="ca_correspondence_email"]').removeAttr('readonly');
			jQuery('#ca_correspondence_email').val('');
			//jQuery('input[name="ca_correspondence_email"]').removeAttr('disabled');
		}
	});
	
	email = jQuery('input[name="email"]').val();
	correspondence_email = jQuery('input[name="correspondence_email"]').val();
	ca_email = jQuery('input[name="ca_email"]').val();
	ca_correspondence_email = jQuery('input[name="ca_correspondence_email"]').val();
	//alert(email);
	//alert(correspondence_email);
	if(email == correspondence_email){		
		jQuery('#cor_email_check').prop('checked', true);
		jQuery('input[name="correspondence_email"]').attr('readonly', 'readonly');
	}
	if(ca_email == ca_correspondence_email){
		if(ca_email != ''){
			jQuery('#ca_cor_email_check').prop('checked', true);
			jQuery('input[name="ca_correspondence_email"]').attr('readonly', 'readonly');
		}
	}
});


// Only for Co-Applicant
///////////////////////////////////////////////////////////////////////////////
function same_as_check(){	
	//alert('check');
	//jQuery('.contact_details').hide();
	//jQuery('#ca_address_1').attr('disabled', 'disabled');
	//jQuery('#ca_first_name').removeClass('required-entry');
	//jQuery('#ca_last_name').removeClass('required-entry');
	//jQuery('#ca_nationality').removeClass('required-entry');
	//jQuery('#ca_email').removeClass('required-entry');
	jQuery('#ca_address_1').removeClass('required-entry');
	//jQuery('#ca_country').removeClass('required-entry');
	//jQuery('#ca_cc_mobile').removeClass('required-entry');
	//jQuery('#ca_mobile').removeClass('required-entry');	
	jQuery('#ca_first_name').removeClass('form-control-error');
	//jQuery('#ca_last_name').removeClass('form-control-error');
	jQuery('#ca_nationality').removeClass('form-control-error');
	jQuery('#ca_email').removeClass('form-control-error');
	jQuery('#ca_address_1').removeClass('form-control-error');
	jQuery('#ca_country').removeClass('form-control-error');
	jQuery('#ca_cc_mobile').removeClass('form-control-error');
	jQuery('#ca_mobile').removeClass('form-control-error');
}

function same_as_uncheck(){
	//alert('uncheck');
	//jQuery('.contact_details').show();
	jQuery('#ca_address_1').removeAttr('disabled');
	jQuery('#ca_first_name').addClass('required-entry');
	//jQuery('#ca_last_name').addClass('required-entry');
	jQuery('#ca_nationality').addClass('required-entry');
	jQuery('#ca_email').addClass('required-entry');
	jQuery('#ca_address_1').addClass('required-entry');
	jQuery('#ca_country').addClass('required-entry');	
	jQuery('#ca_cc_mobile').addClass('required-entry');
	jQuery('#ca_mobile').addClass('required-entry');
	//jQuery('#ca_zipcode').addClass('required-entry');
}

jQuery(function(){
	/*if(jQuery('#ca_same_as_applicant').prop('checked', false)){
		//jQuery('#ca_same_as_applicant').prop('checked', true);
		same_as_check();
	}else{
		same_as_uncheck();
	}*/
	
	<?php if(isset($same_as_applicant) && $same_as_applicant=='1'){?>
		jQuery('#same_as_applicant').prop('checked', true);
		same_as_applicant_check();
	<?php }?>
	
	/*jQuery('#ca_same_as_applicant').on('click', function(){
		val4 = jQuery(this).is(':checked');
		//alert(val4);
		if(val4 == '1'){
			same_as_check();
		}else{
			same_as_uncheck();
		}
	});*/
	
	jQuery('#same_as_applicant').on('click', function(e){
		val4 = jQuery(this).is(':checked');
		
		if(val4 == '1'){
			same_as_applicant_check();
			/*address_1 = jQuery('#address_1');
			country = jQuery('#country');
			state = jQuery('#state');
			city = jQuery('#city');
			zipcode = jQuery('#zipcode');
			cc_mobile = jQuery('#cc_mobile');
			std_code = jQuery('#std_code');
			telephone_residential = jQuery('#telephone_residential');
		
			if(telephone_residential.val() != ''){
				jQuery('input[name="ca_telephone_residential"]').val(telephone_residential.val());				
			}else{
				//telephone_residential.focus();
				//e.preventDefault();
			}
			
			if(std_code.val() != ''){
				jQuery('input[name="ca_std_code"]').val(std_code.val());				
			}else{
				//std_code.focus();
				//e.preventDefault();
			}
			
			if(cc_mobile.val() != ''){
				jQuery('input[name="ca_cc_mobile"]').val(cc_mobile.val());				
			}else{
				cc_mobile.focus();
				e.preventDefault();
			}
			
			if(zipcode.val() != ''){
				jQuery('input[name="ca_zipcode"]').val(zipcode.val());				
			}else{
				//zipcode.focus();
				//e.preventDefault();
			}
			
			if(state.val() != ''){
				jQuery('input[name="ca_state"]').val(state.val());
			}else{
				//state.focus();
				//e.preventDefault();
			}
			
			if(city.val() != ''){
				jQuery('input[name="ca_city"]').val(city.val());				
			}else{
				//city.focus();
				//e.preventDefault();
			}
			
			if(country.val() != ''){
				jQuery('select[name="ca_country"] option[value="'+country.val()+'"]').prop('selected', true);				
			}else{
				country.focus();
				e.preventDefault();
			}
			
			if(address_1.val() != ''){
				jQuery('textarea[name="ca_address_1"]').val(address_1.val());
				
			}else{
				address_1.focus();
				e.preventDefault();
			}
			jQuery('input[name="ca_telephone_residential"]').attr('readonly', 'readonly');
			jQuery('input[name="ca_std_code"]').attr('readonly', 'readonly');
			jQuery('input[name="ca_cc_mobile"]').attr('readonly', 'readonly');
			jQuery('input[name="ca_zipcode"]').attr('readonly', 'readonly');
			jQuery('input[name="ca_state"]').attr('readonly', 'readonly');
			jQuery('input[name="ca_city"]').attr('readonly', 'readonly');
			jQuery('select[name="ca_country"] option[value="'+country.val()+'"]').attr('readonly', 'readonly');
			jQuery('textarea[name="ca_address_1"]').attr('readonly', 'readonly');*/
			
		}else{
			same_as_applicant_uncheck();
			/*jQuery('input[name="ca_telephone_residential"]').val('');
			jQuery('input[name="ca_std_code"]').val('');
			jQuery('input[name="ca_cc_mobile"]').val('');
			jQuery('input[name="ca_zipcode"]').val('');
			jQuery('input[name="ca_state"]').val('');
			jQuery('input[name="ca_city"]').val('');
			jQuery('select[name="ca_country"] option[value=""]').prop('selected', true);
			jQuery('textarea[name="ca_address_1"]').val('');
			
			jQuery('input[name="ca_telephone_residential"]').removeAttr('readonly');
			jQuery('input[name="ca_std_code"]').removeAttr('readonly');
			jQuery('input[name="ca_cc_mobile"]').removeAttr('readonly');
			jQuery('input[name="ca_zipcode"]').removeAttr('readonly');
			jQuery('input[name="ca_state"]').removeAttr('readonly');
			jQuery('input[name="ca_city"]').removeAttr('readonly');
			jQuery('select[name="ca_country"] option[value=""]').removeAttr('readonly');
			jQuery('textarea[name="ca_address_1"]').removeAttr('readonly');*/
			
			/*jQuery('input[name="ca_telephone_residential"]').parent('.form-group').removeClass('has-error');
			jQuery('input[name="ca_std_code"]').parent('.form-group').removeClass('has-error');
			jQuery('input[name="ca_cc_mobile"]').parent('.form-group').removeClass('has-error');
			jQuery('input[name="ca_zipcode"]').parent('.form-group').removeClass('has-error');
			jQuery('input[name="ca_state"]').parent('.form-group').removeClass('has-error');
			jQuery('input[name="ca_city"]').parent('.form-group').removeClass('has-error');
			jQuery('select[name="ca_country"] option[value=""]').parent('.form-group').removeClass('has-error');
			jQuery('textarea[name="ca_address_1"]').parent('.form-group').removeClass('has-error');*/
		}
	});

	jQuery('#frm').validate({
		/*rules: {
			password:{required:true},
			username:{required:true},
		},*/
		messages: {
			//password: "Enter your password",
			//lastname: "Enter your lastname",
			email: {
				required: "Enter your valid Email ID",
				is_unique: "Email Id already registered"
				//valid_email: "Enter a valid Email ID",
				//minlength: jQuery.format("Enter at least {0} characters"),
				//remote: jQuery.format("{0} is already in use")
			},
		}
	});
	
	jQuery.extend(jQuery.validator.messages, {
		equalTo: "Field value doesn't match."
	});
			
});

function same_as_applicant_check(){
	address_1 = jQuery('#address_1');
	country = jQuery('#country');
	state = jQuery('#state');
	city = jQuery('#city');
	zipcode = jQuery('#zipcode');
	cc_mobile = jQuery('#cc_mobile');
	std_code = jQuery('#std_code');
	telephone_residential = jQuery('#telephone_residential');

	if(telephone_residential.val() != ''){
		jQuery('input[name="ca_telephone_residential"]').val(telephone_residential.val());				
	}else{
		//telephone_residential.focus();
		//e.preventDefault();
	}
	
	if(std_code.val() != ''){
		jQuery('input[name="ca_std_code"]').val(std_code.val());				
	}else{
		//std_code.focus();
		//e.preventDefault();
	}
	
	if(cc_mobile.val() != ''){
		jQuery('input[name="ca_cc_mobile"]').val(cc_mobile.val());				
	}else{
		cc_mobile.focus();
		e.preventDefault();
	}
	
	if(zipcode.val() != ''){
		jQuery('input[name="ca_zipcode"]').val(zipcode.val());				
	}else{
		//zipcode.focus();
		//e.preventDefault();
	}
	
	if(state.val() != ''){
		jQuery('input[name="ca_state"]').val(state.val());
	}else{
		//state.focus();
		//e.preventDefault();
	}
	
	if(city.val() != ''){
		jQuery('input[name="ca_city"]').val(city.val());				
	}else{
		//city.focus();
		//e.preventDefault();
	}
	
	if(country.val() != ''){
		jQuery('select[name="ca_country"] option[value="'+country.val()+'"]').prop('selected', true);				
	}else{
		country.focus();
		e.preventDefault();
	}
	
	if(address_1.val() != ''){
		jQuery('textarea[name="ca_address_1"]').val(address_1.val());
		
	}else{
		address_1.focus();
		e.preventDefault();
	}
	jQuery('input[name="ca_telephone_residential"]').attr('readonly', 'readonly');
	jQuery('input[name="ca_std_code"]').attr('readonly', 'readonly');
	jQuery('input[name="ca_cc_mobile"]').attr('readonly', 'readonly');
	jQuery('input[name="ca_zipcode"]').attr('readonly', 'readonly');
	jQuery('input[name="ca_state"]').attr('readonly', 'readonly');
	jQuery('input[name="ca_city"]').attr('readonly', 'readonly');
	jQuery('select[name="ca_country"] option[value="'+country.val()+'"]').attr('readonly', 'readonly');
	jQuery('textarea[name="ca_address_1"]').attr('readonly', 'readonly');
	
	jQuery('input[name="ca_telephone_residential"]').removeClass('form-control-error');
	jQuery('input[name="ca_std_code"]').removeClass('form-control-error');
	jQuery('input[name="ca_cc_mobile"]').removeClass('form-control-error');
	jQuery('input[name="ca_zipcode"]').removeClass('form-control-error');
	jQuery('input[name="ca_state"]').removeClass('form-control-error');
	jQuery('input[name="ca_city"]').removeClass('form-control-error');
	jQuery('select[name="ca_country"]').removeClass('form-control-error');
	jQuery('textarea[name="ca_address_1"]').removeClass('form-control-error');
	
	jQuery('input[name="ca_telephone_residential"]').siblings('.help-block-pop').remove();
	jQuery('input[name="ca_std_code"]').siblings('.help-block-pop').remove();
	jQuery('input[name="ca_cc_mobile"]').siblings('.help-block-pop').remove();
	jQuery('input[name="ca_zipcode"]').siblings('.help-block-pop').remove();
	jQuery('input[name="ca_state"]').siblings('.help-block-pop').remove();
	jQuery('input[name="ca_city"]').siblings('.help-block-pop').remove();
	jQuery('select[name="ca_country"]').siblings('.help-block-pop').remove();
	jQuery('textarea[name="ca_address_1"]').siblings('.help-block-pop').hide();
}


function same_as_applicant_uncheck(){
	jQuery('input[name="ca_telephone_residential"]').val('');
	jQuery('input[name="ca_std_code"]').val('');
	jQuery('input[name="ca_cc_mobile"]').val('');
	jQuery('input[name="ca_zipcode"]').val('');
	jQuery('input[name="ca_state"]').val('');
	jQuery('input[name="ca_city"]').val('');
	jQuery('select[name="ca_country"] option[value=""]').prop('selected', true);
	jQuery('textarea[name="ca_address_1"]').val('');
	
	jQuery('input[name="ca_telephone_residential"]').removeAttr('readonly');
	jQuery('input[name="ca_std_code"]').removeAttr('readonly');
	jQuery('input[name="ca_cc_mobile"]').removeAttr('readonly');
	jQuery('input[name="ca_zipcode"]').removeAttr('readonly');
	jQuery('input[name="ca_state"]').removeAttr('readonly');
	jQuery('input[name="ca_city"]').removeAttr('readonly');
	jQuery('select[name="ca_country"] option[value=""]').removeAttr('readonly');
	jQuery('textarea[name="ca_address_1"]').removeAttr('readonly');
}
</script>