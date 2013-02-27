<?php

class FileUpload {
	protected $origFileName;
	protected $fileName = null;
	protected $fileExt;
	protected $fullPath;
	protected $allowedTypes = null;
	protected $uploadDir;
	protected $uploadURLDir;
	protected $uploaded = false;

	public function __construct($file, $path) {
		$this->uploadDir = __SITE_PATH . '/' . __UPLOAD_DIR . $path . '/';
		$this->uploadURLDIR = __URL_PATH . __UPLOAD_DIR . $path . '/';

		$this->fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$this->origFileName = $file['tmp_name'];
		$this->fileName = pathinfo($file['name'], PATHINFO_FILENAME);
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

	public function getURL() {
		if($this->uploaded) {
			return $this->uploadURLDIR . $this->fileName . '.' . $this->fileExt;
		} else {
			throw new Exception('File is not copied yet. Call process() first');
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
		$this->uploaded = true;

		return $this->fullPath;
	}
}

