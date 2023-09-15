<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WD_Form_validation extends CI_Form_validation {
	
	protected $CI;
	
	public function __construct() {
		parent::__construct();
		// reference to the CodeIgniter super object
		$this->CI =& get_instance();
	}
	
	public function alpha_underscore($str){
		return (bool) preg_match('/^[a-z0-9_]+$/i', $str);
	}
}