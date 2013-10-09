<UL><?php

// testing the CompareStrings method
require_once('../phasher.class.php');
		
$I = PHasher::Instance();

// get a hash string for each image
?> <LI> monalisa.jpg <?php 
$str1 = $I->HashAsString($I->HashImage('monalisa.jpg'));
echo print_r($str1,true);
 
?></LI><LI> monalisa2.jpg <?php 
$str2 = $I->HashAsString($I->HashImage('monalisa2.jpg'));
echo print_r($str2, true); 

// compare them..

?></LI><LI> comparison: <?php
$comp = $I->CompareStrings($str1, $str2);
echo print_r($comp, true);

?></LI></UL>


