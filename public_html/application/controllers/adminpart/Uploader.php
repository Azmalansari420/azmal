<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploader extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Uploader";
		$this->breadcrumb(array("uploader"=>"Uploader"));
		//$this->load->model("Users_model");
	}

	public function index(){
		include "application/third_party/UploadHandler.php";
		
		$options = array('upload_dir'=>'./media/tmp/', 'upload_url'=>'./media/tmp/');
		
		$upload_handler = new UploadHandler($options);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */