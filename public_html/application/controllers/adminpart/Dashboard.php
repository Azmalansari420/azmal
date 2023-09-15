<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Dashboard";
		$this->breadcrumb();
	}
	
	public function index(){
		
		/*$this->load->model('catalog/products_model');
		$this->load->model('sales/order_model');
		$this->load->model('customers/customers_model');*/

		$products = 0;//$this->db->where('status', products_model::STATUS_ENABLE)->get($this->products_model->table_name())->num_rows();
		
		$this->data['products'] = $products;

		$orders = 0;//$this->db->get($this->order_model->table_name())->num_rows();
		
		$this->data['orders'] = $orders;

		$customers = 0;//$this->db->get($this->customers_model->table_name())->num_rows();
		
		$this->data['customers'] = $customers;

		$this->Page("base/dashboard");
	}
	
	public function alerts_count(){
		
		$this->load->model('notifications_model');

		$return = new stdClass;
		$alerts_count = 0; 
		
		$alerts_count = $this->db->where('read_status', notifications_model::STATUS_UNREAD)->get($this->notifications_model->table_name())->num_rows();
		
		$return->alerts_count = $alerts_count;
		
		die(json_encode($return));
	}

	public function alerts(){
		
		$this->load->model('notifications_model');

		$return = new stdClass;
		
		$_alerts = $this->db->where('read_status', notifications_model::STATUS_UNREAD)->get($this->notifications_model->table_name());
		
		$alerts = [];

		if($_alerts->num_rows() > 0){

			$alerts = $_alerts->result();
		}

		$this->data['alerts'] = $alerts;

		$return->html = $this->load->view($this->admin_view("common/notifications"), $this->data, true);
		
		die(json_encode($return));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */