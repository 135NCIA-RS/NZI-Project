<?php

namespace frontend\controllers;

use app\models\Comment;
use common\components;
use app\models\Event;
use app\models\Photo;
use Faker\Provider\Image;
use Yii;
use common\models\LoginForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\UserService;
use common\models\User;

use common\components\RelationService;
use common\components\RelationMode;
use common\components\RelationType;
use common\components\PhotoService;
use common\components\AccessService;
use common\components\RequestService;
use common\components\PostsService;
use common\components\RequestType;
use common\components\ScoreService;
use common\components\EScoreElem;
use common\components\EScoreType;
use common\components\EventService;

class PostController extends components\GlobalController
{
    public function actionEdit($pid)
    {
        $idu = Yii::$app->user->getId();
        $uid = new components\UserId($idu);
        //die(var_dump($pid));
        $post= PostsService::getPostById($pid);
        $nothing= $post->getAuthor();
        $postOwner= $nothing->getId();
        if (!Yii::$app->user->can('admin'))
        {
            if($postOwner != $idu)
            {
                return $this->redirect('/intouch/accessdenied');
            }
        }
       // die(var_dump($post));
        if (Yii::$app->request->isPost)
        {
            try
            {
                $id= Yii::$app->request->post('post_id');
                $post= PostsService::getPostById($id);
                $post->changeContent(Yii::$app->request->post('inputContent'));
                PostsService::savePost($post);

            }
            catch(exception $e)
            {

            }
            EventService::createEvent(components\EEvent::ACCOUNT_INFO_CHANGED(), $uid);
            Yii::$app->session->setFlash('success', 'Post\'s been Succesfuly Updated');
            return $this->redirect('/profile');
        }
        return $this->render('edit',['post' => $post]);
    }
    public function actionComment($cid)
    {
        $idu = Yii::$app->user->getId();
        $uid = new components\UserId($idu);
        $comment= PostsService::getCommentById($cid);
        $nothing= $comment->getAuthor();
        $commentOwner= $nothing->getId();

        //die(var_dump($commentOwner));
        if (!Yii::$app->user->can('admin'))
        {
            if($commentOwner != $idu)
            {
                return $this->redirect('/intouch/accessdenied');
            }
        }

        if (Yii::$app->request->isPost)
        {
            try
            {
                $id= Yii::$app->request->post('comment_id');
                $comment= PostsService::getCommentById($id);
                $comment->changeContent(Yii::$app->request->post('inputContent'));
                PostsService::saveComment($comment);

            }
            catch(exception $e)
            {

            }
            EventService::createEvent(components\EEvent::ACCOUNT_INFO_CHANGED(), $uid);
            Yii::$app->session->setFlash('success', 'Comment\'s been Succesfuly Updated');
            return $this->redirect('/profile');
        }
        return $this->render('comment',['comment' => $comment]);
    }

    public function actionView($pid)
    {

    }


}
