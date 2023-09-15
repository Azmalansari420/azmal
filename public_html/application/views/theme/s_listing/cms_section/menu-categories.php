<?php foreach($category_menu as $category){?>
    <a class="dropdown-item" href="<?php echo category_url($category->slug)?>"><?php echo clean_display($category->name)?></a>
<?php }?>