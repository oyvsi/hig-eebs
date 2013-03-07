<?php
class BlogpostModel extends BaseModel {
	protected $blogPostFields =  array('title' => array('view' => 'Title', 'minLength' => 3, 'maxLength' => 100),		
											   'postIngress' => array('view' => 'Ingress', 'minLength' => 3, 'maxLength' => 100),		
												'postText' => array('view' => 'Post text', 'minLength' => 30, 'maxLength' => 20000));

	/**
	* constructur. sets up initial info in class.
	* 
	* 
	*/
	public function __construct() {
		parent::__construct();
	}	

	/**
	* Function returns one object from blogposts table. 
	* @param int $postID
	* @return array
	*/
	public function getPostFromID($postID) {
		$query = "SELECT * FROM blogPosts WHERE postID = :postID";
		$found = $this->db->selectOne($query, array(':postID' => $postID));
		if ($found) {
			return $found;
		} else {
			throw new Exception('Blogpost not found');
		}

	}

	/**
	* Function returns a blogpost based on blogName and URL.
	* @param string $blogName
	* @param string $postURL
	* @return array
	*/
	public function getPostFromURL($blogName, $postURL) {
		$userInfo = $this->db->selectOne('SELECT userID, theme, backgroundID from users WHERE userName = :userName', array(':userName' => $blogName));
		if($userInfo === false) {
			throw new Exception('Error. No such user');
		}

		$query = 'SELECT blogPosts.*, (SELECT COUNT(comments.commentID) FROM comments WHERE comments.postID = blogPosts.postID AND comments.deleted = 0) as noComments FROM blogPosts 
			WHERE blogPosts.postURL = :postURL AND blogPosts.userID = :userID';
		$result = $this->db->selectOne($query, array('postURL' => $postURL, 'userID' => $userInfo['userID']));
		if($result === false) {
			throw new Exception('Unable to get blogpost');
		}

		//gets background profile url if any.
		if($userInfo['backgroundID'] != null) {
			$sql = 'SELECT * FROM pictures WHERE pictureID = :backgroundID';
			$pic = $this->db->selectOne($sql, array('backgroundID' => $userInfo['backgroundID']));
			$result['backgroundPicture'] = $pic['url'];

			// set this variable to load default background. blablab O-ALF
		} else {
			$result['backgroundPicture'] = '';
		}


		$result['theme'] = $userInfo['theme'];

		$result['userName'] = $blogName;
		$this->setInfo($result);

		return $result;
	}

	/**
	* Function creates or updates a blogpost and returns the URL to the new (updated) post.
	* @param array $data
	* @param int $userID
	* @param bool $updatePostID 
	* @return string
	*/
	public function createPost($data, $userID, $updatePostID = false) {
		// Do some validation shit and check for XSS
		$validate = new ValidateForm($data);
		$validate->setRequired($this->blogPostFields);	
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
				do {
					$notUnique = $this->db->selectOne('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $post['userID'], ':postURL' => $url));
					if($notUnique) {
						$url .= '_';
					} 
				} while($notUnique);
			}
			$query = 'UPDATE blogPosts SET postTitle = :postTitle, postURL = :postURL, postText = :postText, postIngress = :postIngress WHERE postID = :postID';
			$this->db->insert($query, array(':postTitle' => $title, ':postURL' => $url, ':postText' => $contents, ':postIngress' => $ingress, ':postID' => $updatePostID));

		} else { // Inserting a new post
			do {
				$notUnique = $this->db->selectOne('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $userID, ':postURL' => $url));
				if($notUnique) {
					$url .= '_';
				}
			} while($notUnique);
			$query = 'INSERT INTO blogPosts (userID, postTitle, postURL, timestamp, postText, postIngress) VALUES (:userID, :postTitle, :postURL, :timestamp, :postText, :postIngress)';
			$this->db->insert($query, array(':userID' => $userID, ':postTitle' => $title, ':postURL' => $url, ':timestamp' => time(), ':postText' => $contents, ':postIngress' => $ingress));
		}

		return $url;
	}

	/**
	* Function deletes a blogpost based on user and url.
	* post is not removed from database put is set to inactive
	* @param string $username
	* @param string $postURL
	*/
	public function deletePost($userName, $postURL) {
		$find = "SELECT * FROM blogPosts LEFT JOIN users ON users.userID = blogPosts.userID WHERE postURL = :postURL AND userName = :userName AND deleted = 0";
		$post = $this->db->selectOne($find, array(':postURL' => $postURL, ':userName' => $userName));
		if (count($post) != 0){
			$query = 'UPDATE blogPosts SET deleted = 1 WHERE postID = :postID';
			$this->db->insert($query, array(':postID' => $post['postID']));
		} else {
			throw new Exception('Blogpost not found');
		}
	}

	/**
	* Function gets flagged posts from database. can only be seen 
	* by users admins with admin rights.
	* @return array
	*/
	public function getFlagged() {
		if(Auth::checkAdmin()) {
			$query = 'SELECT reportText, blogPostReports.timestamp, blogPosts.PostURL, users.userName as postAuthor, 
				(SELECT userName FROM users WHERE userID = blogPostReports.userID) as reportAuthor FROM blogPostReports 
				LEFT JOIN blogPosts ON blogPostReports.postID = blogPosts.postID LEFT JOIN users ON blogPosts.userID = users.userID WHERE blogPosts.deleted = 0';
			return $this->db->select($query);
		} else {
			throw new Exception('Admin function. Login as an admin or GTFO');
		}	
	}
}
