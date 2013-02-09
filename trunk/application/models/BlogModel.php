<?php

class BlogModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	

	public function test() {
		$a = $this->db->select('SELECT * FROM bruker');
		print_r($a);
	}	
	public function getPost($blogName, $postURL) {
		$userID = $this->db->select('SELECT userID from users WHERE userName = :userName', array(':userName' => $blogName));
		$query = 'SELECT * FROM blogPosts WHERE postURL = :postURL AND userID = :userID';
		$result = $this->db->select($query, array('postURL' => $postURL, 'userID' => $userID[0]['userID']));
		$result[0]['userName'] = $blogName;
		return $result;
	}

	public function getAllPosts($blogName) {
		return array("Post 1<br />",
			"Post 2<br />",
			"Post 3<br />",
			"Post 4<br />");

	}
}
