<?php

class BlogModel extends BaseModel {
	public function __construct() {
		parent::__construct();
		echo "Here comes the Model";
	}	
	
	public function getPost($blogName, $postName) {
			return array("post X<br />");
		}
	public function getAllPosts($blogName) {
	return array("Post 1<br />",
						"Post 2<br />",
						"Post 3<br />",
						"Post 4<br />");
	
	}
}