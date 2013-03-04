<?php

class CommentsModel extends BaseModel {

	public function __construct() {
		parent::__construct();
	}

	public function insertComment($postID, $info) {
		// TODO: Validation
		$this->db->insert('INSERT INTO comments (name, postID, timestamp, comment) VALUES (:name, :postID, :timestamp, :comment)',
								array(':name' => $info['name'], ':postID' => $postID, ':timestamp' => time(), ':comment' => $info['comment']));
	}

	public function getComments($postID) {
		$query = 'SELECT * FROM comments WHERE postID = :postID';
		return $this->db->select($query, array(':postID' => $postID));
	}
	public function getComment($commentID) {
		$query = 'SELECT * FROM comments WHERE commentID = :commentID';
		return $this->db->select($query, array(':commentID' => $commentID));
	}

	public function getFlagged() {
		if(Auth::checkAdmin()) {
			$query = 'SELECT commentReports.*, comments.*, blogPosts.postURL, users.userName as blogName FROM commentReports LEFT JOIN comments ON commentReports.commentID = comments.commentID LEFT JOIN blogPosts ON comments.postID = blogPosts.postID LEFT JOIN users ON blogPosts.userID = users.UserID WHERE comments.deleted = 0';
			return $this->db->select($query);
		} else {
			throw new Exception('Admin function. Login as an admin or GTFO');
		}	
	}
	public function delete($commentID) {
		$query = 'UPDATE comments SET deleted = 1 WHERE commentID = :commentID';
		$result = $this->db->insert($query, array(':commentID' => $commentID));
		return $result;
	}
}
