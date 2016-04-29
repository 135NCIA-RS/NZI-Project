<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
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
use common\components\UserService;
use common\components\ScoreService;
use common\components\ScoreTypeEnum;
use common\components\ScoreElemEnum;

/**
 * Site controller
 */
class SiteController extends components\AdminGlobalController
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'error', 'denied'],
						'allow' => true,
					],
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
		];
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionDenied()
	{
		return $this->render('InsufficientRights');
	}

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login())
		{
			return $this->goBack();
		}
		else
		{
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	public function actionReport() //TODO
	{
		if (Yii::$app->request->isPost || Yii::$app->request->isPjax)
		{
			$request = Yii::$app->request;
			if (!is_null(Yii::$app->request->post('action')))
			{
				switch (Yii::$app->request->post('action'))
				{
					case 'delete':
						//die('dupa');
						PostsService::deletePost(Yii::$app->request->post('post_id'));
						ScoreService::revokeScoreByElemId(Yii::$app->request->post('post_id'),
							new ScoreElemEnum(ScoreElemEnum::post));
						break;
					case 'revoke':
						ScoreService::revokeScoreByElemId(Yii::$app->request->post('post_id'),
							new ScoreElemEnum(ScoreElemEnum::post));
						break;

				}
			}
		}


		$data = ScoreService::getElementsByScoreType(new components\ScoreTypeEnum(components\ScoreTypeEnum::report));
		$table = [];
		foreach ($data as $var)
		{
			if ($var->element_type == components\ScoreElemEnum::post)
			{
				$table[] = PostsService::getPost($var->element_id);
			}
//			if ($var['element_type']==components\ScoreElemEnum::post_comment)
//			{
//				$table[] = PostsService::getComment($var['element_id']);
//			}
		}
		return $this->render('report', [
			'posts' => $table,
		]);
	}

	public function actionReportedComment()
	{
		return $this->render('reportedComment');
	}


}
