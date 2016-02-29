<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=projectdb',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
//        'gii' => [
//            'class' => 'yii\gii\Module',
//            'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
//        ],
    ],
];
