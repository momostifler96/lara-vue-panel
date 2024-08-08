<?php
// File: your-plugin-path/config/auth.php
return [
    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'student' => [
            'driver' => 'session',
            'provider' => 'students',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class,
        ],
    ],
];
