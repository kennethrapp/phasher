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
	
	
	/* multi-pass comparison, returns the highest match after comparing rotations. */
	
	public function Detect($res1, $res2, $precision = 1){
		$hash1 = $this->HashImage($res1);
		$result = 0;
		for($rot=0; $rot<=270; $rot+=90){
			$new_result = $this->Compare($res1, $res2, $rot, $precision);
			if($new_result > $result){
				$result = $new_result;
			}
		}
		
		return $result;
	}
	
	/* hash two images and return an index of their similarty as a percentage. */
	
	public function Compare($res1, $res2, $rot=0, $precision = 1){
		
		$hash1 = $this->HashImage($res1); // this one should never be rotated
		
		$hash2 = $this->HashImage($res2, $rot);
		
		$similarity = count($hash1);
		
		// take the hamming distance between the hashes.
		foreach($hash1 as $key=>$val){
			if($hash1[$key] != $hash2[$key]){
				$similarity--;
			}
		}
		
		$percentage = round(($similarity/count($hash1)*100), $precision);
		
		return $percentage;
	}
	
	/* build a perceptual hash out of an image. Just uses averaging because it's faster.
		also we're storing the hash as an array of bits instead of a string. 
		http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html */
		
	public function HashImage($res, $rot=0, $mir=0, $size = 8){
		
		$res = $this->NormalizeAsResource($res); // make sure this is a resource
		$rescached = imagecreatetruecolor($size, $size);
		imagecopyresampled($rescached, $res, 0, 0, 0, 0, $size, $size, imagesx($res), imagesy($res));
		$res = $this->Desaturate($rescached);
		
		$w = imagesx($res);
		$h = imagesy($res);
		$index=0;
		$pixels = array();

		for($x = 0;$x < $w ; $x++) {
			for($y = 0;$y < $h; $y++) { 
				
				/* 	instead of rotating the image, we'll rotate the position of the pixels to allow us to generate a hash
					we can use to judge if one image is a rotated or flipped version of the other, without actually creating
					an extra image resource. This currently only works at all for 90 degree rotations and mirrors. */
					
				switch($rot){
					case 90:	$rx=(($h-1)-$y);	$ry=$x;			break;
					case 180:	$rx=($w-$x)-1;		$ry=($h-1)-$y;	break;
					case 270:	$rx=$y;				$ry=($h-$x)-1;	break;
					default:	$rx=$x;				$ry=$y;
				}
				
				switch($mir){
					case 1: $rx = (($w-$rx)-1); break;
					case 2: $ry = ($h-$ry); 	break;
					case 3: $rx = (($w-$rx)-1);
							$ry = ($h-$ry); 	break;
					default: 					break;
				}
			
				$pixels[$index] = imagecolorat($res, $rx, $ry);
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

		// return the array
		return $hash;
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
		
		$resource = array();
		$i = 0;

		$width  = imagesx($res);
		$height = imagesy($res);
		
		$canvas = imagecreate($width, $height); 
	 
		for ($c=0; $c<256; $c++) {
			$palette[$c] = imagecolorallocate($canvas, $c, $c, $c);
		}
		
		for($y=0; $y<$height; $y++){
			for($x=0; $x<$width; $x++){
				$rgb = imagecolorat($res,$x,$y);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				$gs = (($r*0.299)+($g*0.587)+($b*0.114));
				imagesetpixel($canvas,$x,$y,$palette[$gs]);
				$i+=1;
			}
		} 
		
		return $canvas;		
	}
	
	/* returns a binary hash as an html table, with each cell representing 1 or 0. */
	public function HashAsTable($hash, $size=8, $cellsize=10){
		
		$index = 0;
		$table = "<table cellpadding=\"0\" cellspacing=\"0\" style=\"display:inline-block;border:1px solid #000;margin:1px;\"><tr><td\<tbody>";
		for($x=0; $x<$size; $x++){
			$table.="<tr>";
			for($y=0; $y<$size; $y++){
				$bit = (bool)($hash[$index]);
				$bitcolor = ($bit)?"#ddd":"#000";
				$abitcolor = ($bit)?"#000":"#fff";
				$sizepx = $size."px";
				$style="width:$sizepx;height:$sizepx;background-color:$bitcolor;color:$abitcolor;text-align:center;padding:1px;";
				$table.="<td style=\"$style\"><img width=\"$size\" height=\"$size\" src=\"dot_clear.gif\"></td>";
				$index++;
			}
			$table.="</tr>";
		}
		$table.="</tbody></table>";
		return $table;
	}	


}
