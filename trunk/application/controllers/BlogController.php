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
		$this->postName = (isset($this->args[2])) ? $this->args[2] : NULL;

		if($this->blogName && $this->postName) {
			$this->blogpostController = new BlogpostController();
			$this->blogpostController->setArgs($this->args);
			$this->blogpostController->view($this->blogName, $this->postName);
		}	
		elseif($this->blogName) {
			$this->view->setVar('posts', $this->model->getAllPosts($this->blogName));
			$this->view->render('blog/index');
			$this->model->updateViewCount($this->blogName);
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
}
