<?php

class BlogModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	
	
	public function updateViewCount($userID) {
		$ipAddress = Helpers::getRealIpAddress();
		$reReadLimit = 24;
		$limitTime = strtotime('-' . $reReadLimit . ' hours');
		$check = $this->db->selectOne('SELECT viewID FROM blogViews 
			WHERE userID = :userID AND ipAddress = :ipAddress AND timestamp BETWEEN :startTime AND :stopTime',
			array(':userID' => $userID, ':ipAddress' => $ipAddress, ':startTime' => $limitTime, ':stopTime' => time()));
 

		// User has not seen this post yet, or not since timelimit. Insert a post view.
		if($check === false) {
			$query = 'INSERT INTO blogViews(userID, timestamp, ipAddress) VALUES (:userID, :timestamp, :ipAddress)';
			$values = array(':userID' => $userID, ':timestamp' => time(), 'ipAddress' => $ipAddress); 
			$this->db->insert($query, $values);
		}
	}
}
