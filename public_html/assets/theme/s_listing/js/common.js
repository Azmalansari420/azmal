!function(e){function r(r){return e(r).filter(function(){return e(this).is(":appeared")})}function t(){a=!1;for(var e=0,t=i.length;t>e;e++){var n=r(i[e]);if(n.trigger("appear",[n]),p[e]){var o=p[e].not(n);o.trigger("disappear",[o])}p[e]=n}}function n(e){i.push(e),p.push()}var i=[],o=!1,a=!1,f={interval:250,force_process:!1},u=e(window),p=[];e.expr[":"].appeared=function(r){var t=e(r);if(!t.is(":visible"))return!1;var n=u.scrollLeft(),i=u.scrollTop(),o=t.offset(),a=o.left,f=o.top;return f+t.height()>=i&&f-(t.data("appear-top-offset")||0)<=i+u.height()&&a+t.width()>=n&&a-(t.data("appear-left-offset")||0)<=n+u.width()?!0:!1},e.fn.extend({appear:function(r){var i=e.extend({},f,r||{}),u=this.selector||this;if(!o){var p=function(){a||(a=!0,setTimeout(t,i.interval))};e(window).scroll(p).resize(p),o=!0}return i.force_process&&setTimeout(t,i.interval),n(u),e(u)}}),e.extend({force_appear:function(){return o?(t(),!0):!1}})}(function(){return"undefined"!=typeof module?require("jquery"):jQuery}());
function validateEmail(email){var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;return re.test(email);}

function _wd_loader_xs(_e){
	jQuery(_e).addClass('h-lo').append('<div class="loader loader-xs"></div>');
}
function _wd_loader_md(_e){
	jQuery(_e).addClass('h-lo').append('<div class="loader loader-md"><div class="ldr-md"><div class="dt"></div><div class="dt"></div><div class="dt"></div></div></div>');
}
function _wd_loader_lg(_e){
    jQuery(_e).addClass('h-lo').append('<div class="loader loader-lg"></div>');
}
function _wd_remove_loader(_e){
	jQuery(_e).delay(500).removeClass('h-lo').find('.loader').remove();
}

function scrollToEle(ele, speed){
	jQuery('html,body').animate({scrollTop: ele.offset().top}, speed);
}

function helpful_stat(i, deal_id, act){
	jQuery.ajax({
		url: _home_url+'/misc/helpful/'+deal_id,
		data: 'act='+act,
		type: 'post',
		success: function(){
			jQuery(i).closest('.d-l-hl-act').html('<li class="tx">Thank you for your feedback.</li>');
		}
	});
}

jQuery(function(){

	jQuery("img.lzl").lazyload();

	_window_width = jQuery(document).width();

	jQuery(document).on('mouseover', '.nav-item.dropdown', function(){
		jQuery('.nav-item.dropdown').removeClass('active');
		jQuery(this).addClass('active');
	}).on('mouseout', '.nav-item.dropdown', function(){
		jQuery('.nav-item.dropdown').removeClass('active');
	});

	if(jQuery('#main_promo_banners').length > 0){
		jQuery('#main_promo_banners').carousel({'interval': 50000});
	}
	jQuery(document).on('click', '.as-ltr-lnk', function(){
		_ltr = jQuery(this).attr('data-link');
		jQuery('html,body').animate({scrollTop: jQuery('#letter_'+_ltr).offset().top}, 500);
	});

	jQuery(document).on('click', '.d-l-tnc-rm', function(){
		_ele = jQuery(this);
		_tnct = jQuery(_ele).parent().find('p');
		_dlod = jQuery(_ele).parent().find('ul');
		if(jQuery(_ele).hasClass('active')){
			jQuery(_tnct).removeClass('exp');
			jQuery(_dlod).removeClass('active');
			jQuery(_ele).removeClass('active').text('Read More');
		}else{
			jQuery(_tnct).addClass('exp');
			jQuery(_dlod).addClass('active');
			jQuery(_ele).addClass('active').text('Read Less');
		}
	});

	jQuery(document).on('click', '.filter-block > strong', function(){
		_p_f_b = jQuery(this).closest('.filter-block').toggleClass('inactive');		
	});

	jQuery(document).on('click', '.d-l-cpn', function(){
		_data_url = jQuery(this).attr('data-url');
		window.location = _data_url;

		FB.AppEvents.logEvent("couponViewed");
	});

	jQuery(document).on('click', '.hlpfl-no', function(){
		_deal_id = jQuery(this).attr('rel');
		helpful_stat(jQuery(this), _deal_id, 'bad');
	});
	jQuery(document).on('click', '.hlpfl-ys', function(){
		_deal_id = jQuery(this).attr('rel');
		helpful_stat(jQuery(this), _deal_id, 'good');
	});

	if(jQuery('#deal_popup').length > 0){
		jQuery('#deal_popup').modal();
	}

	jQuery('#f_nl_subscription').on('submit', function(e){
		_n_e_v = jQuery('#f_newsletter_email').val();
		jQuery('#newsletter_helpblock').remove();
		if(_n_e_v != ''){
			if(!validateEmail(_n_e_v)){
				jQuery('#newsletter_block').addClass('error');
				jQuery('#newsletter_block').append('<div class="help-block" id="newsletter_helpblock">Please enter a valid email address!</div>');
				jQuery('#f_newsletter_email').focus();
				return false;
			}
		}else{
			jQuery('#newsletter_block').addClass('error');
			jQuery('#newsletter_block').append('<div class="help-block" id="newsletter_helpblock">Please enter your email id above!</div>');
			jQuery('#f_newsletter_email').focus();
			return false;
		}
	});

	
});

_home_url
_wd_lnp = false;
_wd_cp = 1;
function _load_next_page(_pager_url, _type, _args){
	_wd_cp = _wd_cp + 1;
	_wd_loader_md(jQuery('#deal_page_loader'));
	jQuery.ajax({
		url: _home_url+'/pager/next/'+_pager_url,
		data: 'pager_type='+_type+'&page='+_wd_cp+'&'+_args,
		type: 'post',
		dataType: 'json',
		success: function(e){
			_wd_remove_loader(jQuery('#deal_page_loader'));
			jQuery(e.html).insertBefore("#deal_page_loader");
			
			_scroll_fix_ele = jQuery('.sidebar-block');
			_footer_ele = jQuery('footer');
			
			block_layered_nav_height = parseInt(jQuery(_scroll_fix_ele).height());
			footer_container_height = parseInt(jQuery(_footer_ele).height());
			doc_height = parseInt(jQuery(document).height());
			_remain_height = (doc_height - footer_container_height - block_layered_nav_height) - 50;
						
			window.setTimeout(function(){
				if(e.count == 10){
					_wd_lnp = false;
				}	
			}, 1000);
		}
	});
}

jQuery(function(){
	if(jQuery('#deal_page_loader').length && jQuery('.deal-list-item').length == 10){
		jQuery('#deal_page_loader').appear();
		jQuery(document).on('appear', '#deal_page_loader', function(){
			if(!_wd_lnp){
				_wd_lnp = true;
				_load_next_page(_pager_url, _pager_type, _pager_filter_args);
			}
		});
	}
});

jQuery(document).ready(function() {
	if(jQuery('.sidebar-block').length > 0){
		_scroll_fix_ele = jQuery('.sidebar-block');
		_footer_ele = jQuery('footer');
		block_layered_nav_height = parseInt(jQuery(_scroll_fix_ele).height());
		footer_container_height = parseInt(jQuery(_footer_ele).height());
		doc_height = parseInt(jQuery(document).height());
		_remain_height = (doc_height - footer_container_height - block_layered_nav_height) - 50;
		var __is_fixed = false;
		
		var _current_top = parseInt(jQuery(_scroll_fix_ele).offset().top);
		var _layer_container_height = (doc_height - footer_container_height - _current_top) - 50;
		var _layer_margin_top = _layer_container_height - block_layered_nav_height;
	
		var scroll, wresize, mobile;
		var headerPos = parseInt(jQuery(_scroll_fix_ele).offset().top ) - 20;
		var once = true;
		var init = false;
		var show, go;
	
		(scroll = function() {
			
			if(mobile != true) {// && $('#menu').css('position') != 'fixed'
				var scrollPos = jQuery(document).scrollTop();
				
				if(scrollPos > _remain_height) {
					
					__is_fixed = true;
					jQuery(_scroll_fix_ele).addClass('scroll');
					jQuery(_scroll_fix_ele).css({"margin-top":_layer_margin_top+"px"});
					
				} else if(__is_fixed === true) {
					jQuery(_scroll_fix_ele).removeClass('scroll');
					jQuery(_scroll_fix_ele).removeAttr('style');
					__is_fixed = false;
				}
				
				if(scrollPos > headerPos) {
					clearTimeout(show);
					init = true;
					_s_ele_width = parseInt(jQuery(_scroll_fix_ele).width());
					jQuery(_scroll_fix_ele).addClass('layered-fixed').css({'width':(_s_ele_width)+'px'});
					
				} else if(init === true) {
					
					clearTimeout(go);
					
					jQuery(_scroll_fix_ele).removeClass('layered-fixed').removeAttr('style');
					once = true;
					init = false;
				}
			}
		})();
		window.addEventListener('touchstart', function() {
			mobile = true;
		});
	
		jQuery(document).scroll(scroll);
	}	
});

jQuery(document).ready(function(){
	jQuery(window).scroll(function() {    
		var w_height = jQuery(window).scrollTop();   
		if(_window_width <= 768){
			if(w_height > 1000){
				jQuery('.to-top').addClass('show');
			}else{
				jQuery('.to-top').removeClass('show');
			}
		}else{
			if(w_height > 200){
				jQuery('.to-top').addClass('show');
			}else{
				jQuery('.to-top').removeClass('show');
			}
		}
	});
	jQuery('.to-top').on('click', function(){
		scrollToEle(jQuery('header'), '1000');
	});
});

jQuery(document).ready(function() {
	if(jQuery('.feed-filters').length > 0){
		var scroll,wresize,mobile;
		var feed_w = jQuery('.feed-filters').width();
		var headerPos = jQuery('.feed-filters').offset().top;
		var _header_h = parseInt(jQuery('header').outerHeight());
		var _f_list_top = jQuery('.news-feed-list').offset().top;
		headerPos = headerPos - _header_h;
		var once=true;
		var init=false;
		var show,go;
		(scroll=function(){
			if(mobile!=true){
				var scrollPos = jQuery(document).scrollTop();
				if(scrollPos > headerPos){
					clearTimeout(show);
					init=true;
					jQuery('.feed-filters').addClass('feed-filters-f').css({'width':feed_w+'px', 'top':_header_h+'px'});
					jQuery('.news-feed-list').css({'margin-top':(_f_list_top/2)+'px'});
				}else if(init===true){
					clearTimeout(go);
					jQuery('.feed-filters').removeClass('feed-filters-f').removeAttr('style');
					jQuery('.news-feed-list').removeAttr('style');
					once = true;
					init = false;
				}
			}
		})();
		window.addEventListener('touchstart',function(){
			mobile=true;
		});
		jQuery(document).scroll(scroll);
	}	
});