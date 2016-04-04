<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 3/20/2016
 * Time: 2:55 PM
 */

namespace common\components;

use app\components\exceptions\FeatureNotImplemented;
use app\components\exceptions\InvalidArgumentException;
use app\components\exceptions\InvalidConstructorArgumentsException;
use common\components\Exceptions;


class Image
{
	private $url;
	private $imageType;
	private $imageLocation;
	private $imageFile;
	public $data;

	/**
	 * Image constructor.
	 *
	 * @param                        $imageFile image filename and extension (file.png)
	 * @param ImageTypes             $imgType
	 * @param ImageLocationInterface $imgLocation
	 * @param null                   $data
	 */
	public function __construct($imageFile, ImageTypes $imgType, ImageLocationInterface $imgLocation,
	                            $data = null)
	{
		try
		{
			$this->url = $imageFile;
		}
		catch (InvalidArgumentException $e)
		{
			throw new InvalidConstructorArgumentsException($e->getMessage());
		}
		$this->imageType = $imgType;
		$this->imageLocation = $imgLocation;
		$this->data = $data;
		$this->imageFile = $imageFile;
	}

	public function save()
	{
		switch ($this->imageType)
		{
			case ImageTypes::ProfilePhoto:
				return $this->imageLocation->save($this->data, $this->imageFile, "avatars/");
				break;
			case ImageTypes::PostPhoto:
				return $this->imageLocation->save($this->data, $this->imageFile, "attachments/");
				break;
			case ImageTypes::GalleryPhoto:
				return $this->imageLocation->save($this->data, $this->imageFile, "images/");
				break;
			default:
				return $this->imageLocation->save($this->data, $this->imageFile);
		}
	}

	public function remove()
	{
		if (strlen($this->imageFile) != 20)
		{
			return true; //do not remove defaults
		}
		switch ($this->imageType)
		{
			case ImageTypes::ProfilePhoto:
				return $this->imageLocation->remove($this->imageFile, "avatars/");
				break;
			case ImageTypes::PostPhoto:
				return $this->imageLocation->remove($this->imageFile, "attachments/");
				break;
			case ImageTypes::GalleryPhoto:
				return $this->imageLocation->remove($this->imageFile, "images/");
				break;
			default:
				return $this->imageLocation->remove($this->imageFile);
		}
	}

	public function getImage()
	{
		switch ($this->imageType)
		{
			case ImageTypes::ProfilePhoto:
				return $this->imageLocation->getUrl() . "avatars/" . $this->url;
				break;
			case ImageTypes::PostPhoto:
				return $this->imageLocation->getUrl() . "attachments/" . $this->url;
				break;
			case ImageTypes::GalleryPhoto:
				return $this->imageLocation->getUrl() . "images/" . $this->url;
				break;
			default:
				return $this->imageLocation->getUrl() . $this->url;
		}

	}

	public function getFileName()
	{
		return $this->imageFile;
	}

	private function validateImageUrl($url)
	{
		if (false)
		{
			throw new InvalidArgumentException("Invalid image Url. It must be 'image.png'; do not include any folders");
		}
		throw new FeatureNotImplemented();
	}
}