<?php

$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
	'id' => 'app-backend',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap' => ['log',
	                'common\components\IntouchBootstrap',
	],
	'language' => 'us',
	'sourceLanguage' => 'us',
	'modules' => [],
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
					//'basePath'              => '@app/messages',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
					'on missingTranslation' => ['common\components\TranslationEventHandler',
					                            'handleMissingTranslation'],
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl' => 'true',
			'showScriptName' => 'false',
			//'enableStrictParsing' => 'true',
			'rules' => [
				'/authenticate' => 'site/login',
				'logout' => 'site/logout',
			    'rep' => 'site/report',
			    'denied' => 'site/denied',
			    'reportedComment' => 'site/repcomment',

			],
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			'defaultRoles' => ['guest'],
		],
	],
	'as beforeRequest' => [
		'class' => 'common\components\LanguageHandler',
	],
	'params' => $params,
];
