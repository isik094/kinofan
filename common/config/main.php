<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'dump' => [
            'class' => \common\components\Dump::class,
            'lifeTime' => 86400 * 30 //один месяц
        ],
        'bc' => [
            'class' => \common\components\Bc::class,
            'address' => 'your address',
            'keyName' => 'your key name',
        ],
    ],
];
