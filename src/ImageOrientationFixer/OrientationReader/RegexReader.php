<?php
namespace DSentker\ImageOrientationFixer\OrientationReader;


use DSentker\ImageOrientationFixer\ImageOrientationException;
use DSentker\ImageOrientationFixer\Image;

class RegexReader implements ReaderInterface
{

    /**
     * @param Image $image
     *
     * @return int|null
     * @throws ImageOrientationException
     */
    public function getOrientation(Image $image)
    {

        $orientation = null;

        if (preg_match('@\x12\x01\x03\x00\x01\x00\x00\x00(.)\x00\x00\x00@', file_get_contents($image->getPath()), $matches)) {
            switch(ord($matches[1])) {
                case self::ORIENTATION_0:
                case self::ORIENTATION_270:
                case self::ORIENTATION_180:
                case self::ORIENTATION_90:
                    return ord($matches[1]);

            }
        }

        throw new ImageOrientationException(sprintf('Unknown orientation: %s!', $matches[1]));

    }


}