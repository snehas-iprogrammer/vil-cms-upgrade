<?php
return [
    'name' => 'Admin',
    'auth' => [
        'admin_model' => \Modules\Admin\Models\User::class,
        'table' => 'admins',
        'password' => ['email' => 'admin::emails.auth.password'],
    ],
    'filemanager' => [
        'url' => 'admin/filemanager/show',
        'url-files' => '/public/admintheme/filemanager/userfiles/',
    ],
    'settings' => [
    ],
    'database' => [
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => env('ADMIN_DB_HOST', 'localhost'),
                'database' => env('ADMIN_DB_DATABASE', 'iplaravel'),
                'username' => env('ADMIN_DB_USERNAME', 'web'),
                'password' => env('ADMIN_DB_PASSWORD', 'websa'),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
            ]
        ]
    ]
];
