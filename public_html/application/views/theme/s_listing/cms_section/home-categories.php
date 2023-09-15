<?php foreach($categories as $category){?>
    <li class="cts-lst-i">
        <a class="cts-lst-lnk tr-2" href="<?php echo category_url($category->slug)?>" title="<?php echo clean_display($category->name)?>">
            <div class="tr-2 ico c-<?php echo substr(strtolower($category->name), 0, 3)?>"></div>   
            <h3 class="cts-lst-head"><?php echo clean_display($category->name)?></h3>
        </a>    
    </li>
<?php }?>