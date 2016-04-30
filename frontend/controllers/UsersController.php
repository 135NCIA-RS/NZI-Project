<?php

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\UserService;
use common\models\User;
use common\components;
use common\components\RelationService;
use common\components\RelationMode;
use common\components\RelationType;
use common\components\PhotoService;
use common\components\AccessService;
use common\components\RequestService;
use common\components\Permission;
use common\components\PostsService;
use common\components\RequestType;

class UsersController extends components\GlobalController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionView($uname)
    {
        /////////////////////////--- Profile Infos ---//////////////////////////
        $id = UserService::getUserIdByName($uname);
        if ($id === false)
        {
            throw new \yii\web\NotFoundHttpException("User cannot be found");
        }
        $myId = Yii::$app->user->getId();
        if ($id == $myId)
        {
            return $this->redirect('/profile');
        }
        if (Yii::$app->request->isPost || Yii::$app->request->isPjax)
        {
            if (Yii::$app->user->can('relations-manage-own'))
            {
                $request = Yii::$app->request;
                if (!is_null($request->post('follow-btn')) && Yii::$app->user->can('relations-follow'))
                {
                    RelationService::setRelation($myId, $id, RelationType::Follower);
                }
                if (!is_null($request->post('friend-btn')) && Yii::$app->user->can('relations-friend'))
                {
                    RequestService::createRequest($myId, $id, RequestType::FriendRequest, date('Y-m-d H:i:s')); //to tutaj
                }

                if (!is_null($request->post('unfriend-btn')) && Yii::$app->user->can('relations-friend'))
                {
                    RelationService::removeRelation($myId, $id, RelationType::Friend);
                }
                if (!is_null($request->post('unfollow-btn')) && Yii::$app->user->can('relations-follow'))
                {
                    RelationService::removeRelation($myId, $id, RelationType::Follower);
                }

                if (!is_null(Yii::$app->request->post('type')))
                {
                    switch (Yii::$app->request->post('type'))
                    {
                        case 'newpost':
                            //die('dupa');
                            PostsService::createPost($id, Yii::$app->request->post('inputText'));
                            break;

                        case 'newcomment':
                            PostsService::createComment(Yii::$app->request->post('post_id'), Yii::$app->request->post('inputText'));
                            break;

                        case 'delete_post':
                            $rep_post_id = Yii::$app->request->post('post_id');
                            PostsService::deletePost($rep_post_id);
                            break;
                        case 'delete_comment':
                            $rep_comment_id = Yii::$app->request->post('comment_id');
                            PostsService::deleteComment($rep_comment_id);
                            break;
                    }
                }
            }
            else
            {
                $this->redirect(["intouch/accessdenied"]);
            }
        }


        $education = UserService::getUserEducation($id);
        $about     = UserService::getUserAbout($id);
        $city      = UserService::getUserCity($id);
        $birth     = UserService::getBirthDate($id);
        $name      = UserService::getName($id);
        $surname   = UserService::getSurname($id);
        if (strlen($name) == 0 || strlen($surname) == 0)                //TODO Move it to view
        {
            $name    = "Dane nie uzupełnione";
            $surname = "";
        }
        $email         = UserService::getEmail($id);
        $followers     = count(RelationService::getUsersWhoFollowMe($id));
        $following     = count(RelationService::getUsersWhoIFollow($id));
        $friends       = count(RelationService::getFriendsList($id));
        $photo         = PhotoService::getProfilePhoto($id, true, true);
        /////$$$$$ FORMS $$$$$//////////////////////////////////////////////////
        ////////////////////////////--- Other stuff ---/////////////////////////
        $UserRelations = RelationService::getRelations($myId, $id);
        $isFriend      = $UserRelations[RelationType::Friend];
        if (!$isFriend)
        {
            if (RequestService::isRequestBetween($id, $myId, RequestType::FriendRequest))
            {
                $isFriend = "Friend Request Sent";
            }
        }
        $IFollow      = $UserRelations[RelationType::Follower];
        $uname        = UserService::getUserName($id);
        //***Do not add anything new below this line (except for the render)****
        $this->getUserData($id);
        $this->layout = 'logged';
        $posts        = PostsService::getPosts($id);
        $shared       = [
            'name'                => $name,
            'surname'             => $surname,
            'email'               => $email,
            'education'           => $education,
            'about'               => $about,
            'city'                => $city,
            'birth'               => $birth,
            'followers'           => $followers,
            'following'           => $following,
            'friends'             => $friends,
            'UserFollowState'     => $IFollow,
            'UserFriendshipState' => $isFriend,
            'UserName'            => $uname,
            'UserProfilePhoto'    => $photo,
            'id'                  => $id,
            'posts'               => $posts,
            'photo'               => $photo,
            'myId'                => $myId,
            'myUname'             => UserService::getUserName($myId),
        ];
        return $this->render('view', $shared);
    }
}
