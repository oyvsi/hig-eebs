<?php

class IndexModel extends BaseModel {
	private $baseQuery = 'SELECT blogPosts.*, users.userName, 
								COUNT(DISTINCT postViews.viewID) as readCount, (SELECT COUNT(comments.commentID) FROM comments WHERE comments.PostID = blogPosts.postID AND comments.deleted = 0) as noComments FROM blogPosts 
					 			LEFT JOIN postViews ON postViews.postID = blogPosts.postID 
					 			LEFT JOIN users ON blogPosts.userID = users.userID
								WHERE blogPosts.deleted = 0';
	protected $blogStats = array();
	


	public function __construct() {
		parent::__construct();
	}

	public function lastPosts($limit) {
		return $this->db->select($this->baseQuery . ' GROUP BY blogPosts.postID ORDER BY timestamp DESC LIMIT :limit', array(':limit'=> 10));
	}
	public function getPostsByUser($userID) {
		return $this->db->select($this->baseQuery . ' AND blogPosts.userID = :userID GROUP BY blogPosts.postID ORDER BY timestamp DESC', array(':userID' => $userID));
	}

	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' AND blogPosts.timestamp BETWEEN :startTime AND :endTime
					 					GROUP BY blogPosts.postID
					 					ORDER BY readCount DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function mostCommented($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' AND blogPosts.timestamp BETWEEN :startTime AND :endTime
									  GROUP BY blogPosts.postID
									  ORDER BY noComments DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	public function topTen() {
		$postCountQuery = 'SELECT COUNT(blogPosts.postID) AS postCount, users.userName FROM blogPosts LEFT JOIN users ON users.userID = blogPosts.userID GROUP BY users.userID';	

		$postCount = $this->db->select($postCountQuery);

		
		$blogViewsQuery = 'SELECT COUNT(blogViews.viewID) AS viewCount, blogViews.userID,users.userName FROM blogViews 
						   LEFT JOIN users ON users.userID = blogViews.userID 
						   GROUP BY users.userID
						   HAVING COUNT(blogViews.viewID) > 0';
		

		$viewCount = $this->db->select($blogViewsQuery);
		
		
		
		$commentQuery = 'SELECT COUNT(comments.commentID) AS commentCount, users.userName FROM blogPosts 
						 LEFT JOIN comments ON blogPosts.postID = comments.postID 
						 LEFT JOIN users ON blogPosts.userID = users.userID
						 GROUP BY users.userID
						 HAVING COUNT(comments.commentID) > 0';
		
		$commentCount = $this->db->select($commentQuery);
		
		


		$postViewQuery = 'SELECT COUNT(postViews.viewID) AS postViewCount, users.userName FROM postViews 
						  LEFT JOIN blogPosts ON blogPosts.postID = postViews.postID 
		                  LEFT JOIN users ON users.userID = blogPosts.userID 
		                  GROUP BY users.userID
		                  HAVING COUNT(postViews.viewID) > 0';
		
		$postViewCount = $this->db->select($postViewQuery);
		

	
		$this->saveResult($postCount, 'userName');
		$this->saveResult($postCount, 'postCount');
		$this->saveResult($viewCount, 'viewCount');
		$this->saveResult($commentCount, 'commentCount');
		$this->saveResult($postViewCount, 'postViewCount');

		$ratings = array();

		

		foreach($this->blogStats as $user) {
			if(count($user) > 3) {
				$ratings[$user['userName']] = ($user['viewCount'] + $user['commentCount'] / $user['postCount']) * log($user['postCount']);
			}

		}

			print_r($ratings);

			print_r($this->blogStats);


		//	$ratings[$key] = ($user['viewCount'] + $user['commentCount'] / $user['postCount']) * log($user['postCount']);
		
			return $ratings;
	}


	public function saveResult($container, $identifier) {
		foreach($container as $array) {
				$this->blogStats[$array['userName']][$identifier] = $array[$identifier];
		}
	}


	public function getKeys($array) {
			$keys = array_keys($array);
			return $keys;
	}

}


//print_r($postViewResult);
//print_r($viewArray);
//print_r($commentResult);




