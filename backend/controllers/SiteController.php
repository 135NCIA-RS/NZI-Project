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

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow'   => true,
                        'roles'   => ['@'],
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
        $this->getUserData();

        if (Yii::$app->user->can('admin'))
        {
            $this->layout = "Admin";
            return $this->render('index');
        }
        else
        {
            $this->layout = "NonAdmin";
            return $this->render('InsufficientRights');
        }
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

    public function getUserData()
    {
        $id = Yii::$app->user->getId();

        $photo = PhotoService::getProfilePhoto($id);

        if (is_string($photo))
        {
            $location                               = "@web/dist/content/images/";
            //TODO set chmod for that directory(php init)
            $this->view->params['userProfilePhoto'] = $location . $photo;
        }
        else
        {
            $location                               = "@web/dist/img/guest.png";
            //TODO add that file
            $this->view->params['userProfilePhoto'] = $location;
        }

        $userinfo                 = array();
        $userinfo['user_name']    = UserService::getName($id);
        $userinfo['user_surname'] = UserService::getSurname($id);
        if ($userinfo['user_name'] == false)
        {
            $userinfo['user_name'] = "Uzupełnij";
        }
        if ($userinfo['user_surname'] == false)
        {
            $userinfo['user_surname'] = "swoje dane";
        }

        $this->view->params['userInfo'] = $userinfo;
        ////////////////////////////////////////////////////// request service

        $notification                             = RequestService::getMyRequests($id);
        $tablelength                              = count($notification);
        $this->view->params['notification_data']  = $notification;
        $this->view->params['notification_count'] = $tablelength;
    }

}
