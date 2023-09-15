!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){function c(a){return h.raw?a:encodeURIComponent(a)}function d(a){return h.raw?a:decodeURIComponent(a)}function e(a){return c(h.json?JSON.stringify(a):String(a))}function f(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(b," ")),h.json?JSON.parse(a):a}catch(a){}}function g(b,c){var d=h.raw?b:f(b);return a.isFunction(c)?c(d):d}var b=/\+/g,h=a.cookie=function(b,f,i){if(arguments.length>1&&!a.isFunction(f)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setMilliseconds(k.getMilliseconds()+864e5*j)}return document.cookie=[c(b),"=",e(f),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=b?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;n<o;n++){var p=m[n].split("="),q=d(p.shift()),r=p.join("=");if(b===q){l=g(r,f);break}b||void 0===(r=g(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b)}});

function wd_loader(_ele){
    jQuery(_ele).addClass('have-loader').append('<div class="cssload-container"><div class="cssload-whirlpool"></div></div>');
}

function remove_wd_loader(_ele){
    jQuery(_ele).removeClass('have-loader');
    _loader = jQuery(_ele).find('.cssload-container');

    if(_loader.length > 0){
        jQuery(_loader).remove();
    }
}

function wd_v_loader(_ele){
  jQuery(_ele).append('<div id="floatBarsG"><div id="floatBarsG_1" class="floatBarsG"></div><div id="floatBarsG_2" class="floatBarsG"></div><div id="floatBarsG_3" class="floatBarsG"></div><div id="floatBarsG_4" class="floatBarsG"></div><div id="floatBarsG_5" class="floatBarsG"></div><div id="floatBarsG_6" class="floatBarsG"></div><div id="floatBarsG_7" class="floatBarsG"></div><div id="floatBarsG_8" class="floatBarsG"></div></div>');
}

function remove_wd_v_loader(_ele){
  _loader = jQuery(_ele).find('#floatBarsG');

  if(_loader.length > 0){
    jQuery(_loader).remove();
  }
}

function validate_unique_value(_id, _val, _field, _table_name){
  if(jQuery("#loader_"+_id).length > 0){
    jQuery("#loader_"+_id).remove();
  }
  jQuery('<div class="ele_validator_loader" id="loader_'+_id+'"></div>').insertAfter(jQuery("#"+_id));
  wd_v_loader(jQuery("#loader_"+_id));
  jQuery.ajax({
    url: admin_url+"util/validate_unique",
    data: "field="+_field+'&value='+_val+'&table_name='+_table_name,
    dataType: 'json',
    type: 'post',
    success: function(a){
      remove_wd_v_loader(jQuery("#loader_"+_id));
      if(a.status == 'error'){
        jQuery("#loader_"+_id).html("<span class='text-danger'><span class='ico'>error</span> "+a.message+"</span>");
      }
      if(a.status == 'success'){
        jQuery("#loader_"+_id).html("<span class='text-success'><span class='ico'>done_all</span> "+a.message+"</span>");
      }
    }
  });
}

function inline_edit(_table_name, _table_index, _row_id, _ele_id){
  if(_table_name != ''){
    if(jQuery("#loader_"+_ele_id).length > 0){
      jQuery("#loader_"+_ele_id).remove();
    }

    jQuery('<div class="ele_validator_loader" id="loader_'+_ele_id+'"></div>').insertAfter(jQuery("#in_edit_field_"+_ele_id));
    wd_v_loader(jQuery("#loader_"+_ele_id));

    _val = jQuery("#in_edit_field_"+_ele_id).val();
    _field = jQuery("#in_edit_field_"+_ele_id).attr('rel');

    jQuery("#in_edit_field_"+_ele_id).attr('disabled', 'disabled');

    _validate_unique = 'no';
    if(jQuery("#in_edit_field_"+_ele_id).hasClass('validate-unique')){
      _validate_unique = 'yes';
    }

    jQuery.ajax({
      url: admin_url+"util/inline_edit",
      data: "field="+_field+'&value='+_val+'&table_name='+_table_name+"&validate_unique="+_validate_unique+'&row_id='+_row_id+'&table_index='+_table_index,
      dataType: 'json',
      type: 'post',
      success: function(a){
        jQuery('#wd_form_submit').removeAttr('disabled');
        remove_wd_v_loader(jQuery("#loader_"+_ele_id));
        if(a.status == 'error'){
          jQuery("#loader_"+_ele_id).html("<span class='text-danger'><span class='ico'>error</span> "+a.message+"</span>");
          jQuery("#in_edit_field_"+_ele_id).removeAttr('disabled');
        }
        if(a.status == 'success'){
          jQuery("#loader_"+_ele_id).html("<span class='text-success'><span class='ico'>done_all</span> "+a.message+"</span>");
          jQuery('#in_view_'+_ele_id).text(jQuery("#in_edit_field_"+_ele_id).val());
          window.setTimeout(function(){
            jQuery("#in_edit_cancel_"+_ele_id).trigger("click");
            jQuery("#in_edit_field_"+_ele_id).val('');
          }, 2000);
        }
      }
    });
  }
}

function edit_read_only(_s, _ele_id){
    _ele = jQuery('#'+_ele_id);
    if(jQuery(_s).hasClass('canceled')){
        _ele.attr('readonly', 'readonly').addClass('readonly editable');
        jQuery(_s).removeClass('canceled').text('Edit');
    }else{
        _ele.removeAttr('readonly').removeClass('readonly editable');
        jQuery(_s).addClass('canceled').text('Done');
    }
}

function edit_disabled(_s, _ele_id){
    _ele = jQuery('#'+_ele_id);
    if(jQuery(_s).hasClass('canceled')){
        _ele.attr('disabled', 'disabled').addClass('disabled editable');
        jQuery(_s).removeClass('canceled').text('Edit');
    }else{
        _ele.removeAttr('disabled').removeClass('disabled editable');
        jQuery(_s).addClass('canceled').text('Cancel');
    }
}

jQuery(function(){
  if(jQuery('.header-alert').length > 0){
    window.setTimeout(function(){
      jQuery('.header-alert').fadeOut();
    }, 10000);
    //window.setTimout(function(){}, 500);
  }
});

jQuery(function(){
    jQuery('body').on('click', '.image-item-remove', function(){
        remove_image_ele(this);
    });
});

function remove_image_ele(_ele){
    if(!confirm("Do you really want to remove this image!!")){
        return;
    }
    _data_id = jQuery(_ele).attr('data-id');

    // _data_photo_items = window['_'+_data_id+'_data'];
    // _data_processing_status = window['_processing_'+_data_id];
    // _data_processing_status = true;
    _image = jQuery('#preview_image_'+_data_id).attr('src').replace(base_url, '');

    wd_loader(jQuery('#image_preview_container_'+_data_id));
    jQuery('#preview_image_'+_data_id).attr('src', base_url+'assets/admin/images/no-image.png');
    jQuery('#photo').val('');
    jQuery('#photo_thumb').val('');
    remove_wd_loader(jQuery('#image_preview_container_'+_data_id));

    jQuery.ajax({
        url: base_url+'imageuploader/remove_image',
        data: "image="+_image,
        type: 'post',
        success: function(r){

            // delete _data_photo_items[r];
            // jQuery('#image_preview_container_'+_data_id+'_'+_data_count).fadeOut(500, function(){
            //     jQuery('#image_preview_container_'+_data_id+'_'+_data_count).remove();
            //     remove_wd_loader(jQuery('#image_preview_container_'+_data_id+'_'+_data_count+''));
            //     _data_processing_status = false;
            // });
            // jQuery('#'+_data_id).text(JSON.stringify(_data_photo_items));                        
        }
    });
    return;
}

jQuery(function(){
    jQuery('body').on('click', '.gallery-item-remove', function(){
        remove_gallery_ele(this);
    });
});

function remove_gallery_ele(_ele){
    if(!confirm("Do you really want to remove this image!!")){
        return;
    }
    _data_id = jQuery(_ele).attr('data-id');
    _data_count = jQuery(_ele).attr('data-count');

    _data_gallery_items = window['_'+_data_id+'_data'];
    _data_processing_status = window['_processing_'+_data_id];
    _data_processing_status = true;

    if(_data_count in _data_gallery_items){
        delete _data_gallery_items[_data_count];
        jQuery('#image_preview_container_'+_data_id+'_'+_data_count).remove();
        //remove_wd_loader(jQuery('#image_preview_container_'+_data_id+'_'+_data_count+''));

        jQuery.each(_data_gallery_items, function(i, items){
            if('thumbs' in _data_gallery_items[i]){
                if(typeof items.thumbs == 'string'){
                    _data_gallery_items[i]['thumbs'] = JSON.parse(items.thumbs);
                }else{
                    _data_gallery_items[i]['thumbs'] = JSON.parse(JSON.stringify(items.thumbs));
                }
            }    	
        });

        jQuery('#'+_data_id).text(JSON.stringify(_data_gallery_items));
    }
    return;

    wd_loader(jQuery('#image_preview_container_'+_data_id+'_'+_data_count+''));

    jQuery.each(_data_gallery_items, function(r,s){
        if(s != null){
            if(_data_count in s){
                jQuery.ajax({
                    url: base_url+'imageuploader/remove_image',
                    data: "image="+s[_data_count],
                    type: 'post',
                    success: function(r){
                        delete _data_gallery_items[r];
                        jQuery('#image_preview_container_'+_data_id+'_'+_data_count).fadeOut(500, function(){
                            jQuery('#image_preview_container_'+_data_id+'_'+_data_count).remove();
                            remove_wd_loader(jQuery('#image_preview_container_'+_data_id+'_'+_data_count+''));
                            _data_processing_status = false;
                        });
                        jQuery('#'+_data_id).text(JSON.stringify(_data_gallery_items));                        
                    }
                });
                return;
            }
        }
    });
}

jQuery(function(){
    jQuery(document).on('click', '.remove-file', function(e){
        e.preventDefault();
        remove_file(this);
    });
});
function remove_file(_e){
    _ele = jQuery(_e);
    alert(_ele.attr('class'));
}

jQuery(function(){
    _url = window.location.href;
    if(_url.indexOf('#add-new-mandate') > -1){
        wd_modal_popup('wd_pop_form');
    }
    jQuery('.header-add-new').on('click', function(){
        window.setTimeout(function(){
            _url = window.location.href;
            if(_url.indexOf('#add-new-mandate') > -1){
                wd_modal_popup('wd_pop_form');
            }
        }, 100);    
    });
})

jQuery(function(){
    jQuery(document).on('click', '.wd-pop-close', function(){
        hide_wd_modal_popup();
    });
});
function wd_modal_popup(_id){
    jQuery('body').addClass('active-popform');
    //jQuery('.wd-form-pop-container')
    jQuery('#'+_id).addClass('show');
    window.setTimeout(function(){
        jQuery('#'+_id).addClass('active');
    }, 100);
    jQuery('#'+_id).find('.wd-pop').addClass('active');
}
function hide_wd_modal_popup(){
    _action_pop_id = jQuery('.wd-pop-container.active').attr('id');
    jQuery('body').removeClass('active-popform');
    jQuery('#'+_action_pop_id).find('.wd-pop').removeClass('active');

    jQuery(document).trigger('modal_popup_hidden', {id: _action_pop_id, element:jQuery('.wd-pop-container.active')});

    window.setTimeout(function(){
        jQuery('#'+_action_pop_id).removeClass('active show');
    }, 300);	
}

jQuery(function(){
	jQuery(document).on('click', '.control-childs', function(){
      if(jQuery(this).hasClass('controlled')){
          jQuery(this).nextUntil(jQuery('.control-childs'), ".list-group-item").show();
          jQuery(this).removeClass('controlled');
      }else{
          jQuery(this).nextUntil(jQuery('.control-childs'), ".list-group-item").hide();
          jQuery(this).addClass('controlled');
      }	
	});
});

//Schedular
var scheduled_date = false;
var schedular_ele = false;
var schedular_note_ele = false;
jQuery(function(){
    if($.fn.datetimepicker){
        jQuery('#wd_schedular_calendar').datetimepicker({inline: true, sideBySide: true, calendarWeeks: false});
        jQuery("#wd_schedular_calendar").on("dp.change", function (e) {
            scheduled_date = jQuery("#wd_schedular_calendar").data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:ss');
            set_scheduled_date();
        });
        jQuery(document).on('click', '.sc-qs-list li', function(){
            _c_s_i = jQuery(this);
            scheduled_date = jQuery(_c_s_i).attr('data-date');
            jQuery('#wd_schedular_calendar').data("DateTimePicker").defaultDate(moment(scheduled_date));
            jQuery('.sc-qs-list li').removeClass('selected');
            jQuery(_c_s_i).addClass('selected');
            set_scheduled_date();
        });

        jQuery(document).on('click', '.sc-qs-action .btn-cancel', function(){
            hide_schedular();
        });
        jQuery(document).on('click', '.sc-qs-action .btn-apply', function(){
            apply_schedular();
        });
    }
});

function set_scheduled_date(){
    jQuery('.sc-selected-date').text(moment(scheduled_date).format('DD MMMM, YYYY hh:mm A'));
}

function apply_schedular(){
    if(schedular_ele && (schedular_ele != undefined || schedular_ele != 'undefined')){
        jQuery(schedular_ele).val(scheduled_date);
    }
    if(schedular_note_ele && (schedular_note_ele != undefined || schedular_note_ele != 'undefined')){
        jQuery(schedular_note_ele).val(jQuery('#schedular_note').val());
    } 
    jQuery(document).trigger('wd_schedular_applied', {element:schedular_ele, date:scheduled_date, note:jQuery('#schedular_note').val()});
    hide_schedular();
}

function schedular(_ele, _note){
    schedular_ele = _ele;
    if(_note && _note != false && (_note != undefined || _note != 'undefined')){
        schedular_note_ele = _note;
    }
    scheduled_date = jQuery("#wd_schedular_calendar").data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:ss');
    set_scheduled_date();
    jQuery('body').addClass('pause');
    jQuery('.page').addClass('blur');
    jQuery('.schedular').addClass('shown');
}

function hide_schedular(){
    jQuery('body').removeClass('pause');
    jQuery('.page').removeClass('blur');
    jQuery('.schedular').removeClass('shown');
    scheduled_date = false;
    schedular_ele = false;
    //jQuery('#wd_schedular_calendar').data("DateTimePicker").defaultDate(moment(jQuery('#sc_qs_today').attr('data-date')));
    jQuery("#sc_qs_today").trigger("click");
    jQuery('#schedular_note').val('');
    jQuery('.schedular .sc-title').text('Schedular');
}

jQuery(function(){
    jQuery('.wd-pop-toolbar-init').on('click', function(){
        _rel = jQuery(this).attr('rel');
        if(jQuery(this).hasClass('active')){
            jQuery('#'+_rel).removeClass('open');
            jQuery(this).removeClass('active');
        }else{
            jQuery('#'+_rel).addClass('open');
            jQuery(this).addClass('active');
        }
    });
});

jQuery(function(){
	jQuery(document).on('click', '.fc-f-b', function(){
		_rel = jQuery(this).attr('rel');
		if(jQuery(this).hasClass('off')){
			jQuery(this).removeClass('off');
			if(_rel == 'forwarded'){
				//jQuery('.list-group-item.cv-item').hide();
				jQuery('.list-group-item.cv-item.forwarded').show();
			}else if(_rel == 'not_forwarded'){
				jQuery('.list-group-item.cv-item').show();
			}else{
				jQuery('#forward_group_'+_rel+' ul').show();
			}	
		}else{
			jQuery(this).addClass('off');
			if(_rel == 'forwarded'){
				//jQuery('.list-group-item.cv-item').show();
				jQuery('.list-group-item.cv-item.forwarded').hide();
			}else if(_rel == 'not_forwarded'){
				jQuery('.list-group-item.cv-item').hide();
				jQuery('.list-group-item.cv-item.forwarded').show();
			}else{
				jQuery('#forward_group_'+_rel+' ul').hide();
			}	
		}
	});
	jQuery(document).on('keyup', '.find-fc-t', function(){
        _data_rel = jQuery(this).closest('.wd-pop-toolbar').attr('data-rel');
        _data_find_ele = jQuery(this).attr('data-find');
        if(typeof _data_find_ele === typeof undefined || _data_find_ele === false){
            _data_find_ele = false;
        }
        _ttf = jQuery(this).val().toLowerCase();
		if(_ttf.length > 2){
            jQuery("#"+_data_rel+" li.list-group-item").hide();
            if(_data_find_ele !== false){
                jQuery('.'+_data_find_ele+":contains('"+_ttf+"')").closest('.list-group-item').fadeIn(100);
            }else{
                jQuery("#"+_data_rel+" li.list-group-item:contains('"+_ttf+"')" ).fadeIn(100);
            }
		}else{
			jQuery("#"+_data_rel+" li.list-group-item").fadeIn();
		}
    });
    
    if(jQuery('.complete-order-btn').length > 0){
        jQuery('.complete-order-btn').on('click', function(){
            _order_id = jQuery(this).data('order_id');
            jQuery.ajax({
                method: 'post',
                dataType: 'json',
                data: 'order_id='+_order_id,
                url: admin_url+'orders/complete_order',
                success: function(r){
                    if(r.status == 'success'){
                        jQuery('.complete-order-btn').remove();
                        jQuery('#co-msg').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Order Status updated successfully!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                }
            });
        });
    }
});