<?php
return [
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'actions' => [
            'user' => [
                'index' => true,
                'create' => true,
                'update' => true,
                'view' => true,
                'delete' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
        ]
    ],
    'user' => [
        'type' => 1,
        'description' => 'Пользователь',
        'actions' => [
            'user' => [
                'index' => true,
                'create' => true,
                'update' => true,
                'view' => true,
                'delete' => true,
                'get-search-attributes' => true,
                'get-sort-attributes' => true,
            ],
        ]
    ],
];