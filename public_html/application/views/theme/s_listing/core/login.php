<div class="row">
	<div class="col-lg-12">
    	<h1 class="page-title">Login</h1>
    </div>
</div>
<div class="row">
	<div class="col-lg-12">
    	<div class="form-tabs">
        	<form method="post" class="wd-form" id="login_form">
            	<div class="form-group row">
					<div class="col-sm-6">
                    	<label for="name" class="col-sm-12 form-control-label">Name</label>
                        <div class="input-group">
                        	<input type="text" class="form-control required-entry" id="name" value="" name="name" />
                       </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function(){
	jQuery('#login_form').validate();
});
</script>