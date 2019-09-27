<?php
class ModelStoragesStorages extends Model {
	public function addStorage($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "storages SET name = '" . $this->db->escape($data['name']) . "'");
	}

	public function editStorage($storage_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "storages SET name = '" . $this->db->escape($data['name']) . "' WHERE storage_id = '" . (int)$storage_id . "'");
	}

	public function deleteStorage($storage_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "storages WHERE storage_id = '" . (int)$storage_id . "'");
	}

	public function getStorage($storage_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "storages WHERE storage_id = '" . (int)$storage_id . "'");

		$user_group = array(
			'name'       => $query->row['name'],			
		);

		return $user_group;
	}

	public function getStorages($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "storages";

		$sql .= " ORDER BY name";

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

	public function getTotalStorages() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "storages");

		return $query->row['total'];
	}

	public function addPermission($user_group_id, $type, $route) {
		$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type][] = $route;

			$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}

	public function removePermission($user_group_id, $type, $route) {
		$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type] = array_diff($data[$type], array($route));

			$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}
}