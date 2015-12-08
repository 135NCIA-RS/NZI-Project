<?php

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\UserService;

class IntouchController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'logout'],
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

        if (\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        //////////////////////////////////

        $this->getUserData();

        $zdjecie = new \app\models\Photo();
        $dane = $zdjecie->find()->all();
        //UserService::setBirthDate(1, "28-04-1993");
        //UserService::setPassword(1, "pass");
        //////////////////////////////////
        $this->layout = 'logged';
        return $this->render('index', ['dane' => $dane]);
    }

    public function actionProfile()
    {
        if (\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        if (Yii::$app->request->isPost)
        {

            //To upload profile photo
              $plik=$_FILES['exampleInputFile']['tmp_name'];
              $nazwa=md5(uniqid(time())).'.jpg';
              move_uploaded_file($plik, Yii::$app->basePath.'/web/dist/content/images/'.$nazwa);
            $zmienna = Yii::$app->request->post('nazwisko');
            ////////////////////
            
            $id = Yii::$app->user->getId();

            UserService::setName($id, Yii::$app->request->post('inputName'));
            UserService::setSurname($id, Yii::$app->request->post('inputSurname'));
            USerService::setEmail($id,Yii::$app->request->post('inputEmail'));
        }
        $this->getUserData();
        $this->layout = 'logged';
        return $this->render('userProfile');
    }

    private function getUserData()
    {
        $id = Yii::$app->user->getId();
        $userProfileData = \app\models\Photo::find()
                        ->where(['user_id' => $id])
                        ->andWhere(['type' => 'profile'])->one();
        if (isset($userProfileData['filename']))
        {
            $location = "@web/dist/content/images/";
            //TODO set chmod for that directory(php init)
            $this->view->params['userProfilePhoto'] = $location . $userProfileData['filename'];
        }
        else
        {
            $location = "@web/dist/img/guest.png";
            //TODO add that file
            $this->view->params['userProfilePhoto'] = $location;
        }

        $usinfo = new \app\models\UserInfo();
        $userinfo = $usinfo->find()->where(['user_id' => $id])->one();
        if ($userinfo == null)
        {
            $userinfo = null;
            $userinfo = ['user_name' => 'Uzupełnij', 'user_surname' => 'Swoje dane'];
        }

        $this->view->params['userInfo'] = $userinfo;
        //die(var_dump($userinfo));
    }

}
