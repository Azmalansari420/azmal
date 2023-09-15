<div class="rating-container" style="width:100%">
	<div class="rating" id="rating_<?php echo $attributes['id']?>"></div>
	<input type="hidden" name="<?php echo $attributes['name']?>" id="<?php echo $attributes['id']?>" />
</div>
<script type="text/javascript">
jQuery(function(){
	jQuery("#rating_<?php echo $attributes['id']?>").rateYo({
    	starWidth: "25px",
		normalFill: "#aaa",
		ratedFill: "#2196F3",
		numStars: <?php echo (isset($attributes['rate']) && $attributes['rate'] != '') ? $attributes['rate'] : 5 ;?>,
		precision: 10,
		fullStar: true,
		spacing: "5px",
		onSet: function (rating, rateYoInstance){
			if(rating > 0){
	      		jQuery('#<?php echo $attributes['id']?>').val(rating);
			}	
    	}
  	});
});
</script>