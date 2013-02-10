<?php

class BlogModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	

	public function getPost($blogName, $postURL) {
		$userID = $this->db->select('SELECT userID from users WHERE userName = :userName', array(':userName' => $blogName));
		$query = 'SELECT blogPosts.*, count(comments.commentID) as noComments FROM blogPosts 
			LEFT JOIN comments ON comments.postID = blogPosts.postID	
			WHERE blogPosts.postURL = :postURL AND blogPosts.userID = :userID';
		$result = $this->db->select($query, array('postURL' => $postURL, 'userID' => $userID[0]['userID']));
		$result[0]['userName'] = $blogName;
		$this->setInfo($result[0]);

		return $result;
	}
	public function updatePostViewCount($postID) {
		// Maybe do this with cookies
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$reReadLimit = 24;
		$limitTime = strtotime('-' . $reReadLimit . ' hours');
		$check = $this->db->select('SELECT viewID FROM postViews 
											WHERE postID = :postID AND ipAddress = :ipAddress AND timestamp BETWEEN :startTime AND :stopTime',
		array(':postID' => $postID, ':ipAddress' => $ipAddress, ':startTime' => $limitTime, ':stopTime' => time()));

		 // User has not seen this post yet, or not since timelimit. Insert a post view.
		if(count($check) == 0) {
			$query = 'INSERT INTO postViews(postID, timestamp, ipAddress) VALUES (:postID, :timestamp, :ipAddress)';
			$values = array(':postID' => $postID, ':timestamp' => time(), 'ipAddress' => $_SERVER['REMOTE_ADDR']); 
			$this->db->insert($query, $values);
		}
	}

	public function getAllPosts($blogName) {
		return array("Post 1<br />",
			"Post 2<br />",
			"Post 3<br />",
			"Post 4<br />");

	}
}
