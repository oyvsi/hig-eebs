<?php

class BlogpostModel extends BaseModel {
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
		$ipAddress = BlogModel::getRealIpAddress();
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

	public function createPost($data, $userID) {
		// Do some validation shit and check for XSS
		$validate = new ValidateForm($data);
		$validate->setRequired(array('title', 'postIngress', 'postText'));
		$validate->setMinLength(array('title' => 3, 'postIngress' => 5, 'postText' => 10));
		if($validate->check() === false) {
			$errors = implode('<br />', $validate->getErrors());
			throw new Exception($errors);
		} 

		$title = $data['title'];
		$ingress = $data['postIngress'];
		$contents = $data['postText'];
		$url = Helpers::makePostUrl($title);
		$notUnique = $this->db->select('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $userID, ':postURL' => $url));
		if(count($notUnique)) {
			$url .= '_';
		}
		$query = 'INSERT INTO blogPosts (userID, postTitle, postURL, timestamp, postText, postIngress) VALUES (:userID, :postTitle, :postURL, :timestamp, :postText, :postIngress)';
		$this->db->insert($query, array(':userID' => $userID, ':postTitle' => $title, ':postURL' => $url, ':timestamp' => time(), ':postText' => $contents, ':postIngress' => $ingress));

		return $url;
	}
	
	public function deletePost($postID) {
		$query = 'DELETE FROM blogPosts WHERE postID = :postID';
		$result = $this->db->insert($query, array(':postID' => $postID));
		//if ($result == false){
		//	throw new Exception('Delete failed');
		//}
	}
}
