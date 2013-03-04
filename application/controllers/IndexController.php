<?php
class IndexController extends BaseController {

	public function __construct() {
		parent::__construct();
		$this->model = new IndexModel();
		$this->view->setVar('title', 'HiG-eebs');
		$this->view->addViewFile('blogPosts');
		$this->view->addViewFile('sideBar');
		$this->view->renderSideBar = true;
	}

	public function loadIndex() {
		$this->topTen();
		if($this->user) {
			echo 'Welcome, '. $this->user->model->userName;
			$this->view->setVar('blogPosts', $this->model->getPostsByUser($this->user->model->userID));
		} else {
			$this->lastPosts();
		}
	 }

	public function mostRead() {
		$this->topTen();
		$this->view->setVar('title', 'Most read');
		$this->view->setVar('blogPosts', $this->model->mostRead(14));
	}

	public function mostCommented() {
		$this->topTen();
		$this->view->setVar('title', 'Most commented');
		$this->view->setVar('blogPosts', $this->model->mostCommented(14));
	}

	public function lastPosts() {
		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->setVar('blogPosts', $this->model->lastPosts(10));
	}

	public function topTen() {
		$result = $this->model->topTen();
		$this->view->setVar('topTenKeys', $this->model->getKeys($result));
		$this->view->setVar('topTen', $result);
	}
}
