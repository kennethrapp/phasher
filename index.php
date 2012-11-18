<?php
/* a demo */
require_once('phasher.class.php');

$I = PHasher::Instance();

function Show($text, $file){
	global $I;
	echo "<hr>$text<br>";
	$hash = $I->HashImage($file);
	echo $I->HashAsTable($hash);
	echo $I->HashAsString($hash);
}

function Compare($text, $file1, $file2){
	global $I;
	echo "<hr>$text<br>";
	$result = $I->Compare($file1, $file2);
	$result90 = $I->Compare($file1, $file2, 90);
	$result180 = $I->Compare($file1, $file2, 180);
	$result270 = $I->Compare($file1, $file2, 270);
	echo "<br> Result: @0: $result, @90: $result90, @180: $result180, @270: $result270<br>";
}

Show("original image, with hash", "monalisa.jpg");
Show("duplicate reduced 50%", "monalisa2.jpg");
Show("duplicate reduced 50% and rotated 90 degrees. It isn't perfect but its close.", "monalisa3.jpg");

Compare('Comparing the original image with the reduced size one, at 90 degree intervals. Since the hashes are the same the correct result should approach 1', 'monalisa.jpg', 'monalisa2.jpg');
Compare('Now comparing the hashes of the original image, and one reduced and rotated 90 degrees.', 'monalisa.jpg', 'monalisa3.jpg');