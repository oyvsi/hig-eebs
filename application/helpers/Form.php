<?php
/**
 * Helper function to create a html form
 *  
 * @author Team Henkars
 */
class Form {
	protected $name;
	protected $action;
	protected $class;
	protected $method;
	protected $inputFields;

	/**
	* Constructor. sets up class Form as a new form.
	* 
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
	 * Set the class of the form
	 * @param string $class name of the class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	* Adds inputs to a form. number of parameters can vary
	* 
	* @param string $type
	* @param string $name
	* @param string $label defaults to false
	* @param string $value defaults to false
	* @param string $readOly defaults to false
	* @param string $id defaults to false
	*/
	public function addInput($type, $name, $label=false, $value=false, $readOnly=false, $id=false) {
		$input = '<p class="input">';

		if($label !== false) {
			$input .= '<label for="' . $name . '">' . $label;

			//sets a colon in the label for every input type except "submit".
			if($type != 'submit') {
				$input .= ':';
			} else {
				$input .= '&nbsp;';
			}
			
			$input .= '</label>' . "\n";
		}

		$input .= "\t" . '<input type="' . $type .'" name="' . $name . '"';

		if($id !== false) {
			$input .= ' id="' . $id . '"';
		} else {
			$input .= ' id="' .$name. '" ';
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
	* 
	* @param string $name
	* @param string $rows
	* @param string $cols
	* @param string $label defaults to false
	* @param string $value defaults to false
	*/
	public function addTextArea($name, $rows, $cols, $label=false, $value=false) {
		$html = '<div>';
		if($label !== false) {
			$html .= '<p>' . $label . ':</p>';
		}
		
		$html .= '<textarea name="' . $name . '" id="' . $name . '"  rows="' . $rows . '" cols="' . $cols . '">';
		
		if ($value !== false) {
			$html .= $value;
		}

		$html .= '</textarea></div>';
		array_push($this->inputFields, $html);
	} 

	/**
	* fuction adds select button to a form. number of parameters can vary
	* 
	* @param string $name
	* @param string $label
	* @param string $value
	* @param string $selected defaults to false
	*/
	public function addSelect($name, $label, $values, $selected=false) {

			$html = '<p class="input"><label for="' .$name. '">' .$label. ':</label><select name="' .$name. '" id="' .$name. '">';

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
		$html = '<form name="' . $this->name . '" id="' . $this->action . '"' . ($this->class ? ' class="' . $this->class . '"' : ' ') . ' action="' . $this->action . '" method="' . $this->method . '" enctype="multipart/form-data">';   
		foreach($this->inputFields as $input) {
			$html .= "\n\t" . $input;
		}
		$html .= "\n" . '</form>';

		return $html;
	}
}
