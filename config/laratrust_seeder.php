<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'manager' => [
            'settings' => 'r,u',
            'admin' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'role' => 'c,r,u,d',
            'pharmacies' => 'c,r,u,d',
            'timetable' => 'c,r,u,d',
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
