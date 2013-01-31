<?php
class IndexController extends BaseController {
	//	private $args;

	public function __construct() {
		parent::__construct();
		$this->model = new IndexModel();
		if(Auth::checkLogin()) {
			header('blog/$username');			
		} else {
		//	$this->lastPosts();
		$this->mostRead();
	 }
	}

	public function mostRead() {
		echo "Most read...";
		print_r($this->model->mostRead(14));	
	}

	public function mostCommented() {
		$this->view->setVar('blogPosts', $this->model->mostCommented(14));
	
	}

	public function lastPosts() {
		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->setVar('blogPosts', $this->model->lastPosts(10));
		$this->view->render('lastPosts');
	}
}
