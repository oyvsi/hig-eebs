<?php
class IndexController extends BaseController {
//	private $args;

	public function __construct() {
		parent::__construct();
		$this->view->setTitle('Bloggsystem2kPro');
		$this->view->render('index');
//		echo "IndexController here";
	}
}
