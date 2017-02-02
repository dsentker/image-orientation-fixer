<?php

namespace DSentker\ImageOrientationFixer\OrientationReader;

class ReaderFactory
{
    /**
     * @return ReaderInterface
     */
    public static function getReader()
    {
        if(function_exists('exif_read_data')) {
            return new ExifReader();
        }

        return new RegexReader();
    }

}