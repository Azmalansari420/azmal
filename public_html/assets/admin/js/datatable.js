// Datatable
var datatable_current_page = 1;

$(function(){

	//set column width
	if(jQuery('#datagrid_headings').length){
		_dt_width = parseInt(jQuery('#datagrid_headings').outerWidth()) - 2;
		_dt_cells = parseInt(jQuery('#datagrid_headings li').length);

		//Find last li
		_dt_row_action_width = parseInt(jQuery('#datagrid_headings li:last-child').outerWidth());

		if(_dt_row_action_width > 0){
			if(jQuery('#datagrid_headings li.action-heading').length > 0){
				_dt_row_action_width = _dt_row_action_width + 30;
				jQuery('#datagrid_headings li.action-heading').attr('data-width', _dt_row_action_width);
			}
		}

		_def_width_total = 0;
		_def_cells = 0;
		jQuery.each(jQuery('#datagrid_headings li'), function(_i,_j){
			if(typeof jQuery(_j).attr('data-width') !== typeof undefined && jQuery(_j).attr('data-width') !== false){
				_def_width_total = _def_width_total + parseInt(jQuery(_j).attr('data-width'));
				_def_cells = _def_cells + 1;
			}
		});

		_dt_cell_width = (_dt_width-_def_width_total) / (_dt_cells - _def_cells);
		_dt_cells_widths = [];
		jQuery.each(jQuery('#datagrid_headings li'), function(_i,_j){
			if(typeof jQuery(_j).attr('data-width') !== typeof undefined && jQuery(_j).attr('data-width') !== false){
				_dt_cells_widths[_i] = parseInt(jQuery(_j).attr('data-width'));
				jQuery(_j).css({'width':(jQuery(_j).attr('data-width'))+'px'});
				jQuery('#datagrid_search li').eq(_i).css({'width':(jQuery(_j).attr('data-width'))+'px'});
			}else{
				_dt_cells_widths[_i] = parseInt(_dt_cell_width);
				jQuery(_j).css({'width':_dt_cell_width+'px'});
				jQuery('#datagrid_search li').eq(_i).css({'width':_dt_cell_width+'px'});
			}
		});
	}

	//load the default page
	window.setTimeout( function(){ paging('', '', ''); }, 500);

	jQuery(".datatable_previous_page_button").on("click", function(e){		if(jQuery(this).attr("rel")!=''){
			//paging(parseInt(jQuery(this).attr("rel")), "");
			datatable_current_page = parseInt(jQuery(this).attr("rel"));
			reloadDataGrid();
		}
		e.preventDefault();
		return false;
	});

	jQuery(".datatable_next_page_button").on("click", function(e){
		if(jQuery(this).attr("rel")!=''){
			//paging(parseInt(jQuery(this).attr("rel")), "");
			datatable_current_page = parseInt(jQuery(this).attr("rel"));
			reloadDataGrid();
		}

		e.preventDefault();		return false;
	});

	jQuery(".datatable_per_page_select").on("change", function(e){
		_per_page = jQuery(this).val();
		filter_str = prepare_filter_args();
		paging(1, filter_str, _per_page);
		jQuery('.datatable_per_page_select').val(_per_page);
	});
});

function paging(page, search_terms, _per_page){
	if(search_terms == '' || search_terms == "undefined"){
		search_terms = '';
	}
	wait = '<div class="datatable_loader">';
		wait += '<div class="progress progress-striped active progress-sm">';
        	wait += '<div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="45" role="progressbar" class="progress-bar progress-bar-success">';
        		wait += '<span class="loading-text">Loading...</span>';
        	wait += '</div>';
    	wait += '</div>';
	wait += '</div>';

	jQuery("#datatable_table_container").append(wait);

	_datatable_w_calculated = false;
	//_per_page = jQuery('.datatable_per_page_select').val();
	if(_per_page == undefined || _per_page == 'undefined'){
		_per_page = '';
	}

	if(page == undefined || page == 'undefined' || page == ''){
		page = '';
	}

	jQuery.ajax({
		url: datatable_base_url + datatable_controller_name,
		data: 'page='+page+search_terms+'&per_page='+_per_page,
		type: 'get',
		success: function(a){
			jQuery('.datatable_loader').remove();
			var r = jQuery.parseJSON(a);
			//if(r.search_result!=''){
			//	jQuery("#datatable_table_container").html(r.table);
			//}else{
				jQuery('.toolbar-footer-container').fadeIn();
				//update table
				jQuery("#datatable_table_container").css({'height':(jQuery("#datatable_table_container").innerHeight())+'px'}).fadeOut(10).html(r.table).fadeIn(300).css({'height':'auto'});
				if(!_datatable_w_calculated){
					_datatable_w_calculated = true;
					_dt_width = parseInt(jQuery('#datagrid_headings').outerWidth()) - 2;
					_dt_cells = parseInt(jQuery('#datagrid_headings li').length);

					//Find last li
					_dt_row_action_width = 0;
					if(jQuery('.datagrid_tr').length){
						_dt_row_action_width = parseInt(jQuery('.datagrid_tr').eq(0).find('li:last-child').outerWidth());
					}

					if(_dt_row_action_width > 0){
						if(jQuery('#datagrid_headings li.action-heading').length > 0){
							_dt_row_action_width = _dt_row_action_width + 30;
							jQuery('#datagrid_headings li.action-heading').attr('data-width', _dt_row_action_width);
						}
					}

					_def_width_total = 0;
					_def_cells = 0;
					jQuery.each(jQuery('#datagrid_headings li'), function(_i,_j){
						if(typeof jQuery(_j).attr('data-width') !== typeof undefined && jQuery(_j).attr('data-width') !== false){
							_def_width_total = _def_width_total + parseInt(jQuery(_j).attr('data-width'));
							_def_cells = _def_cells + 1;
						}
					});


					_dt_cell_width = (_dt_width-_def_width_total) / (_dt_cells - _def_cells);
					_dt_cells_widths = [];
					jQuery.each(jQuery('#datagrid_headings li'), function(_i,_j){
						if(typeof jQuery(_j).attr('data-width') !== typeof undefined && jQuery(_j).attr('data-width') !== false){
							_dt_cells_widths[_i] = parseInt(jQuery(_j).attr('data-width'));
							jQuery(_j).css({'width':(jQuery(_j).attr('data-width'))+'px'});
							jQuery('#datagrid_search li').eq(_i).css({'width':(jQuery(_j).attr('data-width'))+'px'});
						}else{
							_dt_cells_widths[_i] = parseInt(_dt_cell_width);
							jQuery(_j).css({'width':_dt_cell_width+'px'});
							jQuery('#datagrid_search li').eq(_i).css({'width':_dt_cell_width+'px'});
						}
					});

					jQuery.each(jQuery('.datagrid_tr'), function(_n,_r){
						_lis = jQuery(_r).find('li');
						for(_e=0; _e < (_dt_cells_widths.length); _e++){
							//alert(_dt_cells_widths[_e]);
							jQuery(_lis).eq(_e).css({'width':_dt_cells_widths[_e]+'px'});
						}
					});
					//alert(_dt_cells_widths);
				}
			//}

			window.setTimeout(function(){
				jQuery(document).trigger('wd_grid_load', {status:'success'});
			}, 500);

			if(r.total_records == 0){
				//jQuery('#datagrid_search').hide();
			}

			if(r.next == ''){
				jQuery('.datatable_next_page_button').addClass('disabled');
			}else{
				if(parseInt(r.next) > 1){
					jQuery('.datatable_next_page_button').removeClass('disabled');
				}
			}

			if(parseInt(r.previous) >= 1){
				jQuery('.datatable_previous_page_button').removeClass('disabled');
			}
			if(r.previous == ''){
				jQuery('.datatable_previous_page_button').addClass('disabled');
			}

			//update previous number
			jQuery(".datatable_previous_page_button").attr("rel", r.previous);

			//update next number
			jQuery(".datatable_next_page_button").attr("rel", r.next);

			//update current page
			jQuery(".datatable_current_page").text(r.page);

			//update total pages
			jQuery(".datatable_total_pages").text(r.total_pages);

			//update number of records
			jQuery(".datagrid_total_records").text(r.total_records);

			if(jQuery('.calendar').length > 0){				var checkin = jQuery('.calendar').datepicker({
								onRender: function(date){
								}
							}).on('changeDate', function(ev) {
								checkin.hide();
								window.setTimeout(function(){
									reloadDataGrid();
							   }, 300);
							}).data('datepicker');
			}
		}
	});
}

////////////////////////////////////////////////////
///////// DATA GRID SEARCH /////////////////////////
////////////////////////////////////////////////////
var grid_time;

function gridSearch(ev){
	if(grid_time){
    	clearTimeout(grid_time);
	}
	grid_time = setTimeout(reloadDataGrid,500);
}

function prepare_filter_args(){
	//Prepare search
	searches = jQuery(".datatable_search_box");
	filter_str = '';
	jQuery.each(searches, function(a,b){
		if(jQuery(b).val()!=''){
			n = jQuery(b).attr("name").split("datatable_search_")[1];
			filter_str += "&"+n+"="+encodeURI(jQuery(b).val());
		}
	});

	//Prepare sort
	if(jQuery('li.sortable.active').length > 0){
		_current_sortable = jQuery('li.sortable.active').find('.sort-btns');
		_sortable_col = jQuery(_current_sortable).attr('data-col');
		_current_sort_order = jQuery(_current_sortable).find('.active').attr('data-order');

		filter_str += "&sort="+_sortable_col+"&sort_dir="+_current_sort_order;
	}
	return filter_str;
}

function reloadDataGrid(){
	filter_str = prepare_filter_args();
	paging(datatable_current_page, filter_str);
}

function reset_grid_search(){
	jQuery.each(jQuery('.datatable_search_box'), function(i, s_b){
		jQuery(s_b).val();
	});
}

jQuery(function(){
	jQuery('.sort-btns .asc').on('click', function(){
		sort_action(jQuery(this), 'asc');
	});
	jQuery('.sort-btns .desc').on('click', function(){
		sort_action(jQuery(this), 'desc');
	});
});

function sort_action(_btn, _order){
	if(!_btn.hasClass('active')){
		_btg = _btn.parent('.sort-btns');
		_col = jQuery(_btg).attr('data-col');

		_sortable_parent = _btg.parent('.sortable');
		
		//Reset all sortables
		jQuery('.sortable').removeClass('active');
		jQuery('.sort-btns .asc').removeClass('active');
		jQuery('.sort-btns .desc').removeClass('active');

		//Set new sortable
		_sortable_parent.addClass('active');
		_btn.addClass('active');

		window.setTimeout(function(){
			reloadDataGrid();
		}, 300);	
	}
	return false;
}

jQuery(function(){

	var scroll, wresize, mobile;
	var headerPos = 0;
	var headerWidth = 0;
	var headerToolbar = 0;
	var headerToolbarWidth = 0;
	var once = true;
	var init = false;
	var show, go;

	(scroll = function() {
		if(jQuery('#datagrid_headings').length){
			if(headerPos == 0){
				headerPos = parseInt(jQuery('#datagrid_headings').offset().top ) - 20;
				headerWidth = parseInt(jQuery('#datagrid_headings').innerWidth());
				headerToolbar = parseInt(jQuery('.datatable_toolbar').offset().top ) - 20;
				headerToolbarWidth = parseInt(jQuery('.datatable_toolbar').innerWidth());
			}
			if(mobile != true) {// && jQuery('#menu').css('position') != 'fixed'
				var scrollPos = jQuery(document).scrollTop();

				if(scrollPos > headerPos) {
					clearTimeout(show);
					init = true;
					//window.setTimeout(function(){
						jQuery('#datagrid_headings').addClass('fixed').css({'width':headerWidth+'px'});
						//jQuery('#datagrid_headings').addClass('fixed').css({'width':headerWidth+'px', 'top':(parseInt(jQuery('.datatable_toolbar').innerHeight()))+'px'});
						//jQuery('.datatable_toolbar').addClass('fixed').css({'width':headerToolbarWidth+'px'});
					//}, 1000);
				} else if(init === true) {

					clearTimeout(go);

					jQuery('#datagrid_headings').removeClass('fixed').removeAttr('style');
					//jQuery('.datatable_toolbar').removeClass('fixed').removeAttr('style');
					once = true;
					//jQuery('#logo').removeClass('logo-fixed').addClass('logo');
					init = false;
				}
			}
		}
	})();
	window.addEventListener('touchstart', function() {
		mobile = true;
	});

	jQuery(document).scroll(scroll);
});

jQuery(function(){
	jQuery(document).on('change', '.datatable-main-selectable', function(){
		if(this.checked){
			jQuery('.datatable-row-selectable').prop('checked', false);	
		}
		jQuery('.datatable-row-selectable').trigger('click');	
	});

	jQuery(document).on('click', '.datatable-row-selectable', function(){
		_drc = jQuery(this);
		_data_index = jQuery(_drc).attr('data-index');
		if(jQuery(_drc).is(':checked')){
			jQuery('.data-grid-tr-'+_data_index).addClass('checked');
		}else{
			jQuery('.data-grid-tr-'+_data_index).removeClass('checked');
		}
	});

	jQuery(document).on('change', '.bulk-select', function(){
		_ele = jQuery(this);
		if(jQuery(_ele).val() == ''){
			return;
		}
		_action = jQuery(_ele).find('option:selected').attr('data-action');
		_callback = jQuery(_ele).find('option:selected').attr('data-callback');

		_selected_rows = jQuery('.datatable-row-selectable:checked');
		if(_selected_rows.length == 0){
			alert("Rows not selected!!");
			jQuery(_ele).val('');
			return;
		}	

		if(_callback != ''){
			window[_callback]();
		}else if(_action != ''){
			
			if(jQuery(_ele).find('option:selected').attr('data-warning') != ''){
				if(!confirm(jQuery(_ele).find('option:selected').attr('data-warning'))){
					jQuery(_ele).val('');
					return;
				}
			}
			jQuery('.datatable_container').append('<form action="'+_action+'" method="post" id="datatable_bulk_action_form"></form>');
			_checkbox_fields = '';
			jQuery.each(_selected_rows, function(fi, fc){
				_checkbox_fields += '<input type="hidden" name="values[]" value="'+jQuery(fc).val()+'">';
			});
			jQuery('.datatable_container').find('form').append(_checkbox_fields);
			jQuery('#datatable_bulk_action_form')[0].submit();
		}
	});
});

function reset_bulk_action(){
	jQuery('.bulk-select').val();
}