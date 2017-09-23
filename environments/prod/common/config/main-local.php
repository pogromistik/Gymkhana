<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => '',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'aliases' => [
        '@files' => '/var/www/www-root/data/www/gymkhana74/files',
        '@filesView' => 'http://gallery.gymkhana74.ru/'
    ]
];
