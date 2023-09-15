<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datatable
{
	var $CI;

	//table name of the database table
	protected $table_name;
	protected $datatable_record;
	protected $total_rows;
	protected $debug;

	protected $sorters;
	var $default_sort = false;
	var $default_sort_dir = "desc";

	//paging variables
	var $rows_per_page = 10;
	var $current_page = 1;
	var $total_records;
	var $total_pages;

	var $controller_key;

	var $bulk_actions;
	
	var $row_buttons;

	var $controller_name;

	var $title;

	var $hidePaging;
	var $hideHeadings;
	var $hideSearch;

	var $containerClass;

	var $additionalData;

	var $beforeToolbar;

	var $beforeActionButtons;

	var $afterActionButtons;

	var $listTimelineView = false;

	protected $columns;

	var $tables = [];

	function __construct($params=array()){

		{
			$this->CI =& get_instance();
			$this->CI->load->helper('url');
		}

		if(isset($params['table_name'])){
			$this->table_name = $params['table_name'];
		}/*elseif($params['table_record']){
		$this->datatable_record = $params['table_record'];
		}*/

		$url = implode("/", $this->CI->uri->segment_array());

		if(isset($params['controller_name'])){//to use from external controller
			$this->controller_name = $url . "/" . $params['controller_name'];
		}else{
			//$this->controller_name = $url . "/" . $this->CI->router->class . "/" . $this->CI->router->method;
			$this->controller_name = $url . "/" . $this->CI->router->method;
		}

		$current_segments_path = '';

		if(
			($this->CI->uri->segment(2) != "login" && $this->CI->uri->segment(2) != "logout" && $this->CI->uri->segment(2) != "dashboard")
			&&
			$this->CI->uri->segment(3) != "alerts"
		){
			
			if($this->CI->uri->segment(2)){
				$current_segments_path = $this->CI->uri->segment(2);
			}

			if($this->CI->uri->segment(3)){
				$current_segments_path .= '/'.$this->CI->uri->segment(3);
			}
		}

		$this->current_segments_path = $current_segments_path;

		$this->permitted_fields = $this->CI->config->item('permitted_fields');

		//prepare edit link
		//default settings can be override by editLink method
		$this->editTitle 		= "Edit";
		$this->editIndex 		= "id"; //edit by id
		$this->editLink 		= (isset($this->CI->_module)) ? admin_url($this->CI->_module . "/" . $this->CI->router->class . "/add") : '';  //point to add method of the current controller

		//prepare add link
		//default settings can be override by addLink method
		$this->addTitle 		= "Add";
		$this->addLink	 		= (isset($this->CI->_module)) ? admin_url($this->CI->_module . "/" . $this->CI->router->class . "/add") : '';  //point to add method of the current controller

		//extra buttons
		$this->buttons			= '';

		//
		$this->listView			= (isset($this->listView) && $this->listView != '') ? $this->listView : '';

		$this->listTimelineView			= (isset($this->listTimelineView) && $this->listTimelineView == true) ? $this->listTimelineView : false;
		
		//Manage action column
		$this->skipAction		= (!isset($this->skipAction)) ? false : $this->skipAction;

		$this->hidePaging 		= false;
		$this->hideHeadings 	= false;
		$this->hideSearch 		= false;

		//container class
		$this->containerClass = ($this->containerClass != '') ? $this->controller_name . "-grid " . $this->containerClass : $this->controller_name . "-grid";

		$this->beforeToolbar = ($this->beforeToolbar) ? $this->beforeToolbar : '';
	}
	
	public function setControllerKey($key=false){
		if($key && $key != ''){
			$this->controller_key = $key;
		}
	}
	
	public function getControllerKey(){
		if(isset($this->controller_key) && $this->controller_key != ''){
			return $this->controller_key;
		}
		return false;
	}
	
	public function session_vars($val=false){
		if($this->getControllerKey()){
			$controller_key = $this->getControllerKey();
			if($this->CI->session->has_userdata($controller_key)){
				$session_vars = $this->CI->session->userdata($controller_key);
				
				if($val && $val != '' && array_key_exists($val, $session_vars)){
					return $session_vars[$val];
				}
				
				//global session update
				if($val == 'rows_per_page'){
					if($this->CI->session->has_userdata('rows_per_page')){
						return $this->CI->session->userdata('rows_per_page');
					}
				}
				return $session_vars;
			}
		}
	}

	public function setTableName($table_name){
		$this->table_name = $table_name;
	}

	public function setColumns($columns=array()){
		if(!is_array($columns))
			return false;

		$this->columns[] = $columns;

		if(array_key_exists('sortable', $columns))
			$this->sorters[] = $columns['name'];

		if(array_key_exists('field', $columns))
			$this->tables[$columns['name']] = $columns['field'];

		if(array_key_exists('default_sort', $columns)){
			//if(array_key_exists('field', $columns)){
			//	$this->default_sort = $columns['field'];
			//}else{
				$this->default_sort = $columns['name'];
			//}
		}

		if(array_key_exists('default_sort_dir', $columns) && (strtolower($columns['default_sort_dir']) == 'asc' || strtolower($columns['default_sort_dir']) == 'desc')){
			$this->default_sort_dir = $columns['default_sort_dir'];
		}
	}

	public function setRecords($records){
		$this->total_rows = $records;
	}

	public function setTitle($title=''){
		$this->title = $title;
	}

	public function editLink($title=false, $index=false, $link=false){
		if($title==NULL){
			$this->editLink = '';
			return false;
		}
		$this->editTitle = ($title) ? $title : $this->editTitle;
		$this->editIndex = ($index) ? $index : $this->editIndex;
		$this->editLink = ($link) ? $link : $this->editLink;
	}

	public function addLink($title=false, $link=false){
		if($title==NULL){
			$this->addLink = '';
			return false;
		}
		$this->addTitle = ($title) ? $title : $this->addTitle;
		$this->addLink = ($link) ? $link : $this->addLink;
	}

	public function buttons($buttons=array()){
		$this->buttons = $buttons;
	}
	
	public function rows_per_page(){
		
		if($this->session_vars('rows_per_page')){
			$this->rows_per_page = $this->session_vars('rows_per_page');
		}
		
		if(isset($_REQUEST['per_page']) && $_REQUEST['per_page'] != ''){
			$this->rows_per_page = trim($_REQUEST['per_page']);
		}
		
		return $this->rows_per_page;
	}
	
	public function current_page(){
		if($this->session_vars('page')){
			$this->current_page = $this->session_vars('page');
		}
		
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != ''){
			$this->current_page = trim($_REQUEST['page']);
		}else{
			if(!$this->CI->input->is_ajax_request()) {
				$this->current_page = false;
			}
		}
		
		//set page 1 for first ajax call
		if($this->CI->input->is_ajax_request() && (!$this->current_page || $this->current_page == '') && (!isset($_REQUEST['page']) || $_REQUEST['page'] == '')) {
			$this->current_page = 1;
		}
		
		return $this->current_page;
	}

	function grid($query=false){
		{
			$current_page = $this->current_page();
			$paging = false;
			if($current_page){
				$rows_per_page = $this->rows_per_page();
				$from = ($current_page * $rows_per_page) - $rows_per_page;
				$from = ($from < 0) ? 0 : $from;
				$to = $rows_per_page;
				$paging = array("page"=>$current_page, "from"=>$from, "to"=>$to);
				
				if(isset($this->controller_key) && $this->controller_key != ''){
					//session vars
					$session_vars = array();
					
					$session_vars['page'] = $current_page;
					$session_vars['rows_per_page'] = $rows_per_page;
					$this->CI->session->set_userdata($this->controller_key, $session_vars);
				}
				$this->CI->session->set_userdata('rows_per_page', $rows_per_page);
			}
			
			{//Sorting
				$sorter = array();
				$sorter['sort'] = (isset($_GET['sort'])) ? trim($_GET['sort']) : false;
				$sorter['sort_dir'] = (isset($_GET['sort_dir'])) ? trim($_GET['sort_dir']) : false;
			}	

			//remove page index from request
			unset($_GET['page']);
			unset($_GET['per_page']);
			unset($_GET['sort']);
			unset($_GET['sort_dir']);
			$search = false;
			if(count($_GET)>0){//searching

				$search = $_GET;

				//exclude "filter_info"
				if(array_key_exists('filter_info', $search)){
					unset($search['filter_info']);
				}
			}
		}

		return $this->create_table($paging, $query, $search, $sorter);
	}

	function create_table($paging=false, $query=false, $search=false, $sorter=array()){
		//collect all the table fields
		if(count($this->columns)>0){
			//array table fields
			$table_fields = $this->columns;
			
		}else{//if columns not specified

			//@important to convert into array
			$table_fields_list = $this->CI->db->get($this->table_name)->list_fields();//
			foreach($table_fields_list as $list_column){
				$table_fields[] = array("name"=>$list_column);
			}
		}

		$calendar = false;
			
		{//paging
			$table_data = false;

			if($search){
				foreach($search as $search_field => $search_keywords){
					
					$search_type = "like";
					//Check for search type
					foreach($table_fields as $table_fields_for_search){
						if(array_key_exists("search_type", $table_fields_for_search) && ($table_fields_for_search['search_type'] == 'match' || $table_fields_for_search['search_type'] == 'like') && $search_field == $table_fields_for_search['name']){
							$search_type = trim($table_fields_for_search['search_type']);
						}
					}

					//Join tables fix
					if(is_array($this->tables) && array_key_exists($search_field, $this->tables)){
						$search_field = $this->tables[$search_field];
					}

					// $date_parse = date_parse($search_keywords);
					// if(isset($date_parse['year']) && $date_parse['year'] !='' && isset($date_parse['month']) && $date_parse['month'] != '' && isset($date_parse['day']) &&  $date_parse['day'] != '' && $date_parse['error_count'] == 0){
					// 	$this->CI->db->where($search_field, date("Y-m-d", strtotime($search_keywords)));
							
					// }else{

						if($search_type == 'like'){
							$this->CI->db->like($search_field, addslashes(urldecode($search_keywords)));
						}else{
							$this->CI->db->where($search_field, addslashes(urldecode($search_keywords)));
						}	
					// }
				}
			}

			$current_sort_dir = false;
			$current_sort_col = false;
			if( (array_key_exists('sort', $sorter) && ($sorter['sort'] !== false && $sorter['sort'] != '')) && (array_key_exists('sort_dir', $sorter) && ($sorter['sort_dir'] == 'asc' || $sorter['sort_dir'] == 'desc'))){

				//Join tables fix
				$default_sort_column = $sorter['sort'];
				if(is_array($this->tables) && array_key_exists($sorter['sort'], $this->tables)){
					$default_sort_column = $this->tables[$sorter['sort']];
				}

				$this->CI->db->order_by(trim($default_sort_column), trim($sorter['sort_dir']));
				$current_sort_dir = trim($sorter['sort_dir']);
				$current_sort_col = trim($sorter['sort']);
			}elseif( $this->default_sort !== false ){

				//Join tables fix
				$default_sort_column = $this->default_sort;
				if(is_array($this->tables) && array_key_exists($this->default_sort, $this->tables)){
					$default_sort_column = $this->tables[$this->default_sort];
				}

				$this->CI->db->order_by(trim($default_sort_column), trim($this->default_sort_dir));
				$current_sort_dir = trim($this->default_sort_dir);
				$current_sort_col = trim($this->default_sort);
			}
				
			$tempdb = clone $this->CI->db;
			if($counts_query = $query->get()){
				$this->total_records = $counts_query->num_rows();
			}else{
				print_r($this->CI->db->error());
				exit;
			}
			
			$this->debug = $this->CI->db->last_query();

			$from = 0;
			if($paging && is_array($paging) && array_key_exists('from', $paging) && $paging['from']){
				$from = $paging['from'];
			}
			$table_data = $tempdb->limit($this->rows_per_page(), $from)->get()->result();
			
			$this->total_pages = ceil($this->total_records / $this->rows_per_page());
				
			//current_page
			$from = 0;
			if($paging && is_array($paging) && array_key_exists('from', $paging) && $paging['from']){
				$from = $paging['from'];
			}
				
			if($paging && is_array($paging) && array_key_exists('page', $paging) && $paging["page"]){
				$page = $paging["page"];
				$next = ($page+1);
				if($this->total_pages <= $page){
					$next = '';
				}

				$previous = ($page-1);
				if($previous<=0){
					$previous = '';
				}
			}else{
				$next = 1;
				$previous = '';
				$page = 1;
			}
		}
		
		if(!$paging){//if paging is not passed than its initial request and just return the main container not the datatable
			$container = "<div class='datatable_container widget ".($this->containerClass)."'>";
				
				$container .= "<div class='datatable_toolbar'>";

					//Bulk actions
					$bulk_action_bar = $this->table_bulk_action();

					//prepare toolbar
					$table_toolbar = $this->table_toolbar($previous, $next);
					
					//toolbar end
					$container .= $this->beforeToolbar;
					$container .= $bulk_action_bar;
					$container .= $table_toolbar;
					
				$container .= "</div>";
				
				//Preparing Table Head
				$container .= $this->table_heading($table_fields, $current_sort_dir, $current_sort_col);

			$container .= "<div class='datatable_table_container' id='datatable_table_container'></div>";
			
			$container .= "<div class='toolbar-footer-container'><div class='datatable_toolbar'>".$table_toolbar . "</div></div>";
		}


		if($this->listView != ''){

			$table = "<div class='grid-container'>";
			if($table_data && count($table_data) > 0){
				$timeline_view = ($this->listTimelineView) ? 'timeline-view' : '';
				$table .= "<ul class='datatable-list-view {$timeline_view}'>";
				//$table .= $this->CI->load->view(admin_view($this->listView), array('products'=>$table_data, 'table_fields'=>$table_fields), true);
				foreach($table_data as $n => $_table_data){
					$class = '';
					if($n == 0){
						$class = 'first';
					}
					if($n+1 == count($table_data)){
						$class .= ' last';
					}
					$table .= $this->CI->load->view(admin_view($this->listView), array('data'=>$_table_data, 'table_fields'=>$table_fields, 'class'=>$class, 'additional_data'=>$this->additionalData), true);
				}
				$table .= "</ul>";
			}	
			$table .= "</div>";

		}else{
			if($paging){//if paging is passed then send the datatable only
				{//Prepare Table
					$table = "<div class='items-list'><div class='datatable-list wd-list'>";

					{//Table Body
						$table_body = '';
						$row_number = $page;
						if($table_data){
							foreach($table_data as $r_n => $data){
								$row_class_num = '';
								if($r_n == 0){
									$row_class_num = 'first ';
								}
								if(($r_n+1) == count($table_data)){
									$row_class_num .= 'last';
								}
								
								$row_number++;
								$table_body .= "<ul id='datagrid_tr_{$row_number}' class='data-grid-tr-".$data->{$this->editIndex}." datagrid_tr {$row_class_num}'>";

								$cols = 0;
								
								foreach($table_fields as $fields){

									//Check if the current user have field access permission
									if( ($this->permitted_fields && is_array($this->permitted_fields)) ){
										if(array_key_exists('name', $fields) && !in_array($fields['name'], $this->permitted_fields)){
											continue;
										}
									}

									$cols++;
									$cell_properties = '';
									//cell width
									$cell_properties .= (isset($fields['width'])) ? " width='".($fields['width'])."'" : "";
									//cell align
									$cell_properties .= (isset($fields['align'])) ? " align='".($fields['align'])."'" : "";
									
									$selectable_col = (array_key_exists('type', $fields) && $fields['type'] == 'selectable') ? true : false;

									//Ignore value if selectable col
									if($selectable_col){

										$selectable_data_index = (array_key_exists('column', $fields)) ? $fields['column'] : $this->editIndex;

										$table_body .= "<li class='selectable' id='selectable_{$row_number}'>
															<input data-index='".$data->{$selectable_data_index}."' type='checkbox' class='datatable-row-selectable' value='".$data->{$selectable_data_index}."'>
														</li>";

									}else{	
										//callback method

										$value = '';
										if(array_key_exists('name', $fields)){
											$value = $data->{trim($fields['name'])};
										}

										//filter values
										if($value == '0000-00-00' || $value == '0000-00-00 00:00:00'){
											$value = '';
										}
											
										//check if date format

										if(substr_count($value, '-') == 3){
										
											$date_format = date_parse($value);
											if($date_format['year'] && $date_format['month'] && $date_format['day'] && $date_format['error_count'] == 0){
												//its date format
												if($date_format['hour'] && $date_format['minute'] && $date_format['second']){
													$value = date('d M Y, g:iA', strtotime($value));
												}else{
													$value = date('d M Y', strtotime($value));
												}
											}
										}
											
										if(isset($fields['callback']) && $fields['callback'] != ''){
											$callback = explode("/", trim($fields['callback']));
											$value = $this->CI->{$callback[0]}->{$callback[1]}($data);
										}
											
										//title
										$title = str_replace(array("'",'"'), array("",""), stripslashes($value));
										if(isset($fields['title'])){
											$title = str_replace(array("'",'"'), array("",""), $fields['title']);
										}
											
										//image
										if(isset($fields['type']) && $fields['type'] == 'image'){
											if(isset($fields['path']) && $fields['path'] != ''){
												$path = ltrim(rtrim($fields['path'], '/'), '/');
												$path = base_url().$path;
												$_value = $value;
												if($value != ''){
													$value = "<img src='".$path.$_value."' style='float:left; margin-right:10px; max-width:100px; max-height:100px' />&nbsp;&nbsp;&nbsp;&nbsp;";
												}
											}
										}
											
										if(isset($fields['values']) && is_array($fields['values']) && (count($fields['values']) > 0) ){
											if(array_key_exists(trim($value), $fields['values'])){
												if(array_key_exists("badges", $fields)){
													if(array_key_exists($value, $fields['badges'])){
														$value = "<span class='badge ".($fields['badges'][$value])."'>".$fields['values'][$value]."</span>";
													}else{
														$value = $fields['values'][$value];
													}
												}else{
													$value = $fields['values'][$value];
												}	
											}
										}else{
											if(array_key_exists("badges", $fields)){
												if(array_key_exists($value, $fields['badges'])){
													$value = "<span class='badge ".($fields['badges'][$value])."'>".$fields['values'][$value]."</span>";
												}	
											}
										}
									
										$table_body .= "<li {$cell_properties} id='datatable_td_{$row_number}_{$cols}' title='".$title."'>".stripslashes($value)."</li>";
									}	
								}

								$_row_buttons = '';
								if($this->editTitle != false){
									$_row_buttons = "<a class='btn btn-primary btn-xs wd-popup-form-edit' data-index='".$data->{$this->editIndex}."' href='{$this->editLink}".$data->{$this->editIndex}."'>{$this->editTitle}</a>";
								}
									
								$btn_count = 0;
								if(isset($this->row_buttons)){
									$btn_count = count($this->row_buttons);
									foreach($this->row_buttons as $r_buttons){
										$link_target = "";
										if( array_key_exists('target', $r_buttons) && $r_buttons['target'] == '_blank'){
											$link_target = "target='_blank'";
										}
										$do_confirm = "";
										if( array_key_exists('confirm', $r_buttons) && $r_buttons['confirm']){
											//Ignored
											//$do_confirm = "onclick='if(!confirm(\"Are you sure!\")){ return false;}'";
										}

										$callback = "";
										if(array_key_exists('callback', $r_buttons) && $r_buttons['callback']){
											$callback_function = $r_buttons['callback'];
											$callback = "onclick='$callback_function(this, ".$data->{$r_buttons['index']}.")'";
										}

										if($r_buttons['link'] == false){
											$_row_buttons .= "<button {$callback} data-index='".$data->{$r_buttons['index']}."' class='btn btn-primary btn-xs'>".$r_buttons['title']."</button>";
										}else{
											$_row_buttons .= "<a {$link_target} {$do_confirm} {$do_confirm} class='btn btn-primary btn-xs' href='".$r_buttons['link'].$data->{$r_buttons['index']}."'>".$r_buttons['title']."</a> &nbsp;";
										}
									}
									//$_row_buttons = implode('&nbsp;', $_row_buttons);
								}
								$btn_grp_class = 'btn-group';
								if($btn_count > 3){
									$btn_grp_class = 'btn-group-vertical';
								}
								$table_body .= "<li><div class='{$btn_grp_class} row-buttons'>".$_row_buttons."</div></li></ul>";
							}
						}else{
							$no_data_text = '<div class="no-record"><strong>No Records Found</strong>';
							if($this->addLink){
								$no_data_text .= "&nbsp;&nbsp;<a class='btn btn-success btn-xs' href='".$this->addLink."'><i class='ico'>add_circle_outline</i> Add New Data</a>";
							}
							$no_data_text .= "<div>";

							$table_body .= $no_data_text;
						}
						$table_body .= "</div>";
					}

					//$table .= $table_toolbar;
					//$table .= $table_head;
					$table .= $table_body;
					$table .= "</div>";
				}
			}
		}

		{//ajax paging

			$datatable_controller_name = $this->controller_name.'/';
			if(isset($_GET['filter_info'])){

				$filter_info = http_build_query($_GET['filter_info']);
				$datatable_controller_name = $datatable_controller_name . "?" . $filter_info;
			}

			$javascript = '<script type="text/javascript">';
			$javascript .= 'var datatable_base_url = "'.base_url().'";';
			$javascript .= 'var datatable_controller_name = "'.$datatable_controller_name.'";';
			$javascript .= '</script>';
			/*$javascript .= '<script type="text/javascript" src="'.base_url('js/datatable.js').'"></script>'*/
		}

		{//data to return
			$return = new stdClass;
			//$return->javascript = $javascript;
			$html = $javascript;//this is for initial request
				
			$return->next = $next;
			$return->previous = $previous;
			$return->page = $page;
			$return->total_records = $this->total_records;
			$return->total_pages = $this->total_pages;
			$return->search_result = '';
			$return->debug = $this->current_segments_path . ' - ' . $this->debug;
				
			if($search){
				$return->search_result = 'y';
				$return->table = $table;
				die(json_encode($return));
			}
				
			if($paging){
				$return->table = $table;
			}
				
			if(!$paging){
				$container .= "</div>";//datatable container end
				$return->container = $container;
				$html .= $container;
			}
		}

		if(!$paging){
			return $html;
		}else{
			die(json_encode($return));
		}
	}

	public function table_bulk_action(){

		$bulk_action_bar = '';
		if(isset($this->bulk_actions) && is_array($this->bulk_actions)){
			$bulk_action_bar .= "<div class='datatable-bulk-action'>";
				$bulk_action_bar .= "<select class='bulk-select'>";
					$bulk_action_bar .= "<option value=''>With Selected</option>";
					foreach($this->bulk_actions as $bulk_action_item){
						$warning = (array_key_exists('warning', $bulk_action_item)) ? $bulk_action_item['warning'] : '';
						$action = (array_key_exists('action', $bulk_action_item)) ? $bulk_action_item['action'] : '';
						$callback = (array_key_exists('callback', $bulk_action_item)) ? $bulk_action_item['callback'] : ''; 
						$bulk_action_bar .= "<option data-action='".$action."' data-callback='".$callback."' data-warning='".$warning."' value='".($bulk_action_item['title'])."'>".$bulk_action_item['title']."</option>";
					}
				$bulk_action_bar .= "</select>";
			$bulk_action_bar .= "</div>";
		}
		return $bulk_action_bar;
	}

	public function table_toolbar($previous, $next){
		
		$hide_paging = ($this->hidePaging) ? ' style="display:none;"' : '';
		$table_toolbar = "<div class='datatable_tools' {$hide_paging}>";

			$table_toolbar .= "<div class='datatable_record_set'>";
				$table_toolbar .= "<div class='datatable_page_numbers'>Page <span class='datatable_current_page hl'>".$this->current_page()."</span> / <span class='datatable_total_pages hl'>{$this->total_pages}</span></div>";
				$table_toolbar .= "<div class='datatable_total_records'><span class='datagrid_total_records hl'>".$this->total_records."</span> Records Found</div>";
			
				$table_toolbar .= "<div class='datatable_per_page'>";
					$table_toolbar .= "<span class='datatable_per_page_txt'>Show</span>";
						$table_toolbar .= "<select name='datatable_per_page_select' class='datatable_per_page_select'>";
							$table_toolbar .= "<option ".(($this->rows_per_page() == '10') ? 'selected="selected"' : '')." value='10'>10</option>";
							$table_toolbar .= "<option ".(($this->rows_per_page() == '20') ? 'selected="selected"' : '')." value='20'>20</option>";
							$table_toolbar .= "<option ".(($this->rows_per_page() == '50') ? 'selected="selected"' : '')." value='50'>50</option>";
						$table_toolbar .= "</select>";
					$table_toolbar .= "<span class='datatable_per_page_txt'>Per Page</span>";
				$table_toolbar .= "</div>";
				
			$table_toolbar .= "</div>";
			
			$table_toolbar .= "<div class='datatable_toolbar_paging'>";
				$table_toolbar .= "<div class='btn-group'>";
					$table_toolbar .= "<button class='btn btn-xs btn-info disabled datatable_previous_page_button' rel='{$previous}'><i class='ico'>keyboard_arrow_left</i></button>";
					$table_toolbar .= "<button class='btn btn-xs btn-info datatable_next_page_button' rel='{$next}'><i class='ico'>keyboard_arrow_right</i></button>";
				$table_toolbar .= "</div>";
			$table_toolbar .= "</div>";
	
		$table_toolbar .= "</div>";

		$buttons_html = '<div class="datatable_actions_container">';

			if($this->beforeActionButtons && $this->beforeActionButtons != ''){
				$buttons_html .= '<div class="datatable_pre_actions">';
					$buttons_html .= $this->beforeActionButtons;
				$buttons_html .= '</div>';
			}

			if($this->afterActionButtons && $this->afterActionButtons != ''){
				$buttons_html .= '<div class="datatable_post_actions">';
					$buttons_html .= $this->afterActionButtons;
				$buttons_html .= '</div>';
			}

			$buttons_html .= '<div class="datatable_actions">';
				
				if(isset($this->buttons) && $this->buttons!=''){
					foreach($this->buttons as $button){

						$button_callback = "";
						if(array_key_exists('callback', $button) && $button['callback']){
							$button_callback_function = $button['callback'];
							$button_callback = "onclick='return $button_callback_function(this)'";
						}

						$buttons_html .= '<a '.$button_callback.' href="'.$button['link'].'" class="btn btn-info btn-xs"><i class="ico">add_circle_outline</i>'.$button['label'].'</a>&nbsp; &nbsp;';
					}
				}

				if(isset($this->addLink) && $this->addLink){
					$buttons_html .= '<a href="'.$this->addLink.'" class="btn btn-info btn-xs add-new-form-button"><i class="ico">add_circle_outline</i>'.$this->addTitle.'</a>';
				}

			$buttons_html .= '</div>';

		$buttons_html .= "</div>";

		$table_toolbar .=  $buttons_html;

		return $table_toolbar;
	}

	public function table_heading($table_fields, $current_sort_dir, $current_sort_col){

		//calculate width in pixels
		foreach($table_fields as $f_n => $fields){

			//Check if the current user have field access permission
			if( ($this->permitted_fields && is_array($this->permitted_fields)) ){
				if(array_key_exists('name', $fields) && !in_array($fields['name'], $this->permitted_fields)){
					continue;
				}
			}

			$cell_properties = '';

			//selectable declaration
			$selectable_col = (array_key_exists('type', $fields) && $fields['type'] == 'selectable') ? true : false;

			$cell_properties .= (isset($fields['width'])) ? " data-width=".($fields['width'])."" : "";
			
			$sort_buttons = '';
			if(array_key_exists('sortable', $fields) && !$selectable_col){
				$cell_properties .= " class='sortable ".(($current_sort_col == $fields['name']) ? 'active' : '')."'";
				$sort_buttons = "<div class='sort-btns' data-col='".$fields['name']."'>";
					$sort_buttons .= "<span title='Sort ASC' data-order='asc' class='asc ".(($current_sort_dir == 'asc' && ($current_sort_col == $fields['name'])) ? 'active' : '')."'></span>";
					$sort_buttons .= "<span title='Sorc DESC' data-order='desc' class='desc ".(($current_sort_dir == 'desc' && ($current_sort_col == $fields['name'])) ? 'active' : '')."'></span>";
				$sort_buttons .= "</div>";
			}	

			{//prepare select box for search
				$search_box = '';
				if(!isset($fields['hide_search']) && !$selectable_col){
					if(isset($fields['search']) && is_array($fields['search'])){
						$search_box = "<select class='datatable_search_box form-control' style='width:100%' name='datatable_search_".$fields['name']."' id='datatable_search_".$fields['name']."' onchange='gridSearch(arguments[0]||event)'><option value=''>Select...</option>";
						foreach($fields['search'] as $option => $value){
							$search_box .= "<option value='{$option}'>{$value}</option>";
						}
						$search_box .= "</select>";
							
					}elseif(isset($fields['search']) && $fields['search']=='date'){
						$search_box = "<input class='datatable_search_box form-control' type='text' style='width:100%' name='datatable_search_".$fields['name']."' id='datatable_search_".$fields['name']."' onkeydown='gridSearch(arguments[0]||event)' />";
							
					}elseif(isset($fields['calendar']) && $fields['calendar']){
						$search_box = "<input class='datatable_search_box calendar form-control' type='text' name='datatable_search_".$fields['name']."' id='datatable_search_".$fields['name']."' onkeydown='gridSearch(arguments[0]||event)' />";
						$calendar = true;
					}else{
							
						$search_box = "<input placeholder='Search by ".$fields['column']."' class='datatable_search_box form-control' type='text' name='datatable_search_".$fields['name']."' id='datatable_search_".$fields['name']."' onkeydown='gridSearch(arguments[0]||event)' />";
					}
				}
			}
			
			if($selectable_col){
				$table_headings[] = "<li {$cell_properties}><input type='checkbox' class='datatable-main-selectable'></li>";
				$table_search[] = "<li class='search-header' {$cell_properties} align='center'></li>";
			}else{
				$table_headings[] = "<li {$cell_properties}>".ucwords(str_replace("_", " ", $fields['column'])).$sort_buttons."</li>";
				$table_search[] = "<li class='search-header' {$cell_properties} align='center'>{$search_box}</li>";
			}
		}
		$table_head = '';

		$table_columns = "<ul class='datagrid-headings' id='datagrid_headings'>".implode("", $table_headings);
		if(!$this->skipAction){
			$table_columns .= "<li class='action-heading'></li>";
		}	
		$table_columns .= "</ul>";

		if(!$this->hideHeadings){
			$table_head = $table_head . $table_columns;
		}	
			
		$table_search_columns = "<ul class='datagrid-searches' id='datagrid_search'>";
			$table_search_columns .= implode("", $table_search);
			if(!$this->skipAction){
				$table_search_columns .= "<li class='action-heading'></li>";
			}
		$table_search_columns .= "</ul>";
		if(!$this->hideSearch){
			$table_head = $table_head . $table_search_columns;
		}

		return $table_head;
	}
}