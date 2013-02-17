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
	public function flagComment($commentID, $form) {
		$valid = new ValidateForm($form);
		$valid->setRequired(array('reportComment'));
		$valid->setMinLength(array('reportComment' => 5));
		if(Auth::CheckLogin() === false) {
			throw new Exception('Can\'t report comment when you\'re not logged in');
		}

		if($valid->check() === false) {
			$errors = implode('<br />', $valid->getErrors());
			throw new Exception($errors);
		}

		$query = 'INSERT INTO commentReports(commentID, userID, reportText, timestamp) VALUES(:commentID, :userID, :reportText, :timestamp)';
		$this->db->insert($query, array(':commentID' => $commentID, ':userID' => $_SESSION['userID'], ':reportText' => $form['reportComment'], ':timestamp' => time()));
	}
}
