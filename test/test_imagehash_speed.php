<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
Comparison of speed over multiple iterations of FastHashImage and HashImage. Currently, the iteration time appears to be about 1 second per hash so the script time limit is disabled here so it can actually run. <br>
Currently over 100 iterations the total time is 93 seconds for FastHashImage and 99 seconds for HashImage. So FastHashImage is faster, but overall the hashing method is too slow for it to be of any value... 
<hr>
<pre>
<?php


require_once('../imagedeuce.class.php');

$I = ImageDeuce::Instance();

$file = 'images/Alyson_Hannigan_200512.jpg';

$gstart = microtime(1);

echo "<div>100 iterations of FastHashImage: </div>";
for($l=0; $l<=100; $l++){
	set_time_limit(0);
	$hash = $I->FastHashImage($file);
}

$gend = microtime(1);
$gtime = $gend-$gstart;
$gpeak = memory_get_peak_usage(1);

echo "Total runtime: $gtime peak mem: $gpeak";

echo "<br><br><div>100 iterations of HashImage: </div>";

for($l=0; $l<=100; $l++){
	set_time_limit(0);
	$hash = $I->HashImage($file);
}

$gend = microtime(1);
$gtime = $gend-$gstart;
$gpeak = memory_get_peak_usage(1);

echo "Total runtime: $gtime peak mem: $gpeak";

?>
</pre>
</body>
</html>