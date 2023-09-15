
1

<?php foreach($featured_stores as $store){?>
    <div>
        <div class="s-grd-i">
            <a class="s-grd-lnk" href="<?php echo store_url($store->slug)?>" title="<?php echo clean_display($store->promo_title)?>">
                <div class="s-grd-h">
                    <?php $logo = ($store->logo_md != '') ? $store->logo_md : (($store->logo_sm != '') ? $store->logo_sm : $store->logo_orig);?>
                    <img class="lzl" alt="<?php echo clean_display($store->promo_title)?>" src="<?php echo media_url().'f-img.png'?>" data-src="<?php echo media_url()."uploads/".$logo?>">
                </div>    
                <h3 class="s-grd-head"><?php echo clean_display($store->title)?></h3>
            </a>    
        </div>
    </div>    
<?php }?>