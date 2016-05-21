<?php

namespace common\components;

use common\components\ImgLocations\ImgMediaLoc;
use Yii;
use yii\caching\FileCache;
use app\models\Photo;
use common\models\User;
use common\components\Exceptions\InvalidUserException;
use yii\helpers\Url;

class PhotoService // v.1.2
{

	/**
	 * Returns profile photo's filename for specified User's ID
	 * @param $id User_ID
	 *
	 * @return string photo url
	 */
	public static function getProfilePhoto($id)
	{
		$data = Photo::find()
			->where(['user_id' => $id])
			->andWhere(['type' => 'profile'])
			->one();
		if (!is_null($data))
		{
			$imgLocClass = "common\\components\\ImgLocations\\" . $data['image_loc'];
			$img = new Image($data['filename'], new ImageTypes(ImageTypes::ProfilePhoto), new $imgLocClass());
			return $img->getImage();
		}
		else
		{
			$img = new Image("default.png", new ImageTypes(ImageTypes::ProfilePhoto), new ImgMediaLoc());
			return $img->getImage();
		}
	}
        
        /*
         * Gets the post attachment photo by the filename from the Photo table in the DB
         * Takes the filename as an argument
         */
        public static function getPostAttachmentPhoto($filename)
	{
		$data = Photo::find()
			->where(['filename' => $filename])
			->andWhere(['type' => 'postPhoto'])
			->one();
		if (!is_null($data))
		{
			$imgLocClass = "common\\components\\ImgLocations\\" . $data['image_loc'];
			$img = new Image($data['filename'], ImageTypes::PostPhoto(), new $imgLocClass());
			return $img->getImage();
		}
		else
		{
			return null;
		}
	}

	/**
	 * Sets Profile photo
	 * @param $id User_id
	 * @param $imgData $_FILES data
	 *
	 * @return bool
	 */
	public static function setProfilePhoto($id, $imgData)
	{
		$photo = Photo::find()->where(['user_id' => $id, 'type' => 'profile'])->one();

		if ($photo == null)
		{
			$photo = new Photo();
			$photo->user_id = $id;
		}
		else
		{
			$imgLocClass = "common\\components\\ImgLocations\\" . $photo->image_loc;
			$im = new Image($photo->filename, new ImageTypes(ImageTypes::ProfilePhoto), new $imgLocClass());
			$im->remove();
		}

		$genFileName = Yii::$app->security->generateRandomString(20);
		$img = new Image($genFileName, new ImageTypes(ImageTypes::ProfilePhoto), new ImgMediaLoc(), $imgData);
		$img->save();

		$photo->filename = $genFileName;
		$photo->type = "profile";

		if ($photo->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Returns number of photos stored in database for specified user's id
	 *
	 * @param int $id User's ID
	 *
	 * @return int|boolean Value or false on errr
	 */
	public static function countPhotos($id)
	{
		$data = Photo::find()
			->where(['user_id' => $id])
			->count();
		return isset($data) ? (int)$data : false;
	}

	/**
	 * Adds a new photo to the user's gallery
	 *
	 * @param $user_id
	 * @param $data $_FILES data
	 *
	 * @return bool
	 */
	public static function addPhoto($user_id, $data)
	{
		$photo = new Photo();
		$photo->user_id = $user_id;

		$genFileName = Yii::$app->security->generateRandomString(20);
		$img = new Image($genFileName, new ImageTypes(ImageTypes::GalleryPhoto), new ImgMediaLoc(), $data);
		$img->save();

		$photo->filename = $genFileName;
		$photo->type = "gallery";

		if ($photo->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
        
        /*
         * Adds photos attached to the post.
         * Takes only the data as a parameter.
         * Returns the name of the photo (well, not anymore)
         */
        public static function addPostAttachmentPhoto($data, $post_id)
        {
                foreach($data as $picture)
                {
                    $photo = new Photo();
                    $photo->user_id = 0;
                    $genFileName = Yii::$app->security->generateRandomString(20);
                    $img = new Image($genFileName, ImageTypes::PostPhoto(), new ImgMediaLoc(), $picture);
                    $img->save();
                    $photo->filename = $genFileName;
                    $photo->type = "postPhoto";
                    PostsService::addPostAttachmentPhoto($post_id, $genFileName);
                    $photo->save();
                }
        }

	/**
	 * Returns User's Gallery Photos
	 *
	 * @param type $user_id User's ID
	 *
	 * @return array array of photos (urls)
	 */
	public static function getGallery($user_id)
	{
		$data = Photo::find()
			->select('filename')
			->where(['user_id' => $user_id])
			->andWhere(['type' => 'gallery'])
			->all();

		$dt = [];
		foreach ($data as $var)
		{
			$dt[] =
				(new Image($var->filename, new ImageTypes(ImageTypes::GalleryPhoto), new ImgMediaLoc()))->getImage();
		}
		return $dt;
	}

}
