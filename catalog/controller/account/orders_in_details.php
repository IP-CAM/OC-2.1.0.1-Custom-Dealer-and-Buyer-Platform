<?php
class ControllerAccountOrdersInDetails extends Controller {
	
	private $error = array();

	public function product_order_history(){
		$json = array();

		$data = [];
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = 0;
		}
		if (isset($this->request->get['order'])) {
			$data['order'] = $this->request->get['order'];
		} 

		if(isset($this->request->get['sort_order'])){
			$data['sort'] = $this->request->get['sort_order'];
		} 
		
		$this->load->model('account/orders_in_details');

		$data = $this->model_account_orders_in_details->getProductOrderHistory($data);


		if( count($data) > 0){
			//data name, id, model
			$json['order_history'] = $data['order_history'];
			//to be removed
			$json['customer_id'] = $data['customer_id'];
		} 

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function products_for_order_history(){
		
		$json = array();

		if (isset($this->request->get['product_data'])) {
			$product_data = $this->request->get['product_data'];
		} else {
			$product_data = '';
		}
		
		$this->load->model('account/orders_in_details');

		$products = $this->model_account_orders_in_details->getProducts($product_data);

		if( count($products) > 0){
			//data name, id, model
			$json['products'] = $products;
		} 

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		// ARIEL ORDER IN DETAIL

		$this->load->model('account/customer');
		//USELESS
		$data['customer_role'] = $this->model_account_customer->getCustomer($this->customer->getId());
		if( isset($this->session->data['is_dealer']) ){
			$data['is_dealer'] = $this->session->data['is_dealer'];
		} 	

		// ARIEL ORDER IN DETAIL END

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}	

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_option_value'])) {
			$filter_option_value = $this->request->get['filter_option_value'];
		} else {
			$filter_option_value = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		

		$this->load->language('account/orders_in_details');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_option_value'])) {
			$url .= '&filter_option_value=' . urlencode(html_entity_decode($this->request->get['filter_option_value'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/orders_in_details');
		$this->load->model('account/order');
		$this->load->model('catalog/product');

//CHECK $customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);
		

		$filter_data = array(
			'filter_order_id'          => $filter_order_id,
			'filter_date_added'        => $filter_date_added,
			'filter_email'        		=> $filter_email,
			'filter_product_name'      => $filter_product_name,
			'filter_model'      => $filter_model,
			'filter_option_value'      => $filter_option_value,
			
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);
		$results = $this->model_account_orders_in_details->getOrders( $filter_data );

		
		$order_total = $this->model_account_orders_in_details->getOrdersTotal( $filter_data );;
		// var_dump($order_total);
		$data['orders'] = $results;

		//2 data sort
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_option_value'])) {
			$url .= '&filter_option_value=' . urlencode(html_entity_decode($this->request->get['filter_option_value'], ENT_QUOTES, 'UTF-8'));
		}

		
		

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order_id'] 		= $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$data['sort_date_added'] 	= $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_email'] 		= $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . '&sort=o.email' . $url, 'SSL');
		$data['sort_product_name'] 	= $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . '&sort=op.name' . $url, 'SSL');
		$data['sort_model'] 		= $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . '&sort=op.model' . $url, 'SSL');
		$data['sort_option_value'] 	= $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . '&sort=op2.value' . $url, 'SSL');
		
		//3 pagination
		
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
		}			

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_option_value'])) {
			$url .= '&filter_option_value=' . urlencode(html_entity_decode($this->request->get['filter_option_value'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}



		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		// var_dump($page);
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('account/orders_in_details', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		// var_dump($pagination->url);
		// var_dump($pagination->render());
		$data['pagination'] = $pagination->render();
		// var_dump($data['pagination']);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		// ARIEL CHECK PRODUCTS ALREADY IN A CART
		
		$prods_in_cart = $this->cart->getProducts();
		// var_dump($prods_in_cart);

		$data['products_in_cart'] = [];
		$data['products_in_cart_info'] = [];
		// echo '<pre>';
		// var_dump($prods_in_cart);
		// echo '</pre>';

		foreach ($prods_in_cart as $product) {
			array_push($data['products_in_cart'], $product['product_id']);	
			if( isset($data['products_in_cart_info'][$product['product_id']]) ){
				$data['products_in_cart_info'][$product['product_id']][] = [
					'price' 	=> (float)$product['price'],
					'quantity'  => $product['quantity'],
					'option'    => $product['option'],
				];
			} else {
				$data['products_in_cart_info'][$product['product_id']][0] = [
					'price' 	=> (float)$product['price'],
					'quantity'  => $product['quantity'],
					'option'    => $product['option'],
				];
			}
		}

		// echo '<pre>';
		// var_dump($data['products_in_cart_info']);
		// echo '</pre>';
		
		$data['products_in_cart'] = array_unique($data['products_in_cart']);
		// var_dump($data['products_in_cart']);

		//ARIEL FILTERS
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_email'] = $filter_email;
		$data['filter_product_name'] = $filter_product_name;
		$data['filter_model'] = $filter_model;
		$data['filter_option_value'] = $filter_option_value;
		
		$data['button_filter'] = $this->language->get('button_filter');
		$data['token'] = $this->session->data['token'];
		
		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/orders_in_details.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/orders_in_details.tpl', $data));
		}
	}
	
}
