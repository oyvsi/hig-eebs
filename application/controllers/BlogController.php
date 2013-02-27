<?php

class BlogController extends BaseController {
	protected $blogpostController;
	protected $blogName;
	protected $postName;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogModel();
	}

	public function view() {	
		$this->blogName = (isset($this->args[1])) ? $this->args[1] : NULL;
		$this->view->setVar('posts', $this->model->getAllPosts($this->blogName));
		$this->view->viewFile = 'blog/index';
		//$this->model->updateViewCount($this->blogName);
		}
 

	public function create() {
		echo 'Yo dewg! We heard you like da bloggs, so we created one for ya';
	} 
	public function delete() {
		echo 'Remove my stuff';
	}
}
