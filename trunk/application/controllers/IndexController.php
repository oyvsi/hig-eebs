<?php
class IndexController extends BaseController {
//	private $args;

	public function __construct() {
		parent::__construct();
		$this->view->setVar('title', 'Bloggsystem2kPro');
		$this->view->render('index');
//		echo "IndexController here";
	}
}
