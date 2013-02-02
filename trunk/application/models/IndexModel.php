<?php

class IndexModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
				return $this->db->select('SELECT blogPosts.*, users.userName from blogPosts LEFT JOIN users on blogPosts.userID = users.UserID ORDER BY timestamp LIMIT :limit', array(':limit'=> 10));
	}
	public function getPostsbyUser($userID) {
		return $this->db->select('SELECT blogPosts.*, users.userName FROM blogPosts LEFT JOIN users on blogPosts.userID = users.userID WHERE blogPosts.userID = :userID', array(':userID' => $userID));
	}

	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = 'SELECT blogPosts.*, users.userName, count(postViews.postID) as readCount FROM postViews 
					 LEFT JOIN blogPosts ON postViews.postID = blogPosts.postID 
					 LEFT JOIN users ON blogPosts.userID = users.userID
					 WHERE blogPosts.timestamp BETWEEN :startTime AND :endTime';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function mostCommented($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = 'SELECT blogPosts.*, users.userName, count(comments.commentID) as commentCount FROM comments 
			LEFT JOIN blogPosts ON comments.postID = blogPosts.postID 
			LEFT JOIN users ON blogPosts.userID = users.userID
			WHERE blogPosts.timestamp BETWEEN :startTime AND :endTime';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));

	}
}
