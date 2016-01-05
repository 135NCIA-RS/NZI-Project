<?php

namespace app\components;

use app\models\UserInfo;
use common\models\User;
use app\components\exceptions\InvalidDateException;
use app\components\exceptions\InvalidUserException;
use yii\db\Query;
use app\models\Post;
use app\models\PostAttachment;
use app\models\Comment;

class PostsService
{
    public static function getPosts($id)
    {
        $data = Post::find()->where(['user_id'=>$id])->all();
        //die(var_dump($data));
        //$connection = \Yii::$app->db;
        //$model = $connection->createCommand('SELECT * FROM post ORDER BY post_id DESC WHERE user_id='.$id);
        //$data = $model->queryAll();
        return isset($data) ? $data : false;
    }
    
    public static function getNumberOfComments($post_id)
    {
        $data = Comment::find()->where(['post_id'=>$post_id])->all();
        //$connection = \Yii::$app->db;
        //$model = $connection->createCommand('SELECT count(*) FROM comment WHERE post_id='.$post_id);
        //$data = $model->queryAll();
        //return isset($data) ? $data['count(*)'] : false;
        return isset($data) ? count($data) : false;
    }
    
    public static function getPostDate($post_id)
    {
        $data = Post::find()->where(['post_id'=>$post_id])->one();
        //$connection = \Yii::$app->db;
        //$model = $connection->createCommand('SELECT * FROM post WHERE post_id='.$post_id);
        //$data = $model->queryAll();
        //return isset($data) ? $data[0]['post_date'] : false;
        return isset($data) ? $data['post_date'] : false;
    }
    
    public static function getComments($post_id)
    {
        $data = Comment::find()->where(['post_id'=>$post_id])->all();
        //$connection = \Yii::$app->db;
        //$model = $connection->createCommand('SELECT * FROM comment WHERE post_id='.$post_id.' ORDER BY comment_date');
        //$data = $model->queryAll();
        return isset($data) ? $data : false;
    }
    
    public static function getAttachments($post_id)
    {
        $data = PostAttachment::find()->where(['post_id'=>$post_id])->all();
        //$connection = \Yii::$app->db;
        //$model = $connection->createCommand('SELECT * FROM post_attachment WHERE post_id='.$post_id.' ORDER BY attachment_id');
        //$data = $model->queryAll();
        return isset($data) ? $data : false;
    }
}
