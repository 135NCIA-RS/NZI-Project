<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'  => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport'        => [
                'class'    => 'Swift_SmtpTransport',
                'host'     => 'mailtrap.io',
                'username' => '56404dad1167860a2',
                'password' => '5e683988683e5c',
                'port'     => '25',
            //'encryption' => 'tls',
            ],
        ],
    ],
];
