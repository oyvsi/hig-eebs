<?php
class IndexController extends BaseController {
//	private $args;

	public function __construct() {
		parent::__construct();
		$this->model = new IndexModel();
		/*if(Auth::logged_in) {
			header('blog/$username');			
		} else {
			showRecentPosts();
		}*/

		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->setVar('blogPosts', $this->model->lastPosts(10));
		$this->view->render('lastPosts');
//		echo "IndexController here";
	}
}
