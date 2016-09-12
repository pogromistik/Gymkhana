<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'aliases' => [
        '@pictures' => 'C:\xampp\htdocs\gymkhana74\pictures',
        '@picturesView' => 'C:\xampp\htdocs\gymkhana74\pictures'
    ]
];
