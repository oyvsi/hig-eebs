<?php
/**
 * Class for general uploading of files.
 * supports limiting file extension
 *
 * @author Team Henkars
 */

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
	* constuctor. Sets up the FileUpload class.
	* @param string $file the $_FILE key
	* @param string $path the path relative to root
	*/
	public function __construct($file, $path) {
		$this->uploadDir = __SITE_PATH . '/' . __UPLOAD_DIR . $path . '/';
		$this->uploadURLDIR = __URL_PATH . __UPLOAD_DIR . $path . '/';

		$this->fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$this->origFileName = $file['tmp_name'];
		$this->fileName = pathinfo($file['name'], PATHINFO_FILENAME);
	}

	/**
	* Sets the name of the uploaded file (without extension)
	* @param string $name
	*/
	public function setName($name) {
		$this->fileName = $name;
	}

	/**
	* Sets allowed file extentions.
	* @param array $ext textual extension (no ".")
	* @throws Exception if $ext is not an array
	*/
	public function setAllowed($ext) {
		if(is_array($ext)) {
			$this->allowedTypes = $ext;
		} else {
			throw new Exception('Allowed type must be an array');
		}
	}	

	/**
	* Getter for the URL of the uploaded file
	* @throws Exception if process is not called beforehand
	* @return string the URL
	*/
	public function getURL() {
		if($this->uploaded) {
			return $this->uploadURLDIR . $this->fileName . '.' . $this->fileExt;
		} else {
			throw new Exception('File is not copied yet. Call process() first');
		}
	}

	/**
	* Moves the file to correct place and validates extension
	* @throws Exception if the extension is not allowed or the file could not be moved.
	* @return string the path to the file, relative to the document root
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
