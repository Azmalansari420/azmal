<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ajax extends Front_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('cms/page_model');
	}
	
	
	
	public function products_listing(){

		$key = $this->input->post('key', true);

		$filters = [];
		$filters['limit'] = 20;

		//Manage by request params

		if($key == '#suits_by_categories'){

			$parent_category = 'suits';
			$parent_category_id = 0;

			$category_ids = $this->input->post('category_ids', true);
			if($category_ids != ''){
				$filters['category_ids'] = explode(',', $category_ids);
			}

			$except_ids[] = $this->input->post('except_id', true);
			$filters['except_ids'] = $except_ids;
			$filters['limit'] = 10;
			$filters['sorting'] = 'random';
		}

		$products = $this->products_model->get_products_by_cat($filters);

		$products_view = '';

		if(count($products) > 0){
			
			$this->data['products'] = $products;
			$this->data['is_ajax'] = true;

			if($key == '#suits_by_categories'){
				$products_view = $this->load->view($this->theme->get_view("catalog/blocks/related-products"), $this->data, true);
			}else{
				$products_view = $this->load->view($this->theme->get_view("catalog/sliders/products-slider"), $this->data, true);
			}

			die(json_encode(array(
				'status' => 'success',
				'products_view' => $products_view
			),  JSON_INVALID_UTF8_SUBSTITUTE));
		}else{
			$html = '<div class="not-found mb-5 mt-5 pt-5">No Products Found!!</div>';

			die(json_encode(array('status'=>'failure', 'html'=>$html)));
		}
	}

	public function add_to_wishlist(){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$product_id = $this->input->post('product_id', true);
			$customer = $this->session->userdata('account');

			if($product_id && $customer){

				$this->load->model('customers/wishlist_model');
				$item_exists = $this->wishlist_model->check_wishlist_item($product_id, $customer['id']);

				if($item_exists){

					die(json_encode(array('status'=>'failure', 'message'=>'Product is already added to your wishlist!!')));
				}else{

					$this->load->model('catalog/products_model');
					$product = $this->products_model->load_product('id', $product_id);

					if($product){

						$this->wishlist_model->set_post_data('customer_id', $customer['id']);
						$this->wishlist_model->set_post_data('product_id', $product_id);
						$this->wishlist_model->set_post_data('product_name', clean_display($product->name));
						$this->wishlist_model->set_post_data('slug', clean_display($product->url));

						$this->wishlist_model->set_post_data('added_on', date('Y-m-d H:i:s'));

						$added = $this->wishlist_model->post_save();
						if($added){
							die(json_encode(array('status'=>'success', 'message'=>'Product added to wishlist successfully!!')));
						}else{
							die(json_encode(array('status'=>'failure', 'message'=>'There was an error. Please try again later!!')));
						}
					}else{

						die(json_encode(array('status'=>'failure', 'message'=>'Product not available!!')));
					}
				}
			}else{
				die(json_encode(array('status'=>'failure', 'message'=>'Product ID or Customer missing!!')));
			}
		}
	}

	public function remove_from_wishlist(){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$product_id = $this->input->post('product_id', true);
			$customer = $this->session->userdata('account');

			if($product_id && $customer){

				$this->load->model('customers/wishlist_model');
				$item_id = $this->wishlist_model->check_wishlist_item($product_id, $customer['id']);

				if($item_id){

					$this->wishlist_model->remove($item_id);

					die(json_encode(array('status'=>'success', 'message'=>'Product removed from your wishlist!!')));
				}else{

					die(json_encode(array('status'=>'failure', 'message'=>'Product not available!!')));
				}
			}else{
				die(json_encode(array('status'=>'failure', 'message'=>'Product ID or Customer missing!!')));
			}
		}
	}

	public function newsletter_subscribe(){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$email = $this->input->post('email', true);

			$this->load->model('newsletter_subscribers_model');

			if($email != ''){

				$exist = $this->newsletter_subscribers_model->fetch_row_by_field('email', trim($email));

				if(!$exist){
					$this->newsletter_subscribers_model->post_data('email', 'email');
					$this->newsletter_subscribers_model->set_post_data('status', newsletter_subscribers_model::STATUS_ACTIVE);

					$this->newsletter_subscribers_model->set_post_data('added_on', date('Y-m-d H:i:s'));

					$this->newsletter_subscribers_model->post_save();

					die(json_encode(array('status'=>'success', 'message'=>'You have been subscribed successfully!!')));
				}else{

					die(json_encode(array('status'=>'failure', 'message'=>'This email is already subscribed with us!!')));
				}
			}else{
				die(json_encode(array('status'=>'failure', 'message'=>'Email ID not provided!!')));
			}
		}
		error_msg('Method not accessible directly!!', base_url());
	}
}