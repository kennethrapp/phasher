<?php
require_once('../imagedeuce.class.php');
$file = 'images/132669276745.jpg';

$I = ImageDeuce::Instance();
//$rotated = $I->Rotate($file, mt_rand(0, 360));
/*
list($w,$h) = $I->GetDimensions($file);
list($canvasw, $canvash) = $I->GetRotatedCanvasSize($w,$h, mt_rand(0,360));
$canvas = $I->MakeCanvas($canvasw, $canvash);
*/
$D = $I->Desaturate($file);
header('Content-Type: image/jpeg');
imagejpeg($D);
imagedestroy($D);