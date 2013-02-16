<?php
class IndexController extends BaseController {

	public function __construct() {
		parent::__construct();
		$this->model = new IndexModel();
		$this->view->setVar('title', 'HiG-eebs');
	}

	public function loadIndex() {
		if($this->user) {
			echo 'Welcome, '. $this->user->model->userName;
			$this->view->setVar('blogPosts', $this->model->getPostsByUser($this->user->model->userID));
			$this->view->render('blogPosts');
		} else {
			$this->lastPosts();
		}
	 }

	public function mostRead() {
		$this->view->setVar('title', 'Most read');
		$this->view->setVar('blogPosts', $this->model->mostRead(14));
		$this->view->render('blogPosts');	
	}

	public function mostCommented() {
		$this->view->setVar('title', 'Most commented');
		$this->view->setVar('blogPosts', $this->model->mostCommented(14));
		$this->view->render('blogPosts');
	}

	public function lastPosts() {
		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->setVar('blogPosts', $this->model->lastPosts(10));
		$this->view->render('blogPosts');
	}
}
