<?php

namespace app\components;

use app\models\Photo;
use common\models\User;
use app\components\exceptions\InvalidUserException;

class PhotoService // v.1.0
{
    /**
     * Returns profile photo's filename for specified User's ID
     * @param int $id User's ID
     * @return string|boolean filename or false on error
     */
    public static function getProfilePhoto($id)
    {
        $data = Photo::find()
                ->select('filename')
                ->where(['user_id' => $id])
                ->andWhere(['type' => 'profile'])
                ->one();
        return isset($data['filename']) ? $data['filename'] : false;
    }

    /**
     * Sets Profile photo's filename for specified User's ID
     * @param int $id User's ID
     * @param string $filename Photo's filename eg. nerd.png
     * @return boolean true on success, false on fail
     */
    public static function setProfilePhoto($id, $filename)
    {
        $photo = Photo::find()->where(['user_id' => $id])->one();
        if ($photo == null)
        {
            $photo = new Photo();
            $photo->user_id = $id;
        }
        $photo->filename = $filename;
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
     * @param int $id User's ID
     * @return int|boolean Value or false on errr
     */
    public static function countPhotos($id)
    {
        $data = Photo::find()
                ->where(['user_id' => $id])
                ->count();
        return isset($data) ? (int)$data : false;
    }
    
    

}
