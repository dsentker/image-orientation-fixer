<?php

namespace DSentker\ImageOrientationFixer;


class Image
{
    /** @var string */
    private $path;

    /** @var resource */
    private $resource;

    /**
     * Image constructor.
     *
     * @param string        $imagePath
     * @param null|resource $resource
     */
    public function __construct($imagePath, $resource = null)
    {
        $this->path = $imagePath;
        $this->resource = ($resource)
            ? $resource
            : static::createResource($imagePath);
    }

    /**
     * @param resource $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param string $imagePath
     *
     * @return resource
     */
    protected static function createResource($imagePath)
    {
        if (!file_exists($imagePath)) {
            throw new \InvalidArgumentException(sprintf('Unknown image path: "%s"!', $imagePath));
        }
        return imagecreatefromstring(file_get_contents($imagePath));
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }


    /**
     * @param string $filePath
     * @param bool   $explicitOverwrite
     *
     * @return bool
     */
    public function saveAs($filePath, $explicitOverwrite = false)
    {
        preg_match('#\.(.*)$#', $filePath, $match);

        if (empty($match[1])) {
            throw new \InvalidArgumentException('Please provide an extension for the new image path.');
        }

        $ext = $match[1];

        switch ($ext) {
            default:
                $imageType = \IMAGETYPE_JPEG;
                break;
            case 'gif':
                $imageType = \IMAGETYPE_GIF;
                break;
            case 'png':
                $imageType = \IMAGETYPE_PNG;
        }

        return $this->copy($filePath, $explicitOverwrite)->save($imageType);

    }

    /**
     * @param string $newFilePath
     * @param bool   $explicitOverwrite
     *
     * @return static
     */
    public function copy($newFilePath, $explicitOverwrite = false)
    {

        if (file_exists($newFilePath) && (false === $explicitOverwrite)) {
            throw new \RuntimeException(sprintf('Image "%s" already exists!', $newFilePath));
        }

        copy($this->getPath(), $newFilePath);
        $clone = new static($newFilePath, $this->cloneImage());

        return $clone;

    }

    protected function cloneImage()
    {

        $w = imagesx($this->getResource());
        $h = imagesy($this->getResource());
        $trans = imagecolortransparent($this->getResource());

        //If this is a true color image...
        if (imageistruecolor($this->getResource())) {

            $clone = imagecreatetruecolor($w, $h);
            imagealphablending($clone, false);
            imagesavealpha($clone, true);
        } else {
            $clone = imagecreate($w, $h);
            if ($trans >= 0) {
                $rgb = imagecolorsforindex($this->getResource(), $trans);
                imagesavealpha($clone, true);
                $trans_index = imagecolorallocatealpha($clone, $rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
                imagefill($clone, 0, 0, $trans_index);
            }
        }

        imagecopy($clone, $this->getResource(), 0, 0, 0, 0, $w, $h);

        return $clone;
    }

    /**
     * @param null|int $imageType
     *
     * @return bool
     */
    public function save($imageType = null)
    {

        $path = $this->getPath();
        list($width, $height, $originalImageType) = getimagesize($path);
        $imageType = ($imageType) ? $imageType : $originalImageType;

        switch ($imageType) {
            case \IMAGETYPE_GIF:
                $result = imagegif($this->getResource(), $path);
                break;
            case \IMAGETYPE_JPEG:
                $result = imagejpeg($this->getResource(), $path);
                break;
            case \IMAGETYPE_PNG:
                $result = imagepng($this->getResource(), $path);
                break;
            default:
                $result = false;
        }

        return $result;
    }


}