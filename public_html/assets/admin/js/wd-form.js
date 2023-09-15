
WdForm = function(base_url) {
    this.init(base_url);
}

$.extend(WdForm.prototype, {
    base_url: null,

    init: function(base_url) {
        WdForm.prototype.base_url = base_url;
    },

    create: function (){
        wd_modal_popup('wd_pop_form');
    },

    hide: function (){
        hide_wd_modal_popup();
        window.setTimeout(function(){
            //jQuery('#frm')[0].reset();
            WdForm.prototype.reset();
        }, 500);
    },

    reset: function (){
        jQuery("#frm").find('input:hidden, input:text, input:password, input:file, select, textarea').val('');
        jQuery("#frm").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        _image_preview_box = jQuery("#frm").find('.image-preview-box');
        jQuery(_image_preview_box).find('img').attr('src', '');
        jQuery(_image_preview_box).find('.image-message').removeClass('success').removeClass('error').text('');
        jQuery(_image_preview_box).find('input:hidden').val('');
    },

    edit: function (edit_url, id){
        wd_modal_popup('wd_pop_form');
        wd_loader(jQuery('.wd-pop-container.active .wd-form-pop-body'));
        jQuery.ajax({
            url: this.base_url+edit_url+'/'+id,
            dataType: 'json',
            type: 'post',
            success: function(r){
                if(r.data_status == 'success'){
                    jQuery.each(r.data, function(field,value){

                        _ele = jQuery('[name='+field+']');
                        data_field_type = jQuery(_ele).attr('data-field');

                        _ele_by_id = jQuery('#'+field);
                        data_field_type_by_id = jQuery(_ele_by_id).attr('data-field');

                        if(typeof data_field_type === typeof undefined || data_field_type === false){
                            data_field_type = _ele.attr('type');
                        }

                        //Check if field in tag
                        if(data_field_type == 'hidden' && ((typeof data_field_type_by_id !== typeof undefined || data_field_type_by_id !== false) && data_field_type_by_id == 'tag')){
                            _tags_val = value.split(',');
                            jQuery.each(_tags_val, function(t_i, t_val){
                                jQuery("#"+field).tagsManager('pushTag', t_val);
                            });
                        }else{

                            if(data_field_type == 'text' || data_field_type == 'select' || data_field_type == 'hidden'){
                                jQuery('#'+field).val(value);
                            }else if(data_field_type == 'textarea'){
                                jQuery('#'+field).html(value);
                            }else if(data_field_type == 'radio'){
                                jQuery('[value='+value+']').prop('checked', true);
                            }else if(data_field_type == 'texteditor'){
                                tinyMCE.get(field).setContent(value);
                            }else{
                                //alert(data_field_type);
                            }
                        }	
                    });
                }
                window.setTimeout(function(){
                    remove_wd_loader(jQuery('.wd-pop-container.active .wd-form-pop-body'));
                }, 500);
                jQuery(document).trigger('wd_form_data_loaded', {data:r.data});
            }
        });
    },

    save: function (){
        wd_loader(jQuery('.wd-form-pop-body'));
        _fields = jQuery("#frm").serialize();
        jQuery.ajax({
            url: jQuery("#frm").attr('action'),
            data: _fields,
            type: 'post',
            dataType: 'json',
            success: function(a){
                if(a.status == 'success'){
                    WdForm.prototype.hide();
                    if(jQuery.isFunction(reloadDataGrid)){
                        window.setTimeout(function(){
                            reloadDataGrid();
                        }, 500);
                    } 
                    jQuery(document).trigger('wd_form_save_success', {status:'success'});
                }
                remove_wd_loader(jQuery('.wd-form-pop-body'));
            }
        });
        return false;
    }
});