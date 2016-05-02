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
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
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
		$uid = new components\UserId($id);
		$myId = Yii::$app->user->getId();
		$myUid = new components\UserId($myId);
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
					RelationService::setRelation($myUid, $uid, RelationType::Follower);
				}
				if (!is_null($request->post('friend-btn')) && Yii::$app->user->can('relations-friend'))
				{
					RequestService::createRequest($myUid, $uid, RequestType::FriendRequest,
						date('Y-m-d H:i:s')); //to tutaj
				}

				if (!is_null($request->post('unfriend-btn')) && Yii::$app->user->can('relations-friend'))
				{
					RelationService::removeRelation($myUid, $uid, RelationType::Friend);
				}
				if (!is_null($request->post('unfollow-btn')) && Yii::$app->user->can('relations-follow'))
				{
					RelationService::removeRelation($myUid, $uid, RelationType::Follower);
				}

				if (!is_null(Yii::$app->request->post('type')))
				{
					switch (Yii::$app->request->post('type'))
					{
						case 'newpost':
							PostsService::createPost($uid, Yii::$app->request->post('inputText'));
							break;

						case 'newcomment':
							PostsService::createComment(PostsService::getPostById(Yii::$app->request->post('post_id')),
								Yii::$app->request->post('inputText'));
							break;

						case 'delete_post':
							$rep_post_id = Yii::$app->request->post('post_id');
							PostsService::deletePost(PostsService::deletePost(PostsService::getPostById($rep_post_id)));
							break;
						case 'delete_comment':
							$rep_comment_id = Yii::$app->request->post('comment_id');
							PostsService::deleteComment(PostsService::getCommentById($rep_comment_id));
							break;
					}
				}
			}
			else
			{
				$this->redirect(["intouch/accessdenied"]);
			}
		}

		$user = $uid->getUser();
		$followers = count(RelationService::getUsersWhoFollowMe($uid));
		$following = count(RelationService::getUsersWhoIFollow($uid));
		$friends = count(RelationService::getFriendsList($uid));
		/////$$$$$ FORMS $$$$$//////////////////////////////////////////////////
		////////////////////////////--- Other stuff ---/////////////////////////
		$UserRelations = RelationService::getRelations($myUid, $uid);
		$isFriend = $UserRelations[RelationType::Friend];
		if (!$isFriend)
		{
			if (RequestService::isRequestBetween($uid, $myUid, RequestType::FriendRequest))
			{
				$isFriend = "Friend Request Sent";
			}
		}
		$IFollow = $UserRelations[RelationType::Follower];
		//***Do not add anything new below this line (except for the render)****
		//$this->getUserData();
		$posts = PostsService::getUserPosts($uid);
		$shared = [
			'user' => $user,
			'followers' => $followers,
			'following' => $following,
			'friends' => $friends,
			'UserFollowState' => $IFollow,
			'UserFriendshipState' => $isFriend,
			'posts' => $posts,
		];
		return $this->render('view', $shared);
	}
}
