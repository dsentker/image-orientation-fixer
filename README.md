# Image Orientation Fixer

Fixes image orientation problems for uploaded images.
 
## The Problem
An image that has a native dimension of 600x1500 pixels can still be displayed in landscape format (1500x600) if a corresponding value is stored in the metadata. This is the case, for example, when the image was taken with a smartphone, and the device was rotated 90 degrees for a horizontal format.

This image can lead to unexpected results in a browser (displayed with the well-known <img>-element). The browser does not consider the EXIF information and displays the image incorrectly.

## The Solution
Fortunately, smartphones and other modern devices stores suitable EXIF meta information in the images. This PHP library reads this information and automatically rotates the image as needed.

## Advantages
   
_ImageOrientationFixer_ reads EXIF data from the image to evaluate the original orientation of the image. If the EXIF extension is not installed on the server, an alternative reader (RegexReader) can access this information with the help of regular expressions.
 
You have the choice of replacing the original image or saving it under a new name (I really do not know why you should do such a thing).

## Disadvantages
This library uses the GD Image extension to access the filetype and for the rotation. As you know, this extension is memory-hungry. This library is therefore not suitable for the batch processing of multiple large images.

I welcome any help in this open-source project, for example to use a more flexible image processing library with DI.

## Installation
Download the files from github and use the _autoloader.php_ file located in /src/ImageOrientationFixer.

I recommend to use composer:
``` composer require "dsentker/imageorientationfixer:*@dev"```

## Usage

### Quick usage
```php
use DSentker\ImageOrientationFixer\ImageOrientationFixer;
require_once 'vendor/autoload.php';

// image.jpg will be replaced with the fixed version of this image
ImageOrientationFixer::fixImage('path/to/image.jpg');
```

### Advantage Usage
```php
require_once 'vendor/autoload.php';

$imageFile = 'image.jpeg';

require '../src/ImageOrientationFixer/autoload.php';

$fixer = new \DSentker\ImageOrientationFixer\ImageOrientationFixer($imageFile);

/** @var \DSentker\ImageOrientationFixer\Image $fixedImage */
$fixedImage = $fixer->getFixedImage();
$fixedImage->save(); // Replace old image

// Alternate approach with the regex reader, if EXIF extension is not installed
$overwriteImage = true;
$regexFixer = new \DSentker\ImageOrientationFixer\ImageOrientationFixer($imageFile);
$regexFixer->setReader(new \DSentker\ImageOrientationFixer\OrientationReader\RegexReader());
$result = $regexFixer->getFixedImage()->saveAs('fixed.jpg', $overwriteImage);

if($result) {
    echo 'Are U A Wizard?';
}
```

## Credits
* [Daniel Sentker](https://github.com/dsentker)

## Submitting bugs and feature requests
Bugs and feature request are tracked on GitHub.

## ToDo (help is appreciated!)
* Write tests
* Create a factory for orientation reader
* Create Symfony3 bundle
* Let developer choose between GD/GD2, Imagick and other cool image libraries.

## Copyright and license
ImageOrientationFixer is licensed for use under the MIT License (MIT). Please see LICENSE for more information.