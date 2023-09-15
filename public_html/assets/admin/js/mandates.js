
Mandates = function(base_url, hr_id, mandates_data, mandate_stats) {
    this.init(base_url, hr_id, mandates_data, mandate_stats);
}

$.extend(Mandates.prototype, {
    base_url: null,
    hr_id: null,
    mandate_id: null,
    mandates_data: null,
    mandate_stats: null,

    init: function(base_url, hr_id, mandates_data, mandate_stats) {
        Mandates.prototype.base_url = base_url;
        Mandates.prototype.hr_id = hr_id;
        Mandates.prototype.mandate_id = null;
        Mandates.prototype.mandates_data = mandates_data;
        Mandates.prototype.mandate_stats = mandate_stats;
    },

    updateItemStat: function(_item, _val){
        if(jQuery('#'+_item).length > 0){
            jQuery('#'+_item).text(_val);
        }
    },

    updateStats: function(){
        _mandate_stats = this.mandate_stats;
        jQuery.each(_mandate_stats, function(_num, _row){
            Mandates.prototype.updateItemStat('stat_sources_'+_num, _row.sources_total);
            Mandates.prototype.updateItemStat('stat_shortlisted_'+_num, _row.shortlisted_total);
            Mandates.prototype.updateItemStat('stat_forwarded_'+_num, _row.forwarded_total);
            Mandates.prototype.updateItemStat('stat_scheduled_'+_num, _row.scheduled_cvs_count);
            Mandates.prototype.updateItemStat('stat_interviewed_'+_num, _row.interviewed_cvs_count);

            Mandates.prototype.updateItemStat('stat_requested_'+_num, _row.forwarded_count);
            Mandates.prototype.updateItemStat('stat_accepted_'+_num, _row.hm_accepted_cvs_count);
            Mandates.prototype.updateItemStat('stat_interviewed_'+_num, _row.interviewed_cvs_count);
        });
    },

    reloadMandatesStats: function(){
        jQuery.ajax({
            url: this.base_url+'mandates/reload_mandates_stats',
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function(data) {
                Mandates.prototype.mandate_stats = data.mandates_stats;
                Mandates.prototype.updateStats();
            }
        });
    },

    //Load Sources Window
    loadSourcesWindow: function(mandate_id){
        Mandates.prototype.mandate_id = mandate_id;
        wd_modal_popup('mandate_action');

        jQuery('#sources_tab_consultants').tab('show');

        window.setTimeout(function(){
            Mandates.prototype.loadConsultant();
            Mandates.prototype.respondConsultant();
            Mandates.prototype.loadCV();
            Mandates.prototype.loadShortlistedCV();
            Mandates.prototype.loadReferralsList();
            Mandates.prototype.referralsResponses();
        }, 100);    
    },

    loadConsultant: function(){
        wd_loader(jQuery('#consultants_list'));
        jQuery.ajax({
            url: this.base_url+'consultant/list_items',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                jQuery('#consultants_list').html(data.items);
                remove_wd_loader(jQuery('#consultants_list'));
            }
        });
    },

    respondConsultant: function(){
        jQuery.ajax({
            url: this.base_url+'shortlisted/get_consultants_responses',
            dataType: 'json',
            type: 'post',
            data: 'job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                jQuery('#shortlisted_consultants_list').html(data.items);
            }
        });
    },

    loadCV: function(){
        wd_loader(jQuery('#cvs_list'));
        jQuery.ajax({
            url: this.base_url+'shortlisted/cv_list',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                jQuery('#cvs_list').html(data.items);
                remove_wd_loader(jQuery('#cvs_list'));

                if(data.count > 0){
                    init_drag();
                }
            }
        });
    },

    loadShortlistedCV: function(){
        wd_loader(jQuery('#cvs_shortlisted'));
        jQuery.ajax({
            url: this.base_url+'shortlisted/cv_shortlisted_list',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                jQuery('#cvs_shortlisted').html(data.items);
                remove_wd_loader(jQuery('#cvs_shortlisted'));
                
                if(data.count > 0){
                    init_drag();
                }
            }
        });
    },

    loadReferralsList: function(){
        _position = this.mandates_data[this.mandate_id]['position'];
        matter = 'We have an opening for position '+_position+'. Your recommendations are open for references. Kindly use below link for sharing your references with us.\r\n';
        jQuery('#referrals_matter').html(matter);

        Mandates.prototype.reloadReferralsList();
    },

    reloadReferralsList: function(){
        wd_loader(jQuery('#referrals_list'));

        jQuery.ajax({
            url: this.base_url+'shortlisted/referrals_list',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                jQuery('#referrals_list').html(data.items);
                remove_wd_loader(jQuery('#referrals_list'));
            }
        });
    },

    sendReferralsRequest: function(){
        wd_loader(jQuery('#referral'));

        _referrals_matter_val = jQuery('#referrals_matter').val();
        _referrals_sel_items = [];
        jQuery.each(jQuery('.referral_items_check:checked'), function(i,c){
            _referrals_sel_items.push(jQuery(c).val());
        });

        jQuery.ajax({
            url: this.base_url+'referrals/send',
            dataType: 'json',
            type: 'post',
            data: 'referrals_matter='+_referrals_matter_val+'&items='+_referrals_sel_items+'&hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                remove_wd_loader(jQuery('#referral'));
                jQuery('.referral_items_check').prop('checked', false);
                jQuery('#referrals_list_checkall').prop('checked', false);
                if(data.status == 'success'){
                    alert('Referrals sent successfully!!');
                }
            }
        });
    },

    referralsResponses: function(){
        wd_loader(jQuery('#referrals_responses_list'));

        jQuery.ajax({
            url: this.base_url+'referrals/ref_responses',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
                jQuery('#referrals_responses_list').html(data.result);
                remove_wd_loader(jQuery('#referrals_responses_list'));
            }
        });
    },

    //Shortlist referrals responsed cv
    referralResponseAction: function(i, rid, ac){
        wd_loader(jQuery(i).parent());
        jQuery.ajax({
            url: this.base_url+'referrals/request_act',
            type: 'post',
            data: 'rid='+rid+'&status='+ac,
            success:function(r){
                remove_wd_loader(jQuery(i).parent());
                Mandates.prototype.referralsResponses();
            }
        });
    },

    //Send job request to consultant from Request Sources window[Consultants]
    sendJobRequestToConsultant: function(i, cid){
        wd_loader(jQuery(i).parent());
        jQuery.ajax({
            url: this.base_url+'shortlisted/consultant_send_job_request',
            type: 'post',
            data: 'cid='+cid+'&jid='+this.mandate_id,
            success:function(r){
              remove_wd_loader(jQuery(i).parent());
              Mandates.prototype.loadConsultant();
              if(jQuery('#mandate_'+this.mandate_id).hasClass('new')){
                jQuery('#mandate_'+this.mandate_id).removeClass('new').addClass('open');
              }	
            }
        });
    },

    //Shortlist consultants responsed cv
    shortlistConsultantsCV: function(i, rid, ac){
        wd_loader(jQuery(i).parent());
        jQuery.ajax({
            url: this.base_url+'shortlisted/request_act',
            type: 'post',
            data: 'rid='+rid+'&status='+ac,
            success:function(r){
                remove_wd_loader(jQuery(i).parent());
                Mandates.prototype.respondConsultant();
            }
        });
    },

    //Open Forward Window
    loadShortlistedWindow: function(mandate_id) {
        this.mandate_id = mandate_id;
        wd_modal_popup('wd_shortlisted_popup');
        
        wd_loader(jQuery('#wd_shortlisted_popup .wd-pop-body'));

        jQuery.ajax({
            url: this.base_url+'shortlisted/all_shortlisted_cvs',
            dataType: 'json',
            type: 'post',
            data: 'action=shortlist&hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data) {
              jQuery('#wd_shortlisted_shortlisted_cvs').html(data.result);
              remove_wd_loader(jQuery('#wd_shortlisted_popup .wd-pop-body'));
            }
        });
    },

    //Open Forward Window
    loadForwardWindow: function(mandate_id) {
        this.mandate_id = mandate_id;
        wd_modal_popup('wd_forward_popup');
        wd_loader(jQuery('#wd_forward_popup .wd-pop-body'));

        jQuery.ajax({
          	url: this.base_url+'shortlisted/all_shortlisted_cvs',
          	dataType: 'json',
          	type: 'post',
          	data: 'action=forward&hr_id='+this.hr_id+'&job_id='+this.mandate_id,
          	cache: false,
          	success: function(data) {
          		jQuery('#wd_forward_shortlisted_cvs').html(data.result);
          		remove_wd_loader(jQuery('#wd_forward_popup .wd-pop-body'));
          	}
        });
    },

    //Forward shortlisted cv to HR Manager
    forwardMandateToManager: function(source, cv_id, note){
        wd_loader(jQuery('#wd_forward_popup .wd-pop-body'));

        jQuery.ajax({
            url: this.base_url+'shortlisted/add_forwarded_cv',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id+'&note='+note+'&source='+source+'&cv_id='+cv_id,
            cache: false,
            success: function(data){
                jQuery('#'+source+'_'+cv_id).addClass('forwarded success');
                jQuery('#'+source+'_'+cv_id).find('.forward-note').remove();
                window.setTimeout(function(){
                  remove_wd_loader(jQuery('#wd_forward_popup .wd-pop-body'));
                }, 1000);	
            }
        });
    },

    //Open Scheduled Window
    loadScheduledMandateWindowHR: function(mandate_id) {
        this.mandate_id = mandate_id;
        wd_modal_popup('wd_scheduled_popup');
        wd_loader(jQuery('#wd_scheduled_popup .wd-pop-body'));

        jQuery.ajax({
          	url: this.base_url+'shortlisted/all_scheduled_cvs',
          	dataType: 'json',
          	type: 'post',
          	data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
          	cache: false,
          	success: function(data) {
          		jQuery('#wd_scheduled_popup_cvs').html(data.result);
          		remove_wd_loader(jQuery('#wd_scheduled_popup .wd-pop-body'));
          	}
        });
    },

    //HM cv requests window
    cvForwardedToHM: function(mandate_id){
        wd_modal_popup('wd_hm_cv_requests');
        wd_loader(jQuery('#wd_hm_cv_requests .wd-pop-body'));

        jQuery.ajax({
            url: this.base_url+'shortlisted/forwarded_to_hm',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+mandate_id,
            cache: false,
            success: function(data){
                jQuery('#wd_hm_cv_requests_box').html(data.result);
                remove_wd_loader(jQuery('#wd_hm_cv_requests .wd-pop-body'));
            }
        });
    },

    //HM reply to hr
    hmReplyToHR: function(req_id, action){
        wd_loader(jQuery('#wd_hm_cv_requests .wd-pop-body'));
        _reply_text = jQuery('#hm_reply_hr_'+req_id).val();

        jQuery.ajax({
            url: this.base_url+'shortlisted/hm_reply_to_hr',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&req_id='+req_id+"&action="+action+'&reply='+_reply_text,
            cache: false,
            success: function(data){
                //jQuery('#wd_hm_cv_requests_box').html(data.result);
                remove_wd_loader(jQuery('#wd_hm_cv_requests .wd-pop-body'));
            }
        });
    },

    //HM reply to hr
    hmCvAcceptedWindow: function(mandate_id){
        wd_modal_popup('wd_hm_cv_accepted');
        wd_loader(jQuery('#wd_hm_cv_accepted .wd-pop-body'));
        
        jQuery.ajax({
            url: this.base_url+'shortlisted/hm_accepted_window',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&mandate_id='+mandate_id,
            cache: false,
            success: function(data){
                jQuery('#wd_hm_cv_accepted_box').html(data.result);
                remove_wd_loader(jQuery('#wd_hm_cv_accepted .wd-pop-body'));
            }
        });
    },

    //HM schedule cv
    hmScheduleCVCall: function(req_id, date, note){
        wd_loader(jQuery('#wd_hm_cv_accepted .wd-pop-body'));
        
        jQuery.ajax({
            url: this.base_url+'shortlisted/hm_schedule_cv_call',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&req_id='+req_id+'&date='+date+'&note='+note,
            cache: false,
            success: function(data){
                remove_wd_loader(jQuery('#wd_hm_cv_accepted .wd-pop-body'));
                Mandates.prototype.reloadSchedularChat(req_id);
            }
        });
    },

    //Schedular chat
    schedulerChat: function(req_id){
        _s_r_t = jQuery('#reply_schedular_text_'+req_id).val();
        _s_r_t_h = jQuery('#reply_schedular_text_hidden_'+req_id).val();
        _s_r_d = jQuery('#reply_schedular_date_'+req_id).val();
        
		if(_s_r_t != '' || _s_r_d != '' || _s_r_t_h != ''){
            wd_loader(jQuery('#cv_scheduled_'+req_id));
            jQuery.ajax({
                url: this.base_url+'shortlisted/add_scheduler_chat',
                dataType: 'json',
                type: 'post',
                data: 'mandate_id='+this.mandate_id+'&req_id='+req_id+'&date='+_s_r_d+'&note='+_s_r_t+'&note_hidden='+_s_r_t_h,
                cache: false,
                success: function(data){
                    remove_wd_loader(jQuery('#cv_scheduled_'+req_id));
                    Mandates.prototype.reloadSchedularChat(req_id);
                    jQuery('#reply_schedular_text_'+req_id).val('');
                    jQuery('#reply_schedular_text_hidden_'+req_id).val('');
                    jQuery('#reply_schedular_date_'+req_id).val('');
                }
            });
        }    
    },

    //Reload Schedular
    reloadSchedularChat: function(req_id){

        _type = jQuery('#schedular_chat_items_'+req_id).find('.notes').attr('rel');
        Mandates.prototype.reUpdateChat(req_id, _type, false);

        // wd_loader(jQuery('#cv_scheduled_'+req_id));
        // jQuery.ajax({
        //     url: this.base_url+'shortlisted/reload_schedular_chat',
        //     dataType: 'json',
        //     type: 'post',
        //     data: 'mandate_id='+this.mandate_id+'&req_id='+req_id,
        //     cache: false,
        //     success: function(data){
        //         remove_wd_loader(jQuery('#cv_scheduled_'+req_id));
        //         jQuery('#schedular_chat_items_'+req_id).html(data.result).find('.notes').addClass('active');
        //         _last_child = jQuery('#schedular_chat_items_'+req_id).find('.chat-list-item').last();
        //         _container = jQuery('#schedular_chat_items_'+req_id).find('.chat-list-header');
        //         jQuery(_container).animate({
        //             scrollTop: jQuery(_last_child).offset().top + 500
        //         });
        //     }
        // });  
    },

    //Reload Schedular
    reUpdateChat: function(req_id, type, force_update){
        
        _chat_list = jQuery('#schedular_chat_items_'+req_id).find('.chat-list-header .chat-list');
        _current_count = parseInt(jQuery(_chat_list).find('li').length);
        
        jQuery.ajax({
            url: this.base_url+'shortlisted/re_update_chat',
            dataType: 'json',
            type: 'post',
            data: 'mandate_id='+this.mandate_id+'&req_id='+req_id+'&type='+type,
            cache: false,
            success: function(data){
                _data_count = data.counts;
                if(force_update != undefined && force_update == true){
                    _data_count = _current_count + 1;
                }
                
                if(_data_count > _current_count){

                    jQuery(_chat_list).css({'opacity':'.5'});
                    window.setTimeout(function(){

                        jQuery(_chat_list).css({'opacity':'1'});
                        jQuery(_chat_list).html(data.html);

                        _last_child = jQuery('#schedular_chat_items_'+req_id).find('.chat-list-item').last();
                        _container = jQuery('#schedular_chat_items_'+req_id).find('.chat-list-header');
                        jQuery(_container).animate({
                            scrollTop: jQuery(_last_child).offset().top + 1000
                        });
                    }, 100);    
                }
            }
        });  
    },

    acceptSchedule: function(req_id, scheduled_cv_id, type){
        wd_loader('#schedular_chat_items_'+scheduled_cv_id);
        
        jQuery.ajax({
            url: this.base_url+'shortlisted/accept_schedule',
            dataType: 'json',
            type: 'post',
            data: 'scheduled_cv_id='+scheduled_cv_id+'&req_id='+req_id,
            cache: false,
            success: function(data){
                remove_wd_loader('#schedular_chat_items_'+scheduled_cv_id);
                jQuery('#final_scheduled_date_'+scheduled_cv_id).html('<strong class="text-success">'+data.schedule+'</strong>');
                Mandates.prototype.reUpdateChat(scheduled_cv_id, type, true);
            }
        });  
    },

    ////////// Interviewed Items

    loadInterviewdMandates: function(mandate_id){
        this.mandate_id = mandate_id;
        wd_modal_popup('wd_hr_interviewed_window');
        wd_loader(jQuery('#wd_hr_interviewed_window .wd-pop-body'));

        jQuery.ajax({
            url: this.base_url+'mandate_acts/interview/interviewd_items_list',
            dataType: 'json',
            type: 'post',
            data: 'hr_id='+this.hr_id+'&job_id='+this.mandate_id,
            cache: false,
            success: function(data){
                jQuery('#wd_hr_interviewed_body').html(data.html);
                remove_wd_loader(jQuery('#wd_hr_interviewed_window .wd-pop-body'));
            }
        });
    },

    interviewAction: function(i, act, _date, _note){
        wd_loader(jQuery('#wd_hr_interviewed_body .cvs-to-forward-group'));
        _rel_id = jQuery(i).attr('data-id');
        
        jQuery.ajax({
            url: this.base_url+'mandate_acts/interview/change_status',
            dataType: 'json',
            type: 'post',
            data: 'int_id='+_rel_id+'&job_id='+this.mandate_id+'&act='+act+'&interview_date='+_date+'&notes='+_note,
            cache: false,
            success: function(data){
                if(act == 'interviewed'){
                    _act_html = '<div class="btn-group">';
                        _act_html += '<button class="btn btn-xs btn-success ac-int-fin" data-id="'+_rel_id+'">Finalize</button>';
                        _act_html += '<button class="btn btn-xs btn-danger ac-int-rej" data-id="'+_rel_id+'">Reject</button>';
                    _act_html += '</div>';
                    jQuery('#interview_row_action_'+_rel_id).html(_act_html);

                    jQuery('#interview_note_danger_'+_rel_id).removeClass('text-danger').addClass('text-success').html('<span style="font-size:16px;" class="ico text-success">done_all</span> Interview Done - '+data.date);
                }    
                remove_wd_loader(jQuery('#wd_hr_interviewed_body .cvs-to-forward-group'));
            }
        });
    },
});

jQuery(function(){
    window.setInterval(function(){
        if(jQuery('.notes.active').length > 0){
            _n_active = jQuery('.notes.active');
            _rel_id = jQuery(_n_active).parent().attr('id').split('schedular_chat_items_')[1];
            _type = jQuery(_n_active).attr('rel');
            mandate.reUpdateChat(_rel_id, _type, false);
            //alert(jQuery('.notes.active').length);
        }
    }, 10000);
});