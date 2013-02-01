<?php
class IndexController extends BaseController {
	private $user;

	public function __construct() {
		parent::__construct();
		$this->model = new IndexModel();
		print_r($this->args);
	}

	public function loadIndex() {
		if(Auth::checkLogin()) {
			$this->user = new UserController();
			$this->user->fetchUserInfo($_SESSION['userID']);
			echo 'Welcome, '. $this->user->model->userName;
			$this->view->setVar('blogPosts', $this->model->getPostsByUser($this->user->model->userID));
			$this->view->render('blogPosts');
		} else {
			//	$this->lastPosts();
			$this->mostRead();
		}
	}

	public function mostRead() {
		echo "Most read...";
		$this->view->setVar('blogPosts', $this->model->mostRead(14));
		$this->view->render('blogPosts');	
	}

	public function mostCommented() {
		echo "Most commented...";
		$this->view->setVar('blogPosts', $this->model->mostCommented(14));

	}

	public function lastPosts() {
		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->setVar('blogPosts', $this->model->lastPosts(10));
		$this->view->render('lastPosts');
	}
}
