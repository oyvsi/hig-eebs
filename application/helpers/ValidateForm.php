<?php
/**
 * 
 * This class is responsible for validating forms sent from different models.
 *
 * @author Team Henkars
 */
class ValidateForm {
	private $form;
	private $requiredFields = false;
	private $errors  = array();
	public $ignoreFields = array();
		 
	/**
    * Default constructor
    * Saves the given form to validate
    * @param $form
    */
	public function __construct($form) {
		$this->form = $form;
	}

	/**
	* Sets the fields which holds the rules for the given field
	* @param $field
	*/
	public function setRequired($fields) {
		$this->requiredFields = $fields;
	}
	
	/**
	* Getter for errors logged. Used by models before to append error-text.
	* 
	*/
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	* Loops through the requiredFields for a model, then checks if the $field exists in the form and is set.
	* Continues with checking both minLength and maxLength, f the given field has any regex related to it, and if it has,then match it.
	* @return bool valid
	*/
	public function check() {
		if($this->requiredFields !== false) {
			foreach($this->requiredFields as $key => $value) {
				if(array_key_exists($key, $this->form) && !in_array($key, $this->ignoreFields)) {
					strip_tags($this->form[$key]);
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
