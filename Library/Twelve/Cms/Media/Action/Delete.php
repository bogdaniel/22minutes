<?php
namespace Twelve\Cms\Media\Action;
use \Twelve\Cms\Media\Model\Image;
use \Exception;
class Delete
{
	public function __construct(Image $image)
	{
		$source = $image->getSource();
	}
	public function delFile($fileName)
	{
		try 
		{
			if(file_exists(($fileName)))
				unlink($fileName);
		}
		catch(Exception $e)
		{
			throw new Exception("Error Processing Request", 1);
		}	
	}
}