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

		$url = $this->makePostUrl($data['title']);
		$title = $data['title'];
		$contents = $data['postText'];

		echo "Url should be $url <br>";
		echo "Should insert blogPost with...<br>Title: $title <br>Text: $contents";
		$query = 'INSERT INTO blogPosts (userID, postTitle, postURL, timestamp, postText) VALUES (:userID, :postTitle, :postURL, :timestamp, :postText)';
		$this->db->insert($query, array(':userID' => $userID, ':postTitle' => $title, ':postURL' => $url, ':timestamp' => time(), ':postText' => $contents));
	}

	/**
	 * Creates pretty URL from string
	 * Covertes spaces to underscore and replaces NO-specific characters
	 * everything but alphanumeric is stripped after replace
	 * 
	 * @param String $title the string to convert
	 */
	public function makePostUrl($title) {
		$title = strtolower($title);
		$replace = array(' ' => '_', 'æ' => 'ae', 'ø' => 'oe', 'å' => 'aa');
		foreach($replace as $char => $sub) {
			$title = str_replace($char, $sub, $title);
		}
		$title = preg_replace('/[^0-9a-z_]/', "", $title);

		return $title;
	}
}
