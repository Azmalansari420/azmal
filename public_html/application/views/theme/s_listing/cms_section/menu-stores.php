<?php foreach($stores_menu as $menu){?>
    <a class="dropdown-item" href="<?php echo store_url($menu['slug'])?>"><?php echo clean_display($menu['title'])?></a>
<?php }?>