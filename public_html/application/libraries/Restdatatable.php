<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restdatatable {

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
		}
		
		$this->permitted_fields = $this->CI->config->item('permitted_fields');
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

	public function setRowsPerPage($per_page=10){

		$this->rows_per_page = $per_page;
	}

	function rows_per_page(){

		return $this->rows_per_page;
	}

	public function setColumns($columns=array()){
		if(!is_array($columns))
			return false;


		$this->columns = $columns;

		foreach($columns as $column){

			if(array_key_exists('sortable', $column))

			$this->sorters[] = $column['field'];


			if(array_key_exists('field', $column))

				$this->tables[$column['name']] = $column['field'];
				

			if(array_key_exists('default_sort', $column))

				$this->default_sort = $column['field'];


			if(array_key_exists('default_sort_dir', $column) && (strtolower($column['default_sort_dir']) == 'asc' || strtolower($column['default_sort_dir']) == 'desc')){

				$this->default_sort_dir = $column['default_sort_dir'];
			}
		}
	}

	public function setRecords($records){

		$this->total_rows = $records;
	}

	public function current_page(){

		if(isset($_POST['page'])){

			return trim($_POST['page']);
		}

		return 1;
	}

	public function grid($query=false){

		{

			$current_page = $this->current_page();

			$paging = false;

			if($current_page){

				$rows_per_page = $this->rows_per_page();

				$from = ($current_page * $rows_per_page) - $rows_per_page;

				$from = ($from < 0) ? 0 : $from;

				$to = $rows_per_page;

				$paging = array("page"=>$current_page, "from"=>$from, "to"=>$to);
			}

			{//Sorting
				$sorter = array();
				$sorter['sort'] = (isset($_POST['sort_by'])) ? trim($_POST['sort_by']) : false;
				$sorter['sort_dir'] = (isset($_POST['sort_dir'])) ? trim($_POST['sort_dir']) : false;
			}

			$_search = false;
			$_search_list = false;

			if(isset($_POST['search'])){

				$search = @json_decode($_POST['search'], true);

				if(is_array($search) && count($search) > 0){

					foreach($search as $search_field => $search_keywords){

						if($search_keywords['keyword'] != ''){

							$_search_list[$search_field] = $search_keywords;
						}
					}
				}
			}
		}

		return $this->create_table($paging, $query, $_search_list, $sorter);
	}

	function create_table($paging=false, $query=false, $search=false, $sorter=array()){

		$table_fields = $this->columns;

		$calendar = false;

		{//paging

			$table_data = false;

			$allowed_search_types = ['match', 'like', 'gt', 'lt', 'date', 'dategt', 'datelt'];

			if(is_array($search) && count($search) > 0){

				$round = 0;

				// $this->CI->db->group_start();
				
					foreach($search as $search_field => $search_keywords){

						$round++;

						$search_type = "like";

						//Check for search type
						if( array_key_exists("type", $search_keywords) && in_array($search_keywords['type'], $allowed_search_types)){

							$search_type = trim($search_keywords['type']);
						}

						//Join tables fix
						// if(is_array($this->tables) && array_key_exists($search_field, $this->tables)){

						// 	$search_field = $this->tables[$search_field];
						// }

						$search_suffix = "";

						if($search_type == 'gt'){

							$search_suffix = " >";
						}
						if($search_type == 'lt'){

							$search_suffix = " <";
						}

						if($search_type == 'like'){

							$this->CI->db->like($search_field, (trim($search_keywords['keyword'])));

						}elseif($search_type == 'date'){

							$date = date("Y-m-d", strtotime(trim($search_keywords['keyword'])));
							$this->CI->db->where("date({$search_field})", $date);

						}elseif($search_type == 'dategt'){

							$date = date("Y-m-d", strtotime(trim($search_keywords['keyword'])));
							$this->CI->db->where("date({$search_field}) >= ", $date);

						}elseif($search_type == 'datelt'){

							$date = date("Y-m-d", strtotime(trim($search_keywords['keyword'])));
							$this->CI->db->where("date({$search_field}) <= ", $date);
						}else{

							$this->CI->db->where($search_field . $search_suffix, (trim($search_keywords['keyword'])));
						}
					}
				// $this->CI->db->group_end();
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

			$from = 0;

			if(is_array($paging) && array_key_exists('from', $paging)){

				$from = $paging['from'];
			}

			$table_data = $tempdb->limit($this->rows_per_page(), $from)->get()->result();
			// $table_data = $query->limit($this->rows_per_page(), $from)->get()->result();

			// echo $this->CI->db->last_query();

			$this->total_pages = ceil($this->total_records / $this->rows_per_page());
		}

		{//data to return

			$return = new stdClass;

			$return->table_data = $table_data;

			$return->page = $this->current_page();

			$return->total_records = $this->total_records;

			$return->total_pages = $this->total_pages;

			$return->search_result = '';
				

			if($search){

				$return->search_result = 'y';
			}
			
			return $return;
		}
	}
}