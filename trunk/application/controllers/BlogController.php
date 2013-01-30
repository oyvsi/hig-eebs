<?php

class BlogController extends BaseController {
	protected $blogPostController;
	protected $blogName;
	protected $postName;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogModel();
	}

	protected function updateViewCount($blogName, $blogPost = false) {
/* DUNNO HOW TO TEST IT DUE SHIT, mvh Laff
		if($blogPost === false) {
			// update viewcount for blog
			if(limitViewCount()) {
				// insert view timer/ip
				$query = "INSERT INTO blogviews(bloggID, timestamp, ipadress) VALUES (?,?,?)";
				$values = array($this->$blogName, time(), getRealIpAddr()); 
				$this->insert($query, $values);
			}

		} else {
			// update viewcount for post
			if(limitViewCount()) {
				$query = "INSERT INTO postviews(postID, timestamp, ipadress) VALUES (?,?,?)";
				$values = array($this->$postName, time(), getRealIpAddr()); 
				$this->insert($query, $values);
			}
		}
*/
	}
	
	public function view() {
		$this->blogName = (isset($this->args[1])) ? $this->args[1] : NULL;
		$this->postName = (isset($this->args[2])) ? $this->args[2] : NULL;
		//$result = $this->model->setID($this->args[1]);
		//if($result === false) {
		//	throw new Exception('Invalid id fool!');
		//}
//		$this->view->setVar('title', $this->model->getTitle());
		if($this->blogName && $this->postName) {
			echo '<br />Give me the post with title ' . $this->postName . ' on blog ' . $this->blogName;
			$this->view->setVar('posts', $this->model->getPost($this->blogName, $this->postName));
			$this->view->render('blog/index');
			$this->updateViewCount($this->blogName, $this->postName);
		}	
		elseif($this->blogName) {
			echo 'Give me the all posts of blog with name ' . $this->blogName;
			$this->view->setVar('posts', $this->model->getAllPosts($this->blogName));
			$this->view->render('blog/index');
			$this->updateViewCount($this->blogName);
		}
	} 

	public function create() {
		echo 'Yo dewg! We heard you like da bloggs, so we created one for ya';
	} 
	public function delete() {
		echo 'Remove my stuff';
	}
	public function post() {
		$this->blogPostController = new BlogPostController();
	}

/* DUNNO HOW TO TEST IT DUE SHIT, mvh Laff
	public function limitViewCount($type) {

		$query = "SELECT * FROM '$type' WHERE ipadress = ". getRealIpAddr()." AND timestamp - ".time()." <= 3600";
		$result = $this->select($query);

		if(!$result) {
			return true;
		}
	}
*/
/* DUNNO HOW TO TEST IT DUE SHIT, mvh Laff
	public function getRealIpAddr() {

		if(!empty($_SERVER['HTTP_CLIENT_IP'])){  
			$ip=$_SERVER['HTTP_CLIENT_IP']; 

		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 

		} else { 
			$ip=$_SERVER['REMOTE_ADDR']; 
		}

		return $ip; 
	}
*/
}
