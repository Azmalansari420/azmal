<section class="panel">
	<div class="form-group">
    	<label class="control-label col-md-3">Add Vendors</label>
        <div class="col-md-9">
        	<select id="my_multi_select3" multiple="" class="multi-select">
        		<?php foreach($vendors as $id => $vendor){?>
        	    	<option value="<?php echo $id?>"><?php echo $vendor?></option>
        	    <?php }?>
        	</select>
       	</div>
	</div>
    <input type="hidden" name="vendors" id="selected_vendors" <?php if($selected_vendors){?> value="<?php //echo implode(",", $selected_vendors)?>"<?php }?> />
</section>

<script type="text/javascript">
pre_selected_vendors = [];
selected_vendors = [];
<?php if($selected_vendors){?>
	<?php foreach($selected_vendors as $selected_vendor){?>
		//selected_vendors.push('<?php echo $selected_vendor?>');
	<?php }?>
<?php }?>	
jQuery(function(){
jQuery('#my_multi_select3').multiSelect({
    selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
    selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
    afterInit: function (ms) {
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function (e) {
                if (e.which === 40) {
                    that.$selectableUl.focus();
                    return false;
                }
            });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function (e) {
                if (e.which == 40) {
                    that.$selectionUl.focus();
                    return false;
                }
            });
    },
    afterSelect: function (values) {
        this.qs1.cache();
        this.qs2.cache();
		selected_vendors.push(values);
		jQuery('#selected_vendors').val(selected_vendors);
    },
    afterDeselect: function (value) {
        this.qs1.cache();
        this.qs2.cache();
		_selected_vendors = jQuery('#selected_vendors').val();
		_selected_vendors = _selected_vendors.split(',');
		
		jQuery.each(_selected_vendors, function(nr,rr){
			if(rr == value){
				_selected_vendors.splice(nr, 1);
			}
		});
		jQuery('#selected_vendors').val(_selected_vendors);
		selected_vendors = _selected_vendors;
    }
});

<?php if($selected_vendors){?>
	<?php foreach($selected_vendors as $selected_vendor){?>
		pre_selected_vendors.push('<?php echo $selected_vendor?>');
	<?php }?>
	jQuery('#my_multi_select3').multiSelect('select', pre_selected_vendors);
<?php }?>	
});

</script>