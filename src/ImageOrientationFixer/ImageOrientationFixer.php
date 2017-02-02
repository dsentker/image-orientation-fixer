<?php
namespace DSentker\ImageOrientationFixer;

use DSentker\ImageOrientationFixer\OrientationReader\ExifReader;
use DSentker\ImageOrientationFixer\OrientationReader\ReaderInterface;

class ImageOrientationFixer
{

    /** @var Image */
    protected $image;

    /** @var ReaderInterface|null */
    protected $reader;

    /**
     * ImageOrientationFixer constructor.
     *
     * @param string               $imagePath
     * @param ReaderInterface|null $orientationReader
     *
     * @throws ImageOrientationException
     */
    public function __construct($imagePath, ReaderInterface $orientationReader = null)
    {
        if (!extension_loaded('gd')) {
            throw new ImageOrientationException('GD/GD2 Extension is not available.');
        }

        $this->image = new Image($imagePath);
        $this->reader = $orientationReader;
    }

    /**
     * @return Image
     */
    public function fix()
    {
        $reader = ($this->reader) ? $this->reader : new ExifReader();
        $imageResource = $this->image->getResource();

        switch ($reader->getOrientation($this->image)) {
            case ReaderInterface::ORIENTATION_270:
                $imageResource = imagerotate($imageResource, 90, 0);
                break;
            case ReaderInterface::ORIENTATION_180:
                $imageResource = imagerotate($imageResource, 180, 0);
                break;
            case ReaderInterface::ORIENTATION_90:
                $imageResource = imagerotate($imageResource, -90, 0);
                break;
        }

        $this->image->setResource($imageResource);

        return $this->image;

    }

    /**
     * @param null|ReaderInterface $reader
     */
    public function setReader(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }



}