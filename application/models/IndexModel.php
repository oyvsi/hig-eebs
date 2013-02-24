<?php

class IndexModel extends BaseModel {
	private $baseQuery = 'SELECT blogPosts.*, users.userName, 
								COUNT(DISTINCT postViews.viewID) as readCount, (SELECT COUNT(comments.commentID) FROM comments WHERE comments.PostID = blogPosts.postID AND comments.deleted = 0) as noComments FROM blogPosts 
					 			LEFT JOIN postViews ON postViews.postID = blogPosts.postID 
					 			LEFT JOIN users ON blogPosts.userID = users.userID
								WHERE blogPosts.deleted = 0';
	
	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
		return $this->db->select($this->baseQuery . ' GROUP BY blogPosts.postID ORDER BY timestamp DESC LIMIT :limit', array(':limit'=> 10));
	}
	public function getPostsByUser($userID) {
		return $this->db->select($this->baseQuery . ' AND blogPosts.userID = :userID GROUP BY blogPosts.postID ORDER BY timestamp DESC', array(':userID' => $userID));
	}

	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' AND blogPosts.timestamp BETWEEN :startTime AND :endTime
					 					GROUP BY blogPosts.postID
					 					ORDER BY readCount DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function mostCommented($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' AND blogPosts.timestamp BETWEEN :startTime AND :endTime
									  GROUP BY blogPosts.postID
									  ORDER BY noComments DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}
}
