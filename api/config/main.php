<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'homeUrl' => '/',
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'ru',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => \common\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                //v1
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/test'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST test' => 'test',
                        'OPTIONS test' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/auth'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST signup' => 'signup',
                        'OPTIONS signup' => 'options',
                        'POST confirm' => 'confirm',
                        'OPTIONS confirm' => 'options',
                        'POST login' => 'login',
                        'OPTIONS login' => 'options',
                        'GET curator' => 'curator',
                        'OPTIONS curator' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/recovery'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST request' => 'request',
                        'OPTIONS request' => 'options',
                        'POST set' => 'set',
                        'OPTIONS set' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/profile'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                        'POST update' => 'update',
                        'OPTIONS update' => 'options',
                        'POST upload-avatar' => 'upload-avatar',
                        'OPTIONS upload-avatar' => 'options',
                        'GET curator' => 'curator',
                        'OPTIONS curator' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/wallet'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                        'GET history' => 'history',
                        'OPTIONS history' => 'options',
                        'POST send' => 'send',
                        'OPTIONS send' => 'options',
                        'POST replenish' => 'replenish',
                        'OPTIONS replenish' => 'options',
                        'POST payout' => 'payout',
                        'OPTIONS payout' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/invest'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                        'GET get-search-attributes' => 'get-search-attributes',
                        'OPTIONS get-search-attributes' => 'options',
                        'GET get-sort-attributes' => 'get-sort-attributes',
                        'OPTIONS get-sort-attributes' => 'options',
                        'POST create' => 'create',
                        'OPTIONS create' => 'options',
                        'GET close-request' => 'close-request',
                        'OPTIONS close-request' => 'options',
                        'POST close' => 'close',
                        'OPTIONS close' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/marketing'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                        'GET structure' => 'structure',
                        'OPTIONS structure' => 'options',
                        'GET children' => 'children',
                        'OPTIONS children' => 'options',
                        'GET statistic' => 'statistic',
                        'OPTIONS statistic' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/commissions'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/ga'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                        'POST setting' => 'setting',
                        'OPTIONS setting' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
