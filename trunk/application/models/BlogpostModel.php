<?php

class BlogpostModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	

	public function getPost($blogName, $postURL) {
		$userID = $this->db->select('SELECT userID from users WHERE userName = :userName', array(':userName' => $blogName));
		$query = 'SELECT blogPosts.*, (SELECT COUNT(comments.commentID) FROM comments WHERE comments.postID = blogPosts.postID AND comments.deleted = 0) as noComments FROM blogPosts 
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

	public function createPost($data, $userID, $update = false) {
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
		if ($update !== false) {
			echo "her er eg!!";
			$titleEdited = $this->db->select('SELECT postTitle, userID, postURL FROM blogPosts WHERE postID = :postID', array(':postID' => $userID));
			if ($titleEdited['postTitle'] == $title);
			else {
				$notUnique = $this->db->select('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $titleEdited['userID'], ':postURL' => $titleEdited['postURL']));
				if(count($notUnique)) {
					$url .= '_';
				}
			}
			$query = 'UPDATE blogPosts SET postTitle = :postTitle, postURL = :postURL, postText = :postText, postIngress = :postIngress WHERE postID = :postID';
			$this->db->insert($query, array(':postTitle' => $title, ':postURL' => $url, ':postText' => $contents, ':postIngress' => $ingress, ':postID' => $userID));
			
		} else {
			$notUnique = $this->db->select('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $userID, ':postURL' => $url));
			if(count($notUnique)) {
				$url .= '_';
			}
			$query = 'INSERT INTO blogPosts (userID, postTitle, postURL, timestamp, postText, postIngress) VALUES (:userID, :postTitle, :postURL, :timestamp, :postText, :postIngress)';
			$this->db->insert($query, array(':userID' => $userID, ':postTitle' => $title, ':postURL' => $url, ':timestamp' => time(), ':postText' => $contents, ':postIngress' => $ingress));
		}

		return $url;
	}
	
	public function deletePost($postID) {
		$find = "SELECT * FROM blogPosts WHERE postID = :postID";
		$found = $this->db->select($find, array(':postID' => $postID));
		if (count($found) != 0){
			$query = 'UPDATE blogPosts SET deleted = 1 WHERE postID = :postID';
			$this->db->insert($query, array(':postID' => $postID));
		} else {
			throw new Exception('Blogpost not found');
		}
	}
	
	public function getPostValues($postID) {
		$query = "SELECT * FROM blogPosts WHERE postID = :postID";
		$found = $this->db->select($query, array(':postID' => $postID));
		if (count($found != 0)) {
			return $found;
		} else {
			throw new Exception('Blogpost not found');
		}
		
	}
}
