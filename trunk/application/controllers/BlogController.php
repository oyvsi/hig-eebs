<?php
/**
 * 
 * This class holds function specific to a user's blog.
 *
 * @author Team Henkars
 */

class BlogController extends BaseController {
	protected $indexModel;
	protected $blogName;
	protected $userModel;

        /**
        * Default constructor
        * Sets up models
        */
        public function __construct() {
		parent::__construct();
		$this->model = new BlogModel();
		$this->indexModel = new IndexModel();
		$this->userModel = new UserModel();
	}

	/**
         * Shows the blog of an user.
         * The username is determined by string after last /
         * @url /blog/view/$userName
         */
        public function view() {	
		$this->blogName = (isset($this->args[1])) ? $this->args[1] : NULL;
		if($this->blogName !== NULL) {
			$user = $this->userModel->fetchUserProfile($this->blogName);
			$this->view->setVar('blogPosts', $this->indexModel->getPostsByUser($user['userID']));
			$this->view->setVar('userProfile', $user);
			$this->view->setVar('title', $this->blogName);
			$this->view->addViewFile('user/profile');
			$this->view->addViewFile('blogPosts');
			$this->model->updateViewCount($user['userID'], 'userID', 'blogViews');
	
		} else {
			HTML::redirect("");
		
		}
	}
}
