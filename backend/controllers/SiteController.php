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
use yii\data\Pagination;

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
						PostsService::deletePost(PostsService::getPostById(Yii::$app->request->post('post_id')));
						ScoreService::revokeScoresByElemId(
							Yii::$app->request->post('post_id'),
							components\EScoreElem::post()
						);
						break;
					case 'revoke':
						ScoreService::revokeScoresByElemId(
							Yii::$app->request->post('post_id'),
							components\EScoreElem::post()
						);
						break;

				}
			}
		}

		$data = ScoreService::getElementsByScoreType(components\EScoreType::report());
		//$countQuery = clone $data;
		$table = [];
		//$pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>30]);

		foreach ($data as $var)
		{
			/* @var $var components\Score*/
			if ($var->getElementType() == components\EScoreElem::post())
			{
				$table[] = PostsService::getPostById($var->getElementId());
			}
		}
		return $this->render('report', [
			'posts' => $table,
		   //'pagination' => $pagination,
		]);
	}

	public function actionRepcomment()
	{
		if (Yii::$app->request->isPost || Yii::$app->request->isPjax)
		{
			$request = Yii::$app->request;
			if (!is_null(Yii::$app->request->post('action')))
			{
				switch (Yii::$app->request->post('action'))
				{
					case 'delete':
						PostsService::deleteComment(PostsService::getPostById(Yii::$app->request->post('post_id')));
						ScoreService::revokeScoresByElemId(
							Yii::$app->request->post('post_id'),
							components\EScoreElem::post_comment()
						);
						break;
					case 'revoke':
						ScoreService::revokeScoresByElemId(
							Yii::$app->request->post('post_id'),
							components\EScoreElem::post_comment()
						);
						break;

				}
			}
		}

		$data = ScoreService::getElementsByScoreType(components\EScoreType::report());
		$table = [];
		//die(var_dump($data));
		//$pagination = new Pagination(['totalCount' => count($data), 'pageSize'=>30]);
		foreach ($data as $var)
		{
			/* @var $var components\Score*/
			if ($var->getElementType() == components\EScoreElem::post_comment())
			{
				$table[] = PostsService::getCommentById($var->getElementId());
			}
		}
		return $this->render('repcomment', [
			'comments' => $table,
		]);
	}


}
