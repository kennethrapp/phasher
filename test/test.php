<?php

$start = microtime(1);
require_once('../phasher.class.php');

$I = PHasher::Instance();

$images = array(
	'test' => 'images/Alyson_Hannigan_200512.jpg',
	'scaled_down' => 'images/Alyson_Hannigan_200512_02.jpg',
	'rotated_90' => 'images/Alyson_Hannigan_200512_rot.jpg',
	'rotated_180' => 'images/Alyson_Hannigan_200512_rot2.jpg',
	'rotated_270' => 'images/Alyson_Hannigan_200512_rot3.jpg',
	'effect_negative' => 'images/20_215002_naginnaH_nosylA.jpg',
	'effect_high_contrast' => 'images/Alyson_Hannigan_hicon.jpg',
	'effect_low_gamma' => 'images/Alyson_Hannigan_lowgamma.jpg'
);

