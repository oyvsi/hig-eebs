<?php

class BlogController extends BaseController {
	$protected blogPostController;

	public function __construct() {
		parent::__construct();
		echo 'BloggController here, Cpt. Over<br />';	
	}
	
	public function view() {
		//$result = $this->model->setID($this->args[1]);
		//if($result === false) {
		//	throw new Exception('Invalid id fool!');
		//}
		echo 'Vis blogg med id ' . $this->args[1];	
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
