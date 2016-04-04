<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 3/20/2016
 * Time: 4:37 PM
 */

namespace common\components\ImgLocations;


use common\components\Image;
use common\components\ImageLocationInterface;
use Yii;

class ImgIntouch implements ImageLocationInterface
{


	public function save($data, $fileName, $subFolders = "")
	{
		return move_uploaded_file($data, "../../media/web/dist/img/" . $subFolders . $fileName);
	}

	/**
	 * @param Image $image
	 *
	 * @return mixed
	 */
	public function remove($fileName, $subFolders = "")
	{
		$value = "../../media/web/dist/img/" . $subFolders . $fileName;

		if (file_exists($value))
		{
			return unlink($value);
		}
		else
		{
			return null;
		}
	}

	/**
	 * @return mixed
	 */
	public function listImages($subFolders = "")
	{
		// TODO: Implement listImages() method.
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return Yii::getAlias("@media") . "/dist/img/";
	}
}