
<?php foreach($menu as $category_id => $menu_item){?>

    <?php $category = $menu_item['category']?>

    <li class="nav-item <?php echo $category->slug?>">
        <a class="nav-link <?php echo (count($menu_item['child']) > 0) ? 'has-childs' : ''?>" href="<?php echo category_url($category->slug);?>"><?php echo clean_display($category->name)?></a>

        <?php if(count($menu_item['child']) > 0){?>
        
            <div class="childs-wrapper">

                <ul>

                    <?php foreach($menu_item['child'] as $child_category_id => $_child_category){?>
                    
                        <?php $child_category = $_child_category['category']?>

                        <li class="nav-item">
                            
                            <a class="nav-link" href="<?php echo category_url($category->slug . "/" . $child_category->slug);?>"><?php echo clean_display($child_category->name)?></a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
    </li>
<?php }?>