<?php
return [
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'actions' => [
            'auth' => [
                'login' => true,
                'register' => true,
                'refresh' => true,
            ],
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
            'test' => [
                'test' => true,
            ],
        ]
    ],
    'user' => [
        'type' => 1,
        'description' => 'Пользователь',
        'actions' => [
            'auth' => [
                'login' => true,
                'register' => true,
                'refresh' => true,
            ],
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
            'test' => [
                'test' => true,
            ],
        ]
    ],
];