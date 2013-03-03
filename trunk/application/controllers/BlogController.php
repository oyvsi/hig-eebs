<?php

class BlogController extends BaseController {
	protected $indexModel;
	protected $blogName;
	protected $userModel;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogModel();
		$this->indexModel = new IndexModel();
		$this->userModel = new UserModel();
	}

	public function view() {	
		$this->blogName = (isset($this->args[1])) ? $this->args[1] : NULL;
		if($this->blogName !== NULL) {
			$user = $this->userModel->fetchUserProfile($this->blogName);
			$this->view->setVar('blogPosts', $this->indexModel->getPostsByUser($user['userID']));
			$this->view->setVar('userProfile', $user);
			$this->view->setVar('title', $this->blogName);
			$this->view->addViewFile('user/profile');
			$this->view->addViewFile('blogPosts');
			
		} else {
			$this->view->addViewFile = 'blog/index';
		
		}
		$this->model->updateViewCount($user['userID']);
	}
}
