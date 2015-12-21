<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    'app\components\DynamicProfileLinksBootstrap',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'us',
    'sourceLanguage' => 'us',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'sourceLanguage' => 'us',
                    //'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => 'true',
            'showScriptName' => 'false',
            'rules' => [
                'logout' => 'site/logout',
                'register' => 'site/signup',
                'login' => 'site/login',
                'start' => 'intouch/index',
                'profile' => 'intouch/profile',
                'profile/aboutedit' => 'intouch/aboutedit',
                'search' => 'intouch/search',
            ],
        ],
    ],
    'as beforeRequest' => [
        'class' => 'app\components\LanguageHandler',
    ],
    'on beforeAction' => function ($event)
    {
        //$route = $event->sender->requestedRoute; //if DynamicProfileLinkBootstrap is disabled
        $route = substr(Yii::$app->request->url, 1); // because of /
        $users = \common\models\User::find()
                ->select(['username', 'id'])
                ->where(['status' => '10'])
                ->all();
    
        foreach ($users as $user)
        {
            if (strtolower($route) == strtolower($user['username']))
            {
                // Yii::$app->response->redirect(["/" . $route, 'UID' => 2]); //redirect with GET
                Yii::$app->session->set("viewID", $user['id']);
                break;
            }
        }
    },
    'params' => $params,
];
