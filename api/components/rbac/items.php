<?php
return [
    \common\models\User::ROLE_ADMIN => [
        'type' => 1,
        'description' => 'Администратор',
        'actions' => [
            'user' => [
                'logout' => true,
                'index' => true,
                'create' => true,
                'update' => true,
                'view' => true,
                'delete' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'auth' => [
                'login' => true,
                'register' => true,
                'refresh' => true,
            ],
            'cinema' => [
                'index' => true,
                'view' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'similar' => [
                'index' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'product' => [
                'index' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'comment' => [
                'index' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'review' => [
                'create' => true,
            ],
            'person-cinema' => [
                'index' => true,
                'view' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
        ]
    ],
    \common\models\User::ROLE_USER => [
        'type' => 1,
        'description' => 'Пользователь',
        'actions' => [
            'user' => [
                'logout' => true,
                'index' => true,
                'create' => true,
                'update' => true,
                'view' => true,
                'delete' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'auth' => [
                'login' => true,
                'register' => true,
                'refresh' => true,
            ],
            'cinema' => [
                'index' => true,
                'view' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'similar' => [
                'index' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'product' => [
                'index' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'comment' => [
                'index' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
            'review' => [
                'create' => true,
            ],
            'person-cinema' => [
                'index' => true,
                'view' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
        ]
    ],
];