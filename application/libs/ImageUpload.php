<?php

class ImageUpload extends FileUpload {
	protected $thumbWidthRes = 100;
	protected $thumbPostfix = '_thumb';
	protected $res;
	protected $minRes = null;
	protected $maxRes = null;
	protected $thumbGenerated = false;

	public function __construct($file, $path) {
		parent::__construct($file, $path);
		if ((extension_loaded('gd') && function_exists('gd_info')) == false) {
			throw new Exception('The GD-library was not detected on the server');
		}	
	}
	
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

	public function setMinRes($res) {
		$this->minRes = $res;
	}

	public function setMaxRes($res) {
		$this->maxRes = $res;
	}

	public function getThumbURL() {
		if($this->thumbGenerated) {
			return $this->uploadURLDIR . $this->fileName . $this->thumbPostfix . '.' . $this->fileExt;
		} else {
			throw new Exception('Thumb is not generated yet. Call genThumb()');
		}
	}

	public function genThumb() {
		$ratio = $this->thumbWidthRes / $this->res[0];
		$thumbHeight = round($this->res[1] * $ratio);
		$thumbPath =  $this->uploadDir . $this->fileName . $this->thumbPostfix . '.' . $this->fileExt;

		if($this->fileExt == 'jpg' || $this->fileExt == 'jpeg') {
			$origImage = imagecreatefromjpeg($this->fullPath);
		} elseif($this->fileExt == 'png') {
			$origImage = imagecreatefrompng($this->fullPath);
		} else {
			throw new Exception('Only thumbnails from jpg or png is supported');
		}

		$image = imagecreatetruecolor($this->thumbWidthRes, $thumbHeight);
		$thumb = imagecopyresized($image, $origImage, 0, 0, 0, 0, $this->thumbWidthRes, $thumbHeight, $this->res[0], $this->res[1]);  

		if($this->fileExt == 'jpg' || $this->fileExt == 'jpeg') {
			$thumbImage = imagepng($image, $thumbPath);
		}
		$this->thumbGenerated = true;

		return $thumbPath;
	}
}
