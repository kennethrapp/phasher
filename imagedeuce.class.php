<?PHP
/* need to shrink, desat then hash.
	
*/
class ImageDeuce{

private static $Instance;
	private $filetype_data = array();
	private $mimetype_data = array();
	private $files = array();
	
	private function __construct(){
	}
	
	public static function Instance(){
		 if (is_null(self::$Instance)){
			self::$Instance = new self();
		}
		return self::$Instance;
	}
	
	
	private function Reduce($res, $resourcesize = 8){
		$res = $this->NormalizeAsResource($res);
		$rescached = imagecreatetruecolor($resourcesize, $resourcesize);
		imagecopyresampled($rescached, $res, 0, 0, 0, 0, $resourcesize, $resourcesize, imagesx($res), imagesy($res));
		$rescached = $this->Desaturate($rescached);
		imagedestroy($res);
		return $rescached;
	}
	
	
	public function ImageHash($res, $size = 8, $rot = 0, $mir=0){
		
		$res = $this->NormalizeAsResource($res); // make sure this is a resource
		$res = $this->Reduce($res, $size);	// reduce and desaturate the image
		
		$w = imagesx($res);
		$h = imagesy($res);
		$index=0;
		$pixels = array();
		
		// flatten the image into a simple array of pixel values
		// only handles 90 degree rotations
		
		if(in_array($rot, Array(0, 90, 180, 270))){
					
			for($i = 0;$i < $w ; $i++) {
		
				for($j = 0;$j < $h ; $j++) {
				/**/
					// transform for rotation
					switch($rot){
						case 90:	$rx=(($h-1)-$j);	$ry=$i;			break;
						case 180:	$rx=($w-$i)-1;		$ry=($h-1)-$j;	break;
						case 270:	$rx=$j;				$ry=($h-$i)-1;	break;
						default:	$rx=$i;				$ry=$j;
					}
					
					// transform for mirror/flip
					switch($mir){
						case 1: $rx = (($w-$rx)-1); break;
						case 2: $ry = ($h-$ry); 	break;
						case 3: $rx = (($w-$rx)-1);
								$ry = ($h-$ry); 	break;
						default: 					break;
					} 
					
					/* 	instead of actually rotating the image, we want to capture the pixels from a rotated
						position. We will need to build a rotated canvas and place the image on it first. 
						
					list($rx,$ry) = $this->RotateXY($rot,$j,$i); // kinda but doesn't quite work yet*/
					
					$pixels[$index] = imagecolorat($res,$rx,$ry);
					$index++;
				}
			}
		
		
		// find the average value in the array
		$avg = floor(array_sum($pixels) / count(array_filter($pixels)));
		
		// create a hash (1 for pixels above the mean, 0 for average or below)
		$index = 0;
		
		foreach($pixels as $px){
			if($px > $avg){
				$hash[$index] = 1;
			}
			else{
				$hash[$index] = 0;
			}
			$index += 1;
		}
			// return the array as a string 
			return implode(null,$hash);
		}
		
		else return false;
	}
	
	//http://www.php.net/manual/en/function.imagefill.php
	public function MakeCanvas($w, $h, $rot=0, $color=array(0,0,0)){
		if($rot > 0){
			$rot %= 360;
			$rd = $this->GetRotatedCanvasSize($w, $h, $rot);
			$w = $rd[0];
			$h = $rd[1];
		}
		$canvas = imagecreatetruecolor($w, $h);
		$bgcolor = imagecolorallocate($canvas, $color[0], $color[1], $color[2]);
		imagefill($canvas,0,0,$bgcolor);
		return $canvas;
	}
	
	/*	dev at imglib dot endofinternet dot net http://www.php.net/manual/en/function.imagerotate.php#93151
		this just returns new dimensions for a canvas of w,h rotated rot degrees */
	
	public function GetRotatedCanvasSize($srcw, $srch, $angle){
		$theta = deg2rad ($angle);
		function rotateX($x, $y, $theta){
			return $x * cos($theta) - $y * sin($theta);
		}
		function rotateY($x, $y, $theta){
			return $x * sin($theta) + $y * cos($theta);
		}
		// Calculate the width of the destination image.
        $temp = array (rotateX(0, 0, 0-$theta), rotateX($srcw, 0, 0-$theta), rotateX(0, $srch, 0-$theta), rotateX($srcw, $srch, 0-$theta));
        $minX = floor(min($temp));
        $maxX = ceil(max($temp));
        $width = $maxX - $minX;
        // Calculate the height of the destination image.
        $temp = array (rotateY(0, 0, 0-$theta), rotateY($srcw, 0, 0-$theta), rotateY(0, $srch, 0-$theta), rotateY($srcw, $srch, 0-$theta));
        $minY = floor(min($temp));
        $maxY = ceil(max($temp));
        $height = $maxY - $minY;
		return array($width, $height);
	}
	
	public function GetDimensions($res){
		$res = $this->NormalizeAsResource($res);
		return array(imagesx($res),imagesy($res));
	}
	
	function RotateXY($angle, $x, $y){
		$rx = (($x*cos($angle)) - ($y*sin($angle)));
		$ry = (($x*sin($angle)) + ($y*cos($angle)));
		return array($rx, $ry);
	}
	
	
	/* if $resource is a filename pointing to an image, make it an image resource. Otherwise
		return the resource. */
		
	private function NormalizeAsResource($resource){
		if(gettype($resource) == 'resource'){
			return $resource;
		}
		else{
			if(file_exists(realpath($resource)) &&  getimagesize($resource)){
				return imagecreatefromstring(file_get_contents($resource));
			}
		}
	}
	
	//http://php.about.com/od/gdlibrary/ss/grayscale_gd.htm
	public function Desaturate($res){
		$res = $this->NormalizeAsResource($res);
		if(!function_exists('yiq')){
			function yiq($r,$g,$b) {
				return (($r*0.299)+($g*0.587)+($b*0.114));
			} 
		}
		
		$resource = array();
		$i = 0;

		$width  = imagesx($res);
		$height = imagesy($res);
		
		$canvas = imagecreate($width, $height); 
	 
		for ($c=0;$c<256;$c++) {
			$palette[$c] = imagecolorallocate($canvas,$c,$c,$c);
		}
		
		for($y=0; $y<$height; $y++){
			for($x=0; $x<$width; $x++){
				$rgb = imagecolorat($res,$x,$y);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				$gs = yiq($r,$g,$b);
				imagesetpixel($canvas,$x,$y,$palette[$gs]);
				$i+=1;
			}
		} 
		
		return $canvas;		
	}
	
	public function HashAsTable($hash, $size=8, $cellsize=10){

	$index = 0;
	$table = "<table cellpadding=\"0\" cellspacing=\"0\" style=\"display:inline-block;border:1px solid #000;margin:1px;\"><tr><td\<tbody>";
	
	for($x=0; $x<=$size; $x++){
	
		$table.="<tr>";
		
		for($y=0; $y<=$size; $y++){
		
			$bit = (bool)(substr($hash, $index,1));
			$bitcolor = ($bit)?"#ddd":"#000";
			$abitcolor = ($bit)?"#000":"#fff";
			$sizepx = $size."px";
			$style="width:$sizepx;height:$sizepx;background-color:$bitcolor;color:$abitcolor;text-align:center;padding:1px;";
			$table.="<td style=\"$style\">".(($bit)?'1':'0')."</td>";
			
			$index++;
		}
	
		$table.="</tr>";
	}
	
	$table.="</tbody></table>";
	
	return $table;
}


}
