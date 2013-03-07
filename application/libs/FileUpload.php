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

	/**
	* constuctor. sets up the FileUpload class.
	* @param string $file
	* @param string $path
	*/
	public function __construct($file, $path) {
		$this->uploadDir = __SITE_PATH . '/' . __UPLOAD_DIR . $path . '/';
		$this->uploadURLDIR = __URL_PATH . __UPLOAD_DIR . $path . '/';

		$this->fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$this->origFileName = $file['tmp_name'];
		$this->fileName = pathinfo($file['name'], PATHINFO_FILENAME);
	}

	/**
	* function sets filename.
	* @param string $name
	*/
	public function setName($name) {
		$this->fileName = $name;
	}

	/**
	* function sets allowed file extentions.
	* @param array $ext
	*/
	public function setAllowed($ext) {
		if(is_array($ext)) {
			$this->allowedTypes = $ext;
		} else {
			throw new Exception('Allowed type must be an array');
		}
	}	

	/**
	* function returns URL to a file.
	* @return string
	*/
	public function getURL() {
		if($this->uploaded) {
			return $this->uploadURLDIR . $this->fileName . '.' . $this->fileExt;
		} else {
			throw new Exception('File is not copied yet. Call process() first');
		}
	}

	/**
	* FIX THIS
	* @return string
	*/
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

