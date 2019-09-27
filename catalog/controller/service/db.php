<?php
class ControllerServiceDb extends Controller { 
      
	public function index() {
		global $sdb, $secret_key, $returnObj;
		$this->load->helper('nusoap/nusoap');
		$this->load->model('setting/setting');
		$sdb = $this->db;
		$secret_key='';
		
		$data =  $this->model_setting_setting->getSetting('servicedb');
		if (isset($data['servicedb_secret_key'])) {
			$secret_key = $data['servicedb_secret_key'];
		}
		
		$returnObj= new stdClass;
		$returnObj->secretStatus = false;
		
		// Create the server instance
		$server = new soap_server();
		// Initialize WSDL support
		$server->configureWSDL('dbServices', 'urn:dbServices');
		// Register the method to expose
		
		
		$server->register('select',                   // method name
				array('sql' => 'xsd:string', 'secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#query',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Run SELECT QUERY to Opencart Database'                // documentation
		);
		$server->register('query',                   // method name
				array('sql' => 'xsd:string', 'secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#query',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Run INESRT/UPDATE/DELETE Query to Opencart Database'                // documentation
		);
		$server->register('lastId',                   // method name
				array('secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#lastId',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Return Last Inserted ID'               // documentation
		);
		$server->register('countAffected',                   // method name
				array('secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#countAffected',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Return count of affected rows'               // documentation
		);
		$server->register('getTablePrefix',                   // method name
				array('secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#getTablePrefix',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Return Prefix'               // documentation
		);
		$server->register('getDatabaseType',                   // method name
				array('secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#getDatabaseType',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Return type of database'               // documentation
		);
		$server->register('getOCVersion',                   // method name
				array('secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#getOCVersion',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Return version of OC'               // documentation
		);
		$server->register('getErrorLog',                   // method name
				array('secret'=>'xsd:string'),        // input parameters
				array('return' => 'xsd:string'),      // output parameters
				'urn:dbServices',                      // namespace
				'urn:dbServices#getDatabaseType',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'Return type of database'               // documentation
		);
		$server->register('addProductImage',                   // method name
				array('secret'=>'xsd:string',
				'productId'=>'xsd:integer',
				'imageName'=>'xsd:imageName',
				'imageData'=>'imageData',
				),        
				array('return' => 'xsd:string'),      
				'urn:dbServices',                      
				'urn:dbServices#addProductImage',       
				'rpc',                                
				'encoded',                            
				'Upload Product Image'              
		);
	
	
		$postdata = file_get_contents("php://input");
		$server->service($postdata);  
		 
	}
	
}
	function select($sql, $secret) {
		global $sdb, $secret_key, $returnObj;
		
		if($secret == $secret_key && !empty($secret_key)) {
			$sql = base64_decode($sql);
			$escaped = $sdb->escape($sql);
			//$result = $sdb->query($escaped);
			$result = $sdb->query($sql);
			$returnObj->secretStatus = true;
			$returnObj->result = $result->rows;
		} else {
			$returnObj->result = array();
		}
		return json_encode($returnObj);
	}
	
	function query($sql, $secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$sql = base64_decode($sql);
			$escaped = $sdb->escape($sql);
			//$result = $sdb->query($escaped);
			//$result = $sdb->query($sql);
			$qArr = explode("|", $sql);
			foreach ($qArr as $qArrItem) {$sdb->query($qArrItem);}
			$returnObj->secretStatus = true;
			$returnObj->result = true;
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}
	
	function lastId($secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$result = $sdb->getLastId();
			$returnObj->secretStatus = true;
			$returnObj->result = $result;
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}
	
	function countAffected($secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$result = $sdb->countAffected();
			$returnObj->secretStatus = true;
			$returnObj->result = $result;
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}
	
	function getTablePrefix($secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$returnObj->secretStatus = true;
			$returnObj->result = DB_PREFIX;
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}
	
	function getDatabaseType($secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$returnObj->secretStatus = true;
			$returnObj->result = DB_DRIVER;
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}
	function getOCversion($secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$returnObj->secretStatus = true;
			$returnObj->result = VERSION;
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}	
	function getErrorLog($secret) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
			$returnObj->secretStatus = true;
			$returnObj->result = file_get_contents(DIR_LOGS."/error.txt");
		} else {
			$returnObj->result = false;
		}
		return json_encode($returnObj);
	}
	
	function addProductImage($secret, $productId, $imageName, $imageData) {
		global $sdb, $secret_key, $returnObj;
		if($secret == $secret_key && !empty($secret_key)) {
				$returnObj->secretStatus = true;
				$productId = intval($productId);
				$imageName = base64_decode($imageName);
				$imageData = base64_decode($imageData);
				//return json_encode($imageData);
				
				
				if(empty($imageName)) {
					$returnObj->result = false;
					$returnObj->resultError = "Невалидно име на снимка!";
				}
				if(!$productId) {
					$returnObj->result = false;
					$returnObj->resultError = "Невалидно ИД на продукт";
				}
				//create dirs
				$directories = explode("/",$imageName);
				if (count($directories)>1) {
					array_pop($directories);
					$path = '';
					foreach ($directories as $directory) {
						$path = $path . '/' . $directory;
						
						if (!file_exists(DIR_IMAGE . $path)) {
							@mkdir(DIR_IMAGE . $path, 0777);
						}		
					}
				}
			
				$f = fopen(DIR_IMAGE . $imageName, 'w');
				fwrite($f, $imageData);
				fclose($f);
			
				//check if is valid image;
				$info = @getimagesize(DIR_IMAGE . $imageName);
				if($info === false) {
					$returnObj->result = false;
					$returnObj->resultError = "Невалидeн формат на снимката";
					@unlink(DIR_IMAGE . $imageName);
				} else {
					$sdb->query("UPDATE ".DB_PREFIX."product SET image = '".
					$sdb->escape($imageName)."' WHERE product_id = '$productId'");
					
					$result = $sdb->query("SELECT image FROM ".
					DB_PREFIX."product_image WHERE product_id = '$productId'");
					$productImages = array();
					foreach ($result->rows as $row) {
						$productImages[] = $row['image'];
					}
					$productImages[] = $imageName;
					// delete all records
					$sdb->query("DELETE FROM ".
					DB_PREFIX."product_image WHERE product_id = '$productId'");
					foreach (array_unique($productImages) as $imgName) {
						$sdb->query("INSERT INTO ".DB_PREFIX."product_image 
						SET image = '".$sdb->escape($imgName)."' , product_id = '$productId'");
					}
					
					$returnObj->result = true;
					$returnObj->resultError = "";
				}
			
		} else {
			$returnObj->result = false;
			$returnObj->resultError = "Невалиден секретен код";
		}
		return json_encode($returnObj);
	}
	
 ?>