<?php

class IndexModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
				return $this->db->select('SELECT * FROM blogPosts ORDER BY timestamp LIMIT :limit', array(':limit'=> 10));
	}

	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = 'SELECT blogPosts.*, count(postViews.postID) as readCount FROM postViews 
					 LEFT JOIN blogPosts ON postViews.postID = blogPosts.postID 
					 AND postViews.timestamp BETWEEN :startTime AND :endTime';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function mostCommented($days) {
	
	}
}
