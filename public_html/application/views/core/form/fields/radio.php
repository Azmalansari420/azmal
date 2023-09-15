<?php
$radio_options = $attributes['options'];
unset($attributes['options']);
?>
<?php if(count($radio_options) > 0){?>
	<ul class="wd-ui-radio">
		<?php foreach($radio_options as $option_label => $option_value){?>
			<?php /*?><label class="radio-inline">
				<input type="radio" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id'] . "_" .$option_value?>" value="<?php echo $option_label?>" <?php if((array_key_exists('value', $attributes) && $attributes['value'] == $option_label) || ($option_value == $attributes['value'])){?> checked="checked"<?php }?> <?php echo (isset($attributes['disabled']))? 'disabled='.$attributes['disabled']:''?>> <?php echo $option_value?>
			</label><?php */?>
			<li>
				<input data-field="radio" type="radio" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id'] . "_" .$option_value?>" value="<?php echo $option_label?>" <?php if((array_key_exists('value', $attributes) && ($attributes['value'] != '' && $attributes['value'] == $option_label))){?> checked="checked"<?php }?> <?php echo (isset($attributes['disabled']))? 'disabled='.$attributes['disabled']:''?> />
				<label for="<?php echo $attributes['id'] . "_" .$option_value?>"><?php echo $option_value?></label>
				<div class="check"><div class="inside"></div></div>
			</li>	
		<?php }?>
	</ul>	
<?php }?>