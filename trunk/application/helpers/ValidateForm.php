<?php

class ValidateForm {
	private $form;
	private $requiredFields = false;
	private $errors  = array();
	public $ignoreFields = array();
		
	public function __construct($form) {
		$this->form = $form;
	}


	public function setRequired($fields) {
		$this->requiredFields = $fields;
	}
	public function setMinLength($fields) {
		$this->minLength = $fields;	
	}


	public function getErrors() {
		return $this->errors;
	}
	

	public function check() {
		if($this->requiredFields !== false) {
			foreach($this->requiredFields as $key => $value) {
				strip_tags($this->form[$key]);
				if(array_key_exists($key, $this->form) && !in_array($key, $this->ignoreFields)) {
					if(!isset($this->form[$key])) {
						array_push($this->errors, $key . ' is not properly filled out');
					}
						
					if(strlen($this->form[$key]) < $value['minLength']) {
						array_push($this->errors, $value['view'] . ' must be atleast ' . $value['minLength'] . ' characters');
					}
				

					if(strlen($this->form[$key]) > $value['maxLength']) {
						array_push($this->errors, $value['view'] . ' must be below ' . $value['maxLength'] . ' characters');
					} 
						
					if(array_key_exists('regex', $value)) {
						if(!preg_match($value['regex'], $this->form[$key])) {
							array_push($this->errors, $value['view'] . ' is not in a valid form, please use username@domain');
						}

					}
				}
			}
				
		if(count($this->errors) > 0) {
			return false;
		}
		
		return true;
		
		}
	}
}
