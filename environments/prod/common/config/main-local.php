<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=intouch',
            'username' => 'intouch',
            'password' => 'badpassword',
            'charset' => 'utf8',
        ],
    ],
];
