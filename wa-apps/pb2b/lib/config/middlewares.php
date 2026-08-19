<?php

return [
    'global' => [],

    'routes' => [
        'auth/login/' => [pb2bGuestMiddleware::class],
        'auth/registration/' => [pb2bGuestMiddleware::class],
        'auth/recovery/' => [pb2bGuestMiddleware::class],
        'auth/code/' => [pb2bGuestMiddleware::class],
        'auth/password/' => [pb2bAuthMiddleware::class],

        'cabinet/*' => [
            pb2bAuthMiddleware::class,
            pb2bCompanyVerifiedMiddleware::class,
            pb2bCompanyRoleMiddleware::class,
        ],

        'api/*' => [
            pb2bAuthMiddleware::class,
            pb2bCompanyVerifiedMiddleware::class,
            pb2bCompanyRoleMiddleware::class,
        ],
    ],
];
