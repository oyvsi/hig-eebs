<?php
class BlogpostModel extends BaseModel {
	protected $blogPostFields =  array('title' => array('minLength' => 3, 'maxLength' => 100),		
											   'postIngress' => array('minLength' => 3, 'maxLength' => 100),		
												'postText' => array('minLength' => 30, 'maxLength' => 20000));

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
				$notUnique = $this->db->selectOne('SELECT postID FROM blogPosts WHERE userID = :userID AND postURL = :postURL', array(':userID' => $post['userID'], ':postURL' => $url));
				if($notUnique) {
					$url .= '_';
				}
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
