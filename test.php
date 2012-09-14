<?PHP

require_once('image.class.php');

function getRandomImage($dir){
	$extarr = array('png','gif','jpg');
	$f = array();
	$i = 0;
	$maxbytes = 2097152;	
	if(is_dir($dir)){
		$files = scandir($dir);
		foreach ($files as $key => $value) {
			$p = pathinfo($value);
			$ext = $p['extension'];
			if (in_array($ext, $extarr)){
				$t = filesize($dir.'/'.$value);
				if($t <= $maxbytes){
					$f[$i] = $value;
					$i++;
				}
			}
		}
		$randfile = $f[array_rand($f)];
	}
	return $randfile;
}
	
$image1 = getRandomImage(getcwd());
$image2 = getRandomImage(getcwd());

$res1 = imagecreatefromstring(file_get_contents(getcwd().'\\'.$image1));
$res2 = imagecreatefromstring(file_get_contents(getcwd().'\\'.$image2));

$result = ImageCheck::TestSimilarity($res1, $res2);

echo '<pre> A uniqueness value less than 8 counts as a \'match\', and 1 for isinverse means it\'s a reversed version of the same image. 

<img src="'.$image1.'"><img src="'.$image2.'"><hr>'.print_r($result, true).'</pre>';