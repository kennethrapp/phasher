# ImageDeuce - A PHP class for image comparison

ImageDeuce is a php implementation of a perceptual hashing algorithm (http://phash.org, http://www.hackerfactor.com/blog/index.php?/archives/432-Looks-Like-It.html) 
which stores a fingerprint of an image as a hash and allows comparing the similarity of images by comparing the hashes. 
I've added the ability to also test if an image is a rotated or flipped version of another. 

## Start:

```php
<?php
$I = ImageDeuce::Instance();
```

## Methods:

```php
<?php
$I->HashImage(filename, rot, mir);
```

returns an array of 64 bits representing the perceptual hash of the file.
- filename: an image file
- rot (optional) generates the hash as if the image were rotated by this value. Currently this can only be 0, 90, 180 or 270. Default is 0.
- mir (optional) generates the hash as if the image were mirrored (if 1) or flipped (if 2). Default is 0.

```php
<?php
$I->HashAsTable(hash);
```

Takes a hash returned by HashImage and displays it as an html table, with each cell either light gray (for 1) or black (for 0). This method is slow and
probably should only be used for debugging and testing. 

```php
<?php
$I->HashAsString(hash, hex);
```

Takes a hash returned by HashImage and displays it as a string. If hex is true, the string will be hexadecimal, otherwise a binary string. Default is hex.