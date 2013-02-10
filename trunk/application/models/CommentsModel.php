<?php

class CommentsModel extends BaseModel {

	public function __construct() {
		parent::__construct();
	}

	public function insertComment($postID, $info) {
		// TODO: Validation
		$this->db->insert('INSERT INTO comments (name, postID, timestamp, comment) VALUES (:name, :postID, :timestamp, :comment)',
								array(':name' => $info['name'], ':postID' => $postID, ':timestamp' => time(), ':comment' => $info['comment']));
		print_r($info);
		header('Location: '. __URL_PATH . $info['redirect'] . '/comments');
	}

	public function getComments($postID) {
		$query = 'SELECT * FROM comments WHERE postID = :postID';
		return $this->db->select($query, array(':postID' => $postID));
	}
}
