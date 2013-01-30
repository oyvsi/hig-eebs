<?php

class BlogController extends BaseController {
	protected $blogPostController;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogModel();
		echo 'BloggController here, Cpt. Over<br />';	
	}
	
	public function view() {
		//$result = $this->model->setID($this->args[1]);
		//if($result === false) {
		//	throw new Exception('Invalid id fool!');
		//}
		$this->view->setVar('posts', $this->model->getPosts());

		if(isset($this->args[2])) {
			echo 'Give me the post with id ' . $this->args[2];
		}
		$this->view->render('blog/index');
	} 

	public function create() {
		echo 'Yo dewg! We heard you like da bloggs, so we created one for ya';
	} 
	public function delete() {
		echo 'Remove my stuff';
	}
	public function post() {
		$this->blogPostController = new BlogPostController();
	}
}
