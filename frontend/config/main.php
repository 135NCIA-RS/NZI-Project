<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
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
    ],
    'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                        'app.weather' => 'AppWeather.php',
                    ],
                    'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => 'true',
            'showScriptName' => 'false',
            'rules' => [
                //'catchAll' => ['site/login'],
                'login' => 'site/login',
                'logout' => 'site/logout',
                'register' => 'site/signup',
                'sql' => 'site/mysql',
                'weather' => 'app/weather',
                'pogoda' => 'app/weather',
            ],
        ],
    ],
    'as beforeRequest' => [
          'class' => 'app\components\LanguageHandler',  
        ],
    'params' => $params,
];
