<?php
/*
demo for the PHasher class.
There's a special circle of Hell for code like this I'm sure.
*/
class PHasher_Test {

	protected $I;
	protected $tests;
	
	function __construct(){

		require_once('../phasher.class.php');
		
		$this->I = PHasher::Instance();
		
		// this is inverted. WHY DID I DO THIS? 
		$this->tests = array(
			"original image, with hash",
			"duplicate reduced 50%",
			"compared with itself",
			"duplicate reduced 50% and rotated 90 degrees. The hash also rotated -- it isn't perfect but its close.",
			"Comparing the original image with the reduced size one. ",
			"Now comparing the hashes of the original image, and one reduced and rotated 90 degrees.",
			"Comparing an image with a desaturated version."
			
		);
		
		if(isset($_POST['test'])){
			switch(intval($_POST['test'])){
				//case 7:  $this->Test($this->tests[7], 'IsNegative', array('monalisa2.jpg','monalisa5.jpg')); break;
				case 6:  $this->Compare($this->tests[6], 'monalisa2.jpg', 'monalisa4.jpg'); break;
				case 5:  $this->Compare($this->tests[5], 'monalisa.jpg', 'monalisa3.jpg'); break;
				case 4:  $this->Compare($this->tests[4], 'monalisa.jpg', 'monalisa2.jpg'); break;
				case 3:	 $this->Show($this->tests[3], "monalisa3.jpg"); break;
				case 2:  $this->Compare($this->tests[2], 'monalisa2.jpg', 'monalisa2.jpg'); break;
				case 1:  $this->Show($this->tests[1], "monalisa2.jpg"); break;
				default: $this->Show($this->tests[0], "monalisa.jpg"); break;
			}
		}
		else{


echo <<<EOD
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<div class="demo-return">
<UL>
EOD;

foreach($this->tests as $key=>$val){
	echo "<LI class='test-link'><a href='#' data-test=$key>$val</a></LI>";
}

ECHO <<<EOD
</UL>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
<script type="text/javascript">
$(window).on('load', function(){
	$("#demo-menu").load("index.php", function(){
		$("li.test-link").each(function(i,val){
			$(this).find('a').on('click', function(e){
				var datatest = $(this).data('test');
				e.preventDefault();
				var rundemo =  $.ajax({
					"url": "index.php",
					"type": "POST",
					"data": {
						"test": datatest
					}
				});
				rundemo.success(function(data){
					var obj = $.parseJSON(data);
					console.log(obj);
					var content = $("<div></div>");
					content.append(obj.text);
					content.append('<hr>');
					for(t=0; t<=obj.images.length-1; t++){
						content.append("<img src=\""+obj.images[t]+"\">");
					}
					for(t=0; t<=obj.results.length-1; t++){
						content.append("<div>"+obj.results[t]+"</div>");
					}
					$('#phash').html(content);
				});
			})
		});
	});
});
</script>

<div id="demo-menu"></div>
<div id="phash"></div>
</body>
</html>
EOD;
		}
	}

	function Show($text, $file){
		
		$hash = $this->I->HashImage($file);
		
		$result = array(
			"text" => $text,
			"images" => array($file),
			"results" => array(
				"hex: ".$this->I->HashAsString($hash),
				"bin: ".$this->I->HashAsString($hash, false),
				"visual: ".$this->I->HashAsTable($hash)
			)
		);
		
		$result = json_encode($result);
		echo $result;
	}

	function Compare($text, $file1, $file2){
		global $I;
		$hash1 = $this->I->HashImage($file1);
		$hash2 = $this->I->HashImage($file2);
		$result0 = $this->I->Compare($file1, $file2);
		$result90 = $this->I->Compare($file1, $file2, 90);
		$result180 = $this->I->Compare($file1, $file2, 180);
		$result270 = $this->I->Compare($file1, $file2, 270);
		$result = array(
			"text" => $text,
			"images" => array($file1, $file2),
			"results" => array($this->I->HashAsTable($hash1), $this->I->HashAsTable($hash2), "Comparison Result: @0: $result0, @90: $result90, @180: $result180, @270: $result270")
		);
		$result = json_encode($result);
		echo $result;
	}
	
}

$test = new PHasher_Test();