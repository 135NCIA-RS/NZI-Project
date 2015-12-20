<?php

namespace app\components;

use app\models\UserInfo;
use common\models\User;
use app\components\exceptions\InvalidDateException;
use app\components\exceptions\InvalidUserException;
use yii\db\Query;


class PostsMethods
{
    public static function getPosts($id)
    {
        $connection = \Yii::$app->db;
        $model = $connection->createCommand('SELECT * FROM post ORDER BY post_id DESC');
        $data = $model->queryAll();
        return isset($data) ? $data : false;
    }
    
    public static function getNumberOfComments($post_id)
    {
        $connection = \Yii::$app->db;
        $model = $connection->createCommand('SELECT count(*) FROM comment WHERE post_id='.$post_id);
        $data = $model->queryAll();
        return isset($data) ? $data[0]['count(*)'] : false;
    }
    
    public static function getPostDate($post_id)
    {
        $connection = \Yii::$app->db;
        $model = $connection->createCommand('SELECT * FROM post WHERE post_id='.$post_id);
        $data = $model->queryAll();
        return isset($data) ? $data[0]['post_date'] : false;
    }
    
    public static function getComments($post_id)
    {
        $connection = \Yii::$app->db;
        $model = $connection->createCommand('SELECT * FROM comment WHERE post_id='.$post_id.' ORDER BY comment_date');
        $data = $model->queryAll();
        return isset($data) ? $data : false;
    }
}
