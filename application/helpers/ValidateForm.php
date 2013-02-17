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
			foreach($this->requiredFields as $field) {
				if(!isset($this->form[$field])) {
					array_push($this->errors, $field . ' is not properly filled out');
				}
			}
		}

		if($this->minLength !== false) {
			foreach($this->minLength as $field => $minLength) {
				if(strlen($this->form[$field]) < $minLength) {
					array_push($this->errors,  $field . ' must be atleast ' . $minLength . ' characters');
				}
			}
		}
		if(count($this->errors) > 0) {
			return false;
		}
		return true;
	}
}
