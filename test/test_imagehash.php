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

$hashsize = 8;

$file = 'images/Alyson_Hannigan_200512.jpg';
echo 'original image and hash<br>';
echo '<img src="'.$file.'">';
$hash = $I->HashImage($file);
echo $I->HashAsTable($hash);

echo '<hr>';
echo 'original image resized by 100px and hash<br>';
$file = 'images/Alyson_Hannigan_200512_02.jpg';
echo '<img src="'.$file.'">';
$hash = $I->HashImage($file);
echo $I->HashAsTable($hash);

echo '<hr>';

$file = 'images/Alyson_Hannigan_200512_rot.jpg';
echo 'original image rotated 90 degrees clockwise and hash<br>';
echo '<img src="'.$file.'">';
$hash = $I->HashImage($file);
echo $I->HashAsTable($hash);
echo '<hr>';

$file = 'images/Alyson_Hannigan_200512_02.jpg';
echo 'original image with pregenerated rotated hashes (90, 180, 270.)  <br>';
echo '<img src="'.$file.'">';

$hash90 = $I->HashImage($file, 90);
$hash180 = $I->HashImage($file, 180);
$hash270 = $I->HashImage($file, 270);

echo $I->HashAsTable($hash90);
echo $I->HashAsTable($hash180);
echo $I->HashAsTable($hash270);

echo '<hr>';
$file1 = 'images/Alyson_Hannigan_200512_02.jpg';
$file2 = 'images/Alyson_Hannigan_200512.jpg';
echo 'Comparing the original image with the reduced size one. Since the hashes are the same the result should approach 1. <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
echo "<br>Result: $result";

echo '<hr>';
$file1 = 'images/Alyson_Hannigan_200512.jpg';
$file2 = 'images/Alyson_Hannigan_200512_rot.jpg';
echo 'Comparing the original image with the rotated one, passing rotations as arguments. <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
$result90 = $I->Compare($file1, $file2, 90);
$result180 = $I->Compare($file1, $file2, 180);
$result270 = $I->Compare($file1, $file2, 270);
echo "<br> Result: 0: $result, 90: $result90, 180: $result180, 270: $result270<br>";


echo '<hr>';
$file1 = 'images/Alyson_Hannigan_200512.jpg';
$file2 = 'images/Alyson_Hannigan_200512_rot2.jpg';
echo 'Repeating the above test with a 180 degree rotated image <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
$result90 = $I->Compare($file1, $file2, 90);
$result180 = $I->Compare($file1, $file2, 180);
$result270 = $I->Compare($file1, $file2, 270);
echo "<br> Result: 0: $result, 90: $result90, 180: $result180, 270: $result270<br>";

echo '<hr>';
$file1 = 'images/Alyson_Hannigan_200512.jpg';
$file2 = 'images/Alyson_Hannigan_200512_rot3.jpg';
echo 'Repeating again with a 270 degree rotated image <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
$result90 = $I->Compare($file1, $file2, 90);
$result180 = $I->Compare($file1, $file2, 180);
$result270 = $I->Compare($file1, $file2, 270);
echo "<br> Result: 0: $result, 90: $result90, 180: $result180, 270: $result270<br>";

echo '<hr>';
$file1 = 'images/Alyson_Hannigan_200512.jpg';
$file2 = 'images/Alyson_Hannigan_200512_rot3.jpg';
echo 'Test of the detection method -- should return the highest match after comparing rotations <br>( won\'t tell us the matched rotation yet)<br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Detect($file1, $file2);
echo "<br> Result: $result<br>";

echo '<hr>';
$file1 = 'images/Alyson_Hannigan_200512_02.jpg';
$file2 = 'images/Alyson_Hannigan_200512_rot3.jpg';
echo '<br>Test of the detection method again -- but with the reduced image<br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Detect($file1, $file2);
echo "<br> Result: $result<br>";

$end = microtime(1);
	
$time = $end-$start;
$mem = memory_get_peak_usage(1);

echo "<hr>time: $time peak mem: $mem";



?>
</pre>
</body>
</html>