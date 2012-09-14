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
	
	/* takes an image, shrinks it to a square, then desaturates it. 
		turns out desaturate doesn't actually help though. 
		http://www.scratchapixel.com/lessons/2d-image-processing/dct/
		*/
	
	private function Reduce($res, $resourcesize = 8){
		$res = $this->NormalizeAsResource($res);
		$rescached = imagecreatetruecolor($resourcesize, $resourcesize);
		imagecopyresampled($rescached, $res, 0, 0, 0, 0, $resourcesize, $resourcesize, imagesx($res), imagesy($res));
		$rescached = $this->Desaturate($rescached);
		
		/*

		*/
		
		//imagepng($rescached, 'hash_'.mt_rand(0,10).'.png');
		imagedestroy($res);
		return $rescached;
	}
	
	/* build a perceptual hash out of an image. 
		http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html */
		
	public function ImageHash($res, $size = 8){
		
		$res = $this->NormalizeAsResource($res); // make sure this is a resource
		$res = $this->Reduce($res, $size);	// reduce and desaturate the image
		
		$w = imagesx($res);
		$h = imagesy($res);
		$index=0;
		$pixels = array();
		
		
		for($y = 0;$y < $w ; $y++) {
			for($x = 0;$x < $h; $x++) {
				$pixels[$index] = imagecolorat($res,$x,$y);
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
		
		for($x=0; $x<=$size; $x++){
		
			$table.="<tr>";
			
			for($y=0; $y<=$size; $y++){
			
				$bit = (bool)(substr($hash, $index,1));
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
