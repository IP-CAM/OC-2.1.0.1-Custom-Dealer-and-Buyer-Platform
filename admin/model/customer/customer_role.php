<?php
class ModelCustomerCustomerRole extends Model {
	
	public function getCustomerRoles() {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_roles";

		$query = $this->db->query($sql);

		return $query->rows;
	}	
}