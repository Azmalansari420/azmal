<?php
$date_key = false;
$month_key = false;
$year_key = false;
if(array_key_exists('value', $attributes) && $attributes['value'] != ''){
	$date_part = explode('-', $attributes['value']);
	
	$date_key = (array_key_exists(2, $date_part) && $date_part[2] != '') ? $date_part[2] : false;
	$month_key = (array_key_exists(1, $date_part) && $date_part[1] != '') ? $date_part[1] : false;
	$year_key = (array_key_exists(0, $date_part) && $date_part[0] != '') ? $date_part[0] : false;
}
?>
<?php
$months = array('01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr', '05'=>'May', '06'=>'Jun', '07'=>'Jul', '08'=>'Aug', '09'=>'Sep', '10'=>'Oct', '11'=>'Nov', '12'=>'Dec');
?>

<div class="date-container">
	<div class="row">
    	<div class="col-lg-4">
        	<select<?php /*?> name="date_day_<?php echo $attributes['id']?>"<?php */?> id="date_day_<?php echo $attributes['id']?>" class="form-control">
            	<option value="">Date</option>
				<?php for($i=1; $i<=31; $i++){?>
                	<?php $d_value = (strlen($i)==1) ? '0'.$i : $i?>
                	<option <?php if($date_key == $d_value){?> selected="selected"<?php }?> value="<?php echo $d_value?>"><?php echo (strlen($i)==1) ? '0'.$i : $i?></option>
                <?php }?>
            </select>
        </div>
        <div class="col-lg-4">
        	<select<?php /*?> name="date_month_<?php echo $attributes['id']?>"<?php */?> id="date_month_<?php echo $attributes['id']?>" class="form-control">
            	<option value="">Month</option>
				<?php foreach($months as $month_num => $month_text){?>
                	<option <?php if($month_key == $month_num){?> selected="selected"<?php }?> value="<?php echo $month_num?>"><?php echo $month_text?></option>
                <?php }?>
            </select>
        </div>
        <div class="col-lg-4">
        	<select<?php /*?> name="date_year_<?php echo $attributes['id']?>"<?php */?> id="date_year_<?php echo $attributes['id']?>" class="form-control">
            	<option value="">Year</option>
				<?php for($y = date('Y'); $y >= 1900; $y--){?>
                	<option <?php if($year_key == $y){?> selected="selected"<?php }?> value="<?php echo $y?>"><?php echo $y?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <input type="hidden" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id']?>" <?php echo (isset($attributes['value']))? "value='".$attributes['value']."'" : '' ?> />
</div>

<script type="text/javascript">
var _final_date_<?php echo $attributes['id']?> = false;
jQuery(function(){
	jQuery('#date_year_<?php echo $attributes['id']?>').on('change', function(e){
		set_final_date_<?php echo $attributes['id']?>();
	});
	jQuery('#date_month_<?php echo $attributes['id']?>').on('change', function(e){
		set_final_date_<?php echo $attributes['id']?>();
	});
	jQuery('#date_day_<?php echo $attributes['id']?>').on('change', function(e){
		set_final_date_<?php echo $attributes['id']?>();
	});
});


function set_final_date_<?php echo $attributes['id']?>(){

	if( (jQuery('#date_year_<?php echo $attributes['id']?>').val() != '') && (jQuery('#date_month_<?php echo $attributes['id']?>').val() != '') && (jQuery('#date_day_<?php echo $attributes['id']?>').val() != '') ){

		jQuery('#<?php echo $attributes['id']?>').val( jQuery('#date_year_<?php echo $attributes['id']?>').val() + '-' + jQuery('#date_month_<?php echo $attributes['id']?>').val() + '-' + jQuery('#date_day_<?php echo $attributes['id']?>').val());

	}
}
</script>