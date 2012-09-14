<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
Tests for the image hash method.
<hr>
<pre>
<?php
$start = microtime(1);

require_once('../imagedeuce.class.php');

$I = ImageDeuce::Instance();


/*
$hashes = array(
	$I->ImageHash($file, 0, 0),
	$I->ImageHash($file, 90, 0),
	$I->ImageHash($file, 180, 0),
	$I->ImageHash($file, 270, 0),
	$I->ImageHash($file, 0, 1),
	$I->ImageHash($file, 90, 1),
	$I->ImageHash($file, 180, 1),
	$I->ImageHash($file, 270, 1),
	$I->ImageHash($file, 0, 2),
	$I->ImageHash($file, 90, 2),
	$I->ImageHash($file, 180, 2),
	$I->ImageHash($file, 270, 2),
	$I->ImageHash($file, 0, 3),
	$I->ImageHash($file, 90, 3),
	$I->ImageHash($file, 180, 3),
	$I->ImageHash($file, 270, 3)
);

echo "<pre> Hashes for $file: ".print_r($hashes, true)."</pre>"; */
$hashsize = 8;

$file = 'images/Alyson_Hannigan_200512.jpg';
echo 'original image and hash<br>';
echo '<img src="'.$file.'">';
$hash = $I->ImageHash($file, $hashsize);
echo $I->HashAsTable($hash, $hashsize);

echo '<hr>';
echo 'original image resized by 100px and hash<br>';
$file = 'images/Alyson_Hannigan_200512_02.jpg';
echo '<img src="'.$file.'">';
$hash = $I->ImageHash($file, $hashsize);
echo $I->HashAsTable($hash, $hashsize);

echo '<hr>';

$file = 'images/Alyson_Hannigan_200512_rot.jpg';
echo 'original image rotated 90 degrees and hash<br>';
echo '<img src="'.$file.'">';
$hash = $I->ImageHash($file, $hashsize);
echo $I->HashAsTable($hash, $hashsize);


$file = 'images/Alyson_Hannigan_200512_rot.jpg';
echo 'original image with the 90 degree rotated hash pregenerated<br>';
echo '<img src="'.$file.'">';
$hash = $I->ImageHash($file, $hashsize, 90);
echo $I->HashAsTable($hash, $hashsize);

$end = microtime(1);
	
$time = $end-$start;
$mem = memory_get_peak_usage(1);

echo "<hr>time: $time peak $mem";



?>
</pre>
</body>
</html>