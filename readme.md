Perceptual hashing is a method to generate a hash of an image which allows multiple images to be compared by an index of similarity. You can find out more at [The Hacker Factor](http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html) and [phash.org](http://phash.org). 

I've extended this basic method into a class which can also compare images which have been rotated or flipped (but only in 90 degree increments.) It can also match images which have been color corrected (to a degree) or altered. This flexibility, though, does lend itself to false positives and it's ridiculously slow - currently it takes about a second to hash and compare images -- in production you would want to generate a cache of hashes for existing images, perhaps, and then compare the hash of an image to the hashes. 

PHasher is available under the MIT license. 

***
###Usage###

	include_once('phasher.class.php');
	$I = PHasher::Instance();

_yes. it's a singleton._

```
$I->HashImage($res, $rot=0, $mir=0, $size=8);
```

build a perceptual hash out of an image. 

- __$res__ is an image filename. 
- __$rot__ create the hash as if the image were rotated by this value. Default is 0, allowed values are 90, 180, 270.
- __$mir__ create the hash as though the image were mirrored and/or flipped. Default value is 0, 1 is mirrored, 2 is flipped, 3 is both mirrored and flipped. 

*this is currently still buggy.*

- __$size__ the size of the thumbnail created from the original image - the hash will be the square of this (so a value of 8 will build a hash out of an 8x8 image, of 64 bits.)

This returns an array of binary values representing the perceptual hash. This is done as an array to make it easier to process. 

```
$I->Compare($res1, $res2, $rot=0, $precision=1);
```

build perceptual hashes out of two images, compare them and return the similarity between them as a percentage. 

- __$res1__ is an image filename.
- __$res2__ is another image filename. 
- __$rot__ create the hash as if the second image were rotated by this value. Default is 0, allowed values are 90, 180, 270.
- __$precision__ return to this many decimal places. Default is 1.

```
$I->Detect($res1, $res2, $precision=1);
```
Compare two images through all rotations and return the highest match value.

```
$I->FastHashImage($res, $size=8);
```

Faster hashing method without any rotation. Returns the same array as HashImage.

```
$I->HashAsString($hash, $hex=true);
```
convert a hash array returned by one of the HashImage methods into a string of hex (if $hex == true) or binary (if $hex==false.)

```
$I->HashAsTable($hash, $size=8, $cellsize=10);
```

convert a hash array into an html table, with each cell being either white or black depending on its value. this is only being used for demos and debugging and should be avoided as being mostly useless and slow. 

### example usage ###

find a percentage of similarity between 'image1.jpg' and 'image2.jpg.'. Compare at 90 degree angles. 

	$I = PHasher::Instance();
	$file1 = 'image1.jpg';
	$file2 = 'image2.jpg';
	$result = $I->Compare($file1, $file2);
	$result90 = $I->Compare($file1, $file2, 90);
	$result180 = $I->Compare($file1, $file2, 180);
	$result270 = $I->Compare($file1, $file2, 270);
	$max_match = max($result, $result90, $result180, $result270);

the above is the same as:

	$I = PHasher::Instance();
	$file1 = 'image1.jpg';
	$file2 = 'image2.jpg';
	$result = $I->Detect($file1, $file2);
	
### notes ###

I'm still trying to speed the algorithm up and improve the hashing, since it does on occasion produce false positives (it was originally meant to catch imagespam on forums and imageboards so it's as aggressive as it can be.) If anyone wants to add discrete cosine transform to this by all means make a pull request because I still don't know how to do it.
