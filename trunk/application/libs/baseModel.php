<?php

abstract class baseModel {
	protected $db;
	
	function __construct() {
		$this->db = new Database(__DB_TYPE, __DB_HOST, __DB_NAME, __DB_USER, __DB_PASS);
	}
}

