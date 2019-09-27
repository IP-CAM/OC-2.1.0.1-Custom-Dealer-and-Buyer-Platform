<?php
class ControllerCatalogPerStorageProductPerStorage extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog_per_storage/product_per_storage');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog_per_storage/product_per_storage');

		$this->getList();
	}	

	protected function getList() {
		//ARIEL 
		$per_page = 20;
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}	

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
//ARIEL DB RSTAR - SF+DOBR
		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $per_page,
			// 'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $per_page
			// 'limit'           => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_catalog_per_storage_product_per_storage->getTotalProducts($filter_data);

		$results = $this->model_catalog_per_storage_product_per_storage->getProducts($filter_data);
	
		foreach ($results as $result) {	
			$data['product_options'] = [];
			$product_options = $this->model_catalog_per_storage_product_per_storage->getProductOptions($result['product_id']);

			$product_options_sf = $this->model_catalog_per_storage_product_per_storage->getSfProductOptions($result['product_id']);
			
			$product_options_dobr = $this->model_catalog_per_storage_product_per_storage->getDobrProductOptions($result['product_id']);	
			

			foreach ($product_options as $product_option) {
			// $option_id = $product_option['product_option_id'];
			$product_option_value_data = [];

			if (isset($product_option['product_option_value'])) {
				
				foreach ($product_option['product_option_value'] as $product_option_value) {
					//get sf product options
					$flag_sf = false;
					$flag_dobr = false;
					foreach($product_options_sf[0]['product_option_value'] as $posf){
						if( $posf['option_value_name'] == $product_option_value['option_value_name']){
							$quantity_sofia = $posf['quantity'];
							$flag_sf = true;
							break;
						}							
					}
						
					if( !$flag_sf ) { $quantity_sofia = 0; }
					
					//get dobr product options
					foreach($product_options_dobr[0]['product_option_value'] as $podobr){
						if( $podobr['option_value_name'] == $product_option_value['option_value_name']){
							$quantity_dobrich = $podobr['quantity'];
							$flag_dobr = true;
							break;
						}							
					}
						
					if( !$flag_dobr ) { $quantity_dobrich = 0; }

					$product_option_value_data[] = [
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity_total'          => $product_option_value['quantity'],
						'quantity_sofia'          => $quantity_sofia,
						'quantity_dobrich'        => $quantity_dobrich,
						'option_value_name'		 => $product_option_value['option_value_name']
					];
				}
			}

			$data['product_options'][] = [
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data			
			];
		}

		$data['products'][$result['product_id']] = array(
			'product_id' => $result['product_id'],
			'name'       => $result['name'],
			'model'      => $result['model'],
			'quantity'   => $result['quantity'],
			'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
			'options_data' => $data['product_options']
		);

	}

		// ARIEL SF PRODUCTS DATA
		$results_sf = $this->model_catalog_per_storage_product_per_storage->getSfProducts($filter_data);
		
		foreach ($results_sf as $result) {
			$data['products_sf'][$result['product_id']] = [
				'product_id' => $result['product_id'],				
				'name'       => $result['name'],
				'model'      => $result['model'],				
				'quantity'   => $result['quantity'],
				'options_data' => $data['product_options']			
			];
		}

		// ARIEL DOBR DB PRODUCTS DATA
		$results_dobr = $this->model_catalog_per_storage_product_per_storage->getDobrProducts($filter_data);
		
		foreach ($results_dobr as $result) {
			$data['products_dobr'][$result['product_id']] = [
				'product_id' => $result['product_id'],				
				'name'       => $result['name'],
				'model'      => $result['model'],				
				'quantity'   => $result['quantity']			
			];
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog_per_storage/product_per_storage', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog_per_storage/product_per_storage', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog_per_storage/product_per_storage', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog_per_storage/product_per_storage', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog_per_storage/product_per_storage', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// //ARIEL 
		// $per_page = 20; - at the beginning!!!
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $per_page;
		// $pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog_per_storage/product_per_storage', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $per_page) + 1 : 0, ((($page - 1) * $per_page) > ($product_total - $per_page)) ? $product_total : ((($page - 1) * $per_page) + $per_page), $product_total, ceil($product_total / $per_page));
		// $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog_per_storage/product_per_storage_list.tpl', $data));
	}
}
