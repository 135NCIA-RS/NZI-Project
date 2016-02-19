<?php

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
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
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            if ($action->id == 'error')
            {
                if (!Yii::$app->user->isGuest)
                {
                    $this->getUserData();
                    $this->layout = "logged";
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    private function getUserData()
    {
        $id = Yii::$app->user->getId();

        $photo = \common\components\PhotoService::getProfilePhoto($id);

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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->redirect('/intouch/index');
        }
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest)
        {
            //return $this->goHome();
            // user is logged in
            return $this->redirect('/intouch/index');
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

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->redirect('/intouch/index');
        }
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->sendEmail(Yii::$app->params['adminEmail']))
            {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            }
            else
            {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        }
        else
        {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->redirect('/intouch/index');
        }
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->redirect('/intouch/index');
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()))
        {
            if ($user = $model->signup())
            {
                if (Yii::$app->getUser()->login($user))
                {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->redirect('/intouch/index');
        }
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->sendEmail())
            {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->redirect('/intouch/index');
        }
        try
        {
            $model = new ResetPasswordForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
        {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

}
