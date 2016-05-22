<?php
namespace frontend\controllers;

use app\models\Event;
use app\models\Photo;
use Faker\Provider\Image;
use Yii;
use common\models\LoginForm;
use yii\base\InvalidParamException;
use yii\helpers\Url;
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
use common\components\PostsService;
use common\components\RequestType;
use common\components\ScoreService;
use common\components\EScoreElem;
use common\components\EScoreType;
use common\components\EventService;

class IntouchController extends components\GlobalController
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

	/**
	 * @inheritdoc
	 */
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

	public function actionIndex()
	{
		$id = Yii::$app->user->getId();
		$uid = new components\UserId($id);
		$loggedUser = UserService::getUserById($uid);
		if (Yii::$app->request->isPost || Yii::$app->request->isPjax)
		{
			if (!is_null(Yii::$app->request->post('type')))
			{
				switch (Yii::$app->request->post('type'))
				{
					case 'newpost':
						$pliks = $_FILES['kawaiiPicture']['tmp_name'];
						$post_id = PostsService::createPost($uid, Yii::$app->request->post('inputText'));
                                                if($pliks[0]!='')
                                                {
                                                    PhotoService::addPostAttachmentPhoto($pliks, $post_id);
                                                }
						EventService::createEvent(components\EEvent::POST_CREATE(), $uid);
						break;
					case 'newcomment':
						PostsService::createComment(
							PostsService::getPostById(Yii::$app->request->post('post_id')),
							Yii::$app->request->post('inputText')
						);
						break;
					case 'delete':
						PostsService::deletePost(PostsService::getPostById(Yii::$app->request->post('post_id')));
						break;
					case 'like':
						$like_form_post_id = Yii::$app->request->post('post_id');
						$like_form_score_elem = Yii::$app->request->post('score_elem');
						$like_form_user_id = Yii::$app->request->post('user_id');
						$score = new components\Score(EScoreType::like(), null, EScoreElem::$like_form_score_elem(),
							$like_form_post_id, new components\UserId($like_form_user_id));
						$existing_scores = ScoreService::getScoresByElem(EScoreElem::post(), $like_form_post_id);
						$found = false;
						foreach ($existing_scores as $var)
						{
							$user = $var->getPublisher();
							$userId = $user->getId();
							if ((int)$like_form_user_id == $userId && (int)$like_form_post_id == $var->getElementId())
							{
								$found = true;
								$found_score_id = $var->getScoreId();
							}
						}
						if (!$found)
						{
							ScoreService::addScore($score);
						}
						else
						{
							ScoreService::revokeScore($found_score_id);
						}
						break;
				}
			}
		}
		$posts = PostsService::getFriendsPosts($uid);
		$args = [
		'posts' => $posts,
		'loggedUser' => $loggedUser,
		];
		return $this->render('index', $args);
	}

	public function actionLoadMorePosts($last = 1)
	{
		/* @var $data components\Post[] */
		$uid = new components\UserId(Yii::$app->user->id);
		$data = PostsService::getFriendsPosts($uid, $last);
		$lastId = $data[4]->getId();
		$id = Yii::$app->user->id;
		$html = $this->renderPartial('postsLoad', ['UserName' => $id, 'posts' => $data]);
		$arr['html'] = $html;
		$arr['lastId'] = $lastId;
		echo json_encode($arr); // ??????
	}

	public function actionTestmail()
	{
		if (Yii::$app->user->can('admin'))
		{
			$email = UserService::getEmail(1);
			$s = Yii::$app->mailer->compose()
				->setFrom('noreply@yii2.com')
				->setTo('costam@costa.com')
				->setSubject('InTouch')
				->setTextBody('Hello')
				->setHtmlBody('<b>Html Hello</b>')
				->send();
			die(var_dump($s));
		}
		else
		{
			echo "Access Denied, you have to log in as admin";
		}
		//
	}

	public function actionProfile()
	{
		$id = Yii::$app->user->getId();
		$uid = new components\UserId($id);
		$lUser = $uid->getUser();
		if (Yii::$app->request->isPost || Yii::$app->request->isPjax)
		{
			if (!is_null(Yii::$app->request->post('type')))
			{
				switch (Yii::$app->request->post('type'))
				{
					case 'settings':
						//To upload profile photo
						$plik = $_FILES['exampleInputFile']['tmp_name'];
						if (strlen($plik) > 0)
						{
							\common\components\PhotoService::setProfilePhoto($id, $plik);
						}
						$lUser->setName(Yii::$app->request->post('inputName'));
						$lUser->setSurname(Yii::$app->request->post('inputSurname'));
						$lUser->setEmail(Yii::$app->request->post('inputEmail'));
						UserService::saveUser($lUser);
						$pass1cnt = strlen(Yii::$app->request->post('inputPassword'));
						$pass2cnt = strlen(Yii::$app->request->post('inputPasswordRepeat'));
						if ($pass1cnt > 0 || $pass2cnt > 0)
						{
							if ($pass1cnt != $pass2cnt)
							{
								Yii::$app->session->setFlash('error',
									'Passwords not match. Password\'s has not been changed');
								return $this->redirect('/profile');
							}
							if ($pass1cnt < 6)
							{
								Yii::$app->session->setFlash('error', 'Password is too short');
								return $this->redirect('/profile');
							}
							EventService::createEvent(components\EEvent::ACCOUNT_PASSWORD_CHANGED(), $uid);
						}
						////////////////////
						Yii::$app->session->setFlash('success', 'Profile\'s been succesfuly updated');
						break;
					case 'newpost':
						$pliks = $_FILES['kawaiiPicture']['tmp_name'];
						$post_id = PostsService::createPost($uid, Yii::$app->request->post('inputText'));
                                                if($pliks[0]!='')
                                                {
                                                    PhotoService::addPostAttachmentPhoto($pliks, $post_id);
                                                }
						EventService::createEvent(components\EEvent::POST_CREATE(), $uid);
						break;
					case 'newcomment':
						PostsService::createComment(PostsService::getPostById(Yii::$app->request->post('post_id')),
							Yii::$app->request->post('inputText'));
						EventService::createEvent(components\EEvent::COMMENT_CREATE(), $uid);
						break;
					case 'like':
						$like_form_post_id = Yii::$app->request->post('post_id');
						$like_form_score_elem = Yii::$app->request->post('score_elem');
						$like_form_user_id = Yii::$app->request->post('user_id');
						$score = new components\Score(EScoreType::like(), null, EScoreElem::$like_form_score_elem(),
							$like_form_post_id, new components\UserId($like_form_user_id));
						$existing_scores = ScoreService::getScoresByElem(EScoreElem::post(), $like_form_post_id);
						$found = false;
						foreach ($existing_scores as $var)
						{
							$user = $var->getPublisher();
							$userId = $user->getId();
							if ((int)$like_form_user_id == $userId && (int)$like_form_post_id == $var->getElementId())
							{
								$found = true;
								$found_score_id = $var->getScoreId();
							}
						}
						if (!$found)
						{
							ScoreService::addScore($score);
							EventService::createEvent(components\EEvent::POST_LIKED(), $uid);
						}
						else
						{
							EventService::createEvent(components\EEvent::POST_UNLIKED(), $uid);
							ScoreService::revokeScore($found_score_id);
						}
						break;
					case 'report':
						$rep_form_post_id = Yii::$app->request->post('post_id');
						$rep_form_score_elem = Yii::$app->request->post('score_elem');
						$rep_form_user_id = Yii::$app->request->post('user_id');
						$score = new components\Score(EScoreType::like(), null, EScoreElem::$rep_form_score_elem(),
							$rep_form_post_id, new components\UserId($rep_form_user_id));
						ScoreService::addScore($score);
						break;
					case 'delete_post':
						$rep_post_id = Yii::$app->request->post('post_id');
						PostsService::deletePost(PostsService::getPostById($rep_post_id));
						EventService::createEvent(components\EEvent::POST_DELETE(), $uid);
						break;
					case 'delete_comment':
						$rep_comment_id = Yii::$app->request->post('comment_id');
						PostsService::deleteComment(PostsService::getCommentById($rep_comment_id));
						EventService::createEvent(components\EEvent::COMMENT_DELETE(), $uid);
						break;
				}
			}
		}

		$posts = PostsService::getUserPosts($uid);

		$followers = count(RelationService::getUsersWhoFollowMe($uid));
		$following = count(RelationService::getUsersWhoIFollow($uid));
		$friends = count(RelationService::getFriendsList($uid));

		$timeline = components\EventService::getUserEvents($uid, true);
		//////////////////////////////////////////////////////////////////////////
		return $this->render('profile', [
			'userinfo' => $uid->getUser(),
			'posts' => $posts,
			'followers' => $followers,
			'following' => $following,
			'friends' => $friends,
			'loggedUser' => $lUser,
			'timeline' => $timeline,
		]);
	}

	public function actionAboutedit()
	{
		$id = Yii::$app->user->getId();
		$uid = new components\UserId($id);
		////////////////////////////
		$loggedUser = $uid->getUser();
		if (Yii::$app->request->isPost)
		{
			$loggedUser->setCity(Yii::$app->request->post('inputLocation'));
			$loggedUser->setEducation(Yii::$app->request->post('inputEducation'));
			$loggedUser->setAbout(Yii::$app->request->post('inputNotes'));
			try
			{
				$bdate = Yii::$app->request->post('inputDate');
				if (strtotime($bdate) - time() > 0)
				{
					Yii::$app->session->setFlash('error', 'Hello! It\'s date from future!');
					return $this->redirect('/profile/aboutedit');
				}
				$loggedUser->setBirthDate(new \DateTime($bdate));
				UserService::saveUser($loggedUser);
			}
			catch (\common\components\exceptions\InvalidDateException $e)
			{
				Yii::$app->session->setFlash('error', 'Invalid date');
				return $this->redirect('/profile/aboutedit');
			}
			EventService::createEvent(components\EEvent::ACCOUNT_INFO_CHANGED(), $uid);
			Yii::$app->session->setFlash('success', 'Profile\'s been Succesfuly Updated');

			return $this->redirect('/profile');
		}
		$this->getUserData(); // refresh
		return $this->render('aboutEdit', [
		]);
	}

	public function actionSearch($q)
	{
		if (Yii::$app->user->can('search-use'))
		{
			$users = \common\components\SearchService::findUsers($q);
			$resultsCnt = count($users);
			return $this->render('searchResults', [
				'query' => $q,
				'count' => $resultsCnt,
				'users' => $users,
			]);
		}
		else
		{
			$this->redirect("/intouch/accessdenied");
		}
	}

	public function actionAccessdenied()
	{
		return $this->render('accessDenied');
	}

	public function actionNotifications()
	{
		if (Yii::$app->request->isPost)
		{
			if (!is_null(Yii::$app->request->post('accept-btn')) || !is_null(Yii::$app->request->post('dismiss-btn')))
			{
				$answer = false;
				if (!is_null(Yii::$app->request->post('accept-btn')))
				{
					$answer = true;
				}
				$request_id = Yii::$app->request->post('request_id');
				RequestService::answerRequest($request_id, $answer);
			}
		}
		return $this->render('allRequests');
	}

	public function actionMyfriends()
	{
		$id = Yii::$app->user->getId();
		$uid = new components\UserId($id);
		///////
		$friends = RelationService::getFriendsList($uid);
		$falone =
			new components\Image("forever_alone.png", new components\ImageTypes(components\ImageTypes::InTouchImage),
				new components\ImgLocations\ImgIntouch());
		$faloneT = new components\Image("forever_alone_text.png",
			new components\ImageTypes(components\ImageTypes::InTouchImage), new components\ImgLocations\ImgIntouch());
		///////
		return $this->render('myFriends', ['friends' => $friends, 'imgForeverAlone' => $falone->getImage(),
		                                   'imgForeverAloneText' => $faloneT->getImage()]);
	}
}