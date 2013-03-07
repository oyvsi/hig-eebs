<?php

class Form {
	protected $name;
	protected $action;
	protected $method;
	protected $inputFields;

	/**
	* constructor. sets up class Form as a new form.
	* @param string $name
	* @param string $action
	* @param string $method
	*/
	public function __construct($name, $action, $method) {
		$this->inputFields = array();
		$this->name = $name;
		$this->action = __URL_PATH . $action;
		$this->method = $method;

	}

	/**
	* fuction adds inputs to a form. number of parameters can vary
	* @param string $type
	* @param string $name
	* @param string $lable
	* @param string $value
	* @param string $readOly
	* @param string $id
	*/
	public function addInput($type, $name, $lable=false, $value=false, $readOnly=false, $id=false) {
		$input = '<p class="input">';

		if($lable !== false) {
			$input .= '<label for="' . $name . '">' . $lable;

			//sets a colon in the label for every input type except "submit".
			if($type != 'submit') {
				$input .= ':';
			} else {
				$input .= '&nbsp';
			}
			
			$input .= '</label>' . "\n";
		}

		$input .= "\t" . '<input type="' . $type .'" name="' . $name . '"';

		if($id !== false) {
			$input .= ' id="' . $id . '"';
		}
		if($value !== false) {
			$input .= ' value="' . $value . '"';
		}
		if($readOnly !== false) {
			$input .= ' readonly="true"';
		}
		$input .= ' /> </p>';

		array_push($this->inputFields, $input);
	}

	/**
	* fuction adds textarea to a form. number of parameters can vary
	* @param string $name
	* @param string $rows
	* @param string $cols
	* @param string $lable
	* @param string $value
	*/
	public function addTextArea($name, $rows, $cols, $lable=false, $value=false) {
		$html = '<p class="textarea">';
		if($lable !== false) {
			$html .= '<p>' . $lable . ':</p>';
		}
		
		$html .= '<textarea name="' . $name . '" id="' . $name . '"  rows="' . $rows . '" cols="' . $cols . '">';
		
		if ($value !== false) {
			$html .= $value;
		}

		$html .= '</textarea></p>';
		array_push($this->inputFields, $html);
	} 

	/**
	* fuction adds select button to a form. number of parameters can vary
	* @param string $name
	* @param string $lable
	* @param string $value
	* @param string $selected
	*/
	public function addSelect($name, $lable, $values, $selected=false) {

			$html = '<p class="input"><label for="' .$name. '">' .$lable. ':</label><select name="' .$name. '">';

			foreach($values as $value) {
				$html .= '<option value="' .$value['value']. '"';

				if($value['value'] === $selected) {
					$html .= ' selected';
				}

				$html .= '>' .$value['view']. '</option>';
			}

			$html .= '</select></p>';

			array_push($this->inputFields, $html);
	}

	/**
	* fuction creates the html code to create the form.
	* @return string.
	*/
	public function genForm() {
		$html = '<form name="' . $this->name . '" id="' . $this->name . '" action="' . $this->action . '" method="' . $this->method . '" enctype="multipart/form-data">';   
		foreach($this->inputFields as $input) {
			$html .= "\n\t" . $input;
		}
		$html .= "\n" . '</form>';

		return $html;
	}
}
