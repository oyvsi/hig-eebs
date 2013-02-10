<?php

class BlogModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	
	
	/*
	 * No idea where this belongs...
	 */	
	public static function getRealIpAddress() {
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){  
			$ip=$_SERVER['HTTP_CLIENT_IP']; 

		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 

		} else { 
			$ip=$_SERVER['REMOTE_ADDR']; 
		}
		return $ip; 
	}
	public function getAllPosts($blogName) {
		return array("Post 1<br />",
			"Post 2<br />",
			"Post 3<br />",
			"Post 4<br />");
	}
}
