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
        $counter=0;
        foreach ($data as $row) {
            $refined_data[$counter]['post_id'] = (int)$row['post_id'];
            $refined_data[$counter]['name'] = UserService::getName($row['user_id']);
            $refined_data[$counter]['surname'] = UserService::getSurname($row['user_id']);
            $refined_data[$counter]['post_visibility'] = $row['post_visibility'];
            $refined_data[$counter]['post_date'] = $row['post_date'];
            $refined_data[$counter]['post_type'] = $row['post_type'];
            $refined_data[$counter]['post_text'] = $row['post_text'];
            $refined_data[$counter]['comments'] = PostsService::getComments($row['post_id']);
            $refined_data[$counter]['attachments'] = PostsService::getAttachments($row['post_id']);
            $counter++;
        }
        
        return isset($refined_data) ? $refined_data : false;
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
        $counter=0;
        $refined_data = [];
        foreach ($data as $row) {
            $refined_data[$counter]['comment_text'] = $row['comment_text'];
            $refined_data[$counter]['name'] = UserService::getName($row['author_id']);
            $refined_data[$counter]['surname'] = UserService::getSurname($row['author_id']);
            $refined_data[$counter]['comment_date'] = $row['comment_date'];
            $refined_data[$counter]['photo'] = PhotoService::getProfilePhoto($row['author_id']);
            $counter++;
        }
        return isset($refined_data) ? $refined_data : false;
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
