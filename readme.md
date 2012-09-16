# Phasher - a naive perceptual hasher for PHP. 


## Start:

```php
$P = PHasher::Instance();
```

## Methods:

```php
$hash = $I->HashImage($file, $rot); 
```

returns the perceptual hash of the image $file. The hash is an array (default size of 64) of bits. 
- $rot optional: (0|90|180|270) will return the hash as if the image were rotated.  Default is 0.

```php
echo $I->HashAsTable($hash); 
```
Returns the perceptual hash $hash as an html table, with each cell being light gray or black. The default is an 8*8 table.

```php
echo $I->HashAsString($hash, $hex); 
```
Returns the perceptual hash $hash as a string. 
- $hex optional:true or false If true, the string will be hexadecimal, otherwise it will be binary. The default for $hex is true;

```php
echo $I->Compare($file1, $file2); 
```

Compares the hashes of $file1 and $file2 and returns the highest match between them.

```php
echo $I->Detect($file1, $file2); 
```

Compares the hash of $file1 to rotated hashes of $file2 and returns the highest match between them. 