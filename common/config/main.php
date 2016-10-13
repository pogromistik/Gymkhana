<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'aliases' => [
        '@files' => 'C:\xampp\htdocs\gymkhana74\files',
        '@filesView' => 'http://files.gymkhana74.ru/'
    ]
];
