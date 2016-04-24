<?php

namespace OkeanrstBooks\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class ImageService
{
	protected $imagine;
	
	public function __construct()       
    {	
		$this->imagine = new Imagine();
    }
	
	public function makePreview($path, $a, $b)
	{
		$size = new Box($a, $b);
		$mode = ImageInterface::THUMBNAIL_OUTBOUND;
		$image = $this->imagine->open($path);
		return $image->thumbnail($size, $mode)->save($path);		
	}
}