<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=gymkhana',
            'username' => 'gymkhana',
            'password' => 'X2y2B3a1',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
    'aliases' => [
        '@files' => '/var/www/www-root/data/www/gymkhana74/files',
        '@filesView' => 'http://gallery.gymkhana74.ru/'
    ]
];
