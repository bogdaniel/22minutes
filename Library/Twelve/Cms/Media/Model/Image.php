<?php
namespace Twelve\Cms\Media\Model;
class Image
{
    public $imageSource;
    public $details = array();
    public $width;
    public $height;

    public function __construct($source)
    {
        $this->source = $source;
    }
    public function getWidth()
    {
        return $this->details['0'];
    }
    public function getHeight()
    {
        return $this->details['1'];
    }
    public function getSource()
    {
        return $this->source;
    }
    public function getMime()
    {
        return $this->imageDetails['mime'];
    }
    public function getSize()
    {
        $this->details = getimagesize($this->source);

        return $this->details;
    }
    public function setWidth($width)
    {
        $this->details['width'] = $width;
    }
    public function setHeight($height)
    {
        $this->details['height'] = $height;
    }
}
