<?php
class IndexController extends BaseController {

	/**
	* constructor. sets up det index info.
	*/
	public function __construct() {
		parent::__construct();
		$this->model = new IndexModel();
		$this->view->setVar('title', 'HiG-eebs');
		$this->view->addViewFile('blogPosts');
		$this->view->renderSideBar = true;
	}

	/**
	* fuction lods current user and print welcome
	* and the users blogpost.
	* if no user is logged in, last 
	* uploded posts is viewed.
	*/
	public function loadIndex() {
		$this->topTen();
		if($this->user) {
			$this->view->setVar('message', 'Welcome, ' .$this->user->model->userName. '!');
			$this->view->setVar('blogPosts', $this->model->getPostsByUser($this->user->model->userID));
		} else {
			$this->lastPosts();
		}
	 }

	/**
	* gets the most read post of the last 14 days
	*/
	public function mostRead() {
		$this->topTen();
		$this->view->setVar('title', 'Most read');
		$this->view->setVar('blogPosts', $this->model->mostRead(14));
	}

	/**
	* gets the most commented post of the last 14 days
	*/
	public function mostCommented() {
		$this->topTen();
		$this->view->setVar('title', 'Most commented');
		$this->view->setVar('blogPosts', $this->model->mostCommented(14));
	}

	/**
	* gets the last ten post from the logged in user.
	*/
	public function lastPosts() {
		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->setVar('blogPosts', $this->model->lastPosts(10));
	}

	/**
	* functon that calculates the top ten most popular blogs.
	*/
	public function topTen() {
		$result = $this->model->topTen();
		$this->view->setVar('topTenKeys', array_keys($result));
		$this->view->setVar('topTen', $result);
	}
}
