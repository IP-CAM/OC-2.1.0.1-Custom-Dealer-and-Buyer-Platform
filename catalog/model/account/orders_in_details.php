<?php
class ModelAccountOrdersInDetails extends Model {
	public function getProductOrderHistory($product_data){
		// var_dump($product_data);
		// die;
		
		$data = [];
		$data['customer_id'] = (int)$this->customer->getId();
		$sql = "SELECT op.product_id, op.name, op.quantity, op.price, o.order_id, o.date_added FROM " . DB_PREFIX . "order_product op JOIN " . DB_PREFIX . "order o ON (op.order_id=o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND op.product_id = '" . (int)$product_data['product_id'] . "'";
		if( isset($product_data['order']) ){
			$order = $product_data['order'];
		} else {
			$order = 'o.order_id';
		}

		if( isset($product_data['sort']) ){
			$sort = $product_data['sort'];
		} else {
			$sort = 'DESC';
		}

		$sql .= " ORDER BY {$order} {$sort}";
		

		$query = $this->db->query($sql);
		$data['order_history'] = $query->rows;

		return $data;

	}
	public function getProducts($data){
		$product_query = $this->db->query("SELECT p.product_id, p.model, pd.name FROM " . DB_PREFIX . "product p JOIN " . DB_PREFIX . "product_description pd ON (p.product_id=pd.product_id) WHERE pd.name LIKE '%". $this->db->escape($data) ."%' OR p.model LIKE '". $this->db->escape($data) ."%'");
		return $product_query->rows;
	}
	//ARIEL THIS FUNCTION SHOULD RETRIEVE DATA FROM CORRESPONDING DB DEPENDING ON STORAGE LOGGED IN
	public function getProductAllCartOptions($product_id){
		if( isset($this->session->data['storage_id']) ){

			if( $this->session->data['storage_id'] == 1 ){
				$db = 'sfdb';
			} elseif( $this->session->data['storage_id'] == 2 ){
				$db = 'dobrdb';
			} else {
				$db = 'sfdb';
			}
		}
		
		$product_data = array();

		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id='" . (int)$product_id . "'");

		
		foreach ($cart_query->rows as $cart) {
			$stock = true;

			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;

				$option_data = array();

				foreach (json_decode($cart['option']) as $product_option_id => $value) {
					$option_query = $this->$db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
							$option_value_query = $this->$db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									//check for dealer`s price in cart table
									//if dealer`s price is present - get it, else get default product`s price
									'price'                   => $option_value_query->row['price'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
							foreach ($value as $product_option_value_id) {
								$option_value_query = $this->$db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							}
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'value'                   => $value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				}

				$price = $product_query->row['price'];

				// Product Discounts
				$discount_quantity = 0;

				$cart_2_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

				foreach ($cart_2_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				}

				// Product Specials
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}
				
				//ARIEL DEALER PRICE
				if( $cart['dealer_price'] != 0){
					$price = $cart['dealer_price'];
				}

				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'product_id'      => $product_query->row['product_id'],
					'name'            => $product_query->row['name'],
					// 'model'           => $product_query->row['model'],
					// 'shipping'        => $product_query->row['shipping'],
					// 'image'           => $product_query->row['image'],
					'option'          => $option_data,
					// 'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					// 'minimum'         => $product_query->row['minimum'],
					// 'subtract'        => $product_query->row['subtract'],
					// 'stock'           => $stock,
					'price'           => ($price + $option_price),
					// 'total'           => ($price + $option_price) * $cart['quantity'],
					// 'reward'          => $reward * $cart['quantity'],
					// 'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					// 'tax_class_id'    => $product_query->row['tax_class_id'],
					// 'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					// 'weight_class_id' => $product_query->row['weight_class_id'],
					// 'length'          => $product_query->row['length'],
					// 'width'           => $product_query->row['width'],
					// 'height'          => $product_query->row['height'],
					// 'length_class_id' => $product_query->row['length_class_id'],
					// 'recurring'       => $recurring
				);
			} else {
				$this->remove($cart['cart_id']);
			}
		}
		// var_dump($product_data);
		// die;
		return $product_data;
	}
	public function getProductCartOptions($product_id, $options){
		//depending on storage id
		$option_data = [];

		foreach( $options as $key=>$value){
			// $sql = ( "SELECT pov.product_option_value_id, pov.product_option_id, od.option_id, od.name AS option_name, ovd.option_value_id, ovd.name AS option_value_name FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_description` od ON (pov.option_id=od.option_id) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (pov.option_value_id=ovd.option_value_id) WHERE pov.product_option_id={$key} AND pov.product_option_value_id={$value} AND pov.product_id={$product_id}");
			// var_dump($sql);
			// die;
			$option_query = $this->db->query( "SELECT pov.product_option_value_id, pov.product_option_id, od.option_id, od.name AS option_name, ovd.option_value_id, ovd.name AS option_value_name FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_description` od ON (pov.option_id=od.option_id) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (pov.option_value_id=ovd.option_value_id) WHERE pov.product_option_id={$key} AND pov.product_option_value_id={$value} AND pov.product_id={$product_id}");
			$option_data[] = [	
								'option_name'       => $option_query->row['option_name'],
								'option_value_name'   => $option_query->row['option_value_name'],
								];
		}

		return $option_data;
	}
	
	public function getOrders($data = array()) {		

		$sql = "SELECT o.order_id, o.firstname, o.lastname, o.email, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value, op.product_id, op.name as product_name, op.model, op.quantity, op.price, op.total, op.order_product_id, op2.product_option_id, op2.product_option_value_id, op2.name as option_name, op2.value, op2.type, p.status FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) LEFT JOIN " . DB_PREFIX . "order_option op2 ON (op.order_product_id = op2.order_product_id) JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') ."' ";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = "o.order_id = " . $this->db->escape((int)$data['filter_order_id']);
		}

		if (!empty($data['filter_date_added'])) {
			// var_dump($data['filter_date_added']);
			$implode[] = "o.date_added LIKE '" . $this->db->escape($data['filter_date_added']) ."%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "o.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_product_name'])) {
			$implode[] = "op.name  LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "op.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_option_value'])) {
			$implode[] = "op2.value LIKE '" . $this->db->escape($data['filter_option_value']) . "%'";
		}

				
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'o.order_id',
			'o,email',
			'o.date_added',
			'op.name',
			'op.model',
			'op2.value'			
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getOrdersTotal($data = array()) {		

		$sql = "SELECT COUNT(o.order_id) as total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) LEFT JOIN " . DB_PREFIX . "order_option op2 ON (op.order_product_id = op2.order_product_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') ."' ";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = "o.order_id = " . $this->db->escape((int)$data['filter_order_id']);
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "o.date_added = " . $this->db->escape($data['filter_date_added']);
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "o.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_product_name'])) {
			$implode[] = "op.name  LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "op.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_option_value'])) {
			$implode[] = "op2.value LIKE '" . $this->db->escape($data['filter_option_value']) . "%'";
		}

				
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'o.order_id',
			'o,email',
			'o.date_added',
			'product_name',
			'op.model',
			'op2.value'			
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}		
		
		$query = $this->db->query($sql);

		return $query->row['total'];
		
	}
	public function getOrderProduct($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	// public function getOrderProducts($order_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

	// 	return $query->rows;
	// }

	// public function getOrderOptions($order_id, $order_product_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

	// 	return $query->rows;
	// }

	// public function getOrderVouchers($order_id) {
	// 	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

	// 	return $query->rows;
	// }

	// public function getOrderTotals($order_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

	// 	return $query->rows;
	// }

	// public function getOrderHistories($order_id) {
	// 	$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

	// 	return $query->rows;
	// }

	public function getTotalOrders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row['total'];
	}

	// public function getTotalOrderProductsByOrderId($order_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getTotalOrderVouchersByOrderId($order_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

	// 	return $query->row['total'];
	// }
}