<?php
/**
* 
*
*/

class BlogpostController extends BaseController {
	public function __construct() {
		parent::__construct();
		$this->model = new BlogpostModel();
	}

	public function view() {
		echo 'Show a post';
	}
	public function create() {
		// umh Should it be here???
		$form = new Form('blogPost', 'blogpost/createDo', 'POST');
		$form->addInput('text', 'title', 'Title: ');
		$form->addTextArea('postText', 10, 10);
		$form->addInput('submit', 'Submit');
		$this->view->setVar('form', $form->genForm());

		$this->view->render('blog/createPost');
	}
	public function createDo() {
		$this->model->createPost($_POST, $this->user->model->userID);
	}
	
	public function update() {
		echo 'add some shit';
	}
	public function delete() {
		echo 'Well this sucked, remove it!';
	}

	public function flag() {
		echo 'Mark no-good';
	}

}
