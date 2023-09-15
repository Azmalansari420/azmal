<?php if(isset($attributes['view'])){?>
    <div class="tags-container-block" id="<?php echo $attributes['id']?>_tag_container">
        <?php if(isset($attributes['value'])){?>
            <?php $values = explode(",", clean_display($attributes['value']));?>
            <?php foreach($values as $value){?>
                <span class="tm-tag wd-tag-item">
                    <span><?php echo $value?></span>
                </span>
            <?php }?>
        <?php }?>        
    </div>
<?php }else{?>
    <div class="tags-container">
        <div class="input-btn-group">
            <input data-field="tag" type="text" id="<?php echo $attributes['id']?>" placeholder="Enter Here..." class="tm-input form-control"/>
            <span class="input-group-btn">
                <button class="btn btn-success" type="button" id="<?php echo $attributes['id']?>_tag_add_button"><span class="ico">add_circle_outline</span> Add</button>
            </span>        
        </div>
        <?php if(isset($attributes['comment'])){?>
            <span class="text-muted"><?php echo clean_display($attributes['comment'])?></span>
            <?php unset($attributes['comment'])?>
        <?php }else{?>
            <span class="text-muted">Enter text above and click Add button.</span>
        <?php }?>
    </div>
    <div class="tags-container-block" id="<?php echo $attributes['id']?>_tag_container">

    </div>
    <script type="text/javascript">
    jQuery(function(){
        jQuery("#<?php echo $attributes['id']?>").tagsManager({
            <?php if(isset($attributes['value'])){?>
                prefilled: <?php echo json_encode(explode(",", $attributes['value']))?>,
            <?php }?>    
            CapitalizeFirstLetter: true,
            AjaxPush: null,
            AjaxPushAllTags: null,
            AjaxPushParameters: null,
            delimiters: [9, 13, 44],
            backspace: [8],
            blinkBGColor_1: '#FFFF9C',
            blinkBGColor_2: '#CDE69C',
            hiddenTagListName: '<?php echo $attributes['name']?>',
            hiddenTagListId: null,
            deleteTagsOnBackspace: true,
            tagsContainer: jQuery('#<?php echo $attributes['id']?>_tag_container'),
            tagCloseIcon: '×',
            tagClass: 'wd-tag-item',
            validator: null,
            onlyTagList: false
        });

        jQuery('#<?php echo $attributes['id']?>_tag_add_button').on('click', function(){
            if(jQuery('#<?php echo $attributes['id']?>').val() != ''){
                jQuery("#<?php echo $attributes['id']?>").tagsManager('pushTag', jQuery('#<?php echo $attributes['id']?>').val());
            }	
        });
    });
    </script>
<?php }?>    