<?php
require_once('test.php');

$image = $images['test'];
$res = imagecreatefromstring(file_get_contents($image));
$scale = 8;

$P = PHasher::Instance();
$hash = $P->FastHashImage($res);
$str = $P->HashAsString($hash);
$strhex = $P->HashAsString($hash, true);

echo "Hash as string: $str <br> Hash as hex: $strhex";

echo '<pre>'.print_r($hash,true).'</pre>';

