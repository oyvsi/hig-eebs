<?php
/**
* 
*
*/

class BlogpostController extends BaseController {
	private $loadComments = false;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogpostModel();
		$this->user(); // workaround for not being created from bootstrap
	}

	public function setArgs($args) {
		$this->args = $args;
	}

	public function view() {
		$this->view->addViewFile('blog/blogPost');
		$this->loadComments = (isset($this->args[3]) && $this->args[3] == 'comments');
		$commentID = isset($this->args[4]) ? $this->args[4] : false;  // see specific comment

		if($this->loadComments) { // TODO: Fix this
			$this->view->renderFooter = false;
		}

		try {
			$post = $this->model->getPostFromURL($this->args[1], $this->args[2]);
			$isOwner = $this->correctUser($post['userID']);
			$this->view->setVar('isOwner', $isOwner);
			$this->view->setVar('blogPosts', array($post));
			$this->model->updatePostViewCount($this->model->postID);

			if($this->loadComments) {
				$url = 'blogpost/view/'. $this->args[1] . '/' . $this->args[2];
				$commentsController = new CommentsController();
				try {
					$commentsController->loadComments($this->model->postID, $url, $commentID, $isOwner);
				} catch(Exception $excpt) {
					$this->view->setError($excpt);
				}
			}

		}
		catch(Exception $excpt) {
			$this->view->setError($excpt);
		}
	}

	public function create($updateID = false) {
		// umh Should it be here???
		if ($updateID !== false){
			$post = $this->model->getPostFromID($updateID);

			$form = new Form('blogPost', 'blogpost/updateDo/' . $updateID, 'POST');
			$form->addInput('text', 'title', 'Title: ', $post['postTitle']);
			$form->addTextArea('postIngress', 5, 100, 'Ingress', $post['postIngress']);
			$form->addTextArea('postText', 30, 100, 'Post text', $post['postText']);
			//print_r($post);
		} else {
			$form = new Form('blogPost', 'blogpost/createDo', 'POST');
			$form->addInput('text', 'title', 'Title: ');
			$form->addTextArea('postIngress', 5, 100, 'Ingress');
			$form->addTextArea('postText', 30, 100, 'Post text');
		}
		$form->addInput('submit', 'Submit');
		$this->view->setVar('form', $form->genForm());
		$this->view->setVar('title', 'New post');
		$this->view->addViewFile('blog/createPost');
	}

	public function createDo() {
		try {
			$url = $this->model->createPost($_POST, $this->user->model->userID);
			HTML::redirect('blogpost/view/' . $this->user->model->userName . '/' . $url);
		} catch(Exception $excpt) {
			$this->view->setError($excpt);	
			$this->create();
		}
	}

	public function update() {
		$postID = $this->args[1];
		$this->create($postID);
	}

	public function updateDo() {
		try {
//			print_r($_POST);
			$url = $this->model->createPost($_POST, false, $this->args[1]);
			HTML::redirect('blogpost/view/' . $this->user->model->userName . '/' . $url);
		} catch(Exception $excpt) {
			$this->view->setError($excpt);	
			$this->update();
		}
	}

	public function delete() {
		try {
			$this->model->deletePost($this->args[1]);
			HTML::redirect('');
		} catch(Exception $excpt){
			$this->view->setError($excpt);
		}	
	}

	public function flag() {
		if(isset($this->args[1])) {
			$form = new Form('reportPost', 'blogpost/flagDo/', 'POST');
			$form->addTextArea('reportText', 10, 60, 'Report post because');
			$form->addInput('hidden', 'postID', false, $this->args[1]);
			$form->addInput('submit', 'submit');
			$this->view->setVar('form', $form->genForm());
			$this->view->addViewFile('report');
		} 

	}

	public function flagDo() {
		if(isset($_POST['postID'])) {
			try {
				$this->model->flag($_POST['postID'], $_POST);
				$this->view->setVar('message', 'Thank you. Your report will be brought to the administrators');
			} catch(Exception $excpt) {
				$this->view->setError($excpt);
			}
//			$this->view->addViewFile('report');
		}
	}
	public function getFlagged() {
		try {
			$data = $this->model->getFlagged();
			$this->view->setVar('flagged', $data);
			$this->view->addViewFile('admin/flaggedPosts');
		} catch(Exception $excpt) {
			$this->view->setError($excpt);

		}
	}

	public function correctUser($userID){
		return ($this->user && $this->user->model->userID == $userID);
	}
}
