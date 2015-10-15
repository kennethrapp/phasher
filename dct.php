<?php

function grayscale($img){
	if(function_exists('imagefilter')){
		imagefilter($img, IMG_FILTER_GRAYSCALE);
		return $img;
	}
	else{
		$imgw = imagesx($img);
		$imgh = imagesy($img);
		for ($i=0; $i<$imgw; $i++){
        	for ($j=0; $j<$imgh; $j++){
               $rgb = imagecolorat($img, $i, $j);
                $rr = ($rgb >> 16) & 0xFF;
                $gg = ($rgb >> 8) & 0xFF;
                $bb = $rgb & 0xFF;        
                $g = round(($rr + $gg + $bb) / 3);
                $val = imagecolorallocate($img, $g, $g, $g);
                imagesetpixel ($img, $i, $j, $val);
        	}
		}
		return $img;
	}	
}

function resizetosquare($img_original, $size){
	$img = imagecreatetruecolor($size, $size);
    imagecopyresized($img, $img_original, 0, 0, 0, 0, $size, $size, imagesx($img_original), imagesy($img_original));
    return $img;
}



// discrete cosine transform by jey http://stackoverflow.com/users/1976843/jey
// http://stackoverflow.com/questions/14106984/how-to-calculate-discrete-cosine-transform-dct-in-php

function dct1D($in){
    $results = array();
    $N = count($in);
    for($k = 0; $k < $N; $k++){
        $sum = 0;
        for($n = 0; $n < $N; $n++){
            $sum += $in[$k] * cos($k * pi() * (2 * $n + 1) / $N);
        }
        $sum *= sqrt(2 / $N);
        if($k == 0){
            $sum *= 1 / sqrt(2);
        }
        $results[$k] = $sum;
    }
    return $results;
}


function optimizedImgDTC($img){

    $results = array();

    $N1 = imagesx($img);
    $N2 = imagesy($img);

    $rows = array();
    $row = array();

    for($j = 0; $j < $N2; $j++){
        for($i = 0; $i < $N1; $i++)
            $row[$i] = imagecolorat($img, $i, $j);
        $rows[$j] = dct1D($row);
    }

    for($i = 0; $i < $N1; $i++){
        for($j = 0; $j < $N2; $j++)
            $col[$j] = $rows[$j][$i];
        $results[$i] = dct1D($col);
    }

    return $results;
}

function array_average($arr){
	return array_sum($arr)/count($arr);
}

function pHashWithDCT($file){
	$hash=array();
	$image = imagecreatefromstring(file_get_contents($file));
	$image = resizetosquare($image, 8);
	$image = grayscale($image);
	
	$dct = optimizedImgDTC($image);
	foreach($dct as $key=>$val){
		$avg = array_average($val);
		foreach($dct[$key] as $bit=>$bit_val){
			if($bit_val < $avg){
				array_push($hash, 0);
			}
			else{
				array_push($hash, 1);
			}
		}
	} 

	return $hash;
}


function hashAsImage($phash, $w=64, $h=64, $cell=8, $rgb_on = array(0,255,0), $rgb_off=array(0,0,0), $type='image/png'){

	$image = imagecreatetruecolor($w, $h);
	$white = imagecolorallocate($image, $rgb_on[0],  $rgb_on[1],  $rgb_on[2]);
	$black = imagecolorallocate($image, $rgb_off[0], $rgb_off[1], $rgb_off[2]);

	imagefill($image, 0, 0, $black);

	for($x = 0; $x < $w; $x+=$cell){
		for($y=0; $y < $h; $y+=$cell){
			$bit = array_shift($phash);
			if($bit === 1){
				imagefilledrectangle($image, $x, $y, $x+$cell, $y+$cell, $white);
			}
		}
	}

	return $image;
}

$image = "demo/monalisa.jpg";

$hash = pHashWithDCT($image);
$name = md5_file($image).'.jpg';
imagejpeg(hashAsImage($hash, 64, 64, 8), $name);

echo '<img src="'.$image.'"> <img src="'.$name.'">';