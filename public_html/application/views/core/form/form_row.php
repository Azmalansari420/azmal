<div class="form-group row <?php /*echo (array_key_exists("row_class", $_attributes)) ? $_attributes['row_class'] : ''*/?> <?php echo (array_key_exists("field_class", $_attributes)) ? $_attributes['field_class'] : ((array_key_exists("row_class", $_attributes)) ? $_attributes['row_class'] : '')?>">
	<?php //print_r($_attributes)?>
	<?php
	//if multiple column form row
	//for multiple grid - review in future
	if(array_key_exists('fields', $_attributes)){
		
		$elements_list = $_attributes;
		
		$row_class = 'col-lg-6 col-xs-12';
		if(count($elements_list['fields']) == 2){
			$row_class = 'col-lg-6 col-xs-12';
		}elseif(count($elements_list['fields']) == 3){
			$row_class = 'col-lg-4 col-xs-12';
		}elseif(count($elements_list['fields']) == 4){
			$row_class = 'col-lg-3 col-xs-12';
		}
		
		unset($elements_list['field_class']);
		
		$fields = $elements_list['fields'];
		
		foreach($fields as $col_element => $attributes){
			
			$_row_class = $row_class;
			if(isset($attributes['row_class']) && $attributes['row_class'] != ''){
				$_row_class= $attributes['row_class'];
			}
			
			$element		 = $attributes['name'];
			$element_id = isset($attributes['id']) ? $attributes['id'] : $element;
			$required = (isset($attributes['validation']) && strstr($attributes['validation'], "required")) ? '<span class="req">*</span>' : "";
			
			echo '<div class="'.$_row_class.'">';
				if(!isset($attributes['inline-edit'])){
					echo '<label class="col-sm-12 form-control-label" for="'.$element_id.'">'.$attributes['label'].$required.'</label>';
				}
				$label = '';//important
				$col_grid_class = ($attributes['type']=='textarea' || $attributes['type']=='texteditor' || $attributes['type']=='inline-html') ? "col-sm-8" : ((array_key_exists('col_class', $attributes) && $attributes['col_class']!='') ? $attributes['col_class'] : "col-sm-4");
				
				include "elements.php";
				$comment = (isset($attributes['comment'])) ? $attributes['comment'] : false;
				echo ($comment) ? '<span class="text-muted">'.$comment.'</span>' : '';
				echo form_error($element, '<div class="help-block help-block-pop"><span class="ico">warning</span>', '</div>');			
			echo '</div>';
		}

	}else{
		$attributes		 = $_attributes;
		$element		 = $_attributes['name'];
		$element_id = isset($attributes['id']) ? $attributes['id'] : $element;
		$required = (isset($attributes['validation']) && strstr($attributes['validation'], "required")) ? '<span class="req">*</span>' : "";
		
		if($_attributes['type']=='texteditor' || $_attributes['type']=='inline-html' || $_attributes['type']=='gallery' || ($_attributes['type']=='file' && (array_key_exists('multiple', $_attributes) && $_attributes['multiple'] == true))){
			$row_class = 'col-lg-12';
		}elseif($_attributes['type']=='textarea'){
			if(array_key_exists('col_class', $_attributes) && $_attributes['col_class'] != ''){
				$row_class = trim($_attributes['col_class']);
			}else{
				$row_class = 'col-lg-12';
			}
		}elseif(array_key_exists('col_class', $_attributes) && $_attributes['col_class'] != ''){
			$row_class = trim($_attributes['col_class']);
		}else{
			$row_class = 'col-lg-12';
		}
		
		//$row_class = ($_attributes['type']=='textarea' || $_attributes['type']=='texteditor' || $_attributes['type']=='inline-html') ? "col-sm-12" : ((array_key_exists('col_class', $_attributes) && $_attributes['col_class']!='') ? $_attributes['col_class'] : "col-sm-6");
		
		echo '<div class="'.$row_class.'">';
			if(!isset($attributes['inline-edit'])){
				echo '<label class="col-sm-12 form-control-label" for="'.$element_id.'">'.$attributes['label'].$required.'</label>';
			}
			$label = '';//important
			$col_grid_class = ($_attributes['type']=='textarea' || $_attributes['type']=='texteditor' || $_attributes['type']=='inline-html') ? "col-sm-8" : ((array_key_exists('col_class', $_attributes) && $_attributes['col_class']!='') ? $_attributes['col_class'] : "col-sm-4");
			
			include "elements.php";
			$comment = (isset($attributes['comment'])) ? $attributes['comment'] : false;
			echo ($comment) ? '<span class="text-muted">'.$comment.'</span>' : '';
			echo form_error($element, '<div class="help-block help-block-pop"><span class="ico">warning</span>', '</div>');			
		echo '</div>';
		
	}
	?>	
</div>

<?php
$field_attributes = array();
if(array_key_exists('fields', $_attributes)){
	foreach($fields as $col_element => $attributes){
		$field_attributes[] = $attributes;
	}
}else{
	$field_attributes[] = $_attributes;
}
?>

<?php foreach($field_attributes as $_field_attributes){?>
	
	<?php if(isset($_field_attributes['validate_unique'])){?>
	<script type="text/javascript">
	jQuery(function(){
		jQuery('#<?php echo $_field_attributes['id']?>').blur(function(){
			if(jQuery('#<?php echo $_field_attributes['id']?>').val() != ''){
				validate_unique_value('<?php echo $_field_attributes['id']?>', jQuery('#<?php echo $_field_attributes['id']?>').val(), '<?php echo $_field_attributes['name']?>', '<?php echo $_field_attributes['validate_unique']?>');
			}	
		});
	});
	</script>
	<?php }?>
	
	<?php if(array_key_exists('calendar', $_field_attributes) && $_field_attributes['calendar'] == true){?>
		<script type="text/javascript">
		jQuery(function(){
			<?php if(array_key_exists('date-depends', $_field_attributes) && $_field_attributes['date-depends'] != ''){?>
			   	//jQuery('#<?php echo $_field_attributes['id']?>').datetimepicker();
			   	jQuery('#<?php echo $_field_attributes['id']?>').datetimepicker({format: 'DD-MM-YYYY'});
	
			   	//jQuery("#<?php echo $_field_attributes['id']?>").on("dp.change", function (e) {
			   	//	jQuery('#<?php echo $_field_attributes['date-depends']?>').data("DateTimePicker").minDate(e.date);
		        //});
			   	jQuery("#<?php echo $_field_attributes['date-depends']?>").on("dp.change", function (e) {
			   		jQuery('#<?php echo $_field_attributes['id']?>').data("DateTimePicker").minDate(e.date);
			   		//jQuery('#<?php echo $_field_attributes['id']?>').datetimepicker({format: 'DD-MM-YYYY'});
		        });
			<?php }else{?>
				//jQuery('#<?php echo $_field_attributes['id']?>').datetimepicker();
				jQuery('#<?php echo $_field_attributes['id']?>').datetimepicker({format: 'DD-MM-YYYY'});
			<?php }?>   	
		});
		</script>
	<?php }?>
	
	<?php if(isset($_field_attributes['calender-time'])){?>
	<script type="text/javascript">
	$(function(){
		<?php $year = (int)date('Y')?>
		$('#<?php echo $_field_attributes['id']?>').datetimepicker({
			controlType: 'select',
			timeFormat: 'hh:mm tt',
			'dateFormat':'mm/dd/yy',
			changeMonth: true,
			changeYear: true,
			yearRange: "1940:<?php echo $year+15?>"
		});
		
	});
	</script>
	<?php }?>
	
	<?php if(isset($_field_attributes['autocomplete']) && $_field_attributes['autocomplete']==true){?>
	<script type="text/javascript">
	$(function(){
		var cache = {};
		
		$("#<?php echo $_field_attributes['id']?>").autocomplete({
			minLength:2,
			search:function(event, ui){
				if(!$('#au_<?php echo $_field_attributes['id']?>').length){
					$("<div class='wait_small' id='au_<?php echo $_field_attributes['id']?>'></div>").insertAfter($('#<?php echo $_field_attributes['id']?>'));
				}
			},
			source:function(request, response){
				var term = request.term;
				if(term in cache){
					response(cache[term]);
					return;
				}
				$.getJSON('<?php echo $_field_attributes['url']?>', request, function(data, status, xhr){
					cache[term] = data;
					response(data);
				});
			},
			select:function(event, ui){$("#au_<?php echo $_field_attributes['id']?>").remove();},
			close:function(event, ui){$("#au_<?php echo $_field_attributes['id']?>").remove();},
		});
	});
	</script>
	<?php }?>
<?php }?>	