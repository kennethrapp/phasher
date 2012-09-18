<?php
require_once('test.php');

$res = imagecreatefromstring(file_get_contents($images['test']));
$scale = 64;

/*
	heavily from a bicubic resampling function by an unknown author here: http://php.net/manual/en/function.imagecopyresampled.php#78049
	this will scale down, desaturate and hash an image entirely in memory without the intermediate steps of altering the image and
	re-reading pixel data. 
	
	Ironically... I don't know if it's that much faster. But it does work. 
*/

function FastImageHashResample($src_img, $scale){

	$hash = array();
	$src_w = imagesx($src_img);
	$src_h = imagesy($src_img);
	
    $rX = $src_w / $scale;
    $rY = $src_h / $scale;
    $w = 0;
    for ($y = 0; $y < $scale; $y++)  {
        $ow = $w; $w = round(($y + 1) * $rY);
        $t = 0;
        for ($x = 0; $x < $scale; $x++)  {
            $r = $g = $b = 0; $a = 0;
            $ot = $t; $t = round(($x + 1) * $rX);
            for ($u = 0; $u < ($w - $ow); $u++)  {
                for ($p = 0; $p < ($t - $ot); $p++)  {
				
					$rgb = imagecolorat($src_img, $ot + $p, $ow + $u);
					
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					
					$gs = floor((($r*0.299)+($g*0.587)+($b*0.114))); 
					$hash[$x][$y] = $gs;
                }
				
            }
        }
    }
	
	// reset all the indexes. 
	$nhash = array();

	$xnormal=0;

	foreach($hash as $xkey=>$xval){
		foreach($hash[$xkey] as $ykey=>$yval){
			unset($hash[$xkey]);
			$nhash[$xnormal][] = $yval;
		}
		$xnormal++;
	}
	
	return $nhash;
}

$hash = FastImageHashResample($res, $scale);

$Z = imagecreatetruecolor($scale,$scale);


/* display the content of the array as an image */

for($x=0; $x<$scale; $x++){
	$avg = floor(array_sum($hash[$x]) / count(array_filter($hash[$x])));
	for($y=0; $y<$scale; $y++){
		$rgb = $hash[$x][$y];
		if($rgb > $avg){
			$c = imagecolorallocate($Z, 255, 255, 255);
		}
		else{
			$c = imagecolorallocate($Z, 0, 0, 0);
		}
		imagesetpixel($Z, $x, $y, $c);
	}
}

header('Content-Type: image/png');
imagepng($Z);
imagedestroy($Z);