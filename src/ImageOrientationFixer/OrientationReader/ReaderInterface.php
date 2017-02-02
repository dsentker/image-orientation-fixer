<?php
namespace DSentker\ImageOrientationFixer\OrientationReader;


use DSentker\ImageOrientationFixer\Image;

interface ReaderInterface
{

    const ORIENTATION_0 = 1;
    const ORIENTATION_180 = 3;
    const ORIENTATION_90 = 6;
    const ORIENTATION_270 = 8;

    /**
     * @param Image $image
     *
     * @return int
     */
    public function getOrientation(Image $image);

}