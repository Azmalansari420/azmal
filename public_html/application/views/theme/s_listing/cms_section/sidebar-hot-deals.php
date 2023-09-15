<ul class="list-group d-l-lg">
    <?php foreach($deals as $deal){?>
        <a href="" class="list-group-item list-group-item-action">
            <div class="d-l-ico">
                <?php if($deal->ico_type == 't'){?>
                    <?php
                    $ic_class = 't';
                    if(stristr(strtolower($deal->ico_text), 'rs')){
                        $ic_class = 't-r';
                        $deal->ico_text = str_replace(array('Rs', 'rs'), "<span class='d-p-c'>Rs</span>", str_replace('.', '', $deal->ico_text));
                    }
                    ?>
                    <div class="d-l-p-discount <?php echo $ic_class?> br-f">
                        <?php echo clean_display($deal->ico_text)?>
                    </div>
                <?php }elseif($deal->ico_type == 's'){?>
                    <div class="d-l-p-discount s br-f">
                        Sale
                    </div>
                <?php }elseif($deal->ico_type == 'i'){?>
                    <img src="<?php echo $deal->ico_img?>">
                <?php }else{?>

                    <?php if($page_type == 'single_category'){?>
                        <?php $logo = ($deal->logo_sm != '') ? $deal->logo_sm : (($deal->logo_md != '') ? $deal->logo_md : '');?>
                        <?php if($logo != ''){?>
                            <div class="d-l-p-discount sl">
                                <img alt="<?php echo clean_display($deal->store_title)?>" src="<?php echo media_url()."uploads/".$logo?>">
                            </div>    
                        <?php }else{?>
                            <div class="d-l-p-discount c">
                                <div class="tr-2 cl-ico ico cl-<?php echo substr(strtolower($deal->category_name), 0, 3)?>"></div>
                            </div>    
                        <?php }?>    
                    <?php }else{?>
                        <div class="d-l-p-discount c">
                            <div class="tr-2 cl-ico ico cl-<?php echo substr(strtolower($deal->category_name), 0, 3)?>"></div>
                        </div>    
                    <?php }?>

                <?php }?>
            </div>
            <div class="d-l-content">
                <strong class="d-l-title">
                    <?php echo clean_display($deal->title)?>
                </strong>
            </div>
        </a>
    <?php }?>
</ul>