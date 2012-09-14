<?php


class Test{

	public $images = array();
	public $palette = array();
	public $dir = null;

	function __construct($dir){

		$this->dir=$dir;


		for($t=0; $t<=mt_rand(64,255); $t++){
			$this->palette[] = $this->AllocateRandomColor($this->image);
		}

		for($t=0; $t<=mt_rand(64,255); $t++){
			$this->images[] = $this->MakeCanvas();
		}

		// stuff

		foreach($this->images as $image){
			$this->ExportImage($this->dir);
		}

	}

	function RandomColor(){
		$rgb = array();
		for($c=0; $c<=2; $c++){
			$rgb[]=mt_rand(0,255);
		}

		return $rgb;
	}

	function AllocateRandomColor($i){
		$arr = RandomColor();
		$arr = array_unshift($arr, $i);
		return call_user_func_array('imagecolorallocate', $arr);

	}

	function MakeCanvas(){

		$w = mt_rand(100, 1000);
		$h = mt_rand(100, 1000);
		$this->images[] = imagecreatetruecolor($w,$h);

		$color = $this->GetRandomColor();

		imagefill($this->image, 0, 0, $color);

	}

	function GetRandomColor(){
		return $this->palette[mt_rand(0,count($this->palette))];
	}

	function ExportImage($dir=''){

		$methods = array('imagepng', 'imagejpeg', 'imagegif');
		$imagesave = $methods[mt_rand(0, count($methods))];
		$filename = md5(uniqid().mt_rand());

		$i = array_shift($this->images);
		$imagesave($i, $dir.$filename);

	}
}

