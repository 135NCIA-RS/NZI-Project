<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ActionController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLang($lang = 'us')
    {
        //Yii::$app->language = Yii::$app->request->post('lang');
        $cookie = new Cookie([
            'name' => 'lang',
            'value' => $lang,
        ]);
        Yii::$app->getResponse()->getCookies()->add($cookie);
        return $this->redirect(Yii::$app->request->referrer);
    }

}
