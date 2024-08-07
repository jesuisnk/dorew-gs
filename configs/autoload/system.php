<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

return [
    'app' => [
        'name' => env('APP_NAME', 'Dorew'),
        'description' => 'Thích Ngao Du',
        'keyword' => 'dorew, giải trí, chat chit, chém gió, kết bạn, wapmaster, webmaster, johncms, wapego, wap4'
    ],
    'PostList' => [
        'order_by' => ['id', 'time', 'update_time', 'view'],
        'sort' => ['asc', 'desc']
    ],
    'UserList' => [
        'order_by' => ['post','comment'],
        'sort' => ['asc', 'desc']
    ]
];
