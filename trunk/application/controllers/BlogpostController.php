<?php
/**
* 
*
*/

class BlogpostController extends BaseController {
	public function __construct() {
		parent::__construct();
	}

	public function view() {
		echo 'Show a post';
	}
	public function create() {
		//maybe in constructor?
		echo 'Get me that wyziwig';
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
