<?php
/**
 * Class to process image uploads. Extends FileUpload
 * Requires that GD2 is installed
 *
 * @see FileUpload
 * @author Team Henkars
 */
class ImageUpload extends FileUpload {
	protected static $thumbPostfix = '_thumb';
	protected $thumbWidthRes = 100;
	protected $res;
	protected $minRes = null;
	protected $maxRes = null;
	protected $thumbGenerated = false;

	/**
	 * Default constructor
	 * Calls parents constructor and checks for GD
	 *
	 * @param $file the $_FILE key to process
	 * @param $path the path to upload to
	 * @throws Exception if GD is not loaded
	 */
	public function __construct($file, $path) {
		parent::__construct($file, $path);
		if ((extension_loaded('gd') && function_exists('gd_info')) == false) {
			throw new Exception('The GD-library was not detected on the server');
		}	
	}

	/**
	 * Moves the image to correct location and validates
	 *
	 * @return string path to the image
	 * @throws Exception if the resolution is outside the bounds set 	
	 */
	public function process() {
		$file = parent::process();
		$this->res = getimagesize($this->fullPath);
		if($this->minRes != null && ($this->res[0] < $this->minRes[0] || $this->res[1] < $this->minRes[1])) {
			throw new Exception('Minumum resolution is set to ' . $this->minRes[0] . 'x' . $this->minRes[1]);
		}
		if($this->maxRes != null && ($this->res[0] > $this->maxRes[0] || $this->res[1] > $this->maxRes[1])) {
			throw new Exception('Maximum resolution is set to ' . $this->maxRes[0] . 'x' . $this->maxRes[1]);
		}
		return $file;
	}

	/**
	 * Generates the thumbURL from url to picture.
	 *
	 * @param string the url to the picture
	 * @return string the url to the thumbnail
	 */
	public static function thumbURLfromURL($pictureURL) {
		$pathInfo = pathinfo($pictureURL);

		return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . self::$thumbPostfix . '.' . $pathInfo['extension'];
	}

	/** 
	 * Setter for the minimum image resolution
	 *
	 * @param array the resolution index 0 should be with, and 1 should be height
	 */
	public function setMinRes($res) {
		$this->minRes = $res;
	}

	/** 
	 * Setter for the maximum image resolution
	 *
	 * @param array the resolution index 0 should be with, and 1 should be height
	 */
	public function setMaxRes($res) {
		$this->maxRes = $res;
	}

	/**
	 * Getter for the tumb url
	 *
	 * @return string the thumb url
	 * @throws Exception if no thumb is generated
	 */
	public function getThumbURL() {
		if($this->thumbGenerated) {
			return $this->uploadURLDIR . $this->fileName . self::$thumbPostfix . '.' . $this->fileExt;
		} else {
			throw new Exception('Thumb is not generated yet. Call genThumb()');
		}
	}

	/**
	 * Generates the thumbnail. Scales from the thumbnail width - image width ratio
	 *
	 * @return string the path to the thumbnail
	 * @throws Exception if the file extension is not supported
	 */
	public function genThumb() {
		$ratio = $this->thumbWidthRes / $this->res[0];
		$thumbHeight = round($this->res[1] * $ratio);
		$thumbPath =  $this->uploadDir . $this->fileName . self::$thumbPostfix . '.' . $this->fileExt;
		if($this->fileExt == 'jpg' || $this->fileExt == 'jpeg') {
			$origImage = imagecreatefromjpeg($this->fullPath);
		} else if($this->fileExt == 'png') { 
			$origImage = imagecreatefrompng($this->fullPath);
		} else {
			throw new Exception('Only thumbnails from jpg or png is supported');
		}

		$image = imagecreatetruecolor($this->thumbWidthRes, $thumbHeight);
		$thumb = imagecopyresized($image, $origImage, 0, 0, 0, 0, $this->thumbWidthRes, $thumbHeight, $this->res[0], $this->res[1]);  

		if($this->fileExt == 'jpg' || $this->fileExt == 'jpeg') {
			$thumbImage = imagejpeg($image, $thumbPath);
		} else if($this->fileExt == 'png') {
			$thumbImage = imagepng($image, $thumbPath);
		}
		$this->thumbGenerated = true;

		return $thumbPath;
	}
}
