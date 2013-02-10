<?php
abstract class BaseModel {
	protected $db;

	public function __construct() {
		$this->db = new Database(__DB_TYPE, __DB_HOST, __DB_NAME, __DB_USER, __DB_PASS);
	}
	protected function setInfo($assArray) {
		foreach($assArray as $key => $value) {
			$this->$key = $value;
		}	
	}
	

}
