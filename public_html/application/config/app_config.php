<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

///////////////////////////////////////////////////////////////////////////////////////////
$config['site_title']		= 'Listing';
///////////////////////////////////////////////////////////////////////////////////////////

//ci_form element types
$config['element_types'] = array("text"=>"input", "file"=>"upload", "image"=>"upload", "gallery"=>"upload", "select"=>"dropdown", "texteditor"=>"textarea", "textarea"=>"textarea", "checkbox"=>"checkbox", "radio"=>"radio", "yesno"=>"dropdown", "multiselect"=>"multiselect", "hidden"=>"hidden", "password"=>"password", 'categories'=>'', 'rating'=>'rating', 'date'=>'date');

//////////////////////////////////////////
//admin setup
//////////////////////////////////////////
//admin directory name
$config['admin_dir']			= 'admin';

//admin url name
$config['admin_url']			= 'adminpart';
	
//base js and css
$config['add_js']			= array('jquery.js', 'jquery.easing.1.3.js', 'common.js', 'tether.min.js', 'bootstrap.min.js', 'validator.js'/*, 'jquery.dcjqaccordion.2.7.js', 'jquery.scrollTo.min.js', 'jquery.slimscroll.js', 'jquery.nicescroll.js', 'functions.js', 'calendar.js'*//*, 'base/scripts.js'*/);

$config['add_css']			= array('tether.min.css', 'bootstrap.min.css', 'style.css'/*, 'bootstrap-reset.css', 'font-awesome.min.css', 'jquery-jvectormap-1.2.2.css', 'style-responsive.css', 'calendar.css'*/);

$config['modules'] 			= array(
									'dashboard' => array('path'=>'dashboard', 'title'=>'Dashboard', 'icon'=>'dashboard',
												'permissions'=>array(
													array('path'=>'dashboard', 'title'=>'Dashboard'),	
													)
												),
												
									'location' => array('path'=>'location', 'title'=>'location', 'icon'=>'map',
											'childs'=>array(
												array('heading'=>'Location'),
												array('path'=>'location/cities', 'title'=>'Cities', 'icon'=>'orders'),
												array('path'=>'location/locality', 'title'=>'Locality', 'icon'=>'view_headline'),
											),
											'permissions'=>array(
												array('path'=>'location/cities', 'title'=>'All Cities', 'icon'=>'list'),
												array('path'=>'location/cities/add', 'title'=>'Add City', 'icon'=>'add'),
												array('path'=>'location/locality', 'title'=>'Locality', 'icon'=>'list'),
												array('path'=>'location/locality/add', 'title'=>'Add Locality', 'icon'=>'add'),
											)
										),			
									
									'keywords' => array('path'=>'keywords', 'title'=>'Keywords', 'icon'=>'dns',
												'permissions'=>array(
													array('path'=>'keywords', 'title'=>'Keywords'),	
													)
												),
									
									'tags' => array('path'=>'tags', 'title'=>'Tags', 'icon'=>'dns',
												'permissions'=>array(
													array('path'=>'tags', 'title'=>'Tags'),	
													)
												),
									
									'profile_list' => array('path'=>'profile_list', 'title'=>'Profiles List', 'icon'=>'dns',
												'permissions'=>array(
													array('path'=>'profile_list', 'title'=>'Profiles List'),	
													)
												),
									
									/*'profile_list' => array('path'=>'profile_list', 'title'=>'Profiles', 'icon'=>'map',
											'childs'=>array(
												array('heading'=>'Profiles'),
												array('path'=>'profile_list', 'title'=>'Profiles List', 'icon'=>'orders'),
												array('path'=>'profile_list/enquiries', 'title'=>'Profiles Enquiries', 'icon'=>'orders'),
												
												//array('path'=>'profiles/upnumber/city', 'title'=>'Update Numbers in City', 'icon'=>'view_headline'),
												//array('path'=>'profiles/upnumber/locality', 'title'=>'Update Numbers in Locality', 'icon'=>'view_headline'),
											),
											'permissions'=>array(
												array('path'=>'profile_list', 'title'=>'All Profiles', 'icon'=>'list'),
												array('path'=>'profile_list/add', 'title'=>'Add Profile', 'icon'=>'add'),
												array('path'=>'profile_list/enquiries', 'title'=>'Profiles Enquiries', 'icon'=>'list'),
												//array('path'=>'profiles/upnumber/city', 'title'=>'Update Numbers in City', 'icon'=>'list'),
												//array('path'=>'profiles/upnumber/locality', 'title'=>'Update Numbers in Locality', 'icon'=>'list'),
											)
										),*/
									
									
									'enquiries' => array('path'=>'enquiries', 'title'=>'Enquiries', 'icon'=>'dns',
												'permissions'=>array(
													array('path'=>'enquiries', 'title'=>'Enquiries'),	
													)
												),			
									
									
									
									'customers' => array('path'=>'customers', 'title'=>'Customers', 'icon'=>'dns',
												'permissions'=>array(
													array('path'=>'customers', 'title'=>'Customers'),	
													)
												),
									
												
										
									'cms' => array('path'=>'cms/page', 'title'=>'Pages CMS', 'icon'=>'Pages',
												'permissions'=>array(
													array('path'=>'cms/page', 'title'=>'Pages'),	
													)
												),
									
									'settings' => array('path'=>'settings', 'title'=>'Settings', 'icon'=>'settings',
												'permissions'=>array(
													array('path'=>'settings', 'title'=>'Settings'),	
													)
												),					

									/*'catalog' => array('path'=>'catalog', 'title'=>'Catalog', 'icon'=>'dns',
												'childs'=>array(
													
													array('heading'=>'Catalog'),

													array('path'=>'categories', 'title'=>'Categories', 'icon'=>'view_headline', 'parent'=>'catalog'),

													array('path'=>'catalog/products/listing/product', 'title'=>'Products', 'icon'=>'view_headline', 'parent'=>'catalog'),

													array('path'=>'reviews', 'title'=>'Reviews', 'icon'=>'view_headline', 'parent'=>'catalog'),
													array('path'=>'taxes', 'title'=>'Taxes', 'icon'=>'view_headline', 'parent'=>'catalog'),
													array('path'=>'units', 'title'=>'Units Master', 'icon'=>'view_headline', 'parent'=>'catalog'),

													array('seperator'=>true),

													array('heading'=>'Attributes'),
													array('path'=>'catalog/attributes', 'title'=>'All Attributes', 'icon'=>'view_headline', 'parent'=>'catalog'),
													
													array('seperator'=>true),
													
													array('heading'=>'Fabrics & Stitchings'),
													array('path'=>'fabrics/groups', 'title'=>'Fabric Groups', 'icon'=>'view_headline', 'parent'=>'catalog'),
													array('path'=>'fabrics', 'title'=>'Fabrics', 'icon'=>'view_headline', 'parent'=>'catalog'),
													array('path'=>'fabrics/lining', 'title'=>'Linings', 'icon'=>'view_headline', 'parent'=>'catalog'),
													array('path'=>'fabrics/stitching', 'title'=>'Stitchings', 'icon'=>'view_headline', 'parent'=>'catalog'),
												),
												'permissions'=>array(
													array('path'=>'categories', 'title'=>'View Categories'),
													array('path'=>'categories/root', 'title'=>'Add/Edit Category'),
													array('path'=>'products', 'title'=>'View All Products'),
													array('path'=>'products/add', 'title'=>'Add/Edit Product'),
													array('path'=>'products/view', 'title'=>'View Single Products'),
													array('path'=>'products/remove', 'title'=>'Delete Product'),
													array('path'=>'reviews', 'title'=>'All Reviews', 'icon'=>'list'),
													array('path'=>'reviews/add', 'title'=>'Edit Review', 'icon'=>'add'),
													array('path'=>'reviews/view', 'title'=>'View Review', 'icon'=>'add'),
													array('path'=>'reviews/remove', 'title'=>'Remove Review', 'icon'=>'add'),

													array('path'=>'taxes', 'title'=>'All Taxes', 'icon'=>'list'),
													array('path'=>'taxes/add', 'title'=>'Edit Tax', 'icon'=>'add'),
													array('path'=>'taxes/remove', 'title'=>'Remove Tax', 'icon'=>'add'),

													array('path'=>'units', 'title'=>'All Units', 'icon'=>'list'),
													array('path'=>'units/add', 'title'=>'Edit Unit', 'icon'=>'add'),
													array('path'=>'units/remove', 'title'=>'Remove Unit', 'icon'=>'add'),

													array('path'=>'fabrics/groups', 'title'=>'Fabric Groups List', 'icon'=>'add'),
													array('path'=>'fabrics/groups/add', 'title'=>'Add/Edit Fabric Group', 'icon'=>'add'),
													array('path'=>'fabrics', 'title'=>'Fabrics List', 'icon'=>'add'),
													array('path'=>'fabrics/add', 'title'=>'Add/Edit Fabrics', 'icon'=>'add'),
												)
											),*/

									/*'customers' => array('path'=>'customers', 'title'=>'Customers', 'icon'=>'supervisor_account',
											'permissions'=>array(
												array('path'=>'customers', 'title'=>'All Customers', 'icon'=>'list'),
												array('path'=>'customers/add', 'title'=>'Add Customer', 'icon'=>'add'),

												array('path'=>'customers/reviews', 'title'=>'All Reviews', 'icon'=>'list'),
												array('path'=>'customers/reviews/add', 'title'=>'Edit Review', 'icon'=>'add'),
												array('path'=>'customers/reviews/view', 'title'=>'View Review', 'icon'=>'add'),
												array('path'=>'customers/reviews/remove', 'title'=>'Remove Review', 'icon'=>'add'),
											)
										),*/

									/*'sales' => array('path'=>'sales', 'title'=>'Sales', 'icon'=>'shopping_cart',
											'childs'=>array(
												array('heading'=>'Orders'),
												array('path'=>'sales/orders', 'title'=>'Orders', 'icon'=>'orders'),
												array('path'=>'sales/payments', 'title'=>'Payments', 'icon'=>'view_headline'),
												
												// array('heading'=>'Offers'),
												// array('path'=>'sales/coupons', 'title'=>'Coupons', 'icon'=>'orders'),
												// array('path'=>'sales/vouchers', 'title'=>'Vouchers', 'icon'=>'orders'),
											),
											'permissions'=>array(
												array('path'=>'sales/orders', 'title'=>'All Orders', 'icon'=>'list'),
												array('path'=>'sales/orders/add', 'title'=>'Add Order', 'icon'=>'add'),
												array('path'=>'sales/payments', 'title'=>'Payments', 'icon'=>'list'),
											)
										),*/
									
									/*'cms' => array('path'=>'cms', 'title'=>'CMS', 'icon'=>'pages', 'include_in_nav' => true, 'nav_model' => 'cms_page_model',
											'childs'=>array(
												array('path'=>'cms/page', 'title'=>'Pages', 'icon'=>'pages'),
												array('path'=>'cms/section', 'title'=>'Sections', 'icon'=>'pages'),
												// array('path'=>'sitemap', 'title'=>'Sitemap', 'icon'=>'pages'),
												array('seperator'=>true),
												array('heading'=>'Banners'),
												array('path'=>'banners', 'title'=>'Manage Banners', 'icon'=>'pages', 'parent'=>'cms'),
											),
											'permissions'=>array(
												array('path'=>'cms/page', 'title'=>'All Pages', 'icon'=>'list'),
												array('path'=>'cms/page/add', 'title'=>'Add Page', 'icon'=>'add'),
												array('path'=>'banners', 'title'=>'All Banners', 'icon'=>'list'),
												array('path'=>'banners/add', 'title'=>'Add Banner', 'icon'=>'add'),
											)		
										),*/

									/*'settings' => array('path'=>'settings', 'title'=>'System', 'icon'=>'settings',
												'childs'=>array(
													array('path'=>'settings', 'title'=>'General Settings', 'icon'=>'view_headline', 'parent'=>'settings'),
													array('path'=>'email_template', 'title'=>'Email Template', 'icon'=>'local_post_office'),
													array('seperator'=>true),
													// array('heading'=>'Theme Settings'),
													// array('path'=>'menu', 'title'=>'Navigations', 'icon'=>'view_headline', 'parent'=>'settings'),
												),
												'permissions'=>array(
													array('path'=>'settings', 'title'=>'View Settings'),
													array('path'=>'settings/add', 'title'=>'Add/Edit Settings'),
													array('path'=>'email_template', 'title'=>'All Email Templates', 'icon'=>'list'),
													array('path'=>'email_template/add', 'title'=>'Add Email Template', 'icon'=>'add'),
													array('path'=>'email_template/view', 'title'=>'View Email Template'),
												)
											),*/
												
									/*'users' => array('path'=>'users', 'title'=>'Users', 'icon'=>'people_outline',
												'childs'=>array(
													array('path'=>'users', 'title'=>'View All Users', 'icon'=>'view_headline'),
													array('path'=>'users/roles', 'title'=>'View Roles', 'icon'=>'view_headline'),
												),
												'permissions'=>array(
													array('path'=>'users', 'title'=>'View All Users'),
													array('path'=>'users/roles', 'title'=>'View Roles'),
												)
											),*/
								);

//////////////////////////////////////////

//module configs
$config['module_backup'] = 'misc/modules_backup/';

/*
 Media Configs
*/
$config['media_url'] = 'media/';

//media uploads
$config['media_upload_dir'] = $config['media_url'].'uploads/';

//media downloads
$config['media_download_dir'] = $config['media_url'].'downloads/';

//allowed media types
$config['media_allowed_types'] = 'gif|jpg|png|JPG|GIF|PNG|MP3|mp3';


/* End of file config.php */
/* Location: ./application/config/app_config.php */