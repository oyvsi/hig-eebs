<?php

class IndexModel extends BaseModel {
	private $baseQuery = 'SELECT LEFT(blogPosts.postText, 60) as postSummary, blogPosts.*, users.userName, 
								COUNT(DISTINCT postViews.viewID) as readCount, COUNT(DISTINCT comments.commentID) as noComments FROM blogPosts 
					 			LEFT JOIN postViews ON postViews.postID = blogPosts.postID 
					 			LEFT JOIN users ON blogPosts.userID = users.userID
					 			LEFT JOIN comments ON comments.PostID = blogPosts.postID';
	
	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
		return $this->db->select($this->baseQuery . ' GROUP BY blogPosts.postID ORDER BY timestamp DESC LIMIT :limit', array(':limit'=> 10));
	}
	public function getPostsbyUser($userID) {
		return $this->db->select($this->baseQuery . ' WHERE blogPosts.userID = :userID GROUP BY blogPosts.postID', array(':userID' => $userID));
	}

	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' WHERE blogPosts.timestamp BETWEEN :startTime AND :endTime
					 					GROUP BY blogPosts.postID
					 					ORDER BY readCount DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function mostCommented($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' WHERE blogPosts.timestamp BETWEEN :startTime AND :endTime
									  GROUP BY blogPosts.postID
									  ORDER BY noComments DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}
}
