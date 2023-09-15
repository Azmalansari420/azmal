<!DOCTYPE html>
<html lang="en">
<head>
   	<meta charset="utf-8">
   	<meta http-equiv="X-UA-Compatible" content="IE=edge">
   	<meta name="viewport" content="width=device-width, initial-scale=1.0">
   	<title>Login</title>
	
                
   	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
   	<!--[if lt IE 9]>
   	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
   	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
   	<![endif]-->
    
    <!--<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700' rel='stylesheet' type='text/css'>-->
    
    <!--Core CSS -->
	<link href='<?php echo base_url()?>assets/admin/style/bootstrap.min.css' rel='stylesheet'>
	<link href='<?php echo base_url()?>assets/admin/style/style.css' rel='stylesheet'>
	<link href='<?php echo base_url()?>assets/admin/style/login.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!--Core js-->
	<script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/validator.js"></script>
    <script type="text/javascript">var base_url = '<?php echo base_url()?>';</script>
 	<script type="text/javascript"></script>
</head>


<body class="login-body">
<style>
.help-block-pop{color:#FF5656}
</style>
	<div class="bg-cover"></div>
	<div class="container">

    	<form class="form-signin wd-form" action="" id="admin_login_form" method="post">

    		<div class="login-wrap">
    			<div class="icon-block"><span class="ico">lock</span></div>
    			<h2 class="form-signin-heading" style="font-size:20px;"><?echo $site_title?></h2>
        		<div class="form_row user-login-info">

           			<?php if($this->session->flashdata('msg')){ ?>
            			<div class="alert alert-success vendor_login_message">
	        		  		<button type="button" class="close" data-dismiss="alert">&times;</button>
							<?php echo $this->session->flashdata('msg');?>
	        			</div>
					<?php }?>

					<?php if($this->session->flashdata('error_msg')){ ?>
                    	<div class="alert alert-danger vendor_login_message">
	                    	<button type="button" class="close" data-dismiss="alert">&times;</button>
							<?php echo $this->session->flashdata('error_msg');?>
			        	</div>
					<?php }?>

					<div class="form-group row ">
						<div class="col-lg-12 col-xs-12">
							<label class="col-sm-12 form-control-label" for="username">Username<span class="req">*</span></label>
							<div class="input-group">
								<input name="username" id="username" class="form-control required-entry" type="text" placeholder="Username" autocomplete="off" />
							</div>
						</div>
					</div>
					<div class="form-group row ">
						<div class="col-lg-12 col-xs-12">
							<label class="col-sm-12 form-control-label" for="username">Password<span class="req">*</span></label>
							<div class="input-group">
								<input name="password" id="password" class="form-control required-entry" type="password" placeholder="Password" autocomplete="off" />
							</div>
						</div>
					</div>
					<div class="form-group row ">
						<div class="col-lg-12 col-xs-12">
							<div class="input-group">
								<button type="submit" id="vendor_login_submit" class="btn btn-lg btn-login btn-block btn-success">Sign in</button>
							</div>
						</div>
					</div>
        		</div>
            </div>
            <div class="powered-by">
			</div>
		</form>
	</div>

    <script type="text/javascript">

    jQuery(function(){
		jQuery('#username')[0].focus();
		jQuery('#username').attr('autocomplete', 'off');
		jQuery('#admin_login_form').validate({

			rules: {

    	       	password:{required:true},

    	       	username:{required:true},

			},

			highlight: function(element) {

    	        jQuery(element).closest('.form-group').addClass('has-error');

				jQuery(element).addClass('form-control-error');

    	    },

    	    unhighlight: function(element) {

    	        jQuery(element).closest('.form-group').removeClass('has-error');

				jQuery(element).removeClass('form-control-error');

    	    },

    	    errorElement: 'span',

    	    errorClass: 'help-block help-block-pop',

    	    errorPlacement: function(error, element) {

    	        if(element.parent('.input-group').length) {

    	            error.insertAfter(element.parent());

    	        } else {

    	            error.insertAfter(element);

    	        }

    	    }

		});

	});

    </script>

</body>

</html>