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
        $id = Yii::$app->user->getId();
        if (Yii::$app->request->isPost)
        {

            //To upload profile photo
            $plik = $_FILES['exampleInputFile']['tmp_name'];
            if (strlen($plik) > 0)
            {
                $nazwa = md5(uniqid(time())) . '.jpg';
                move_uploaded_file($plik, Yii::$app->basePath . 
                        '/web/dist/content/images/' .
                        $nazwa);
                $zmienna = Yii::$app->request->post('nazwisko');
                \app\components\PhotoService::setProfilePhoto($id, $nazwa);
            }

            UserService::setName($id, Yii::$app->request->post('inputName'));
            UserService::setSurname($id, Yii::$app->request->post('inputSurname'));
            USerService::setEmail($id, Yii::$app->request->post('inputEmail'));

            $pass1cnt = strlen(Yii::$app->request->post('inputPassword'));
            $pass2cnt = strlen(Yii::$app->request->post('inputPasswordRepeat'));
            if ($pass1cnt > 0 || $pass2cnt > 0)
            {
                if ($pass1cnt != $pass2cnt)
                {
                    Yii::$app->session->setFlash('error', 'Passwords not match. Password\'s has not been changed');
                    return $this->redirect('/profile');
                }
                if ($pass1cnt < 6)
                {
                    Yii::$app->session->setFlash('error', 'Password is too short');
                    return $this->redirect('/profile');
                }
            }
            ////////////////////

            Yii::$app->session->setFlash('success', 'Profile\'s been succesfuly updated');
        }
        $education = UserService::getUserEducation($id);
        $about = UserService::getUserAbout($id);
        $city = UserService::getUserCity($id);
        $birth = UserService::getBirthDate($id);
        $name = UserService::getName($id);
        $surname = UserService::getSurname($id);
        $email = UserService::getEmail($id);
        //////////////////////////////////////////////////////////////////////////
        $this->getUserData();
        $this->layout = 'logged';
        return $this->render('userProfile', ['name' => $name, 'surname' => $surname, 'email' => $email, 'education' => $education, 'about' => $about, 'city' => $city, 'birth' => $birth]);
    }

    public function actionAboutedit()
    {
        if (\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $id = Yii::$app->user->getId();
        ////////////////////////////

        $education = UserService::getUserEducation($id);
        $about = UserService::getUserAbout($id);
        $city = UserService::getUserCity($id);
        $birth = UserService::getBirthDate($id);

        if (Yii::$app->request->isPost)
        {
            UserService::setUserCity($id, Yii::$app->request->post('inputLocation'));
            UserService::setUserEducation($id, Yii::$app->request->post('inputEducation'));
            UserService::setUserAbout($id, Yii::$app->request->post('inputNotes'));

            try
            {
                UserService::setBirthDate($id, Yii::$app->request->post('inputDate'));
            }
            catch (\app\components\exceptions\InvalidDateException $e)
            {
                Yii::$app->session->setFlash('error', 'Invalid date');
                return $this->redirect('/profile/aboutedit');
            }

            Yii::$app->session->setFlash('success', 'Profile\'s been Succesfuly Updated');
            //UserService::setUserAbout($id, Yii::$app->request->post('inputNotes'));
            return $this->redirect('/profile');
        }


        ///////////////////////////
        $this->getUserData();
        $this->layout = 'logged';
        return $this->render('aboutEdit', ['education' => $education, 'about' => $about, 'city' => $city, 'birth' => $birth]);
    }

    private function getUserData()
    {
        $id = Yii::$app->user->getId();

        $photo = \app\components\PhotoService::getProfilePhoto($id);

        if (is_string($photo))
        {
            $location = "@web/dist/content/images/";
            //TODO set chmod for that directory(php init)
            $this->view->params['userProfilePhoto'] = $location . $photo;
        }
        else
        {
            $location = "@web/dist/img/guest.png";
            //TODO add that file
            $this->view->params['userProfilePhoto'] = $location;
        }

        $userinfo = array();
        $userinfo['user_name'] = UserService::getName($id);
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
    }

}
