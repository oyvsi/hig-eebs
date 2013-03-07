<?php

class CommentsModel extends BaseModel {
	protected $commentFields = array('title' => array('view' => 'Comment', 'minLength' => 3, 'maxLength' => 100));

	/** 
	* constructor, sets up the class info.
	*/
	public function __construct() {
		parent::__construct();
	}

	/** 
	* fuction inserts comment into database.
	* @param array $info
	* @param int $postID
	*/
	public function insertComment($postID, $info) {
		$validate = new ValidateForm($data);
		$validate->setRequired($this->blogPostFields);	
		if($validate->check() === false) {
			$errors = implode('<br />', $validate->getErrors());
			throw new Exception($errors);
		}
		
		$this->db->insert('INSERT INTO comments (name, postID, timestamp, comment) VALUES (:name, :postID, :timestamp, :comment)',
								array(':name' => $info['name'], ':postID' => $postID, ':timestamp' => time(), ':comment' => $info['comment']));
	}

	/** 
	* fuction to get comments from the database that belongs to one post.
	* @param int $postID
	* @return array
	*/
	public function getComments($postID) {
		$query = 'SELECT * FROM comments WHERE postID = :postID';
		return $this->db->select($query, array(':postID' => $postID));
	}
	
	/** 
	* fuction to get a comment from the database with a given commentID.
	* @param int $commentID
	* @return array
	*/
	public function getComment($commentID) {
		$query = 'SELECT * FROM comments WHERE commentID = :commentID';
		return $this->db->select($query, array(':commentID' => $commentID));
	}

	/** 
	* fuction gets all flagged comments if you have admin rights. 
	* @return array
	*/
	public function getFlagged() {
		if(Auth::checkAdmin()) {
			$query = 'SELECT commentReports.*, comments.*, blogPosts.postURL, users.userName as blogName FROM commentReports LEFT JOIN comments ON commentReports.commentID = comments.commentID LEFT JOIN blogPosts ON comments.postID = blogPosts.postID LEFT JOIN users ON blogPosts.userID = users.UserID WHERE comments.deleted = 0';
			return $this->db->select($query);
		} else {
			throw new Exception('Admin function. Login as an admin or GTFO');
		}	
	}

	/** 
	* fuction where admin or comment owner can delete a comment.
	* @param int $commentID
	* @param int $userID
	* @return int
	*/
	public function delete($commentID, $userID) {
		$postOwner = $this->db->selectOne('SELECT comments.postID, blogPosts.userID FROM comments LEFT JOIN blogPosts on comments.postID = blogPosts.postID WHERE comments.commentID = :commentID AND blogPosts.userID = :userID', array(':commentID' => $commentID, ':userID' => $userID));
		if(Auth::checkAdmin() || $postOwner) {

			$query = 'UPDATE comments SET deleted = 1 WHERE commentID = :commentID';
			$result = 1;
			$result = $this->db->insert($query, array(':commentID' => $commentID));
			
			return $result;
		} else {
			throw new Exception('Admin/owner function. Login as an admin or the owner or GTFO!');
		}
	}
}
