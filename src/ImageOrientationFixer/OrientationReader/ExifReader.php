<?php
namespace DSentker\ImageOrientationFixer\OrientationReader;


use DSentker\ImageOrientationFixer\ImageOrientationException;
use DSentker\ImageOrientationFixer\Image;

class ExifReader implements ReaderInterface
{

    /**
     * @param Image $image
     *
     * @return int|null
     * @throws ImageOrientationException
     */
    public function getOrientation(Image $image)
    {
        if(!function_exists('exif_read_data')) {
            throw new ImageOrientationException(sprintf('EXIF module not loaded!'));
        }

        $exif = exif_read_data($image->getPath());
        if ($exif === false) {
            throw new ImageOrientationException('Unknown orientation - exif data couldn\'t be parsed');
        }

        $orientation = null;

        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case self::ORIENTATION_0:
                case self::ORIENTATION_270:
                case self::ORIENTATION_180:
                case self::ORIENTATION_90:
                    $orientation = (int) $exif['Orientation'];
                    break;

            }
        }

        if(null === $orientation) {
            throw new ImageOrientationException(sprintf('Unknown orientation: %s!', $exif['Orientation']));
        }

        return $orientation;

    }


}
