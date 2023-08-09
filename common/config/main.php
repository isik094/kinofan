<?php
return [
    'name' => 'Kinofan',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru-RU',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'dump' => [
            'class' => \common\components\Dump::class,
            'lifeTime' => 86400 * 30 //один месяц
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => 'SECRET-KEY',  //typically a long random string
            'jwtValidationData' => \common\components\JwtValidationData::class,
        ],
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
        ],
    ],
];
