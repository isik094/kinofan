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
                    'controller' => ['v1/file'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS ' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/auth'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'OPTIONS ' => 'options',
                        'POST register' => 'register',
                        'OPTIONS register' => 'options',
                        'POST refresh' => 'refresh',
                        'OPTIONS refresh' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/user'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'DELETE logout' => 'logout',
                        'OPTIONS logout' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/cinema'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS index' => 'options',
                        'GET /<id:\w+>' => 'view',
                        'OPTIONS view' => 'options',
                        'GET get-search-attributes' => 'get-search-attributes',
                        'OPTIONS get-search-attributes' => 'options',
                        'GET get-sort-attributes' => 'get-sort-attributes',
                        'OPTIONS get-sort-attributes' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/similar'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS index' => 'options',
                        'GET get-search-attributes' => 'get-search-attributes',
                        'OPTIONS get-search-attributes' => 'options',
                        'GET get-sort-attributes' => 'get-sort-attributes',
                        'OPTIONS get-sort-attributes' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/product'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS index' => 'options',
                        'GET get-search-attributes' => 'get-search-attributes',
                        'OPTIONS get-search-attributes' => 'options',
                        'GET get-sort-attributes' => 'get-sort-attributes',
                        'OPTIONS get-sort-attributes' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/comment'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS index' => 'options',
                        'GET get-search-attributes' => 'get-search-attributes',
                        'OPTIONS get-search-attributes' => 'options',
                        'GET get-sort-attributes' => 'get-sort-attributes',
                        'OPTIONS get-sort-attributes' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/review'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST ' => 'create',
                        'OPTIONS create' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/person-cinema'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS index' => 'options',
                        'GET /<id:\w+>' => 'view',
                        'OPTIONS view' => 'options',
                        'GET best-cinema/<id:\w+>' => 'best-cinema',
                        'OPTIONS best-cinema' => 'options',
                        'GET get-search-attributes' => 'get-search-attributes',
                        'OPTIONS get-search-attributes' => 'options',
                        'GET get-sort-attributes' => 'get-sort-attributes',
                        'OPTIONS get-sort-attributes' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/profile'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'PUT /<id:\w+>' => 'update',
                        'OPTIONS update' => 'update',
                        'POST cinema-watched' => 'cinema-watched',
                        'OPTIONS cinema-watched' => 'options',
                        'PUT change-password' => 'change-password',
                        'OPTIONS change-password' => 'options',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/favorites'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index',
                        'OPTIONS index' => 'options',
                        'POST ' => 'add',
                        'OPTIONS add' => 'options',
                        'DELETE ' => 'delete',
                        'OPTIONS delete' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
