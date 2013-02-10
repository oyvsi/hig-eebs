<?php

class BlogController extends BaseController {
	protected $blogpostController;
	protected $blogName;
	protected $postName;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogModel();
	}

	protected function updateViewCount($blogName, $blogPost = false) {
		if($blogPost === false) {
			// update viewcount for blog
			$this->model->updateViewCount($blogName);

		} else {
			// update viewcount for post. Should be moved to BlogpostController....
			$this->model->updatePostViewCount($blogPost);
		}
	}
	public function view() {	
		$this->blogName = (isset($this->args[1])) ? $this->args[1] : NULL;
		$this->postName = (isset($this->args[2])) ? $this->args[2] : NULL;

		//$result = $this->model->setID($this->args[1]);
		//if($result === false) {
		//	throw new Exception('Invalid id fool!');
		//}
		//		$this->view->setVar('title', $this->model->getTitle());

		// This should probalby be moved to BlogpostController
		if($this->blogName && $this->postName) {
			$loadComments = (isset($this->args[3]) && $this->args[3] == 'comments');
			//echo '<br />Give me the post with title ' . $this->postName . ' on blog ' . $this->blogName;
			$this->blogpostController = new BlogpostController();
			$this->blogpostController->view();

			$this->view->setVar('blogPosts', $this->model->getPost($this->blogName, $this->postName));
			if($loadComments) { // TODO: Fix this
				$this->view->renderFooter = false;
			}
			$this->view->render('blog/blogPost');
			$this->updateViewCount('jens', $this->model->postID);

			if($loadComments) {
				$url = 'blog/view/'. $this->blogName . '/' . $this->postName;
				$commentsController = new CommentsController();
				$commentsController->loadComments($this->model->postID, $url);
			}


		}	
		elseif($this->blogName) {
			//echo 'Give me the all posts of blog with name ' . $this->blogName;
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
		$this->blogpostController = new BlogpostController();
		$this->blogpostController->create();
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
