<?php

class BlogpostModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	

	public function getPostFromID($postID) {
		$query = "SELECT * FROM blogPosts WHERE postID = :postID";
		$found = $this->db->selectOne($query, array(':postID' => $postID));
		if ($found) {
			return $found;
		} else {
			throw new Exception('Blogpost not found');
		}

	}
	public function getPostFromURL($blogName, $postURL) {
		$userID = $this->db->selectOne('SELECT userID from users WHERE userName = :userName', array(':userName' => $blogName));
		if($userID === false) {
			throw new Exception('Error. No such user');
		}

		$query = 'SELECT blogPosts.*, (SELECT COUNT(comments.commentID) FROM comments WHERE comments.postID = blogPosts.postID AND comments.deleted = 0) as noComments FROM blogPosts 
			WHERE blogPosts.postURL = :postURL AND blogPosts.userID = :userID';
		$result = $this->db->selectOne($query, array('postURL' => $postURL, 'userID' => $userID['userID']));
		if($result === false) {
			throw new Exception('Unable to get blogpost');
		}

		$result['userName'] = $blogName;
		$this->setInfo($result);

		return $result;
	}

	public function updatePostViewCount($postID) {
		// Maybe do this with cookies
		$ipAddress = BlogModel::getRealIpAddress();
		$reReadLimit = 24;
		$limitTime = strtotime('-' . $reReadLimit . ' hours');
		$check = $this->db->selectOne('SELECT viewID FROM postViews 
			WHERE postID = :postID AND ipAddress = :ipAddress AND timestamp BETWEEN :startTime AND :stopTime',
			array(':postID' => $postID, ':ipAddress' => $ipAddress, ':startTime' => $limitTime, ':stopTime' => time()));

		// User has not seen this post yet, or not since timelimit. Insert a post view.
		if($check === false) {
			$query = 'INSERT INTO postViews(postID, timestamp, ipAddress) VALUES (:postID, :timestamp, :ipAddress)';
			$values = array(':postID' => $postID, ':timestamp' => time(), 'ipAddress' => $_SERVER['REMOTE_ADDR']); 
			$this->db->insert($query, $values);
		}
	}

	public function createPost($data, $userID, $updatePostID = false) {
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

		if ($updatePostID !== false) { // Updating existing post
			$post = $this->getPostFromID($updatePostID);
			if ($post['postTitle'] != $title) {
				$notUnique = $this->db->selectOne('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $post['userID'], ':postURL' => $url));
				if($notUnique) {
					$url .= '_';
				}
			}
			$query = 'UPDATE blogPosts SET postTitle = :postTitle, postURL = :postURL, postText = :postText, postIngress = :postIngress WHERE postID = :postID';
			$this->db->insert($query, array(':postTitle' => $title, ':postURL' => $url, ':postText' => $contents, ':postIngress' => $ingress, ':postID' => $updatePostID));

		} else { // Inserting a new post
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


	public function flag($postID, $form) {
		$valid = new ValidateForm($form);
		$valid->setRequired(array('reportText'));
		$valid->setMinLength(array('reportText' => 5));
		if(Auth::CheckLogin() === false) {
			throw new Exception('Can\'t report blog post when you\'re not logged in');
		}

		if($valid->check() === false) {
			$errors = implode('<br />', $valid->getErrors());
			throw new Exception($errors);
		}

		$query = 'INSERT INTO blogPostReports(postID, userID, reportText, timestamp) VALUES(:postID, :userID, :reportText, :timestamp)';
		$this->db->insert($query, array(':postID' => $postID, ':userID' => $_SESSION['userID'], ':reportText' => $form['reportText'], ':timestamp' => time()));
	}

	public function getFlagged() {
		if(Auth::checkAdmin()) {
			$query = 'SELECT reportText, blogPosts.PostURL, users.userName as postAuthor, (SELECT userName FROM users WHERE userID = blogPostReports.userID) as reportAuthor FROM blogPostReports LEFT JOIN blogPosts ON blogPostReports.postID = blogPosts.postID LEFT JOIN users ON blogPosts.userID = users.userID WHERE blogPosts.deleted = 0;';
			return $this->db->select($query);
		} else {
			throw new Exception('Admin function. Login as an admin or GTFO');
		}	
	}

}
