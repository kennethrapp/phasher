<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
How hashing works with effected images...
<hr>
<pre>
<?php

require_once('test.php');

echo '<hr>';
$file1 = $images['scaled_down'];
$file2 = $images['effect_negative'];
echo 'With negative image - each hash is the inverse of the other. <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
$result90 = $I->Compare($file1, $file2, 90);
$result180 = $I->Compare($file1, $file2, 180);
$result270 = $I->Compare($file1, $file2, 270);
echo "<br> Result: 0: $result, 90: $result90, 180: $result180, 270: $result270<br>";

echo '<hr>';
$file1 = $images['scaled_down'];
$file2 = $images['effect_high_contrast'];
echo 'With high contrast shift <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
$result90 = $I->Compare($file1, $file2, 90);
$result180 = $I->Compare($file1, $file2, 180);
$result270 = $I->Compare($file1, $file2, 270);
echo "<br> Result: 0: $result, 90: $result90, 180: $result180, 270: $result270<br>";

echo '<hr>';
$file1 = $images['scaled_down'];
$file2 = $images['effect_low_gamma'];
echo 'With low gamma - worst of the lot (this is where knowing how to into discrete cosine transform would be handy. ) <br>';
echo '<img src="'.$file1.'">';
echo '<img src="'.$file2.'">';
$result = $I->Compare($file1, $file2);
$result90 = $I->Compare($file1, $file2, 90);
$result180 = $I->Compare($file1, $file2, 180);
$result270 = $I->Compare($file1, $file2, 270);
echo "<br> Result: 0: $result, 90: $result90, 180: $result180, 270: $result270<br>";


$end = microtime(1);
	
$time = $end-$start;
$mem = memory_get_peak_usage(1);

echo "<hr>time: $time peak mem: $mem";



?>
</pre>
</body>
</html>