
WdCart = function(base_url, bundled_products) {

    this.init(base_url, bundled_products);
}

$.extend(WdCart.prototype, {
    base_url: null,
    cart_url: null,
    checkout_url: null,
    header_cart_wrapper: null,
    header_cart_container: null,
    additional_products: {},
    bundled_products: {},

    init: function(base_url, __bundled_products){
        WdCart.prototype.base_url = base_url;
        WdCart.prototype.cart_url = base_url + '/cart';
        WdCart.prototype.checkout_url = base_url + '/cart/checkout';

        WdCart.prototype.cartCounts();
        WdCart.prototype.cartTotal();

        if(typeof __bundled_products == 'object'){

            var _bundled_products = {};

            for(var b in __bundled_products){

                _bundled_products[parseInt(b)] = 1;
            }

            var price = _product_prices['final_price'];
            price = parseFloat(price);

            WdCart.prototype.bundled_products = _bundled_products;
            WdCart.prototype.calculateBundledPrice(price);
        }
    },
    
    cartCounts: function(){
        jQuery.ajax({
            url: WdCart.prototype.cart_url + '/items_count',
            success: function(r){
                jQuery('.header-items-count').html(r);
            }
        });
    },

    cartTotal: function(){
        jQuery.ajax({
            url: WdCart.prototype.cart_url + '/items_total',
            success: function(r){
                jQuery('.cart-price-block .price.total').html(r);
            }
        });
    },

    loadCartSummary: function(){
        jQuery.ajax({
            url: WdCart.prototype.cart_url + '/cart_summary',
            dataType: 'json',
            success: function(r){

                jQuery('.sidebar-cart-container').html(r.html);
            }
        });
    },

    cartSummary: function(){
        WdCart.prototype.loadCartSummary();
        jQuery('.header-cart').addClass('active');
        jQuery('body').addClass('fix');
        jQuery('.cart-sidebar').addClass('show');
    },

    hideCartSummary: function(){
        jQuery('.header-cart').removeClass('active');
        jQuery('body').removeClass('fix');
        jQuery('.cart-sidebar').removeClass('show');
    },

    addToCart: function(product_form, button){

        wd_loader(jQuery(product_form).parent());

        var data = product_form.serializeArray();
        
        if(Object.keys(WdCart.prototype.additional_products).length){

            data.push({name: 'additional_products', value: JSON.stringify(WdCart.prototype.additional_products)});
        }

        if(Object.keys(WdCart.prototype.bundled_products).length){

            data.push({name: 'bundled_products', value: JSON.stringify(WdCart.prototype.bundled_products)});
        }

        jQuery.ajax({
            method: 'post',
            url: WdCart.prototype.cart_url + '/add_to_cart',
            data: data,
            dataType: 'json',
            success: function(a){
                
                remove_wd_loader(jQuery(product_form).parent());

                if(a.status == 'error'){

                    error = '<div class="alert alert-danger alert-dismissible" role="alert">'+a.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    jQuery(error).insertBefore('.content-wrapper');

                    jQuery('html, body').animate({scrollTop: jQuery(".page").offset().top}, 800);
                    
                    window.setTimeout(function(){

                        jQuery('.alert-danger').remove();
                    }, 5000);
                }else{
                
                    WdCart.prototype.cartCounts();
                    WdCart.prototype.cartTotal();

                    // WdCart.prototype.cartSummary();

                    message = '<div class="alert alert-success alert-dismissible" role="alert">'+a.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    jQuery(message).insertBefore('.content-wrapper');

                    jQuery('html, body').animate({scrollTop: jQuery(".page").offset().top}, 800);

                    window.setTimeout(function(){
                        window.location.href = WdCart.prototype.cart_url;
                    }, 3000);

                    // window.setTimeout(function(){
                    //     WdCart.prototype.hideCartSummary();
                    // }, 10000);
                }
            },
            failure: function(){
                remove_wd_loader(jQuery(product_form).parent());
            },
            error: function(){
                remove_wd_loader(jQuery(product_form).parent());
            }
        });
    },

    updateCartItem: function(element, qty, type){
        
        cart_url = WdCart.prototype.cart_url;

        wd_loader(jQuery('.cart-items-list'));

        jQuery.ajax({
            method: 'post',
            url: WdCart.prototype.cart_url + '/update_cart_item',
            data: {item_id: jQuery(element).data('rel'), qty: qty},
            dataType: 'json',
            success: function(a){
                
                if(a.status == 'success'){
                    
                    if(type == 'post'){
                        window.location = cart_url;
                    }

                    WdCart.prototype.cartCounts();
                    WdCart.prototype.cartTotal();

                    if(!type || type != 'post'){
                        WdCart.prototype.cartSummary();
                    }

                }else{

                    remove_wd_loader(jQuery('.cart-items-list'));
                    error = '<div class="alert alert-danger alert-dismissible" role="alert">'+a.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    jQuery(error).insertBefore('.content-wrapper');
                    jQuery(error).insertBefore('.cart-items-list');

                    window.setTimeout(function(){

                        jQuery('.alert-danger').remove();
                    }, 5000);
                }
            }
        });
    },

    removeCartItem: function(element, type){
        
        cart_url = WdCart.prototype.cart_url;

        wd_loader(jQuery('.shopping-cart-wrapper'));
        jQuery.ajax({
            method: 'post',
            url: WdCart.prototype.cart_url + '/remove_cart_item',
            data: {item_id: jQuery(element).data('rel')},
            dataType: 'json',
            success: function(a){
                
                if(a.status == 'success'){
                    if(type == 'post'){
                        window.location = cart_url;
                    }

                    WdCart.prototype.cartCounts();
                    WdCart.prototype.cartTotal();
                    
                    WdCart.prototype.cartSummary();

                }else{
                    remove_wd_loader(jQuery('.shopping-cart-wrapper'));
                    error = '<div class="alert alert-danger alert-dismissible" role="alert">'+a.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    jQuery(error).insertBefore('.content-wrapper');
                }
            }
        });
    },

    triggerBundleProduct: function(it, product_id){

        var bundled_products = WdCart.prototype.bundled_products;

        if(jQuery(it).is(':checked')){

            if(bundled_products.hasOwnProperty(product_id)){

                delete bundled_products[product_id];

                if(jQuery('#bundle_item_qty_' + product_id).length){
                
                    jQuery('#bundle_item_qty_' + product_id).attr('disabled', 'disabled');
                }
            }
        }else{

            if(jQuery('#bundle_item_qty_' + product_id).length){
            
                jQuery('#bundle_item_qty_' + product_id).removeAttr('disabled');
                bundled_products[product_id] = jQuery('#bundle_item_qty_' + product_id).val();
            }else{

                bundled_products[product_id] = 1;
            }
        }

        WdCart.prototype.bundled_products = bundled_products;

        WdCart.prototype.calculateBundledPrice();
    },

    changeBundleQty: function(product_id, qty){

        var bundled_products = WdCart.prototype.bundled_products;

        bundled_products[product_id] = qty;

        WdCart.prototype.bundled_products = bundled_products;

        WdCart.prototype.calculateBundledPrice();
    },

    calculateBundledPrice: function(){

        var price = _product_prices['final_price'];
        price = parseFloat(price);

        price = WdCart.prototype.calculateBundleProductsPrice(price);

        price = WdCart.prototype.calculateAdditionalPrice(price);

        jQuery('.product-actions .final-price').text(price);
    },

    calculateBundleProductsPrice: function(price){

        var bundled_products = WdCart.prototype.bundled_products;

        if(Object.keys(bundled_products).length > 0){

            for(var _product_id in bundled_products){

                if(_additional_products_json){

                    var _qty = bundled_products[_product_id];

                    if(_additional_products_json.hasOwnProperty(_product_id)){

                        var _data = _additional_products_json[_product_id];

                        var _special_price = _data['price']['special_price'];
                        var _price = _data['price']['price'];

                        if(parseFloat(_special_price) > 0 && (parseFloat(_special_price) < parseFloat(_price))){

                            price = parseFloat(price) + parseFloat(parseFloat(_special_price) * parseInt(_qty));
                        }else{

                            price = parseFloat(price) + parseFloat(parseFloat(_price) * parseInt(_qty));
                        }

                        if(_data['price'].hasOwnProperty('additional_prices')){

                            for(var p in _data['price']['additional_prices']){

                                var additional_price = _data['price']['additional_prices'][p];

                                if(additional_price.required == 1){

                                    price = parseFloat(price) + parseFloat(additional_price.price);
                                }
                            }
                        }
                    }
                }
            }
        }

        return price;
    },

    changeAdditionalQty: function(product_id, qty){

        var additional_products = WdCart.prototype.additional_products;

        if(additional_products.hasOwnProperty(product_id)){

            additional_products[product_id] = qty;
        }

        WdCart.prototype.additional_products = additional_products;

        WdCart.prototype.calculateBundledPrice();
    },

    addAdditionalProduct: function(it, product_id){

        var additional_products = WdCart.prototype.additional_products;

        additional_products[product_id] = jQuery('#additional_item_qty_' + product_id).val();

        jQuery(it).parent().html("<button class='btn btn-danger btn-xs' onclick='_WdCart.removeAdditionalProduct(this, "+product_id+")'>Remove</button>");
        
        WdCart.prototype.additional_products = additional_products;

        WdCart.prototype.calculateBundledPrice();
    },

    removeAdditionalProduct: function(it, product_id){

        var additional_products = WdCart.prototype.additional_products;
        
        if(additional_products.hasOwnProperty(product_id)){

            delete additional_products[product_id];
        }

        jQuery(it).parent().html("<button class='btn btn-primary btn-xs' onclick='_WdCart.addAdditionalProduct(this, "+product_id+")'>Buy</button>");
    
        WdCart.prototype.additional_products = additional_products;

        WdCart.prototype.calculateBundledPrice();
    },

    calculateAdditionalPrice: function(price){

        var additional_products = WdCart.prototype.additional_products;

        for(var _product_id in additional_products){

            if(_additional_products_json){

                var _qty = additional_products[_product_id];

                if(_additional_products_json.hasOwnProperty(_product_id)){

                    var _data = _additional_products_json[_product_id];

                    var _special_price = _data['price']['special_price'];
                    var _price = _data['price']['price'];

                    if(parseFloat(_special_price) > 0 && (parseFloat(_special_price) < parseFloat(_price))){

                        price = parseFloat(price) + parseFloat(parseFloat(_special_price) * parseInt(_qty));
                    }else{

                        price = parseFloat(price) + parseFloat(parseFloat(_price) * parseInt(_qty));
                    }
                }
            }
        }

        return price;
    },

    loadPaymentMethods: function(address_id, state_id, zipcode){

        wd_loader(jQuery('.card payment-method-card'));

        jQuery.ajax({
            type: 'post',
            data: {address_id: address_id, state_id: state_id, zipcode: zipcode},
            url: WdCart.prototype.checkout_url + '/payment_methods',
            dataType: 'json',
            success: function(a){
                
                remove_wd_loader(jQuery('.card payment-method-card'));

                if(a.status == 'success'){

                    jQuery('#payment_methods_html').html(a.html);
                    jQuery('#checkout_summary').html(a.summary);
                }
                // if(a.status){
                //     jQuery('#checkout_offers').html('Coupons applied successfully!');
                //     jQuery('.discount-price').html(a.discount_amount);
                //     jQuery('.total-price').html(a.total_amount);
                // }else{
                //     alert(a.message);
                // }
            },
            error: function(e){

                remove_wd_loader(jQuery('.card payment-method-card'));
            }
        });
    },
});

if (typeof _bundled_products_json !== 'undefined') {

    var _WdCart = new WdCart(_home_url, _bundled_products_json);
}else{

    var _WdCart = new WdCart(_home_url, false);
}

jQuery(function(){

    if(jQuery('.bundled_product_qty').length){

        jQuery(document).on('change', '.bundled_product_qty', function(e){

            var id = jQuery(this).attr('id');
            var product_id = id.split('bundle_item_qty_')[1];

            _WdCart.changeBundleQty(product_id, jQuery(this).val());
        });
    }

    if(jQuery('.additional_product_qty').length){

        jQuery(document).on('change', '.additional_product_qty', function(e){

            var id = jQuery(this).attr('id');
            var product_id = id.split('additional_item_qty_')[1];

            _WdCart.changeAdditionalQty(product_id, jQuery(this).val());
        });
    }

    jQuery(document).on('click', '.btn-increase-qty', function(e){

        var _ele = jQuery(this);

        var _qty = _ele.siblings('.item-qty').val();
        
        var qty = parseInt(_qty) + 1; 
        
        _WdCart.updateCartItem(_ele, qty, 'post');
    });

    jQuery(document).on('click', '.btn-decrease-qty', function(e){

        var _ele = jQuery(this);

        var _qty = _ele.siblings('.item-qty').val();

        if(parseInt(_qty) > 1){
        
            var qty = parseInt(_qty) - 1; 
        
            _WdCart.updateCartItem(_ele, qty, 'post');
        }
    });

    jQuery(document).on('click', '.increase-qty', function(e){

        var _ele = jQuery(this);

        var _rel = jQuery(_ele).data('rel');
        var _qty = jQuery('.qty_' + _rel).text();
        
        var qty = parseInt(_qty) + 1; 
        
        _WdCart.updateCartItem(_ele, qty);
    });

    jQuery(document).on('click', '.decrease-qty', function(e){

        var _ele = jQuery(this);

        var _rel = jQuery(_ele).data('rel');
        var _qty = jQuery('.qty_' + _rel).text();

        if(parseInt(_qty) > 1){
        
            var qty = parseInt(_qty) - 1; 
        
            _WdCart.updateCartItem(_ele, qty);
        }
    });

    jQuery(document).on('click', '.apply-coupons', function(){
        _WdCart.redeemCoupons();
    });

    if(jQuery('#add_to_cart_form').length){
        _add_to_cart_form_ = jQuery('#add_to_cart_form');
        _add_to_cart_form_validate = _add_to_cart_form_.validate({
            highlight: function(element) {
                jQuery(element).closest('.form-group').addClass('has-error');
                jQuery(element).addClass('form-control-error');
                jQuery(element).closest('ul').addClass('has-error');
            },
            unhighlight: function(element) {
                jQuery(element).closest('.form-group').removeClass('has-error');
                jQuery(element).removeClass('form-control-error');
                jQuery(element).closest('ul').removeClass('has-error');
            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').append(error);
                element.closest('ul').after(error);
            },
        });
    }

    jQuery(document).on('submit', '.add-to-cart-frm', function(e){
        _WdCart.addToCart(this, this);
        return false;
    });

    jQuery(document).on('click', '.add-to-cart-main', function(e){
        if(_add_to_cart_form_.valid()){
            _WdCart.addToCart(_add_to_cart_form_, this);
            return false;
        }

        return false;
    });

    jQuery(document).on('click', '.close-cart-summary, .continue-shopping', function(e){
        _WdCart.hideCartSummary();
    });

    jQuery(document).on('click', '.header-cart', function(e){
        if(jQuery(this).hasClass('active')){
            _WdCart.hideCartSummary();
        }else{
            _WdCart.cartSummary();
        }    
    });

    jQuery(document).on('click', '.remove-cart-item', function(e){
        if(confirm('Do you really want to remove this item!')){

            if(jQuery(this).data('source') == 'cart'){

                _WdCart.removeCartItem(this, 'post');    
            }else{
            
                _WdCart.removeCartItem(this, false);
            }
        }
    });

    jQuery(document).on('click', '.update-cart-item', function(e){
        // _qty = jQuery(this).closest('tr').find('input[name=qty]').val();
        _qty = jQuery(this).closest('tr').find('.item-qty').val();

        if(_qty == ''){
            alert('Quantity must not be blank.');
            jQuery(this).closest('tr').find('input[name=qty]').val('1');
            return;
        }else if(!jQuery.isNumeric(_qty)){
            alert('Invalid Quantity');
            _qty = 1;
            jQuery(this).closest('tr').find('input[name=qty]').val(_qty);
            // return;
        }else if(_qty < 1){
            alert('Quantity must be greater than or equal to 1.');
            jQuery(this).closest('tr').find('input[name=qty]').val('1');
            return;
        }

        _WdCart.updateCartItem(this, _qty, 'post');
    });

    if(jQuery('#cart_form').length){
        jQuery('#cart_form').validate();

        jQuery(document).on('click', '.checkout-btn', function(e){
            e.preventDefault();
            if(jQuery('#cart_form').valid()){
                jQuery('#cart_form').submit();
            }
        });
    }

    jQuery(document).on('change', '.shipping-method', function(){
        
        if(jQuery(this).data('help-block')){
            jQuery('.shipping-help-block').hide();
            if(jQuery(this).is(':checked')){
                jQuery('#'+jQuery(this).data('help-block')).show();
            }else{
                jQuery('#'+jQuery(this).data('help-block')).hide();
            }    
        }    
    });    

    if(jQuery('#checkout_form').length){
        
        _checkout_form_ = jQuery('#checkout_form');
        _checkout_form_validate = _checkout_form_.validate({
            ignore: ":hidden",
            highlight: function(element) {
                jQuery(element).closest('.form-group').addClass('has-error');
                jQuery(element).addClass('form-control-error');
                jQuery(element).closest('.checkout-methods-list').addClass('has-error');
            },
            unhighlight: function(element) {
                jQuery(element).closest('.form-group').removeClass('has-error');
                jQuery(element).removeClass('form-control-error');
                jQuery(element).closest('.checkout-methods-list').removeClass('has-error');
            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').append(error);
                element.closest('.checkout-methods-list').after(error);
            },
        });

        jQuery(document).on('click', 'input[name="address_id"]', function(){

            jQuery('#state_dropdown').val('');
            jQuery('#zipcode').val('');

            if(jQuery(this).is(':checked')){

                _WdCart.loadPaymentMethods(jQuery(this).val(), 0, 0);
            }
        });

        jQuery(document).on('blur', '#zipcode', function(){

            _WdCart.loadPaymentMethods(0, jQuery('#state_dropdown').val(), jQuery(this).val());
        });

        jQuery(document).on('change', '#state_dropdown', function(e){

            _WdCart.loadPaymentMethods(0, jQuery(this).val(), jQuery('#zipcode').val());
        });

        window.setTimeout(function(){

            if(jQuery('input[name="address_id"]').length){
            
                var _default_address = jQuery('input[name="address_id"]:checked');
                
                if(_default_address){

                    var _default_address_id = _default_address.val();

                    _WdCart.loadPaymentMethods(_default_address_id, 0, 0);
                }
            }
        }, 500);
    }
    
    jQuery(document).on('click', '.proceed-to-pay', function(){

        _checkout_form_.validate();
        jQuery('.address-error').remove();
        
		if(_checkout_form_.valid()){

            if(!jQuery('#checkout_new_address').is(':checked')){

                if(jQuery('input[name="address_id"]').length){
                    
                    var selected_address_id = jQuery('input[name="address_id"]:checked').val();
                    
                    if(typeof selected_address_id == 'undefined'){

                        jQuery('<div class="alert alert-danger alert-dismissible address-error" role="alert">Please select one address!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>').insertBefore('.customer-addresses-block');

                        jQuery('html, body').animate({
                            scrollTop: jQuery("#checkout_form").offset().top
                        }, 500);

                        return false;
                    }
                }
            }
			_checkout_form_.submit();
		}
    });
    
    jQuery(document).on('change', '#checkout_new_address', function(){

        _customer_checkout_address = jQuery('.customer-checkout-address:checked');
        if(_customer_checkout_address.length != 0){
            jQuery('.customer-checkout-address:checked').prop('checked', false);
        }

        _WdCart.loadPaymentMethods(0, 0, 0);
        jQuery('#state_dropdown').val('');
        
        if(jQuery(this).is(':checked')){
            jQuery('.billing-address-block').show();
            jQuery('.customer-checkout-address').removeClass('required-entry');
            jQuery('.billing-address-block .required').removeClass('ignore');

            jQuery("html, body").animate({ scrollTop: jQuery('.billing-address-block').offset().top - 50 }, 1000);
        }else{
            jQuery('.billing-address-block').hide();
            jQuery('.customer-checkout-address').addClass('required-entry');
            jQuery('.billing-address-block .required').addClass('ignore');
        }
    });

    jQuery(document).on('change', '.customer-checkout-address', function(){
        _customer_checkout_address = jQuery('.customer-checkout-address:checked');
        if(_customer_checkout_address.length > 0){
            if(jQuery('#checkout_new_address').is(':checked')){
                jQuery('#checkout_new_address').prop('checked', false);
                jQuery(".billing-address-block").hide();
            }
        }
    });
});    
