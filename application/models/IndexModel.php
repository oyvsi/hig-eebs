<?php
/**
 * Index database functions 
 * @author Team Henkars
 */
class IndexModel extends BaseModel {
	private $baseQuery = 'SELECT blogPosts.*, users.userName, 
								COUNT(DISTINCT postViews.viewID) as readCount, (SELECT COUNT(comments.commentID) FROM comments WHERE comments.PostID = blogPosts.postID AND comments.deleted = 0) as noComments FROM blogPosts 
					 			LEFT JOIN postViews ON postViews.postID = blogPosts.postID 
					 			LEFT JOIN users ON blogPosts.userID = users.userID
								WHERE blogPosts.deleted = 0';
	protected $blogStats = array();
	

	/**
	* constructor. sets up basic info.
	*/	
	public function __construct() {
		parent::__construct();
	}

	/**
	* Fuction returns a limited number of last blogposts from all users.
	* @param int $limit
	* @return array
	*/
	public function lastPosts($limit) {
		return $this->db->select($this->baseQuery . ' GROUP BY blogPosts.postID ORDER BY timestamp DESC LIMIT :limit', array(':limit'=> $limit));
	}
	
	/**
	* Fuction returns a given users blogposts.
	* @param int $userID
	* @return array
	*/	
	public function getPostsByUser($userID) {
		return $this->db->select($this->baseQuery . ' AND blogPosts.userID = :userID GROUP BY blogPosts.postID ORDER BY timestamp DESC', array(':userID' => $userID));
	}

	/**
	* Fuction returns most read posts within tha last given days
	* @param int $days
	* @return array
	*/	
	public function mostRead($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' AND blogPosts.timestamp BETWEEN :startTime AND :endTime
					 					GROUP BY blogPosts.postID
					 					ORDER BY readCount DESC, timestamp DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	/**
	* Fuction returns most commented bolgpost within the last given days.
	* @param int $days
	* @return array
	*/	
	public function mostCommented($days) {
		$startTime = strtotime('-' . $days . 'days');
		$endTime = strtotime('now');
		$query = $this->baseQuery . ' AND blogPosts.timestamp BETWEEN :startTime AND :endTime
									  GROUP BY blogPosts.postID HAVING noComments > 0
									  ORDER BY noComments DESC, timestamp DESC';

		return $this->db->select($query, array(':startTime' => $startTime, ':endTime' => $endTime));
	}

	/**
	* Fuction calculates top ten most popular blogs.
	* The rating is based on blog views, post views
	* number of comments and total number of posts.
	* @return array
	*/	
	public function topTen() {
		$ratings = array();

		$postCountQuery = 'SELECT COUNT(blogPosts.postID) AS postCount, users.userName FROM blogPosts LEFT JOIN users ON users.userID = blogPosts.userID WHERE blogPosts.deleted = 0 GROUP BY users.userID';	

		$postCount = $this->db->select($postCountQuery);
		
		$blogViewsQuery = 'SELECT COUNT(blogViews.viewID) AS viewCount, blogViews.userID,users.userName FROM blogViews 
						   LEFT JOIN users ON users.userID = blogViews.userID 
						   GROUP BY users.userID
						   HAVING COUNT(blogViews.viewID) > 0';

		$viewCount = $this->db->select($blogViewsQuery);
		
		$commentQuery = 'SELECT COUNT(comments.commentID) AS commentCount, users.userName FROM blogPosts 
						 LEFT JOIN comments ON blogPosts.postID = comments.postID 
						 LEFT JOIN users ON blogPosts.userID = users.userID
						 WHERE blogPosts.deleted = 0
						 GROUP BY users.userID
						 HAVING COUNT(comments.commentID) > 0';
		
		$commentCount = $this->db->select($commentQuery);

		$postViewQuery = 'SELECT COUNT(postViews.viewID) AS postViewCount, users.userName FROM postViews 
						  LEFT JOIN blogPosts ON blogPosts.postID = postViews.postID 
		                  LEFT JOIN users ON users.userID = blogPosts.userID 
		                  WHERE blogPosts.deleted = 0
		                  GROUP BY users.userID
		                  HAVING COUNT(postViews.viewID) > 0';
		
		$postViewCount = $this->db->select($postViewQuery);
	
		$this->saveResult($postCount, 'userName');
		$this->saveResult($postCount, 'postCount');
		$this->saveResult($viewCount, 'viewCount');
		$this->saveResult($commentCount, 'commentCount');
		$this->saveResult($postViewCount, 'postViewCount');

		foreach($this->blogStats as $user) {
			if(count($user) > 4) {
				$sum = ($user['postViewCount'] + $user['viewCount'] + $user['commentCount'] / $user['postCount']) * log($user['postCount']) * log($user['viewCount'] * 5);
				if($sum > 1) { 
					$ratings[$user['userName']] = $sum; 
				}
			}
		}	

        arsort($ratings);
        return $ratings;
	}

	/**
	* Function saves given parameters inn protected variable blogStats.
	* @param array $container
	* @param string $identifyer
	*/	
	public function saveResult($container, $identifier) {
		foreach($container as $array) {
			$this->blogStats[$array['userName']][$identifier] = $array[$identifier];
		}
	}
	
}
