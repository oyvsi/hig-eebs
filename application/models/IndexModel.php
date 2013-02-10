<?php

class IndexModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
		return $this->db->select('SELECT LEFT(postText, 60) as postSummary, blogPosts.*, users.userName, COUNT(comments.commentID) as noComments
											FROM blogPosts 
											LEFT JOIN users on blogPosts.userID = users.UserID 
											LEFT JOIN comments on comments.postID = blogPosts.postID 
											GROUP BY blogPosts.postID
											ORDER BY timestamp DESC LIMIT :limit', 
											array(':limit'=> 10));
	}
	public function getPostsbyUser($userID) {
		return $this->db->select('SELECT LEFT(blogPosts.postText, 60) as postSummary, blogPosts.*, users.userName FROM blogPosts LEFT JOIN users on blogPosts.userID = users.userID WHERE blogPosts.userID = :userID', array(':userID' => $userID));
	}

	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = 'SELECT LEFT(blogPosts.postText, 60) as postSummary, blogPosts.*, users.userName, count(postViews.postID) as readCount FROM postViews 
					 LEFT JOIN blogPosts ON postViews.postID = blogPosts.postID 
					 LEFT JOIN users ON blogPosts.userID = users.userID
					 WHERE blogPosts.timestamp BETWEEN :startTime AND :endTime';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function mostCommented($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = 'SELECT LEFT(blogPosts.postText, 60) as postSummary, blogPosts.*, users.userName, count(comments.commentID) as noComments FROM blogPosts 
			LEFT JOIN comments ON comments.postID = blogPosts.postID 
			LEFT JOIN users ON blogPosts.userID = users.userID
			WHERE blogPosts.timestamp BETWEEN :startTime AND :endTime
			GROUP BY blogPosts.postID
			ORDER BY noComments DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));

	}
}
