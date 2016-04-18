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
	public function actionRepports() //TODO
	{
		if (Yii::$app->request->isPost || Yii::$app->request->isPjax)
		{
			$request = Yii::$app->request;
			//TODO

		}


		$data = ScoreService::getElementsByScoreType(ScoreTypeEnum::report());
		$table =[];
		/**
		 * @var $var components\Score
		 */
		foreach($data as $var)
		{
			if($var->element_type == components\ScoreElemEnum::post())
			{
				$table[] = PostsService::getPost($var->element_id);
			}
		}
		return $this->render('reports', [
			'posts' => $table,
		]);
	}


}
