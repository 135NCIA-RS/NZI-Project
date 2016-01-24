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
use common\models\User;
use app\components;
use app\components\RelationService;
use app\components\RelationMode;
use app\components\RelationType;
use app\components\PhotoService;
use app\components\AccessService;
use app\components\RequestService;
use app\components\Permission;
use app\components\PostsService;
use app\components\RequestType;

class IntouchController extends Controller
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
        $this->getUserData();
        $zdjecie = new \app\models\Photo();
        $dane = $zdjecie->find()->all();
        $this->layout = 'logged';
        return $this->render('index', ['dane' => $dane]);
    }

    public function actionTestmail()
    {
        $email = UserService::getEmail(1);
        $s = Yii::$app->mailer->compose()
                ->setFrom('noreply@yii2.local')
                ->setTo($email)
                ->setSubject('InTouch')
                ->setTextBody('Hello')
                ->setHtmlBody('<b>Html Hello</b>')
                ->send();
        die(var_dump($s));
        //
    }

    public function actionProfile()
    {
        $id = Yii::$app->user->getId();
        if (Yii::$app->request->isPost)
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
                        break;

                    case 'newpost':
                        PostsService::createPost($id, Yii::$app->request->post('inputText'));
                        break;

                    case 'newcomment':
                        PostsService::createComment(Yii::$app->request->post('post_id'), Yii::$app->request->post('inputText'));
                        break;
                }
            }
        }
        $education = UserService::getUserEducation($id);
        $about = UserService::getUserAbout($id);
        $city = UserService::getUserCity($id);
        $birth = UserService::getBirthDate($id);
        $name = UserService::getName($id);
        $surname = UserService::getSurname($id);
        $email = UserService::getEmail($id);
        $followers = count(RelationService::getUsersWhoFollowMe($id));
        $following = count(RelationService::getUsersWhoIFollow($id));
        $friends = count(RelationService::getFriendsList($id));
        $posts = PostsService::getPosts($id);
        $photo = PhotoService::getProfilePhoto($id, true, true);
        //////////////////////////////////////////////////////////////////////////
        $this->getUserData();
        $this->layout = 'logged';
        return $this->render('profile', [
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'education' => $education,
                    'about' => $about,
                    'city' => $city,
                    'birth' => $birth,
                    'followers' => $followers,
                    'following' => $following,
                    'friends' => $friends,
                    'posts' => $posts,
                    'photo' => $photo,
                    'id' => $id,
        ]);
    }

    public function actionAboutedit()
    {
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
        return $this->render('aboutEdit', [
                    'education' => $education,
                    'about' => $about,
                    'city' => $city,
                    'birth' => $birth
        ]);
    }

    public function getUserData()
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
        ////////////////////////////////////////////////////// request service

        $notification = RequestService::getMyRequests($id);
        $tablelength = count($notification);
        $this->view->params['notification_data'] = $notification;
        $this->view->params['notification_count'] = $tablelength;
    }

    public function actionSearch($q)
    {
        if (AccessService::check(Permission::UseSearch))
        {
            $id = Yii::$app->user->getId();
            $this->getUserData($id);
            $this->layout = 'logged';
            $users = \app\components\SearchService::findUsers($q);
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
        $id = Yii::$app->user->getId();
        $this->getUserData($id);
        $this->layout = 'logged';
        return $this->render('accessDenied');
    }

    public function actionNotifications()
    {
        $id = Yii::$app->user->getId();


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

        $this->getUserData($id);
        $this->layout = 'logged';
        return $this->render('allRequests');
    }
    
    public function actionMyfriends()
    {
        $id = Yii::$app->user->getId();
        ///////
        $friends = RelationService::getFriendsList($id, true);
        ///////
        $this->getUserData($id);
        $this->layout = 'logged';
        return $this->render('myFriends',['friends' => $friends]);
    }

}
