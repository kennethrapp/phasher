<?php
require_once('../imagedeuce.class.php');
$file = 'images/132669276745.jpg';

$I = ImageDeuce::Instance();

$D = $I->Desaturate($file);
header('Content-Type: image/jpeg');
imagejpeg($D);
imagedestroy($D);