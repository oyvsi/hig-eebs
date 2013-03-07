<?php

class ValidateForm {
	private $form;
	private $requiredFields = false;
	private $minLength = false;
	private $errors  = array();
		
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
				if(array_key_exists($key, $this->form)) {
				
					if(!isset($this->form[$key])) {
						array_push($this->errors, $key . ' is not properly filled out');
					}
						
					if(strlen($this->form[$key]) < $value['minLength']) {
						array_push($this->errors, $value['view'] . ' must be atleast ' . $value['minLength'] . ' characters');
					}
				

					if(strlen($this->form[$key]) > $value['maxLength']) {
						array_push($this->errors, $value['view'] . ' must be atleast ' . $value['maxLength'] . ' characters');
					} 
						
					if(array_key_exists('regex', $value)) {
						if(!preg_match($value['regex'], $this->form[$key])) {
							array_push($this->errors, $value['view'] . ' is SHIT ' . $key .  ' characters');
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
