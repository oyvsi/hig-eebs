<?php
/**
* @author Team Henkars
*
* This class holds functions for a blog post
*/

class BlogpostController extends BaseController {

   /**
    * Default constructor
    * Sets up the model
    */
	public function __construct() {
		parent::__construct();
		$this->model = new BlogpostModel();
	}

	public function setArgs($args) {
		$this->args = $args;
	}

   /**
    * Function to show a blog post
    * Arguments from url: userName and postName the post name to show 
    * 
    * url is blogpost/view/$userName/$postName/
    */
	public function view() {
   
      if(isset($this->args[1]) && isset($this->args[2])) {
         $this->view->addViewFile('blog/blogPost');
         try {
            $post = $this->model->getPostFromURL($this->args[1], $this->args[2]);
            $isOwner = ($this->user && $this->user->model->userID == $post['userID']);
            $this->view->setVar('isOwner', $isOwner);
            $this->view->setVar('title', $post['postTitle']);
            $this->view->setVar('blogPost', $post);
            $this->model->updateViewCount($this->model->postID, 'postID', 'postViews');

        }
         catch(Exception $excpt) {
            $this->view->setError($excpt);
         }
      } else {
         HTML::redirect('');  // No username and postname is URL, show index
      }
	}

   /**
    * Function to create and update blogposts.
    * creates a form which either is empty or 
    * contains current info from blogpost.
    * @param int $updateID = false
    */
	public function create($updateID = false) {
		if(Auth::checkLogin()) {	
			if ($updateID !== false){
				$post = $this->model->getPostFromID($updateID);

				$form = new Form('blogPost', 'blogpost/updateDo/' . $updateID, 'POST');
				$form->setClass('ajaxPost');
				$form->addInput('text', 'title', 'Title', $post['postTitle']);
				$form->addTextArea('postIngress', 5, 100, 'Ingress', $post['postIngress']);
				$form->addTextArea('postText', 30, 100, 'Post text', $post['postText']);
			} else {
				$form = new Form('blogPost', 'blogpost/createDo', 'POST');
				$form->setClass('ajaxPost');
				$form->addInput('text', 'title', 'Title: ');
				$form->addTextArea('postIngress', 5, 100, 'Ingress');
				$form->addTextArea('postText', 30, 100, 'Post text');
			}
			$form->addInput('submit', 'Submit');
			$this->view->setVar('form', $form->genForm());
			$this->view->setVar('title', 'New post');
			$this->view->addViewFile('blog/createPost');
		} else {
			HTML::redirect('');
		}
	}
	
   /**
    * Passes post creation information to the model 
    * gets info from $_POST
    * 
    */
	public function createDo() {
      $this->render = false;
      
		try {
			$url = $this->model->createPost($_POST, $this->user->model->userID);
			echo json_encode(array('status' => 'ok', 'url' => __URL_PATH . 'blogpost/view/' . $this->user->model->userName .'/' . $url));
		} catch(Exception $excpt) {
			echo json_encode(array('status' => 'error', 'error' => $excpt->getMessage()));
		}
	}

   /**
    * Updates blogpost. Calls BlogpostController::create() with postID from url
    */
	public function update() {
		$postID = $this->args[1];
		$this->create($postID);
	}

   /**
    * Passes edited post to the model  
    * gets info from $_POST
    */
	public function updateDo() {
		$this->render = false;
		try {
			$url = $this->model->createPost($_POST, false, $this->args[1]);
			echo json_encode(array('status' => 'ok', 'url' => __URL_PATH . 'blogpost/view/' . $this->user->model->userName .'/' . $url));
		} catch(Exception $excpt) {
			echo json_encode(array('status' => 'error', 'error' => $excpt->getMessage()));
		}
	}

   /**
    * Deletes a blogpost. 
    * Arguments from url: userID to postowner and postURL to post
    * 
    * url is comments/delete/$userID/$postURL
    */
	public function delete() {
		try {
			$this->model->deletePost($this->args[1], $this->args[2] );
			HTML::redirect('/blog/view/'. $this->args[1]);
		} catch(Exception $excpt){
			$this->view->setError($excpt);
		}	
	}

   /**
    *  Displays all reported posts.
    */
	public function getFlagged() {
		try {
			$data = $this->model->getFlagged();
			$this->view->setVar('flagged', $data);
			$this->view->setvar('title', 'Flagged Posts');
			$this->view->addViewFile('admin/flaggedPosts');
		} catch(Exception $excpt) {
			$this->view->setError($excpt);

		}
	}
}
