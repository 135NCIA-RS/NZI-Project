<?php

namespace common\components;

use Yii;
use yii\caching\FileCache;
use app\models\Photo;
use common\models\User;
use common\components\exceptions\InvalidUserException;
use yii\helpers\Url;

class PhotoService // v.1.2
{

    /**
     * Returns profile photo's filename for specified User's ID
     * @param int $id User's ID
     * @param type $onErrorDefaultPhoto if true returns default user photo if user doesn't have picture
     * @return string|boolean filename or false on error
     */
    public static function getProfilePhoto($id, $onErrorDefaultPhoto = false, $includeFullPath = false)
    {
        $profilePhotosLocation = "/dist/content/images/";

        $data = Photo::find()
                ->select('filename')
                ->where(['user_id' => $id])
                ->andWhere(['type' => 'profile'])
                ->one();
        if ($includeFullPath)
        {
            if (isset($data['filename']))
            {
                $data['filename'] = $profilePhotosLocation . $data['filename'];
            }
        }
        return isset($data['filename']) ? $data['filename'] : ($onErrorDefaultPhoto ? "/dist/img/guest.png" : false);
    }

    /**
     * Sets Profile photo's filename for specified User's ID
     * @param int $id User's ID
     * @param string $filename Photo's filename eg. nerd.png
     * @return boolean true on success, false on fail
     */
    public static function setProfilePhoto($id, $filename)
    {
        $photo = Photo::find()->where(['user_id' => $id, 'type' => 'profile'])->one();

        if ($photo == null)
        {
            $photo          = new Photo();
            $photo->user_id = $id;
        }
        else
        {
            if (file_exists("../web/dist/content/images/" . $photo->filename))
            {
                unlink("../web/dist/content/images/" . $photo->filename);
            }
        }

        $photo->filename = $filename;
        $photo->type     = "profile";

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
     * @param int $id User's ID
     * @return int|boolean Value or false on errr
     */
    public static function countPhotos($id)
    {
        $data = Photo::find()
                ->where(['user_id' => $id])
                ->count();
        return isset($data) ? (int) $data : false;
    }

    /**
     * Adds a new photo to the user's gallery
     * @param type $user_id User's ID
     * @param type $filename photo's Filename
     * @return boolean|array false on error or array with filenames
     */
    public static function addPhoto($user_id, $filename)
    {
        $photo = Photo::find()->where(['user_id' => $id, 'type' => 'gallery', 'filename' => $filename])->one();

        if ($photo == null)
        {
            $photo          = new Photo();
            $photo->user_id = $id;
        }
        else
        {
            return true; //already in database
        }

        $photo->filename = $filename;
        $photo->type     = "gallery";

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
     * Returns User's Gallery Photos Filenames
     * @param type $user_id User's ID
     * @return null|boolean|array null on empty gallery, false on error or array of filenames
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
            $dt[] = $var['filename'];
        }
        if (count($dt) == 0)
            return null;
        return isset($dt) ? $dt : false;
    }

}
