<?php

class ControllerModuleServicedb extends Controller{ 
	private $error = array();

    public function index(){
		$this->load->language('module/servicedb');
		
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('servicedb', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}	
		
		//$data =  $this->model_setting_setting->getSetting('servicedb');

		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['secret_kay_label'] = $this->language->get('secret_kay_label');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['error_secret_key'])) {
			$data['error_secret_key'] = $this->error['error_secret_key'];
		} else {
			$data['error_secret_key'] = '';
		}		
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/servicedb', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['action'] = $this->url->link('module/servicedb', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		
		//if (isset($data['servicedb_secret_key'])) {
			//$data['servicedb_secret_key'] = $data['servicedb_secret_key'];
		//} else {
			// $data['servicedb_secret_key'] = '';
		//}
		
		if (isset($this->request->post['servicedb_secret_key'])) {
			$data['servicedb_secret_key'] = $this->request->post['servicedb_secret_key'];
		} else {
			$data['servicedb_secret_key'] = $this->config->get('servicedb_secret_key');
		}		
		
		
        //$this->template = 'module/servicedb.tpl';
        //if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'module/servicedb.tpl')) {
            //$this->template = $this->config->get('config_template') . 'module/servicedb.tpl';
        //}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('module/servicedb.tpl', $data));
        //$this->response->setOutput($this->render());
    }
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/servicedb')) {
			$this->error['warning'] = $this->language->get('error_permission');
			//return false;
			return !$this->error;
		}
		
		$servicedb_secret_key = $this->request->post['servicedb_secret_key'];
		if(strlen($servicedb_secret_key)<16) {
			$data['error_secret_key'] = $this->language->get('error_secret_key_len');
			$this->error['error_secret_key'] = $this->language->get('error_secret_key_len');
			//return false;
			return !$this->error;
		}

		return true;
	}
}
?>