<?php

class IndexModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
				return $this->db->select("SELECT * FROM blogPosts ORDER BY timestamp LIMIT :limit", array(":limit"=> 10));
	}
}
