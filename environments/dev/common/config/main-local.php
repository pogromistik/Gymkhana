<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=gymkhana',
            'username' => 'postgres',
            'password' => 'oogeec4cai',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
    'aliases' => [
        '@files' => 'C:\xampp\htdocs\gymkhana74\files',
        '@filesView' => 'http://files.gymkhana74.ru/'
    ]
];
