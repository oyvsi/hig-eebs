<?php

class FileUpload {
	protected $origFileName;
	protected $fileName = null;
	protected $fileExt;
	protected $fullPath;
	protected $allowedTypes = null;
	protected $uploadDir = '/var/www/www-tek/hig-eebs/public/profileImages/';

	public function __construct($file) {
		print_r($file);
		$this->fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$this->origFileName = $file['tmp_name'];
		$this->fileName = pathinfo($file['name'], PATHINFO_FILENAME);
		echo "Name $this->fileName";
	}

	public function setName($name) {
		$this->fileName = $name;
	}

	public function setAllowed($ext) {
		if(is_array($ext)) {
			$this->allowedTypes = $ext;
		} else {
			throw new Exception('Allowed type must be an array');
		}
	}	

	public function process() {
		if($this->allowedTypes !== null && in_array($this->fileExt, $this->allowedTypes) === false) {
			throw new Exception('Illegal file type ' . $this->fileExt);
		}
		if(move_uploaded_file($this->origFileName, $this->uploadDir . $this->fileName . '.' . $this->fileExt) == false) {
			throw new Exception('Unable to move file');
		}
		$this->fullPath = $this->uploadDir . $this->fileName .  '.' . $this->fileExt;
		return $this->fullPath;
	}
}

