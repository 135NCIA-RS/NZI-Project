<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;

/**
 * User controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actionUser() {
        $user = User::findOne(1);
        $user->username='aa';
        $user->save();
    }
    /**
     * @inheritdoc
     */
    
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
   
    /**
     * Displays contact page.
     *
     * @return mixed
     */
    
    /**
     * Displays about page.
     *
     * @return mixed
     */
    

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
   
}
